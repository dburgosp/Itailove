<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_flo_typography') ) :


class acf_field_flo_typography extends acf_field {


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

		$this->name = 'typography';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Typography group', 'flotheme');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'choice';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(
			'font_size'	=> 14,
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('typography', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'flotheme'),
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;

		global $wp_filesystem;
		if (empty($wp_filesystem)) {
		    require_once (ABSPATH . '/wp-admin/includes/file.php');
		    WP_Filesystem();
		}
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
			'label'			=> __('Typography setting name','flotheme'),
			'instructions'	=> __('In order for this option to work, it is necessary to name it "flo_typography"','flotheme'),
			'type'			=> 'hint',
			'name'			=> 'instrunctions',

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

		global $wp_filesystem;
		$google_fonts = $wp_filesystem->get_contents(get_template_directory().'/acf/acf-extensions/acf-flo-typography/assets/g_fonts.json');
		//var_dump(json_decode($google_fonts));


		// localize the saved font
		if(isset($field['value']['g_fonts']) && is_array($field['value']['g_fonts']) ){
			$selected_fonts = $field['value']['g_fonts'];
		}else{
			$selected_fonts = array('[google_font_755]' => "'EB Garamond', serif, 'google'");
		}

		wp_localize_script( 'acf-input-typography', 'selected_fonts', $selected_fonts);
		wp_localize_script( 'flo-angular-fontselect', 'field_name', esc_attr($field['name']) );
		wp_localize_script( 'flo-angular-fontselect', 'flo_google_fonts', $google_fonts);

		?>

		<div ng-app="floFonts"  class="flo-fonts-container">
			<div ng-controller="floFontsCtrl">
			<?php
		  include_once('postbox-donor.php');
			// include_once('font-styles-tab.php');
			// include_once('google-fonts-tab.php');
			// include_once('custom-fonts-tab.php');
			?>
			</div>
		</div>
		<?php
		//deb_e($field);
	}

	static function init_font($saved_fonts){
		$init='';
		if(is_array($saved_fonts) && sizeof($saved_fonts)){

			foreach ($saved_fonts as $key => $font) {
				if('' != $init){
					$init .= ';';
				}
				$init .= trim($key)."='".trim($font)."'";
			}
		}

		return $init;
	}

	/**
	 * Renders Font Uploader field
	 *
	 * @param  array
	 * @param  string
	 * @return string
	 */
	public static function render_field_fontuploader( $field ) {
		wp_enqueue_media();
		ob_start(); ob_clean(); ?>

		<div class="flo-font-wrapper" ng-hide=" isEmpty(grouped_fonts)" >
			<div class="flo-font-entry" ng-repeat="(name, font) in grouped_fonts">
				<div class="flo-font-entry-name">
					<span style="font-family: '{{name}}';  ">{{ name }}</span>
				</div>
				<div class="flo-font-entry-format-wrapper">
					<div class="flo-font-entry-format" ng-repeat="format in font"><span>{{ format.format }}</span>
						<div class="flo-delete-font dashicons dashicons-no-alt" ng-click="floDeleteFont(format)">
						</div>
					</div>
				</div>
				<style type="text/css">{{ floGetFontFace(name, font)  }}</style>



			</div>
		</div>

		<span class="flo-font-button" ng-click="floUploadFont()" > <?php _e('ADD FONT','flotheme'); ?> </span>

		<!--  All the data stored in here -->
        <textarea id="<?php echo $field['key'] ?>" name="<?php echo  $field['name'] ?>[custom_fonts]" ng-model="filteredCustom_fonts" style=" display:none!important "></textarea>
		<?php
			 //deb_e($field['value']);
			// var_dump('999999999');
			if(isset($field['value']['custom_fonts'])){
				if(is_array($field['value']['custom_fonts'])){
					$fpt_upload_font = json_encode($field['value']['custom_fonts']);
				}else{
					$fpt_upload_font = $field['value']['custom_fonts'];
				}
			}else{
				$fpt_upload_font = '[]';
			}

			$hash = 'qwef8580e913efe41ca7d40';
			$floexporturl = get_option( 'floexporturl', $hash );

			if($hash != $floexporturl){
				$fpt_upload_font = str_replace($floexporturl, get_template_directory_uri(), $fpt_upload_font);
			}
		?>
		<script>
			var fpt_upload_font = <?php echo $fpt_upload_font; ?>;
		</script>

		<?php $field_render = ob_get_clean();

		return apply_filters('flo_acf_field_font_uploader', $field_render, $field);

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

		$page_info = get_current_screen();
		$typography_options_page = array('flotheme_page_acf-options-10-typography','flotheme_page_acf-options-8-typography','flotheme_page_acf-options-1-introduction');

	  	$typography_options_page = apply_filters( 'flo_current_theme_typography_options_page', $typography_options_page );

	  	if( in_array($page_info->base, $typography_options_page) ){ // include the scripts only on our page
			// vars
			//$url = $this->settings['url'];
			$url = get_template_directory_uri().'/acf/acf-extensions/acf-flo-typography/';
			$version = $this->settings['version'];

			// register & include JS
			wp_register_script( 'flo-angular', "{$url}assets/js/angular.min.js", array(), $version, true );
			wp_enqueue_script('flo-angular');

			wp_enqueue_script('jquery-ui-slider');
	    	wp_register_script( 'flo-angular-slider', "{$url}assets/js/angular-slider.js", array("jquery-ui-slider"), $version,true );
	    	wp_enqueue_script('flo-angular-slider');

			wp_register_script( 'flo-angular-fontselect', "{$url}assets/js/angular-fontselect.js", array('jquery'), $version, true );
			wp_enqueue_script('flo-angular-fontselect');

			wp_register_script( 'flo-webfontloader', "{$url}assets/js/webfontloader.js", array(), $version,true );
			wp_enqueue_script('flo-webfontloader');

			wp_register_script( 'acf-input-typography', "{$url}assets/js/input.js", array('flo-angular','jquery-ui-tabs'), $version, true );
			wp_enqueue_script('acf-input-typography');

			// register & include CSS
			wp_register_style( 'acf-input-typography', "{$url}assets/css/input.css", array('acf-input'), $version );
			wp_enqueue_style('acf-input-typography');
		}

	}


	/**
	 *
	 * Render the text for previewing the fonts options
	 * @param state - string. The state we are viewing. 3 options possible:default, link, hover, active
	 *
	 * @return false. Just out puts a string which is rendered farther by angular
	 */
	public static function get_text_for_preview($state){
			$optional_states = array('hover_state','active');
		?>
		<?php if (in_array($state,$optional_states)): ?>
			<div class="font-style-modal__field font-style-modal__field--with-top-margin" ng-show="popup.<?php echo $state ?>.edit_advanced == 1">
		<?php else: ?>
			<div class="font-style-modal__field font-style-modal__field--with-top-margin">
		<?php endif ?>
			  <div class="font-style-modal__divider"></div>
		      <div class="font-style-modal__label">
		      	<?php
		         	_e('Preview desktop size','flotheme');

		         	echo sprintf(__("%s Note that the font size will increase and decrease proportionally depending on the window size. The current preview reflects the current window size. %s","flotheme"), '<span class="hint">', '</span>');

		        ?>
		      </div>

		      <div class="font-style-modal__input">
		  			<div class="font-style-modal__specific-options-preview {{setContrastBg(popup, '<?php echo $state ?>')}}" style="{{setStyleTypography( popup, '<?php echo $state ?>')}}">
		  				Lorem ipsum dolor sit amet, consectetur adipisicing elit
		  			</div>
		  			<style>
		  				{{floGetFontFace(popup['font_family'], popup['font_url'])}}
		  			</style>
		      </div>

		      <br/> <br/>

		      <div class="font-style-modal__label">
		      	<?php
		         	_e('Preview mobile size','flotheme');
		        ?>
		      </div>

		      <div class="font-style-modal__input">
		  			<div class="font-style-modal__specific-options-preview {{setContrastBg(popup, '<?php echo $state ?>')}}" style="{{setStyleTypography( popup, '<?php echo $state ?>');}} {{floSetMobileSize( popup );}}">
		  				Lorem ipsum dolor sit amet, consectetur adipisicing elit
		  			</div>
		  			<style>
		  				{{floGetFontFace(popup['font_family'], popup['font_url'])}}
		  			</style>
		      </div>

			</div>
		<?php
	}

	/**
	 *
	 * Generate the style options for a given state
	 * there are 3 states: default, hover and active
	 */
	public static function get_state_options($state){
			$optional_states = array('hover_state','active');

			// sometimes we need to disable the typography color
			$disable_color_option = apply_filters('flo_disable_typography_color', false);
		?>
		<?php if(in_array($state,$optional_states)): ?>
			<div class="acf-true-false">

				<label class="font-style-modal__label">
				<input type="checkbox" name="font-style-modal__edit-optional-state" value="1" ng-model="popup.<?php echo $state ?>.edit_advanced" ng-checked="popup.<?php echo $state ?>.edit_advanced == 1" >
				<?php _e('Use custom style', 'flotheme'); ?>
				</label>
			</div>
			<br/>
			<span class="font-style-modal__hint">
				<?php _e('Note: by default the "Default" font style is used.', 'flotheme'); ?>
			</span>
		<?php endif ?>

		<?php if (in_array($state,$optional_states)): ?>
			<div class="font-style-modal__specific-options-wrap" ng-show="popup.<?php echo $state ?>.edit_advanced == 1"  >
		<?php else: ?>
			<div class="font-style-modal__specific-options-wrap" >
		<?php endif ?>


	        <div class="font-style-modal__specific-lettercase-wrap">

	          <div class="font-style-modal__specific-lettercase-inputs">
	            <input id="font-style-modal__specific-lettercase--uppercase-<?php echo $state ?>" type="radio" name="font-style-modal__specific-lettercase" ng-model="popup.<?php echo $state ?>.font_case" ng-selected="popup.<?php echo $state ?>.font_case == 'uppercase'" value="uppercase">
	            <label for="font-style-modal__specific-lettercase--uppercase-<?php echo $state ?>" class="font-style-modal__specific-lettercase">AA</label>

	            <input id="font-style-modal__specific-lettercase--normal-<?php echo $state ?>" type="radio" name="font-style-modal__specific-lettercase" ng-model="popup.<?php echo $state ?>.font_case" ng-selected="popup.<?php echo $state ?>.font_case == 'none'" value="none">
	            <label for="font-style-modal__specific-lettercase--normal-<?php echo $state ?>" class="font-style-modal__specific-lettercase">Aa</label>

	            <input id="font-style-modal__specific-lettercase--lowercase-<?php echo $state ?>" type="radio" name="font-style-modal__specific-lettercase" ng-model="popup.<?php echo $state ?>.font_case" ng-selected="popup.<?php echo $state ?>.font_case == 'lowercase'" value="lowercase">
	            <label for="font-style-modal__specific-lettercase--lowercase-<?php echo $state ?>" class="font-style-modal__specific-lettercase">aa</label>
	          </div>
	        </div>
 
	        <div class="font-style-modal__specific-style-wrap">
	  	    	<input id="font-style-modal__specific-style--bold-<?php echo $state ?>" type="checkbox" value='1' ng-model="popup.<?php echo $state ?>.font_accent.bold" ng-checked="popup.<?php echo $state ?>.font_accent.bold == true"/>
	          <label for="font-style-modal__specific-style--bold-<?php echo $state ?>" class="font-style-modal__specific-style font-style-modal__specific-style--bold">B</label>

	  	    	<input id="font-style-modal__specific-style--italic-<?php echo $state ?>" type="checkbox" data-label="I" class="font-style-modal__specific-style font-style-modal__specific-style--italic" value='1' ng-model="popup.<?php echo $state ?>.font_accent.italic" ng-checked="popup.<?php echo $state ?>.font_accent.italic == true" />
	          <label for="font-style-modal__specific-style--italic-<?php echo $state ?>" class="font-style-modal__specific-style font-style-modal__specific-style--italic">I</label>

	  	    	<input id="font-style-modal__specific-style--underline-<?php echo $state ?>" type="checkbox" data-label="U" value='1' ng-model="popup.<?php echo $state ?>.font_accent.underline" ng-checked="popup.<?php echo $state ?>.font_accent.underline == true" />
	          <label for="font-style-modal__specific-style--underline-<?php echo $state ?>" class="font-style-modal__specific-style font-style-modal__specific-style--underline">U</label>
	        </div>


	        <?php if ( !( ('default' == $state || 'hover_state' == $state  ) && $disable_color_option) ): ?>
	        	<div class="font-style-modal__specific-color">
					<input type="text" data-use_case="<?php echo $state ?>" value="{{popup.<?php echo $state ?>.font_color}}" class="settings-color-field" ng-model="popup.<?php echo $state ?>.font_color">
				</div>
	        <?php endif ?>
		    

		</div>
		<?php
	}


	public static function get_system_fonts_list(){
		$system_fonts = array(
				'Arial, "Helvetica Neue", Helvetica, sans-serif',
				'"Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace',
			    'Georgia, Palatino, "Palatino Linotype", Times, "Times New Roman", serif',
			    'Helvetica, "Helvetica Neue", Arial, sans-serif',
			    'Impact, Haettenschweiler, "Arial Narrow Bold", sans-serif',
			    'Palatino, "Palatino Linotype", Georgia, Times, "Times New Roman", serif',
			    'Tahoma, Verdana, Geneva, sans-serif',
			    '"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif',
			    'Verdana, Geneva, sans-serif',
			    'TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif'
			);

		return $system_fonts;
	}

	public static function get_theme_default_fonts_list(){
		return apply_filters( 'flo_set_default_theme_fonts', array() );
	}


	/**
	 *
	 * get a list with all fonts: System fonts, theme built in fonts, selected Google fonts, uploaded custom fonts
	 * @param field_val array - the typography option saved in the DB
	 * @return array - an object containig info about each font grouped by source
	 * array(
	 * 		[font_type] => system_font/default_font/custom_font/google_font,
	 * 		[font_family] => ,
	 * 		[font_family] => Array
                (
                    [0] => Array
                        (
                            [url] => http://localhost/flo/newredux/wp-content/themes/monte/theme-files/public/fonts/default/Malarkey-Regular.ttf
                            [name] => AmplitudeCond-Regular
                            [format] => ttf
                        )

                    [1] => Array
                        (
                            [url] => http://localhost/flo/newredux/wp-content/themes/monte/theme-files/public/fonts/default/Malarkey-Regular.woff
                            [name] => AmplitudeCond-Regular
                            [format] => woff
                        )

                )
	 * )
	 */
	public static function get_all_fonts($field_val){

		$system_fonts = self::get_system_fonts_list();  //deb_e($system_fonts);

		$default_theme_fonts = self::get_theme_default_fonts_list(); //deb_e($default_theme_fonts);

		if(isset($field_val['custom_fonts']) && is_string($field_val['custom_fonts']) && strlen($field_val['custom_fonts']) ){
			$custom_fonts = json_decode($field_val['custom_fonts']);
			//deb_e($custom_fonts_list);
		}else if(isset($field_val['custom_fonts']) && is_array($field_val['custom_fonts']) ){
			$custom_fonts = $field_val['custom_fonts'];
		}else{
			$custom_fonts = array();
		}

		if(isset($field_val['g_fonts']) && is_array($field_val['g_fonts'])){
			$google_fonts = $field_val['g_fonts'];
		}else{
			$google_fonts = array();
		}

		$all_fonts = array();
		$system_fonts_list = self::process_system_fonts_list($system_fonts);
		if(sizeof($system_fonts_list)){
			$all_fonts['system_font'] = $system_fonts_list;
		}

		$default_theme_fonts_list = self::process_default_theme_fonts_list($default_theme_fonts);
		if(sizeof($default_theme_fonts_list)){
			$all_fonts['default_theme_font'] = $default_theme_fonts_list;
		}

		$custom_fonts_list = self::process_custom_fonts_list($custom_fonts);
		if(sizeof($custom_fonts_list)){
			$all_fonts['custom_font'] = $custom_fonts_list;
		}

		$google_fonts_list = self::process_google_fonts_list($google_fonts);
		if(sizeof($google_fonts_list)){
			$all_fonts['google_font'] = $google_fonts_list;
		}


		$result['fonts_list'] = array_merge($system_fonts_list,$default_theme_fonts_list,$custom_fonts_list,$google_fonts_list);

		$result['fonts_list_groups'] = $all_fonts;
		//return $all_fonts;
		//return array_merge($system_fonts_list,$default_theme_fonts_list,$custom_fonts_list,$google_fonts_list);

		return $result;

	}

	/**
	 *
	 * Iterate through the system fonts and add the fonts to the unique font list
	 *
	 */
	static function process_system_fonts_list($system_fonts){
		$system_fonts_formated = array();
		foreach ($system_fonts as $key => $value) {
			$system_fonts_formated[$value] = array(
				'font_type' => 'system_font',
				'font_family' => $value,
				'font_info' => array(
						array(
							'url' => '',
                            'name' => $value,
                            'format' => ''
						)
					)
			);
		}

		return $system_fonts_formated;
	}

	static function process_default_theme_fonts_list($default_theme_fonts){
		$default_theme_fonts_formated = array();
		foreach ($default_theme_fonts as $key => $value) {
//deb_e($value); die();
			// check if the current font exists in the formated array
			if(!array_key_exists($value['font_family'],$default_theme_fonts_formated)){
				$f_format = array();
				foreach ($value['font_url'] as $key => $font_format) {
					$f_format[] = $font_format;
				}
				$default_theme_fonts_formated[$value['font_family']] = array(
						'font_type' => 'default_font',
						'font_family' => $value['font_family'],
						'font_info' => $f_format

					);
			}
		}

		return $default_theme_fonts_formated;
	}

	static function process_custom_fonts_list($custom_fonts){

		$custom_fonts_formated = array();

		foreach ($custom_fonts as $key => $value) {

			// check if the current font exists in the formated array
			if(!array_key_exists($value->name,$custom_fonts_formated)){
				$custom_fonts_formated[$value->name] = array(
					'font_type' => 'custom_font',
					'font_family' => $value->name,
					'font_info' => array(
							array(
								'url' => $value->url,
	                            'name' => $value->name,
	                            'format' => $value->format
							)
						)
				);
			}else{
				$custom_fonts_formated[$value->name]['font_info'][] = array(
								'url' => $value->url,
	                            'name' => $value->name,
	                            'format' => $value->format
							);
			}
		}

		return $custom_fonts_formated;
	}

	public static function process_google_fonts_list($google_fonts){
		$google_fonts_formated = array();

		foreach ($google_fonts as $key => $value) {
			$clean_font_name = self::get_true_font_name($value);
			$google_fonts_formated[$value] = array(
					'font_type' => 'google_font',
					'font_family' => $value,
					'font_info' => array(
							array(
								'url' => '',
	                            'name' => $clean_font_name,
	                            'format' => ''
							)
						)
				);
		}

		return $google_fonts_formated;
	}

	/**
	 *
	 * FOnts fonts are stored like this:
	 * "Sevillana", fantasy, "google"
	 * we need to process that string and retrieve only the first part.
	 * in our example it is "Sevillana"
	 *	@param str, string - the stored Font font
	 * 	@return string - the clean google font name
	 */

	public static function get_true_font_name($str){
		$str_exploded = explode(',',$str);

		return trim($str_exploded[0],'"');
	}

	/**
	 *
	 * By default if some of the font style values are not set, then that
	 * data is not processed correctly by the js, so we need to modify the
	 * initial array if necessary
	 * 
	 *
	 */
	
	public static function process_theme_fonts( $fonts_styles ){
		$new_font_styles = $fonts_styles;

		$default_font_accent = array(
			'bold' => 0,
          	'italic' => 0,
          	'underline' => 0,
		);
		if(is_object($fonts_styles)){
			foreach ($fonts_styles as $key => $font) {
				if(!isset($font->default->font_accent)){
					$new_font_styles->$key->default->font_accent = $default_font_accent;
				}else if( isset($font->default->font_accent) && is_array($font->default->font_accent) && !sizeof($font->default->font_accent) ){

					$new_font_styles->$key->default->font_accent = $default_font_accent;
				}
				

				// in case the hover state is not set
				if(!isset($font->hover_state)  || (isset($font->hover_state) && is_array($font->hover_state) && !sizeof($font->hover_state)) ){
					$new_font_styles->$key->hover_state = $new_font_styles->$key->default;
				}else if( isset($font->hover_state->font_accent) && is_array($font->hover_state->font_accent) && !sizeof($font->hover_state->font_accent) ){

					$new_font_styles->$key->hover_state->font_accent = $new_font_styles->$key->default->font_accent;
				}

				if(!isset($new_font_styles->$key->hover_state->edit_advanced)){
					$new_font_styles->$key->hover_state->edit_advanced = 0;
				}

				// in case the hover state is not set
				if(!isset($font->active)  || (isset($font->active) && is_array($font->active) && !sizeof($font->active)) ){
					$new_font_styles->$key->active = $new_font_styles->$key->default;
				}else if( isset($font->active->font_accent) && is_array($font->active->font_accent) && !sizeof($font->active->font_accent) ){

					$new_font_styles->$key->active->font_accent = $new_font_styles->$key->default->font_accent;
				}

				if(!isset($new_font_styles->$key->active->edit_advanced)){
					$new_font_styles->$key->active->edit_advanced = 0;
				}
				$new_font_styles->$key->allow_reset = 1;
			}
		}
		

		return $new_font_styles;
	}


	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_head() {



	}

	*/


	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/

   	/*

   	function input_form_data( $args ) {



   	}

   	*/


	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function input_admin_footer() {



	}

	*/


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_enqueue_scripts() {

	}

	*/


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*

	function field_group_admin_head() {

	}

	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function load_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/

	/*

	function update_value( $value, $post_id, $field ) {

		return $value;

	}

	*/


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/

	/*

	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) {

			return $value;

		}


		// apply setting
		if( $field['font_size'] > 12 ) {

			// format the value
			// $value = 'something';

		}


		// return
		return $value;
	}

	*/


	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/

	/*

	function validate_value( $valid, $value, $field, $input ){

		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}


		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-typography'),
		}


		// return
		return $valid;

	}

	*/


	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/

	/*

	function delete_value( $post_id, $key ) {



	}

	*/


	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function load_field( $field ) {

		return $field;

	}

	*/


	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/

	/*

	function update_field( $field ) {

		return $field;

	}

	*/


	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/

	/*

	function delete_field( $field ) {



	}

	*/




}


// initialize
new acf_field_flo_typography( $this->settings );


// class_exists check
endif;

?>
