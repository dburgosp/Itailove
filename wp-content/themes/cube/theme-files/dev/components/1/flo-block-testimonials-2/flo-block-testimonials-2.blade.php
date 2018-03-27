<?php
$b = "flo-block-testimonials-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$elements_color = flo_data($data, "elements_color");
$testimonials = flo_data($data, "testimonials");
$testimonial_title_font = flo_data($data, "testimonial_title_font");
$testimonial_text_font = flo_data($data, "testimonial_text_font");
$controls_label = flo_data($data, "controls_label");
$controls_label_font = flo_data($data, "controls_label_font");

$pretitle = flo_data($data, "pretitle");
$pretitle_font = flo_data($data, "pretitle_font");
$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$title_area_background_color = flo_data($data, "title_area_background_color");
?>
@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__testimonial-title",
    $testimonial_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__testimonial-text",
    $testimonial_text_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__controls-label",
    $controls_label_font
    )
    ."

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

    ".$b__uniq_for_css." ".$b__for_css."__testimonial-title,
    ".$b__uniq_for_css." ".$b__for_css."__testimonial-text,
    ".$b__uniq_for_css." ".$b__for_css."__controls-label,
    ".$b__uniq_for_css." ".$b__for_css."__pretitle,
    ".$b__uniq_for_css." ".$b__for_css."__title
    {
      color: ".$elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__testimonial-image-background {
      background-color: ".$title_area_background_color.";
    }

  "
])
<div class="{{$b}} {{$b__uniq}}" data-onready="flo_block_testimonials_2">

  <div class="{{$b}}__testimonials">
    @foreach ($testimonials as $testimonial)
      <?php
        $testimonial_image = flo_data($testimonial, "image");
        $testimonial_image_url = flo_data($testimonial_image, "url");
        $testimonial_author_avatar = flo_data($testimonial, "author_avatar");
        $testimonial_title = flo_data($testimonial, "title");
        $testimonial_text = flo_data($testimonial, "text");
      ?>
      <div class="{{$b}}__testimonial">
        <div class="{{$b}}__testimonial-content">


          <div class="{{$b}}__testimonial-image-wrap">
            <div class="{{$b}}__testimonial-image-background"></div>
            <div class="{{$b}}__testimonial-image" style='background-image: url({{$testimonial_image_url}})'></div>
          </div>
          <div class="{{$b}}__testimonial-right-wrap">
            <div class="{{$b}}__testimonial-title-area">
              @if ($testimonial_author_avatar)
                <div class="{{$b}}__testimonial-avatar" style='background-image: url({{$testimonial_author_avatar["url"]}})'></div>
              @else
                <div class="{{$b}}__testimonial-avatar"></div>
              @endif
              <h4 class="{{$b}}__testimonial-title">
                {{$testimonial_title}}
              </h4>
            </div>
            <div class="{{$b}}__testimonial-text">
              {{$testimonial_text}}
            </div>
          </div>

        </div>
      </div>
    @endforeach
  </div>

  <div class="{{$b}}__controls">
    <div class="{{$b}}__controls-arrow {{$b}}__controls-arrow--prev">
      <i class="flo-icon-arrow-small-left"></i>
    </div>
    @if ($controls_label)
      <div class="{{$b}}__controls-label">
        {{$controls_label}}
      </div>
    @endif
    <div class="{{$b}}__controls-arrow {{$b}}__controls-arrow--next">
      <i class="flo-icon-arrow-small-right"></i>
    </div>
  </div>

  <div class="{{$b}}__title-area">
    @if ($pretitle)
      <h5 class="{{$b}}__pretitle">
        {{$pretitle}}
      </h5>
    @endif
    <h3 class="{{$b}}__title">
      {{$title}}
    </h3>
  </div>

</div>
