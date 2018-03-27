<?php
/**
 * Theme main config.
 */

/**
 *
 * Initiate the Global $flo_options variable
 * which will store the theme options
 *
 */
global $flo_options;
$flo_options = flo_get_flo_options();

/**
 * Textdomain
 * If you're translating a theme, you'll need to use a text domain to denote all text belonging to that theme.
 *
 * @link https://codex.wordpress.org/I18n_for_WordPress_Developers
 * @var string
 */
$textdomain = 'flotheme';

/**
 * Environment.
 * Can be developemnt/production.
 * In this theme it is used to deliver minified assets when environment is production and originals for development
 *
 * @var string
 */
$environment = 'production';

/**
 * Theme Post types
 *
 * @link https://github.com/anrw/classy/wiki/Custom-post-types
 * @var array
 */
if(isset($flo_options['flo-portfolio-gallery__gallery-slug']) && strlen(trim($flo_options['flo-portfolio-gallery__gallery-slug']))){
	$gallery_permalink = $flo_options['flo-portfolio-gallery__gallery-slug'];
}else{
	$gallery_permalink = 'gallery';
}

$post_types = array(
	'gallery' => array(
	    'config' => array(
	        'public' => true,
	        'menu_position' => 7,
	        'menu_icon' => 'dashicons-format-gallery',
	        'hierarhical' => false,
	        'rewrite' => array('slug' => $gallery_permalink, 'with_front' => true),
	        '__on_front_page' => true,
	        'supports' => array(
	            'title',
	            'editor',
	            'thumbnail',
	            'comments',
                'excerpt'
	        ),
	        'show_in_nav_menus' => true,
	    ),
	    'singular' => __( 'Gallery', 'flotheme' ),
	    'multiple' => __( 'Galleries', 'flotheme' ),
	    'add_new' => _x('Add New', 'Gallery', 'flotheme'),
	    'add_new_item' => __('Add New Gallery', 'flotheme'),
	    'edit_item' => __('Edit Gallery', 'flotheme'),
	    'new_item' => __('New Gallery', 'flotheme'),
	    'view_item' => __('View Gallery', 'flotheme'),
	    'search_items' => __('Search Gallery', 'flotheme'),
	    'not_found' => __('Nothing found', 'flotheme'),
	    'not_found_in_trash' => __('Nothing found in Trash', 'flotheme')
	),
	'slideshow' => array(
	    'config' => array(
	        'public' => true,
	        'hierarchical' => false,
	        'exclude_from_search' => true,
	        'menu_position' => 3,
	        'has_archive' => false,
	        'supports' =>  array('title'),
	        '__on_front_page' => true,
    		'menu_icon' => 'dashicons-slides'

	    ),
	    'singular' => __( 'Slideshow', 'flotheme' ),
	    'multiple' => __( 'Slideshows', 'flotheme' ),
	),

);

/**
 * Theme Taxonomies
 *
 * @link https://github.com/anrw/classy/wiki/Taxonomies
 * @var array
 */
if( isset($flo_options['flo-portfolio-gallery__gallery-category-slug']) && strlen(trim($flo_options['flo-portfolio-gallery__gallery-category-slug']))){
	$gallery_categ_permalink = $flo_options['flo-portfolio-gallery__gallery-category-slug'];
}else{
	$gallery_categ_permalink = 'gallery-category';
}

if( isset($flo_options['flo-portfolio-gallery__gallery-tag-slug']) && strlen(trim($flo_options['flo-portfolio-gallery__gallery-tag-slug']))){
	$gallery_tags_permalink = $flo_options['flo-portfolio-gallery__gallery-tag-slug'];
}else{
	$gallery_tags_permalink = 'gallery-tag';
}

$taxonomies = array(
	'gallery-category' => array(
	    'for'       => array( 'gallery' ),
	    'config'    => array(
	        'hierarchical'  => true,
	        'show_ui' => true,
		    'query_var' => true,
		    'rewrite' => array('slug' => $gallery_categ_permalink),
	    ),
	    'singular'  => 'Gallery Category',
	    'multiple'  => 'Gallery Categories',
	),

	'gallery-tag' => array(
	    'for'       => array( 'gallery' ),
	    'config'    => array(
	        'hierarchical'  => false,
	        'show_ui' => true,
		    'query_var' => true,
		    'rewrite' => array('slug' => $gallery_tags_permalink),
	    ),
	    'singular'  => 'Gallery Tag',
	    'multiple'  => 'Gallery Tags',
	),
);

/**
 * Theme post formats.
 *
 * @link https://github.com/anrw/classy/wiki/Post-formats
 * @var array
 */
$post_formats = array();

/**
 * Sidebars
 *
 * @link https://github.com/anrw/classy/wiki/Sidebars
 * @var array
 */
$sidebars = array(
    'main' => __('Main Sidebar','flotheme'),
    'header-translation' => __('Header Translation','flotheme'),
    'top-sidebar' => __('Header Top full Screen width','flotheme'),
    'full-content-area1' => __('Full content width area 1','flotheme'),
    'footer-first' => __('Footer First','flotheme'),
    'footer-second' => __('Footer Second','flotheme'),
    'footer-third' => __('Footer Third','flotheme'),
    'full-content-area2' => __('Full content width area 2','flotheme')
);
