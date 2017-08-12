<?php /* Template Name: Full Width */ ?>

<?php
get_header();
the_post();
?>

<section class="section u-pt3 u-pb7">
    <div class="container-fluid">
        <h2 class="u-tac"><?php the_title(); ?></h2>
        <?php the_content(); ?>
    </div>
</section>

<?php
get_footer();
?>