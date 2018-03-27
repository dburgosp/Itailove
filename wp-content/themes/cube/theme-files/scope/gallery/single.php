<?php
/**
 * Data that will be accessible on single gallery post.
 */
$theme_framework = get_theme_framework();

$post = $theme_framework::get_post();

$the_real_gallery_type = get_field("flo-cube-portfolio-gallery__type");
if (! ( is_string($the_real_gallery_type) && strlen( trim($the_real_gallery_type) ) ) ) {
  $the_real_gallery_type = "image";
}

// get the attached gallery images meta
$post_image_gallery = get_field('_post_image_gallery');

if (is_string($post_image_gallery) && strlen(trim($post_image_gallery))) {
    // compatibility with the old themes  structure - before using ACF galery field
    $img_id_array = array_filter(explode(',', $post_image_gallery));
}else if(is_array($post_image_gallery) && sizeof($post_image_gallery)){
    $img_id_array = $post_image_gallery;
}else{
    $img_id_array = array();
}



$gallery_items = [];

switch ($the_real_gallery_type) {
  case 'image':

    $gallery_items = $img_id_array;
  break;

  // case 'video':
  //   $gallery_items = get_field("_post_video_gallery");
  // break;

  // images and videos
  case 'prius':
    $gallery_items = get_field("_post_prius_gallery");
    $img_id_array = get_field("_post_prius_gallery");// reinit the $img_id_array for the Mix gallery and images
  break;

}

// list here the supported gallery types.
// it may be different for each theme
$supported_gallery_types = array('grid', 'slideshow', 'slideshow__crop', "slideshow__no-crop");
$supported_gallery_types = apply_filters('flo_supported_gallery_types', $supported_gallery_types);


// get the gallery_type meta data
$custom_gallery_view = get_field('custom_gallery_view');

if(  isset($custom_gallery_view) && ($custom_gallery_view === true || $custom_gallery_view == 1 ) ){
	$gallery_type = get_field('flo-portfolio-gallery__gallery-view-type', $post->ID);

}else{
	$gallery_type = flo_get_option("flo-cube-portfolio-gallery__gallery-view-type","slideshow__crop");
//	deb_e($gallery_type); die();
}

/* translators: used between list items, there is a space after the comma */
$separate_meta = __( ', ', 'flotheme' );

// Get Categories for posts.
if( flo_get_option('flo-cube-portfolio-gallery__show-categories',true) ){
  $categories_list = get_the_term_list( $post->ID, 'gallery-category', '', $separate_meta );
}else{
  $categories_list = false;
}


// in case the value for the gallery type is not supported by this theme, we will choose the first one from the supported list
// this may happen when migrating from one theme to another
if(!in_array($gallery_type,$supported_gallery_types )){
	$gallery_type = $supported_gallery_types[0];
}


$max_slider_image_height = flo_get_option('flo-cube-portfolio-gallery__max_slider_image_height',715);
if(!is_numeric($max_slider_image_height)){
	$max_slider_image_height = 715;
}

$slider_height_sizing = flo_get_option('flo-cube-portfolio-gallery__max_slider_image_height_sizing','px');

if('rem' == $slider_height_sizing){
	$slideshow_height = ($max_slider_image_height / 16)."rem";
}else{
	$slideshow_height = $max_slider_image_height ."px";
}


$gallery__grid_columns = flo_get_option('flo-cube-portfolio-gallery__grid-columns',3);
if(!is_numeric($gallery__grid_columns)){
	$gallery__grid_columns = 3;
}

$gallery_grid_columns_class = flo_columns_arabic_to_chars($gallery__grid_columns);

$grid_ratio_info = flo_cube_grid_ratio($gallery__grid_columns);

//deb_e($grid_ratio_info);

$selected_grid_ratio = flo_get_option('flo-cube-portfolio-gallery__grid-thumb-size','3_2');
if(!in_array($selected_grid_ratio,array('3_2','2_3','4_3','3_4','masonry','custom','advanced'))){
	$selected_grid_ratio = '3_2';
}

//deb_e($grid_ratio_info); die();
$grid_sizes = $grid_ratio_info[$selected_grid_ratio];

if('masonry' == $selected_grid_ratio){
	$crop_tumbs = false;
}else{
	$crop_tumbs = true;
}


// Type C and D transition Effect
$sl_transition_effect = flo_get_option('flo-cube-portfolio-gallery__slider-transition-effect','slide');
if($sl_transition_effect == 'fade'){
	$slider_c_d_fade = 'true';
}else{
	$slider_c_d_fade = 'false';
}


$show_gal_comments = flo_get_option('flo-cube-portfolio-gallery__comments-enabled');
$comments_options = flo_get_option('flo-cube-blog-post__comments',array('wp'));

//deb_e($gallery_type); die();
$data = array(
	'post' => $post,

	'flo_post_top__margin_bottom' => '-0',
    'flo_gallery_content__enable_share' => flo_get_option('flo-portfolio-gallery__content-share',true),
	'flo_gallery__max_slider_image_height' => $max_slider_image_height,
	'categories_list' => $categories_list,

  'the_real_gallery_type' => $the_real_gallery_type,

	'flo_gallery__gallery_type' => $gallery_type,
	'flo_gallery__gallery_images' => $img_id_array,
  'gallery_items' => $gallery_items,
	'selected_grid_ratio' => $selected_grid_ratio,
	'grid_sizes' => $grid_sizes,
	'crop_tumbs' => $crop_tumbs,
	'gallery_grid_columns_class' => $gallery_grid_columns_class,
	'show_gal_comments' => $show_gal_comments,
	'comments_options' => $comments_options,
	'slideshow_height' => $slideshow_height,
	'slider_c_d_fade' => $slider_c_d_fade

);
