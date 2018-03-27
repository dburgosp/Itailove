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
