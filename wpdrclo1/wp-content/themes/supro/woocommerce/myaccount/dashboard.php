<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account-dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woothemes.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="row">
	<div class="myaccount-sidebar col-lg-3 col-md-4 col-sm-12 col-xs-12">
		<?php
		$user = get_user_by( 'ID', get_current_user_id() );
		if ( $user ) {
			?>
			<ul>
				<li>
					<?php echo get_avatar( get_current_user_id(), 125 ); ?>
				</li>
				<li>
					<?php printf( '<span class="m-title">%s %s</span>', esc_html__( 'Hello!', 'supro' ), $user->display_name ); ?>
				</li>
				<li>
					<?php printf( '<span>%s:</span>%s', esc_html__( 'Full Name', 'supro' ), get_user_meta( get_current_user_id(), 'billing_first_name', true ) . ' ' . get_user_meta( get_current_user_id(), 'billing_last_name', true ) ); ?>
				</li>
				<li>
					<?php printf( '<span>%s:</span>%s', esc_html__( 'Email', 'supro' ), $user->user_email ); ?>
				</li>
				<li>
					<?php printf( '<span>%s:</span>%s', esc_html__( 'Phone', 'supro' ), get_user_meta( get_current_user_id(), 'billing_phone', true ) ); ?>
				</li>
				<li>
					<?php
					$country = get_user_meta( get_current_user_id(), 'billing_country', true );
					if ( $country && function_exists( 'WC' ) ) {
						$country = WC()->countries->countries[$country];
					}
					?>
					<?php printf( '<span>%s:</span>%s', esc_html__( 'Country', 'supro' ), $country ) ?>
				</li>
				<li>
					<?php printf( '<span>%s:</span>%s', esc_html__( 'Postcode', 'supro' ), get_user_meta( get_current_user_id(), 'billing_postcode', true ) ); ?>
				</li>
				<li>
					<?php printf( '<a href="%s" class="m-button">%s</a>', esc_url( wc_get_endpoint_url( 'edit-account' ) ), esc_html__( 'Edit Profile', 'supro' ) ); ?>
				</li>
			</ul>
		<?php } ?>
	</div>
	<div class="myaccount-content col-lg-9 col-md-8 col-sm-12 col-xs-12">
		<?php
		/**
		 * My Account dashboard.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_dashboard' );

		/**
		 * Deprecated woocommerce_before_my_account action.
		 *
		 * @deprecated 2.6.0
		 */
		do_action( 'woocommerce_before_my_account' );

		/**
		 * Deprecated woocommerce_after_my_account action.
		 *
		 * @deprecated 2.6.0
		 */
		do_action( 'woocommerce_after_my_account' );
		?>
	</div>
</div>


