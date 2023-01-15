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

	<section class="related products section section--full u-pt3 section--centered-heading">
		<h2><?php esc_html_e( 'You may also like', 'maison-tina' ); ?></h2>
		<div class="row">
			<div class="col-md-offset-1 col-md-18">
				<div class="row-slider">
					<a href="#" class="row-slider-arrow row-slider-arrow-left"></a>
					<div class="row-slider-track-window">
						<div class="row row-slider-track">
						<?php foreach ( $related_products as $related_product ) : 
							$post_object = get_post( $related_product->get_id() );

							setup_postdata( $GLOBALS['post'] =& $post_object );

							wc_get_template_part( 'content', 'product' ); 
						endforeach; ?>
						</div>
					</div>
					<a href="#" class="row-slider-arrow row-slider-arrow-right"></a>
				</div>
			</div>
		</div>
	</section>

<?php endif;

wp_reset_postdata();
