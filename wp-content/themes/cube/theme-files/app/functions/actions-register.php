<?php
/**
 * Register all the themes actions here and their functions
 *
 * @since  0.1.0
 */

	// used for Slideshow to add data to the new added slides
	add_action('wp_ajax_get_slide_data', 'flo_get_slide_data' );
	if(!function_exists('flo_get_slide_data')){
		function flo_get_slide_data(){

		    if (isset($_POST['post_id']) && isset($_POST['attachment_id']) && isset($_POST['container_class'])) {

		        $key = $_POST['container_class']; // the unique key that will be used mark each slide info

		        echo flo_get_slider_image_meta($_POST['attachment_id'], array(), $_POST['post_id'], $key);

		    }

		    exit;
		}
	}

	//add_action( 'init', 'flo_plugins_loaded' );
	if(!function_exists('flo_plugins_loaded')){
		function flo_plugins_loaded(){
			// var_dump('dddd');
			// if(!defined('COSMO_TEMPLATE_PATH')){
			// 	define('COSMO_TEMPLATE_PATH',get_template_directory().'/theme-files/floshortcodes/');
			// }else{
			// 	uopz_undefine('COSMO_TEMPLATE_PATH');
			// 	define('COSMO_TEMPLATE_PATH',get_template_directory().'/theme-files/floshortcodes/');
			// }
			define('COSMO_TEMPLATE_PATH',false);
			define('COSMO_TEMPLATE_PATH',get_template_directory().'/theme-files/floshortcodes/');
		}
	}


	/**
	 *
	 * Set the correct number of posts per page for the search and archive page
	 *
	 */
	add_action( 'pre_get_posts', 'flo_archive_nr_posts_per_page' );
	if(!function_exists('flo_archive_nr_posts_per_page')){
		function flo_archive_nr_posts_per_page($query){
			if(!is_admin()){
				global $flo_options;
				$archive_posts_per_page = '';

				if(is_archive() || is_home()){
					if(isset($flo_options['flo-archives']['page-listing-listing__posts-per-page'])){
						$archive_posts_per_page = $flo_options['flo-archives']['page-listing-listing__posts-per-page'];
					}
				}

				if(is_search()){
					if(isset($flo_options['flo-search']['page-listing-listing__posts-per-page'])){
						$archive_posts_per_page = $flo_options['flo-search']['page-listing-listing__posts-per-page'];
					}
				}

				if(is_numeric($archive_posts_per_page)){
					$query->set( 'posts_per_page', $archive_posts_per_page );
				}
			}
			
		}
	}

?>
