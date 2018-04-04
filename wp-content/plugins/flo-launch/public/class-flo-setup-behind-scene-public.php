<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/public
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Setup_Behind_Scene_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flo_Setup_Behind_Scene_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Setup_Behind_Scene_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flo-setup-behind-scene-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flo_Setup_Behind_Scene_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Setup_Behind_Scene_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flo-setup-behind-scene-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 *
	 * Enable or disable the Test Drive mode by passig some parameters in the URL
	 * We need that to make it easy for users to test in various browsers
	 *
	 */
	public static function flo_enable_disable_test_mode(){
		if(isset($_GET['flo_test_mode']) && $_GET['flo_test_mode'] == 1){

			$table_prefix_cookie_name = get_option('flo_setup_table_prefix');

			setcookie("flo_custom_table_prefix", $table_prefix_cookie_name, time()+3600*24*365*3, '/');  /* expire in 3 years */

		}

		if(isset($_GET['flo_disable_test_mode']) && $_GET['flo_disable_test_mode'] == 1){
			setcookie('flo_custom_table_prefix', '', time()-1000, '/');  /* set a past time to make the cokie expired */
		}
	}

	public static function flo_test_drive_footer_print(){
		$msg2 = '';
		if(get_option('flo_test_drive_tables_created')){
			$msg = 'The test mode was started.';

			// the test mode is not enabled yet
			if ( !(isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) ) ){
				$msg2 = 'The test mode is not enabled.';
			}else if (isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) ){
				$msg2 = 'The test mode is  enabled.';
			}
		}else if (isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) ){
			$msg = 'The test mode is  enabled.';
		}
		

		echo '<!--FTD activated-->';
		echo '<!-- '.$msg.' -->';
		echo '<!-- '.$msg2.' -->';
		
	}
}
