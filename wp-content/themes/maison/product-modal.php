<?php
$product_thumbnail_id = get_post_thumbnail_id();
$product_thumbnail = wp_get_attachment_image_src($product_thumbnail_id, 'full');
while ( have_posts() ) :
the_post(); ?>
<div class="product-modal-content">
    <div class="row row--no-gutter article-details">
        <div class="col-sm-11">
            <div class="u-pr">
                <?php woocommerce_show_product_sale_flash(); ?>
                <img src="<?php echo $product_thumbnail[0]; ?>" class="responsive" />
            </div>
        </div>
        <div class="col-sm-9">
            <div class="product-details">
                <h1 class="product-heading"><?php the_title(); woocommerce_template_single_price(); ?></h1>
                <div class="js-read-more" data-rm-words="25">
                    <?php
                    woocommerce_template_single_excerpt();
                    require 'inc/product-simple-attributes.php';
                    ?>
                </div>
                <?php
                woocommerce_template_single_add_to_cart();
                echo '<a href="' . get_permalink() . '" class="u-ttu">' . __('View Full Item', 'maison-tina') . '</a>';
                ?>
            </div>
        </div>
    </div>

    <a href="#" data-prev-product class="product-modal-content-navigate-link product-modal-content-navigate-link--prev">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="46" viewBox="0 0 15 29" xml:space="preserve">
            <polyline fill="none" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="15,0 0,15 15,29 "/>
        </svg>
    </a>
    <a href="#" data-next-product class="product-modal-content-navigate-link product-modal-content-navigate-link--next">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="46" viewBox="0 0 15 29" xml:space="preserve">
            <polyline fill="none" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="0,0 15,15 0,29 "/>
        </svg>
    </a>
</div>
<?php endwhile; ?>