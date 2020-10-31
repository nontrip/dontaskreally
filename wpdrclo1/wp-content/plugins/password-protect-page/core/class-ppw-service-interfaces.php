<?php
if ( ! interface_exists( 'PPW_Service_Interfaces' ) ) {
	interface PPW_Service_Interfaces {

		/**
		 * Check content is protected
		 *
		 * @param $post_id
		 *
		 * @return mixed
		 */
		public function is_protected_content( $post_id );

		/**
		 * Check password is valid
		 *
		 * @param $password
		 * @param $post_id
		 * @param $current_roles
		 *
		 * @return mixed
		 */
		public function is_valid_password( $password, $post_id, $current_roles );

		/**
		 * Set password to cookie
		 *
		 * @param $password
		 * @param $cookie_name
		 *
		 * @return mixed
		 */
		public function set_password_to_cookie( $password, $cookie_name );

		/**
		 * Check whether the current cookie is valid
		 *
		 * @param $post_id
		 * @param $passwords
		 * @param $cookie_name
		 *
		 * @return mixed
		 */
		public function is_valid_cookie( $post_id, $passwords, $cookie_name );

		/**
		 * Redirect after enter password
		 *
		 * @param $is_valid
		 *
		 * @return mixed
		 */
		public function handle_redirect_after_enter_password( $is_valid );
	}
}
