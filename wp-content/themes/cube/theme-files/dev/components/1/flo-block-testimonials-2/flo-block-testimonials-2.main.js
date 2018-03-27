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
