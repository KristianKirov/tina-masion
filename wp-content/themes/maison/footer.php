<?php global $separate_footer; ?>
    </main>
    <section class="section section--footer<?php if (!$separate_footer) echo ' section--footer--no-separator' ?>">
        <div class="container-fluid">
            <footer class="desktop-only">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-4">
                        <h3><?php _e('Information', 'maison-tina') ?></h3>
                        <?php wp_nav_menu(array('theme_location' => 'footer-information', 'container' => 'nav')); ?>
                    </div>

                    <div class="col-xs-offset-1 col-xs-6 col-sm-offset-1 col-sm-6 col-md-offset-0 col-md-4">
                        <h3><?php _e('Online Shop', 'maison-tina') ?></h3>
                        <?php wp_nav_menu(array('theme_location' => 'footer-online-shop', 'container' => 'nav')); ?>
                    </div>

                    <div class="col-xs-offset-1 col-xs-6 col-sm-offset-1 col-sm-6 col-md-offset-0 col-md-3">
                        <h3><?php _e('Legal', 'maison-tina') ?></h3>
                        <?php wp_nav_menu(array('theme_location' => 'footer-legal', 'container' => 'nav')); ?>
                    </div>

                    <div class="col-xs-13 col-sm-13 col-md-5">
                        <h3><?php _e('Newsletter', 'maison-tina') ?></h3>
                        <?php echo do_shortcode('[mc4wp_form id="342"]'); ?>
                    </div>

                    <div class="col-xs-offset-1 col-xs-6 col-sm-6 col-md-3">
                        <h3><?php _e('Follow Tina', 'maison-tina') ?></h3>
                        <nav class="share-panel">
                        <a href="https://www.facebook.com/tinasocialclub"><span class="icon-facebook"></span></a>
                        <a href="https://www.instagram.com/maison_tina/"><span class="icon-instagram"></span></a>
                        <!--<a href="/missing"><span class="icon-pinterest"></span></a>-->
                        </nav>
                    </div>
                </div>
            </footer>

            <footer class="mobile-only">
                <div class="mobile-footer-content">
                    <nav class="share-panel">
                        <a href="https://www.facebook.com/tinasocialclub"><span class="icon-facebook"></span></a>
                        <a href="https://www.instagram.com/maison_tina/"><span class="icon-instagram"></span></a>
                        <!--<a href="/missing"><span class="icon-pinterest"></span></a>-->
                    </nav>

                    <?php wp_nav_menu(array('theme_location' => 'footer-mobile', 'container' => 'nav')); ?>
                </div>

                <a href="#" class="scroll-home arrow-up"></a>
            </footer>

            <div class="clearfix copyright">
                <small class="u-fl">&copy; <?php echo date("Y"); ?> Copyright MAISON TINA.</small>
                <small class="u-fr"><?php _e('Website by', 'maison-tina') ?>: Acataleo</small>
            </div>
        </div>
    </section>

    <?php wp_footer(); ?>
</body>
</html>