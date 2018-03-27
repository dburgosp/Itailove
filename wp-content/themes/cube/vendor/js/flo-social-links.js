(function($){
	if($('.flo-social-links--link-custom').length){
		$('.flo-social-links--link-custom').each(function( index ) {
		  	var img = $( this ).find('img'),
		  		orig_img = img.attr('src'),
		  		data_img_hover = img.data('icon_hover');

		  		if('undefined' !== data_img_hover){
		  			img.hover(
		               function () {
		                  img.attr('src',data_img_hover);
		               },

		               function () {
		                  img.attr('src',orig_img);
		               }
		            );
		  		}

		});
	}
})(jQuery);
