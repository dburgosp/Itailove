jQuery(function($){
  var b = "acf-flo-stylekit-shop";
  var dotb = "." + b;
  var $b = $(dotb);

  $b.each(function(){
    var $el = $(this);
    var $preview = $el.find(dotb + "__preview");
    var $stylekit = $el.find(dotb + "__stylekit");

    /* START: PROMO SLIDESHOW */
      $el.find(dotb + "__promo-slideshow").slick({
        adaptiveHeight: true,
        prevArrow: $el.find(dotb + "__promo-slideshow-arrow--prev"),
        nextArrow: $el.find(dotb + "__promo-slideshow-arrow--next")
      });
    /* END: PROMO SLIDESHOW */

    /* START: REVEAL PREVIEW */
      var preview_open_class = b + "--preview-open";
      $stylekit.click(function(){
        var thumbs_timeout = 0;
        var preview_timout = 240;
        if ($el.hasClass(preview_open_class)) {
          thumbs_timeout = 400;
          preview_timout = 0;
        }
        setTimeout(function () {
          $el.addClass(preview_open_class);
        }, thumbs_timeout);
        setTimeout(function () {
          $el.find(dotb + "__right-wrap").slideDown(400);
        }, preview_timout);
      });
    /* END: REVEAL PREVIEW */

    /* START: APPLY ACTIVE CLASS TO THUMBS */
      $stylekit.click(function(){
        var active_class = b + "__stylekit--active";
        var $this_stylekit = $(this);
        $stylekit.removeClass(active_class);
        setTimeout(function () {
          $this_stylekit.addClass(active_class);
        }, 10);
      });
    /* END: APPLY ACTIVE CLASS TO THUMBS */

    /* START: APPLY PREVIEW IMAGE */
      $el.find(dotb + "__stylekit").click(function(){
        var data_preview = $(this).attr("data-preview");
        $preview.attr("data-img-loaded", "false")

        var image = new Image();
        var image_size_interval = setInterval(function () {
          if (typeof image.naturalWidth != "undefined" && image.naturalWidth) {
            clearInterval(image_size_interval);
            image_size_interval = false;
            $preview.attr({
              "data-img-width"  : image.naturalWidth,
              "data-img-height" : image.naturalHeight,
              "data-img-orientation" : image.naturalWidth / image.naturalHeight >= 1 ? "landscape" : "portrait"
            });
          }
        }, 100);
        $(image).on("load", function(){

          $preview.css("background-image", "url(" + data_preview + ")");
          setTimeout(function () {
            $preview.attr("data-img-loaded", "true");
          }, 1500);

          var load_interval = setInterval(function () {
            if (image_size_interval == false) {
              clearInterval(load_interval)
              image = null;
            }
          }, 100);
        });
        image.src = data_preview;
      });
    /* END: APPLY PREVIEW IMAGE */

    /* START: APPLY META TO RIGHT WRAP */
      $stylekit.click(function(){
        $this = $(this);
        $el.find(dotb + "__right-wrap-title").text(
          $this.attr("data-name")
        );
        $el.find(dotb + "__right-wrap-description").text(
          $this.attr("data-description")
        );
        $el.find(dotb + "__right-wrap-price").text(
          $this.attr("data-price")
        );
        $el.find(dotb + "__purchase-button").attr(
          "href",
          $this.attr("data-purchase-url")
        );
      });
    /* END: APPLY META TO RIGHT WRAP */

    /* START: PREVIEW ZOOMING FUNCTIONALITY */
      var preview_navigation_box = $el.find(dotb + "__preview-navigation-box");
      var mouse_position = {};

      $preview
        .on("mouseover", function(){

          var preview_navigation_box_height = preview_navigation_box.height();

          preview = $(this);
          preview.css("background-size", "100%");

          /* Start: Mouse nav for preview image */
            $(document).mousemove(function(e){
              preview_navigation_box_mouse_position = {
                left: e.pageX - preview_navigation_box.offset().left,
                top: e.pageY - preview_navigation_box.offset().top
              };

              var preview_navigation_box_vertical_position =
                preview_navigation_box_mouse_position.top * 100 / preview_navigation_box_height
              ;

              if (preview_navigation_box_vertical_position < 0) {
                preview_navigation_box_vertical_position = 0;
              } else if (preview_navigation_box_vertical_position > 100) {
                preview_navigation_box_vertical_position = 100;
              }

              var container_ratio = $preview.width() / $preview.height();
              var img_ratio = $preview.attr("data-img-width") / $preview.attr("data-img-height");
              var is_img_taller_than_container = img_ratio < container_ratio;
              var is_img_loaded = $preview.attr("data-img-loaded") == "true" ? true : false;
              if (is_img_taller_than_container && is_img_loaded) {
                $preview.css(
                  "background-position",
                  "center " + preview_navigation_box_vertical_position + "%"
                );
              }
            });
          /* End: Mouse nav for preview image */

        })
        .on("mouseleave", function(){
          preview = $(this);
          preview.css("background-size", "");
          preview.css("background-position", "");
          $(document).unbind("mousemove");
        })
      ;
    /* END: PREVIEW ZOOMING FUNCTIONALITY */

  });
});
