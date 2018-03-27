<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('acf_field_font_style_select') ) :


class acf_field_font_style_select extends acf_field {

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

		$this->name = 'font_style_select';


		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/

		$this->label = __('Font Style Select', 'flotheme');


		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/

		$this->category = 'choice';


		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/

		$this->defaults = array(

		);


		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('font_style_select', 'error');
		*/

		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'flotheme'),
		);


		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/

		$this->settings = $settings;

		add_action( 'admin_enqueue_scripts', array($this,'add_admin_styles' ));

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
			'label'			=> __('Default selectors','flotheme'),
			'instructions'	=> __('Add here the default css selectors.','flotheme'),
			'type'			=> 'text',
			'name'			=> 'default_css_selectors',
			//'prepend'		=> 'px',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Active item selector','flotheme'),
			'instructions'	=> __('If this element has active state, and it has a specific class, add it here. By default active class will be considered ".flo-active" ','flotheme'),
			'type'			=> 'text',
			'name'			=> 'active_css_selectors',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Hover item selector','flotheme'),
			'instructions'	=> __('If this element has hover state, and it has a specific class, add it here. By default hover class will be considered "(default_selector):hover" ','flotheme'),
			'type'			=> 'text',
			'name'			=> 'hover_css_selectors',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Typography option name','flotheme'),
			'instructions'	=> __('Add here the name of the typography option that will be used to populate the dropdown.','flotheme'),
			'type'			=> 'text',
			'name'			=> 'typography_option_name',
			//'prepend'		=> 'px',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Default Typography style','flotheme'),
			'instructions'	=> __('Add here the key name of the default typography styling. This should be taken from $default_theme_fonts array. For example: "flo-header__logo" ','flotheme'),
			'type'			=> 'text',
			'name'			=> 'default_typography_style',
			//'prepend'		=> 'px',
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

		//deb_e($field);
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

//typography_option_name
		if(isset($field['typography_option_name']) && strlen($field['typography_option_name'])){
			$typography_option_name = $field['typography_option_name'];
		}else{
			$theme_typography_option_name = 'flo-typography';
            $theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);
			
			$typography_option_name = $theme_typography_option_name;
		}
		$font_styles_data = get_font_styles_data($typography_option_name);

		self::render_font_style_dropdown($font_styles_data, $field);

		if(isset($field['default_css_selectors']) && strlen($field['default_css_selectors'])){
			$default_css_selectors = __('Default selector is: ','flotheme') . $field['default_css_selectors'];
		}else{
			$default_css_selectors = '';
		}

		if(isset($field['active_css_selectors']) && strlen($field['active_css_selectors'])){
			$active_css_selectors = __('Default selector is: ','flotheme') . $field['active_css_selectors'];
		}else{
			$active_css_selectors = '';
		}

		if(isset($field['hover_css_selectors']) && strlen($field['hover_css_selectors'])){
			$hover_css_selectors = __('Default selector is: ','flotheme') . $field['hover_css_selectors'];
		}else{
			$hover_css_selectors = '';
		}

		if(isset($field['value']['font_style']['css_selector'])){
			$css_selector = esc_attr($field['value']['font_style']['css_selector']);
		}else{
			$css_selector = '';
		}

		if(isset($field['value']['font_style']['active_item_css_selector'])){
			$active_item_css_selector_value = esc_attr($field['value']['font_style']['active_item_css_selector']);
		}else{
			$active_item_css_selector_value = '';
		}

		if(isset($field['value']['font_style']['hover_item_css_selector'])){
			$hover_item_css_selector_value = esc_attr($field['value']['font_style']['hover_item_css_selector']);
		}else{
			$hover_item_css_selector_value = '';
		}


		?>
		<div class="css-selector-block">
			<div class="acf-label">
				<label for="<?php echo $field['ID'] ?>">
					<?php _e('Css selector','flotheme'); ?>
				</label>
			</div>
			<input type="text" id="<?php echo $field['ID'] ?>" name="<?php echo esc_attr($field['name']) ?>[font_style][css_selector]" value="<?php echo $css_selector; ?>" placeholder="<?php echo $default_css_selectors; ?>" />

			<span class="hint">
				<?php
					_e('If you need to overwrite the default css selector for this font style add it in the field above. Leave it blank to use the default value.','flotheme');
					echo '<br/>' . sprintf(__('%s Note! %s Using a custom selector in this field and doing it wrong, will result in wrong font styles for the elements the option is used for.', 'flotheme'),'<b>','</b>');
					echo '<br><br/>';
					if(isset($default_css_selectors)){
						echo sprintf(__(' The defaul css selector is: %s %s %s','flotheme'), '<span class="default-css-selector-hint">', $field['default_css_selectors'],'</span>');
					}
				?>
			</span>
			<hr/>
			<br/><br/>

			<div class="acf-label">
				<label for="<?php echo $field['ID'] ?>_active">
					<?php _e('Active item css selector','flotheme'); ?>
				</label>
			</div>
			<input type="text" id="<?php echo $field['ID'] ?>_active" name="<?php echo esc_attr($field['name']) ?>[font_style][active_item_css_selector]" value="<?php echo $active_item_css_selector_value; ?>" placeholder="<?php echo $active_css_selectors; ?>" />

			<span class="hint">
				<?php
					_e('This selector is necessary only for elements that have an active state, for example the menu item. Leave this field empty if you want to use the default or the element does not need an active state.','flotheme');
					if(isset($active_css_selectors) && strlen($active_css_selectors)){
						echo '<br/><br/>';
						echo sprintf(__(' %s %s %s','flotheme'), '<span class="default-css-selector-hint">', $active_css_selectors,'</span>');
					}
				?>
			</span>
			<hr/>
			<br/><br/>

			<div class="acf-label">
				<label for="<?php echo $field['ID'] ?>_active">
					<?php _e('Hover item css selector','flotheme'); ?>
				</label>
			</div>
			<input type="text" id="<?php echo $field['ID'] ?>_hover" name="<?php echo esc_attr($field['name']) ?>[font_style][hover_item_css_selector]" value="<?php echo $hover_item_css_selector_value; ?>" placeholder="<?php echo $hover_css_selectors; ?>" />

			<span class="hint">
				<?php
					_e('This selector is necessary only for elements that have a hover state, for example the category list. Leave this field empty if you want to use the default or the element does not need a hover state.','flotheme');
					if(isset($hover_css_selectors) && strlen($hover_css_selectors)){
						echo '<br/><br/>';
						echo sprintf(__(' %s %s %s','flotheme'), '<span class="default-css-selector-hint">', $hover_css_selectors,'</span>');
					}
				?>
			</span>
		</div>
		<?php
	}

	/**
	 *
	 * Render the dropdown for selecting a Font Style
	 *
	 */
	static function render_font_style_dropdown($font_styles_data, $field){
 		if(isset($font_styles_data['font_styles_list']) && sizeof($font_styles_data['font_styles_list'])){

 			$typography_option_url = get_admin_url().'admin.php?page=acf-options-8-typography&edit_font_style=';

 			// if necessary overwrite this in the theme in case the URL is different
 			$typography_option_url = apply_filters('flo_custom_typography_option_url',$typography_option_url);

 			$hidden_input_val = '';

 			if(isset($field['value']['font_style']['name'])
 				&& strlen($field['value']['font_style']['name'])

 				// this may not be set when the saved value was removed from options
 				&& isset($font_styles_data['font_styles_list'][$field['value']['font_style']['name']])
 				){
 				$slected_val = "<span class='".$field['value']['font_style']['name']."'>".$font_styles_data['font_styles_list'][$field['value']['font_style']['name']].'</span>';
 				$hidden_input_val = $field['value']['font_style']['name'];

 			}else if( isset($field['default_typography_style']) && strlen(trim($field['default_typography_style'])) ){

 				$slected_val = "<span class='".$field['default_typography_style']."'>".$font_styles_data['font_styles_list'][$field['default_typography_style']].'</span>';

 				$hidden_input_val = $field['default_typography_style'];
 			}else{
 				$slected_val = __('Please select a font style','flotheme');
 			}
 		?>
 		<dl id="sample" class="dropdown font-style-select">
	        <dt>
	        	<div class="selected-style">
	        		<span class="selected-f-style"><?php echo $slected_val; ?></span>
	        		<i class="arrow-down"></i>
	        	</div>
	        	<a href="<?php echo $typography_option_url.$hidden_input_val; ?>" target="_blank" class="edit-current-font-style">
			    	<i class="flo-admin-icon-pen" title="<?php _e('Edit font style','flotheme'); ?>"></i>
			    </a>
	        </dt>
	        <dd>
	            <ul>
 		<?php
      $font_styles_data__asort = $font_styles_data['font_styles_list'];
      asort($font_styles_data__asort);

 			foreach ($font_styles_data__asort as $key => $value) {
 				?>
 				<li >
 					<a>
 						<span class="<?php echo $key ?>" data-value="<?php echo $key ?>"><?php echo $value ?></span>
 					</a>
 				</li>
 				<?php
 			}
 		?>
	 			</ul>
	        </dd>
	    </dl>
	    <input class="selected-font-style" type="hidden" name="<?php echo esc_attr($field['name']) ?>[font_style][name]" value="<?php echo $hidden_input_val; ?>" />
	    <span class=" advanced-font-style-select" ><?php _e('Advanced','flotheme'); ?></span>
	    <span class="closed">+</span>
	    <span class="oppened">-</span>
	    <?php
 		}
 	}

 	/**
 	 *
 	 * Render the css styles for the font styles dropdown items
 	 * Each item should have the proper style to reflect the corresponding sont style
 	 *
 	 */
 	public static function add_admin_styles(){


		//$font_styles_data = $this->font_styles_data;
		$theme_typography_option_name = 'flo-typography';
        $theme_typography_option_name = apply_filters('flo_theme_typography_option_name',$theme_typography_option_name);
			
		$font_styles_data = get_font_styles_data($theme_typography_option_name,$add_bg_color = true);

		//Handle Google fonts enque
		if(isset($font_styles_data['google_font_face']) && is_array($font_styles_data['google_font_face']) && sizeof($font_styles_data['google_font_face'])){
			$family_name = implode('|',$font_styles_data['google_font_face']);
			$family_name = trim(str_replace(' ','+',$family_name));
			$query_args = array(
				'family' => $family_name
				//'subset' => 'latin,latin-ext',
			);
			wp_register_style( 'admin_google_fonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
		    wp_enqueue_style('admin_google_fonts');
		}



		wp_enqueue_style(
			'acf-input-font_style_select_live',
			get_template_directory_uri().'/acf/acf-extensions/acf-font-style-select/assets/css/input.css'
		);

		$custom_css = '';

		//Handle font face for the custom fonts
		if(isset($font_styles_data['custom_font_face'])){
			foreach ($font_styles_data['custom_font_face'] as $key => $font_face) {
				$custom_css .= $font_face;
			}
		}

		//Handle Google fonts enque
		if(isset($font_styles_data['font_style_css'])){
			foreach ($font_styles_data['font_style_css'] as $key => $customcss) {
				// deb_e($key);
				// deb_e($customcss);
				if(isset($customcss['default'])){
					$custom_css .= '.'.$key.' { '.$customcss['default'] .'}';
				}
				if(isset($customcss['hover']) && strlen(trim($customcss['hover']))){
					$custom_css .= '.'.$key.':hover { '.$customcss['hover'].'}';
				}
				if(isset($customcss['active']) && strlen(trim($customcss['active']))){
					$custom_css .= '.'.$key.' .flo-activey {'.$customcss['active'].'}';
				}

			}
		}

		wp_add_inline_style( 'acf-input-font_style_select_live', $custom_css );
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
		$url = get_template_directory_uri().'/acf/acf-extensions/acf-font-style-select/';
		$version = $this->settings['version'];
		// register & include JS
		wp_register_script( 'acf-input-font_style_select', "{$url}assets/js/input.js", array('acf-input'), $version );
		wp_enqueue_script('acf-input-font_style_select');
		// register & include CSS
		// wp_register_style( 'acf-input-font_style_select', "{$url}assets/css/input.css", array('acf-input'), $version );
		// wp_enqueue_style('acf-input-font_style_select');

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


	function load_value( $value, $post_id, $field ) {

		if(is_admin()){
			return $value;
		}else{
			return flo_get_font_style_by_option_value($value, $field);
		}

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
			$valid = __('The value is too little!','acf-font_style_select'),
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
new acf_field_font_style_select( $this->settings );


// class_exists check
endif;

?>
