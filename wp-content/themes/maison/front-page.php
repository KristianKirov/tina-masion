<?php
get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        $hero_collection = get_field('herocollection');
        $first_collection = get_field('firstcollection');
        $first_collection_url = get_permalink($first_collection);
        $second_collection = get_field('secondcollection');
        $second_collection_url = get_permalink($second_collection); ?>
<header class="section section--full section--background section--background-right section--head section--head--home section--white u-pr" style="background-image: url(<?php echo get_field('heroimage'); ?>)">
    <?php if (get_field('add_overlay')): require('inc/overlay.php'); endif; ?>
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10">
                    <div class="u-tac">
                        <h3><?php echo get_field('year', $hero_collection); ?></h3>
                        <h1 class="u-ttn"><?php echo get_the_title($hero_collection); ?></h1>
                        <a class="link link--big" href="<?php echo get_permalink($hero_collection); ?>"><?php _e('Discover', 'maison-tina') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</header>

<section class="section u-pt7 u-pb7 u-tac mobile-head-title">
    <div class="container-fluid">
        <h3><?php echo get_field('year', $hero_collection); ?></h3>
        <h1 class="u-ttn"><?php echo get_the_title($hero_collection); ?></h1>
        <a class="link u-cblack" href="<?php echo get_permalink($hero_collection); ?>"><?php _e('Discover', 'maison-tina') ?></a>
    </div>
</section>

<section class="section u-pt7 u-pb7 section--centered-heading">
    <div class="container-fluid">
        <h2><?php _e('Collections', 'maison-tina') ?></h2>
        <div class="row">
            <div class="col-xs-20 col-sm-10 col-md-6">
                <div class="collection1-margin1">
                    <?php
                    $collection_media_params = array('collection_url' => $first_collection_url, 'video_field' => 'c1video1', 'image_field' => 'c1image1', 'orientation' => 'portrait');
                    require('inc/home-collection-media.php');
                    ?>
                </div>
            </div>
            <div class="col-xs-20 col-sm-10 col-md-offset-1 col-md-6">
                <div class="collection1-margin2">
                    <h3><small class="u-db u-mb2"><?php echo get_field('year', $first_collection); ?></small> <?php echo get_the_title($first_collection); ?></h3>
                    <div class="row">
                        <div class="col-xs-offset-3 col-xs-17">
                            <?php echo apply_filters( 'the_excerpt', get_the_excerpt($first_collection)); ?>
                            <a class="link u-cblack" href="<?php echo $first_collection_url; ?>"><?php _e('View', 'maison-tina') ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-20 col-sm-10 col-md-offset-2 col-md-5 desktop-only">
                <div class="collection1-margin3">
                    <?php
                    $collection_media_params = array('collection_url' => $first_collection_url, 'video_field' => 'c1video2', 'image_field' => 'c1image2', 'orientation' => 'landscape');
                    require('inc/home-collection-media.php');
                    ?>
                </div>
            </div>
            <div class="col-xs-20 col-sm-10 col-md-8 col-md-pull-1 u-fr desktop-only">
                <div class="collection1-margin4">
                    <?php
                    $collection_media_params = array('collection_url' => $first_collection_url, 'video_field' => 'c1video3', 'image_field' => 'c1image3', 'orientation' => 'landscape');
                    require('inc/home-collection-media.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$second_collection_second_image_url = get_field('c2image2');
if ($second_collection_second_image_url): ?>
<img src="<?php echo $second_collection_second_image_url; ?>" class="responsive mobile-only" />
<?php endif; ?>

<section class="section u-pt7 u-pb7">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-20 col-sm-10 col-md-offset-2 col-md-8">
                <div class="collection2-margin1">
                    <h3><small class="u-db u-mb2"><?php echo get_field('year', $second_collection); ?></small> <?php echo get_the_title($second_collection); ?></h3>
                    <div class="row">
                        <div class="col-xs-offset-3 col-xs-17">
                            <?php echo apply_filters( 'the_excerpt', get_the_excerpt($second_collection)); ?>
                            <a class="link u-cblack" href="<?php echo $second_collection_url; ?>"><?php _e('View', 'maison-tina') ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-20 col-sm-10 col-md-6 u-fr desktop-only">
                <div class="collection2-margin2">
                    <?php
                    $collection_media_params = array('collection_url' => $second_collection_url, 'video_field' => 'c2video1', 'image_field' => 'c2image1', 'orientation' => 'portrait');
                    require('inc/home-collection-media.php');
                    ?>
                </div>
            </div>
            <div class="col-xs-20 col-sm-10 col-md-9 u-zi1 desktop-only">
                <div class="collection2-margin3">
                    <?php
                    $collection_media_params = array('collection_url' => $second_collection_url, 'video_field' => 'c2video2', 'image_field' => 'c2image2', 'orientation' => 'landscape');
                    require('inc/home-collection-media.php');
                    ?>
                </div>
            </div>

            <div class="col-xs-20 col-sm-10 col-md-pull-1 col-md-5 desktop-only">
                <div class="collection2-margin4">
                    <?php
                    $collection_media_params = array('collection_url' => $second_collection_url, 'video_field' => 'c2video3', 'image_field' => 'c2image3', 'orientation' => 'portrait');
                    require('inc/home-collection-media.php');
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section--background section--background-outter section--white section--overflow-bottom section--centered-heading" style="background-image: url('<?php echo get_field('maisonbackgroundimage'); ?>')">
    <div class="container-fluid">
        <div class="row row--sm-equals">
            <div class="col-sm-14 u-vam">
                <h2 class="big u-fwt u-mt1 u-mb1"><?php echo get_field('maisonsectiontitle'); ?></h2>
            </div>
            <div class="col-sm-6">
                <div class="overflow-bottom-item u-tac">
                    <h3>
                        <small class="u-db u-mb2"><?php _e('Explore', 'maison-tina') ?></small>
                        The Maison
                    </h3>
                    <p><?php echo get_field('masiondescription'); ?></p>
                    <a class="link" href="<?php echo get_field('maisonpage'); ?>"><?php _e('About', 'maison-tina') ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section u-pt7 u-pb1 section--centered-heading">
    <div class="container-fluid">
        <h2><?php _e('Wear Tina', 'maison-tina') ?></h2>

        <div class="row">
            <div class="col-md-offset-1 col-md-18">
                <div class="row cols-2-sm--equal-height cols-2-xs--equal-height cols-4-gt-md--equal-height">
                    <?php
                    $featured_products_meta_query = WC()->query->get_meta_query();
                    $featured_products_meta_query[] = array(
                        'key'   => '_featured',
                        'value' => 'yes'
                    );

                    $featured_products_query_args = array(
                        'post_type'           => 'product',
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => 0,
                        'posts_per_page'      => -1,
                        'orderby'             => 'date',
                        'order'               => 'desc',
                        'meta_query'          => $featured_products_meta_query
                    );
                    
                    $featured_products_query = new WP_Query($featured_products_query_args);
                    if ($featured_products_query->have_posts()) :
                        while ($featured_products_query->have_posts()) :
                            $featured_products_query->the_post();
                            $product_post = $featured_products_query->post;
                            ?>
                            <div class="col-xs-10 col-md-5">
                                <?php require('inc/product-simple-view.php'); ?>
                            </div>
                            <?php                            
                        endwhile;
                    endif;
                    wp_reset_query();
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section--full u-pt7 u-pb10 section--centered-heading">
    <div class="container-fluid">
        <h2>@Maisontina</h2>
    </div>
    <?php echo do_shortcode('[instagram-feed]') ?>
</section>

<?php
    endwhile;
endif;
get_footer();
?>