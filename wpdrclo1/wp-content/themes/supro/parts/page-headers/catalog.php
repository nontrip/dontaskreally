<?php
$breadcrumb = supro_get_option( 'catalog_page_header_breadcrumbs' );
$layout     = supro_get_option( 'catalog_page_header_layout' );
$banner     = intval( supro_get_option( 'catalog_page_header_banner' ) );
$ph_class   = $breadcrumb ? '' : 'no-breadcrumb';
$ph_class .= ' layout-' . $layout;
?>
<div id="page-header-catalog" class="page-header page-header-catalog <?php echo esc_attr( $ph_class ) ?>">
	<div class="page-header-wrapper">
		<div class="page-header-title">
			<?php
			the_archive_title( '<h1>', '</h1>' );
			supro_get_breadcrumbs();
			?>
		</div>
		<?php if ( $layout == '3' && $banner ) : ?>
			<div class="page-header-banner">
				<?php echo do_shortcode( wp_kses( supro_get_option( 'catalog_page_header_banner_shortcode' ), wp_kses_allowed_html( 'post' ) ) ); ?>
			</div>
		<?php endif; ?>
		<div class="page-header-shop-toolbar">
			<?php do_action( 'supro_page_header_shop_toolbar' ) ?>
		</div>
	</div>
</div>