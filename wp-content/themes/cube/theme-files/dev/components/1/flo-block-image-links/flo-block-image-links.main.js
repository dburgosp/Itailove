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
