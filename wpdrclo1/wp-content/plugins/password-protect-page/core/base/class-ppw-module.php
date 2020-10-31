<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/25/19
 * Time: 16:38
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PPW_Module' ) ) {
	abstract class PPW_Module {
		/**
		 * Get module name.
		 *
		 * Retrieve the module name.
		 *
		 * @since 1.7.0
		 * @access public
		 * @abstract
		 *
		 * @return string Module name.
		 */
		abstract public function get_name();

		/**
		 * Instance.
		 *
		 * Ensures only one instance of the module class is loaded or can be loaded.
		 *
		 * @since 1.7.0
		 * @access public
		 * @static
		 *
		 * @return Module An instance of the class.
		 */
		public static function instance() {
			$class_name = static::class_name();

			if ( empty( static::$_instances[ $class_name ] ) ) {
				static::$_instances[ $class_name ] = new static();
			}

			return static::$_instances[ $class_name ];
		}
	}
}
