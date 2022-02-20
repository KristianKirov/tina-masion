<div class="lity-modal-notice u-tac">
    <div class="lity-title"><?php echo sprintf(__( '%s has been added to your cart.', 'woocommerce' ), __('Item', 'woocommerce')) ?></div>
    <div class="lity-content-wrapper">
        <?php
        foreach ( $products as $product_id => $qty ) :
            $product = wc_get_product($product_id); ?>
            <a href="<?php echo esc_url($product->get_permalink()) ?>">
                <?php echo $product->get_image(); ?>
                <div class="hl u-ttu u-cblack u-mt1"><?php echo $product->get_title() ?></div>
            </a>
            <p class="price"><?php echo $product->get_price_html() ?></p>
        <?php endforeach ?>

	    <a href="<?php echo esc_url(wc_get_page_permalink('cart')) ?>" class="btn u-db u-mb1"><?php echo esc_html__('View cart', 'woocommerce') ?></a>
		<button type="button" class="btn btn-sec u-db" data-lity-close><?php echo esc_html__('Continue shopping', 'woocommerce') ?></a>
    </div>
</div>