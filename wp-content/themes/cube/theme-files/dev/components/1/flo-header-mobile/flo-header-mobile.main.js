$(function(){
  var is_not_sticky_class = "is-not-sticky";
  $(".flo-header-mobile").addClass(is_not_sticky_class);
  $(".flo-header-mobile.sticky")
    .on("sticky-start", function(){
      $(this).removeClass(is_not_sticky_class);
    })
    .on("sticky-end", function(){
      $(this).addClass(is_not_sticky_class);
    })
    .sticky({
        zIndex: 1000,
        className: "is-sticky",
        wrapperClassName: "flo-header-mobile-sticky-wrapper"
    })
  ;
});
