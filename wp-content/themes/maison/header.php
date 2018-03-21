<?php require 'inc/header-common.php'; ?>

<?php $navClass = is_front_page() ? 'nav-top--white' : ''; ?>
    <nav class="nav-top <?php echo $navClass; ?>" data-normal-class="<?php echo $navClass; ?>" data-condensed-class="nav-top--fixed">
        <section class="section">
            <div class="nav-wrapper">
                <div class="nav-section">
                    <?php wp_nav_menu(array('theme_location' => 'top-nav-left', 'container' => false)); ?>

                    <a href="<?php echo home_url('/') ?>" class="logo">
                        <span class="img"></span>
                    </a>

                    <ul class="mobile-only-menu">
                        <li><?php do_action('maison_mobile_cart_link'); ?></li>
                    </ul>
                </div>
                
                <div class="nav-section u-tar">
                    <?php
                        wp_nav_menu(array('theme_location' => 'top-nav-right', 'container' => false));
                        qtranxf_generateLanguageSelectCode(array('type' => 'text'));
                    ?>

                    <button type="button" class="nav-toggle js-nav-mobile-menu-toggle">
                        <span class="toggle-bar"></span>
                        <span class="toggle-bar"></span>
                        <span class="toggle-bar"></span>
                    </button>
                </div>
            </div>
        </section>
    </nav>

    <div class="nav-mobile-menu nav-mobile-menu--collapsed">
        <div class="container-fluid">
            <header class="u-mb3">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/maison-logo-white.svg" />
                <button type="button" class="nav-close js-nav-mobile-menu-toggle">&times;</button>
            </header>

            <?php wp_nav_menu(array('theme_location' => 'top-nav-mobile-main', 'container' => 'nav', 'container_class' => 'main-nav u-mb1')); ?>

            <?php wp_nav_menu(array('theme_location' => 'top-nav-mobile-secondary', 'container' => 'nav', 'container_class' => 'secondary-nav u-mb1')); ?>
        </div>
    </div>

    <main<?php if (!is_front_page()) echo ' class="content-after-nav"'; ?>>