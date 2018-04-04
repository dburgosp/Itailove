<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/admin
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Forms_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flo_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flo-forms-admin.min.css', array(), $this->version, 'all' );


		global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
		    $post = get_post($_GET['post']);
		    $typenow = $post->post_type;
		}

		if (is_admin() && $typenow=='flo_forms') {
		    if ($pagenow=='post-new.php' OR $pagenow=='post.php') {
		    	wp_enqueue_style('wp-color-picker');
		    	wp_enqueue_style( $this->plugin_name.'_jquery_ui', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui-flo/jquery-ui.min.css', array(), $this->version, 'all');
		    }
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flo_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(function_exists('wp_enqueue_editor')) {
			wp_enqueue_editor();	
		}
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flo-forms-admin.js', array( 'jquery' ), $this->version, false );

		global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
		    $post = get_post($_GET['post']);
		    $typenow = $post->post_type;
		}

		if (is_admin() && $typenow=='flo_forms') {
		    if ($pagenow=='post-new.php' OR $pagenow=='post.php') {
		    	wp_enqueue_script('jquery-ui-tabs');
		    	wp_enqueue_script('jquery-ui-sortable');
		    	wp_enqueue_script('wp-color-picker');
		    	
		    }
		}

	}

	static public function add_form_field( $field_type, $field_settings = array() ){

		if(isset($_POST['field_type'])){ // we have an ajax request
			$field_type = $_POST['field_type'];
			$is_ajax = true;
		}else{
			$is_ajax = false;
		}

		if(!isset($field_settings) || empty($field_settings)){
			// this should usually happen only when the function is called via ajax to add a new element
			$field_id = mt_rand(1,99999);
			$field_id = 'fid_'.$field_id;

			$field_settings = array(
				'id' => $field_id,
				'type' => $field_type
			);
		}


		ob_start();
		ob_clean();
		Flo_Form_Fields::render_field_wrapper($field_settings,$field_type);
		$form_field = ob_get_clean();

		ob_start();
		ob_clean();
		Flo_Form_Fields::render_field_settigs_wrapper($field_settings,$field_type);
		$field_settigs = ob_get_clean();

		$response['form_field'] = $form_field;
		$response['field_settings'] = $field_settigs;



		if($is_ajax){
			echo json_encode($response);
			exit();
		}else{
			return $response;
		}

	}

	/**
	 *
	 * check if it is necessary to render the saved fields
	 * @params
	 *
	 * @form_settings - array containing the data about the form settings
	 * @info-type - form_field or field_settings -> specifies what king of info we want to retrieve
	 *
	 * return - string, the visual inputs or the settings inputs
	 */
	static public function maybe_render_fields( $form_settings, $info_type, $side = 'fields_order' ){
		if(isset($form_settings) && is_array($form_settings) && isset($form_settings[$side]) && isset($form_settings[$side]) ){
			$fields = '';
			foreach ($form_settings[$side] as $settings_key) {
				$current_field_settings = $form_settings['field_settings'][$settings_key];
				$the_field_settings = self::add_form_field( $current_field_settings['type'], $current_field_settings );
				if(isset($the_field_settings[$info_type])){
					$fields .= $the_field_settings[$info_type];
				}
			}

			return $fields;
		}else{
			if($info_type == 'form_field'){
				return sprintf(__('%s Add fields to the form %s','flo-forms'),'<div class="add-fields-msg">','</div>');
			}else if($info_type == 'field_settings'){
				return '';
			}
			
		}

	}

	/**
	 *
	 * Add custom shortcode column to the Forms browse page
	 *
	 */
	public function custom_columns($column, $post_id){
		if ($column == 'form_shortcode'){
		?>	
		[flo_form id='<?php echo $post_id ?>']
		<?php
		}
	}

	/**
	 *
	 * register Shortcode column to the Forms browse page
	 *
	 */
	public function set_custom_columns($columns){

		$new = array();
		foreach($columns as $key => $title) {
		    if ($key=='date') // Put the Shortcode column before the Date column
		      $new['form_shortcode'] = __( 'Shortcode', 'flo-forms' );
		    $new[$key] = $title;
		}
		return $new;

	}

	public function entry_custom_columns($column, $post_id){
		if ($column == 'user_email'){
			$user_email = get_post_meta($post_id, 'user_email', true);
			echo $user_email;
		
		}
	}

	public function set_entry_custom_columns($columns){
		
		$new = array();
		foreach($columns as $key => $title) {
		    if ($key=='date') // Put the User Email column before the Date column
		      $new['user_email'] = __( 'User Email', 'flo-forms' );
		    $new[$key] = $title;
		}
		return $new;
	}



	/**
	 *
	 * Create a drop down with the list of Forms to be able to filter entries by Form
	 * Used on Entries browse page
	 *
	 */
	public function restrict_entries_by_form() {
		global $typenow;
		$post_type = 'flo_form_entry'; // change HERE
		$taxonomy = 'entry_form'; // change HERE
		if ($typenow == $post_type) {
			$selected = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
			$info_taxonomy = get_taxonomy($taxonomy);
			wp_dropdown_categories(array(
				'show_option_all' => __("Show All {$info_taxonomy->label}"),
				'taxonomy' => $taxonomy,
				'name' => $taxonomy,
				'orderby' => 'name',
				'selected' => $selected,
				'show_count' => true,
				'hide_empty' => true,
				'value_field' => 'slug'
			));
		};
	}

	/**
	 *
	 * Method used by post_class filter
	 * check if the post type is flo_form_entry and if it has 'entry_read'
	 * meta data, and add a class to that Form Entry post
	 * That class is used to style the Browse entries differntly depending 
	 * if it was read or not
	 *
	 */
	public function read_unread_entries($classes, $class, $post_id){
		// only for entries posts
		if(get_post_type($post_id) == 'flo_form_entry'){
			$is_entry_read = get_post_meta($post_id,'entry_read', true);
			if ( 'read' == $is_entry_read ) {
		        $classes[] = 'entry-read';
		    }
		}

	    return $classes;
	}

	/**
	 *
	 * this method is fired when a post is edited
	 * check if the post is a Form Entry, and add a meta data that will be used
	 * as flad for read and unread Entries
	 *
	 */
	public function set_entry_read(){
		if(isset($_GET['post']) && is_numeric($_GET['post'])){
			if(get_post_type($_GET['post']) == 'flo_form_entry'){
				update_post_meta($_GET['post'],'entry_read','read'); // set a meta data for the read entries
			}
		}
	}


	/**
	 *
	 * to the Entries browse list add a button that allows to mark each entry as read or unread
	 *
	 */
	public function read_unread_entries_button($actions, $post){
		//check for your post type
	   if ($post->post_type =="flo_form_entry"){
	   	
	   		$is_entry_read = get_post_meta($post->ID,'entry_read', true);
			if ( 'read' == $is_entry_read ) {
		        $actions['entry_read_unread'] = '<a href="#" data-entry_read="1" data-post_id="'.$post->ID.'" onclick="entryReadUnread(jQuery(this)); return false;">'.__('Mark as unread','flo-forms').'</a>';
		    }else{
		    	$actions['entry_read_unread'] = '<a href="#" data-entry_read="0" data-post_id="'.$post->ID.'" onclick="entryReadUnread(jQuery(this)); return false;">'.__('Mark as read','flo-forms').'</a>';
		    }


	   }
	   return $actions;
	}

	/**
	 *
	 * handle ajax request that marks a Entry  as read or unread
	 *
	 */
	public function read_unread_entry(){
		if($_POST['is_read'] == 1){ // if the Entry was read already
			// we want to mark it as unread
			delete_post_meta( $_POST['post_id'], 'entry_read');
			$response['is_read'] = 0;
			$response['text'] = __('Mark as read','flo-forms');
		}else{
			update_post_meta($_POST['post_id'],'entry_read','read');
			$response['is_read'] = 1;
			$response['text'] = __('Mark as unread','flo-forms');
		}

		echo json_encode($response);
		exit();
	}

	/**
	 *
	 * SHow a update-like notification bubble on Entries menu
	 * to show the number of unread entries
	 *
	 */
	public function unread_entries_note(){
		global  $submenu;

		// retrieve all unread Entries
		$args = array(
			'post_type' => 'flo_form_entry',
			'posts_per_page' => -1,
			'meta_query' => array(
			    array(
			     'key' => 'entry_read',
			     'compare' => 'NOT EXISTS' // this should work...
			    ),
			)

		);
		$query = new WP_Query( $args );
 
		if($query->post_count){
			if(isset($submenu['edit.php?post_type=flo_forms'])){
				foreach ( $submenu['edit.php?post_type=flo_forms'] as $key => $value) {
					if($value[2] == 'edit.php?post_type=flo_form_entry'){
						$submenu['edit.php?post_type=flo_forms'][$key][0] .= '<span title="'.__('Unread entries','flo-forms').'" class="update-plugins count-' . $query->post_count . '"><span class="plugin-count">' . $query->post_count . '</span></span>';
						return;
					}
				}
			}
		}
		

	}

	/**
	 * Check for the plugin updates
	 *
	 * @since    1.0.0
	 */
	function flo_plugin_update() {

		if ( is_admin() && !version_compare(phpversion(), '5.3.0', '<') ) {

			$plugin_data = get_plugin_data( plugin_dir_path( __DIR__ ) . 'flo-forms.php' );

			$plugin_version = $plugin_data['Version'];

			$flo_plugin_remote_path = 'http://flothemes.com/recommended_plugins/plugin-updates.php';

			$flo_plugin_slug = plugin_basename( plugin_dir_path( __DIR__ ) . 'flo-forms.php' );

			$product_name = 'flo-forms';

			$the_plugin = 'flo-forms/flo-forms.php';

			require_once ('class-flo-plugin-auto-update.php');

			new flo_plugin_auto_update ($plugin_version, $flo_plugin_remote_path, $flo_plugin_slug, $product_name, $the_plugin);

		}

	}

	/**
	 * Add the plugins settings page
	 *
	 * @since    1.0.0
	 */
	public function flo_add_forms_options(){

		add_menu_page( $page_title  = 'Flo Forms settings', $menu_title = 'FloForms Settings', $capability = 'manage_options', $menu_slug = 'flo_forms_settings', $function = array(&$this, 'flo_forms_options') , $icon_url = 'dashicons-admin-generic', $position = '53.7' );


		add_submenu_page( 'flo_forms_settings', $page_title  = 'Flo Forms settings', $menu_title = 'FloForms Settings', $capability = 'manage_options', $menu_slug = 'flo_forms_settings', $function = array(&$this, 'flo_forms_options') , $icon_url = 'dashicons-admin-generic' );


	}

	/**
	 * Add the plugins settings page options
	 *
	 * @since    1.0.0
	 */
	public function flo_forms_options(){
		$forms_options = get_option('flo_forms_options');

		if(!$forms_options){
			$current_user = wp_get_current_user(); // get the current user info

			// if the options are not save yet, we define the defaults
			$forms_options = array(
				'enable_email_reminder' => 1,
				'reply_to_header' => 1,
				//how many days old should entries be in order to triger the reminder email
				'entries_days_old_reminder' => 1,
				'send_to_email' => $current_user->user_email,
				'text_email' => 0,
                'enable-captcha' => 0,
                'g_site_key' => '',
                'g_secret_key' => ''
			);
		}

		if(!isset($forms_options['reply_to_header'])){
			$forms_options['reply_to_header'] = 1;
		}

		if(!isset($forms_options['text_email'])){
			$forms_options['text_email'] = 0;
		}

		include_once('partials/options-form.php');
	}


}
