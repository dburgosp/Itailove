<!-- <div class="acf-field acf-field-tab " data-type="tab" data-key="field_57ee6c1e6b10e2">
	<div class="acf-label">
		<label for="acf-field_57ee6c1e6b10e2">Google fonts</label>
	</div>
	<div class="acf-input">
		<div class="acf-tab" data-placement="top" data-endpoint="0">Google fonts</div>
	</div>
</div> -->

<div class="no-fonts acf-field acf-field-message" data-type="message" ng-hide="selected_fonts_length > 0">
	<div class="acf-label">
		<label for="">Note:</label>
	</div>
	<div class="acf-input">
  	<?php _e('Currently there are no active Google fonts. Start adding them to the active collection by clicking the button bellow.','flotheme') ?>
	</div>
</div>

<?php if (isset($field['value']['g_fonts']) && is_array($field['value']['g_fonts']) ): ?>
  <?php foreach ($field['value']['g_fonts'] as $key => $g_font): ?>
    <div class="acf-field group_google_font <?php echo $key; ?>">
      <div class="acf-label">
        <label ><?php echo explode('"', $g_font)[1]; ?></label>
      </div>

      <div class="acf-input">

        <div class="font-style-block">

          <div class="font-style-block__top-bar ">
            <div class="font-style-block__top-bar-item " ng-click="deleteGoogleFont('<?php echo $key; ?>')">
              <i class="flo-admin-icon-trash"></i>
            </div>
            <div class="font-style-block__top-bar-item font-style-block__top-bar-item--hover-orange font-style-block__top-bar-item--open-gfontselect">
              <i class="flo-admin-icon-pen"></i>
            </div>
          </div>
          <div class="fontselect">
            <jd-fontselect stack="<?php echo $key ?>"></jd-fontselect>
          </div>

          <div class="font-style-block__paragraph content" style="font-family: {{<?php echo $key ?>}};">
            <input type="hidden" value="{{<?php echo $key ?>}}" name="<?php echo esc_attr($field['name']) ?>[g_fonts][<?php echo $key ?>]" />
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua
          </div>

        </div>

      </div>

    </div>

  <?php endforeach ?>
<?php endif ?>

<div class="acf-field">
  <div class="acf-label">
    <label for=""></label>
  </div>
  <div class="acf-input">
    <jd-add-block></jd-add-block>
  </div>
</div>
