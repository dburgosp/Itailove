<?php

/**
 * Fired during plugin activation
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/includes
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Setup_Behind_Scene_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flo_setup_table_prefix();
	}

}
