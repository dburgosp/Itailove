jQuery(document).ready(function() {

	// On label click, change the input and class
	jQuery(document).on('click', '.acf-image-select label img, .acf-image-select label .acf-image-select-title', function(e) {
		var id         = jQuery(this).closest('label').attr('for');
		var parentList = jQuery(this).closest('ul.acf-image-select-list');
		
		parentList.find('.acf-image-select-selected').removeClass('acf-image-select-selected').find("input[type='radio']").attr("checked",false).removeAttr("data-checked");
		
		jQuery('label[for="' + id + '"]').addClass('acf-image-select-selected').find("input[type='radio']").attr("checked",true).attr("data-checked","checked");
		
	});
	
	flo_init_image_select_thumbs();

	jQuery('#wpbody-content').on('change','.metabox-holder',function(){
		

		setTimeout(function(){
			// 2 seconds delay to wait for the ajax request to update the meta box
			flo_init_image_select_thumbs();
		}, 2000);
		
	});

});


function flo_init_image_select_thumbs(){
	// radio inputs having the class 'flo-image-select' will be transformed
	// into image select inputs
	if(jQuery('.flo-image-select').length){
		var image_url = flo_strings.image_path;
		var img;

		//iterate through each radio input
		jQuery('.flo-image-select .acf-radio-list li input').each(function( index ) {
			// the icon should be in png format and have the same name as 
			// the radio input value.
			// i.e. if the radio input has the value 'full_width' ,  then the icon should be full_width.png

			if(!jQuery( this ).parent().find('img').length){
				// insert the image only if it does not exists yet
				img = '<img src="'+image_url+jQuery( this ).val()+'.png">';
				jQuery( this ).after( img );
			}
			
		  	
		});
	}

}
