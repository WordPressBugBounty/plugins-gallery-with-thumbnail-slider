<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
 * Whether an options screen reported a successful save.
 *
 * Uses the settings-updated query arg set by options.php after a nonce-verified save.
 *
 * @return bool
 */
function gwts_gwl_admin_settings_updated() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$updated = filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

	return is_string( $updated ) && '' !== $updated;
}

/**
 * Attachment caption (media excerpt) or empty string.
 *
 * @param int|string $attachment_id Attachment post ID.
 * @return string
 */
function gwts_gwl_get_attachment_caption( $attachment_id ) {
	$attachment = get_post( (int) $attachment_id );
	if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
		return '';
	}
	return $attachment->post_excerpt;
}

/**
 * Placeholder thumbnail when a gallery has no featured image.
 *
 * @return string
 */
function gwts_gwl_get_placeholder_thumbnail_url() {
	$plugin_image = GWTS_GWL_PLUGINPATH . 'includes/images/thumbnail.png';
	if ( file_exists( $plugin_image ) ) {
		return GWTS_GWL_PLUGINURL . 'includes/images/thumbnail.png';
	}
	return includes_url( 'images/media/default.svg' );
}

/**
 * Whether a string contains a GWTS gallery shortcode.
 *
 * @param string $content Content to search.
 * @return bool
 */
function gwts_gwl_string_has_gallery_shortcode( $content ) {
	if ( ! is_string( $content ) || '' === $content ) {
		return false;
	}

	return false !== strpos( $content, '[gwts_gwl_gallery_slider' )
		|| false !== strpos( $content, '[gwts_gwl_galleries_listing' );
}

/**
 * Whether front-end slider assets should load on the current request.
 *
 * @return bool
 */
function gwts_gwl_should_enqueue_frontend_assets() {
	if ( is_admin() ) {
		return false;
	}

	global $post;
	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	if ( has_shortcode( $post->post_content, 'gwts_gwl_gallery_slider' ) || has_shortcode( $post->post_content, 'gwts_gwl_galleries_listing' ) ) {
		return true;
	}

	if ( gwts_gwl_string_has_gallery_shortcode( $post->post_content ) ) {
		return true;
	}

	$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
	if ( gwts_gwl_string_has_gallery_shortcode( $elementor_data ) ) {
		return true;
	}

	$post_types = get_option( 'gwts_gwl_posttypes' );
	if ( ! is_array( $post_types ) ) {
		$post_types = array();
	}
	$post_types[] = 'gwts-gallery';

	if ( in_array( $post->post_type, $post_types, true ) && 'true' !== get_post_meta( $post->ID, 'gwts_gwl_switcher', true ) ) {
		$images = get_post_meta( $post->ID, '_gwts_gwl_attachment_id', true );
		return ! empty( $images ) && is_array( $images );
	}

	return false;
}

/**
 * Enqueue front-end slider scripts and styles.
 *
 * Safe to call multiple times or after wp_enqueue_scripts (before wp_footer).
 *
 * @return void
 */
function gwts_gwl_enqueue_frontend_assets() {
	static $enqueued = false;

	if ( $enqueued || is_admin() ) {
		return;
	}

	$enqueued = true;
	$script_version = gmdate( 'Ymd' );

	wp_enqueue_style( 'gwts-gwl-lightslider-css', GWTS_GWL_PLUGINURL . 'includes/css/lightslider.css', array(), $script_version );
	wp_enqueue_style( 'gwts-gwl-style-css', GWTS_GWL_PLUGINURL . 'includes/css/gwts-style.css', array(), $script_version );
	wp_enqueue_style( 'gwts-gwl-lightgal-css', GWTS_GWL_PLUGINURL . 'includes/css/lightgallery.css', array(), $script_version );

	wp_enqueue_script( 'gwts-gwl-lightslider', GWTS_GWL_PLUGINURL . 'includes/js/lightslider.js', array( 'jquery' ), $script_version, true );
	wp_enqueue_script( 'gwts-gwl-cdngal', GWTS_GWL_PLUGINURL . 'includes/js/picturefill.min.js', array( 'jquery' ), $script_version, true );
	wp_enqueue_script( 'gwts-gwl-mousewheel', GWTS_GWL_PLUGINURL . 'includes/js/jquery.mousewheel.min.js', array( 'jquery' ), $script_version, true );
	wp_enqueue_script( 'gwts-gwl-lightgallry', GWTS_GWL_PLUGINURL . 'includes/js/lightgallery-all.min.js', array( 'jquery', 'gwts-gwl-mousewheel' ), $script_version, true );
	wp_enqueue_script( 'gwts-gwl-dompurify', GWTS_GWL_PLUGINURL . 'includes/js/dompurify.min.js', array(), '3.1.6', true );
	wp_enqueue_script( 'gwts-gwl-lightgallery-sanitize', GWTS_GWL_PLUGINURL . 'includes/js/gwts-lightgallery-sanitize.js', array( 'jquery', 'gwts-gwl-dompurify', 'gwts-gwl-lightgallry' ), $script_version, true );
	wp_enqueue_script( 'gwts-gwl-zoom.min', GWTS_GWL_PLUGINURL . 'includes/js/gwts.zoom.min.js', array( 'jquery' ), $script_version, true );
}

/**
 * Queue gallery initialization to run after plugin scripts load in the footer.
 *
 * @param string $javascript    Raw JS without script tags.
 * @param string $script_handle Script handle to attach inline script to.
 * @return void
 */
function gwts_gwl_add_slider_init_script( $javascript, $script_handle = 'gwts-gwl-lightslider' ) {
	gwts_gwl_enqueue_frontend_assets();
	wp_enqueue_script( $script_handle );

	if ( 'gwts-gwl-lightslider' === $script_handle ) {
		$javascript = "var gwtsGwlRunSliderInit=function(){if(typeof jQuery.fn.lightSlider!=='function'){setTimeout(gwtsGwlRunSliderInit,50);return;}" . $javascript . "};gwtsGwlRunSliderInit();";
	}

	wp_add_inline_script( $script_handle, $javascript, 'after' );
}

function gwts_gwl_shortcode_gallery_slider($postid){
	$outputgal = '';
	if(!empty($postid)){

		$getimag 				= get_post_meta($postid,'_gwts_gwl_attachment_id', true);
	 	$getttl 				= get_post_meta($postid,'_gwts_gallery_title', true);
	 	$getdescription = get_post_meta($postid,'_gwts_gallery_desc', true);

	 	$hidetitle 				= get_post_meta($postid, '_gwtshide_title', true);
	 	$hidedescription 	= get_post_meta($postid,'_gwtshide_description', true); 

	 	$lboxswitchr 	= get_option('gwts_gwl_lightbx_switcher');
		$simagezoom 	= get_post_meta($postid, '_gwtsimage_zoom', true);

		$getverticalgal = get_post_meta($postid, '_gwtsvertical_gal', true);
		$getverticalopt = get_post_meta($postid, '_gwtsVerticalOpt', true);
		$getverticalcontrl = get_post_meta($postid, '_gwtsVerticalcontrl', true);
		$getverticalBreakpoints = get_post_meta( $postid, '_gwtsSetVertBreakpoints', true );
		$sthumbalign = get_post_meta($postid, '_gwtsslider_alignment', true);

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
	 
		if(!empty($getimag) && is_array($getimag)){ 
			gwts_gwl_enqueue_frontend_assets();
			ob_start();
				
			if((null == $hidetitle && empty($hidetitle) && !empty($getttl)) || ((null == $hidedescription) && empty($hidedescription) && !empty($getdescription))) { ?>
			<div class="gwts-gwl-prev-gallery">
				<?php if(null == $hidetitle && empty($hidetitle) && !empty($getttl)) { ?>				
					<p class="gwts-gwl-prev-title"><strong><?php echo esc_html($getttl);?></strong></p> 
				<?php } ?>
				<?php if(null == $hidedescription && empty($hidedescription) && !empty($getdescription)) { ?>
					<p class="gwts-gwl-prev-desc"><?php echo esc_html($getdescription);?></p> 
				<?php } ?>
			</div>
			<?php } 

				if(null !== $getverticalgal && !empty($getverticalgal)){
					if( null!== $getverticalopt){
						$VssliderRange = $getverticalopt;
						$smaxwidth = !empty($VssliderRange[0]) ? $VssliderRange[0] : '1100'; 
					}
				}
				else{
					$smaxwidth = get_option('gwts_gwl_sliderwidth');
				}

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

		 	<div class="item" style="<?php if(!empty($sliderbgcolor)){ ?>background-color:<?php echo esc_attr( $sliderbgcolor ); ?>;<?php } ?>padding: <?php echo esc_attr( $sliderPadding ); ?>;">            
	      <div class="clearfix" <?php if(!empty($smaxwidth)){ ?>style="max-width:<?php echo esc_attr( $smaxwidth ); ?>px;"<?php } ?>>

	        <ul id="gwts-gwl-img-gallery<?php echo esc_attr( $postid ); ?>" class="gwts-gwl-slidergal list-unstyled cS-hidden <?php echo esc_attr( $itemscls ); ?>" data-litebx="<?php if(!empty($lboxswitchr)){ echo esc_attr($lboxswitchr); }else{ echo "false"; }?>">
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
              	<img src="<?php echo esc_url($attchimg[0]); ?>" alt="<?php echo esc_attr( $sanitized_alt ); ?>" />
              		<?php if($scaption == 'true' && !empty($image_cap)): ?>
              			<p><?php echo esc_html($image_cap);?></p>
              		<?php endif; ?>
              </li>
					 	<?php } ?>
					</ul>
				</div>
			</div>

			<?php if(!get_option('gwts_gwl_enable_alt_txt')){ ?>
			<style type="text/css">
				.lg .lg-sub-html{
					display: none !important;
				}
			</style>
			<?php } ?>

			<?php 
			$slide_item = get_option('gwts_gwl_gallery_numberof_items');
			if(!empty($slide_item)){
				$slide_item = $slide_item;
			}
			else{
				$slide_item = 1;
			}

			?>
			

			<?php if(null !== $simagezoom && !empty($simagezoom)){ ?>
			<?php
			gwts_gwl_add_slider_init_script(
				"jQuery(function() {
				  jQuery('.zoom').zoom();
				});",
				'gwts-gwl-zoom.min'
			);
			?>
			<?php	} 

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
				$seffect = get_option('gwts_gwl_slider_effect');

				if(null !== $getverticalgal && !empty($getverticalgal)){
					if( null!== $getverticalopt){
					    $sliderRange = $getverticalopt;
					}
					  $sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
					  $sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';
					  $thumbnlWidth = !empty($sliderRange) ? $sliderRange[2] : '100';
					  $maxThumbItm = !empty($sliderRange) ? $sliderRange[3] : '6';

					  if(!empty($getverticalcontrl)){
					  	$contrlNav = 'true';
					  }
					  else{
					  	$contrlNav = 'false';
					  }
					  
					if( null!== $getverticalBreakpoints){
					    $sliderBreakpoints = $getverticalBreakpoints;
					}  
					$vheight480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[0] : '200'; 
					$vthumb480 = !empty($sliderBreakpoints) ? $sliderBreakpoints[1] : '4';
					
					$vheight641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[2] : '300';
					$vthumb641 = !empty($sliderBreakpoints) ? $sliderBreakpoints[3] : '6';

					$vheight800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[4] : '370';
					$vthumb800 = !empty($sliderBreakpoints) ? $sliderBreakpoints[5] : '6';
					
					if($sthumbalign == 'left'){
					?>
					<style>
						.lSSlideOuter.vertical{
							padding-left: 105px;
							padding-right: 0px!important;
						}
						.lSSlideOuter.vertical .lSGallery {
						    left: 0;
						    margin-left: 0px!important;
						}
					</style>
					<?php 
					}
					?>

	<style>
		
	</style>

				<?php
				$vertical_slider_js = "jQuery(document).ready(function() {
						if (typeof lightGallery !== 'undefined' && !jQuery.fn.lightGallery) {
							jQuery.fn.lightGallery = lightGallery;
						}
						var setting_download = '" . esc_js( (string) $lboxdownload ) . "';
            var count  = 0;
              if (count === 1) return;
              jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').addClass('cS-hidden');
                jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').lightSlider({
                  gallery:true,";
				if ( 'fade' === $seffect ) {
					$vertical_slider_js .= "mode: 'fade',";
				}
				$vertical_slider_js .= "
	              speed:" . (int) $sliderspd . ",
                  auto:" . esc_js( (string) $smode ) . ",
                  item: 1,
							    loop: " . esc_js( (string) $sloop ) . ",
							    thumbItem: " . (int) $maxThumbItm . ",
							    vertical: true,
							    verticalHeight:" . (int) $sliderHeight . ",
							    vThumbWidth:" . (int) $thumbnlWidth . ",
							    thumbMargin:4,
							    controls:" . esc_js( (string) $contrlNav ) . ",
							    responsive : [
			            {
		                breakpoint:800,
		                settings: {
	                    item:1,
	                    slideMove:1,
	                    verticalHeight:" . (int) $vheight800 . ",
	                    thumbItem:" . (int) $vthumb800 . ",
	                  }
			            },
			            {
		                breakpoint:641,
		                settings: {
	                    item:1,
	                    slideMove:1,
	                    verticalHeight:" . (int) $vheight641 . ",
	                    thumbItem:" . (int) $vthumb641 . ",
	                  }
			            },
			            {
		                breakpoint:480,
		                settings: {
	                    item:1,
	                    slideMove:1,
	                    verticalHeight:" . (int) $vheight480 . ",
	                    thumbItem:" . (int) $vthumb480 . ",
	                  }
			            }
			        	],
                onSliderLoad: function(obj) {
                	jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').removeClass('cS-hidden');
	                var lithbox = jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').attr('data-litebx');
					if(lithbox=='true'){
						var galleryElement = jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "');
						var galleryItems = galleryElement.find('.lslide');
						if(galleryElement.length > 0 && galleryItems.length > 0 && typeof jQuery.fn.lightGallery !== 'undefined'){
                            galleryElement.lightGallery({
                                download: setting_download,
                                galleryId: " . absint( $postid ) . ",
                                selector: '#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . " li'
                            });
						}
					}
                }
              });
            count++;
          });";
				gwts_gwl_add_slider_init_script( $vertical_slider_js );
				?>
				<?php } else {
				$sthumbalign = get_post_meta($postid, '_gwtsslider_alignment', true);
				if($sthumbalign == 'center'){
				?>
				<style>
					.lSSlideOuter .lSPager.lSGallery {
						margin-left: auto;
						margin-right: auto;
					}
				</style>
				<?php 
				}
				else if($sthumbalign == 'right'){
				?>
				<style>
					.lSSlideOuter .lSPager.lSGallery {
						margin-left: auto;
					}
				</style>
				<?php 
				}
				?>
				<?php
				$horizontal_slider_js = "jQuery(document).ready(function() {
		    		if (typeof lightGallery !== 'undefined' && !jQuery.fn.lightGallery) {
		    			jQuery.fn.lightGallery = lightGallery;
		    		}
					var setting_download = '" . esc_js( (string) $lboxdownload ) . "';
		        jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').lightSlider({
              item:" . (int) $gallitms . ",
              slideMargin:" . (int) $getmargin . ",
              addClass:'" . esc_js( (string) $addclss ) . "',
              speed:" . (int) $sliderspd . ",
              pause:" . (int) $spause . ",
              auto:" . esc_js( (string) $smode ) . ",
              loop:" . esc_js( (string) $sloop ) . ",
              pager:" . esc_js( (string) $spager ) . ",
              gallery:" . esc_js( (string) $sgallery ) . ",
              thumbItem:" . (int) $sthumbitem . ",
	    	  controls:" . esc_js( (string) $s_nav ) . ",";
				if ( 'fade' === $seffect ) {
					$horizontal_slider_js .= "mode: 'fade',";
				}
				$horizontal_slider_js .= "
	    				responsive : [
		            {
	                breakpoint:800,
	                settings: {
                    item:1,
                    slideMove:1,
                  }
		            },
		            {
	                breakpoint:641,
	                settings: {
                    item:1,
                    slideMove:1,
                  }
		            },
		            {
	                breakpoint:480,
	                settings: {
                    item:1,
                    slideMove:1,
                  }
		            }
						  ],
	    					useCSS: true,
	        			cssEasing: 'ease',
	        			easing: 'linear',
	        			keyPress: false,
	        			slideEndAnimation: true,
	        			swipeThreshold: 40,
		              	onSliderLoad: function(el) {
							jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').removeClass('cS-hidden');
							jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').addClass('gwts-loaded');
							var lithbox = jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "').attr('data-litebx');
							if(lithbox=='true'){
								var galleryElement = jQuery('#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . "');
								var galleryItems = galleryElement.find('.lslide');
								if(galleryElement.length > 0 && galleryItems.length > 0 && typeof jQuery.fn.lightGallery !== 'undefined'){
                                    galleryElement.lightGallery({
                                        download: setting_download,
                                        galleryId: " . absint( $postid ) . ",
                                        selector: '#gwts-gwl-img-gallery" . esc_js( (string) $postid ) . " li'
                                    });
								}
							}
		              	}
			        });";
				if ( 'false' !== $smode ) {
					$horizontal_slider_js .= "
						setTimeout(function() {
						var autoplaySlide = document.querySelector('.lSSlideOuter > ul > li:nth-child(2)');
						if (autoplaySlide) { autoplaySlide.click(); }
						}, 3000);";
				}
				$horizontal_slider_js .= "
					});";
				gwts_gwl_add_slider_init_script( $horizontal_slider_js );
				?>
		 <?php }
		 $outputgal = ob_get_clean();			 
		}		
	}
	return $outputgal;
}

/* Gallery thumbnail grid shortcode */
function gwts_gwl_shortcode_display_gallery_list($no_of_items){
	$outputgallery = '';
	$argary = array(
		'posts_per_page' => $no_of_items,
		'post_type'	=>	'gwts-gallery',
		'post_status'	=> 'publish',
		);
	$getgallery = new WP_Query($argary);	
	if($getgallery->have_posts()){
		ob_start();
		echo '<div class="gwts-gwl-gallery-listings"><ul id="gwts-gwl-thumbrig">';		
		while ($getgallery->have_posts()) {
			$getgallery->the_post();
			if( has_post_thumbnail()) { ?>
				<li>
    			<a class="gwts-gwl-thumbrig-cell" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank">
    				<?php the_post_thumbnail('thumbnail', array( 'class' => 'gwts-gwl-thumbrig-img' )); ?>
            	<span class="gwts-gwl-thumbrig-overlay"></span>
            	<span class="gwts-gwl-thumbrig-text"><?php the_title_attribute(); ?></span>
            </a>
        </li>				  			
    	<?php   			
			}
			else { ?>
				<li>
    			<a class="gwts-gwl-thumbrig-cell" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" target="_blank">
    				<img class="gwts-gwl-thumbrig-img" src="<?php echo esc_url( gwts_gwl_get_placeholder_thumbnail_url() ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"/>
            	<span class="gwts-gwl-thumbrig-overlay"></span>
            	<span class="gwts-gwl-thumbrig-text"><?php the_title_attribute(); ?></span>
            </a>
        </li>
			<?php
			}
		}
		echo '<div class="clear clearfix"></div>';
		echo '</div></ul>';
		
		$outputgallery = ob_get_clean();
	}	
	return $outputgallery;
}
