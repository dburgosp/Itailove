@include('components.flo-generic-slides-data')

<?php
// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
// End: Class name automation

$slideshow_data = flo_get_slideshow_data($slideshow_id);
if(isset($force_transition) && ($force_transition == 'slide')){
    $slideshow_data["fade"] = false;
}
if (!isset($image_type)) {
  $image_type = "bgi";
}

/* START: SLIDE SIZE */
  if (!isset($height_type)) {
    $height_type = "full";
  }

  if ($height_type == "full") {
    $height = "100vh";
  } elseif ($height_type == "specific") {
    if (!isset($height_px)) {
      $height_px = 600;
    }

    $height = $height_px / 16 . "rem";
  }
/* END: SLIDE SIZE */

?>

@if($slideshow_data["slides"])
  {{-- START: SET HEIGHT --}}
  @if (isset($height))
    @include('core.style', [
      "breakpoint__medium_up" => "

        ".$b__uniq_for_css." ".$b__for_css."__slide-img {
          height: ".$height.";
        }

      "
    ])
  @endif
  {{-- END: SET HEIGHT --}}

  {{-- START: AUTO ELEMENTS COLOR CHANGE --}}
    <?php
      $elements_to_color = [];
    ?>
    <script type="text/javascript">
      /* START: HEX2RGBA */
        function hex2rgba(hex, alpha) {
            var r = parseInt(hex.slice(1, 3), 16),
                g = parseInt(hex.slice(3, 5), 16),
                b = parseInt(hex.slice(5, 7), 16);

            if (alpha) {
                return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
            } else {
                return "rgb(" + r + ", " + g + ", " + b + ")";
            }
        }
      /* END: HEX2RGBA */

      jQuery(document).on("floInit", ".flo-generic-slides", function(){
        "use strict";

        var $ = jQuery;
        // var $el = $(this);
        var $el =$(".flo-generic-slides");
        var parent = $el.parents(".flo-block");
        var block_id = parent.attr("data-id");

        /* START: CSS SELECTORS */
          var b = "flo-generic-slides"; // Block class for HTML
          var dotb = "." + b; // Block class for CSS
          var block_parent_with_id = ".flo-block--" + block_id; // Block Parent class with ID

          var block = block_parent_with_id + " " + ".{{$b}}";
        /* END: CSS SELECTORS */

        $el
          .on("init afterChange", function(){

            var elements_color = $(this).find(".slick-current").attr("data-elements-color");

            var css = [];

            css.push([
              @yield('elements_to_change_color_to')
            ].join("\n"));

            css = css.join("\n");

            if ($("style.flo-slideshow__slides--" + block_id).length) {
              $("style.flo-slideshow__slides--" + block_id).html(css);
            } else {
              $("head").append(
                $(" <style class='flo-generic-slides-styles--" + block_id + " '> ").html(css)
              );
            }
          })
        ;
      }
    );
    </script>
  {{-- END: AUTO ELEMENTS COLOR CHANGE --}}

  <div
    class="flo-generic-slides flo-generic-slides--image-type-{{$image_type}} {{$b}}__slides {{$b}}__slides--image-type-{{$image_type}}"
    data-slick='{
      "speed": {{ $slideshow_data["transition_speed"] }},
      "fade": {{ $slideshow_data["fade"] }},
      "autoplay": {{ $slideshow_data["autoplay"] }},
      "autoplaySpeed": {{ $slideshow_data["autoplay_speed"] }},
      "pauseOnHover": {{ $slideshow_data["pause_on_hover"] }},
      {{-- START: SLICK OPTIONS SECTION TO BE EXTENDED --}}
        @yield("slick_options")
      {{-- END: SLICK OPTIONS SECTION TO BE EXTENDED --}}
      "": ""
    }'
    data-autoplay="{{ $slideshow_data['autoplay'] }}"
  >
    @foreach($slideshow_data["slides"] as $slide)
      <?php
        $slide_class = $b."__slide--".mt_rand(1, 9999);
        $slide_class_for_css = ".".$slide_class;

        if(isset($slide['alt'])){
          $alt_text = $slide['alt'];
        } else {
          $alt_text = '';
        }
      ?>
      <div
        class="flo-generic-slides__slide flo-generic-slides__slide--{{$slide["type"]}} {{$b}}__slide {{ $slide_class }} {{$b}}__slide--{{$slide["type"]}}"

        data-elements-color="{{$slide["elements_color"]}}"
        data-pretitle="{{$slide["pretitle"]}}"
        data-title="{{$slide["title"]}}"
        data-subtitle="{{$slide["subtitle"]}}"
        data-url="{{$slide["url"]}}"
        data-img="{{$slide["img"]}}"

        @if ($slide["type"] == "image_and_video_embed")
          data-embed-code="{{ htmlentities($slide["video_embed_code"]) }}"
        @endif

        style="color: {{$slide["elements_color"]}}"
      >

        <div class="flo-generic-slides__slide-content {{$b}}__slide-content">
          {{-- START: SLIDE IMAGES --}}
            @if ($image_type == "bgi")
              <div class="flo-generic-slides__slide-img flo-generic-slides__slide-img--bgi {{$b}}__slide-img {{$b}}__slide-img--bgi" aria-label="{{$alt_text}}" style="background-image: url({{$slide["img"]}})"></div>
            @elseif ($image_type == "img")
               <img class="flo-generic-slides__slide-img flo-generic-slides__slide-img--img {{$b}}__slide-img {{$b}}__slide-img--img" alt="{{$alt_text}}" src="{{$slide["img"]}}" srcset="{{ esc_attr($slide['image_srcset']) }}">

            @endif
          {{-- END: SLIDE IMAGES --}}

          {{-- START: VIDEO BACKGROUND --}}
            @if ($slide["type"] == "video_slide")
              <div class="
                flo-generic-slides__slide-background-video
                flo-generic-slides__slide-background-video--{{ $slide["object"]["slide_video_size"] }}
                {{$b}}__slide-background-video
                {{$b}}__slide-background-video--{{ $slide["object"]["slide_video_size"] }}
              ">
                <video muted playsinline loop autoplay>
                  <source src="{{ $slide["video_url"] }}" type="video/mp4">
                </video>
              </div>
            @endif
          {{-- END: VIDEO BACKGROUND --}}

          {{-- START: CONTENT SECTION TO BE EXTENDED --}}
            @yield('slide_content')
          {{-- END: CONTENT SECTION TO BE EXTENDED --}}
        </div>

        {{-- START: VIDEO BUTTON --}}
          @if ($slide["type"] == "image_and_video_embed")
            @include('components.flo-hero-video-embed')
          @endif
        {{-- END: VIDEO BUTTON --}}

      </div>
    @endforeach

  </div>
@endif
