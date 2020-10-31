<?php
/**
 * Handle ajax requests
 */

/**
 * Authenticate a user, confirming the login credentials are valid.
 */
function supro_login_authenticate() {
	$creds = $_POST['creds'];

	$user = wp_signon( $creds );

	if ( is_wp_error( $user ) ) {
		wp_send_json_error( $user->get_error_message() );
	} else {
		wp_send_json_success();
	}
}

add_action( 'wp_ajax_nopriv_supro_login_authenticate', 'supro_login_authenticate' );
