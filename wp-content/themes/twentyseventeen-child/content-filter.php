<?php
/*
 * Filter HTML
 * */
?>

<?php
    $authors    = get_authors();
    $styles     = get_styles();
?>

<form class="filter" method="POST">

    <select id="orderby" class="filter-select" name="orderby">
        <option value="date">Date</option>
        <option value="audio_author">Author</option>
        <option value="term">Term</option>
        <option value="title">Title</option>
    </select>

    <select id="order" class="filter-select" name="order">
        <option value="DESC">Z-A / New</option>
        <option value="ASC">A-Z / Old</option>
    </select>

    <select id="author" class="filter-select" name="author">
        <option value="">--- Any Author ---</option>
        <?php foreach( $authors as $author ) : ?>
            <?php echo '<option value="' . $author . '">' . $author . '</option>' ?>
        <?php endforeach; ?>
    </select>

    <select id="style" class="filter-select" name="style">
        <option value="">--- Any Style ---</option>
        <?php foreach( $styles as $style ) : ?>
            <?php echo '<option value="' . $style->term_id . '">' . $style->name . '</option>' ?>
        <?php endforeach; ?>
    </select>

</form>