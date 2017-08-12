<?php get_header(); the_post(); ?>

<header class="section section--full section--background section--white section--maison u-pr" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/images/the-maison-hero.jpg)">
    <section class="section">
        <div class="container-fluid">
            <div class="u-tac">
                <h4 class="u-fwt">The</h4>
                <h1 class="u-fwt">Maison</h1>
                <a href="https://www.youtube.com/watch?v=Fo5EFx4Obv8" data-lity>
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
                            Timeless Chic
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
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/the-maison-1.jpg" class="responsive u-mb2" />
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-fluid">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/the-maison-2.jpg" class="responsive" />
    </div>
</section>

<section class="section u-tac u-mb10 u-mt10 u-xs-mt5 u-xs-mb5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-sm-offset-4">
                <h2>
                    <small  class="u-db u-mb2">Craftsmanship</small>
                    Meticilous Attention
                </h2>
                <p>Following the traditions of haute couture fashion houses, Maison Tina pays meticulous attention
        to every detail. The fabrics in use are produced by the finest production houses in Italy and France. All embroideries
        and appliques are hand-sewn and carefully handcrafted in Tina’s atelier, turning each of the 
        models into an exquisite piece of art.</p>
            </div>
        </div>
    </div>
</section>

<section class="section u-tac u-pb10 u-xs-pb5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/the-maison-3.jpg" class="responsive u-mb5" />
            </div>
            <div class="col-sm-offset-3 col-md-push-1 col-sm-9">
                <div class="js-youtubevideo u-mt-3p u-xs-mt0" data-id="Fo5EFx4Obv8" data-orientation="landscape"></div>
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
                            The Designer
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-offset-6 col-xs-offset-2 col-sm-12">
                        <p>Hristina Angelova is the designer and founder of Maison Tina.
                        Born in a family with strong traditions in fashion design, it was only natural that she started
                        her own brand that reflects her vision of woman aesthetics. Her models inwrought the strong
                        influence of iconic French haute-couture designers and the lightness of the Mediterranean culture.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-offset-2 col-sm-7">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/the-maison-designer.jpg" class="responsive u-mt-10 u-mb10 u-xs-mt0 u-xs-mb2" />
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>