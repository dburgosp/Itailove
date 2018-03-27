<?php

/*
*  ACF Text Field Class
*
*  All the logic for this field type
*
*  @class 		acf_flo_template_selector
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_flo_template_selector') ) :

class acf_flo_template_selector extends acf_field {


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

	function __construct() {

		// vars
		$this->name = 'flo_template_selector';
		$this->label = __("Flo Template Selector",'acf');
    $this->category = "layout";
		$this->defaults = array(
      'flexible_content_field_name' => "layout",
      'title' => "Choose a Layout Template",
      'subtitle' => "Note: all the blocks from the current layout will be replaced with the ones from the selected template.",
			'default_value'	=> '',
			'maxlength'		=> '',
			'placeholder'	=> '',
			'prepend'		=> '',
			'append'		=> ''
		);


		// do not delete!
    	parent::__construct();
	}


	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field( $field ) {
    $templates = Array();

    $optimize_for_json_block_fetching_class = ( isset($field["optimize_for_json_block_fetching"]) &&  $field["optimize_for_json_block_fetching"]) ? "acf-flo-template-selector-popup--optimized-for-json-block-fetching" : "";

    // check if child theme is installed and active
    $all_templates = glob(get_template_directory() . "/theme-files/flo_layout_templates/*.template.php");
    if ( get_template_directory() !== get_stylesheet_directory() ){
      if(file_exists( get_stylesheet_directory() . "/theme-files/flo_layout_templates") && count(glob(get_stylesheet_directory() . "/theme-files/flo_layout_templates/*.template.php") ) > 0 ){
        $child_theme_all_templates = glob(get_stylesheet_directory() . "/theme-files/flo_layout_templates/*.template.php");
        $all_templates = array_merge($all_templates, $child_theme_all_templates);
      }

    }
    // Start: Add all templates to array
    foreach ($all_templates as $template_file){
      include $template_file;
    }
    // End: Add all templates to array

    if (!function_exists("render_thumbs")) {
      function render_thumbs() {
        global $templates;

        $screen = get_current_screen();
        ?>
        <div class="acf-flo-template-selector-thumbs__wrap">
          <!-- store the screen post type value in a hidden input to use it later -->
          <input type="hidden" class="flo-screen-post-type" value="<?php echo $screen->post_type; ?>">
        <?php
          foreach ($templates as $template) {
            // Start: Show if
              $show_if = [];
              if (isset($template["show_if"]) && is_array($template["show_if"])) {
                $show_if = $template["show_if"];
              }
              $show_if = htmlentities(json_encode($show_if));
            // End: Show if

            // Start: Hide if
              $hide_if = [];
              if (isset($template["hide_if"]) && is_array($template["hide_if"])) {
                $hide_if = $template["hide_if"];
              }
              $hide_if = htmlentities(json_encode($hide_if));
            // End: Hide if

            // Start: Default if
              $default_if = "false";

              if (isset($template["default_if"])) {
                $default_if = $template["default_if"];
              }
            // End: Default if
          ?>
            <div
              class="acf-flo-template-selector-thumbs__thumb"
              data-show-if="<?php echo $show_if ?>"
              data-hide-if="<?php echo $hide_if ?>"
              data-default-if="<?php echo $default_if ?>"
              data-template-name="<?php echo $template["name"] ?>"
              data-template-preview="<?php echo $template["preview_url"] ?>"
              data-template-blocks='<?php echo json_encode($template["blocks"]) ?>'
              <?php if ($template['name']=='post-type-a'){ ?>
                data-template-default-blocks='<?php echo json_encode($template["blocks_default"]) ?>'
              <?php } ?>
            >
              <div class="acf-flo-template-selector-thumbs__thumb-image" style="background-image: url(<?php echo $template["thumb_url"] ?>)"></div>
              <div class="acf-flo-template-selector-thumbs__thumb-title flo-font-style-h4">
                <?php echo $template["title"] ?>
              </div>
            </div>
          <?php
          }
        ?>
        </div>
        <?php
      }
    }

    ?>
      <!-- Start: Thumbs Grid -->

      <!-- End: Thumbs Grid -->

      <!-- Start: Popup -->
        <div class="acf-flo-template-selector-popup <?php echo $optimize_for_json_block_fetching_class ?>" data-target-flexible-content-name="<?php echo $field["flexible_content_field_name"] ?>">

          <div class="acf-flo-template-selector-popup__content">

            <div class="flo-admin-popup__section acf-flo-template-selector-popup__section">

              <div class=" acf-flo-template-selector-popup__sides">
                <div class="acf-flo-template-selector-popup__left-side">
                  <div class="flo-admin-popup__section-title-area acf-flo-template-selector-popup__section-title-area">
                    <div class="flo-admin-popup__section-title">
                      <?php echo $field["title"]; ?>
                    </div>
                    <div class="flo-admin-popup__section-subtitle">
                      <?php echo $field["subtitle"]; ?>
                    </div>
                  </div>
                  <?php render_thumbs() ?>
                </div>
                <div class="acf-flo-template-selector-popup__right-side">
                  <div class="acf-flo-template-selector-popup__top-wrap">
                    <div class="flo-admin-button acf-flo-template-selector-popup__button-apply" >
                      Apply Template
                    </div>
                  </div>
                  <div class="acf-flo-template-selector-popup__preview">
                    <div class="acf-flo-template-selector-popup__preview-nav-bounding-box"></div>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>
      <!-- End: Popup -->

    <?php
	}


	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @param	$field	- an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field_settings( $field ) {

		acf_render_field_setting( $field, array(
			'label'			=> __('Title','acf'),
			'type'			=> 'text',
			'name'			=> 'title',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Subtitle','acf'),
			'type'			=> 'text',
			'name'			=> 'subtitle',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Target Flexible Content Field Name','acf'),
			'instructions'	=> __('The one on which templates will be applied.','acf'),
			'type'			=> 'text',
			'name'			=> 'flexible_content_field_name',
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Optimize for JSON block fetching','acf'),
			'type'			=> 'true_false',
			'name'			=> 'optimize_for_json_block_fetching',
		));

	}

  function input_admin_enqueue_scripts() {

    $dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-template-selector/';

    wp_register_script( 'acf-flo-template-selector', "{$dir}acf-flo-template-selector.js" );
    wp_enqueue_script( 'acf-flo-template-selector' );

  }

}


// initialize
if(function_exists('acf_register_field_type')){
  acf_register_field_type( new acf_flo_template_selector() );
}

endif; // class_exists check

?>
