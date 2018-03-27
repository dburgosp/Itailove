window.flo_block_slideshow_2 = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo-block-slideshow-2";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var id = parent.attr("data-id");

  $el.find(dotb + "__slides")
    .on("init", function(){
      var $slides = $(this);
      $slides.find('.slick-list').attr('tabindex', 0).focus();
      $el.find("."+b+"__slide--image_and_video_embed").each(function(){
        var active_slide__$ = $(this);
        var video_embed_host = parent;
        var video_button = active_slide__$.find(".flo-hero-video-embed__button");
        var video_container = active_slide__$.find(".flo-hero-video-embed__container");
        var embed_code = active_slide__$.attr("data-embed-code");

        video_button.on("click", function(){
          if (!video_embed_host.hasClass("video-is-playing")) {
            if($slides.attr("data-autoplay") == "true"){
              $slides.slick('slickPause');
            }
            video_container.html(unescape(embed_code));
            video_embed_host.addClass("video-is-playing");
          } else if (video_embed_host.hasClass("video-is-playing")) {
            if($slides.attr("data-autoplay") == "true"){
              $slides.slick('slickPlay');
            }
            video_container.html("");
            video_embed_host.removeClass("video-is-playing");
          }
        });

      });
    })
    /* START: COLOR CHANGE */
      .on("init beforeChange", function(e, slick, currentSlide, nextSlide){
        var elements_color;
        var check_delay;
        if (e.type == "init") {
          check_delay = 100;
          elements_color = $el.find(".slick-current " + dotb +"__slide-content").css("color");
        } else if (e.type == "beforeChange") {
          check_delay = 0;
          elements_color = $el.find(".slick-slide[data-slick-index='"+ nextSlide +"'] " + dotb + "__slide-content").css("color");
        }

        var b_for_custom_css = "flo-block-slideshow-2-" + id;
        var dotb_for_custom_css = "." + b_for_custom_css;

        setTimeout(function () {

          parent.find([
            ".flo-header__logo",
            ".flo-header__menu > div > ul > li > a",
            ".flo-header__search-trigger",
            ".flo-header__search-input"
          ].join(","))
            .customCSS("flo-block-slideshow-2-" + id, [
              ".is-not-sticky " + dotb_for_custom_css + "{",
                "color: " + elements_color + "!important;",
                "border-color: " + elements_color + "!important;",
              "}"
            ].join("\n"))
          ;

        }, check_delay);
      })
    /* END: COLOR CHANGE */
    /* START: CHANGE LOGO BASED ON SLIDE ELEMENTS COLOR */
      .on("init beforeChange", function(e, slick, currentSlide, nextSlide){
        var $this = $(this);
        var $currentSlide = $this.find(".slick-current");
        var elements_color;
        if (e.type == "init") {
          elements_color = $currentSlide.attr("data-elements-color");
        } else if (e.type == "beforeChange") {
          elements_color = $this.find(".slick-slide[data-slick-index='"+nextSlide+"']").attr("data-elements-color");
        }
        var color_brightness = is_color_bright(elements_color);

        if (color_brightness) {
          parent.find(dotb + "__logo").addClass(b + "__logo--light");

          parent.find(".flo-header__logo").addClass("flo-header__logo--light");

          setTimeout(function(){
            parent.find(".flo-header-mobile .flo-header-mobile__logo").addClass("flo-header-mobile__logo--light");
          });
        } else {
          parent.find(dotb + "__logo").removeClass(b + "__logo--light");

          parent.find(".flo-header__logo").removeClass("flo-header__logo--light");

          setTimeout(function(){
            parent.find(".flo-header-mobile .flo-header-mobile__logo").removeClass("flo-header-mobile__logo--light");
          });
        }
      })
    /* END: CHANGE LOGO BASED ON SLIDE ELEMENTS COLOR */

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

    .slick({
      nextArrow: $el.find(dotb + "__arrow--next"),
      prevArrow: $el.find(dotb + "__arrow--prev"),
      pauseOnFocus: false,
      variableWidth: true,
      centerMode: true
    })
  ;
}
