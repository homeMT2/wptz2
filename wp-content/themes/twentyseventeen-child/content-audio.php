<?php
/*
 * Audio element
 * */
?>

<?php
    $img_url    = get_post_meta( get_the_ID(), 'meta-image', TRUE );
    $audio_url  = get_post_meta( get_the_ID(), 'meta-file', TRUE );
    $size       = get_post_meta( get_the_ID(), 'meta-file-size', TRUE );

    if( $img_url == '' ) {
        $img_url = get_site_url() . '/wp-content/themes/twentyseventeen-child/img/audio.png';
    }

    if( $audio_url == '' ) {
        $audio_url = '#';
    }

    if( $size == '' ) {
        $size = '-';
    }

?>

<div id="post-<?php the_ID(); ?>" class="audio clearfix">
    <div class="img">
        <img src="<?php echo $img_url; ?>" alt="audio">
    </div>

    <div class="info">
        <h3 class="title">
            <?php the_title(); ?> - <?php echo get_post_meta( get_the_ID(), 'audio_author', TRUE ); ?>
        </h3>

        <div class="tags">
            <?php echo terms_inline( get_the_ID(), 'tag' ); ?>
            <?php echo terms_inline( get_the_ID(), 'style' ); ?>
        </div>

        <p class="date">
            <?php echo get_the_date(); ?>
        </p>

        <p class="date">
            <a href="<?php echo $audio_url; ?>">Download (<?php echo $size; ?>)</a>
        </p>

    </div>
</div>