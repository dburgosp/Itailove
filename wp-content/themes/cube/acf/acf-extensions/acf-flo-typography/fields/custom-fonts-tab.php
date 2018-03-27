<!-- <div class="acf-field acf-field-tab acf-field-57ee6de2a88dd" data-type="tab" data-key="field_57ee6de2a88dd2">
	<div class="acf-label">
		<label for="acf-field_57ee6de2a88dd2">Custom fonts</label>
	</div>
	<div class="acf-input">
		<div class="acf-tab" data-placement="top" data-endpoint="0">Custom fonts</div>
	</div>
</div> -->

<div class="acf-field flo-custom-fonts">
  <div class="acf-label">
		<label class="title" for="fpt-upload-fonts">
			Custom Fonts <span class="req-mark"></span>
		</label>
		<p class="description">
				<?php _e('Use the same file name when you are adding multiple files of the same font. It is recommended to have at least a TTF file and a WOFF file for the same font','flotheme') ?>
		</p>
  </div>

  <div class="acf-input">

  	<div class="flo-type-font-uploader">
  	<?php
  		echo acf_field_flo_typography::render_field_fontuploader($field);
  		// deb_e($field['value']);
  		// deb_e($field['value']['custom_fonts']);
  	?>
  	</div>
  </div>

</div>
