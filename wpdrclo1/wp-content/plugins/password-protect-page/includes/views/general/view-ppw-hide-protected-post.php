<?php
$ppw_post_types   = ppw_core_get_post_type_for_hide_protect_content();
$link_description = sprintf( '<a target="_blank" rel="noopener" href="%s">Select which views to hide</a>', 'https://passwordprotectwp.com/docs/how-to-hide-password-protected-wordpress-content/' );
// translators: %s: Link to documentation.
$link_description_rss = sprintf( '<a target="_blank" rel="noopener" href="%s">show protected content in RSS feeds</a>', 'https://passwordprotectwp.com/docs/display-protected-content-rss-feed/' );

$description = sprintf( esc_html__( '%s your password protected content. Learn how to %s.', 'password-protect-page' ), $link_description, $link_description_rss );
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Protected Content Visibility', 'password-protect-page' ); ?></label>
			<?php echo wp_kses_post( $description ); ?>
		<p>Switch post types to customize their own visibility. Only Pages & Posts are available on Free version.</p>
		<select class="ppw_select_custom_post_type_edit" id="ppw_select_custom_post_type_edit">
			<?php
			foreach ( $ppw_post_types as $ppw_type ) {
				$ppw_disabled = apply_filters( PPW_Constants::HOOK_CUSTOM_OPTION_HIDE_PROTECT_CONTENT, 'page_post' === $ppw_type['value'] ? '' : 'disabled', $ppw_type['value'] );
				?>
				<option <?php echo esc_attr( $ppw_disabled ); ?>
						value="<?php echo esc_attr( $ppw_type['value'] ); ?>"><?php echo esc_attr( $ppw_type['label'] ); ?></option>
				<?php
			}
			?>
		</select>
		</p>
	</td>
</tr>
<?php
ppw_core_check_logic_before_render_ui( $ppw_post_types );
?>
