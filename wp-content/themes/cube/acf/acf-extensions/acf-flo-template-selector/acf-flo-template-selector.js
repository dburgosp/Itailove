jQuery(function($){

  /* START: THUMBS */
    var template_selector_thumbs = "acf-flo-template-selector-thumbs";
    var template_selector_thumbs__class = "." + template_selector_thumbs;
    var template_selector_thumbs__thumb_selected_class = template_selector_thumbs + "__thumb" + "--selected";
    var $template_selector_thumbs__container = $(template_selector_thumbs__class+"__wrap");
    var $template_selector_thumbs = $(template_selector_thumbs__class + "__thumb");

    $(template_selector_thumbs__class + "__thumb").each(function(){
      thumb = $(this);
      thumb__display = true;

      // Start: Process Conditions

        // Start: Hide if
          var conditions__hide_if = JSON.parse(thumb.attr("data-hide-if"));
          conditions__hide_if.forEach(function(value){
            // If there is an object with the needed class then the object is visible
            if ( value && $("."+value).length ) {
              thumb__display = false;
            }

            if ( value && value == "always" ) {
              thumb__display = false;
            }
          });
        // End: Hide if

        // Start: Show if
          var conditions__show_if = JSON.parse(thumb.attr("data-show-if"));
          conditions__show_if.forEach(function(value){
            // If there is an object with the needed class then the object is visible
            if ( $("."+value).length ) {
              thumb__display = true;
            }
          });
        // End: Show if

        if (thumb__display) {
          thumb.addClass("visible");
        }

      // End: Process Conditions

      thumb.on("click", function(){
        thumb = $(this);
        thumb__template_name = thumb.attr("data-template-name");
        thumb__preview_url = thumb.attr("data-template-preview");

        $template_selector_popup.addClass(template_selector_popup + "--active");

        $template_selector_popup.trigger("open");
        $template_selector_popup.find(template_selector_thumbs__class + "__wrap").trigger("setSelected", [thumb__template_name, thumb__preview_url]);

      });

    });
  /* END: THUMBS */

  /* START: POPUP */
    var template_selector_popup = "acf-flo-template-selector-popup";
    var template_selector_popup__class = "." + template_selector_popup;
    var $template_selector_popup = $(template_selector_popup__class);

    var template_selector_popup__preview = template_selector_popup + "__preview";
    var template_selector_popup__preview_class = "." + template_selector_popup__preview;
    var $template_selector_popup__preview = $template_selector_popup.find(template_selector_popup__preview_class);

    /* Start: Thumbs */
      $template_selector_popup.find(template_selector_thumbs__class + "__wrap").on("setSelected", function(e, template__name, template__preview_url){

        $template_selector_popup.find(template_selector_thumbs__class + "__thumb").removeClass(template_selector_thumbs__thumb_selected_class);
        $template_selector_popup.find(template_selector_thumbs__class + "__thumb" + "[data-template-name="+template__name+"]").addClass(template_selector_thumbs__thumb_selected_class);

        $template_selector_popup__preview.attr("data-img-loaded", "false").css("background-image", "");

        var image = new Image();
        var image_size_interval = setInterval(function () {
          // console.log("Image Size Interval");
          if (typeof image.naturalWidth != "undefined" && image.naturalWidth) {
            clearInterval(image_size_interval);
            image_size_interval = false;
            $template_selector_popup__preview.attr({
              "data-img-width"  : image.naturalWidth,
              "data-img-height" : image.naturalHeight,
              "data-img-orientation" : image.naturalWidth / image.naturalHeight >= 1 ? "landscape" : "portrait"
            });
          }
        }, 100);
        $(image).on("load", function(){

          $template_selector_popup__preview.attr("data-img-loaded", "true").css("background-image", "url(" + template__preview_url + ")");

          var load_interval = setInterval(function () {
            // console.log("Load Interval");
            if (image_size_interval == false) {
              clearInterval(load_interval)
              image = null;
            }
          }, 100);
        });
        image.src = template__preview_url;


      });
    /* End: Thumbs */

    /* START: PREVIEW */
      // Start: Mouse Zoom and nav
        var template_selector_popup__preview_nav_bounding_box = $template_selector_popup__preview.find(template_selector_popup__class + "__preview-nav-bounding-box");
        var template_selector_popup__preview_mouse_position = {};

        $template_selector_popup__preview
          .on("mouseover", function(){
            // var template_selector_popup__preview_height = $template_selector_popup__preview.height();

            var template_selector_popup__preview_nav_bounding_box_height = template_selector_popup__preview_nav_bounding_box.height();

            preview = $(this);
            preview.css("background-size", "100%");

            /* Start: Mouse nav for preview image */
              $(document).mousemove(function(e){
                // template_selector_popup__preview_mouse_position = {
                //   left: e.pageX - $template_selector_popup__preview.offset().left,
                //   top: e.pageY - $template_selector_popup__preview.offset().top
                // };

                template_selector_popup__preview_nav_bounding_box_mouse_position = {
                  left: e.pageX - template_selector_popup__preview_nav_bounding_box.offset().left,
                  top: e.pageY - template_selector_popup__preview_nav_bounding_box.offset().top
                };

                // var template_selector_popup__preview_image_vertical_position =
                //   template_selector_popup__preview_mouse_position.top * 100 / template_selector_popup__preview_height
                // ;

                var template_selector_popup__preview_nav_bounding_box_vertical_position =
                  template_selector_popup__preview_nav_bounding_box_mouse_position.top * 100 / template_selector_popup__preview_nav_bounding_box_height
                ;

                if (template_selector_popup__preview_nav_bounding_box_vertical_position < 0) {
                  template_selector_popup__preview_nav_bounding_box_vertical_position = 0;
                } else if (template_selector_popup__preview_nav_bounding_box_vertical_position > 100) {
                  template_selector_popup__preview_nav_bounding_box_vertical_position = 100;
                }

                var container_ratio = $template_selector_popup__preview.width() / $template_selector_popup__preview.height();
                var img_ratio = $template_selector_popup__preview.attr("data-img-width") / $template_selector_popup__preview.attr("data-img-height");
                var is_img_taller_than_container = img_ratio < container_ratio;
                var is_img_loaded = $template_selector_popup__preview.attr("data-img-loaded") == "true" ? true : false;
                if (is_img_taller_than_container && is_img_loaded) {
                  $template_selector_popup__preview.css(
                    "background-position",
                    // "center " + template_selector_popup__preview_image_vertical_position + "%"
                    "center " + template_selector_popup__preview_nav_bounding_box_vertical_position + "%"
                  );
                }
              });
            /* End: Mouse nav for preview image */

          })
          .on("mouseleave", function(){
            preview = $(this);
            preview.css("background-size", "");
            preview.css("background-position", "");
            $(document).unbind("mousemove");
          })
        ;
      // End: Mouse Zoom and nav
    /* END: PREVIEW */

    /* START: TARGET FLEXIBLE CONTENT */
      var target_flexible_content__name = $template_selector_popup.attr("data-target-flexible-content-name");
      var $target_flexible_content = $(".acf-field[data-name='"+target_flexible_content__name+"']").first();

      $target_flexible_content.on("removeAll", function(){
        // $(this).find('[data-event="remove-layout"]').click();
        $(this).find(".values").empty();
      });

      $target_flexible_content.on("add", function(e, blocks){
        // console.log("Add block triggered.");
        var i = 1;

        blocks = JSON.parse(blocks);
        //console.log(blocks);
        blocks.forEach(function(block_name){
          // console.log("Adding block: ",block_name);

          // we want to have a timeout between each click to allow the ajax request that adds the template block to finish
          // executing in the same order as they were clicked.
          setTimeout(function () {
            $target_flexible_content.find(".acf-fc-popup--hidden a[data-layout='"+block_name+"']").click();
          }, i*500);

          i = i + 1;
        });
      });
    /* END: TARGET FLEXIBLE CONTENT */

    /* START: APPLY BUTTON */
      var $apply_button = $template_selector_popup.find(template_selector_popup__class + "__button-apply");

      $apply_button.on("click", function(){
        var confirmation = confirm('Note: All blocks inside the "Layout" tab will be replaced with the blocks from the new template.');
        if (confirmation) {
          var currently_selected_thumb = function(){
            return $("." + template_selector_thumbs__thumb_selected_class).first();
          }
          var currently_selected_thumb__blocks = currently_selected_thumb().attr("data-template-blocks");
          $target_flexible_content.trigger("removeAll");

          if ($template_selector_popup.hasClass(template_selector_popup + "--optimized-for-json-block-fetching")) {
            // console.log("Template Selector: Optimize for json enabled");
            $target_flexible_content.find(".acf-flexible-content").trigger("add_json_template", currently_selected_thumb__blocks);
          } else {
            $target_flexible_content.trigger("add", currently_selected_thumb__blocks);
            $template_selector_popup.trigger("close");
          }

          $(".acf-tab-button:contains('Layout')").click();
        }
      });
    /* END: APPLY BUTTON */


  /* END: POPUP */

  /* START: ADD DEFAULT BLOCKS BASED ON FLAG */

    /* START: POST AND GALLERY GLOBAL TEMPLATE INHERITING */
    var custom_layout_group = $('[data-name="use_custom_layout"]');
    var global_post_template = $template_selector_thumbs__container.find("[data-default-if='is-post-editor']");
    if(custom_layout_group.length && global_post_template.length){
      var custom_layout_radio = custom_layout_group.find('input[type="checkbox"]');
      var custom_layout_status = custom_layout_radio.attr('checked');
      var global_post_blocks = global_post_template.attr("data-template-blocks");

      // if custom layout is currently disabled and template blocks are present, proceed
      if( typeof(custom_layout_status) === 'undefined' && global_post_blocks !== "false"){
        custom_layout_radio.one('click', function(){
          $target_flexible_content.find(".values").empty();

          // add the global post template blocks, use old method if not optimized for JSON
          if ($template_selector_popup.hasClass(template_selector_popup + "--optimized-for-json-block-fetching")) {
            acf.fields.flo_flexible_content.add_json_template(global_post_blocks);
          } else {
            $target_flexible_content.trigger("add", global_post_blocks);
          }
        })
      }
    /* END: POST AND GALLERY GLOBAL TEMPLATE INHERITING */
    } else if ($target_flexible_content.find(".values").children().length === 0) {

      /* START: SET DEFAULT TEMPLATE */
        var default_template = $template_selector_thumbs__container.find("[data-default-if='none-other-present']");
      /* END: SET DEFAULT TEMPLATE */

      /* START: SET DEFAULT TEMPLATE BY FLAGS */
        $template_selector_thumbs.each(function(){
          $this = $(this);
          default_value = $this.attr("data-default-if");
          if (default_value !== "false") {
            if ($("."+default_value).length) {
              default_template = $this;
            }
          }
        });
      /* END: SET DEFAULT TEMPLATE BY FLAGS */


      /* START: ADD BLOCKS FROM TEMPLATE */
        if (default_template.length) {
          var default_blocks = default_template.attr("data-template-blocks");
          //$target_flexible_content.trigger("add", default_blocks);
          acf.fields.flo_flexible_content.add_json_template(default_blocks); // add the default template blocks
        }
      /* END: ADD BLOCKS FROM TEMPLATE */

    }
  /* END: ADD DEFAULT BLOCKS BASED ON FLAG */
});
