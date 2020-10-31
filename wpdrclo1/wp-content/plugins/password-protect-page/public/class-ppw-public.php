<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://passwordprotectwp.com
 * @since      1.0.0
 *
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/public
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class PPW_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Class PPW_Password_Services
	 *
	 * @var PPW_Password_Services
	 */
	private $password_services;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name       = $plugin_name;
		$this->version           = $version;
		$this->password_services = new PPW_Password_Services();
	}

	/**
	 * Register the stylesheets and javascript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_assets() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Password_Protect_Page_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Password_Protect_Page_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Filter before render content.
	 *
	 * @param string $content Content of post/page.
	 *
	 * @return mixed
	 * @deprecated Because we only use post_password_required to show login form.
	 * @since      1.2.2 Deprecated for function, we will remove it after 2 release.
	 */
	public function ppw_filter_content( $content ) {
		if ( ! in_the_loop() ) {
			return $content;
		}

		$post = get_post();
		if ( is_null( $post ) ) {
			return $content;
		}

		$post_id         = $post->ID;
		$is_pro_activate = apply_filters( PPW_Constants::HOOK_IS_PRO_ACTIVATE, false );
		if ( $is_pro_activate ) {
			return apply_filters( PPW_Constants::HOOK_CHECK_PASSWORD_BEFORE_RENDER_CONTENT, $content, $post_id );
		}

		return $this->ppw_free_content_filter( $content, $post_id );
	}

	/**
	 * Filter content for free version
	 *
	 * @param array  $post_id Data from client.
	 * @param string $content Data from client.
	 *
	 * @return bool|string
	 * @deprecated
	 *
	 */
	private function ppw_free_content_filter( $content, $post_id ) {
		// 1. Check page/post is protected.
		$result        = $this->password_services->is_protected_content( $post_id );
		if ( false === $result ) {
			return $content;
		}

		// 2. Check password in cookie.
		$passwords = $result['passwords'];
		if ( $this->password_services->is_valid_cookie( $post_id, $passwords, PPW_Constants::COOKIE_NAME ) ) {
			return $content;
		}

		// 3. Form rendering.
		if ( $result['has_global_passwords'] || ( $result['has_role_passwords'] && $result['has_current_role_password'] ) ) {
			return ppw_core_render_login_form();
		}

		return '<p><strong>This page is protected. Please try again or contact the website owner.</strong></p>';
	}

	/**
	 * Post class
	 *
	 * @param array $classes Classes.
	 *
	 * @return array
	 * @since 1.5.0 Mark deprecated function.
	 * @deprecated
	 */
	public function ppw_post_class( $classes ) {
		$classes[] = PPW_Constants::CUSTOM_POST_CLASS;

		return $classes;
	}

	/**
	 * Show custom login form which protected by PPW Plugin, it will replace default form of WordPress.
	 *
	 * @param string   $output The password form HTML output.
	 *
	 * @return string The password form HTML output.
	 *
	 * @global WP_Post $post   Post object
	 * @since 1.2.2 Init the_password_form
	 */
	public function ppw_the_password_form( $output ) {
		$post = $GLOBALS['post'];
		if ( empty( $post->ID ) || ! ppw_is_post_type_selected_in_setting( $post->post_type ) ) {
			return $output;
		}

		$should_render_form = apply_filters( PPW_Constants::HOOK_SHOULD_RENDER_PASSWORD_FORM, true );

		if ( ! $should_render_form ) {
			return '';
		}

		return ppw_core_render_login_form();
	}

	/**
	 * Only render text in all page diff post/page custom post type which it is not have post_id input.
	 * Check a site is post/page or custom post type
	 * Use regex to check it is our password form then render text.
	 *
	 * @param string $content Content of the post.
	 *
	 * @return string
	 */
	public function ppw_the_content( $content ) {
		// Do not handle on admin page.
		if ( is_admin() ) {
			return $content;
		}

		$is_show_excerpt = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::PROTECT_EXCERPT, PPW_Constants::MISC_OPTIONS );
		if ( is_singular() && ! $is_show_excerpt ) {
			return $content;
		}

		$post = get_post();
		// Check post type is selected.
		if ( ! $post || ! ppw_is_post_type_selected_in_setting( $post->post_type ) ) {
			return $content;
		}

		// Check it is password form.
		if ( post_password_required() ) {

			return ppw_handle_protected_content( $post, $content, $is_show_excerpt );
		}

		return $content;
	}

	/**
	 * Register shortcodes
	 */
	public function register_shortcodes() {
		PPW_Shortcode::get_instance();
	}

	/**
	 * Check logic and hide pages/posts protected
	 *
	 * @param string   $where    The WHERE clause of the query.
	 * @param WP_Query $wp_query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function handle_hide_post_protected( $where, $wp_query ) {
		if ( is_admin() ) {
			return $where;
		}

		return $this->password_services->handle_hide_post_protected( $where, $wp_query );
	}

	/**
	 * Check logic and hide posts protected in recent post
	 *
	 * @param array $posts_args An array of arguments used to retrieve the recent posts.
	 *
	 * @return array
	 */
	public function handle_hide_post_protected_recent_post( $posts_args ) {
		if ( is_admin() ) {
			return $posts_args;
		}

		return $this->password_services->handle_hide_post_protected_recent_post( $posts_args );
	}

	/**
	 * Check logic and hide posts protected in next and previous post
	 *
	 * @param string $where The WHERE clause of the query.
	 *
	 * @return string
	 */
	public function handle_hide_post_protected_next_and_previous( $where ) {
		if ( is_admin() ) {
			return $where;
		}

		return $this->password_services->handle_hide_post_protected_next_and_previous( $where );
	}

	/**
	 * Check condition and exclude protected page in list page get by function get_pages
	 *
	 * @param array $pages List of pages to retrieve.
	 * @param array $param Array of get_pages() arguments.
	 *
	 * @return array
	 */
	public function handle_hide_page_protected( $pages, $param ) {
		if ( is_admin() ) {
			return $pages;
		}

		return $this->password_services->handle_hide_page_protected( $pages );
	}

	/**
	 * Check condition and exclude page/post protected in Yoast SEO XML Sitemaps
	 *
	 * @param array $ids List page_id/post_id exclude in Yoast SEO XML Sitemaps.
	 *
	 * @return array
	 */
	public function handle_hide_page_protected_yoast_seo_sitemaps( $ids ) {
		if ( is_admin() ) {
			return $ids;
		}

		return $this->password_services->handle_hide_page_protected_yoast_seo_sitemaps( $ids );
	}

	/**
	 * Validate login.
	 */
	public function ppw_core_validate_login() {
		/**
		 * Should check request have parameter generated by PPF Form.
		 */
		if ( ! isset( $_GET['action'] ) || ! isset( $_GET['type'] ) || ! isset( $_GET[ PPW_Constants::CALL_BACK_URL_PARAM ] ) ) {
			return;
		}
		if ( 'ppw_postpass' !== $_GET['action'] || 'individual' !== $_GET['type'] ) {
			return;
		}

		if ( ! ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_CUSTOM_FORM_ACTION, PPW_Constants::MISC_OPTIONS ) ) {
			return;
		}

		// It is post method and have post_password input from user.
		if ( ! isset( $_POST['post_password'] ) ) {
			wp_safe_redirect( $this->password_services->get_referer_url() );
			exit();
		}

		// Get post_id from referer url if Post data is not exist post_id.
		$post_id = ppw_get_post_id_from_request();

		if ( empty( $post_id ) ) {
			wp_safe_redirect( $this->password_services->get_referer_url() );
			exit();
		}

		$password = wp_unslash( $_POST['post_password'] );

		$this->password_services->handle_after_enter_password_in_password_form( $post_id, $password );
	}

	/**
	 * Generate action URL.
	 *
	 * @param string $action_url Action URL.
	 *
	 * @return string Action URL after generated.
	 */
	public function ppw_core_get_ppf_action_url( $action_url ) {
		if ( ! ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_CUSTOM_FORM_ACTION, PPW_Constants::MISC_OPTIONS ) ) {
			return $action_url;
		}

		$callback_value = rawurlencode( apply_filters( PPW_Constants::HOOK_CALLBACK_URL, get_permalink() ) );
		$url            = add_query_arg(
			array(
				'action'                           => 'ppw_postpass',
				'type'                             => 'individual',
				PPW_Constants::CALL_BACK_URL_PARAM => $callback_value,
			),
			null
		);

		if ( isset( $_GET['ppws'] ) ) {
			$url = add_query_arg( 'ppws', $_GET['ppws'], $url );
		}

		return $url;
	}

	/**
	 * Set cookie time for password.
	 * 
	 * @param integer $time Expired time of a cookie.
	 * 
	 * @return integer
	 */
	public function set_cookie_time( $time ) {
		if ( ! isset( $_GET['ppws'] ) || '1' !== $_GET['ppws'] ) {
			return $time;
		}

		return 0;
	}

	/**
	 * Handle access link with ppw_ac parameter and without encoding URL.
	 */
	public function handle_access_link() {
		if ( ! isset( $_GET['ppw_ac'] ) ) {
			return;
		}
		if ( ! is_singular() ) {
			return;
		}

		$password    = wp_unslash( $_GET['ppw_ac'] );
		$post_id     = get_the_ID();
		$permalink   = get_permalink( $post_id );
		$current_url = apply_filters( 'ppwp_access_link', $permalink, $post_id );

		$password_service = new PPW_Password_Services();
		$is_valid = $password_service->is_valid_password_from_request( $post_id, $password );

		if ( $is_valid ) {
			// Bypass single password.
			add_filter( 'post_password_required', '__return_false', 50 );
		}
	}

}
