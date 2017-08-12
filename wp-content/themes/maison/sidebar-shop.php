<?php if (!is_shop() && !is_product_category()) return; ?>

<div class="col-lg-3 col-lg-pull-17 col-md-4 col-md-pull-16 col-sm-5 col-sm-pull-15">
    <?php
    $selected_category_css_class = "u-cblack";
    $shop_page_url = get_permalink(wc_get_page_id( 'shop' ));
    $product_categories = get_terms(array('taxonomy' => 'product_cat'));
    $current_product_category_id = null;
    if (is_product_category()) {
        $current_product_category = get_queried_object();
        $current_product_category_id =  empty( $current_product_category->term_id ) ? null : $current_product_category->term_id;;
    }
    ?>

    <ul class="list-clean">
        <li class="u-mb2">
            <a <?php if ($current_product_category_id == null) echo "class=\"$selected_category_css_class\""; ?> href="<?php echo $shop_page_url; ?>">All</a>
        </li>
        <?php foreach ($product_categories as $product_category) : ?>
        <li class="u-mb2">
            <a <?php if ($current_product_category_id == $product_category->term_id) echo "class=\"$selected_category_css_class\""; ?> href="<?php echo get_term_link($product_category, 'product_cat'); ?>"><?php echo $product_category->name; ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>