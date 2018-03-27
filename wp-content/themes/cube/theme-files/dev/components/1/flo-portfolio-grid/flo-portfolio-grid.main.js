$(function(){

  $(".flo-portfolio-grid .row.masonry, .flo-section--journal-grid-section .row.masonry").each(function(){
    $grid = $(this);

    function do_masonry($grid) {
      if (window.innerWidth >= 768) $grid.masonry({
        itemSelector : ".column",
        columnWidth : 0
      });
    }

    do_masonry($grid);

     $(this).find("img").on("load", function(){
      do_masonry($grid);
    });

  });

});
