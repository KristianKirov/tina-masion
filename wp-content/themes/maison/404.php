<?php get_header(); ?>

<style type="text/css">
    .home-link {
        color: #021639;
        font-size: 22px;
        font-family: 'Josefin Sans', Helvetica, Arial, sans-serif;
        margin-top: 100px;
    }
</style>

<section class="section u-pt3 u-pb7 u-tac">
    <div class="container-fluid">
        <h2>Pardon!</h2>

        <h2 class="u-ttn"><?php _e('The page you’re looking for', 'maison-tina') ?><br /><?php _e('doesn’t exist.', 'maison-tina') ?></h2>

        <a href="<?php echo home_url('/') ?>" class="u-ttu home-link fwsb u-dib"><?php _e('Back To Home', 'maison-tina') ?></a>
    </div>
</section>

<?php get_footer(); ?>