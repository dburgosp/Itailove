(function( $ ) {
	'use strict';

	$(document).ready(function() {
		$(".flo-form").validate({
		  	submitHandler: function(form) {

		    	$('.flo-response').html('');
		    	$('.flo.overlay-loader').show();

		    	jQuery.ajax({
					url: formData.ajaxurl,
					data: '&action=flo_form_submit&formNonce='+formData.formNonce+'&'+jQuery( form ).serialize(),
					type: 'POST',
					dataType: "json",
					cache: false,
					success: function (json) {
						$('.flo.overlay-loader').hide();
						console.log(json);
						//$('.flo-response').html(json.msg);

						if(json.error){
							$('.flo-response').html(json.error);
						}else if(json.confirmation_opt && json.confirmation_opt == 'text_confirmation'){
							$('.flo-response').html(json.success_msg);
							resetForm('.flo-form');
						}else if(json.confirmation_opt && json.confirmation_opt == 'page_confirmation'){
							window.location = json.success_page;
						}

					}

				});
		  	}
		});

		$('.date-input').datepicker({
			dateFormat: formData.date_format,
			minDate: 0
		});
    if(!jQuery('.right_column').length){
        jQuery('.left_column').css('width','100%');
    }
	});


})( jQuery );

// reset the form inputs after the form is submited
function resetForm($form) {
    jQuery($form).find('.field-row input[type="text"],.field-row input[type="email"], .field-row select, .field-row textarea').val('');
    jQuery($form).find('.field-row input:radio, .field-row input:checkbox').removeAttr('checked').removeAttr('selected');
}
