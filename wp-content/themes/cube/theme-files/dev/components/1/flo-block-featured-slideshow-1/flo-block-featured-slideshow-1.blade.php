<?php
$b = "flo-block-featured-slideshow-1"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$slides = flo_data($data, "slides");
$left_side_area_background_color = flo_data($data, "left_side_area_background_color");
$left_side_area_elements_color = flo_data($data, "left_side_area_elements_color");
$display_image_inner_stroke = flo_data($data, "display_image_inner_stroke");
$image_inner_stroke_color = $display_image_inner_stroke ? $b . "--image-inner-stroke-visible" : "";
$image_inner_stroke_color = flo_data($data, "image_inner_stroke_color");
$image_label_font = flo_data($data, "image_label_font");

$right_side_background_color = flo_data($data, "right_side_background_color");
$right_side_elements_color = flo_data($data, "right_side_elements_color");
$slide_title_font = flo_data($data, "slide_title_font");
$slide_text_font = flo_data($data, "slide_text_font");
$display_counter = flo_data($data, "display_counter");
$counter_font = flo_data($data, "counter_font");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__image-label",
    $image_label_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__text-slide-title",
    $slide_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__text-slide-text",
    $slide_text_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__counter",
    $counter_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__left-side {
      background-color: ".$left_side_area_background_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__image:before {
      border-color: ".$image_inner_stroke_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__image-label {
      color: ".$left_side_area_elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__right-side {
      background-color: ".$right_side_background_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__slide-title,
    ".$b__uniq_for_css." ".$b__for_css."__slide-text,
    ".$b__uniq_for_css." ".$b__for_css."__counter,
    ".$b__uniq_for_css." ".$b__for_css."__arrow
    {
      color: ".$right_side_elements_color."!important;
    }

  ",
  "breakpoint__small_only" => "
    ".$b__uniq_for_css." ".$b__for_css."__image-label {
      color: ".$image_inner_stroke_color."!important;
    }
  "
])
<div class="{{$b}} {{$b__uniq}} {{$display_image_inner_stroke}}" data-onready="flo_block_featured_slideshow_1">

  <div class="{{$b}}__left-side">
    <div class="{{$b}}__image-slides">
      @foreach ($slides as $slide)
        <div class="{{$b}}__image-slide">
          <div class="{{$b}}__image" style='background-image: url({{$slide["image"]["url"]}})'>
            <h5 class="{{$b}}__image-label">
              {{$slide["image_label"]}}
            </h5>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="{{$b}}__right-side">

    <div class="{{$b}}__text-slides">
      @foreach ($slides as $slide)
        <div class="{{$b}}__text-slide">
          <div class="{{$b}}__text-slide-wrap">
            @if ($slide["title"])
              <h3 class="{{$b}}__text-slide-title">
                {{$slide["title"]}}
              </h3>
            @endif
            @if ($slide["text"])
              <div class="{{$b}}__text-slide-text">
                {{$slide["text"]}}
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    <div class="{{$b}}__arrows-and-counter">
      <div class="{{$b}}__arrow {{$b}}__arrow--prev">
        <i class="flo-icon-arrow-small-left"></i>
      </div>
      @if ($display_counter)
        <div class="{{$b}}__counter">
          <div class="{{$b}}__counter-index">
            02
          </div>
          <div class="{{$b}}__counter-separator">
            /
          </div>
          <div class="{{$b}}__counter-count">
            09
          </div>
        </div>
      @endif
      <div class="{{$b}}__arrow {{$b}}__arrow--next">
        <i class="flo-icon-arrow-small-right"></i>
      </div>
    </div>

  </div>

</div>