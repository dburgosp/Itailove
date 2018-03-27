$(function(){
    if( $(".flo-featured-posts__img img").length ){
		setTimeout(function(){
			BackgroundCheck.init({
				windowEvents: true,
				targets: '.flo-featured-posts__featured-post:not(.flo-featured-posts--layout-grid-has-excerpt) .flo-featured-posts__text',
				images: ".flo-featured-posts__img img"
			});
		});
  	}
});
