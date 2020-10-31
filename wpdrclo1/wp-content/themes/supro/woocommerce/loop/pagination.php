<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}

$catelog_nav_type = supro_get_option( 'shop_nav_type' );

$next = '<i class="icon-chevron-right"></i>';
$prev = '<i class="icon-chevron-left"></i>';
$paging_id = 'supro-woocommerce-pagination';
$paging_class = 'supro-woocommerce-pagination';
if ( $catelog_nav_type != 'numbers' ) {
	$paging_id = 'supro-shop-infinite-loading';
	$paging_class = 'infinite';
	$prev = '';
	$view_more = apply_filters( 'supro_catalog_view_more_text', esc_html__( 'DISCOVER MORE', 'supro' ) );

	$next = sprintf(
		'<span id="supro-products-loading" class="nav-previous-ajax">
			<span class="nav-text">%s</span>
			<span class="loading-icon">
				<span class="loading-text">%s</span>
				<span class="icon_loading supro-spin su-icon"></span>
			</span>
		</span>',
		$view_more,
		esc_html__( 'Loading', 'supro' )
	);
}
?>
<nav class="woocommerce-pagination <?php echo esc_attr( $paging_class )?>" id="<?php echo esc_attr( $paging_id ); ?>">
	<?php
	echo paginate_links( apply_filters( 'woocommerce_pagination_args', array(
		'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
		'format'       => '',
		'add_args'     => false,
		'current'      => max( 1, get_query_var( 'paged' ) ),
		'total'        => $wp_query->max_num_pages,
		'prev_text'    => $prev,
		'next_text'    => $next,
		'type'         => 'list',
		'end_size'     => 3,
		'mid_size'     => 3
	) ) );
	?>
</nav>
