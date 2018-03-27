<?php
$b = "flo-block-information-block-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$background_color = flo_data($data, "background_color");
$elements_color = flo_data($data, "elements_color");
$text_areas = flo_data($data, "text_areas");
$text_area_title_font = flo_data($data, "text_area_title_font");
$text_area_text_font = flo_data($data, "text_area_text_font");
$text_area_link_font = flo_data($data, "text_area_link_font");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__text-area-title",
    $text_area_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__text-area-text",
    $text_area_text_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__link",
    $text_area_link_font
    )
    ."

    ".$b__uniq_for_css." {
      color: ".$elements_color.";
      background-color: ".$background_color.";
    }

  "
])
<div class="{{$b}} {{$b__uniq}}">
  <div class="{{$b}}__text-areas">
    @foreach ($text_areas as $text_area)
      <?php
        $title = flo_data($text_area, "title");
        $text = flo_data($text_area, "text");
        $link_label = flo_data($text_area, "link_label");
        $link_url = flo_data($text_area, "link_url");
      ?>
      <div class="{{$b}}__text-area">
        <h3 class="{{$b}}__text-area-title">
          {{$title}}
        </h3>
        <div class="{{$b}}__text-area-text">
          {{$text}}
        </div>
        @if ($link_label)
          <a class="{{$b}}__link" href="{{$link_url}}">
            {{$link_label}}
          </a>
        @endif
      </div>
    @endforeach
  </div>

</div>
