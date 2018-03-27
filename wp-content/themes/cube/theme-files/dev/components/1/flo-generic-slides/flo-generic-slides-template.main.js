$(document).on("floInit", ".flo-generic-slides", function(e, slickAdditionalOptions){
  "use strict";

  var $el = $(this);
  var b = "flo-generic-slides";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var parent_class = "flo-block" + parent.attr("data-id");

  /* START: MERGE DEFAULT SLICK OPTIONS WITH ADDITIONAL ONES */
    var slickOptions = {
      "dots": "false",
      "arrows": "false",
      "cssEase": "cubic-bezier(0.23, 1, 0.32, 1)",
    };
    for (var attrname in slickAdditionalOptions) {
      slickOptions[attrname] = slickAdditionalOptions[attrname];
    }
  /* END: MERGE DEFAULT SLICK OPTIONS WITH ADDITIONAL ONES */

  $el
    /* START: VIDEO BACKGROUND */
      .on("init reInit afterChange", function(){
        // Start: Pause all videos
          $el.find(dotb + "__slide--video_slide:not(.slick-current)").find("video").each(function(){
            this.pause();
          });
        // End: Pause all videos
        var active_slide__$ = $el.find(".slick-current");
        if (active_slide__$.hasClass(b + "__slide--video_slide")) {
          var video_container = active_slide__$.find(dotb + "__slide-background-video");
          var video = video_container.find("video")[0];

          video.play();
        }
      })
    /* END: VIDEO BACKGROUND */

    /* START: VIDEO EMBED */
      .on("init", function(){
        var $slides = $(this);
        $el.find("."+b+"__slide--image_and_video_embed").each(function(){
          var active_slide__$ = $(this);
          var video_embed_host = parent;
          var video_button = active_slide__$.find(".flo-hero-video-embed__button");
          var video_container = active_slide__$.find(".flo-hero-video-embed__container");
          var embed_code = active_slide__$.attr("data-embed-code");

          video_button.on("click", function(){
            if (!video_embed_host.hasClass("video-is-playing")) {
              video_container.html(unescape(embed_code));
              video_embed_host.addClass("video-is-playing");

              $slides.slick("slickSetOption", "autoplay", false, true);
            } else if (video_embed_host.hasClass("video-is-playing")) {
              video_container.html("");
              video_embed_host.removeClass("video-is-playing");

              var autoplay = $slides.attr("data-autoplay") == "true" ? true : false;
              $slides.slick("slickSetOption", "autoplay", autoplay , true);
            }
          });
        });
      })
      .on("beforeChange", function(){
        var active_slide__$ = $(this).find(".slick-current");
        var video_embed_host = parent;

        /* START: VIDEO EMBED CLOSE ON SLIDE CHANGE */
          if (video_embed_host.hasClass("video-is-playing")) {
            active_slide__$.find(".flo-hero-video-embed__button").click();
          }
        /* END: VIDEO EMBED CLOSE ON SLIDE CHANGE */

      })
    /* END: VIDEO EMBED */

    /* START: CHANGE LOGO BASED ON SLIDE ELEMENTS COLOR */
      .on("init afterChange", function(){
        var $this = $(this);
        var $currentSlide = $this.find(".slick-current");
        var elements_color = $currentSlide.attr("data-elements-color");
        var color_brightness = is_color_bright(elements_color);

        if (color_brightness) {
          parent.find(dotb + "__logo").addClass(b + "__logo--light");

          parent.find(".flo-header__logo").addClass("flo-header__logo--light");

          setTimeout(function(){
            parent.find(".is-main .flo-header-mobile__logo").addClass("flo-header-mobile__logo--light");
          });
        } else {
          parent.find(dotb + "__logo").removeClass(b + "__logo--light");

          parent.find(".flo-header__logo").removeClass("flo-header__logo--light");

          setTimeout(function(){
            parent.find(".is-main .flo-header-mobile__logo").removeClass("flo-header-mobile__logo--light");
          });
        }
      })
    /* END: CHANGE LOGO BASED ON SLIDE ELEMENTS COLOR */

    /* START: FIRE EVENTS ON LIGHT OR DARK ELEMENTS COLOR */
      .on("init afterChange", function(){
        var $this = $(this);
        var $currentSlide = $this.find(".slick-current");
        var elements_color = $currentSlide.attr("data-elements-color");
        var color_brightness = is_color_bright(elements_color);

        if (color_brightness) {
          $this.trigger("elementsColorLight", elements_color)
        } else {
          $this.trigger("elementsColorDark", elements_color)
        }
      })
    /* END: FIRE EVENTS ON LIGHT OR DARK ELEMENTS COLOR */

    /* START: INITIALIZATION */
      .slick(slickOptions)
    /* END: INITIALIZATION */
  ;
});
