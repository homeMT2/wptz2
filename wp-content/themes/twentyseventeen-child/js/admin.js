jQuery('#meta-file-button').click(function() {

    var send_attachment_bkp = wp.media.editor.send.attachment;

    wp.media.editor.send.attachment = function(props, attachment) {

        jQuery('#meta-file').val(attachment.url);
        wp.media.editor.send.attachment = send_attachment_bkp;
    }

    wp.media.editor.open();

    return false;
});


jQuery('#meta-image-button').click(function() {

    var send_attachment_bkp = wp.media.editor.send.attachment;

    wp.media.editor.send.attachment = function(props, attachment) {

        jQuery('#meta-image').val(attachment.url);
        jQuery('#meta-image-preview').attr('src',attachment.url);
        wp.media.editor.send.attachment = send_attachment_bkp;
    }

    wp.media.editor.open();

    return false;
});