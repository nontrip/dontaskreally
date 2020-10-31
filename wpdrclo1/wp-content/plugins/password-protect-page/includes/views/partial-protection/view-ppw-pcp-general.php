<?php
// translators: %s: Link to documentation.
$page_builder_link = sprintf(
	'<a target="_blank" rel="noopener" href="%s">work seamlessly with top page builders</a>',
	'https://passwordprotectwp.com/docs/protect-partial-content-page-builders'
);
$page_builder_desc = sprintf(
	'Alternatively, use our built-in blocks for popular page builders, e.g. %s and %s.',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/password-protect-partial-content-elementor/">Elementor</a>',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/protect-partial-content-page-builders/#bb">Beaver Builder</a>'
);
// translators: %s: Link to documentation.
$pcp_desc                   = sprintf(
	'To track Partial Content Protection (PCP) password usage, please get %s and use %s instead.',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/extensions/password-statistics/">Statistics addon</a>',
	'<a target="_blank" rel="noopener" href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/">PCP global passwords</a>'
);
$page                       = isset( $_GET['page'] ) ? $_GET['page'] : null;
$tab                        = isset( $_GET['tab'] ) ? $_GET['tab'] : null;
$message                    = 'Great! You’ve successfully copied the shortcode to clipboard.';
$use_shortcode_page_builder = ppw_core_get_setting_type_bool_by_option_name( PPW_Constants::USE_SHORTCODE_PAGE_BUILDER, PPW_Constants::SHORTCODE_OPTIONS ) ? 'checked' : '';
?>
<div class="ppw_main_container" id="ppw_shortcodes_form">
	<form id="wpp_shortcode_form" method="post">
		<table class="ppw-pcp-settings ppwp_settings_table" cellpadding="4">
			<tr>
				<td>
					<label class="pda_switch" for="<?php echo PPW_Constants::USE_SHORTCODE_PAGE_BUILDER; ?>">
						<input type="checkbox"
						       id="<?php echo PPW_Constants::USE_SHORTCODE_PAGE_BUILDER; ?>" <?php echo $use_shortcode_page_builder; ?>>
						<span class="pda-slider round"></span>
					</label>
				</td>
				<td>
					<p>
						<label><?php _e( 'Use Shortcode within Page Builder', 'password-protect-page' ) ?></label>
						<?php _e( 'Allow our shortcode to', 'password-protect-page' ) ?>
						<?php echo wp_kses_post( $page_builder_link ); ?><?php _e( ' without breaking the page structure.', 'password-protect-page' ) ?>
					</p>
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<hr>
				</td>
			</tr>
		</table>
	</form>
	<?php if ( PPW_Asset_Services::is_partial_protection_submenu( $page, $tab ) ) { ?>
		<div>
			<div>
				<h2 style="margin-top: 0;">[ppwp] Shortcode</h2>
				<p>
					<?php _e( 'Use the following shortcode to', 'password-protect-page' ) ?>
					<a target="_blank" rel="noopener"
					   href="https://passwordprotectwp.com/docs/password-protect-wordpress-content-sections/">
						<?php _e( 'lock parts of your content', 'password-protect-page' ) ?></a>.
					<?php echo wp_kses_post( $page_builder_desc ); ?>
				</p>
				<p><?php echo wp_kses_post( $pcp_desc ); ?></p>
				<div class="ppwp-shortcodes-wrap">
					<textarea
							onclick="ppwUtils.copy('ppwp-shortcode', '<?php echo esc_attr( $message, 'password-protect-page' ); ?>', '<?php echo 'Password Protect WordPress'; ?>')"
							id="ppwp-shortcode" style="width: 100%" rows="3" cols="50" readonly>[ppwp passwords="password1 password2" whitelisted_roles="administrator, editor"]&#13;&#10;Your protected content&#13;&#10;[/ppwp]</textarea>
				</div>
			</div>
			<div>
				<h2>Shortcode Attributes</h2>
				<p>Below are all attributes available with this shortcode. It's important to note that the shortcode is
					valid as long as it includes <b>at least</b> one of the <code>required*</code> attributes.</p>
				<div>
					<table class="ppw-shortcode-opt-table wp-list-table widefat fixed striped posts">
						<thead>
						<tr>
							<th>Attribute name</th>
							<th>Possible & Default values</th>
							<td></td>
						</thead>
						<tbody>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">passwords</code>
								<p class="description"><a
											href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/#define">Global
										inline passwords</a>, which are used to unlock the protected section</p>
							</td>
							<td>
								<ul>
									<li>Each password is case-sensitivity and no more than 100 characters, but doesn't
										contain [, ], ", ' and space characters
									</li>
									<li>Password(s) are separated by space(s)</li>
								</ul>
							</td>
							<td>required*</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">pwd</code>
								<p class="description">ID-based <a
											href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/#id">shortcode
										global passwords</a></p>
							</td>
							<td>
								<ul>
									<li>Available in PPWP Pro only</li>
									<li>ID(s) are separated by comma(s)</li>
								</ul>
							</td>
							<td>required*</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">pwd_label</code>
								<p class="description">Label-based <a
											href="https://passwordprotectwp.com/docs/manage-shortcode-global-passwords/#label">shortcode
										global passwords</a></p>
							</td>
							<td>
								<ul>
									<li>Available in PPWP Pro only</li>
									<li>Label(s) separated by comma(s)</li>
								</ul>
							</td>
							<td>required*</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">whitelisted_roles</code>
								<p class="description">Define who can access protected sections directly without
									entering a password</p>
							</td>
							<td>Options: administrator, editor, author, contributor, subscriber</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">hidden_form_text</code>
								<p class="description"><a
											href="https://passwordprotectwp.com/docs/protect-content-sections-single-password-form/">Hide
										password form</a> or display a text instead</p>
							</td>
							<td>
								<ul>
									<li>Available in PPWP Pro only</li>
									<li>Empty value or text</li>
									<li>Accept HTML tags</li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">on</code>
								<p class="description">Show protected content automatically at a set time until the “off” time</p>
							</td>
							<td>
								<ul>
								<li>Format: <code>Y-m-d h:i:sa</code></li>
									<li>Sample: 2020/10/20 14:00:00</li>
									<li>Without "off" attribute,  the content will be public since the “on” time </li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">off</code>
								<p class="description">Stop showing protected content without entering passwords</p>
							</td>
							<td>
								<ul>
									<li>Format: <code>Y-m-d h:i:sa</code></li>
									<li>Sample: 2020/10/30 14:00:00</li>
									<li>Require "on" attribute</li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">headline</code>
								<p class="description">Headline of the password form</p>
							</td>
							<td>
								<ul>
									<li>Default: <code>Restricted Content</code></li>
									<li>Accept HTML tags</li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">description</code>
								<p class="description">Description of the password form</p>
							</td>
							<td>
								<ul>
									<li>Default: <code>To view this protected content, enter the password below:</code>
									</li>
									<li>Accept HTML tags</li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">label</code>
								<p class="description">Label of the password field</p>
							</td>
							<td>Default: <code>Password:</code></td>
							<td>optional</td>
						</tr>
						<tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">placeholder</code>
								<p class="description">Placeholder of the password field</p>
							</td>
							<td>Default: <i>empty</i></td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">button</code>
								<p class="description">Button text of the password form</p>
							</td>
							<td>Default: <code>Enter</code></td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">error_msg</code>
								<p class="description">The message which is shown when users enter a wrong password</p>
							</td>
							<td>
								<ul>
									<li>Default: <code>Please enter the correct password!</code></li>
									<li>Accept HTML tags</li>
								</ul>
							</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">cookie</code>
								<p class="description">Set cookie expiration time</p>
							</td>
							<td>
								<ul>
									<li>Available in PPWP Pro only</li>
									<li>Count by hours</li>
								</ul>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">download_limit</code>
								<p class="description">Set the maximum number of times users can <a
											href=https://passwordprotectwp.com/docs/how-to-password-protect-files-in-content/#download-limit>download
										a file embedded into content</a></p>
							</td>
							<td>
								<ul>
									<li>Available in PPWP Pro only</li>
									<li>Count by clicks</li>
								</ul>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">class</code>
								<p class="description">Style the password form based on class</p>
							</td>
							<td>CSS class name(s) separated by space(s)</td>
							<td>optional</td>
						</tr>
						<tr>
							<td>
								<code class="ppw-shortcode-attr">id</code>
								<p class="description">Style the password form based on id</p>
							</td>
							<td>Default: <i>empty</i></td>
							<td>optional</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
