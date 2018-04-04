<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/includes
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Setup_Behind_Scene_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		global $wp_filesystem;
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if (empty($wp_filesystem)) {
		    require_once (ABSPATH . '/wp-admin/includes/file.php');
		    WP_Filesystem();
		}

		// remove the backup directory when the plugin is deactivated
		$upload_dir = wp_upload_dir();
		$file_dir = $upload_dir['basedir'] . '/flo_backups/';

        if($wp_filesystem->is_dir($file_dir)){
			/* directory  exist, so let's remove it */
			$rmd = $wp_filesystem->rmdir($file_dir, true);

		}
	}

}
