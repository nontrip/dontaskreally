<?php
if ( ! class_exists( 'PPW_Password_Subscribe' ) ) {
	class PPW_Password_Subscribe {

		/**
		 * Handle subscriber request(Call api to save data for subscriber)
		 *
		 * @param string $email email user request.
		 *
		 * @return array
		 */
		public function handle_subscribe_request( $email ) {
			$data     = array(
				'email'    => $email,
				'campaign' => array(
					'campaignId' => 'K0MtC',
				),
			);
			$args     = array(
				'body'        => json_encode( $data ),
				'timeout'     => '100',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking'    => true,
				'headers'     => array(
					'X-Auth-Token' => 'api-key ae824cfc3df1a2aa18e8a5419ec1c38b',
					'Content-Type' => 'application/json',
				),
			);
			$response = wp_remote_post(
				'https://api.getresponse.com/v3/contacts',
				$args
			);
			if ( is_wp_error( $response ) ) {
				return array(
					'error_message' => $response->get_error_message(),
				);
			} else {
				update_user_meta( get_current_user_id(), PPW_Constants::USER_SUBSCRIBE, true );

				return array(
					'data' => json_decode( wp_remote_retrieve_body( $response ) ),
				);
			}
		}
	}
}
