<?php
/**
 * Data that will be accessible on single post.
 */
$theme_framework = get_theme_framework();

global $flo_options;
$post = $theme_framework::get_post();

$comments_options = flo_get_option('flo-cube-blog-post__comments',array('wp') );


// handle the categories
// by design the first category is listed next to the title, and the others on the right side
$first_category = '';
$other_categories = '';

if(flo_get_option('flo-cube-blog-post__title-block-category', true)){
	$categories_list = get_the_term_list($post->ID, 'category', $before = '' , $sep = ', ' , $after = '' );
}else{
	$categories_list = '';
}


$data = array(
	'post' => $post,
	'comments_options' => $comments_options,
	'categories_list' => $categories_list
);
