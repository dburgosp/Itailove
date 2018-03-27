<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_flo_backup_stylekit') ) :


class acf_field_flo_backup_stylekit extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct( $settings ) {

		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/

		$this->name = 'flo_backup_stylekit';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Backup Flo Stylekit', 'flotheme');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'flothemes';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'color_options_prefix'	=> 'flo-color-',
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('flo_backup_stylekit', 'error');
		*/

		$this->l10n = array(
			//'error'	=> __('Error! Please enter a higher value', 'acf-flo_backup_stylekit'),
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;


		// do not delete!
    	parent::__construct();

	}


	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field ) {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		acf_render_field_setting( $field, array(
			'label'			=> __('Color Options Prefix','flotheme'),
			//'instructions'	=> __('Customise the input font size','acf-flo_backup_stylekit'),
			'type'			=> 'text',
			'name'			=> 'color_options_prefix',
		));

	}



	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field( $field ) {

		/*
		*  Create a simple text input using the 'font_size' setting.
		*/
		//option name will be flo_stylekit_backup_%theme_name%
		$theme_data = wp_get_theme();
        $theme_name = strtolower($theme_data->Name);


		// get the current stylekit backup if available
        $flo_stylekits_backup = get_option('flo_stylekit_backup_'.$theme_name, array());

        wp_localize_script( 'acf-input-flo_backup_stylekit', 'flo_stylekits_backup', array_reverse($flo_stylekits_backup));
		?>
		<div ng-asdpp="BackupStylekit">
			<div ng-controller="floBackupStylekitCtrl" >

        <div class="flo-backup__main-wrap">
          <!-- START: LEFT SIDE -->
            <div class="flo-backup__left-wrap">
              <div class="flo-backup__wrap-title">
                Backup
              </div>
      				<input type="button" class="flo-backup__showname flo-backup-showname flo-admin-button  " value="SAVE YOUR CURRENT SETUP" onclick="floBackupShowName();">
      				<div class="flo-backup__name-block hidden">
      					<input type="text" class="flo-backup__name-value" placeholder="<?php _e('BACKUP NAME','flotheme'); ?>">
      					<input type="hidden" class="flo-backup__colors-option-prefix" value="<?php echo $field['color_options_prefix']; ?>">
      					<input type="button" class="flo-backup__button-backup-stylekit flo-backup-stylekit flo-admin-button  " value="BACKUP"  ng-click="floBackupStylekit('<?php echo $field['color_options_prefix']; ?>')">
      					<!-- <span class="spinner backup-spinner"></span> -->
      				</div>

      				<p class="flo-backup__response-msg">{{response_message}}</p>
            </div>
          <!-- END: LEFT SIDE -->

          <!-- START: RIGHT SIDE -->
            <div class="flo-backup__right-wrap">
              <div class="flo-backup__wrap-title">
                Restore
              </div>
      				<div class="flo-backup__available-backups" ng-hide=" flo_stylekits_backup.length == 0">
      					<div class="flo-backup__list-wrapper">
      						<div class="flo-backup__list" ng-repeat="(stylekit_index, stylekit_backup) in flo_stylekits_backup">
      							<div class="flo-backup__list-entry">
      								<div class="flo-backup__entry-time">{{ stylekit_backup.time * 1000| date:'yyyy-MM-dd HH:mm:ss' }}</div>
      								<div class="flo-backup__entry-name">{{ stylekit_backup.backup_name }}</div>
      								<div class="flo-backup__entry-restore " title="<?php _e('Restore this stylekit version','flotheme'); ?>">
      									<span class="dashicons dashicons-image-rotate" ng-click="floRestoreStylekit(stylekit_index)"></span>
      								</div>
      							</div>
      						</div>
      					</div>
      				</div>

            </div>
          <!-- LEFT: RIGHT SIDE -->
        </div>

			</div>
		</div>
		<?php
	}

	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/
	function input_admin_enqueue_scripts() {

		// vars
		$url = get_template_directory_uri().'/acf/acf-extensions/acf-flo-backup-stylekit/';
		$version = $this->settings['version'];

		$siteurl = get_option('siteurl');
	    if (!empty($siteurl)) {
	      $siteurl = rtrim($siteurl, '/') . '/wp-admin/admin-ajax.php';
	    } else {
	      $siteurl = home_url('/wp-admin/admin-ajax.php');
	    }

		// register & include JS
		wp_register_script( 'acf-input-flo_backup_stylekit', "{$url}assets/js/input.js", array('acf-input'), $version, true );
		wp_enqueue_script('acf-input-flo_backup_stylekit');
		wp_localize_script( 'acf-input-flo_backup_stylekit', 'ajaxurl', $siteurl );


		// register & include CSS
		wp_register_style( 'acf-input-flo_backup_stylekit', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-flo_backup_stylekit');

	}


	/**
	 *
	 * Saving a stylekit means saving the current Typography and Colors settings
	 *
	 */
	function flo_backup_stylekit(){

		$response = array();

		if( isset($_POST['backup_name']) && isset($_POST['color_options_prefix']) && strlen($_POST['color_options_prefix']) ){
			global $flo_options;

			$stylekit_options_backup = array();


			// 1. COLOR OPTIONS
			// iterare through all the options
			foreach ($flo_options as $key => $value) {
				// if the options name starts with the color options prefix
				if( strpos($key, $_POST['color_options_prefix']) === 0 ){
					$stylekit_options_backup['color_options'][$key] = $value;
				}

			}

			// 2. COLOR OPTIONS
			$theme_typography_option_name = flo_get_theme_typography_option_name();
			if(isset($flo_options[$theme_typography_option_name])){
				$stylekit_options_backup['typography_options'] = $flo_options[$theme_typography_option_name];
			}

			// 3. TIME
			// store the current time to be able to show when the backup was made
			$stylekit_options_backup['time'] = time();

			// 4. BACKUP NAME
			// the backup name
			$stylekit_options_backup['backup_name'] = $_POST['backup_name'];

			//option name will be flo_stylekit_backup_%theme_name%
			$theme_data = wp_get_theme();
	        $theme_name = strtolower($theme_data->Name);


			// get the current stylekit backup if available
	        $flo_stylekit_backup = get_option('flo_stylekit_backup_'.$theme_name, array());

	        $current_backup_key = str_replace(' ' , '_' ,  strtolower(trim($_POST['backup_name']) ) );

	        // add the current options to the existing saved Stylekit
	        $flo_stylekit_backup[$current_backup_key] = $stylekit_options_backup;

	        // update the backup in the DB
	        update_option('flo_stylekit_backup_'.$theme_name, $flo_stylekit_backup);

	        $response['status'] = 'success';
	        $response['message'] = __('Options were saved succesfully','flotheme');
	        $response['backup_data'] = array_reverse($flo_stylekit_backup);

		}else{
			$response['status'] = 'error';
			$response['message'] = __('Color options prefix or the backup name is not available','flotheme');
		}

		echo json_encode($response);

		exit();
	}


	/**
	 *
	 * Processes an ajax request.
	 * The stylekit_index is passed as a POST parameter and then it is used to restore the
	 * Stylekit options related to that stylekit_index.
	 *
	 */
	function flo_restore_stylekit_backup(){
		$response = array();
		if(isset($_POST['stylekit_index']) && strlen($_POST['stylekit_index']) ){
			//option name will be flo_stylekit_backup_%theme_name%
			$theme_data = wp_get_theme();
	        $theme_name = strtolower($theme_data->Name);


			// get the current stylekit backup if available
	        $flo_stylekit_backup = get_option('flo_stylekit_backup_'.$theme_name, array());

	        if(isset($flo_stylekit_backup[$_POST['stylekit_index']])){
	        	$selected_stylekit_backup = $flo_stylekit_backup[$_POST['stylekit_index']];

	        	if(isset($selected_stylekit_backup['color_options'])){
	        		foreach ($selected_stylekit_backup['color_options'] as $color_option_name => $color_value) {
	        			//var_dump($color_option_name, $color_value);
	        			update_field($color_option_name, $color_value, 'options');
	        		}
	        	}




	        	if(isset($selected_stylekit_backup['typography_options'])){
	        		$typography_option_name = flo_get_theme_typography_option_name();

	        		//print_r(get_option('options_'.$typography_option_name));
	        		//print_r($selected_stylekit_backup['typography_options']);
	        	//die();

	        		update_option('options_'.$typography_option_name, $selected_stylekit_backup['typography_options']);
	        	}

	        	flo_delete_theme_options_transient(); // delete the options cache;

	        	$response['status'] = 'success';
	        	$response['message'] = __('The backup was succesfully restored','flotheme');
	        }else{
	        	$response['status'] = 'error';
	        	$response['message'] = __('Something is wrong with the selected backup. It could not be found.','flotheme');
	        }
		}else{
			$response['status'] = 'error';
	    	$response['message'] = __('Something is wrong with the selected backup. Empty name.','flotheme');
		}



		echo json_encode($response);

		exit;
	}

}


// initialize
new acf_field_flo_backup_stylekit( $this->settings );


// class_exists check
endif;

add_action('wp_ajax_flo_backup_stylekit' , array('acf_field_flo_backup_stylekit','flo_backup_stylekit') );
add_action('wp_ajax_flo_restore_stylekit_backup' , array('acf_field_flo_backup_stylekit','flo_restore_stylekit_backup') );

?>
