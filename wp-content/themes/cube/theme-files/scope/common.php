<?php
/**
 * Data that will be accessible in every view.
 */

$theme_framework = get_theme_framework();

global $flo_options;

$post = $theme_framework::get_post();
/**
 * Sample of the $page_layout array:
 * Array(
	    [layout_type] => full_width // options: full_width/left_sidebar/right_sidebar
	    [sidebar] => main // or any other sidebar name
	)
	flo_get_page_layout function has a filter, in case you need to modify this data
 */
$page_layout = flo_get_page_layout();

$favicon = '';

if(isset($flo_options['flo-get_started__favicon'])){
	$favicon = $flo_options['flo-get_started__favicon'];
}

if( !($favicon && strlen($favicon)) ){
	$favicon = '';
}

// pages without sidebars
$layout_type = $page_layout['layout_type'];


$data = array(
	//'menu' => new Classy\Menu
	'post' => $post,
	'flo_options' => $flo_options,
	'layout_type' => $layout_type,
	'sidebar' => $page_layout['sidebar'],
	'flo_custom_favicon' => $favicon,
	'body_sidebar_class' => $page_layout['body_sidebar_class'],
	'sidebar_container_class' => $page_layout['sidebar_container_class'],
);
//deb_e($data);
