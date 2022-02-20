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
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<div class="u-tac mobile-only u-mb2">
	<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
</div>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="table-container--responsive u-mb1">
		<table class="shop_table shop_table--cart shop_table_responsive cart woocommerce-cart-form__contents table--full" cellspacing="0">
			<thead>
				<tr>
					<!--<th class="product-thumbnail">&nbsp;</th>-->
					<th class="product-name u-tal u-fwl u-fs12" colspan="2"><?php _e('Item', 'maison-tina') ?></th>
					<th class="product-quantity u-tac u-fwl u-fs12 desktop-only"><?php _e('Quantity', 'maison-tina') ?></th>
					<th class="product-price u-tac u-fwl u-fs12 desktop-only"><?php _e('Price', 'maison-tina') ?></th>
					<th class="product-subtotal u-tar u-fwl u-fs12 desktop-only"><?php _e( 'Total', 'woocommerce' ); ?></th>
					<!-- <th class="product-remove">&nbsp;</th> -->
				</tr>
			</thead>
			<tbody>
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

							<td class="product-thumbnail">
								<?php
									$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('shop_catalog', array('class' => 'responsive')), $cart_item, $cart_item_key );

									if ( ! $product_permalink ) {
										echo $thumbnail;
									} else {
										printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
									}
								?>
							</td>

							<td class="product-name u-cdark u-tal" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
								<?php
									echo '<div class="u-fs16 u-ttu u-cblack">';
									if ( ! $product_permalink ) {
										echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
									} else {
										echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
									}
									echo '</div>';

									// Meta data
									echo WC()->cart->get_item_data( $cart_item );

									// Backorder notification
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>';
									}

									echo '<div class="desktop-only u-mt1 u-lhs">';
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
										esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() ),
										__( 'Remove this item', 'woocommerce' )
									), $cart_item_key );
									echo '</div>';
								?>
							</td>

							<td class="product-quantity u-tac" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
											'min_value'   => '0',
										), $_product, false );
									}

									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</td>

							<td class="product-price u-tac" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
								<span class="mobile-only"><?php echo $cart_item['quantity']; ?> &times;</span>
								<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
								?>
							</td>

							<td class="product-subtotal u-tar desktop-only" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
								<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
								?>
							</td>

							<td class="product-remove mobile-only u-cblack u-tar u-lhs">
								<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
										esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() ),
										__( 'Remove this item', 'woocommerce' )
									), $cart_item_key );
								?>
							</td>
						</tr>
						<?php
					}
				}
				?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<tr>
					<td colspan="3" class="product-coupon">
						<?php if ( wc_coupons_enabled() ) { ?>
							<div class="coupon">
								<input type="text" name="coupon_code" class="input-text control u-wa u-mb1" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
								<input type="submit" class="button btn btn-medium btn-sec u-wa u-mb1" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>" />
								<?php do_action( 'woocommerce_cart_coupon' ); ?>
							</div>
						<?php } ?>
					</td>
					<td colspan="2" class="product-actions u-tar">
						<input type="submit" class="button btn btn-medium btn-sec u-wa u-mb1" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" />

						<?php do_action( 'woocommerce_cart_actions' ); ?>

						<?php wp_nonce_field( 'woocommerce-cart' ); ?>
					</td>
				</tr>

				<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			</tbody>
		</table>
	</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<div class="cart-collaterals">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
