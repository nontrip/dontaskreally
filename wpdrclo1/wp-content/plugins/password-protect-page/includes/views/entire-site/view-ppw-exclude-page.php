<?php
$all_page_post = ppw_free_get_all_page_post();
?>
<tr class="ppwp_free_version ppwp_logic_show_input_password <?php echo esc_attr( $is_display ); ?>">
	<td></td>
	<td class="ppwp_set_height_for_password_entire_site">
		<div class="ppwp_wrap_new_password">
			<label class="pda_switch" for="ppwp_free_switch_exclude_page">
				<input type="checkbox" id="ppwp_free_switch_exclude_page"/>
				<span class="pda-slider round"></span>
			</label>
			<span class="ppwp-set-new-password-text">
				Exclude these pages and posts from site-wide protection.
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="http://bit.ly/ppwp-lsb-pro">
						<span class="ppwp_dashicons dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Available in Pro version only', PPW_Constants::DOMAIN ) ?></span>
						</span>
					</a>
				</span>	
			</span>
		</div>
		<div class="ppwp_free_wrap_select_exclude_page ppwp-hidden-password">
			<select multiple="multiple" class="ppwp_select2">
				<option value="ppwp_home_page">Home Page</option>
				<?php foreach ( $all_page_post as $page ) { ?>
					<option><?php echo esc_html( $page->post_title ); ?></option>
				<?php } ?>
			</select>
		</div>
	</td>
</tr>
