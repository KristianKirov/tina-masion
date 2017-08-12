<?php
/**
 * Order Customer Details
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="woocommerce-customer-details">

	<div class="co-section">
		<h4 calss="co-section-title"><?php _e( 'Customer details', 'woocommerce' ); ?></h4>
		
		<table class="woocommerce-table woocommerce-table--customer-details shop_table shop_table--default customer_details">

			<?php if ( $order->get_customer_note() ) : ?>
				<tr>
					<th><?php _e( 'Note:', 'woocommerce' ); ?></th>
					<td><?php echo wptexturize( $order->get_customer_note() ); ?></td>
				</tr>
			<?php endif; ?>

			<?php if ( $order->get_billing_email() ) : ?>
				<tr>
					<th><?php _e( 'Email:', 'woocommerce' ); ?></th>
					<td><?php echo esc_html( $order->get_billing_email() ); ?></td>
				</tr>
			<?php endif; ?>

			<?php if ( $order->get_billing_phone() ) : ?>
				<tr>
					<th><?php _e( 'Phone:', 'woocommerce' ); ?></th>
					<td><?php echo esc_html( $order->get_billing_phone() ); ?></td>
				</tr>
			<?php endif; ?>

			<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

		</table>
	</div>

	<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>

	<section class="row woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">

		<div class="col-sm-10 woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">
			<div class="co-section">
				<?php endif; ?>

				<h4 class="co-section-title woocommerce-column__title"><?php _e( 'Billing address', 'woocommerce' ); ?></h4>

				<address>
					<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
				</address>

				<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() ) : ?>
			</div>
		</div><!-- /.col-1 -->

		<div class="col-sm-10 woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">
			<div class="co-section">
				<h4 class="co-section-title woocommerce-column__title"><?php _e( 'Shipping address', 'woocommerce' ); ?></h4>

				<address>
					<?php echo ( $address = $order->get_formatted_shipping_address() ) ? $address : __( 'N/A', 'woocommerce' ); ?>
				</address>
			</div>

		</div><!-- /.col-2 -->

	</section><!-- /.col2-set -->

	<?php endif; ?>

</section>
