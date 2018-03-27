<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_image_select') ) :


class acf_field_image_select extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct( $settings ) {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'image_select';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Image Select', 'flotheme');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'choice';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults    = array(
			'choices'			=>	array(),
			'default_value'		=>	'',
			'multiple'          => 0,
			'image_path'		=>	get_template_directory_uri() . '/theme-files/admin-assets/img/acf-image-select/',
			'image_extension'   => 'png',
			'return_format'		=> 'value'
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('image_select', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'flotheme'),
			'image_path'		=>	get_template_directory_uri() . '/theme-files/admin-assets/img/layout-images/',
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;


		// do not delete!
    	parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		$field['choices'] = acf_encode_choices($field['choices']);


		acf_render_field_setting($field, array(
			'label' => __('Choices','flotheme'),
			'type'	=>	'textarea',
			'name'	=>	'choices',
			'instructions' => "Enter your choices one per line. <br /><span style='color:#BC0B0B'>Please note:</span> The first value of each choices will used as name of image.<br>Like for '<strong>Blue</strong>' or '<strong>blue : Blue</strong>', the image name will be '<strong>blue.png</strong>'. The image files should be located in theme-files/admin-assets/img/acf-image-select/"
		));

		acf_render_field_setting($field, array(
			'label'	=> __('Default Value','flotheme'),
			'type'	=>	'text',
			'name'	=>	'default_value',
		));

		acf_render_field_setting($field, array(
			'label'	=> __('Allow Multiple Choices?','flotheme'),
			'name'	=>	'multiple',
			'type'	=> 'radio',
			'choices'	=>	array(
				1	=>	__("Yes",'flotheme'),
				0	=>	__("No",'flotheme'),
			),
			'layout'	=>	'horizontal',
		));

		acf_render_field_setting($field, array(
			'label'	=> __('Image Path','flotheme'),
			'instructions' => "Enter complete URL for images<br /><span style='color:#BC0B0B'>Some Important Paths:</span><ul>
					<li><strong>Theme URL:</strong>" . get_template_directory_uri() . "(<em><u>If current theme is child theme.</u></em>)</li>
					<li><strong>Current/Child Theme:</strong>" . get_stylesheet_directory_uri() . "</li>
					<li><strong>Content Folder:</strong>" .  content_url() . "</li>
					<li><strong>Home URL:</strong>" . home_url() . "</li>
				</ul>",
			'type'	=>	'text',
			'name'	=>	'image_path',
		));

		acf_render_field_setting($field, array(
			'label'	=> _('Image Extension'),
			'type'	=>	'text',
			'name'	=>	'image_extension',
		));

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {


		// vars
		$i = 0;
		$e = '<ul class="acf-image-select-list ' . esc_attr($field['class']) . '" data-image-select-multiple="'.$field['multiple'].'">';
		//deb_e($field); die();
		//hardcode the field image path
		$field['image_path'] =	get_template_directory_uri() . '/theme-files/admin-assets/img/acf-image-select/';

		// add choices
		if( is_array($field['choices']) )
		{
			foreach( $field['choices'] as $key => $value )
			{
				// vars
				$i++;
				$atts  = '';
				$class = '';

				// if there is no value and this is the first of the choices, select this on by default
				if( $field['value'] === array() )
				{
					if( $i === 1 )
					{
						$atts = 'checked="checked" data-checked="checked"';
						$class = 'acf-image-select-selected';
					}
				}
				else
				{
					if( strval($key) === strval($field['value']) )
					{
						$atts = 'checked="checked" data-checked="checked"';
						$class = 'acf-image-select-selected';
					}
				}

				// HTML
				$field_id = esc_attr($field['id']) . '-' . esc_attr($key);
				$e .= '<li class="acf-image-select">';

					$e .= '<label for="' . $field_id . '" class="'.$class.'">';
						// $e .= '<span class="item-title ' . $field_id . '-title">'.$value.'</span>';
						// $e .= '<br/><br/>';
						$e .= '<input id="' . $field_id . '" class="item-input" type="radio" name="' . esc_attr($field['name']) . '" value="' . esc_attr($key) . '" ' .  $atts  . ' />';
						$e .= '<img style="display: block; width: 100%;" class="item-image ' . $field_id . '-image" alt="'.$value.'" src="'.$field['image_path'].esc_attr($key).'.'.$field['image_extension'].'">';
						// $e .= '<br/>';
					$e .= '</label>';
				$e .= '</li>';
			}
		}

		$e .= '</ul>';

		echo $e;
	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/



	function input_admin_enqueue_scripts() {

		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];



		// register & include JS
		wp_register_script( 'acf-input-image_select', get_template_directory_uri().'/acf/acf-extensions/acf-image-select/assets/js/input.js', array('acf-input'), $version );
		wp_enqueue_script('acf-input-image_select');

		// this can be used for translation and also it contains the url to the folder that contains the
		// icons for the image select inputs
		wp_localize_script( 'acf-input-image_select', 'flo_strings', $this->l10n );


		// register & include CSS
		wp_register_style( 'acf-input-image_select', get_template_directory_uri().'/acf/acf-extensions/acf-image-select/assets/css/input.css', array('acf-input'), $version );
		wp_enqueue_style('acf-input-image_select');


	}




	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function load_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/



	function update_value( $value, $post_id, $field ) {

		// validate
		if( empty($value) ) {

			return $value;

		}


		// array
		if( is_array($value) ) {

			// save value as strings, so we can clearly search for them in SQL LIKE statements
			$value = array_map('strval', $value);

		}


		// return
		return $value;

	}




	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/



	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {
			return $value;
		}


		// get value

		$retvalue = esc_attr($value);

		// format value

		// return value
		return $retvalue;
	}




	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-image_select'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function load_field( $field ) {

		return $field;

	}

	*/


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/



	function update_field( $field ) {

		// decode choices (convert to array)
		$field['choices'] = acf_decode_choices($field['choices']);
		$field['default_value'] = $field['default_value'];


		// return
		return $field;

	}




	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/


}


// initialize
new acf_field_image_select( $this->settings );


// class_exists check
endif;

?>
