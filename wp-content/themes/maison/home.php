<?php get_header(); ?>

<section class="section u-pt3">
    <div class="container-fluid">
        <div class="row cols-2-sm--equal-height cols-3-gt-md--equal-height">
        <?php
        $index = 0;
        while ( have_posts() ) :
            the_post();
            $css_classes = "col-md-6 col-sm-10";
            if ($index % 3 != 0) {
                $css_classes .= " col-md-offset-1";
            }

            $post_link = get_permalink();
            ?>
            <div class="<?php echo $css_classes; ?> u-mb4 u-tac post-item">
                <figure class="u-mb3">
                    <a href="<?php echo $post_link; ?>" class="u-db">
                        <img src="<?php echo get_the_post_thumbnail_url(null, 'thumbnail') ?>" class="responsive" />
                    </a>
                </figure>
                <p class="post-date"><?php echo get_the_date(); ?></p>
                <h5 class="u-fwsb"><a href="<?php echo $post_link; ?>"><?php the_title(); ?></a></h5>
                <?php the_excerpt(); ?>
            </div>
        <?php
        ++$index;
        endwhile; ?>
        </div>
        <?php
        $arrow_left = '<span class="icon-left-open-big"></span>';
        $prev_posts_link = get_previous_posts_link($arrow_left . 'Newer');

        $arrow_right = '<span class="icon-right-open-big"></span>';
        $next_posts_link = get_next_posts_link('Older ' . $arrow_right);
        if ($next_posts_link || $prev_posts_link) :
        ?>
        <div class="row u-mb9 u-ttu posts-pager">
            <div class="col-xs-10">
                <div class="u-tar <?php if (!$prev_posts_link) echo ' disabled'; ?>">
                    <?php echo ($prev_posts_link ? $prev_posts_link : $arrow_left . ' <span>Newer</span>'); ?>
                </div>
            </div>
            <div class="col-xs-10">
                <div <?php if (!$next_posts_link) echo 'class="disabled"'; ?>>
                    <?php echo ($next_posts_link ? $next_posts_link : '<span>Older</span> ' . $arrow_right); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>