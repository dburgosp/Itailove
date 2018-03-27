$(function(){

  // Start: Scroll Up Button
    $('.flo-footer__scroll-up, .flo-footer-b__back-to-top').click(function () {
        $('body,html').animate({ scrollTop: 0}, 800);
        return false;
    });
  // End: Scroll Up Button

  /* Start: Dropdown */
    if ($(window).width() > 768) var dropdown_elements = new Foundation.DropdownMenu(
      $(".flo-footer .menu-item-has-children")
    );
  /* End: Dropdown */

});
