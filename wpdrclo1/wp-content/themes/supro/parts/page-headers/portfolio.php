<?php
$css        = '';
$image      = supro_get_option( 'portfolio_page_header_background' );
$parallax   = supro_get_option( 'portfolio_page_header_parallax' );
$text_color = supro_get_option( 'portfolio_page_header_text_color' );

$image_tablet = supro_get_option( 'portfolio_page_header_background_tablet' );
$image_mobile = supro_get_option( 'portfolio_page_header_background_mobile' );

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
<div class="page-header page-header-portfolio text-center <?php echo esc_attr( $css ); ?>">
	<div class="feature-image"></div>
	<div class="container">
		<?php
		the_archive_title( '<h1>', '</h1>' );
		supro_get_breadcrumbs();
		?>
	</div>
</div>