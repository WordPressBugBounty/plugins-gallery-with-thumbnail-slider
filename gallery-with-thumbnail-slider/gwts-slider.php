<?php
if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

function gwts_gwl_filter_the_content_in_the_main_loop( $content ) {
	$getpostopt = get_option('gwts_gwl_posttypes');
	if(empty($getpostopt)){
		$getpostopt = array();
	}
	array_push($getpostopt,'gwts-gallery');
	$postid = get_the_ID();	
	$getpostyp = get_post_type($postid);
	$outputgal = '';
	$_switchslider = get_post_meta($postid, 'gwts_gwl_switcher', true);

	$sliderbgcolor = get_post_meta($postid, '_gwtsslider_bgcolor', true);

	$sliderPLeft = get_post_meta($postid, '_gwtsslider_pleft', true);
	$sliderPRight = get_post_meta($postid, '_gwtsslider_pright', true);
	$sliderPTop = get_post_meta($postid, '_gwtsslider_ptop', true);
	$sliderPBottom = get_post_meta($postid, '_gwtsslider_pbottom', true);
	$sliderPadding = $sliderPTop.' '.$sliderPRight.' '.$sliderPBottom.' '.$sliderPLeft;

	$lboxdownload 	= get_option('gwts_gwl_lightbx_download');
	if(empty($lboxdownload)){
		$lboxdownload = false;
	}
	
	if($_switchslider != 'true'){
		if(!empty($getpostopt)){
			if(in_array($getpostyp, $getpostopt)){

			 	$getimag = get_post_meta($postid, '_gwts_gwl_attachment_id', true);
			 	$getttl = get_post_meta($postid, '_gwts_gallery_title', true);
			 	$getdescription = get_post_meta($postid, '_gwts_gallery_desc', true);

				if(!empty($getimag)){ 
					ob_start(); 
					if(!empty($getttl) || !empty($getdescription)) {?>
					<div class="gwts-gwl-prev-gallery">
						<?php if(!empty($getttl)) { ?>				
						<p class="gwts-gwl-prev-title"><strong><?php echo esc_html($getttl);?></strong></p> <?php } ?>
						<?php if(!empty($getdescription)) { ?>
						<p class="gwts-gwl-prev-desc"><?php echo esc_html($getdescription);?></p> <?php } ?>
					</div>
					<?php } 
					if(null !== get_post_meta($postid, '_gwtsvertical_gal', true) && !empty(get_post_meta($postid, '_gwtsvertical_gal', true))){
						if( null!== get_post_meta( $postid, '_gwtsVerticalOpt', true )){
						   $VssliderRange = get_post_meta($postid, '_gwtsVerticalOpt', true);
						   $smaxwidth = $VssliderRange[0];
						}
					}
					else{
						$smaxwidth = get_option('gwts_gwl_sliderwidth');
					}
					/* Fetch Thumbnail image size */
					$thumbsize = get_option('gwts_gwl_slider_thumb_size');
					if (!empty($thumbsize)) {
						$thumbsize = get_option('gwts_gwl_slider_thumb_size');
					}else{
						$thumbsize = 'thumbnail';
					}
					?>
				 	<div class="item gwts-gwl-prev-gallery-items" style="<?php if(!empty($sliderbgcolor)){ ?>background-color:<?php esc_html_e($sliderbgcolor, 'gallery-with-thumbnail-slider'); ?>;<?php } ?>padding: <?php echo $sliderPadding; ?>;">
			        	<div class="clearfix" <?php if(!empty($smaxwidth)){ ?> style="max-width:<?php esc_html_e($smaxwidth, 'gallery-with-thumbnail-slider') ?>px;" <?php } ?>>
				 			
				 		<?php $lboxswitchr = get_option('gwts_gwl_lightbx_switcher'); ?>
			        <ul id="gwts-gwl-img-gallery" class="gwts-gwl-slidergal list-unstyled cS-hidden" data-litebx="<?php if(!empty($lboxswitchr)){echo $lboxswitchr;}else{echo "false";}?>">
						   	
						   	<?php
							 	foreach ($getimag as $imgvalue) {

							 		$attchimg = wp_get_attachment_image_src($imgvalue,'full');
							 		$thumbnailimg = wp_get_attachment_image_src($imgvalue, $thumbsize);
							 		$image_alt = get_post_meta($imgvalue, '_wp_attachment_image_alt', true);
								?>
							 		<li data-thumb="<?php echo esc_url($thumbnailimg[0]); ?>" data-responsive="<?php echo esc_url($thumbnailimg[0]); ?>" data-src="<?php echo esc_url($attchimg[0]); ?>"> 
				            <img src="<?php echo esc_attr($attchimg[0]); ?>" alt="<?php echo esc_attr($image_alt);?>"/>
				          </li>
							 	<?php } ?>
							</ul>
						</div>
					</div>				
					<?php 
						$gallitms = get_option('gwts_gwl_gallery_numberof_items');
						if(!empty($gallitms)){
							$gallitms = $gallitms;
						}
						else{
							$gallitms = 1;
						}
						$getmargin = get_option('gwts_gwl_slidemargin');
						if(!empty($getmargin)){
							$getmargin = $getmargin;
						}
						else{
							$getmargin = 10;
						}
						$addclss = get_option('gwts_gwl_classtoslider');
						$sliderspd = get_option('gwts_gwl_speedslider');
						if(!empty($sliderspd)){
							$sliderspd = $sliderspd;
						}
						else{
							$sliderspd = 500;
						}
						$spause = get_option('gwts_gwl_slideinterval');
						if(!empty($spause)){
							$spause = $spause*1000;
						}
						else{
							$spause = 2000;
						}
						$smode = get_option('gwts_gwl_slidermode');
						if(!empty($smode)){
							$smode = $smode;
						}
						else{
							$smode = "false";
						}
						$sloop = get_option('gwts_gwl_allow_looping');
						if(!empty($sloop)){
							$sloop = $sloop;
						}
						else{
							$sloop = "false";
						}
						$spager = get_option('gwts_gwl_slider_pagination');
						if(!empty($spager)){
							$spager = $spager;
						}
						else{
							$spager = "true";
						}
						$sgallery = get_option('gwts_gwl_slider_menuoption');
						if(!empty($sgallery)){
							$sgallery = $sgallery;
						}
						else{
							$sgallery = "true";
						}
						$sthumbitem = get_option('gwts_gwl_numberof_thumbitems');
						if(!empty($sthumbitem)){
							$sthumbitem = $sthumbitem;
						}
						else{
							$sthumbitem = 9;
						}

						$s_nav = get_option('gwts_gwl_slider_navigation');
						if(!empty($s_nav)){
							$s_nav = $s_nav;
						}
						else{
							$s_nav = "true";
						}
						
						if(null !== get_post_meta($postid, '_gwtsvertical_gal', true) && !empty(get_post_meta($postid, '_gwtsvertical_gal', true))){
							if( null!== get_post_meta( $postid, '_gwtsVerticalOpt', true )){
							    $sliderRange = get_post_meta($postid, '_gwtsVerticalOpt', true);
							}

							$sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
							$sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';
							$thumbnlWidth = !empty($sliderRange) ? $sliderRange[2] : '100';
							$maxThumbItm = !empty($sliderRange) ? $sliderRange[3] : '6';

							if(!empty(get_post_meta($postid, '_gwtsVerticalcontrl', true))){
							  	$contrlNav = 'true';
							}
							else{
							  	$contrlNav = 'false';
							}
							  
							if( null!== get_post_meta( get_the_ID(), '_gwtsSetVertBreakpoints', true )){
							   $sliderBreakpoints = get_post_meta(get_the_ID(), '_gwtsSetVertBreakpoints', true);
							} 
							$vheight480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[0] : '200'; 
							$vthumb480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[1] : '4';
							
							$vheight641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[2] : '300';
							$vthumb641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[3] : '6';

							$vheight800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[4] : '370';
							$vthumb800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[5] : '6';
							?>
							<script>
							jQuery(document).ready(function() {
								var setting_download = '<?php echo $lboxdownload; ?>';
                var count  = 0
                  if (count === 1) return;
                  jQuery('#gwts-gwl-img-gallery').addClass('cS-hidden');
                  jQuery('#gwts-gwl-img-gallery').lightSlider({	
                    gallery:true,	                        
                    speed:<?php esc_html_e($sliderspd, 'gallery-with-thumbnail-slider');?>,
                    pause:<?php esc_html_e($spause, 'gallery-with-thumbnail-slider');?>,
                    auto:<?php esc_html_e('fade', 'gallery-with-thumbnail-slider');?>,
                    item: 1,
									  loop: <?php esc_html_e($sloop, 'gallery-with-thumbnail-slider');?>,
									  thumbItem: <?php esc_html_e($maxThumbItm, 'gallery-with-thumbnail-slider'); ?>,
									  vertical: true,
									  verticalHeight:<?php esc_html_e($sliderHeight, 'gallery-with-thumbnail-slider'); ?>,
									  vThumbWidth:<?php esc_html_e($thumbnlWidth, 'gallery-with-thumbnail-slider'); ?>,
									  thumbMargin:4,
									  controls:<?php esc_html_e($contrlNav, 'gallery-with-thumbnail-slider'); ?>,
									  responsive : [
				            {
				              breakpoint:800,
		                	settings: {
		                    item:1,
		                    slideMove:1,
		                    verticalHeight:<?php esc_html_e($vheight800, 'gallery-with-thumbnail-slider'); ?>,
		                    thumbItem:<?php esc_html_e($vthumb800, 'gallery-with-thumbnail-slider'); ?>,
		                  }
				            },
				            {
			                breakpoint:641,
			                settings: {
		                    item:1,
		                    slideMove:1,
		                    verticalHeight:<?php esc_html_e($vheight641, 'gallery-with-thumbnail-slider'); ?>,
		                    thumbItem:<?php esc_html_e($vthumb641, 'gallery-with-thumbnail-slider'); ?>,
		                  }
				            },
				            {
			                breakpoint:480,
			                settings: {
		                    item:1,
		                    slideMove:1,
		                    verticalHeight:<?php esc_html_e($vheight480, 'gallery-with-thumbnail-slider'); ?>,
		                    thumbItem:<?php esc_html_e($vthumb480, 'gallery-with-thumbnail-slider'); ?>,
		                  }
				            },								            
				        		],
                    onSliderLoad: function(obj) {
                      jQuery('#gwts-gwl-img-gallery').removeClass('cS-hidden');
                      var lithbox = jQuery('#gwts-gwl-img-gallery').attr("data-litebx");
											
											if(lithbox=='true'){
												obj.lightGallery({
													download: setting_download,
			                		selector: '#gwts-gwl-img-gallery .lslide'
			            			});
											}            
                  	} 
                  });
			            
			            count++;
				        });
				      </script>
						<?php } else { ?>

						<script>
				    	jQuery(document).ready(function() {	
							var setting_download = '<?php echo $lboxdownload; ?>';		    	 	
	            	jQuery('#gwts-gwl-img-gallery').lightSlider({		                
                	item:<?php esc_html_e($gallitms, 'gallery-with-thumbnail-slider');?>,
                	slideMargin:<?php esc_html_e($getmargin, 'gallery-with-thumbnail-slider');?>,
                	addClass:'<?php esc_html_e($addclss, 'gallery-with-thumbnail-slider');?>',
                	speed:<?php esc_html_e($sliderspd, 'gallery-with-thumbnail-slider');?>,
                	pause:<?php esc_html_e($spause, 'gallery-with-thumbnail-slider');?>,
                	auto:<?php esc_html_e($smode, 'gallery-with-thumbnail-slider');?>,
                	loop:<?php esc_html_e($sloop, 'gallery-with-thumbnail-slider');?>,
                	pager:<?php esc_html_e($spager, 'gallery-with-thumbnail-slider');?>,
                	gallery:<?php esc_html_e($sgallery, 'gallery-with-thumbnail-slider');?>,
                	thumbItem:<?php esc_html_e($sthumbitem, 'gallery-with-thumbnail-slider');?>,			 
                	controls:<?php esc_html_e($s_nav,'gallery-with-thumbnail-slider');?>,
      						useCSS: true,
      						cssEasing: 'ease',
	        				easing: 'linear',
	        				keyPress: false,
	        				slideEndAnimation: true,
	        				swipeThreshold: 40,
	                onSliderLoad: function(el) {
                    jQuery('#gwts-gwl-img-gallery').removeClass('cS-hidden');
                    jQuery('#gwts-gwl-img-gallery').addClass('gwts-loaded');

										var maxHeight = 0,
										container = jQuery(el),
										children = container.children();
										children.each(function () {
											var childHeight = jQuery(this).height();
											
											if (childHeight > maxHeight) {
												maxHeight = childHeight;
											}
										});
									container.height(maxHeight);
									var lithbox = jQuery('#gwts-gwl-img-gallery').attr("data-litebx");
						
									if(lithbox=='true'){
										el.lightGallery({
											download: setting_download,
				              selector: '.gwts-gwl-slidergal .lslide'
				            });
									}
	              }, 
	            });
						});
				  </script>			    	
				 	<?php }
				 $outputgal = ob_get_clean();			 
				}
			}
		}
	}
	return $content.$outputgal;
}
add_filter( 'the_content', 'gwts_gwl_filter_the_content_in_the_main_loop' );