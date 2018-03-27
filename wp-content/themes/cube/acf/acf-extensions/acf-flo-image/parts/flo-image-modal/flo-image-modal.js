jQuery(function($){

  var b_class = "acf-flo-image-modal";
  var b = "." + b_class;

  $(b).each(function(){
    var modal = $(this);
    var modal__modificator_visible = b_class +"--visible";
    var modal__close_button = modal.find(b+"__close");

    /* Start: Methods */
      var modal__open = function(){
        modal
          .fadeIn()
          .addClass(modal__modificator_visible)
        ;
      }
      var modal__close = function(){
        modal
          .fadeOut(modal__modificator_visible)
          .removeClass(modal__modificator_visible)
        ;
      }
    /* End: Methods */

    /* Start: Events */
      modal.on("close", function(){
        modal__close();
        modal.find("*").trigger("modal_closed");
      });
      modal.on("open", function(){
        modal__open();
        modal.find("*").trigger("modal_opened");
      });
    /* End: Events */

    modal__close_button.add(b+"__background").on("click", function(){
      modal__close();
    });

    $(document).on("keyup", function(e){
      if (modal.is(":visible")) {
        if (e.keyCode == 27) {
          modal__close();
        }
      }
    });

  });

});
