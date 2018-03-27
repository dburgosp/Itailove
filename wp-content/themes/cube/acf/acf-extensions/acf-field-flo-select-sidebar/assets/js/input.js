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
			
			// search $el for fields of type 'select_sidebar'
			acf.get_fields({ type : 'select_sidebar'}, $el).each(function(){
				
				initialize_field( $(this) );
				
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
			
			$(postbox).find('.field[data-field_type="select_sidebar"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);

function updateLayoutMeta(post_type,layout_meta,sidebar_meta){
    if(confirm('Are you sure you want to the layout for all posts ?.')){
    	var layout_meta_value, sidebar_meta_value;
    	layout_meta_value = jQuery('.acf-field.'+layout_meta+' .acf-input select option:selected').val();
    	sidebar_meta_value = jQuery('.acf-field.'+sidebar_meta+' .acf-input select option:selected').val();
        jQuery('.spinner-container .spinner').show();
        jQuery('.spinner-container .spinner').css({
            'visibility':'visible'
        });

        jQuery.ajax({
            url: ajaxurl,
            data: '&action=updateLayoutMeta&layout_meta='+layout_meta_value+'&sidebar_meta='+sidebar_meta_value+'&post_type='+post_type,
            type: 'POST',
            dataType: "json",
            cache: false,
            success: function (json) {

                jQuery('.spinner-container .spinner').hide();

            },
            error: function (xhr) {
                jQuery('.spinner-container .spinner').hide();
            }
        });

    }



}


