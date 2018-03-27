<?php
/*
Plugin Name: Flothemes Theme Updater
Plugin URI: http://flothemes.com/
Description: Flothemes Themes Auto Updates. <em>NOTE: Customized themes do not allow to update</em>.
Version: 2.0
Author: Flosites | George M.
License: GPL2
*/

define('THEMEUP_DEFAULT_VERSION', '1.0');
define('THEMEUP_CHECK_URL', 'http://flothemes.com/themeupnow/index.php/check');
//define('THEMEUP_CHECK_URL', 'http://flothemes.dev/themeupnow/index.php/check');
define('THEMEUP_OPTNAME_KEY', 'flotheme_theme_key');

if(is_admin()){
	add_filter('pre_set_site_transient_update_themes', 'floupdates_check_theme_update');
}

/**
 * 
 * theme_update data example
 * <code>
 * stdClass::__set_state(array(
 *    'last_checked' => 1340200765,
 *    'checked' => array('twentyeleven' => '1.3', 'twentyten' => '1.3'),
 *    'response' => array (
 *     'twentyeleven' => array (
 *       'new_version' => '1.4',
 *       'url' => 'http://wordpress.org/extend/themes/twentyeleven',
 *       'package' => 'http://wordpress.org/extend/themes/download/twentyeleven.1.4.zip',
 *      ),
 *     'twentyten' => array (
 *       'new_version' => '1.4',
 *       'url' => 'http://wordpress.org/extend/themes/twentyten',
 *       'package' => 'http://wordpress.org/extend/themes/download/twentyten.1.4.zip',
 *     ),
 *   ),
 * ))
 * </code>
 *
 * @param stdClass $transient
 * @return stdClass 
 */

function floupdates_check_theme_update($transient)
{
	if (defined('FLOTHEME_CUSTOMIZED') && FLOTHEME_CUSTOMIZED) {
		return;
	}

	if (!current_user_can('update_themes')) {
		return;
	}
	
	$theme_data = wp_get_theme();
	if ($theme_data['Author Name'] !== 'Flothemes') {
		return $transient;
	}

	// get the Flo child theme update option. This option should be set in the child theme that should receive updates independently
	// for example see Blanco shop theme
	$is_child_theme_update = get_option('FLO_CHILD_THEME_UPDATE');

	// if this option exists, that means the current installed theme is on sale and it is good to receive updates
	if($is_child_theme_update){

		// get the template directory that will be used to get the current theme's folder name
		$stylesheet_directory = get_stylesheet_directory();
		$stylesheet_directory_array = explode('/', $stylesheet_directory); // explode the template directory
		

		if(is_array($stylesheet_directory_array) && sizeof($stylesheet_directory_array) ){
			// take the last elem which represents theme folder name.
			// we need to do that instead of just using the $theme_data because the child themes return the parent theme
			// template name, and we can not offer Updates for child themes.
			$theme_name = $stylesheet_directory_array[ (sizeof($stylesheet_directory_array)-1) ]; 
		}else{ // fallback
			$theme_name = isset($theme_data['Template']) ? $theme_data['Template'] : get_current_theme();	
		}
		
		$theme_version = isset($theme_data['Version']) ? $theme_data['Version'] : THEMEUP_DEFAULT_VERSION;
	}else{
		$theme_name = isset($theme_data['Template']) ? $theme_data['Template'] : get_current_theme();

		// fix the theme version if the child theme is activated
		// get the parent theme version if this is the case
		$parent_theme_data = wp_get_theme($theme_name); 
		$theme_version = isset($parent_theme_data['Version']) ? $parent_theme_data['Version'] : THEMEUP_DEFAULT_VERSION;
	
	}
	
	
	
	$keyval = get_option(THEMEUP_OPTNAME_KEY);

	if ( ! $keyval ) {
		add_action( 'admin_notices', 'flo_need_key' );
		return;
	}

	// make the request to flothemes.com to check for updates only once per hour
	if ( FALSE == $response = get_transient( 'flothemes_updates' ) ) {
		$response = wp_remote_post(
			THEMEUP_CHECK_URL, 
			array(
				'timeout' => 60,
				'body' => array(
				'theme' => $theme_name, 
				'version' => $theme_version,
				'key' => $keyval,
				)
			)
		);

		set_transient( 'flothemes_updates', $response, 60 * 60 );
	}
	if(isset($_GET['flo_debug']) && $_GET['flo_debug'] == 1){
		echo '<pre>';
		var_dump($response);
		echo '</pre>';
		die();
	}

	if ( !is_wp_error($response) && isset( $response['response']['code'] ) && $response['response']['code'] == '401') {

		// if we have a custom message
		if(isset($response['body']) && is_string($response['body']) ){
			global $response_msg;
			$response_msg = $response['body'];
		}
		add_action( 'admin_notices', 'flo_need_update_key' );
	}
 
	if (is_wp_error($response) || $response['response']['code'] != '200') {
		return $transient;
	}
	
	$remote = json_decode($response['body'], true);
	$transient->checked[$theme_name] = $theme_version;
	$transient->response[$theme_name] = $remote;
 
	if(isset($remote['new_version']) && $remote['new_version'] > $theme_version){
		add_action( 'admin_notices', 'update_notice' );
		return $transient;
	}


}

function update_notice(){
	if(is_admin()){
		echo '<div class="error"><p>A newer theme version is available. Please, <a href="'.site_url( '/wp-admin/themes.php' ).'">update</a> your theme.</p></div>';	
	}	
}	


// hook into core_version_check_locale filter to force delete the theme update Transiant to refresh the update data
add_filter('core_version_check_locale', 'flo_check_now',100);
function flo_check_now(){
	delete_transient( 'flothemes_updates' );
}



add_filter('admin_init', 'flo_activate_au',100);
function flo_activate_au()
{
	if(is_admin()){
		$plugin_data = get_plugin_data( __FILE__ );
		require_once ('flo_wp_auto_update.php');
		$plugin_version = $plugin_data['Version'];

		$flo_plugin_remote_path = 'http://flothemes.com/recommended_plugins/plugin-updates.php';
		$flo_plugin_slug = plugin_basename(__FILE__);
		new flo_wp_auto_update ($plugin_version, $flo_plugin_remote_path, $flo_plugin_slug);
	}
}

function flo_need_update_key() {
	global $response_msg;
    $class = "error";

    if(isset($response_msg) && strlen($response_msg)){
    	$message = $response_msg;
    }else{
    	$message = __('Please make sure the <a href="admin.php?page=flotheme_updater">Flothemes purchase key</a> is added correctly, as updates and the template may not work properly. Right now there is a problem with that.');
    }

    echo"<div class=\"$class\"> <p>$message</p></div>"; 
}

function flo_need_key() {
    $class = "error";
	$message = 'Please add the <a href="admin.php?page=flotheme_updater">purchase key</a> to be eligible for the theme updates. Example: XXXX-YYYY';
	
	$message = apply_filters('flo_no_activation_key_message', $message); // use this filter in the themes to change the message

    echo"<div class=\"$class\"> <p>$message</p></div>"; 
}

/* deleting transient on theme update */
function flo_themeup_update_key( $new_value, $old_value ) {
	delete_transient('flothemes_updates');
	return $new_value;
}
function flo_themeup_init() {
	add_filter( 'pre_update_option_flotheme_theme_key', 'flo_themeup_update_key', 10, 2 );
}
add_action( 'init', 'flo_themeup_init' );


add_filter( 'http_request_args', 'flo_fix_acp_plugin_update', 10, 2 );
function flo_fix_acp_plugin_update( $r, $url ){
    $starts_with = 'flothemes.com';
    // if the url starts with ^ then don't verify SSL
    if ( false !== strpos( $url, $starts_with ) ){
        $r['sslverify'] = false;
    }
    return $r;
}



add_filter( 'admin_footer_text', 'flo_admin_footer_text_backend' );
/**
 * Modify the footer text inside of the WordPress admin area.
 *
 * @since 1.0.0
 *
 * @param string $wp_text  The default footer text.
 * @return string $wp_text Amended footer text.
 */
function flo_admin_footer_text_backend( $wp_text ) {

	$my_theme = wp_get_theme();
	$theme_name = $my_theme->get( 'Name' );
	$theme_version = $my_theme->get( 'Version' );
 
	$result = $wp_text . '<span> Theme: ' . $theme_name . ' v' . $theme_version;
	return $result;
}
