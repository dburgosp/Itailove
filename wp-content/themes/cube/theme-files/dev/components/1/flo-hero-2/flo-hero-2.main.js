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
