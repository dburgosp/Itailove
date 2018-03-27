<?php
$b = "flo-block-image-links"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$links = flo_data($data, "links");
$columns = count($links);
$columns_class = $b . "--columns-" . $columns;
$links_font = flo_data($data, "links_font");
$links_hover_overlay_color = flo_data($data, "links_hover_overlay_color");
$links_hover_overlay_text_color = flo_data($data, "links_hover_overlay_text_color");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__link-text",
    $links_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__link:hover ".$b__for_css."__link-overlay {
      background-color: ".$links_hover_overlay_color.";
      color: ".$links_hover_overlay_text_color."!important;
    }

  "
])
<div class="{{$b}} {{$b__uniq}} {{$columns_class}}" data-onready="flo_block_image_links">
  @foreach ($links as $link)
    <?php
      $image = flo_data($link, "image");
      $image_url = $image ? $image["url"] : "";
      $text = flo_data($link, "text");
      $text_color = flo_data($link, "text_color");
      $url = flo_data($link, "url");
    ?>
    <a class="{{$b}}__link" href="{{$url}}" style="color: {{$text_color}}">
      <span class="{{$b}}__link-image" style='background-image: url({{$image_url}})'></span>
      @if ($text)
        <span class="{{$b}}__link-overlay">
          <span class="{{$b}}__link-text">
            {{$text}}
          </span>
        </span>
      @endif
    </a>
  @endforeach
</div>
