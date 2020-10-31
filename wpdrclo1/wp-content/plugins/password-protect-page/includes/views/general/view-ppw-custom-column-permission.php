<?php
$post_types = ppw_core_get_all_post_types();
unset( $post_types['post'] );
unset( $post_types['page'] );
?>
<tr class="ppwp_free_version">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Post Type Protection', PPW_Constants::DOMAIN ); ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="http://bit.ly/ppwp-lsb-pro">
						<span class="dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Available in Pro version only', PPW_Constants::DOMAIN ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo _e( '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/#cpt">Select which custom post types</a> you want to password protect. Default: Pages & Posts.', PPW_Constants::DOMAIN ); ?>
		</p>
		<div class="ppw_wrap_select_protection_selected">
			<div class="ppw_wrap_protection_selected">
				<span class="ppw_protection_selected">Pages</span>
				<span class="ppw_protection_selected">Posts</span>
			</div>
			<select multiple="multiple" class="ppwp_select2">
				<?php foreach ( $post_types as $post_type ): ?>
					<option disabled="disabled" <?php echo 'post' === $post_type->name || 'page' === $post_type->name ? 'selected="selected"' : '' ?>
							value="<?php echo esc_attr( $post_type->name ) ?>"><?php echo esc_html__( $post_type->label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</td>
</tr>
