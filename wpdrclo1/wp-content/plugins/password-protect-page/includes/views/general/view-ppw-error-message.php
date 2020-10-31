<tr class="ppwp_free_version">
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Error Message', PPW_Constants::DOMAIN ) ?></label>
			<?php echo _e( 'Customize the error message when users enter wrong passwords.<em> Available in Pro version only.</em>', PPW_Constants::DOMAIN ) ?>
		</p>
		<span>
            <input type="text"
                   value="<?php echo esc_html( PPW_Constants::DEFAULT_WRONG_PASSWORD_MESSAGE ); ?>"/>
        </span>
	</td>
</tr>
