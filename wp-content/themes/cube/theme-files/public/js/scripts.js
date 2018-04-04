(function($){function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}

jQuery.fn.extend({
  changeText: function(text){
    return this.each(function(){
      var $el = $(this);
      if ($el.text() !== text ) {
        $el
          .animate({"opacity": 0}, 200)
        ;
        setTimeout(function(){
          $el.text(text);
          $el
            .animate({"opacity": 1}, 200)
          ;
        }, 200);
      }
    });
  },
  changeTextUI: function(text, animation, speed){
    if (typeof animation === "undefined") {
      var animation = "fade";
    }
    if (typeof speed === "undefined") {
      var speed = 400;
    }
    return this.each(function(){
      var $el = $(this);

      var animation_map = {
        fade: {
          name: "fade",
          show_attr: {
          },
          hide_attr: {
          }
        },
        counter: {
          name: "slide",
          show_attr: {
            direction: "down"
          },
          hide_attr: {
            direction: "up"
          }
        },
        slide_left: {
          name: "drop",
          show_attr: {
            direction: "left"
          },
          hide_attr: {
            direction: "right"
          }
        },
        slide_right: {
          name: "drop",
          show_attr: {
            direction: "right"
          },
          hide_attr: {
            direction: "left"
          }
        },
        drop_up: {
          name: "drop",
          show_attr: {
            direction: "up"
          },
          hide_attr: {
            direction: "down"
          }
        },

      }

      // if ($el.text() !== text ) {
        // $el
        //   .animate({"opacity": 0}, 200)
        // ;
        $el.hide(animation_map[animation].name, animation_map[animation].show_attr, speed / 2);
        setTimeout(function () {
          $el.text(text);
        }, speed / 2);
        // setTimeout(function(){
          // $el
          //   .animate({"opacity": 1}, 200)
          // ;
            $el.show(animation_map[animation].name, animation_map[animation].hide_attr, speed / 2);
        // }, speed);
      // }
    });
  },
  changeCSS: function(property, value){
    return this.each(function(){
      var $el = $(this);
      if ($el.css(property) !== value ) {
        $el
          .animate({"opacity": 0}, 200)
          // .css("transform", "translateY(-0.3rem)")
          // .css("transition", "transform 0.8s, color 0.4s")
        ;
        setTimeout(function(){
          $el.css(property, value);
          $el
            .animate({"opacity": 1}, 200)
            // .css("transform", "translateY(-0rem)")
            // .css("transition", "")
          ;
        }, 200);
      }
    });
  },
  customCSS: function(custom_class, css) {
    return this.each(function(){
      var $el = $(this);
      if (custom_class && css) {
        $el.addClass(custom_class);
        if ($("style.flo-custom-css--" + custom_class).length) {
          $("style.flo-custom-css--" + custom_class).html(css);
        } else {
          $("head").append(
            $(" <style class='flo-custom-css--" + custom_class + " '> ").html(css)
          );
        }
      }
    });
  }
});


// A function that tells if a color is light(true) or dark (false)
function is_color_bright(color) {
  if (color && color.length == 7 && color[0] == "#") {
    var c = color;
    var c = c.substring(1);      // strip #
    var rgb = parseInt(c, 16);   // convert rrggbb to decimal
    var r = (rgb >> 16) & 0xff;  // extract red
    var g = (rgb >>  8) & 0xff;  // extract green
    var b = (rgb >>  0) & 0xff;  // extract blue

    var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709

    return luma > 40;
  } else {
    console.log("Color Brightness: Invalid Color String");
    return 0;
  }
}


$(function(){
  $(document).foundation();
  $(document).flo_lqip();



  // START: BLOCK SCRIPTS
    $("[data-onready]").each(function(){
      window[$(this).attr("data-onready")](this);
    });
  // END: BLOCK SCRIPTS


  /* Start: animate section appearance - viewport checker */
    $(window).on("startViewportChecker", function(){

      $(
        [
          ".flo-block:not(:first-child)",
          ".flo_page > .flo-section:not(:first-of-type):not(.disable-appear)",
          "footer",
          ".to-appear",
          ".to-appear--custom",
          ".flo-post > *",
          ".widget"
        ].join(",")
      )
        .addClass("flo-to-be-visible")
        .viewportChecker({
          classToAdd: 'visible',
          repeat: true,
          offset: 40,
          invertBottomOffset: false
        })
      ;

    }).trigger("startViewportChecker");
  /* End: animate section appearance - viewport checker */


    /* START: MOBILE COOKIE */

    // add the cookie that is used to detect mobile and retina screens
    (function(){

        var is_mobile_screen,
            is_tablet_screen,
            mobile_cookie_name = "flo_small_screen",
            tablet_cookie_name = "flo_tablet_screen",
            mobile_cookie = floGetCookie(mobile_cookie_name), // Can return "1", "0", null;
            tablet_cookie = floGetCookie(tablet_cookie_name), // Can return "1", "0", null;
            set_mobile = function(value) {
                createCookie(mobile_cookie_name, value, 1);
            },
            set_tablet = function(value) {
                createCookie(tablet_cookie_name, value, 1);
            },

        //  we consider screens larger than 760 not beeing mobile
        is_mobile_screen = document.documentElement.clientWidth <= 760;

        is_tablet_screen = (document.documentElement.clientWidth >= 761 && document.documentElement.clientWidth <= 1024);

        if (is_mobile_screen) {
            if (mobile_cookie === '' || mobile_cookie == "0") {
                set_mobile(1);
                set_tablet(0);
                location.reload();
            }
        }else if(is_tablet_screen){
            if (tablet_cookie === '' || tablet_cookie == "0") {
                set_mobile(0);
                set_tablet(1);
                location.reload();
            }
        } else {
            if (tablet_cookie == '1' || mobile_cookie == "1") {
                set_mobile(0);
                set_tablet(0);
                location.reload();
            }
        }

    // Set the cookie for the retina devices
    // the cookie is used later to serve appropriate image size
      if( document.cookie.indexOf('flo_device_pixel_ratio') == -1 && 'devicePixelRatio' in window && window.devicePixelRatio == 2 && !is_mobile_screen ){

        var date = new Date();

        date.setTime( date.getTime() + 3600000 );

        document.cookie = 'flo_device_pixel_ratio=' + window.devicePixelRatio + ';' + ' expires=' + date.toUTCString() +'; path=/';

        //if cookies are not blocked, reload the page

        if(document.cookie.indexOf('flo_device_pixel_ratio') != -1) {

          window.location.reload();

        }

      } else if(document.cookie.indexOf('flo_device_pixel_ratio') != -1 && floGetCookie('flo_device_pixel_ratio') != window.devicePixelRatio){
            // delete the coockie if the saved cookie does not match the current device pixel reatio

            var dateO = new Date();
            dateO.setTime( dateO.getTime() - 3600000 ); // set a past date that will be used to make the cookie expired

            document.cookie = 'flo_device_pixel_ratio=' + window.devicePixelRatio + ';' + ' expires=' + dateO.toUTCString() +'; path=/';

            window.location.reload(); // reload the page after deletting the cookie
        }

      })();


    /* START: MOBILE COOKIE */
});


function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

function floGetCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) === 0) return c.substring(name.length,c.length);
    }
    return "";
}

if($(".flo-block--merged-with-header").length) {
  $("body header").first().remove();
  $("body .flo-mobile-menu").first().remove();
  $("body .flo-header-mobile").first().remove();
}

$(function(){

    // form submission process
    $('.page').on('submit','.flo-form__built-in',function(e){
		e.preventDefault();

		var form = $(this),
			container = '.contact-response';  // the div for the error response messages

		jQuery('.flo-name').removeClass('invalid');
		jQuery('.flo-email').removeClass('invalid');

		jQuery(container).html('');

		jQuery.ajax({
			url: ajaxurl,
			data: '&action=floSendContact&'+jQuery( form ).serialize(),
			type: 'POST',
			dataType: "json",
	//      cache: false,
			success: function (json) {

				//jQuery('#flo-loading').fadeOut('slow'); // loading effect

				if(json.contact_name ){
					jQuery('.flo-name').addClass('invalid');
					jQuery(container).append(json.contact_name);
				}

				if(json.contact_email ){
					jQuery('.flo-email').addClass('invalid');
					jQuery(container).append(json.contact_email);
				}

				if(json.error_message ){
				
					jQuery(container).append(json.error_message);
				}

				

				if(json.message ){
					jQuery('.flo-modal').fadeIn('slow');

					jQuery( form).find('input[type="text"], textarea').val('');

					setTimeout(function(){
						jQuery('.flo-modal').fadeOut('slow');
					},3000);
				}

			}

		});
	});


    jQuery('.flo-modal .flo-icon__close').click(function(){
    	jQuery('.flo-modal').hide();
    });

});

$(function(){
  $(".flo-video-embed").each(function(){
    var video_embed = $(this);
    var video_embed__loaded_class = "flo-video-embed--loaded";
    var video_screen = video_embed.find(".flo-video-embed__screen");
    var video_screen__embed_code = video_screen.attr("data-flo-video-embed-embed-code");
    var video_button = video_embed.find(".flo-video-embed__video-button");

    video_button.on("click", function(){
      switch (video_embed.hasClass(video_embed__loaded_class)) {
        case false:
          video_screen.html(video_screen__embed_code);
          video_embed.addClass(video_embed__loaded_class);
        break;
        case true:
          video_embed.removeClass(video_embed__loaded_class);
          video_screen.html("");
        break;
      }
    });

  });
});

window.flo_block_featured_slideshow_1 = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo-block-featured-slideshow-1";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el.find(dotb);

  $el.find(dotb + "__image-slides").slick({
    fade: true,
    arrows: false,
    asNavFor: $el.find(dotb + "__text-slides")
  });

  $el.find(dotb + "__text-slides")
    /* START: COUNTER - SET COUNT */
      .on("init", function(){
        var $this = $(this);
        $el.find(dotb + "__counter-count").html(
          pad(
            $this.find(".slick-slide:not(.slick-cloned)").length,
            2
          )
        );
      })
    /* END: COUNTER - SET COUNT */

    /* START: COUNTER - SET INDEX */
      .on("init beforeChange", function(e, slick, currentSlide, nextSlide){
        var $this = $(this);
        var new_index = $this.find(".slick-current").attr("data-slick-index");
        if (e.type == "beforeChange") {
          new_index = nextSlide;
        }
        $el.find(dotb + "__counter-index").changeTextUI(
          pad(
            parseInt(new_index)+1,
            2
          ),
          "counter"
        );
      })
    /* END: COUNTER - SET INDEX */

    .slick({
      adaptiveHeight: true,
      nextArrow: $el.find(dotb + "__arrow--next"),
      prevArrow: $el.find(dotb + "__arrow--prev"),
      asNavFor: $el.find(dotb + "__image-slides")

    })
  ;

}

window.flo_block_information_block_3 = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo-block-information-block-3";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el.find(dotb);

  $el.find(dotb + "__slideshow").slick({
    arrows: false,
    fade: true,
    dots: true,
    autoplay: true
  });
}

window.flo_block_image_links = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo-block-image-links";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el.find(dotb);

  function do_sizing() {
    var items = $el.find(dotb + "__link");
    items.each(function(){
      var width = $(this).width();
      $(this).css("height", width);
    });
  }

  setTimeout(function () {
    do_sizing();
  }, 10);

  $(window).on("resize", do_sizing);

}

window.flo_newsletter_signup = function(){

	$( document ).ready(function() {
	    var $form = jQuery(".flo-form--newsletter");
	
	  if ($form.length) {
	    // Start: Validation
	      $form.parsley();
	    // End: Validation

	    // Start: Mailchimp Subscription
	      var
	      embed_code =
	        unescape(
	          $form.parent().find(".embed_code").text()
	        ),
	      $embed_code = $("<div>").html(embed_code);
	
	      if(typeof $embed_code.find("form").attr("action") !== 'undefined'){
	        var embed_form_action = $embed_code.find("form").attr("action").replace(/\\"/g, '');

	        $form.attr("action", embed_form_action);
	      }else{
	        console.log('The mailchimp code is incorect');
	      }

	    // End: Mailchimp Subscription`

	  }
	});
  

}

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
      // $slides.find('.slick-list').attr('tabindex', 0).focus();
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

window.flo_block_slideshow_1 = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo-block-slideshow-1";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var id = parent.attr("data-id");

  $el.find(dotb + "__slides")

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
        $el.find(dotb + "__arrow")
          .css({
            "color": elements_color
          })
        ;
        $el.find(".slick-dots li")
          .css({
            "background-color": elements_color,
            "color": elements_color
          })
        ;

        var b_for_custom_css = "flo-block-slideshow-1-" + id;
        var dotb_for_custom_css = "." + b_for_custom_css;

        setTimeout(function () {

          parent.find([
            ".flo-header__logo",
            ".flo-header-mobile__logo",
            ".flo-header__menu > div > ul > li > a",
            ".flo-header__search-trigger",
            ".flo-header__search-input"
          ].join(","))
            .customCSS("flo-block-slideshow-1-" + id, [
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
      prevArrow: $el.find(dotb + "__arrow--prev")
    })
  ;
}

window.flo_block_testimonials_2 = function(el){
  "use strict";
  var $el = $(el);
  var b = "flo-block-testimonials-2";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el.find(dotb);

  $el.find(dotb + "__testimonials").slick({
    fade: true,
    nextArrow: $el.find(dotb + "__controls-arrow--next"),
    prevArrow: $el.find(dotb + "__controls-arrow--prev"),
    adaptiveHeight: true
  });
}

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

$(function(){
  var is_not_sticky_class = "is-not-sticky";
  $(".flo-header-mobile").addClass(is_not_sticky_class);
  $(".flo-header-mobile.sticky")
    .on("sticky-start", function(){
      $(this).removeClass(is_not_sticky_class);
    })
    .on("sticky-end", function(){
      $(this).addClass(is_not_sticky_class);
    })
    .sticky({
        zIndex: 1000,
        className: "is-sticky",
        wrapperClassName: "flo-header-mobile-sticky-wrapper"
    })
  ;
});

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

$(function(){


    $(".flo-hero-2__slider").on('afterChange', function(){
        // BackgroundCheck.init({
        //     targets: '.flo-section__title-wrap',
        //     images: '.flo-page-hero__slide-content'
        // });
    }).slick({
        fade: true,
        arrows: false,
        dots: false,
        cssEase: "cubic-bezier(0.445, 0.050, 0.550, 0.950)",
        responsive: [
            {
                breakpoint: 600,
                settings: {
                    arrows: false
                }
            }
        ]

    });
});

$(function(){
    $(".flo-hero-3__slider")
    .flo_lqip("setSlickPreload")
    .on("afterChange", function(e, slick){
      var slideshow__$ = slick.$slider;

      /* Start: Thumbnails sync */
        var slideshow_thumbnails__selector = ".flo-portfolio-post__thumbnails";
        if ($(slideshow_thumbnails__selector).length) {
          slideshow__$.slick("slickSetOption", {
            "asNavFor": slideshow_thumbnails__selector
          });
        }
      /* End: Thumbnails sync */
    })
    .on("afterChange init", function(){
      var slideshow__$ = $(this);
      var slideshow__list = slideshow__$.find(".slick-list");
      var slideshow__active_slide = slideshow__$.find(".slick-current");
      var slideshow__active_slide_img = slideshow__active_slide.find("img");

      /* Start: Set height of slider by image */
        // setTimeout(function () {
        //   slideshow__list.css("height", slideshow__active_slide_img.height());
        // }, 500);
        slideshow__active_slide.find("img").on("load", function(){
          slideshow__list.css("height", slideshow__active_slide_img.height());
        });
      /* End: Set height of slider by image */
    })
    .on("init", function(){
      $(this).find('.slick-list').attr('tabindex', 0).focus();
    })
    .slick({
        speed: 400,
        arrows: true,
        pauseOnFocus: false,
        nextArrow: '<span class="flo-hero-3__arrow flo-hero-3__arrow--next flo-icon-arrow-right"></span>',
        prevArrow: '<span class="flo-hero-3__arrow flo-hero-3__arrow--prev flo-icon-arrow-left"></span>',
        cssEase: "cubic-bezier(0.445, 0.050, 0.550, 0.950)",
        responsive: [
          {
            breakpoint: 736,
            settings: {
              slidesToShow: 1,
              dots: false,
              arrows: true,
              centerPadding: 0,
              adaptiveHeight: true,
              variableWidth: false
            }
          }
        ],
    });

    var init_masonry = function(){
    $('.flo-gallery-type-c__row').masonry({
        // options
        itemSelector: '.flo-gallery-type-c__column'
    });
  }

  init_masonry();

  /* Start: Is On Screen Function */
    function isInViewport(element) {
      var rect = element.getBoundingClientRect();
      var html = document.documentElement;
      var offset = 800;
      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight + offset  || html.clientHeight + offset)  &&
        rect.right <= (window.innerWidth || html.clientWidth)
      );
    }
  /* End: Is On Screen Function */

  $(".flo-gallery-type-c__image:not([src])").each(function(){
    var image = $(this);

    image.on("load", function(){
      init_masonry();
    });

    $(window).on("scroll", function(){
      if (isInViewport(image[0])) {
        image.attr("src", image.attr("data-src") );
      }
    });

  });

});

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

$(function(){

  $(".flo-portfolio-grid .row.masonry, .flo-section--journal-grid-section .row.masonry").each(function(){
    $grid = $(this);

    function do_masonry($grid) {
      if (window.innerWidth >= 768) $grid.masonry({
        itemSelector : ".column",
        columnWidth : 0
      });
    }

    do_masonry($grid);

     $(this).find("img").on("load", function(){
      do_masonry($grid);
    });

  });

});

$(function(){
  // Start: Full Page Scroll
    if ($(".layout-sections--scroll-section").length) {

      if ($( window ).width() > 768) {

        $.scrollify({
          section:".layout-sections .full-scrollable-section",
          easing: "swing",
          scrollSpeed: 600,
          sectionName: false,
          interstitialSection:".layout-sections .full-scrollable-footer, .layout-sections header"
        });

        $(".flo-section__scroll-down").click(function(e) {
          e.preventDefault();
          $.scrollify.next();
        });

      }

    }
  // End: Full Page Scroll
});

$(function(){

  var splash__$ = $(".flo-splash");
  var splash__animation_map = {
    "fade" : [
      "fade-in",
      "fade-out"
    ],

    "hinge-top": [
      "hinge-in-from-top",
      "hinge-out-from-top"
    ],
    "hinge-vertical": [
      "hinge-in-from-top",
      "hinge-out-from-bottom"
    ],
    "hinge-left": [
      "hinge-in-from-left",
      "hinge-out-from-left"
    ],
    "hinge-horizontal": [
      "hinge-in-from-left",
      "hinge-out-from-right"
    ],

    "slide-left": [
      "slide-in-left",
      "slide-out-left"
    ],
    "slide-horizontal": [
      "slide-in-left",
      "slide-out-right"
    ],
    "slide-up": [
      "slide-in-down",
      "slide-out-up"
    ],
    "slide-vertical": [
      "slide-in-down",
      "slide-out-down"
    ],

    "spin": [
      "spin-in",
      "spin-out-ccw"
    ]


  }

  var splash__animation_name = splash__$.attr("data-animation");

  // var splash__animation_name = "fade";

  // var splash__animation_name = "hinge-top";
  // var splash__animation_name = "hinge-vertical";
  // var splash__animation_name = "hinge-left";
  // var splash__animation_name = "hinge-horizontal";

  // var splash__animation_name = "slide-left";
  // var splash__animation_name = "slide-horizontal";
  // var splash__animation_name = "slide-up";
  // var splash__animation_name = "slide-vertical";

  // var splash__animation_name = "spin";

  var splash__show = function() {
    Foundation.Motion.animateIn(splash__$, splash__animation_map[splash__animation_name][0]);
  }
  var splash__hide = function() {
    Foundation.Motion.animateOut(splash__$, splash__animation_map[splash__animation_name][1]);
  }

  // Start: animate on page load and unload
    var document__is_ready = false;
    $(document).ready(function() {
      splash__hide();
      document__is_ready = true;
    });
    window.onpageshow = function() {
      if (document__is_ready) splash__hide();
    };
  // End: Body FadeIn

  // Start: Body FadeOut
    window.onbeforeunload = function () {
      splash__show();
    };
  //End: Load/Unload Animation

});

$(function(){

  $(".flo-testimonials-slideshow__testimonials").slick({
    adaptiveHeight: true,
    dots: false,
    arrows: true,
    fade: true,
    nextArrow: '<button type="button" class="slick-next"><i class="flo-icon-arrow-right"></i></button>',
    prevArrow: '<button type="button" class="slick-prev"><i class="flo-icon-arrow-left"></i></button>'
  });

});

$(function(){

  var $form = $(".flo-footer__newsletter-form");
  if ($form.length) {
    // Start: Validation
      $form.parsley();
    // End: Validation

    // Start: Mailchimp Subscription
      var
      embed_code =
        unescape(
          $form.parent().find(".embed_code").text()
        ),
      $embed_code = $("<div>").html(embed_code),
      embed_form_action
      ;

      if(typeof $embed_code.find("form").attr("action") != 'undefined'){
        embed_form_action = $embed_code.find("form").attr("action").replace(/\\"/g, '')
        $form.attr("action", embed_form_action);
      }
      
    // End: Mailchimp Subscription`
  }
});

$(function(){

  /* START: SET SLIDER POSITION ACCORDING TO CLICKED THUMBNAIL */
    $(".flo-portfolio-grid__thumbnail").click(function(){
      var index = $(this).data("img-index");
      $(".flo-hero-3__slider").slick("slickGoTo", $(this).data("img-index"));
    });
  /* END: SET SLIDER POSITION ACCORDING TO CLICKED THUMBNAIL */

  /* START: TRIGGER RESIZE SO THAT THE SLIDER SETS IT POSITION PROPERLY INSIDE THE REVEAL */
    $(".flo-popup-gallery").on('open.zf.reveal', function () {
      var i = 0;
      var i__limit = 5;
      var trigger_interval = setInterval(function(){
        // if (i>1) $(window).trigger("resize");
        if (i < 3) $(".flo-hero-3__slider")[0].slick.refresh();
        if (i == i__limit) clearInterval(trigger_interval);
        i++;
      }, 100);
    });
  /* END: TRIGGER RESIZE SO THAT THE SLIDER SETS IT POSITION PROPERLY INSIDE THE REVEAL */

});

$(function(){


    // START: DISPLAY MOBILE MENU
	    $('.flo-icon-sidebar').click(function () {
	      $('.flo_page_wrap').find('.flo_sidebar').toggleClass('flo_sidebar--display');
	      $("body").toggleClass("flo_sidebar--active");
	    });
  	// END: DISPLAY MOBILE MENU

  	// START: SET PADDING TOP FOR SIDEBAR
    	$(".flo_sidebar").css("top", $(".flo-header-mobile").outerHeight(true) );
  	// END: SET PADDING TOP FOR SIDEBAR

});

$(function(){
    if( $(".flo-featured-posts__img img").length ){
		setTimeout(function(){
			BackgroundCheck.init({
				windowEvents: true,
				targets: '.flo-featured-posts__featured-post:not(.flo-featured-posts--layout-grid-has-excerpt) .flo-featured-posts__text',
				images: ".flo-featured-posts__img img"
			});
		});
  	}
});

$(function(){

  $(".flo-portfolio-post__thumbnails").slick({
    slidesToShow: 3,
    arrows: false,
    dots: false,
    focusOnSelect: true,
    asNavFor: ".flo-hero-3__slider"
  });

});

$(function(){
  /* Start: Scroll Down Button */
      $(".flo-header-section").on('click', '.flo-section__scroll-down', function(e) {
          e.preventDefault();
          $('html,body').animate({scrollTop: $('main').offset().top});
      });
  /* End: Scroll Down Button */
});

$(function(){
    $(".flo-instagram-images__slider").slick({
        arrows: true,
        dots: false,
        slidesToShow: 6,
        slidesToScroll: 6,
        cssEase: "cubic-bezier(0.445, 0.050, 0.550, 0.950)",
        nextArrow: '<span class="flo-slider-arrow flo-icon-flo-arrow-right"></span>',
        prevArrow: '<span class="flo-slider-arrow flo-icon-flo-arrow-left"></span>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    arrows: false,
                    centerMode: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});

window.flo_listing_grid_3 = function(el){
  "use strict";

  var $el = $(el);
  var b = "flo-listing-grid-3";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el.find(dotb);

  function do_sizing() {
    var items = $el.find(dotb + "__item");
    items.each(function(){
      var width = $(this).width();
      $(this).css("height", width);
    });
  }

  setTimeout(function () {
    do_sizing();
  }, 10);

  $(window).on("resize", do_sizing);
}

$(function(){

    // Start: Display Mobile Menu
    $('.flo-icon-sidebar').click(function () {
        $('body, html').find('.flo-page-has-sidebar__sidebar').toggleClass('sidebar-display');
    });

    // End: Display Mobile Menu

});

window.flo_listing_grid_4 = function(el){
  "use strict";

  var $el = $(el);
  var b = "flo-listing-grid-4";
  var dotb = "." + b;
  var parent = $el.parents(".flo-block");
  var $b = $el.find(dotb);

  function do_sizing() {
    var items = $el.find(dotb + "__image");
    items.each(function(){
      var width = $(this).width();
      $(this).css("height", width);
    });
  }

  setTimeout(function () {
    do_sizing();
  }, 10);

  $(window).on("resize", do_sizing);
}

$(function(){
  $(".flo-special-block__related-posts").css({
    "max-height": $(".flo-post--content").outerHeight(true) + $(".flo-special-block").outerHeight(true)

  })
});

$(function(){

	if ($(window).width() > 1024) $('.press-page__link').hover(function() {
		var block_class = $(this).data('block_class');
		$('.figure-'+block_class + ' img').attr('src', $(this).data('img'));
		$('.figure-'+block_class + ' .press-page__featured-image-description').html($(this).data('description'));
	});

});

$(function(){
  $(".flo-core-style").each(function(){
    var template = $(this);
    var style = template.html();
    $("head").append(style);
    template.remove();
  });
  $( "<style>body *{outline: solid transparent;}body { opacity: 1; }</style>" ).appendTo( "head" );
});
})(jQuery);
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImdsb2JhbC5qcyIsIjAvZmxvLWJsb2NrL2Zsby1ibG9jay5tYWluLmpzIiwiMC9mbG8tZm9ybS9mbG8tZm9ybS5tYWluLmpzIiwiMC9mbG8tdmlkZW8tZW1iZWQvZmxvLXZpZGVvLWVtYmVkLm1haW4uanMiLCIxL2Zsby1ibG9jay1mZWF0dXJlZC1zbGlkZXNob3ctMS9mbG8tYmxvY2stZmVhdHVyZWQtc2xpZGVzaG93LTEubWFpbi5qcyIsIjEvZmxvLWJsb2NrLWluZm9ybWF0aW9uLWJsb2NrLTMvZmxvLWJsb2NrLWluZm9ybWF0aW9uLWJsb2NrLTMubWFpbi5qcyIsIjEvZmxvLWJsb2NrLWltYWdlLWxpbmtzL2Zsby1ibG9jay1pbWFnZS1saW5rcy5tYWluLmpzIiwiMS9mbG8tYmxvY2stbmV3c2xldHRlci0yL2Zsby1ibG9jay1uZXdzbGV0dGVyLTIubWFpbi5qcyIsIjEvZmxvLWJsb2NrLXNsaWRlc2hvdy0yL2Zsby1ibG9jay1zbGlkZXNob3ctMi5tYWluLmpzIiwiMS9mbG8tYmxvY2stc2xpZGVzaG93LTEvZmxvLWJsb2NrLXNsaWRlc2hvdy0xLm1haW4uanMiLCIxL2Zsby1ibG9jay10ZXN0aW1vbmlhbHMtMi9mbG8tYmxvY2stdGVzdGltb25pYWxzLTIubWFpbi5qcyIsIjEvZmxvLWJsb2NrLXZpZGVvL2Zsby1ibG9jay12aWRlby5tYWluLmpzIiwiMS9mbG8tZ2VuZXJpYy1zbGlkZXMvZmxvLWdlbmVyaWMtc2xpZGVzLXRlbXBsYXRlLm1haW4uanMiLCIxL2Zsby1mb290ZXIvZmxvLWZvb3Rlci5tYWluLmpzIiwiMS9mbG8taGVhZGVyLW1vYmlsZS9mbG8taGVhZGVyLW1vYmlsZS5tYWluLmpzIiwiMS9mbG8taGVhZGVyL2Zsby1oZWFkZXItcG9wdXAubWFpbi5qcyIsIjEvZmxvLWhlYWRlci9mbG8taGVhZGVyLm1haW4uanMiLCIxL2Zsby1oZXJvLTIvZmxvLWhlcm8tMi5tYWluLmpzIiwiMS9mbG8taGVyby0zL2Zsby1oZXJvLTMubWFpbi5qcyIsIjEvZmxvLW1vYmlsZS1tZW51L2Zsby1tb2JpbGUtbWVudS5tYWluLmpzIiwiMS9mbG8tcGFnZS1oZXJvL2Zsby1wYWdlLWhlcm8ubWFpbi5qcyIsIjEvZmxvLXBvcnRmb2xpby1ncmlkL2Zsby1wb3J0Zm9saW8tZ3JpZC5tYWluLmpzIiwiMS9mbG8tc2VjdGlvbi9mbG8tc2VjdGlvbi5tYWluLmpzIiwiMS9mbG8tc3BsYXNoL2Zsby1zcGxhc2gubWFpbi5qcyIsIjEvZmxvLXRlc3RpbW9uaWFscy1zbGlkZXNob3cvZmxvLXRlc3RpbW9uaWFscy1zbGlkZXNob3cubWFpbi5qcyIsIjEvbmV3c2xldHRlci9uZXdzbGV0dGVyLm1haW4uanMiLCIxL2dhbGxlcnktcG9wdXAvZ2FsbGVyeS5wb3B1cC5tYWluLmpzIiwiMS9zaWRlYmFyL3NpZGVyYmFyLm1haW4uanMiLCIyL2Zsby1mZWF0dXJlZC1wb3N0cy9mbG8tZmVhdHVyZWQtcG9zdHMubWFpbi5qcyIsIjIvZmxvLWdhbGxlcnkvZmxvLWdhbGxlcnkubWFpbi5qcyIsIjIvZmxvLWhlYWRlci1zZWN0aW9uL2Zsby1oZWFkZXItc2VjdGlvbi5tYWluLmpzIiwiMi9mbG8taW5zdGFncmFtLWltYWdlcy1zbGlkZXIvZmxvLWluc3RhZ3JhbS1pbWFnZXMtc2xpZGVyLm1haW4uanMiLCIyL2Zsby1saXN0aW5nLWdyaWQtMy9mbG8tbGlzdGluZy1ncmlkLTMubWFpbi5qcyIsIjIvZmxvLXBhZ2UtaGFzLXNpZGVyYmFyL2JvZHktaGFzLXNpZGVyYmFyLm1haW4uanMiLCIyL2Zsby1saXN0aW5nLWdyaWQtNC9mbG8tbGlzdGluZy1ncmlkLTQubWFpbi5qcyIsIjIvZmxvLXNwZWNpYWwtYmxvY2svZmxvLXNwZWNpYWwtYmxvY2subWFpbi5qcyIsIjIvcHJlc3MtcGFnZS9wcmVzcy1wYWdlLm1haW4uanMiLCJzdHlsZS9zdHlsZS5tYWluLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ3JTQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUNMQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUM5REE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDdkJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDdERBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDZkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDdkJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDakNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDaklBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDbEhBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDZkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUM5QkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQzVIQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDaEJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ2pCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ3BGQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDdkdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDeEJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUM5RkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUNuREE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUMxTEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUNyQkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUN4QkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDNUZBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDWkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDMUJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ3ZCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ2ZBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ1hBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ1hBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ1JBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQzdCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUN2QkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ1ZBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ3ZCQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQ05BO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FDVEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EiLCJmaWxlIjoic2NyaXB0cy5qcyIsInNvdXJjZXNDb250ZW50IjpbImZ1bmN0aW9uIHBhZCAoc3RyLCBtYXgpIHtcclxuICBzdHIgPSBzdHIudG9TdHJpbmcoKTtcclxuICByZXR1cm4gc3RyLmxlbmd0aCA8IG1heCA/IHBhZChcIjBcIiArIHN0ciwgbWF4KSA6IHN0cjtcclxufVxyXG5cclxualF1ZXJ5LmZuLmV4dGVuZCh7XHJcbiAgY2hhbmdlVGV4dDogZnVuY3Rpb24odGV4dCl7XHJcbiAgICByZXR1cm4gdGhpcy5lYWNoKGZ1bmN0aW9uKCl7XHJcbiAgICAgIHZhciAkZWwgPSAkKHRoaXMpO1xyXG4gICAgICBpZiAoJGVsLnRleHQoKSAhPT0gdGV4dCApIHtcclxuICAgICAgICAkZWxcclxuICAgICAgICAgIC5hbmltYXRlKHtcIm9wYWNpdHlcIjogMH0sIDIwMClcclxuICAgICAgICA7XHJcbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xyXG4gICAgICAgICAgJGVsLnRleHQodGV4dCk7XHJcbiAgICAgICAgICAkZWxcclxuICAgICAgICAgICAgLmFuaW1hdGUoe1wib3BhY2l0eVwiOiAxfSwgMjAwKVxyXG4gICAgICAgICAgO1xyXG4gICAgICAgIH0sIDIwMCk7XHJcbiAgICAgIH1cclxuICAgIH0pO1xyXG4gIH0sXHJcbiAgY2hhbmdlVGV4dFVJOiBmdW5jdGlvbih0ZXh0LCBhbmltYXRpb24sIHNwZWVkKXtcclxuICAgIGlmICh0eXBlb2YgYW5pbWF0aW9uID09PSBcInVuZGVmaW5lZFwiKSB7XHJcbiAgICAgIHZhciBhbmltYXRpb24gPSBcImZhZGVcIjtcclxuICAgIH1cclxuICAgIGlmICh0eXBlb2Ygc3BlZWQgPT09IFwidW5kZWZpbmVkXCIpIHtcclxuICAgICAgdmFyIHNwZWVkID0gNDAwO1xyXG4gICAgfVxyXG4gICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe1xyXG4gICAgICB2YXIgJGVsID0gJCh0aGlzKTtcclxuXHJcbiAgICAgIHZhciBhbmltYXRpb25fbWFwID0ge1xyXG4gICAgICAgIGZhZGU6IHtcclxuICAgICAgICAgIG5hbWU6IFwiZmFkZVwiLFxyXG4gICAgICAgICAgc2hvd19hdHRyOiB7XHJcbiAgICAgICAgICB9LFxyXG4gICAgICAgICAgaGlkZV9hdHRyOiB7XHJcbiAgICAgICAgICB9XHJcbiAgICAgICAgfSxcclxuICAgICAgICBjb3VudGVyOiB7XHJcbiAgICAgICAgICBuYW1lOiBcInNsaWRlXCIsXHJcbiAgICAgICAgICBzaG93X2F0dHI6IHtcclxuICAgICAgICAgICAgZGlyZWN0aW9uOiBcImRvd25cIlxyXG4gICAgICAgICAgfSxcclxuICAgICAgICAgIGhpZGVfYXR0cjoge1xyXG4gICAgICAgICAgICBkaXJlY3Rpb246IFwidXBcIlxyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc2xpZGVfbGVmdDoge1xyXG4gICAgICAgICAgbmFtZTogXCJkcm9wXCIsXHJcbiAgICAgICAgICBzaG93X2F0dHI6IHtcclxuICAgICAgICAgICAgZGlyZWN0aW9uOiBcImxlZnRcIlxyXG4gICAgICAgICAgfSxcclxuICAgICAgICAgIGhpZGVfYXR0cjoge1xyXG4gICAgICAgICAgICBkaXJlY3Rpb246IFwicmlnaHRcIlxyXG4gICAgICAgICAgfVxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgc2xpZGVfcmlnaHQ6IHtcclxuICAgICAgICAgIG5hbWU6IFwiZHJvcFwiLFxyXG4gICAgICAgICAgc2hvd19hdHRyOiB7XHJcbiAgICAgICAgICAgIGRpcmVjdGlvbjogXCJyaWdodFwiXHJcbiAgICAgICAgICB9LFxyXG4gICAgICAgICAgaGlkZV9hdHRyOiB7XHJcbiAgICAgICAgICAgIGRpcmVjdGlvbjogXCJsZWZ0XCJcclxuICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG4gICAgICAgIGRyb3BfdXA6IHtcclxuICAgICAgICAgIG5hbWU6IFwiZHJvcFwiLFxyXG4gICAgICAgICAgc2hvd19hdHRyOiB7XHJcbiAgICAgICAgICAgIGRpcmVjdGlvbjogXCJ1cFwiXHJcbiAgICAgICAgICB9LFxyXG4gICAgICAgICAgaGlkZV9hdHRyOiB7XHJcbiAgICAgICAgICAgIGRpcmVjdGlvbjogXCJkb3duXCJcclxuICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgfVxyXG5cclxuICAgICAgLy8gaWYgKCRlbC50ZXh0KCkgIT09IHRleHQgKSB7XHJcbiAgICAgICAgLy8gJGVsXHJcbiAgICAgICAgLy8gICAuYW5pbWF0ZSh7XCJvcGFjaXR5XCI6IDB9LCAyMDApXHJcbiAgICAgICAgLy8gO1xyXG4gICAgICAgICRlbC5oaWRlKGFuaW1hdGlvbl9tYXBbYW5pbWF0aW9uXS5uYW1lLCBhbmltYXRpb25fbWFwW2FuaW1hdGlvbl0uc2hvd19hdHRyLCBzcGVlZCAvIDIpO1xyXG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgJGVsLnRleHQodGV4dCk7XHJcbiAgICAgICAgfSwgc3BlZWQgLyAyKTtcclxuICAgICAgICAvLyBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgICAvLyAkZWxcclxuICAgICAgICAgIC8vICAgLmFuaW1hdGUoe1wib3BhY2l0eVwiOiAxfSwgMjAwKVxyXG4gICAgICAgICAgLy8gO1xyXG4gICAgICAgICAgICAkZWwuc2hvdyhhbmltYXRpb25fbWFwW2FuaW1hdGlvbl0ubmFtZSwgYW5pbWF0aW9uX21hcFthbmltYXRpb25dLmhpZGVfYXR0ciwgc3BlZWQgLyAyKTtcclxuICAgICAgICAvLyB9LCBzcGVlZCk7XHJcbiAgICAgIC8vIH1cclxuICAgIH0pO1xyXG4gIH0sXHJcbiAgY2hhbmdlQ1NTOiBmdW5jdGlvbihwcm9wZXJ0eSwgdmFsdWUpe1xyXG4gICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe1xyXG4gICAgICB2YXIgJGVsID0gJCh0aGlzKTtcclxuICAgICAgaWYgKCRlbC5jc3MocHJvcGVydHkpICE9PSB2YWx1ZSApIHtcclxuICAgICAgICAkZWxcclxuICAgICAgICAgIC5hbmltYXRlKHtcIm9wYWNpdHlcIjogMH0sIDIwMClcclxuICAgICAgICAgIC8vIC5jc3MoXCJ0cmFuc2Zvcm1cIiwgXCJ0cmFuc2xhdGVZKC0wLjNyZW0pXCIpXHJcbiAgICAgICAgICAvLyAuY3NzKFwidHJhbnNpdGlvblwiLCBcInRyYW5zZm9ybSAwLjhzLCBjb2xvciAwLjRzXCIpXHJcbiAgICAgICAgO1xyXG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKXtcclxuICAgICAgICAgICRlbC5jc3MocHJvcGVydHksIHZhbHVlKTtcclxuICAgICAgICAgICRlbFxyXG4gICAgICAgICAgICAuYW5pbWF0ZSh7XCJvcGFjaXR5XCI6IDF9LCAyMDApXHJcbiAgICAgICAgICAgIC8vIC5jc3MoXCJ0cmFuc2Zvcm1cIiwgXCJ0cmFuc2xhdGVZKC0wcmVtKVwiKVxyXG4gICAgICAgICAgICAvLyAuY3NzKFwidHJhbnNpdGlvblwiLCBcIlwiKVxyXG4gICAgICAgICAgO1xyXG4gICAgICAgIH0sIDIwMCk7XHJcbiAgICAgIH1cclxuICAgIH0pO1xyXG4gIH0sXHJcbiAgY3VzdG9tQ1NTOiBmdW5jdGlvbihjdXN0b21fY2xhc3MsIGNzcykge1xyXG4gICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbigpe1xyXG4gICAgICB2YXIgJGVsID0gJCh0aGlzKTtcclxuICAgICAgaWYgKGN1c3RvbV9jbGFzcyAmJiBjc3MpIHtcclxuICAgICAgICAkZWwuYWRkQ2xhc3MoY3VzdG9tX2NsYXNzKTtcclxuICAgICAgICBpZiAoJChcInN0eWxlLmZsby1jdXN0b20tY3NzLS1cIiArIGN1c3RvbV9jbGFzcykubGVuZ3RoKSB7XHJcbiAgICAgICAgICAkKFwic3R5bGUuZmxvLWN1c3RvbS1jc3MtLVwiICsgY3VzdG9tX2NsYXNzKS5odG1sKGNzcyk7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICQoXCJoZWFkXCIpLmFwcGVuZChcclxuICAgICAgICAgICAgJChcIiA8c3R5bGUgY2xhc3M9J2Zsby1jdXN0b20tY3NzLS1cIiArIGN1c3RvbV9jbGFzcyArIFwiICc+IFwiKS5odG1sKGNzcylcclxuICAgICAgICAgICk7XHJcbiAgICAgICAgfVxyXG4gICAgICB9XHJcbiAgICB9KTtcclxuICB9XHJcbn0pO1xyXG5cclxuXHJcbi8vIEEgZnVuY3Rpb24gdGhhdCB0ZWxscyBpZiBhIGNvbG9yIGlzIGxpZ2h0KHRydWUpIG9yIGRhcmsgKGZhbHNlKVxyXG5mdW5jdGlvbiBpc19jb2xvcl9icmlnaHQoY29sb3IpIHtcclxuICBpZiAoY29sb3IgJiYgY29sb3IubGVuZ3RoID09IDcgJiYgY29sb3JbMF0gPT0gXCIjXCIpIHtcclxuICAgIHZhciBjID0gY29sb3I7XHJcbiAgICB2YXIgYyA9IGMuc3Vic3RyaW5nKDEpOyAgICAgIC8vIHN0cmlwICNcclxuICAgIHZhciByZ2IgPSBwYXJzZUludChjLCAxNik7ICAgLy8gY29udmVydCBycmdnYmIgdG8gZGVjaW1hbFxyXG4gICAgdmFyIHIgPSAocmdiID4+IDE2KSAmIDB4ZmY7ICAvLyBleHRyYWN0IHJlZFxyXG4gICAgdmFyIGcgPSAocmdiID4+ICA4KSAmIDB4ZmY7ICAvLyBleHRyYWN0IGdyZWVuXHJcbiAgICB2YXIgYiA9IChyZ2IgPj4gIDApICYgMHhmZjsgIC8vIGV4dHJhY3QgYmx1ZVxyXG5cclxuICAgIHZhciBsdW1hID0gMC4yMTI2ICogciArIDAuNzE1MiAqIGcgKyAwLjA3MjIgKiBiOyAvLyBwZXIgSVRVLVIgQlQuNzA5XHJcblxyXG4gICAgcmV0dXJuIGx1bWEgPiA0MDtcclxuICB9IGVsc2Uge1xyXG4gICAgY29uc29sZS5sb2coXCJDb2xvciBCcmlnaHRuZXNzOiBJbnZhbGlkIENvbG9yIFN0cmluZ1wiKTtcclxuICAgIHJldHVybiAwO1xyXG4gIH1cclxufVxyXG5cclxuXHJcbiQoZnVuY3Rpb24oKXtcclxuICAkKGRvY3VtZW50KS5mb3VuZGF0aW9uKCk7XHJcbiAgJChkb2N1bWVudCkuZmxvX2xxaXAoKTtcclxuXHJcblxyXG5cclxuICAvLyBTVEFSVDogQkxPQ0sgU0NSSVBUU1xyXG4gICAgJChcIltkYXRhLW9ucmVhZHldXCIpLmVhY2goZnVuY3Rpb24oKXtcclxuICAgICAgd2luZG93WyQodGhpcykuYXR0cihcImRhdGEtb25yZWFkeVwiKV0odGhpcyk7XHJcbiAgICB9KTtcclxuICAvLyBFTkQ6IEJMT0NLIFNDUklQVFNcclxuXHJcblxyXG4gIC8qIFN0YXJ0OiBhbmltYXRlIHNlY3Rpb24gYXBwZWFyYW5jZSAtIHZpZXdwb3J0IGNoZWNrZXIgKi9cclxuICAgICQod2luZG93KS5vbihcInN0YXJ0Vmlld3BvcnRDaGVja2VyXCIsIGZ1bmN0aW9uKCl7XHJcblxyXG4gICAgICAkKFxyXG4gICAgICAgIFtcclxuICAgICAgICAgIFwiLmZsby1ibG9jazpub3QoOmZpcnN0LWNoaWxkKVwiLFxyXG4gICAgICAgICAgXCIuZmxvX3BhZ2UgPiAuZmxvLXNlY3Rpb246bm90KDpmaXJzdC1vZi10eXBlKTpub3QoLmRpc2FibGUtYXBwZWFyKVwiLFxyXG4gICAgICAgICAgXCJmb290ZXJcIixcclxuICAgICAgICAgIFwiLnRvLWFwcGVhclwiLFxyXG4gICAgICAgICAgXCIudG8tYXBwZWFyLS1jdXN0b21cIixcclxuICAgICAgICAgIFwiLmZsby1wb3N0ID4gKlwiLFxyXG4gICAgICAgICAgXCIud2lkZ2V0XCJcclxuICAgICAgICBdLmpvaW4oXCIsXCIpXHJcbiAgICAgIClcclxuICAgICAgICAuYWRkQ2xhc3MoXCJmbG8tdG8tYmUtdmlzaWJsZVwiKVxyXG4gICAgICAgIC52aWV3cG9ydENoZWNrZXIoe1xyXG4gICAgICAgICAgY2xhc3NUb0FkZDogJ3Zpc2libGUnLFxyXG4gICAgICAgICAgcmVwZWF0OiB0cnVlLFxyXG4gICAgICAgICAgb2Zmc2V0OiA0MCxcclxuICAgICAgICAgIGludmVydEJvdHRvbU9mZnNldDogZmFsc2VcclxuICAgICAgICB9KVxyXG4gICAgICA7XHJcblxyXG4gICAgfSkudHJpZ2dlcihcInN0YXJ0Vmlld3BvcnRDaGVja2VyXCIpO1xyXG4gIC8qIEVuZDogYW5pbWF0ZSBzZWN0aW9uIGFwcGVhcmFuY2UgLSB2aWV3cG9ydCBjaGVja2VyICovXHJcblxyXG5cclxuICAgIC8qIFNUQVJUOiBNT0JJTEUgQ09PS0lFICovXHJcblxyXG4gICAgLy8gYWRkIHRoZSBjb29raWUgdGhhdCBpcyB1c2VkIHRvIGRldGVjdCBtb2JpbGUgYW5kIHJldGluYSBzY3JlZW5zXHJcbiAgICAoZnVuY3Rpb24oKXtcclxuXHJcbiAgICAgICAgdmFyIGlzX21vYmlsZV9zY3JlZW4sXHJcbiAgICAgICAgICAgIGlzX3RhYmxldF9zY3JlZW4sXHJcbiAgICAgICAgICAgIG1vYmlsZV9jb29raWVfbmFtZSA9IFwiZmxvX3NtYWxsX3NjcmVlblwiLFxyXG4gICAgICAgICAgICB0YWJsZXRfY29va2llX25hbWUgPSBcImZsb190YWJsZXRfc2NyZWVuXCIsXHJcbiAgICAgICAgICAgIG1vYmlsZV9jb29raWUgPSBmbG9HZXRDb29raWUobW9iaWxlX2Nvb2tpZV9uYW1lKSwgLy8gQ2FuIHJldHVybiBcIjFcIiwgXCIwXCIsIG51bGw7XHJcbiAgICAgICAgICAgIHRhYmxldF9jb29raWUgPSBmbG9HZXRDb29raWUodGFibGV0X2Nvb2tpZV9uYW1lKSwgLy8gQ2FuIHJldHVybiBcIjFcIiwgXCIwXCIsIG51bGw7XHJcbiAgICAgICAgICAgIHNldF9tb2JpbGUgPSBmdW5jdGlvbih2YWx1ZSkge1xyXG4gICAgICAgICAgICAgICAgY3JlYXRlQ29va2llKG1vYmlsZV9jb29raWVfbmFtZSwgdmFsdWUsIDEpO1xyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBzZXRfdGFibGV0ID0gZnVuY3Rpb24odmFsdWUpIHtcclxuICAgICAgICAgICAgICAgIGNyZWF0ZUNvb2tpZSh0YWJsZXRfY29va2llX25hbWUsIHZhbHVlLCAxKTtcclxuICAgICAgICAgICAgfSxcclxuXHJcbiAgICAgICAgLy8gIHdlIGNvbnNpZGVyIHNjcmVlbnMgbGFyZ2VyIHRoYW4gNzYwIG5vdCBiZWVpbmcgbW9iaWxlXHJcbiAgICAgICAgaXNfbW9iaWxlX3NjcmVlbiA9IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudC5jbGllbnRXaWR0aCA8PSA3NjA7XHJcblxyXG4gICAgICAgIGlzX3RhYmxldF9zY3JlZW4gPSAoZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LmNsaWVudFdpZHRoID49IDc2MSAmJiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xpZW50V2lkdGggPD0gMTAyNCk7XHJcblxyXG4gICAgICAgIGlmIChpc19tb2JpbGVfc2NyZWVuKSB7XHJcbiAgICAgICAgICAgIGlmIChtb2JpbGVfY29va2llID09PSAnJyB8fCBtb2JpbGVfY29va2llID09IFwiMFwiKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRfbW9iaWxlKDEpO1xyXG4gICAgICAgICAgICAgICAgc2V0X3RhYmxldCgwKTtcclxuICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfWVsc2UgaWYoaXNfdGFibGV0X3NjcmVlbil7XHJcbiAgICAgICAgICAgIGlmICh0YWJsZXRfY29va2llID09PSAnJyB8fCB0YWJsZXRfY29va2llID09IFwiMFwiKSB7XHJcbiAgICAgICAgICAgICAgICBzZXRfbW9iaWxlKDApO1xyXG4gICAgICAgICAgICAgICAgc2V0X3RhYmxldCgxKTtcclxuICAgICAgICAgICAgICAgIGxvY2F0aW9uLnJlbG9hZCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgaWYgKHRhYmxldF9jb29raWUgPT0gJzEnIHx8IG1vYmlsZV9jb29raWUgPT0gXCIxXCIpIHtcclxuICAgICAgICAgICAgICAgIHNldF9tb2JpbGUoMCk7XHJcbiAgICAgICAgICAgICAgICBzZXRfdGFibGV0KDApO1xyXG4gICAgICAgICAgICAgICAgbG9jYXRpb24ucmVsb2FkKCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgLy8gU2V0IHRoZSBjb29raWUgZm9yIHRoZSByZXRpbmEgZGV2aWNlc1xyXG4gICAgLy8gdGhlIGNvb2tpZSBpcyB1c2VkIGxhdGVyIHRvIHNlcnZlIGFwcHJvcHJpYXRlIGltYWdlIHNpemVcclxuICAgICAgaWYoIGRvY3VtZW50LmNvb2tpZS5pbmRleE9mKCdmbG9fZGV2aWNlX3BpeGVsX3JhdGlvJykgPT0gLTEgJiYgJ2RldmljZVBpeGVsUmF0aW8nIGluIHdpbmRvdyAmJiB3aW5kb3cuZGV2aWNlUGl4ZWxSYXRpbyA9PSAyICYmICFpc19tb2JpbGVfc2NyZWVuICl7XHJcblxyXG4gICAgICAgIHZhciBkYXRlID0gbmV3IERhdGUoKTtcclxuXHJcbiAgICAgICAgZGF0ZS5zZXRUaW1lKCBkYXRlLmdldFRpbWUoKSArIDM2MDAwMDAgKTtcclxuXHJcbiAgICAgICAgZG9jdW1lbnQuY29va2llID0gJ2Zsb19kZXZpY2VfcGl4ZWxfcmF0aW89JyArIHdpbmRvdy5kZXZpY2VQaXhlbFJhdGlvICsgJzsnICsgJyBleHBpcmVzPScgKyBkYXRlLnRvVVRDU3RyaW5nKCkgKyc7IHBhdGg9Lyc7XHJcblxyXG4gICAgICAgIC8vaWYgY29va2llcyBhcmUgbm90IGJsb2NrZWQsIHJlbG9hZCB0aGUgcGFnZVxyXG5cclxuICAgICAgICBpZihkb2N1bWVudC5jb29raWUuaW5kZXhPZignZmxvX2RldmljZV9waXhlbF9yYXRpbycpICE9IC0xKSB7XHJcblxyXG4gICAgICAgICAgd2luZG93LmxvY2F0aW9uLnJlbG9hZCgpO1xyXG5cclxuICAgICAgICB9XHJcblxyXG4gICAgICB9IGVsc2UgaWYoZG9jdW1lbnQuY29va2llLmluZGV4T2YoJ2Zsb19kZXZpY2VfcGl4ZWxfcmF0aW8nKSAhPSAtMSAmJiBmbG9HZXRDb29raWUoJ2Zsb19kZXZpY2VfcGl4ZWxfcmF0aW8nKSAhPSB3aW5kb3cuZGV2aWNlUGl4ZWxSYXRpbyl7XHJcbiAgICAgICAgICAgIC8vIGRlbGV0ZSB0aGUgY29vY2tpZSBpZiB0aGUgc2F2ZWQgY29va2llIGRvZXMgbm90IG1hdGNoIHRoZSBjdXJyZW50IGRldmljZSBwaXhlbCByZWF0aW9cclxuXHJcbiAgICAgICAgICAgIHZhciBkYXRlTyA9IG5ldyBEYXRlKCk7XHJcbiAgICAgICAgICAgIGRhdGVPLnNldFRpbWUoIGRhdGVPLmdldFRpbWUoKSAtIDM2MDAwMDAgKTsgLy8gc2V0IGEgcGFzdCBkYXRlIHRoYXQgd2lsbCBiZSB1c2VkIHRvIG1ha2UgdGhlIGNvb2tpZSBleHBpcmVkXHJcblxyXG4gICAgICAgICAgICBkb2N1bWVudC5jb29raWUgPSAnZmxvX2RldmljZV9waXhlbF9yYXRpbz0nICsgd2luZG93LmRldmljZVBpeGVsUmF0aW8gKyAnOycgKyAnIGV4cGlyZXM9JyArIGRhdGVPLnRvVVRDU3RyaW5nKCkgKyc7IHBhdGg9Lyc7XHJcblxyXG4gICAgICAgICAgICB3aW5kb3cubG9jYXRpb24ucmVsb2FkKCk7IC8vIHJlbG9hZCB0aGUgcGFnZSBhZnRlciBkZWxldHRpbmcgdGhlIGNvb2tpZVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgIH0pKCk7XHJcblxyXG5cclxuICAgIC8qIFNUQVJUOiBNT0JJTEUgQ09PS0lFICovXHJcbn0pO1xyXG5cclxuXHJcbmZ1bmN0aW9uIGNyZWF0ZUNvb2tpZShuYW1lLHZhbHVlLGRheXMpIHtcclxuICAgIHZhciBleHBpcmVzID0gXCJcIjtcclxuICAgIGlmIChkYXlzKSB7XHJcbiAgICAgICAgdmFyIGRhdGUgPSBuZXcgRGF0ZSgpO1xyXG4gICAgICAgIGRhdGUuc2V0VGltZShkYXRlLmdldFRpbWUoKSsoZGF5cyoyNCo2MCo2MCoxMDAwKSk7XHJcbiAgICAgICAgZXhwaXJlcyA9IFwiOyBleHBpcmVzPVwiK2RhdGUudG9HTVRTdHJpbmcoKTtcclxuICAgIH1cclxuICAgIGRvY3VtZW50LmNvb2tpZSA9IG5hbWUrXCI9XCIrdmFsdWUrZXhwaXJlcytcIjsgcGF0aD0vXCI7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIGZsb0dldENvb2tpZShjbmFtZSkge1xyXG4gICAgdmFyIG5hbWUgPSBjbmFtZSArIFwiPVwiO1xyXG4gICAgdmFyIGNhID0gZG9jdW1lbnQuY29va2llLnNwbGl0KCc7Jyk7XHJcbiAgICBmb3IodmFyIGk9MDsgaTxjYS5sZW5ndGg7IGkrKykge1xyXG4gICAgICAgIHZhciBjID0gY2FbaV07XHJcbiAgICAgICAgd2hpbGUgKGMuY2hhckF0KDApPT0nICcpIGMgPSBjLnN1YnN0cmluZygxKTtcclxuICAgICAgICBpZiAoYy5pbmRleE9mKG5hbWUpID09PSAwKSByZXR1cm4gYy5zdWJzdHJpbmcobmFtZS5sZW5ndGgsYy5sZW5ndGgpO1xyXG4gICAgfVxyXG4gICAgcmV0dXJuIFwiXCI7XHJcbn1cclxuIiwiaWYoJChcIi5mbG8tYmxvY2stLW1lcmdlZC13aXRoLWhlYWRlclwiKS5sZW5ndGgpIHtcbiAgJChcImJvZHkgaGVhZGVyXCIpLmZpcnN0KCkucmVtb3ZlKCk7XG4gICQoXCJib2R5IC5mbG8tbW9iaWxlLW1lbnVcIikuZmlyc3QoKS5yZW1vdmUoKTtcbiAgJChcImJvZHkgLmZsby1oZWFkZXItbW9iaWxlXCIpLmZpcnN0KCkucmVtb3ZlKCk7XG59XG4iLCIkKGZ1bmN0aW9uKCl7XG5cbiAgICAvLyBmb3JtIHN1Ym1pc3Npb24gcHJvY2Vzc1xuICAgICQoJy5wYWdlJykub24oJ3N1Ym1pdCcsJy5mbG8tZm9ybV9fYnVpbHQtaW4nLGZ1bmN0aW9uKGUpe1xuXHRcdGUucHJldmVudERlZmF1bHQoKTtcblxuXHRcdHZhciBmb3JtID0gJCh0aGlzKSxcblx0XHRcdGNvbnRhaW5lciA9ICcuY29udGFjdC1yZXNwb25zZSc7ICAvLyB0aGUgZGl2IGZvciB0aGUgZXJyb3IgcmVzcG9uc2UgbWVzc2FnZXNcblxuXHRcdGpRdWVyeSgnLmZsby1uYW1lJykucmVtb3ZlQ2xhc3MoJ2ludmFsaWQnKTtcblx0XHRqUXVlcnkoJy5mbG8tZW1haWwnKS5yZW1vdmVDbGFzcygnaW52YWxpZCcpO1xuXG5cdFx0alF1ZXJ5KGNvbnRhaW5lcikuaHRtbCgnJyk7XG5cblx0XHRqUXVlcnkuYWpheCh7XG5cdFx0XHR1cmw6IGFqYXh1cmwsXG5cdFx0XHRkYXRhOiAnJmFjdGlvbj1mbG9TZW5kQ29udGFjdCYnK2pRdWVyeSggZm9ybSApLnNlcmlhbGl6ZSgpLFxuXHRcdFx0dHlwZTogJ1BPU1QnLFxuXHRcdFx0ZGF0YVR5cGU6IFwianNvblwiLFxuXHQvLyAgICAgIGNhY2hlOiBmYWxzZSxcblx0XHRcdHN1Y2Nlc3M6IGZ1bmN0aW9uIChqc29uKSB7XG5cblx0XHRcdFx0Ly9qUXVlcnkoJyNmbG8tbG9hZGluZycpLmZhZGVPdXQoJ3Nsb3cnKTsgLy8gbG9hZGluZyBlZmZlY3RcblxuXHRcdFx0XHRpZihqc29uLmNvbnRhY3RfbmFtZSApe1xuXHRcdFx0XHRcdGpRdWVyeSgnLmZsby1uYW1lJykuYWRkQ2xhc3MoJ2ludmFsaWQnKTtcblx0XHRcdFx0XHRqUXVlcnkoY29udGFpbmVyKS5hcHBlbmQoanNvbi5jb250YWN0X25hbWUpO1xuXHRcdFx0XHR9XG5cblx0XHRcdFx0aWYoanNvbi5jb250YWN0X2VtYWlsICl7XG5cdFx0XHRcdFx0alF1ZXJ5KCcuZmxvLWVtYWlsJykuYWRkQ2xhc3MoJ2ludmFsaWQnKTtcblx0XHRcdFx0XHRqUXVlcnkoY29udGFpbmVyKS5hcHBlbmQoanNvbi5jb250YWN0X2VtYWlsKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdGlmKGpzb24uZXJyb3JfbWVzc2FnZSApe1xuXHRcdFx0XHRcblx0XHRcdFx0XHRqUXVlcnkoY29udGFpbmVyKS5hcHBlbmQoanNvbi5lcnJvcl9tZXNzYWdlKTtcblx0XHRcdFx0fVxuXG5cdFx0XHRcdFxuXG5cdFx0XHRcdGlmKGpzb24ubWVzc2FnZSApe1xuXHRcdFx0XHRcdGpRdWVyeSgnLmZsby1tb2RhbCcpLmZhZGVJbignc2xvdycpO1xuXG5cdFx0XHRcdFx0alF1ZXJ5KCBmb3JtKS5maW5kKCdpbnB1dFt0eXBlPVwidGV4dFwiXSwgdGV4dGFyZWEnKS52YWwoJycpO1xuXG5cdFx0XHRcdFx0c2V0VGltZW91dChmdW5jdGlvbigpe1xuXHRcdFx0XHRcdFx0alF1ZXJ5KCcuZmxvLW1vZGFsJykuZmFkZU91dCgnc2xvdycpO1xuXHRcdFx0XHRcdH0sMzAwMCk7XG5cdFx0XHRcdH1cblxuXHRcdFx0fVxuXG5cdFx0fSk7XG5cdH0pO1xuXG5cbiAgICBqUXVlcnkoJy5mbG8tbW9kYWwgLmZsby1pY29uX19jbG9zZScpLmNsaWNrKGZ1bmN0aW9uKCl7XG4gICAgXHRqUXVlcnkoJy5mbG8tbW9kYWwnKS5oaWRlKCk7XG4gICAgfSk7XG5cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuICAkKFwiLmZsby12aWRlby1lbWJlZFwiKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgdmFyIHZpZGVvX2VtYmVkID0gJCh0aGlzKTtcbiAgICB2YXIgdmlkZW9fZW1iZWRfX2xvYWRlZF9jbGFzcyA9IFwiZmxvLXZpZGVvLWVtYmVkLS1sb2FkZWRcIjtcbiAgICB2YXIgdmlkZW9fc2NyZWVuID0gdmlkZW9fZW1iZWQuZmluZChcIi5mbG8tdmlkZW8tZW1iZWRfX3NjcmVlblwiKTtcbiAgICB2YXIgdmlkZW9fc2NyZWVuX19lbWJlZF9jb2RlID0gdmlkZW9fc2NyZWVuLmF0dHIoXCJkYXRhLWZsby12aWRlby1lbWJlZC1lbWJlZC1jb2RlXCIpO1xuICAgIHZhciB2aWRlb19idXR0b24gPSB2aWRlb19lbWJlZC5maW5kKFwiLmZsby12aWRlby1lbWJlZF9fdmlkZW8tYnV0dG9uXCIpO1xuXG4gICAgdmlkZW9fYnV0dG9uLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24oKXtcbiAgICAgIHN3aXRjaCAodmlkZW9fZW1iZWQuaGFzQ2xhc3ModmlkZW9fZW1iZWRfX2xvYWRlZF9jbGFzcykpIHtcbiAgICAgICAgY2FzZSBmYWxzZTpcbiAgICAgICAgICB2aWRlb19zY3JlZW4uaHRtbCh2aWRlb19zY3JlZW5fX2VtYmVkX2NvZGUpO1xuICAgICAgICAgIHZpZGVvX2VtYmVkLmFkZENsYXNzKHZpZGVvX2VtYmVkX19sb2FkZWRfY2xhc3MpO1xuICAgICAgICBicmVhaztcbiAgICAgICAgY2FzZSB0cnVlOlxuICAgICAgICAgIHZpZGVvX2VtYmVkLnJlbW92ZUNsYXNzKHZpZGVvX2VtYmVkX19sb2FkZWRfY2xhc3MpO1xuICAgICAgICAgIHZpZGVvX3NjcmVlbi5odG1sKFwiXCIpO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICB9KTtcblxuICB9KTtcbn0pO1xuIiwid2luZG93LmZsb19ibG9ja19mZWF0dXJlZF9zbGlkZXNob3dfMSA9IGZ1bmN0aW9uKGVsKXtcbiAgXCJ1c2Ugc3RyaWN0XCI7XG4gIHZhciAkZWwgPSAkKGVsKTtcbiAgdmFyIGIgPSBcImZsby1ibG9jay1mZWF0dXJlZC1zbGlkZXNob3ctMVwiO1xuICB2YXIgZG90YiA9IFwiLlwiICsgYjtcbiAgdmFyIHBhcmVudCA9ICRlbC5wYXJlbnRzKFwiLmZsby1ibG9ja1wiKTtcbiAgdmFyICRiID0gJGVsLmZpbmQoZG90Yik7XG5cbiAgJGVsLmZpbmQoZG90YiArIFwiX19pbWFnZS1zbGlkZXNcIikuc2xpY2soe1xuICAgIGZhZGU6IHRydWUsXG4gICAgYXJyb3dzOiBmYWxzZSxcbiAgICBhc05hdkZvcjogJGVsLmZpbmQoZG90YiArIFwiX190ZXh0LXNsaWRlc1wiKVxuICB9KTtcblxuICAkZWwuZmluZChkb3RiICsgXCJfX3RleHQtc2xpZGVzXCIpXG4gICAgLyogU1RBUlQ6IENPVU5URVIgLSBTRVQgQ09VTlQgKi9cbiAgICAgIC5vbihcImluaXRcIiwgZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgJGVsLmZpbmQoZG90YiArIFwiX19jb3VudGVyLWNvdW50XCIpLmh0bWwoXG4gICAgICAgICAgcGFkKFxuICAgICAgICAgICAgJHRoaXMuZmluZChcIi5zbGljay1zbGlkZTpub3QoLnNsaWNrLWNsb25lZClcIikubGVuZ3RoLFxuICAgICAgICAgICAgMlxuICAgICAgICAgIClcbiAgICAgICAgKTtcbiAgICAgIH0pXG4gICAgLyogRU5EOiBDT1VOVEVSIC0gU0VUIENPVU5UICovXG5cbiAgICAvKiBTVEFSVDogQ09VTlRFUiAtIFNFVCBJTkRFWCAqL1xuICAgICAgLm9uKFwiaW5pdCBiZWZvcmVDaGFuZ2VcIiwgZnVuY3Rpb24oZSwgc2xpY2ssIGN1cnJlbnRTbGlkZSwgbmV4dFNsaWRlKXtcbiAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgdmFyIG5ld19pbmRleCA9ICR0aGlzLmZpbmQoXCIuc2xpY2stY3VycmVudFwiKS5hdHRyKFwiZGF0YS1zbGljay1pbmRleFwiKTtcbiAgICAgICAgaWYgKGUudHlwZSA9PSBcImJlZm9yZUNoYW5nZVwiKSB7XG4gICAgICAgICAgbmV3X2luZGV4ID0gbmV4dFNsaWRlO1xuICAgICAgICB9XG4gICAgICAgICRlbC5maW5kKGRvdGIgKyBcIl9fY291bnRlci1pbmRleFwiKS5jaGFuZ2VUZXh0VUkoXG4gICAgICAgICAgcGFkKFxuICAgICAgICAgICAgcGFyc2VJbnQobmV3X2luZGV4KSsxLFxuICAgICAgICAgICAgMlxuICAgICAgICAgICksXG4gICAgICAgICAgXCJjb3VudGVyXCJcbiAgICAgICAgKTtcbiAgICAgIH0pXG4gICAgLyogRU5EOiBDT1VOVEVSIC0gU0VUIElOREVYICovXG5cbiAgICAuc2xpY2soe1xuICAgICAgYWRhcHRpdmVIZWlnaHQ6IHRydWUsXG4gICAgICBuZXh0QXJyb3c6ICRlbC5maW5kKGRvdGIgKyBcIl9fYXJyb3ctLW5leHRcIiksXG4gICAgICBwcmV2QXJyb3c6ICRlbC5maW5kKGRvdGIgKyBcIl9fYXJyb3ctLXByZXZcIiksXG4gICAgICBhc05hdkZvcjogJGVsLmZpbmQoZG90YiArIFwiX19pbWFnZS1zbGlkZXNcIilcblxuICAgIH0pXG4gIDtcblxufVxuIiwid2luZG93LmZsb19ibG9ja19pbmZvcm1hdGlvbl9ibG9ja18zID0gZnVuY3Rpb24oZWwpe1xuICBcInVzZSBzdHJpY3RcIjtcbiAgdmFyICRlbCA9ICQoZWwpO1xuICB2YXIgYiA9IFwiZmxvLWJsb2NrLWluZm9ybWF0aW9uLWJsb2NrLTNcIjtcbiAgdmFyIGRvdGIgPSBcIi5cIiArIGI7XG4gIHZhciBwYXJlbnQgPSAkZWwucGFyZW50cyhcIi5mbG8tYmxvY2tcIik7XG4gIHZhciAkYiA9ICRlbC5maW5kKGRvdGIpO1xuXG4gICRlbC5maW5kKGRvdGIgKyBcIl9fc2xpZGVzaG93XCIpLnNsaWNrKHtcbiAgICBhcnJvd3M6IGZhbHNlLFxuICAgIGZhZGU6IHRydWUsXG4gICAgZG90czogdHJ1ZSxcbiAgICBhdXRvcGxheTogdHJ1ZVxuICB9KTtcbn1cbiIsIndpbmRvdy5mbG9fYmxvY2tfaW1hZ2VfbGlua3MgPSBmdW5jdGlvbihlbCl7XG4gIFwidXNlIHN0cmljdFwiO1xuICB2YXIgJGVsID0gJChlbCk7XG4gIHZhciBiID0gXCJmbG8tYmxvY2staW1hZ2UtbGlua3NcIjtcbiAgdmFyIGRvdGIgPSBcIi5cIiArIGI7XG4gIHZhciBwYXJlbnQgPSAkZWwucGFyZW50cyhcIi5mbG8tYmxvY2tcIik7XG4gIHZhciAkYiA9ICRlbC5maW5kKGRvdGIpO1xuXG4gIGZ1bmN0aW9uIGRvX3NpemluZygpIHtcbiAgICB2YXIgaXRlbXMgPSAkZWwuZmluZChkb3RiICsgXCJfX2xpbmtcIik7XG4gICAgaXRlbXMuZWFjaChmdW5jdGlvbigpe1xuICAgICAgdmFyIHdpZHRoID0gJCh0aGlzKS53aWR0aCgpO1xuICAgICAgJCh0aGlzKS5jc3MoXCJoZWlnaHRcIiwgd2lkdGgpO1xuICAgIH0pO1xuICB9XG5cbiAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgZG9fc2l6aW5nKCk7XG4gIH0sIDEwKTtcblxuICAkKHdpbmRvdykub24oXCJyZXNpemVcIiwgZG9fc2l6aW5nKTtcblxufVxuIiwid2luZG93LmZsb19uZXdzbGV0dGVyX3NpZ251cCA9IGZ1bmN0aW9uKCl7XG5cblx0JCggZG9jdW1lbnQgKS5yZWFkeShmdW5jdGlvbigpIHtcblx0ICAgIHZhciAkZm9ybSA9IGpRdWVyeShcIi5mbG8tZm9ybS0tbmV3c2xldHRlclwiKTtcblx0XG5cdCAgaWYgKCRmb3JtLmxlbmd0aCkge1xuXHQgICAgLy8gU3RhcnQ6IFZhbGlkYXRpb25cblx0ICAgICAgJGZvcm0ucGFyc2xleSgpO1xuXHQgICAgLy8gRW5kOiBWYWxpZGF0aW9uXG5cblx0ICAgIC8vIFN0YXJ0OiBNYWlsY2hpbXAgU3Vic2NyaXB0aW9uXG5cdCAgICAgIHZhclxuXHQgICAgICBlbWJlZF9jb2RlID1cblx0ICAgICAgICB1bmVzY2FwZShcblx0ICAgICAgICAgICRmb3JtLnBhcmVudCgpLmZpbmQoXCIuZW1iZWRfY29kZVwiKS50ZXh0KClcblx0ICAgICAgICApLFxuXHQgICAgICAkZW1iZWRfY29kZSA9ICQoXCI8ZGl2PlwiKS5odG1sKGVtYmVkX2NvZGUpO1xuXHRcblx0ICAgICAgaWYodHlwZW9mICRlbWJlZF9jb2RlLmZpbmQoXCJmb3JtXCIpLmF0dHIoXCJhY3Rpb25cIikgIT09ICd1bmRlZmluZWQnKXtcblx0ICAgICAgICB2YXIgZW1iZWRfZm9ybV9hY3Rpb24gPSAkZW1iZWRfY29kZS5maW5kKFwiZm9ybVwiKS5hdHRyKFwiYWN0aW9uXCIpLnJlcGxhY2UoL1xcXFxcIi9nLCAnJyk7XG5cblx0ICAgICAgICAkZm9ybS5hdHRyKFwiYWN0aW9uXCIsIGVtYmVkX2Zvcm1fYWN0aW9uKTtcblx0ICAgICAgfWVsc2V7XG5cdCAgICAgICAgY29uc29sZS5sb2coJ1RoZSBtYWlsY2hpbXAgY29kZSBpcyBpbmNvcmVjdCcpO1xuXHQgICAgICB9XG5cblx0ICAgIC8vIEVuZDogTWFpbGNoaW1wIFN1YnNjcmlwdGlvbmBcblxuXHQgIH1cblx0fSk7XG4gIFxuXG59XG4iLCJ3aW5kb3cuZmxvX2Jsb2NrX3NsaWRlc2hvd18yID0gZnVuY3Rpb24oZWwpe1xuICBcInVzZSBzdHJpY3RcIjtcbiAgdmFyICRlbCA9ICQoZWwpO1xuICB2YXIgYiA9IFwiZmxvLWJsb2NrLXNsaWRlc2hvdy0yXCI7XG4gIHZhciBkb3RiID0gXCIuXCIgKyBiO1xuICB2YXIgcGFyZW50ID0gJGVsLnBhcmVudHMoXCIuZmxvLWJsb2NrXCIpO1xuICB2YXIgaWQgPSBwYXJlbnQuYXR0cihcImRhdGEtaWRcIik7XG5cbiAgJGVsLmZpbmQoZG90YiArIFwiX19zbGlkZXNcIilcbiAgICAub24oXCJpbml0XCIsIGZ1bmN0aW9uKCl7XG4gICAgICB2YXIgJHNsaWRlcyA9ICQodGhpcyk7XG4gICAgICAvLyAkc2xpZGVzLmZpbmQoJy5zbGljay1saXN0JykuYXR0cigndGFiaW5kZXgnLCAwKS5mb2N1cygpO1xuICAgICAgJGVsLmZpbmQoXCIuXCIrYitcIl9fc2xpZGUtLWltYWdlX2FuZF92aWRlb19lbWJlZFwiKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciBhY3RpdmVfc2xpZGVfXyQgPSAkKHRoaXMpO1xuICAgICAgICB2YXIgdmlkZW9fZW1iZWRfaG9zdCA9IHBhcmVudDtcbiAgICAgICAgdmFyIHZpZGVvX2J1dHRvbiA9IGFjdGl2ZV9zbGlkZV9fJC5maW5kKFwiLmZsby1oZXJvLXZpZGVvLWVtYmVkX19idXR0b25cIik7XG4gICAgICAgIHZhciB2aWRlb19jb250YWluZXIgPSBhY3RpdmVfc2xpZGVfXyQuZmluZChcIi5mbG8taGVyby12aWRlby1lbWJlZF9fY29udGFpbmVyXCIpO1xuICAgICAgICB2YXIgZW1iZWRfY29kZSA9IGFjdGl2ZV9zbGlkZV9fJC5hdHRyKFwiZGF0YS1lbWJlZC1jb2RlXCIpO1xuXG4gICAgICAgIHZpZGVvX2J1dHRvbi5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgaWYgKCF2aWRlb19lbWJlZF9ob3N0Lmhhc0NsYXNzKFwidmlkZW8taXMtcGxheWluZ1wiKSkge1xuICAgICAgICAgICAgaWYoJHNsaWRlcy5hdHRyKFwiZGF0YS1hdXRvcGxheVwiKSA9PSBcInRydWVcIil7XG4gICAgICAgICAgICAgICRzbGlkZXMuc2xpY2soJ3NsaWNrUGF1c2UnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZpZGVvX2NvbnRhaW5lci5odG1sKHVuZXNjYXBlKGVtYmVkX2NvZGUpKTtcbiAgICAgICAgICAgIHZpZGVvX2VtYmVkX2hvc3QuYWRkQ2xhc3MoXCJ2aWRlby1pcy1wbGF5aW5nXCIpO1xuICAgICAgICAgIH0gZWxzZSBpZiAodmlkZW9fZW1iZWRfaG9zdC5oYXNDbGFzcyhcInZpZGVvLWlzLXBsYXlpbmdcIikpIHtcbiAgICAgICAgICAgIGlmKCRzbGlkZXMuYXR0cihcImRhdGEtYXV0b3BsYXlcIikgPT0gXCJ0cnVlXCIpe1xuICAgICAgICAgICAgICAkc2xpZGVzLnNsaWNrKCdzbGlja1BsYXknKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHZpZGVvX2NvbnRhaW5lci5odG1sKFwiXCIpO1xuICAgICAgICAgICAgdmlkZW9fZW1iZWRfaG9zdC5yZW1vdmVDbGFzcyhcInZpZGVvLWlzLXBsYXlpbmdcIik7XG4gICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgfSk7XG4gICAgfSlcbiAgICAvKiBTVEFSVDogQ09MT1IgQ0hBTkdFICovXG4gICAgICAub24oXCJpbml0IGJlZm9yZUNoYW5nZVwiLCBmdW5jdGlvbihlLCBzbGljaywgY3VycmVudFNsaWRlLCBuZXh0U2xpZGUpe1xuICAgICAgICB2YXIgZWxlbWVudHNfY29sb3I7XG4gICAgICAgIHZhciBjaGVja19kZWxheTtcbiAgICAgICAgaWYgKGUudHlwZSA9PSBcImluaXRcIikge1xuICAgICAgICAgIGNoZWNrX2RlbGF5ID0gMTAwO1xuICAgICAgICAgIGVsZW1lbnRzX2NvbG9yID0gJGVsLmZpbmQoXCIuc2xpY2stY3VycmVudCBcIiArIGRvdGIgK1wiX19zbGlkZS1jb250ZW50XCIpLmNzcyhcImNvbG9yXCIpO1xuICAgICAgICB9IGVsc2UgaWYgKGUudHlwZSA9PSBcImJlZm9yZUNoYW5nZVwiKSB7XG4gICAgICAgICAgY2hlY2tfZGVsYXkgPSAwO1xuICAgICAgICAgIGVsZW1lbnRzX2NvbG9yID0gJGVsLmZpbmQoXCIuc2xpY2stc2xpZGVbZGF0YS1zbGljay1pbmRleD0nXCIrIG5leHRTbGlkZSArXCInXSBcIiArIGRvdGIgKyBcIl9fc2xpZGUtY29udGVudFwiKS5jc3MoXCJjb2xvclwiKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciBiX2Zvcl9jdXN0b21fY3NzID0gXCJmbG8tYmxvY2stc2xpZGVzaG93LTItXCIgKyBpZDtcbiAgICAgICAgdmFyIGRvdGJfZm9yX2N1c3RvbV9jc3MgPSBcIi5cIiArIGJfZm9yX2N1c3RvbV9jc3M7XG5cbiAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG5cbiAgICAgICAgICBwYXJlbnQuZmluZChbXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyX19sb2dvXCIsXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyX19tZW51ID4gZGl2ID4gdWwgPiBsaSA+IGFcIixcbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXJfX3NlYXJjaC10cmlnZ2VyXCIsXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyX19zZWFyY2gtaW5wdXRcIlxuICAgICAgICAgIF0uam9pbihcIixcIikpXG4gICAgICAgICAgICAuY3VzdG9tQ1NTKFwiZmxvLWJsb2NrLXNsaWRlc2hvdy0yLVwiICsgaWQsIFtcbiAgICAgICAgICAgICAgXCIuaXMtbm90LXN0aWNreSBcIiArIGRvdGJfZm9yX2N1c3RvbV9jc3MgKyBcIntcIixcbiAgICAgICAgICAgICAgICBcImNvbG9yOiBcIiArIGVsZW1lbnRzX2NvbG9yICsgXCIhaW1wb3J0YW50O1wiLFxuICAgICAgICAgICAgICAgIFwiYm9yZGVyLWNvbG9yOiBcIiArIGVsZW1lbnRzX2NvbG9yICsgXCIhaW1wb3J0YW50O1wiLFxuICAgICAgICAgICAgICBcIn1cIlxuICAgICAgICAgICAgXS5qb2luKFwiXFxuXCIpKVxuICAgICAgICAgIDtcblxuICAgICAgICB9LCBjaGVja19kZWxheSk7XG4gICAgICB9KVxuICAgIC8qIEVORDogQ09MT1IgQ0hBTkdFICovXG4gICAgLyogU1RBUlQ6IENIQU5HRSBMT0dPIEJBU0VEIE9OIFNMSURFIEVMRU1FTlRTIENPTE9SICovXG4gICAgICAub24oXCJpbml0IGJlZm9yZUNoYW5nZVwiLCBmdW5jdGlvbihlLCBzbGljaywgY3VycmVudFNsaWRlLCBuZXh0U2xpZGUpe1xuICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpO1xuICAgICAgICB2YXIgJGN1cnJlbnRTbGlkZSA9ICR0aGlzLmZpbmQoXCIuc2xpY2stY3VycmVudFwiKTtcbiAgICAgICAgdmFyIGVsZW1lbnRzX2NvbG9yO1xuICAgICAgICBpZiAoZS50eXBlID09IFwiaW5pdFwiKSB7XG4gICAgICAgICAgZWxlbWVudHNfY29sb3IgPSAkY3VycmVudFNsaWRlLmF0dHIoXCJkYXRhLWVsZW1lbnRzLWNvbG9yXCIpO1xuICAgICAgICB9IGVsc2UgaWYgKGUudHlwZSA9PSBcImJlZm9yZUNoYW5nZVwiKSB7XG4gICAgICAgICAgZWxlbWVudHNfY29sb3IgPSAkdGhpcy5maW5kKFwiLnNsaWNrLXNsaWRlW2RhdGEtc2xpY2staW5kZXg9J1wiK25leHRTbGlkZStcIiddXCIpLmF0dHIoXCJkYXRhLWVsZW1lbnRzLWNvbG9yXCIpO1xuICAgICAgICB9XG4gICAgICAgIHZhciBjb2xvcl9icmlnaHRuZXNzID0gaXNfY29sb3JfYnJpZ2h0KGVsZW1lbnRzX2NvbG9yKTtcblxuICAgICAgICBpZiAoY29sb3JfYnJpZ2h0bmVzcykge1xuICAgICAgICAgIHBhcmVudC5maW5kKGRvdGIgKyBcIl9fbG9nb1wiKS5hZGRDbGFzcyhiICsgXCJfX2xvZ28tLWxpZ2h0XCIpO1xuXG4gICAgICAgICAgcGFyZW50LmZpbmQoXCIuZmxvLWhlYWRlcl9fbG9nb1wiKS5hZGRDbGFzcyhcImZsby1oZWFkZXJfX2xvZ28tLWxpZ2h0XCIpO1xuXG4gICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgcGFyZW50LmZpbmQoXCIuZmxvLWhlYWRlci1tb2JpbGUgLmZsby1oZWFkZXItbW9iaWxlX19sb2dvXCIpLmFkZENsYXNzKFwiZmxvLWhlYWRlci1tb2JpbGVfX2xvZ28tLWxpZ2h0XCIpO1xuICAgICAgICAgIH0pO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHBhcmVudC5maW5kKGRvdGIgKyBcIl9fbG9nb1wiKS5yZW1vdmVDbGFzcyhiICsgXCJfX2xvZ28tLWxpZ2h0XCIpO1xuXG4gICAgICAgICAgcGFyZW50LmZpbmQoXCIuZmxvLWhlYWRlcl9fbG9nb1wiKS5yZW1vdmVDbGFzcyhcImZsby1oZWFkZXJfX2xvZ28tLWxpZ2h0XCIpO1xuXG4gICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgcGFyZW50LmZpbmQoXCIuZmxvLWhlYWRlci1tb2JpbGUgLmZsby1oZWFkZXItbW9iaWxlX19sb2dvXCIpLnJlbW92ZUNsYXNzKFwiZmxvLWhlYWRlci1tb2JpbGVfX2xvZ28tLWxpZ2h0XCIpO1xuICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgICB9KVxuICAgIC8qIEVORDogQ0hBTkdFIExPR08gQkFTRUQgT04gU0xJREUgRUxFTUVOVFMgQ09MT1IgKi9cblxuICAgIC8qIFNUQVJUOiBWSURFTyBCQUNLR1JPVU5EICovXG4gICAgICAub24oXCJpbml0IHJlSW5pdCBhZnRlckNoYW5nZVwiLCBmdW5jdGlvbigpe1xuICAgICAgICAvLyBTdGFydDogUGF1c2UgYWxsIHZpZGVvc1xuICAgICAgICAgICRlbC5maW5kKGRvdGIgKyBcIl9fc2xpZGUtLXZpZGVvX3NsaWRlOm5vdCguc2xpY2stY3VycmVudClcIikuZmluZChcInZpZGVvXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMucGF1c2UoKTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgLy8gRW5kOiBQYXVzZSBhbGwgdmlkZW9zXG4gICAgICAgIHZhciBhY3RpdmVfc2xpZGVfXyQgPSAkZWwuZmluZChcIi5zbGljay1jdXJyZW50XCIpO1xuICAgICAgICBpZiAoYWN0aXZlX3NsaWRlX18kLmhhc0NsYXNzKGIgKyBcIl9fc2xpZGUtLXZpZGVvX3NsaWRlXCIpKSB7XG4gICAgICAgICAgdmFyIHZpZGVvX2NvbnRhaW5lciA9IGFjdGl2ZV9zbGlkZV9fJC5maW5kKGRvdGIgKyBcIl9fc2xpZGUtYmFja2dyb3VuZC12aWRlb1wiKTtcbiAgICAgICAgICB2YXIgdmlkZW8gPSB2aWRlb19jb250YWluZXIuZmluZChcInZpZGVvXCIpWzBdO1xuXG4gICAgICAgICAgdmlkZW8ucGxheSgpO1xuICAgICAgICB9XG4gICAgICB9KVxuICAgIC8qIEVORDogVklERU8gQkFDS0dST1VORCAqL1xuXG4gICAgLnNsaWNrKHtcbiAgICAgIG5leHRBcnJvdzogJGVsLmZpbmQoZG90YiArIFwiX19hcnJvdy0tbmV4dFwiKSxcbiAgICAgIHByZXZBcnJvdzogJGVsLmZpbmQoZG90YiArIFwiX19hcnJvdy0tcHJldlwiKSxcbiAgICAgIHBhdXNlT25Gb2N1czogZmFsc2UsXG4gICAgICB2YXJpYWJsZVdpZHRoOiB0cnVlLFxuICAgICAgY2VudGVyTW9kZTogdHJ1ZVxuICAgIH0pXG4gIDtcbn1cbiIsIndpbmRvdy5mbG9fYmxvY2tfc2xpZGVzaG93XzEgPSBmdW5jdGlvbihlbCl7XG4gIFwidXNlIHN0cmljdFwiO1xuICB2YXIgJGVsID0gJChlbCk7XG4gIHZhciBiID0gXCJmbG8tYmxvY2stc2xpZGVzaG93LTFcIjtcbiAgdmFyIGRvdGIgPSBcIi5cIiArIGI7XG4gIHZhciBwYXJlbnQgPSAkZWwucGFyZW50cyhcIi5mbG8tYmxvY2tcIik7XG4gIHZhciBpZCA9IHBhcmVudC5hdHRyKFwiZGF0YS1pZFwiKTtcblxuICAkZWwuZmluZChkb3RiICsgXCJfX3NsaWRlc1wiKVxuXG4gICAgLyogU1RBUlQ6IENPTE9SIENIQU5HRSAqL1xuICAgICAgLm9uKFwiaW5pdCBiZWZvcmVDaGFuZ2VcIiwgZnVuY3Rpb24oZSwgc2xpY2ssIGN1cnJlbnRTbGlkZSwgbmV4dFNsaWRlKXtcbiAgICAgICAgdmFyIGVsZW1lbnRzX2NvbG9yO1xuICAgICAgICB2YXIgY2hlY2tfZGVsYXk7XG4gICAgICAgIGlmIChlLnR5cGUgPT0gXCJpbml0XCIpIHtcbiAgICAgICAgICBjaGVja19kZWxheSA9IDEwMDtcbiAgICAgICAgICBlbGVtZW50c19jb2xvciA9ICRlbC5maW5kKFwiLnNsaWNrLWN1cnJlbnQgXCIgKyBkb3RiICtcIl9fc2xpZGUtY29udGVudFwiKS5jc3MoXCJjb2xvclwiKTtcbiAgICAgICAgfSBlbHNlIGlmIChlLnR5cGUgPT0gXCJiZWZvcmVDaGFuZ2VcIikge1xuICAgICAgICAgIGNoZWNrX2RlbGF5ID0gMDtcbiAgICAgICAgICBlbGVtZW50c19jb2xvciA9ICRlbC5maW5kKFwiLnNsaWNrLXNsaWRlW2RhdGEtc2xpY2staW5kZXg9J1wiKyBuZXh0U2xpZGUgK1wiJ10gXCIgKyBkb3RiICsgXCJfX3NsaWRlLWNvbnRlbnRcIikuY3NzKFwiY29sb3JcIik7XG4gICAgICAgIH1cbiAgICAgICAgJGVsLmZpbmQoZG90YiArIFwiX19hcnJvd1wiKVxuICAgICAgICAgIC5jc3Moe1xuICAgICAgICAgICAgXCJjb2xvclwiOiBlbGVtZW50c19jb2xvclxuICAgICAgICAgIH0pXG4gICAgICAgIDtcbiAgICAgICAgJGVsLmZpbmQoXCIuc2xpY2stZG90cyBsaVwiKVxuICAgICAgICAgIC5jc3Moe1xuICAgICAgICAgICAgXCJiYWNrZ3JvdW5kLWNvbG9yXCI6IGVsZW1lbnRzX2NvbG9yLFxuICAgICAgICAgICAgXCJjb2xvclwiOiBlbGVtZW50c19jb2xvclxuICAgICAgICAgIH0pXG4gICAgICAgIDtcblxuICAgICAgICB2YXIgYl9mb3JfY3VzdG9tX2NzcyA9IFwiZmxvLWJsb2NrLXNsaWRlc2hvdy0xLVwiICsgaWQ7XG4gICAgICAgIHZhciBkb3RiX2Zvcl9jdXN0b21fY3NzID0gXCIuXCIgKyBiX2Zvcl9jdXN0b21fY3NzO1xuXG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgcGFyZW50LmZpbmQoW1xuICAgICAgICAgICAgXCIuZmxvLWhlYWRlcl9fbG9nb1wiLFxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1tb2JpbGVfX2xvZ29cIixcbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXJfX21lbnUgPiBkaXYgPiB1bCA+IGxpID4gYVwiLFxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlcl9fc2VhcmNoLXRyaWdnZXJcIixcbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXJfX3NlYXJjaC1pbnB1dFwiXG4gICAgICAgICAgXS5qb2luKFwiLFwiKSlcbiAgICAgICAgICAgIC5jdXN0b21DU1MoXCJmbG8tYmxvY2stc2xpZGVzaG93LTEtXCIgKyBpZCwgW1xuICAgICAgICAgICAgICBcIi5pcy1ub3Qtc3RpY2t5IFwiICsgZG90Yl9mb3JfY3VzdG9tX2NzcyArIFwie1wiLFxuICAgICAgICAgICAgICAgIFwiY29sb3I6IFwiICsgZWxlbWVudHNfY29sb3IgKyBcIiFpbXBvcnRhbnQ7XCIsXG4gICAgICAgICAgICAgICAgXCJib3JkZXItY29sb3I6IFwiICsgZWxlbWVudHNfY29sb3IgKyBcIiFpbXBvcnRhbnQ7XCIsXG4gICAgICAgICAgICAgIFwifVwiXG4gICAgICAgICAgICBdLmpvaW4oXCJcXG5cIikpXG4gICAgICAgICAgO1xuXG4gICAgICAgIH0sIGNoZWNrX2RlbGF5KTtcbiAgICAgIH0pXG4gICAgLyogRU5EOiBDT0xPUiBDSEFOR0UgKi9cblxuICAgIC8qIFNUQVJUOiBDSEFOR0UgTE9HTyBCQVNFRCBPTiBTTElERSBFTEVNRU5UUyBDT0xPUiAqL1xuICAgICAgLm9uKFwiaW5pdCBiZWZvcmVDaGFuZ2VcIiwgZnVuY3Rpb24oZSwgc2xpY2ssIGN1cnJlbnRTbGlkZSwgbmV4dFNsaWRlKXtcbiAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgdmFyICRjdXJyZW50U2xpZGUgPSAkdGhpcy5maW5kKFwiLnNsaWNrLWN1cnJlbnRcIik7XG4gICAgICAgIHZhciBlbGVtZW50c19jb2xvcjtcbiAgICAgICAgaWYgKGUudHlwZSA9PSBcImluaXRcIikge1xuICAgICAgICAgIGVsZW1lbnRzX2NvbG9yID0gJGN1cnJlbnRTbGlkZS5hdHRyKFwiZGF0YS1lbGVtZW50cy1jb2xvclwiKTtcbiAgICAgICAgfSBlbHNlIGlmIChlLnR5cGUgPT0gXCJiZWZvcmVDaGFuZ2VcIikge1xuICAgICAgICAgIGVsZW1lbnRzX2NvbG9yID0gJHRoaXMuZmluZChcIi5zbGljay1zbGlkZVtkYXRhLXNsaWNrLWluZGV4PSdcIituZXh0U2xpZGUrXCInXVwiKS5hdHRyKFwiZGF0YS1lbGVtZW50cy1jb2xvclwiKTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgY29sb3JfYnJpZ2h0bmVzcyA9IGlzX2NvbG9yX2JyaWdodChlbGVtZW50c19jb2xvcik7XG5cblxuXG4gICAgICAgIGlmIChjb2xvcl9icmlnaHRuZXNzKSB7XG4gICAgICAgICAgcGFyZW50LmZpbmQoZG90YiArIFwiX19sb2dvXCIpLmFkZENsYXNzKGIgKyBcIl9fbG9nby0tbGlnaHRcIik7XG5cbiAgICAgICAgICBwYXJlbnQuZmluZChcIi5mbG8taGVhZGVyX19sb2dvXCIpLmFkZENsYXNzKFwiZmxvLWhlYWRlcl9fbG9nby0tbGlnaHRcIik7XG5cbiAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBwYXJlbnQuZmluZChcIi5mbG8taGVhZGVyLW1vYmlsZSAuZmxvLWhlYWRlci1tb2JpbGVfX2xvZ29cIikuYWRkQ2xhc3MoXCJmbG8taGVhZGVyLW1vYmlsZV9fbG9nby0tbGlnaHRcIik7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgcGFyZW50LmZpbmQoZG90YiArIFwiX19sb2dvXCIpLnJlbW92ZUNsYXNzKGIgKyBcIl9fbG9nby0tbGlnaHRcIik7XG5cbiAgICAgICAgICBwYXJlbnQuZmluZChcIi5mbG8taGVhZGVyX19sb2dvXCIpLnJlbW92ZUNsYXNzKFwiZmxvLWhlYWRlcl9fbG9nby0tbGlnaHRcIik7XG5cbiAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBwYXJlbnQuZmluZChcIi5mbG8taGVhZGVyLW1vYmlsZSAuZmxvLWhlYWRlci1tb2JpbGVfX2xvZ29cIikucmVtb3ZlQ2xhc3MoXCJmbG8taGVhZGVyLW1vYmlsZV9fbG9nby0tbGlnaHRcIik7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgIH0pXG4gICAgLyogRU5EOiBDSEFOR0UgTE9HTyBCQVNFRCBPTiBTTElERSBFTEVNRU5UUyBDT0xPUiAqL1xuXG4gICAgLyogU1RBUlQ6IFZJREVPIEJBQ0tHUk9VTkQgKi9cbiAgICAgIC5vbihcImluaXQgcmVJbml0IGFmdGVyQ2hhbmdlXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgIC8vIFN0YXJ0OiBQYXVzZSBhbGwgdmlkZW9zXG4gICAgICAgICAgJGVsLmZpbmQoZG90YiArIFwiX19zbGlkZS0tdmlkZW9fc2xpZGU6bm90KC5zbGljay1jdXJyZW50KVwiKS5maW5kKFwidmlkZW9cIikuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5wYXVzZSgpO1xuICAgICAgICAgIH0pO1xuICAgICAgICAvLyBFbmQ6IFBhdXNlIGFsbCB2aWRlb3NcbiAgICAgICAgdmFyIGFjdGl2ZV9zbGlkZV9fJCA9ICRlbC5maW5kKFwiLnNsaWNrLWN1cnJlbnRcIik7XG4gICAgICAgIGlmIChhY3RpdmVfc2xpZGVfXyQuaGFzQ2xhc3MoYiArIFwiX19zbGlkZS0tdmlkZW9fc2xpZGVcIikpIHtcbiAgICAgICAgICB2YXIgdmlkZW9fY29udGFpbmVyID0gYWN0aXZlX3NsaWRlX18kLmZpbmQoZG90YiArIFwiX19zbGlkZS1iYWNrZ3JvdW5kLXZpZGVvXCIpO1xuICAgICAgICAgIHZhciB2aWRlbyA9IHZpZGVvX2NvbnRhaW5lci5maW5kKFwidmlkZW9cIilbMF07XG5cbiAgICAgICAgICB2aWRlby5wbGF5KCk7XG4gICAgICAgIH1cbiAgICAgIH0pXG4gICAgLyogRU5EOiBWSURFTyBCQUNLR1JPVU5EICovXG5cbiAgICAuc2xpY2soe1xuICAgICAgbmV4dEFycm93OiAkZWwuZmluZChkb3RiICsgXCJfX2Fycm93LS1uZXh0XCIpLFxuICAgICAgcHJldkFycm93OiAkZWwuZmluZChkb3RiICsgXCJfX2Fycm93LS1wcmV2XCIpXG4gICAgfSlcbiAgO1xufVxuIiwid2luZG93LmZsb19ibG9ja190ZXN0aW1vbmlhbHNfMiA9IGZ1bmN0aW9uKGVsKXtcbiAgXCJ1c2Ugc3RyaWN0XCI7XG4gIHZhciAkZWwgPSAkKGVsKTtcbiAgdmFyIGIgPSBcImZsby1ibG9jay10ZXN0aW1vbmlhbHMtMlwiO1xuICB2YXIgZG90YiA9IFwiLlwiICsgYjtcbiAgdmFyIHBhcmVudCA9ICRlbC5wYXJlbnRzKFwiLmZsby1ibG9ja1wiKTtcbiAgdmFyICRiID0gJGVsLmZpbmQoZG90Yik7XG5cbiAgJGVsLmZpbmQoZG90YiArIFwiX190ZXN0aW1vbmlhbHNcIikuc2xpY2soe1xuICAgIGZhZGU6IHRydWUsXG4gICAgbmV4dEFycm93OiAkZWwuZmluZChkb3RiICsgXCJfX2NvbnRyb2xzLWFycm93LS1uZXh0XCIpLFxuICAgIHByZXZBcnJvdzogJGVsLmZpbmQoZG90YiArIFwiX19jb250cm9scy1hcnJvdy0tcHJldlwiKSxcbiAgICBhZGFwdGl2ZUhlaWdodDogdHJ1ZVxuICB9KTtcbn1cbiIsIndpbmRvdy5mbG9fYmxvY2tfdmlkZW8gPSBmdW5jdGlvbihlbCl7XG4gIFwidXNlIHN0cmljdFwiO1xuICB2YXIgJGVsID0gJChlbCk7XG4gIHZhciBiID0gXCJmbG9fYmxvY2tfdmlkZW9cIjtcbiAgdmFyIGRvdGIgPSBcIi5cIiArIGI7XG4gIHZhciBwYXJlbnQgPSAkZWwucGFyZW50cyhcIi5mbG8tYmxvY2tcIik7XG4gIHZhciAkYiA9ICRlbDtcbiAgdmFyIGlkID0gcGFyZW50LmF0dHIoXCJkYXRhLWlkXCIpO1xuXG4gIC8qIFNUQVJUOiBDT0xPUiBDSEFOR0UgKi9cbiAgICB2YXIgZWxlbWVudHNfY29sb3IgPSAkYi5hdHRyKFwiZGF0YS1lbGVtZW50cy1jb2xvclwiKTtcbiAgICB2YXIgYl9mb3JfY3VzdG9tX2NzcyA9IFwiZmxvLWJsb2NrLXZpZGVvLVwiICsgaWQ7XG4gICAgdmFyIGRvdGJfZm9yX2N1c3RvbV9jc3MgPSBcIi5cIiArIGJfZm9yX2N1c3RvbV9jc3M7XG5cbiAgICBwYXJlbnQuZmluZChbXG4gICAgICBcIi5mbG8taGVhZGVyX19sb2dvXCIsXG4gICAgICBcIi5mbG8taGVhZGVyLW1vYmlsZV9fbG9nb1wiLFxuICAgICAgXCIuZmxvLWhlYWRlcl9fbWVudSA+IGRpdiA+IHVsID4gbGkgPiBhXCIsXG4gICAgICBcIi5mbG8taGVhZGVyX19zZWFyY2gtdHJpZ2dlclwiLFxuICAgICAgXCIuZmxvLWhlYWRlcl9fc2VhcmNoLWlucHV0XCJcbiAgICBdLmpvaW4oXCIsXCIpKVxuICAgICAgLmN1c3RvbUNTUyhiX2Zvcl9jdXN0b21fY3NzLCBbXG4gICAgICAgIFwiLmlzLW5vdC1zdGlja3kgXCIgKyBkb3RiX2Zvcl9jdXN0b21fY3NzICsgXCJ7XCIsXG4gICAgICAgICAgXCJjb2xvcjogXCIgKyBlbGVtZW50c19jb2xvciArIFwiIWltcG9ydGFudDtcIixcbiAgICAgICAgICBcImJvcmRlci1jb2xvcjogXCIgKyBlbGVtZW50c19jb2xvciArIFwiIWltcG9ydGFudDtcIixcbiAgICAgICAgXCJ9XCJcbiAgICAgIF0uam9pbihcIlxcblwiKSlcbiAgICA7XG4gIC8qIEVORDogQ09MT1IgQ0hBTkdFICovXG59XG4iLCIkKGRvY3VtZW50KS5vbihcImZsb0luaXRcIiwgXCIuZmxvLWdlbmVyaWMtc2xpZGVzXCIsIGZ1bmN0aW9uKGUsIHNsaWNrQWRkaXRpb25hbE9wdGlvbnMpe1xuICBcInVzZSBzdHJpY3RcIjtcblxuICB2YXIgJGVsID0gJCh0aGlzKTtcbiAgdmFyIGIgPSBcImZsby1nZW5lcmljLXNsaWRlc1wiO1xuICB2YXIgZG90YiA9IFwiLlwiICsgYjtcbiAgdmFyIHBhcmVudCA9ICRlbC5wYXJlbnRzKFwiLmZsby1ibG9ja1wiKTtcbiAgdmFyIHBhcmVudF9jbGFzcyA9IFwiZmxvLWJsb2NrXCIgKyBwYXJlbnQuYXR0cihcImRhdGEtaWRcIik7XG5cbiAgLyogU1RBUlQ6IE1FUkdFIERFRkFVTFQgU0xJQ0sgT1BUSU9OUyBXSVRIIEFERElUSU9OQUwgT05FUyAqL1xuICAgIHZhciBzbGlja09wdGlvbnMgPSB7XG4gICAgICBcImRvdHNcIjogXCJmYWxzZVwiLFxuICAgICAgXCJhcnJvd3NcIjogXCJmYWxzZVwiLFxuICAgICAgXCJjc3NFYXNlXCI6IFwiY3ViaWMtYmV6aWVyKDAuMjMsIDEsIDAuMzIsIDEpXCIsXG4gICAgfTtcbiAgICBmb3IgKHZhciBhdHRybmFtZSBpbiBzbGlja0FkZGl0aW9uYWxPcHRpb25zKSB7XG4gICAgICBzbGlja09wdGlvbnNbYXR0cm5hbWVdID0gc2xpY2tBZGRpdGlvbmFsT3B0aW9uc1thdHRybmFtZV07XG4gICAgfVxuICAvKiBFTkQ6IE1FUkdFIERFRkFVTFQgU0xJQ0sgT1BUSU9OUyBXSVRIIEFERElUSU9OQUwgT05FUyAqL1xuXG4gICRlbFxuICAgIC8qIFNUQVJUOiBWSURFTyBCQUNLR1JPVU5EICovXG4gICAgICAub24oXCJpbml0IHJlSW5pdCBhZnRlckNoYW5nZVwiLCBmdW5jdGlvbigpe1xuICAgICAgICAvLyBTdGFydDogUGF1c2UgYWxsIHZpZGVvc1xuICAgICAgICAgICRlbC5maW5kKGRvdGIgKyBcIl9fc2xpZGUtLXZpZGVvX3NsaWRlOm5vdCguc2xpY2stY3VycmVudClcIikuZmluZChcInZpZGVvXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgICAgICAgIHRoaXMucGF1c2UoKTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgLy8gRW5kOiBQYXVzZSBhbGwgdmlkZW9zXG4gICAgICAgIHZhciBhY3RpdmVfc2xpZGVfXyQgPSAkZWwuZmluZChcIi5zbGljay1jdXJyZW50XCIpO1xuICAgICAgICBpZiAoYWN0aXZlX3NsaWRlX18kLmhhc0NsYXNzKGIgKyBcIl9fc2xpZGUtLXZpZGVvX3NsaWRlXCIpKSB7XG4gICAgICAgICAgdmFyIHZpZGVvX2NvbnRhaW5lciA9IGFjdGl2ZV9zbGlkZV9fJC5maW5kKGRvdGIgKyBcIl9fc2xpZGUtYmFja2dyb3VuZC12aWRlb1wiKTtcbiAgICAgICAgICB2YXIgdmlkZW8gPSB2aWRlb19jb250YWluZXIuZmluZChcInZpZGVvXCIpWzBdO1xuXG4gICAgICAgICAgdmlkZW8ucGxheSgpO1xuICAgICAgICB9XG4gICAgICB9KVxuICAgIC8qIEVORDogVklERU8gQkFDS0dST1VORCAqL1xuXG4gICAgLyogU1RBUlQ6IFZJREVPIEVNQkVEICovXG4gICAgICAub24oXCJpbml0XCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciAkc2xpZGVzID0gJCh0aGlzKTtcbiAgICAgICAgJGVsLmZpbmQoXCIuXCIrYitcIl9fc2xpZGUtLWltYWdlX2FuZF92aWRlb19lbWJlZFwiKS5lYWNoKGZ1bmN0aW9uKCl7XG4gICAgICAgICAgdmFyIGFjdGl2ZV9zbGlkZV9fJCA9ICQodGhpcyk7XG4gICAgICAgICAgdmFyIHZpZGVvX2VtYmVkX2hvc3QgPSBwYXJlbnQ7XG4gICAgICAgICAgdmFyIHZpZGVvX2J1dHRvbiA9IGFjdGl2ZV9zbGlkZV9fJC5maW5kKFwiLmZsby1oZXJvLXZpZGVvLWVtYmVkX19idXR0b25cIik7XG4gICAgICAgICAgdmFyIHZpZGVvX2NvbnRhaW5lciA9IGFjdGl2ZV9zbGlkZV9fJC5maW5kKFwiLmZsby1oZXJvLXZpZGVvLWVtYmVkX19jb250YWluZXJcIik7XG4gICAgICAgICAgdmFyIGVtYmVkX2NvZGUgPSBhY3RpdmVfc2xpZGVfXyQuYXR0cihcImRhdGEtZW1iZWQtY29kZVwiKTtcblxuICAgICAgICAgIHZpZGVvX2J1dHRvbi5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBpZiAoIXZpZGVvX2VtYmVkX2hvc3QuaGFzQ2xhc3MoXCJ2aWRlby1pcy1wbGF5aW5nXCIpKSB7XG4gICAgICAgICAgICAgIHZpZGVvX2NvbnRhaW5lci5odG1sKHVuZXNjYXBlKGVtYmVkX2NvZGUpKTtcbiAgICAgICAgICAgICAgdmlkZW9fZW1iZWRfaG9zdC5hZGRDbGFzcyhcInZpZGVvLWlzLXBsYXlpbmdcIik7XG5cbiAgICAgICAgICAgICAgJHNsaWRlcy5zbGljayhcInNsaWNrU2V0T3B0aW9uXCIsIFwiYXV0b3BsYXlcIiwgZmFsc2UsIHRydWUpO1xuICAgICAgICAgICAgfSBlbHNlIGlmICh2aWRlb19lbWJlZF9ob3N0Lmhhc0NsYXNzKFwidmlkZW8taXMtcGxheWluZ1wiKSkge1xuICAgICAgICAgICAgICB2aWRlb19jb250YWluZXIuaHRtbChcIlwiKTtcbiAgICAgICAgICAgICAgdmlkZW9fZW1iZWRfaG9zdC5yZW1vdmVDbGFzcyhcInZpZGVvLWlzLXBsYXlpbmdcIik7XG5cbiAgICAgICAgICAgICAgdmFyIGF1dG9wbGF5ID0gJHNsaWRlcy5hdHRyKFwiZGF0YS1hdXRvcGxheVwiKSA9PSBcInRydWVcIiA/IHRydWUgOiBmYWxzZTtcbiAgICAgICAgICAgICAgJHNsaWRlcy5zbGljayhcInNsaWNrU2V0T3B0aW9uXCIsIFwiYXV0b3BsYXlcIiwgYXV0b3BsYXkgLCB0cnVlKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgICB9KVxuICAgICAgLm9uKFwiYmVmb3JlQ2hhbmdlXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAgIHZhciBhY3RpdmVfc2xpZGVfXyQgPSAkKHRoaXMpLmZpbmQoXCIuc2xpY2stY3VycmVudFwiKTtcbiAgICAgICAgdmFyIHZpZGVvX2VtYmVkX2hvc3QgPSBwYXJlbnQ7XG5cbiAgICAgICAgLyogU1RBUlQ6IFZJREVPIEVNQkVEIENMT1NFIE9OIFNMSURFIENIQU5HRSAqL1xuICAgICAgICAgIGlmICh2aWRlb19lbWJlZF9ob3N0Lmhhc0NsYXNzKFwidmlkZW8taXMtcGxheWluZ1wiKSkge1xuICAgICAgICAgICAgYWN0aXZlX3NsaWRlX18kLmZpbmQoXCIuZmxvLWhlcm8tdmlkZW8tZW1iZWRfX2J1dHRvblwiKS5jbGljaygpO1xuICAgICAgICAgIH1cbiAgICAgICAgLyogRU5EOiBWSURFTyBFTUJFRCBDTE9TRSBPTiBTTElERSBDSEFOR0UgKi9cblxuICAgICAgfSlcbiAgICAvKiBFTkQ6IFZJREVPIEVNQkVEICovXG5cbiAgICAvKiBTVEFSVDogQ0hBTkdFIExPR08gQkFTRUQgT04gU0xJREUgRUxFTUVOVFMgQ09MT1IgKi9cbiAgICAgIC5vbihcImluaXQgYWZ0ZXJDaGFuZ2VcIiwgZnVuY3Rpb24oKXtcbiAgICAgICAgdmFyICR0aGlzID0gJCh0aGlzKTtcbiAgICAgICAgdmFyICRjdXJyZW50U2xpZGUgPSAkdGhpcy5maW5kKFwiLnNsaWNrLWN1cnJlbnRcIik7XG4gICAgICAgIHZhciBlbGVtZW50c19jb2xvciA9ICRjdXJyZW50U2xpZGUuYXR0cihcImRhdGEtZWxlbWVudHMtY29sb3JcIik7XG4gICAgICAgIHZhciBjb2xvcl9icmlnaHRuZXNzID0gaXNfY29sb3JfYnJpZ2h0KGVsZW1lbnRzX2NvbG9yKTtcblxuICAgICAgICBpZiAoY29sb3JfYnJpZ2h0bmVzcykge1xuICAgICAgICAgIHBhcmVudC5maW5kKGRvdGIgKyBcIl9fbG9nb1wiKS5hZGRDbGFzcyhiICsgXCJfX2xvZ28tLWxpZ2h0XCIpO1xuXG4gICAgICAgICAgcGFyZW50LmZpbmQoXCIuZmxvLWhlYWRlcl9fbG9nb1wiKS5hZGRDbGFzcyhcImZsby1oZWFkZXJfX2xvZ28tLWxpZ2h0XCIpO1xuXG4gICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbigpe1xuICAgICAgICAgICAgcGFyZW50LmZpbmQoXCIuaXMtbWFpbiAuZmxvLWhlYWRlci1tb2JpbGVfX2xvZ29cIikuYWRkQ2xhc3MoXCJmbG8taGVhZGVyLW1vYmlsZV9fbG9nby0tbGlnaHRcIik7XG4gICAgICAgICAgfSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgcGFyZW50LmZpbmQoZG90YiArIFwiX19sb2dvXCIpLnJlbW92ZUNsYXNzKGIgKyBcIl9fbG9nby0tbGlnaHRcIik7XG5cbiAgICAgICAgICBwYXJlbnQuZmluZChcIi5mbG8taGVhZGVyX19sb2dvXCIpLnJlbW92ZUNsYXNzKFwiZmxvLWhlYWRlcl9fbG9nby0tbGlnaHRcIik7XG5cbiAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG4gICAgICAgICAgICBwYXJlbnQuZmluZChcIi5pcy1tYWluIC5mbG8taGVhZGVyLW1vYmlsZV9fbG9nb1wiKS5yZW1vdmVDbGFzcyhcImZsby1oZWFkZXItbW9iaWxlX19sb2dvLS1saWdodFwiKTtcbiAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgfSlcbiAgICAvKiBFTkQ6IENIQU5HRSBMT0dPIEJBU0VEIE9OIFNMSURFIEVMRU1FTlRTIENPTE9SICovXG5cbiAgICAvKiBTVEFSVDogRklSRSBFVkVOVFMgT04gTElHSFQgT1IgREFSSyBFTEVNRU5UUyBDT0xPUiAqL1xuICAgICAgLm9uKFwiaW5pdCBhZnRlckNoYW5nZVwiLCBmdW5jdGlvbigpe1xuICAgICAgICB2YXIgJHRoaXMgPSAkKHRoaXMpO1xuICAgICAgICB2YXIgJGN1cnJlbnRTbGlkZSA9ICR0aGlzLmZpbmQoXCIuc2xpY2stY3VycmVudFwiKTtcbiAgICAgICAgdmFyIGVsZW1lbnRzX2NvbG9yID0gJGN1cnJlbnRTbGlkZS5hdHRyKFwiZGF0YS1lbGVtZW50cy1jb2xvclwiKTtcbiAgICAgICAgdmFyIGNvbG9yX2JyaWdodG5lc3MgPSBpc19jb2xvcl9icmlnaHQoZWxlbWVudHNfY29sb3IpO1xuXG4gICAgICAgIGlmIChjb2xvcl9icmlnaHRuZXNzKSB7XG4gICAgICAgICAgJHRoaXMudHJpZ2dlcihcImVsZW1lbnRzQ29sb3JMaWdodFwiLCBlbGVtZW50c19jb2xvcilcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAkdGhpcy50cmlnZ2VyKFwiZWxlbWVudHNDb2xvckRhcmtcIiwgZWxlbWVudHNfY29sb3IpXG4gICAgICAgIH1cbiAgICAgIH0pXG4gICAgLyogRU5EOiBGSVJFIEVWRU5UUyBPTiBMSUdIVCBPUiBEQVJLIEVMRU1FTlRTIENPTE9SICovXG5cbiAgICAvKiBTVEFSVDogSU5JVElBTElaQVRJT04gKi9cbiAgICAgIC5zbGljayhzbGlja09wdGlvbnMpXG4gICAgLyogRU5EOiBJTklUSUFMSVpBVElPTiAqL1xuICA7XG59KTtcbiIsIiQoZnVuY3Rpb24oKXtcclxuXHJcbiAgLy8gU3RhcnQ6IFNjcm9sbCBVcCBCdXR0b25cclxuICAgICQoJy5mbG8tZm9vdGVyX19zY3JvbGwtdXAsIC5mbG8tZm9vdGVyLWJfX2JhY2stdG8tdG9wJykuY2xpY2soZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICQoJ2JvZHksaHRtbCcpLmFuaW1hdGUoeyBzY3JvbGxUb3A6IDB9LCA4MDApO1xyXG4gICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgIH0pO1xyXG4gIC8vIEVuZDogU2Nyb2xsIFVwIEJ1dHRvblxyXG5cclxuICAvKiBTdGFydDogRHJvcGRvd24gKi9cclxuICAgIGlmICgkKHdpbmRvdykud2lkdGgoKSA+IDc2OCkgdmFyIGRyb3Bkb3duX2VsZW1lbnRzID0gbmV3IEZvdW5kYXRpb24uRHJvcGRvd25NZW51KFxyXG4gICAgICAkKFwiLmZsby1mb290ZXIgLm1lbnUtaXRlbS1oYXMtY2hpbGRyZW5cIilcclxuICAgICk7XHJcbiAgLyogRW5kOiBEcm9wZG93biAqL1xyXG5cclxufSk7XHJcbiIsIiQoZnVuY3Rpb24oKXtcbiAgdmFyIGlzX25vdF9zdGlja3lfY2xhc3MgPSBcImlzLW5vdC1zdGlja3lcIjtcbiAgJChcIi5mbG8taGVhZGVyLW1vYmlsZVwiKS5hZGRDbGFzcyhpc19ub3Rfc3RpY2t5X2NsYXNzKTtcbiAgJChcIi5mbG8taGVhZGVyLW1vYmlsZS5zdGlja3lcIilcbiAgICAub24oXCJzdGlja3ktc3RhcnRcIiwgZnVuY3Rpb24oKXtcbiAgICAgICQodGhpcykucmVtb3ZlQ2xhc3MoaXNfbm90X3N0aWNreV9jbGFzcyk7XG4gICAgfSlcbiAgICAub24oXCJzdGlja3ktZW5kXCIsIGZ1bmN0aW9uKCl7XG4gICAgICAkKHRoaXMpLmFkZENsYXNzKGlzX25vdF9zdGlja3lfY2xhc3MpO1xuICAgIH0pXG4gICAgLnN0aWNreSh7XG4gICAgICAgIHpJbmRleDogMTAwMCxcbiAgICAgICAgY2xhc3NOYW1lOiBcImlzLXN0aWNreVwiLFxuICAgICAgICB3cmFwcGVyQ2xhc3NOYW1lOiBcImZsby1oZWFkZXItbW9iaWxlLXN0aWNreS13cmFwcGVyXCJcbiAgICB9KVxuICA7XG59KTtcbiIsIiQoZnVuY3Rpb24oKXtcbiAgdmFyIGhlYWRlcl9wb3B1cF9fJCA9ICQoXCIuZmxvLWhlYWRlci1wb3B1cFwiKTtcbiAgdmFyIGhlYWRlcl9wb3B1cF9fdmlld3NfJCA9ICQoXCIuZmxvLWhlYWRlci1wb3B1cF9fdmlld3NcIik7XG4gIHZhciBoZWFkZXJfcG9wdXBfX2NsYXNzZXMgPSB7XG4gICAgdmlzaWJsZSA6IFwiYm9keS0tZmxvLWhlYWRlci1wb3B1cC12aXNpYmxlXCIsXG4gICAgaGlkZGVuIDogXCJib2R5LS1mbG8taGVhZGVyLXBvcHVwLWhpZGRlblwiXG4gIH1cbiAgdmFyIGhlYWRlcl9wb3B1cF9fc2V0ID0gZnVuY3Rpb24oc3RhdGUpIHtcbiAgICBzd2l0Y2ggKHN0YXRlKSB7XG4gICAgICBjYXNlIFwidmlzaWJsZVwiOlxuICAgICAgICAkKFwiYm9keVwiKS5hZGRDbGFzcyhoZWFkZXJfcG9wdXBfX2NsYXNzZXMudmlzaWJsZSk7XG4gICAgICAgICQoXCJib2R5XCIpLnJlbW92ZUNsYXNzKGhlYWRlcl9wb3B1cF9fY2xhc3Nlcy5oaWRkZW4pO1xuICAgICAgYnJlYWs7XG5cbiAgICAgIGNhc2UgXCJoaWRkZW5cIjpcbiAgICAgICAgJChcImJvZHlcIikuYWRkQ2xhc3MoaGVhZGVyX3BvcHVwX19jbGFzc2VzLmhpZGRlbik7XG4gICAgICAgICQoXCJib2R5XCIpLnJlbW92ZUNsYXNzKGhlYWRlcl9wb3B1cF9fY2xhc3Nlcy52aXNpYmxlKTtcbiAgICAgIGJyZWFrO1xuXG4gICAgICBjYXNlIFwiaW5pdGlhbFwiOlxuICAgICAgYnJlYWs7XG5cbiAgICAgIGRlZmF1bHQ6XG5cbiAgICB9XG4gIH1cblxuXG4gIC8qIFNUQVJUOiBQT1BVUCBUUklHR0VSICovXG4gICAgdmFyIGhlYWRlcl9wb3B1cF90cmlnZ2VyX18kID0gJChcIi5mbG8taGVhZGVyX19oYW1idXJnZXJcIik7XG4gICAgaGVhZGVyX3BvcHVwX3RyaWdnZXJfXyQub24oXCJjbGlja1wiLCBmdW5jdGlvbigpe1xuICAgICAgaGVhZGVyX3BvcHVwX19zZXQoXCJ2aXNpYmxlXCIpO1xuICAgIH0pO1xuICAvKiBFTkQ6IFBPUFVQIFRSSUdHRVIgKi9cblxuICAvKiBTVEFSVDogSElERSBPTiBFU0NBUEUgKi9cbiAgICAkKGRvY3VtZW50KS5rZXl1cChmdW5jdGlvbihlKSB7XG4gICAgICBpZiAoZS5rZXlDb2RlID09IDI3KSB7XG4gICAgICAgIGhlYWRlcl9wb3B1cF9fc2V0KFwiaGlkZGVuXCIpO1xuICAgICAgICBoZWFkZXJfcG9wdXBfX3NldChcImluaXRpYWxcIilcbiAgICAgIH1cbiAgICB9KTtcbiAgLyogRU5EOiBISURFIE9OIEVTQ0FQRSAqL1xuXG4gIC8qIFNUQVJUOiBTRUFSQ0ggVFJJR0dFUiAqL1xuICAgIHZhciBzZWFyY2hfX3RyaWdnZXJfJCA9ICQoXCIuZmxvLWhlYWRlci1wb3B1cF9fc2VhcmNoLXRyaWdnZXJcIik7XG4gICAgdmFyIHNlYXJjaF9fdmlld18kID0gJChcIi5mbG8taGVhZGVyLXBvcHVwX192aWV3LS1zZWFyY2hcIik7XG5cbiAgICBzZWFyY2hfX3RyaWdnZXJfJC5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCl7XG4gICAgICBoZWFkZXJfcG9wdXBfX3ZpZXdzXyQuZm91bmRhdGlvbihcbiAgICAgICAgXCJjaGFuZ2VTbGlkZVwiLFxuICAgICAgICBmYWxzZSxcbiAgICAgICAgc2VhcmNoX192aWV3XyRcbiAgICAgICk7XG4gICAgfSk7XG4gIC8qIEVORDogU0VBUkNIIFRSSUdHRVIgKi9cblxuICAvKiBTVEFSVDogQ0xPU0UgQlVUVE9OICovXG4gICAgdmFyIGNsb3NlX2J1dHRvbl9fJCA9ICQoXCIuZmxvLWhlYWRlci1wb3B1cF9fY2xvc2UtYnV0dG9uXCIpO1xuICAgIGNsb3NlX2J1dHRvbl9fJC5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCl7XG5cbiAgICAgIGZpcnN0X3ZpZXdfXyQgPSAkKFwiLmZsby1oZWFkZXItcG9wdXBfX3ZpZXdbZGF0YS1zbGlkZT0wXVwiKVxuICAgICAgZmlyc3Rfdmlld19faXNfYWN0aXZlID0gZmlyc3Rfdmlld19fJC5oYXNDbGFzcyhcImlzLWFjdGl2ZVwiKTtcblxuICAgICAgLyogU3RhcnQ6IGlmIGZpcnN0IHNsaWRlIGFjdGl2ZSBjbG9zZSBwb3B1cCAqL1xuICAgICAgICBpZiAoZmlyc3Rfdmlld19faXNfYWN0aXZlKSB7XG4gICAgICAgICAgaGVhZGVyX3BvcHVwX19zZXQoXCJoaWRkZW5cIilcbiAgICAgICAgfVxuICAgICAgLyogRW5kOiBpZiBmaXJzdCBzbGlkZSBhY3RpdmUgY2xvc2UgcG9wdXAgKi9cblxuICAgICAgLyogU3RhcnQ6IGlmIGFub3RoZXIgc2xpZGUgaXMgYWN0aXZlIHNldCBmaXJzdCBzbGlkZSBhcyBhY3RpdmUgKi9cbiAgICAgICAgaWYgKCFmaXJzdF92aWV3X19pc19hY3RpdmUpIHtcbiAgICAgICAgICBoZWFkZXJfcG9wdXBfX3ZpZXdzXyQuZm91bmRhdGlvbihcbiAgICAgICAgICAgIFwiY2hhbmdlU2xpZGVcIixcbiAgICAgICAgICAgIGZhbHNlLFxuICAgICAgICAgICAgZmlyc3Rfdmlld19fJFxuICAgICAgICAgICk7XG4gICAgICAgIH1cbiAgICAgIC8qIEVuZDogaWYgYW5vdGhlciBzbGlkZSBpcyBhY3RpdmUgc2V0IGZpcnN0IHNsaWRlIGFzIGFjdGl2ZSAqL1xuXG4gICAgfSk7XG4gIC8qIEVORDogQ0xPU0UgQlVUVE9OICovXG5cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuICAvKiBTdGFydDogTG9nbyBDZW50ZXIgLSBzcGxpdCBtZW51IGluIGhhbGYgKi9cbiAgICBpZiAoJChcIi5mbG8taGVhZGVyLS1tZW51LWNlbnRlclwiKS5sZW5ndGgpe1xuICAgICAgdmFyXG4gICAgICAgICRtZW51X2Rvbm9yID0gJChcIi5mbG8taGVhZGVyX19tZW51LWRvbm9yXCIpLFxuICAgICAgICAkbWVudV9kb25vcl91bCA9ICRtZW51X2Rvbm9yLmZpbmQoXCI+IGRpdiA+IHVsXCIpLFxuICAgICAgICAkbWVudV9kb25vcl9maXJzdF9sZXZlbCA9ICRtZW51X2Rvbm9yX3VsLmNoaWxkcmVuKFwibGlcIiksXG4gICAgICAgICRtZW51X2xlZnQgPSAkKFwiLmZsby1oZWFkZXJfX21lbnUtd3JhcC0tbGVmdCAuZmxvLWhlYWRlcl9fbWVudSA+IGRpdiA+IHVsXCIpLFxuICAgICAgICAkbWVudV9yaWdodCA9ICQoXCIuZmxvLWhlYWRlcl9fbWVudS13cmFwLS1yaWdodCAuZmxvLWhlYWRlcl9fbWVudSA+IGRpdiA+IHVsXCIpXG4gICAgICA7XG4gICAgICAkbWVudV9kb25vcl9maXJzdF9sZXZlbC5lYWNoKGZ1bmN0aW9uKGluZGV4KXtcbiAgICAgICAgdmFyXG4gICAgICAgICAgJGl0ZW0gPSAkKHRoaXMpLFxuICAgICAgICAgIGxlbmd0aCA9ICRtZW51X2Rvbm9yX2ZpcnN0X2xldmVsLmxlbmd0aFxuICAgICAgICA7XG4gICAgICAgIGlmIChpbmRleCA8IGxlbmd0aCAvIDIpIHtcbiAgICAgICAgICAkbWVudV9sZWZ0LmFwcGVuZCgkaXRlbSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGluZGV4ID49IGxlbmd0aCAvIDIpIHtcbiAgICAgICAgICAkbWVudV9yaWdodC5hcHBlbmQoJGl0ZW0pO1xuICAgICAgICB9XG4gICAgICAgIGlmIChpbmRleCA9PSBsZW5ndGgtMSkge1xuICAgICAgICAgICRtZW51X2Rvbm9yLnJlbW92ZSgpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9XG4gIC8qIEVuZDogTG9nbyBDZW50ZXIgLSBzcGxpdCBtZW51IGluIGhhbGYgKi9cblxuICAvKiBTdGFydDogU3RpY2t5IEhlYWRlciAqL1xuICAgIHZhciBpc19ub3Rfc3RpY2t5X2NsYXNzID0gXCJpcy1ub3Qtc3RpY2t5XCI7XG4gICAgdmFyIG1lbnVfd3JhcHBlcl9fJCA9ICQoXCIuZmxvLWhlYWRlcl9fbWVudS13cmFwXCIpO1xuICAgIHZhciBtZW51X3RyaWdnZXJfXyQgPSAkKFwiLmZsby1oZWFkZXJfX2hhbWJ1cmdlclwiKTtcbiAgICB2YXIgbG9nb19fJCA9ICQoXCIuZmxvLWhlYWRlcl9fbG9nb1wiKTtcbiAgICAkKFwiLmZsby1oZWFkZXJcIikuYWRkQ2xhc3MoaXNfbm90X3N0aWNreV9jbGFzcyk7XG4gICAgaWYgKCAkKHdpbmRvdykud2lkdGgoKSA+IDc2NyApICQoXCJib2R5Lmhhcy1zdGlja3kgLmZsby1oZWFkZXJcIilcbiAgICAub24oXCJzdGlja3ktc3RhcnRcIiwgZnVuY3Rpb24oKXtcbiAgICAgICQodGhpcykucmVtb3ZlQ2xhc3MoaXNfbm90X3N0aWNreV9jbGFzcyk7XG5cbiAgICAgIC8qIFNUQVJUOiBDRU5URVIgRUxFTUVOVFMgVkVSVElDQUxMWSAqL1xuICAgICAgICB2YXIgc3RpY2t5X3dyYXBwZXJfX2hlaWdodCA9ICQodGhpcykuaGVpZ2h0KCk7XG5cbiAgICAgICAgLyogU1RBUlQ6IENFTlRFUiBNRU5VICovXG4gICAgICAgICAgdmFyIG1lbnVfd3JhcHBlcl9faGVpZ2h0ID0gbWVudV93cmFwcGVyX18kLmhlaWdodCgpO1xuICAgICAgICAgIHZhciBtZW51X3dyYXBwZXJfX29uX3N0aWNreV90cmFuc2xhdGVZX3ZhbHVlID0gKHN0aWNreV93cmFwcGVyX19oZWlnaHQgLSBtZW51X3dyYXBwZXJfX2hlaWdodCkgLyAyO1xuXG4gICAgICAgICAgbWVudV93cmFwcGVyX18kLmNzcyhcbiAgICAgICAgICAgIFwidHJhbnNmb3JtXCIsXG4gICAgICAgICAgICBcInRyYW5zbGF0ZVkoXCIrIG1lbnVfd3JhcHBlcl9fb25fc3RpY2t5X3RyYW5zbGF0ZVlfdmFsdWUgK1wicHgpXCJcbiAgICAgICAgICApO1xuICAgICAgICAvKiBFTkQ6IENFTlRFUiBNRU5VICovXG5cbiAgICAgICAgLyogU1RBUlQ6IENFTlRFUiBNRU5VIFRSSUdHRVIgKi9cbiAgICAgICAgICB2YXIgbWVudV90cmlnZ2VyX19oZWlnaHQgPSBtZW51X3RyaWdnZXJfXyQuaGVpZ2h0KCk7XG4gICAgICAgICAgdmFyIG1lbnVfdHJpZ2dlcl9fb25fc3RpY2t5X3RyYW5zbGF0ZVlfdmFsdWUgPSAoc3RpY2t5X3dyYXBwZXJfX2hlaWdodCAtIG1lbnVfdHJpZ2dlcl9faGVpZ2h0KSAvIDI7XG5cbiAgICAgICAgICBtZW51X3RyaWdnZXJfXyQuY3NzKFxuICAgICAgICAgICAgXCJ0cmFuc2Zvcm1cIixcbiAgICAgICAgICAgIFwidHJhbnNsYXRlWShcIisgbWVudV90cmlnZ2VyX19vbl9zdGlja3lfdHJhbnNsYXRlWV92YWx1ZSArXCJweClcIlxuICAgICAgICAgICk7XG4gICAgICAgIC8qIEVORDogQ0VOVEVSIE1FTlUgVFJJR0dFUiAqL1xuXG4gICAgICAgIC8qIFNUQVJUOiBDRU5URVIgTUVOVSBMT0dPICovXG4gICAgICAgICAgdmFyIGxvZ29fX2hlaWdodCA9IGxvZ29fXyQuaGVpZ2h0KCk7XG4gICAgICAgICAgdmFyIGxvZ29fX29uX3N0aWNreV90cmFuc2xhdGVZX3ZhbHVlID0gKHN0aWNreV93cmFwcGVyX19oZWlnaHQgLSBsb2dvX19oZWlnaHQpIC8gMjtcblxuICAgICAgICAgIGxvZ29fXyQuY3NzKFxuICAgICAgICAgICAgXCJ0cmFuc2Zvcm1cIixcbiAgICAgICAgICAgIFwidHJhbnNsYXRlWShcIisgbG9nb19fb25fc3RpY2t5X3RyYW5zbGF0ZVlfdmFsdWUgK1wicHgpXCJcbiAgICAgICAgICApO1xuICAgICAgICAvKiBFTkQ6IENFTlRFUiBNRU5VIExPR08gKi9cblxuICAgICAgLyogRU5EOiBDRU5URVIgRUxFTUVOVFMgVkVSVElDQUxMWSAqL1xuICAgIH0pXG4gICAgLm9uKFwic3RpY2t5LWVuZFwiLCBmdW5jdGlvbigpe1xuICAgICAgJCh0aGlzKS5hZGRDbGFzcyhpc19ub3Rfc3RpY2t5X2NsYXNzKTtcblxuICAgICAgLyogU1RBUlQ6IFVOQ0VOVEVSIEVMRU1FTlRTICovXG4gICAgICAgIG1lbnVfd3JhcHBlcl9fJC5jc3MoXCJ0cmFuc2Zvcm1cIiwgXCJcIik7XG4gICAgICAgIG1lbnVfdHJpZ2dlcl9fJC5jc3MoXCJ0cmFuc2Zvcm1cIiwgXCJcIik7XG4gICAgICAgIGxvZ29fXyQuY3NzKFwidHJhbnNmb3JtXCIsIFwiXCIpO1xuICAgICAgLyogRU5EOiBVTkNFTlRFUiBFTEVNRU5UUyAqL1xuICAgIH0pXG4gICAgLnN0aWNreSh7XG4gICAgICAgIHpJbmRleDogOCxcbiAgICAgICAgY2xhc3NOYW1lOiBcImlzLXN0aWNreVwiLFxuICAgICAgICB3cmFwcGVyQ2xhc3NOYW1lOiBcImZsby1oZWFkZXItc3RpY2t5LXdyYXBwZXJcIlxuICAgIH0pO1xuICAvKiBFbmQ6IFN0aWNreSBIZWFkZXIqL1xuXG4gIC8qIFN0YXJ0OiBEcm9wZG93biAqL1xuICAgIHZhciBkcm9wZG93bl9lbGVtZW50cyA9IG5ldyBGb3VuZGF0aW9uLkRyb3Bkb3duTWVudShcbiAgICAgICQoXCIuZmxvLWhlYWRlciAubWVudS1pdGVtLWhhcy1jaGlsZHJlbiB1bFwiKVxuICAgICk7XG4gIC8qIEVuZDogRHJvcGRvd24gKi9cblxuICAvKiBTdGFydDogU2VhcmNoIFRyaWdnZXIgKi9cbiAgICB2YXIgZmxvX2hlYWRlcl9zZWFyY2hfX29wZW5fY2xhc3MgPSBcImZsby1oZWFkZXItLXNlYXJjaC1vcGVuXCI7XG5cbiAgICAkKFwiLmZsby1oZWFkZXJfX3NlYXJjaC10cmlnZ2VyXCIpLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24oKXtcbiAgICAgICQoXCIuZmxvLWhlYWRlclwiKS50b2dnbGVDbGFzcyhmbG9faGVhZGVyX3NlYXJjaF9fb3Blbl9jbGFzcyk7XG4gICAgfSk7XG4gIC8qIEVuZDogU2VhcmNoIFRyaWdnZXIgKi9cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuXG5cbiAgICAkKFwiLmZsby1oZXJvLTJfX3NsaWRlclwiKS5vbignYWZ0ZXJDaGFuZ2UnLCBmdW5jdGlvbigpe1xuICAgICAgICAvLyBCYWNrZ3JvdW5kQ2hlY2suaW5pdCh7XG4gICAgICAgIC8vICAgICB0YXJnZXRzOiAnLmZsby1zZWN0aW9uX190aXRsZS13cmFwJyxcbiAgICAgICAgLy8gICAgIGltYWdlczogJy5mbG8tcGFnZS1oZXJvX19zbGlkZS1jb250ZW50J1xuICAgICAgICAvLyB9KTtcbiAgICB9KS5zbGljayh7XG4gICAgICAgIGZhZGU6IHRydWUsXG4gICAgICAgIGFycm93czogZmFsc2UsXG4gICAgICAgIGRvdHM6IGZhbHNlLFxuICAgICAgICBjc3NFYXNlOiBcImN1YmljLWJlemllcigwLjQ0NSwgMC4wNTAsIDAuNTUwLCAwLjk1MClcIixcbiAgICAgICAgcmVzcG9uc2l2ZTogW1xuICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIGJyZWFrcG9pbnQ6IDYwMCxcbiAgICAgICAgICAgICAgICBzZXR0aW5nczoge1xuICAgICAgICAgICAgICAgICAgICBhcnJvd3M6IGZhbHNlXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICBdXG5cbiAgICB9KTtcbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuICAgICQoXCIuZmxvLWhlcm8tM19fc2xpZGVyXCIpXG4gICAgLmZsb19scWlwKFwic2V0U2xpY2tQcmVsb2FkXCIpXG4gICAgLm9uKFwiYWZ0ZXJDaGFuZ2VcIiwgZnVuY3Rpb24oZSwgc2xpY2spe1xuICAgICAgdmFyIHNsaWRlc2hvd19fJCA9IHNsaWNrLiRzbGlkZXI7XG5cbiAgICAgIC8qIFN0YXJ0OiBUaHVtYm5haWxzIHN5bmMgKi9cbiAgICAgICAgdmFyIHNsaWRlc2hvd190aHVtYm5haWxzX19zZWxlY3RvciA9IFwiLmZsby1wb3J0Zm9saW8tcG9zdF9fdGh1bWJuYWlsc1wiO1xuICAgICAgICBpZiAoJChzbGlkZXNob3dfdGh1bWJuYWlsc19fc2VsZWN0b3IpLmxlbmd0aCkge1xuICAgICAgICAgIHNsaWRlc2hvd19fJC5zbGljayhcInNsaWNrU2V0T3B0aW9uXCIsIHtcbiAgICAgICAgICAgIFwiYXNOYXZGb3JcIjogc2xpZGVzaG93X3RodW1ibmFpbHNfX3NlbGVjdG9yXG4gICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgIC8qIEVuZDogVGh1bWJuYWlscyBzeW5jICovXG4gICAgfSlcbiAgICAub24oXCJhZnRlckNoYW5nZSBpbml0XCIsIGZ1bmN0aW9uKCl7XG4gICAgICB2YXIgc2xpZGVzaG93X18kID0gJCh0aGlzKTtcbiAgICAgIHZhciBzbGlkZXNob3dfX2xpc3QgPSBzbGlkZXNob3dfXyQuZmluZChcIi5zbGljay1saXN0XCIpO1xuICAgICAgdmFyIHNsaWRlc2hvd19fYWN0aXZlX3NsaWRlID0gc2xpZGVzaG93X18kLmZpbmQoXCIuc2xpY2stY3VycmVudFwiKTtcbiAgICAgIHZhciBzbGlkZXNob3dfX2FjdGl2ZV9zbGlkZV9pbWcgPSBzbGlkZXNob3dfX2FjdGl2ZV9zbGlkZS5maW5kKFwiaW1nXCIpO1xuXG4gICAgICAvKiBTdGFydDogU2V0IGhlaWdodCBvZiBzbGlkZXIgYnkgaW1hZ2UgKi9cbiAgICAgICAgLy8gc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgIC8vICAgc2xpZGVzaG93X19saXN0LmNzcyhcImhlaWdodFwiLCBzbGlkZXNob3dfX2FjdGl2ZV9zbGlkZV9pbWcuaGVpZ2h0KCkpO1xuICAgICAgICAvLyB9LCA1MDApO1xuICAgICAgICBzbGlkZXNob3dfX2FjdGl2ZV9zbGlkZS5maW5kKFwiaW1nXCIpLm9uKFwibG9hZFwiLCBmdW5jdGlvbigpe1xuICAgICAgICAgIHNsaWRlc2hvd19fbGlzdC5jc3MoXCJoZWlnaHRcIiwgc2xpZGVzaG93X19hY3RpdmVfc2xpZGVfaW1nLmhlaWdodCgpKTtcbiAgICAgICAgfSk7XG4gICAgICAvKiBFbmQ6IFNldCBoZWlnaHQgb2Ygc2xpZGVyIGJ5IGltYWdlICovXG4gICAgfSlcbiAgICAub24oXCJpbml0XCIsIGZ1bmN0aW9uKCl7XG4gICAgICAkKHRoaXMpLmZpbmQoJy5zbGljay1saXN0JykuYXR0cigndGFiaW5kZXgnLCAwKS5mb2N1cygpO1xuICAgIH0pXG4gICAgLnNsaWNrKHtcbiAgICAgICAgc3BlZWQ6IDQwMCxcbiAgICAgICAgYXJyb3dzOiB0cnVlLFxuICAgICAgICBwYXVzZU9uRm9jdXM6IGZhbHNlLFxuICAgICAgICBuZXh0QXJyb3c6ICc8c3BhbiBjbGFzcz1cImZsby1oZXJvLTNfX2Fycm93IGZsby1oZXJvLTNfX2Fycm93LS1uZXh0IGZsby1pY29uLWFycm93LXJpZ2h0XCI+PC9zcGFuPicsXG4gICAgICAgIHByZXZBcnJvdzogJzxzcGFuIGNsYXNzPVwiZmxvLWhlcm8tM19fYXJyb3cgZmxvLWhlcm8tM19fYXJyb3ctLXByZXYgZmxvLWljb24tYXJyb3ctbGVmdFwiPjwvc3Bhbj4nLFxuICAgICAgICBjc3NFYXNlOiBcImN1YmljLWJlemllcigwLjQ0NSwgMC4wNTAsIDAuNTUwLCAwLjk1MClcIixcbiAgICAgICAgcmVzcG9uc2l2ZTogW1xuICAgICAgICAgIHtcbiAgICAgICAgICAgIGJyZWFrcG9pbnQ6IDczNixcbiAgICAgICAgICAgIHNldHRpbmdzOiB7XG4gICAgICAgICAgICAgIHNsaWRlc1RvU2hvdzogMSxcbiAgICAgICAgICAgICAgZG90czogZmFsc2UsXG4gICAgICAgICAgICAgIGFycm93czogdHJ1ZSxcbiAgICAgICAgICAgICAgY2VudGVyUGFkZGluZzogMCxcbiAgICAgICAgICAgICAgYWRhcHRpdmVIZWlnaHQ6IHRydWUsXG4gICAgICAgICAgICAgIHZhcmlhYmxlV2lkdGg6IGZhbHNlXG4gICAgICAgICAgICB9XG4gICAgICAgICAgfVxuICAgICAgICBdLFxuICAgIH0pO1xuXG4gICAgdmFyIGluaXRfbWFzb25yeSA9IGZ1bmN0aW9uKCl7XG4gICAgJCgnLmZsby1nYWxsZXJ5LXR5cGUtY19fcm93JykubWFzb25yeSh7XG4gICAgICAgIC8vIG9wdGlvbnNcbiAgICAgICAgaXRlbVNlbGVjdG9yOiAnLmZsby1nYWxsZXJ5LXR5cGUtY19fY29sdW1uJ1xuICAgIH0pO1xuICB9XG5cbiAgaW5pdF9tYXNvbnJ5KCk7XG5cbiAgLyogU3RhcnQ6IElzIE9uIFNjcmVlbiBGdW5jdGlvbiAqL1xuICAgIGZ1bmN0aW9uIGlzSW5WaWV3cG9ydChlbGVtZW50KSB7XG4gICAgICB2YXIgcmVjdCA9IGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCk7XG4gICAgICB2YXIgaHRtbCA9IGRvY3VtZW50LmRvY3VtZW50RWxlbWVudDtcbiAgICAgIHZhciBvZmZzZXQgPSA4MDA7XG4gICAgICByZXR1cm4gKFxuICAgICAgICByZWN0LnRvcCA+PSAwICYmXG4gICAgICAgIHJlY3QubGVmdCA+PSAwICYmXG4gICAgICAgIHJlY3QuYm90dG9tIDw9ICh3aW5kb3cuaW5uZXJIZWlnaHQgKyBvZmZzZXQgIHx8IGh0bWwuY2xpZW50SGVpZ2h0ICsgb2Zmc2V0KSAgJiZcbiAgICAgICAgcmVjdC5yaWdodCA8PSAod2luZG93LmlubmVyV2lkdGggfHwgaHRtbC5jbGllbnRXaWR0aClcbiAgICAgICk7XG4gICAgfVxuICAvKiBFbmQ6IElzIE9uIFNjcmVlbiBGdW5jdGlvbiAqL1xuXG4gICQoXCIuZmxvLWdhbGxlcnktdHlwZS1jX19pbWFnZTpub3QoW3NyY10pXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICB2YXIgaW1hZ2UgPSAkKHRoaXMpO1xuXG4gICAgaW1hZ2Uub24oXCJsb2FkXCIsIGZ1bmN0aW9uKCl7XG4gICAgICBpbml0X21hc29ucnkoKTtcbiAgICB9KTtcblxuICAgICQod2luZG93KS5vbihcInNjcm9sbFwiLCBmdW5jdGlvbigpe1xuICAgICAgaWYgKGlzSW5WaWV3cG9ydChpbWFnZVswXSkpIHtcbiAgICAgICAgaW1hZ2UuYXR0cihcInNyY1wiLCBpbWFnZS5hdHRyKFwiZGF0YS1zcmNcIikgKTtcbiAgICAgIH1cbiAgICB9KTtcblxuICB9KTtcblxufSk7XG4iLCIkKGZ1bmN0aW9uKCl7XHJcbiAgdmFyIG1vYmlsZV9tZW51ID0gJChcIi5mbG8tbW9iaWxlLW1lbnVcIik7XHJcblxyXG4gIC8qIFNUQVJUOiBESVNQTEFZL0hJREUgTU9CSUxFIE1FTlUgKi9cclxuICAgICQoXCIuZmxvLWhlYWRlci1tb2JpbGVfX21lbnUtdHJpZ2dlclwiKS5jbGljayhmdW5jdGlvbigpIHtcclxuICAgICAgJCgnLmZsby1tb2JpbGUtbWVudScpLmFkZENsYXNzKCdmbG8tbW9iaWxlLW1lbnUtLXZpc2libGUnKTtcclxuICAgIH0pO1xyXG5cclxuICAgICQoXCIuZmxvLW1vYmlsZS1tZW51X19jbG9zZVwiKS5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCl7XHJcbiAgICAgIC8vIFNhZmFyaSBuZWVkcyB0aGlzLi4uXHJcbiAgICB9KTtcclxuXHJcbiAgICAkKFwiYm9keVwiKS5vbihcclxuICAgICAgXCJjbGlja1wiLFxyXG4gICAgICBcIi5mbG8tbW9iaWxlLW1lbnU6bm90KC5mbG8tbW9iaWxlLW1lbnUtLXNlYXJjaC12aXNpYmxlKSAuZmxvLW1vYmlsZS1tZW51X19jbG9zZVwiLFxyXG4gICAgICBmdW5jdGlvbigpIHtcclxuICAgICAgY29uc29sZS5sb2coXCJoZWxsb1wiKTtcclxuICAgICAgICAkKCcuZmxvLW1vYmlsZS1tZW51JykucmVtb3ZlQ2xhc3MoJ2Zsby1tb2JpbGUtbWVudS0tdmlzaWJsZScpO1xyXG4gICAgICB9XHJcbiAgICApO1xyXG4gIC8qICBFTkQ6IERJU1BMQVkvSElERSBNT0JJTEUgTUVOVSAqL1xyXG5cclxuICAvKiBTVEFSVDogVE9HR0xFIFNFQVJDSCAqL1xyXG4gICAgJChcIi5mbG8tbW9iaWxlLW1lbnVfX3NlYXJjaC10b2dnbGVcIilcclxuICAgIC5vbihcImNsaWNrXCIsIGZ1bmN0aW9uKCl7XHJcbiAgICAgIG1vYmlsZV9tZW51LmFkZENsYXNzKFwiZmxvLW1vYmlsZS1tZW51LS1zZWFyY2gtdmlzaWJsZVwiKTtcclxuICAgIH0pO1xyXG5cclxuICAgICQoXCJib2R5XCIpLm9uKFwiY2xpY2tcIiwgXCIuZmxvLW1vYmlsZS1tZW51LS1zZWFyY2gtdmlzaWJsZSAuZmxvLW1vYmlsZS1tZW51X19jbG9zZVwiLCBmdW5jdGlvbigpe1xyXG4gICAgICBtb2JpbGVfbWVudS5yZW1vdmVDbGFzcyhcImZsby1tb2JpbGUtbWVudS0tc2VhcmNoLXZpc2libGVcIik7XHJcbiAgICB9KTtcclxuXHJcbiAgLyogRU5EOiBUT0dHTEUgU0VBUkNIICovXHJcblxyXG4gIC8qIFNUQVJUOiBUT0dHTEUgRFJPUERPV04gKi9cclxuXHJcbiAgICAvKiBTdGFydDogQWRkIGRyb3Bkb3duIHRvZ2dsZXMgdG8gZXZlcnkgaXRlbSB3aXRoIGRyb3Bkb3duICovXHJcbiAgICAgICQoXCIuZmxvLW1vYmlsZS1tZW51X19tZW51LS1zdHlsZS1jb2xsYXBzZWQgPiB1bCA+IGxpLm1lbnUtaXRlbS1oYXMtY2hpbGRyZW5cIilcclxuICAgICAgICAuY2hpbGRyZW4oXCJhXCIpXHJcbiAgICAgICAgICAuYWZ0ZXIoXCI8ZGl2IGNsYXNzPSdmbG8tbW9iaWxlLW1lbnVfX21lbnUtZHJvcGRvd24tdG9nZ2xlJz48aSBjbGFzcz0nZmxvLWljb24tYXJyb3ctcmlnaHQtYmlnJz48L2k+PC9kaXY+XCIpXHJcbiAgICAgIDtcclxuXHJcbiAgICAgICQoXCIuZmxvLW1vYmlsZS1tZW51XCIpLm9uKFwiY2xpY2tcIiwgXCIuZmxvLW1vYmlsZS1tZW51X19tZW51LWRyb3Bkb3duLXRvZ2dsZVwiLCBmdW5jdGlvbihlKXtcclxuICAgICAgICAkKHRoaXMpLnNpYmxpbmdzKFwiLnN1Yi1tZW51XCIpLnNsaWRlVG9nZ2xlKFwic2xvd1wiKTtcclxuICAgICAgICAkKHRoaXMpLnBhcmVudCgpLnRvZ2dsZUNsYXNzKFwiY2hpbGRyZW4tdmlzaWJsZVwiKTtcclxuICAgICAgfSk7XHJcbiAgICAvKiBFbmQ6IEFkZCBkcm9wZG93biB0b2dnbGVzIHRvIGV2ZXJ5IGl0ZW0gd2l0aCBkcm9wZG93biAqL1xyXG5cclxuICAvKiBFTkQ6IFRPR0dMRSBEUk9QRE9XTiAqL1xyXG5cclxufSk7XHJcbiIsIiQoZnVuY3Rpb24oKXtcblxuICAvKiBTVEFSVDogVklERU8gRU1CRUQgKi9cbiAgICAkKFwiLmZsby1wYWdlLWhlcm9fX3NsaWRlLS1pbWFnZV9hbmRfdmlkZW9fZW1iZWRcIikuZWFjaChmdW5jdGlvbigpe1xuICAgICAgYWN0aXZlX3NsaWRlX18kID0gJCh0aGlzKTtcbiAgICAgIHZhciB2aWRlb19idXR0b24gPSBhY3RpdmVfc2xpZGVfXyQuZmluZChcIi5mbG8tcGFnZS1oZXJvX192aWRlby1idXR0b25cIik7XG4gICAgICB2YXIgdmlkZW9fY29udGFpbmVyID0gYWN0aXZlX3NsaWRlX18kLmZpbmQoXCIuZmxvLXBhZ2UtaGVyb19fc2xpZGUtdmlkZW8tZW1iZWQtY29udGFpbmVyXCIpO1xuICAgICAgdmFyIGVtYmVkX2NvZGUgPSBhY3RpdmVfc2xpZGVfXyQuYXR0cihcImRhdGEtZW1iZWQtY29kZVwiKTtcblxuICAgICAgdmlkZW9fYnV0dG9uLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24oKXtcbiAgICAgICAgaWYgKCEkKFwiYm9keVwiKS5oYXNDbGFzcyhcImJvZHktLWZsby1wYWdlLWhlcm8tdmlkZW8taXMtcGxheWluZ1wiKSkge1xuICAgICAgICAgIHZpZGVvX2NvbnRhaW5lci5odG1sKHVuZXNjYXBlKGVtYmVkX2NvZGUpKTtcbiAgICAgICAgICAkKFwiYm9keVwiKS5hZGRDbGFzcyhcImJvZHktLWZsby1wYWdlLWhlcm8tdmlkZW8taXMtcGxheWluZ1wiKTtcblxuICAgICAgICAgICQoXCIuZmxvLXBhZ2UtaGVyb19fc2xpZGVzXCIpLnNsaWNrKFwic2xpY2tTZXRPcHRpb25cIiwgXCJhdXRvcGxheVwiLCBmYWxzZSwgdHJ1ZSk7XG4gICAgICAgIH0gZWxzZSBpZiAoJChcImJvZHlcIikuaGFzQ2xhc3MoXCJib2R5LS1mbG8tcGFnZS1oZXJvLXZpZGVvLWlzLXBsYXlpbmdcIikpIHtcbiAgICAgICAgICB2aWRlb19jb250YWluZXIuaHRtbChcIlwiKTtcbiAgICAgICAgICAkKFwiYm9keVwiKS5yZW1vdmVDbGFzcyhcImJvZHktLWZsby1wYWdlLWhlcm8tdmlkZW8taXMtcGxheWluZ1wiKTtcblxuICAgICAgICAgIHZhciBhdXRvcGxheSA9ICQoXCIuZmxvLXBhZ2UtaGVyb19fc2xpZGVzXCIpLmF0dHIoXCJkYXRhLWF1dG9wbGF5XCIpID09IFwidHJ1ZVwiID8gdHJ1ZSA6IGZhbHNlO1xuICAgICAgICAgICQoXCIuZmxvLXBhZ2UtaGVyb19fc2xpZGVzXCIpLnNsaWNrKFwic2xpY2tTZXRPcHRpb25cIiwgXCJhdXRvcGxheVwiLCBhdXRvcGxheSAsIHRydWUpO1xuICAgICAgICB9XG4gICAgICB9KTtcbiAgICB9KTtcbiAgLyogRU5EOiBWSURFTyBFTUJFRCAqL1xuXG4gIC8qIFNUQVJUOiBTTElERVNIT1cgSU5JVElBTElaQVRJT04gKi9cbiAgICAkKFwiLmZsby1wYWdlLWhlcm9fX3NsaWRlc1wiKVxuICAgIC5vbihcImJlZm9yZUNoYW5nZVwiLCBmdW5jdGlvbigpe1xuICAgICAgdmFyIGFjdGl2ZV9zbGlkZV9fJCA9ICQoXCIuZmxvLXBhZ2UtaGVyb19fc2xpZGUuc2xpY2stY3VycmVudFwiKTtcblxuICAgICAgLyogU1RBUlQ6IFZJREVPIEVNQkVEIENMT1NFIE9OIFNMSURFIENIQU5HRSAqL1xuICAgICAgICBpZiAoJChcImJvZHlcIikuaGFzQ2xhc3MoXCJib2R5LS1mbG8tcGFnZS1oZXJvLXZpZGVvLWlzLXBsYXlpbmdcIikpIHtcbiAgICAgICAgICBhY3RpdmVfc2xpZGVfXyQuZmluZChcIi5mbG8tcGFnZS1oZXJvX192aWRlby1idXR0b25cIikuY2xpY2soKTtcbiAgICAgICAgfVxuICAgICAgLyogRU5EOiBWSURFTyBFTUJFRCBDTE9TRSBPTiBTTElERSBDSEFOR0UgKi9cbiAgICB9KVxuICAgIC5vbihcImFmdGVyQ2hhbmdlIGluaXRcIiwgZnVuY3Rpb24oZSl7XG4gICAgICB2YXIgYWN0aXZlX3NsaWRlX18kID0gJChcIi5mbG8tcGFnZS1oZXJvX19zbGlkZS5zbGljay1jdXJyZW50XCIpO1xuICAgICAgdmFyIGFjdGl2ZV9zbGlkZV9fZWxlbWVudHNfY29sb3IgPSBhY3RpdmVfc2xpZGVfXyQuYXR0cihcImRhdGEtZWxlbWVudHMtY29sb3JcIik7XG4gICAgICBpZiAoICQoXCIuZ2FsbGVyeS1lbGVtZW50cy1jb2xvclwiKS5sZW5ndGggKSB7XG4gICAgICAgIHZhciBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yID0gJChcIi5nYWxsZXJ5LWVsZW1lbnRzLWNvbG9yXCIpLmF0dHIoXCJkYXRhLWVsZW1lbnRzLWNvbG9yXCIpO1xuICAgICAgfVxuXG4gICAgICAvKiBTVEFSVDogU0VUIExJR0hUIExPR08gT04gU0xJREVTSE9XIElGIE5FRURFRCAqL1xuICAgICAgICBpZiAoYWN0aXZlX3NsaWRlX19lbGVtZW50c19jb2xvcikge1xuICAgICAgICAgIC8vIFN0YXJ0OiBDaGVja2luZyBDb2xvclxuICAgICAgICAgICAgdmFyIGMgPSBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yO1xuICAgICAgICAgICAgdmFyIGMgPSBjLnN1YnN0cmluZygxKTsgICAgICAvLyBzdHJpcCAjXG4gICAgICAgICAgICB2YXIgcmdiID0gcGFyc2VJbnQoYywgMTYpOyAgIC8vIGNvbnZlcnQgcnJnZ2JiIHRvIGRlY2ltYWxcbiAgICAgICAgICAgIHZhciByID0gKHJnYiA+PiAxNikgJiAweGZmOyAgLy8gZXh0cmFjdCByZWRcbiAgICAgICAgICAgIHZhciBnID0gKHJnYiA+PiAgOCkgJiAweGZmOyAgLy8gZXh0cmFjdCBncmVlblxuICAgICAgICAgICAgdmFyIGIgPSAocmdiID4+ICAwKSAmIDB4ZmY7ICAvLyBleHRyYWN0IGJsdWVcblxuICAgICAgICAgICAgdmFyIGx1bWEgPSAwLjIxMjYgKiByICsgMC43MTUyICogZyArIDAuMDcyMiAqIGI7IC8vIHBlciBJVFUtUiBCVC43MDlcbiAgICAgICAgICAvLyBFbmQ6IENoZWNraW5nIENvbG9yXG5cbiAgICAgICAgICB2YXIgYWN0aXZlX3NsaWRlX19lbGVtZW50c19jb2xvcl9pc19saWdodCA9IGx1bWEgPiA0MDtcblxuICAgICAgICAgIGlmIChhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yX2lzX2xpZ2h0KSB7XG4gICAgICAgICAgICAkKFwiYm9keVwiKS5hZGRDbGFzcyhcImJvZHktLWZsby1wYWdlLWhlcm8tZWxlbWVudHMtY29sb3ItaXMtbGlnaHRcIik7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICQoXCJib2R5XCIpLnJlbW92ZUNsYXNzKFwiYm9keS0tZmxvLXBhZ2UtaGVyby1lbGVtZW50cy1jb2xvci1pcy1saWdodFwiKTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgLyogRU5EOiBTRVQgTElHSFQgTE9HTyBPTiBTTElERVNIT1cgSUYgTkVFREVEICovXG5cbiAgICAgIC8qIFNUQVJUOiBCT0RZIC0+IFNFVCBIQVMgTE9HTyBDTEFTUyBJRiBORUVERUQgKi9cbiAgICAgICAgaWYgKGFjdGl2ZV9zbGlkZV9fJC5oYXNDbGFzcyhcImZsby1wYWdlLWhlcm9fX3NsaWRlLS1oYXMtbG9nb1wiKSkge1xuICAgICAgICAgICQoXCJib2R5XCIpLmFkZENsYXNzKFwiYm9keS0tZmxvLXBhZ2UtaGVyb19fc2xpZGUtLWhhcy1sb2dvXCIpXG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgJChcImJvZHlcIikucmVtb3ZlQ2xhc3MoXCJib2R5LS1mbG8tcGFnZS1oZXJvX19zbGlkZS0taGFzLWxvZ29cIilcbiAgICAgICAgfVxuICAgICAgLyogRU5EOiBCT0RZIC0+IFNFVCBIQVMgTE9HTyBDTEFTUyBJRiBORUVERUQgKi9cblxuICAgICAgLyogU1RBUlQ6IEJPRFkgLT4gU0VUIEhBUyBWSURFTyBFTUJFRCBDTEFTUyBJRiBORUVERUQgKi9cbiAgICAgICAgaWYgKGFjdGl2ZV9zbGlkZV9fJC5oYXNDbGFzcyhcImZsby1wYWdlLWhlcm9fX3NsaWRlLS1pbWFnZV9hbmRfdmlkZW9fZW1iZWRcIikpIHtcbiAgICAgICAgICAkKFwiYm9keVwiKS5hZGRDbGFzcyhcImJvZHktLWZsby1wYWdlLWhlcm9fX3NsaWRlLS1oYXMtdmlkZW8tZW1iZWRcIilcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAkKFwiYm9keVwiKS5yZW1vdmVDbGFzcyhcImJvZHktLWZsby1wYWdlLWhlcm9fX3NsaWRlLS1oYXMtdmlkZW8tZW1iZWRcIilcbiAgICAgICAgfVxuICAgICAgLyogRU5EOiBCT0RZIC0+IFNFVCBIQVMgVklERU8gRU1CRUQgQ0xBU1MgSUYgTkVFREVEICovXG5cbiAgICAgIC8qIFNUQVJUOiBDSEFOR0UgRUxFTUVOVFMgQ09MT1IgKi9cblxuICAgICAgICAvKiBTdGFydDogQ3JlYXRlIEVsZW1lbnRzIENTUyAqL1xuICAgICAgICAgICAgdmFyIGVsZW1lbnRzX2Nzc19fY3NzID0gW1xuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1zZWN0aW9uIC5mbG8taGVhZGVyLXN0aWNreS13cmFwcGVyOm5vdCguaXMtc3RpY2t5KSAuZmxvLWhlYWRlcl9fbG9nbywgXCIsXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyLXNlY3Rpb24gLmZsby1oZWFkZXItc3RpY2t5LXdyYXBwZXI6bm90KC5pcy1zdGlja3kpIC5mbG8taGVhZGVyLCBcIixcbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXItc2VjdGlvbiAuZmxvLWhlYWRlci1zdGlja3ktd3JhcHBlcjpub3QoLmlzLXN0aWNreSkgLmZsby1oZWFkZXJfX21lbnUgPiBkaXYgPiB1bCA+IGxpID4gYSwgXCIsXG5cbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXItc2VjdGlvbiAuZmxvLWhlYWRlcl9fbG9nbywgXCIsXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyLXNlY3Rpb24gLmZsby1oZWFkZXIsIFwiLFxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1zZWN0aW9uIC5mbG8taGVhZGVyX19tZW51ID4gZGl2ID4gdWwgPiBsaSA+IGEsIFwiLFxuXG4gICAgICAgICAgICBcIi5mbG8tcGFnZS1oZXJvX19hcnJvdywgXCIsXG4gICAgICAgICAgICBcIi5mbG8tcGFnZS1oZXJvX19zbGlkZS1wcmV0aXRsZSwgXCIsXG4gICAgICAgICAgICBcIi5mbG8tcGFnZS1oZXJvX19zbGlkZS10aXRsZSwgXCIsXG4gICAgICAgICAgICBcIi5mbG8tcGFnZS1oZXJvX19zbGlkZS1zdWJ0aXRsZSwgXCIsXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyLXN0aWNreS13cmFwcGVyOm5vdCguaXMtc3RpY2t5KSAuZmxvLWhlYWRlcl9fc2VhcmNoLXRyaWdnZXIsIFwiLFxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1zdGlja3ktd3JhcHBlcjpub3QoLmlzLXN0aWNreSkgLmZsby1oZWFkZXJfX3NvY2lhbC1saW5rcywgXCIsXG5cbiAgICAgICAgICAgIFwiLmZsby1wYWdlLWhlcm9fX3Njcm9sbC1kb3duLCBcIixcblxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1zZWN0aW9uID4gLmZsby1oZWFkZXItbW9iaWxlIC5mbG8taGVhZGVyLW1vYmlsZV9fbG9nbywgXCIsXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyLXNlY3Rpb24gPiAuZmxvLWhlYWRlci1tb2JpbGUtc3RpY2t5LXdyYXBwZXI6bm90KC5pcy1zdGlja3kpIC5mbG8taGVhZGVyLW1vYmlsZSAuZmxvLWhlYWRlci1tb2JpbGVfX2xvZ28gXCIsXG4gICAgICAgICAgICBcIntcIixcbiAgICAgICAgICAgICAgXCJjb2xvcjogXCIgKyBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yICsgXCI7XCIsXG4gICAgICAgICAgICBcIn1cIixcblxuICAgICAgICAgICAgXCJib2R5Om5vdCguYm9keS0tZmxvLXBhZ2UtaGVyby12aWRlby1pcy1wbGF5aW5nKSAuZmxvLXBhZ2UtaGVyb19fdmlkZW8tYnV0dG9uXCIsXG4gICAgICAgICAgICBcIntcIixcbiAgICAgICAgICAgICAgXCJjb2xvcjogXCIgKyBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yICsgXCI7XCIsXG4gICAgICAgICAgICAgIFwiYm9yZGVyLWNvbG9yOiBcIiArIGFjdGl2ZV9zbGlkZV9fZWxlbWVudHNfY29sb3IgKyBcIjtcIixcbiAgICAgICAgICAgIFwifVwiLFxuXG4gICAgICAgICAgICBcIi5mbG8taGVhZGVyLXN0aWNreS13cmFwcGVyOm5vdCguaXMtc3RpY2t5KSAuZmxvLWhlYWRlcl9faGFtYnVyZ2VyLCBcIixcbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXItbW9iaWxlX19tZW51LXRyaWdnZXJcIixcbiAgICAgICAgICAgIFwiLmZsby1oZWFkZXItbW9iaWxlLXN0aWNreS13cmFwcGVyOm5vdCguaXMtc3RpY2t5KSAgLmZsby1oZWFkZXItbW9iaWxlX19tZW51LXRyaWdnZXJcIixcbiAgICAgICAgICAgIFwie1wiLFxuICAgICAgICAgICAgICAvLyBcImJvcmRlci1jb2xvcjogXCIgKyBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yICsgXCI7XCIsXG4gICAgICAgICAgICAgIFwiYm94LXNoYWRvdzogMHB4IDBweCAxNXB4IC01cHggXCIgKyBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yICsgXCI7XCIsXG4gICAgICAgICAgICBcIn1cIixcblxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1zZWN0aW9uIC5mbG8taGVhZGVyLXN0aWNreS13cmFwcGVyOm5vdCguaXMtc3RpY2t5KSBpbnB1dC5mbG8taGVhZGVyX19zZWFyY2gtaW5wdXQsIFwiLFxuICAgICAgICAgICAgXCIuZmxvLWhlYWRlci1zZWN0aW9uIGlucHV0LmZsby1oZWFkZXJfX3NlYXJjaC1pbnB1dFwiLFxuICAgICAgICAgICAgXCJ7XCIsXG4gICAgICAgICAgICAgIFwiY29sb3I6IFwiICsgYWN0aXZlX3NsaWRlX19lbGVtZW50c19jb2xvciArIFwiO1wiLFxuICAgICAgICAgICAgICBcImJvcmRlci1jb2xvcjogXCIgKyBhY3RpdmVfc2xpZGVfX2VsZW1lbnRzX2NvbG9yICsgXCI7XCIsXG4gICAgICAgICAgICBcIn1cIlxuICAgICAgICAgIF0uam9pbihcIlxcclxcblwiKTtcbiAgICAgICAgLyogRW5kOiBDcmVhdGUgRWxlbWVudHMgQ1NTICovXG5cbiAgICAgICAgLyogU3RhcnQ6IEFwcGx5IEVsZW1lbnRzIENTUyAqL1xuICAgICAgICAgIHZhciBlbGVtZW50c19jc3NfX3dyYXBfY2xhc3MgPSBcImZsby1wYWdlLWhlcm9fX2VsZW1lbnRzLWNzc1wiO1xuICAgICAgICAgIHZhciBlbGVtZW50c19jc3NfX3dyYXBfJCA9ICQoXCIuXCIgKyBlbGVtZW50c19jc3NfX3dyYXBfY2xhc3MpO1xuICAgICAgICAgIGlmIChlbGVtZW50c19jc3NfX3dyYXBfJC5sZW5ndGgpIHtcbiAgICAgICAgICAgIGVsZW1lbnRzX2Nzc19fd3JhcF8kLmh0bWwoZWxlbWVudHNfY3NzX19jc3MpO1xuICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAkKFwiaGVhZFwiKS5hcHBlbmQoXG4gICAgICAgICAgICAgIFwiPHN0eWxlIGNsYXNzPSdcIiArIGVsZW1lbnRzX2Nzc19fd3JhcF9jbGFzcyArIFwiJz5cIiArIGVsZW1lbnRzX2Nzc19fY3NzICsgXCI8L3N0eWxlPlwiXG4gICAgICAgICAgICApO1xuICAgICAgICAgIH1cbiAgICAgICAgLyogRW5kOiBBcHBseSBFbGVtZW50cyBDU1MgKi9cblxuICAgICAgLyogRU5EOiBDSEFOR0UgRUxFTUVOVFMgQ09MT1IgKi9cblxuICAgICAgLyogU1RBUlQ6IFZJREVPIEVNQkVEICovXG4gICAgICAgIC8vIFN0YXJ0OiBQYXVzZSBhbGwgdmlkZW9zXG4gICAgICAgICAgJChcIi5mbG8tcGFnZS1oZXJvX19zbGlkZS0tdmlkZW9fc2xpZGU6bm90KC5zbGljay1jdXJyZW50KVwiKS5maW5kKFwidmlkZW9cIikuZWFjaChmdW5jdGlvbigpe1xuICAgICAgICAgICAgdGhpcy5wYXVzZSgpO1xuICAgICAgICAgIH0pO1xuICAgICAgICAvLyBFbmQ6IFBhdXNlIGFsbCB2aWRlb3NcblxuICAgICAgICBpZiAoYWN0aXZlX3NsaWRlX18kLmhhc0NsYXNzKFwiZmxvLXBhZ2UtaGVyb19fc2xpZGUtLXZpZGVvX3NsaWRlXCIpKSB7XG4gICAgICAgICAgdmFyIHZpZGVvX2NvbnRhaW5lciA9IGFjdGl2ZV9zbGlkZV9fJC5maW5kKFwiLmZsby1wYWdlLWhlcm9fX3NsaWRlLWJhY2tncm91bmQtdmlkZW9cIik7XG4gICAgICAgICAgdmFyIHZpZGVvID0gdmlkZW9fY29udGFpbmVyLmZpbmQoXCJ2aWRlb1wiKVswXTtcblxuICAgICAgICAgIHZpZGVvLnBsYXkoKTtcbiAgICAgICAgfVxuICAgICAgLyogRU5EOiBWSURFTyBFTUJFRCAqL1xuICAgIH0pXG4gICAgLm9uKFwiaW5pdFwiLCBmdW5jdGlvbigpe1xuICAgICAgJCh0aGlzKS5maW5kKCcuc2xpY2stbGlzdCcpLmF0dHIoJ3RhYmluZGV4JywgMCkuZm9jdXMoKTtcbiAgICB9KVxuICAgIC5zbGljayh7XG4gICAgICBwcmV2QXJyb3c6ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cImZsby1wYWdlLWhlcm9fX2Fycm93IGZsby1wYWdlLWhlcm9fX2Fycm93LS1wcmV2IHNsaWNrLXByZXZcIj48aSBjbGFzcz1cImZsby1pY29uLWFycm93LWxlZnRcIj48L2k+PC9idXR0b24+JyxcbiAgICAgIG5leHRBcnJvdzogJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiZmxvLXBhZ2UtaGVyb19fYXJyb3cgZmxvLXBhZ2UtaGVyb19fYXJyb3ctLW5leHQgc2xpY2stbmV4dFwiPjxpIGNsYXNzPVwiZmxvLWljb24tYXJyb3ctcmlnaHRcIj48L2k+PC9idXR0b24+JyxcbiAgICAgIGFycm93czogdHJ1ZSxcbiAgICAgIHBhdXNlT25Gb2N1czogZmFsc2UsXG4gICAgICBkb3RzOiBmYWxzZSxcbiAgICAgIGNzc0Vhc2U6IFwiY3ViaWMtYmV6aWVyKDAuNDQ1LCAwLjA1MCwgMC41NTAsIDAuOTUwKVwiLFxuICAgIH0pO1xuICAvKiBFTkQ6IFNMSURFU0hPVyBJTklUSUFMSVpBVElPTiAqL1xuXG4gIC8qIFNUQVJUOiBTQ1JPTEwgRE9XTiBCVVRUT04gKi9cbiAgICAkKFwiLmZsby1wYWdlLWhlcm9fX3Njcm9sbC1kb3duXCIpLm9uKFwiY2xpY2tcIiwgZnVuY3Rpb24oKXtcbiAgICAgICQoJ2h0bWwsIGJvZHknKS5hbmltYXRlKHtcbiAgICAgICAgc2Nyb2xsVG9wOiAkKHdpbmRvdykuaGVpZ2h0KClcbiAgICB9LCA0MDApO1xuICAgIH0pO1xuICAvKiBFTkQ6IFNDUk9MTCBET1dOIEJVVFRPTiAqL1xuXG5cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuXG4gICQoXCIuZmxvLXBvcnRmb2xpby1ncmlkIC5yb3cubWFzb25yeSwgLmZsby1zZWN0aW9uLS1qb3VybmFsLWdyaWQtc2VjdGlvbiAucm93Lm1hc29ucnlcIikuZWFjaChmdW5jdGlvbigpe1xuICAgICRncmlkID0gJCh0aGlzKTtcblxuICAgIGZ1bmN0aW9uIGRvX21hc29ucnkoJGdyaWQpIHtcbiAgICAgIGlmICh3aW5kb3cuaW5uZXJXaWR0aCA+PSA3NjgpICRncmlkLm1hc29ucnkoe1xuICAgICAgICBpdGVtU2VsZWN0b3IgOiBcIi5jb2x1bW5cIixcbiAgICAgICAgY29sdW1uV2lkdGggOiAwXG4gICAgICB9KTtcbiAgICB9XG5cbiAgICBkb19tYXNvbnJ5KCRncmlkKTtcblxuICAgICAkKHRoaXMpLmZpbmQoXCJpbWdcIikub24oXCJsb2FkXCIsIGZ1bmN0aW9uKCl7XG4gICAgICBkb19tYXNvbnJ5KCRncmlkKTtcbiAgICB9KTtcblxuICB9KTtcblxufSk7XG4iLCIkKGZ1bmN0aW9uKCl7XG4gIC8vIFN0YXJ0OiBGdWxsIFBhZ2UgU2Nyb2xsXG4gICAgaWYgKCQoXCIubGF5b3V0LXNlY3Rpb25zLS1zY3JvbGwtc2VjdGlvblwiKS5sZW5ndGgpIHtcblxuICAgICAgaWYgKCQoIHdpbmRvdyApLndpZHRoKCkgPiA3NjgpIHtcblxuICAgICAgICAkLnNjcm9sbGlmeSh7XG4gICAgICAgICAgc2VjdGlvbjpcIi5sYXlvdXQtc2VjdGlvbnMgLmZ1bGwtc2Nyb2xsYWJsZS1zZWN0aW9uXCIsXG4gICAgICAgICAgZWFzaW5nOiBcInN3aW5nXCIsXG4gICAgICAgICAgc2Nyb2xsU3BlZWQ6IDYwMCxcbiAgICAgICAgICBzZWN0aW9uTmFtZTogZmFsc2UsXG4gICAgICAgICAgaW50ZXJzdGl0aWFsU2VjdGlvbjpcIi5sYXlvdXQtc2VjdGlvbnMgLmZ1bGwtc2Nyb2xsYWJsZS1mb290ZXIsIC5sYXlvdXQtc2VjdGlvbnMgaGVhZGVyXCJcbiAgICAgICAgfSk7XG5cbiAgICAgICAgJChcIi5mbG8tc2VjdGlvbl9fc2Nyb2xsLWRvd25cIikuY2xpY2soZnVuY3Rpb24oZSkge1xuICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAkLnNjcm9sbGlmeS5uZXh0KCk7XG4gICAgICAgIH0pO1xuXG4gICAgICB9XG5cbiAgICB9XG4gIC8vIEVuZDogRnVsbCBQYWdlIFNjcm9sbFxufSk7XG4iLCIkKGZ1bmN0aW9uKCl7XG5cbiAgdmFyIHNwbGFzaF9fJCA9ICQoXCIuZmxvLXNwbGFzaFwiKTtcbiAgdmFyIHNwbGFzaF9fYW5pbWF0aW9uX21hcCA9IHtcbiAgICBcImZhZGVcIiA6IFtcbiAgICAgIFwiZmFkZS1pblwiLFxuICAgICAgXCJmYWRlLW91dFwiXG4gICAgXSxcblxuICAgIFwiaGluZ2UtdG9wXCI6IFtcbiAgICAgIFwiaGluZ2UtaW4tZnJvbS10b3BcIixcbiAgICAgIFwiaGluZ2Utb3V0LWZyb20tdG9wXCJcbiAgICBdLFxuICAgIFwiaGluZ2UtdmVydGljYWxcIjogW1xuICAgICAgXCJoaW5nZS1pbi1mcm9tLXRvcFwiLFxuICAgICAgXCJoaW5nZS1vdXQtZnJvbS1ib3R0b21cIlxuICAgIF0sXG4gICAgXCJoaW5nZS1sZWZ0XCI6IFtcbiAgICAgIFwiaGluZ2UtaW4tZnJvbS1sZWZ0XCIsXG4gICAgICBcImhpbmdlLW91dC1mcm9tLWxlZnRcIlxuICAgIF0sXG4gICAgXCJoaW5nZS1ob3Jpem9udGFsXCI6IFtcbiAgICAgIFwiaGluZ2UtaW4tZnJvbS1sZWZ0XCIsXG4gICAgICBcImhpbmdlLW91dC1mcm9tLXJpZ2h0XCJcbiAgICBdLFxuXG4gICAgXCJzbGlkZS1sZWZ0XCI6IFtcbiAgICAgIFwic2xpZGUtaW4tbGVmdFwiLFxuICAgICAgXCJzbGlkZS1vdXQtbGVmdFwiXG4gICAgXSxcbiAgICBcInNsaWRlLWhvcml6b250YWxcIjogW1xuICAgICAgXCJzbGlkZS1pbi1sZWZ0XCIsXG4gICAgICBcInNsaWRlLW91dC1yaWdodFwiXG4gICAgXSxcbiAgICBcInNsaWRlLXVwXCI6IFtcbiAgICAgIFwic2xpZGUtaW4tZG93blwiLFxuICAgICAgXCJzbGlkZS1vdXQtdXBcIlxuICAgIF0sXG4gICAgXCJzbGlkZS12ZXJ0aWNhbFwiOiBbXG4gICAgICBcInNsaWRlLWluLWRvd25cIixcbiAgICAgIFwic2xpZGUtb3V0LWRvd25cIlxuICAgIF0sXG5cbiAgICBcInNwaW5cIjogW1xuICAgICAgXCJzcGluLWluXCIsXG4gICAgICBcInNwaW4tb3V0LWNjd1wiXG4gICAgXVxuXG5cbiAgfVxuXG4gIHZhciBzcGxhc2hfX2FuaW1hdGlvbl9uYW1lID0gc3BsYXNoX18kLmF0dHIoXCJkYXRhLWFuaW1hdGlvblwiKTtcblxuICAvLyB2YXIgc3BsYXNoX19hbmltYXRpb25fbmFtZSA9IFwiZmFkZVwiO1xuXG4gIC8vIHZhciBzcGxhc2hfX2FuaW1hdGlvbl9uYW1lID0gXCJoaW5nZS10b3BcIjtcbiAgLy8gdmFyIHNwbGFzaF9fYW5pbWF0aW9uX25hbWUgPSBcImhpbmdlLXZlcnRpY2FsXCI7XG4gIC8vIHZhciBzcGxhc2hfX2FuaW1hdGlvbl9uYW1lID0gXCJoaW5nZS1sZWZ0XCI7XG4gIC8vIHZhciBzcGxhc2hfX2FuaW1hdGlvbl9uYW1lID0gXCJoaW5nZS1ob3Jpem9udGFsXCI7XG5cbiAgLy8gdmFyIHNwbGFzaF9fYW5pbWF0aW9uX25hbWUgPSBcInNsaWRlLWxlZnRcIjtcbiAgLy8gdmFyIHNwbGFzaF9fYW5pbWF0aW9uX25hbWUgPSBcInNsaWRlLWhvcml6b250YWxcIjtcbiAgLy8gdmFyIHNwbGFzaF9fYW5pbWF0aW9uX25hbWUgPSBcInNsaWRlLXVwXCI7XG4gIC8vIHZhciBzcGxhc2hfX2FuaW1hdGlvbl9uYW1lID0gXCJzbGlkZS12ZXJ0aWNhbFwiO1xuXG4gIC8vIHZhciBzcGxhc2hfX2FuaW1hdGlvbl9uYW1lID0gXCJzcGluXCI7XG5cbiAgdmFyIHNwbGFzaF9fc2hvdyA9IGZ1bmN0aW9uKCkge1xuICAgIEZvdW5kYXRpb24uTW90aW9uLmFuaW1hdGVJbihzcGxhc2hfXyQsIHNwbGFzaF9fYW5pbWF0aW9uX21hcFtzcGxhc2hfX2FuaW1hdGlvbl9uYW1lXVswXSk7XG4gIH1cbiAgdmFyIHNwbGFzaF9faGlkZSA9IGZ1bmN0aW9uKCkge1xuICAgIEZvdW5kYXRpb24uTW90aW9uLmFuaW1hdGVPdXQoc3BsYXNoX18kLCBzcGxhc2hfX2FuaW1hdGlvbl9tYXBbc3BsYXNoX19hbmltYXRpb25fbmFtZV1bMV0pO1xuICB9XG5cbiAgLy8gU3RhcnQ6IGFuaW1hdGUgb24gcGFnZSBsb2FkIGFuZCB1bmxvYWRcbiAgICB2YXIgZG9jdW1lbnRfX2lzX3JlYWR5ID0gZmFsc2U7XG4gICAgJChkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgICBzcGxhc2hfX2hpZGUoKTtcbiAgICAgIGRvY3VtZW50X19pc19yZWFkeSA9IHRydWU7XG4gICAgfSk7XG4gICAgd2luZG93Lm9ucGFnZXNob3cgPSBmdW5jdGlvbigpIHtcbiAgICAgIGlmIChkb2N1bWVudF9faXNfcmVhZHkpIHNwbGFzaF9faGlkZSgpO1xuICAgIH07XG4gIC8vIEVuZDogQm9keSBGYWRlSW5cblxuICAvLyBTdGFydDogQm9keSBGYWRlT3V0XG4gICAgd2luZG93Lm9uYmVmb3JldW5sb2FkID0gZnVuY3Rpb24gKCkge1xuICAgICAgc3BsYXNoX19zaG93KCk7XG4gICAgfTtcbiAgLy9FbmQ6IExvYWQvVW5sb2FkIEFuaW1hdGlvblxuXG59KTtcbiIsIiQoZnVuY3Rpb24oKXtcblxuICAkKFwiLmZsby10ZXN0aW1vbmlhbHMtc2xpZGVzaG93X190ZXN0aW1vbmlhbHNcIikuc2xpY2soe1xuICAgIGFkYXB0aXZlSGVpZ2h0OiB0cnVlLFxuICAgIGRvdHM6IGZhbHNlLFxuICAgIGFycm93czogdHJ1ZSxcbiAgICBmYWRlOiB0cnVlLFxuICAgIG5leHRBcnJvdzogJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwic2xpY2stbmV4dFwiPjxpIGNsYXNzPVwiZmxvLWljb24tYXJyb3ctcmlnaHRcIj48L2k+PC9idXR0b24+JyxcbiAgICBwcmV2QXJyb3c6ICc8YnV0dG9uIHR5cGU9XCJidXR0b25cIiBjbGFzcz1cInNsaWNrLXByZXZcIj48aSBjbGFzcz1cImZsby1pY29uLWFycm93LWxlZnRcIj48L2k+PC9idXR0b24+J1xuICB9KTtcblxufSk7XG4iLCIkKGZ1bmN0aW9uKCl7XG5cbiAgdmFyICRmb3JtID0gJChcIi5mbG8tZm9vdGVyX19uZXdzbGV0dGVyLWZvcm1cIik7XG4gIGlmICgkZm9ybS5sZW5ndGgpIHtcbiAgICAvLyBTdGFydDogVmFsaWRhdGlvblxuICAgICAgJGZvcm0ucGFyc2xleSgpO1xuICAgIC8vIEVuZDogVmFsaWRhdGlvblxuXG4gICAgLy8gU3RhcnQ6IE1haWxjaGltcCBTdWJzY3JpcHRpb25cbiAgICAgIHZhclxuICAgICAgZW1iZWRfY29kZSA9XG4gICAgICAgIHVuZXNjYXBlKFxuICAgICAgICAgICRmb3JtLnBhcmVudCgpLmZpbmQoXCIuZW1iZWRfY29kZVwiKS50ZXh0KClcbiAgICAgICAgKSxcbiAgICAgICRlbWJlZF9jb2RlID0gJChcIjxkaXY+XCIpLmh0bWwoZW1iZWRfY29kZSksXG4gICAgICBlbWJlZF9mb3JtX2FjdGlvblxuICAgICAgO1xuXG4gICAgICBpZih0eXBlb2YgJGVtYmVkX2NvZGUuZmluZChcImZvcm1cIikuYXR0cihcImFjdGlvblwiKSAhPSAndW5kZWZpbmVkJyl7XG4gICAgICAgIGVtYmVkX2Zvcm1fYWN0aW9uID0gJGVtYmVkX2NvZGUuZmluZChcImZvcm1cIikuYXR0cihcImFjdGlvblwiKS5yZXBsYWNlKC9cXFxcXCIvZywgJycpXG4gICAgICAgICRmb3JtLmF0dHIoXCJhY3Rpb25cIiwgZW1iZWRfZm9ybV9hY3Rpb24pO1xuICAgICAgfVxuICAgICAgXG4gICAgLy8gRW5kOiBNYWlsY2hpbXAgU3Vic2NyaXB0aW9uYFxuICB9XG59KTtcbiIsIiQoZnVuY3Rpb24oKXtcblxuICAvKiBTVEFSVDogU0VUIFNMSURFUiBQT1NJVElPTiBBQ0NPUkRJTkcgVE8gQ0xJQ0tFRCBUSFVNQk5BSUwgKi9cbiAgICAkKFwiLmZsby1wb3J0Zm9saW8tZ3JpZF9fdGh1bWJuYWlsXCIpLmNsaWNrKGZ1bmN0aW9uKCl7XG4gICAgICB2YXIgaW5kZXggPSAkKHRoaXMpLmRhdGEoXCJpbWctaW5kZXhcIik7XG4gICAgICAkKFwiLmZsby1oZXJvLTNfX3NsaWRlclwiKS5zbGljayhcInNsaWNrR29Ub1wiLCAkKHRoaXMpLmRhdGEoXCJpbWctaW5kZXhcIikpO1xuICAgIH0pO1xuICAvKiBFTkQ6IFNFVCBTTElERVIgUE9TSVRJT04gQUNDT1JESU5HIFRPIENMSUNLRUQgVEhVTUJOQUlMICovXG5cbiAgLyogU1RBUlQ6IFRSSUdHRVIgUkVTSVpFIFNPIFRIQVQgVEhFIFNMSURFUiBTRVRTIElUIFBPU0lUSU9OIFBST1BFUkxZIElOU0lERSBUSEUgUkVWRUFMICovXG4gICAgJChcIi5mbG8tcG9wdXAtZ2FsbGVyeVwiKS5vbignb3Blbi56Zi5yZXZlYWwnLCBmdW5jdGlvbiAoKSB7XG4gICAgICB2YXIgaSA9IDA7XG4gICAgICB2YXIgaV9fbGltaXQgPSA1O1xuICAgICAgdmFyIHRyaWdnZXJfaW50ZXJ2YWwgPSBzZXRJbnRlcnZhbChmdW5jdGlvbigpe1xuICAgICAgICAvLyBpZiAoaT4xKSAkKHdpbmRvdykudHJpZ2dlcihcInJlc2l6ZVwiKTtcbiAgICAgICAgaWYgKGkgPCAzKSAkKFwiLmZsby1oZXJvLTNfX3NsaWRlclwiKVswXS5zbGljay5yZWZyZXNoKCk7XG4gICAgICAgIGlmIChpID09IGlfX2xpbWl0KSBjbGVhckludGVydmFsKHRyaWdnZXJfaW50ZXJ2YWwpO1xuICAgICAgICBpKys7XG4gICAgICB9LCAxMDApO1xuICAgIH0pO1xuICAvKiBFTkQ6IFRSSUdHRVIgUkVTSVpFIFNPIFRIQVQgVEhFIFNMSURFUiBTRVRTIElUIFBPU0lUSU9OIFBST1BFUkxZIElOU0lERSBUSEUgUkVWRUFMICovXG5cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuXG5cbiAgICAvLyBTVEFSVDogRElTUExBWSBNT0JJTEUgTUVOVVxuXHQgICAgJCgnLmZsby1pY29uLXNpZGViYXInKS5jbGljayhmdW5jdGlvbiAoKSB7XG5cdCAgICAgICQoJy5mbG9fcGFnZV93cmFwJykuZmluZCgnLmZsb19zaWRlYmFyJykudG9nZ2xlQ2xhc3MoJ2Zsb19zaWRlYmFyLS1kaXNwbGF5Jyk7XG5cdCAgICAgICQoXCJib2R5XCIpLnRvZ2dsZUNsYXNzKFwiZmxvX3NpZGViYXItLWFjdGl2ZVwiKTtcblx0ICAgIH0pO1xuICBcdC8vIEVORDogRElTUExBWSBNT0JJTEUgTUVOVVxuXG4gIFx0Ly8gU1RBUlQ6IFNFVCBQQURESU5HIFRPUCBGT1IgU0lERUJBUlxuICAgIFx0JChcIi5mbG9fc2lkZWJhclwiKS5jc3MoXCJ0b3BcIiwgJChcIi5mbG8taGVhZGVyLW1vYmlsZVwiKS5vdXRlckhlaWdodCh0cnVlKSApO1xuICBcdC8vIEVORDogU0VUIFBBRERJTkcgVE9QIEZPUiBTSURFQkFSXG5cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuICAgIGlmKCAkKFwiLmZsby1mZWF0dXJlZC1wb3N0c19faW1nIGltZ1wiKS5sZW5ndGggKXtcblx0XHRzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XG5cdFx0XHRCYWNrZ3JvdW5kQ2hlY2suaW5pdCh7XG5cdFx0XHRcdHdpbmRvd0V2ZW50czogdHJ1ZSxcblx0XHRcdFx0dGFyZ2V0czogJy5mbG8tZmVhdHVyZWQtcG9zdHNfX2ZlYXR1cmVkLXBvc3Q6bm90KC5mbG8tZmVhdHVyZWQtcG9zdHMtLWxheW91dC1ncmlkLWhhcy1leGNlcnB0KSAuZmxvLWZlYXR1cmVkLXBvc3RzX190ZXh0Jyxcblx0XHRcdFx0aW1hZ2VzOiBcIi5mbG8tZmVhdHVyZWQtcG9zdHNfX2ltZyBpbWdcIlxuXHRcdFx0fSk7XG5cdFx0fSk7XG4gIFx0fVxufSk7XG4iLCIkKGZ1bmN0aW9uKCl7XG5cbiAgJChcIi5mbG8tcG9ydGZvbGlvLXBvc3RfX3RodW1ibmFpbHNcIikuc2xpY2soe1xuICAgIHNsaWRlc1RvU2hvdzogMyxcbiAgICBhcnJvd3M6IGZhbHNlLFxuICAgIGRvdHM6IGZhbHNlLFxuICAgIGZvY3VzT25TZWxlY3Q6IHRydWUsXG4gICAgYXNOYXZGb3I6IFwiLmZsby1oZXJvLTNfX3NsaWRlclwiXG4gIH0pO1xuXG59KTtcbiIsIiQoZnVuY3Rpb24oKXtcbiAgLyogU3RhcnQ6IFNjcm9sbCBEb3duIEJ1dHRvbiAqL1xuICAgICAgJChcIi5mbG8taGVhZGVyLXNlY3Rpb25cIikub24oJ2NsaWNrJywgJy5mbG8tc2VjdGlvbl9fc2Nyb2xsLWRvd24nLCBmdW5jdGlvbihlKSB7XG4gICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICQoJ2h0bWwsYm9keScpLmFuaW1hdGUoe3Njcm9sbFRvcDogJCgnbWFpbicpLm9mZnNldCgpLnRvcH0pO1xuICAgICAgfSk7XG4gIC8qIEVuZDogU2Nyb2xsIERvd24gQnV0dG9uICovXG59KTtcbiIsIiQoZnVuY3Rpb24oKXtcbiAgICAkKFwiLmZsby1pbnN0YWdyYW0taW1hZ2VzX19zbGlkZXJcIikuc2xpY2soe1xuICAgICAgICBhcnJvd3M6IHRydWUsXG4gICAgICAgIGRvdHM6IGZhbHNlLFxuICAgICAgICBzbGlkZXNUb1Nob3c6IDYsXG4gICAgICAgIHNsaWRlc1RvU2Nyb2xsOiA2LFxuICAgICAgICBjc3NFYXNlOiBcImN1YmljLWJlemllcigwLjQ0NSwgMC4wNTAsIDAuNTUwLCAwLjk1MClcIixcbiAgICAgICAgbmV4dEFycm93OiAnPHNwYW4gY2xhc3M9XCJmbG8tc2xpZGVyLWFycm93IGZsby1pY29uLWZsby1hcnJvdy1yaWdodFwiPjwvc3Bhbj4nLFxuICAgICAgICBwcmV2QXJyb3c6ICc8c3BhbiBjbGFzcz1cImZsby1zbGlkZXItYXJyb3cgZmxvLWljb24tZmxvLWFycm93LWxlZnRcIj48L3NwYW4+JyxcbiAgICAgICAgcmVzcG9uc2l2ZTogW1xuICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIGJyZWFrcG9pbnQ6IDc2OCxcbiAgICAgICAgICAgICAgICBzZXR0aW5nczoge1xuICAgICAgICAgICAgICAgICAgICBzbGlkZXNUb1Nob3c6IDMsXG4gICAgICAgICAgICAgICAgICAgIHNsaWRlc1RvU2Nyb2xsOiAzXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICBicmVha3BvaW50OiA0ODAsXG4gICAgICAgICAgICAgICAgc2V0dGluZ3M6IHtcbiAgICAgICAgICAgICAgICAgICAgYXJyb3dzOiBmYWxzZSxcbiAgICAgICAgICAgICAgICAgICAgY2VudGVyTW9kZTogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgc2xpZGVzVG9TaG93OiAxLFxuICAgICAgICAgICAgICAgICAgICBzbGlkZXNUb1Njcm9sbDogMVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgXVxuICAgIH0pO1xufSk7XG4iLCJ3aW5kb3cuZmxvX2xpc3RpbmdfZ3JpZF8zID0gZnVuY3Rpb24oZWwpe1xuICBcInVzZSBzdHJpY3RcIjtcblxuICB2YXIgJGVsID0gJChlbCk7XG4gIHZhciBiID0gXCJmbG8tbGlzdGluZy1ncmlkLTNcIjtcbiAgdmFyIGRvdGIgPSBcIi5cIiArIGI7XG4gIHZhciBwYXJlbnQgPSAkZWwucGFyZW50cyhcIi5mbG8tYmxvY2tcIik7XG4gIHZhciAkYiA9ICRlbC5maW5kKGRvdGIpO1xuXG4gIGZ1bmN0aW9uIGRvX3NpemluZygpIHtcbiAgICB2YXIgaXRlbXMgPSAkZWwuZmluZChkb3RiICsgXCJfX2l0ZW1cIik7XG4gICAgaXRlbXMuZWFjaChmdW5jdGlvbigpe1xuICAgICAgdmFyIHdpZHRoID0gJCh0aGlzKS53aWR0aCgpO1xuICAgICAgJCh0aGlzKS5jc3MoXCJoZWlnaHRcIiwgd2lkdGgpO1xuICAgIH0pO1xuICB9XG5cbiAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgZG9fc2l6aW5nKCk7XG4gIH0sIDEwKTtcblxuICAkKHdpbmRvdykub24oXCJyZXNpemVcIiwgZG9fc2l6aW5nKTtcbn1cbiIsIiQoZnVuY3Rpb24oKXtcblxuICAgIC8vIFN0YXJ0OiBEaXNwbGF5IE1vYmlsZSBNZW51XG4gICAgJCgnLmZsby1pY29uLXNpZGViYXInKS5jbGljayhmdW5jdGlvbiAoKSB7XG4gICAgICAgICQoJ2JvZHksIGh0bWwnKS5maW5kKCcuZmxvLXBhZ2UtaGFzLXNpZGViYXJfX3NpZGViYXInKS50b2dnbGVDbGFzcygnc2lkZWJhci1kaXNwbGF5Jyk7XG4gICAgfSk7XG5cbiAgICAvLyBFbmQ6IERpc3BsYXkgTW9iaWxlIE1lbnVcblxufSk7XG4iLCJ3aW5kb3cuZmxvX2xpc3RpbmdfZ3JpZF80ID0gZnVuY3Rpb24oZWwpe1xuICBcInVzZSBzdHJpY3RcIjtcblxuICB2YXIgJGVsID0gJChlbCk7XG4gIHZhciBiID0gXCJmbG8tbGlzdGluZy1ncmlkLTRcIjtcbiAgdmFyIGRvdGIgPSBcIi5cIiArIGI7XG4gIHZhciBwYXJlbnQgPSAkZWwucGFyZW50cyhcIi5mbG8tYmxvY2tcIik7XG4gIHZhciAkYiA9ICRlbC5maW5kKGRvdGIpO1xuXG4gIGZ1bmN0aW9uIGRvX3NpemluZygpIHtcbiAgICB2YXIgaXRlbXMgPSAkZWwuZmluZChkb3RiICsgXCJfX2ltYWdlXCIpO1xuICAgIGl0ZW1zLmVhY2goZnVuY3Rpb24oKXtcbiAgICAgIHZhciB3aWR0aCA9ICQodGhpcykud2lkdGgoKTtcbiAgICAgICQodGhpcykuY3NzKFwiaGVpZ2h0XCIsIHdpZHRoKTtcbiAgICB9KTtcbiAgfVxuXG4gIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgIGRvX3NpemluZygpO1xuICB9LCAxMCk7XG5cbiAgJCh3aW5kb3cpLm9uKFwicmVzaXplXCIsIGRvX3NpemluZyk7XG59XG4iLCIkKGZ1bmN0aW9uKCl7XG4gICQoXCIuZmxvLXNwZWNpYWwtYmxvY2tfX3JlbGF0ZWQtcG9zdHNcIikuY3NzKHtcbiAgICBcIm1heC1oZWlnaHRcIjogJChcIi5mbG8tcG9zdC0tY29udGVudFwiKS5vdXRlckhlaWdodCh0cnVlKSArICQoXCIuZmxvLXNwZWNpYWwtYmxvY2tcIikub3V0ZXJIZWlnaHQodHJ1ZSlcblxuICB9KVxufSk7XG4iLCIkKGZ1bmN0aW9uKCl7XG5cblx0aWYgKCQod2luZG93KS53aWR0aCgpID4gMTAyNCkgJCgnLnByZXNzLXBhZ2VfX2xpbmsnKS5ob3ZlcihmdW5jdGlvbigpIHtcblx0XHR2YXIgYmxvY2tfY2xhc3MgPSAkKHRoaXMpLmRhdGEoJ2Jsb2NrX2NsYXNzJyk7XG5cdFx0JCgnLmZpZ3VyZS0nK2Jsb2NrX2NsYXNzICsgJyBpbWcnKS5hdHRyKCdzcmMnLCAkKHRoaXMpLmRhdGEoJ2ltZycpKTtcblx0XHQkKCcuZmlndXJlLScrYmxvY2tfY2xhc3MgKyAnIC5wcmVzcy1wYWdlX19mZWF0dXJlZC1pbWFnZS1kZXNjcmlwdGlvbicpLmh0bWwoJCh0aGlzKS5kYXRhKCdkZXNjcmlwdGlvbicpKTtcblx0fSk7XG5cbn0pO1xuIiwiJChmdW5jdGlvbigpe1xuICAkKFwiLmZsby1jb3JlLXN0eWxlXCIpLmVhY2goZnVuY3Rpb24oKXtcbiAgICB2YXIgdGVtcGxhdGUgPSAkKHRoaXMpO1xuICAgIHZhciBzdHlsZSA9IHRlbXBsYXRlLmh0bWwoKTtcbiAgICAkKFwiaGVhZFwiKS5hcHBlbmQoc3R5bGUpO1xuICAgIHRlbXBsYXRlLnJlbW92ZSgpO1xuICB9KTtcbiAgJCggXCI8c3R5bGU+Ym9keSAqe291dGxpbmU6IHNvbGlkIHRyYW5zcGFyZW50O31ib2R5IHsgb3BhY2l0eTogMTsgfTwvc3R5bGU+XCIgKS5hcHBlbmRUbyggXCJoZWFkXCIgKTtcbn0pO1xuIl19
