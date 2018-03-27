<?php
$theme_typography_option_name = 'flo-typography';
$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

// delete_option('options_'.$theme_typography_option_name);
// delete_option('_options_'.$theme_typography_option_name);
// delete_option('options_flo-quick_typography');
// delete_option('options_quick_typography');

// delete_option('options_flo-header__logo-font1');


	/**
	 *
	 * Parse the typography options and return the
	 * the Font face for the custom fonts
	 * The google fonts
	 * the css styles for each typograpy style
	 * the simplified array of font styles
	 * @param string typography_option_name - the name on the typography option
	 * @return array font_styles - array containing the info described above
	 * font_styles = array(
	 * 		'custom_font_face' => ...,
	 * 		'google_font_face' => ...,
	 * 		'font_style_css' => ...,
	 * 		'font_styles_list => ...'
	 * )
	 */
	if(!function_exists('get_font_styles_data')){
		function get_font_styles_data($typography_option_name,$add_bg_color = false){

			$font_styles_list = array();

			if(defined('TYPO_DEV') && TYPO_DEV == 'DEV'){
				// in development mode while we are preparing the default fonts list, it is convinient
				// to work directly with the default fonts list to avoid spending time
				// on saving the typography options every time a change to the default fonts array is made
				// WORK with default options set in the config file
				$default_fonts_set = apply_filters( 'flo_set_default_theme_fonts', array() ); // returns the default fonts specified in .../theme-files/app/fonts-theme-default.php

				// create an array that has the same structure as the one saved in the typography options
				$flo_typography = array(
						'font_styles' => '{"theme_defaults": '. json_encode($default_fonts_set) .',"user_specified": {}}',
						'custom_fonts' => '[]'
					);

			}else{
				// work with options from the DB

				// support for wpml: load the default language typography options
				add_filter('acf/settings/current_language', 'flo_acf_set_language_to_default', 100);
				$flo_typography = get_field($typography_option_name,'options');
				// remove the filter to not affect the other areas
				remove_filter('acf/settings/current_language', 'flo_acf_set_language_to_default', 100);
			}

// echo '<pre>';
// var_dump($flo_typography);
// echo '</pre>';
//deb_e($flo_typography);


			$custom_font_face_array = array();
			$google_font_face = array();
			$font_styles_css =  array();
			//deb_e($flo_typography);

			if(isset($flo_typography['font_styles'])){
				if(is_string($flo_typography['font_styles'])){
					$font_styles_data = json_decode($flo_typography['font_styles']);
				}else{
					$font_styles_data = (array)$flo_typography['font_styles'];
				}


				if(is_object($font_styles_data)){

					$theme_defaults = array();
					$user_specified = array();

					if(isset($font_styles_data->theme_defaults)){
						$theme_defaults = $font_styles_data->theme_defaults;
					}
					if(isset($font_styles_data->user_specified)){
						$user_specified = $font_styles_data->user_specified;
					}

					// merge the 2 objects to have only 1 iteration for both
					$typography_styles = (object) array_merge((array) $theme_defaults, (array) $user_specified);
//deb_e($typography_styles); deb_e('rerere');
					foreach ($typography_styles as $key => $value) {
						$font_styles_list[$key] = $value->font_style_name;

						$font_face = get_font_face($value);
//deb_e($font_face);
						if(isset($font_face['custom_font_face']) &&
							!in_array($font_face['custom_font_face'],$custom_font_face_array)){

							$custom_font_face_array[] = $font_face['custom_font_face'];
						}
						if(isset($font_face['google_font_face']) &&
							!in_array($font_face['google_font_face'], $google_font_face) ){

							$google_font_face[] = $font_face['google_font_face'];
						}

						// the css styles for each font style
						$font_styles_css[$key] = get_font_styles($value, $add_bg_color);
					}

				}
			}

			$font_styles['custom_font_face'] = $custom_font_face_array;
			$font_styles['google_font_face'] = $google_font_face;
			$font_styles['font_style_css'] = $font_styles_css;
			$font_styles['font_styles_list'] = $font_styles_list;

			// deb_e($custom_font_face_array);
			// deb_e($google_font_face);

			//font_styles_list

			//deb_e($font_styles);

			return $font_styles;
		}
	}


	if(!function_exists('get_font_face')){
		/**
		 *
		 * generate the font face from the given font style
		 *	@param $font_style - object containing info about the font style. Example data: http://pastebin.com/HcEY8CEj
		 * 	@return string - the font face
		 */
		function get_font_face($font_style){
			$font_face = array();
			if(isset($font_style->font_type)){
				switch ($font_style->font_type) {
					case 'default_font':
						$font_face['custom_font_face'] = get_custom_font_face($font_style);

						break;
					case 'custom_font':
						$font_face['custom_font_face'] = get_custom_font_face($font_style);

						break;

					case 'google_font':
						$font_url = $font_style->font_url; //deb_e($font_url);
						if( 'array' == gettype($font_url) && isset($font_url[0])){

							$font_face['google_font_face'] = acf_field_flo_typography::get_true_font_name(stripslashes($font_url[0]->name));
						}else if(isset($font_url->name)){
							$font_face['google_font_face'] = acf_field_flo_typography::get_true_font_name(stripslashes($font_url->name));
						}

						break;

					default:
						# code...
						break;
				}
			}

			return $font_face;
		}
	}

	/**
	 *
	 * Check if the given color is dark or light
	 *
	 */
	function flo_light_dark($color){

	    //break up the color in its RGB components
		$r = hexdec(substr($color,0,2));
		$g = hexdec(substr($color,2,2));
		$b = hexdec(substr($color,4,2));

		// you may change the value 382 to something else
		if($r + $g + $b > 382){
		    //bright color, use dark font
		    return 'light';
		}else{
		    //dark color, use bright font
		    return 'dark';
		}

	}

	if(!function_exists('get_font_styles')){
		function get_font_styles($font_style, $add_bg_color = false){
			$font_style_css = array(
				'default' => '',
				'hover' => '',
				'active' => '',
				'mobile' => '',
				'tablet' => '',
			);

			// for some themes we will disable the color option for the typography
			$disable_color_option = apply_filters('flo_disable_typography_color', false);

			if(isset($font_style->font_family)){
				$font_style->font_family = str_replace('"',"'",$font_style->font_family);
				$font_style_css['default'] .= 'font-family: '.$font_style->font_family.';';
			}

			if(isset($font_style->font_size)){
				// transform px to rem
				$font_size_rem = $font_style->font_size/16;
				$font_style_css['default'] .= 'font-size: '.$font_size_rem.'rem;';
				//$font_style_css['default'] .= 'line-height: '.$font_size_rem.'rem;';

				// get the tablet font size zoom factor
				// this is used to increaze the tablet font size if it is too small
				// NOTE! The option name sould be the same for all the themes
				$tablet_zoom_ratio = get_field('flo-tablet_font-size-zoom', 'options'); 
				

				if( is_numeric($tablet_zoom_ratio) && $tablet_zoom_ratio != 0){
					$small_font = 1.8; // we will zoom in only the fonts smaller than this

					// if a specific theme needs a different value, use this filter
					$small_font = apply_filters('flo_tablet_font_zoom_factor',$small_font);

					// we do not Zoom In the fonts greater than 1.8 rem
					if($font_size_rem < $small_font){
						$true_tablet_font_size = $font_size_rem * (1+ $tablet_zoom_ratio/100 );
						if($true_tablet_font_size > $small_font){
							$true_tablet_font_size = $small_font;
						}

						$font_style_css['tablet'] .= 'font-size: '.$true_tablet_font_size.'rem;';
					}

				}
			}

			if(isset($font_style->font_size_mobile)){
				$font_size_mobile_rem = $font_style->font_size_mobile/16;
				$font_style_css['mobile'] .= 'font-size: ' . $font_size_mobile_rem . 'rem';
			}

			if(isset($font_style->letter_spacing)){
				$font_style_css['default'] .= 'letter-spacing: '.($font_style->letter_spacing).'em;';
				$font_style_css['offset'] = $font_style->letter_spacing;
			}
			if(isset($font_style->word_spacing)){
				$font_style_css['default'] .= 'word-spacing: '.($font_style->word_spacing).'em;';
			}
			if(isset($font_style->line_height)){
				$font_style_css['default'] .= 'line-height: '.($font_style->line_height).'em;';
			}

			// default font styles
			if( !$disable_color_option && isset($font_style->default->font_color)){
				$font_style_css['default'] .= 'color: '.$font_style->default->font_color.';';
				$flo_light_dark = '';
				// if we need the bg color to have a contrast
				if($add_bg_color){
					$flo_light_dark = flo_light_dark($font_style->default->font_color);
					if($flo_light_dark == 'light'){
						$font_style_css['default'] .= 'background-color: #000;';
					}
				}
			}
			if(isset($font_style->default->font_case)){
				$font_style_css['default'] .= 'text-transform: '.$font_style->default->font_case.';';
			}
			if(isset($font_style->default->font_accent->bold) && $font_style->default->font_accent->bold){
				$font_style_css['default'] .= 'font-weight: bold;';
			}else{
				$font_style_css['default'] .= 'font-weight: normal;';
			}
			if(isset($font_style->default->font_accent->italic) && $font_style->default->font_accent->italic){
				$font_style_css['default'] .= 'font-style: italic;';
			}
			if(isset($font_style->default->font_accent->underline) && $font_style->default->font_accent->underline){
				$font_style_css['default'] .= 'text-decoration: underline;';
			}



			if( (isset($font_style->hover_state->edit_advanced) && $font_style->hover_state->edit_advanced) || !isset($font_style->hover_state->edit_advanced) ){


				// link_hover styles
				if( !$disable_color_option && isset($font_style->hover_state->font_color) &&
					$font_style->hover_state->font_color != $font_style->default->font_color){
					$font_style_css['hover'] .= 'color: '.$font_style->hover_state->font_color.';';
				}
				if(isset($font_style->hover_state->font_case) &&
					$font_style->hover_state->font_case != $font_style->default->font_case){
					$font_style_css['hover'] .= 'text-transform: '.$font_style->hover_state->font_case.';';
				}
				if(isset($font_style->hover_state->font_accent->bold) && $font_style->hover_state->font_accent->bold){
					$font_style_css['hover'] .= 'font-weight: bold;';
				}
				if(isset($font_style->hover_state->font_accent->italic) && $font_style->hover_state->font_accent->italic){
					$font_style_css['hover'] .= 'font-style: italic;';
				}
				if(isset($font_style->hover_state->font_accent->underline) && $font_style->hover_state->font_accent->underline){

					$font_style_css['hover'] .= 'text-decoration: underline;';
				}
			}

			if( (isset($font_style->active->edit_advanced) && $font_style->active->edit_advanced) || !isset($font_style->active->edit_advanced) ){
				// link_hover styles
				// add the custom class for active
				if(isset($font_style->active->font_color) &&
					$font_style->active->font_color != $font_style->default->font_color){
					$font_style_css['active'] .= 'color: '.$font_style->active->font_color.';';
				}
				if(isset($font_style->active->font_case) &&
					$font_style->active->font_case != $font_style->default->font_case){
					$font_style_css['active'] .= 'text-transform: '.$font_style->active->font_case.';';
				}
				if(isset($font_style->active->font_accent->bold) && $font_style->active->font_accent->bold){
					$font_style_css['active'] .= 'font-weight: bold;';
				}
				if(isset($font_style->active->font_accent->italic) && $font_style->active->font_accent->italic){
					$font_style_css['active'] .= 'font-style: italic;';
				}
				if(isset($font_style->active->font_accent->underline) && $font_style->active->font_accent->underline){
					$font_style_css['active'] .= 'text-decoration: underline;';
				}
			}
			// deb_e($font_style->font_style_name);
			// deb_e($font_style_css);

			return $font_style_css;
		}
	}


	if(!function_exists('get_custom_font_face')){
		function get_custom_font_face($font_style){

			// if we are dealing with a system font, we don't need to do anything
			if($font_style->font_type == 'system_font'){
				return;
			}

			$fontface = '';
			$fontface .= '@font-face {';
			$fontface .= 'font-family: "'.$font_style->font_family.'"; ';

			$lines = array();
			$eot = '';

			$hash = 'qwef8580e913efe41ca7d40';
			$floexporturl = get_option( 'floexporturl', $hash );
			//deb_e($font_style);
			if(isset($font_style->font_url) && is_array($font_style->font_url) ){

				// we want tp get the current site scheme URL
				$template_directory_uri_components = parse_url(get_template_directory_uri());
				$template_scheme = $template_directory_uri_components['scheme']; // this scheme will be used to check if the given font URL scheme is the same as current site scheme

				foreach ($font_style->font_url as $key => $value) {
					$line = '';


					$parse_url = parse_url($value->url); // we will use the $parse_url object to acces the curren usrl host name

					// if the host URL of the current font is not the same as the current site host
					if(isset($parse_url['host']) && false === strpos( get_template_directory_uri(), $parse_url['host']) ){

						// if the current font URL contains '/theme-files'
						if(strpos($value->url, '/theme-files')){
							// we want to replace the remote URL from the imported settings
							// i.e.
							//http://demo.flothemes.com/osaka-export/wp-content/themes/osaka/theme-files/public/fonts/default/Unna-Bold.ttf
							// we want to replace 'http://demo.flothemes.com/osaka-export/wp-content/themes/osaka' with the
							// users URL
							$pattern = '/.*theme-files/i';
							$replacement = get_template_directory_uri().'/theme-files';
							$value->url = preg_replace($pattern, $replacement, $value->url);
						}


						// if the current font URL contains '/files/'
						if(strpos($value->url, '/files/')){
							// replace the remote URL from imported custom fonts
							$uploads = wp_upload_dir();
							$upload_path = $uploads['baseurl']; // this is the 'Uploads' directory base name

							// when the fonts are imported from the demo site we have a format simmilar to the following sample:
							// http://demo.flothemes.com/osaka2/files/2017/03/AlegreyaSans-Medium.ttf
							// therefore we will replace everything up to '/files' with the curent site's upload directory
							$pattern = '/.*\/files/i';
							$replacement = $upload_path;
							$value->url = preg_replace($pattern, $replacement, $value->url);
						}
					}

					// replace http with https, or https with http if necessary
					// check if the current URL scheme is the same as this site's URL scheme
					$font_url_components = parse_url($value->url);
					if( isset($font_url_components['scheme']) && $font_url_components['scheme'] != $template_scheme){
						$value->url = str_replace($font_url_components['scheme'],$template_scheme, $value->url );
					}

					//var_dump($parse_url);

					switch ($value->format) {

						case 'otf':
					        $line = "url('" . $value->url . "') format('opentype')";
					        break;
					    case 'svg':
					        $line = "url('" . $value->url . "') format('svg')";
					        break;
				        case 'ttf':
					        $line = "url('" . $value->url . "') format('truetype')";
					        break;
				        case 'woff':
					        $line = "url('" . $value->url . "') format('woff')";
					        break;
				        case 'woff2':
					        $line = "url('" . $value->url . "') format('woff2')";
					        break;
					    case 'eot':
					        $line = "url('" . $value->url . "?#iefix') format('embedded-opentype')";
					        $eot = "url('" . $value->url . "');"; /* IE9 Compat Modes */
					        break;

					}
					$lines[] = $line;
				}

			}
			

			if(strlen($eot)){
				$fontface .= "src: ".$eot; // the eot font should be first
			}

			if(sizeof($lines)){
				$fontface .= "src: ".implode(",",$lines).";";
			}

			$fontface .= "}";

			return $fontface;
		}
	}

	/**
	 *
	 * Get the font style for a single 'font_style_select' option
	 *
	 * @param font_style_select_name - string : The name of the option we need to generate styles
	 * @param $is_option - bool : a flag that tells us if we are working with a option or a meta data
	 *  @param $is_subfield - bool : a flag that tells us if we are working with a subfield. Should be used in a acf loop
	 *
	 * @return string - the generated CSS
	 */
	function flo_get_font_style($font_style_select_name, $is_option = true, $is_subfield = false){

		if($is_subfield){
			$font_select = get_sub_field_object($font_style_select_name);
		}else{
			if($is_option){
				$font_select = get_field_object($font_style_select_name, 'options');
			}else{
				$font_select = get_field_object($font_style_select_name);
			}
		}


		if(isset($font_select['typography_option_name']) && strlen($font_select['typography_option_name'])){
			$typography_option_name = $font_select['typography_option_name'];
		}else{
			$theme_typography_option_name = 'flo-typography';
			$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);
			
			$typography_option_name = $theme_typography_option_name;
		}

		if(isset($font_select['value']['font_style']['name']) && strlen(trim($font_select['value']['font_style']['name']))){
			$font_style_name = $font_select['value']['font_style']['name'];
			$font_styles_data = get_font_styles_data($typography_option_name);

			if(isset($font_styles_data['font_style_css'][$font_style_name])){
				return $font_styles_data['font_style_css'][$font_style_name];
			}else{

				flo_console(' (1) there is no data for the '.$font_style_name);
			}

		}

	}

  function flo_get_font_style_by_option_value($option_value, $field) {
    $font_select = $option_value;

		if(isset($font_select['typography_option_name']) && strlen($font_select['typography_option_name'])){
			$typography_option_name = $font_select['typography_option_name'];
		}else{
			$theme_typography_option_name = 'flo-typography';
			$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);
			
			$typography_option_name = $theme_typography_option_name;
		}

		if(isset($font_select['font_style']['name']) && strlen(trim($font_select['font_style']['name']))){
			$font_style_name = $font_select['font_style']['name'];
			$font_styles_data = get_font_styles_data($typography_option_name);

//deb_e($font_styles_data); die();

			if(isset($font_styles_data['font_style_css'][$font_style_name])){
				//var_dump($field['_name']);
				$css_selector = '';
				if(isset($option_value['font_style']['css_selector'])){
					$css_selector = $option_value['font_style']['css_selector'];
				}

				if(strlen(trim($css_selector))){
					$font_styles_data['font_style_css'][$font_style_name]['css_selector'] = $css_selector;
				}else{
					$font_styles_data['font_style_css'][$font_style_name]['css_selector'] = $field['default_css_selectors'];
				}

				if(isset($option_value['font_style']['hover_item_css_selector'])){
					$hover_css_selector = $option_value['font_style']['hover_item_css_selector'];
				}else{
					$hover_css_selector = '';
				}


				if(strlen(trim($hover_css_selector))){
					$font_styles_data['font_style_css'][$font_style_name]['hover_css_selector'] = $hover_css_selector;
				}else{
					if(isset($field['hover_css_selectors'])){
						$font_styles_data['font_style_css'][$font_style_name]['hover_css_selector'] = $field['hover_css_selectors'];
					}else{
						$font_styles_data['font_style_css'][$font_style_name]['hover_css_selector'] = '';
					}

				}

				if(isset($option_value['font_style']['active_item_css_selector'])){
					$active_css_selector = $option_value['font_style']['active_item_css_selector'];
				}else{
					$active_css_selector = '';
				}

				if(strlen(trim($active_css_selector))){
					$font_styles_data['font_style_css'][$font_style_name]['active_css_selector'] = $active_css_selector;
				}else{
					if(isset($field['active_css_selectors'])){
						$font_styles_data['font_style_css'][$font_style_name]['active_css_selector'] = $field['active_css_selectors'];
					}else{
						$font_styles_data['font_style_css'][$font_style_name]['active_css_selector'] = '';
					}

				}

				return $font_styles_data['font_style_css'][$font_style_name];
			}else{
				flo_console(' (2) there is no data for the '.$font_style_name);
			}

		}
  }

  	function flo_render_custom_css($field_data){

		$custom_css = '';
		$custom_css_mobile = '';
		$custom_css_tablet = '';

		// if the current field has set a custom css selector we will use it
		if(isset($field_data['value']['css_selector']) && strlen(trim($field_data['value']['css_selector'])) ){
			$css_selector = $field_data['value']['css_selector'];
		}else{
			// othewise we will use the default_css_selectors
			$css_selector = $field_data['default_css_selectors'];
		}

//deb_e($field_data['value']);
		if(strlen(trim($css_selector)) ){

			if( isset($field_data['value']['default']) ){

				$custom_css .= $css_selector.' { '.$field_data['value']['default'] .'}';
			}

			// tablet font size
			if( isset($field_data['value']['tablet']) && strlen(trim($field_data['value']['tablet'])) ){
				$custom_css_tablet .= $css_selector.' { '.$field_data['value']['tablet'] .'}';
			}

			if( isset($field_data['value']['mobile']) ){

				if(is_numeric($field_data['value']['mobile'])){
					$font_size_mobile_rem = 'font-size: '.$field_data['value']['mobile']/16 .'rem';
				}else{
					$font_size_mobile_rem = $field_data['value']['mobile'];
				}
				$custom_css_mobile .= $css_selector.' { '.$font_size_mobile_rem .'}';
			}

//deb_e($field_data['value']);

			if(isset($field_data['value']['active_css_selector'])){
				$active_css_selectors = $field_data['value']['active_css_selector'];
			}else{
				$active_css_selectors = '';
			}

			if(isset($field_data['value']['hover_css_selector'])){
				$hover_css_selectors = $field_data['value']['hover_css_selector'];
			}else{
				$hover_css_selectors = '';
			}


			if(isset($field_data['value']['hover']) &&
				strlen(trim($field_data['value']['hover']) ) ){

				if( isset($hover_css_selectors) && strlen(trim($hover_css_selectors)) ){
					$hover_css_selectors_val = $hover_css_selectors;
				}else{
					$hover_css_selectors_val = $css_selector;
				}

				$new_css_selector_array = array();
				// incase we have multiple selectors:
				foreach (explode(',',$hover_css_selectors_val) as $key => $single_css_selector) {
					$new_css_selector_array[] = $single_css_selector.':hover';
				}

				$new_css_selector = implode(',',$new_css_selector_array);
				$custom_css .= $new_css_selector.' { '.$field_data['value']['hover'] .'}';
			}

			if( isset($active_css_selectors) && strlen(trim($active_css_selectors)) && isset($field_data['value']['active']) &&
				strlen(trim($field_data['value']['active'])) ){

				$custom_css .= $active_css_selectors.' { '.$field_data['value']['active'] .'}';
			}
		}
		$result['custom_css'] = $custom_css;
		$result['custom_css_mobile'] = $custom_css_mobile;
		$result['custom_css_tablet'] = $custom_css_tablet;
		return $result;
	}

	/**
	 *
	 * Check the font style options and render the font faces for the custom fonts, enqueue the google fonts,
	 * and render the css styles for the fonts
	 *
	 */
	if(!function_exists('flo_get_font_styles')){
		add_action('wp_enqueue_scripts', 'flo_get_font_styles',12);
		function flo_get_font_styles(){


			// example of the font styles info: http://pastebin.com/9WeNEKP0
			$theme_typography_option_name = 'flo-typography';
			$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

			$font_styles_data = get_font_styles_data($theme_typography_option_name);
//deb_e($font_styles_data); die();
			//Handle Google fonts enque
			if(isset($font_styles_data['google_font_face']) && is_array($font_styles_data['google_font_face']) && sizeof($font_styles_data['google_font_face'])){
				$family_name = implode('|',$font_styles_data['google_font_face']);
				$family_name = trim(str_replace(' ','+',$family_name));
				$query_args = array(
					'family' => $family_name
					//'subset' => 'latin,latin-ext',
				);
				wp_register_style( 'admin_google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
			    wp_enqueue_style('admin_google_fonts');
			}

			// wp_enqueue_style(
			// 	'theme-css',
			// 	get_template_directory_uri() . "/theme-files/public/css/style.css"
			// );
			wp_enqueue_style("theme-css");

			$custom_css = '';
			$custom_css_mobile = '';
			$custom_css_tablet = '';

			//Handle font face for the custom fonts face
			if(isset($font_styles_data['custom_font_face'])){
				foreach ($font_styles_data['custom_font_face'] as $key => $font_face) {
					$custom_css .= $font_face;
				}
			}

			// retrieve all ACF fields

			$current_posts_acf_fields = array(); // init with an empty array

			// there are themes that have page templates with Font options, 
			// and for those pages we need to render the Font Styles
			// Also there are themes whose pages that are based only on flexible content.
			// THese themes do not need the Page fonts options
			$need_field_objects = true;

			$need_field_objects = apply_filters('flo_pages_need_font_field_objects', $need_field_objects);
			if($need_field_objects){
				$current_posts_acf_fields = get_field_objects(); // acf fields for the current page
			}

			$options_acf_fields = flo_get_options_field_objects(); // acf fields used on option page
			//deb_e($options_acf_fields);
			if(!is_array($current_posts_acf_fields)){
				$current_posts_acf_fields = array();
			}
			if(!is_array($options_acf_fields)){
				$options_acf_fields = array();
			}
//var_dump($options_acf_fields);
			//merge these 2 arrays to have all the ACF fields in a single array
			$acf_fields = array_merge($current_posts_acf_fields, $options_acf_fields);

			// iterate throught all acf fields to get only those of 'font_style_select' type
			foreach ($acf_fields as $field_key => $field_data) {

				$active_css_selectors = '';
				$hover_css_selectors = '';

				if('font_style_select' == $field_data['type']){
					// if there is no font style selected, we will use the defalult one
					if(!isset($field_data['value']['font_style']['name']) || '' == trim($field_data['value']['font_style']['name'])){

						$field_data['value']['font_style']['name'] = $field_data['default_typography_style'];
					}

					// var_dump('font_style_select' == $field_data['type']);
					// var_dump(isset($field_data['value']['font_style']));
					// var_dump(sizeof($field_data['value']['font_style']));
				}

				if('font_style_select' == $field_data['type'] && isset($field_data['value']) && sizeof($field_data['value'])){//deb_e($field_data['value']);

					$custom_style = flo_render_custom_css($field_data);

					$custom_css .= $custom_style['custom_css'];

					if(isset($custom_style['custom_css_mobile']) && strlen($custom_style['custom_css_mobile']) ){
						$custom_css_mobile .= $custom_style['custom_css_mobile'];
					}

					if(isset($custom_style['custom_css_tablet']) && strlen($custom_style['custom_css_tablet']) ){
						$custom_css_tablet .= $custom_style['custom_css_tablet'];
					}

				}

				// TO DO: make the cloned typography options work
				// if('clone' == $field_data['type']){
				// 	foreach ($field_data['sub_fields'] as $clone_field_key => $clone_field_data) {
				// 		//deb_e($clone_field_data);
				// 		if( 'font_style_select' == $clone_field_data['type']){

				// 			// if there is no font style selected, we will use the defalult one
				// 			if(!isset($clone_field_data['value']['font_style']['name']) || '' == trim($clone_field_data['value']['font_style']['name'])){

				// 				$clone_field_data['value']['font_style']['name'] = $clone_field_data['default_typography_style'];
				// 			}

				// 			if( isset($clone_field_data['value']) && sizeof($clone_field_data['value'])){

				// 				$custom_css .= flo_render_custom_css($clone_field_data);

				// 			}
				// 		}
				// 	}
				// }
			}
//deb_e($custom_css_mobile);
			if(strlen($custom_css_mobile)){
				$custom_css .= '@media (max-width: 767px) { '.$custom_css_mobile.' }';
			}

			if(strlen(trim($custom_css_tablet)) ){
				$custom_css .= '@media (min-width: 768px) and (max-width: 1024px){ '.$custom_css_tablet.' }';
			}

			// append out custom css after the theme css
			wp_add_inline_style( 'theme-css', $custom_css );
		}

	}



/**
 *
 * Find the attachment in the DB by name and return it's guid
 * @param string - font_name: the name of the attachment file
 * @param string - font_type: 'ttf/otf/woff/svg' . There may be several font files with the same nam but different format. 
 * 								And in order to return the correct guid, we need to pass the font type as well
 * 
 * @return strin/bool - the file guid if succesfully found, or FALSE otherwhise.
 *
 */
if( ! ( function_exists( 'flo_get_attachment_by_post_name' ) ) ) {
    function flo_get_attachment_by_post_name( $font_name, $font_type ) {

    	//post_mime_type

    	$mime_type = array(
    		'ttf' => 'font/ttf',
		    'otf' => 'font/otf',
		    'woff' => 'application/font-woff',
		    'svg' => 'image/svg+xml'
    	);

    	if(isset($mime_type[$font_type])){
    		$post_mime_type = $mime_type[$font_type];
    	}else{
    		return false;
    	}


        $args = array(
            'posts_per_page' => 1,
            'post_type'      => 'attachment',
            'title'           => trim ( $font_name ),
            'post_status'	=> 'inherit',
            'post_mime_type' => $post_mime_type
        );
        $get_attachment = new WP_Query( $args );

        if ( $get_attachment->post_count && $get_attachment->posts[0] ){
        	return $get_attachment->posts[0]->guid;
        }else{
        	return false;
        }
    }
}



/**
 *
 * Iterate through the typoghraphy options and replace the fonts URLs with the URLS from the current server if found in the Uploads/DB
 *
 */

function replace_typography_fonts(){

	$replaced_fonts = array();

	$theme_typography_option_name = 'flo-typography';
	$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

	// retrieve the Typography option
	$t = get_option('options_'.$theme_typography_option_name);

	// check if the Typography data is available in the valid format
	if(is_array($t)){ 
		// Note! the data may not b an array if it was just imported using Dummy data importer.
		// In order to solve that it is necessary to save once the typography options

		$font_styles = json_decode($t['font_styles']);

		$custom_fonts = json_decode($t['custom_fonts']);

		// echo '<pre>';
		// //echo print_r(  $font_styles );
		// echo '</pre>';


		// iterate through 'theme_defaults'
		foreach ($font_styles->theme_defaults as $key => $fontstyle) {
			foreach ($fontstyle->font_url as $font_url => $font_value) {
				$local_font_url = flo_get_attachment_by_post_name( $font_value->name, $font_value->format );

				// if there is a local version of this font, we prepare it for replacement.
				if($local_font_url){
					$replaced_fonts['theme_defaults'][] = $font_value->url .' Replaced with: '. $local_font_url ;
					$font_value->url = $local_font_url;

				}else{
					$parse_url = parse_url($font_value->url); // we will use the $parse_url object to acces the curren usrl host name
					if(isset($parse_url['host']) && false === strpos( get_template_directory_uri(), $parse_url['host']) ){
						// if the current font URL contains '/theme-files'
						if(strpos($font_value->url, '/theme-files')){
							$original_font_url = $font_value->url;
							// we want to replace the remote URL from the imported settings
							// i.e.
							//http://demo.flothemes.com/osaka-export/wp-content/themes/osaka/theme-files/public/fonts/default/Unna-Bold.ttf
							// we want to replace 'http://demo.flothemes.com/osaka-export/wp-content/themes/osaka' with the
							// users URL
							$pattern = '/.*theme-files/i';
							$replacement = get_template_directory_uri().'/theme-files';
							$font_value->url = preg_replace($pattern, $replacement, $font_value->url);

							$replaced_fonts['theme_defaults'][] = $original_font_url .' Replaced with: '. $local_font_url ;
						}


						// if the current font URL contains '/files/'
						if(strpos($value->url, '/files/')){
							$original_font_url = $font_value->url;

							// replace the remote URL from imported custom fonts
							$uploads = wp_upload_dir();
							$upload_path = $uploads['baseurl']; // this is the 'Uploads' directory base name

							// when the fonts are imported from the demo site we have a format simmilar to the following sample:
							// http://demo.flothemes.com/osaka2/files/2017/03/AlegreyaSans-Medium.ttf
							// therefore we will replace everything up to '/files' with the curent site's upload directory
							$pattern = '/.*\/files/i';
							$replacement = $upload_path;
							$font_value->url = preg_replace($pattern, $replacement, $font_value->url);

							$replaced_fonts['theme_defaults'][] = $original_font_url .' Replaced with: '. $local_font_url ;
						}
					}
					

				}

			}
		}

		$t['font_styles'] = json_encode($font_styles);

		// iterate through 'custom_fonts'
		foreach ($custom_fonts as $cf_key => &$cf_value) {
			$cf_local_url = flo_get_attachment_by_post_name( $cf_value->name, $cf_value->format );

			// if there is a local version of this font, we prepare it for replacement.
			if($cf_local_url){
				$replaced_fonts['custom_fonts'][] = $cf_value->url.' Replaced with: '. $cf_local_url;
				$cf_value->url = $cf_local_url;
				
			}
		}

		$t['custom_fonts'] = json_encode($custom_fonts);

		// update the fonts options in the DB
		update_option('options_'.$theme_typography_option_name, $t);

		$replaced_fonts['message'] = 'All good! The fonts URLs were repaced';
	}else{
		// ask the user to save the options to have the proper data format.
		$replaced_fonts['message'] = 'The fonts data is not in the correct format. Save the typography options and try again';
	}



	if(isset($_POST['action']) && $_POST['action'] == 'replace_typography_fonts' ){
		// if the function is called via Ajax
		echo json_encode($replaced_fonts);
		exit();
	}

}
add_action('wp_ajax_replace_typography_fonts' , 'replace_typography_fonts' );

?>
