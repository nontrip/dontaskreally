<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://passwordprotectwp.com
 * @since      1.0.0
 *
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Password_Protect_Page
 * @subpackage Password_Protect_Page/includes
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class Password_Protect_Page {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      PPW_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PPW_VERSION' ) ) {
			$this->version = PPW_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'password-protect-page';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Password_Protect_Page_Loader. Orchestrates the hooks of the plugin.
	 * - Password_Protect_Page_i18n. Defines internationalization functionality.
	 * - Password_Protect_Page_Admin. Defines all hooks for the admin area.
	 * - Password_Protect_Page_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-i18n.php';

		/**
		 * Require Constants
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-constants.php';

		/**
		 * Require Functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-functions.php';

		/**
		 * Require Service Interfaces
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/class-ppw-service-interfaces.php';

		/**
		 * Require Options Services
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-options.php';

		/**
		 * Require Services Class
		 * TODO: need to rename for meaningful idea.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-passwords.php';

		/**
		 * The class responsible for subscribe services
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-subscribe.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-assets.php';

		/**
		 * The class responsible for caching service
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-cache.php';

		/**
		 * The class responsible for settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-ppw-settings.php';

		/**
		 * The class responsible for sitewide settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-ppw-sitewide-settings.php';

		/**
		 * The class responsible for shortcode settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-ppw-partial-protection-settings.php';

		/**
		 * The class responsible for entire site services
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-entire-site.php';

		/**
		 * The class responsible for shortcode service
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-shortcode.php';

		/**
		 * The class responsible for API declaration
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-api.php';

		/**
		 * The class responsible for core functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'core/class-ppw-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ppw-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ppw-public.php';

		/**
		 * The class responsible for defining all actions that occur in the customizer
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-customizer.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-customizer-sitewide.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-customizer-upsell.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizers/class-ppw-text-editor-control.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizers/class-ppw-toggle-control.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/customizers/class-ppw-title-group-control.php';

		/**
		 * The class responsible for defining all addons.
		 */
		require_once PPW_DIR_PATH . 'includes/addons/beaver-builder/class-ppw-beaver-loader.php';
		require_once PPW_DIR_PATH . 'includes/addons/elementor/class-ppw-elementor.php';

		/**
		 * The class responsible for defining database functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ppw-db.php';

		/**
		 * The class responsible for defining protect category.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/services/class-ppw-category.php';

		/**
		 * The class responsible for plugin SDK
		 */
		if ( is_pro_active_and_valid_license() ) {
			// Need the load the pro lib first that using for the other add-ons.
			ppw_core_load_pro_lib();
		}

		$this->loader = new PPW_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Password_Protect_Page_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new PPW_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new PPW_Admin( $this->get_plugin_name(), $this->get_version() );
		PPW_Customizer_Service::get_instance();
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_assets' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ppw_add_menu', 10 );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'handle_admin_notices', 10 );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'ppw_free_add_custom_meta_box_to_edit_page' );
		$this->loader->add_action( 'login_form_ppw_postpass', $plugin_admin, 'ppw_handle_enter_password' );

		// Hook to handle default action WordPress which use our plugin.
		$this->loader->add_action( 'login_form_postpass', $plugin_admin, 'ppw_handle_enter_password_for_default_action' );

		$this->loader->add_action( 'template_redirect', $plugin_admin, 'ppw_render_form_entire_site' );
		$this->loader->add_action( 'ppw_redirect_after_enter_password', $plugin_admin, 'ppw_handle_redirect_after_enter_password' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'handle_admin_init' );

		$this->load_base();

		if ( ! is_pro_active_and_valid_license() ) {
			PPW_Customizer_Sitewide::get_instance()->register_sitewide_form();
			PPW_Customizer_Upsell::get_instance();

			$this->loader->add_action( 'wp_ajax_ppw_free_set_password', $plugin_admin, 'ppw_free_set_password' );
			$this->loader->add_action( 'wp_ajax_ppw_free_update_general_settings', $plugin_admin, 'ppw_free_update_general_settings' );
			$this->loader->add_action( 'wp_ajax_ppw_free_update_entire_site_settings', $plugin_admin, 'ppw_free_update_entire_site_settings' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'handle_admin_init_when_pro_is_not_activate' );

			$this->loader->add_action( 'ppw_render_content_general', $plugin_admin, 'ppw_free_render_content_general', 10 );
			$this->loader->add_action( 'ppw_render_content_entire_site', $plugin_admin, 'ppw_free_render_content_entire_site', 11 );

			$this->loader->add_filter( 'post_password_required', $plugin_admin, 'ppw_handle_post_password_required', 10, 2 );
			$this->loader->add_filter( PPW_Constants::HOOK_CUSTOM_TAB, $plugin_admin, 'ppw_handle_custom_tab', 50 );
			$this->loader->add_filter( PPW_Constants::HOOK_ADD_NEW_TAB, $plugin_admin, 'ppw_handle_add_new_tab', 50 );
			$this->loader->add_filter( PPW_Constants::HOOK_CUSTOM_TAB, $plugin_admin, 'ppw_handle_hide_shortcode_content', 50 );
			$this->loader->add_filter( PPW_Constants::HOOK_ADD_NEW_TAB, $plugin_admin, 'ppw_handle_hide_shortcode_tab', 50 );

			$this->loader->add_action( 'ppw_render_sitewide_content_general', $plugin_admin, 'ppw_free_render_content_entire_site', 10 );
			// Testing only the new feature that applied for free only.
			$this->loader->add_shortcode( 'ppw-content-protect', $plugin_admin, 'handle_content_protect_short_code' );

			$this->loader->add_action( 'post_row_actions', $plugin_admin, 'ppw_custom_row_action', 10, 2 );
			$this->loader->add_action( 'page_row_actions', $plugin_admin, 'ppw_custom_row_action', 10, 2 );
			$this->loader->add_action( 'wp_ajax_ppw_update_post_status', $plugin_admin, 'handle_update_post_status' );

			$post_types = array( 'post', 'page' );
			foreach ( $post_types as $post_type ) {
				$this->loader->add_filter( 'manage_' . $post_type . '_posts_columns', $plugin_admin, 'add_custom_column' );
				$this->loader->add_action( 'manage_' . $post_type . '_posts_custom_column', $plugin_admin, 'render_content_custom_column', 10, 2 );
			}
			PPW_Category_Service::get_instance()->register( false );
		} else {
			$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'handle_plugin_loaded' );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'update_column_for_ppwp_pro' );
			PPW_Category_Service::get_instance()->register( true );
		}
		PPW_Customizer_Sitewide::get_instance()->register_sitewide_style();
		$this->loader->add_action( 'wp_ajax_ppw_free_update_misc_settings', $plugin_admin, 'ppw_free_update_misc_settings' );
		$this->loader->add_action( 'wp_ajax_ppw_free_update_category_settings', $plugin_admin, 'ppw_free_update_category_settings' );
		$this->loader->add_action( 'wp_ajax_ppw_free_update_shortcode_settings', $plugin_admin, 'ppw_free_update_shortcode_settings' );
		$this->loader->add_action( 'wp_ajax_ppw_free_restore_wp_passwords', $plugin_admin, 'ppw_free_restore_wp_passwords' );
		$this->loader->add_action( 'ppw_render_content_shortcodes', $plugin_admin, 'ppw_free_render_content_shortcodes', 11 );
		$this->loader->add_action( 'ppw_render_content_master_passwords', $plugin_admin, 'ppw_free_render_content_master_passwords', 11 );
		$this->loader->add_action( 'ppw_render_content_misc', $plugin_admin, 'ppw_free_render_content_misc', 11 );
		$this->loader->add_action( 'ppw_render_content_troubleshooting', $plugin_admin, 'ppw_free_render_content_troubleshooting', 11 );
		$this->loader->add_action( PPW_Constants::HOOK_RESTRICT_CONTENT_AFTER_VALID_PWD, $plugin_admin, 'set_postpass_cookie_to_prevent_cache', 10, 2 );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'rest_api_init', 10, 2 );
		$this->loader->add_filter( 'ppw_content_shortcode_source', $plugin_admin, 'handle_content_shortcode_for_multiple_pages', 11, 3 );
		PPW_Beaver_Loader::get_instance();
		$this->loader->add_action( 'wp_ajax_ppw_free_subscribe_request', $plugin_admin, 'handle_subscribe_request' );
		$this->loader->add_action( 'ppw_render_pcp_content_general', $plugin_admin, 'ppw_free_render_content_pcp_general_tab', 10 );
		$this->loader->add_action( 'plugin_row_meta', $plugin_admin, 'register_plugins_links', 10, 2 );

		PPW_Elementor::get_instance( $this->loader );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new PPW_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'template_redirect', $plugin_public, 'handle_access_link', - 10 );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_assets' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'ppw_the_content', 99999 );
		$this->loader->add_filter( 'ppw_cookie_expire', $plugin_public, 'set_cookie_time', 10 );

		/**
		 * The hook to render our password form
		 */
		$this->loader->add_filter( 'the_password_form', $plugin_public, 'ppw_the_password_form', 99999999 );

		// Register ppwp_content_protector shortcode with WordPress.
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

		//phpcs:ignore
		#region add hook to handle feature "Hide Protected Content"
		$this->loader->add_filter( 'posts_where_paged', $plugin_public, 'handle_hide_post_protected', 10, 2 );
		$this->loader->add_filter( 'widget_posts_args', $plugin_public, 'handle_hide_post_protected_recent_post', 10, 1 );
		$this->loader->add_filter( 'get_next_post_where', $plugin_public, 'handle_hide_post_protected_next_and_previous', 10, 1 );
		$this->loader->add_filter( 'get_previous_post_where', $plugin_public, 'handle_hide_post_protected_next_and_previous', 10, 1 );
		$this->loader->add_filter( 'get_pages', $plugin_public, 'handle_hide_page_protected', 10, 2 );
		$this->loader->add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', $plugin_public, 'handle_hide_page_protected_yoast_seo_sitemaps', 10, 1 );
		//phpcs:ignore #endregion

		$this->loader->add_filter( 'ppwp_ppf_action_url', $plugin_public, 'ppw_core_get_ppf_action_url' );
		$this->loader->add_action( 'wp', $plugin_public, 'ppw_core_validate_login', 5 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    PPW_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Load base classes
	 */
	private function load_base() {
		$core_dir = PPW_DIR_PATH . 'core/base';
		require_once "$core_dir/class-ppw-module.php";
		require_once "$core_dir/class-ppw-background-task.php";
		require_once PPW_DIR_PATH . 'includes/services/class-ppw-migration.php';
		require_once "$core_dir/class-ppw-background-task-manager.php";
		require_once "$core_dir/class-ppw-data-migration-manager.php";

		require_once PPW_DIR_PATH . 'includes/services/class-ppw-migrations.php';
		require_once PPW_DIR_PATH . 'includes/services/class-ppw-migration-manager.php';
		require_once PPW_DIR_PATH . 'includes/services/class-ppw-password-recovery.php';
		require_once PPW_DIR_PATH . 'includes/services/class-ppw-password-recovery-manager.php';
	}
}
