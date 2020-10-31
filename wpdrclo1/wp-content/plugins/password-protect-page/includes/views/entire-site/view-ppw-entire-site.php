<form class="ppw_main_container" id="ppw_entire_site_form">
	<input type="hidden"
	       value="<?php echo wp_create_nonce( PPW_Constants::ENTIRE_SITE_FORM_NONCE ); ?>"
	       id="ppw_entire_site_form_nonce"/>
	<table class="ppwp_settings_table" cellpadding="4">
		<?php
		include PPW_DIR_PATH . 'includes/views/entire-site/view-ppw-set-password.php';
		include PPW_DIR_PATH . 'includes/views/entire-site/view-ppw-exclude-page.php';
		?>
	</table>
	<?php
	submit_button();
	?>
</form>
