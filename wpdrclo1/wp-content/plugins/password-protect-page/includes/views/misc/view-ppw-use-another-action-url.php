<?php
$checked = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_CUSTOM_FORM_ACTION, PPW_Constants::MISC_OPTIONS ) ? 'checked' : '';
$message = array(
	'label'       => 'Use Custom Form Action',
	'description' => 'Enable this option when <a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/custom-login-page-compatibility/">the password protection doesn\'t work</a>, e.g. users get redirected to homepage or 404 error page.'
);

?>
<tr>
	<td>
		<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::USE_CUSTOM_FORM_ACTION ); ?>">
			<input type="checkbox"
			       id="<?php echo esc_attr( PPW_Constants::USE_CUSTOM_FORM_ACTION ); ?>" <?php echo esc_attr( $checked ); ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( $message['label'], PPW_Constants::USE_CUSTOM_FORM_ACTION ) ?></label>
			<?php echo _e( $message['description'], PPW_Constants::USE_CUSTOM_FORM_ACTION ) ?>
		</p>
	</td>
</tr>
