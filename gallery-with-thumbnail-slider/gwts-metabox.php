<?php
/**
 * Register meta box(es).
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/* Register metabox for gallery */
function gwts_gwl_gallery_register_meta_boxes() {
	$getpostopt = get_option('gwts_gwl_posttypes');
	$getpostid = get_the_ID();	
	$getpostyp = get_post_type($getpostid);

	if(!empty($getpostopt)){
	    add_meta_box( 'gwts-gwl-metabox-id', __( 'GWTS Gallery', 'gallery-with-thumbnail-slider' ), 'gwts_gwl_gallery_display_callback',$getpostopt );
	}
	if($getpostyp == 'gwts-gallery'){
		add_meta_box( 'gwts-gwl-metabox-id', __( 'GWTS Gallery', 'gallery-with-thumbnail-slider' ), 'gwts_gwl_gallery_display_callback','gwts-gallery' );
	}
}
add_action( 'add_meta_boxes', 'gwts_gwl_gallery_register_meta_boxes' );
 
/* Meta box display callback. */
function gwts_gwl_gallery_display_callback( $post ) {
	$gwts_gallery_title = sanitize_text_field(get_post_meta($post->ID,'_gwts_gallery_title',true));

	$gwts_gallery_desc = sanitize_textarea_field(get_post_meta($post->ID,'_gwts_gallery_desc',true));
	$getpostid = get_the_ID();	
	$getpostyp = get_post_type($getpostid);	
	$switchslider = get_post_meta($post->ID,'gwts_gwl_switcher',true);
?>

    <!--gallery Title-->
    <p class="gwt-gwlgallery"><label for="_gwts_gallery_title"><?php esc_html_e('Title', 'gallery-with-thumbnail-slider'); ?></label> <input type="text" name="_gwts_gallery_title" class="regular-text" id="gwt-gwl-title" value="<?php if(!empty($gwts_gallery_title)){echo esc_html($gwts_gallery_title);}?>" ></p>

    <!--Gallery Discription-->
    <p class="gwt-gwlgallery"><label for="_gwts_gallery_desc"><?php esc_html_e('Description', 'gallery-with-thumbnail-slider'); ?></label> <textarea name="_gwts_gallery_desc" class="regular-text" id="gwts-gwl-desc"><?php if(!empty($gwts_gallery_desc)){ echo esc_html($gwts_gallery_desc); } ?></textarea></p>
    
    <!--image uploader-->
    <p class="gwt-gwlgallery">
		<label for="gwts_fields[image]"><?php esc_html_e('Upload Images', 'gallery-with-thumbnail-slider'); ?></label>
		<input type="button" class="button gwts-gwl-imgupload" value="Browse Images">
	</p>

	<div id ="gwts-gwl-sortableitem" class="image-preview">
	<?php 
	 $getimag = get_post_meta($post->ID,'_gwts_gwl_attachment_id',true);
	 if(!empty($getimag)){
	 	foreach ($getimag as $imgvalue) {
	 		$attchimg = wp_get_attachment_image_src($imgvalue,'full'); ?>
	 			<div class="gwt-gwlgalleryimg">
	 				<img src="<?php echo esc_url($attchimg[0]); ?>" title="img<?php echo esc_attr($imgvalue); ?>">
           	<input id="gwts-gwl-image-input<?php echo esc_attr($imgvalue); ?>" type="hidden" name="_gwts_gwl_attachment_id[]"  value="<?php echo esc_attr($imgvalue); ?>">
           	<input class="gwts-gwl-image-delete" type="button" name="_gwts_gwl_delete_img_item"  data-dlt="<?php echo esc_attr($imgvalue); ?>" value="Delete this">
      	</div>
	<?php } } ?>
	</div>
	<hr>
	<div class="showshrotcode"><h4><?php esc_html_e('Use this shortcode to display gallery slider.', 'gallery-with-thumbnail-slider'); ?></h4>[gwts_gwl_gallery_slider id="<?php echo $post->ID; ?>"]</div>
	<?php if($getpostyp != 'gwts-gallery'){ ?>
		<div class="switchslider">
			<h4>
				<span class="gwlslideroff"><?php esc_html_e('Disable slider', 'gallery-with-thumbnail-slider'); ?></span>
				<input type="checkbox" name="gwts_gwl_switcher" value="true" <?php if(!empty($switchslider) && $switchslider == "true"){ echo "checked"; } ?> >
			</h4>
		</div>
	<?php } ?>
  	<script>
	    jQuery( function() {
		    jQuery( "#gwts-gwl-sortableitem" ).sortable();
		  });
	    	
	    jQuery(document).on("click",'.gwts-gwl-image-delete',function (e) {
		    var tst = jQuery(this).parent().remove();
		  });

  	</script>  	
	<?php wp_nonce_field( 'gwts_gwl_gallery_nounce', 'gwts_gwl_gallery_nounce_field' ); ?>

<?php }
 
/* Save meta box content. */
function gwts_gwl_gallery_save_meta_box( $post_id ) {
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  /* if our current user can't edit this post */
  if( !current_user_can( 'edit_posts' ) ) return;
  /* if our nonce isn't there, or we can't verify it */
  if(!isset($_REQUEST['gwts_gwl_gallery_nounce_field']) || ! wp_verify_nonce( $_REQUEST['gwts_gwl_gallery_nounce_field'], 'gwts_gwl_gallery_nounce')){
  	return;
  }
  else{
  	if(isset($_REQUEST['_gwts_gallery_title'])){
    	update_post_meta($post_id,'_gwts_gallery_title', sanitize_text_field($_REQUEST['_gwts_gallery_title']));
    }
    if(isset($_REQUEST['_gwts_gallery_desc'])){
    	update_post_meta($post_id,'_gwts_gallery_desc', sanitize_textarea_field($_REQUEST['_gwts_gallery_desc']));
    }
    if(isset($_REQUEST['_gwts_gwl_attachment_id'])){
    	if(!empty($_REQUEST['_gwts_gwl_attachment_id'])){
    	update_post_meta($post_id, '_gwts_gwl_attachment_id', rest_sanitize_array($_REQUEST['_gwts_gwl_attachment_id']));	    	
    	}	    	
    }
    else{
  		update_post_meta($post_id, '_gwts_gwl_attachment_id', '');
  	}
  } 
	update_post_meta($post_id,'gwts_gwl_switcher', sanitize_text_field($_REQUEST['gwts_gwl_switcher']));
}
add_action( 'save_post', 'gwts_gwl_gallery_save_meta_box' );