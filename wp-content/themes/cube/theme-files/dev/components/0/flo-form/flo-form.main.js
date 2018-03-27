$(function(){

    // form submission process
    $('.page').on('submit','.flo-form__built-in',function(e){
		e.preventDefault();

		var form = $(this),
			container = '.contact-response';  // the div for the error response messages

		jQuery('.flo-name').removeClass('invalid');
		jQuery('.flo-email').removeClass('invalid');

		jQuery(container).html('');

		jQuery.ajax({
			url: ajaxurl,
			data: '&action=floSendContact&'+jQuery( form ).serialize(),
			type: 'POST',
			dataType: "json",
	//      cache: false,
			success: function (json) {

				//jQuery('#flo-loading').fadeOut('slow'); // loading effect

				if(json.contact_name ){
					jQuery('.flo-name').addClass('invalid');
					jQuery(container).append(json.contact_name);
				}

				if(json.contact_email ){
					jQuery('.flo-email').addClass('invalid');
					jQuery(container).append(json.contact_email);
				}

				if(json.error_message ){
				
					jQuery(container).append(json.error_message);
				}

				

				if(json.message ){
					jQuery('.flo-modal').fadeIn('slow');

					jQuery( form).find('input[type="text"], textarea').val('');

					setTimeout(function(){
						jQuery('.flo-modal').fadeOut('slow');
					},3000);
				}

			}

		});
	});


    jQuery('.flo-modal .flo-icon__close').click(function(){
    	jQuery('.flo-modal').hide();
    });

});
