<?php
$all_page_post = ppw_free_get_all_page_post();
?>
<tr class="ppwp_free_version">
	<td>
		<label class="pda_switch" for="ppwp_free_apply_password_for_pages_posts">
			<input type="checkbox" id="ppwp_free_apply_password_for_pages_posts"/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Password Protect Private Pages', PPW_Constants::DOMAIN ) ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="http://bit.ly/ppwp-lsb-pro">
						<span class="dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Available in Pro version only', PPW_Constants::DOMAIN ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo _e( 'Set the same password to protect the following pages and posts.', PPW_Constants::DOMAIN ) ?>
		</p>
	</td>
</tr>
<tr class="ppwp-free-pages-posts-set-password ppwp-hidden-password ppwp_free_version">
	<td></td>
	<td><p><?php echo esc_html__( 'Select your private pages or posts', PPW_Constants::DOMAIN ) ?></p>
		<select multiple="multiple" class="ppwp_select2">
			<?php foreach ( $all_page_post as $page ): ?>
				<option disabled="disabled"><?php echo esc_html( $page->post_title ) ?></option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>
<tr class="ppwp-free-pages-posts-set-password ppwp-hidden-password ppwp_free_version">
	<td></td>
	<td class="ppwp_wrap_set_new_password_for_pages_posts">
		<p><?php echo esc_html__( 'Set a password', PPW_Constants::DOMAIN ) ?></p>
		<input type="text" placeholder="Enter a password"/>
	</td>
</tr>
