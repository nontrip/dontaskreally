<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Supro
 */
if ( 'full-content' == supro_get_layout() ) {
	return;
}

$sidebar = 'blog-sidebar';

if ( supro_is_catalog() ) {
	$sidebar = 'catalog-sidebar';
}

if ( is_singular('product') && supro_get_option( 'single_product_layout' ) == '1' ) {
	$sidebar = 'product-sidebar';
}

?>
<aside id="primary-sidebar" class="widgets-area primary-sidebar <?php echo esc_attr( $sidebar ) ?> col-xs-12 col-sm-12 col-md-3">
	<?php
	/*
	 * supro_open_wrapper_catalog_content_sidebar -10
	 *
	 */
	do_action( 'supro_before_sidebar_content' );

	if (is_active_sidebar($sidebar)) {
		dynamic_sidebar($sidebar);
	}

	/*
	 * supro_open_wrapper_catalog_content_sidebar -100
	 *
	 */
	do_action( 'supro_after_sidebar_content' );

	?>
</aside><!-- #secondary -->
