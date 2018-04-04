<?php

/**
 * Forms builder plugin
 *
 * @link              http://flothemes.com
 * @since             1.0.0
 * @package           Flo_Forms
 *
 * @wordpress-plugin
 * Plugin Name:       Flo Forms
 * Plugin URI:        http://flothemes.com
 * Description:       A easy to use contact form builder plugin
 * Version:           1.6.1
 * Author:            Alex G.
 * Author URI:        http://flothemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       flo-forms
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-flo-forms-activator.php
 */
function activate_flo_forms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flo-forms-activator.php';
	Flo_Forms_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-flo-forms-deactivator.php
 */
function deactivate_flo_forms() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-flo-forms-deactivator.php';
	Flo_Forms_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_flo_forms' );
register_deactivation_hook( __FILE__, 'deactivate_flo_forms' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-flo-forms.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_flo_forms() {

	$plugin = new Flo_Forms();
	$plugin->run();

}
run_flo_forms();
