<?php

/*
Plugin Name:    Advanced Custom Fields: Number Slider
Plugin URI:     http://www.qstudio.us/plugins/
Description:    Number Slider field for Advanced Custom Fields
Version:        0.4.2
Author:         Q Studio
Author URI:     http://www.qstudio.us
License:        GPLv2 or later
License URI:    http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * This plugin uses the Simpler Slider jQuery library by James Smith - http://loopj.com/jquery-simple-slider/
 * Version 5 compatibiltity added by chrisgoddard
 */

class acf_field_number_slider_plugin
{
	
    /*
	*  Construct
	*
	*  @description:
	*  @since: 0.1
	*/
    function __construct()
    {
        
        // set text domain
        $domain = 'flotheme';
        $mofile = trailingslashit( dirname(__FILE__)) . 'lang/' . $domain . '-' . get_locale() . '.mo';
        load_textdomain( $domain, $mofile );

        
        
        add_action('acf/include_field_types', array($this, 'include_field_types_number_slider'));	
        
    }



    function include_field_types_number_slider( $version ) {
	
        include_once('number-slider-v5.php');
		
    }


}

new acf_field_number_slider_plugin();
