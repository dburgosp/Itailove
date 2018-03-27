<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_flo_quick_typography') ) :


class acf_field_flo_quick_typography extends acf_field {


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

		$this->name = 'flo_quick_typography';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Flo quick typography', 'flotheme');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'choice';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$theme_typography_option_name = 'flo-typography';
        $theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);
		
		$this->defaults = array(
			'default_font_key'	=> '',
			'typography_option'	=> $theme_typography_option_name,
		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('flo_quick_typography', 'error');
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
			'label'			=> __('Default fonts key - name pair.','flotheme'),
			'instructions'	=> __('Add one per line.Ex: font_1:Font 1','flotheme'),
			'type'			=> 'textarea',
			'name'			=> 'default_font_key',

		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Flo Typography option name','flotheme'),
			'instructions'	=> __('Default value is: flo-typography of the theme does not overwrite that with a filter','flotheme'),
			'type'			=> 'text',
			'name'			=> 'typography_option',

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

		$theme_default_fonts_array = array();
		if(function_exists('flo_default_fonts_sets')){
			$theme_default_fonts_array = flo_default_fonts_sets();
		}

		// we will store the current value in a hidden text are to be able to compare which fonts were changed
		$old_value = stripslashes_deep( $field['value'] );

		if(isset($field['value']['quick_font']) && is_object(json_decode($field['value']['quick_font']) ) ){
			$quick_font = self::rebuild_quick_font($field['value']['quick_font'], $field);

			// flag to know if we are dealing with existing data or the field is new(neer saved yet)
			$new_data = false;

		}else{
			$quick_font = $theme_default_fonts_array;

			// flag to know if we are dealing with existing data or the field is new(neer saved yet)
			$new_data = true;

		}

		wp_localize_script( 'flo-angular-fontselect', 'flo_google_fonts', $google_fonts);

		wp_localize_script( 'acf-input-flo_quick_typography', 'quick_font', $quick_font);
		wp_localize_script( 'acf-input-flo_quick_typography', 'old_value', $old_value);


		//$default_fonts = self::get_default_fonts($field['default_font_key']);

		$default_fonts = self::get_default_fonts_from_file();
//deb_e($quick_font);
		?>
		<div ng-asspp="floQuickFonts"  class="flo-quick-fonts-container">
			<div ng-controller="floQuickFontsCtrl" class="acf-fields -left">
				<?php if (sizeof($default_fonts)): ?>
					<?php foreach ($default_fonts as $font_key => $font_label):

						if(is_array($quick_font)){
							$quick_font_select[$font_key] = $quick_font[$font_key]['font_family'];
						}else if(is_object($quick_font)){
							$quick_font_select[$font_key] = $quick_font->$font_key->font_family;
						}

					?>
						<div class="acf-field group_google_font <?php echo $font_key ?>">  <!-- don't change this class -->
							<!-- Google fonts dropdown -->
							<div class="acf-label font-label">
                <label for="">
								  <?php echo $font_label; ?>
                </label>
							</div>

              <div class="acf-input acf-input--quick-typography">

                <!-- Start: Current Style -->
                  <div class="flo-acf-label">
                    Current Font:
                  </div>
                  <section class="content mw" style="font-family: {{google_font_<?php echo $font_key ?>}};">
                      <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt.</p>
                  </section>
                <!-- End: Current Style -->

                <!-- Start: Existing Fonts -->
                  <div class="flo-acf-label">
                    Change to an existing Font:
                  </div>
    							<?php
                  	echo self::render_fonts_dropdown( $font_key );
                  ?>
                <!-- End: Existing Fonts -->

                <?php 
                	// flag that tells us if the quick typography field is used on the same page
                	// with  the typography field. We need it to avoid conflict between quick typography and
                	// typography field when on the same page
                	$on_same_page_with_typography = true;

                	// filter to change that from  a theme if necessary
                	$on_same_page_with_typography = apply_filters('flo_quick_typography_adn_typography_on_same_page',$on_same_page_with_typography);
                ?>

                <!-- Start: Google Fonts -->
                <?php if (!$on_same_page_with_typography): ?>
                	<div class="flo-acf-label">
                    	Change to a Google Font:
                  	</div>
    				<div class="fontselect">
                    	<jd-fontselect stack="google_font_<?php echo $font_key ?>"></jd-fontselect>
                  	</div>
                  	<?php
                  		echo self::render_field_fontuploader( $field , $font_key);
                  	?>
                <?php endif ?>

                  <input type="hidden" value="{{google_font_<?php echo $font_key ?>}}" data-key="<?php echo $font_key ?>" class="quick-font-unit <?php echo $font_key ?>" name=<?php echo  $field['name'] ?>[<?php echo $font_key ?>]>
                <!-- End: Google Fonts -->


              </div>
						</div>
					<?php endforeach ?>
				<?php endif ?>
				<!-- we will use the field key name for the model name to store the data -->
				<textarea style="display: none;"  name="<?php echo  $field['name'] ?>[quick_font]"  style=" /*display:none!important*/ ">{{quick_font}}</textarea>
				<textarea style="display: none;" name="<?php echo  $field['name'] ?>[old_value]"  style=" /*display:none!important*/ ">{{old_value}}</textarea>
				<style class="font-face-style"></style>
			</div>
		</div>

		<?php

		wp_localize_script( 'acf-input-flo_quick_typography', 'quick_font_select', $quick_font_select);

	}

	static function render_fonts_dropdown($font_key){

		add_filter('acf/settings/current_language', 'flo_acf_set_language_to_default', 100); // wpml support
		$options_acf_fields = get_field_objects('options'); // acf fields used on option page
		remove_filter('acf/settings/current_language', 'flo_acf_set_language_to_default', 100); // remove the wpml filter to not affect other options
//deb_e($options_acf_fields); die();
		// iterate throught all acf fields to get only those of 'typography' type

		foreach ($options_acf_fields as $field_key => $field_data) {

			// if the field type is ' typography' and its name starts with 'flo-'
			if('typography' == $field_data['type'] &&  floStartsWith($field_data['_name'], 'flo-')){

				// retrieve all fonts from the typography option
				$all_fonts = acf_field_flo_typography::get_all_fonts($field_data['value']);

				// an array with all the fonts. will be used to populate the dropdown
				$all_fonts_list = $all_fonts['fonts_list'];
				$all_fonts_groups = $all_fonts['fonts_list_groups'];

				if(isset($all_fonts_groups['custom_font']) && isset($all_fonts_groups['default_theme_font']) ){
					// if we have both cuatom fonts uploaded by the user and custom fonts built in the theme
					// we merge these 2 arrays
					$all_custom_fonts = array_merge($all_fonts_groups['custom_font'],$all_fonts_groups['default_theme_font'] );
				}else if( isset($all_fonts_groups['custom_font']) ){
					// if only custom fonts uploaded by the user are available
					$all_custom_fonts = $all_fonts_groups['custom_font'];
				}else if( isset($all_fonts_groups['default_theme_font']) ){
					// if only custom fonts built in the theme are available
					$all_custom_fonts = $all_fonts_groups['default_theme_font'];
				}

				//$all_custom_fonts - will be used to render the font faces for the custom fonts


				//localize this to be used to populate the dropdown
				wp_localize_script( 'acf-input-flo_quick_typography', 'all_quick_fonts_list', $all_fonts_list);
				break;
			}
		}

		// we need to render the font face for all added custom fonts even if they are not used yet in the font styles.
		if(isset($all_custom_fonts)){

			$font_face = '';

			foreach ($all_custom_fonts as $key => $custom_font) {
				$font_face .= self::get_custom_font_face( $custom_font);
			}

			if(strlen($font_face)){
				?>
				<style>
					<?php echo $font_face ?>
				</style>
				<?php
			}

		}

		if(isset($all_fonts_list)){
		?>
    	<div class="font-style-modal__field">
          <div class="font-style-modal__input">
            <select class="quick_font_select_<?php echo $font_key; ?>" ng-model="quick_font_select.<?php echo $font_key; ?>" ng-change="floQuickFontChange('<?php echo $font_key; ?>')">
              <option ng-repeat="(key, value) in fonts_list_quick" value="{{key}}" data-font_type="{{value.font_type}}" data-font_info="{{value.font_info}}">{{key}}</option>
            </select>
          </div>
      </div>
		<?php
		} // end if

	}

	/**
	 *
	 * Generate the font face styles for the given font style
	 * @param font_style - array
	 *
	 * @return - string
	 */
	static function get_custom_font_face($font_style){


			$fontface = '';
			$fontface .= '@font-face {';
			$fontface .= 'font-family: "'.$font_style['font_family'].'"; ';

			$lines = array();
			$eot = '';

			$hash = 'qwef8580e913efe41ca7d40';

			//if the export URL is from a localhost, or from a different server
			// we will use this to replace the font URL to avoid errors
			$floexporturl = get_option( 'floexporturl', $hash );
			foreach ($font_style['font_info'] as $key => $value) {
				$line = '';


				if($floexporturl != $hash){
					// replace the URL to make sure we are loading the font from the current server
					$value['url'] = str_replace($floexporturl, get_template_directory_uri(), $value['url']);
				}
				switch ($value['format']) {

					case 'otf':
				        $line = "url('" . $value['url'] . "') format('opentype')";
				        break;
				    case 'svg':
				        $line = "url('" . $value['url'] . "') format('svg')";
				        break;
			        case 'ttf':
				        $line = "url('" . $value['url'] . "') format('truetype')";
				        break;
			        case 'woff':
				        $line = "url('" . $value['url'] . "') format('woff')";
				        break;
			        case 'woff2':
				        $line = "url('" . $$value['url'] . "') format('woff2')";
				        break;
				    case 'eot':
				        $line = "url('" . $value['url'] . "?#iefix') format('embedded-opentype')";
				        $eot = "url('" . $value['url'] . "');"; /* IE9 Compat Modes */
				        break;

				}
				$lines[] = $line;
			}

			if(strlen($eot)){
				$fontface .= "src: ".$eot; // the eot font should be first
			}

			if(sizeof($lines)){
				$fontface .= "src: ".implode(",",$lines).";";
			}

			$fontface .= "}";

			return $fontface;
		}


	/**
	 *
	 * Update the quick font object if necessary.
	 * @params
	 * @raw_quick_font - string , a json encoded object
	 * return object - updated quick_font font
	 *
	 */
	static function rebuild_quick_font($raw_quick_font, $field){

		// in the DB we save something like that: http://pastebin.com/TDveecbH
		// therefore we need to iterate through the quick_font and update the key where we should have s google font
		$quick_font = json_decode($raw_quick_font);


		$result = new stdClass();

		foreach ((array)$quick_font as $key => $font) {
// var_dump($key);
// deb_e($field);
			//consider only the quick fonts which are different from the true fonts faces
			if( isset($field['value'][$key]) &&  trim($font->font_family) != trim($field['value'][$key]) ){


				// make sure it is a goole font (the custom fonts are updated when they are uploaded, so we do not have to worry about that)
				if(strpos($field['value'][$key],'google') !== false){ // if this is not a google font

					// init an object that  will store the info about the true Google font
					$this_font = new stdClass();
					$this_font->font_family = $field['value'][$key]; // add the font family name
					$this_font->font_type = 'google_font'; // font type

					// font url info
					$this_font->font_url = array(
							'url' => '',
							'format' => '',
							'name' => $field['value'][$key]
						);

					// replate the outdated quick font
					$quick_font->$key = $this_font;
				}

			}
		}

		return $quick_font;
	}


	/**
	 * Renders Font Uploader field
	 *
	 * @param  array
	 * @param  string
	 * @return string
	 */
	public static function render_field_fontuploader( $field , $key) {
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

		<span class="flo-font-button" ng-click="floQuickUploadFont('<?php echo $key; ?>')" > <?php _e('ADD CUSTOM FONT','flotheme'); ?> </span>

		<script>

			var fpt_upload_font = <?php echo isset($field['value']['custom_fonts'])? $field['value']['custom_fonts'] : '[]'; ?>;
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

		// vars
		$url = get_template_directory_uri().'/acf/acf-extensions/acf-flo-quick-typography/';
		$version = $this->settings['version'];


		// register & include JS
		wp_register_script( 'acf-input-flo_quick_typography', "{$url}assets/js/input.js", array('acf-input'), $version, $in_footer = true );
		wp_enqueue_script('acf-input-flo_quick_typography');


		// register & include CSS
		wp_register_style( 'acf-input-flo_quick_typography', "{$url}assets/css/input.css", array('acf-input'), $version );
		wp_enqueue_style('acf-input-flo_quick_typography');

	}

	/**
	 *
	 * Process a multiline text and return an array.
	 * @params
	 * @text string - in the following format:
	 * font_1:Font 1
		font_2:Font 2
		font_3:Font 3
		font_4:Font 4
	 *
	 *	@return - array
	 */
	static function get_default_fonts($text){
		$result = array();
		// break text by new line
		$lines = explode(PHP_EOL,$text);
		if(is_array($lines) && sizeof($lines)){
			foreach ($lines as $key => $value) {
				$line = explode(':',$value);
				if(is_array($line) && sizeof($line) && isset($line[0]) && isset($line[1])){
					$result[$line[0]] = $line[1];
				}

			}
		}
		return $result;
	}


	/**
	 *
	 * Get the default fonts defined in
	 * ../theme-files/typography/typography__font-families.php
	 */
	static function get_default_fonts_from_file(){

		
		$current_stylekit = flo_get_current_style_kit();
		if(file_exists(dirname(__FILE__)."/../../../../theme-files/flo_stylekits_config/".$current_stylekit."/typography__font-families.php")){
			include(dirname(__FILE__)."/../../../../theme-files/flo_stylekits_config/".$current_stylekit."/typography__font-families.php");
//deb_e($default_font_faces);
		}else{
			include(dirname(__FILE__)."/../../../../theme-files/typography/typography__font-families.php");
		}
		//var_dump($default_font_faces);
		// the file included above contains and array - $default_font_faces which defines the default font faces
		$result = array();

		$counter = 1;
		foreach ($default_font_faces as $key => $value) {
			$result[$key] = __('Font','flotheme').' '.$counter;
			$counter++;
		}

		return $result;
	}
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



	function update_value( $value, $post_id, $field ) {
		//  deb_e(json_decode(stripslashes($value)));
		// // deb_e($post_id);
		//  deb_e($field);
		// deb_e($_POST);
		//deb_e( json_decode(stripslashes($_POST['acf'][$field['key']]['quick_font'])) );
		//deb_e( stripslashes_deep($_POST['acf'][$field['key']]) );


		// load all the fonts into an array
		$current_quick_fonts = array();
		if(isset($_POST['acf'][$field['key']])){
			// iterate through the post data that contains info about the each font
			foreach ($_POST['acf'][$field['key']] as $post_data_key => $post_data) {
				if($post_data_key != 'quick_font'){ // we are interested only in simple font names
					$current_quick_fonts[] = stripslashes($post_data);
				}
			}

			// now we will load from the options the $flo_typography data
			$flo_typography = get_field($field['typography_option'],'options');


			// get the previous saved data
			$old_value = json_decode(stripslashes($_POST['acf'][$field['key']]['old_value']));

			// now we will compare the old value with the new value

			$changed_fonts = array(); // collection of the font names that were changed
			$old_new_fonts_pairs = array(); // here we will

			if($old_value !== NULL){
				foreach ($old_value as $val_key => $font_name) {
					if( ( isset($_POST['acf'][$field['key']][$val_key]) && $font_name != $_POST['acf'][$field['key']][$val_key] )
							&& $val_key != 'quick_font' && $val_key != 'old_value'){ // if the old font is different than the new one
						
						$changed_fonts[] = $font_name; //

						$old_new_fonts_pairs[$font_name] = stripslashes_deep($_POST['acf'][$field['key']][$val_key]);
					}
				}
			}else{
				$theme_default_fonts_array = flo_default_fonts_sets();
				foreach ($theme_default_fonts_array as $default_f_key => $default_font_value) {
					$changed_fonts[] = $default_font_value['font_family'];

					$old_new_fonts_pairs[$default_font_value['font_family']] = $_POST['acf'][$field['key']][$default_f_key];
				}
			}
//deb_e($old_new_fonts_pairs);
//deb_e(json_decode(json_encode(acf_field_flo_typography::get_theme_default_fonts_list())));

// $font_styles_data = get_font_styles_data($field['typography_option']);
// deb_e($font_styles_data);


			// the option may not exist if we are using the quick fonts options before the advanced options
			if( !isset($flo_typography['font_styles']) ){
				$theme_default_fonts_list = apply_filters( 'flo_set_default_theme_fonts', array() );

				if(is_array($theme_default_fonts_list) && sizeof($theme_default_fonts_list)){
					$flo_typography['font_styles']->theme_defaults = json_encode($theme_default_fonts_list);
				}

			}

			if(isset($flo_typography['font_styles'])){
				if(is_string($flo_typography['font_styles'])){
					$font_styles_data = json_decode($flo_typography['font_styles']);
					//$font_styles_data = (object)$font_styles_data;
				}else{
					$font_styles_data = $flo_typography['font_styles'];
				}

				if(is_object($font_styles_data) ){
					$theme_defaults = array();
					$user_specified = array();

					if(isset($font_styles_data->theme_defaults)){
						$theme_defaults = $font_styles_data->theme_defaults;

						if(is_string($theme_defaults)){
							$theme_defaults = json_decode($theme_defaults);
						}
//deb_e($theme_defaults);
						$theme_defaults_modified = self::maybe_change_fonts($theme_defaults, $changed_fonts, $old_new_fonts_pairs, $_POST['acf'][$field['key']]['quick_font']);

//deb_e($theme_defaults_modified);

						if(is_string($theme_defaults_modified)){
							$theme_defaults_modified = json_decode($theme_defaults_modified);
						}

//deb_e($theme_defaults_modified);
						//update the fonts
						$font_styles_data->theme_defaults = $theme_defaults_modified;
					}
					if(isset($font_styles_data->user_specified)){
						$user_specified = $font_styles_data->user_specified;

						$user_specified_modified = self::maybe_change_fonts($user_specified, $changed_fonts, $old_new_fonts_pairs, $_POST['acf'][$field['key']]['quick_font']);

						//update the fonts
						$font_styles_data->user_specified = $user_specified_modified;
					}
//deb_e(($font_styles_data)); die();
					$flo_typography['font_styles'] = json_encode($font_styles_data);

					$fonts_to_update = self::get_new_fonts($old_new_fonts_pairs, $_POST['acf'][$field['key']], $flo_typography);

					if(sizeof($fonts_to_update['new_google_fonts'])){
						//$flo_typography[];
						if(isset($flo_typography['g_fonts'])){
							$flo_typography['g_fonts'] = array_merge($flo_typography['g_fonts'],$fonts_to_update['new_google_fonts']);
						}else{
							$flo_typography['g_fonts'] = $fonts_to_update['new_google_fonts'];
						}
					}

// deb_e($fonts_to_update);
// deb_e($flo_typography);
// var_dump(json_decode($flo_typography['custom_fonts']) );
					if(sizeof($fonts_to_update['new_custom_fonts'])){
//						deb_e(1);
						if(isset($flo_typography['custom_fonts']) && is_array(json_decode($flo_typography['custom_fonts'])) && sizeof(json_decode($flo_typography['custom_fonts']) ) ){

//							deb_e(22);
							$flo_typography['custom_fonts'] = array_merge(json_decode($flo_typography['custom_fonts']),$fonts_to_update['new_custom_fonts']);
						}else{
//							deb_e(333);
							$flo_typography['custom_fonts'] = $fonts_to_update['new_custom_fonts'];
						}
					}

//deb_e($flo_typography); die();
					//acf_update_option('options_'.$field['typography_option'],$flo_typography);
					update_option('options_'.$field['typography_option'],$flo_typography);
				}


			}
			//deb_e($old_value);
		}
 // deb_e($flo_typography);
 // 		die();
		return $value;

	}

	static function get_new_fonts($old_new_fonts_pairs, $post_data,$flo_typography){
		// deb_e($old_new_fonts_pairs);
		// deb_e(stripslashes_deep($post_data));
		// deb_e($flo_typography);
		// deb_e(json_decode($flo_typography['custom_fonts']));

		if(isset($flo_typography['g_fonts'])){
			$existing_g_fonts = $flo_typography['g_fonts'];
		}else{
			$existing_g_fonts = array();
		}

		if(isset($flo_typography['custom_fonts'])){
			if(!is_array($flo_typography['custom_fonts'])){
				$existing_custom_fonts = json_decode($flo_typography['custom_fonts']);
			}else{
				$existing_custom_fonts = $flo_typography['custom_fonts'];
			}

		}else{
			$existing_custom_fonts = array();
		}

		$new_google_fonts = array();
		$new_custom_fonts = array();


		foreach ($old_new_fonts_pairs as $key => $new_font_name) {

			// if the font did not change, we stop here
			if($key == $new_font_name){
				continue;
			}

			// if this is a google font
			if(strpos($new_font_name,'google') !== false && !in_array($new_font_name, $existing_g_fonts)){
				$k = mt_rand(1,10000);
				$new_google_fonts['google_font_'.$k] = stripslashes($new_font_name);
			}else{

				// the post data is in a format like:
				// array(
				// 		[font_1] => "Amiri", serif, "google"
				// 	    [font_2] => Brandon_blk
				// 	    [font_3] => Brandon_bld_it_0
				// 		[quick_font] => {"font_1":{"font_family":"\"Abril Fatface\", fantasy, \"google\"","font_type":"google_font","font_url":{"url":"","format":"","name":"\"Abril Fatface\", fantasy, \"google\""}},"font_2":{"font_type":"custom_font","font_family":"Brandon_blk","font_url":{"0":{"url":"http://localhost/flo/newredux/wp-content/uploads/sites/83/2016/10/Brandon_blk.otf","name":"Brandon_blk","format":"otf"}}},"font_3":{"font_type":"custom_font","font_family":"Brandon_bld_it_0","font_url":{"0":{"url":"http://localhost/flo/newredux/wp-content/uploads/sites/83/2016/10/Brandon_bld_it_0.ttf","name":"Brandon_bld_it_0","format":"ttf"},"1":{"name":"Brandon_bld_it_0","format":"otf","url":"http://localhost/flo/newredux/wp-content/uploads/sites/83/2016/10/Brandon_bld_it_0.otf"}}}}

				// 	)
				// and we need to find the key corresponding to the current font, to use it later to find
				// the info about the custom font in $_POST...[quick_font]
				$post_font_key = array_search($new_font_name, $post_data);

				if($post_font_key !== false && isset($post_data['quick_font'])){
					$current_quick_font = json_decode(stripslashes_deep($post_data['quick_font']));
					if(isset($current_quick_font->$post_font_key->font_url)){
						foreach ($current_quick_font->$post_font_key->font_url as $custom_url_key => $custom_url) {


							if(!in_array($custom_url,$existing_custom_fonts )){
								$new_custom_fonts[] = $custom_url;
							}

						}

					}
				}
			}
		}

		$result = array(
			'new_google_fonts' => $new_google_fonts,
			'new_custom_fonts' => $new_custom_fonts
			);


		return $result;

	}
	static function maybe_change_fonts($font_styles, $changed_fonts, $old_new_fonts_pairs, $quick_fonts){

		$quick_fonts = self::change_quick_fonts_keys(json_decode(stripslashes_deep($quick_fonts)));
		// deb_e($font_styles);
		// deb_e($quick_fonts);
		// deb_e($changed_fonts); die();

		foreach ($font_styles as $key => $current_font) {

			if( in_array($current_font->font_family, $changed_fonts) ){

				//change the font family

				$new_font_family = $old_new_fonts_pairs[$current_font->font_family];
				$font_styles->$key->font_family = stripslashes($new_font_family);

				// if this is a google font

				//if(strpos($current_font->font_family,'google') !== false){
				if(strpos($new_font_family,'google') !== false){

					$font_styles->$key->font_type = 'google_font';
					$this_font = new stdClass();

					// font family comes some times in a form like: "\"Alex Brush\", cursive, \"google\"",
					// and we want to use only the first name - i.e. Alex Brush in this case
					$new_font_family_array = explode(',', stripslashes($new_font_family) );
					if(isset($new_font_family_array[0])){
						$clean_google_font_name = $new_font_family_array[0];
					}else{
						$clean_google_font_name = stripslashes($new_font_family);
					}

					$this_font = array(
							'url' => '',
							'format' => '',
							'name' => $clean_google_font_name
						);

					$font_styles->$key->font_url = $this_font;
				}else{

					$font_styles->$key->font_type = 'custom_font';
					$q_font_key = $current_font->font_family;
					if(isset($quick_fonts->$q_font_key->font_url)){
						$new_font_url = $quick_fonts->$q_font_key->font_url;
						$font_styles->$key->font_url = $new_font_url;
					}else{
						$font_styles->$key->font_url = '';
					}



				}

			}

		}

//deb_e($font_styles);
		return $font_styles;

	}

	/**
	 *
	 * The Quick fonts object has a structure llike this: http://pastebin.com/pEBDqXhL
	 * We need to change its main key in order to be able to access the data easier.
	 * We will use the fonts name as keys
	 *
	 */
	static function change_quick_fonts_keys($quick_fonts){
		$new_quick_fonts = new stdClass();

		foreach ($quick_fonts as $key => $value) {
			$q = $value->font_family;
			$new_quick_fonts->$q = $value;
		}

		return $new_quick_fonts;
	}


}


// initialize
new acf_field_flo_quick_typography( $this->settings );


// class_exists check
endif;

?>
