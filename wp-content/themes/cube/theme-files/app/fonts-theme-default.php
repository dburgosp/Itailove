<?php

/**
 *
 * Define here the theme specific typography option name
 */
add_filter( 'flo_theme_typography_option_name', 'flo_set_theme_typography_option_name', 10, 1 );
if(!function_exists('flo_set_theme_typography_option_name')){
	function flo_set_theme_typography_option_name(){
		return 'flo-typography';
	}
}

/**
 *
 * This is where we define the default fonts specific for this theme
 * The custom fonts which are shipped with the theme
 */

add_filter( 'flo_set_default_theme_fonts', 'flo_set_default_fonts', 10, 1 );


/**
 *
 * Define here the list of the default fonts used in the theme
 *
 */
if(!function_exists('flo_default_fonts_sets')){
	function flo_default_fonts_sets(){

		$current_stylekit = flo_get_current_style_kit();
		if(file_exists(dirname(__FILE__)."/../flo_stylekits_config/".$current_stylekit."/typography__font-families.php")){
			include(dirname(__FILE__)."/../flo_stylekits_config/".$current_stylekit."/typography__font-families.php");
		}else{
			include(dirname(__FILE__)."/../typography/typography__font-families.php");
		}


		// add possibility to filter this using a filter
		// it may be useful if we have different stylekits for example
		$temp_result = apply_filters('flo_default_font_families',$default_font_faces);
		if(is_array($temp_result) && sizeof($temp_result)){
			$default_font_faces = $temp_result;
		}
		$result = $default_font_faces;
		return $result;
	}
}

/**
 *
 * prepare the default font styles
 *
 */
if(!function_exists('flo_set_default_fonts')){
	function flo_set_default_fonts($default_theme_fonts){

		// get the default fonts list
		$default_fonts = flo_default_fonts_sets();

		$current_stylekit = flo_get_current_style_kit();
		//die($current_stylekit);
		if(file_exists(dirname(__FILE__)."/../flo_stylekits_config/".$current_stylekit."/typography__components.php")){
			//include(dirname(__FILE__)."/../flo_stylekits_config/".$current_stylekit."/typography__components.php");

			include(dirname(__FILE__)."/../typography/typography__variables.php");

			$default_theme_fonts_path = dirname(__FILE__)."/../flo_stylekits_config/".$current_stylekit."/typography__components.php";
		}else{
			include(dirname(__FILE__)."/../typography/typography__variables.php");

    		$default_theme_fonts_path = dirname(__FILE__)."/../typography/typography__components.php";
		}

    	

    	// Use the following filter to change that.
    	// When there are several style kits, this is usually used.
    	$temp_theme_fonts_path = apply_filters('flo_default_theme_fonts_path',$default_theme_fonts_path);
    	if(file_exists($temp_theme_fonts_path)){
    		$default_theme_fonts_path = $temp_theme_fonts_path;
    	}


    	include($default_theme_fonts_path); //$default_theme_fonts

		$result = array();

		foreach ($default_theme_fonts as $key => $value) {
			$font_face_info = $default_fonts[$value['font_family']];
			//deb_e($value);
			//deb_e($font_face_info);
			$result[$key] = array_merge($value,$font_face_info);
		}
		//return $default_theme_fonts;
		return $result;
	}

// 	$e = apply_filters( 'flo_set_default_theme_fonts', array() );
// deb_e($e);
}

?>
