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
