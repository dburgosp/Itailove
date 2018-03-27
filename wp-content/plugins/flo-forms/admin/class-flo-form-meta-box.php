<?php

class Flo_Form_Meta_Box {

	public static $available_fields; 

	public function __construct() {


		if ( is_admin() ) {

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

			self::$available_fields = array(
				'text' => array(
						'class' => 'text dashicons dashicons-editor-textcolor',
						'label' => __('Single line text','flo-forms'),
						'has_placeholder' => true,
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'textarea' => array(
						'class' => 'textarea dashicons dashicons-editor-paragraph',
						'label' => __('Paragraph text','flo-forms'),
						'has_placeholder' => true,
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'email' => array(
						'class' => 'email dashicons dashicons-email-alt',
						'label' => __('Email','flo-forms'),
						'has_placeholder' => true,
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'date' => array(
						'class' => 'date dashicons dashicons-calendar-alt',
						'label' => __('Date','flo-forms'),
						'has_placeholder' => true,
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'checkbox' => array(
						'class' => 'checkbox dashicons dashicons-yes',
						'label' => __('Checkbox','flo-forms'),
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'radio_button' => array(
						'class' => 'radio_button dashicons dashicons-marker',
						'label' => __('Radio button','flo-forms'),
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'dropdown' => array(
						'class' => 'dropdown dashicons dashicons-arrow-down',
						'label' => __('Dropdown','flo-forms'),
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'section_break' => array(
						'class' => 'section_break dashicons',
						'label' => __('Section break','flo-forms'),
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => true
					),
				'hidden' => array(
						'class' => ' dashicons-hidden',
						'label' => __('Hidden','flo-forms'),
                        'has_width' => false,
                        'has_css_class' => false,
                        'can_be_required' => false
					),
				'message' => array(
						'class' => ' message dashicons dashicons-media-text',
						'label' => __('Message','flo-forms'),
                        'has_width' => true,
                        'has_css_class' => true,
                        'can_be_required' => false
					),

			);

            if(isset($forms_options['enable-captcha']) && $forms_options['enable-captcha']){
                self::$available_fields['captcha'] = array(
                    'class' => 'dashicons dashicons-captcha',
                    'label' => __('Captcha','flo-forms'),
                    'has_width' => true,
                    'has_css_class' => true,
                    'can_be_required' => false
                );
            }

			// also add the conditons that it is flo_forms post type
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  ),9        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'flo_form_settings', // meta box id 
			__( 'Form Settings', 'flo-forms' ), // Title of the meta box
			array( $this, 'render_metabox' ),  // call back funtion
			'flo_forms', // post type
			'side', // The context within the screen where the boxes should display.
			'high' // priority
		);

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'flo_form_nonce_action', 'flo_form_nonce' );


		// Retrieve an existing value from the database.
		$flo_form_settings = get_post_meta( $post->ID, 'flo_form_settings', true );

//deb_e($flo_form_settings); die();

		// make sure we set the defaults if some data is not available
		$flo_form_settings = self::set_default_settings($flo_form_settings);

		//prepare the data for the outout
		$flo_form_settings = self::prepare_data($flo_form_settings);

		// if the confirmation option is a redirect to a page
		if( 'page_confirmation' == $flo_form_settings['confirmation_opt']){
			$text_conf_claass = ' hidden ';
			$page_conf_class = '';
		}else{
			$text_conf_claass = '';
			$page_conf_class = ' hidden ';
		}

		$args = array(
				'selected'              => $flo_form_settings['confimation_page'],
    			'echo'                  => 0,
    			'name'                  => 'flo_form_settings[confimation_page]',
    			'show_option_none'      => __('Select a page','flo-forms'), // string
    			'class'					=> 'page-confirmation-value '.$page_conf_class
			);
		$confimation_page = wp_dropdown_pages( $args );

		

		//include the form builder markup
		include_once('partials/flo-forms-admin-display.php');

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		if(isset($_POST['flo_form_nonce'])){
			$nonce_name   = $_POST['flo_form_nonce'];
			$nonce_action = 'flo_form_nonce_action';
		}
		

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// Sanitize user input.
		$flo_form_settings = isset( $_POST[ 'flo_form_settings' ] ) ? self::sanitize( $_POST[ 'flo_form_settings' ] ) : '';
		// Update the meta field in the database.
		update_post_meta( $post_id, 'flo_form_settings', $flo_form_settings );


	}

	/**
	 * A custom sanitization function that will take the incoming input, and sanitize
	 * the input before handing it back to WordPress to save to the database.
	 *
	 * @since    1.0.0
	 *
	 * @param    array    $input        The address input.
	 * @return   array    $new_input    The sanitized input.
	 */
	static public function sanitize( $input ) {

		// Initialize the new array that will hold the sanitize values
		$new_input = array();

		// Loop through the input and sanitize each of the values
		foreach ( $input as $key => $val ) {

			if(is_array($input[ $key ])){
				$new_input[ $key ] = $input[ $key ];
			}else{
				$new_input[ $key ] = ( isset( $input[ $key ] ) ) ?
				sanitize_text_field( $val ) :
				'';
			}
			

		}

		return $new_input;

	}

	/**
	 *
	 * Prepare the saved setting for displaying on the screen
	 *
	 */
	static public function prepare_data($data_array){

		$new_data = array();

		foreach ($data_array as $key => $value) {
			if( empty( $value ) ){
				$clean_value = ''; // set the default value
			}else{
				if(!is_array($value)){
					$clean_value = esc_attr($value); // escape the saved value
				}else{
					$clean_value = self::prepare_data($value); // if the value is an array, we recussively call this method again
				}
				
			}
			$new_data[$key] = $clean_value;
		}
		return $new_data;
	}


	static public function set_default_settings($data_array){

		$current_user = wp_get_current_user(); // get the current user info
		
		// when new options will be added, add the defaults below:
		$default_form_settings = array(
			'send-to-email' => $current_user->user_email,
			'email-subject' => sprintf(__('A new message from %s','flo-forms'),get_bloginfo('name')),
			'label-placement' => 'topLabel',
			'confirmation_opt' => 'text_confirmation',
			'text_confirmation_value' => __('Thank you!','flo-forms'),
			'nr_of_columns' => 1,
			'confimation_page' =>0
		);

		if(!is_array($data_array)){
			$data_array = $default_form_settings;
		}else{
			foreach ($default_form_settings as $key => $value) {
				// if for some reason one of the optons does not exist, then use the default value
				if(!isset($data_array[$key])){ 
					$data_array[$key] = $value;
				}
			}
		}


		return $data_array;
	}

	/**
	 *
	 * This function renders the buttons which represent the available fields
	 *
	 */
	static public function render_add_field_buttons(){

		$available_fields = self::$available_fields;


		echo '<ul class="field-buttons">';
		foreach ( $available_fields as $key => $value ) {
			?>
			<li class="type-<?php echo $value['class'] ?>" >
				<a href="#" onclick="flo_add_form_filed('<?php echo $key ?>');return false;" >
					<?php echo $value['label']; ?>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
	}



}

new Flo_Form_Meta_Box;
?>
