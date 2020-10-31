<?php
/**
 * Check condition and include plugin.php file
 */
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Check data before update setting
 *
 * @param array $request         Request data.
 * @param array $setting_keys    Keys need to check.
 * @param bool  $is_check_cookie Is check cookie.
 *
 * @return bool
 */
function ppw_free_is_setting_data_invalid( $request, $setting_keys, $is_check_cookie = true ) {
	if ( ppw_free_is_setting_keys_and_nonce_invalid( $request, PPW_Constants::GENERAL_FORM_NONCE ) ) {
		return true;
	}

	$settings = $request["settings"];
	foreach ( $setting_keys as $key ) {
		if ( ! array_key_exists( $key, $settings ) ) {
			return true;
		}
	}

	if ( ! $is_check_cookie ) {
		return false;
	}

	// Check regular expression.
	return ppw_core_validate_cookie_expiry( $settings[ PPW_Constants::COOKIE_EXPIRED ] );
}

/**
 * Check data before update entire site settings
 *
 * @param $request
 *
 * @return bool
 */
function ppw_free_is_entire_site_settings_data_invalid( $request ) {
	return ppw_free_is_setting_keys_and_nonce_invalid( $request, PPW_Constants::ENTIRE_SITE_FORM_NONCE );
}

/**
 * @param $request
 * @param $nonce_key
 *
 * @return bool
 */
function ppw_free_is_setting_keys_and_nonce_invalid( $request, $nonce_key ) {
	if ( ! array_key_exists( 'settings', $request ) ||
	     ! array_key_exists( 'security_check', $request ) ) {
		return true;
	}

	if ( ! wp_verify_nonce( $request['security_check'], $nonce_key ) ) {
		return true;
	}

	return false;
}

/**
 * Check error before create new password
 *
 * @param $request
 * @param $setting_keys
 *
 * @return bool
 */
function ppw_free_error_before_create_password( $request, $setting_keys ) {
	if ( ppw_free_is_setting_keys_and_nonce_invalid( $request, PPW_Constants::META_BOX_NONCE ) ) {
		return true;
	}

	$settings = $request["settings"];
	foreach ( $setting_keys as $key ) {
		if ( ! array_key_exists( $key, $settings ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Validate password type is global
 *
 * @param $new_global_passwords
 * @param $current_global_passwords
 * @param $current_roles_password
 */
function ppw_free_validate_password_type_global( $new_global_passwords, $current_global_passwords, $current_roles_password ) {
	if ( count( $new_global_passwords ) < 1 && empty( $current_global_passwords ) ) {
		wp_send_json(
			array(
				'is_error' => true,
				'message'  => PPW_Constants::EMPTY_PASSWORD,
			),
			400
		);
		wp_die();
	}

	$global_validate = ppw_free_check_duplicate_global_password( $new_global_passwords, $current_roles_password );
	if ( $global_validate ) {
		wp_send_json(
			array(
				'is_error' => true,
				'message'  => PPW_Constants::DUPLICATE_PASSWORD,
			),
			400
		);
		wp_die();
	}
}

/**
 * Check case duplicate password type is global
 *
 * @param $new_global_passwords
 * @param $current_roles_password
 *
 * @return bool
 */
function ppw_free_check_duplicate_global_password( $new_global_passwords, $current_roles_password ) {
	if ( empty( $current_roles_password ) ) {
		return false;
	}
	$password_duplicate = array_intersect( $new_global_passwords, array_values( $current_roles_password ) );

	return ! empty( $password_duplicate );
}

/**
 * Validate password type is role
 *
 * @param $role_selected
 * @param $new_role_password
 * @param $current_global_passwords
 * @param $current_roles_password
 */
function ppw_free_validate_password_type_role( $role_selected, $new_role_password, $current_global_passwords, $current_roles_password ) {
	if ( '' === $new_role_password && ( ! isset( $current_roles_password[ $role_selected ] ) || '' === $current_roles_password[ $role_selected ] ) ) {
		wp_send_json(
			array(
				'is_error' => true,
				'message'  => PPW_Constants::EMPTY_PASSWORD,
			),
			400
		);
		wp_die();
	}

	$role_validate = ppw_free_check_duplicate_role_password( $new_role_password, $current_global_passwords );
	if ( $role_validate ) {
		wp_send_json(
			array(
				'is_error' => true,
				'message'  => PPW_Constants::DUPLICATE_PASSWORD,
			),
			400
		);
		wp_die();
	}
}

/**
 * Check case duplicate password type is role
 *
 * @param $new_role_password
 * @param $current_global_passwords
 *
 * @return bool
 */
function ppw_free_check_duplicate_role_password( $new_role_password, $current_global_passwords ) {
	if ( empty( $current_global_passwords ) ) {
		return false;
	}
	$new_role_password = wp_unslash( $new_role_password );

	return in_array( $new_role_password, $current_global_passwords );
}

/**
 * Get all page and post
 *
 * @return array
 */
function ppw_free_get_all_page_post() {
	return array_merge( get_pages(), get_posts( array( 'post_status' => 'publish', 'numberposts' => - 1 ) ) );
}

/**
 * Helper to fix serialized data
 * TODO: write UT for this important function
 *
 * @param $raw_data
 * @param $is_un_slashed
 *
 * @return array
 */
function ppw_free_fix_serialize_data( $raw_data, $is_un_slashed = true ) {
	if ( ! $raw_data ) {
		return array();
	}

	$serialize_raw_data = @unserialize( $raw_data );
	if ( false === $serialize_raw_data ) {
		return $raw_data;
	}

	return $is_un_slashed ? wp_unslash( $serialize_raw_data ) : $serialize_raw_data;
}

/**
 * @param $cookie
 * @param $expiration
 *
 * @return bool
 */
function ppw_free_bypass_cache_with_cookie_for_pro_version( $cookie, $expiration ) {
	if ( defined( 'COOKIEHASH' ) ) {
		$cookie_hash = preg_quote( constant( 'COOKIEHASH' ) );
	}
	setcookie( PPW_Constants::WP_POST_PASS . $cookie_hash, $cookie, $expiration, COOKIEPATH, COOKIE_DOMAIN );

	return true;
}

/**
 * Check custom login form is showing to avoid conflict with the_password_form default of WordPress.
 *  - Check post type is default type ( post or page )
 *  - Do not show login form product type because we handled it in PPW Pro version. ( woocommerce_before_single_product
 *  hook )
 *  - If Pro version is active then we check protection type in setting to show login form
 *
 * @param string $post_type Post Type of Post.
 *
 * @return bool True|False Is show login form.
 */
function ppw_is_post_type_selected_in_setting( $post_type ) {
	/**
	 * Check default post type
	 * Free & Pro version default: post and page type.
	 */
	if ( 'post' === $post_type || 'page' === $post_type ) {
		return true;
	}

	$is_handle_old_product_type = apply_filters( PPW_Constants::HOOK_HANDLE_BEFORE_RENDER_WOO_PRODUCT, 'product' === $post_type, $post_type );
	if ( $is_handle_old_product_type || ! class_exists( 'PPW_Pro_Constants' ) ) {
		return false;
	}
	$post_type_selected = ppw_core_get_setting_type_array( PPW_Pro_Constants::WPP_WHITELIST_COLUMN_PROTECTIONS );

	/**
	 * Check post type in setting which user selected.
	 */
	return in_array( $post_type, $post_type_selected, true );
}

/**
 * Get post_id from referer url if Post data is not exist post_id.
 *
 * @return int post_id Post ID, 0 if post id not exist.
 */
function ppw_get_post_id_from_request() {
	if ( isset( $_POST['post_id'] ) ) {
		return (int) wp_unslash( $_POST['post_id'] );
	}
	/**
	 * Make sure http referer on server.
	 * Not make exception in url_to_postid.
	 */
	if ( ! isset( $_SERVER['HTTP_REFERER'] ) ) {
		return 0;
	}

	// Get post id from referer url.
	return url_to_postid( $_SERVER['HTTP_REFERER'] );
}

/**
 * WP introduced is_wp_version_compatible function from version 5.2.0 only.
 * (https://developer.wordpress.org/reference/functions/is_wp_version_compatible/)
 * Need to write the helper by our-self.
 *
 * @param string $required Version to check.
 *
 * @return bool
 */
function ppw_is_wp_version_compatible( $required ) {
	return empty( $required ) || version_compare( get_bloginfo( 'version' ), $required, '>=' );
}

/**
 * Get page title for home, category, tag or post
 *
 * @return string
 */
function ppw_get_page_title() {
	$site_title       = get_bloginfo( 'title' );
	$site_description = get_bloginfo( 'description' );
	$post_title       = wp_title( '', false ); // Post title, category tile, tag title.
	$dash_score_site  = '' === $site_title || '' === $site_description ? '' : ' – ';
	$dash_score_post  = '' === $site_title || '' === $post_title ? '' : ' – ';

	return is_home() || is_front_page()
		? sprintf( '%1$s%2$s%3$s', $site_title, $dash_score_site, $site_description )
		: sprintf( '%1$s%2$s%3$s', $post_title, $dash_score_post, $site_title );
}

/**
 * Get post excerpt if post is protected via Settings.
 *
 * @param WP_Post $post            Post WordPress Object.
 * @param string  $content         Content of post.
 * @param bool    $is_show_excerpt Is show excerpt.
 *                                 TODO: Need to refactor logic for this function.
 *
 * @return string
 */
function ppw_handle_protected_content( $post, $content, $is_show_excerpt ) {
	if ( $is_show_excerpt && $post->post_excerpt ) {
		$content = $post->post_excerpt . $content;
	}

	if ( ! is_singular() && ! preg_match( '/name=.+post_id/mi', $content ) ) {
		$content = '<em>[This is password-protected.]</em>';

		return apply_filters( 'the_ppw_password_message', $content );
	}

	return $content;
}

/**
 * Helper function to get Pro version.
 */
function ppw_get_pro_version() {
	if ( ! defined( 'PPW_PRO_VERSION' ) ) {
		return '';
	}

	return PPW_PRO_VERSION;
}

/**
 * Bypass function using to
 *    - Display feed content when user turn on sitewide protection.
 *
 * @return bool True is bypass sitewide.
 */
function ppw_free_has_bypass_sitewide_protection() {
	$has_bypass = defined( 'PPWP_SITEWIDE_FEED_DISPLAY' ) && PPWP_SITEWIDE_FEED_DISPLAY && is_feed();

	return apply_filters( 'ppwp_sitewide_has_bypass', $has_bypass );
}

/**
 * Bypass function using to
 *    - Display feed content when post/page is protected by single protection.
 *
 * @return bool True is bypass post_password_required.
 */
function ppw_free_has_bypass_single_protection() {
	$has_bypass = defined( 'PPWP_SINGLE_FEED_DISPLAY' ) && PPWP_SINGLE_FEED_DISPLAY && is_feed();

	return apply_filters( 'ppwp_single_has_bypass', $has_bypass );
}

/**
 * Has support PPWP shortcode for page builder.
 *
 * @return bool
 */
function ppw_free_has_support_shortcode_page_builder() {
	// Have user turn on option.
	$enabled = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_SHORTCODE_PAGE_BUILDER, PPW_Constants::SHORTCODE_OPTIONS );

	return apply_filters( 'ppwp_shortcode_enable_page_builder', $enabled );
}

/**
 * Checks the plaintext password against the encrypted Password.
 *
 * @param string $password Plaintext user's password
 * @param string $hash     Hash of the user's password to check against.
 *
 * @return bool False, if the $password does not match the hashed password
 * @link https://developer.wordpress.org/reference/functions/wp_check_password/
 */
function ppw_free_check_password( $password, $hash ) {
	global $wp_hasher;
	// If the stored hash is longer than an MD5,
	// presume the new style phpass portable hash.
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		// By default, use the portable hash from phpass.
		$wp_hasher = new PasswordHash( 8, true );
	}

	return $wp_hasher->CheckPassword( $password, $hash );
}

/**
 * Retrieve the shortcode matches for searching.
 *
 * @param $content
 *
 * @return array The regular expression contains 6 different sub matches to help with parsing.
 * 1 - An extra [ to allow for escaping shortcodes with double [[]]
 * 2 – The shortcode name
 * 3 – The shortcode argument list
 * 4 – The self closing /
 * 5 – The content of a shortcode when it wraps some content.
 * 6 – An extra ] to allow for escaping shortcodes with double [[]]
 */
function ppw_free_search_shortcode_content( $content ) {
	preg_match_all( '/' . get_shortcode_regex( array( 'ppwp' ) ) . '/', $content, $matches, PREG_SET_ORDER );

	return $matches;
}

function ppw_free_valid_pcp_password( $shortcode, $password ) {
	$default_args = array(
		'is_valid_password' => false,
		'atts'              => array(),
	);
	// Check ppwp shortcode exist.
	if ( PPW_Constants::PPW_HOOK_SHORT_CODE_NAME !== $shortcode[2] || ! isset( $shortcode[3] ) ) {
		return $default_args;
	}
	// Parse shortcode string to array.
	$parsed_atts = shortcode_parse_atts( trim( $shortcode[3] ) );

	// Get attributes from shortcode.
	$atts      = PPW_Shortcode::get_instance()->get_attributes( $parsed_atts );
	$passwords = apply_filters( PPW_Constants::HOOK_SHORTCODE_PASSWORDS, array_filter( $atts['passwords'], 'strlen' ), $parsed_atts );

	// Check password exist.
	if ( in_array( $password, $passwords, true ) ) {
		$default_args['is_valid_password'] = true;
		$default_args['atts']              = $atts;
	}

	if ( isset( $parsed_atts['error_msg'] ) ) {
		$default_args['message'] = wp_kses_post( $parsed_atts['error_msg'] );
	}

	return $default_args;
}

/**
 * Validate date.
 *
 * @param        $date
 * @param string $format
 *
 * @return bool
 */
function ppw_free_validate_date( $date ) {
	return false !== strtotime( $date );
}
