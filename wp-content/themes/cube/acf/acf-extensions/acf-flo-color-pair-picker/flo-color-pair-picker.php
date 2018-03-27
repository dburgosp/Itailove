<?php

/*
*  ACF Color Picker Field Class
*
*  All the logic for this field type
*
*  @class 		acf_field_flo_color_pair_picker
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_field_flo_color_pair_picker') ) :

class acf_field_flo_color_pair_picker extends acf_field {


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

	function __construct() {

		// vars
		$this->name = 'flo_color_pair_picker';
		$this->label = __("Flo Color Pair Picker",'acf');
		$this->category = 'jquery';
		$this->defaults = array(
      'color_pair_options_prefix' => 'flo-color-pair-',
			'default_color_pair'	=> false,
      'default_background_color' => false,
      'default_elements_color' => false,
      'display_swatcher' => true
		);

		// do not delete!
    	parent::__construct();

	}


	/*
	*  input_admin_enqueue_scripts
	*
	*  description
	*
	*  @type	function
	*  @date	16/12/2015
	*  @since	5.3.2
	*
	*  @param	$post_id (int)
	*  @return	$post_id (int)
	*/

	function input_admin_enqueue_scripts() {

		// globals
		global $wp_scripts, $wp_styles;


		// register if not already (on front end)
		// http://wordpress.stackexchange.com/questions/82718/how-do-i-implement-the-wordpress-iris-picker-into-my-plugin-on-the-front-end
		if( !isset($wp_scripts->registered['iris']) ) {

			// styles
			// wp_register_style('wp-color-picker', admin_url('css/color-picker.css'), array(), '', true);


			// scripts
			wp_register_script('iris', admin_url('js/iris.min.js'), array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), '1.0.7', true);
			// wp_register_script('wp-color-picker', admin_url('js/color-picker.min.js'), array('iris'), '', true);


			// localize
		    wp_localize_script('wp-color-picker', 'wpColorPickerL10n', array(
		        'clear'			=> __('Clear', 'acf' ),
		        'defaultString'	=> __('Default', 'acf' ),
		        'pick'			=> __('Select Color', 'acf' ),
		        'current'		=> __('Current Color', 'acf' )
		    ));

		}


		// enqueue
		wp_enqueue_style('wp-color-picker');
	  wp_enqueue_script('wp-color-picker');

    $dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-color-pair-picker/';

    wp_register_script( 'acf-flo-color-pair-picker', "{$dir}flo-color-pair-picker.js" );
    wp_enqueue_script( 'acf-flo-color-pair-picker' );
	}


	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field( $field ) {
    ?>
      <div class="flo-color-pair-picker">
        <!–– START: SWATCHES ––>
          <div class="flo-color-pair-picker__swatches">

            <div class="flo-color-pair-picker__swatch flo-color-pair-picker__swatch--selected">
              <div class="flo-color-pair-picker__swatch-background-color" style="background-color:<?php echo "#FFFFFF"; ?>"></div>
              <div class="flo-color-pair-picker__swatch-elements-color" style="color:<?php echo "#000000"; ?>"></div>
            </div>

            <div class="flo-color-pair-picker__swatch">
              <div class="flo-color-pair-picker__swatch-background-color" style="background-color:<?php echo "black"; ?>"></div>
              <div class="flo-color-pair-picker__swatch-elements-color" style="color:<?php echo "white"; ?>"></div>
            </div>

            <div class="flo-color-pair-picker__swatch">
              <div class="flo-color-pair-picker__swatch-background-color" style="background-color:<?php echo "gray"; ?>"></div>
              <div class="flo-color-pair-picker__swatch-elements-color" style="color:<?php echo "black"; ?>"></div>
            </div>

            <div class="flo-color-pair-picker__swatch">
              <div class="flo-color-pair-picker__swatch-background-color" style="background-color:<?php echo "#FFFFFF"; ?>"></div>
              <div class="flo-color-pair-picker__swatch-elements-color" style="color:<?php echo "#000000"; ?>"></div>
            </div>

            <div class="flo-color-pair-picker__swatch flo-color-pair-picker__swatch--custom">
              CUSTOM
            </div>

          </div>
        <!–– END: SWATCHES ––>

        <!–– START: CUSTOM COLORS ––>
          <div class="flo-color-pair-picker__custom-colors">
            <div class="flo-color-pair-picker__custom-colors-label">
              Custom Colors:
            </div>
            <div class="flo-color-pair-picker__custom-color flo-color-pair-picker__custom-color--background-color">
              <input class="flo-color-pair-picker__custom-color-picker" type="text" value="#FFFFFF"/>
            </div>
            <div class="flo-color-pair-picker__custom-color flo-color-pair-picker__custom-color--elements-color">
              <input class="flo-color-pair-picker__custom-color-picker" type="text" value="#000000"/>
            </div>
          </div>
        <!–– END: CUSTOM COLORS ––>

      </div>
    <?php
	}

  /*
  *  render_field_settings()
  *
  *  Create extra options for your field. This is rendered when editing a field.
  *  The value of $field['name'] can be used (like bellow) to save extra data to the $field
  *
  *  @type	action
  *  @since	3.6
  *  @date	23/01/13
  *
  *  @param	$field	- an array holding all the field's data
  */

  function render_field_settings( $field ) {

    // display_format
    acf_render_field_setting( $field, array(
      'name'			=> 'color_pair_option_prefix',
      'label'			=> __('Color Pair Option Prefix','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'placeholder'	=> '',
      'value' => 'flo-color-pair-'
    ));

    acf_render_field_setting( $field, array(
      'name'			=> 'default_color_pair_id',
      'label'			=> __('Default Color Pair ID','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'placeholder'	=> ''
    ));

    acf_render_field_setting( $field, array(
      'name'			=> 'default_background_color',
      'label'			=> __('Default Background Color','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'placeholder'	=> '#FFFFFF',
      'value'     => ''
    ));

    acf_render_field_setting( $field, array(
      'name'			=> 'default_elements_color',
      'label'			=> __('Default Elements Color','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'placeholder'	=> '#FFFFFF',
      'value'     => ''
    ));

    acf_render_field_setting( $field, array(
      'name'			=> 'display_color_swatches',
      'label'			=> __('Display Color Swatcher','acf'),
      'instructions'	=> '',
      'type'			=> 'true_false',
      'value'     => true
    ));

  }


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
	function format_value( $value, $post_id, $field ) {

		// custom color case
		if(isset($value['use_custom_color']) && 1 == $value['use_custom_color'] && isset($value['color']) ){
			return $value['color'];
		}else if(isset($field['color_options_prefix']) && isset($field['default_color_id']) ){
			// append default_color_id to color_options_prefix  to get the collor style option name
			$color_style_option_name = $field['color_options_prefix'].$field['default_color_id'];

			//if(is_admin() || (defined("TYPO_DEV") && TYPO_DEV == "DEV") ){
			$color_style_value = get_field($color_style_option_name,'options');
			// }else{
			// 	$color_style_value = flo_get_option($color_style_option_name);
			// }

			return $color_style_value;
		}else{
			// we assume the value is a string ;
			return $value;
		}

	}

}



// initialize
acf_register_field_type( new acf_field_flo_color_pair_picker() );

endif; // class_exists check

?>
