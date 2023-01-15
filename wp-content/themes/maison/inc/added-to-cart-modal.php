<div class="lity-modal-notice u-tac">
    <div class="lity-title"><?php echo sprintf(__( '%s has been added to your cart.', 'woocommerce' ), __('Item', 'woocommerce')) ?></div>
    <div class="lity-content-wrapper">
        <?php
        foreach ( $products as $product_id => $qty ) :
            $product = wc_get_product($product_id); ?>
            <div class="row u-pt3 u-pb3">
                <div class="col-xs-8 col-xs-offset-6 col-sm-8 col-sm-offset-1">
                    <a href="<?php echo esc_url($product->get_permalink()) ?>" class="u-db">
                        <?php echo $product->get_image('shop_catalog', array("class" => "responsive")); ?>
                    </a>
                </div>
                <div class="col-xs-12 col-xs-offset-4 col-sm-9 col-sm-offset-1">
                    <h4 class="u-ttu u-cblack u-mb1 u-mt5"><?php echo $product->get_title() ?></h4>
                    <p class="price u-mb3"><?php echo $product->get_price_html() ?></p>
                    <div>
                        <a href="<?php echo esc_url(wc_get_page_permalink('cart')) ?>" class="btn u-db u-mb1"><?php echo esc_html__('View cart', 'woocommerce') ?></a>
		                <button type="button" class="btn btn-sec u-db" data-lity-close><?php echo esc_html__('Continue shopping', 'woocommerce') ?></a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>