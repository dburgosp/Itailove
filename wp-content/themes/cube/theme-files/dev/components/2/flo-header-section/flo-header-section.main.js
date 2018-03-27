$(function(){
  /* Start: Scroll Down Button */
      $(".flo-header-section").on('click', '.flo-section__scroll-down', function(e) {
          e.preventDefault();
          $('html,body').animate({scrollTop: $('main').offset().top});
      });
  /* End: Scroll Down Button */
});
