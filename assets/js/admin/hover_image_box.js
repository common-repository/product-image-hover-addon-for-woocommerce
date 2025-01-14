/**
 * Widgets - Media Upload
 */
jQuery( document ).ready( function() {

    // Upload / Change Image
    function wih_image_upload( button_class ) {
        
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

        jQuery( 'body' ).on( 'click', button_class, function(e) {

            var button_id = '#' + jQuery( this ).attr( 'id' ),
                self = jQuery( button_id),
                send_attachment_bkp = wp.media.editor.send.attachment,
                button = jQuery( button_id ),
                id = button.attr( 'id' ).replace( '-button', '' );

            _custom_media = true;

            wp.media.editor.send.attachment = function( props, attachment ){

                if ( _custom_media ) {
					button.css( 'display', 'none' );
                    jQuery( '#' + id + '-preview' ).attr( 'src', attachment.url ).css( 'display', 'block' );
                    jQuery( '#' + id + '-remove' ).css( 'display', 'inline-block' );
                    jQuery( '#' + id + '-noimg' ).css( 'display', 'none' );
                    jQuery( '#' + id ).val( attachment.url ).trigger( 'change' );  

                } else {

                    return _orig_send_attachment.apply( button_id, [props, attachment] );

                }
            }

            wp.media.editor.open( button );

            return false;
        });
    }
    wih_image_upload( '.wih-media-upload' );

    // Remove Image
    function wih_image_remove( button_class ) {

        jQuery( 'body' ).on( 'click', button_class, function(e) {

            var button = jQuery( this ),
                id = button.attr( 'id' ).replace( '-remove', '' );

            jQuery( '#' + id + '-preview' ).css( 'display', 'none' );
            jQuery( '#' + id + '-noimg' ).css( 'display', 'block' );
			jQuery( '#' + id + '-button' ).css( 'display', 'inline-block' );
			jQuery( '#' + id ).val( '' );
            button.css( 'display', 'none' );
            jQuery( '#' + id ).val( '' ).trigger( 'change' );

        });
    }
    wih_image_remove( '.wih-media-remove' );

});