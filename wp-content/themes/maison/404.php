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

        <h2 class="u-ttn">The page you’re looking for <br /> doesn’t exist.</h2>

        <a href="<?php echo home_url('/') ?>" class="u-ttu home-link fwsb u-dib">Back To Home</a>
    </div>
</section>

<?php get_footer(); ?>