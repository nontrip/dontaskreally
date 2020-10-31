<?php

if ( ! class_exists( 'PPW_Elementor' ) ) {
	class PPW_Elementor {
		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @access   protected
		 * @var      PPW_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;
		/**
		 * Minimum elementor version.
		 *
		 * @var PPW_Elementor
		 */
		private static $instance;

		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		const MINIMUM_PPW_FREE_VERSION = '1.2.3.3';

		/**
		 * Get instance.
		 *
		 * @param PPW_Loader $loader Maintains and registers all hooks for the plugin.
		 *
		 * @return PPW_Elementor
		 */
		public static function get_instance( $loader ) {
			if ( null === self::$instance ) {
				self::$instance = new self( $loader );
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @param PPW_Loader $loader Maintains and registers all hooks for the plugin.
		 *
		 * PPW_Elementor constructor.
		 */
		public function __construct( $loader ) {
			$this->loader = $loader;
			$this->init();
		}

		/**
		 * Register Elementor hooks.
		 */
		public function init() {
			if ( ! did_action( 'elementor/loaded' ) ) {
				return;
			}

			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) || ! version_compare( PPW_VERSION, self::MINIMUM_PPW_FREE_VERSION, '>=' ) ) {
				return;
			}

			$this->loader->add_action( 'elementor/widgets/widgets_registered', $this, 'register_widgets' );
		}

		/**
		 * Register widgets.
		 */
		public function register_widgets() {
			$supported_pro_version = array( '1.1.5', '1.1.5.1' );
			if ( defined( 'PPW_PRO_VERSION' ) && in_array( PPW_PRO_VERSION, $supported_pro_version, true ) && is_pro_active_and_valid_license() ) {
				return;
			}

			// Include widget files.
			require_once( __DIR__ . '/widgets/class-ppw-elementor-widget-shortcode.php' ); //phpcs:ignore
			require_once( __DIR__ . '/widgets/class-ppw-elementor-advance-widget-shortcode.php' ); //phpcs:ignore

			// Register widget.
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new PPW_Shortcode_Widget() );
			// Handle hooks from origin widget to add more features.
			$advance_widget = new PPW_Shortcode_Advance_Widget();
			$advance_widget->init();
		}

	}
}
