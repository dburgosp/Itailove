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
