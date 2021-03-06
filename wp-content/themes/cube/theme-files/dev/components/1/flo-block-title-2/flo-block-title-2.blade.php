<?php
$b = "flo-block-title-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");
$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$side_text = flo_data($data, "side_text");
$side_text_font = flo_data($data, "side_text_font");
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
    $b__uniq_for_css." ".$b__for_css."__side-text",
    $side_text_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__side-text
    {
      color: ".$elements_color."!important;
    }

  "
])
<div class="{{$b}} {{$b__uniq}}">
  <h1 class="{{$b}}__title">
    {{$title}}
  </h1>
  @if ($side_text)
    <div class="{{$b}}__side-text">
      {{$side_text}}
    </div>
  @endif
</div>
