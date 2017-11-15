<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
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
 * @version 2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! wc_coupons_enabled() ) {
	return;
}

// if ( empty( WC()->cart->applied_coupons ) ) {
// 	$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
// 	wc_print_notice( $info_message, 'notice' );
// }
?>

<form class="checkout_coupon co-section u-pb0" method="post" style="display:none">
	<div class="row">
	 	<div class="col-md-15 form-row">
			<input type="text" name="coupon_code" class="input-text u-mb2" placeholder="<?php _e('Enter Promo Code', 'maison-tina') ?>" id="coupon_code" value="" />
		</div>
		<div class="col-md-5 form-row">
			<input type="submit" class="button u-mb2" name="apply_coupon" value="<?php _e('Apply', 'maison-tina') ?>" />
		</div>
	</div>
</form>
