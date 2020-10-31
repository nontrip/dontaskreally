<?php
$password_cookie_expired = ppw_core_get_setting_type_string( PPW_Constants::COOKIE_EXPIRED );
$time                    = 7;
$units                   = 'days';
$max                     = PPW_Constants::MAX_COOKIE_EXPIRED;
if ( ! empty( $password_cookie_expired ) ) {
	$tmp = explode( ' ', $password_cookie_expired );
	if ( count( $tmp ) === 2 ) {
		$time  = (int) $tmp[0];
		$units = $tmp[1];
		if ( 'hours' === $units ) {
			$max = $max * 24;
		} elseif ( 'minutes' === $units ) {
			$max = $max * 24 * 60;
		}
	}
}
?>
<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Cookie Expiration Time', PPW_Constants::DOMAIN ); ?></label>
			<?php echo _e( 'By default, users won’t have to re-enter passwords until its cookie expires. You can also <a target="_blank" href="https://passwordprotectwp.com/docs/settings/#cookies">use session cookies</a> to log users out right after they close the browser.', PPW_Constants::DOMAIN ); ?>
		</p>
		<input required value="<?php echo esc_attr( $time ); ?>" class="wpp_time_number" type="number"
		       id="wpp_password_cookie_times" min="1" max="<?php echo esc_attr( $max ); ?>"/>
		<select id="wpp_password_cookie_units" class="wpp_password_cookie_units">
			<option value="days" <?php if ( 'days' === $units ) {
				echo 'selected';
			} ?>><?php echo esc_html__( 'Days', PPW_Constants::DOMAIN ); ?></option>
			<option value="hours" <?php if ( 'hours' === $units ) {
				echo 'selected';
			} ?> ><?php echo esc_html__( 'Hours', PPW_Constants::DOMAIN ) ?>
			</option>
			<option value="minutes" <?php if ( 'minutes' === $units ) {
				echo 'selected';
			} ?> ><?php echo esc_html__( 'Minutes', PPW_Constants::DOMAIN ) ?>
			</option>
		</select>
	</td>
</tr>
