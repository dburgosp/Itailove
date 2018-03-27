function displayCaptchaOptions() {
	var captchaRow = jQuery('.captcha-row');
	var captchaChecked = jQuery('.captcha-true').attr('checked');

	if(typeof captchaChecked != 'undefined' && captchaChecked == 'checked'){
		captchaRow.show();
	}else{
		captchaRow.hide();
	}
}

(function( $ ) {
	'use strict';

	$( document ).ready(function() {

		
		$( ".flo-form-message" ).each(function( index ) {
			var textarea_id = $( this ).attr('id');
		  
		  // init tiny MCE
			wp.editor.initialize( textarea_id, {
				tinymce: {
					wpautop: true
				},
				quicktags: true,
				mediaButtons: false,
				auto_focus: 'flo-form-msg'
			});
		});
		

		setTimeout(function(){
			
			$("iframe[id^='flo-form-msg']").each(function( index ) {
				//var iFrameDOM = $("iframe[id^='flo-form-msg']").contents();
				var iFrameDOM = $( this ).contents();
				var the_iframe = $( this );
				

				iFrameDOM.find('.mce-content-body>p').on('DOMSubtreeModified', function(){
					console.log(jQuery(this).text());
					
					var parent_id = the_iframe.parents('.field-settings').data('field_id'),
							field_value = jQuery(this).text();
					jQuery('.flo-fields-preview .'+parent_id+' .the-label').text(field_value);
				});
			});

			//var iFrameDOM = $("iframe#flo-form-msg_ifr").contents();
			

		}, 500);
		
		
		
    displayCaptchaOptions();

  	$(function() {
  		if($( "#form-tabs" ).length){
  			$( "#form-tabs" ).tabs({
			  	active: 0
			});
  		}

		});
        
    flo_sortable();

		$("li.add-field, li.form-settings").click(function(){
			// when the 1st and 3rd tabs are clicked we want to remove the focus from any 
			// currently selected fields
			$( ".flo-fields-preview li.draggable" ).removeClass('focused');
		});

		$("li.fields-settings").click(function(){
			// when the field settings tab is clicked we trigger a click
			// on the first available field to make sure we show the settings for the 1st field
			$('.flo-fields-preview li.draggable:first-child').click();
		});


		$( ".form-settings-wrapper" ).on( "click", ".flo-fields-preview li.draggable", function() {
			var field_id = $( this ).attr('id');

			$( ".form-preview li.form-preview-row" ).removeClass('focused');
			$(this).addClass('focused');

			$('.fields-controls .field-settings').removeClass('visible'); // remove the visibale classfrom all the field blocks
		  	$('.fields-controls .'+field_id).addClass('visible'); // make only the selecte field's options visible
		  	$( "#form-tabs" ).tabs( "option", "active", 1 );
		  	$(".field-id-value").html(field_id);
		});

		
		$( ".form-preview" ).on( "click", "li.fid_submit_button", function() {
			var field_id = $( this ).attr('id');

			$( ".flo-fields-preview li.form-preview-row" ).removeClass('focused');
			$(this).addClass('focused');

			$('.fields-controls .field-settings').removeClass('visible'); // remove the visibale classfrom all the field blocks
		  	$('.fields-controls .'+field_id).addClass('visible'); // make only the selecte field's options visible
		  	$( "#form-tabs" ).tabs( "option", "active", 1 );
		});


		$( ".form-settings-wrapper" ).on( "click", ".flo-fields-preview .delete-field", function() {
			var field_id = $( this ).data('field_id');

			$('.fields-controls .'+field_id).remove(); //remove the settings row
			$('#'+field_id).remove(); // remove the preview row

			//show the firs available field
			setTimeout(function(){
				$('.flo-fields-preview .draggable:first-child').click();
			}, 500);

		});

		// remove choice settings
		$( ".flo-fields-preview, #fields-settings" ).on( "click", ".fields-controls .remove-choice", function() {

			// find the specific selectors in order to match the correct options in the preview tab
			var field_id = $(this).parents('.field-settings').data('field_id'),
				choice_id = $(this).parents('.choice-block').data('choice_id'),
				field_type = $(this).parents('.choice-block').data('field_type');

			$(this).parents('.choice-block').remove(); //remove the settings

			// remove the preview choice
			if(field_type == 'radio_button' || field_type == 'checkbox'){
				$('.flo-fields-preview .'+field_id+' .'+choice_id).remove();
			}else if(field_type == 'dropdown'){
				// find the proper drop down by data-choice_id attribute and remove it
				$('.flo-fields-preview .'+field_id+' select option[data-choice_id='+choice_id+']').remove();
			}


		});

		// The confirmation mesage options
		$(".confirmation_opt").change(function () {
			if(this.value == 'text_confirmation'){
				$('.text-confirmation-value').show();
				$('.page-confirmation-value').hide();
			}else if(this.value == 'page_confirmation'){
				$('.text-confirmation-value').hide();
				$('.page-confirmation-value').show();
			}
	    });

		// submit buttom possition dropdown change
		$(".button-possition").change(function () {
	        $('.fid_submit_button div').attr('class',this.value);
	    });

	    // field width dropdown change 
	    $(".fields-controls").on('change','.field-width',function () {
	    	var field_id = $(this).parents('.field-settings').data('field_id'),
	    		preview_row = $('.form-preview li.'+field_id);

	    	preview_row.removeClass('width-50 width-100').addClass($(this).val());
	    });

	    $(".fields-controls").on('change','.choice-layout',function () {
	    	var field_id = $(this).parents('.field-settings').data('field_id'),
	    		preview_row = $('.form-preview li.'+field_id);
	    	preview_row.removeClass('one-column two-columns three-columns side-by-side').addClass($(this).val());
	    });


	    if($('.flo-forms-color-picker').length){
	    	var colorOptions = {
			    change: function(event, ui){
					var field_id = $(this).parents('.field-settings').data('field_id'),
		    			preview_row = $('.form-preview li.'+field_id);
		    		$(preview_row).find('.flo-submit-button').css($(this).data('property'),$(this).val());
		    	}
			};
		    $('.flo-forms-color-picker').wpColorPicker(colorOptions);
	    }

	    // css class input on chage
	   //  $(".fields-controls .css-class").keyup( function() {
	   //  	var field_id = $(this).parents('.field-settings').data('field_id'),
	   //  		preview_row = $('.form-preview li.'+field_id);

	   //  		// add what user types in as the class for the preview row
				// preview_row.addClass($(this).val());

	   //  });

	    // Save form click
	    $("#flo-publishing-action").on( "click", "#flo_publish", function() {
	    	$("#submitdiv #publish").click();
	    	$("#flo-publishing-action .spinner").css('visibility','visible');
	    });

		//add a new choice
		$( ".fields-controls" ).on( "click", ".add-new-choice", function() {
			// find the specific selectors in order to match the correct options in the preview tab
			var field_id = $(this).parents('.field-settings').data('field_id'),
				choice_id = $(this).parents('.choice-block').data('choice_id'),
				current_choice_block = $(this).parents('.choice-block'),
				field_type = $(this).parents('.choice-block').data('field_type'),
				new_field_id = Math.floor((Math.random() * 10000) ) + 100; // generate a random number to user it as the new field id

			var curr_choice_html = current_choice_block.clone().wrap('<p>').parent().html(),
				new_choice_block = curr_choice_html.replace(new RegExp(choice_id, 'g'), 'choice_block_'+new_field_id);

			var new_choice_block_updated;

			// we check against a hardcoded id because the first choice element can not be deleted
			// and thus will always be the same
			if(choice_id == 'choice_block_0'){
				// if the first element is clonned, we want to make sure the remove row button is added to the clone
				new_choice_block_updated = $(new_choice_block).append('<span class="remove-choice dashicons dashicons-minus" title="Remove this choice"></span>');
			}else{
				new_choice_block_updated = new_choice_block;
			}

			jQuery(current_choice_block).after(new_choice_block_updated); // append the new settings


			// add the preview choice
			if(field_type == 'radio_button' || field_type == 'checkbox'){
				var this_choice_preview = $('.flo-fields-preview .'+field_id+' .'+choice_id),
				 	this_choice_preview_html = this_choice_preview.clone().wrap('<p>').parent().html(),
				 	new_choice_preview = this_choice_preview_html.replace(new RegExp(choice_id, 'g'), 'choice_block_'+new_field_id);

				jQuery(this_choice_preview).after(new_choice_preview);

			}else if(field_type == 'dropdown'){
				// find the proper drop down by data-choice_id attribute and remove it
				var this_drop_down_choice_preview = $('.flo-fields-preview .'+field_id+' select option[data-choice_id='+choice_id+']'),
					this_drop_down_choice_preview_html = this_drop_down_choice_preview.clone().wrap('<p>').parent().html(),
					new_drop_down_choice_preview = this_drop_down_choice_preview_html.replace(new RegExp(choice_id, 'g'), 'choice_block_'+new_field_id);

				jQuery(this_drop_down_choice_preview).after(new_drop_down_choice_preview);
			}
		});

        flo_after_drop_event();
        jQuery('.form-settings-wrapper').on('change, keyup','.textarea_height', function(){
            var field_id = jQuery(this).parents('.field-settings').data('field_id'),
                preview_row = jQuery('.form-preview li.'+field_id+' textarea');
            var height = jQuery(this).val();
            preview_row.css('height',height)
        })

	});

})( jQuery );
function flo_after_drop_event(){
    jQuery('.flo-fields-preview li').each(function(){
        var div_id = jQuery(this).parents('div').data('div_id');
        var $el = jQuery(this).find("input[name='flo_form_settings[div_id][]']");
        var $el1 = jQuery(this).find("input[name='flo_form_settings[div_id_1][]']");
        var $el2 = jQuery(this).find("input[name='flo_form_settings[div_id_2][]']");
        var $el3 = jQuery(this).find("input[name='flo_form_settings[div_id_undefined][]']");
        if(div_id) {
            $el.attr('name', 'flo_form_settings[div_id_' + div_id + '][]');
            $el1.attr('name', 'flo_form_settings[div_id_' + div_id + '][]');
            $el2.attr('name', 'flo_form_settings[div_id_' + div_id + '][]');
        }else{
            $el.attr('name', 'flo_form_settings[div_id_1][]');
            $el1.attr('name', 'flo_form_settings[div_id_1][]');
            $el2.attr('name', 'flo_form_settings[div_id_1][]');
        }
    })
}
function flo_add_form_filed(field_type){
	jQuery('.add-field-spinner').css('visibility','visible');
	jQuery('.form-preview').css('opacity','0.5');

	jQuery.ajax({
		url: ajaxurl,
		data: '&action=add_form_field&field_type='+field_type,
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {

			jQuery('.flo-fields-preview.left').append(json.form_field);
			jQuery('#fields-settings ul').append(json.field_settings);

			jQuery('.add-field-spinner').css('visibility','hidden');
			jQuery('.form-preview').css('opacity','1');

      flo_after_drop_event();

      // we need to find the id of the newly created text area to be able to initialize the wp editor
      // The code below explained:
	    // $('<div/>') is a fake <div> that does not exist in the DOM
			// $('<div/>').html(json.field_settings) appends string within that fake <div> as children
			// .contents() retrieves the children of that fake <div> as a jQuery object
      var settings_object = $('<div/>').html(json.field_settings).contents(),
      		textarea_id = settings_object.find('.flo-form-message').attr('id');


      setTimeout(function(){ 
      	
      	wp.editor.initialize( textarea_id, {
					tinymce: {
						wpautop: true,
						
					},
					quicktags: true,
					mediaButtons: false
				});  
      }, 1000);


      setTimeout(function(){
			
				var iFrameDOM = $("iframe#"+textarea_id+'_ifr').contents();

				iFrameDOM.find('.mce-content-body').on('DOMSubtreeModified', function(){
					console.log(jQuery(this).text());
					
					var parent_id = $("iframe#"+textarea_id+'_ifr').parents('.field-settings').data('field_id'),
							field_value = jQuery(this).text();
					jQuery('.flo-fields-preview .'+parent_id+' .the-label').text(field_value);
				});

			}, 1300);
          
		}
	});

}

function updateProperties(obj, field_type){
	var parent_id = obj.parents('.field-settings').data('field_id'),
		field_value = obj.val();

	switch(field_type) {
	    case 'label':
	    	jQuery('.flo-fields-preview .'+parent_id+' .the-label').text(field_value);
	        break;
	    case 'IsRequired':
	        if(jQuery(obj).is(":checked")){
	        	jQuery('.flo-fields-preview .'+parent_id+' .req').text(' *');
	        }else{
	        	jQuery('.flo-fields-preview .'+parent_id+' .req').text('');
	        }
	        break;
	    case 'button_title':
	    	jQuery('.form-preview .'+parent_id +' .flo-submit-button').val(field_value);

	    	break;
	    case 'placeholder':
	    	jQuery('.flo-fields-preview .'+parent_id+' input').attr('placeholder',field_value);
	    	jQuery('.flo-fields-preview .'+parent_id+' textarea').attr('placeholder',field_value);
	    	break;
	    default:
	        //default code block

	}
}


function updateChoice(obj){
	var choice_id = obj.parents('.choice-block').data('choice_id'),
		choice_val = obj.val(),
		filed_id = obj.parents('.field-settings').data('field_id'),
		field_type = obj.parents('.choice-block').data('field_type');

		if(field_type == 'dropdown'){

			var preview_radio_choice_val = jQuery('.flo-fields-preview .'+ filed_id +' select option[data-choice_id='+choice_id+']');

			preview_radio_choice_val.text(choice_val);
			preview_radio_choice_val.val(choice_val);
		}else{
			var preview_choice_val = jQuery('.flo-fields-preview .'+ filed_id +' .'+choice_id+' .choice__text');
			preview_choice_val.text(choice_val);
		}


}
function flo_sortable(){
    if(jQuery( ".left_block .flo-fields-preview" ).length){
        jQuery(function() {
            jQuery('.left_block, .right_block').show().css('visibility','visible');
            jQuery(".left_block .flo-fields-preview").sortable({
                connectWith: '.right_block .flo-fields-preview'
            }).disableSelection();
            jQuery(".right_block .flo-fields-preview").sortable({
                connectWith: '.left_block .flo-fields-preview'
            }).disableSelection();
        });
        jQuery( ".right_block .flo-fields-preview, .left_block .flo-fields-preview" ).sortable({
            update: function( ) {
                flo_after_drop_event();
            }
        });
    }else if(jQuery( ".flo-fields-preview" ).length){
        jQuery( ".flo-fields-preview" ).sortable();
    }
}

function updateForm(obj, form_action){
	if(form_action == 'LabelAlign'){
		jQuery('.form-preview').removeClass('topLabel leftLabel rightLabel').addClass(obj.val());
	}
    if(form_action == 'Nr_Of_Columns'){
        if(obj.val() == '1'){
            var $left_list_of_elements,$right_list_of_elements;
            $left_list_of_elements = jQuery('.left_block .flo-fields-preview').prop('outerHTML');
            $right_list_of_elements = jQuery('.right_block .flo-fields-preview').html();
            jQuery('.row.form-preview').prepend($left_list_of_elements);
            jQuery('.row.form-preview ul.flo-fields-preview').append($right_list_of_elements);
            jQuery('.left_block').html('').hide();
            jQuery('.right_block').html('').hide();
            flo_sortable();
        }else if(obj.val() == '2'){
            var $list_of_elements = jQuery('.flo-fields-preview').prop('outerHTML');
            jQuery('.flo-fields-preview').addClass('copy');
            var $right_list_of_elements = '<ul class="flo-fields-preview ui-sortable"></ul>';
            jQuery('.left_block').html($list_of_elements);
            jQuery('.right_block').html($right_list_of_elements);
            jQuery('.flo-fields-preview.copy').remove();
            jQuery('.left_block, .right_block').show().css('visibility','visible');
            setTimeout(function(){
                jQuery('.right_block').css('min-height','100px');
                flo_sortable();
            }, 200);



        }
    }
}

/**
 *
 * Mark an entry as read or unread
 *
 */
function entryReadUnread(obj){
	var is_read = obj.data('entry_read'),
		post_id = obj.data('post_id');

	jQuery.ajax({
		url: ajaxurl,
		data: '&action=entry_read_unread&is_read='+is_read+'&post_id='+post_id,
		type: 'POST',
		dataType: "json",
		cache: false,
		success: function (json) {
			obj.data('post_id',json.is_read); // update the is_read data attribute
			obj.text(json.text); // update the button text
			obj.parents('tr').toggleClass('entry-read');

		}
	});
}
