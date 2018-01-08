<?php

/*
 * Functions file
 * */

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'audio-thumb', 300, 300, TRUE );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

add_action( 'admin_enqueue_scripts', 'admin_theme_name_scripts' );

function theme_name_scripts() {
    wp_enqueue_script( 'script', get_site_url() . '/wp-content/themes/twentyseventeen-child/js/functions.js', array(), '1.0.0', true );
}

function admin_theme_name_scripts() {
    wp_enqueue_script( 'admin_script', get_site_url() . '/wp-content/themes/twentyseventeen-child/js/admin.js', array(), '1.0.0', true );
}

function ajax_get_audio() {
    get_audio( $_POST );
}

function get_authors() {
    $meta = get_meta_values( 'audio_author', 'audio' );
    return array_unique( $meta );
}

function get_styles() {
    $terms = get_terms(
        array(
            'taxonomy' => 'style',
            'hide_empty' => false,
        )
    );

    return $terms;
}

function get_audio( $attr = array() )
{
    $number_posts   = ( isset( $attr['number_posts'] ) ) ? $attr['number_posts'] : 4;
    $offset         = ( isset( $attr['offset'] ) ) ? $attr['offset'] * $number_posts : 0;

    $orderby        = ( isset( $attr['orderby'] ) ) ? $attr['orderby'] : 'date';
    $order          = ( isset( $attr['order'] ) ) ? $attr['order'] : 'DESC';

    $args = array(
        'numberposts'       => $number_posts,
        'offset'            => $offset,
        'category'          => 0,
        'orderby'           => $orderby,
        'order'             => $order,
        'include'           => array(),
        'exclude'           => array(),
        'meta_key'          => '',
        'meta_value'        => '',
        'post_type'         => 'audio',
        'suppress_filters'  => true,
    );

    if( $orderby == 'audio_author' ) {
        $args['meta_key'] = $orderby;
    }

    if( $attr['author'] != '' ) {

        $args['meta_query'] = array(
            array(
                'key' => 'audio_author',
                'value' => $attr['author'],
                'compare' => '=',
            )
        );

    }

    if( $attr['style'] != '' ) {

        $args['tax_query'] = array(
            array(
                'taxonomy' => 'style',
                'field' => 'term_id',
                'terms' => $attr['style'],
            )
        );

    }

    global $post;
    $posts = get_posts($args);

    foreach ($posts as $post) :
        get_template_part('content', 'audio');
    endforeach;

    wp_reset_postdata();

    die();
}

add_action('wp_ajax_loadmore', 'ajax_get_audio');
add_action('wp_ajax_nopriv_loadmore', 'ajax_get_audio');

/* Add custom post type */
function audio_post_type()
{
    register_post_type( 'audio',
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
                'name' => __( 'Audio' ),
                'singular_name' => __( 'Audio' )
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}

function audio_taxonomies()
{
    $labels = array(
        'name' => _x( 'Tag', 'tag' ),
        'singular_name' => _x( 'Tag', 'tag' ),
        'search_items' =>  __( 'Search Tags' ),
        'popular_items' => __( 'Popular Tags' ),
        'all_items' => __( 'All Tags' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Tag' ),
        'update_item' => __( 'Update Tag' ),
        'add_new_item' => __( 'Add New Tag' ),
        'new_item_name' => __( 'New Tag Name' ),
        'separate_items_with_commas' => __( 'Separate tag with commas' ),
        'add_or_remove_items' => __( 'Add or remove tags' ),
        'choose_from_most_used' => __( 'Choose from the most used tags' ),
        'menu_name' => __( 'Tags' ),
    );

    register_taxonomy(
        'tag',
        'audio',
        array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'tag' ),
        )
    );


    $labels = array(
        'name' => _x( 'Style', 'style' ),
        'singular_name' => _x( 'Style', 'style' ),
        'search_items' =>  __( 'Search Styles' ),
        'popular_items' => __( 'Popular Styles' ),
        'all_items' => __( 'All Styles' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Style' ),
        'update_item' => __( 'Update Style' ),
        'add_new_item' => __( 'Add New Style' ),
        'new_item_name' => __( 'New Style Name' ),
        'separate_items_with_commas' => __( 'Separate style with commas' ),
        'add_or_remove_items' => __( 'Add or remove styles' ),
        'choose_from_most_used' => __( 'Choose from the most used styles' ),
        'menu_name' => __( 'Style' ),
    );

    register_taxonomy(
        'style',
        'audio',
        array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'style' ),
        )
    );
}

add_action( 'init', 'audio_post_type', 1 );
add_action( 'init', 'audio_taxonomies', 2 );

/* Show */
function show( $array = array() ) {
    echo '~~~';
    echo '<pre>';
    print_r( $array );
    echo '</pre>';
    echo '~~~';
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
        $term_string .= '</span> ';
    }

    return $term_string;
}


/* Meta boxes */
add_action( 'admin_init', 'metaboxes' );

function metaboxes()
{
    // IMG
    add_meta_box( 'meta_audio_img', 'IMG', 'audio_img_display', 'audio', 'normal', 'high' );

    // Audio
    add_meta_box( 'meta_audio_file', 'Audio', 'audio_file_display', 'audio', 'normal', 'high' );

    // Author
    add_meta_box( 'meta_audio_author', 'Author', 'audio_author_display', 'audio', 'normal', 'high' );
}


/* Meta Box Author */
function audio_author_display( $post )
{
    ?>
    <p class="description">
        Enter AUTHOR name here.
    </p>

    <input type="text" name="audio_author" value="<?php echo esc_html( get_post_meta( $post->ID, 'audio_author', true ) ); ?>" />
    <?php
}

function audio_author_save()
{
    global $post;

    if ( $post->post_type == 'audio' && isset( $_POST['audio_author'] ) ) {
        update_post_meta( $post->ID, 'audio_author', $_POST['audio_author'] );
    }
}

add_action( 'save_post', 'audio_author_save', 10 );


/* Meta Box Audio */
function audio_file_display( $post )
{

    wp_nonce_field( 'case_study_bg_submit', 'case_study_bg_nonce' );

    $meta = get_post_meta( $post->ID );

    ?>

    <p>
        <?php
            $url = '';
            $size = 0;

            if ( isset ( $meta['meta-file'] ) ) {
                $url = $meta['meta-file'][0];
                $size = $meta['meta-file-size'][0];
            }
        ?>

        <input type="text" name="meta-file" id="meta-file" class="meta_file" value="<?php echo $url; ?>" />
        <input type="text" name="meta-file-size" id="meta-file-size" class="meta_file_size" disabled value="<?php echo $size; ?>" />

        <input type="button" id="meta-file-button" class="button" value="Open AUDIO" />
    </p>

    <?php

}

function audio_file_save( $post_id )
{
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'case_study_bg_nonce' ] ) && wp_verify_nonce( $_POST[ 'case_study_bg_nonce' ], 'case_study_bg_submit' ) ) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce  ) {
        return;
    }

    if( isset( $_POST[ 'meta-file' ] ) ) {

        update_post_meta( $post_id, 'meta-file', $_POST[ 'meta-file' ] );

        $audio_url = get_post_meta( get_the_ID(), 'meta-file', TRUE );
        $size = file_size_convert( strlen( file_get_contents( $audio_url ) ) );
        update_post_meta( $post_id, 'meta-file-size', $size );
    }
}

add_action('save_post', 'audio_file_save', 20 );


/* Meta Box IMG */
function audio_img_display( $post ) {
    wp_nonce_field( 'case_study_bg_submit', 'case_study_bg_nonce' );
    $lacuna2_stored_meta = get_post_meta( $post->ID ); ?>

    <p>

        <?php
            $img = '';
            if ( isset ( $lacuna2_stored_meta['meta-image'] ) ) {
                $img = $lacuna2_stored_meta['meta-image'][0];
            }

            $url = '';
            if ( isset ( $lacuna2_stored_meta['meta-image'] ) ) {
                $url = $lacuna2_stored_meta['meta-image'][0];
            }
        ?>

        <img style="max-width:200px; height:auto;" id="meta-image-preview" src="<?php echo $img; ?>" />

        <input type="text" name="meta-image" id="meta-image" class="meta_image" value="<?php echo $url; ?>" />
        <input type="button" id="meta-image-button" class="button" value="Open IMG" />
    </p>

    <?php

}

function audio_img_save( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'case_study_bg_nonce' ] ) && wp_verify_nonce( $_POST[ 'case_study_bg_nonce' ], 'case_study_bg_submit' ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce  ) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'meta-image' ] ) ) {
        update_post_meta( $post_id, 'meta-image', $_POST[ 'meta-image' ] );
    }
}

add_action( 'save_post', 'audio_img_save', 30 );

function get_meta_values( $key = '', $type = 'post', $status = 'publish' ) {

    global $wpdb;

    if( empty( $key ) ) {
        return;
    }

    $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '%s'
        AND p.post_status = '%s'
        AND p.post_type = '%s'
    ", $key, $status, $type ) );

    return $r;
}

function get_file_size($url)
{
    $url = parse_url($url);
    if($fp = @fsockopen($url['host'],emptyempty($url['port'])?80:$url['port'],$error))
    {
        fputs( $fp, "GET " . ( emptyempty( $url['path'] ) ? '/' : $url['path']) . " HTTP/1.1\r\n" );
        fputs( $fp, "Host : $url[host]\r\n\r\n" );

        while(!feof($fp))
        {
            $tmp = fgets($fp);
            if(trim($tmp) == '')
            {
                break;
            }
            elseif(preg_match('/Content-Length:(.*)/si',$tmp,$arr))
            {
                return trim($arr[1]);
            }
        }
        return null;
    }
    else
    {
        return null;
    }
}

function file_size_convert($bytes)
{
    $result = '0 B';

    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}