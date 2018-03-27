<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/public
 * @author     Alex G. <alexg@flothemes.com>
 */
class Flo_Forms_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flo_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( 'jquery_ui_styles', plugin_dir_url( __FILE__ ) . 'vendor/jquery-ui-flo/jquery-ui.min.css', array(), $this->version, 'all');
		//wp_register_style( 'jquery_ui_styles' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');

		wp_register_style( 'flo-forms-public', plugin_dir_url( __FILE__ ) . 'css/flo-forms-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Flo_Forms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Flo_Forms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( 'flo-jquery-validate', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'flo-forms-public', plugin_dir_url( __FILE__ ) . 'js/flo-forms-public.js', array( 'jquery','flo-jquery-validate' ), $this->version, false );

		$siteurl = get_option('siteurl');
		if( !empty($siteurl) ){
			$siteurl = rtrim( $siteurl , '/') . '/wp-admin/admin-ajax.php' ;
		}else{
			$siteurl = home_url('/wp-admin/admin-ajax.php');
		}

		$formData = array(

		    // generate a nonce with a unique ID "header_1_menu_spacing_nonce_val"
		    // so that you can check it later when an AJAX request is sent
		    //'formNonce' => wp_create_nonce( 'formNonceVal' ),
		    'ajaxurl' => $siteurl,
		    'date_format' => self::date_format_php_to_js( get_option( 'date_format' ))

		);


		// localize the necessary data
		wp_localize_script( 'flo-forms-public', 'formData', $formData );

		// use this action if for some reason the scripts are not enqueued
		// see Monte theme as an example
		do_action('flo_forms_after_scripts_register');

	}

	function date_format_php_to_js( $sFormat ) {
	    switch( $sFormat ) {
	        //Predefined WP date formats
	        case 'F j, Y':
	            return( 'MM dd, yy' );
	            break;
	        case 'Y/m/d':
	            return( 'yy/mm/dd' );
	            break;
	        case 'm/d/Y':
	            return( 'mm/dd/yy' );
	            break;
	        case 'd/m/Y':
	            return( 'dd/mm/yy' );
	            break;
	        case 'Y-m-d':
	        	return('yy/mm/dd');
	        	break;
	        default:
	        	return( 'MM dd, yy' );
	            break;
	    }

	}
	static public function flo_forms_shortcode($atts){

		
		if(isset($atts['id']) && is_numeric($atts['id'])){


			// enqueue the scripts only when the shortcode is used on a page

			wp_enqueue_style( 'jquery_ui_styles' );
			wp_enqueue_style( 'flo-forms-public' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'flo-forms-public' );

			// we should have the options in the following format: http://pastebin.com/FGiNW6jr
			$form_options = get_post_meta( $atts['id'], 'flo_form_settings', true );

			$email_is_valid_string = isset($form_options['send-to-email']) && is_email($form_options['send-to-email'] );
			$email_is_array_with_valide_strings = true;

			if(isset($form_options['send-to-email']) && is_array($form_options['send-to-email'])){
                if ($email_is_array_with_valide_strings){
                        foreach ($form_options['send-to-email'] as $email){
                            if(!is_email($email)){
                                $email_is_array_with_valide_strings = false;
                            }
                        }
                    }
            }

			if( !($email_is_valid_string || $email_is_array_with_valide_strings) ){

				return __('Please add the Recipient email for this form. Make sure there is a valid email','flo-forms');
			}

			if(isset($form_options['field_settings']) && is_array($form_options['field_settings']) && sizeof($form_options['field_settings']) && isset($form_options['fields_order']) && is_array($form_options['fields_order']) && sizeof($form_options['fields_order'])){

				ob_start();
				ob_clean();
				include('partials/flo-forms-public-display.php');
				$the_form = ob_get_clean();
				return $the_form;
			}else{
				return __('Something is wrong with the form settings','flo-forms');
			}

			
		}else{
			return __('The passed Form ID is not valid','flo-forms');
		}

	}

	static public function set_default_field_settings($field = array()){
		if(!isset($field['label'])){
			$field['label'] = '';
		}

		if(!isset($field['field_width'])){
			$field['field_width'] = ' width-100 ';
		}

		if(!isset($field['css_class'])){
			$field['css_class'] = '';
		}

		if(!isset($field['required']) || (isset($field['required']) && $field['required'] != 1 ) ){
			$field['required'] = '0';
		}

		if(!isset($field['type'])){
			$field['type'] = 'text';
		}

		return $field;
	}

	static public function render_field($field_settings){
		if(is_array($field_settings) && sizeof($field_settings)){
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
			echo self::maybe_render_label($field_settings); // show the label if necessary

			$fields_with_choices_layout = array('radio_button','checkbox');

			$field_layout_class = '';
			if( isset($field_settings['type']) && in_array($field_settings['type'], $fields_with_choices_layout) ){
				if(isset($field_settings['field_layout'])){
					$field_layout_class = $field_settings['field_layout'];
				}
			}

			if(isset($field_settings['default_value']) && strlen($field_settings['default_value']) ){
				$default_value = $field_settings['default_value'];
			}else{
				$default_value = '';
			}

			echo '<div class="field-box '.$field_settings['type'].' '.$field_layout_class.'">'; // wrapper for the field
            $height = isset($field_settings['textarea_height']) &&  $field_settings['textarea_height'] != '' ? $field_settings['textarea_height']: '';
			switch ($field_settings['type']) {
				case 'textarea':
				?>
				<textarea <?php if($height != ''):?>style="height: <?php echo $height;?>px"<?php endif;?>  name="<?php echo $field_settings['id']; ?>" placeholder="<?php echo $field_settings['placeholder']; ?>" id="<?php echo $field_settings['id']; ?>"  <?php echo self::maybe_is_required($field_settings); ?> ><?php echo $default_value; ?></textarea>

				<?php
				break;
			case 'message':
				 // the message fiels has no input, it has just the label which is rendered above
				break;	
			case 'email':
				?>
				<input type="email" name="<?php echo $field_settings['id']; ?>" placeholder="<?php echo $field_settings['placeholder']; ?>" id="<?php echo $field_settings['id']; ?>"  data-rule-required="true" data-rule-email="true" data-msg-required="<?php _e('Please enter your email address','flo-forms'); ?>" data-msg-email="<?php _e('Please enter a valid email address','flo-forms'); ?>" value="<?php echo $default_value; ?>" >
				<?php
				break;

			case 'date':
				?>
				<input type="text" class="date-input" name="<?php echo $field_settings['id']; ?>" placeholder="<?php echo $field_settings['placeholder']; ?>" id="<?php echo $field_settings['id']; ?>"  <?php echo self::maybe_is_required($field_settings); ?> value="<?php echo $default_value; ?>" >
				<?php
				break;

			case 'checkbox':
				if(is_array($field_settings) && isset($field_settings['choices']) && sizeof($field_settings['choices'])){
					self::draw_chkbox_or_rd($type = 'checkbox', $field_settings );
				}
				break;

			case 'radio_button':
				if(is_array($field_settings) && isset($field_settings['choices']) && sizeof($field_settings['choices'])){
					self::draw_chkbox_or_rd($type = 'radio', $field_settings );
				}
				break;

			case 'dropdown':
				if(is_array($field_settings) && isset($field_settings['choices']) && sizeof($field_settings['choices'])){
					self::draw_dropdown( $field_settings);
				}
				break;

			case 'section_break':
				?>
				<hr class="flo-section-break">
				<?php
				break;
			case 'hidden':
				?>
				<input name="<?php echo $field_settings['id']; ?>" type="hidden" value="<?php echo $default_value; ?>">
				<?php
				break;
            case 'captcha':
               if($forms_options['enable-captcha']){
                   ?>
                   <div style="width: 100%; text-align: center;">
                       <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                       <div>
                           <div style="display: inline-block" class="g-recaptcha" data-sitekey="<?php echo $forms_options['g_site_key']?>"></div>
                       </div>
                   </div>
                   <?php
               }
                break;
			default: // text field

				?>
				<input type="text" name="<?php echo $field_settings['id']; ?>" placeholder="<?php echo $field_settings['placeholder']; ?>" id="<?php echo $field_settings['id']; ?>" <?php echo self::maybe_is_required($field_settings); ?> value="<?php echo $default_value; ?>"
				>
				<?php
				break;
			}

			echo '</div>';
		}
	}

	static public function maybe_is_required($field_settings){
		if(isset($field_settings['required']) && $field_settings['required'] == 1){
			$required_msg = __('This field is required','flo-forms');
			return 'data-rule-required="true" data-msg-required="'.$required_msg.'"';
		}
	}

	static public function maybe_render_label($field_settings){
		if(isset($field_settings['label']) && strlen(trim($field_settings['label'])) && $field_settings['type'] != 'hidden'){

			// for the message field we do not need a label. Because the label is used as the message
			if($field_settings['type'] == 'message') {
				return nl2br($field_settings['label']);
			}else{
				return '<label class="main-label" for="'.$field_settings['id'].'">'.$field_settings['label'].'</label>';
			}
			
		}
	}

	/**
	 *
	 * draw a checkbox or radio button inputs
	 *
	 * 	$choices => array(
	 * 		array(
	 * 			'label' => 'the label 1',
	 * 			'preselected' => true/false/ or none
	 * 		),
	 * 		array(
	 * 			'label' => 'the label 2',
	 * 			'preselected' => true/false/ or none
	 * 		)
	 * )
	 *
	 */
	static public function draw_chkbox_or_rd($type = 'checkbox', $field_settings){
		//deb_e($choices);

		$choices = Flo_Form_Fields::create_choices_array($field_settings['choices']);
		if($type == 'checkbox'){
			$name = $field_settings['id'].'[]';
		}else{
			$name = $field_settings['id'];
		}
		foreach ($choices as $key => $value) {
			if(isset($value['label'])){
				$the_label = $value['label'];
			}else{
				$the_label = '';
			}
			?>
			<span class="the-choice">
				
				<label class="choice" for="<?php echo $field_settings['id'].'_'.$key; ?>">
					<input value="<?php echo $the_label ?>" class="checkbox" type="<?php echo $type; ?>" name="<?php echo $name; ?>" id="<?php echo $field_settings['id'].'_'.$key; ?>"  <?php echo self::maybe_is_required($field_settings); ?> >
					<span class="choice__text "><?php echo $the_label; ?></span>
				</label>
			</span>
			<?php
		}
		echo '<label for="'.$name.'" class="error"></label>'; //this is used for the validation error
	}

	static public function draw_dropdown( $field_settings){

		$choices = Flo_Form_Fields::create_choices_array($field_settings['choices']);

		if(is_array($choices) && sizeof($choices)){
		?>
			<select name="<?php echo $field_settings['id']; ?>" id="<?php echo $field_settings['id']; ?>"  <?php echo self::maybe_is_required($field_settings); ?>>
			<?php foreach ($choices as $key => $value) { 
					if(isset($value['label'])){
						$the_label = $value['label'];
					}else{
						$the_label = '';
					}
			?>
				<option value="<?php echo $the_label; ?>" >
					<?php echo $the_label ?>
				</option>
			<?php } ?>
			</select>
		<?php
		}
	}

	static public function flo_submit_form(){
        $forms_options = get_option('flo_forms_options');

        if(!$forms_options){
            $current_user = wp_get_current_user();

            $forms_options = array(
                'enable_email_reminder' => 1,
                //how many days old should entries be in order to triger the reminder email
                'entries_days_old_reminder' => 1,
                'send_to_email' => $current_user->user_email,
                'text_email' => 0,
                'enable-captcha' => 0,
                'g_site_key' => '',
                'g_secret_key' => ''
            );
        }

        if(isset($forms_options['enable-captcha']) && $forms_options['enable-captcha']){
            if(!($_POST['g-recaptcha-response'] && $_POST['g-recaptcha-response'])){
                $response['error'] = sprintf(__('%s Please complete captcha verification %s','flo-forms'), '<div class="error">','</div>');
                echo( json_encode($response) );

                exit();
            }
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/grecaptcha/autoload.php';
            $recaptcha = new \ReCaptcha\ReCaptcha($forms_options['g_secret_key'], new \ReCaptcha\RequestMethod\SocketPost());

            $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

            if(!$resp->isSuccess()){
                $response['error'] = sprintf(__('%s Captcha not valid, please try again %s','flo-forms'), '<div class="error">','</div>');
                echo( json_encode($response) );

                exit();
            }

        }

		$response = array();

		//echo( json_encode($_POST) );
		// var_dump($_POST['formNonce']);
		//if ( ! wp_verify_nonce( $_POST['formNonce'], 'formNonceVal' ) ){
        //    $response['error'] = __('Busted! Wrong Nonce. Refresh the page and try again.','flo-forms');
        //}

        if(isset($_POST['name']) && strlen($_POST['name'])){
        	//if that field was completed, then we condider this filled in by a spammer, maybe robot
        	$response['error'] = sprintf(__('%s Are you kidding me ? %s','flo-forms'), '<div class="error">','</div>');
        	echo( json_encode($response) );

        	exit();
        }
        $form_id = $_POST['flo_fid']; // get the form id
		$form_options = get_post_meta( $form_id, 'flo_form_settings', true ); // get the form settings

        $submited_info = self::create_message_from_form($_POST,$form_options);
		//$response['msg'] = $submited_info['table'];

        // now we will create the Entry in the DB
		$entry_id = self::create_message_entry($submited_info, $form_id);

		do_action( 'flo_forms_before_send_mail', $_POST );

		// send the email
		$maybe_send_email = self::send_message($form_options,$submited_info['table'],$submited_info['user_email'],$entry_id,$_POST);
		

		if(true !== $maybe_send_email){ // if for somereasont he email was not delivered
			$response['error'] = sprintf(__('%s The email could not be sent %s','flo-forms'), '<div class="error">','</div>');
		}else{
			$response['confirmation_opt'] = $form_options['confirmation_opt'];

			if('text_confirmation' === $form_options['confirmation_opt']){
				$response['success_msg'] = '<div class="success">'.$form_options['text_confirmation_value'].'</div>';
			}else{
				if(is_numeric($form_options['confimation_page']) && 0 != $form_options['confimation_page']){

					$response['success_page'] = get_permalink($form_options['confimation_page']);
				}else{
					$response['confirmation_opt'] = 'text_confirmation';
					$response['success_msg'] = '<div class="success">'.$form_options['text_confirmation_value'].'</div>';
				}
				
			}
		}

		
		// we also have   $submited_info['submission_info'] // that contains the submited info in a array format
		// use it to attach to the submission custom post type

        echo( json_encode($response) );
		exit();
	}

	/**
	 *
	 * The users can use field ID in the subject and we need to replace them with the field value
	 * @params:
	 * @subject - string : the email subject that may contain the field IDs
	 * @post_data - array containing the form POST data
	 *
	 * @return - string - the email subject with the field ID replaces by field Value
	 */
	static function maybe_replace_subject($subject,$post_data){
		$patern = '%fid_\d+%';
		preg_match_all($patern,$subject,$out,PREG_PATTERN_ORDER);

		if(isset($out[0]) && is_array($out[0]) && sizeof($out[0])){

			foreach ($out[0] as $key => $field_id) {
				if(isset($post_data[$field_id])){
					$subject = str_replace('%'.$field_id.'%',$post_data[$field_id],$subject);
				}
			}

			
		}
		
		return $subject;
	}

	/**
	 *
	 * Send the email containing the contact form message
	 * params:
	 * @form_options - array containing the form settings
	 * @submited_info_html - string, a html table that contains the info the user has submitted
	 * @user_email - string, the email/emails fields the user completed in the form
	 * @post_data - array the POST data send via ajax
	 *
	 * return - bool, true if the message was sent, and false otherwise
	 */
	static function send_message($form_options,$submited_info_html,$user_email,$entry_id, $post_data){



		$tomail = $form_options['send-to-email'];

        if(strpos($tomail, ',')){
            $tomail = str_replace(' ', '', $tomail);
            $tomail = explode(',',$tomail);
        }

		$subject = self::maybe_replace_subject($form_options['email-subject'],$post_data);
		$forms_options = get_option('flo_forms_options');
		// generate a hidden image to be attached to the email
		// so that when the email is openned, the entry is marked as read
		if(isset($entry_id) && is_numeric($entry_id) && !$forms_options['text_email']){
			//$hidden_image_url = plugin_dir_url( __FILE__ ).'read-entry.png?flo_read_msg='.$entry_id;
			$hidden_image_url = home_url().'?flo_read_msg='.$entry_id;
			$hidden_image = '<div style="display: none"><img style="visibility: hidden" src="'.$hidden_image_url.'" /></div>';
		}else{
			$hidden_image = '';
		}

		if(isset($forms_options['reply_to_header']) && 0 == $forms_options['reply_to_header']){
			$reply_to_header = false;
		}else{
			$reply_to_header = true;
		}
		
		$message = $submited_info_html.$hidden_image;
		$headers = array();
		if(!$forms_options['text_email']){
			$headers[] = 'Content-Type: text/html; charset=UTF-8';// send html email
		}else{
			$headers[] = "Content-Type: text/plain; charset=\"utf-8\"\r\n";
		}

		if(strlen($user_email) && $reply_to_header ){
			$headers[] = 'Reply-To: ' . trim($user_email);
		}

		$maybe_send_email = wp_mail( $tomail, $subject, $message, $headers);

		return $maybe_send_email;
	}

	/**
	 *
	 * Creates a message from the fields form
	 * that will be sent via email and used as content in the DB Entries
	 * params
	 * @form_info - array, the fields submitted bu the visitor
	 * @form_options - array, the form settings saved in the backend
	 * 
	 * return array - an array that contains the submitted info in form of an html table, and the info in form of an array
	 * 
	 */
	static function create_message_from_form($form_info,$form_options){
		// example of form_info http://pastebin.com/sAumFDNX

		
		$backend_fields = $form_options['field_settings'];
		
		if(isset($form_options['fields_order']) && is_array($form_options['fields_order']) && sizeof($form_options['fields_order'])){

			// iterate through each field in the order it is set in the backend
			// and because in the front end we use for each feld the same name as the field iD in theback end
			// we can acces the front end value(what a visitor submited) for a given front end field like $form_info[$field_id]

			// we will create a table to use it in the email and when a Entry is checked, but at the same time we will save
			// all the user submited data as an array to the Entry meta data (just in case we will need to use this data for search other operations later)

			$submission_info = array(); //an array that contains the form data, will be saved as metadata to the submission/entry custom post type
		?>
			
		<?php	
			$table_rows = '';
			$email_fields = '';
			foreach ($form_options['fields_order'] as $key => $field_id) {
				if( !(isset($backend_fields[$field_id]['type']) && ($backend_fields[$field_id]['type'] == 'section_break'
                        || $backend_fields[$field_id]['type'] == 'hidden' || $backend_fields[$field_id]['type'] == 'captcha')) ){
					if(isset($backend_fields[$field_id]['type']) && $backend_fields[$field_id]['type'] == 'email'){
						if(strlen($form_info[$field_id]) && is_email($form_info[$field_id])){
							$email_fields .= $form_info[$field_id].',';
						}
					}

					if(isset($backend_fields[$field_id]['label']) && strlen($backend_fields[$field_id]['label'])){
						$label = $backend_fields[$field_id]['label']; // we will show the label if is set
					}else if(isset($backend_fields[$field_id]['placeholder']) && strlen($backend_fields[$field_id]['placeholder'])){
						$label = $backend_fields[$field_id]['placeholder']; // if the label is not set we will check the placeholder
					}else{
						$label = '';
					}
					$label_row_style = 'font-weight: bold; background-color: #fafafa; padding: 8px 35px';
					$value_row_style = 'padding: 5px 30px 5px 60px; background-color: #fff; border-bottom: 1px solid #DFDFDF;';


					//output the feld value
					$message_field_value = '';
					if(isset($form_info[$field_id]) && is_array($form_info[$field_id]) ){
						// for the felds like cheboxes the value is an array that may contain several values
						foreach ($form_info[$field_id] as $val_key => $form_field_value) {
							$message_field_value .= $form_field_value . '<br/>';
						}
					}else if(isset($form_info[$field_id]) && !is_array($form_info[$field_id]) ){
						// for siple inputs
						$message_field_value = $form_info[$field_id];

					}
					if($message_field_value == ''){
						$message_field_value = '';
					}
					$plugin_options = get_option('flo_forms_options');
					ob_start();
					ob_clean();
					if(!$plugin_options['text_email']){

				?>
					<tr style="<?php //echo $label_row_style; ?>">
						<td style="<?php echo $label_row_style; ?>"><?php echo $label; ?></td>
					</tr>
					<tr style="<?php //echo $value_row_style; ?>">
						<td style="<?php echo $value_row_style; ?>"><?php echo $message_field_value; ?></td>
					</tr>
				<?php
					}else{

					echo $label."\n";
					echo "\t\t".strip_tags($message_field_value)."\n\n";

					}
					$table_rows .= ob_get_clean();
					$submission_info[$label] = $message_field_value;
				}


			}
			if(!$plugin_options['text_email']){
				$the_table = '<table style="width: 100%; border: 1px solid #DFDFDF; border-bottom:0px; border-spacing: 0px;">';
				$the_table .= $table_rows;
				$the_table .= '</table>';
			}else{
				$the_table = $table_rows;
			}

			// $email_fields is built in form of user@something.com, some_other_email@smth.com,
			// therefore we need to remove the last comma
			$email_fields = rtrim($email_fields, ','); //remove the last comma from the email
			return array('table' => $the_table, 'submission_info' => $submission_info, 'user_email' => $email_fields);

		}
	}

	/**
	 *
	 * Create the submission Entry post
	 * parems:
	 * @$submited_info array containing the info submitted by the user in html table format and array format
	 * @form_id - int, the ID of the contact form
	 *
	 * return - $entry_id if the Entry with the necessary meta data was created succesfully, or false otherwise
	 *
	 */
	static function create_message_entry($submited_info, $form_id){
		$form = get_post($form_id);
		$form_title = $form->post_title;

		$entry_info = array(
		  'post_title'    => $form_title,
		  'post_content'  => '',
		  'post_status'   => 'publish',
		  'post_type' => 'flo_form_entry'
		  //'post_author'   => 1,

		);

		// Insert the post into the database
		$entry_id = wp_insert_post( $entry_info );
		
		if(is_numeric($entry_id) && $entry_id > 0){ // if the post was created succesfully
			// create a taxonomy - 'entry_form' for the newlly created entry. The taxonomy title is the same as the form title
			wp_set_object_terms( $entry_id, $form_title, 'entry_form' );

			// now update the entry title
			$updated_entry = array(
			      'ID'           => $entry_id,
			      'post_title'   => $form_title. ' : '.__('Entry #','flo-forms').' '.$entry_id
			);

			// Update the post into the database
			wp_update_post( $updated_entry );

			// set the necessary meta data:
			$message_table = $submited_info['table'];
			$message_array = $submited_info['submission_info'];

			update_post_meta($entry_id, 'message_table', $message_table);
			update_post_meta($entry_id, 'message_array', $message_array);
			update_post_meta($entry_id, 'user_email', $submited_info['user_email']);


			// everything went goot, return the Entry ID
			return $entry_id;
		}else{
			return false;
		}
	}

	/**
	 *
	 * mark a Entry as read if http request has flo_read_msg
	 * Usually this method  is called from a hidden 1x1px image which is sent in the user email
	 * When user opens the email, the image which has the user http://site_url?flo_read_msg=entry_id
	 * triggers this function and we mark the entry as read
	 *
	 */
	public function read_entry(){
		if(isset($_GET['flo_read_msg']) && is_numeric($_GET['flo_read_msg']) ){
			header('Content-Type: image/jpeg');
			$hidden_image_url = plugin_dir_url( __FILE__ ).'read-entry.png';
			$img = @imageCreateFromPng($hidden_image_url);

			if($img){
				imagepng($img);
				imagedestroy($img);
			}


			//$_GET['flo_read_msg'] -> is the ID of the Entry post

			update_post_meta($_GET['flo_read_msg'],'entry_read','read'); // Mark  the entry as read

			die();
		}
	}


	public function unread_entries_reminder(){


		$forms_options = get_option('flo_forms_options');


		if(!$forms_options){
			$current_user = wp_get_current_user();

			$forms_options = array(
				'enable_email_reminder' => 1,
				//how many days old should entries be in order to triger the reminder email
				'entries_days_old_reminder' => 1,
				'send_to_email' => $current_user->user_email,
				'text_email' => 0,
                'enable-captcha' => 0,
                'g_site_key' => '',
                'g_secret_key' => ''
			);
		}

		if(isset($forms_options['enable_email_reminder']) && $forms_options['enable_email_reminder'] == 1 && isset($forms_options['send_to_email']) && is_email($forms_options['send_to_email'])){

			$transient_name = 'flo_forms_reminder_interval';

			if ( FALSE == $request = get_transient( $transient_name ) ) {

	            $days_settings = $forms_options['entries_days_old_reminder'];
				$hours_old = 24*$days_settings;

				//subtract from the current time the number of hours passed as argument
				$old_entries_time = date("Y-m-d H:i:s", time()-3600*$hours_old );

				$args = array(
					'post_type' => 'flo_form_entry',
					'posts_per_page' => -1,
					'meta_query' => array(
					    array(
					     'key' => 'entry_read',
					     'compare' => 'NOT EXISTS' // this should work...
					    ),
					),
					'date_query' => array(
						array(
							'before'    => $old_entries_time,
							'inclusive' => true,
						),
					),

				);
				$query1 = new WP_Query( $args );

				if($query1->post_count){
					
					$tomail = $forms_options['send_to_email'];
					$subject = __('You have unread form entries on your site','flo-forms');
					$message = sprintf(__('This is a reminder that there are unread Entries on your website. You can check them %s here %s','flo-forms'),'<a  href="'.get_admin_url().'edit.php?post_type=flo_form_entry" target="_blank">','</a>');
					//get_admin_url().'edit.php?post_type=flo_form_entry'
					$headers = array();
					$headers[] = 'Content-Type: text/html; charset=UTF-8'; // send html email

					$maybe_send_email = wp_mail( $tomail, $subject, $message, $headers);
				}

				$transient_life = 24*60 * 60; // 24 hours transient
				//$transient_life = 1 * 60; // 1 min transient for test
	            set_transient( $transient_name, 'reminder run OK', $transient_life ); 
	        }
		}

		




	}
}


