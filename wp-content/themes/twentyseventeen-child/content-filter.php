<?php
/*
 * Filter HTML
 * */
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
</form>