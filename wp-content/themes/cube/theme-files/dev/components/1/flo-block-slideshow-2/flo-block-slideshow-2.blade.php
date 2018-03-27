<?php
$b = "flo-block-slideshow-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$slideshow_id = flo_data($data, "slideshow_id");
$slideshow_title_font = flo_data($data, "slideshow_title_font");
$slideshow_subtitle_font = flo_data($data, "slideshow_subtitle_font");
$button_label = flo_data($data, "button_label");
$button_font = flo_data($data, "button_font");
$slideshow_height = flo_data($data, "slideshow_height", 600) / 16 . "rem";

?>
@include('components.flo-generic-slides-data')
<?php
  $slideshow_data = flo_get_slideshow_data($slideshow_id);
  $slides = Array();
  if ($slideshow_data && isset($slideshow_data["slides"])) {
    $slides = $slideshow_data["slides"];
  }
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__title",
    $slideshow_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__subtitle",
    $slideshow_subtitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__button",
    $button_font
    )
    ."

  ",
  "breakpoint__medium_up" => "
    ".$b__uniq_for_css." ".$b__for_css."__slide-image {
      height: ".$slideshow_height.";
    }
  "
])
<div class="{{$b}} {{$b__uniq}}" data-onready="flo_block_slideshow_2">
  <div
    class="{{$b}}__slides"
    data-slick='{
      "speed": {{ $slideshow_data["transition_speed"] }},
      "fade": {{ $slideshow_data["fade"] }},
      "autoplay": {{ $slideshow_data["autoplay"] }},
      "autoplaySpeed": {{ $slideshow_data["autoplay_speed"] }},
      "pauseOnHover": {{ $slideshow_data["pause_on_hover"] }},
      "fade": false,
      "": ""
    }'
    data-autoplay="{{ $slideshow_data['autoplay'] }}"
  >
    @foreach ($slides as $slide)
      <div
        class="{{$b}}__slide {{$b}}__slide--{{$slide["type"]}}""
        data-elements-color="{{$slide["elements_color"]}}"
        @if ($slide["type"] == "image_and_video_embed")
          data-embed-code="{{ htmlentities($slide["video_embed_code"]) }}"
        @endif
      >
        <div class="{{$b}}__slide-content" style='color: {{$slide["elements_color"]}}'>
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

            <img class="{{$b}}__slide-image" src="{{$slide["img"]}}">
          {{-- END: VIDEO BACKGROUND --}}

          <div class="{{$b}}__title-area">
            @if ($slide["title"])
              <h3 class="{{$b}}__title">
                {{$slide["title"]}}
              </h3>
            @endif
            @if ($slide["subtitle"])
              <h5 class="{{$b}}__subtitle">
                {{$slide["subtitle"]}}
              </h5>
            @endif
          </div>
          @if ($button_label && $slide["url"])
            <a class="{{$b}}__button" href="{{$slide["url"]}}">
              {{$button_label}}
            </a>
          @endif
          @if ($slide["type"] == "image_and_video_embed")
            @include('components.flo-hero-video-embed')
          @endif
        </div>

      </div>
    @endforeach
  </div>
  <div class="{{$b}}__arrow {{$b}}__arrow--prev">
    <i class="flo-icon-arrow-left"></i>
  </div>
  <div class="{{$b}}__arrow {{$b}}__arrow--next">
    <i class="flo-icon-arrow-right"></i>
  </div>
</div>
