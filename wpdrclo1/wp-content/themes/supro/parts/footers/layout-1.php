<?php
$classes = array(
	'footer-layout',
	'footer-layout-' . supro_get_option( 'footer_layout' ),
	supro_get_option( 'footer_skin' ) . '-skin'
);

if ( ! intval( supro_get_option( 'footer_copyright' ) ) ) {
	$classes[] = 'no-footer-copyright';
}

$bg_color = supro_get_option( 'footer_background_color' );
$bg_image = supro_get_option( 'footer_background_image' );
$bg_h     = supro_get_option( 'footer_background_horizontal' );
$bg_v     = supro_get_option( 'footer_background_vertical' );
$bg_r     = supro_get_option( 'footer_background_repeat' );
$bg_a     = supro_get_option( 'footer_background_attachment' );
$bg_s     = supro_get_option( 'footer_background_size' );

$style = array(
	! empty( $bg_color ) ? 'background-color: ' . $bg_color . ';' : '',
	! empty( $bg_image ) ? 'background-image: url( ' . esc_url( $bg_image ) . ' );' : '',
	! empty( $bg_h ) ? 'background-position-x: ' . $bg_h . ';' : '',
	! empty( $bg_v ) ? 'background-position-y: ' . $bg_v . ';' : '',
	! empty( $bg_r ) ? 'background-repeat: ' . $bg_r . ';' : '',
	! empty( $bg_a ) ? 'background-attachment:' . $bg_a . ';' : '',
	! empty( $bg_s ) ? 'background-size: ' . $bg_s . ';': '',
);

?>
<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="<?php echo esc_attr( implode( '', $style ) ); ?>">
	<?php supro_footer_widgets(); ?>
	<?php supro_footer_copyright(); ?>
</nav>