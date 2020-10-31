<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 8/28/19
 * Time: 11:24
 */

if ( ! class_exists( 'PPW_Shortcode' ) ) {
	/**
	 *
	 * Class PPW_Shortcode
	 */
	class PPW_Shortcode {

		/**
		 * Short code attributes.
		 *
		 * @var array
		 */
		private $attributes;

		/**
		 * Supported roles.
		 *
		 * @var array
		 */
		private $supported_roles;

		/**
		 * Supported post types.
		 *
		 * @var array
		 */
		private $supported_post_types;

		/**
		 * The main class name which using to add the index.
		 *
		 * @var string
		 */
		private $main_class_name;

		/**
		 * Register the short code ppwp_content_protector with WordPress
		 * and include the asserts for it.
		 */
		public function __construct() {
			$this->attributes = apply_filters(
				PPW_Constants::HOOK_SHORT_CODE_ATTRS,
				array(
					'passwords'         => '',
					'headline'          => PPW_Constants::DEFAULT_SHORTCODE_HEADLINE,
					'description'       => PPW_Constants::DEFAULT_SHORTCODE_DESCRIPTION,
					'id'                => '',
					'class'             => '',
					'placeholder'       => '',
					'button'            => PPW_Constants::DEFAULT_SHORTCODE_BUTTON,
					'whitelisted_roles' => '',
					'group'             => '',
					'label'             => PPW_Constants::DEFAULT_SHORTCODE_LABEL,
					'error_msg'         => PPW_Constants::DEFAULT_SHORTCODE_ERROR_MSG,
					'on'                => '',
					'off'               => '',
				)
			);

			// Defined by WordPress: https://wordpress.org/support/article/roles-and-capabilities/.
			$this->supported_roles = apply_filters(
				PPW_Constants::HOOK_SUPPORTED_WHITELIST_ROLES,
				array(
					'administrator',
					'editor',
					'author',
					'contributor',
					'subscriber',
				)
			);

			$this->supported_post_types = apply_filters(
				PPW_Constants::HOOK_SUPPORTED_POST_TYPES,
				array(
					'page',
					'post',
				)
			);

			add_shortcode( PPW_Constants::PPW_HOOK_SHORT_CODE_NAME, array( $this, 'render_shortcode' ) );
			add_filter( 'ppw_content_shortcode_source', array( $this, 'render_block_content' ), 15 );

			// Support page builder.
			add_action( 'the_post', array( $this, 'maybe_remove_ppwp_shortcode' ), 10 );
			add_action( 'the_post', array( $this, 'maybe_add_ppwp_shortcode' ), 99999 );


			/**
			 * Need to keep the old Pro version work, because the sidewide shortcode is using global var ppwContentGlobal.
			 */
			if ( defined( 'PPW_PRO_VERSION' ) ) {
				$pro_version = ppw_get_pro_version();
				if ( version_compare( $pro_version, '1.2.2', '<' ) ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
				}
			}

			$this->main_class_name = PPW_Constants::DEFAULT_SHORTCODE_CLASS_NAME;
		}

		/**
		 * Maybe remove shortcode before WPBakery and WordPress do_shortcode in FrontEnd.
		 */
		public function maybe_remove_ppwp_shortcode() {
			if ( ! ppw_free_has_support_shortcode_page_builder() ) {
				return;
			}

			remove_shortcode( PPW_Constants::PPW_HOOK_SHORT_CODE_NAME );
		}

		/**
		 * Maybe add shortcode back.
		 */
		public function maybe_add_ppwp_shortcode() {
			if ( ! ppw_free_has_support_shortcode_page_builder() ) {
				return;
			}

			add_filter( 'the_content', function ( $content ) {
				add_shortcode( PPW_Constants::PPW_HOOK_SHORT_CODE_NAME, array( $this, 'render_shortcode' ) );

				/* translators: Opening curly double quote. */
				$opening_quote = _x( '&#8220;', 'opening curly double quote' );
				/* translators: Closing curly double quote. */
				$closing_quote = _x( '&#8221;', 'closing curly double quote' );
				/* translators: Apostrophe, for example in 'cause or can't. */
				$apos = _x( '&#8217;', 'apostrophe' );
				/* translators: Prime, for example in 9' (nine feet). */
				$prime = _x( '&#8242;', 'prime' );
				/* translators: Double prime, for example in 9" (nine inches). */
				$double_prime = _x( '&#8243;', 'double prime' );
				/* translators: Opening curly single quote. */
				$opening_single_quote = _x( '&#8216;', 'opening curly single quote' );
				/* translators: Closing curly single quote. */
				$closing_single_quote = _x( '&#8217;', 'closing curly single quote' );

				$matches = ppw_free_search_shortcode_content( $content );
				if ( ! empty( $matches ) ) {
					foreach ( $matches as $match ) {
						// The shortcode argument list
						$old_argument_shortcode = $match[3];
						$argument_shortcode     = $match[3];

						$argument_shortcode = str_replace( $opening_quote, '"', $argument_shortcode );
						$argument_shortcode = str_replace( $closing_quote, '"', $argument_shortcode );
						$argument_shortcode = str_replace( $apos, '\'', $argument_shortcode );
						$argument_shortcode = str_replace( $prime, '\'', $argument_shortcode );
						$argument_shortcode = str_replace( $double_prime, '"', $argument_shortcode );
						$argument_shortcode = str_replace( $opening_single_quote, '\'', $argument_shortcode );
						$argument_shortcode = str_replace( $closing_single_quote, '\'', $argument_shortcode );

						$content = str_replace( $old_argument_shortcode, $argument_shortcode, $content );
					}
				}

				$content = do_shortcode( $content );

				return $content;
			}, 99999 );
		}

		/**
		 * Get short code instance
		 *
		 * @return PPW_Shortcode
		 */
		public static function get_instance() {
			return new PPW_Shortcode();
		}

		/**
		 * Render password form or restricted content
		 * 0. Check current post type is in whitelist types
		 * 1. Check is valid attributes
		 * 2. Check whitelist roles
		 * 3. Check password is correct compare to Cookie
		 * 4. Show form
		 *
		 * @param array  $attrs   list of attributes including password.
		 * @param string $content the content inside short code.
		 *
		 * @return string
		 */
		public function render_shortcode( $attrs, $content = null ) {
			global $page;

			// In case the shortcode is outside in the loop, the page is 0.
			$number = ! empty( $page ) ? $page : 1;

			$attrs = shortcode_atts(
				$this->attributes,
				$attrs
			);

			$message = $this->is_valid_shortcode( $attrs, $content );
			if ( true !== $message ) {
				return $this->get_invalid_shortcode_message( $message, $attrs );
			}

			$content = sprintf(
				'<div class="%s">%s</div>',
				$this->get_main_class_name( $attrs ),
				do_shortcode( $content )
			);

			$whitelisted_roles = apply_filters( PPW_Constants::HOOK_SHORT_CODE_WHITELISTED_ROLES, $attrs['whitelisted_roles'] );

			if ( $this->is_whitelisted_role( $whitelisted_roles ) ) {
				// Remember to wrap the content between the parent div. If you want to replace the shortcode content.
				return apply_filters( PPW_Constants::HOOK_SHORTCODE_RENDER_CONTENT, $content, $attrs );
			}

			// Unlock content by datetime.
			$unlocked = apply_filters( 'ppw_shortcode_unlock_content', $this->is_unlock_content_by_time( $attrs ), $attrs ); 
			if ( $unlocked ) {
				return apply_filters( PPW_Constants::HOOK_SHORTCODE_RENDER_CONTENT, $content, $attrs );
			}

			do_action( PPW_Constants::HOOK_SHORT_CODE_BEFORE_CHECK_PASSWORD, $content );

			// Passwords attribute format: passwords="123 345 898942".
			$passwords = apply_filters( PPW_Constants::HOOK_SHORTCODE_PASSWORDS, array_filter( explode( ' ', trim( $attrs['passwords'] ) ), 'strlen' ), $attrs );

			foreach ( $passwords as $password ) {
				// When passwords attribute having special characters eg: <script>alert('hello')</script>. WP will encode the HTML tag. Need to decode to compare the value in Cookie.
				$hashed_password = wp_hash_password( wp_specialchars_decode( $password ) );
				if ( $this->is_valid_password( $hashed_password ) ) {
					// Remember to wrap the content between the parent div. If you want to replace the shortcode content.
					return apply_filters( PPW_Constants::HOOK_SHORTCODE_RENDER_CONTENT, $content, $attrs );
				}
			}

			do_action( PPW_Constants::HOOK_SHORT_CODE_AFTER_CHECK_PASSWORD, $content );

			$this->add_scripts();

			// Show custom text instead of password form.
			$custom_form = apply_filters( PPW_Constants::HOOK_SHORTCODE_BEFORE_RENDER_PASSWORD_FORM, false, $attrs );
			if ( false !== $custom_form ) {
				return sprintf(
					'<div class="%s">%s</div>',
					$this->get_main_class_name( $attrs ),
					$this->massage_attributes( $custom_form )
				);
			}

			return $this->get_restricted_content_form( $attrs, $number );
		}

		/**
		 * Show content if user set on_date or off_date attribute.
		 * $on_date: Date to unlock content
		 * $off_date: Date to protect content.
		 *
		 * @param array $attrs Attributes.
		 *
		 * @return false True is unlock content else false.
		 */
		private function is_unlock_content_by_time( $attrs ) {
			$on_date = false;
			if ( '' !== $attrs['on'] ) {
				$on_date = strtotime( $attrs['on'] );
			}

			$off_date = false;
			if ( '' !== $attrs['off'] ) {
				$off_date = strtotime( $attrs['off'] );
			}

			// Show password form if on_date and off_date are empty.
			if ( ! $on_date && ! $off_date ) {
				return false;
			}

			$now = current_time( 'timestamp' );

			// Unlock content between on_date and off_date.
			if ( $on_date && $off_date && $on_date <= $now && $off_date >= $now ) {
				return apply_filters( 'ppw_shortcode_unlock_content_by_time', true, $attrs );
			}

			// Unlock content from on_date.
			if ( $on_date && ! $off_date && $now >= $on_date ) {
				return apply_filters( 'ppw_shortcode_unlock_content_by_time', true, $attrs );
			}

			return false;
		}

		/**
		 * Require javascript bundle file for shortcode.
		 */
		public function add_scripts() {
			$assert_folder = '/public/js/dist';
			wp_enqueue_script(
				'ppw-cookie',
				PPW_DIR_URL . "$assert_folder/ppw-rc-form.bundle.js",
				array( 'jquery' ),
				PPW_VERSION,
				false
			);
			wp_localize_script(
				'ppw-cookie',
				'ppwContentGlobal',
				array(
					'restUrl'             => get_rest_url(),
					'nonce'               => wp_create_nonce( 'wp_rest' ),
					'cookieExpiration'    => $this->get_cookie_expiration(),
					'supportedClassNames' => apply_filters(
						'ppw_shortcode_supported_class_name',
						array(
							'defaultType' => PPW_Constants::DEFAULT_SHORTCODE_CLASS_NAME,
						)
					),
					'label'               => array(
						'LOADING' => _x( 'Loading...', PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, 'password-protect-page' ),
					),
				)
			);
		}

		/**
		 * Check whether short code is valid.
		 *
		 * @param array  $attrs   Shortcode attributes.
		 * @param string $content Short code content.
		 *
		 * @return string
		 */
		private function is_valid_shortcode( $attrs, $content ) {
			if ( ! $this->is_supported_post_types( get_post_type() ) ) {
				/* translators: %s: Short code name */
				$message = sprintf( __( 'Our Free version [%s] shortcode doesn\'t support Custom Post Type', 'password-protect-page' ), PPW_Constants::PPW_HOOK_SHORT_CODE_NAME );

				return apply_filters( PPW_Constants::HOOK_SHORTCODE_NOT_SUPPORT_TYPE_ERROR_MESSAGE, $message );
			}

			/* translators: %s: Short code name */
			$message = sprintf( __( '[%s] Empty content, invalid attributes or values', 'password-protect-page' ), PPW_Constants::PPW_HOOK_SHORT_CODE_NAME );
			$message = apply_filters( PPW_Constants::HOOK_SHORT_CODE_ERROR_MESSAGE, $message );

			if ( $this->is_empty_content( $content, $attrs ) ) {
				return $message;
			}

			if ( ! $this->is_valid_attributes( $attrs ) ) {
				return $message;
			}

			return true;
		}

		/**
		 * @param $attrs
		 */
		private function is_valid_date( $attrs ) {
			if ( '' !== $attrs['on'] && ! ppw_free_validate_date( $attrs['on'] ) ) {
				return false;
			}
			if ( '' !== $attrs['off'] && ! ppw_free_validate_date( $attrs['off'] ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Check if the password is valid, comparing with cookie.
		 *
		 * @param string $password Password.
		 *
		 * @return bool
		 */
		private function is_valid_password( $password ) {

			$is_valid = apply_filters( 'ppw_shortcode_is_valid_password_with_cookie', false, $password, $_COOKIE );

			if ( $is_valid ) {

				return apply_filters( 'ppw_shortcode_after_check_is_valid_password_with_cookie', $is_valid, $password, array() );

			}

			$cookie_name = 'ppw_rc-' . get_the_ID();
			if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
				return false;
			}

			global $wp_hasher;
			// Here do not need to sanitize $_COOKIE data, because we use it for comparision.
			$cookie_val = json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ) ); // phpcs:ignore
			if ( ! is_array( $cookie_val ) ) {
				return false;
			}

			foreach ( $cookie_val as $val ) {
				if ( get_the_ID() !== (int) $val->post_id ) {
					continue;
				}

				foreach ( $val->passwords as $cookie_pass ) {
					if ( $wp_hasher->CheckPassword( $cookie_pass, $password ) ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * Get restricted content form.
		 *
		 * @param array $attrs  Short-code attributes.
		 * @param int   $number Short-code attributes.
		 *
		 * @return array|mixed
		 */
		private function get_restricted_content_form( $attrs, $number ) {
			ob_start();
			include apply_filters(
				PPW_Constants::HOOK_SHORT_CODE_TEMPLATE,
				PPW_DIR_PATH . 'includes/views/shortcode/view-ppw-restriced-content-form.php'
			);
			$form_template = ob_get_contents();
			ob_end_clean();
			// phpcs:disable
			$form_params = array(
				PPW_Constants::SHORT_CODE_FORM_HEADLINE      => _x( $this->massage_attributes( $attrs['headline'] ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, 'password-protect-page' ),
				PPW_Constants::SHORT_CODE_FORM_INSTRUCT      => _x( $this->massage_attributes( $attrs['description'] ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, 'password-protect-page' ),
				PPW_Constants::SHORT_CODE_FORM_PLACEHOLDER   => _x( $this->massage_attributes( $attrs['placeholder'] ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, 'password-protect-page' ),
				PPW_Constants::SHORT_CODE_FORM_AUTH          => get_the_ID(),
				PPW_Constants::SHORT_CODE_BUTTON             => _x( wp_kses_post( $attrs['button'] ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, 'password-protect-page' ),
				PPW_Constants::SHORT_CODE_FORM_CURRENT_URL   => $this->get_the_permalink_without_cache( wp_rand( 0, 100 ) ),
				PPW_Constants::SHORT_CODE_FORM_ID            => '' === $attrs['id'] ? get_the_ID() . wp_rand( 0, 1000 ) : wp_kses_post( $attrs['id'] ),
				PPW_Constants::SHORT_CODE_FORM_CLASS         => '' === $attrs['class'] ? $this->get_main_class_name( $attrs ) : $this->get_main_class_name( $attrs ) . ' ' . wp_kses_post( $attrs['class'] ),
				PPW_Constants::SHORT_CODE_PASSWORD_LABEL     => _x( $this->massage_attributes( $attrs['label'] ), PPW_Constants::CONTEXT_PCP_PASSWORD_FORM, 'password-protect-page' ),
				PPW_Constants::SHORT_CODE_FORM_ERROR_MESSAGE => '',
				'[PPW_PAGE]'                                 => $number,
			);
			// phpcs:enable

			foreach ( $form_params as $key => $value ) {
				$form_template = str_replace( $key, $value, $form_template );
			}

			return $form_template;
		}

		/**
		 * Massage attributes before showing the front end.
		 *
		 * @param string $val The value.
		 *
		 * @return mixed
		 */
		private function massage_attributes( $val ) {
			return wp_kses_post( html_entity_decode( $val ) );
		}

		/**
		 * Get post permalink with random value
		 *
		 * @param int $rand_value Random value.
		 *
		 * @return string
		 */
		private function get_the_permalink_without_cache( $rand_value ) {
			return get_the_permalink() . "?action=postpass&r=$rand_value";
		}

		/**
		 * Validate short_code attributes
		 *
		 * @param array $attrs Attributes.
		 *
		 * @return bool
		 */
		private function is_valid_attributes( $attrs ) {
			$required_attrs = apply_filters(
				PPW_Constants::HOOK_SHORTCODE_ATTRIBUTES_VALIDATION,
				array(
					array(
						'key'       => 'passwords',
						'length'    => 100,
						'delimiter' => ' ',
					),
				),
				$attrs
			);

			foreach ( $required_attrs as $attr ) {
				$val = trim( $attrs[ $attr['key'] ] );
				if ( '' === $val ) {
					return false;
				}

				$items = explode( $attr['delimiter'], $val );
				foreach ( $items as $item ) {
					if ( $attr['length'] < strlen( $item ) ) {
						return false;
					}
				}
			}

			if ( ! $this->is_valid_date( $attrs ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Invalid shortcode message.
		 *
		 * @param string $message Error message.
		 * @param array  $attrs   Attributes shortcode.
		 *
		 * @return string
		 */
		private function get_invalid_shortcode_message( $message, $attrs ) {
			$color = esc_attr( PPW_Constants::PPW_ERROR_MESSAGE_COLOR );

			return sprintf(
				'<span class="%s" style="color:%s;display: block">%s</span>',
				$this->get_main_class_name( $attrs ),
				$color,
				$message
			);
		}

		/**
		 * Is whitelisted roles
		 *
		 * @param string $whitelisted_roles Attribute whitelist roles from shortcode.
		 *
		 * @return bool
		 */
		private function is_whitelisted_role( $whitelisted_roles ) {
			$roles = explode( ',', trim( $whitelisted_roles ) );
			foreach ( $roles as $role ) {
				$role = trim( $role );
				if ( in_array( $role, $this->supported_roles, true ) && current_user_can( $role ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Is current post type supported.
		 *
		 * @param string $type Current post type.
		 *
		 * @return bool
		 */
		private function is_supported_post_types( $type ) {
			$is_bypass = apply_filters( PPW_Constants::HOOK_SHORTCODE_ALLOW_BYPASS_VALID_POST_TYPE, defined( 'PPW_PRO_VERSION' ) );
			if ( $is_bypass ) {
				return true;
			}

			return in_array( $type, $this->supported_post_types, true );
		}

		/**
		 * Get cookie expiration
		 *
		 * @return int
		 */
		public function get_cookie_expiration() {
			$default            = apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + 7 * DAY_IN_SECONDS );
			$setting_expiration = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
			if ( empty( $setting_expiration ) ) {
				return $default;
			}

			$tmp = explode( ' ', $setting_expiration );
			if ( count( $tmp ) < 2 ) {
				return $default;
			}

			$val  = $tmp[0];
			$unit = ppw_core_get_unit_time( $setting_expiration );

			if ( 0 === $unit ) {
				return $default;
			}

			return apply_filters( PPW_Constants::HOOK_COOKIE_EXPIRED, time() + (int) $val * $unit );
		}

		/**
		 * Check whether the content is empty.
		 *
		 * @param string $content The content.
		 * @param array  $attrs   The shortcode attributes.
		 *
		 * @return bool
		 */
		private function is_empty_content( $content, $attrs ) {
			$is_empty = '' === $content;

			return apply_filters( 'ppwp_shortcode_is_empty_content', $is_empty, $content, $attrs );
		}

		/**
		 * Get attributes from shortcode
		 *
		 * @param array $parsed_atts Shortcode attributes in array type.
		 *
		 * @return array
		 */
		public function get_attributes( $parsed_atts ) {

			// Default values for attributes.
			$default_values = array(
				'passwords' => array(),
				'cookie'    => $this->get_cookie_expiration(),
			);

			// Shortcode_parse_atts return array or empty which we only use array.
			if ( ! is_array( $parsed_atts ) ) {
				return $default_values;
			}

			// Get passwords attribute.
			if ( isset( $parsed_atts['passwords'] ) ) {
				$default_values['passwords'] = $this->get_passwords_attribute( $parsed_atts );
			}

			// Get cookie attribute.
			if ( isset( $parsed_atts['cookie'] ) && intval( $parsed_atts['cookie'] ) > PPW_Constants::MIN_COOKIE_EXPIRED ) {
				$default_values['cookie'] = $this->get_expired_time_cookie_attribute( $parsed_atts );
			}

			return $default_values;
		}

		/**
		 * Convert string password to array
		 * Example:
		 * Input: 'a b c'
		 * Output: ['a','b','c']
		 *
		 * @param array $parsed_atts Attributes parsed.
		 *
		 * @return array
		 */
		private function get_passwords_attribute( $parsed_atts ) {
			return array_map(
				function ( $p ) {
					return wp_specialchars_decode( $p );
				},
				explode( ' ', $parsed_atts['passwords'] )
			);
		}

		/**
		 * Convert day to timestamp of cookie.
		 *
		 * @param array $parsed_atts Attributes parsed.
		 *
		 * @return int
		 */
		private function get_expired_time_cookie_attribute( $parsed_atts ) {
			$hours = absint( $parsed_atts['cookie'] );
			$hours = $hours > PPW_Constants::MAX_COOKIE_EXPIRED ? PPW_Constants::MAX_COOKIE_EXPIRED : $hours;

			return time() + $hours * HOUR_IN_SECONDS;
		}

		/**
		 * Get main class name.
		 *
		 * @param array $attrs Attributes shortcode.
		 *
		 * @return string
		 */
		public function get_main_class_name( $attrs ) {
			$post_fix = empty( $attrs['type'] )
				? ''
				: '-' . $attrs['type'];

			return $this->main_class_name . $post_fix;
		}

		/**
		 * Get content for post by page number. Case use break page in content.
		 *
		 * @param object $post The post content.
		 * @param int    $page The page number.
		 *
		 * @return bool|string
		 */
		public function get_content_by_page_number( $post, $page ) {
			if ( function_exists( 'generate_postdata' ) ) {
				$postdata = generate_postdata( $post );
				$pages    = $postdata['pages'];
			} else {
				$postdata = setup_postdata( $post );
				global $pages;
			}

			if ( false === $postdata ) {
				return false;
			}

			return $pages[ $page - 1 ];
		}

		/**
		 * Handle block content on Gutenberg
		 *
		 * @param string $content Post content.
		 *
		 * @return string Content after rendered.
		 */
		public function render_block_content( $content ) {
			if ( ! function_exists( 'parse_blocks' ) ||
			     ! function_exists( 'has_blocks' ) ||
			     ! function_exists( 'render_block' )
			) {
				return $content;
			}
			if ( has_blocks( $content ) ) {
				$content_markup = '';
				$blocks = parse_blocks( $content );
				foreach ( $blocks as $block ) {
					$content_markup .= render_block( $block );
				}

				return $content_markup;
			}

			return $content;
		}
	}

}
