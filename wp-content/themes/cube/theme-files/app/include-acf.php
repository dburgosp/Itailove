<?php


	//This function will include all files from a specified directory.
	//You can filter files by extension (php for default)
	if(!function_exists('flo_include_from')){
		function flo_include_from($dir, $ext='php'){
			$opened_dir = opendir($dir);
			while ($element=readdir($opened_dir)){
			   $fext=substr($element,strlen($ext)*-1);
			   if(($element!='.') && ($element!='..') && ($fext==$ext)){
				  include($dir.$element);
			   }
			}
			closedir($opened_dir);
		}
	}

	/**
	 *
	 * get an array of terms by term name
	 * ususlly used by the ACF fields
	 *
	 * @param
	 * @term_name - string the term name
	 *
	 * @return
	 * @terms_array - array in the following format $term->term_id => $term->name;
	 */
	if(!function_exists('flo_get_terms_array')){
		function flo_get_terms_array($term_name){
			$terms_array = array();
			$terms = get_terms($term_name);

			if(is_array($terms) && sizeof($terms)){
				foreach ($terms as $key => $term) {
					$terms_array[$term->term_id] = $term->name;
				}
			}
			return $terms_array;
		}
	}

	/**
	 * Show a message to the user when the ACF is disabled
	 *
	 * @since    1.0.0
	 */
	if(!function_exists('flo_acf_deactivated_admin_notice')){
		function flo_acf_deactivated_admin_notice() {

			?>
			<div class="updated">
		        <p><?php _e( 'ACF plugin was disabled to avoind conflicts with the FloCore plugin', 'flotheme' ); ?></p>
		    </div>
			<?php

		}
	}


	// 1. customize ACF path
	add_filter('acf/settings/path', 'flo_acf_settings_path');

	function flo_acf_settings_path( $path ) {

	    // update path
	    $path = get_template_directory() . '/acf/acf-plugin/';

	    // return
	    return $path;

	}


	// 2. customize ACF dir
	add_filter('acf/settings/dir', 'flo_acf_settings_dir');

	function flo_acf_settings_dir( $dir ) {

	    // update path
	    $dir = get_template_directory_uri() . '/acf/acf-plugin/';

	    // return
	    return $dir;

	}


	// 3. Hide ACF field group menu item
	if (!(defined('FLO_ENVIROMENT') && FLO_ENVIROMENT == 'DEV')) {
		// for Developers:
		// in wp-config.php add
		// define('FLO_ENVIROMENT', 'DEV');
		// to have access to the ACF settings
		add_filter('acf/settings/show_admin', '__return_false');
	}


	// 4. Include ACF
	include_once( get_template_directory() . '/acf/acf-plugin/acf.php' );


	// Add below ACF extensions:
	include_once get_template_directory() . '/acf/acf-extensions/advanced-custom-fields-number-slider/acf-number-slider.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-image-select/acf-image_select.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-flo-typography/acf-flo-typography.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-font-style-select/acf-font-style-select.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-flo-quick-typography/acf-flo-quick-typography.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-field-import-export/acf-import-export.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-field-flo-select-sidebar/acf-select_sidebar.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-enhanced-message-field/acf-enhanced-message.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-code-field/acf-code-field.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-flo-image/flo-image.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-flo-flexible-content/flo-flexible-content.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-flo-template-selector/acf-flo-template-selector.php';
	include_once get_template_directory() . '/acf/acf-extensions/acf-flo-backup-stylekit/acf-flo-backup-stylekit.php';

	add_action('init','flo_include_acf_fields');

	if(!function_exists('flo_include_acf_fields')){
		function flo_include_acf_fields(){
			// include all the exported configurations available in the folder: /acf/acf-fields/
			flo_include_from(get_template_directory().'/theme-files/acf-fields/');
		}
	}


	/**
	 *
	 * Sometimes we need the acf description below the  acf field.
	 * Because ACF does not provide such an option, we use js to
	 * move the description for the fields with the class 'acf-field--instructions-below-field'
	 * below the field
	 */
	if(!function_exists('flo_acf_admin_head')){
		function flo_acf_admin_head(){
	    ?>

		    <script type="text/javascript">

		    (function($){
		    	setTimeout(function(){

			    	jQuery('.acf-field--instructions-below-field').each(function( index ) {

			    		// copy the current description in a variable
					  	var descr = jQuery(this).find('.acf-label .description');

					  	// remove the original desctiption
					  	jQuery(this).find('.acf-label .description').remove();

					  	// move the description below the acf-input
					  	jQuery(this).find('.acf-input').append(descr);
					});
		    	}, 1000);

		    })(jQuery);
		    </script>
		    <?php
		}
	}
	add_action('acf/input/admin_head', 'flo_acf_admin_head');


?>
