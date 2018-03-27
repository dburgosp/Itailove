(function($){


	function initialize_field( $el ) {

		//$el.doStuff();

	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		acf.add_action('ready append', function( $el ){

			// search $el for fields of type 'font_style_select'
			acf.get_fields({ type : 'font_style_select'}, $el).each(function(){

				initialize_field( $(this) );

				// when clicking on advanced button
				$('.acf-field-font-style-select').unbind("click").on('click','.advanced-font-style-select',function(){
					jQuery(this).parents('.acf-input').find('.css-selector-block,.oppened,.closed').fadeToggle();
				})
				// for some reason the click is triggered twice, therefore we unbind the click first
				$(".acf-input .dropdown").unbind("click").on('click', '.selected-style', function() {
					//event.stopPropagation();
					$(this).parents(".dropdown").find("dd ul").fadeToggle();
				});


	            $(".dropdown dd ul li a").click(function() {
	                var edit_font_style_url,
	                	new_edit_font_style_url,
	                	to_replace,
	                	text = $(this).html();
	                $(this).parents('.acf-field-font-style-select').find(".dropdown dt div .selected-f-style").html(text);
	                $(".dropdown dd ul").hide();
	                //console.log($(this).parents('.acf-input').attr('class'));
	                $(this).parents('.acf-field-font-style-select').find(".selected-font-style").val(getSelectedValue(jQuery(this)));

	                // update the 'Edit font style URL as well'
	                edit_font_style_url = $(this).parents('.acf-field-font-style-select').find(".edit-current-font-style").attr('href');
	                to_replace = floGetParameterByName('edit_font_style', edit_font_style_url); // get the current 'edit_font_style' URL value
	                // replace the current 'edit_font_style' with the newly selected value
	                new_edit_font_style_url = edit_font_style_url.replace(to_replace, getSelectedValue(jQuery(this)));
	                // update the Edit font style URL with the updated link
	                $(this).parents('.acf-field-font-style-select').find(".edit-current-font-style").attr('href', new_edit_font_style_url);

	            });

	            function getSelectedValue(obj) {
	                return $(obj).find("span").data('value');
	            }

	            $(document).bind('click', function(e) {
	                var $clicked = $(e.target);
	                if (! $clicked.parents().hasClass("dropdown"))
	                    $(".dropdown dd ul").hide();
	            });

			});

		});

	} else {

		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM.
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="font_style_select"]').each(function(){

				initialize_field( $(this) );

			});

		});


	}


})(jQuery);
