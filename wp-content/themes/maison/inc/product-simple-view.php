<?php
$product_thumbnail_id = get_post_thumbnail_id($product_post);
$product_thumbnail = wp_get_attachment_image_src($product_thumbnail_id, 'shop_catalog');
$product_image = wp_get_attachment_image_src($product_thumbnail_id, 'shop_single');
?>
<div class="article u-mb6 u-tac">
    <a class="article-link" href="<?php echo get_permalink($product_post); ?>">
        <figure>
            <img class="responsive" src="<?php echo $product_thumbnail[0]; ?>" />
            <img class="responsive article-image--hover" src="<?php echo $product_image[0]; ?>" />
        </figure>

        <span class="u-ttu article-title"><?php echo get_the_title($product_post); ?></span>
    </a>
</div>