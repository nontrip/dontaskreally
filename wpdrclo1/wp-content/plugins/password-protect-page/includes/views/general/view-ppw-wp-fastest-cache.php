<?php if ( is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) { ?>
	<tr>
		<td>
			<span class="dashicons dashicons-yes ppwp-custom-dashicons-yes"></span>
		</td>
		<td>
			<p>
				<label><?php echo esc_html__( 'WP Fastest Cache', PPW_Constants::DOMAIN ) ?></label>
				<?php echo _e( 'Our plugin\'s already working correctly with WP Fastest Cache without any other configurations', PPW_Constants::DOMAIN ) ?>
			</p>
		</td>
	</tr>
<?php } ?>
