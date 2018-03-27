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
