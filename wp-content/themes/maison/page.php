<?php
get_header();
the_post();
?>

<section class="section u-pt3 u-pb7">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-offset-6 col-md-offset-4 col-sm-offset-3 col-lg-8 col-md-12 col-sm-14">
                <h2 class="u-tac"><?php the_title(); ?></h2>
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
?>