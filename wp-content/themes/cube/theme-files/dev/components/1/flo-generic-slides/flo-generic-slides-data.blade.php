{{--
  REQUIRES VARIABLES:
  - REQ VAR - $b
  - REQ VAR - $b__uniq_for_css
  - REQ VAR - $slideshow_id
  - REQ VAR - $image_type - can be "img" or "bgi"
  - REQ VAR - $height_type - can be "full" or "specific"
  - REQ VAR - $height_px

  SECTIONS:
  - SECTION - slide_content - here you can add custom elements to each slide's content.
  - SECTION - slick_options - here you can add extra options to the slick initialization function.
    Example:
      @section("slick_options")
        "arrows": "false",
        "cssEase": "ease-in-out",
        "speed": "400",
      @overwrite
  - SECTION - elements_to_change_color_to - here you specify which elements inside slides should change their colors (e.g. color, border-color etc.)
    Example:
      @section("elements_to_change_color_to")
        block + "__footer-middle-area:before,",
        block + "__footer-middle-area:after",
        "{ ",
          "background-color: " + hex2rgba(elements_color, 0.2) + ";",
        "} ",

        block + " .flo-block-delimiter-1__delimiter-line",
        "{ ",
          "background-color: " + hex2rgba(elements_color, 0.2) + ";",
        "} ",
      @overwrite

    Note: Don't forget to add a trailing comma to the list!
    Note: RGBA - for a rgba value of elements color use the hex2rgba function.
      Example:
        "border-color: " + hex2rgba(elements_color, 0.1) + "!important;",

  HTML DATA ATTRIBUTES:
  Each slide has some attributes with slide properties. You can get them with the jQuery .attr() function.
  - DATA ATTR - data-elements-color
  - DATA ATTR - data-title
  - DATA ATTR - data-subtitle
  - DATA ATTR - data-url
  - DATA ATTR - data-img

  EVENTS:
  - EVENT - All Slick Events - first of all, since the generic slides are based on the slick carousel - all events provided by the slick carousel are usable.
  - EVENT - elementsColorLight - when the elements color of a slide are light - this event is fired.
  - EVENT - elementsColorDark - when the elements color of a slide are dark - this event is fired.

  NOTES:
  - NOTE - Initialization - to initialize the slideshow you need to trigger the "floInit" event on it. This is very useful when you need to bind some custom functionality to its events before it is intialized.
    Example:
    // $el.find(dotb + "__slides") - usually the selector for the slideshow in our themes.
    $el.find(dotb + "__slides")
      .trigger("floInit")
    ;

--}}

{{-- START: GET SLIDESHOW DATA FUNCTION --}}
  <?php

  if (!function_exists("flo_get_slideshow_data")) {
    function flo_get_slideshow_data($slideshow_id) {
      if ($slides = get_field("_post_image_gallery", $slideshow_id)){
        $slideshow_data = Array();
        $slideshow_data["slides"] = Array();

        /* START: SLIDESHOW DATA */
          $slideshow_data["autoplay"] = get_field("slideshow_autoplay", $slideshow_id) ? 'true': 'false';
          $slideshow_data["transition_speed"] = get_field("slideshow_transition_speed", $slideshow_id);
          $slideshow_data["autoplay_speed"] = get_field("autoplay_speed", $slideshow_id) * 1000;
          $slideshow_data["pause_on_hover"] = get_field("slideshow_pause_on_hover", $slideshow_id) ? "true" : "false";
          $slideshow_data["fade"] = get_field("slideshow_slide_effect", $slideshow_id) == "fade" ? "true" : "false";
        /* END: SLIDESHOW DATA */

        /* START: SLIDES DATA */
          foreach ($slides as $slide) {

            /* START: MOBILE CROP POSITION */
              if( isset($slide["slide_image"]['crop_position']) ){
                $crop_position = $slide["slide_image"]['crop_position'];

                // the background position is calculated using the following formula:
                // y = 1.8x - 40
                // http://www.wolframalpha.com/input/?i=interpolate+%5B(22.22,+0),(77.77,100)+%5D
                $mobile_crop_position = (1.8*$crop_position - 40).'%';
              }else{
                $mobile_crop_position = '50';
              }
            /* END: MOBILE CROP POSITION */

            /* START: VIDEO FUNCTIONALITY */
              $slide_type = $slide["slide_type"];

              $video_embed_code = "";
              $video_url = "";

              switch ($slide_type) {
                case 'image':

                break;
                case 'image_and_video_embed':
                $video_embed_code = $slide["slide_video_url"];
                break;

                case 'video_slide':
                $video_url = $slide["slide_video"];
                break;

                default:
                break;
              }
            /* END: VIDEO FUNCTIONALITY */

            /* START: ALT TEXT */
              if(isset($slide["slide_image"]['title'])){
                $alt_text = $slide["slide_image"]['title'];
              }else{
                $alt_text = '';
              }
            /* END: ALT TEXT */

            /* START: ADD SLIDE DATA TO SLIDESHOW DATA */
              $slideshow_data["slides"][] = [
                "object" => $slide,
                "elements_color" => $slide["slide_info"][0]["elements_color"],
                "type" => $slide_type,

                "img" => $slide["slide_image"]["url"],
                "image_srcset" => wp_get_attachment_image_srcset( $slide["slide_image"]["id"], 'full' ),
                "alt" => $alt_text,
                "mobile_crop_position" => $mobile_crop_position,

                "video_url" => $video_url,
                "video_embed_code" => $video_embed_code,

                "pretitle" => $slide['slide_info'][0]['pretitle'],
                "title" => $slide['slide_info'][0]['title'],
                "subtitle" => $slide['slide_info'][0]['subtitle'],
                "url" => $slide["slide_info"][0]["title_url"],
              ];
            /* END: ADD SLIDE DATA TO SLIDESHOW DATA */

          }
        /* END: SLIDES DATA */

      }

      if (isset($slideshow_data)) {
        return $slideshow_data;
      } else {
        return;
      }
    }
  }

  ?>
{{-- END: GET SLIDESHOW DATA FUNCTION --}}
