$(function(){
  var header_popup__$ = $(".flo-header-popup");
  var header_popup__views_$ = $(".flo-header-popup__views");
  var header_popup__classes = {
    visible : "body--flo-header-popup-visible",
    hidden : "body--flo-header-popup-hidden"
  }
  var header_popup__set = function(state) {
    switch (state) {
      case "visible":
        $("body").addClass(header_popup__classes.visible);
        $("body").removeClass(header_popup__classes.hidden);
      break;

      case "hidden":
        $("body").addClass(header_popup__classes.hidden);
        $("body").removeClass(header_popup__classes.visible);
      break;

      case "initial":
      break;

      default:

    }
  }


  /* START: POPUP TRIGGER */
    var header_popup_trigger__$ = $(".flo-header__hamburger");
    header_popup_trigger__$.on("click", function(){
      header_popup__set("visible");
    });
  /* END: POPUP TRIGGER */

  /* START: HIDE ON ESCAPE */
    $(document).keyup(function(e) {
      if (e.keyCode == 27) {
        header_popup__set("hidden");
        header_popup__set("initial")
      }
    });
  /* END: HIDE ON ESCAPE */

  /* START: SEARCH TRIGGER */
    var search__trigger_$ = $(".flo-header-popup__search-trigger");
    var search__view_$ = $(".flo-header-popup__view--search");

    search__trigger_$.on("click", function(){
      header_popup__views_$.foundation(
        "changeSlide",
        false,
        search__view_$
      );
    });
  /* END: SEARCH TRIGGER */

  /* START: CLOSE BUTTON */
    var close_button__$ = $(".flo-header-popup__close-button");
    close_button__$.on("click", function(){

      first_view__$ = $(".flo-header-popup__view[data-slide=0]")
      first_view__is_active = first_view__$.hasClass("is-active");

      /* Start: if first slide active close popup */
        if (first_view__is_active) {
          header_popup__set("hidden")
        }
      /* End: if first slide active close popup */

      /* Start: if another slide is active set first slide as active */
        if (!first_view__is_active) {
          header_popup__views_$.foundation(
            "changeSlide",
            false,
            first_view__$
          );
        }
      /* End: if another slide is active set first slide as active */

    });
  /* END: CLOSE BUTTON */

});
