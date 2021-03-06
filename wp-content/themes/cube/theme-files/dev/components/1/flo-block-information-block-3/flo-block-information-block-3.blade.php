<?php
$b = "flo-block-information-block-3"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");

$pretitle = flo_data($data, "pretitle");
$pretitle_font = flo_data($data, "pretitle_font");
$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$text = flo_data($data, "text");
$text_font = flo_data($data, "text_font");
$bottom_link_title = flo_data($data, "bottom_link_title");
$bottom_link_font = flo_data($data, "bottom_link_font");
$bottom_link_url = flo_data($data, "bottom_link_url");
$bottom_left_image = flo_data($data, "bottom_left_image");

$slides = flo_data($data, "slides");
$elements_on_slides_color = flo_data($data, "elements_on_slides_color");
$slide_title_font = flo_data($data, "slide_title_font");
$display_inner_stroke = flo_data($data, "display_inner_stroke");
$display_inner_stroke_class = $display_inner_stroke ? $b . "--display-image-stroke" : "";
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__pretitle",
    $pretitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__title",
    $title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__text",
    $text_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__bottom-link",
    $bottom_link_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__slide-title",
    $slide_title_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__pretitle,
    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__text,
    ".$b__uniq_for_css." ".$b__for_css."__bottom-link,
    ".$b__uniq_for_css." ".$b__for_css."__slide-title
    {
      color: ".$elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__slide-image
    {
      color: ".$elements_on_slides_color."!important;
    }

    ".$b__uniq_for_css." .slick-dots li
    {
      border-color: ".$elements_on_slides_color."!important;
      background-color: ".$elements_on_slides_color."!important;
    }

  ",
  "breakpoint__small_only" => "
    ".$b__uniq_for_css." ".$b__for_css."__slide-title
    {
      color: ".$elements_on_slides_color."!important;
    }
  "
])
<div class="{{$b}} {{$b__uniq}} {{$display_inner_stroke_class}}" data-onready="flo_block_information_block_3">
  <div class="{{$b}}__left-side">
    <div class="{{$b}}__text-area">
      @if ($pretitle)
        <h4 class="{{$b}}__pretitle">
          {{$pretitle}}
        </h4>
      @endif

      @if ($title)
        <h3 class="{{$b}}__title">
          {{$title}}
        </h3>
      @endif

      @if ($text)
        <div class="{{$b}}__text">
          {{$text}}
        </div>
      @endif

      @if ($bottom_link_title)
        <a class="{{$b}}__bottom-link" href="{{$bottom_link_url}}">
          {{$bottom_link_title}}
        </a>
      @endif
    </div>

    @if ($bottom_left_image)
      <div class="{{$b}}__bottom-left-image" style='background-image: url({{$bottom_left_image["url"]}})'></div>
    @endif
  </div>

  <div class="{{$b}}__right-side">
    <div class="{{$b}}__slideshow">
      @foreach ($slides as $slide)
        <?php
          $image_title = flo_data($slide, "image_title");
          $image = flo_data($slide, "image");
          $image_url = flo_data($image, "url");
        ?>
        <div class="{{$b}}__slide">
          <div class="{{$b}}__slide-wrap">
            @if ($image_title)
              <div class="{{$b}}__slide-title">
                {{$image_title}}
              </div>
            @endif
            <div class="{{$b}}__slide-image" style='background-image: url({{$image_url}})'></div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>
