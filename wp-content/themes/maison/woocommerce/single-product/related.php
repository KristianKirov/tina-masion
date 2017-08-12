<?php
/**
 * Related Products
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<section class="related products section section--full u-pt7 section--centered-heading">
		<div class="container-fluid">

			<h2><?php esc_html_e( 'Related products', 'woocommerce' ); ?></h2>
			<div class="row">
            	<div class="col-md-offset-1 col-md-18">
					<?php woocommerce_product_loop_start(); ?>

						<?php foreach ( $related_products as $related_product ) : ?>

							<?php
								$post_object = get_post( $related_product->get_id() );

								setup_postdata( $GLOBALS['post'] =& $post_object );

								wc_get_template_part( 'content', 'product' ); ?>

						<?php endforeach; ?>

					<?php woocommerce_product_loop_end(); ?>
				</div>
			</div>
		</div>
	</section>

<?php endif;

wp_reset_postdata();
