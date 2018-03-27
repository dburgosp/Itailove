jQuery(function($){

  var b_class = "acf-flo-image-positioner";
  var b = "." + b_class;

  $(b).each(function(){

    var positioner = $(this);
    var content = positioner.find(b+"__content");
    var crop_area = positioner.find(b+"__container-area");
    var data_input = positioner.find(b+"__data");
    var image = positioner.find(b+"__image");
    var image__crop_position = image.attr("data-crop-position");

    positioner.on("modal_opened", function(){
      image.attr("src", image.attr("data-src"));
      image.on("load", function(){

        /* Start: Position Crop Area by the initial Image Position */
          crop_area.css("left", image.width() / 100 * image__crop_position - crop_area.width() / 2 );
        /* End: Position Crop Area by the initial Image Position */

      });

      crop_area.draggable(
        {
          containment: "parent",
          axis: "x",

          stop: function(e, ui){
            var content__width = content.width();

            var crop_area__width = crop_area.width();
            var crop_area__left = ui.position.left;
            var crop_area__middle_left = crop_area__left + crop_area__width / 2;
            var crop_area__middle_left_percent = crop_area__middle_left / (content__width / 100);
            var image_position_inside_container = crop_area__middle_left_percent;
            var crop_position_imput = positioner.parents('.acf-image-uploader').find('.acf-flo-image__input-positioner-position');

            data_input.val(image_position_inside_container);
            image.attr("data-crop-position", image_position_inside_container);
            crop_position_imput.attr('value',image_position_inside_container);
            
          }
        }
      );

    });

  });

});
