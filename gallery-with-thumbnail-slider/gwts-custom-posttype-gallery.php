<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
/* Register Custom post type gallery*/
function gwts_gwl_gallery_posttype(){
	$argsments = array('labels'	=> 	array(
		'name'	=>	__('Gallery','gallery-with-thumbnail-slider'),
		'singular_name'	=>	__('gallery','gallery-with-thumbnail-slider'),
		'add_new'	=> __('New Gallery','gallery-with-thumbnail-slider'),
		'add_new_item'	=> __('Add New Gallery','gallery-with-thumbnail-slider'),
		'edit_item'	=>	__('Edit Gallery','gallery-with-thumbnail-slider'),
		'new_item'	=>	__('New Gallery','gallery-with-thumbnail-slider'),
		'view_item'	=>	__('View Gallery','gallery-with-thumbnail-slider'),
		'items_archive'	=>	__('Gallery Archive','gallery-with-thumbnail-slider'),
		'search_items'	=>	__('Search Gallery','gallery-with-thumbnail-slider'),
		'not_found'	=>	__('No galleries found','gallery-with-thumbnail-slider'),
		'not_found_in_trash'	=>	__('No galleries found in trash','gallery-with-thumbnail-slider')),
		'description'        => __( 'Description.', 'gallery-with-thumbnail-slider' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'query_var'          => false,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'show_in_menu'=>'edit.php?post_type=gwts-gallery',		
		'supports'	=> array('title','thumbnail'));
 register_post_type('gwts-gallery', $argsments);
}
add_action('init', 'gwts_gwl_gallery_posttype');


/* add column shortcode to custom post type gallery. */
function gwts_gwl_add_gallery_columns($columns) {
    $columns['shortcode'] = __('Shortcode', 'gallery-with-thumbnail-slider');
    return $columns;
}
add_filter('manage_gwts-gallery_posts_columns' , 'gwts_gwl_add_gallery_columns');

/* implement the shortcode to post type gallery */
add_action( 'manage_gwts-gallery_posts_custom_column' , 'gwts_gwl_custom_gallery_column', 10, 2 );
function gwts_gwl_custom_gallery_column($column, $post_id){
	if($column == 'shortcode'){
		echo "[gwts_gwl_gallery_slider id=" . esc_html($post_id) . "]";
	}
}

/* Remove view link from gwts-gallery post type admin list */
function gwts_remove_view_link_from_gallery_posts($actions, $post) {
    if ($post->post_type == 'gwts-gallery') {
        unset($actions['view']);
    }
    return $actions;
}
add_filter('post_row_actions', 'gwts_remove_view_link_from_gallery_posts', 10, 2);

/* Disable permalink section in edit posts for gwts-gallery */
function gwts_disable_permalink_for_gallery_posts() {
    global $post_type;
    if ($post_type == 'gwts-gallery') {
        echo '<style>
            #edit-slug-box { display: none !important; }
            .post-type-gwts-gallery #edit-slug-box { display: none !important; }
        </style>';
    }
}
add_action('admin_head', 'gwts_disable_permalink_for_gallery_posts');
