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
			
			// search $el for fields of type 'FIELD_NAME'
			acf.get_fields({ type : 'FIELD_NAME'}, $el).each(function(){
				
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
			
			$(postbox).find('.field[data-field_type="FIELD_NAME"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);

function downloadJson(options_prefix){
	jQuery.ajax({
	    url: ajaxurl,
	    data: '&action=flo_download_options&options_prefix='+options_prefix,
	    type: 'POST',
	    dataType: "json",
	//      cache: false,
	    success: function (json) {
	    	var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(json, undefined, 2));
			var dlAnchorElem = document.getElementById('downloadAnchorElem');
			dlAnchorElem.setAttribute("href",     dataStr     );
			dlAnchorElem.setAttribute("download", "flotheme-options.json");
			dlAnchorElem.click();
	    }
	});
}

function importOptions(obj){

	jQuery('.acf-field-import-export .spinner').css('visibility','visible');

	var import_options = jQuery(obj).parents('.block').find('textarea').val();
	jQuery.ajax({
	    url: ajaxurl,
	    data: '&action=flo_import_options&import_options='+import_options,
	    type: 'POST',
	    dataType: "json",
	//      cache: false,
	    success: function (json) {
	    	jQuery('.acf-field-import-export .spinner').css('visibility','hidden');
	    	jQuery('.acf-field-import-export .response-msg').text(json.message);

	    }
	});

}
