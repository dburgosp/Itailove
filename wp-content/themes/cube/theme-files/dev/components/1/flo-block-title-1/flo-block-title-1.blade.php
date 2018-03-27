<?php
$b = "flo-block-title-1"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$color = flo_data($data, "color");
$title = flo_data($data, "title");
$font = flo_data($data, "font");

?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css ." ". $b__for_css ."__title",
    $font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__title {
      color: ".$color.";
    }

  "
])
<div class="{{$b}} {{$b__uniq}}">
  <h1 class="{{$b}}__title">
    {{$title}}
  </h1>
</div>
