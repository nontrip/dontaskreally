<?php

if ( function_exists( 'wp_get_theme' ) ) {
	$curr_theme = wp_get_theme();
	if ( method_exists( $curr_theme, 'get' ) && 'Edubin' === $curr_theme->get( 'Name' ) ) {
		require_once( ABSPATH . WPINC . '/class-wp-customize-section.php' );
	}
}

require_once( ABSPATH . WPINC . '/class-wp-customize-control.php' );

class PPW_Text_Editor_Custom_Control extends WP_Customize_Control {
	/**
	 * @var string Control type
	 */
	public $type = 'editor';

	/**
	 * Render the content on the theme customizer page.
	 */
	public function render_content() {
		$input_id = $this->id;
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		</label>
		<input type="hidden" <?php echo $this->get_link(); ?> value="<?php echo esc_attr( $this->value() ); ?>">
		<?php
		wp_editor( $this->value(), $input_id, array(
			'textarea_name' => $input_id,
			'textarea_rows' => 3,
		) );
		do_action( 'admin_footer' );
		do_action( 'admin_print_footer_scripts' );
		?>
		<?php
	}
}
