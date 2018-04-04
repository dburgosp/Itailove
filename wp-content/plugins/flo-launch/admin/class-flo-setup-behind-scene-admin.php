<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flo_Setup_Behind_Scene
 * @subpackage Flo_Setup_Behind_Scene/admin
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Setup_Behind_Scene_Admin {

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

		add_action('admin_menu', array(&$this,'flo_add_sbs_options') );

		if (isset($_COOKIE['flo_custom_table_prefix']) && strlen($_COOKIE['flo_custom_table_prefix']) &&
				!(isset($_GET['page']) && 'flo_sbs_settings' == $_GET['page'])
			){
			add_action( 'admin_notices', array(&$this, 'flo_test_drive_enabled_notice') ); 
		}

		add_action('wp_ajax_flo_is_config_writable', array( &$this , 'flo_is_config_writable' ) );

		add_action('wp_ajax_flo_db_backup', array( &$this , 'flo_db_backup' ) );

		add_action('admin_init', array( &$this , 'flo_disable_disk_check_option' ) );

		add_action('wp_ajax_flo_disable_test_drive_mode', array( &$this , 'flo_disable_test_drive_mode' ) );

		add_action('wp_ajax_disable_disk_check', array( &$this , 'disable_disk_check' ) );

		add_action('wp_ajax_flo_launch_test_drive_site', array( &$this , 'flo_launch_test_drive_site' ) );

		add_action('wp_ajax_flo_get_db_tables', array( &$this , 'flo_get_db_tables' ) );

		add_action('wp_ajax_flo_insert_into_tables', array( &$this , 'flo_insert_into_tables' ) );

		add_action('wp_ajax_flo_prepare_for_launch', array( &$this , 'flo_prepare_for_launch' ) );

		add_action('wp_ajax_enable_test_drive_mode_cookie', array( &$this , 'flo_enable_test_drive_mode_cookie' ) );		

		add_action('wp_ajax_flo_delete_test_site', array( &$this , 'flo_delete_test_site' ) );

	}

	/**
	 * Add the plugins settings page
	 *
	 * @since    1.0.0
	 */
	public function flo_add_sbs_options(){
		add_options_page('Flo Launch settings', 'Flo Launch', 'manage_options', 'flo_sbs_settings', array(&$this, 'flo_sbs_options') );

	}
	public function flo_disable_disk_check_option(){
		add_option('disable_disk_check','no');
		// delete_option('disable_disk_check');
	}
	/**
	 * Add the plugins settings page options
	 *
	 * @since    1.0.0
	 */
	public function flo_sbs_options(){
		include_once('options-form.php');
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
		 * defined in Flo_Setup_Behind_Scene_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Setup_Behind_Scene_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/flo-setup-behind-scene-admin.css', array(), $this->version, 'all' );

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
		 * defined in Flo_Setup_Behind_Scene_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Setup_Behind_Scene_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$siteurl = get_option('siteurl');
	    if (!empty($siteurl)) {
	        $siteurl = rtrim($siteurl, '/') . '/wp-admin/admin-ajax.php';
	    } else {
	        $siteurl = home_url('/wp-admin/admin-ajax.php');
	    }

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/flo-setup-behind-scene-admin.js', array( 'jquery' ), $this->version, false );

		wp_localize_script( $this->plugin_name, 'ajaxurl', $siteurl );
	}

	public function flo_test_drive_enabled_notice(){
		$class = "flo-test-drive-enabled error";
		$message = __("The test drive is enabled in this browser. You can activate a different theme and plugins and start 
			working on something amazing, while your users will still see your original site.
			In case you want to check your original site, you can disable the Test Drive mode or use another browser.
			If you are going to make the test site your main site in the future, please do not add any new content to 
			your original public site because the content is not synchronized between the live and test site.",'flotheme');
        
        echo"<div class=\"$class\"> 
        		<div class='left-msg'>
        			<h2 class='title test-drive-enabled-instructions dashicons dashicons-info'>THE TEST MODE IS <span class='gold'>ENABLED</span></h2>
        			<p>$message</p>
        		</div><div class='action-buttons'>
        			<span class='flo-disable-test-mode btn-wraper'>
	        			<button type='button' class='stop-test-mode' onclick='flo_disable_test_drive_mode();''>Disable the test mode</button>
						<span class='spinner'></span>
					</span>
					<span class='flo-launch-test-site btn-wraper'>
						<button type='button' class='launch' onclick='flo_launch_test_drive_site();''>Publish the Test site</button>
						<span class='spinner'></span>
					</span>
        		</div>
        	</div>"; 
	}

	/**
	 * Check if the WP config file is writable
	 *
	 * @since    1.0.2
	 */
	public function flo_is_config_writable(){
		$config_file_name = 'wp-config.php';
		if ( file_exists( ABSPATH . $config_file_name) ) {
			$global_config_file = ABSPATH . $config_file_name;
		} else {
			$global_config_file = dirname(ABSPATH) . '/'.$config_file_name;
		}

		$result['response'] = 'success';

		if ( @is_file( $global_config_file ) == false ) {
			$result['response'] = __('The config file was not found','flotheme');
		}else if (!is_writable($global_config_file)) {
			$result['response'] = sprintf( __("Error:%s The %swp-config.php%s file  is %snot writable%s. %s Make that file writable please in order to be able to use this plugin.",'flotheme'),'<br/>','<b>','</b>','<b>','</b>', '<br>' );
		}
		// $if_trigger = true;
		$disable_disk_check = get_option('disable_disk_check');
		// on some servers disk_free_space(ABSPATH) == false, that means we can not apreciate the available disk space
		// in this case we will ignore this verification and will hope we are luky and there is enough disk space 
		if ( ( false !== disk_free_space(ABSPATH) && disk_free_space(ABSPATH) <= 3000 ) && $disable_disk_check == 'no' ) { 
			// make sure there is enough disk space to write to wp-config
			// otherwise when trying to edit that file it will result in an empty file which
			// will stop the site
			// a basic wp-config file has ~ 2=3 kb, this is why we are comparing with '3000'
		    $result['response'] = __('There is not enough disk space and the test mode can not be enabled. If you are sure that this is a mistake, you can disable <a href="javascript:void()" onclick="disable_disk_check()">disk space verification</a> and try again.','flotheme');
		}

		echo json_encode($result);

		exit();
	}

	/**
	 * Create DB backup file and return the link to download it
	 *
	 * @since    1.0.0
	 */
	public function flo_db_backup(){

		global $wp_filesystem;

		$backup_tables = self::flo_copy_tables();

		$result = array();
		if(strlen(trim($backup_tables))){
			// Initialize the WP filesystem, no more using 'file-put-contents' function
			if (empty($wp_filesystem)) {
			    require_once (ABSPATH . '/wp-admin/includes/file.php');
			    WP_Filesystem();
			}

			$upload_dir = wp_upload_dir();
			//var_dump($upload_dir);
			$file_dir = $upload_dir['basedir'] . '/flo_backups/';
			$file_name = 'db-flo-' . time() . '.sql';
			$file_path = $file_dir.$file_name;
			$file_url = $upload_dir['baseurl'] . '/flo_backups/'.$file_name;

	        if(!$wp_filesystem->is_dir($file_dir)){
				/* directory didn't exist, so let's create it */
				$wp_filesystem->mkdir($file_dir);
			}

	        if ( ! $file = $wp_filesystem->put_contents(  $file_path, '', FS_CHMOD_FILE) ){ // check if we can write to the file

	            $result['response'] = 'Error saving file!';
				//$result['backup_url'] = $file_path;

	        }else{
	        	// write the SQL queries in the file
				$wp_filesystem->put_contents(  $file_path, $backup_tables, FS_CHMOD_FILE);

				$result['response'] = 'success';
				$result['backup_url'] = $file_url;
				$result['content_url'] = content_url();
	        }


		}else{
			$result['response'] = 'The data base could not be backed up.';
		}

		echo json_encode($result);

		exit();
	}

	/**
	 *
	 * Create the SQL cript for the DB back up and return it
	 *
	 */
	static function flo_copy_tables($replace_prefix = false){
		// in case the user uses the test site again after the site was launched
		// this way the message that the site was already launged will dissapear
		delete_option( 'flo_test_test_drive_site_launched');


		global $wpdb;

		$wp_db_exclude_table=array();
		$wp_db_exclude_table=get_option('wp_db_exclude_table');
		$tables = $wpdb->get_col('SHOW TABLES');

		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		$output = '';
		foreach($tables as $table) {
	        if(empty($wp_db_exclude_table) || (!(in_array($table,$wp_db_exclude_table)))){



				$result = $wpdb->get_results("SELECT * FROM {$table}", ARRAY_N);
				$row2 = $wpdb->get_row('SHOW CREATE TABLE '.$table, ARRAY_N); 
				$output .= "\n\n".$row2[1].";\n\n";
				for($i = 0; $i < count($result); $i++) {
					$row = $result[$i];
					$output .= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<count($result[0]); $j++) {

						$row[$j] = mysqli_real_escape_string($link, $row[$j]);
						$output .= (isset($row[$j])) ? '"'.$row[$j].'"'	: '""'; 

						if ($j < (count($result[0])-1)) {
							$output .= ',';
						}
					}
					$output .= ");\n";
				}
				$output .= "\n";
	        }
		}
		$wpdb->flush();

		return $output;

	}

	static function flo_enable_test_drive_mode_cookie(){
		// crete the cookie that will be used as a flag for enabling and disabling the test drive mode
		$result['message'] = self::flo_enable_test_drive_mode();

		$result['response'] = 'success';
		
		echo json_encode($result);

		exit();
	}
	/**
	 *
	 * Query the DB and the the list of all the tables
	 *
	 * @return - array - the tables available
	 */
	static function flo_get_db_tables(){

		// create the tables prefix options in the new DB
		flo_setup_table_prefix();

		global $wpdb;

		// how many rows are inserted at a time
		$limit = 600; //this should be the same value as in the flo_insert_into_tables function

		$wp_db_exclude_table=array();// in case you want to add some tables to exclude

		$tables = $wpdb->get_col('SHOW TABLES'); // get all tables

		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // we need this to able to us dbDelta to create new tables

		$response = array();
		$response['message'] = '';
		$response['nr_iterations'] = 0;
		if(!get_option('flo_test_drive_tables_created') ){
			
			foreach($tables as $table) {
		        if( empty($wp_db_exclude_table) || (!(in_array($table,$wp_db_exclude_table))) ){


			    	$flo_setup_table_prefix = get_option('flo_setup_table_prefix');
			    	$flo_original_table_prefix = get_option('flo_original_table_prefix');
			    	$new_table = str_replace($flo_original_table_prefix, $flo_setup_table_prefix, $table);
			    	
			    	if(!in_array($new_table, $tables)){ // make sure we don't go further if the tables were created already

			    		$response['tables'][] = $new_table; // add the new table to the list of tables
						$response['original_tables'][] = $table; // add the original table to the list of original tables

						$row2 = $wpdb->get_row('SHOW CREATE TABLE '.$table, ARRAY_N);

						$rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table");

						$this_table_iterations = ceil($rowcount/$limit);

						if($this_table_iterations == 0){ // if the table has 0 records, we will still make 1 request
							$this_table_iterations = 1;
						}

						$response['nr_iterations'] += $this_table_iterations;

						$create_table_script = str_replace($table, $new_table, $row2);

						dbDelta( $create_table_script ); // create the new table

						// get the table field names
						$table_fields = array();
						foreach ( $wpdb->get_col( "DESC " . $table, 0 ) as $column_name ) {
							$table_fields[] = $column_name;
						}
						$response['table_fields'][$new_table] = $table_fields; // add the new table to the list of tables

			    	}

		        }

			}

			$response['response'] = 'success';
		}else{
			$response['response'] = 'tables are created already';
		}

		$wpdb->flush();

		

		echo json_encode($response);

		exit();
	}

	public static function flo_insert_into_tables(){
		global $wpdb;
		$limit = 600;
		

		//var_dump($_POST);

		$response = array();
		if( isset($_POST['table_name']) && isset($_POST['table_row']) && isset($_POST['iteration_number'])){

			$table = $_POST['table_name'];
			$original_table = $_POST['original_table_name'];
			$offset = $limit * $_POST['iteration_number'];

			$result = $wpdb->get_results("SELECT * FROM {$original_table} LIMIT {$limit} OFFSET {$offset}", ARRAY_N);
//var_dump('2222  ', count($result));
			$response['query'] = "SELECT * FROM {$original_table} LIMIT {$limit} OFFSET {$offset}";
			$response['count_result'] = count($result);
			if(count($result) < $limit){
				$response['table_done'] = 1;
			}

			$new_table = $_POST['table_name'];
			$table_fields = explode(',',$_POST['table_row']);
			
			for($i = 0; $i < count($result); $i++) {

				$row = $result[$i];

				$table_row = array();
				for($j=0; $j<count($result[0]); $j++) {

					$table_row[$table_fields[$j]] = $row[$j];
				}

				//$response['table_row'] = $table_row;


				$w = $wpdb->insert(
					$new_table,
					$table_row

				);


			}

			$response['message'] = 'success';
			//var_dump($response);
		}else{
			$response['message'] = __('Wrong post data','flotheme');
		}

		echo json_encode($response);
		exit();

	}

	public static function flo_prepare_for_launch(){

		// if the tables were created, then we will fix the user_meta and Options tables
		$response['message'] = self::flo_meta_old_prefix();

		// this is a flag that the copy of the tables were created////			
		update_option( 'flo_test_drive_tables_created', 1);

		//modify the config file to be able to use the new  tables prefix
		$response['message'] .= self::flo_edit_config_file();

		// crete the cookie that will be used as a flag for enabling and disabling the test drive mode
		$response['message'] .= self::flo_enable_test_drive_mode();

		echo json_encode($response);

		exit();
	}

	/**
	 *
	 * When the table prefix is changed, there are wome problems
	 * To fix those errors it is necessary to find all meta_keys in the user meta table that have the prefix 'wp_' (old prefix)
	 * Also in the options table we need to update all option names that begin with 'wp_' (old prefix) with the new prefix
	 */
	static function flo_meta_old_prefix(){
		global $wpdb;

		$flo_setup_table_prefix = get_option('flo_setup_table_prefix');
		$flo_original_table_prefix = get_option('flo_original_table_prefix');

		// update the user meta table
		// UPDATE flo_pr_usermeta
		// SET meta_key = REPLACE(meta_key,'wp_','flo_pr_') 
		// Where meta_key like 'wp_%';
		$wpdb->query("
			UPDATE ".$flo_setup_table_prefix."usermeta
			SET meta_key = REPLACE(meta_key,'$flo_original_table_prefix', '$flo_setup_table_prefix')
			Where meta_key like '$flo_original_table_prefix%'"
		);


		// update the options table
		// UPDATE flo_pr_options
		// SET option_name = REPLACE(option_name,'wp_','flo_pr_') 
		// Where option_name like 'wp_%';
		$wpdb->query("
			UPDATE ".$flo_setup_table_prefix."options
			SET option_name = REPLACE(option_name,'$flo_original_table_prefix', '$flo_setup_table_prefix')
			Where option_name like '$flo_original_table_prefix%'"
		);

	}

	/**
	 *
	 * Find the config file and call the flo_replace_file_line method to edit it
	 *
	 */
	static function flo_edit_config_file(){
		$config_file_name = 'wp-config.php';
		if ( file_exists( ABSPATH . $config_file_name) ) {
			$global_config_file = ABSPATH . $config_file_name;
		} else {
			$global_config_file = dirname(ABSPATH) . '/'.$config_file_name;
		}

		$flo_original_table_prefix = get_option('flo_original_table_prefix');

		$old_line = "\$table_prefix\s*=\s*'".$flo_original_table_prefix."'\s*;";
		$new_line = 'if(isset($_COOKIE["flo_custom_table_prefix"]) && $_COOKIE["flo_custom_table_prefix"] != ""){$table_prefix=$_COOKIE["flo_custom_table_prefix"];}';
		return self::flo_replace_file_line($old_line, $new_line, $global_config_file );

	}

	/**
	 *
	 * Read a file and load the content into an array
	 * Then iterate through each line and add the new line after the line we need
	 * Basically it is used to find in 'wp-config' the '$table_prefix  = 'wp_';' line
	 * and add after it
	 * if(isset($_COOKIE["flo_custom_table_prefix"]) && $_COOKIE["flo_custom_table_prefix"] != ""){$table_prefix=$_COOKIE["flo_custom_table_prefix"];}
	 *
	 * Then we update the wp-config file with the new content
	 */
	public static function flo_replace_file_line($old, $new, $my_file) {
		if ( @is_file( $my_file ) == false ) {
			return __('The config file was not found','flotheme');
		}
		if (!is_writable($my_file)) {
			return __("Error: file $my_file is not writable.\n",'flotheme');
		}

		global $wp_filesystem;
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if (empty($wp_filesystem)) {
		    require_once (ABSPATH . '/wp-admin/includes/file.php');
		    WP_Filesystem();
		}

		$conf_file_content = $wp_filesystem->get_contents($my_file);

		//if the file was not edited yet
		if(!strstr($conf_file_content,'//Added by FLO-test drive plugin')){
			$conf_file_content_array = explode(PHP_EOL,$conf_file_content);

			$comment_text = '//Added by FLO-test drive plugin';
			foreach ($conf_file_content_array as $key => $line) {
				if(strstr($line,'table_prefix')){
					$conf_file_content_array[$key] = $line.PHP_EOL.$new.$comment_text;
				}
			}

			$new_config_content = implode(PHP_EOL,$conf_file_content_array);

			$wp_filesystem->put_contents(  $my_file, $new_config_content, FS_CHMOD_FILE);

			return __('The config file was replaced succesfully','flotheme');

		}else{
			return  __('The config file was modified already','flotheme');
		}

	}

	// crete the cookie that will be used as a flag for enabling and disabling the test drive mode
	public static function flo_enable_test_drive_mode(){
		$table_prefix_cookie_name = get_option('flo_setup_table_prefix');

		setcookie("flo_custom_table_prefix", $table_prefix_cookie_name, time()+3600*24*365*3, '/');  /* expire in 3 years */

		return __('The cookie was created succesfully','flotheme');
	}

	// delete the cookie that will be used as a flag for enabling and disabling the test drive mode
	public static function flo_disable_test_drive_mode(){
		//setcookie("flo_custom_table_prefix", '', time()-1000, '/');  /* set a past time to make the cokie expired */
		self::flo_delete_cookie("flo_custom_table_prefix");
		$result['response'] = 'success';

		echo json_encode($result);
		exit();
	}

	public static function disable_disk_check(){
		update_option('disable_disk_check', 'yes' );

		$result['response'] = 'success';

		echo json_encode($result);
		exit();
	}

	public static function flo_delete_cookie($cookie_name){
		setcookie($cookie_name, '', time()-1000, '/');  /* set a past time to make the cokie expired */
	}

	/**
	 *
	 * When the test site is launched we need to so
	 * a few operations 
	 *
	 */
	
	public static function flo_launch_test_drive_site(){

		//clear the cookie
		self::flo_delete_cookie("flo_custom_table_prefix");

		self::flo_delete_migration_flag();

		// this is a flag that the copy of the tables were created
		update_option( 'flo_test_test_drive_site_launched', 1);


		//edit the wp-config file
		self::flo_prepare_config_for_launch();

		$result['response'] = 'success';

		echo json_encode($result);

		exit();
	}

	/**
	 *
	 * After the Test site is launched, we want to delete the 'migration flag' from
	 * the original database, in case we want to go back to the original DB
	 *
	 */
	public static function flo_delete_migration_flag(){
		global $wpdb;
		$flo_original_table_prefix = get_option('flo_original_table_prefix'); // this option is available in both databases

		$wpdb->query("
			DELETE FROM ".$flo_original_table_prefix."options 
			WHERE ".$flo_original_table_prefix."options.option_name = 'flo_test_drive_tables_created'
			"
		);

	}

	/**
	 *
	 * Change the table prefix in the wp-config file to use the new DB tables
	 *
	 */
	public static function flo_prepare_config_for_launch($start_over_mode = false, $is_ajax = true) {
		$new_table_prefix = get_option('flo_setup_table_prefix');

		$config_file_name = 'wp-config.php';
		if ( file_exists( ABSPATH . $config_file_name) ) {
			$my_file = ABSPATH . $config_file_name;
		} else {
			$my_file = dirname(ABSPATH) . '/'.$config_file_name;
		}

		if ( @is_file( $my_file ) == false ) {
			return __('The config file was not found','flotheme');
		}
		if (!is_writable($my_file)) {
			return __("Error: file $my_file is not writable.\n",'flotheme');
		}

		global $wp_filesystem;
		// Initialize the WP filesystem, no more using 'file-put-contents' function
		if (empty($wp_filesystem)) {
		    require_once (ABSPATH . '/wp-admin/includes/file.php');
		    WP_Filesystem();
		}

		$conf_file_content = $wp_filesystem->get_contents($my_file);

		//if the file was not edited yet
		if(strstr($conf_file_content,'//Added by FLO-test drive plugin')){
			$conf_file_content_array = explode(PHP_EOL,$conf_file_content);
			$conf_file_content_array_modified = $conf_file_content_array;

			$comment_text = '//Added by FLO-test drive plugin';
			foreach ($conf_file_content_array as $key => $line) {

				// if start over mode is true, then that means we want to return to the original
				// tate of the wp-config we don;t need to replace the prefix
				if(!$start_over_mode){
					// replace the old prefix with the new one
					if(strstr($line,'table_prefix') && !strstr($line,$comment_text)){
						$conf_file_content_array_modified[$key] = "\$table_prefix = '" . $new_table_prefix ."';";
					}
				}


				if(strstr($line,$comment_text) ){
					unset($conf_file_content_array_modified[$key]);
				}
			}

			$new_config_content = implode(PHP_EOL,$conf_file_content_array_modified);

			$wp_filesystem->put_contents(  $my_file, $new_config_content, FS_CHMOD_FILE);

			$result['response'] = 'success';
			//return __('The config file was replaced succesfully','flotheme');

		}else{
			$result['response'] = 'The config file was not modified previously. There is nothing to change.';
			//return  __('The config file was not modified previously. There is nothing to change.','flotheme');
		}

		if($is_ajax){  // when this function is called via Ajax
			echo json_encode($result);

			exit();
		}

	}

	/**
	 *
	 * Delete the current test site tables and remove the flag options related to the test site
	 *
	 */
	public function flo_delete_test_site(){
		$result = array();

		$flo_setup_table_prefix = get_option( 'flo_setup_table_prefix');
		$flo_original_table_prefix = get_option( 'flo_original_table_prefix');

		// 1) delete the test tables 
		self::flo_delete_tables($flo_setup_table_prefix);

		// 2) delete the options related to the test site
		//delete_option( 'flo_setup_table_prefix');
		delete_option( 'flo_test_drive_tables_created');
		delete_option( 'flo_test_test_drive_site_launched');

		// 3) edit back the wp config file
		self::flo_prepare_config_for_launch($start_over_mode = true, $is_ajax = false);

		$result['response'] = __('The test site was succesfully deleted.','flotheme');

		echo json_encode($result);

		exit();
	}

	public static function flo_delete_tables($table_prefix){
		global $wpdb;

		$tables = $wpdb->get_col('SHOW TABLES'); // get all tables


		foreach($tables as $table) {
			if( strpos ( $table , $table_prefix )  === 0){ // IF THE table name begins with the prefix we are interested in
				//echo "DROP TABLE $table";
				$wpdb->query("DROP TABLE $table");
			}
		}
	}

}
