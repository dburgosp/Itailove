jQuery(function($){

  var popup = "acf-flo-flexible-content-blocks-popup";
  var popup__class = "." + popup;

  $(document).on("hover", popup__class + "__item", function(){
    var item = $(this);
    var $popup = item.parents(popup__class);

    $popup.find(popup__class + "__preview").addClass(popup + "__preview--active").css("background-image", "url("+item.attr("data-preview")+")");
  });

  // START: ON SAVE/PUBLISH VALIDATION

    // Start: Uncollapse on error
      $("#publishing-action").on("click", function(){
        setTimeout(function(){
          $(".acf-field-flo-flexible-content").find(".layout:has(.acf-error-message)").removeClass("-collapsed");
        }, 900);
      });
    // End: Uncollapse on error

  // END: ON SAVE/PUBLISH VALIDATION
  

});
