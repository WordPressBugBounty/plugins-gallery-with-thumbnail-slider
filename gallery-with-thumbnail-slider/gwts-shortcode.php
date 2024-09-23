<?php 
if(!defined('ABSPATH')){
	exit;
}
/* call shortcode for display gallery slider. */
function gwts_gwl_gallery_slider_shortcode($atts){
	$arry_arg = shortcode_atts(array('id'=>''),$atts);
	return gwts_gwl_shortcode_gallery_slider($arry_arg['id']);
}
add_shortcode('gwts_gwl_gallery_slider', 'gwts_gwl_gallery_slider_shortcode');

/* call shortcode for display all gallary posts grid */
function gwts_gwl_gallery_thumb_shortcode($attr){
	$gallery_arry = shortcode_atts(array('no_of_items'=>'12'),$attr);
	return gwts_gwl_shortcode_display_gallery_list($gallery_arry['no_of_items']);
}
add_shortcode('gwts_gwl_galleries_listing', 'gwts_gwl_gallery_thumb_shortcode');