<?php if ( is_plugin_active( 'w3-total-cache/w3-total-cache.php' ) ) {
	$free_cache = new PPW_Cache_Services();
	$has_config = $free_cache->check_has_config_w3_total_cache();
	?>
	<tr>
		<td>
			<span class="dashicons <?php echo $has_config ? 'dashicons-yes ppwp-custom-dashicons-yes' : 'dashicons-no-alt ppwp-custom-dashicons-no'; ?>"></span>
		</td>
		<td>
			<p>
				<label><?php echo esc_html__( 'W3 Total Cache', PPW_Constants::DOMAIN ) ?></label>
				<?php
				if ( ! $has_config ) {
					_e( 'Please <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/password-protect-wordpress-caching-plugins/#w3-total-cache">refer to this guide</a> on how to integrate our plugin with W3 Total Cache', PPW_Constants::DOMAIN );
				} else {
					_e( 'Our plugin\'s working correctly with W3 Total Cache now', PPW_Constants::DOMAIN );
				}
				?>
			</p>
		</td>
	</tr>
<?php } ?>
