<?php

/*
*  ACF Image Field Class
*
*  All the logic for this field type
*
*  @class 		acf_field_image
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_flo_image') ) :

class acf_flo_image extends acf_field {

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
		$this->name = 'flo-image';
		$this->label = __("Flo Image",'acf');
		$this->category = 'content';
		$this->defaults = array(
			'return_format'	=> 'array',
			'preview_size'	=> 'thumbnail',
			'library'		=> 'all',
			'min_width'		=> 0,
			'min_height'	=> 0,
			'min_size'		=> 0,
			'max_width'		=> 0,
			'max_height'	=> 0,
			'max_size'		=> 0,
			'mime_types'	=> ''
		);
		$this->l10n = array(
			'select'		=> __("Select Image",'acf'),
			'edit'			=> __("Edit Image",'acf'),
			'update'		=> __("Update Image",'acf'),
			'uploadedTo'	=> __("Uploaded to this post",'acf'),
			'all'			=> __("All images",'acf'),
		);


		// filters
		add_filter('get_media_item_args',				array($this, 'get_media_item_args'));
		add_filter('wp_prepare_attachment_for_js',		array($this, 'wp_prepare_attachment_for_js'), 10, 3);


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

		// vars
		$uploader = acf_get_setting('uploader');


		// enqueue
		if( $uploader == 'wp' ) {

			acf_enqueue_uploader();

		}


		// vars
		$url = '';
    	$url_full = "";
		$alt = '';
		$div = array(
			'class'					=> 'acf-image-uploader acf-cf',
			'data-preview_size'		=> $field['preview_size'],
			'data-library'			=> $field['library'],
			'data-mime_types'		=> $field['mime_types'],
			'data-uploader'			=> $uploader
		);

//deb_e($field);
//die();
		if(isset($field['value']) && is_numeric($field['value'])){
			// backward compatibility when the field was a stadard ACF image field
			$img_id = $field['value'];
		}else if(isset($field['value']['img_id'])){
			$img_id = $field['value']['img_id'];
		}else{
			$img_id = '';
		}

		if(isset($field['value']['crop_position']) && floatval($field['value']['crop_position']) > 0 ){
			$crop_position = $field['value']['crop_position'];
		}else{
			$crop_position = '50';
		}

		// has value?
		if( isset($img_id) && is_numeric($img_id)  ) {

			// update vars
			$url = wp_get_attachment_image_src($img_id, $field['preview_size']);
      		$url_full = wp_get_attachment_image_src($img_id, "large");
			$url_full = isset($url_full) && $url_full ? $url_full[0] : "";
			$alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);


			// url exists
			if( $url ) $url = $url[0];


			// url exists
			if( $url ) {

				$div['class'] .= ' has-value';

			}

		}


		// get size of preview value
		$size = acf_get_image_size($field['preview_size']);

    	$dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-image/';

?>

<div <?php acf_esc_attr_e( $div ); ?>>
  <div class="acf-flo-image-modal">
    <div class="acf-flo-image-modal__background"></div>
    <div class="acf-flo-image-modal__content">
      <div class="acf-flo-image-modal__close">
        <i class="flo-admin-icon-close"></i>
      </div>

      <!-- START: IMAGE POSITION -->
        <div class="acf-flo-image-modal__section">
          <div class="acf-flo-image-modal__section-title-area">

            <div class="acf-flo-image-modal__section-title">
              Image Position on Phones
            </div>
            <div class="acf-flo-image-modal__section-subtitle">Drag the focal area below to set the desired image position</div>
          </div>
          <div class="acf-flo-image-modal__section-content">

            <div class="acf-flo-image-positioner">

              <div class="acf-flo-image-positioner__content">
                <img class="acf-flo-image-positioner__image" data-src="<?php echo $url_full; ?>" data-crop-position="<?php echo $crop_position; ?>">
                <div class="acf-flo-image-positioner__container-area">
                  <div class="acf-flo-image-positioner__container-area-aim">

                  </div>
                </div>

              </div>


            </div>

          </div>
        </div>
      <!-- END: IMAGE POSITION -->

    </div>
  </div>

	<div class="acf-hidden">
		<input type="text" name="<?php echo esc_attr($field['name']) ?>[img_id]" value="<?php echo esc_attr($img_id) ?>" class='acf-flo-image__input-image-id' />
		<input type="text" name="<?php echo esc_attr($field['name']) ?>[crop_position]" value="<?php echo esc_attr($crop_position) ?>" class='acf-flo-image__input-positioner-position' />
	
		
	</div>
	<div class="view show-if-value acf-soh" <?php if( $size['width'] ) echo 'style="max-width: '.$size['width'].'px"'; ?>>
		<img data-name="image" src="<?php echo $url; ?>" alt="<?php echo $alt; ?>"/>
		<ul class="acf-hl acf-soh-target">
      <li>
        <a href="#" class="acf-icon dark" data-name="open_modal" title='Image Position on Phones'>
          <i class="flo-admin-icon-drag"></i>
        </a>
      </li>
			<?php if( $uploader != 'basic' ): ?>
				<li>
          <a class="acf-icon -pencil dark" data-name="edit" href="#" title="<?php _e('Edit', 'acf'); ?>"></a>
        </li>
			<?php endif; ?>
			<li>
        <a class="acf-icon -cancel dark" data-name="remove" href="#" title="<?php _e('Remove', 'acf'); ?>"></a>
      </li>
		</ul>
	</div>

	<div class="view hide-if-value">
		<?php if( $uploader == 'basic' ): ?>

			<?php if( $field['value'] && !is_numeric($field['value']) ): ?>
				<div class="acf-error-message"><p><?php echo $field['value']; ?></p></div>
			<?php endif; ?>

			<label class="acf-basic-uploader">
				<input type="file" name="<?php echo $field['name']; ?>" id="<?php echo $field['id']; ?>" />
			</label>
		<?php else: ?>

			<p style="margin:0;"><?php _e('No image selected','acf'); ?> <a data-name="add" class="acf-button button" href="#"><?php _e('Add Image','acf'); ?></a></p>

		<?php endif; ?>
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
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function render_field_settings( $field ) {

		// clear numeric settings
		$clear = array(
			'min_width',
			'min_height',
			'min_size',
			'max_width',
			'max_height',
			'max_size'
		);

		foreach( $clear as $k ) {

			if( empty($field[$k]) ) {

				$field[$k] = '';

			}

		}


		// return_format
		// acf_render_field_setting( $field, array(
		// 	'label'			=> __('Return Value','acf'),
		// 	'instructions'	=> __('Specify the returned value on front end','acf'),
		// 	'type'			=> 'radio',
		// 	'name'			=> 'return_format',
		// 	'layout'		=> 'horizontal',
		// 	'choices'		=> array(
		// 		'array'			=> __("Image Array",'acf')
		// 		// 'url'			=> __("Image URL",'acf'),
		// 		// 'id'			=> __("Image ID",'acf')
		// 	)
		// ));


		// preview_size
		acf_render_field_setting( $field, array(
			'label'			=> __('Preview Size','acf'),
			'instructions'	=> __('Shown when entering data','acf'),
			'type'			=> 'select',
			'name'			=> 'preview_size',
			'choices'		=> acf_get_image_sizes()
		));


		// library
		acf_render_field_setting( $field, array(
			'label'			=> __('Library','acf'),
			'instructions'	=> __('Limit the media library choice','acf'),
			'type'			=> 'radio',
			'name'			=> 'library',
			'layout'		=> 'horizontal',
			'choices' 		=> array(
				'all'			=> __('All', 'acf'),
				'uploadedTo'	=> __('Uploaded to post', 'acf')
			)
		));


		// min
		acf_render_field_setting( $field, array(
			'label'			=> __('Minimum','acf'),
			'instructions'	=> __('Restrict which images can be uploaded','acf'),
			'type'			=> 'text',
			'name'			=> 'min_width',
			'prepend'		=> __('Width', 'acf'),
			'append'		=> 'px',
		));

		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_height',
			'prepend'		=> __('Height', 'acf'),
			'append'		=> 'px',
			'_append' 		=> 'min_width'
		));

		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_size',
			'prepend'		=> __('File size', 'acf'),
			'append'		=> 'MB',
			'_append' 		=> 'min_width'
		));


		// max
		acf_render_field_setting( $field, array(
			'label'			=> __('Maximum','acf'),
			'instructions'	=> __('Restrict which images can be uploaded','acf'),
			'type'			=> 'text',
			'name'			=> 'max_width',
			'prepend'		=> __('Width', 'acf'),
			'append'		=> 'px',
		));

		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_height',
			'prepend'		=> __('Height', 'acf'),
			'append'		=> 'px',
			'_append' 		=> 'max_width'
		));

		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_size',
			'prepend'		=> __('File size', 'acf'),
			'append'		=> 'MB',
			'_append' 		=> 'max_width'
		));


		// allowed type
		acf_render_field_setting( $field, array(
			'label'			=> __('Allowed file types','acf'),
			'instructions'	=> __('Comma separated list. Leave blank for all types','acf'),
			'type'			=> 'text',
			'name'			=> 'mime_types',
		));

	}


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

	function format_value( $value, $post_id, $field ) {

		// bail early if no value
		if( empty($value) ) return false;


		/// backwards compatibility for the standsrd IMAGE Type inputs
		if( is_numeric($value) ){
			// convert to int
			$value = intval($value);
			// format
			return acf_get_attachment( $value );

		}

		// bail early if not array (error message)
		if( !is_array($value) ) return false;

		$result = array();

		if(isset($value['img_id']) && is_numeric($value['img_id']) ){
			$result = acf_get_attachment( $value['img_id'] );
		}

		if(isset($value['crop_position']) && floatval($value['crop_position']) > 0 ){
			$crop_position = $value['crop_position'];
		}else{
			$crop_position = '50';
		}

		$result['crop_position'] = $crop_position;

		return $result;


		// return
		// return $value;

	}


	/*
	*  get_media_item_args
	*
	*  description
	*
	*  @type	function
	*  @date	27/01/13
	*  @since	3.6.0
	*
	*  @param	$vars (array)
	*  @return	$vars
	*/

	function get_media_item_args( $vars ) {

	    $vars['send'] = true;
	    return($vars);

	}


	/*
	*  wp_prepare_attachment_for_js
	*
	*  this filter allows ACF to add in extra data to an attachment JS object
	*  This sneaky hook adds the missing sizes to each attachment in the 3.5 uploader.
	*  It would be a lot easier to add all the sizes to the 'image_size_names_choose' filter but
	*  then it will show up on the normal the_content editor
	*
	*  @type	function
	*  @since:	3.5.7
	*  @date	13/01/13
	*
	*  @param	{int}	$post_id
	*  @return	{int}	$post_id
	*/

	function wp_prepare_attachment_for_js( $response, $attachment, $meta ) {

		// only for image
		if( $response['type'] != 'image' ) {

			return $response;

		}


		// make sure sizes exist. Perhaps they dont?
		if( !isset($meta['sizes']) ) {

			return $response;

		}


		$attachment_url = $response['url'];
		$base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url );

		if( isset($meta['sizes']) && is_array($meta['sizes']) ) {

			foreach( $meta['sizes'] as $k => $v ) {

				if( !isset($response['sizes'][ $k ]) ) {

					$response['sizes'][ $k ] = array(
						'height'      => $v['height'],
						'width'       => $v['width'],
						'url'         => $base_url .  $v['file'],
						'orientation' => $v['height'] > $v['width'] ? 'portrait' : 'landscape',
					);
				}

			}

		}

		return $response;
	}


	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

	function update_value( $value, $post_id, $field ) {
		//deb_e($value); die();

		// numeric
		if( is_numeric($value) ) return $value;


		// array?
		if( is_array($value) && isset($value['ID']) ) return $value['ID'];


		// object?
		if( is_object($value) && isset($value->ID) ) return $value->ID;


		// return
		return $value;

	}

  function input_admin_enqueue_scripts() {

    $dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-image/';

    wp_enqueue_script("jquery-ui-draggable");
    wp_register_script( 'acf-flo-image', "{$dir}flo-image.js" );
    wp_enqueue_script( 'acf-flo-image' );


    wp_enqueue_script( 'acf-flo-image-modal', "{$dir}/parts/flo-image-modal/flo-image-modal.js" );
    wp_enqueue_style( 'acf-flo-image-modal', "{$dir}/parts/flo-image-modal/flo-image-modal.css" );

    wp_enqueue_script( 'acf-flo-image-positioner', "{$dir}/parts/flo-image-positioner/flo-image-positioner.js" );
    wp_enqueue_style( 'acf-flo-image-positioner', "{$dir}/parts/flo-image-positioner/flo-image-positioner.css" );

    wp_enqueue_style( 'acf-flo-image', "{$dir}flo-image.css" );
  }

}


// initialize
if(function_exists('acf_register_field_type')){
	acf_register_field_type( new acf_flo_image() );
}

endif; // class_exists check

?>
