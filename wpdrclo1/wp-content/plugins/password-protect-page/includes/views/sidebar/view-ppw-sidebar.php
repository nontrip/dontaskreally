<?php
$current_user = wp_get_current_user();
$user_meta    = get_user_meta( get_current_user_id(), PPW_Constants::USER_SUBSCRIBE );
?>
<div class="ppw_wrap_subscribe_form">
	<form id="ppw_subscribe_form" class="ppw_subscribe_form <?php echo empty( $user_meta ) ? '' : 'ppw_subscribe_hidden'; ?>">
		<h3><?php echo esc_html( 'Feeling lucky?', 'password-protect-page' ); ?></h3>
		<p><?php echo esc_html( 'Be the first to get our latest updates and probably 1-year Pro license for free.', 'password-protect-page' ); ?></p>
		<input type="text" id="ppw_email_subscribe" class="input_email" placeholder="you@example.com" value="<?php echo esc_attr( $current_user->user_email ); ?>"/>
		<input type="hidden" id="ppw_subscribe_form_nonce" value="<?php echo wp_create_nonce( PPW_Constants::SUBSCRIBE_FORM_NONCE ); ?>">
		<p class="ppw_subscribe_error"><?php echo esc_html( 'Please enter your valid email.', 'password-protect-page' ); ?></p>
		<p><input type="submit" class="button button-primary" id="ppw_subscribe_button" value="<?php echo esc_html( 'Get Lucky', 'password-protect-page' ); ?>"/></p>
	</form>
	<div id="ppw_subscribe_form_success" class="ppw_subscribe_form <?php echo empty( $user_meta ) ? 'ppw_subscribe_hidden' : ''; ?>">
		<p><?php echo esc_html( 'Congrats! You\'ve subscribed to our newsletter and now stand a chance to win our 1-year Pro license for free.', 'password-protect-page' ); ?></p>
		<p><?php echo esc_html( 'Stay tuned for our updates :)', 'password-protect-page' ); ?></p>
	</div>
	<div class="ppw_wrap_al">
		<h3>Go Pro with many more powerful features</h3>
		<a target="_blank" rel="noopener" href="http://bit.ly/ppwp-lsb-pro">
			<img src="<?php echo PPW_DIR_URL; ?>admin/images/ppwp-pro-banner.png"/>
		</a>
	</div>
</div>
