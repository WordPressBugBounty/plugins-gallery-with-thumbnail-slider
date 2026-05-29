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
	$simagezoom 	= get_post_meta($postid, '_gwtsimage_zoom', true);
	
	if(empty($lboxdownload)){
		$lboxdownload = false;
	}
	
	if($_switchslider != 'true'){
		if(!empty($getpostopt)){
			if(in_array($getpostyp, $getpostopt)){

			 	$getimag = get_post_meta($postid, '_gwts_gwl_attachment_id', true);
			 	$getttl = get_post_meta($postid, '_gwts_gallery_title', true);
			 	$getdescription = get_post_meta($postid, '_gwts_gallery_desc', true);

				if(!empty($getimag) && is_array($getimag)){ 
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

					$getverticalgal = get_post_meta($postid, '_gwtsvertical_gal', true);

					$slide_item = get_option('gwts_gwl_gallery_numberof_items');
					if(!empty($slide_item)){
						$slide_item = $slide_item;
					}
					else{
						$slide_item = 1;
					}

					$itemscls = "";
					if($slide_item == 1 && !$getverticalgal){
						$itemscls = "item-single";
					}

					?>
				 	<div class="item gwts-gwl-prev-gallery-items" style="<?php if(!empty($sliderbgcolor)){ ?>background-color:<?php echo esc_attr( $sliderbgcolor ); ?>;<?php } ?>padding: <?php echo esc_attr( $sliderPadding ); ?>;">
			        	<div class="clearfix" <?php if(!empty($smaxwidth)){ ?> style="max-width:<?php echo esc_attr( $smaxwidth ); ?>px;" <?php } ?>>
				 			
				 		<?php $lboxswitchr = get_option('gwts_gwl_lightbx_switcher'); ?>

			        	<ul id="gwts-gwl-img-gallery" class="gwts-gwl-slidergal list-unstyled cS-hidden <?php echo esc_attr( $itemscls ); ?>" data-litebx="<?php if(!empty($lboxswitchr)){ echo esc_attr($lboxswitchr); }else{ echo "false"; }?>">
						   	
						   	<?php
							   $scaption = get_option('gwts_gwl_enable_caption');
							 	foreach ($getimag as $imgvalue) {

							 		$attchimg = wp_get_attachment_image_src($imgvalue,'full');
							 		$thumbnailimg = wp_get_attachment_image_src($imgvalue, $thumbsize);
							 		$image_alt = get_post_meta($imgvalue, '_wp_attachment_image_alt', true);
									$decoded_alt = ! empty( $image_alt ) ? wp_specialchars_decode( $image_alt, ENT_QUOTES ) : '';
									$sanitized_alt = sanitize_text_field( $decoded_alt );
									$image_cap = gwts_gwl_get_attachment_caption( $imgvalue );
								?>
							 		<li data-thumb="<?php echo esc_url($thumbnailimg[0]); ?>" data-responsive="<?php echo esc_url($thumbnailimg[0]); ?>" data-src="<?php echo esc_url($attchimg[0]); ?>" class="<?php if(!empty($simagezoom)){ echo esc_attr('zoom'); }?>"> 
				                      <img src="<?php echo esc_attr($attchimg[0]); ?>" alt="<?php echo esc_attr( $sanitized_alt ); ?>"/>
				                      <?php if($scaption == 'true' && !empty($image_cap)): ?>
										<p><?php echo esc_html($image_cap);?></p>
									  <?php endif; ?>
									</li>
							 	<?php } ?>
						</ul>

						</div>
					</div>
					<style>
					.lightSlider li.lslide p { position: absolute; bottom: 0;color: #fff; padding: 10px; background: rgb(0 0 0 / 39%); width: 100%; text-align: center; font-size:20px;}
					.lightSlider li.lslide { position: relative; }
					</style>				

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
						
						// Start For Vertical Slider
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
								// Ensure lightGallery is available on jQuery
								if (typeof lightGallery !== 'undefined' && !jQuery.fn.lightGallery) {
									jQuery.fn.lightGallery = lightGallery;
								}
								var setting_download = '<?php echo esc_attr($lboxdownload); ?>';
								var count  = 0
								if (count === 1) return;
                  				
								jQuery('#gwts-gwl-img-gallery').addClass('cS-hidden');
                  				jQuery('#gwts-gwl-img-gallery').lightSlider({	
									gallery:true,	                        
									speed:<?php echo esc_attr($sliderspd);?>,
									pause:<?php echo esc_attr($spause);?>,
									auto:<?php echo esc_attr($smode);?>,
									item: 1,
									loop: <?php echo esc_attr($sloop);?>,
									thumbItem: <?php echo esc_attr($maxThumbItm); ?>,
									vertical: true,
									verticalHeight:<?php echo esc_attr($sliderHeight); ?>,
									vThumbWidth:<?php echo esc_attr($thumbnlWidth); ?>,
									thumbMargin:4,
									controls:<?php echo esc_attr($contrlNav); ?>,
									responsive : [
										{
											breakpoint:800,
											settings: {
												item:1,
												slideMove:1,
												verticalHeight:<?php echo esc_attr($vheight800); ?>,
												thumbItem:<?php echo esc_attr($vthumb800); ?>,
											}
										},
										{
											breakpoint:641,
											settings: {
												item:1,
												slideMove:1,
												verticalHeight:<?php echo esc_attr($vheight641); ?>,
												thumbItem:<?php echo esc_attr($vthumb641); ?>,
											}
										},
											{
											breakpoint:480,
											settings: {
												item:1,
												slideMove:1,
												verticalHeight:<?php echo esc_attr($vheight480); ?>,
												thumbItem:<?php echo esc_attr($vthumb480); ?>,
											}
										},								            
				        			],
									onSliderLoad: function(obj) {
										jQuery('#gwts-gwl-img-gallery').removeClass('cS-hidden');
										var lithbox = jQuery('#gwts-gwl-img-gallery').attr("data-litebx");
										if(lithbox=='true'){
											var galleryElement = jQuery('#gwts-gwl-img-gallery');
											var galleryItems = galleryElement.find('.lslide');
											if(galleryElement.length > 0 && galleryItems.length > 0 && typeof jQuery.fn.lightGallery !== 'undefined'){
												galleryElement.lightGallery({
													download: setting_download,
													galleryId: <?php echo absint( $postid ); ?>,
													selector: '#gwts-gwl-img-gallery li'
												});
											}
										}            
									} 
                  				});
								count++;
				        	});
				      		</script>
				      		
				      		<?php if(null !== $simagezoom && !empty($simagezoom)){ ?>
							<script>
								jQuery(function() {
									jQuery('#gwts-gwl-img-gallery .zoom').zoom();
								});
							</script>
							<?php } ?>
				      		
						<?php } else { ?>

						<script>
						jQuery(document).ready(function() {
							// Ensure lightGallery is available on jQuery
							if (typeof lightGallery !== 'undefined' && !jQuery.fn.lightGallery) {
								jQuery.fn.lightGallery = lightGallery;
							}
							var setting_download = '<?php echo esc_attr($lboxdownload); ?>';

							jQuery('#gwts-gwl-img-gallery').lightSlider({		                
							item:<?php echo esc_attr($gallitms);?>,
							slideMargin:<?php echo esc_attr($getmargin);?>,
							addClass:'<?php echo esc_attr($addclss);?>',
							speed:<?php echo esc_attr($sliderspd);?>,
							pause:<?php echo esc_attr($spause);?>,
							auto:<?php echo esc_attr($smode);?>,
							loop:<?php echo esc_attr($sloop);?>,
							pager:<?php echo esc_attr($spager);?>,
							gallery:<?php echo esc_attr($sgallery);?>,
							thumbItem:<?php echo esc_attr($sthumbitem);?>,			 
							controls:<?php echo esc_attr($s_nav);?>,
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
										var galleryElement = jQuery('#gwts-gwl-img-gallery');
										var galleryItems = galleryElement.find('.lslide');
										if(galleryElement.length > 0 && galleryItems.length > 0 && typeof jQuery.fn.lightGallery !== 'undefined'){
											galleryElement.lightGallery({
												download: setting_download,
												galleryId: <?php echo absint( $postid ); ?>,
												selector: jQuery('#gwts-gwl-img-gallery li')
											});
										}
									}
							}, 
						});
					});
				  	</script>
				  	
				  	<?php if(null !== $simagezoom && !empty($simagezoom)){ ?>
					<script>
						jQuery(function() {
							jQuery('#gwts-gwl-img-gallery .zoom').zoom();
						});
					</script>
					<?php } ?>
				  	
				 	<?php }
				 	$outputgal = ob_get_clean();			 
				}
			}
		}
	}
	return $content.$outputgal;
}
add_filter( 'the_content', 'gwts_gwl_filter_the_content_in_the_main_loop' );
