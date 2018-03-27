<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Flo_Forms
 * @subpackage Flo_Forms/includes
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Forms {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Flo_Forms_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'flo-forms';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->flo_register_custom_post_type();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Flo_Forms_Loader. Orchestrates the hooks of the plugin.
	 * - Flo_Forms_i18n. Defines internationalization functionality.
	 * - Flo_Forms_Admin. Defines all hooks for the admin area.
	 * - Flo_Forms_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flo-forms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flo-forms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flo-forms-admin.php';

		/**
		 * The class responsible for creating the forms settings 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flo-form-meta-box.php';

		/**
		 * The class responsible for creating the Entries Meta boxes 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flo-entry-meta-box.php';

		/**
		 * The class responsible for creating the forms fields
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flo-form-fields.php';

		/**
		 * The class responsible for creating MCE shortcode generator buttons
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-flo-forms-mce.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-flo-forms-public.php';

		/**
		 * The class responsible for registering the custom post types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-flo-forms-custom-posts.php';

		$this->loader = new Flo_Forms_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Flo_Forms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Flo_Forms_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Init registration of the custom posts type
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function flo_register_custom_post_type(){
		$flo_init_c_p_type = new Flo_Forms_custom_posts();
		$this->loader->add_action( 'init', $flo_init_c_p_type, 'flo_reg_custom_post_type' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Flo_Forms_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_ajax_add_form_field', $plugin_admin, 'add_form_field' );
		$this->loader->add_action( 'wp_ajax_entry_read_unread', $plugin_admin, 'read_unread_entry' );
		
		$this->loader->add_action( 'manage_flo_forms_posts_columns', $plugin_admin, 'set_custom_columns' ); // set the custom columns
		$this->loader->add_action( 'manage_flo_forms_posts_custom_column', $plugin_admin, 'custom_columns', 10, 2 ); // add custom columns to the forms browse page


		$this->loader->add_action( 'manage_flo_form_entry_posts_columns', $plugin_admin, 'set_entry_custom_columns' ); // set the custom columns
		$this->loader->add_action( 'manage_flo_form_entry_posts_custom_column', $plugin_admin, 'entry_custom_columns', 10, 2 ); // add custom columns to the entries browse page

		// we want to add Form taxonomy dropdown to the entries browse page to filter easily
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'restrict_entries_by_form' );

		// run an action on post edit page to update the read Entry meta.
		//post.php?post=1077&action=edit
		$this->loader->add_action( 'load-post.php', $plugin_admin, 'set_entry_read' );

		// add a note in the dashboard menu showing the number of unread Entries
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'unread_entries_note', 999 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'flo_add_forms_options' );
		

		//for the entries that are read we add a specific class
		$this->loader->add_filter( 'post_class', $plugin_admin, 'read_unread_entries', 10, 3 );

		// to the Entries browse list add a button that allows to mark each entry as read or unread
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'read_unread_entries_button', 10, 2 );

		// run the updater
		$this->loader->add_action( 'admin_init', $plugin_admin, 'flo_plugin_update' );


		// register the Tiny mce buttons for the shortcode generator
//		$plugin_admin_mce = new Flo_Forms_Mce();
//		$this->loader->add_action( 'mce_external_plugins', $plugin_admin_mce, 'flo_forms_add_button' );
//		$this->loader->add_action( 'mce_buttons', $plugin_admin_mce, 'flo_forms_register_button' );
//		$this->loader->add_action( 'wp_ajax_plugin_slug_insert_dialog', $plugin_admin_mce, 'flo_forms_insert_gist_dialog' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Flo_Forms_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// action that marks a certain Entry as read
		$this->loader->add_action( 'init', $plugin_public, 'read_entry' );

		// action for the daily Unread entries reminder
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'unread_entries_reminder', 999 );


		// ajax actions
		$this->loader->add_action( 'wp_ajax_flo_form_submit', $plugin_public, 'flo_submit_form' );
		$this->loader->add_action( 'wp_ajax_nopriv_flo_form_submit', $plugin_public, 'flo_submit_form' );

		// registoer  the form  plugin
		add_shortcode( 'flo_form', array($plugin_public,'flo_forms_shortcode') ); // register the forms shortcode

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Flo_Forms_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function flo_form_post_init(){

	}

}

if(!function_exists('deb_e')){
	function deb_e( $data ){
		print '<pre style="margin:10px; border:1px dashed #999999; padding:10px; color:#333; background:#ffffff;">';
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        //print "[ File : " . self::short( $caller['file'] ) . " ][ Line : " . $caller['line'] . " ]\n";
        print "--------------------------------------------------------------\n";
		print "</pre>";
	}
}
