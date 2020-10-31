<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 7/30/19
 * Time: 20:34
 */

if ( ! class_exists( 'PPW_Options_Services' ) ) {

	class PPW_Options_Services {

		protected static $instance;

		private $prefix;

		public function __construct() {
			$this->prefix = 'ppw_pro';
		}

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new PPW_Options_Services();
			}

			return self::$instance;
		}

		public function add_flag( $flag ) {
			update_option( $this->prefix . '_' . $flag, 1 );
		}

		public function get_flag( $flag ) {
			return get_option( $this->prefix . '_' . $flag );
		}

		public function delete_flag( $flag ) {
			$option_name = $this->prefix . '_' . $flag;
			if ( is_multisite() ) {
				foreach ( get_sites() as $site ) {
					delete_blog_option( $site->blog_id, $option_name );
				}
			} else {
				delete_option( $option_name );
			}
		}
	}
}
