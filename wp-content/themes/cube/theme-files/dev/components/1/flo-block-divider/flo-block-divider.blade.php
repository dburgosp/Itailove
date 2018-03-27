<?php
$b = "flo-block-divider"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$display_line = flo_data($data, "display_line");
$line_color = flo_data($data, "line_color");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".$b__uniq_for_css." ".$b__for_css."__line {
      background-color: ".$line_color.";
      height: 1px;
      margin: 5.25rem 0;
    }

  ",
  "breakpoint__small_only" => "
    ".$b__uniq_for_css." ".$b__for_css."__line {
      margin: 2.5rem 0;
    }
  "
])
<div class="{{$b}} {{$b__uniq}}">
  @if ($display_line)
    <div class="{{$b}}__line"></div>
  @endif
</div>
