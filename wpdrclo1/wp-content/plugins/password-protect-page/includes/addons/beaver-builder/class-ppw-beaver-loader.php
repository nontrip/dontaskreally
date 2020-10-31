<?php

class PPW_Beaver_Loader {
	/**
	 * Instance of PPW_Beaver_Loader class.
	 *
	 * @var PPW_Beaver_Loader
	 */
	protected static $instance = null;

	/**
	 * PPW_Beaver_Loader constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'setup_hooks' ) );
	}

	/**
	 * Setup hooks.
	 */
	public function setup_hooks() {
		if ( ! class_exists( 'FLBuilder' ) ) {
			return;
		}

		add_filter( 'fl_builder_custom_fields', array( $this, 'register_fields' ) );

		// Load custom modules.
		add_action( 'init', array( $this, 'load_modules' ) );
	}

	/**
	 * Get instance
	 *
	 * @return PPW_Beaver_Loader
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			// Use static instead of self due to the inheritance later.
			// For example: ChildSC extends this class, when we call get_instance
			// it will return the object of child class. On the other hand, self function
			// will return the object of base class.
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Load modules
	 */
	public function load_modules() {
		require_once __DIR__ . '/modules/ppw-individual-page/class-ppw-module.php';
	}

	/**
	 * Register custom fields.
	 *
	 * @param array $fields Fields.
	 *
	 * @return array Fields.
	 */
	public function register_fields( $fields ) {
		$fields['input-number'] = PPW_DIR_PATH . 'includes/addons/beaver-builder/fields/input-number.php';
		return $fields;
	}
}
