window.flo_block_video = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo_block_video";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el;
  var id = parent.attr("data-id");

  /* START: COLOR CHANGE */
    var elements_color = $b.attr("data-elements-color");
    var b_for_custom_css = "flo-block-video-" + id;
    var dotb_for_custom_css = "." + b_for_custom_css;

    parent.find([
      ".flo-header__logo",
      ".flo-header-mobile__logo",
      ".flo-header__menu > div > ul > li > a",
      ".flo-header__search-trigger",
      ".flo-header__search-input"
    ].join(","))
      .customCSS(b_for_custom_css, [
        ".is-not-sticky " + dotb_for_custom_css + "{",
          "color: " + elements_color + "!important;",
          "border-color: " + elements_color + "!important;",
        "}"
      ].join("\n"))
    ;
  /* END: COLOR CHANGE */
}
