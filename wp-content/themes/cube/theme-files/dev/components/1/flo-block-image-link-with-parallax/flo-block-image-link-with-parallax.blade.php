<?php
$b = "flo-block-image-link-with-parallax"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$image_link = flo_data($data, "url");
$image = flo_data($data, "image");
$image_url = $image ? $image["url"] : "";
$image_height = flo_data($data, "image_height", 520) / 16 . "rem";
$enable_parallax_effect = flo_data($data, "enable_parallax_effect");
$enable_parallax_effect_class = $enable_parallax_effect ? $b . "--parallax-enabled" : "";

$overlay_background_color = flo_data($data, "overlay_background_color");
$overlay_elements_color = flo_data($data, "overlay_elements_color");
$overlay_opacity = flo_data($data, "overlay_opacity", 80) / 100;
$overlay_background_color = hex2rgba($overlay_background_color, $overlay_opacity);

$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$subtitle = flo_data($data, "subtitle");
$subtitle_font = flo_data($data, "subtitle_font");

$button_label = flo_data($data, "button_label");
$button_font = flo_data($data, "button_font");
$button_background_color = flo_data($data, "button_background_color");
$button_text_color = flo_data($data, "button_text_color");
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
    $b__uniq_for_css." ".$b__for_css."__button",
    $button_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__overlay {
      background-color: ".$overlay_background_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__subtitle
    {
      color: ".$overlay_elements_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__button {
      color: ".$button_text_color.";
      background-color: ".$button_background_color.";
    }

  ",
  "breakpoint__medium_up" => "
    ".$b__uniq_for_css." {
      height: ".$image_height.";
    }
  "
])
<a href="{{$image_link}}" class="{{$b}} {{$b__uniq}} {{$enable_parallax_effect_class}}" style='background-image: url({{$image_url}})'>
  <span class="{{$b}}__overlay">
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

    @if ($button_label)
      <span class="{{$b}}__button">
        {{$button_label}}
      </span>
    @endif
  </span>
</a>
