<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_import_export') ) :


class acf_field_import_export extends acf_field {
	
	
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
		
		$this->name = 'import_export';
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Export/Import', 'flotheme');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'basic';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'options_prefix'	=> 'flo-',
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('import_export', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'flotheme'),
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
			'label'			=> __('Options prefix','flotheme'),
			'instructions'	=> __('Add here the prefix for the options used in the current theme. The default is "flo-"','flotheme'),
			'type'			=> 'text',
			'name'			=> 'options_prefix',
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
		*  Review the data of $field.
		*  This will show what data is available
		*/
		
		// echo '<pre>';
		// 	print_r( $field );
		// echo '</pre>';


		/*
		*  Create a simple text input using the 'font_size' setting.
		*/
		//self::flo_download_options();
		?>
		<div class="flo-warning">
			<?php
				echo sprintf(__('Warning! If you paste the import data and click "Import" %s you will lose all your current settings. %s Double check everything!','flotheme'),'<br/>', '<br/>');
			?>
		</div>

		<div class="block">
			<label>
				<?php _e('EXPORT OPTIONS','flotheme'); ?> <br/>
				<textarea><?php self::flo_download_options($is_ajax = false, $pretty_print = false, $flo_download_options = $field['options_prefix']) ?></textarea>
				<span class="hint">
					<?php _e('Here you can copy your current option settings. Keep this safe as you can use it as a backup should anything go wrong, or you can use it to restore your settings on this site (or any other site).','flotheme') ?>
				</span>
			</label>
			
		</div>

		<div class="block">
			<label>
				<?php _e('IMPORT OPTIONS','flotheme'); ?> <br/>
				
				<textarea></textarea>
				<span class="hint">
					<?php _e('Input your backup file above and hit Import to restore your sites options from a backup.','flotheme') ?>
				</span>
			</label>

			<br/>

			<input class="flo-import-options" type="button" onclick="importOptions(jQuery(this))" value="<?php _e('Import','flotheme'); ?> ">
			<span class="spinner"></span>
			<div class="response-msg"></div>
		</div>

		<input type="button" style="display: none;" onclick="downloadJson('<?php echo $field["options_prefix"] ?>')" value="<?php _e('Export options','flotheme'); ?> ">
		

		


		<a id="downloadAnchorElem" style="display:none"></a>
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
		$url = get_template_directory_uri().'/acf/acf-extensions/acf-field-import-export/';
		$version = $this->settings['version'];
		
		$siteurl = get_option('siteurl');
		if (!empty($siteurl)) {
			$siteurl = rtrim($siteurl, '/') . '/wp-admin/admin-ajax.php';
		} else {
			$siteurl = home_url('/wp-admin/admin-ajax.php');
		}
		
		// register & include JS
		wp_register_script( 'acf-input-import_export', "{$url}assets/js/input.js", array('acf-input'), $version );
		wp_localize_script( 'acf-input-import_export', 'ajaxurl', $siteurl );
		wp_enqueue_script('acf-input-import_export');
		
		
		// register & include CSS
		wp_register_style( 'acf-input-import_export', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-import_export');
		
	}

	static function flo_download_options($is_ajax = true, $pretty_print = true, $options_prefix = 'flo-'){
		global $wpdb;
		
		if(isset($_POST['options_prefix']) && strlen($_POST['options_prefix']) ){
			$options_prefix = $_POST['options_prefix'];
		}

		$option_names = $wpdb->get_col( "SELECT DISTINCT `option_name` FROM $wpdb->options WHERE `option_name` LIKE 'options_".$options_prefix."%' OR `option_name` LIKE '_options_".$options_prefix."%' " );


		if ( ! empty( $option_names ) ) {
			$export_options = array();
			foreach ( $option_names as $option_name ) {

				$option_value = get_option( $option_name );
				$export_options[ $option_name ] = maybe_serialize( $option_value );
			}

			if($pretty_print){
				$JSON_PRETTY_PRINT = defined( 'JSON_PRETTY_PRINT' ) ? JSON_PRETTY_PRINT : null;

				echo json_encode( 
					array(
						'options' => $export_options,
						'export_template_directory_uri' => get_template_directory_uri()
					),
					$JSON_PRETTY_PRINT 
					);
			}else{
				echo json_encode( 
					array( 	'options' => $export_options, 
							'export_template_directory_uri' => get_template_directory_uri()
						)
				);
			}
			
		}

		if($is_ajax || isset($_POST['action'])){
			exit();
		}

	}

	static function flo_import_options(){




		$response['message'] = __('Something went wrong and the options could not be updated. Please make sure the options you are trying to import are in the correct format.','flotheme');
		//var_dump(($_POST['import_options']));
		if(isset($_POST['import_options']) && strlen(trim($_POST['import_options'])) ){
			$post_data_obj = json_decode(stripslashes($_POST['import_options']) );
			if(isset($post_data_obj->options) && sizeof((array)$post_data_obj->options) ){

				$theme_typography_option_name = 'flo-typography';
				$theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);

				foreach ((array)$post_data_obj->options as $option_name => $option_value) {
					if('options_'.$theme_typography_option_name == $option_name){
					//	deb_e($option_name);
						update_option($option_name,$option_value);
					}else{
						acf_update_option($option_name,$option_value);
					}
					//acf_update_option($option_name,$option_value);
				}
				flo_delete_theme_options_transient();
				$response['message'] = __('The options were updated successfully','flotheme');
			}

		}else{
			$response['message'] = __('No options were imported because no data was passed','flotheme');
		}

		echo json_encode($response);
		exit();
	}

}


// initialize
new acf_field_import_export( $this->settings );


// class_exists check
endif;


add_action('wp_ajax_flo_download_options' , array('acf_field_import_export','flo_download_options') );
add_action('wp_ajax_flo_import_options' , array('acf_field_import_export','flo_import_options') );


?>
