<?php get_header(); the_post(); ?>

<header class="section section--full section--background section--white section--maison u-pr" style="background-image: url(<?php echo get_field('heroimage'); ?>)">
    <section class="section">
        <div class="container-fluid">
            <div class="u-tac">
                <h4 class="u-fwt">The</h4>
                <h1 class="u-fwt">Maison</h1>
                <a href="<?php echo get_field('herovideo'); ?>" data-lity>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/play.png" class="hover--transparent" />
                </a>
            </div>
        </div>
    </section>

    <a href="#timeless-chic" class="desktop-only scroll-down-link" data-smooth-scroll>↓</a>
</header>

<section id="timeless-chic" class="section u-mb10 u-mt10 u-xs-mt5 u-xs-mb5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-offset-4 col-xs-offset-0 col-sm-14">
                        <h2 class="u-mt-3p u-xs-mt2">
                            <small  class="u-db u-mb2">Philosophy</small>
                            <?php echo get_field('philosophytitle'); ?>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-6 col-xs-offset-2 col-sm-12">
                        <?php the_content() ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-7">
                <img src="<?php echo get_field('philosophyimage1'); ?>" class="responsive u-mb2" />
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-fluid">
        <img src="<?php echo get_field('philosophyimage2'); ?>" class="responsive" />
    </div>
</section>

<section class="section u-tac u-mb10 u-mt10 u-xs-mt5 u-xs-mb5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-sm-offset-4">
                <h2>
                    <small  class="u-db u-mb2">Craftsmanship</small>
                    <?php echo get_field('craftsmanshiptitle'); ?>
                </h2>
                <p><?php echo get_field('craftsmanshipdescription'); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section u-tac u-pb10 u-xs-pb5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <?php
                $craftsmanshipFirstImage = get_field('craftsmanshipimage1');
                if ($craftsmanshipFirstImage) {
                    ?><img src="<?php echo $craftsmanshipFirstImage; ?>" class="responsive u-mb5" /><?php
                }
                else {
                    $craftsmanshipFirstVideo = get_field('craftsmanshipvideo1');
                    if ($craftsmanshipFirstVideo) {
                        ?><div class="js-youtubevideo u-mb5" data-id="<?php echo $craftsmanshipFirstVideo['vid']; ?>" data-orientation="portrait"></div><?php
                    }
                }
                ?>
            </div>
            <div class="col-sm-offset-3 col-md-push-1 col-sm-9">
                <?php
                $craftsmanshipSecondImage = get_field('craftsmanshipimage2');
                if ($craftsmanshipSecondImage) {
                    ?><img src="<?php echo $craftsmanshipSecondImage; ?>" class="responsive u-mt-3p u-xs-mt0" /><?php
                }
                else {
                    $craftsmanshipSecondVideo = get_field('craftsmanshipvideo2');
                    if ($craftsmanshipSecondVideo) {
                        ?><div class="js-youtubevideo u-mt-3p u-xs-mt0" data-id="<?php echo $craftsmanshipSecondVideo['vid']; ?>" data-orientation="landscape"></div><?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>

<section class="section section--bg-blue u-mb10 u-mt10 u-xs-mt0 u-xs-mb5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-offset-4 col-xs-offset-0 col-sm-14">
                        <h2 class="u-cwhite u-mt-3p u-xs-mt2">
                            <small class="u-db u-mb2 u-clightgray">Creative</small>
                            <?php echo get_field('creativetitle'); ?>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-6 col-xs-offset-2 col-sm-12">
                        <p><?php echo get_field('creativedescription'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-7">
                <img src="<?php echo get_field('creativeimage'); ?>" class="responsive u-mt-10 u-mb10 u-xs-mt0 u-xs-mb2" />
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>