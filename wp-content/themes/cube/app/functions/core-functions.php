<?php
/**
 * Add here usefull functions that will be used for each theme
 *
 */

/**
*
* Function used for debuging
*
**/
if(!function_exists('debe')){
	function debe( $data ){
		print '<pre style="margin:10px; border:1px dashed #999999; padding:10px; color:#333; background:#ffffff; margin-left: 150px;">';
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        //print "[ File : " . self::short( $caller['file'] ) . " ][ Line : " . $caller['line'] . " ]\n";
        print "--------------------------------------------------------------\n";
		print_r( $data );
		print "</pre>";
	}
}

/**
 *
 * Console log something
 *
 */
if(!function_exists('flo_console')){


	// function flo_console($info){
	// ? >
	// <script>
	// 	console.log('< ?php echo $info; ? >');
	// </script>
	// < ?php
	// }

  function flo_console($info) {
    // ALEX L -> DUMMY DEFINE OF THE SCRIPT ABOVE -> The thing can put the scripts anywhere. Even outside the html tag. That's what happened with my stuff.
  }
}
/**
 * Load Theme Variable Data
 * @param string $var
 * @return string
 */
if(!function_exists('flo_theme_data_variable')){
	function flo_theme_data_variable($var)
	{
		if (!is_file(STYLESHEETPATH . '/style.css')) {
			return '';
		}

		$theme_data = wp_get_theme();
		return $theme_data->{$var};

	}
}

/**
 *
 * Get the current stylekit name save in the option
 * @param none
 * @return string
 */
if(!function_exists('flo_get_current_style_kit')){
	function flo_get_current_style_kit(){
		// filtareble option name
		$stylekit_option_name = apply_filters('flo_theme_stylekit_option_name','flo-theme_stylekit');
		// filtareable default stylekit name
		$theme_default_stylekit = apply_filters('flo_theme_default_stylekit','style_1');

		// get the saved stylekit name from the option
		$current_stylekit = get_option($stylekit_option_name, $default = $theme_default_stylekit);

		return $current_stylekit;
	}
}

/**
 *
 * Check if the current page has any left or right sidebars
 *
 * @return string - the location of the sidebar(left_sidebar, right_sidebar) if it exists, 'full_width' otherwise
 */
if(!function_exists('flo_get_page_layout')){
	function flo_get_page_layout(){
		global $flo_options; // $flo_options  should be defined in the comon scope

		// the following variable is filterable
		// if you have a theme that has other post types that supports sidebars
		// modify the array using 'flo_post_types_with_sidebars' filter
		$post_types_with_sidebars = apply_filters( 'flo_post_types_with_sidebars', array('post', 'page') );

		// default values
		$page_layout_data = array(
			'layout_type' 	=> 'full_width',
			'sidebar'		=> 'main',
			'sidebar_container_class' => '',
			'body_sidebar_class' => ''

		);

		if(is_singular()){
			//flo-page__default-layout
			//flo-page__ default_sidebar
			$post_type = get_post_type();

			if(in_array( $post_type, $post_types_with_sidebars )){

				$layout_type = get_field('layout_type');

				if($layout_type){
					$layout_type = $layout_type;
				}else{ // if the meta is not available for the current post
					// we need to get the default option set in the theme settings

					// we need to have the same standard for the layout options
					// example options:
					//flo-page__default-layout
					//flo-page__ default_sidebar
					// they should be named like: flo-'.$post_type.'__default-layout
					$post_default_layout = get_field('flo-'.$post_type.'__default-layout','options');

					if( $post_default_layout && strlen($post_default_layout) ){
						$layout_type = $post_default_layout;
					}else{
						$layout_type = 'full_width';
					}

				}

				// add here the pages temlates that do not support sidebars by design
				$templates_without_sidebars = array('template-home');

				// allow the possibility to overwrite this depending on the theme
				$templates_without_sidebars = apply_filters( 'flo_theme_templates_without_sidebars', $templates_without_sidebars );
				if(is_page()){

					$page_template = \Classy\Hierarchy::get_classy_template();

					if(in_array($page_template, $templates_without_sidebars)){
						// make sure we force the full width layout for the page templates that
						// do not allow having a sidebar
						$layout_type = 'full_width';
					}
				}

				// update the default layout type
				$page_layout_data['layout_type'] = $layout_type;

				if($layout_type != 'full_width'){
					// if the layout is not full width we should have a sidebar
					// and we need to check which one is assigned or use the default one
					$post_sidebar = get_field('layout_sidebar');
					if($post_sidebar){
						$layout_sidebar = $post_sidebar;
					}else{
						if(isset($flo_options) && is_array($flo_options)){
							$layout_sidebar_default = $flo_options['flo-'.$post_type.'__default-sidebar'];
						}else{
							$layout_sidebar_default = get_field('flo-'.$post_type.'__default-sidebar','options');
						}


						if( $layout_sidebar_default && strlen($layout_sidebar_default) ){
							$layout_sidebar = $layout_sidebar_default;
						}else{
							$layout_sidebar = 'main';
						}
					}



					// update the default sidebar
					$page_layout_data['sidebar'] = $layout_sidebar;

					$page_layout_data['body_sidebar_class'] = 'body_has_sidebar';

					if('left_sidebar' == $layout_type){
						$page_layout_data['sidebar_container_class'] = 'flo_sidebar--on-left';
					}else if('right_sidebar' == $layout_type){
						$page_layout_data['sidebar_container_class'] = 'flo_sidebar--on-right';
					}

				}

			}
		}

		// if you need to modify the returned data, use the folowing filter
		$page_layout_data = apply_filters('flo_page_layout_data',$page_layout_data);

		return $page_layout_data;

	}
}


add_action( 'admin_init', 'flo_check_php_version' );

if(!function_exists('flo_check_php_version')){
	function flo_check_php_version(){

		// if the current PHP version is older than 5.4
		if(version_compare(PHP_VERSION,'5.4.0') < 0){
			add_action( 'admin_notices', 'flo_old_php_version_notice' );
		}

	}
}

if(!function_exists('flo_old_php_version_notice')){
	function flo_old_php_version_notice(){
		?>
		<div class="notice notice-error is-dismissible">
	        <p style="padding: 20px; font-size: 24px; line-height: 1.4;"><?php echo sprintf(__( 'This server is using PHP version %s, but this theme requires at least version 5.4.0. %s Contact please your hosting or webmaster and ask them to upgrade the PHP to a newer version.', 'flotheme'),PHP_VERSION,'<br/>' ); ?></p>
	    </div>
		<?php
	}
}


add_action('wp_ajax_updateLayoutMeta' , 'flo_bulk_update_layout_meta' );
if(!function_exists('flo_bulk_update_layout_meta')){
	function flo_bulk_update_layout_meta(){
	    if(isset($_POST['layout_meta']) && isset($_POST['post_type'])){

	        $args = array(
	            'posts_per_page' => '-1',
	            'post_type' => $_POST['post_type'],
	            'post_status' => 'publish',
	        );
	        $posts_to_update = new WP_Query( $args ); //Get all the post whose meta you want to update
	        foreach($posts_to_update->posts as $p):

	            if(isset($_POST['sidebar_meta']) && '' != $_POST['sidebar_meta']){
	                update_post_meta( $p->ID, 'layout_sidebar', $_POST['sidebar_meta'] ); //Update all the posts meta.
	            }
	            update_post_meta( $p->ID, 'layout_type', $_POST['layout_meta'] ); //Update all the posts meta.
	        endforeach;
	    }
	    exit;
	}
}


/**
 *
 * There are options where user should provide the image ration in the formmat like '3:2'
 * We get the value of that option and if the format is correct, then we return the
 * width and height for the requested ratio.
 * In case the format is not correct, then we will return predefined values
 */
if(!function_exists('flo_get_custom_ratio_dimensions')){
	function flo_get_custom_ratio_dimensions($option_name, $nr_columns = false, $post_id = false, $block_data = false){

		if(is_numeric($post_id)){


			if($block_data && isset($block_data[$option_name] )){
				// this is used in CUBE - check flo_cube_grid_ratio()
				$ratio = $block_data[$option_name];
			}else{
				$ratio = get_field($option_name, $post_id);
			}

			if(!$ratio){
				$ratio = '1:1';
			}
		}else{
			$ratio = flo_get_option($option_name);
		}


		$ratio_array = explode(':',$ratio);
		if(isset($ratio_array[0]) && is_numeric(trim($ratio_array[0])) && isset($ratio_array[1]) && is_numeric(trim($ratio_array[1]) ) ){

			// the width will be differnet depending on the nr columns
			if($nr_columns && is_numeric($nr_columns)){
				switch ($nr_columns) {
					case 2:
							$result['width'] = 800;
						break;

					case 2:
							$result['width'] = 400;
						break;

					default: // 3 columns
							$result['width'] = 560;
						break;
				}
			}else{
				$result['width'] = 500;
			}

			$result['height'] = round($result['width']*trim($ratio_array[1])/trim($ratio_array[0]) );
		}else{
			// we will return something like 3:2
			$result['width'] = 510; // this dimmension should work for 3 columns on large screens
			$result['height'] = 340;
		}
//deb_e($result);
		return $result;
	}
}

/**
 *
 * There are options where user should provide the image size in the formmat like '600x400'
 * We get the value of that option and if the format is correct, then we return the
 * width and height.
 * In case the format is not correct, then we will return predefined values
 */
if(!function_exists('flo_get_grid_advanced_size_dimensions')){
	function flo_get_grid_advanced_size_dimensions($option_name, $post_id = false, $block_data = false){

		if(is_numeric($post_id)){
			if($block_data && isset($block_data[$option_name] )){
				$ratio = $block_data[$option_name];
			}else{
				$ratio = get_field($option_name,$post_id);
			}

		}else{
			$ratio = get_field($option_name,'option');
		}

		$ratio_array = explode('x',$ratio);

		if(isset($ratio_array[0]) && is_numeric(trim($ratio_array[0])) && isset($ratio_array[1]) && is_numeric(trim($ratio_array[1]) ) ){
			$result['width'] = trim($ratio_array[0]);
			$result['height'] = trim($ratio_array[1]);
		}else{
			// we will return something like 3:2
			$result['width'] = 510; // this dimmension should work for 3 columns on large screens
			$result['height'] = 340;
		}

		return $result;
	}
}

/**
 *
 * save the initial data for the options that do not exist yet to make sure
 * everything works smooth
 *
 */
if(!function_exists('flo_initial_options_setup')){
	function flo_initial_options_setup(){

		$theme_data = wp_get_theme();
		$theme_name = $theme_data->Name;

		//if(!get_option('flo_initial_theme_options_'.$theme_name)){
			global $wp_filesystem;
			if (empty($wp_filesystem)) {
			    require_once (ABSPATH . '/wp-admin/includes/file.php');
			    WP_Filesystem();
			}

			$initial_options = $wp_filesystem->get_contents(get_template_directory().'/theme-files/app/flotheme-options.json');
			if(is_string($initial_options) && strlen($initial_options)){
				$flothemes_options = json_decode(($initial_options));
				if(isset($flothemes_options->options)){

					$hash = '148f8580e913efe41ca7d402cc51e842';

					foreach ($flothemes_options->options as $option_name => $option_value) {
						// we're going to use a random hash as our default, to know if something is set or not
						$old_value = get_option( $option_name, $hash );

						// only import the setting if it's not present
						if ( $old_value !== $hash ) {
							//echo "\n<p>" . sprintf( __( 'Skipped option `%s` because it currently exists.', 'flotheme' ), esc_html( $option_name ) ) . '</p>';
							continue;
						}

						$theme_typography_option_name = 'flo-typography';
						$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

						if('options_'.$theme_typography_option_name == $option_name){
							update_option($option_name,$option_value);
						}else{
							acf_update_option($option_name,$option_value);
						}

						//echo "\n<p>" . sprintf( __( 'Set option `%s` .', 'flotheme' ), esc_html( $option_name ) ) . '</p>';
					}

					// save an option with the export URL to be able to replace it later
					if(isset($flothemes_options->export_template_directory_uri)){
						update_option('floexporturl', $flothemes_options->export_template_directory_uri);
					}

					flo_delete_theme_options_transient();
				}

			}

			// set a flag to not run this any more
			//update_option('migrated_matte_videos','updated successfully');
		//}


		maybe_fix_typography_options();

	}
}
add_action('after_switch_theme','flo_initial_options_setup');

/**
 *
 * On theme activation compare the existing typography styles with $default_theme_fonts
 * and if there are styles in $default_theme_fonts which are missing from the DB,
 * then we will add them
 *
 */
function maybe_fix_typography_options($typography_option_name = 'flo-typography'){
	//return;

	//$typography_styles = get_option('options_'.$typography_option_name);
	$typography_styles = get_field('flo-typography','options');

	if(is_array($typography_styles) && sizeof($typography_styles)){
		$font_styles_data = json_decode($typography_styles['font_styles']);

		if(isset($font_styles_data->theme_defaults)){
			$font_styles_data_theme_defaults = (array)$font_styles_data->theme_defaults;

			$default_fonts_set = apply_filters( 'flo_set_default_theme_fonts', array() ); // returns the default fonts specified in .../theme-files/app/fonts-theme-default.php


			$maybe_smth_updated = false;// flag to know if anything was updated
			foreach ($default_fonts_set as $f_style_key => $default_font_style) {

				// if the current default font style does not exist in the existing saved options,
				// we will add it
				if(!array_key_exists($f_style_key, $font_styles_data_theme_defaults) ){
					$font_styles_data_theme_defaults[$f_style_key] = $default_font_style;
					$maybe_smth_updated = true;
				}
			}

			if($maybe_smth_updated){
				$typography_styles['font_styles'] = json_encode(array(
														'theme_defaults' => $font_styles_data_theme_defaults)
													);

				update_option('options_'.$typography_option_name, $typography_styles);
			}
		}

	}

}



/**
 *
 * For some reason the Flo forms plugin scripts are beeing enqueued
 * therefore we need to use this action to enqueu them
 *
 */
add_action('flo_forms_after_scripts_register','flo_forms_scripts_enqueue');
if(!function_exists('flo_forms_scripts_enqueue')){
	function flo_forms_scripts_enqueue(){
		if(class_exists('Flo_Forms')){
			wp_enqueue_style('jquery_ui_styles');
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'flo-forms-public' );
			wp_enqueue_style( 'flo-forms-public' );
		}

	}
}

/**
 *
 * Fuction that validated the contact form and sends the email
 *
 */
if(!function_exists('flo_send_contact')){
	function flo_send_contact(){
		if (isset($_POST['action']) && $_POST['action'] == 'floSendContact') {

			if(isset($_POST['pid']) && (is_numeric($_POST['pid']) || 'options' == $_POST['pid'] ) ){

				$post_id = $_POST['pid'];
				$tomail   = get_field( 'flo-contact-page__contact_email' ,$_POST['pid']);

				if(!is_email($tomail)){
					$result['error_message'] = '<p class="text-error">' . __('Please add a valid contact email. ', 'flotheme') . '</p>';
				}

				$frommail = '';

				if (isset($_POST['flo-name']) && strlen($_POST['flo-name'])) {
					$name = trim($_POST['flo-name']);
				} else {
					$result['contact_name'] = '<p class="text-error">' . __('Error, name is required field. ', 'flotheme') . '</p>';
					$name                   = '';
				}

				if (isset($_POST['flo-email']) && is_email($_POST['flo-email'])) {
					$frommail = trim($_POST['flo-email']);
				} else {

					$result['contact_email'] = '<p class="text-error">' . __('Error, please enter a valid email address. ', 'flotheme') . '</p>';

				}

				$message = '';
				if (isset($_POST['flo-name'])) {
					$message .= __('Contact name: ', 'flotheme') . trim($_POST['flo-name']) . "\n";
				}
				if (isset($_POST['flo-email'])) {
					$message .= __('Contact email: ', 'flotheme') . trim($_POST['flo-email']) . "\n";
				}
				if (isset($_POST['flo-date'])) {
					$message .= __('Event date: ', 'flotheme') . trim($_POST['flo-date']) . "\n";
				}

				// other unexpected (non standard) field names
				foreach ($_POST as $post_data_key => $post_data_val) {
					$reserver_keys = array('action','flo-name','flo-email','flo-date', 'flo-subject', 'flo-message','pid');

					if( !in_array( $post_data_key, $reserver_keys) ) {
						$message .= $post_data_key .": ". trim($post_data_val) . "\n";
					}

				}
				

				if (isset($_POST['flo-subject'])) {
					$message .= __('Subject: ', 'flotheme') . trim($_POST['flo-subject']) . "\n\n";
				}


				$message .= "\n" . trim($_POST['flo-message']);

				if (is_email($tomail) && strlen($tomail) && strlen($frommail) && strlen($name) && strlen($message)) {

					//flo-contact_email_address
					$email_from = get_field('contact_page__reply_header', $post_id);


					// if this option is enabled, then we will use the visitor email in the Form field
	                if( $email_from ){

	                	$headers = array();

						$headers[] = 'From: ' . trim($_POST['flo-name']) . ' <' . trim($_POST['flo-email']). '>';


						$headers[] = 'Reply-To: ' . trim($_POST['flo-email']);


	                }else{
	                	$headers = array();

						//$headers[] = 'Reply-To: ' . trim($_POST['flo-email']);

	                    //$headers = 'Reply-To: '.trim($_POST['flo-name']) . ' ' . trim($_POST['flo-email']) .' \r\n';
	                }


					$subject = __('New email from', 'flotheme') . ' ' . get_bloginfo('name') . '.' . __(' Sent via contact form.', 'flotheme');

					$maybe_send_email = wp_mail($tomail, $subject, $message, $headers);

					if(true !== $maybe_send_email ){
						$result['error_message'] = '<p class="text-error">' . __('The email could not be sent. ', 'flotheme') . '</p>';
					}else{
						if (isset($_POST['thx_msg']) && strlen(trim($_POST['thx_msg']))) {
							$thx_msg = urldecode($_POST['thx_msg']);
						} else {
							$thx_msg = __('The email was sent successfully ', 'flotheme');
						}

						$result['message'] = '<span class="text-success" >' . $thx_msg . '</span>';
					}



				}

				echo json_encode($result);

			}

		}
		exit();
	}
}

add_action('wp_ajax_importDummySettings' , 'importDummySettings'); // for one click Settings import

/* imports dummy content and settings */
function importDummySettings(){


    global $flo_options ; // This is your opt_name.
	if(isset($_POST['folder']) && $_POST['folder'] != ''){
		//$import_folder_name = $_POST['folder'].'/';
		$import_folder_name = get_template_directory().'/theme-files/demo_content/'.$_POST['folder'].'/';
	}else{
		die(json_encode(array('message' => 'Please select the options to inport.', 'code' => -1)));
	}

	/// the code below is similoar to the one from importFwOptions function
	// any changes made there, should be added here as well
    if($import_folder_name == ''){
        $import_folder_name = get_template_directory().'/theme-files/demo_content/export/';
    }

    $fw_import_options = $import_folder_name.'flotheme-options.json'; // this it the absolute path to the settings file

    $imported_options = @json_decode( file_get_contents($fw_import_options), true ); // read the settings file and json_decode it
//deb_e($imported_options['export_template_directory_uri']); die();
    if(isset($imported_options['options']) && sizeof((array)$imported_options['options']) ){

        $theme_typography_option_name = 'flo-typography';
        $theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

        foreach ((array)$imported_options['options'] as $option_name => $option_value) {

            if('options_'.$theme_typography_option_name == $option_name){
                update_option($option_name,$option_value);
            }else{
                acf_update_option($option_name,$option_value);
            }
        }
        flo_delete_theme_options_transient();
        if(isset($imported_options['export_template_directory_uri'] )){
			update_option('floexporturl', $imported_options['export_template_directory_uri']);
		}
        die(json_encode(array('message' => 'The settings were successfully imported', 'code' => 1)));
    }

    die(json_encode(array('message' => 'Something went wrong and the settings were not imported. Please contact the administrator', 'code' => -1)));

	exit();
}


///========================================


add_action('wp_ajax_importDummyData' , 'importDummyData'); // for one click import

/* imports dummy content and settings */
function importDummyData(){

    //$import_archive = get_theme_root(PLAYGROUND_TEMPLATE) . '/' . PLAYGROUND_TEMPLATE . '/import-archives/' . implode('#', array($theme_slug, $skin_slug, $homepage_slug)) . '.zip';

    require_once get_template_directory() . '/app/functions/flo-importer/wordpress-importer.php';
    global $flo_options ; // This is your opt_name.
	if(isset($_POST['folder']) && $_POST['folder'] != ''){
		$import_dir = $_POST['folder'];
	}else{
		die(json_encode(array('message' => 'Please select the options to inport.', 'code' => -1)));
	}

    $tmp_dir = get_template_directory() . '/theme-files/demo_content/' . $import_dir . '/';
    if (class_exists('Flo_Import_Dummy_Data')) {

        $flo_importer = new Flo_Import_Dummy_Data();
        ob_start();
        $result = $flo_importer->install_programatically($tmp_dir);
        $buffer = ob_get_clean();
        if ($result && stripos($buffer, '<p>All done.') !== false) {
            $slideshows = get_posts('post_type=slideshow');
            foreach($slideshows as $slideshow){
                wp_update_post($slideshow);
            }
            $pages = get_posts('post_type=page');
            foreach($pages as $page){
                wp_update_post($page);
            }
            flo_delete_theme_options_transient();
            die(json_encode(array('message' => 'All good!', 'code' => 1)));
        } else {
            die(json_encode(array('message' => 'Something went wrong. Please contact the administrator', 'code' => -1)));
        }
    } else {
        die(json_encode(array('message' => 'Missing importer class. Please contact the administrator.', 'code' => -1)));
    }

  die;
}


if(!function_exists('flo_delete_all_options')){
	function flo_delete_all_options(){

		// the options will be deleted only if the get param is passed
		if(isset($_GET['delete_options']) && $_GET['delete_options'] == 1){
			global $wpdb;

			$option_names = $wpdb->get_col( "SELECT DISTINCT `option_name` FROM $wpdb->options WHERE `option_name` LIKE 'options_flo%' OR `option_name` LIKE '_options_flo%'" );
			//deb_e($option_names);

			if ( ! empty( $option_names ) ) {
				foreach ( $option_names as $option_name ) {
					//echo $option_name . '<br/>';
					delete_option( $option_name );
				}
			}

			// delete stylekit backup option
			$theme_data = wp_get_theme();
	        $theme_name = strtolower($theme_data->Name);
	        delete_option('flo_stylekit_backup_'.$theme_name);
		}

		// delete typography options
		if(isset($_GET['delete_typography']) && $_GET['delete_typography'] == 1){
			$theme_typography_option_name = 'flo-typography';
			$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

			delete_option('options_'.$theme_typography_option_name);
			delete_option('_options_'.$theme_typography_option_name);
		}

		// delete quick typography options
		if(isset($_GET['delete_quick_typography']) && $_GET['delete_quick_typography'] == 1){
			$theme_quick_typography_option_name = 'options_flo-quick_typography';
			$theme_quick_typography_option_name = apply_filters('flo_theme_quick_typography_option_name',$theme_quick_typography_option_name);

			delete_option($theme_quick_typography_option_name);
			delete_option('options_quick_typography');
		}


	}
}

// run the following when need to delete all the options
add_action('admin_init','flo_delete_all_options',1);


if (!function_exists("flo_get_the_date")) {
    function flo_get_the_date ($post_id) {
        return date_i18n(get_option('date_format'), get_the_time('U', $post_id));
    }
}


/**
 *
 * check if a string starts with a given substring
 *
 */
if(!function_exists('floStartsWith')){
	function floStartsWith($haystack, $needle){
	     $length = strlen($needle);
	     return (substr($haystack, 0, $length) === $needle);
	}
}


/**
 *
 * Check the theme build number,
 * and if it is different from the saved build number,
 * then we will regenerate the css file
 *
 */
add_action('admin_init','flo_set_theme_build_data');
function flo_set_theme_build_data(){
	if( defined( 'FLO_BUILD_VERSION' ) ){
		$saved_build_unumber = get_option('flobuildnumber','not-set');
		//var_dump($saved_build_unumber,FLO_BUILD_VERSION);

		if('not-set' != $saved_build_unumber){ // if the build number was saved in the DB
			if(FLO_BUILD_VERSION != $saved_build_unumber){

				maybe_fix_typography_options();

				// regenerate the WPML config file if necessary
				if(function_exists('icl_object_id')) {
					flo_generate_wpml_config();
				}
			}
		}

		update_option('flobuildnumber',FLO_BUILD_VERSION);

	}
}

/**
 *
 * When ACF options are saved, we will delete the transient for the
 * theme options in order to make sure that we work with the
 * new data
 *
 */

add_action('acf/save_post', 'flo_delete_theme_options_transient', 20);
if(!function_exists('flo_delete_theme_options_transient')){
	function flo_delete_theme_options_transient(){
		$theme_name = flo_theme_data_variable('Name');
		$options_transient_name = $theme_name . '_flo_options';
		$options_objects_transient_name = $theme_name . '_flo_options_objects';

		delete_transient($options_transient_name);
		delete_transient($options_objects_transient_name);
	}
}

/**
 *
 * Load flo_options from transient if available
 * otherwise use get_fields('options') and save the value to the transient
 *
 */
if(!function_exists('flo_get_flo_options')){
	function flo_get_flo_options(){
		
		$flo_wmpl_disable_cache = get_option( 'flo_wmpl_disable_cache', $default = false );

		$theme_name = flo_theme_data_variable('Name');
		$options_transient_name = $theme_name . '_flo_options';

		// if WPML is enabled, then we need to store the options for each language in a different transient
		if(function_exists('icl_object_id')) {
            $options_transient_name .= '__'.ICL_LANGUAGE_CODE; 
        }

		if($flo_wmpl_disable_cache || (defined('TYPO_DEV') && TYPO_DEV == 'DEV') ){ // in dev mode we work directly with the DB
			$flo_options = get_fields('options');
		}else{
			$flo_options = get_transient( $options_transient_name );
			if(!$flo_options){
				$flo_options = get_fields('options');

				set_transient( $options_transient_name, $flo_options, 60 * 60 * 24 ); // save the options for 24h
			}
		}




		return $flo_options;
	}
}

/**
 *
 * Load options_field_objects from transient if available
 * otherwise use get_field_objects('options') and save the value to the transient
 *
 */
if(!function_exists('flo_get_options_field_objects')){
	function flo_get_options_field_objects(){


		if(defined('TYPO_DEV') && TYPO_DEV == 'DEV'){ // in dev mode we work directly with the DB
			$options_acf_fields = get_field_objects('options'); // acf fields used on option page
		}else{
			$theme_name = flo_theme_data_variable('Name');
			$options_objects_transient_name = $theme_name . '_flo_options_objects';


			$options_acf_fields = get_transient( $options_objects_transient_name );
			if(!$options_acf_fields){
				$options_acf_fields = get_field_objects('options'); // acf fields used on option page

				set_transient( $options_objects_transient_name, $options_acf_fields, 60 * 60 * 24 ); // save the options for 24h
			}
		}



		return $options_acf_fields;
	}
}


/**
 *
 * Cache the layout options for the single blog post and single gallery because they many expensive queries
 *
 */
if(!function_exists('flo_maybe_get_cached_layout')){
	function flo_maybe_get_cached_layout($option_name) {

		$transient_name = $option_name.'_cached';

		$layout = get_transient($transient_name);

		if(!$layout) {
			$layout = get_field($option_name, "options");

			set_transient( $transient_name, $layout, 60 * 60 * 24 * 7 ); // save the options for 1 week
		}

		return $layout;
	}
}


/**
 *
 * When the Post and Gallery layouts are updated, we delete the saved transient
 *
 */
add_filter('acf/update_value', 'flo_acf_update_layouts', 10, 3);
function flo_acf_update_layouts( $value, $post_id, $field  ) {


		// the layout options names
		$cached_layout = array('flo-p2-p_layout','flo-p2-g_layout');

		// filterable option. each theme may have different options names.
		// therefore check where 'flo_maybe_get_cached_layout' fuction is used
		// to find out the layout options names for each theme
		// and then use the filter below.
		$cached_layout = apply_filters( 'flo_cached_layout_options',$cached_layout);

		foreach ($cached_layout as $layout_option_name) {
			delete_transient($layout_option_name.'_cached');
		}


	// don't forget to return to be saved in the database
    return $value;

}



/**
* Adds the appropriate mime types to WordPress
*
* @param array $existing_mimes
*
* @return array
*/
if(!function_exists('flo_additional_mime_types')){
	function flo_additional_mime_types($mimes){
		$mimes['ico'] = 'image/x-icon';
		$mimes['ttf']  = 'font/ttf';
	    $mimes['otf']  = 'font/otf';
	    $mimes['woff'] = 'application/font-woff';
	    $mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}
}
add_filter('upload_mimes', 'flo_additional_mime_types');


/*
Restores the ability to upload non-image files in WordPress 4.7.1 and 4.7.2. Please remove the plugin once
Remove this function when the bug is fixed in WP CORE
*/
function flo_disable_real_mime_check( $data, $file, $filename, $mimes ) {
	$wp_filetype = wp_check_filetype( $filename, $mimes );

	$ext = $wp_filetype['ext'];
	$type = $wp_filetype['type'];
	$proper_filename = $data['proper_filename'];

	return compact( 'ext', 'type', 'proper_filename' );
}
add_filter( 'wp_check_filetype_and_ext', 'flo_disable_real_mime_check', 10, 4 );

add_action('wp_ajax_quick_update_pemalins', 'flo_quick_update_pemalinks' );
if(!function_exists('flo_quick_update_pemalinks')){
    function flo_quick_update_pemalinks(){

        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( '/%postname%/' );
        flush_rewrite_rules();

        $response = array('message' => __('The permalinks were updated succesfully','flotheme'));

        echo json_encode($response);

        exit();
    }
}

add_action('wp_ajax_set_main_menu', 'flo_maybe_set_main_menu' );
/**
 *
 * This function precesses the Ajax request for setting up the main menu.
 * It will either create an menu frm the existig page,
 * or will set the menu selected by the user as main menu
 *
 */
if(!function_exists('flo_maybe_set_main_menu')){
    function flo_maybe_set_main_menu(){
        $response = array();

        if(isset($_POST['action']) && 'set_main_menu' == $_POST['action']){

            if(isset($_POST['menu_option']) && $_POST['menu_option'] == 'automatically' ){
                $main_menu = flo_maybe_create_default_menu();

                if(is_numeric($main_menu)){

                    // when there is no main menu and it is actually created '$main_menu' is the created Menu ID
                    $response['message'] = sprintf(__('The Main menu was created succesfully. You can edit it %s here %s','flotheme'), '<a href="'.get_dashboard_url().'nav-menus.php?action=edit&menu='.$main_menu.'" target="_blank">', '</a>' );
                }else{

                    // usually we get here when there already exists a primary menu
                    // in this case '$main_menu' is a strig telling us that the Primary menu already exists and we can edit it

                    $response['message'] = $main_menu;

                }


            }else if(isset($_POST['menu_option']) && $_POST['menu_option'] == 'manually'
                && isset($_POST['manually_menu_option']) && is_numeric($_POST['manually_menu_option'])){

                $main_menu = $_POST['manually_menu_option'];

                // set the selected menu as the main menu
                flo_set_main_menu($main_menu);
                $response['message'] = sprintf(__('The selected menu was set as the main menu. You can edit it %s here %s','flotheme'), '<a href="'.get_dashboard_url().'nav-menus.php?action=edit&menu='.$main_menu.'" target="_blank">', '</a>' );
            }else{
                $response['message'] = __('Invalid Menu creation method was selected. Try again please.','flotheme');
            }
        }else{
            $response['message'] = __('Invalid action','flotheme');
        }

        echo json_encode($response);
        exit();
    }
}

/**
 *
 * Crete a menu from the existing pages and set it as the Primary menu
 *
 */
function flo_maybe_create_default_menu(){


    if (!has_nav_menu('primary')) {
        $primary_nav_id = wp_create_nav_menu('Main Menu', array('slug' => 'main_menu'));
        $flotheme_nav_theme_mod['primary'] = $primary_nav_id;

        if ($flotheme_nav_theme_mod) {
            set_theme_mod('nav_menu_locations', $flotheme_nav_theme_mod);
        }

        $primary_nav = wp_get_nav_menu_object('Main Menu');
        $primary_nav_term_id = (int) $primary_nav->term_id;
        $menu_items = wp_get_nav_menu_items($primary_nav_term_id);
        if (!$menu_items || empty($menu_items)) {
            $pages = get_pages();
            foreach ($pages as $page) {
                $item = array(
                    'menu-item-object-id' => $page->ID,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                );
                wp_update_nav_menu_item($primary_nav_term_id, 0, $item);
            }
        }

        return $primary_nav_id; // return the ID of the created menu
    }else{

        return sprintf(__('There is already a Primary menu set, you can edit it from %s here %s','flotheme'),
                '<a href="'.get_dashboard_url().'nav-menus.php" target="_blank">','</a>'
            );
    }



}

/**
 *
 * Set a given menu as the primary menu
 *
 * @param - menu ID
 *
 */
if(!function_exists('flo_set_main_menu')){
    function flo_set_main_menu($menu_id){
        $flotheme_nav_theme_mod['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $flotheme_nav_theme_mod);
    }
}

// when theme is activated, redirect to quick setup tab
add_action("after_switch_theme", "flo_redirect_to_quik_setup");
if(!function_exists('flo_redirect_to_quik_setup')){
	function flo_redirect_to_quik_setup(){

		if ( version_compare(phpversion(), THEME_REQUIRED_PHP_VERSION, '>=') ){
			// update all pages in atempt to fix any meta data that can conflict
		    $args = array(
					'post_type' => 'page',
					'posts_per_page' => -1
				);
			$pages = get_posts($args);
		  	foreach($pages as $page){
		      	wp_update_post($page);
		  	}

		    // redirect the user to Quick setup tab in the theme options
		    // were they can choose the 'Quick setup' options they need

			if(defined('_FLO_CORE_') && _FLO_CORE_ == 'Enzo'){
				$quick_setup_url = get_dashboard_url(). 'admin.php?page=acf-options-1-introduction';
			    $quick_setup_url = apply_filters( 'flo_set_quick_setup_url' ,$quick_setup_url);
			    wp_redirect( $quick_setup_url, 301 );
			}

		    //wp_redirect( admin_url().'/admin.php?page=_flo_options&tab=12', 301 );
		}

	}
}

add_action('wp_head', 'flo_maybe_custom_css',20);
if(!function_exists('flo_maybe_custom_css')){
	function flo_maybe_custom_css(){
		global $flo_options;
		$custom_css_option_name = 'flo-custom-css';

		// make this option filterable in case other themes will name it differently
		$custom_css_option_name = apply_filters( 'flo_custom_css_option_name' ,$custom_css_option_name);

		if(isset($flo_options[$custom_css_option_name]) && strlen(trim($flo_options[$custom_css_option_name]))){
			echo "<style>".$flo_options[$custom_css_option_name].'</style>';
		}
	}
}

/**
 *
 * Add the google analytics to the site header
 * if the user added the code
 *
 */
add_action('wp_head', 'flo_maybe_analytics',20);
if(!function_exists('flo_maybe_analytics')){
	function flo_maybe_analytics(){
		global $flo_options;

		$analytics_option_name = 'flo-get_started__analytics';
		// make this option filterable in case other themes will name it differently
		$analytics_option_name = apply_filters( 'flo_analytics_option_name' ,$analytics_option_name);

		if(isset($flo_options[$analytics_option_name]) && strlen(trim($flo_options[$analytics_option_name])) ){
			$analytics_code = $flo_options[$analytics_option_name];
			if($analytics_code && strlen($analytics_code)){
				echo $analytics_code;
			}
		}

	}
}

add_action('wp_footer', 'flo_maybe_custom_js',20);
if(!function_exists('flo_maybe_custom_js')){
	function flo_maybe_custom_js(){
		global $flo_options;
		$custom_js_option_name = 'flo-custom-js';
		// make this option filterable in case other themes will name it differently
		$custom_js_option_name = apply_filters( 'flo_custom_js_option_name' ,$custom_js_option_name);

		if(isset($flo_options[$custom_js_option_name]) && strlen(trim($flo_options[$custom_js_option_name]))){
			$custom_js = str_replace('</script>','', str_replace('<script>','',$flo_options[$custom_js_option_name]));
			echo '<script>'.$custom_js.'</script>';
		}
	}
}

/**
 *
 * Prepare the arguments for the API request to Flothemes.com
 *
 */
if(!function_exists('flo_get_stats_args')){
	function flo_get_stats_args(){
		$result = array();
		$theme_data = wp_get_theme(); // get the current theme name

		// do the same thing one more time in case we are working with a child theme
		// to make sure we will get the parrent theme Name
		$theme_data = wp_get_theme($theme_data->Template);

	    $result['product_name'] = $theme_data->Name;
	    $result['domain_name'] = get_site_url();
		$result['api_url'] = "https://flothemes.com/wp-json/flo/v1/product/";
	    //$result['api_url'] = "http://localhost/flocom/wp-json/flo/v1/product/";

	    return $result;
	}
}

/**
 *
 * Send an request to the stats API on Flothemes.com
 * this method is used on theme activation, on theme deactivation and on License key activation.
 */
if(!function_exists('flo_stats_request')){
	function flo_stats_request($method = 'POST', $activation_key = ''){
		$body_args = flo_get_stats_args();
		if(strlen(trim($activation_key))){
			$body_args['activation_key'] = $activation_key;
		}

		$request_args = array(
			'method' => $method,
			'body' => $body_args
		);

		// because this code runs after the them was switched
		// if the theme is deactivated, then we should get the name
		// of the theme from the option we've created on activation
		if($method == 'DELETE'){
			$request_args['body']['product_name'] = get_option('flo_theme_name');
		}

		$response = wp_remote_get( $body_args['api_url'], $request_args );

	}
}

// when the theme is activated send and request with the theme name and domain URL

if(!function_exists('flo_register_product')){
	function flo_register_product(){

		if ( version_compare(phpversion(), THEME_REQUIRED_PHP_VERSION, '>=') ){
			$theme_data = flo_get_stats_args();
			// add to an option the current theme name
			// it will be used when the theme is deactivated
			update_option('flo_theme_name',$theme_data['product_name']);

			// send the request to register the activation of this theme
			flo_stats_request('POST');
		}

	}
}
add_action("after_switch_theme", "flo_register_product");


if(!function_exists('flo_deregister_product')){
	function flo_deregister_product(){
		flo_stats_request('DELETE');
	}
}
add_action('switch_theme', 'flo_deregister_product', 1);

/**
 * Get an attachment meta given a URL.
 *
 * @param string $url
 *
 * @return int Attachment ID on success, 0 on failure
 */
function flo_get_attachment_meta( $url ) {
	$attachment_id = 0;
	$meta = array();
	$dir = wp_upload_dir();
	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
		$file = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			)
		);
		$query = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}
	}
	return $meta;
}

function flo_low_thumbnail_quality(){
	return 1;
}

function flo_normal_thumbnail_quality(){
	return 90;
}


/* START: FLO LQIP */
  if(!function_exists('flo_lqip')){
  	function flo_lqip($original_img_url, $crop_tumbs = false, $image_width = 99997, $image_height = 99997){

  		// if the width or height was not passed
  		if(99997 == $image_width || 99997 == $image_height){

  			// try to get the attachment info from the URL
  			$attachment_meta = flo_get_attachment_meta($original_img_url);

  			// get the width from the attachment meta
  			if(99997 == $image_width){
  				if(is_array($attachment_meta) && sizeof($attachment_meta)){
  					// devide the meta width by 10 to have a smaller image
  					$image_width = $attachment_meta['width']/10;
  				}
  			}

  			if(99997 == $image_height){
  				if(is_array($attachment_meta) && sizeof($attachment_meta)){
  					$image_height = $attachment_meta['height']/10;
  				}
  			}

  		}

  		// we want set the image quality as low as possible and generate a smaller proportional thumbnail in order
          // to have a faster page load
          add_filter( 'jpeg_quality', 'flo_low_thumbnail_quality'); // set the quality to 1
          // low quality image placeholder
          $img_lqip = aq_resize(
            $original_img_url,
            $width = ceil($image_width/30), //devide by a factor of 30 to get a small image
            $height = ceil($image_height/30),
            $crop = $crop_tumbs,
            $single = true,
            $upscale = false
          );
          add_filter( 'jpeg_quality', 'flo_normal_thumbnail_quality'); // return the resize jpeg quality

          return $img_lqip;
  	}
  }

  if (!function_exists("flo_lqip__set_attr")) {
    function flo_lqip__set_attr($img, $bgi = false, $manual = false, $image_width = false, $image_height = false ) {
    	if($image_width && $image_height){
    		$img_lqip = flo_lqip($img,$crop = false, $image_width, $image_height);
    	}else{
    		$img_lqip = flo_lqip($img);
    	}

      $attr = " data-original-src='".$img."' data-lqip-src='" . $img_lqip . "' ";
      if ($bgi == true) {
        $attr .= " data-lqip-is-bg='true' ";
      } else {
        $attr .= " src='" . $img_lqip . "' ";
      }

      if ($manual == true ) {
        $attr .= " data-lqip--is-manual='true' ";
      }

      return $attr;
    }
  }
/* END: FLO LQIP */


// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');


/**
 *
 * Add theme update menu
 *
 */
if(!function_exists('flo_add_theme_update_menu')){
	function flo_add_theme_update_menu(){
		if (!FLOTHEME_CUSTOMIZED) {

			add_submenu_page('theme-general-settings', __('Theme Updater', 'flotheme'), __('Theme Updates', 'flotheme'), 'edit_theme_options', 'flotheme_updater', 'flotheme_updater_page');
		}
	}
}

add_action('admin_menu', 'flo_add_theme_update_menu', 111);


/**
 *	Old slideshows data compatibility check
 * check the data for the repeater fields
 * this should resolve the problem that occurs when the slideshows from
 * older themes are edited in Enzo themes and the repeater behaves unexpectedly
 *
 */
function flo_slideshow_data_check( $field ) {
	// deb_e($field['value']);
	// echo '<pre>';
	// 		var_dump( $field );
	// 		echo 'rrr';
	// 	echo '</pre>';
	// die();

	if( is_admin() && isset($field['sub_fields'][0]['name']) && $field['sub_fields'][0]['name'] == 'slide_image'){
		if(isset($field['value']) && is_array($field['value'])){
			foreach ($field['value'] as $key => $slide_value) {
				// the data from the old slideshows (Crowd) looks in ACF repeater like this:
				// http://pastebin.com/hJWkq4pC
				// therefore we will iterate through values of each slide
				// and if both keys are NULL, then we set the value to empty array

				if(is_array($slide_value) && (sizeof($slide_value) == 2 || sizeof($slide_value) == 5 || sizeof($slide_value) == 6 ) ){
					// For Crowd sizeof($slide_value) = 2 and sometimes sizeof($slide_value) = 6
					// For Porto sizeof($slide_value) = 5

					$counter = 1;
					$key_1_is_null = false;
					$key_2_is_null = false;
					foreach ($slide_value as $key_1 => $slide_options) {
						if($slide_options == NULL){
							if($counter == 1)
								$key_1_is_null = true;


							if($counter == 2)
								$key_2_is_null = true;

						}
						$counter ++;
					}

					if($key_1_is_null && $key_2_is_null){
						// set the value to an empty array so that it does not load unexpectedly
						$field['value'] = array();
					}
				}
				break;
			}
		}

	}

 	return $field;


}

// check the data for the repeater fields for Slideshow fields compatibility
add_action( 'acf/prepare_field/type=repeater', 'flo_slideshow_data_check', 1, 1 );


/**
 *
 * Check if a given option exists,
 * if not, return the passed default value
 *
 */
if(!function_exists('flo_get_option')){
	function flo_get_option($option_name, $default = false ){
		global $flo_options;

		// support for WPML
		if(function_exists('icl_object_id')) {
			// if WPML is active
			// an option is considered that it needs translation if it is found in the wpml-config.xml under 'admin-texts'
			if(flo_maybe_option_needs_translation($option_name)){
				// use this filter to return the proper option for the current option
				add_filter('acf/settings/current_language', 'flo_acf_settings_current_language',1);
				return get_field($option_name,'options');
				remove_filter('acf/settings/current_language', 'flo_acf_settings_current_language',1);
			}
		}

		// if this is not a WPML option. continue as usual.
		if(isset($flo_options[$option_name])){
			return $flo_options[$option_name];
		}else{
			return $default;
		}

	}

}


/**
 *
 * Check if a given option is available for translation using WPML
 *
 * @param string $option_name the theme option name
 * @return bool True if the option is found in wpml-config.xml and false otherwise
 *
 */
if(!function_exists('flo_maybe_option_needs_translation')){
	function flo_maybe_option_needs_translation($option_name){

		// check if the wpml-config.xml exists
		if(file_exists(get_stylesheet_directory()."/theme-files/wpml-config.xml")){
			$wpml_config = simplexml_load_file(get_stylesheet_directory()."/theme-files/wpml-config.xml");

			// check for 'admin-texts'
			if(isset($wpml_config->{'admin-texts'})){
				// iterate through the options in the xml file and return when found
				foreach ($wpml_config->{'admin-texts'}->key as $index => $option) {
					if($option['name'] == 'options_'.$option_name){
						return true;
						break;
					}
					# code...
				}

			}
		}


		return false;

	}
}

// Same as above but for get_field()
if(!function_exists('flo_get_field')){
  function flo_get_field($key, $default = false) {
    if (get_field($key)) {
      return get_field($key);
    } else {
      return $default;
    }
  }
}

// Same as above but uses $data as it's fields array. Useful inside, e.g., blocks of the Default Page.
if(!function_exists('flo_data')){
  function flo_data($data, $key, $default = false) {
    if (isset($data[$key])) {
      return $data[$key];
    } else {
      return $default;
    }
  }
}


/**
 *
 * Retrieve the font/typography info from the passed $data
 * When we are dealing with blocks representing the post meta data, the the $key is available in the $data,
 * otherwise when the block is a flexible block from the general theme options,
 * we need to use the 'get_field' in order to get the the proper font data
 */
if(!function_exists('flo_font_data')){
	function flo_font_data($data, $key, $default = false) {

		// ususally this is true for the blocks from post meta data
		if (isset($data[$key]) && is_string($data[$key]) ) {

			return $data[$key];

		}else if (isset($data[$key]) && is_array($data[$key]) && isset($data[$key]['default']) && strlen(trim($data[$key]['default'])) ) {
			// for some blocks the post meta data is saved as array
			return $data[$key];

		}else{
			// when we work with flexible block from the general theme options
			return get_field($key, 'options');
		}
	}
}

/**
 *
 * Check if a given option existsand is not empty string,
 * if not, return the passed default value
 *
 */
if(!function_exists('flo_get_option_strict')){
	function flo_get_option_strict($option_name, $default = false ){
		global $flo_options;

		if(isset($flo_options[$option_name]) && strlen($flo_options[$option_name]) ){
			return $flo_options[$option_name];
		}else{
			return $default;
		}
	}
}

if(!function_exists('flo_get_first_term_filter')){

	// used by the_category_list filter
	function flo_get_first_term_filter($links){

		if(isset($links[0])){
			$links = array( 0 => $links[0] );
		}
		return $links;
	}
}


/**
 *
 * Show just the first blog post category.
 * uses 'term_links-'.$taxonomy' filter
 *
 */
if(!function_exists('flo_get_the_first_term')){
	//function flo_get_the_first_category($delimiter = ', ',$post_id = 0){
	function flo_get_the_first_term( $id, $taxonomy, $before = '', $sep = '', $after = '', $linked_terms = true ){

		if($linked_terms){
			add_filter('term_links-'.$taxonomy, 'flo_get_first_term_filter', 2, 9);
			$term_list = get_the_term_list($id, $taxonomy, $before , $sep , $after );


		    remove_filter('the_category_list', 'flo_get_first_category');

		    return $term_list;
		}else{
			// compatibility with Yoast SEO's primary category option
			if(class_exists('WPSEO_Primary_Term')){
				$categ = new WPSEO_Primary_Term($taxonomy, $id);
				$categ = $categ->get_primary_term();
				if($categ){
					$categ = get_term($categ, $taxonomy);
					$first_cat = $categ->name;
					return $first_cat;
				}
			}

      // fallback to original code
      // if we do not need anchor
			$term_list = wp_get_post_terms( $id, $taxonomy );
			if(isset($term_list[0]) ){
				$first_cat = $term_list[0];
				if(isset($first_cat->name)){
					return $first_cat->name;
				}
			}
		}

	}
}


/**
 *
 * get the category term based on the post type
 *
 * @param object $post The post object
 * @return string The category term slug
 */
if(!function_exists('flo_get_category_term')){
	function flo_get_category_term($post){
		$post_type = get_post_type($post);

		switch ($post_type) {
			case 'post':
				$categ_term = 'category';
				break;

			case 'gallery':
				$categ_term = 'gallery-category';
				break;

			case 'video':
				$categ_term = 'video-category';
				break;

			default:
				$categ_term = '';
				break;
		}

		return $categ_term;
	}
}


if(!function_exists('flo_render_typography_styles_by_option_name')){
	function flo_render_typography_styles_by_option_name($option_name, $post_id = 'options', $options_value = null){
		$css = '';
		if(null != $options_value && isset($options_value[$option_name])){
			$typography_val = $options_value[$option_name];
		}else{
			$typography_val = get_field($option_name, $post_id);
		}

		if(isset($typography_val['default']) && strlen(trim($typography_val['default'])) &&
			isset($typography_val['css_selector']) && strlen($typography_val['css_selector'])){
			$css .= $typography_val['css_selector'].'{ '.$typography_val['default'] .' }';
		}

		if(isset($typography_val['hover']) && strlen(trim($typography_val['hover'])) &&
			isset($typography_val['hover_css_selector']) && strlen($typography_val['hover_css_selector'])){
			$css .= $typography_val['hover_css_selector'].':hover{ '.$typography_val['hover'] .' }';
		}

		if(isset($typography_val['active']) && strlen(trim($typography_val['active'])) &&
			isset($typography_val['active_css_selector']) && strlen($typography_val['active_css_selector'])){
			$css .= $typography_val['active_css_selector'].'{ '.$typography_val['active'] .' }';
		}

		return $css;
	}
}



/**
 *
 * @param string $selector - the css selector the styles should be aplied for
 * @param array $typography_info - the info about the typography styles for different states (default, hover, active)
 *
 * @return - string, the generated css
 */
if(!function_exists('flo_render_typography_styles')){
	function flo_render_typography_styles($selector, $typography_info){
		$css = '';
		if(isset($typography_info['default']) && strlen($typography_info['default']) ){
			$css .= $selector. '{' .$typography_info['default'] . '}';

		}
		if(isset($typography_info['hover']) && strlen(trim($typography_info['hover'])) ){
			$css .= $selector. ':hover{' .$typography_info['hover'] . '}';
		}

		// for tablets we will overwrite the font size
		if(isset($typography_info['tablet']) && strlen($typography_info['tablet']) ){
			$css .= '@media screen and (min-width: 768px) and (max-width: 1024px){'.$selector. '{' .$typography_info['tablet'] . '}}';
		}

		// use media queries instead of cookies to avoid cached cookies
		if(isset($typography_info['mobile']) && strlen($typography_info['mobile']) ){
			$css .= '@media screen and (max-width: 767px){'.$selector. '{' .$typography_info['mobile'] . '}}';
		}
		return $css;
	}
}



/**
 *
 * add custom css in the head from different templates
 *
 */
if(!function_exists('flo_head__styles')) {
	function flo_head__styles(){
		global $flo_head__styles;
		echo $flo_head__styles;
	}
}


// add_filter('acf/settings/default_language', 'my_acf_settings_default_language');

// function my_acf_settings_default_language( $language ) {

//     return false;

// }


//add_filter('acf/settings/current_language', 'flo_acf_settings_current_language');
/**
  *
  * get language independent ACF theme options on a WPML site
  *
  */
function flo_acf_settings_current_language( $language ) {
    return false;
}

function flo_acf_set_language_to_default() {
  return acf_get_setting('default_language');
}
// Set language to default
add_filter('acf/settings/current_language', 'flo_acf_set_language_to_default', 10);


/**
 *
 * Retrieve the post featured image alt text
 * @param int $post_id
 * @return str
 *
 */
function flo_get_feat_img_alt($post_id) {
	if(has_post_thumbnail($post_id )) {
		$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		return get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
	}else{
		return '';
	}
}

// find the first imgage in the post content and return it's url on success,
// otherwise returns an empty string
function flo_catch_first_content_image($post, $return_falback_img = true) {

  	$first_img = '';
  	ob_start();
  	ob_end_clean();

  	$output = preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $post->post_content, $matches);

  	if(isset($matches[1]) ){
  		$first_img = $matches[1];
  	}

  	if($return_falback_img && empty($first_img)){ //Defines a default image
    	$first_img = "/images/default.jpg";
  	}
  	return $first_img;
}

// retrieves the attachment ID from the file URL
function flo_get_image_id($image_url) {
	global $wpdb;

	$thumb_patern = '/-\d*x\d*./'; // some thing like -dddxddd.jpg

	// if we have a resized thumbnail, we remove the size at the end and will get the original img URL
	$original_image_url = preg_replace($thumb_patern, '.', $image_url);
	preg_match($thumb_patern, $image_url, $matches2, PREG_OFFSET_CAPTURE);

	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $original_image_url ));
	//var_dump($attachment);
	if(isset($attachment[0])){
		return $attachment[0];
	}else{
		return false;
	}


}

// Woocommerce 3.0 support for the product gallery
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );



if(!function_exists('flo_getmobile_image_possition_parameters')){
	// we should solve equations like this and return $a & $b values:
	// $a*$m_factor - $b = 0;
	// $a*$n_factor - $b = 100;
	function flo_getmobile_image_possition_parameters($m_factor,$n_factor){
		$b = 100/(($n_factor - $m_factor)/$m_factor);
		$a = $b/$m_factor;

		return(array('a' => round($a,2), 'b' => round($b,2) ));
	}
}


/**
 *
 * Calculate the crop possition depending on the image proportion
 *
 */
function flo_get_mobile_sl_image_m_n_factor($image_proportion){
	// http://www.wolframalpha.com/input/?i=interpolate+%5B(1.5,+22.22),(1.92,17.36)+%5D
	//39.5771 - 11.5714 x
	$m_factor = round( (39.5771 - 11.5714*$image_proportion) ,2);


	// http://www.wolframalpha.com/input/?i=interpolate+%5B(1.5,+77.77),(1.92,82.63)+%5D
	//11.5714 x + 60.4129
	$n_factor = round((11.5714 * $image_proportion + 60.4129),2);

	return array('m' => $m_factor, 'n' => $n_factor);
}

/**
 * Having the image proportion and the crop possition we will return the bg image possition
 * for the mobile devices in portrait orientation
 *
 * return $m_factor*$crop_position - $n_factor
 */

function mobile_crop_position($image_proportion, $crop_position){
	$m_n_factor = flo_get_mobile_sl_image_m_n_factor($image_proportion);

	$pos_params = flo_getmobile_image_possition_parameters($m_n_factor['m'],$m_n_factor['n']);

	return round($pos_params['a']*$crop_position - $pos_params['b'],2);
}

// YOAST SEO Validation
add_action( 'admin_enqueue_scripts',  'flo_acf_bind_acf_validation_js');
if(!function_exists('flo_acf_bind_acf_validation_js')){
	function flo_acf_bind_acf_validation_js(){
		if (defined('WPSEO_VERSION')) {
			wp_enqueue_script('flo_acf_yoast_seo_validator', get_template_directory_uri() . '/assets/admin/js/acf_yoast.js', false, false, true);
        }
	}
}

if(!function_exists('flo_get_sidebar_width')){
	function flo_get_sidebar_width(){
		$sidebar_width = flo_get_option("flo-sidebars__sidebar_width",3); // 3 columns by default
		switch ($sidebar_width) {
		    case 2: // 1/6
		      $sidebar_width_val = '16%';
		      break;
		    case 3: // 1/4
		      $sidebar_width_val = '25%';
		      break;
		    case 4: // 1/3
		      $sidebar_width_val = '33%';
		      break;
		    case 5: // 5/12
		      $sidebar_width_val = '42%';
		      break;

		    default: // 1/4
		      $sidebar_width_val = '25%';
		      break;
		}

		return $sidebar_width_val;
	}
}


/**
 *
 * Return the excerpt of a vertain length giving the post object and the desired excerpt length
 *
 */

if(!function_exists('flo_get_post_excerpt')){
	function flo_get_post_excerpt($post, $ln = 30, $subfix = ''){
		//var_dump($post);
		if (isset($post->post_excerpt) && strlen(trim($post->post_excerpt)) ) {

			// if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > $ln) {

			// 	return mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, $ln) . $subfix;

			// } else {

				return strip_tags(strip_shortcodes($post->post_excerpt));

			//}

		} else {

			if (strlen(strip_tags(strip_shortcodes($post->post_content))) > $ln) {
				return mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, $ln) . $subfix;
			} else {
				if(!empty($post->post_content)){
					return strip_tags(strip_shortcodes($post->post_content));
				}
			}

		}
	}
}


if(!function_exists('flo_get_mobile_crop_position')){
	function flo_get_mobile_crop_position($slide){
		if( isset($slide["slide_image"]['crop_position']) ){
	        $crop_position = $slide["slide_image"]['crop_position'];

	        // the background position is calculated using the following formula:
	        // y = 1.8x - 40
	        // http://www.wolframalpha.com/input/?i=interpolate+%5B(22.22,+0),(77.77,100)+%5D
	        //$mobile_crop_position = (1.8*$crop_position - 40).'%';

			// adding a zero check to avoid a fatal error (see screenshot for details): https://image.prntscr.com/image/4znR6UpGSpmRJQXjtJFXKw.png
			if(isset($slide['slide_image']['width']) && isset($slide['slide_image']['height']) && $slide['slide_image']['height'] != 0 && $slide['slide_image']['width'] != 0){
	          $image_proportion = round($slide['slide_image']['width']/$slide['slide_image']['height'], 2);
	        }else{
	          $image_proportion = 1.5;
	        }

	        $mobile_crop_position = mobile_crop_position($image_proportion, $crop_position).'%';

	    }else{
	        $mobile_crop_position = '50';
	    }

	    return $mobile_crop_position;
	}
}

if(!function_exists('flo_default_theme_fonts_path')){
	function flo_default_theme_fonts_path(){
		return '';
	}
}
//add_filter('flo_default_theme_fonts_path','flo_default_theme_fonts_path');

add_filter('acf/update_value/type=radio', 'flo_acf_radio_update_value', 10, 3);
function flo_acf_radio_update_value($value, $post_id, $field ){


	// if the field name starts with 'flo-stylekit'
	if(strpos($field['name'], 'flo-stylekit') === 0 ){

		// use this filter to overwrite the default value if necessary
		$stylekit_option_name = apply_filters('flo_theme_stylekit_option_name','flo-theme_stylekit');

		// we store the current value in a standard option
		if($value != ''){
			update_option($stylekit_option_name,$value);
		}


		// just return nothing in order to not save the ACF filed
		return;
	}

	return $value;
}


/**
 *
 * In the stylekit settings, we have a hidden text input
 * its name is 'flo-stylekit-trigger'
 * if that input has the value 'set_stylekit' -> it will trigger the stylekit settings update.
 *
 */

add_filter('acf/update_value/type=text', 'flo_acf_input_update_value', 10, 3);
function flo_acf_input_update_value($value, $post_id, $field ){
	if($field['name'] == 'flo-stylekit-trigger' && $value == 'set_stylekit'){

		$current_stylekit = flo_get_current_style_kit();

		// update the stylekit typography:
		flo_set_stylekit_typography($current_stylekit);

		// update the stylekit colors:
		flo_set_stylekit_colors( $current_stylekit );

		return;
	}

	return $value;
}



/**
 *
 * Update the color typography styles depending on the passed stylekit
 *
 */
if(!function_exists('flo_set_stylekit_typography')){
	function flo_set_stylekit_typography($stylekit){

		// delete the current typography options
		$theme_typography_option_name = 'flo-typography';
		$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

		//now set the options from the given stylekit

		$default_fonts_set = apply_filters( 'flo_set_default_theme_fonts', array() ); // returns the default fonts specified in .../theme-files/app/fonts-theme-default.php
		$typography_styles['font_styles'] = json_encode(array(
							'theme_defaults' => $default_fonts_set)
						);

		update_option('options_'.$theme_typography_option_name, $typography_styles);

	}
}


/**
 *
 * Update the color styles depanding on the passed stylekit
 * @param stylekit - string
 * @return null
 */
if(!function_exists('flo_set_stylekit_colors')){
	function flo_set_stylekit_colors( $stylekit ){
		// check if the stylekit colors settings file is available

		if(file_exists(get_template_directory()."/theme-files/flo_stylekits_config/".$stylekit."/colors_settings.php")){

			// including the collors settings file will make available the $color_settings array
			// $color_settings has the following format: https://pastebin.com/ePPxk9GS
			include(get_template_directory()."/theme-files/flo_stylekits_config/".$stylekit."/colors_settings.php");

			if(isset($color_settings) && is_array($color_settings)){
				foreach ($color_settings as $color_option_name => $color_value) {

					update_field($color_option_name, $color_value, 'options');

				}
			}

		}
	}
}


/**
 *
 * Get the Typography option name for the current theme
 *
 * 	@return string
 */
if(!function_exists('flo_get_theme_typography_option_name')){
	function flo_get_theme_typography_option_name(){
		$theme_typography_option_name = 'flo-typography';
		$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

		return $theme_typography_option_name;
	}
}


/**
 *
 * 	this function is supposed to be used inside the anchors tags that may have empty  links.
 * It's role is to avvoid scrolling to the top of the pag when anchor is = '#'
 * or avoidin reloading the page when anchor is empty
 * Check if the given URL is empty or '#'. If true, then return "onclick='return false'";
 *
 * @param string - the URL that is used for the anchor tag
 * @return string - "onclick='return false'";  OR empty string
 *
 */
if(!function_exists('flo_url')){
	function flo_url($url){
		if($url == '' || $url == '#'){
			return "onclick='return false'";
		}else{
			return '';
		}

	}
}

/* START: COLOR - HEX TO RGBA */
  if (!function_exists("hex2rgba")) {
    function hex2rgba($color, $opacity = false) {

      $default = 'rgb(0,0,0)';

      //Return default if no color provided
      if(empty($color)) return $default;

      //Sanitize $color if "#" is provided
      if ($color[0] == '#' ) {
        $color = substr( $color, 1 );
      }

      //Check if color has 6 or 3 characters and get values
      if (strlen($color) == 6) {
              $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
      } elseif ( strlen( $color ) == 3 ) {
              $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
      } else {
              return $default;
      }

      //Convert hexadec to rgb
      $rgb =  array_map('hexdec', $hex);

      //Check if opacity is set(rgba or rgb)
      if($opacity){
        if(abs($opacity) > 1)
          $opacity = 1.0;
        $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
      } else {
        $output = 'rgb('.implode(",",$rgb).')';
      }

      //Return rgb(a) color string
      return $output;
    }
  }
/* END: COLOR - HEX TO RGBA */


// track JS errors on Typography settings page.
add_action('admin_head', 'track_typography_page_errors',1);
function track_typography_page_errors(){
  $page_info = get_current_screen();

  $typography_options_page = array('flotheme_page_acf-options-10-typography');

  $typography_options_page = apply_filters( 'flo_current_theme_typography_options_page', $typography_options_page );

  if( is_object($page_info) && in_array($page_info->base, $typography_options_page)){
    echo '<script type="text/javascript">
      window.onerror = function(){
      	if( !jQuery(".flo-typography-error-msg").length ){ //make sure we show this message only once.
      		var errorMessage = "<div class=\"flo-typography-error-msg\"><p>We have detected a JavaScript error on this page. Saving options has been disabled since this can trigger unexpected loss of customizations or information. This usually happens when certain plugins cause conflicts with the theme. Try disabling third-party plugins one by one until this message disappears. If this does not help, contact the Flothemes support team for help.</p><a>Submit a Ticket</a></div>";
	        jQuery("#major-publishing-actions #publishing-action #publish").css({
	          "pointer-events":"none",
	          "opacity": "0.3"
	        });
	        jQuery("#major-publishing-actions").parents("#side-sortables").append(errorMessage);
	        jQuery("#side-sortables a").addClass("button button-primary button-large");
	        jQuery("#side-sortables a").attr("href","https://flothemes.zendesk.com/hc/en-us/requests/new/");
	        jQuery("#side-sortables a").attr("target","blank");
	        jQuery("#side-sortables p").css({
	          "color": "red",
	          "min-width": "255px",
	          "border": "1px solid #e5e5e5",
	          "-webkit-box-shadow": "0 1px 1px rgba(0,0,0,.04)",
	          "box-shadow": "0 1px 1px rgba(0,0,0,.04)",
	          "background": "#fff",
	          "padding" : "8px 12px"
	        });
      	}

      }
    </script>';
  }

}

function flo_deregister_third_party_angular_scripts() {
    global $wp_scripts;
    foreach( $wp_scripts->queue as $handle ) :
        echo $handle . ' | ';
    endforeach;
}
//add_action( 'wp_print_scripts', 'flo_deregister_third_party_angular_scripts' );


/**
 *
 * In an given string look for '%year%' and replace it with the current Year
 * @param string $str - the given string
 * @return string
 */
function flo_get_copyright_year($str) {
	return str_replace('%year%', date('Y'), $str);
}

/* START: AQUARESIZE SHORTHAND FUNCTION */
  function flo_aq($url = "", $width = 300, $height = 200, $crop = true) {
    return aq_resize( $url, $width, $height, $crop, $single = true, $upscale = false );
  }
/* END: AQUARESIZE SHORTHAND FUNCTION */

/* START: AQUARESIZE SHORTHAND FUNCTION THAT RETURNS A IMG */
  function flo_aq_img($class = "", $url = "", $width = 300, $height = 200, $crop = true, $alt = '', $force_sizes = false) {
    $url = aq_resize( $url, $width, $height, $crop, $single = true, $upscale = false, $force_sizes );
    ?>
      <img class="<?php echo $class ?>" src="<?php echo $url ?>" alt="<?php echo $alt; ?>">
    <?php
  }
/* END: AQUARESIZE SHORTHAND FUNCTION THAT RETURNS A IMG */

/* START: COLOR BRIGHTNESS CHECK */
	if(!function_exists('flo_color_bright')){
		function flo_color_bright($color){
			// we usually have this in js (is_color_bright()) but php is less browser consuming
			// returns true if color is bright, false if is dark

			// break up the color in its RGB components
			$r = hexdec(substr($color, 1, 2));
			$g = hexdec(substr($color, 3, 2));
			$b = hexdec(substr($color, 5, 2));

			$luma = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b; // per ITU-R BT.709

			if($luma > 40){
		    //bright color
		    $brightness = true;
			}else{
		    //dark color
		    $brightness = false;
			}
			return $brightness;
		}
	}
/* END: COLOR BRIGHTNESS CHECK */


include_once 'wpml-conf-generation.php';

?>