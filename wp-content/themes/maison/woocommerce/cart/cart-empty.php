<?php
/**
 * Empty cart page
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

?>

<p class="cart-empty">
	You have nothing in your shopping cart.
	<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<span class="return-to-shop">
			<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				Continue Shopping.
			</a>
		</span>
	<?php endif; ?>
</p>

<?php do_action( 'woocommerce_cart_is_empty' ); ?>
