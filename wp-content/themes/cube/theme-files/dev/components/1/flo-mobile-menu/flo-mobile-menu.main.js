$(function(){
  var mobile_menu = $(".flo-mobile-menu");

  /* START: DISPLAY/HIDE MOBILE MENU */
    $(".flo-header-mobile__menu-trigger").click(function() {
      $('.flo-mobile-menu').addClass('flo-mobile-menu--visible');
    });

    $(".flo-mobile-menu__close").on("click", function(){
      // Safari needs this...
    });

    $("body").on(
      "click",
      ".flo-mobile-menu:not(.flo-mobile-menu--search-visible) .flo-mobile-menu__close",
      function() {
      console.log("hello");
        $('.flo-mobile-menu').removeClass('flo-mobile-menu--visible');
      }
    );
  /*  END: DISPLAY/HIDE MOBILE MENU */

  /* START: TOGGLE SEARCH */
    $(".flo-mobile-menu__search-toggle")
    .on("click", function(){
      mobile_menu.addClass("flo-mobile-menu--search-visible");
    });

    $("body").on("click", ".flo-mobile-menu--search-visible .flo-mobile-menu__close", function(){
      mobile_menu.removeClass("flo-mobile-menu--search-visible");
    });

  /* END: TOGGLE SEARCH */

  /* START: TOGGLE DROPDOWN */

    /* Start: Add dropdown toggles to every item with dropdown */
      $(".flo-mobile-menu__menu--style-collapsed > ul > li.menu-item-has-children")
        .children("a")
          .after("<div class='flo-mobile-menu__menu-dropdown-toggle'><i class='flo-icon-arrow-right-big'></i></div>")
      ;

      $(".flo-mobile-menu").on("click", ".flo-mobile-menu__menu-dropdown-toggle", function(e){
        $(this).siblings(".sub-menu").slideToggle("slow");
        $(this).parent().toggleClass("children-visible");
      });
    /* End: Add dropdown toggles to every item with dropdown */

  /* END: TOGGLE DROPDOWN */

});
