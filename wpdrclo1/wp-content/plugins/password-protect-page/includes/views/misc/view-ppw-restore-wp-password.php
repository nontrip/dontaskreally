<?php
global $password_recovery_service;

$num_wp_passwords = PPW_Repository_Passwords::get_instance()->count_wp_post_passwords();
$is_running       = $password_recovery_service->is_running();

?>
<tr>
	<td>
		<span class="feature-input"></span>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Restore Default WordPress Passwords', 'password-protect-page' ); ?></label>
			<a target="_blank" rel="noopener noreferrer"
			   href="https://passwordprotectwp.com/docs/password-migration/#backup">Restore all your backup
				passwords</a> to maintain your content's protection status after plugin deactivation.
			<br>
			The process runs in the background. You will get a notification once it’s completed.
		<p>
			<?php
			if ( $is_running ) {
				echo __( 'Restoring ', 'password-protect-page' ) . '<b>' . $num_wp_passwords . '</b>' . __( ' backup password(s)...', 'password-protect-page' );
			} else {
				echo __( 'There are ', 'password-protect-page' ) . '<b>' . $num_wp_passwords . '</b>' . __( ' backup password(s).', 'password-protect-page' );
			}
			?>
		</p>
		</p>
		<p>
			<input id="ppw-restore-passwords" <?php echo ! $num_wp_passwords || $is_running ? 'disabled="true"' : '' ?>
			       type="button"
			       class="button button-primary"
			       value="Restore Now"
			>
			<div class="ppw-warning">
				<strong><?php echo __( 'Warning', 'password-protect-page' ) ?></strong>: <?php echo __( 'Do not restore default WordPress
				passwords unless you are to deactivate our plugin', 'password-protect-page' ) ?>
			</div>
		</p>
	</td>
</tr>
