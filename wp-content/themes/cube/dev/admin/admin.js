jQuery(function($){
  var $acfClient = $("body:not(.post-type-acf-field-group)");

  // START: ACF TAB APPEAR
    $acfClient.find(".acf-postbox").fadeIn("slow");
  // END: ACF TAB APPEAR

  // START: TABS--LEFT -> LABEL -> PUT INDEXES IN A SEPARATE SPAN
    var $labels = $acfClient.find(".acf-tab-wrap.-left .acf-tab-group li a");
    var labels_count = $labels.length;

    labels_iteration_possible_parent_tab_id = "";
    $labels.each(function(){
      var $item = $(this);

      var initial_text = $item.text();
      var initial_text_array = initial_text.split(". ");

      var label = initial_text_array[1];

      var index = initial_text_array[0] + ".";
      var index_has_offset = ~index.indexOf("- ") ? true : false;
      var index = index_has_offset ? index.replace("- ", "") : index;
      var index_class = index_has_offset ? "acf-tab-button__index acf-tab-button__index--with-offset" : "acf-tab-button__index";

      if (initial_text_array.length > 1) {
        $item.html([
          "<span class='", index_class, "'>",
            index,
          "</span>",
          label
        ].join(""));
      }

      // Start: Change possible parent id if this one is not a sub tab
        if (!index_has_offset) {
          labels_iteration_possible_parent_tab_id = $item.attr("data-key");
        }
      // End: Change possible parent id if this one is not a sub tab

      // Start: Set Class and parent Id To Sub Items + add "acf-parent-tab" class to parent
        if (index_has_offset) {
          $item.parent()
            .addClass("acf-subtab")
            .attr("data-parent-id", labels_iteration_possible_parent_tab_id);
          ;
          $(".acf-tab-group > li a[data-key=" + labels_iteration_possible_parent_tab_id + "]").parent().addClass("acf-parent-tab");
        }
      // End: Set Class and parent Id To Sub Items + add "acf-parent-tab" class to parent

    });

    // Start: On Tab Click - hide all subtabs and show the needed subtabs
      $(".acf-tab-group > li:not(.acf-subtab) a").on("click", function(){
        tab_id = $(this).attr("data-key");
        $(".acf-tab-group > li.acf-subtab:not([data-parent-id=" + tab_id + "])").slideUp("slow");
        $(".acf-tab-group > li.acf-subtab[data-parent-id=" + tab_id + "]").slideDown("slow");
      });
    // End: On Tab Click - hide all subtabs and show the needed subtabs

  // END: TABS--LEFT -> PUT INDEXES IN A SEPARATE SPAN

  var $tabs = $acfClient.find([
    ".inside.acf-fields.-top.-sidebar > .acf-tab-wrap.-left > .acf-hl.acf-tab-group",
    ".inside.acf-fields.-left.-sidebar > .acf-tab-wrap.-left > .acf-hl.acf-tab-group",
    ".acf-tab-wrap.-top > .acf-hl.acf-tab-group"
  ].join(" , "));
  // START: FLYING SIDE TABS

    var $tabs__flying = $acfClient.find([
      ".inside.acf-fields.-top.-sidebar > .acf-tab-wrap.-left > .acf-hl.acf-tab-group",
      ".inside.acf-fields.-left.-sidebar > .acf-tab-wrap.-left > .acf-hl.acf-tab-group"
    ].join(" , "));
    // Start: Start Flying Tabs
      $tabs__flying.makeFixed();
    // End: Start Flying Tabs

    // Start: On Tab Click - misc
      $tabs.click(function(){
        // Adjust Number Slider position
        $(".simple_slider").trigger("adjustposition");
      });
    // End: On Tab Click - misc

    // Start: Stop Flying if parent bottom reached
      $(document).on("scroll", function(){
        $tabs__flying.each(function(){

          var
            $tabs = $(this),
            tabs__height = $tabs.height(),
            tabs__class_fixed_to_parent_bottom = "fixed_to_bottom",

            non_fixed_threshold = 52 + tabs__height,

            $tabs_parent = $tabs.parent().parent(),
            tabs_parent__height = $tabs_parent.height(),
            tabs_parent__viewport_bottom = $tabs_parent[0].getBoundingClientRect().top + tabs_parent__height,
            tabs_parent__viewport_bottom_has_reached_non_fixed_threshold = tabs_parent__viewport_bottom < non_fixed_threshold
          ;

          if(tabs_parent__viewport_bottom_has_reached_non_fixed_threshold){
            if (!$tabs.hasClass(tabs__class_fixed_to_parent_bottom)) {
              $tabs.addClass(tabs__class_fixed_to_parent_bottom);
            }
          }

          else {
            $tabs.removeClass(tabs__class_fixed_to_parent_bottom);
          }

          if ($tabs.hasClass(tabs__class_fixed_to_parent_bottom)) {
            $tabs.css(
              {
                top : tabs_parent__viewport_bottom - tabs__height
              }
            );
          }

        });
      });
    // End: Stop Flying if parent bottom reached

  // END: FLYING SIDE TABS

  /* START: RETURN TO LAST TAB AFTER SAVE */

    var body_classes = $("body").attr("class");
    var floAdminLastTabs = localStorage.floAdminLastTabs ? JSON.parse(localStorage.floAdminLastTabs) : {};
    // var floAdminLastTabs = {}; // Resets the localStorage.floAdminLastTabs
    var floAdminLastTabId = floAdminLastTabs[body_classes];

    // Start: Remember Last Tab
      $acfClient.find(".inside > .acf-tab-wrap li a").on("click", function(){
        var data_key = $(this).data("key");
        floAdminLastTabs[body_classes] = data_key;
        localStorage.floAdminLastTabs = JSON.stringify(floAdminLastTabs);
      });
    // End: Remember Last Tab

    // Start: Click Last Tab
      if (floAdminLastTabId) {
        var $floAdminLastTab = $acfClient.find(".acf-tab-button[data-key=" + floAdminLastTabId + "]");
        if ($tabs.length && $floAdminLastTab.length) {

          $floAdminLastTab.click();

          // Start: If is subtab -> show childs
            var $floAdminLastTabParent = $floAdminLastTab.parent();
            if ($floAdminLastTabParent.hasClass("acf-subtab")) {
              var parent_id = $floAdminLastTabParent.attr("data-parent-id");
              $("li[data-parent-id=" + parent_id + "]").slideDown("slow");
              // console.log($floAdminLastTab.length);
              // console.log($floAdminLastTabParent.hasClass("acf-subtab"));
            }
          // End: If is subtab -> show childs

          $tabs.animate({
            scrollTop: $floAdminLastTab.position().top
          }, 400);

        }
      }
    // End: Click Last Tab

  /* END: RETURN TO LAST TAB AFTER SAVE */

});

/* START: GETTING STARTED - ONE CLICK PERMALINKS SETUP */
  jQuery(document).ready(function($) {
    jQuery('.flo_pretty_permalinks').change(function() {

        if(jQuery(this).find('input[type="checkbox"]').attr('checked') == 'checked' ){

            // call the function that makes the Ajax request to update the Permilink structure
            flo_quick_update_pemalinks();
        }

    });
  });
/* END: GETTING STARTED - ONE CLICK PERMALINKS SETUP */

/* START: CUSTOM CSS INIT */
  jQuery(document).ready(function($){
    // init custom css
    if ($("#flo-custom-css-div").length){
          var editor_field = $( '.acf-field.flo-custom-css' ).attr('data-key');
          var editor = ace.edit("flo-custom-css-div");
          var textarea = $('#acf-'+editor_field).hide();
          editor.getSession().setMode("ace/mode/css");
          editor.setBehavioursEnabled(true);
          // enable emmet
          editor.setOption("enableEmmet", true);
          editor.getSession().setValue(textarea.val());
          editor.getSession().on('change', function(){
            textarea.val(editor.getSession().getValue());
          });
      }
  });
/* END: CUSTOM CSS INIT */

/* START: GETTING STARTED - FUNCTION THAT UPDATES THE PERMALINKS */
  function flo_quick_update_pemalinks(){

      jQuery('.wizard-permalinks-response').hide();

      jQuery.ajax({
          url: ajaxurl,
          data: '&action=quick_update_pemalins',
          type: 'POST',
          dataType: "json",
          cache: false,
          success: function (json) {

              console.log(json.message);
              jQuery('.wizard-permalinks-response').show();

          },
          error: function (xhr) {
              console.log(xhr);
          }
      });
  }
/* END: GETTING STARTED - FUNCTION THAT UPDATES THE PERMALINKS */

/* START: GETTING STARTED - SET THE MAIN MENU */
  function floSetMainMenu(){
      var menu_option = jQuery('.menu-creation select option:selected').val(),
          manually_menu_option = jQuery('.main-menu-select option:selected').val();

      jQuery('.wizard-menu-response').html('');
      jQuery('.wizard-menu-response').hide();

      if( jQuery.trim(menu_option).length && menu_option == 'manually' && manually_menu_option == '' ){

          alert('Select please a menu from the dropd down that will be used as the Main menu.');

      }else if( jQuery.trim(menu_option).length ){

          jQuery('.wizard-menu-spinner').show();
          jQuery('.wizard-menu-spinner').css({
              'visibility':'visible'
          });


          jQuery.ajax({
              url: ajaxurl,
              data: '&action=set_main_menu&menu_option='+menu_option+'&manually_menu_option='+manually_menu_option,
              type: 'POST',
              dataType: "json",
              cache: false,
              success: function (json) {

                  jQuery('.wizard-menu-response').html(json.message);
                  jQuery('.wizard-menu-response').show();
                  jQuery('.wizard-menu-spinner').hide();

              },
              error: function (xhr) {
                  jQuery('.wizard-menu-spinner').hide();
                  console.log(xhr);
              }
          });
      }else{
          alert('Select please the method used to create the Main menu from the drop down.');
      }
  }
/* END: GETTING STARTED - SET THE MAIN MENU */

/* START: FLO ADMIN POPUP */
  jQuery(function($){

    var b_class = "flo-admin-popup";
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
/* END: FLO ADMIN POPUP */

function importDummyData(){

    var folder_name = jQuery('.demo-content-folder select option:selected').val();
    if( jQuery.trim(folder_name).length && folder_name != 0){
        if(confirm('Are you sure you want to import Dummy data? This will change your current content and settings.')){
            jQuery('.import-demo-spinner').show();
            jQuery('.import-demo-spinner').css({
                'visibility':'visible'
            });

            jQuery('.import-response').show();


            jQuery.ajax({
                url: ajaxurl,
                data: '&action=importDummyData&folder='+folder_name,
                type: 'POST',
                dataType: "json",
                cache: false,
                success: function (json) {

                    jQuery('.import-response').html(json.message);
                    jQuery('.import-demo-spinner').hide();

                },
                error: function (xhr) {
                    jQuery('.import-demo-spinner').hide();
                }
            });
        }
    }else{
        alert('Select please the demo version you want to import.');
    }


}


function importDummySettings(){

    var folder_name = jQuery('.demo-settings-folder select option:selected').val();
    if( jQuery.trim(folder_name).length && folder_name != 0){
        if(confirm('Are you sure you want to import the Dummy Settings? This will change your current settings.')){
            jQuery('.import-demo-settings-spinner').show();
            jQuery('.import-demo-settings-spinner').css({
                'visibility':'visible'
            });

            jQuery('.settings-import-response').show();


            jQuery.ajax({
                url: ajaxurl,
                data: '&action=importDummySettings&folder='+folder_name,
                type: 'POST',
                dataType: "json",
                cache: false,
                success: function (json) {

                    jQuery('.settings-import-response').html(json.message);
                    jQuery('.import-demo-settings-spinner').hide();

                },
                error: function (xhr) {
                    jQuery('.import-demo-settings-spinner').hide();
                }
            });
        }
    }else{
        alert('Select please the demo version you want to import the settings from.');
    }


}


/**
 *
 * Function that saves the options when 'Set the selected stylekit button is clicked'
 *
 */
function floSetStylekit(){
  //console.log( jQuery('[data-name*="flo-stylekit"] input[type="radio"]:checked').length );

  if(jQuery('[data-name*="flo-stylekit"] input[type="radio"]:checked').length){
    if(confirm('Do you want to activate the selected Style Kit? This will change the current Typography and Color settings.')){

      // we need to set the value for this input to 'set_stylekit' because only such value will trigger the stylekit update
      jQuery('[data-name="flo-stylekit-trigger"] .acf-input-wrap input').val('set_stylekit');

      // delay a bit the click on the button to make sure the value is set on the input above.
      setTimeout(function() {
        // click on the publish button
        jQuery('#publish').click();
      }, 10);

    }
  }else{
    alert('Please select a Style Kit');
  }
}



/**
 *
 * trigger the ajax request that should change the custom fonts URLs
 *
 */
function importReplaceDemoFonts(){

  if(confirm(' This will replace the fonts URLs. It is recommended to have a DB backup first.  Are you sure ?  ')){

    jQuery('.import-replace-dummy-font.spinner').css('visible','visible');

    jQuery.ajax({
        url: ajaxurl,
        data: '&action=replace_typography_fonts',
        type: 'POST',
        dataType: "json",
        cache: false,
        success: function (json) {

            jQuery('.import-replace-dummy-font.spinner').css('visible','hidden');
            console.log(json);
            alert(json.message);
            //jQuery('.wizard-permalinks-response').show();

        },
        error: function (xhr) {
            console.log(xhr);
        }
    });
  }

}

function flo_disable_default_page_templates_warning() {
  jQuery.ajax({
      url: ajaxurl,
      data: '&action=disable_default_page_templates_warning',
      type: 'POST',
      dataType: "json",
      cache: false,
      success: function (json) {
        jQuery('.default_page_templates_warning').hide();
      },
      error: function (xhr) {
          console.log(xhr);
      }
  });
}


function wpml_conf_generation() {
  if(confirm('Are you sure you want to regenerate the default Wpml configuration file ? This ation will replace the current file.')){
    
    jQuery('.wpml-conf-spinner').show();
    jQuery('.wpml-conf-spinner').css({
        'visibility':'visible'
    });

    jQuery.ajax({
        url: ajaxurl,
        data: '&action=wpml_conf_generation',
        type: 'POST',
        dataType: "json",
        cache: false,
        success: function (json) {

          jQuery('.flo-wpml-config-regeneration .import-response').text(json.msg);
          
          jQuery('.wpml-conf-spinner').hide();

        },
        error: function (xhr) {
            jQuery('.wpml-conf-spinner').hide();
        }
    });

  }
}

jQuery(document).ready(function($) {
  
  $('.flo-wmpl-disable-cache').change( function() {
    var cache_action;

    jQuery('.wpml-conf-spinner').show();
    jQuery('.wpml-conf-spinner').css({
        'visibility':'visible'
    });

    
    if ($('#flo_wmpl_disable_cache').is(':checked') ) { // if the checkbox is checked we need to disablethe cache
      cache_action = 'disable_cache';
    }else{
      cache_action = 'enable_cache';
    }

    jQuery.ajax({
        url: ajaxurl,
        data: '&action=flo_wmpl_alter_cache_option&cache_action='+cache_action,
        type: 'POST',
        //dataType: "json",
        cache: false,
        success: function (response) {

          jQuery('.flo-wpml-config-regeneration .import-response').html(response);
          
          jQuery('.wpml-conf-spinner').hide();

        },
        error: function (xhr) {
            jQuery('.wpml-conf-spinner').hide();

        }
    }); 
    

    
    
  });

});

function disableEnter(event){
  if(event.keyCode == 13){
    event.preventDefault();
  }
}

function searchBlocks(event) {
  var input, filter, blocks_container, child_block, child_block_label, i;
  input = jQuery(".acf-flo-flexible-content-blocks-popup__search-blocks");
  blocks_container = jQuery(".acf-flo-flexible-content-blocks-popup__items");
  child_block = blocks_container.find(".acf-flo-flexible-content-blocks-popup__item");

  if(event.keyCode == 27){
    jQuery(input).val('');
    jQuery(input).text('');
  }

  filter = input[0].value.toUpperCase();
  
  for (i = 0; i < child_block.length; i++) {
    child_block_label = jQuery(child_block[i]).find(".acf-flo-flexible-content-blocks-popup__item-label");
    if (child_block_label) {
      if (child_block_label.text().toUpperCase().indexOf(filter) > -1) {
        child_block[i].style.display = "";
      } else {
        child_block[i].style.display = "none";
      }
    } 
  }
}