<?php /* Template Name: Checkout */ ?>

<?php
require 'inc/header-common.php';
the_post();
?>

<nav class="co-nav u-tac">
    <a href="<?php echo home_url('/') ?>" class="u-dib co-logo">
        <img width="38" height="31" src="<?php echo get_template_directory_uri(); ?>/assets/images/maison-small-black.svg" />
    </a>
</nav>

<main class="content-after-nav">

<section class="section section--checkout">
    <div class="container-fluid">
        <?php the_content(); ?>
    </div>
</section>

</main>

<?php wp_footer(); ?>
</body>
</html>