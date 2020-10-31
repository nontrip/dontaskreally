<?php
$roles = get_editable_roles();
?>
<tr class="ppwp_free_version">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label>
				<?php echo esc_html__( 'Whitelisted Roles', PPW_Constants::DOMAIN ) ?>
				<span class="ppwp_upgrade_advice">
					<a rel="noopener" target="_blank" href="http://bit.ly/ppwp-lsb-pro">
						<span class="dashicons dashicons-lock">
							<span class="ppwp_upgrade_tooltip"><?php echo esc_html__( 'Available in Pro version only', PPW_Constants::DOMAIN ) ?></span>
						</span>
					</a>
				</span>
			</label>
			<?php echo _e( 'Select user roles who can access all protected content without having to enter passwords.', PPW_Constants::DOMAIN ) ?>
		</p>
		<select id="wpp_free_whitelist_roles">
			<option value="blank"><?php echo esc_html__( 'No one', PPW_Constants::DOMAIN ) ?></option>
			<option disabled value="admin_users"><?php echo esc_html__( 'Admin users', PPW_Constants::DOMAIN ) ?></option>
			<option disabled value="author"><?php echo esc_html__( 'The post\'s author', PPW_Constants::DOMAIN ) ?></option>
			<option disabled value="logged_users"><?php echo esc_html__( 'Logged-in users', PPW_Constants::DOMAIN ) ?></option>
			<option disabled value="custom_roles"><?php echo esc_html__( 'Choose custom roles', PPW_Constants::DOMAIN ) ?></option>
		</select>
	</td>
</tr>
<tr id="wpp_free_roles_access" class="wpp_hide_role_access ppwp_free_version">
	<td></td>
	<td><p><?php echo esc_html__( 'Grant access to these user roles only', PPW_Constants::DOMAIN ) ?></p>
		<select multiple="multiple" class="wpp_roles_select ppwp_select2">
			<?php foreach ( $roles as $role_name => $role_info ) { ?>
				<option><?php echo $role_name ?></option>
			<?php } ?>
		</select>
	</td>
</tr>
