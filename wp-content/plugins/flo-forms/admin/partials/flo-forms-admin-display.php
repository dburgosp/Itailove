<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the form builder
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="form-settings-wrapper">
	<div id="form-tabs">
		<ul>
		    <li class="add-field"><a href="#add-field"><?php _e('Add a field','flotmeme'); ?></a></li>
		    <li class="fields-settings"><a href="#fields-settings"><?php _e('Field settings','flotmeme'); ?></a></li>
		    <li class="form-settings"><a href="#form-settings"><?php _e('Form settings','flotmeme'); ?></a></li>
		</ul>
		<div id="add-field">
			<div class="tab-title"><?php _e('Click on the field type button you need to add it to the Form','flo-forms'); ?></div>
		    <?php  
		    	Flo_Form_Meta_Box::render_add_field_buttons();
		    ?>
		</div>
		<div id="fields-settings">
		    <ul class="fields-controls">
		    	<div class="tab-title"><?php _e('Edit selected Field options','flo-forms'); ?></div>
		    	<span class="field-id" style="display:block;"><?php _e('Field ID: ');?></span> <span class="field-id-value"></span>
		    <?php
		    	echo Flo_Forms_Admin::maybe_render_fields( $flo_form_settings, 'field_settings' );

	//deb_e($flo_form_settings);
		    	$submit_button_label = 'Send email';
		    	$submit_button_position = 'auto';
		    	$btn_bg = '';
		    	$btn_color = '';
		    	// submit button settings
		    	if(isset($flo_form_settings['field_settings']['fid_submit_button'])){
		    		if(isset($flo_form_settings['field_settings']['fid_submit_button']['label'])){
		    			$submit_button_label = $flo_form_settings['field_settings']['fid_submit_button']['label'];
		    		}

		    		if(isset($flo_form_settings['field_settings']['fid_submit_button']['position'])){
		    			$submit_button_position = $flo_form_settings['field_settings']['fid_submit_button']['position'];
		    		}

		    		if(isset($flo_form_settings['field_settings']['fid_submit_button']['btn_bg'])){
		    			$btn_bg = $flo_form_settings['field_settings']['fid_submit_button']['btn_bg'];
		    		}

		    		if(isset($flo_form_settings['field_settings']['fid_submit_button']['btn_color'])){
		    			$btn_color = $flo_form_settings['field_settings']['fid_submit_button']['btn_color'];
		    		}

		    	}

		    ?>
		    	<li class="field-settings fid_submit_button" data-field_id="fid_submit_button">
		    		<label class="desc">
						<span class="the-label"><?php _e('Button text','flo-forms'); ?></span>
						<textarea rows="2" cols="25" onkeyup="updateProperties(jQuery(this), 'button_title')" onmouseup="updateProperties(jQuery(this), 'label')" name="flo_form_settings[field_settings][fid_submit_button][label]"><?php echo $submit_button_label; ?></textarea>
					</label>
					<br/><br/>
					<label class="desc">
						<span class="the-label"><?php _e('Button Position','flo-forms'); ?></span>
						<select name="flo_form_settings[field_settings][fid_submit_button][position]" class="button-possition">
							<option value="auto" <?php selected( $submit_button_position, 'auto' ); ?>><?php _e('Auto','flotmeme'); ?></option>
							<option value="left" <?php selected( $submit_button_position, 'left' ); ?>><?php _e('Left','flotmeme'); ?></option>
							<option value="center" <?php selected( $submit_button_position, 'center' ); ?>><?php _e('Center','flotmeme'); ?></option>
							<option value="right" <?php selected( $submit_button_position, 'right' ); ?>><?php _e('Right','flotmeme'); ?></option>
						</select>
					</label>
					<br/><br/>
					<label class="desc">
						<span class="the-label"><?php _e('Button background color','flo-forms'); ?></span>
						<br/>
						<input type="text" data-property="background-color" class="flo-forms-color-picker" name="flo_form_settings[field_settings][fid_submit_button][btn_bg]" value="<?php echo $btn_bg; ?>">
					</label>
					<br/><br/>
					<label class="desc">
						<span class="the-label"><?php _e('Button text color','flo-forms'); ?></span>
						<br/>
						<input type="text" data-property="color" class="flo-forms-color-picker" name="flo_form_settings[field_settings][fid_submit_button][btn_color]" value="<?php echo $btn_color; ?>">
					</label>
		    	</li>
		    </ul>
		</div>
		<div id="form-settings">
			<div class="tab-title"><?php _e('Edit the Form settings','flo-forms'); ?></div>
		    <label class="field-settings-label" for="send-to-email"> 
		    	<?php _e('Recipient email','flotmeme'); ?>: <br/>
		    	
		    	<input type="text" name="flo_form_settings[send-to-email]" id="send-to-email" value="<?php echo $flo_form_settings['send-to-email'] ?>">
		    	<span class="tab-title hint"><?php _e('If you need to send the messages to several recipients, add the email addresses separated by comma. Example: "info@flothemes.com, support@flothemes.com"') ?></span>
		    </label>
			<br/><br/>

			<label class="field-settings-label" for="email-subject">
				<?php _e('Email subject','flotmeme'); ?>: <br/>
		    	<input type="text" name="flo_form_settings[email-subject]" id="email-subject" value="<?php echo $flo_form_settings['email-subject'] ?>">
		    	<span class="tab-title hint"><?php _e('You can use any of the existing fields value by using %field_id%. For example: %fid_95908% - where fid_95908 is the ID of one of the form fields. To find out the ID of any field, just click on if in the preview on the right.','flo-forms'); ?></span>
		    </label>
			<br/><br/>

            <label class="field-settings-label label-placement">
                <?php _e('Form columns','flotmeme'); ?>
                <select name="flo_form_settings[nr_of_columns]" id="nr_of_columns" autocomplete="off" onchange="updateForm(jQuery(this), 'Nr_Of_Columns')">
                    <option value="1" <?php selected( $flo_form_settings['nr_of_columns'], '1' ); ?>><?php _e('One Column','flotmeme'); ?></option>
                    <option value="2" <?php selected( $flo_form_settings['nr_of_columns'], '2' ); ?>><?php _e('Two Columns','flotmeme'); ?></option>
                </select>
            </label>
            <br/>
            <br/>

			<label class="field-settings-label label-placement">
		    <?php _e('Label placement','flotmeme'); ?> 
		    <select name="flo_form_settings[label-placement]" id="formLabelAlign" autocomplete="off" onchange="updateForm(jQuery(this), 'LabelAlign')">
				<option value="topLabel" <?php selected( $flo_form_settings['label-placement'], 'topLabel' ); ?>><?php _e('Top Aligned','flotmeme'); ?></option>
				<option value="leftLabel" <?php selected( $flo_form_settings['label-placement'], 'leftLabel' ); ?>><?php _e('Left Aligned','flotmeme'); ?></option>
				<option value="rightLabel" <?php selected( $flo_form_settings['label-placement'], 'rightLabel' ); ?>><?php _e('Right Aligned','flotmeme'); ?></option>
			</select>
			</label>
			<br/>

		    <?php _e('Confirmation options','flotmeme'); ?>: <br/>

		    <label class="confirmation-label">
		    <input type="radio" class="confirmation_opt" name="flo_form_settings[confirmation_opt]" value="text_confirmation" <?php checked( $flo_form_settings['confirmation_opt'], 'text_confirmation' ); ?> > <div><?php _e('Show a text message','flotmeme'); ?></div>
		    </label>
		    <label class="confirmation-label">
	  		<input type="radio" class="confirmation_opt" name="flo_form_settings[confirmation_opt]" value="page_confirmation" <?php checked( $flo_form_settings['confirmation_opt'], 'page_confirmation' ); ?> > <div><?php _e('Redirect to a page','flotmeme'); ?></div>
	  		</label>
	  		<textarea name="flo_form_settings[text_confirmation_value]" class="text-confirmation-value <?php echo $text_conf_claass; ?>"><?php echo $flo_form_settings['text_confirmation_value'] ?></textarea>

	  		<?php echo $confimation_page; ?>

		</div>
	</div>
	<div class="row form-preview <?php echo $flo_form_settings['label-placement'] ?>">
        <div class="left_block " data-div_id="1">

            <?php if(isset($flo_form_settings['nr_of_columns']) && $flo_form_settings['nr_of_columns'] == '2'):?>
                <ul class="flo-fields-preview left">
                    <?php
                    echo Flo_Forms_Admin::maybe_render_fields( $flo_form_settings, 'form_field', 'div_id_1' );
                    ?>
                </ul>
            <?php endif;?>
        </div>
        <div class="right_block" data-div_id="2">
            <?php if(isset($flo_form_settings['nr_of_columns']) && $flo_form_settings['nr_of_columns'] == '2'):?>
                <ul class="flo-fields-preview ">
                    <?php
                    echo Flo_Forms_Admin::maybe_render_fields( $flo_form_settings, 'form_field', 'div_id_2' );
                    ?>
                </ul>
            <?php endif;?>
        </div>
        <?php if(!isset($flo_form_settings['nr_of_columns']) || $flo_form_settings['nr_of_columns'] == '1'):?>
            <span class="spinner add-field-spinner"></span>
            <ul class="flo-fields-preview left">
                <?php
                    echo Flo_Forms_Admin::maybe_render_fields( $flo_form_settings, 'form_field' );
                ?>
            </ul>

        <?php endif;?>
        <ul class="flo_btn_submit">
            <li class="fid_submit_button form-preview-row" id="fid_submit_button" title="<?php _e('Click to edit.','flo-forms'); ?>">
                <div class="<?php echo $submit_button_position; ?>" id="">
                    <input type="button" class="flo-submit-button" value="<?php echo $submit_button_label; ?>">
                </div>
            </li>
        </ul>
	</div>
</div>
<br/>
<?php  
	if(isset($_GET['post'])){
		echo '<div class="form-shortcode-preview">';
		_e('You can use the following shortcode in any page or post: ','flo-forms');
		?>
		&nbsp;&nbsp;&nbsp;
		[flo_form id='<?php echo $_GET['post'] ?>']
		<?php
		
		echo '</div>';

	}
?>
<div id="flo-publishing-action">
	<span class="spinner"></span>
	<input name="original_publish" type="hidden" id="flo_original_publish" value="Update">
	<input name="save" type="button" class="button button-primary button-large" id="flo_publish" value="<?php _e('Save form','flo-forms'); ?>">
</div>
