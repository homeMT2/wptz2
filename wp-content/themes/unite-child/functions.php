<?php

/*
 * Functions file
 * */

// Get movies
add_shortcode( 'get_movies' , 'get_movies' );

function get_movies( $attr = array() )
{
    $number_posts = ( isset( $attr['number_posts'] ) ) ? $attr['number_posts'] : 5;
    $grid         = ( isset( $attr['grid'] ) )         ? TRUE : FALSE;

    $args = array(
        'numberposts' => $number_posts,
        'category' => 0,
        'orderby' => 'date',
        'order' => 'DESC',
        'include' => array(),
        'exclude' => array(),
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'movie',
        'suppress_filters' => true,
    );

    global $post;
    $posts = get_posts($args);

    $i = 0;
    foreach ($posts as $post) :
        setup_postdata($post);

        if( $i%2 == 0 && $grid == TRUE ) {
            echo '<div class="row">';
        }

        if( $grid == TRUE ) {
            echo '<div class="col-md-6">';
        }

        get_template_part('content', 'single');

        if( $grid == TRUE ) {
            echo '</div>';
        }

        if( $i%2 == 1 && $grid == TRUE ) {
            echo '</div>';
        }

        $i++;
    endforeach;

    wp_reset_postdata();
}

/* Add custom post type */
function movie_post_type() {
    register_post_type( 'movie',
        array(
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'custom-fields',
                'revisions'
            ),
            'labels' => array(
                'name' => __( 'Movies' ),
                'singular_name' => __( 'Movie' )
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}
add_action( 'init', 'movie_post_type', 0 );
add_action( 'init', 'create_genres_taxonomies', 1 );
add_action( 'init', 'create_countries_taxonomies', 2 );
add_action( 'init', 'create_actors_taxonomies', 3 );
add_action( 'init', 'create_years_taxonomies', 4 );

function create_genres_taxonomies()
{
    $labels = array(
        'name' => _x( 'Genres', 'genres' ),
        'singular_name' => _x( 'Genre', 'genre' ),
        'search_items' =>  __( 'Search Genres' ),
        'popular_items' => __( 'Popular Genres' ),
        'all_items' => __( 'All Genres' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Genre' ),
        'update_item' => __( 'Update Genre' ),
        'add_new_item' => __( 'Add New Genre' ),
        'new_item_name' => __( 'New Genre Name' ),
        'separate_items_with_commas' => __( 'Separate genre with commas' ),
        'add_or_remove_items' => __( 'Add or remove genres' ),
        'choose_from_most_used' => __( 'Choose from the most used genres' ),
        'menu_name' => __( 'Genres' ),
    );

    register_taxonomy(
        'genre',
        'movie',
        array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'genre' ),
        )
    );
}

function create_countries_taxonomies()
{
    $labels = array(
        'name' => _x( 'Countries', 'countries' ),
        'singular_name' => _x( 'Country', 'country' ),
        'search_items' =>  __( 'Search Countries' ),
        'popular_items' => __( 'Popular Countries' ),
        'all_items' => __( 'All Countries' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Country' ),
        'update_item' => __( 'Update Country' ),
        'add_new_item' => __( 'Add New Country' ),
        'new_item_name' => __( 'New Country Name' ),
        'separate_items_with_commas' => __( 'Separate country with commas' ),
        'add_or_remove_items' => __( 'Add or remove countries' ),
        'choose_from_most_used' => __( 'Choose from the most used countries' ),
        'menu_name' => __( 'Countries' ),
    );

    register_taxonomy(
        'country',
        'movie',
        array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'country' ),
        )
    );
}

function create_actors_taxonomies()
{
    $labels = array(
        'name' => _x( 'Actors', 'actors' ),
        'singular_name' => _x( 'Actor', 'actor' ),
        'search_items' =>  __( 'Search Actors' ),
        'popular_items' => __( 'Popular Actors' ),
        'all_items' => __( 'All Actors' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Actor' ),
        'update_item' => __( 'Update Actor' ),
        'add_new_item' => __( 'Add New Actor' ),
        'new_item_name' => __( 'New Actor Name' ),
        'separate_items_with_commas' => __( 'Separate actor with commas' ),
        'add_or_remove_items' => __( 'Add or remove actors' ),
        'choose_from_most_used' => __( 'Choose from the most used actors' ),
        'menu_name' => __( 'Actors' ),
    );

    register_taxonomy(
        'actor',
        'movie',
        array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'actor' ),
        )
    );
}

function create_years_taxonomies()
{
    $labels = array(
        'name' => _x( 'Years', 'years' ),
        'singular_name' => _x( 'Year', 'year' ),
        'search_items' =>  __( 'Search Years' ),
        'popular_items' => __( 'Popular Years' ),
        'all_items' => __( 'All Years' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Year' ),
        'update_item' => __( 'Update Year' ),
        'add_new_item' => __( 'Add New Year' ),
        'new_item_name' => __( 'New Year Name' ),
        'separate_items_with_commas' => __( 'Separate year with commas' ),
        'add_or_remove_items' => __( 'Add or remove years' ),
        'choose_from_most_used' => __( 'Choose from the most used years' ),
        'menu_name' => __( 'Years' ),
    );

    register_taxonomy(
        'year',
        'movie',
        array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'year' ),
        )
    );
}

// Time style
function time_style( $time ) {
    return date( 'd / F / Y', $time );
}


// Terms in-line
function terms_inline( $post_id, $tax, $icon = FALSE ) {

    $terms = get_the_terms( $post_id, $tax );
    $term_string = '';

    foreach ( $terms as $term ) {

        $term_link = get_term_link( $term, array( 'teams_positions') );

        if( is_wp_error( $term_link ) ) {
            continue;
        }

        $term_string .= '<span class="term-element ' . $tax . '-element">';

        if( $icon != FALSE ) {
            $term_string .= '<i class="fa fa-' . $icon . '" aria-hidden="true"></i>';
        }

        $term_string .= $term->name;
        $term_string .= '</span>';
    }

    return $term_string;
}

// Max length
function wpdocs_excerpt_more( $more ) {
    return sprintf( '<a class="read-more" href="%1$s">%2$s</a>',
        get_permalink( get_the_ID() ),
        __( 'Read More', 'textdomain' )
    );
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

function wpdocs_custom_excerpt_length( $length ) {
    return 50;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

/* END */