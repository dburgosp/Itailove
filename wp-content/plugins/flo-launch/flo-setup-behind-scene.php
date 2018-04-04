<?php

/**
 * @link              http://flothemes.com
 * @since             1.0.0
 * @package           Flo_Test_Drive
 *
 * @wordpress-plugin
 * Plugin Name:       Flo Launch
 * Plugin URI:        http://flothemes.com
 * Description:       This plugin allows tesing new themes, plugins or just ideas while the users see the current site.
 * Version:           1.0.6
 * Author:            Alex G.
 * Author URI:        http://flothemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flotheme
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flo-setup-behind-scene-activator.php
 */
function activate_flo_setup_behind_scene() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flo-setup-behind-scene-activator.php';
	Flo_Setup_Behind_Scene_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flo-setup-behind-scene-deactivator.php
 */
function deactivate_flo_setup_behind_scene() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flo-setup-behind-scene-deactivator.php';
	Flo_Setup_Behind_Scene_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flo_setup_behind_scene' );
register_deactivation_hook( __FILE__, 'deactivate_flo_setup_behind_scene' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flo-setup-behind-scene.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flo_setup_behind_scene() {

	$plugin = new Flo_Setup_Behind_Scene();
	$plugin->run();

}
run_flo_setup_behind_scene();

function create_big_post(){
	if(isset($_GET['big_post'])){

		$big_content = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.';
		$big_content .= $big_content;

		for ($i=0; $i < 5; $i++) {
			$big_content .= '<br/>'.$big_content;
		}

		for ($i=3000; $i < 7100; $i++) { 
			// Create post object
			$my_post = array(
			  'post_title'    => 'Robot post '.$i,
			  'post_content'  => $big_content,
			  'post_status'   => 'publish'

			);
			 
			// Insert the post into the database
			wp_insert_post( $my_post );
		}
		
	}



}
//add_action('init', 'create_big_post');


function fl_remove_tables(){
	if(isset($_GET['remove_post'])){

		global $wpdb;


		$wpdb->query("
			Drop Table 

				flo_pr_commentmeta, flo_pr_comments, flo_pr_ewwwio_images, flo_pr_fresh_slider, flo_pr_links,
				flo_pr_options, flo_pr_postmeta,flo_pr_posts,flo_pr_term_relationships,
				flo_pr_term_taxonomy,flo_pr_termmeta,flo_pr_terms,flo_pr_usermeta,flo_pr_users,
				flo_pr_wfBadLeechers,flo_pr_wfBlocks,flo_pr_wfConfig,flo_pr_wfCrawlers,flo_pr_wfFileMods,flo_pr_wfHits,
				flo_pr_wfHoover,flo_pr_wfIssues,flo_pr_wfLeechers,flo_pr_wfLockedOut,flo_pr_wfLocs,flo_pr_wfLogins,
				flo_pr_wfNet404s,flo_pr_wfReverseCache,flo_pr_wfScanners,flo_pr_wfStatus,flo_pr_wfThrottleLog, flo_pr_wfVulnScanners
			"
		);

		// $wpdb->query("
		// 	Drop Table 
		// 		`flo_pr_ao_kompanii`, `flo_pr_ao_otzivi`, `flo_pr_ao_plains`, `flo_pr_commentmeta`, `flo_pr_comments`, `flo_pr_earthquakes`, `flo_pr_links`, `flo_pr_options`, `flo_pr_postmeta`, `flo_pr_posts`, `flo_pr_post_relationships`, `flo_pr_pp_customizations`, `flo_pr_pp_designs`, `flo_pr_pp_design_images`, `flo_pr_pp_entities_images`, `flo_pr_pp_layout_entities`, `flo_pr_pp_layout_meta`, `flo_pr_pp_menus`, `flo_pr_pp_menu_items`, `flo_pr_pp_menu_item_instances`, `flo_pr_pp_menu_item_types`, `flo_pr_pp_migrations`, `flo_pr_pp_taxonomies`, `flo_pr_pp_templates`, `flo_pr_pp_templates_customization_parents`, `flo_pr_pp_template_entities`, `flo_pr_sns_backups`, `flo_pr_sns_options`, `flo_pr_sns_settings_destinations`, `flo_pr_sns_settings_ftp`, `flo_pr_sns_state`, `flo_pr_termmeta`, `flo_pr_terms`, `flo_pr_term_relationships`, `flo_pr_term_taxonomy`, `flo_pr_usermeta`, `flo_pr_users`
		// 	"
		// );


	}
}

//fl_remove_tables();


/**
 *
 * Add link to the plugin's settings page from the Plugins page
 *
 */
function flo_test_drive_settings_link ( $links ) {
 	$mylinks = array(
 		'<a href="' . admin_url( 'options-general.php?page=flo_sbs_settings' ) . '">Settings</a>',
 	);

	return array_merge( $links, $mylinks );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'flo_test_drive_settings_link' );

function flo_setup_table_prefix(){
	//check if the 'flo_setup_table_prefix' option is saved in the DB
	//if(!get_option('flo_setup_table_prefix')){
	if(!get_option('flo_test_drive_tables_created') ){
		global $wpdb;

		$rand_prefix_1 = mt_rand(10,1000000);
		$default_flo_table_prefix = 'flo_pr'.$rand_prefix_1.'_';

		$rand_prefix = mt_rand(10,1000000);
		$second_option_flo_table_prefix = 'flo_pr'.$rand_prefix.'_';

		if( $default_flo_table_prefix != $wpdb->prefix){ // check to make sure that the current Table prefix is not the same as $default_flo_table_prefix
			$setup_prefix = $default_flo_table_prefix;
		}else{
			$setup_prefix = $second_option_flo_table_prefix;
		}
//var_dump($setup_prefix,$wpdb->prefix);
		// Store in the DB the current and the new Table prefix
		update_option( 'flo_setup_table_prefix', $setup_prefix );

		update_option( 'flo_original_table_prefix', $wpdb->prefix ); // save the original table prefix in case we  will need it

	}
}
