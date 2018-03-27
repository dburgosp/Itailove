<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://flothemes.com
 * @since      1.0.0
 *
 * @package    Flo_Forms
 * @subpackage Flo_Forms/public/partials
 */
 //deb_e($form_options);

//form-style1 class can be changed or removed, it is used to give the form a unique style
// if you want to inherit the styles from the theme, then remove that class
// if you want to have several style kits, then use a different class and write its styles

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form class="flo-form">
    <div class="form-style1 <?php echo $form_options['label-placement'];  ?> ">
        <input type="hidden" name="flo_fid" value="<?php echo $atts['id']; // add here the form ID ?>">

        <?php if(isset($form_options['nr_of_columns']) && $form_options['nr_of_columns'] == 2):?>
            <?php if(isset($form_options['div_id_1']) && $form_options['div_id_1'] != '' && !empty($form_options['div_id_1'])):?>
                <div class="left_column">
                    <?php
                    $counter = 1;
                    foreach ($form_options['div_id_1'] as $key => $field_id) {
                        if($counter != sizeof($form_options['div_id_1']) ){
                            $inline_after = '<!--';
                        }else{
                            $inline_after = '';
                        }

                        if($counter != 1){
                            $inline_before = '-->';
                        }else{
                            $inline_before = '';
                        }
                        $field_settings = Flo_Forms_Public::set_default_field_settings($form_options['field_settings'][$field_id]);
                        ?>
                        <?php
                        $additional_classes = $field_settings['type'] == 'hidden'? 'display-none':'';
                        echo $inline_before; ?><div class="field-row <?php echo $additional_classes . $field_settings['field_width'].' '.$field_settings['css_class'] ?>">
                        <?php Flo_Forms_Public::render_field($field_settings); ?>
                        </div><?php echo $inline_after; ?>
                        <?php
                        $counter++;
                    }?>
                </div>
            <?php endif;?>
            <?php if(isset($form_options['div_id_2']) && $form_options['div_id_2'] != '' && !empty($form_options['div_id_2'])):?>
                <div class="right_column">
                    <?php
                    $counter = 1;
                        foreach ($form_options['div_id_2'] as $key => $field_id) {
                            if($counter != sizeof($form_options['div_id_2']) ){
                                $inline_after = '<!--';
                            }else{
                                $inline_after = '';
                            }

                            if($counter != 1){
                                $inline_before = '-->';
                            }else{
                                $inline_before = '';
                            }
                            $field_settings = Flo_Forms_Public::set_default_field_settings($form_options['field_settings'][$field_id]);
                            ?>
                            <?php
                            $additional_classes = $field_settings['type'] == 'hidden'? 'display-none':'';
                            echo $inline_before; ?><div class="field-row <?php echo $additional_classes . $field_settings['field_width'].' '.$field_settings['css_class'] ?>">
                            <?php Flo_Forms_Public::render_field($field_settings); ?>
                            </div><?php echo $inline_after; ?>
                            <?php
                            $counter++;
                        }?>
                </div>
            <?php endif;?>
            <div class="clear"></div>
        <?php else: ?>
            <?php
            $counter = 1;
            foreach ($form_options['fields_order'] as $key => $field_id) {
                if($counter != sizeof($form_options['fields_order']) ){
                    $inline_after = '<!--';
                }else{
                    $inline_after = '';
                }

                if($counter != 1){
                    $inline_before = '-->';
                }else{
                    $inline_before = '';
                }
                $field_settings = Flo_Forms_Public::set_default_field_settings($form_options['field_settings'][$field_id]);
                ?>
                <?php
                $additional_classes = $field_settings['type'] == 'hidden'? 'display-none':'';
                echo $inline_before; ?><div class="field-row <?php echo $additional_classes . $field_settings['field_width'].' '.$field_settings['css_class'] ?>">
                <?php Flo_Forms_Public::render_field($field_settings); ?>
                </div><?php echo $inline_after; ?>
                <?php
                $counter++;
            }?>
        <?php endif;?>

	<?php
		$btn_styles = '';
		if(isset($form_options['field_settings']['fid_submit_button']['btn_bg']) && strlen($form_options['field_settings']['fid_submit_button']['btn_bg']) ){
			$btn_bg = $form_options['field_settings']['fid_submit_button']['btn_bg'];
			$btn_styles .= ' background-color:'.$btn_bg.'; ';
		}

		if(isset($form_options['field_settings']['fid_submit_button']['btn_color']) && strlen($form_options['field_settings']['fid_submit_button']['btn_color'])){
			$btn_color = $form_options['field_settings']['fid_submit_button']['btn_color'];
			$btn_styles .= ' color:'.$btn_color.'; ';
		}
	?>
		<div class="field-row submit-row btn-<?php echo $form_options['field_settings']['fid_submit_button']['position']; ?>">
			<input type="submit" class="flo-submit-button" style="<?php echo $btn_styles; ?>" value="<?php echo $form_options['field_settings']['fid_submit_button']['label']; ?>">
			<div class="flo overlay-loader">
				<div class="loader">
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
		</div>

	</div>

	<div class="flo-response"></div>
</form>


