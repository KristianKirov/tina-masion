<?php
get_header();
the_post();
?>

<section class="section u-pt3 u-pb7">
    <div class="container-fluid">
        <div class="post-date u-tac u-mb5"><?php the_date(); ?></div>
        <h2 class="u-tac u-mb5"><?php the_title(); ?></h2>
        <?php the_post_thumbnail('full', array('class' => 'responsive u-mb7')) ?>
        <div class="blog-content">
            <?php the_content(); ?>
        </div>

        <?php
        $next_post = get_next_post();
        $prev_post = get_previous_post();
        if ($next_post || $prev_post):
        ?>
        <div class="u-pt3 u-pb3">
            <div class="row">
                <div class="col-sm-10">
                    <?php if ($prev_post): ?>
                    <a href="<?php the_permalink($prev_post); ?>" class="post-sibling-link u-dib">
                        <small><?php _e('Previous', 'maison-tina') ?></small><br />
                        <?php echo get_the_title($prev_post); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="col-sm-10 u-tar">
                    <?php if ($next_post): ?>
                    <a href="<?php the_permalink($next_post); ?>" class="post-sibling-link u-dib">
                        <small><?php _e('Next', 'maison-tina') ?></small><br />
                        <?php echo get_the_title($next_post); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
?>