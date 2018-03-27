<?php
if( function_exists('acf_add_local_field_group') ):

/* #custom code BOF */
global $wp_registered_sidebars, $flo_options;

// add the registered sidebars in an array
foreach ($wp_registered_sidebars as $side) {
    $sidebar_value[$side['id']] = $side['name'];
}

// figure out if we are working with a page or a blog post
// then get the corresponding default layout options
if(isset($_GET['post'])){
	$post_type = get_post_type($_GET['post']);
}else if(isset($_GET['post_type'])){
	$post_type = $_GET['post_type'];
}else{
	$post_type = 'post';
}

$default_sidebar = '';

if(isset($flo_options['flo-'.$post_type.'__default-sidebar'])){
	$layout_sidebar_default = $flo_options['flo-'.$post_type.'__default-sidebar'];
}else{
	$layout_sidebar_default = 'main';
}

//deb_e($layout_sidebar_default);
if( $layout_sidebar_default && strlen($layout_sidebar_default) ){
	$default_sidebar = $layout_sidebar_default;
}
//deb_e($default_sidebar); die();
if(isset($flo_options['flo-'.$post_type.'__default-layout'])){
	$default_layout = $flo_options['flo-'.$post_type.'__default-layout'];
}else{
	$default_layout = 'full_width';
}

// in case you need to modify the layout and sidebar options, us e the filters below:
$default_layout = apply_filters('flo_default_layout_meta_value',$default_layout);
$default_sidebar = apply_filters('flo_default_layout_sidebar_meta_value',$default_sidebar);

// defines for which post types this meta box is available
$location_array = array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
			array (
				'param' => 'page_template',
				'operator' => '!=',
				'value' => 'classy-template-home',
			),
			array (
				'param' => 'page_template',
				'operator' => '!=',
				'value' => 'classy-template-listing',
			),
			array (
				'param' => 'page_template',
				'operator' => '!=',
				'value' => 'classy-template-about',
			),
			array (
				'param' => 'page_template',
				'operator' => '!=',
				'value' => 'classy-template-contact',
			),
			array (
				'param' => 'page_template',
				'operator' => '!=',
				'value' => 'classy-template-press',
			)

		),
	);
// add possibility to filter this in case we need to add new post types with sidebars
$location_array = apply_filters( 'flo_sidebars_available_post_types' , $location_array );
//deb_e($default_layout); die();
/* #custom code EOF */

acf_add_local_field_group(array (
	'key' => 'group_57b1a6aeb7247',
	'title' => 'Layout and Sidebars',
	'fields' => array (
		array (
			'key' => 'field_57b1a6cc123b0',
			'label' => 'Select layout type',
			'name' => 'layout_type',
			'type' => 'radio',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => 'layout_sidebars hidden-label flo-image-select',
				'id' => '',
			),
			'choices' => array (
				'left_sidebar' => 'Left sidebar',
				'full_width' => 'Full width',
				'right_sidebar' => 'Right sidebar',
			),
			'allow_null' => 0,
			'other_choice' => 0,
			'save_other_choice' => 0,
			'default_value' => "$default_layout", /* #custom code */
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array (
			'key' => 'field_57b1b1bdc15d2',
			'label' => 'Select the widget area (sidebar)',
			'name' => 'layout_sidebar',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array (
				array (
					array (
						'field' => 'field_57b1a6cc123b0',
						'operator' => '!=',
						'value' => 'full_width',
					),
				),
			),
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => $sidebar_value, /* #custom code */

			'default_value' => array (
				$default_sidebar /* #custom code */
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
	),
	'location' => $location_array, /* #custom code */
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
?>
