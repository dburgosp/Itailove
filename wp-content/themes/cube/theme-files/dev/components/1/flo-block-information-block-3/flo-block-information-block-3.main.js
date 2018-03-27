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
