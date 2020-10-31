<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'supro' ); ?></th>
			<th class="product-price hidden-xs"><?php esc_html_e( 'Price', 'supro' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'supro' ); ?></th>
			<th class="product-subtotal hidden-xs"><?php esc_html_e( 'Total', 'supro' ); ?></th>
			<th class="product-remove hidden-xs">&nbsp;</th>
		</tr>
		</thead>
		<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		$i = 1;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			global $supro_woocommerce;
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="woocommerce-cart-form__cart-item cart-item-<?php echo esc_attr( $i ); ?> <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'supro' ); ?>">
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							if ( ! $product_permalink ) {
								echo wp_kses_post( $thumbnail );
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
							} else {
								printf( '<a href="%s" class="product-url">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="product-url">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}

							// Meta data.
							echo wp_kses_post( $supro_woocommerce->supro_get_item_data( $cart_item ) ); // PHPCS: XSS ok.
							// echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

							// Price on mobile
							echo '<p class="price hidden-lg hidden-md hidden-sm">' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) . '</p>';

							// Remove product on mobile
							echo apply_filters(
							'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove hidden-lg hidden-md hidden-sm" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'supro' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() ),
								esc_html__( 'Delete', 'supro' )
								), $cart_item_key
							);

							// Backorder notification.
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'supro' ) . '</p>', $product_id ) );
							}
						?>
					</td>


					<td class="product-price hidden-xs" data-title="<?php esc_attr_e( 'Price', 'supro' ); ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'supro' ); ?>"><?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
									'min_value'    => '0',
									'product_name' => $_product->get_name(),
								), $_product, false
							);
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?></td>

					<td class="product-subtotal hidden-xs" data-title="<?php esc_attr_e( 'Total', 'supro' ); ?>">
						<?php
						echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-remove hidden-xs">
						<?php
						// @codingStandardsIgnoreLine
						echo apply_filters(
							'woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="icon-cross2"></i></a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'supro' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key
						);
						?>
					</td>
				</tr>
				<?php
			}

			$i ++;
		}
		?>

		<?php do_action( 'woocommerce_cart_contents' ); ?>

		<tr>
			<td colspan="6" class="actions">
				<div class="cart-actions">
					<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ); ?>" class="button btn-shop"><?php esc_html_e( 'Continue Shopping', 'supro' ); ?>
					</a>
					<button type="submit" class="button btn-update" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'supro' ); ?>"><?php esc_html_e( 'Update cart', 'supro' ); ?></button>
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Discount Code', 'supro' ); ?></label>

							<div class="coupon-field">
								<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Enter your code', 'supro' ); ?>" />
								<input type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'supro' ); ?>" />
							</div>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>
				</div>

				<?php do_action( 'woocommerce_cart_actions' ); ?>

				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</td>
		</tr>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
