$(function(){


    // START: DISPLAY MOBILE MENU
	    $('.flo-icon-sidebar').click(function () {
	      $('.flo_page_wrap').find('.flo_sidebar').toggleClass('flo_sidebar--display');
	      $("body").toggleClass("flo_sidebar--active");
	    });
  	// END: DISPLAY MOBILE MENU

  	// START: SET PADDING TOP FOR SIDEBAR
    	$(".flo_sidebar").css("top", $(".flo-header-mobile").outerHeight(true) );
  	// END: SET PADDING TOP FOR SIDEBAR

});
