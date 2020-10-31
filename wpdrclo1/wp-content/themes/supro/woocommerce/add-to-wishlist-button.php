<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 3.0.0
 */

/**
 * Template variables:
 *
 * @var $base_url string Current page url
 * @var $wishlist_url string Url to wishlist page
 * @var $exists bool Whether current product is already in wishlist
 * @var $show_exists bool Whether to show already in wishlist link on multi wishlist
 * @var $show_count bool Whether to show count of times item was added to wishlist
 * @var $product_id int Current product id
 * @var $parent_product_id int Parent for current product
 * @var $product_type string Current product type
 * @var $label string Button label
 * @var $browse_wishlist_text string Browse wishlist text
 * @var $already_in_wishslist_text string Already in wishlist text
 * @var $product_added_text string Product added text
 * @var $icon string Icon for Add to Wishlist button
 * @var $link_classes string Classed for Add to Wishlist button
 * @var $available_multi_wishlist bool Whether add to wishlist is available or not
 * @var $disable_wishlist bool Whether wishlist is disabled or not
 * @var $template_part string Template part
 * @var $container_classes string Container classes
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product;
$product = $product ? $product : wc_get_product($product_id);
$product_title = $product ?  $product->get_title() : '';
?>

<div class="yith-wcwl-add-button">
    <a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id, $base_url ) ) ?>"
       rel="nofollow"
       data-product-id="<?php echo esc_attr( $product_id ) ?>"
       data-product-title="<?php echo esc_attr( $product_title ); ?>"
       data-product-type="<?php echo esc_attr( $product_type ) ?>"
       data-original-product-id="<?php echo esc_attr( $parent_product_id ) ?>"
       class="<?php echo esc_attr( $link_classes ) ?>"
       data-title="<?php echo esc_attr( apply_filters( 'yith_wcwl_add_to_wishlist_title', $label ) ) ?>"
       title="<?php esc_attr_e( 'Save', 'supro' ) ?>"
       data-rel="tooltip"
    >
		<?php echo ! empty( $icon ) ? $icon : '' ?>
        <span class="indent-text"><?php echo ! empty( $label ) ? $label : '' ?></span>
    </a>
    <span class="ajax-loading" style="visibility:hidden">
        <span class="fa-spin loading-icon"></span>
    </span>
</div>