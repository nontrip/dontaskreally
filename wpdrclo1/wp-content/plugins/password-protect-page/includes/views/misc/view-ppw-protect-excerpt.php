<?php
$checked = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::PROTECT_EXCERPT, PPW_Constants::MISC_OPTIONS ) ? 'checked' : '';
?>
<tr>
	<td>
		<label class="pda_switch" for="<?php echo PPW_Constants::PROTECT_EXCERPT; ?>">
			<input type="checkbox" id="<?php echo PPW_Constants::PROTECT_EXCERPT; ?>" <?php echo $checked; ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Show Post Excerpt', 'password-protect-page' ); ?></label>
			<?php echo _e( '<a target="_blank" href="https://passwordprotectwp.com/docs/display-featured-image-password-protected-excerpt/">Display the excerpt</a> of password protected posts. You can also <a target="_blank" href="https://passwordprotectwp.com/docs/display-featured-image-password-protected-excerpt/#customize-default">customize the default excerpt</a> using a custom code snippet.', 'password-protect-page' ) ?>
		</p>
	</td>
</tr>
