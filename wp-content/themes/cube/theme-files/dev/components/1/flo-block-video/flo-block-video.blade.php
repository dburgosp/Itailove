<?php
$b = "flo-block-video"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");
$video_code = flo_data($data, "video_code");
$video_cover_image = flo_data($data, "video_cover_image");
$video_height = flo_data($data, "video_height");
$video_height_in_px = flo_data($data, "video_height_in_px", 500) / 16 . "rem";
$video_height_desktop = $video_height == "full" ? "100vh" : $video_height_in_px;
$play_button_icon_color = flo_data($data, "play_button_icon_color");
$bottom_label = flo_data($data, "bottom_label");
$bottom_label_font = flo_data($data, "bottom_label_font");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__bottom-label",
    $bottom_label_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__bottom-label{
      color: ".$elements_color.";
    }

    ".$b__uniq_for_css." .flo-video-embed__video-button {
      background-color: ".$elements_color.";
      color: ".$play_button_icon_color.";
    }
  ",
  "breakpoint__medium_up" => "
    ".$b__uniq_for_css." ".$b__for_css."__video {
      height: ".$video_height_desktop.";
    }
  "
])
<div class="{{$b}} {{$b__uniq}}" data-elements-color="{{$elements_color}}" data-onready="flo_block_video">
  <div class="{{$b}}__video">
    <div class="{{$b}}__video-cover-image" style='background-image: url({{$video_cover_image["url"]}})'></div>
    <div class="{{$b}}__bottom-label">
      {{$bottom_label}}
    </div>
    @include('components.flo-video-embed', [
      "embed_code" => $video_code
    ])
  </div>
</div>
