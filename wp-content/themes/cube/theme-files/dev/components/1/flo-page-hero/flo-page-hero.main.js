$(function(){

  /* START: VIDEO EMBED */
    $(".flo-page-hero__slide--image_and_video_embed").each(function(){
      active_slide__$ = $(this);
      var video_button = active_slide__$.find(".flo-page-hero__video-button");
      var video_container = active_slide__$.find(".flo-page-hero__slide-video-embed-container");
      var embed_code = active_slide__$.attr("data-embed-code");

      video_button.on("click", function(){
        if (!$("body").hasClass("body--flo-page-hero-video-is-playing")) {
          video_container.html(unescape(embed_code));
          $("body").addClass("body--flo-page-hero-video-is-playing");

          $(".flo-page-hero__slides").slick("slickSetOption", "autoplay", false, true);
        } else if ($("body").hasClass("body--flo-page-hero-video-is-playing")) {
          video_container.html("");
          $("body").removeClass("body--flo-page-hero-video-is-playing");

          var autoplay = $(".flo-page-hero__slides").attr("data-autoplay") == "true" ? true : false;
          $(".flo-page-hero__slides").slick("slickSetOption", "autoplay", autoplay , true);
        }
      });
    });
  /* END: VIDEO EMBED */

  /* START: SLIDESHOW INITIALIZATION */
    $(".flo-page-hero__slides")
    .on("beforeChange", function(){
      var active_slide__$ = $(".flo-page-hero__slide.slick-current");

      /* START: VIDEO EMBED CLOSE ON SLIDE CHANGE */
        if ($("body").hasClass("body--flo-page-hero-video-is-playing")) {
          active_slide__$.find(".flo-page-hero__video-button").click();
        }
      /* END: VIDEO EMBED CLOSE ON SLIDE CHANGE */
    })
    .on("afterChange init", function(e){
      var active_slide__$ = $(".flo-page-hero__slide.slick-current");
      var active_slide__elements_color = active_slide__$.attr("data-elements-color");
      if ( $(".gallery-elements-color").length ) {
        var active_slide__elements_color = $(".gallery-elements-color").attr("data-elements-color");
      }

      /* START: SET LIGHT LOGO ON SLIDESHOW IF NEEDED */
        if (active_slide__elements_color) {
          // Start: Checking Color
            var c = active_slide__elements_color;
            var c = c.substring(1);      // strip #
            var rgb = parseInt(c, 16);   // convert rrggbb to decimal
            var r = (rgb >> 16) & 0xff;  // extract red
            var g = (rgb >>  8) & 0xff;  // extract green
            var b = (rgb >>  0) & 0xff;  // extract blue

            var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
          // End: Checking Color

          var active_slide__elements_color_is_light = luma > 40;

          if (active_slide__elements_color_is_light) {
            $("body").addClass("body--flo-page-hero-elements-color-is-light");
          } else {
            $("body").removeClass("body--flo-page-hero-elements-color-is-light");
          }
        }

      /* END: SET LIGHT LOGO ON SLIDESHOW IF NEEDED */

      /* START: BODY -> SET HAS LOGO CLASS IF NEEDED */
        if (active_slide__$.hasClass("flo-page-hero__slide--has-logo")) {
          $("body").addClass("body--flo-page-hero__slide--has-logo")
        } else {
          $("body").removeClass("body--flo-page-hero__slide--has-logo")
        }
      /* END: BODY -> SET HAS LOGO CLASS IF NEEDED */

      /* START: BODY -> SET HAS VIDEO EMBED CLASS IF NEEDED */
        if (active_slide__$.hasClass("flo-page-hero__slide--image_and_video_embed")) {
          $("body").addClass("body--flo-page-hero__slide--has-video-embed")
        } else {
          $("body").removeClass("body--flo-page-hero__slide--has-video-embed")
        }
      /* END: BODY -> SET HAS VIDEO EMBED CLASS IF NEEDED */

      /* START: CHANGE ELEMENTS COLOR */

        /* Start: Create Elements CSS */
            var elements_css__css = [
            ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) .flo-header__logo, ",
            ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) .flo-header, ",
            ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) .flo-header__menu > div > ul > li > a, ",

            ".flo-header-section .flo-header__logo, ",
            ".flo-header-section .flo-header, ",
            ".flo-header-section .flo-header__menu > div > ul > li > a, ",

            ".flo-page-hero__arrow, ",
            ".flo-page-hero__slide-pretitle, ",
            ".flo-page-hero__slide-title, ",
            ".flo-page-hero__slide-subtitle, ",
            ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__search-trigger, ",
            ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__social-links, ",

            ".flo-page-hero__scroll-down, ",

            ".flo-header-section > .flo-header-mobile .flo-header-mobile__logo, ",
            ".flo-header-section > .flo-header-mobile-sticky-wrapper:not(.is-sticky) .flo-header-mobile .flo-header-mobile__logo ",
            "{",
              "color: " + active_slide__elements_color + ";",
            "}",

            "body:not(.body--flo-page-hero-video-is-playing) .flo-page-hero__video-button",
            "{",
              "color: " + active_slide__elements_color + ";",
              "border-color: " + active_slide__elements_color + ";",
            "}",

            ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__hamburger, ",
            ".flo-header-mobile__menu-trigger",
            ".flo-header-mobile-sticky-wrapper:not(.is-sticky)  .flo-header-mobile__menu-trigger",
            "{",
              // "border-color: " + active_slide__elements_color + ";",
              "box-shadow: 0px 0px 15px -5px " + active_slide__elements_color + ";",
            "}",

            ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) input.flo-header__search-input, ",
            ".flo-header-section input.flo-header__search-input",
            "{",
              "color: " + active_slide__elements_color + ";",
              "border-color: " + active_slide__elements_color + ";",
            "}"
          ].join("\r\n");
        /* End: Create Elements CSS */

        /* Start: Apply Elements CSS */
          var elements_css__wrap_class = "flo-page-hero__elements-css";
          var elements_css__wrap_$ = $("." + elements_css__wrap_class);
          if (elements_css__wrap_$.length) {
            elements_css__wrap_$.html(elements_css__css);
          } else {
            $("head").append(
              "<style class='" + elements_css__wrap_class + "'>" + elements_css__css + "</style>"
            );
          }
        /* End: Apply Elements CSS */

      /* END: CHANGE ELEMENTS COLOR */

      /* START: VIDEO EMBED */
        // Start: Pause all videos
          $(".flo-page-hero__slide--video_slide:not(.slick-current)").find("video").each(function(){
            this.pause();
          });
        // End: Pause all videos

        if (active_slide__$.hasClass("flo-page-hero__slide--video_slide")) {
          var video_container = active_slide__$.find(".flo-page-hero__slide-background-video");
          var video = video_container.find("video")[0];

          video.play();
        }
      /* END: VIDEO EMBED */
    })
    .on("init", function(){
      $(this).find('.slick-list').attr('tabindex', 0).focus();
    })
    .slick({
      prevArrow: '<button type="button" class="flo-page-hero__arrow flo-page-hero__arrow--prev slick-prev"><i class="flo-icon-arrow-left"></i></button>',
      nextArrow: '<button type="button" class="flo-page-hero__arrow flo-page-hero__arrow--next slick-next"><i class="flo-icon-arrow-right"></i></button>',
      arrows: true,
      pauseOnFocus: false,
      dots: false,
      cssEase: "cubic-bezier(0.445, 0.050, 0.550, 0.950)",
    });
  /* END: SLIDESHOW INITIALIZATION */

  /* START: SCROLL DOWN BUTTON */
    $(".flo-page-hero__scroll-down").on("click", function(){
      $('html, body').animate({
        scrollTop: $(window).height()
    }, 400);
    });
  /* END: SCROLL DOWN BUTTON */


});
