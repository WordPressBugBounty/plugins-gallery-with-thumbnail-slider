<?php
/**
 * Register meta box(es).
 */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/* Register metabox for vertical slider settings */
function gwts_gwl_gallery_register_meta_boxes_verticalslide() {
	$getpostopt = get_option('gwts_gwl_posttypes');
	$getpostid = get_the_ID();	
	$getpostyp = get_post_type($getpostid);
	if(!empty($getpostopt)){
		if (in_array($getpostyp, $getpostopt)){
			add_meta_box( 'gwts-gwl-vslider-settng', __('Vertical Thumb Gallery Settings', 'gallery-with-thumbnail-slider' ), 'gwts_gwl_gallery_display_callback_for_verticalslid', $getpostyp, 'side' );
		}		
	}
	add_meta_box( 'gwts-gwl-vslider-settng', __('Vertical Thumb Gallery Settings', 'gallery-with-thumbnail-slider'), 'gwts_gwl_gallery_display_callback_for_verticalslid','gwts-gallery', 'side' );
}
add_action( 'add_meta_boxes', 'gwts_gwl_gallery_register_meta_boxes_verticalslide' );

function gwts_gwl_gallery_display_callback_for_verticalslid($post){ 
	wp_enqueue_script( 'gwts-gwl-veticlegal' );
	if( null!== get_post_meta( get_the_ID(), '_gwtsVerticalOpt', true )){
	  $sliderRange = get_post_meta(get_the_ID(), '_gwtsVerticalOpt', true);
	}
	  $sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
	  $sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';
	  $thumbnlWidth = !empty($sliderRange) ? $sliderRange[2] : '100';
	  $maxThumbItm = !empty($sliderRange) ? $sliderRange[3] : '6';

	if( null!== get_post_meta( get_the_ID(), '_gwtsSetVertBreakpoints', true )){
	  $sliderBreakpoints = get_post_meta(get_the_ID(), '_gwtsSetVertBreakpoints', true);
	}
		$vheight480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[0] : '200'; 
		$vthumb480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[1] : '4';
		
		$vheight641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[2] : '300';
		$vthumb641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[3] : '6';

		$vheight800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[4] : '370';
		$vthumb800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[5] : '6';

		//BG Color
		if( null!== get_post_meta( get_the_ID(), '_gwtsslider_bgcolor', true )){
			$sliderBgColor = get_post_meta(get_the_ID(), '_gwtsslider_bgcolor', true);
		}
		$sliderBgColor = !empty($sliderBgColor) ? $sliderBgColor : '#ffffff'; 

		if( null!== get_post_meta( get_the_ID(), '_gwtsslider_pleft', true )){
			$sliderPLeft = get_post_meta(get_the_ID(), '_gwtsslider_pleft', true);
		}
		$sliderPLeft = !empty($sliderPLeft) ? $sliderPLeft : '0px';
		if( null!== get_post_meta( get_the_ID(), '_gwtsslider_pright', true )){
			$sliderPRight = get_post_meta(get_the_ID(), '_gwtsslider_pright', true);
		}	
		$sliderPRight = !empty($sliderPRight) ? $sliderPRight : '0px';
		if( null!== get_post_meta( get_the_ID(), '_gwtsslider_ptop', true )){
			$sliderPTop = get_post_meta(get_the_ID(), '_gwtsslider_ptop', true);
		}	
		$sliderPTop = !empty($sliderPTop) ? $sliderPTop : '0px';
		if( null!== get_post_meta( get_the_ID(), '_gwtsslider_pbottom', true )){
			$sliderPBottom = get_post_meta(get_the_ID(), '_gwtsslider_pbottom', true);
		}		
		$sliderPBottom = !empty($sliderPBottom) ? $sliderPBottom : '0px';
?>	
	<div class="gwts-vertcleslide-container">	
		<!-- Enable vertical gallery -->
		<p class="enble_ver_box"><label for="vertical_slider"><strong><?php echo esc_html('Enable Vertical Gallery'); ?></strong></label>
				<input class="gwts-verti-checkbx" type="checkbox" id="enable_vrticle_gal" name="enable_vrticle_gal" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtsvertical_gal', true))){echo "checked"; } ?>>			
		</p>
		<p><small><?php echo esc_html('To better view of the slider, use constant images size and customize below options according to them. All these options work when the vertical gallery is enabled.'); ?></small></p>
		<!-- Enable zoom image -->
		<p class="enble_ver_box"><label for="vertical_slider"><strong><?php echo esc_html('Enable Image Zoom'); ?></strong></label>
				<input class="gwts-verti-checkbx" type="checkbox" name="enable_image_zoom" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtsimage_zoom', true))){echo "checked"; } ?>>			
		</p>
		
		<!-- Hide Title -->
		<p class="enble_ver_box"><label for="vertical_slider"><strong><?php echo esc_html('Enable Hide Title'); ?></strong></label>
				<input class="gwts-verti-checkbx" type="checkbox" name="enable_hide_title" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtshide_title', true))){echo "checked"; } ?>>			
		</p>

		<!-- Hide Description -->
		<p class="enble_ver_box"><label for="vertical_slider"><strong><?php echo esc_html('Enable Hide Description'); ?></strong></label>
				<input class="gwts-verti-checkbx" type="checkbox" name="enable_hide_description" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtshide_description', true))){echo "checked"; } ?>>			
		</p>

		<!-- Enable Alignment -->
	  <p><strong><?php echo esc_html( 'Select Thumbnail Alignment : ' ); ?></strong>
			<select name="gwts_gwl_alignment" id="gwts-gwl-thumbnail-alignment">
			 	<option value="left" <?php if(get_post_meta($post->ID, '_gwtsslider_alignment', true) == "left"){echo "selected";} ?>>Left</option>
			 	<option value="right" <?php if(get_post_meta($post->ID, '_gwtsslider_alignment', true) == "right"){echo "selected";} ?>>Right</option>
			 	<option id="center_align" value="center" <?php if(get_post_meta($post->ID, '_gwtsslider_alignment', true) == "center"){echo "selected";} ?>>Center</option>
			</select>
		</p>
	<!-- Enable Background Color -->
	<p><strong><?php echo esc_html( 'Slider Backgorund Color (Ex. #FFFFFF) : ' ); ?></strong>
		<input type="text" name="gwts_gwl_bgclor" id="gwts-gwl-bgcolor" value="<?php echo esc_attr($sliderBgColor); ?>">
	</p>
	<!-- Enable Slider Paddding (px) -->
	<p><strong><?php echo esc_html( 'Padding Left (Ex. 15px) : ' ); ?></strong>
		<input type="text" name="gwts_gwl_pleft" id="gwts-gwl-pleft" value="<?php echo esc_attr($sliderPLeft); ?>">
	</p>
	<p><strong><?php echo esc_html( 'Padding Right (Ex. 15px) : ' ); ?></strong>
		<input type="text" name="gwts_gwl_pright" id="gwts-gwl-pright" value="<?php echo esc_attr($sliderPRight); ?>">
	</p>
	<p><strong><?php echo esc_html( 'Padding Top (Ex. 15px) : ' ); ?></strong>
		<input type="text" name="gwts_gwl_ptop" id="gwts-gwl-ptop" value="<?php echo esc_attr($sliderPTop); ?>">
	</p>
	<p><strong><?php echo esc_html( 'Padding Bottom (Ex. 15px) : ' ); ?></strong>
		<input type="text" name="gwts_gwl_pbottom" id="gwts-gwl-pbottom" value="<?php echo esc_attr($sliderPBottom); ?>">
	</p>
    <!-- slider width -->
    <p><strong><?php echo esc_html('Slider Width: ');?></strong><span id="gwts_displyVert_wdth"></span>px</p>
    <input type="range" name="gwtsVerticalOpt[]" min="320" max="2200" value="<?php echo esc_attr($sliderWidth); ?>" class="gwts-opt-vtcle" id="gwts_vrt_width">

    <!-- slider height -->
    <p><strong><?php echo esc_html('Vertical Height: ');?></strong><span id="gwts_Vslide_height"></span>px</p>
    <input type="range" name="gwtsVerticalOpt[]" min="100" max="900" value="<?php echo esc_attr($sliderHeight); ?>" class="gwts-opt-vtcle" id="gwts_vrt_height">

    <!-- Thumb Width -->
    <p><strong><?php echo esc_html('Thumbnail Width: ');?></strong><span id="gwts_thumbVWidt"></span>px</p>
    <input type="range" name="gwtsVerticalOpt[]" min="50" max="200" value="<?php echo esc_attr($thumbnlWidth); ?>" class="gwts-opt-vtcle" id="gwts_thmb_width">

	  <!-- Thumb Item -->
    <p><strong><?php echo esc_html('Show Thumbnail: ');?></strong><span id="gwts_show_maxthumb"></span></p>
      <input type="range" name="gwtsVerticalOpt[]" min="2" max="20" value="<?php echo esc_attr($maxThumbItm); ?>" class="gwts-opt-vtcle" id="gwts_max_thumb">  

    <!-- Navigation -->
    <p class="enble_ver_box"><label for="vertical_slider"><?php echo esc_html('Show Next/Prev Arrows'); ?></label>
		<input class="gwts-verti-checkbx" type="checkbox" name="gwtsVerticalcontrl" value="1" <?php if(!empty(get_post_meta($post->ID, '_gwtsVerticalcontrl', true))){echo "checked"; } ?>>
		</p>
		<hr/>
		<p class="enble_ver_box gwts-rsponsivmode"><strong><label><?php echo esc_html('Set Different breakpoints for responsive Gallery');?></label></strong></p>
		<small><?php echo esc_html('Set gallery size in the different breakpoints.'); ?></small>

		<!-- Set breakpoints to 480px  -->
		<p><strong><?php echo esc_html('Breakpoint 480px'); ?></strong></p>
		<p><?php echo esc_html('Vertical Height: ');?><span id="gwts_Vslide_brkheight"></span>px</p>
	    <input type="range" name="gwtsSetVertBreakpoints[]" min="100" max="900" value="<?php echo esc_attr($vheight480); ?>" class="gwts-opt-vtcle" id="gwts_brekvrt_height">
	    
	    <p><?php echo esc_html('Thumb Items: ');?><span id="gwts_show_brkmaxthumb"></span></p><input type="range" name="gwtsSetVertBreakpoints[]" min="2" max="20" value="<?php echo esc_attr($vthumb480); ?>" class="gwts-opt-vtcle" id="gwts_max_brkthumb">  

	  <!-- Set breakpoints to 641px  -->  
		<p><strong><?php echo esc_html('Breakpoint 641px'); ?></strong></p>
		<p><?php echo esc_html('Vertical Height: ');?><span id="gwts_Vslide_sixfouroneheight"></span>px</p>
    <input type="range" name="gwtsSetVertBreakpoints[]" min="100" max="900" value="<?php echo esc_attr($vheight641); ?>" class="gwts-opt-vtcle" id="gwts_vrt_sixfoheight">
    
    <p><?php echo esc_html('Thumb Items: ');?><span id="gwts_show_sixfomaxthumb"></span></p><input type="range" name="gwtsSetVertBreakpoints[]" min="2" max="20" value="<?php echo esc_attr($vthumb641); ?>" class="gwts-opt-vtcle" id="gwts_max_sixforthumb">  

		<!-- Set breakpoints to 800px  -->
		<p><strong><?php echo esc_html('Breakpoint 800px'); ?></strong></p>
		<p><?php echo esc_html('Vertical Height: ');?><span id="gwts_Vslide_eightheight"></span>px</p>
    <input type="range" name="gwtsSetVertBreakpoints[]" min="100" max="900" value="<?php echo esc_attr($vheight800); ?>" class="gwts-opt-vtcle" id="gwts_vrt_eightheight">
    <p><?php echo esc_html('Thumb Items: ');?><span id="gwts_show_eightmaxthumb"></span></p><input type="range" name="gwtsSetVertBreakpoints[]" min="2" max="20" value="<?php echo esc_attr($vthumb800); ?>" class="gwts-opt-vtcle" id="gwts_max_eigthumb">  
	</div>

	<?php wp_nonce_field( basename(__FILE__), 'gwts_enable_gallery_setting_nonce' ); ?>

	<script>
		jQuery(function(){
	   	jQuery('#enable_vrticle_gal').change(function() {
	      jQuery("#center_align").toggleClass("show-hide");
	   	});
		});	
	</script>
	<style> 
		.show-hide{ display: none; }
	</style>

<?php 
}

function gwts_gwl_vertical_gallery_callback($post_id){

	$nonce_value = isset($_POST['gwts_enable_gallery_setting_nonce']) ? sanitize_text_field(wp_unslash($_POST['gwts_enable_gallery_setting_nonce'])) : '';
	if (!wp_verify_nonce($nonce_value, basename(__FILE__))) {
		return;
	}

	/* show vertical gallery */
	if(isset($_POST['enable_vrticle_gal'])){
		update_post_meta($post_id,'_gwtsvertical_gal', sanitize_text_field( wp_unslash($_POST['enable_vrticle_gal'])));	
	}
	else{
		update_post_meta($post_id,'_gwtsvertical_gal', '');
	}

	if(isset($_POST['enable_image_zoom'])){
		update_post_meta($post_id,'_gwtsimage_zoom', sanitize_text_field( wp_unslash($_POST['enable_image_zoom'])));	
	}
	else{
		update_post_meta($post_id,'_gwtsimage_zoom', '');
	}

	if(isset($_POST['enable_hide_title'])){
		update_post_meta($post_id,'_gwtshide_title', sanitize_text_field( wp_unslash($_POST['enable_hide_title'])));
	}
	else{
		update_post_meta($post_id,'_gwtshide_title', '');
	}

	if(isset($_POST['enable_hide_description'])){
		update_post_meta($post_id,'_gwtshide_description', sanitize_text_field( wp_unslash($_POST['enable_hide_description'])));		
	}
	else{
		update_post_meta($post_id,'_gwtshide_description', '');
	}

	if(isset($_POST['gwts_gwl_alignment'])){
		update_post_meta($post_id,'_gwtsslider_alignment', sanitize_text_field( wp_unslash($_POST['gwts_gwl_alignment'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsslider_alignment', '');
	}

	if(isset($_POST['gwts_gwl_bgclor'])){
		update_post_meta($post_id,'_gwtsslider_bgcolor', sanitize_text_field( wp_unslash($_POST['gwts_gwl_bgclor'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsslider_bgcolor', '');
	}

	// Slider Paddings
	if(isset($_POST['gwts_gwl_pleft'])){
		update_post_meta($post_id,'_gwtsslider_pleft', sanitize_text_field( wp_unslash($_POST['gwts_gwl_pleft'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsslider_pleft', '');
	}
	if(isset($_POST['gwts_gwl_pright'])){
		update_post_meta($post_id,'_gwtsslider_pright', sanitize_text_field(wp_unslash($_POST['gwts_gwl_pright'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsslider_pright', '');
	}
	if(isset($_POST['gwts_gwl_ptop'])){
		update_post_meta($post_id,'_gwtsslider_ptop', sanitize_text_field(wp_unslash($_POST['gwts_gwl_ptop'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsslider_ptop', '');
	}
	if(isset($_POST['gwts_gwl_pbottom'])){
		update_post_meta($post_id,'_gwtsslider_pbottom', sanitize_text_field(wp_unslash($_POST['gwts_gwl_pbottom'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsslider_pbottom', '');
	}

	/*show navigation*/
	if(isset($_POST['gwtsVerticalcontrl'])){
		update_post_meta($post_id,'_gwtsVerticalcontrl', sanitize_text_field( wp_unslash($_POST['gwtsVerticalcontrl'])));		
	}
	else{
		update_post_meta($post_id,'_gwtsVerticalcontrl', '');
	}
	
	/*update settings*/
	if(isset($_POST['gwtsVerticalOpt']) && is_array($_POST['gwtsVerticalOpt'])){
		$sanitized_array = array_map('sanitize_text_field', wp_unslash($_POST['gwtsVerticalOpt']));
		update_post_meta($post_id, '_gwtsVerticalOpt', $sanitized_array);
	}
	
	/*update breakpoints*/
	if(isset($_POST['gwtsSetVertBreakpoints']) && is_array($_POST['gwtsSetVertBreakpoints'])){
		$sanitized_array = array_map('sanitize_text_field', wp_unslash($_POST['gwtsSetVertBreakpoints']));
		update_post_meta($post_id, '_gwtsSetVertBreakpoints', $sanitized_array);
	}
}
add_action('save_post','gwts_gwl_vertical_gallery_callback');
