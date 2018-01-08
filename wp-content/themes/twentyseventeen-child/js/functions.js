jQuery(document).ready(function($){

    var offset = 0;
    var block = false;

    function get_audio() {

        block = true;

        $("#info").html('Loading...');

        $.ajax({
            type: "POST",
            url: document.location.href + 'wp-admin/admin-ajax.php',
            data: {
                'action'    : 'loadmore',
                'offset'    : offset,
                'order'     : $('#order').val(),
                'orderby'   : $('#orderby').val(),
                'author'    : $('#author').val(),
                'style'     : $('#style').val()
            },
            success: function(results) {
                $('#audio').html( $('#audio').html() + results );
                offset++;

                $("#info").html('');
                block = false;
            },
            error: function(results) {

                if(results.status == 404) {
                    $("#info").html('No more posts to show...');
                }
                else {
                    $("#info").html('Error retrieving posts...');
                }

                block = false;
            }

        });
    }

    get_audio();

    $(window).scroll(function() {

        if( $(window).scrollTop() + $(window).height() == $(document).height() && block == false ) {
            get_audio();
        }
    });

    $( ".filter-select" ).change(function() {
        $('#audio').html('');
        offset = 0;
        get_audio();
    });

});


jQuery(document).ready(function($){

    // Instantiates the variable that holds the media library frame.
    var meta_image_frame;

    // Runs when the image button is clicked.
    $('#meta-image-button').click(function(e){

        // Prevents the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( meta_image_frame ) {
            wp.media.editor.open();
            return;
        }

        // Sets up the media library frame
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: { text:  meta_image.button },
            library: { type: 'image' }
        });

        // Runs when an image is selected.
        meta_image_frame.on('select', function(){

            //Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

            // Sends the attachment URL to our custom image input field.
            $('#meta-image').val(media_attachment.url);
        });

        // Opens the media library frame.
        wp.media.editor.open();
    });
});