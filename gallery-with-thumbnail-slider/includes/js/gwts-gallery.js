
jQuery(document).ready(function ($) {
  // Instantiates the variable that holds the media library frame.
  var meta_image_frame;
  // Runs when the image button is clicked.
  $('.gwts-gwl-imgupload').click(function (e) {
    e.preventDefault();
    // If the frame already exists, re-open it.
    if (meta_image_frame) {
      meta_image_frame.open();
      return;
    }

    // Sets up the media library frame
    meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
      title: 'Select images to upload',
      button: {
        text: 'Use this image',
      },
      multiple: true
    });
    // Runs when an image is selected.
    meta_image_frame.on('select', function () {

      var attachments = meta_image_frame.state().get('selection').map( function( attachment ) {
                    attachment.toJSON();
                    return attachment;

            });

          //loop through the array and do things with each attachment
           var i;

           for (i = 0; i < attachments.length; ++i) {

                //sample function 1: add image preview
                $('#gwts-gwl-sortableitem').append(
                    '<div class="gwt-gwlgalleryimg"><img src="' + 
                    attachments[i].attributes.url + '" title="img' + 
                    attachments[i].id + '" >'
                    +'<input id="gwts-gwl-image-input' +  attachments[i].id + '" type="hidden" name="_gwts_gwl_attachment_id[]"  value="' + 
                    attachments[i].id + '"><input class="gwts-gwl-image-delete" type="button" name="_gwts_gwl_delete_img_item"  data-dlt="' + 
                    attachments[i].id + '" value="Delete this"></div>'
                    );

            }
    });
    // Opens the media library frame.
    meta_image_frame.open();
  });
    
});

