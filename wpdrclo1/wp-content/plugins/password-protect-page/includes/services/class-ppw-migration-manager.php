<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/31/19
 * Time: 14:57
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'PPW_Default_PW_Manager_Services' ) ) {
	class PPW_Default_PW_Manager_Services extends PPW_Migration_Manager {
		/**
		 * Get module name.
		 *
		 * Retrieve the module name.
		 *
		 * @return string Module name.
		 * @since 1.7.0
		 * @access public
		 *
		 */
		public function get_name() {
			return 'default-pwd-migration';
		}

		public function get_action() {
			return 'ppw_default_pwd_migration';
		}

		public function get_plugin_name() {
			return 'ppw';
		}

		public function get_plugin_label() {
			return __( PPW_PLUGIN_NAME, 'password-protect-page' );
		}

		public function get_updater_label() {
			return sprintf( '<strong>%s </strong> &#8211;', __( 'Password Protect WordPress', 'password-protect-page' ) );
		}

		public function get_query_limit() {
			// TODO: Implement get_query_limit() method.
		}

		public function get_migrations_class() {
			return 'PPW_Default_PW_Migrations';
		}

		public function get_migration_label() {
			return sprintf( '<strong>%s </strong> &#8211;', __( 'PPWP Data Migration', 'password-protect-page' ) );
		}

		public function get_success_message() {
			return '<p>' . sprintf( __( '%s The <a href="https://passwordprotectwp.com/password-migration/" target="_blank" rel="noopener noreferrer">password migration process</a> is now complete. Thank you for your patience!', 'password-protect-page' ), $this->get_updater_label() ) . '</p>';
		}

	}
}
