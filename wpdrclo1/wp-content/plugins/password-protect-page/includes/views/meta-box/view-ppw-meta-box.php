<?php

add_meta_box(
	'ppw_add_meta_box',
	__( 'Password Protect WordPress', PPW_Constants::DOMAIN ),
	apply_filters( PPW_Constants::HOOK_FUNCTION_HANDLE_META_BOX, 'ppw_free_feature_set_password_in_meta_box' ),
	apply_filters( PPW_Constants::HOOK_META_BOX_POSITION, array( 'page', 'post' ) ),
	'side',
	'high'
);

/**
 * Function to render meta box set password for pages, posts in free version
 *
 * @param $post
 */
function ppw_free_feature_set_password_in_meta_box( $post ) {
	$raw_data           = get_post_meta( $post->ID, PPW_Constants::POST_PROTECTION_ROLES, true );
	$protected_roles    = ppw_free_fix_serialize_data( $raw_data );
	$multiple_passwords = get_post_meta( $post->ID, PPW_Constants::GLOBAL_PASSWORDS, true );
	$password           = '';
	if ( ! empty( $multiple_passwords ) && is_array( $multiple_passwords ) ) {
		$password                  = esc_textarea( implode( "\n", $multiple_passwords ) );
		$global_password           = implode( ' ', $multiple_passwords );
		$protected_roles['global'] = $global_password;
	}

	$have_password_roles = array_keys( array_filter( $protected_roles, function ( $val ) {
		return $val !== '';
	} ) );

	$no_have_password_roles = sizeof( $have_password_roles );

	$no_roles = $no_have_password_roles > 1 ? $no_have_password_roles . ' roles' : $no_have_password_roles . ' role';

	$roles = get_editable_roles();

	?>
	<div id="passwords-roles-map" class="ppwp-post-protection">
		<input type="hidden" id="ppw_meta_box_nonce"
		       value="<?php echo wp_create_nonce( PPW_Constants::META_BOX_NONCE ); ?>">
		<span id="post-protection-status"> Password protected by
                <span id="number_roles"><?php echo esc_html( $no_roles ); ?></span>
            </span><a href="#protection" class="edit-post-protection hide-if-no-js pup-tooltip" role="button"
		              style="display: inline;">
			<span class="roles" aria-hidden="true">Edit</span>
		</a>
		<div style="display: none" id="post-protection">
			<p id="all_roles_select">
				<?php foreach ( $have_password_roles as $key => $role ): ?>
					<span><?php echo esc_html( $role ); ?></span>
				<?php endforeach; ?>
			</p>
			<div>
				<?php foreach ( $protected_roles as $key => $value ): ?>
					<span style="display: none"
					      id="post-protection-password-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></span>
				<?php endforeach; ?>
			</div>
			<div>
				<label for="post-protection-role">Role</label>
				<select class="pda-selected-role-select2" name="post-protection-role" id="is_role_selected">
					<option value="global">global</option>
					<?php foreach ( $roles as $role_name => $role_info ): ?>
						<option <?php echo esc_attr( in_array( $role_name, [] ) ? 'selected="selected"' : '' ); ?>" value="<?php echo esc_attr( $role_name ); ?>">
						<?php echo esc_html( $role_name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<!-- Region Data Save in HTML Tag START-->
			<?php foreach ( $roles as $role_name => $role_info ): ?>
				<?php $value = array_key_exists( $role_name, $protected_roles ) ? $protected_roles[ $role_name ] : ""; ?>
				<input type="hidden" id="<?php echo esc_attr( $role_name ); ?>"
				       name="data[<?php echo esc_attr( $role_name ); ?>]"
				       value="<?php echo esc_attr( $value ); ?>"/>
			<?php endforeach; ?>
			<!-- Region Data Save in HTML Tag END-->

			<div class="ppwp_wrap_role_password">
				<label id="label-password-post">Passwords</label>
				<input autocomplete="off" type="text" class="password post-protection-password"
				       placeholder="Enter password"
				       name="post-protection-password"
				       id="post-protection-password"/>
				<textarea rows="3" id="ppwp_multiple_password" class="ppwp_multiple_password"
				          placeholder="<?php echo esc_attr( 'Enter one password per line', 'wp-protect-password' ); ?>"><?php echo esc_html( $password, 'wp-protect-password' ) ?></textarea>
			</div>
			<p class="ppwp-wrap-submit-hide">
				<input type="hidden" value="<?php echo esc_attr( $post->ID ); ?>" id="id_page_post">
				<button href="#protection" id="save_password" class="ppwp-button-submit">Submit</button>
				<a class="cancel-pda-protection-map button-cancel ppwp-button-hide">Hide</a>
			</p>
		</div>
	</div>
	<?php
	$asset_services = new PPW_Asset_Services( '', '' );
	$asset_services->load_assets_for_meta_box();
}
