function pad (str, max) {
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
