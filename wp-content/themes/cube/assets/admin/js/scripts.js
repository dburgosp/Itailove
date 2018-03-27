/*
* Execute an Ajax request to get data fields for new added slideshow images
*/
function flo_get_sl_data(attachment_id,post_id, container_class){
    jQuery.ajax({
        url: ajaxurl,
        data: '&action=get_slide_data&post_id='+post_id+'&attachment_id='+attachment_id+'&container_class='+container_class,
        type: 'POST',
        cache: false,
        success: function (result) {

            jQuery('.'+container_class).append(result);
    
        }
    });
}

// initialize  the color picker
function init_color_pickers( selector ){
    /* color piker */
    jQuery('.generic-field input.settings-color-field').each(function() {
        jQuery('.settings-color-field').wpColorPicker();
    });
}


function updateLayoutMeta(post_type,layout_meta,sidebar_meta,option_container){
    if(confirm('Are you sure you want to update the layout for all posts ?.')){
        jQuery(option_container+' .spinner').show();
        jQuery(option_container+' .spinner').css({
            'visibility':'visible'
        });
        var sidebar = jQuery('#'+sidebar_meta+'-select').val();
        var sidebar_type = jQuery('#'+layout_meta+'-select').val();
        //console.log(sidebar, sidebar_meta,sidebar_type);
        jQuery.ajax({
            url: ajaxurl,
            data: '&action=updateLayoutMeta&layout_meta='+layout_meta+'&sidebar_meta='+sidebar_meta+'&post_type='+post_type+'&sidebar_type='+sidebar_type+'&sidebar='+sidebar,
            type: 'POST',
            dataType: "json",
            cache: false,
            success: function (json) {
                jQuery(option_container+' .spinner').hide();
            },
            error: function (xhr) {
                jQuery(option_container+' .spinner').hide();
            }
        });
    }
}
