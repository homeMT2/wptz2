<?php
/*
 * Sub movie info
 * */
?>

<div class="film-info">

    <span class="film-label film-country">
        <?php echo terms_inline( get_the_ID(), 'country', 'flag' ); ?>
    </span>

    <span class="film-label film-genre">
        <?php echo terms_inline( get_the_ID(), 'genre', 'film' ); ?>
    </span>

    <span class="film-label film-price">
        <span class="term-element genre-price">
            <i class="fa fa-usd" aria-hidden="true"></i>
            <?php echo get_field('price_label') . ' : ' . get_field('price') . ' hrn'; ?>
        </span>
    </span>

    <span class="film-label film-date">
        <span class="term-element genre-release">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            <?php echo get_field('release_date_label') . ' : ' . get_field('release_date'); ?>
        </span>
    </span>

</div>