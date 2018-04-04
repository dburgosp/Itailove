<?php

/*
*  ACF Color Picker Field Class
*
*  All the logic for this field type
*
*  @class 		acf_field_flo_color_picker
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_field_flo_color_picker') ) :

class acf_field_flo_color_picker extends acf_field {


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
		$this->name = 'flo_color_picker';
		$this->label = __("Flo Color Picker",'acf');
		$this->category = 'jquery';
		$this->defaults = array(
			'default_value'	=> '',
			'use_alpha' => false,
      'color_options_prefix' => 'flo-color-'
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
			wp_register_style('wp-color-picker', admin_url('css/color-picker.css'), array(), '', true);


			// scripts
			wp_register_script('iris', admin_url('js/iris.min.js'), array('jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch'), '1.0.7', true);
			wp_register_script('wp-color-picker', admin_url('js/color-picker.min.js'), array('iris'), '', true);


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

    $dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-color-picker/';

		wp_register_script( 'acf-flo-color-picker', "{$dir}flo-color-picker.js" );
		wp_enqueue_script( 'wp-color-picker-alpha', "{$dir}wp-color-picker-alpha.js", array( 'wp-color-picker' ), '2.0.0', true );
		wp_enqueue_script( 'acf-flo-color-picker' );
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

      $stylekit_color_object = get_field_object($field["color_options_prefix"].$field["default_color_id"] ,"options");
      $stylekit_color_name = $stylekit_color_object["label"];
      $stylekit_color = $stylekit_color_object["value"];

    	// $stylekit_color = get_field($field["color_options_prefix"].$field["default_color_id"] ,"options");


	    if(isset($field["value"]["use_custom_color"]) && $field["value"]["use_custom_color"] == 1 ){
	      $use_custom_color = 1;
	      $custom_color_class = '';
	      $current_color_class = ' hidden ';
	    } else {
	      $use_custom_color = 0;
	      $custom_color_class = ' hidden ';
	      $current_color_class = '';
	    }

		// vars
		$text = acf_get_sub_array( $field, array('id', 'class') );
		$hidden = acf_get_sub_array( $field, array('name', 'value') );
		if(isset($field["value"]) && is_array($field["value"]) && isset($field["value"]['color']) && is_string($field["value"]['color']) ){
			$custom_color = $field["value"]['color'];
		}else if( isset($field["value"]) && !is_array($field["value"])){
			$custom_color = $field["value"];
		}else{
			$custom_color = '';
		}
		$use_alpha = $field['use_alpha'];
		$e = '';

    /* START: GET STYLEKIT COLORS FOR COLOR PICKER PALETTE */
      $theme_color_palette_file =  get_template_directory() . '/theme-files/flo_stylekits_config/theme-color-palette.php';
      if (file_exists($theme_color_palette_file)) {
        include $theme_color_palette_file;
      }

      if (isset($theme_color_palette)) {
        $palette = $theme_color_palette;
      } else {
        $palette = Array(
          flo_color("primary-background-color"),
          flo_color("secondary-background-color-1"),
          flo_color("secondary-background-color-2"),
          flo_color("secondary-background-color-3"),
          flo_color("secondary-background-color-4"),
          flo_color("secondary-background-color-5"),
          flo_color("secondary-background-color-6")
        );
      }
      $palette = join(",", $palette);
    /* END: GET STYLEKIT COLORS FOR COLOR PICKER PALETTE */

		// render
		?>
		<div class="acf-flo-color-picker">
      <div class="acf-flo-color-picker__color_wrap">
        <div class="acf-flo-color-picker__current-color <?php echo $current_color_class; ?>" style="background-color: <?php echo $stylekit_color ?>" data-color="<?php echo $stylekit_color; ?>"></div>
        <div class="acf-flo-color-picker__custom-color-picker <?php echo $custom_color_class; ?> " data-palette="<?php echo $palette ?>">
    			<?php acf_hidden_input($hidden); ?>
    			<input type="text" name="<?php echo esc_attr($field['name']) ?>[color]" value="<?php echo $custom_color; ?>" <?php echo acf_esc_attr($text); ?> data-alpha ="<?php echo $use_alpha; ?>" />
        </div>
      </div>

      <div class="acf-flo-color-picker__custom-color-question">
        <div class="acf-true-false">
          <input class="acf-flo-color-picker__custom-color-checkbox" <?php echo $use_custom_color ? "checked=checked" : "" ?> type="checkbox" name="<?php echo esc_attr($field['name']) ?>[use_custom_color]" value="1">
        </div>
        <div class="acf-flo-color-picker__custom-color-text">
          Use a different color
        </div>
      </div>

		</div>
    <p class="description">Default: <a href="<?php echo get_admin_url() ?>admin.php?page=acf-options-10-style" target="_blank"><?php echo $stylekit_color_name ?></a> (<?php echo $stylekit_color ?>)</p>
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
      'label'			=> __('Color Options Prefix','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'name'			=> 'color_options_prefix',
      'placeholder'	=> '',
      'value' => 'flo-color-'
    ));

    acf_render_field_setting( $field, array(
      'label'			=> __('Default Color ID','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'name'			=> 'default_color_id',
      'placeholder'	=> ''
    ));

    acf_render_field_setting( $field, array(
      'label'			=> __('Default Value','acf'),
      'instructions'	=> '',
      'type'			=> 'text',
      'name'			=> 'default_value',
      'placeholder'	=> '#FFFFFF'
    ));

		// for rgba
		acf_render_field_setting( $field, array(
			'name'			=> 'use_alpha',
			'label'			=> __('Use Alpha?','acf'),
			'instructions'	=> '',
			'type'			=> 'true_false'
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
acf_register_field_type( new acf_field_flo_color_picker() );

endif; // class_exists check

?>
