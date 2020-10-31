<tr class="ppwp_free_version">
	<td>
		<label class="pda_switch" for="ppwp_free_remove_search_engine">
			<input type="checkbox" id="ppwp_free_remove_search_engine"/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Block Search Indexing', PPW_Constants::DOMAIN ); ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="http://bit.ly/ppwp-lsb-pro">
						<span class="dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Available in Pro version only', PPW_Constants::DOMAIN ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo _e( '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/#block-indexing">Prevent search engines from indexing</a> your password protected content.', PPW_Constants::DOMAIN ); ?>
		</p>
	</td>
</tr>

