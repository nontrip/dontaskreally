<?php
$password_for_website = ppw_core_get_setting_entire_site_type_string( PPW_Constants::PASSWORD_ENTIRE_SITE );
$is_protected         = ppw_core_get_setting_entire_site_type_bool( PPW_Constants::IS_PROTECT_ENTIRE_SITE );
$is_display           = $is_protected ? '' : 'ppwp-hidden-password';
?>
<tr>
	<td>
		<?php if ( $is_protected ) { ?>
			<label class="pda_switch" for="ppwp_apply_password_for_entire_site">
				<input type="checkbox" id="ppwp_apply_password_for_entire_site" checked/>
				<span class="pda-slider round"></span>
			</label>
		<?php } else { ?>
			<label class="pda_switch" for="ppwp_apply_password_for_entire_site">
				<input type="checkbox" id="ppwp_apply_password_for_entire_site"/>
				<span class="pda-slider round"></span>
			</label>
		<?php } ?>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Password Protect Entire Site', PPW_Constants::DOMAIN ) ?></label>
			<?php echo sprintf( '%1$s <a href="%2$s">%3$s</a>.', __( 'Set passwords to protect your entire WordPress site. Customize password login form using', 'password-protect-page' ), admin_url( 'customize.php?autofocus[panel]=ppwp_sitewide' ), __( 'WordPress Customizer', 'password-protect-page' ) ) ?>
		</p>
	</td>
</tr>
<?php if ( ! empty( $password_for_website ) ) { ?>
	<tr class="ppwp_logic_show_input_password <?php echo esc_attr( $is_display ) ?>">
		<td></td>
		<td class="ppwp_set_height_for_password_entire_site">
			<p><?php _e( 'You’ve set a password to protect your site.', PPW_Constants::DOMAIN ) ?></p>
			<div class="ppwp_wrap_new_password_for_entire_site ppwp_wrap_new_password">
				<label class="pda_switch" for="ppwp_set_new_password_for_entire_site">
					<input type="checkbox" id="ppwp_set_new_password_for_entire_site"/>
					<span class="pda-slider round"></span>
				</label>
				<span class="ppwp-set-new-password-text">Set a new password</span>
			</div>
			<div class="ppwp_hidden_new_password_for_entire_site" id="ppwp_new_password_entire_site">
				<div class="ppwp_wrap_new_password">
					<label class="pda_switch"></label>
					<span class="ppwp-set-new-password-input">
                    <input required type="password" autocomplete="off" placeholder="Enter new password"
                           id="ppwp_password_for_entire_site" name="ppwp_password_for_entire_site">
                </span>
				</div>
			</div>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppwp_logic_show_input_password <?php echo esc_attr( $is_display ) ?>">
		<td></td>
		<td class="ppwp_text_after_enter_password_succes">
			<p><?php echo esc_html__( 'Set a password', PPW_Constants::DOMAIN ) ?></p>
			<input required type="password" autocomplete="off" placeholder="Enter a password"
			       id="ppwp_password_for_entire_site" name="ppwp_password_for_entire_site">
		</td>
	</tr>
<?php } ?>
