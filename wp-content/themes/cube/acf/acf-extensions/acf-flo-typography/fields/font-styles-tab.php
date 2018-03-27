<?php

//deb_e($field['value']);
	if(isset($field['value']['font_styles'])){

    $hash = 'qwef8580e913efe41ca7d40';
    $floexporturl = get_option( 'floexporturl', $hash );

    if($hash != $floexporturl){
      // replace the export URL saved initially on theme activation with
      // the current site URL
      if( is_string($field['value']['font_styles']) ){
        $font_style_options = str_replace($floexporturl, get_template_directory_uri(), $field['value']['font_styles']);

        $font_style_options = json_decode($font_style_options);
      }else{
        $font_style_options = (array)$field['value']['font_styles'];
      }

    }else{
      if(is_string($field['value']['font_styles'])){
        $font_style_options = json_decode($field['value']['font_styles']);
      }else{
        $font_style_options = (array)$field['value']['font_styles'];
      }

    }

	}


  if(defined('TYPO_DEV') && TYPO_DEV == 'DEV'){
    // in development mode while we are preparing the default fonts list, it is convinient
    // to work directly with the default fonts list to avoid spending time
    // on saving the typography options every time a change to the default fonts array is made
    // WORK with default options set in the config file
    $default_fonts_set = apply_filters( 'flo_set_default_theme_fonts', array() ); // returns the default fonts specified in .../theme-files/app/fonts-theme-default.php

    $default_theme_fonts = $default_fonts_set;

  }else if(isset($field['value']['font_styles']) && isset($font_style_options) && isset($font_style_options->theme_defaults) ){
		// if the  typography options are saved in the DB, we use them
		$default_theme_fonts = $font_style_options->theme_defaults;

	}else{
		// if the typography options are not yet saved, we get the default options
		$default_theme_fonts = self::get_theme_default_fonts_list();
	}

  // proces the $default_theme_fonts to make sure we do not have ampty array which do not work properly
  $default_theme_fonts = self::process_theme_fonts($default_theme_fonts);

  // the original theme fonts will be used to restore to default values if user wants that
  $original_default_fonts_set = apply_filters( 'flo_set_default_theme_fonts', array() ); // returns the default fonts specified in .../theme-files/app/fonts-theme-default.php

	if(isset($field['value']['font_styles']) && isset($font_style_options) && isset($font_style_options->user_specified) ){
		$user_specified = $font_style_options->user_specified;
	}else{
		$user_specified = new stdClass();
	}

	//deb_e($default_theme_fonts);

	$all_fonts_list = self::get_all_fonts($field['value']);
  $all_fonts_list = $all_fonts_list['fonts_list'];
//deb_e($all_fonts_list);

	$flo_font_styles = array(
			'theme_defaults' => $default_theme_fonts,
			'user_specified' => $user_specified, // we need this as a object
      'original_theme_defaults' => $original_default_fonts_set
		);

  /* START: SORT ARRAY ALPHABETICALLY */
    function cmp($a, $b) {
        return strcmp($a["font_style_name"], $b["font_style_name"]);
    }

    if(is_array($flo_font_styles["theme_defaults"])){
      uasort($flo_font_styles["theme_defaults"], "cmp");
    }
  /* END: SORT ARRAY ALPHABETICALLY */

	// deb_e($flo_font_styles["theme_defaults"]);

	wp_localize_script( 'acf-input-typography', 'flo_font_styles', $flo_font_styles);
	wp_localize_script( 'acf-input-typography', 'all_fonts_list', $all_fonts_list);

  if(isset($_GET['font_url_fix']) && $_GET['font_url_fix'] == 1 ){
    $font_replace_class = '';
  }else{
    $font_replace_class = 'hidden';
  }
?>
  <div class="<?php echo $font_replace_class; ?>">
    This button is used to fix the fonts URLs after migrating from one domain to another <br/>
    <input class="button button-primary button-large" type="button"  onclick="importReplaceDemoFonts()" value="Replace Demo Fonts">
    <span class="spinner import-replace-dummy-font"></span>

    <br/><br/><br/>
  </div>

<!-- <div class="acf-field acf-field-tab acf-field-57ee6de2a8812d" data-type="tab" data-key="field_57ee6de2a8812d">
	<div class="acf-label">
		<label for="acf-field_57ee6de2a8812d">Font Styles</label>
	</div>
	<div class="acf-input">
		<div class="acf-tab" data-placement="top" data-endpoint="0">Font Styles</div>
	</div>
</div> -->


<!-- default theme fonts

user specified -->
    <?php ob_start(); ?>
      <div class="acf-label">
        <label>{{font.font_style_name}}</label>
      </div>
      <div class="acf-input">

        <div class="font-style-block {{setContrastBg(font, 'default')}}">

          <div class="font-style-block__top-bar">
            <div class="font-style-block__top-bar-item" title="<?php _e('Reset to default','flotheme'); ?>" ng-show="font.allow_reset == 1">
              <i class="dashicons dashicons-image-rotate" ng-click="resetToDefaultFont(name)"></i>
            </div>
            <div class="font-style-block__top-bar-item" ng-click="deleteFontStyle(key)" ng-show="font.allow_reset != 1">
              <i class="flo-admin-icon-trash"></i>
            </div>
            <div class="font-style-block__top-bar-item font-style-block__top-bar-item--hover-orange" data-edit="{{name}}"  ng-click="floShowModal(font, name)">
              <i class="flo-admin-icon-pen"></i>
            </div>
          </div>

          <div class="font-style-block__paragraph" style="{{setStyleTypography( font, 'default')}}">
            Lorem ipsum dolor sit amet, consectetur
          </div>

          <style>
            {{floGetFontFace(font['font_family'], font['font_url'])}}
          </style>

        </div>

      </div>
    <?php $font_style_block = ob_get_clean(); ?>

		<div class="acf-field" ng-repeat="(name,font) in default_font_styles" on-finish-render="flo_maybe_open_modal()">
      <?php echo $font_style_block; ?>
		</div>

		<div class="acf-field" ng-repeat="(key,font) in font_styles.user_specified">
      <?php echo $font_style_block; ?>
		</div>

    <div class="acf-field">
      <div class="acf-label">
        <label for=""></label>
      </div>
      <div class="acf-input">

    		<div class="font-style-add" ng-click="addFontStyle()">
    			<?php _e('Add Font Style','flotheme'); ?>
    		</div>
		    <textarea id="<?php echo $field['key'] ?>" name="<?php echo  $field['name'] ?>[font_styles]"   style=" display:none!important ">{{font_styles | json}}</textarea>

      </div>
    </div>


	<!-- The Modal -->
  <div class="font-style-modal">

    <div class="font-style-modal__wrap">

        <div class="font-style-block__top-bar-item" title="<?php _e('Reset to default','flotheme'); ?>" ng-show="popup.allow_reset == 1">
          <i class="dashicons dashicons-image-rotate" ng-click="resetToDefaultFont(popup.font_style_key)"></i>
        </div>
        <div class="font-style-modal__close flo-admin-icon-close" title="close" onclick="floCloseModal();">
        </div>

        <div class="font-style-modal__half font-style-modal__half--with-divider">

          <div class="font-style-modal__title">
            General Styling
          </div>

          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Font Style name:','flotheme') ?>
            </div>
            <div class="font-style-modal__input">
              <input type="text" ng-model="popup.font_style_name">
            </div>
          </div>

          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Font Family:','flotheme') ?>
              <span class="add-google-font" title="<?php _e('Add a new Google font to the dropdown below','flotheme') ?>">+ <?php _e('Add Google Font','flotheme'); ?></span>
              <span class="add-custom-font" title="<?php _e('Add a new Custom font to the dropdown below','flotheme') ?>">+ <?php _e('Add Custom Font','flotheme'); ?></span>
            </div>
            <div class="font-style-modal__input">
              <select ng-model="popup.font_family" ng-change="floFontChange(jQuery(this))">
                <option ng-repeat="(key, value) in fonts_list" value="{{key}}" data-font_type="{{value.font_type}}" data-font_info="{{value.font_info}}">{{key}}</option>
              </select>
            </div>
          </div>

          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Font size (in px):','flotheme') ?>
            </div>
            <div class="font-style-modal__input">
              <div ui-slider min="5" max="200" ng-model="popup.font_size" class="flo-admin-slider"></div>
              <div class="flo-admin-slider-description">{{ popup.font_size }}px</div>
            </div>
          </div>

          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Mobile Font size (in px):','flotheme') ?>
            </div>
            <div class="font-style-modal__input">
              <div ui-slider min="5" max="200" ng-model="popup.font_size_mobile" class="flo-admin-slider"></div>
              <div class="flo-admin-slider-description">{{ popup.font_size_mobile }}px</div>
            </div>
          </div>

          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Letter spacing (in em):','flotheme') ?>
            </div>
            <div class="font-style-modal__input">
              <div ui-slider min="-2" max="2" step="0.01" use-decimals ng-model="popup.letter_spacing" class="flo-admin-slider"></div>
              <div class="flo-admin-slider-description">{{ popup.letter_spacing }}em</div>
            </div>
          </div>

          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Word spacing (in em):','flotheme') ?>
            </div>
            <div class="font-style-modal__input">
              <div ui-slider min="-2" max="2" step="0.01" use-decimals ng-model="popup.word_spacing" class="flo-admin-slider"></div>
              <div class="flo-admin-slider-description">{{ popup.word_spacing }}em</div>
            </div>
          </div>
          <div class="font-style-modal__field">
            <div class="font-style-modal__label">
              <?php _e('Line height (in em):','flotheme') ?>
            </div>
            <div class="font-style-modal__input">
              <div ui-slider min="1" max="3" step="0.01" use-decimals ng-model="popup.line_height" class="flo-admin-slider"></div>
              <div class="flo-admin-slider-description">{{ popup.line_height }}em</div>
            </div>
          </div>


        </div>


        <div class="font-style-modal__half">

          <div class="font-style-modal__title">
            Specific Styling
          </div>

          <div class="font-style-modal__tabs">
            <ul>
              <li>
                <a href="#tabs-styles-default">
                  <i class="flo-admin-icon-paragraph"></i>
                  <?php _e('Default','flotheme') ?>
                </a>
              </li>
              <li>
                <a href="#tabs-styles-hover_state">
                  <i class="flo-admin-icon-link"></i>
                  <?php _e('Hover','flotheme') ?>
                </a>
              </li>
              <li>
                <a href="#tabs-styles-active">
                  <i class="flo-admin-icon-link"></i>
                  <?php _e('Active','flotheme') ?>
                </a>
              </li>
            </ul>

            <div class="font-style-modal__divider"></div>

            <?php
            $font_styles_modal__specific_tabs = [
              "default",
              "hover_state",
              "active"
            ];

            foreach ($font_styles_modal__specific_tabs as $tab_name) {
              ?>
              <div id="tabs-styles-<?php echo $tab_name ?>">
                <?php

    					    acf_field_flo_typography::get_state_options($tab_name);
                ?>

                <?php
    					    acf_field_flo_typography::get_text_for_preview($tab_name);
    				    ?>
              </div>
              <?php
            }
            ?>
          </div>

        </div>

    </div>

  </div>
	<!-- <textarea id="<?php //echo $field['key'] ?>" name="<?php //echo  $field['name'] ?>[font_styles]" ng-model="font_styles" style=" /*display:none!important*/ "></textarea> -->
