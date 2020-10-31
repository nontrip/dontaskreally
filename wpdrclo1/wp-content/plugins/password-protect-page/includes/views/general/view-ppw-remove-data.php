<?php
$remove_checked = ppw_core_get_setting_type_bool( PPW_Constants::REMOVE_DATA ) ? 'checked' : '';
$message        = apply_filters( PPW_Constants::HOOK_CUSTOM_TEXT_FEATURE_REMOVE_DATA, array(
	'label'       => 'Remove Data Upon Uninstall',
	'description' => 'Remove all your data created by Password Protect WordPress upon uninstall. You should <b>NOT</b> remove our Free when upgrading to our Pro version.'
) );
?>
<tr>
	<td>
		<label class="pda_switch" for="<?php echo esc_attr( PPW_Constants::REMOVE_DATA ); ?>">
			<input type="checkbox"
			       id="<?php echo esc_attr( PPW_Constants::REMOVE_DATA ); ?>" <?php echo esc_attr( $remove_checked ); ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( $message['label'], PPW_Constants::DOMAIN ) ?></label>
			<?php echo _e( $message['description'], PPW_Constants::DOMAIN ) ?>
		</p>
	</td>
</tr>
