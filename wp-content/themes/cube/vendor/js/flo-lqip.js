(function($){

jQuery.fn.extend(
  {

    flo_lqip : function() {
      "use strict";
      var $target = $(this);

      /* START: LQIP CLASSES */
        var flo_lqip__classes = {
          is_original : "flo-lqip--original-loaded",
          is_loading  : "flo-lqip--is-loading"
        }
      /* END: LQIP CLASSES */

      /* START: LQIP DATA ATTRIBUTES NAMES */
        var flo_lqip__data_names = {
          src_original  : "data-original-src",
          src_lqip      : "data-lqip-src",
          is_bg         : "data-lqip-is-bg",
          is_manual     : "data-lqip--is-manual"
        }
      /* END: LQIP DATA ATTRIBUTES NAMES */

      /* START: LQIP EVENT NAMES */
        var flo_lqip__event_names = {
          original_set  : "flo_lqip__original_set",
          lqip_set      : "flo_lqip__lqip_set",
          preloaded     : "flo_lqip__preloaded"
        }
      /* END: LQIP EVENT NAMES */

      /* START: LQIP FUNCTIONS */
        var flo_lqip__functions = {
          isOriginal : function($target){
            return $target.hasClass(flo_lqip__classes.is_original);
          },

          isManual : function($target) {
            return $target.attr(flo_lqip__data_names.is_manual) == "true";
          },

          setIsLoading : function($target) {
            $target.addClass( flo_lqip__classes.is_loading );
          },

          setLoaded : function($target) {
            setTimeout(function(){
              $target.addClass( flo_lqip__classes.is_original );
              $target.removeClass( flo_lqip__classes.is_loading );
            }, 200);
          },

          status : {

          },

          img : {
            setOriginal : function($target) {
              flo_lqip__functions.setIsLoading($target);

              var $img = $("<img/>").attr("src", $target.attr( flo_lqip__data_names.src_original ))
              var set_cached_img = function () {
                $target.attr("src", $target.attr(flo_lqip__data_names.src_original))
                flo_lqip__functions.setLoaded($target);
                $target.trigger(flo_lqip__event_names.original_set);
              }

              if ($img.prop("complete")) {
                set_cached_img();
              } else {
                $img.on("load", function(){
                  $img.remove();
                  set_cached_img();
                });
              }
            },
            setLQIP : function($target) {
              $target
                .removeClass(flo_lqip__classes.is_original)
                .attr("src", $target.attr(flo_lqip__data_names.src_lqip))
              ;
            }
          },

          bg : {
            setOriginal : function($target) {
              flo_lqip__functions.setIsLoading($target);

              var $img = $("<img/>").attr("src", $target.attr(flo_lqip__data_names.src_original))
              var set_cached_bgi = function () {
                $target.css("background-image", "url(" + $target.attr(flo_lqip__data_names.src_original) + ")");
                flo_lqip__functions.setLoaded($target);
                $target.trigger(flo_lqip__event_names.original_set);
              }
              if ($img.prop("complete")) {
                set_cached_bgi();
              } else {
                $img.on("load", function(){
                  set_cached_bgi();
                });
              }
            },
            setLQIP : function($target) {
              $target.css("background-image", "url(" + $target.attr(flo_lqip__data_names.src_lqip) + ")");
              $target.removeClass(flo_lqip__classes.is_original)
            }
          },

          setOriginal : function($target) {
            setTimeout(function(){
              $target.each(function(){

                $target = $(this);
                if (!flo_lqip__functions.isOriginal($target)) {

                  if ( !$target.attr(flo_lqip__data_names.is_bg) ) {
                    flo_lqip__functions.img.setOriginal($target);
                  } else {
                    flo_lqip__functions.bg.setOriginal($target);
                  }

                }
              });
            }, 100);
          },

          setLQIP : function($target) {
            if (flo_lqip__functions.isOriginal() ) {

              if ( !$target.attr(flo_lqip__data_names.is_bg) ) {
                flo_lqip__functions.img.setLQIP($target);
              } else {
                flo_lqip__functions.bg.setLQIP($target);
              }

            }
          }

        }
      /* END: LQIP FUNCTIONS */

      /* START: FUNCTIONS BASED ON ATTRIBUTES */
        var flo_lqip__arguments = arguments;
        if ($target.length) switch (flo_lqip__arguments[0]) {

          /* START: INTIALIZATION FUNCTION */
            case undefined:
              $target.imagesLoaded(function(){

                var $lqip_imgs = $("*["+ flo_lqip__data_names.src_lqip +"]");
                function do_lqip() {
                  $lqip_imgs.each(function(){
                    if (
                      $(this).is(":in-viewport")
                      && !flo_lqip__functions.isManual($(this))
                    ) {
                      flo_lqip__functions.setOriginal($(this));
                    }
                  });
                }
                do_lqip();

                $(document)
                  .on("flo-lqip__refresh", function(){
                    do_lqip();
                  })
                  .on("scroll", function() {
                    clearTimeout($.data(this, 'scrollTimer'));
                    $.data(this, 'scrollTimer', setTimeout(function() {
                      do_lqip();
                    }, 250));
                  })
                ;

              });
            break;
          /* END: INTIALIZATION FUNCTION */

          /* START: SET ORIGINAL */
            case "setOriginal":
              flo_lqip__functions.setOriginal($target);
            break;
          /* END: SET ORIGINAL */

          /* START: SET LQIP */
            case "setLQIP":
              flo_lqip__functions.setLQIP($target);
            break;
          /* END: SET LQIP */

          /* START: SET SLICK PRELOAD */
            case "setSlickPreload":
              $target.on("afterChange init", function(e){
                var direction = "next";

                var $currentSlide = $(this).find(".slick-slide.slick-current:not(.slick-cloned)");
                var preload_count = 3;
                if (flo_lqip__arguments[1]) {
                  preload_count = flo_lqip__arguments[2];
                }

                // Start: Preload
                  var preload = {
                    future : {
                      index : 0,
                      count : preload_count,
                      $currentSlide : $currentSlide,

                      preload : function() {
                        preload.future.$currentSlide.find( "[" + flo_lqip__data_names.src_lqip + "]" ).flo_lqip("setOriginal");
                        preload.future.$currentSlide = preload.future.$currentSlide.next();
                      }
                    },
                    past : {
                      index: 0,
                      count : preload_count,
                      $currentSlide : $currentSlide,

                      preload : function() {
                        preload.past.$currentSlide.find( "[" + flo_lqip__data_names.src_lqip + "]" ).flo_lqip("setOriginal");
                        preload.past.$currentSlide = preload.past.$currentSlide.prev();
                        // preload.past.$currentSlide.find( "[" + flo_lqip__data_names.src_lqip + "]" ).flo_lqip("setOriginal");
                      }
                    }
                  }

                  // Start: Preload some of the future slides
                    for ( preload.future.index; preload.future.index < preload.future.count; preload.future.index++) {
                      preload.future.$currentSlide
                        .on( flo_lqip__event_names.original_set, function(){
                          preload.future.preload();
                        })
                      ;
                    }
                    preload.future.preload();
                  // End: Pre Load a couple of future slides

                  // Start: Preload some of the past slides
                    for ( preload.past.index; preload.past.index < preload.past.count; preload.past.index++) {
                      preload.past.$currentSlide
                        .on( flo_lqip__event_names.original_set, function(){
                          preload.past.preload();
                        })
                      ;
                    }
                    preload.past.preload();
                  // End: Pre Load a couple of past slides

                  /* Start: Pre Load All Clones*/
                    // $(this).find( ".slick-cloned *[" + flo_lqip__data_names.src_lqip + "]" ).flo_lqip("setOriginal");
                  /* End: Pre Load All Clones*/

                // End: Preload
              });
            break;
          /* START: SET SLICK PRELOAD */

        }
      /* END: FUNCTIONS BASED ON ATTRIBUTES */

      return $target;
    }

  }
);

})(jQuery);
