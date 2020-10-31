<?php

if ( ! class_exists( 'PPW_Entire_Site_Services' ) ) {
	class PPW_Entire_Site_Services {
		/**
		 * Auth cookie
		 *
		 * @return bool
		 */
		function validate_auth_cookie_entire_site() {
			$cookie_elements = $this->parse_cookie_entire_site();
			if ( false === $cookie_elements ) {
				return false;
			}

			if ( (int) $cookie_elements[1] < time() ) {
				return false;
			}

			$password = ppw_core_get_setting_entire_site_type_string( PPW_Constants::PASSWORD_ENTIRE_SITE );
			$hash     = hash_hmac( 'md5', PPW_Constants::ENTIRE_SITE_COOKIE_NAME, $password );

			return $cookie_elements[0] === $hash;
		}

		/**
		 * Parse cookie
		 *
		 * @return array|bool
		 */
		function parse_cookie_entire_site() {
			$cookie_name = PPW_Constants::ENTIRE_SITE_COOKIE_NAME;
			if ( empty( $_COOKIE[ $cookie_name ] ) ) {
				return false;
			}

			$cookie          = $_COOKIE[ $cookie_name ];
			$cookie_elements = explode( '|', $cookie );
			if ( count( $cookie_elements ) != 2 ) {
				return false;
			}

			return $cookie_elements;
		}

		/**
		 * Check is valid password
		 *
		 * @param $password
		 *
		 * @return bool
		 */
		public function entire_site_is_valid_password( $password ) {
			if ( ! isset( $_REQUEST['input_wp_protect_password'] ) ) {
				return false;
			}

			$password_input = $_REQUEST['input_wp_protect_password'];

			return md5( $password_input ) === $password;
		}

		/**
		 * Set password to cookie
		 *
		 * @param string $password Password.
		 */
		public function entire_site_set_password_to_cookie( $password ) {
			$expiration     = time() + 7 * DAY_IN_SECONDS;
			$cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
			if ( ! empty( $cookie_expired ) ) {
				$time = explode( ' ', $cookie_expired )[0];
				$unit = ppw_core_get_unit_time( $cookie_expired );
				if ( 0 !== $unit ) {
					$expiration = time() + (int) $time * $unit;
				}
			}

			$hash   = hash_hmac( 'md5', PPW_Constants::ENTIRE_SITE_COOKIE_NAME, $password );
			$cookie = $hash . '|' . $expiration;
			ppw_free_bypass_cache_with_cookie_for_pro_version( $cookie, $expiration );
			setcookie( PPW_Constants::ENTIRE_SITE_COOKIE_NAME, $cookie, $expiration, COOKIEPATH, COOKIE_DOMAIN );
		}

		/**
		 * Redirect after enter password
		 */
		public function entire_site_redirect_after_enter_password() {
			// Can get the HTTP_REFERER first as the redirect URL that:
			// Fixes the private link redirection belonged to PPP Pro.
			if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
				$current_url = $_SERVER['HTTP_REFERER']; //phpcs:ignore
			} else {
				global $wp;
				$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
			}

			// TODO: consider to user wp_safe_redirect.
			wp_redirect( $current_url );
		}

		/**
		 * Handle before update settings for entire site
		 *
		 * @param $data_settings
		 *
		 * @return bool
		 */
		public function handle_before_update_settings( $data_settings ) {
			// Clear cache Super Cache plugin
//			$free_cache = new PPW_Cache_Services();
//			$free_cache->clear_cache_super_cache();

			if ( array_key_exists( PPW_Constants::IS_PROTECT_ENTIRE_SITE, $data_settings ) && $data_settings[ PPW_Constants::IS_PROTECT_ENTIRE_SITE ] === "true" ) {
				// Create new password
				if ( ! array_key_exists( PPW_Constants::SET_NEW_PASSWORD_ENTIRE_SITE, $data_settings ) ) {
					return $this->create_new_password( $data_settings );
				}

				// Change password
				if ( array_key_exists( PPW_Constants::SET_NEW_PASSWORD_ENTIRE_SITE, $data_settings ) && $data_settings[ PPW_Constants::SET_NEW_PASSWORD_ENTIRE_SITE ] === "true" ) {
					return $this->change_password( $data_settings );
				}

				// Don't change password
				return true;
			}

			// Unprotect entire site
			return delete_option( PPW_Constants::ENTIRE_SITE_OPTIONS );
		}

		/**
		 * Create new password entire site
		 *
		 * @param $data_settings
		 *
		 * @return bool
		 */
		public function create_new_password( $data_settings ) {
			$data_settings[ PPW_Constants::PASSWORD_ENTIRE_SITE ] = md5( $data_settings[ PPW_Constants::PASSWORD_ENTIRE_SITE ] );
			update_option( PPW_Constants::ENTIRE_SITE_OPTIONS, $data_settings );

			return true;
		}

		/**
		 * Change password entire site
		 *
		 * @param $data_settings
		 *
		 * @return bool
		 */
		public function change_password( $data_settings ) {
			$data_settings[ PPW_Constants::PASSWORD_ENTIRE_SITE ] = md5( $data_settings[ PPW_Constants::PASSWORD_ENTIRE_SITE ] );
			unset( $data_settings[ PPW_Constants::SET_NEW_PASSWORD_ENTIRE_SITE ] );
			update_option( PPW_Constants::ENTIRE_SITE_OPTIONS, $data_settings );

			return true;
		}
	}
}
