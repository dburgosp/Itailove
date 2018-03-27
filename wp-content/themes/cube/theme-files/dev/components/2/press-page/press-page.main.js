$(function(){

	if ($(window).width() > 1024) $('.press-page__link').hover(function() {
		var block_class = $(this).data('block_class');
		$('.figure-'+block_class + ' img').attr('src', $(this).data('img'));
		$('.figure-'+block_class + ' .press-page__featured-image-description').html($(this).data('description'));
	});

});
