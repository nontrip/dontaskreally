<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/24/19
 * Time: 15:00
 */
if ( ! class_exists( 'PPW_Asset_Services' ) ) {

	class PPW_Asset_Services {

		/**
		 * Current screen
		 *
		 * @var
		 */
		private $screen;

		/**
		 * Page name of current screen
		 * @var string
		 */
		private $page;

		/**
		 * Tab name of current screen
		 * @var
		 */
		private $tab;

		public function __construct( $screen, $get_params ) {
			$this->screen = $screen;
			if ( isset( $get_params['page'] ) ) {
				$this->page = $get_params['page'];
			}
			if ( isset( $get_params['tab'] ) ) {
				$this->tab = $get_params['tab'];
			}
		}

		/**
		 * Render css and js for entire site tab
		 */
		public function load_assets_for_entire_site_tab() {
			$module = PPW_Constants::ENTIRE_SITE_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && 'entire_site' === $this->tab ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_js( $module, PPW_VERSION );
				wp_localize_script(
					"ppw-$module-js",
					'ppw_entire_site_data',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					)
				);
				$this->load_select2_lib();
				$this->load_toastr_lib();
			}
		}

		/**
		 * Render css & js for sitewide submenu
		 */
		public function load_assets_for_entire_site_page() {
			$module = PPW_Constants::ENTIRE_SITE_MODULE;
			if ( PPW_Constants::SITEWIDE_PAGE_PREFIX === $this->page && ( 'general' === $this->tab || null === $this->tab ) ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_js( $module, PPW_VERSION );
				wp_localize_script(
					"ppw-$module-js",
					'ppw_entire_site_data',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					)
				);
				$this->load_select2_lib();
				$this->load_toastr_lib();
			}
		}

		public function load_assets_for_shortcode_page() {
			if ( PPW_Constants::PCP_PAGE_PREFIX === $this->page && ( 'general' === $this->tab || null === $this->tab ) ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_toastr_lib();
				$this->load_shared_lib();
			}
		}

		/**
		 * Load assets for shortcode setting.
		 */
		public function load_assets_for_shortcode_setting() {
			$module           = PPW_Constants::SHORTCODES_SETTINGS_MODULE;
			$is_shortcode_tab = PPW_Constants::MENU_NAME === $this->page
			                    && 'shortcodes' === $this->tab;
			$is_pcp_submenu   = PPW_Constants::PCP_PAGE_PREFIX === $this->page
			                    && ( 'general' === $this->tab || null === $this->tab );
			if ( $is_shortcode_tab || $is_pcp_submenu ) {
				$this->load_select2_lib();
				$this->load_js( $module, PPW_VERSION );
				wp_localize_script(
					"ppw-$module-js",
					'ppw_shortcode_data',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'home_url' => ppw_core_get_home_url_with_ssl(),
						'nonce'    => wp_create_nonce( PPW_Constants::GENERAL_FORM_NONCE ),
					)
				);
			}
		}

		/**
		 * Is Partial Protection submenu.
		 *
		 * @param string $page Page name.
		 * @param string $tab Tab name.
		 *
		 * @return bool
		 */
		public static function is_partial_protection_submenu( $page, $tab ) {
			return PPW_Constants::PCP_PAGE_PREFIX === $page
			       && ( 'general' === $tab || null === $tab );
		}

		/**
		 * Render css and js for general tab
		 */
		public function load_assets_for_general_tab() {
			$module = PPW_Constants::GENERAL_SETTINGS_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && ( $module === $this->tab || null === $this->tab ) ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_js( $module, PPW_VERSION );
				wp_localize_script(
					"ppw-$module-js",
					'ppw_general_data',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'home_url' => ppw_core_get_home_url_with_ssl(),
						'nonce'    => wp_create_nonce( 'wp_rest' ),
					)
				);
				$this->load_select2_lib();
				$this->load_toastr_lib();
			}
		}

		/**
		 * Render css and js for general tab
		 */
		public function load_assets_for_misc_tab() {
			$module = PPW_Constants::MISC_SETTINGS_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && ( $module === $this->tab || null === $this->tab ) ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_js( $module, PPW_VERSION );
				wp_localize_script(
					"ppw-$module-js",
					'ppw_misc_data',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'home_url' => ppw_core_get_home_url_with_ssl(),
						'nonce'    => wp_create_nonce( 'wp_rest' ),
					)
				);
				$this->load_select2_lib();
				$this->load_toastr_lib();

				do_action( PPW_Constants::HOOK_ADVANCED_TAB_LOAD_ASSETS );
			}
		}

		/**
		 * Render css and js for general tab
		 */
		public function load_assets_for_category_page() {
			global $pagenow;
			$module  = 'category';
			$is_show = 'edit-tags.php' === $pagenow && isset( $_GET['taxonomy'] ) && 'category' === $_GET['taxonomy'];
			$is_show = apply_filters( 'ppwp_is_load_assets_for_category_page', $is_show );
			if ( $is_show ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_css( $module, PPW_VERSION );
				$this->load_js( $module, PPW_VERSION );
				wp_localize_script(
					"ppw-$module-js",
					'ppw_category_data',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'home_url' => ppw_core_get_home_url_with_ssl(),
						'nonce'    => wp_create_nonce( 'wp_rest' ),
					)
				);
				$this->load_select2_lib();
				$this->load_toastr_lib();

				do_action( 'ppw_category_page_load_assets' );
			}
		}

		/**
		 * Render css and js for troubleshoot tab
		 */
		public function load_assets_for_troubleshoot_tab() {
			$module = PPW_Constants::TROUBLESHOOT_SETTINGS_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && ( $module === $this->tab || null === $this->tab ) ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_js( $module, PPW_VERSION );
			}
		}

		/**
		 * Load asserts for meta-box.
		 */
		public function load_assets_for_meta_box() {
			$module = PPW_Constants::META_BOX_MODULE;
			$this->load_css( $module, PPW_VERSION );
			$this->load_js( $module, PPW_VERSION );
			wp_localize_script(
				"ppw-$module-js",
				'save_password_data',
				array(
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'error_message' => array(
						'duplicate_password' => PPW_Constants::DUPLICATE_PASSWORD,
						'empty_password'     => PPW_Constants::EMPTY_PASSWORD,
						'space_password'     => PPW_Constants::SPACE_PASSWORD,
					),
				)
			);

			$this->load_toastr_lib();
		}

		/**
		 * Load asserts for shortcodes setting tab.
		 */
		public function load_assets_for_shortcodes() {
			$module = PPW_Constants::SHORTCODES_SETTINGS_MODULE;
			if ( PPW_Constants::MENU_NAME === $this->page && $module === $this->tab ) {
				$this->load_bundle_css( PPW_VERSION );
				$this->load_toastr_lib();
				$this->load_shared_lib();
			}
		}

		/**
		 * Render Select2 library
		 */
		public function load_select2_lib() {
			wp_enqueue_script( 'ppw-select2-js', PPW_DIR_URL . 'admin/js/lib/select2.min.js', array( 'jquery' ), '4.0.3', true );
			wp_enqueue_style( 'ppw-select2-css', PPW_DIR_URL . 'admin/css/lib/select2.min.css', array(), '4.0.3', 'all' );
		}

		/**
		 * Load js file to show notice when deactivating the plugin.
		 */
		public function load_js_show_notice_deactivate_plugin() {
			if ( 'plugins' === $this->screen ) {
				wp_enqueue_script( 'ppw-notice-deactivate-js', PPW_DIR_URL . 'admin/js/class-ppw-notice-deactivate.js', array( 'jquery' ), PPW_VERSION, true );
				wp_localize_script(
					'ppw-notice-deactivate-js',
					'ppw_deactivate_data',
					array(
						'is_active_pro' => is_plugin_active( PPW_Constants::PRO_DIRECTORY ) || is_plugin_active( PPW_Constants::DEV_PRO_DIRECTORY ),
					)
				);
			}
		}

		/**
		 * Load the shared lib.
		 */
		public function load_shared_lib() {
			wp_enqueue_script( 'ppw-shared-js', PPW_DIR_URL . 'includes/views/shared/assets/dist/ppwUtils.bundle.js', array( 'jquery' ), PPW_VERSION, true );
			wp_localize_script(
				'ppw-shared-js',
				'ppw_general_data',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				)
			);
		}

		/**
		 * Render Toastr library
		 */
		public function load_toastr_lib() {
			wp_enqueue_script( 'ppw-toastr-js', PPW_DIR_URL . 'admin/js/lib/toastr.min.js', array( 'jquery' ), '2.1.3', true );
			wp_enqueue_style( 'ppw-toastr-css', PPW_DIR_URL . 'admin/css/lib/toastr.min.css', array(), '2.1.3', 'all' );
		}

		/**
		 * Render CSS to hide feature set password of WP
		 */
		public function load_css_hide_feature_set_password_wp() {
			$page_post_screens = apply_filters(
				PPW_Constants::HOOK_HIDE_DEFAULT_PW_WP_POSITION,
				[
					'edit-post',
					'edit-page',
					'page',
					'post',
				]
			);
			if ( in_array( $this->screen, $page_post_screens, true ) ) {
				wp_enqueue_style( 'ppw-hide-default-css', PPW_DIR_URL . 'admin/css/ppw-hide-default.css', array(), PPW_VERSION, 'all' );
			}
		}

		/**
		 * Helper function to load css of module
		 *
		 * @param string $module       Module for loading.
		 * @param string $version      Version to load.
		 * @param array  $dependencies Dependencies.
		 */
		public function load_css( $module, $version, $dependencies = array() ) {
			wp_enqueue_style( "ppw-$module-css", PPW_VIEW_URL . "dist/ppw-$module.css", $dependencies, $version, 'all' );
		}

		/**
		 * Load bundle css file
		 *
		 * @param string $version CSS version.
		 */
		public function load_bundle_css( $version ) {
			wp_enqueue_style( 'ppw-bundle-css', PPW_DIR_URL . 'admin/css/dist/ppw-setting.css', array(), $version, 'all' );
			if ( ppw_is_wp_version_compatible( '5.3' ) ) {
				wp_enqueue_style( 'ppw-bundle-css-wp-5-3', PPW_DIR_URL . 'includes/views/dist/ppw-general-wp-5-3.css', array(), $version, 'all' );
			}
		}

		/**
		 * Helper function to load js of module
		 *
		 * @param string $module       Module for loading.
		 * @param string $version      Version to load.
		 * @param array  $dependencies Dependencies.
		 */
		public function load_js( $module, $version, $dependencies = array() ) {
			wp_enqueue_script( "ppw-$module-js", PPW_VIEW_URL . "dist/ppw-$module.js", $dependencies, $version, 'all' );
		}
	}
}
