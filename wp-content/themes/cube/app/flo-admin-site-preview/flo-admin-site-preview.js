jQuery(function($){
  "use strict";
  var b = "flo-admin-site-preview";
  var dotb = "." + b;
  var $el = $(dotb);
  var $wrap = $el.find(dotb + "__wrap");
  var $preview_wrap = $el.find(dotb + "__preview-wrap");
  var $preview = $el.find(dotb + "__preview");

  /* START: GENERIC FUNCTIONS */
    function preview_refresh() {
      $preview_wrap.addClass('loading');
      $preview.attr("src", $preview.attr("src"));
    }
  /* END: GENERIC FUNCTIONS */

  /* START: CHANGE PREVIEW SIZE */
    $el.find(dotb + "__tool--device").click(function(){
      var $this = $(this);
      var size_to_set = $(this).attr("data-size");
      var current_size = $preview_wrap.attr("data-size");

      /* START: SWITCH BETWEEN LANDSCAPE AND PORTRAIT */
        if (current_size == size_to_set) {
          if (current_size == "tablet" || current_size == "phone") {
            size_to_set = size_to_set + "-landscape";
            $this.addClass(b + "__tool--twisted");
          }
        } else {
          $this.removeClass(b + "__tool--twisted");
        }
      /* END: SWITCH BETWEEN LANDSCAPE AND PORTRAIT */

      /* START: APPLY MOBILE CURSOR TO PHONE AND TABLETS */
        var preview_add_mobile_cursor = function() {
          if (!$preview.contents().find(".flo-admin-site-preview-mobile-cursor-style").length) {
            $preview.contents().find("head").append("<style class='flo-admin-site-preview-mobile-cursor-style'>* {cursor: url("+ $preview.attr("data-mobile-cursor-url") +") 10 10, auto !important;}</style>")
          }
        }
        var preview_remove_mobile_cursor = function() {
          $preview.contents().find(".flo-admin-site-preview-mobile-cursor-style").remove();
        }
        // When switching to/from phone
        $preview.load(function(){
          if (
            size_to_set == "tablet"
            || size_to_set == "tablet-landscape"
            || size_to_set == "phone"
            || size_to_set == "phone-landscape"
          ) {
            preview_add_mobile_cursor();
          } else {
            preview_remove_mobile_cursor();
          }
        });

        // When switching to tablet
        if (
          size_to_set == "tablet"
          || size_to_set == "tablet-landscape"
        ) {
          preview_add_mobile_cursor();
        } else {
          preview_remove_mobile_cursor();
        }
      /* END: APPLY MOBILE CURSOR TO PHONE AND TABLETS */

      /* START: APPLY SIZE */
        $preview_wrap.fadeOut(300, function(){
          $preview_wrap.attr("data-size", size_to_set);

          /* START: REFRESH IF SWITCHING TO PHONE */
            if (
              (
                size_to_set == "phone"
                || size_to_set == "phone-landscape"
              )
              && (
                current_size != "phone"
                && current_size != "phone-landscape"
              )
            ) {
              preview_refresh();
            }
          /* END: REFRESH IF SWITCHING TO PHONE */

          /* START: REFRESH IF SWITCHING FROM PHONE */
            if (
              (
                size_to_set !== "phone"
                && size_to_set !== "phone-landscape"
              )
              && (
                current_size == "phone"
                || current_size == "phone-landscape"
              )
            ) {
              preview_refresh();
            }
          /* END: REFRESH IF SWITCHING FROM PHONE */

          setTimeout(function () {
            $preview_wrap.fadeIn(300);
          }, 10);
        });
      /* END: APPLY SIZE */

      $this.toggleClass(b + "__tool--active");
      $this.siblings().removeClass(b + "__tool--active");
      $this.siblings().removeClass(b + "__tool--twisted");
    });
  /* END: CHANGE PREVIEW SIZE */

  /* START: CLOSE / OPEN */
    // close
    $el.find(dotb + "__tool--close, " + dotb + "__tool--note").click(function(){
      $wrap.removeClass(b + "__wrap--visible");
      $("body").css("overflow", "");
    });
    // open
    $el.find(dotb + "__trigger").click(function(){
      $wrap.addClass(b + "__wrap--visible");
      $("body").css("overflow", "hidden");
      if (!$preview.attr("src")) {
        $preview.attr("src", $preview.attr("data-src"));
      }
    });
  /* END: CLOSE / OPEN */
    $preview.on('load', function(){
      $(this).parents($preview_wrap).removeClass('loading');
    })
  /* START: REFRESH */
    $el.find(dotb + "__tool--refresh").click(function(){
      preview_refresh();
    });
  /* END: REFRESH */
});
