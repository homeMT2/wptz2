<?php
/**
 * Front page
 *
 * @package unite
 */
?>

<?php get_header(); ?>

    <div class="front-page">
        <?php get_movies( array('number_posts' => 4, 'grid' => TRUE ) ); ?>
    </div>

<?php get_footer(); ?>