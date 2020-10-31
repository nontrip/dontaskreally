<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/6/19
 * Time: 21:04
 */

if ( ! class_exists( 'PPW_Password_Services' ) ) {
	class PPW_Password_Services implements PPW_Service_Interfaces {

		/**
		 * @var PPW_Repository_Passwords
		 */
		private $passwords_repository;

		/**
		 * PPW_Password_Services constructor.
		 *
		 * @param PPW_Repository_Passwords $repo The password repository class help to interact with DB.
		 */
		public function __construct( $repo = null ) {
			if ( is_null( $repo ) ) {
				$this->passwords_repository = new PPW_Repository_Passwords();
			} else {
				$this->passwords_repository = $repo;
			}
		}

		/**
		 * Check content is protected
		 *
		 * @param $post_id
		 *
		 * @return array|bool
		 */
		public function is_protected_content( $post_id ) {
			$result = $this->get_passwords( $post_id );
			if ( ! $result['has_global_passwords'] && ! $result['has_role_passwords'] ) {
				return false;
			}

			return $result;
		}

		/**
		 * Check password is valid
		 *
		 * @param $password
		 * @param $post_id
		 * @param $current_roles
		 *
		 * @return bool
		 */
		public function is_valid_password( $password, $post_id, $current_roles ) {
			if ( $this->check_password_type_is_global( $post_id, $password ) ) {
				$this->set_cookie_bypass_cache( $password . $post_id, PPW_Constants::COOKIE_NAME . $post_id );

				return true;
			}

			if ( ! is_user_logged_in() ) {
				return false;
			}

			$role_meta       = get_post_meta( $post_id, PPW_Constants::POST_PROTECTION_ROLES, true );
			$protected_roles = ppw_free_fix_serialize_data( $role_meta );

			if ( empty( $protected_roles ) ) {
				return false;
			}

			return $this->check_password_type_is_roles( $current_roles, $protected_roles, $password, $post_id );
		}

		/**
		 * Set password to cookie
		 *
		 * @param $password
		 * @param $cookie_name
		 */
		public function set_password_to_cookie( $password, $cookie_name ) {
			$password_hashed         = wp_hash_password( $password );
			$expire                  = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + 7 * DAY_IN_SECONDS );
			$password_cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
			if ( ! empty( $password_cookie_expired ) ) {
				$time = explode( ' ', $password_cookie_expired )[0];
				$unit = ppw_core_get_unit_time( $password_cookie_expired );
				if ( 0 !== $unit ) {
					$expire = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + (int) $time * $unit );
				}
			}

			$referer = wp_get_referer();
			if ( $referer ) {
				$secure = ( 'https' === parse_url( $referer, PHP_URL_SCHEME ) );
			} else {
				$secure = false;
			}

			$expire = apply_filters( 'ppw_cookie_expire', $expire );
			return setcookie( $cookie_name . COOKIEHASH, $password_hashed, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );
		}

		/**
		 * Set password to cookie with case cookie name the same WP to bypass cache
		 *
		 * @param string $cookie_value The value of the cookie.
		 * @param string $cookie_name  The name of the cookie.
		 */
		public function set_cookie_bypass_cache( $cookie_value, $cookie_name ) {
			// Bypass Caching plugin with WordPress cookie.
			$this->set_password_to_cookie( $cookie_value, PPW_Constants::WP_POST_PASS );
			$this->set_password_to_cookie( $cookie_value, $cookie_name );
		}

		/**
		 * Check whether the current cookie is valid.
		 *
		 * @param $post_id
		 * @param $passwords
		 * @param $cookie_name
		 *
		 * @return bool
		 */
		public function is_valid_cookie( $post_id, $passwords, $cookie_name ) {
			if ( ! isset( $_COOKIE[ $cookie_name . $post_id . COOKIEHASH ] ) ) {
				return false;
			}

			$cookie = sanitize_text_field( $_COOKIE[ $cookie_name . $post_id . COOKIEHASH ] );
			$hash   = wp_unslash( $cookie );
			if ( 0 !== strpos( $hash, '$P$B' ) ) {
				return false;
			}

			$roles = ppw_core_get_current_role();
			foreach ( $passwords as $password ) {
				if ( wp_check_password( $password . $post_id, $hash ) ) {
					return true;
				}

				foreach ( $roles as $role ) {
					if ( wp_check_password( $password . $role . $post_id, $hash ) ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Redirect after enter password
		 *
		 * @param bool $is_valid Is entered password valid.
		 */
		public function handle_redirect_after_enter_password( $is_valid ) {
			// Refactor since 1.4.2.
			// 1. Clean code
			// 2. Easier to write UT.
			$redirect_url = $this->get_redirect_url( $is_valid );
			wp_safe_redirect( $redirect_url );
			exit();
		}

		/**
		 * Get redirect URL after user entered password.
		 *
		 * @param bool $is_valid Is password valid.
		 *
		 * @return string
		 */
		public function get_redirect_url( $is_valid ) {
			$referrer_url      = $this->get_referer_url();
			$params_in_referer = ppw_core_get_param_in_url( $referrer_url );

			if ( $is_valid ) {
				$referrer_url = apply_filters(
					'ppwp_ppf_referrer_url',
					$referrer_url,
					array(
						'is_valid'   => $is_valid,
						'parameters' => $params_in_referer,
					)
				);

				$url_redirect = preg_replace( '/[&?]' . PPW_Constants::WRONG_PASSWORD_PARAM . '=true/', '', $referrer_url );
				$params       = apply_filters(
					PPW_Constants::HOOK_PARAM_PASSWORD_SUCCESS,
					array(
						'name'  => PPW_Constants::PASSWORD_PARAM_NAME,
						'value' => PPW_Constants::PASSWORD_PARAM_VALUE,
					)
				);

				if ( array_key_exists( $params['name'], $params_in_referer ) && '1' === $params_in_referer[ $params['name'] ] ) {
					return $url_redirect;
				}

				$params_in_redirect = ppw_core_get_param_in_url( $url_redirect );
				$new_param          = empty( $params_in_redirect ) ? '?' . $params['name'] . '=' . $params['value'] : '&' . $params['name'] . '=' . $params['value'];

				return $url_redirect . $new_param;
			}

			if ( array_key_exists( PPW_Constants::WRONG_PASSWORD_PARAM, $params_in_referer ) && 'true' === $params_in_referer[ PPW_Constants::WRONG_PASSWORD_PARAM ] ) {
				return apply_filters(
					'ppwp_ppf_redirect_url',
					$referrer_url,
					array(
						'is_valid'   => $is_valid,
						'parameters' => $params_in_referer,
					)
				);
			}

			$new_param = empty( $params_in_referer ) ? '?' . PPW_Constants::WRONG_PASSWORD_PARAM . '=true' : '&' . PPW_Constants::WRONG_PASSWORD_PARAM . '=true';

			return apply_filters(
				'ppwp_ppf_redirect_url',
				$referrer_url . $new_param,
				array(
					'is_valid'   => $is_valid,
					'parameters' => $params_in_referer,
				)
			);
		}

		/**
		 * Get referer URL from HTTP Referrer or callback URL in post form action URL.
		 *
		 * @return mixed False if cannot find the referer URL.
		 */
		public function get_referer_url() {
			$referrer_url = wp_get_referer();
			if ( false === $referrer_url ) {
				// We need to get the callback URL in the password form action URL.
				// in case Referrer-Policy is set no-referrer.
				$cb_param = PPW_Constants::CALL_BACK_URL_PARAM;
				if ( isset( $_GET[ $cb_param ] ) ) { //phpcs:ignore
					$referrer_url = rawurldecode( $_GET[ $cb_param ] ); //phpcs:ignore
				}
			}

			// If doesn't have callback URL and no-referer then return to home page.
			if ( false === $referrer_url ) {
				global $wp;
				$referrer_url = home_url( $wp->request );
			}

			return $referrer_url;
		}

		/**
		 * Handle and check condition before create new password
		 *
		 * @param int|string $id                   The post ID.
		 * @param string     $role_selected        The role user select on client.
		 * @param array      $new_global_passwords List global passwords user enter on client.
		 * @param string     $new_role_password    Role password user enter on client.
		 *
		 * @return array|mixed
		 */
		public function create_new_password( $id, $role_selected, $new_global_passwords, $new_role_password ) {
			$post_meta                = get_post_meta( $id, PPW_Constants::POST_PROTECTION_ROLES, true );
			$current_roles_password   = ppw_free_fix_serialize_data( $post_meta );
			$current_global_passwords = get_post_meta( $id, PPW_Constants::GLOBAL_PASSWORDS, true );
			if ( 'global' === $role_selected ) {
				return $this->create_password_type_global( $id, $new_global_passwords, $current_global_passwords, $current_roles_password, $role_selected );
			}

			return $this->create_password_type_role( $id, $role_selected, $new_role_password, $current_global_passwords, $current_roles_password );
		}

		/**
		 * Check condition before create new password type global
		 *
		 * @param int|string $id                       The post ID.
		 * @param array      $new_global_passwords     List global passwords user enter on client.
		 * @param array      $current_global_passwords List all current global passwords.
		 * @param array      $current_roles_password   List all current role passwords.
		 * @param string     $role_selected            The role user select on client.
		 *
		 * @return mixed
		 */
		public function create_password_type_global( $id, $new_global_passwords, $current_global_passwords, $current_roles_password, $role_selected ) {
			// Validate global password(check bad request).
			if ( $this->global_passwords_is_bad_request( $new_global_passwords ) ) {
				wp_send_json(
					array(
						'is_error' => true,
						'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
					),
					400
				);
				wp_die();
			}

			// Validate global password(empty and duplicate).
			ppw_free_validate_password_type_global( $new_global_passwords, $current_global_passwords, $current_roles_password );
			update_post_meta( $id, PPW_Constants::GLOBAL_PASSWORDS, $new_global_passwords );

			// Clear cache for Cache plugin.
			ppw_core_clear_cache_by_id( $id );

			/*
			// Handle cache for page/post have password type is global with Super Cache plugin.
			$free_cache = new PPW_Cache_Services();
			$free_cache->handle_cache_for_password_type_global_with_super_cache( $new_global_passwords, $id, $current_roles_password );
			*/
			$current_roles_password[ $role_selected ] = implode( "\n", $new_global_passwords );

			return $current_roles_password;
		}

		/**
		 * Check bad request with data type is global passwords
		 *
		 * @param array $passwords Global passwords.
		 *
		 * @return bool
		 */
		private function global_passwords_is_bad_request( $passwords ) {
			foreach ( $passwords as $password ) {
				if ( strpos( $password, ' ' ) !== false ) {
					return true;
				}
			}

			// Check element unique in array.
			return count( $passwords ) !== count( array_unique( $passwords ) );
		}

		/**
		 * Check condition before create new password type role
		 *
		 * @param int|string $id                       The post ID.
		 * @param string     $role_selected            The role user select on client.
		 * @param string     $new_role_password        Role password user enter on client.
		 * @param array      $current_global_passwords List all current global passwords.
		 * @param array      $current_roles_password   List all current role passwords.
		 *
		 * @return mixed
		 */
		public function create_password_type_role( $id, $role_selected, $new_role_password, $current_global_passwords, $current_roles_password ) {
			// Validate role password(check bad request).
			if ( $this->role_password_is_bad_request( $new_role_password ) ) {
				wp_send_json(
					array(
						'is_error' => true,
						'message'  => PPW_Constants::BAD_REQUEST_MESSAGE,
					),
					400
				);
				wp_die();
			}

			// Validate role password(empty and duplicate).
			ppw_free_validate_password_type_role( $role_selected, $new_role_password, $current_global_passwords, $current_roles_password );
			$current_roles_password[ $role_selected ] = $new_role_password;
			delete_post_meta( $id, PPW_Constants::POST_PROTECTION_ROLES );
			add_post_meta( $id, PPW_Constants::POST_PROTECTION_ROLES, $current_roles_password );

			// Clear cache for Cache plugin.
			ppw_core_clear_cache_by_id( $id );

			/*
			// Handle cache for page/post have password type is role with Super Cache plugin.
			$free_cache = new PPW_Cache_Services();
			$free_cache->handle_cache_for_password_type_role_with_super_cache( $new_role_password, $id, $current_roles_password, $current_global_passwords );
			*/
			if ( ! empty( $current_global_passwords ) ) {
				$current_roles_password['global'] = implode( "\n", $current_global_passwords );
			}

			return $current_roles_password;
		}

		/**
		 * Check bad request with data type is role password
		 *
		 * @param string $password Role password.
		 *
		 * @return bool
		 */
		private function role_password_is_bad_request( $password ) {
			return strpos( $password, ' ' ) !== false;
		}

		/**
		 * Password is empty with not 0.
		 *
		 * @param string $pwd Password.
		 *
		 * @return bool
		 */
		public function has_no_empty_password( $pwd ) {
			return ! empty( $pwd ) || '0' === $pwd;
		}

		/**
		 * Get all passwords
		 *
		 * @param int|string $post_id The post ID.
		 *
		 * @return array
		 */
		public function get_passwords( $post_id ) {
			// 1. Get all passwords.
			$global_passwords     = get_post_meta( $post_id, PPW_Constants::GLOBAL_PASSWORDS, true );
			$global_passwords     = ! empty( $global_passwords ) ? $global_passwords : array();
			$has_global_passwords = ! empty( $global_passwords ) && is_array( $global_passwords );
			$raw_data             = get_post_meta( $post_id, PPW_Constants::POST_PROTECTION_ROLES, true );
			$protected_roles      = ppw_free_fix_serialize_data( $raw_data );

			$filtered_protected_roles  = array_filter(
				$protected_roles,
				function ( $pass ) {
					return $this->has_no_empty_password( $pass );
				}
			);
			$has_role_passwords        = ! empty( $filtered_protected_roles );
			$has_current_role_password = false;
			if ( $has_role_passwords ) {
				$roles = ppw_core_get_current_role();
				foreach ( $roles as $role ) {
					if ( array_key_exists( $role, $filtered_protected_roles ) ) {
						$has_current_role_password = true;
						array_push( $global_passwords, $protected_roles[ $role ] );
					}
				}
			}

			return array(
				'passwords'                 => $global_passwords,
				'has_role_passwords'        => $has_role_passwords,
				'has_current_role_password' => $has_current_role_password,
				'has_global_passwords'      => $has_global_passwords,
			);
		}

		/**
		 * Check password type is global
		 *
		 * @param $post_id
		 * @param $password
		 *
		 * @return bool
		 */
		public function check_password_type_is_global( $post_id, $password ) {
			$global_passwords = get_post_meta( $post_id, PPW_Constants::GLOBAL_PASSWORDS, true );
			if ( empty( $global_passwords ) || ! is_array( $global_passwords ) ) {
				return false;
			}

			foreach ( $global_passwords as $pass ) {
				if ( $password === $pass ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check password type is roles
		 *
		 * @param $current_roles
		 * @param $protectedRoles
		 * @param $password
		 * @param $post_id
		 *
		 * @return bool
		 */
		public function check_password_type_is_roles( $current_roles, $protectedRoles, $password, $post_id ) {
			foreach ( $current_roles as $role ) {
				if ( ! array_key_exists( $role, $protectedRoles ) || ! $this->has_no_empty_password( $protectedRoles[ $role ] ) || $protectedRoles[ $role ] !== $password ) {
					continue;
				}

				$this->set_cookie_bypass_cache( $password . $role . $post_id, PPW_Constants::COOKIE_NAME . $post_id );

				return true;
			}

			return false;
		}

		/**
		 * Migrate default password and update post password of Wordpress for free version
		 * TODO: need to revamp the logic.
		 */
		function migrate_default_password() {
			$posts = ppw_core_get_posts_password_protected_by_wp();
			error_log( '[Migrate Default PWD]Things to migrate: ' . wp_json_encode( $posts ) );
			error_log( sprintf( '[Migrate Default PWD]Total: %d', count( $posts ) ) );
			foreach ( $posts as $post ) {
				$post_id = $post->ID;
				error_log( sprintf( '[Migrate Default PWD]Migrating password for post %d', $post_id ) );
				$global_password = get_post_meta( $post_id, PPW_Constants::GLOBAL_PASSWORDS, true );
				$global_password = ! empty( $global_password ) ? $global_password : array();
				$raw_data        = get_post_meta( $post->ID, PPW_Constants::POST_PROTECTION_ROLES, true );

				// 1. Update password for role
				$protected_roles = ppw_free_fix_serialize_data( $raw_data );
				foreach ( $protected_roles as $key => $value ) {
					if ( str_replace( " ", "", $post->post_password ) === $value ) {
						$protected_roles[ $key ] = '';
						update_post_meta( $post_id, PPW_Constants::POST_PROTECTION_ROLES, $protected_roles );
					}
				}

				// 2. Update password for global
				if ( ! in_array( str_replace( " ", "", $post->post_password ), $global_password ) ) {
					array_push( $global_password, str_replace( " ", "", $post->post_password ) );
				}

				update_post_meta( $post_id, PPW_Constants::GLOBAL_PASSWORDS, $global_password );
				update_post_meta( $post_id, 'ppwp_post_password_bk', $post->post_password );

				// 3. Update default password for Wordpress
				wp_update_post( array(
					'ID'            => $post_id,
					'post_password' => '',
				) );
			}
		}

		public function get_pw_meta( $post_id = false ) {
			global $wpdb;
			$table_name = $wpdb->prefix . "postmeta";
			$global_key = PPW_Constants::GLOBAL_PASSWORDS;
			$role_key   = PPW_Constants::POST_PROTECTION_ROLES;

			$query = "SELECT * FROM $table_name where ( meta_key IN ( '$global_key', '$role_key' ) )";

			if ( $post_id ) {
				$query = $wpdb->prepare( $query . ' AND post_id = %d', $post_id );
			}

			return $wpdb->get_results( $query );
		}


		public function get_data_to_migrate() {
			$ids    = $this->get_protected_post_ids();
			$result = [];
			foreach ( $ids as $post_id ) {
				$passwords = $this->get_pw_meta( $post_id );
				$result[]  = [
					'post_id'   => $post_id,
					'passwords' => $this->massage_pw_from_post_meta( $passwords ),
				];
			}
			$old = get_option( 'ppw_data_checksum', false );
			if ( false === $old ) {
				update_option( 'ppw_data_checksum', $result );

				return $result;
			}
			$diff = $this->check_sum_migrate_data( $result, $old );
			update_option( 'ppw_data_checksum', $result );

			return $diff;
		}

		public function check_sum_migrate_data( $current, $old ) {
			if ( count( $current ) > count( $old ) ) {
				$large = $current;
				$small = $old;
			} else {
				$large = $old;
				$small = $current;
			}

			$post_ids = array_column( $small, 'post_id' );
			$result   = [];
			foreach ( $large as $cur ) {
				$post_id     = $cur['post_id'];
				$found_index = array_search( $post_id, $post_ids );
				if ( false === $found_index ) {
					$result[] = $cur;
					continue;
				}

				$found = $small[ $found_index ];

				if ( ! isset ( $found['passwords'] ) ) {
					continue;
				}

				if ( $this->compare_passwords( $cur, $found ) ) {
					$result[] = $found;
				}
			}

			return $result;
		}

		/**
		 * Massage password from post meta
		 *
		 * @param array $meta post meta from DB.
		 *
		 * @return array
		 */
		public function massage_pw_from_post_meta( $meta ) {
			$result = array(
				'global' => array(),
				'role'   => array(),
			);
			foreach ( $meta as $val ) {
				if ( PPW_Constants::GLOBAL_PASSWORDS === $val->meta_key ) {
					$meta_value       = ppw_free_fix_serialize_data( $val->meta_value );
					$result['global'] = array_merge( $result['global'], $meta_value );
				} elseif ( PPW_Constants::POST_PROTECTION_ROLES === $val->meta_key ) {
					$meta_value     = ppw_free_fix_serialize_data( @unserialize( $val->meta_value ) );
					$result['role'] = $this->massage_pw_for_roles_from_post_meta( $meta_value );
				}
			}

			return $result;
		}

		/**
		 * Massage by define a password - role map
		 *
		 * Input: [ "admin" => "1", "editor" => "2", author => "1"]
		 * Output: [ "1" => array('admin', 'author'), "2" => array('editor') ]
		 *
		 * @param $meta_value
		 *
		 * @return array
		 */
		public function massage_pw_for_roles_from_post_meta( $meta_value ) {
			$result = [];
			if ( ! is_array( $meta_value ) ) {
				return $result;
			}

			foreach ( $meta_value as $role => $pw ) {

				if ( "" === $pw ) {
					continue;
				}

				if ( ! array_key_exists( $pw, $result ) ) {
					$result[ $pw ] = [ $role ];
				}

				if ( ! in_array( $role, $result[ $pw ] ) ) {
					array_push( $result[ $pw ], $role );
				}
			}

			return $result;
		}


		public function get_protected_post_ids() {
			$role_key = PPW_Constants::POST_PROTECTION_ROLES;
			$results  = array_filter( $this->get_pw_meta(), function ( $value ) use ( $role_key ) {
				$meta_value          = ppw_free_fix_serialize_data( @unserialize( $value->meta_value ) );
				$is_valid_meta_value = is_array( $meta_value );
				if ( $is_valid_meta_value && $role_key === $value->meta_key ) {
					foreach ( $meta_value as $meta ) {
						return $meta !== '';
					}
				}

				return $is_valid_meta_value && count( $meta_value ) > 0;
			} );

			return array_unique(
				array_map( function ( $val ) {
					return $val->post_id;
				}, $results )
			);
		}

		/**
		 * Generate custom row action.
		 *
		 * @param array    $actions An array for row action.
		 * @param stdClass $post    The post object.
		 *
		 * @return array
		 */
		public function generate_custom_row_action( $actions, $post ) {
			$post_id           = $post->ID;
			$is_protected      = $this->is_protected_content( $post_id );
			$btn_label         = $is_protected ? __( 'Unprotect', 'password-protect-page' ) : __( 'Protect', 'password-protect-page' );
			$title             = $is_protected ? __( 'Unprotect this page', 'password-protect-page' ) : __( 'Protect this page', 'password-protect-page' );
			$protection_status = $is_protected ? PPW_Constants::PROTECTION_STATUS['unprotect'] : PPW_Constants::PROTECTION_STATUS['protect'];

			$actions['ppw_protect'] = '<a style="cursor: pointer" data-ppw-status="' . $protection_status . '" onclick="ppwpRowAction.handleOnClickRowAction(' . $post_id . ')" id="ppw-protect-post_' . $post_id . '" class="ppw-protect-action" title="' . $title . '">' . $btn_label . '</a>';

			return $actions;
		}

		/**
		 * Handle protect page/post.
		 *
		 * @param int $post_id The post ID.
		 */
		public function protect_page_post( $post_id ) {
			$password = array(
				uniqid( '', false )
			);

			$this->create_new_password( $post_id, 'global', $password, null );
		}

		/**
		 * Handle unprotect page/post.
		 *
		 * @param int $post_id The post ID.
		 */
		public function unprotect_page_post( $post_id ) {
			delete_post_meta( $post_id, PPW_Constants::POST_PROTECTION_ROLES );
			delete_post_meta( $post_id, PPW_Constants::GLOBAL_PASSWORDS );
		}

		/**
		 * Update post status request from row action
		 *
		 * @param array $request Request from row action.
		 */
		public function update_post_status( $request ) {
			if ( ! isset( $request['postId'] ) || ! isset( $request['status'] ) ) {
				send_json_data_error( __( 'Our server cannot understand the data request!', 'password-protect-page' ) );
			}

			$post_id       = $request['postId'];
			$client_status = (int) $request['status'];

			if ( ! in_array( $client_status, array_values( PPW_Constants::PROTECTION_STATUS ), true ) ) {
				send_json_data_error( __( 'Our server cannot understand the data request!', 'password-protect-page' ) );
			}

			$server_status  = $client_status;
			$message        = __( 'Oops! Something went wrong. Please reload the page and try again.', 'password-protect-page' );
			$status_request = 400;
			if ( PPW_Constants::PROTECTION_STATUS['protect'] === $client_status ) {
				if ( ! $this->is_protected_content( $post_id ) ) {
					$this->protect_page_post( $post_id );
					$server_status  = PPW_Constants::PROTECTION_STATUS['unprotect'];
					$message        = __( 'Great! You\'ve successfully protected this page.', 'password-protect-page' );
					$status_request = 200;
				}
			} else {
				if ( $this->is_protected_content( $post_id ) ) {
					$this->unprotect_page_post( $post_id );
					$server_status  = PPW_Constants::PROTECTION_STATUS['protect'];
					$message        = __( 'Great! You\'ve successfully unprotected this page.', 'password-protect-page' );
					$status_request = 200;
				}
			}

			wp_send_json(
				array(
					'is_error'      => 200 === $status_request ? false : true,
					'server_status' => $server_status,
					'message'       => $message,
				),
				$status_request
			);
			wp_die();
		}

		/**
		 * @param $pwds
		 *
		 * @return array
		 */
		private function massage_role_pwd( $pwds ) {
			return array_map( function ( $v ) {
				natsort( $v );

				return implode( ',', $v );
			}, $pwds );
		}

		/**
		 * @param $cur
		 * @param $found
		 *
		 * @return bool
		 */
		private function compare_passwords( $cur, $found ) {
			$global_diff = $this->advance_array_diff( $cur['passwords']['global'], $found['passwords']['global'] );

			if ( ! empty( $global_diff ) ) {
				return true;
			}

			$current_roles = $this->massage_role_pwd( $cur['passwords']['role'] );
			$new_roles     = $this->massage_role_pwd( $found['passwords']['role'] );

			$role_diff = $this->advance_array_diff( $current_roles, $new_roles );

			return ! empty ( $role_diff );
		}

		/**
		 * @param $first
		 * @param $second
		 *
		 * @return array
		 */
		private function advance_array_diff( $first, $second ) {
			return array_merge( array_diff(
				$first,
				$second
			), array_diff(
				$second,
				$first
			) );
		}

		/**
		 * Valid permission of post ID.
		 *
		 * @param bool $required Required Password.
		 * @param int  $post_id  Post ID.
		 *
		 * @return bool True|False. True: Password is required so it will render form.
		 */
		public function is_valid_permission( $required, $post_id ) {
			// 1. Check page/post is protected.
			$result = $this->is_protected_content( $post_id );
			if ( false === $result ) {
				return false;
			}

			// 2. Check master password is valid.
			$is_valid_master_password = $this->check_master_password_is_valid( $post_id );
			if ( apply_filters( 'ppw_is_valid_cookie', $is_valid_master_password, $post_id ) ) {
				return false;
			}

			// 3. Check password in cookie.
			$passwords = $result['passwords'];

			$is_valid = $this->is_valid_cookie( $post_id, $passwords, PPW_Constants::COOKIE_NAME );

			return false === apply_filters( 'ppw_is_valid_cookie', $is_valid, $post_id );
		}

		/**
		 * Check password is exist when user enter password in form.
		 *
		 * @param int    $post_id  Post ID.
		 * @param string $password Password which user enter.
		 */
		public function handle_after_enter_password_in_password_form( $post_id, $password ) {
			$is_valid = $this->is_valid_password_from_request( $post_id, $password );

			do_action( 'ppw_redirect_after_enter_password', $is_valid );
		}

		public function is_valid_password_from_request( $post_id, $password ) {
			// Get current role of current user.
			$current_roles   = ppw_core_get_current_role();
			$is_pro_activate = apply_filters( PPW_Constants::HOOK_IS_PRO_ACTIVATE, false );
			if ( $is_pro_activate ) {
				$is_valid = apply_filters( PPW_Constants::HOOK_CHECK_PASSWORD_IS_VALID, false, $password, $post_id, $current_roles );

				/**
				 * Check post is protected by pro version to handle master password.
				 */
				if ( $this->is_protected_content_by_pro( $post_id ) ) {
					$is_valid = $this->handle_master_passwords( $password, $is_valid, $current_roles, $post_id );
				}
			} else {
				$is_valid = $this->is_valid_free_password( $post_id, $password, $current_roles );
			}

			return apply_filters( 'ppw_is_valid_password', $is_valid, $post_id, $password, $current_roles );
		}

		/**
		 * Is valid free Password.
		 * 
		 * @param integer $post_id Post ID.
		 * @param string $password Password.
		 * @param string $current_roles Current user roles.
		 * 
		 * @return bool True is valid password, false is no.
		 */
		public function is_valid_free_password( $post_id, $password, $current_roles ) {
			$is_valid = $this->is_valid_password( $password, $post_id, $current_roles );
			$is_valid = $this->handle_master_passwords( $password, $is_valid, $current_roles, $post_id );

			return $is_valid;
		}

		/**
		 * Check is protected content by pro version.
		 *
		 * @param integer $post_id Post ID.
		 *
		 * @return bool Is content protected by pro.
		 */
		public function is_protected_content_by_pro( $post_id ) {
			if ( ! function_exists( 'ppw_pro_get_post_id_follow_protect_child_page' ) || ! method_exists( 'PPW_Pro_Password_Services', 'is_protected_content' ) ) {
				return false;
			}
			$password_pro_service = new PPW_Pro_Password_Services();

			/**
			 * Get parent post id if post have parent-child page.
			 */
			$new_post_id = ppw_pro_get_post_id_follow_protect_child_page( $post_id );

			/**
			 * Check post or page is protected.
			 * TODO: Improve with global variable and refactor PPWP pro to check post is protected with Global variables.
			 */
			return apply_filters( PPW_Constants::HOOK_CHECK_CONTENT_IS_PROTECTED_BY_PRO, $password_pro_service->is_protected_content( $new_post_id ), $post_id );
		}


		/**
		 * Check master password is exist and apply for this post.
		 *
		 * @param string $password      Password.
		 * @param bool   $is_valid      Valid password before.
		 * @param array  $current_roles Current roles.
		 * @param int    $post_id       Post ID.
		 *
		 * @return bool Allow open content or not.
		 */
		public function handle_master_passwords( $password, $is_valid, $current_roles, $post_id ) {
			$password_info = $this->passwords_repository->get_master_password_info_by_password( $password );

			if ( is_null( $password_info ) ) {
				return $is_valid;
			}

			// Check post type is exist in password.
			if ( ! $this->check_post_type_for_master_password( $post_id, $password_info ) ) {
				return $is_valid;
			}

			$result = $this->check_valid_master_password( $password_info, $current_roles, $password );
			if ( $result['is_valid'] ) {
				/**
				 * Save cookie to client.
				 * If $result['role'] is not empty then password will be role password
				 * Else global password.
				 */
				$this->set_cookie_bypass_cache( $password . $result['role'] . $password_info->id, PPW_Constants::MASTER_COOKIE_NAME . $password_info->id );

				// Count when user enter right password.
				$this->passwords_repository->update_password(
					$password_info->id,
					array(
						'hits_count' => (int) $password_info->hits_count + 1,
					)
				);

				return true;
			}

			return $is_valid;
		}

		/**
		 * Check valid master password when user enter.
		 *
		 * @param array  $password_info Password information get from database.
		 * @param array  $current_roles Current user roles.
		 * @param string $password      Password.
		 *
		 * @return array
		 */
		public function check_valid_master_password( $password_info, $current_roles, $password ) {
			$password_types = $password_info->campaign_app_type;
			$default_values = array(
				'is_valid' => false,
				'role'     => '',
			);
			// Check with global master password.
			if ( PPW_Constants::PPW_MASTER_GLOBAL === $password_types ) {
				$default_values['is_valid'] = true;

				return $default_values;
			}
			// Check with role master password.
			$role = $this->get_role_of_master_password( $current_roles, $password_types );
			if ( false !== $role ) {
				$default_values['is_valid'] = true;
				$default_values['role']     = $role;

				return $default_values;
			}

			return $default_values;
		}

		/**
		 * Check post type is valid for master password.
		 *
		 * @param string $post_id       Post ID.
		 * @param object $password_info Password information from database.
		 *
		 * @return bool Check post type is exist in database.
		 */
		public function check_post_type_for_master_password( $post_id, $password_info ) {
			$post_type = get_post_type( $post_id );

			// Valid post type data.
			if ( false === $post_type || empty( $password_info->post_types ) ) {
				return false;
			}

			$post_types_protection = apply_filters( PPW_Constants::HOOK_MASTER_PASSWORDS_VALID_POST_TYPES, array( 'post' ) );

			// Check if post type exist in settings.
			if ( ! in_array( $post_type, $post_types_protection, true ) ) {
				return false;
			}

			$post_types_selected = explode( ';', $password_info->post_types );

			return in_array( $post_type, $post_types_selected, true );
		}

		/**
		 * Check password for type is roles.
		 *
		 * @param array  $current_roles  List current roles.
		 * @param string $password_types password type in DB.
		 *
		 * @return bool|mixed
		 */
		public function get_role_of_master_password( $current_roles, $password_types ) {
			$type_array = explode( ';', $password_types );
			foreach ( $type_array as $password_type ) {
				$role = str_replace( PPW_Constants::PPW_MASTER_ROLE, '', $password_type );
				if ( in_array( $role, $current_roles, true ) ) {
					return $role;
				}
			}

			return false;
		}

		/**
		 * Check protection in settings
		 *
		 * @param int $post_id The post ID.
		 *
		 * @return bool
		 */
		public function check_protection( $post_id ) {
			$post_type = get_post_type( $post_id );
			if ( 'post' === $post_type || 'page' === $post_type ) {
				return true;
			}

			return false;
		}

		/**
		 * Check if master passwords in cookie is valid.
		 *
		 * @param int $post_id Post ID.
		 *
		 * @return bool True if password is valid.
		 */
		public function check_master_password_is_valid( $post_id ) {
			if ( ! $this->check_master_cookies_are_exist() ) {
				return false;
			}
			$master_passwords = $this->passwords_repository->get_activate_master_passwords_info();
			// Get all passwords which exist current post type.
			$master_passwords = $this->massage_master_passwords_with_post_type( $master_passwords, $post_id );

			if ( count( $master_passwords ) > 0 ) {
				// Valid master cookies.
				foreach ( $master_passwords as $master_password ) {
					if ( $this->is_valid_cookie( $master_password->id, array( $master_password->password ), PPW_Constants::MASTER_COOKIE_NAME ) ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Get master passwords which post type is valid.
		 *
		 * @param array $master_passwords List Master Passwords from database.
		 * @param int   $post_id          Post ID.
		 *
		 * @return array List master passwords after valid post type.
		 */
		public function massage_master_passwords_with_post_type( $master_passwords, $post_id ) {
			$post_type = get_post_type( $post_id );

			if ( false === $post_type ) {
				return array();
			}

			$post_types_protection = apply_filters( PPW_Constants::HOOK_MASTER_PASSWORDS_VALID_POST_TYPES, array( 'post' ) );
			if ( ! in_array( $post_type, $post_types_protection, true ) ) {
				return array();
			}

			return array_filter(
				$master_passwords,
				function ( $master_password ) use ( $post_type ) {
					if ( empty( $master_password->post_types ) ) {
						return false;
					}
					$post_types = explode( ';', $master_password->post_types );

					return in_array( $post_type, $post_types, true );
				}
			);
		}

		/**
		 * Check master cookie is exist.
		 *
		 * @return bool True if master cookie is exist.
		 */
		public function check_master_cookies_are_exist() {
			if ( ! isset( $_COOKIE ) ) {
				return false;
			}

			// Check with cookie name which contains master password name.
			foreach ( $_COOKIE as $key => $value ) {
				if ( false !== strpos( $key, PPW_Constants::MASTER_COOKIE_NAME ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Get protection post type after convert it to select options.
		 *
		 * @return array Protection post types select option.
		 */
		public function get_protection_post_types_select() {
			$post_types_protection = apply_filters( PPW_Constants::HOOK_MASTER_PASSWORDS_VALID_POST_TYPES, array( 'post' ) );

			return array_reduce(
				ppw_core_get_all_post_types(),
				function ( $carry, $post_type ) use ( $post_types_protection ) {
					if ( isset( $post_type->name ) && in_array( $post_type->name, $post_types_protection, true ) ) {
						$carry[] = array(
							'key'   => $post_type->name,
							'value' => $post_type->label,
						);
					}

					return $carry;
				},
				array()
			);
		}

		/**
		 * Check logic and hide pages/posts protected in home, categories, search results, tags, authors, archive, feed.
		 *
		 * @param string   $where    The WHERE clause of the query.
		 * @param WP_Query $wp_query The WP_Query instance (passed by reference).
		 *
		 * @return string
		 */
		public function handle_hide_post_protected( $where, $wp_query ) {
			$post_types    = apply_filters( PPW_Constants::HOOK_CUSTOM_POST_TYPE_HIDE_PROTECTED_POST, PPW_Constants::DEFAULT_POST_TYPE );
			$protected_ids = $this->custom_protected_ids();
			if ( empty( $protected_ids ) ) {
				return $where;
			}

			foreach ( $post_types as $post_type ) {
				$is_hide = ppw_core_get_setting_type_bool( PPW_Constants::HIDE_PROTECTED . $post_type );
				if ( ! $is_hide ) {
					continue;
				}
				$position_selected = ppw_core_get_setting_type_array( PPW_Constants::HIDE_SELECTED . $post_type );
				$where             = ppw_core_handle_logic_add_query( $position_selected, $protected_ids, $where, $post_type );
			}

			return $where;
		}

		/**
		 * Check logic and hide posts protected in recent post
		 *
		 * @param array $posts_args An array of arguments used to retrieve the recent posts.
		 *
		 * @return array
		 */
		public function handle_hide_post_protected_recent_post( $posts_args ) {
			$post_types    = apply_filters( PPW_Constants::HOOK_CUSTOM_POST_TYPE_RECENT_POST, array( 'post' ) );
			$protected_ids = $this->custom_protected_ids();
			if ( empty( $protected_ids ) ) {
				return $posts_args;
			}

			$old_post_not_in = isset( $posts_args['post__not_in'] ) ? $posts_args['post__not_in'] : array();
			foreach ( $post_types as $post_type ) {
				$is_hide = ppw_core_get_setting_type_bool( PPW_Constants::HIDE_PROTECTED . $post_type );
				if ( ! $is_hide ) {
					continue;
				}
				$position_selected = ppw_core_get_setting_type_array( PPW_Constants::HIDE_SELECTED . $post_type );
				if ( ! in_array( PPW_Constants::RECENT_POST, $position_selected, true ) ) {
					continue;
				}
				foreach ( $protected_ids as $id ) {
					if ( get_post_type( $id ) !== $post_type ) {
						continue;
					}
					$old_post_not_in[] = $id;
				}
			}
			$posts_args['post__not_in'] = $old_post_not_in;

			return $posts_args;
		}

		/**
		 * Check logic and hide posts protected in next and previous post
		 *
		 * @param string $where The WHERE clause of the query.
		 *
		 * @return string
		 */
		public function handle_hide_post_protected_next_and_previous( $where ) {
			$post_types    = apply_filters( PPW_Constants::HOOK_CUSTOM_POST_TYPE_NEXT_AND_PREVIOUS, array( 'post' ) );
			$protected_ids = $this->custom_protected_ids();
			if ( empty( $protected_ids ) ) {
				return $where;
			}

			foreach ( $post_types as $post_type ) {
				$is_hide = ppw_core_get_setting_type_bool( PPW_Constants::HIDE_PROTECTED . $post_type );
				if ( ! $is_hide ) {
					continue;
				}
				$position_selected = ppw_core_get_setting_type_array( PPW_Constants::HIDE_SELECTED . $post_type );
				if ( ! in_array( PPW_Constants::NEXT_PREVIOUS, $position_selected, true ) ) {
					continue;
				}
				foreach ( $protected_ids as $id ) {
					if ( get_post_type( $id ) !== $post_type ) {
						continue;
					}
					$where .= " AND p.ID != {$id}";
				}
			}

			return $where;
		}

		/**
		 * Check condition and exclude protected page in list page get by function get_pages
		 *
		 * @param array $pages List of pages to retrieve.
		 *
		 * @return array
		 */
		public function handle_hide_page_protected( $pages ) {
			$type      = 'page';
			$page_hide = ppw_core_get_setting_type_bool( PPW_Constants::HIDE_PROTECTED . $type );
			if ( ! $page_hide ) {
				return $pages;
			}
			$protected_ids = $this->custom_protected_ids();
			if ( empty( $protected_ids ) ) {
				return $pages;
			}
			$position_selected = ppw_core_get_setting_type_array( PPW_Constants::HIDE_SELECTED . $type );
			if ( ! in_array( PPW_Constants::EVERYWHERE_PAGE, $position_selected, true ) ) {
				return $pages;
			}
			foreach ( $protected_ids as $id ) {
				if ( 'page' !== get_post_type( $id ) ) {
					continue;
				}
				$pages = array_filter(
					$pages,
					function ( $page ) use ( $id ) {
						return $page->ID !== (int) $id;
					}
				);
			}

			return $pages;
		}

		/**
		 * Check condition and exclude page/post protected in Yoast SEO XML Sitemaps
		 *
		 * @param array $ids List page_id/post_id exclude in Yoast SEO XML Sitemaps.
		 *
		 * @return array
		 */
		public function handle_hide_page_protected_yoast_seo_sitemaps( $ids ) {
			$post_types    = apply_filters( PPW_Constants::HOOK_CUSTOM_POST_TYPE_HIDE_PROTECTED_POST, PPW_Constants::DEFAULT_POST_TYPE );
			$protected_ids = $this->custom_protected_ids();
			if ( empty( $protected_ids ) ) {
				return $ids;
			}

			foreach ( $post_types as $post_type ) {
				$is_hide = ppw_core_get_setting_type_bool( PPW_Constants::HIDE_PROTECTED . $post_type );
				if ( ! $is_hide ) {
					continue;
				}
				$position_selected = ppw_core_get_setting_type_array( PPW_Constants::HIDE_SELECTED . $post_type );
				// Push the post ID into list exclude from site map.
				$ids = ppw_core_list_posts_exclude_in_site_maps( $position_selected, $protected_ids, $ids, $post_type );
			}

			return $ids;
		}

		/**
		 * Get protected IDs.
		 * Declare hook for Pro custom protected IDs to handle hide protected posts.
		 *
		 * @return array
		 */
		public function custom_protected_ids() {
			$protected_ids = wp_cache_get( 'ppwp_protected_ids' );
			if ( false === $protected_ids ) {
				$protected_ids = apply_filters( PPW_Constants::HOOK_CUSTOM_POST_ID_HIDE_PROTECTED_POST, array() );
				if ( empty( $protected_ids ) ) {
					$protected_ids = $this->get_protected_post_ids();
				}
				wp_cache_set( 'ppwp_protected_ids', $protected_ids );
			}

			return $protected_ids;
		}

		/**
		 * Restore WP Post password.
		 */
		public function restore_wp_post_password() {
			$post_passwords = $this->passwords_repository->get_wp_post_passwords();
			if ( empty( $post_passwords ) ) {
				return;
			}

			foreach ( $post_passwords as $post_password ) {
				$post_id = wp_update_post(
					array(
						'ID'            => $post_password->post_id,
						'post_password' => $post_password->meta_value,
					)
				);
				if ( $post_id ) {
					delete_post_meta( $post_id, $post_password->meta_key, $post_password->meta_value );
				}
			}
		}

	}
}
