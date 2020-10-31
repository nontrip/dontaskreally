<tr class="ppwp_free_version">
	<td>
		<label class="pda_switch" for="ppwp_free_auto_protect_all_child_pages">
			<input type="checkbox" id="ppwp_free_auto_protect_all_child_pages"/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Password Protect Child Pages', PPW_Constants::DOMAIN ); ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="http://bit.ly/ppwp-lsb-pro">
						<span class="dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Available in Pro version only', 'password-protect-page' ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo _e( 'Automatically protect all child pages once their parent is protected.', PPW_Constants::DOMAIN ) ?>
		</p>
	</td>
</tr>
