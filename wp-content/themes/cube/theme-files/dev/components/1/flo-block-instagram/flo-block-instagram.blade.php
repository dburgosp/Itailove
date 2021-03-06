<?php
$b = "flo-block-instagram"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");
$pretitle = flo_data($data, "pretitle");
$pretitle_font = flo_data($data, "pretitle_font");
$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$side_link_label = flo_data($data, "side_link_label");
$side_link_label_font = flo_data($data, "side_link_label_font");
$side_link_url = flo_data($data, "side_link_url");
$images_source = flo_data($data, "images_source");
$images = flo_data($data, "images");
$images_background = flo_data($data, "images_background");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__pretitle",
    $pretitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__title",
    $title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__side-link",
    $side_link_label_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__preitlte,
    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__side-link
    {
      color: ".$elements_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__images
    {
      background-color: ".$images_background.";
    }
  "
])
<div class="{{$b}} {{$b__uniq}}">
  <div class="{{$b}}__title-area">
    <div class="{{$b}}__title-wrap">
      <h5 class="{{$b}}__pretitle">
        {{$pretitle}}
      </h5>
      <h2 class="{{$b}}__title">
        {{$title}}
      </h2>
    </div>
    <a class="{{$b}}__side-link" href="{{$side_link_url}}">
      {{$side_link_label}}
    </a>
  </div>

  <div class="{{$b}}__images">
    @if ($images_source == "plugin")
      <?php echo do_shortcode('[flo_instagram padding="0" use_pattern="" crop="0" nr_columns="4" limit="4" picture_sizes="320x320_crop" link="1" ]'); ?>
    @elseif ($images_source == "images")
      @foreach ($images as $image)
        <?php
          $image = flo_data($image, "image");
          $image_url = flo_data($image, "url");
        ?>
        <div class="{{$b}}__image" style='background-image: url({{$image_url}})'></div>
      @endforeach
    @endif
  </div>
</div>
