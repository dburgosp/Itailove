$(function(){
  // Start: Full Page Scroll
    if ($(".layout-sections--scroll-section").length) {

      if ($( window ).width() > 768) {

        $.scrollify({
          section:".layout-sections .full-scrollable-section",
          easing: "swing",
          scrollSpeed: 600,
          sectionName: false,
          interstitialSection:".layout-sections .full-scrollable-footer, .layout-sections header"
        });

        $(".flo-section__scroll-down").click(function(e) {
          e.preventDefault();
          $.scrollify.next();
        });

      }

    }
  // End: Full Page Scroll
});
