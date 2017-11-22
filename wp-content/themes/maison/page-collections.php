<?php get_header(); ?>

<div class="collections-slider u-mb5">
    <?php
    global $separate_footer;
    $separate_footer = false;
    $collections_query = new WP_Query(array('post_type' => 'collection', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'asc'));
    $collections_count = 0;
    while ($collections_query->have_posts()):
        $collections_query->the_post();
        ++$collections_count;
    ?>

    <section class="section section--background section--background-outter section--white collection-slide" style="background-image: url('<?php echo get_the_post_thumbnail_url(null, 'full'); ?>');">
        <?php if (get_field('add_overlay')): require('inc/overlay.php'); endif; ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-offset-1 col-md-15">
                    <h1 class="u-ttn small"><small class="u-db u-mb1 u-cwhite"><?php echo get_field('year'); ?></small> <?php the_title(); ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-offset-2 col-lg-6 col-md-9">
                    <p class="u-cwhite u-o9"><?php echo get_the_excerpt() ?></p>
                    <a href="<?php the_permalink(); ?>" class="link"><?php _e('Discover', 'maison-tina') ?></a>
                </div>
            </div>
        </div>
    </section>

    <?php
    endwhile;
    wp_reset_query(); ?>

    <div class="controls-panel">
        <a href="#" class="js-up-trigger arrow-control arrow-control--up">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="29" height="15" viewBox="0 0 29 15" xml:space="preserve">
                <polyline fill="none" stroke="#FFFFFF" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="0,15 15,0 29,15 "/>
            </svg>
        </a>
        <a href="#" class="js-down-trigger arrow-control arrow-control--down">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="29" height="15" viewBox="0 0 29 15" xml:space="preserve">
                <polyline fill="none" stroke="#FFFFFF" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="0,0 15,15 29,0 "/>
            </svg>
        </a>
    </div>

    <div class="paging-panel">
        <?php for ($coll_index = 0; $coll_index < $collections_count; ++$coll_index): ?>
        <a href="#"><span></span></a>
        <?php endfor; ?>
    </div>
</div>

<?php get_footer(); ?>