<?php
$b = "flo-block-image-links-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");

$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");

$links = flo_data($data, "links");
$link_overlay_color = flo_data($data, "link_overlay_color");
$link_overlay_color = hex2rgba($link_overlay_color, 0.8);
$link_overlay_text_color = flo_data($data, "link_overlay_text_color");
$link_title_font = flo_data($data, "link_title_font");
$link_subtitle_font = flo_data($data, "link_subtitle_font");
$link_bottom_button_label = flo_data($data, "link_bottom_button_label");
$link_bottom_button_font = flo_data($data, "link_bottom_button_font");
$link_bottom_button_background_color = flo_data($data, "link_bottom_button_background_color");
$link_bottom_button_text_color = flo_data($data, "link_bottom_button_text_color");

$display_bottom_link = flo_data($data, "display_bottom_link");
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
    $b__uniq_for_css." ".$b__for_css."__link-title",
    $link_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__link-subtitle",
    $link_subtitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__link-bottom-button",
    $link_bottom_button_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__bottom-link",
    $bottom_link_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__bottom-link
    {
      color: ".$elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__link-overlay {
      background-color: ".$link_overlay_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__link-title,
    ".$b__uniq_for_css." ".$b__for_css."__link-subtitle
    {
      color: ".$link_overlay_text_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__link-bottom-button {
      color: ".$link_bottom_button_text_color."!important;
      background-color: ".$link_bottom_button_background_color.";
    }

  "
])
<div class="{{$b}} {{$b__uniq}}">
  @if ($title)
    <h4 class="{{$b}}__title">
      {{$title}}
    </h4>
  @endif

  <div class="{{$b}}__links">
    @foreach ($links as $link)
      <?php
        $link_image = flo_data($link, "image");
        $link_image_url = $link_image ? $link_image["url"] : "";
        $link_title = flo_data($link, "title");
        $link_subtitle = flo_data($link, "subtitle");
        $link_url = flo_data($link, "url");
      ?>
      <a class="{{$b}}__link" href="{{$link_url}}" style='background-image: url({{$link_image_url}})'>
        <span class="{{$b}}__link-overlay">
          @if ($link_title)
            <span class="{{$b}}__link-title">
              {{$link_title}}
            </span>
          @endif
          @if ($link_subtitle)
            <span class="{{$b}}__link-subtitle">
              {{$link_subtitle}}
            </span>
          @endif
          @if ($link_bottom_button_label)
            <span class="{{$b}}__link-bottom-button">
              {{$link_bottom_button_label}}
            </span>
          @endif
        </span>
      </a>
    @endforeach
  </div>

  @if ($display_bottom_link)
    <a class="{{$b}}__bottom-link" href="{{$bottom_link_url}}">
      {{$bottom_link_label}}
    </a>
  @endif
</div>
