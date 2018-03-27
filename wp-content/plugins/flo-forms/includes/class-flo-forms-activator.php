<?php

/**
 * Fired during plugin activation
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Flo_Forms
 * @subpackage Flo_Forms/includes
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Forms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// on plugin activation set the initial plugin settings

		$current_user = wp_get_current_user(); // get the current user info

		$forms_options = get_option('flo_forms_options');
		if(!$forms_options){
			// if the options are not save yet, we define the defaults
			$forms_options = array(
				'enable_email_reminder' => 1,
				//how many days old should entries be in order to triger the reminder email
				'entries_days_old_reminder' => 1,
				'send_to_email' => $current_user->user_email,
				'text_email' => 0,
                'enable-captcha' => 0,
                'g_site_key' => '',
                'g_secret_key' => ''
			);
		}

		update_option('flo_forms_options',$forms_options);
	}

}
