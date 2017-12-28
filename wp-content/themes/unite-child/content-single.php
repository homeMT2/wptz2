<?php
/**
 * @package unite
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php
    if ( of_get_option( 'single_post_image', 1 ) == 1 ) :
        the_post_thumbnail( 'unite-featured', array( 'class' => 'thumbnail' ));
    endif;
    ?>

    <h2 class="movie-title">
        <?php the_title(); ?>
    </h2>

    <div class="movie-content">
        <?php
        if ( is_front_page() ) {
            the_excerpt();
        }
        else {
            the_content();
        }
        ?>
    </div>

    <?php get_template_part( 'content', 'info' ); ?>

</article>