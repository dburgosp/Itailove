<?php 

class Flo_Form_Fields {

	static public function render_field_settigs_wrapper($field_settings = array(),$field_type){

		//deb_e($field_settings);
		//wp_enqueue_editor();


		$field_name = "flo_form_settings[field_settings][".$field_settings['id']."]";
		$field_settings = self::set_default_field_settings($field_settings,$field_type); // make sure we set the proper defaults for the required settings

		if(isset($field_settings['required']) && $field_settings['required'] == 1){
			$required = 1;
		}else{
			$required = 0;
		}

		?>
		<li class="field-settings <?php echo $field_settings['id']; ?>" data-field_id="<?php echo $field_settings['id']; ?>">
			<label class="field-settings-label">
			<?php
				if($field_type == 'message') { // for the 'message' field, we will use the label as the Message that will be displayed in the front.
					_e('Add the message','flo-forms'); 
				}else{
					_e('Field Label','flo-forms'); 
				}
				
				$editor_id = mt_rand(1,99999);
				?>: <br/>
			
				<textarea   rows="2" cols="25" <?php if($field_type == 'message'){ echo 'id="flo-form-msg_'.$editor_id.'"'; } ?> class="<?php if($field_type == 'message'){ echo 'flo-form-message'; } ?>" onkeyup="updateProperties(jQuery(this), 'label')" onmouseup="updateProperties(jQuery(this), 'label')" name="<?php echo $field_name."[label]"; ?>"><?php echo $field_settings['label']; ?></textarea>
			

			</label>

			<br/>
			<?php 
				$available_fields = Flo_Form_Meta_Box::$available_fields;
				if(isset($available_fields[$field_type]['has_placeholder']) && true === $available_fields[$field_type]['has_placeholder'] ): ?>
					<label class="field-settings-label">
					<?php _e('Field Placeholder','flo-forms'); ?>:
					<textarea   rows="2" cols="25" onkeyup="updateProperties(jQuery(this), 'placeholder')" onmouseup="updateProperties(jQuery(this), 'placeholder')" name="<?php echo $field_name."[placeholder]"; ?>"><?php echo $field_settings['placeholder']; ?></textarea>
					</label>
					<br/>	
			<?php endif; ?>

            <?php if(isset($available_fields[$field_type]['has_width']) && $available_fields[$field_type]['has_width']):?>
                <label class="field-settings-label">
                    <?php _e('Field width','flo-forms'); ?>:
                    <select class="field-width" name="<?php echo $field_name."[field_width]"; ?>">
                        <option value="width-100" <?php selected( $field_settings['field_width'], 'width-100' ); ?>>100%</option>
                        <option value="width-50" <?php selected( $field_settings['field_width'], 'width-50' ); ?>>50%</option>
                    </select>
                </label>
                <br/>
            <?php endif;?>

			<?php if(isset($available_fields[$field_type]['has_css_class']) && $available_fields[$field_type]['has_css_class']):?>
                <label class="field-settings-label">
                    <?php _e('CSS class (optional)','flo-forms'); ?>: <br/>
                    <input type="text"  class="css-class" value="<?php echo $field_settings['css_class']; ?>" maxlength="255"  name="<?php echo $field_name."[css_class]"; ?>">
                </label>
                <br/>
            <?php endif; ?>
			<?php if(isset($available_fields[$field_type]['can_be_required']) && $available_fields[$field_type]['can_be_required']): ?>
                <label class="field-settings-label">
                    <input type="checkbox" value="1" onclick="(this.checked) ? checkVal = '1' : checkVal = '0';updateProperties(jQuery(this), 'IsRequired')" name="<?php echo $field_name."[required]"; ?>" <?php checked( $required, 1); ?>> <?php _e('Required','flo-forms'); ?>
                </label>
            <?php endif;?>
			<input type="hidden" name="<?php echo $field_name."[type]"; ?>" value="<?php echo $field_settings['type']; ?>">
			<input type="hidden" name="<?php echo $field_name."[id]"; ?>" value="<?php echo $field_settings['id']; ?>">

			<?php 
				echo self::maybe_render_default_input_options($field_settings); 
				echo self::maybe_render_choices_fields_layout_settings($field_settings); 
				echo self::maybe_render_choices_settings($field_settings);
                echo self::maybe_render_textarea_height_option($field_settings);
			?>
		</li>
		<?php
	}

	/**
	 *
	 * Render the field preview wrapper
	 *
	 */
	static public function render_field_wrapper($field_settings = array(),$field_type){

		$field_settings = self::set_default_field_settings($field_settings,$field_type); // make sure we set the proper defaults for the required settings

		$fields_with_choices_layout = array('radio_button','checkbox');
		if( in_array($field_settings['type'], $fields_with_choices_layout) ){
			$field_layout_class = ' field-layout '. $field_settings['field_layout'];
		}else{
			$field_layout_class = '';
		}

		?>

		<li  class="draggable form-preview-row <?php echo $field_settings['type'] .' '. $field_settings['id']. ' ' .$field_settings['field_width'].$field_layout_class.' '.$field_settings['css_class']; ?> width-100" id="<?php echo $field_settings['id']; ?>" title="<?php _e('Click to edit. Drag to reorder','flo-forms'); ?>">
			<label class="desc">
				<span class="the-label"><?php echo html_entity_decode( $field_settings['label'] ); ?></span>
				<span class="req">
					<?php if(isset($field_settings['required']) && $field_settings['required']){
						echo ' *';
					} ?>
				</span>
			</label>

			<?php self::render_field( $field_settings); ?>
			<span class="delete-field dashicons dashicons-trash" title="<?php _e('Delete','flo-forms'); ?>" data-field_id="<?php echo $field_settings['id']; ?>"></span>
			
			<!-- use this hidden input to store the order of the fields -->
			<input type="hidden" value="<?php echo $field_settings['id']; ?>" name="flo_form_settings[fields_order][]">
			<input type="hidden" value="<?php echo $field_settings['id']; ?>" name="flo_form_settings[div_id][]">
		</li>
		<?php
	}

	static public function set_default_field_settings($field_settings,$field_type){

		if(!isset($field_settings['label'])){
			$fields_types = Flo_Form_Meta_Box::$available_fields;

			if(isset($fields_types[$field_type]['label'])){
				$field_settings['label'] = $fields_types[$field_type]['label'];
			}else{
				$field_settings['label'] = __('Untitled','flo-forms');
			}

		}

		if(!isset($field_settings['field_layout'])){
			$field_settings['field_layout'] = 'side-by-side';
		}

		if(!isset($field_settings['field_width'])){
			$field_settings['field_width'] = 'width-100';
		}

		if(!isset($field_settings['default_value'])){
			$field_settings['default_value'] = '';
		}

		if(!isset($field_settings['css_class'])){
			$field_settings['css_class'] = '';
		}

		if(!isset($field_settings['placeholder'])){
			$field_settings['placeholder'] = '';
		}

		if(!isset($field_settings['choices'])){
			$field_settings['choices'] = array(   // only for elements that requires several choices
		  		array(
		  			'label' => __('First choice','flo-forms'),
		  			'preselected' => '',
		  			'id' => 'choice_block_0'
		  		),
		  		array(
		  			'label' => __('Second choice','flo-forms'),
		  			'preselected' => '',
		  			'id' => 'choice_block_1'
		  		),
		  		array(
		  			'label' => __('Third choice','flo-forms'),
		  			'preselected' => '',
		  			'id' => 'choice_block_2'
		  		),
		  	);
		}

		return $field_settings;
	
	}

	static public function render_field( $field_settings = array()){

		$field_type = $field_settings['type'];

		$default_choices = array(
	  		array('label' => __('First choice','flo-forms'), 'preselected' => false, 'id' => 'choice_block_0' ),
	  		array('label' => __('Second choice','flo-forms'), 'preselected' => false, 'id' => 'choice_block_1' ),
	  		array('label' => __('Third choice','flo-forms'), 'preselected' => false, 'id' => 'choice_block_2' ),
	  	);
        $height = isset($field_settings['textarea_height']) &&  $field_settings['textarea_height'] != '' ? $field_settings['textarea_height']: '';
		echo '<div class="field-box-wrap">';
		switch ($field_type) {
			case 'textarea':
				?>
				<textarea <?php if($height != ''):?>style="height: <?php echo $height;?>px"<?php endif;?> class="field textarea medium" placeholder="<?php echo $field_settings['placeholder']; ?>"  rows="6" cols="30" readonly="readonly" disabled="disabled"></textarea>
				<?php
				break;
			case 'message':
					// the message fiels has no input, it has just the label which is rendered above
				break;	
			case 'email':
				?>
				<input type="email" class="field text medium" placeholder="<?php echo $field_settings['placeholder']; ?>" value="" readonly="readonly" disabled="disabled">
				<?php
				break;

			case 'date':
				?>
				<input type="text" readonly="readonly" placeholder="<?php echo $field_settings['placeholder']; ?>" disabled="disabled">
				<?php
				break;

			case 'checkbox':
				if(is_array($field_settings) && isset($field_settings['choices']) && sizeof($field_settings['choices'])){
					$choices = self::create_choices_array($field_settings['choices']);
					self::draw_chkbox_or_rd($type = 'checkbox', $choices );
				}else{
					self::draw_chkbox_or_rd($type = 'checkbox', $default_choices );
				}
				break;

			case 'radio_button':
				if(is_array($field_settings) && isset($field_settings['choices']) && sizeof($field_settings['choices'])){
					$choices = self::create_choices_array($field_settings['choices']);
					self::draw_chkbox_or_rd($type = 'radio', $choices );
				}else{
					self::draw_chkbox_or_rd($type = 'radio', $default_choices );
				}
				break;

			case 'dropdown':
				if(is_array($field_settings) && isset($field_settings['choices']) && sizeof($field_settings['choices'])){
					$choices = self::create_choices_array($field_settings['choices']);
					self::draw_dropdown( $choices );
					//deb_e($choices);
				}else{
					self::draw_dropdown( $default_choices );
				}
				break;

			case 'section_break':
				?>
				<hr class="flo-section-break">
				<?php
				break;
            case 'captcha':
                ?>
                <div>

                </div>
                <?php
                break;

			default: // text field
				?>
				<input type="text" readonly="readonly" placeholder="<?php if(isset($field_settings['placeholder'])){ echo $field_settings['placeholder']; } ?>" disabled="disabled">
				<?php
				break;
		}
		?>
		<span class="field-id"><?php _e('Field ID: '); ?> <p><?php echo $field_settings['id']; ?></p></span>
		<?php
		echo '</div>';
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
	 */
	static public function draw_chkbox_or_rd($type = 'checkbox', $choices = array()){
		//deb_e($choices);
		foreach ($choices as $key => $value) {
			if(isset($value['label'])){
				$the_label = $value['label'];
			}else{
				$the_label = '';
			}
			?>
			<span class="the-choice <?php echo $value['id']; ?>">
				<label class="choice">
					<input   class="field radio" value="" tabindex="1"  readonly="readonly" disabled="disabled" type="<?php echo $type; ?>" >
					<span class="choice__text "><?php echo $the_label; ?></span>
				</label>
			</span>
			<?php
		}
	}



	/**
	 *
	 * render the select input preview
	 *
	 */
	static public function draw_dropdown($choices = array()){

		if(is_array($choices) && sizeof($choices)){
			?>
			<select readonly="readonly" disabled="disabled">
			<?php
			foreach ($choices as $key => $value) {
				if(isset($value['label'])){
					$the_label = $value['label'];
				}else{
					$the_label = '';
				}
			?>
				<option value="<?php echo $the_label; ?>" data-choice_id="<?php echo $value['id']; ?>">
					<?php echo $the_label ?>
				</option>
			<?php
			}
			?>
			</select>
			<?php
		}

	}


	static function create_choices_array($choices_settings){
		$choices = array();
		if(is_array($choices_settings) && sizeof($choices_settings)){
			// from DB the choises array comes in the following format:
			/*[choices] => Array
	        (
	            [id] => Array
	                (
	                    [0] => choice_block_0
	                    [1] => choice_block_1
	                    [2] => choice_block_2
	                )

	            [label] => Array
	                (
	                    [0] => First choice 88
	                    [1] => Second choice 99
	                    [2] => Third choice 66
	                )

	        )*/

	        // because of the format we showed above, we will create a easier to work with array
	        
	        if(isset($choices_settings['id']) ){
	        	// we should get here when we work with an existing field (the data comes from the DB)
		        foreach ($choices_settings['id'] as $key => $value) {
		        	$choices[] = array(
		        		'label' => $choices_settings['label'][$key],
		        		'id' => $choices_settings['id'][$key],
		        	);
		        }
		    }else{
		    	// we should get here when $choices_settings are set manually for the new fields
		    	$choices = $choices_settings;
		    }
		}

		return $choices;
	}

	/**
	 * For the Field settings tab
	 * Render the choices settings for the fields that require them.
	 *
	 */
	static function maybe_render_choices_fields_layout_settings($field_settings){
		$fields_with_choices_layout = array('radio_button','checkbox');

		if( isset($field_settings['type']) && in_array($field_settings['type'], $fields_with_choices_layout) ){
			$field_name = "flo_form_settings[field_settings][".$field_settings['id']."]";

			echo sprintf(__('%s Field layout : %s','flo-forms'),'<br/><label>','<label>');
			?>
			<select name="<?php echo $field_name ?>[field_layout]" class="choice-layout">
				<option value="side-by-side" <?php selected( $field_settings['field_layout'], 'side-by-side' ); ?>><?php _e('Side by side','flo-forms') ?></option>
				<option value="one-column" <?php selected( $field_settings['field_layout'], 'one-column' ); ?>><?php _e('One column','flo-forms') ?></option>
				<option value="two-columns" <?php selected( $field_settings['field_layout'], 'two-columns' ); ?>><?php _e('Two columns','flo-forms') ?></option>
				<option value="three-columns" <?php selected( $field_settings['field_layout'], 'three-columns' ); ?>><?php _e('Three columns','flo-forms') ?></option>
			</select>
			<br/>
			<?php
		}
	}

	/**
	 * For the Field settings tab
	 * Render the choices settings for the fields that require them.
	 *
	 */
	static public function maybe_render_choices_settings($field_settings){

//deb_e($field_settings);

            $fields_with_choices = array('radio_button','checkbox','dropdown');

		if( isset($field_settings['type']) && in_array($field_settings['type'], $fields_with_choices) ){

			// if we have the choices settings already saved

			if(isset($field_settings['choices']['id']) && sizeof($field_settings['choices'])){
				$choices = self::create_choices_array($field_settings['choices']);

			}else{ // if the field is new we will create default sample options

				$choices = array(
					array(
			  			'label' => __('The first choice','flo-forms'),
			  			'preselected' => ''
			  		),
			  		array(
			  			'label' => __('The second choice','flo-forms'),
			  			'preselected' => ''
			  		),
			  		array(
			  			'label' => __('The third choice','flo-forms'),
			  			'preselected' => ''
			  		),
				);
			}
//deb_e($choices);
			echo sprintf(__('%s Choices : %s','flo-forms'),'<br/><label>','<label>');
			foreach ($choices as $key => $choice) { //deb_e($choice);
				if(isset($choice['id'])){
					$choice_id = $choice['id']; // for the new elemetns that
				}else{
					// for the fields saved in the DS that have choices, we use the saved keys
					$choice_id = 'choice_block_'.$key;
				}

				$field_name = "flo_form_settings[field_settings][".$field_settings['id']."]";
			?>
				<div class="choice-block <?php echo $choice_id ?>" data-choice_id="<?php echo $choice_id ?>" data-field_type="<?php echo $field_settings['type']; ?>">
					<input type="hidden" name="<?php echo $field_name; ?>[choices][id][]" value="<?php echo $choice_id ?>">
					<input name="<?php echo $field_name; ?>[preselected_choice]" type="radio" title="<?php _e('Make this choice pre-selected.','flo-forms'); ?>" class="preselected-choice">
					<input class="text" type="text" value="<?php echo $choice['label'] ?>" onkeyup="updateChoice(jQuery(this));" name="<?php echo $field_name; ?>[choices][label][]" >
					<span class="add-new-choice dashicons dashicons-plus-alt" title="<?php _e('Add another choice','flo-forms') ?>"></span>
					<?php if($key > 0){ ?>
						<span class="remove-choice dashicons dashicons-minus" title="<?php _e('Remove this choice','flo-forms') ?>"></span>
					<?php } ?>
				</div>
			<?php
			}
		}


	}

    /**
     * For the Field settings tab
     * Render height option for textarea field.
     *
     */
    static public function maybe_render_textarea_height_option($field_settings){
        if( isset($field_settings['type']) && $field_settings['type'] == 'textarea' ){
                $field_name = "flo_form_settings[field_settings][".$field_settings['id']."]";
                ?>
                    </br>
            <?php $height = isset($field_settings['textarea_height']) && $field_settings['textarea_height'] ? $field_settings['textarea_height'] : '';?>
                <label class="field-settings-label">
                    <?php _e('Height (in px)','flo-forms'); ?>: <br/>
                        <input type="number"  class="textarea_height" value="<?php echo $height; ?>" maxlength="255"  name="<?php echo $field_name."[textarea_height]"; ?>">
                    </label>
            <?php
        }
    }

    static public function maybe_render_default_input_options($field_settings){
    	$field_types = array('text','textarea','email','date', 'hidden');

    	if( isset($field_settings['type']) && in_array($field_settings['type'], $field_types)){
    		$field_name = "flo_form_settings[field_settings][".$field_settings['id']."]";
    		?>
			<br/>
    		<label class="field-settings-label">
			<?php _e('Default value','flo-forms'); ?>:
			<textarea   rows="2" cols="25"  name="<?php echo $field_name."[default_value]"; ?>"><?php echo $field_settings['default_value']; ?></textarea>
			</label>
			<br/>

			<?php
    	}

    }
}

?>
