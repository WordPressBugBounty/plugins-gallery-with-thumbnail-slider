<?php 
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
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
	 
		if(!empty($getimag)){ 
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
				    $smaxwidth = $VssliderRange;
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
			?>

		 	<div class="item" style="<?php if(!empty($sliderbgcolor)){ ?>background-color:<?php esc_html_e($sliderbgcolor, 'gallery-with-thumbnail-slider'); ?>;<?php } ?>padding: <?php echo $sliderPadding; ?>;">            
	      <div class="clearfix" <?php if(!empty($smaxwidth)){ ?>style="max-width:<?php esc_html_e($smaxwidth, 'gallery-with-thumbnail-slider'); ?>px;"<?php } ?>>

	        <ul id="gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>" class="gwts-gwl-slidergal list-unstyled cS-hidden" data-litebx="<?php if(!empty($lboxswitchr)){ echo esc_attr($lboxswitchr); }else{ echo "false"; }?>">
				    <?php
					$scaption = get_option('gwts_gwl_enable_caption');
						foreach ($getimag as $imgvalue) {

					 		$attchimg = wp_get_attachment_image_src($imgvalue,'full');
					 		$thumbnailimg = wp_get_attachment_image_src($imgvalue, $thumbsize);
					 		$image_alt = get_post_meta($imgvalue, '_wp_attachment_image_alt', true);
					 		$image_cap = get_post($imgvalue)->post_excerpt;
					 	?>

					 		<li data-thumb="<?php echo esc_url($thumbnailimg[0]); ?>" data-responsive="<?php echo esc_url($thumbnailimg[0]); ?>" data-src="<?php echo esc_url($attchimg[0]); ?>" class="<?php if(!empty($simagezoom)){ esc_html_e('zoom', 'gallery-with-thumbnail-slider'); }?>"> 
              	<img src="<?php echo esc_url($attchimg[0]); ?>" alt="<?php echo esc_attr($image_alt);?>" />
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

			<style type="text/css">
				.lSSlideOuter .lSSlideWrapper ul li img{
				  width: 100%;
				}
			</style>

			<?php if(null !== $simagezoom && !empty($simagezoom)){ ?>

			<script>
				jQuery(function() {
				  jQuery('.zoom').zoom();
				});
			</script>

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
				<script>
					jQuery(document).ready(function() {
						var setting_download = '<?php echo $lboxdownload; ?>';
            var count  = 0
              if (count === 1) return;
              jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').addClass('cS-hidden');
                jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').lightSlider({
                  gallery:true,
				  <?php if($seffect == 'fade'): ?>
					mode: '<?php esc_html_e($seffect, 'gallery-with-thumbnail-slider'); ?>',
				   <?php endif; ?>	                        
	              speed:<?php esc_html_e($sliderspd, 'gallery-with-thumbnail-slider');?>,
                  auto:<?php esc_html_e($smode, 'gallery-with-thumbnail-slider');?>,
                  item: 1,
							    loop: <?php esc_html_e($sloop, 'gallery-with-thumbnail-slider');?>,
							    thumbItem: <?php esc_html_e($maxThumbItm, 'gallery-with-thumbnail-slider'); ?>,
							    vertical: true,
							    verticalHeight:<?php esc_html_e($sliderHeight, 'gallery-with-thumbnail-slider'); ?>,
							    vThumbWidth:<?php esc_html_e($thumbnlWidth, 'gallery-with-thumbnail-slider'); ?>,
							    thumbMargin:4,
							    controls:<?php esc_html_e($contrlNav, 'gallery-with-thumbnail-slider'); ?>,//navigation
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
                	jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').removeClass('cS-hidden');
	                var lithbox = jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').attr("data-litebx");
					if(lithbox=='true'){
						obj.lightGallery({
							download: setting_download,
							selector: '#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?> .lslide'
						});
					}            
                } 
              });
            count++;
          });
	      </script>
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
				<script>
		    	jQuery(document).ready(function() {
					var setting_download = '<?php echo $lboxdownload; ?>';
		        jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').lightSlider({
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
	    	  controls:<?php esc_html_e($s_nav, 'gallery-with-thumbnail-slider');?>,
	    	   <?php if($seffect == 'fade'): ?>
								mode: '<?php esc_html_e($seffect, 'gallery-with-thumbnail-slider'); ?>',
							<?php endif; ?>
							
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
		            },						           
						  ],
	    					useCSS: true,
	        			cssEasing: 'ease',
	        			easing: 'linear',
	        			keyPress: false,
	        			slideEndAnimation: true,
	        			swipeThreshold: 40,        			
		              	onSliderLoad: function(el) {
							jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').removeClass('cS-hidden');
							jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').addClass('gwts-loaded');
							
							var lithbox = jQuery('#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?>').attr("data-litebx");
							if(lithbox=='true'){
								el.lightGallery({
									download: setting_download,
									selector: '#gwts-gwl-img-gallery<?php esc_html_e($postid, 'gallery-with-thumbnail-slider'); ?> .lslide'
								});
							}	
		              	}  
			        });
					});
		    	<?php if($smode != 'false'){ ?>
						var interval = setTimeout(function() {
						document.querySelector('.lSSlideOuter > ul > li:nth-child(2)').click();
						}, 3000);
					<?php } ?>
			</script>
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
    				<img class="gwts-gwl-thumbrig-img" src="<?php echo GWTS_GWL_PLUGINURL; ?>includes/images/thumbnail.png" alt="img"/>
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
