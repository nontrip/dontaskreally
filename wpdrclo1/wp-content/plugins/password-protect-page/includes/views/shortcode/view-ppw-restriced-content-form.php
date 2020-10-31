<div class="[PPW_FORM_CLASS] ppw-pcp-container" id="[PPW_FORM_ID]">
	<form method="post" autocomplete="off" action="[PPW_CURRENT_URL]" target="_top" class="post-password-form ppw-form ppw-pcp-password-form" data-submit="[PPW_AUTH]">
		<h3 class="ppw-headline ppw-pcp-pf-headline">[PPWP_FORM_HEADLINE]</h3>
		<div class="ppw-description ppw-pcp-pf-desc">[PPWP_FORM_INSTRUCTIONS]</div>
		<p class="ppw-input">
			<label>[PPWP_FORM_PASSWORD_LABEL] <input placeholder="[PPW_PLACEHOLDER]" type="password" tabindex="1" name="[PPW_AUTH]" class="ppw-password-input ppw-pcp-pf-password-input" autocomplete="new-password">
			</label>
			<input class="ppw-page" type="hidden" value="[PPW_PAGE]" />
			<input name="submit" type="submit" class="ppw-submit ppw-pcp-pf-submit-btn" value="[PPW_BUTTON_LABEL]"/>
		</p>
		<div class="ppw-error ppw-pcp-pf-error-msg" style="color: <?php echo esc_attr( PPW_Constants::PPW_ERROR_MESSAGE_COLOR ); ?>">
			[PPW_ERROR_MESSAGE]
		</div>
	</form>
</div>
