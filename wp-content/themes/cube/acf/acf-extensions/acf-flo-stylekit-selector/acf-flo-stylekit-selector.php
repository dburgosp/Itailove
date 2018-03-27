<?php

/*
*  ACF Text Field Class
*
*  All the logic for this field type
*
*  @class 		acf_flo_stylekit_selector
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_flo_stylekit_selector') ) :

class acf_flo_stylekit_selector extends acf_field {


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
		$this->name = 'flo_stylekit_selector';
		$this->label = __("Flo Stylekit Selector",'acf');
    $this->category = "layout";


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
		$b = "acf-flo-stylekit-selector";
		$stylekits = Array(
			Array(
				"name" => "Evora",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-1.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
			Array(
				"name" => "Airy",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-2.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
			Array(
				"name" => "Evora",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-1.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
			Array(
				"name" => "Airy",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-2.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
		);

		?>
		<div class="<?php echo $b; ?> <?php echo $b ?>--preview-opesn">
			<div class="<?php echo $b ?>__title">
				Style Kits
			</div>
			<div class="<?php echo $b ?>__subtitle">
				A style kit is a set of colors and font options that determine the style and feel of your website.
	Below we crafted a few style kits for you to chose from. Please note, that applying a new style kit will override the current color and font settings.
			</div>

			<div class="<?php echo $b ?>__stylekits-and-preview">

				<div class="<?php echo $b ?>__stylekits">
					<?php foreach ($stylekits as $stylekit): ?>
						<div class="<?php echo $b ?>__stylekit" data-preview="<?php echo $stylekit["preview"] ?>">
							<div class="<?php echo $b ?>__stylekit-thumb-wrap">
								<div class="<?php echo $b ?>__stylekit-thumb" style="background-image: url(<?php echo $stylekit["thumb"] ?>)"></div>
							</div>
							<div class="<?php echo $b ?>__stylekit-name">
								<?php echo $stylekit["name"] ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="<?php echo $b ?>__right-wrap">
					<div class="<?php echo $b ?>__apply-button flo-admin-button">
						APPLY STYLEKIT
					</div>
					<div class="<?php echo $b ?>__preview-wrap">
						<div class="<?php echo $b ?>__preview-loading">Loading Preview</div>
						<div class="<?php echo $b ?>__preview">
							<div class="<?php echo $b ?>__preview-navigation-box"></div>
						</div>
					</div>
				</div>
			</div>

		</div>
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

	}

  function input_admin_enqueue_scripts() {

    $dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-stylekit-selector/';

    wp_register_script( 'acf-flo-stylekit-selector', "{$dir}acf-flo-stylekit-selector.js" );
    wp_enqueue_script( 'acf-flo-stylekit-selector' );

  }

}


// initialize
if(function_exists('acf_register_field_type')){
  acf_register_field_type( new acf_flo_stylekit_selector() );
}

endif; // class_exists check

?>
