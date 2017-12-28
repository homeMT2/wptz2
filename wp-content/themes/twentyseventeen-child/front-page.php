<?php
/**
 * Front page
 *
 * @package twentyseventeen
 */
?>

<?php get_header(); ?>

<div class="front-page">

    <?php get_template_part('content', 'filter'); ?>

    <div id="audio">
        <?php //get_audio(); ?>
    </div>

    <div id="info"></div>

</div>

<?php get_footer(); ?>
