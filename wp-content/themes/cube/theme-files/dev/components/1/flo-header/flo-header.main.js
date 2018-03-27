$(function(){
  /* Start: Logo Center - split menu in half */
    if ($(".flo-header--menu-center").length){
      var
        $menu_donor = $(".flo-header__menu-donor"),
        $menu_donor_ul = $menu_donor.find("> div > ul"),
        $menu_donor_first_level = $menu_donor_ul.children("li"),
        $menu_left = $(".flo-header__menu-wrap--left .flo-header__menu > div > ul"),
        $menu_right = $(".flo-header__menu-wrap--right .flo-header__menu > div > ul")
      ;
      $menu_donor_first_level.each(function(index){
        var
          $item = $(this),
          length = $menu_donor_first_level.length
        ;
        if (index < length / 2) {
          $menu_left.append($item);
        }
        if (index >= length / 2) {
          $menu_right.append($item);
        }
        if (index == length-1) {
          $menu_donor.remove();
        }
      });
    }
  /* End: Logo Center - split menu in half */

  /* Start: Sticky Header */
    var is_not_sticky_class = "is-not-sticky";
    var menu_wrapper__$ = $(".flo-header__menu-wrap");
    var menu_trigger__$ = $(".flo-header__hamburger");
    var logo__$ = $(".flo-header__logo");
    $(".flo-header").addClass(is_not_sticky_class);
    if ( $(window).width() > 767 ) $("body.has-sticky .flo-header")
    .on("sticky-start", function(){
      $(this).removeClass(is_not_sticky_class);

      /* START: CENTER ELEMENTS VERTICALLY */
        var sticky_wrapper__height = $(this).height();

        /* START: CENTER MENU */
          var menu_wrapper__height = menu_wrapper__$.height();
          var menu_wrapper__on_sticky_translateY_value = (sticky_wrapper__height - menu_wrapper__height) / 2;

          menu_wrapper__$.css(
            "transform",
            "translateY("+ menu_wrapper__on_sticky_translateY_value +"px)"
          );
        /* END: CENTER MENU */

        /* START: CENTER MENU TRIGGER */
          var menu_trigger__height = menu_trigger__$.height();
          var menu_trigger__on_sticky_translateY_value = (sticky_wrapper__height - menu_trigger__height) / 2;

          menu_trigger__$.css(
            "transform",
            "translateY("+ menu_trigger__on_sticky_translateY_value +"px)"
          );
        /* END: CENTER MENU TRIGGER */

        /* START: CENTER MENU LOGO */
          var logo__height = logo__$.height();
          var logo__on_sticky_translateY_value = (sticky_wrapper__height - logo__height) / 2;

          logo__$.css(
            "transform",
            "translateY("+ logo__on_sticky_translateY_value +"px)"
          );
        /* END: CENTER MENU LOGO */

      /* END: CENTER ELEMENTS VERTICALLY */
    })
    .on("sticky-end", function(){
      $(this).addClass(is_not_sticky_class);

      /* START: UNCENTER ELEMENTS */
        menu_wrapper__$.css("transform", "");
        menu_trigger__$.css("transform", "");
        logo__$.css("transform", "");
      /* END: UNCENTER ELEMENTS */
    })
    .sticky({
        zIndex: 8,
        className: "is-sticky",
        wrapperClassName: "flo-header-sticky-wrapper"
    });
  /* End: Sticky Header*/

  /* Start: Dropdown */
    var dropdown_elements = new Foundation.DropdownMenu(
      $(".flo-header .menu-item-has-children ul")
    );
  /* End: Dropdown */

  /* Start: Search Trigger */
    var flo_header_search__open_class = "flo-header--search-open";

    $(".flo-header__search-trigger").on("click", function(){
      $(".flo-header").toggleClass(flo_header_search__open_class);
    });
  /* End: Search Trigger */
});
