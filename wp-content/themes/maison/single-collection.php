<?php
get_header(); the_post();

$campaign_images = maison_photo_gallery('campaign_images', get_the_ID());
$campaign_rows_count = 0;
$campaign_rows = array();
$campaign_images_count = count($campaign_images);
if($campaign_images_count) {
    $campaign_row_index = 0;
    $campaign_rows = array();
    $campaign_image_index = 0;
    while ($campaign_image_index < $campaign_images_count) {
        $current_campaign_image = $campaign_images[$campaign_image_index];
        if ($campaign_row_index % 2 == 1 && $current_campaign_image['orientation'] == 'landscape') {
            $campaign_rows[] = array(array(
                'url' => $current_campaign_image['full_url'],
                'css_class' => 'col-sm-16 col-sm-offset-2'
            ));
        }
        else {
            $current_campaign_row = array();
            if ($current_campaign_image['orientation'] == 'landscape') {
                $current_campaign_row[] = array(
                    'url' => $current_campaign_image['full_url'],
                    'css_class' => 'col-sm-10'
                );
            }
            else {
                $current_campaign_row[] = array(
                    'url' => $current_campaign_image['full_url'],
                    'css_class' => 'col-sm-8'
                );
            }

            $campaign_image_index++;
            if ($campaign_image_index < $campaign_images_count) {
                $next_campaign_image = $campaign_images[$campaign_image_index];
                if ($next_campaign_image['orientation'] == 'landscape') {
                    $current_campaign_row[] = array(
                        'url' => $next_campaign_image['full_url'],
                        'css_class' => 'col-md-10 col-md-push-1' . ($current_campaign_image['orientation'] == 'portrait' ? ' col-sm-10 col-sm-offset-2' : ' col-sm-9 col-md-offset-0 col-sm-offset-1')
                    );
                }
                else {
                    $current_campaign_row[] = array(
                        'url' => $next_campaign_image['full_url'],
                        'css_class' => 'col-sm-8' . ($current_campaign_image['orientation'] == 'portrait' ? ' col-sm-offset-4' : ' col-sm-offset-2')
                    );
                }

                if ($current_campaign_image['orientation'] == 'landscape' && $next_campaign_image['orientation'] == 'portrait') {
                    $current_campaign_row[0]['css_class'] .= ' u-mt-_5p u-xs-mt0';
                }
                else if ($current_campaign_image['orientation'] == 'portrait' && $next_campaign_image['orientation'] == 'landscape') {
                    $current_campaign_row[1]['css_class'] .= ' u-mt-1_5p  u-xs-mt0';
                }
            }

            $campaign_rows[] = $current_campaign_row;
        }

        $campaign_image_index++;
        $campaign_row_index++;
    }
}

$video_thumbnail = get_field('video_thumbnail');
$video = get_field('video');
$show_making = $video_thumbnail && $video;
?>

<header class="section section--full section--background section--white section--head u-pr" style="background-image: url(<?php echo get_field('hero_image'); ?>)">
    <?php if (get_field('add_overlay_hero')): require('inc/overlay.php'); endif; ?>
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-10 u-tac">
                    <h3><?php echo get_field('year'); ?></h3>
                    <h1 class="u-ttn"><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </section>

    <nav class="nav-sub u-tac">
        <ul>
            <li><a href="#about" data-smooth-scroll><?php _e('About', 'maison-tina') ?></a></li>
            <?php if ($campaign_images_count) : ?>
            <li><a href="#campaign" data-smooth-scroll><?php _e('Campaign', 'maison-tina') ?></a></li>
            <?php endif; ?>
            <?php if ($show_making) : ?>
            <li><a href="#making" data-smooth-scroll><?php _e('Making', 'maison-tina') ?></a></li>
            <?php endif; ?>
            <li><a href="#lookbook" data-smooth-scroll><?php _e('Lookbook', 'maison-tina') ?></a></li>
        </ul>
    </nav>

    <a href="#about" class="desktop-only scroll-down-link" data-smooth-scroll>↓</a>
</header>

<section id="about" class="section u-pt7 u-pb7">
    <div class="u-mt6 u-xs-mt2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-10 col-sm-push-1">
                    <img src="<?php echo get_field('about_image'); ?>" class="responsive" />
                </div>
                <div class="col-sm-10 col-sm-pull-1">
                    <div class="u-pt7 u-pb5 u-mt10 u-xs-mt0 u-bgcwhite">
                        <div class="row">
                            <div class="col-xs-18 col-xs-offset-2">
                                <h2><?php _e('<small class="u-db u-mb2">The</small> Collection', 'maison-tina') ?></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-16 col-xs-offset-4">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($campaign_images_count) : ?>
<section id="campaign" class="section u-pt7 u-pb7 section--centered-heading">
    <div class="container-fluid">
        <h2><?php _e('Campaign', 'maison-tina') ?></h2>

        <?php foreach($campaign_rows as $campaign_row) : ?>
            <div class="row">
                <?php foreach($campaign_row as $campaign_row_image) : ?>
                    <div class="<?php echo $campaign_row_image['css_class']; ?>">
                        <img src="<?php echo $campaign_row_image['url']; ?>" class="responsive u-mt7 u-mb7 u-xs-mt0 u-xs-mb2" />
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($show_making) : ?>
<section id="making" class="section section--full section--background u-tac" style="background-image: url(<?php echo $video_thumbnail; ?>)">
    <a href="<?php echo $video; ?>" data-lity class="u-pt-2p u-pb-2p u-db">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.png" class="hover--transparent" />
    </a>
</section>
<?php endif; ?>

<section id="lookbook" class="section u-pt7 u-pb7 section--centered-heading">
    <div class="container-fluid">
        <h2><?php _e('Lookbook', 'maison-tina') ?></h2>

        <div class="row">
            <div class="col-md-offset-1 col-md-18">
                <div class="row cols-2-sm--equal-height cols-2-xs--equal-height cols-3-gt-md--equal-height">
                    <?php
                    $product_category_id = get_field('product_category');
                    $lookbook_slug = 'lookbook';
                    $collection_lookbook_products_query_args = array(
                        'post_type'           => 'product',
                        'post_status'         => 'publish',
                        'ignore_sticky_posts' => 0,
                        'posts_per_page'      => -1,
                        'orderby'             => 'date',
                        'order'               => 'desc',
                        'product_tag'         => $lookbook_slug,
                        'tax_query'           => array(
                            array(
                                'taxonomy'  => 'product_cat',
                                'field'     => 'id', 
                                'terms'     => $product_category_id
                            )
                        )
                    );
                    $collection_lookbook_products_query = new WP_Query($collection_lookbook_products_query_args);
                    $index = 0;
                    while ($collection_lookbook_products_query->have_posts()) :
                        $collection_lookbook_products_query->the_post();
                        $product_post = $collection_lookbook_products_query->post;
                    ?>
                        <div class="col-xs-10 col-md-6 <?php if ($index % 3 != 0) : echo ' col-md-offset-1'; endif; ?>">
                            <?php require('inc/product-simple-view.php'); ?>
                        </div>
                    <?php $index++; endwhile; wp_reset_query();?>
                </div>
            </div>
        </div>

        <div class="u-tac">
            <a href="<?php echo get_term_link($product_category_id, 'product_cat'); ?>" class="link u-cblack"><?php _e('Shop the Collection', 'maison-tina') ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>