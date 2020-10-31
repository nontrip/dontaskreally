<?php
$css        = '';
$image      = supro_get_option( 'page_header_background' );
$parallax   = supro_get_option( 'page_header_parallax' );
$text_color = supro_get_option( 'page_header_text_color' );

$image_tablet = supro_get_option( 'page_header_background_tablet' );
$image_mobile = supro_get_option( 'page_header_background_mobile' );

if ( get_post_meta( get_the_ID(), 'page_header_custom_layout', true ) ) {
	if ( $custom_bg = get_post_meta( get_the_ID(), 'page_header_bg', true ) ) {
		$image = wp_get_attachment_url( $custom_bg );
	}

	if ( $custom_bg_image_tablet = get_post_meta( get_the_ID(), 'page_header_bg_tablet', true ) ) {
		$image_tablet = wp_get_attachment_url( $custom_bg_image_tablet );
	}

	if ( $custom_bg_image_mobile = get_post_meta( get_the_ID(), 'page_header_bg_mobile', true ) ) {
		$image_mobile = wp_get_attachment_url( $custom_bg_image_mobile );
	}

	$text_color = get_post_meta( get_the_ID(), 'text_color', true );
	$parallax   = intval( get_post_meta( get_the_ID(), 'parallax', true ) );
}

if ( ! $image ) {
	$css .= ' no-bg';
} else {
	if ( intval( $parallax ) ) {
		$css .= ' parallax';
	}
}

if ( ! $image_tablet && ! $image ) {
	$css .= ' no-bg-tablet';
}

if ( ! $image_mobile && ! $image_tablet && ! $image ) {
	$css .= ' no-bg-mobile';
}

$css .= ' text-' . $text_color;

?>
<div class="page-header page-header-default text-center <?php echo esc_attr( $css ); ?>">
	<div class="feature-image"></div>
	<div class="container">
		<?php
		the_archive_title( '<h1>', '</h1>' );
		supro_get_breadcrumbs();
		?>
	</div>
</div>