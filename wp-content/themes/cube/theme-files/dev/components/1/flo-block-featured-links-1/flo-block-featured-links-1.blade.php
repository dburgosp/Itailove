<?php
$b = "flo-block-featured-links-1"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");

$image = flo_data($data, "image");
$image_url = flo_data($image, "url");
$display_image_inner_stroke = flo_data($data, "display_image_inner_stroke");
$display_image_inner_stroke_class = $display_image_inner_stroke ? $b . "--image-inner-stroke-visible" : "";
$image_inner_stroke_color = flo_data($data, "image_inner_stroke_color");

$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$subtitle = flo_data($data, "subtitle");
$subtitle_font = flo_data($data, "subtitle_font");
$links = flo_data($data, "links");
$link_font = flo_data($data, "link_font");
$bottom_link_label = flo_data($data, "bottom_link_label");
$bottom_link_font = flo_data($data, "bottom_link_font");
$bottom_link_url = flo_data($data, "bottom_link_url");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__title",
    $title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__subtitle",
    $subtitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__link",
    $link_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__bottom-link",
    $bottom_link_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__image:before
    {
      border-color: ".$image_inner_stroke_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__subtitle,
    ".$b__uniq_for_css." ".$b__for_css."__link,
    ".$b__uniq_for_css." ".$b__for_css."__bottom-link
    {
      color: ".$elements_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__link
    {
      border-color: ".hex2rgba($elements_color, 0.07).";
    }

    ".$b__uniq_for_css." ".$b__for_css."__link:hover
    {
      border-color: ".hex2rgba($elements_color, 1).";
    }

  "
])
<div class="{{$b}} {{$b__uniq}} {{$display_image_inner_stroke_class}}">
  @if ($image)
    <div class="{{$b}}__image" style='background-image: url({{$image_url}})'></div>
  @endif

  <div class="{{$b}}__right-wrap">
    @if ($subtitle || $title)
      <div class="{{$b}}__title-area">
        @if ($title)
          <h3 class="{{$b}}__title">
            {{$title}}
          </h3>
        @endif
        @if ($subtitle)
          <h5 class="{{$b}}__subtitle">
            {{$subtitle}}
          </h5>
        @endif
      </div>
    @endif

    <div class="{{$b}}__link-list">
      @foreach ($links as $link)
        <a class="{{$b}}__link" href="{{$link["url"]}}">{{$link["title"]}}</a>
      @endforeach
    </div>

    @if ($bottom_link_label)
      <a class="{{$b}}__bottom-link" href="{{$bottom_link_url}}">
        {{$bottom_link_label}}
      </a>
    @endif
  </div>
</div>
