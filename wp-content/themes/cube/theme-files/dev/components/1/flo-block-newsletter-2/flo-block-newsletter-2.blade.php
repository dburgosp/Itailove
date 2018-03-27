<?php
$b = "flo-block-newsletter-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$background_color = flo_data($data, "background_color");
$elements_color = flo_data($data, "elements_color");

$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");

$left_side_link_image = flo_data($data, "left_side_link_image");
$left_side_link_title = flo_data($data, "left_side_link_title");
$left_side_link_title_font = flo_data($data, "left_side_link_title_font");
$left_side_link_title_color = flo_data($data, "left_side_link_title_color");
$left_side_link_url = flo_data($data, "left_side_link_url");

$form_background_color = flo_data($data, "form_background_color");
$form_elements_color = flo_data($data, "form_elements_color");
$form_title = flo_data($data, "form_title");
$form_title_font = flo_data($data, "form_title_font");
$form_fields_font = flo_data($data, "form_fields_font");
$form_submit_button_label = flo_data($data, "form_submit_button_label");
$form_submit_button_label_font = flo_data($data, "form_submit_button_label_font");

$right_side_link_image = flo_data($data, "right_side_link_image");
$right_side_link_title = flo_data($data, "right_side_link_title");
$right_side_link_title_font = flo_data($data, "right_side_link_title_font");
$right_side_link_title_color = flo_data($data, "right_side_link_title_color");
$right_side_link_url = flo_data($data, "right_side_link_url");
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
    $b__uniq_for_css." ".$b__for_css."__link--left",
    $left_side_link_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__form-title",
    $form_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__field",
    $form_fields_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__submit",
    $form_submit_button_label_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__link--right",
    $right_side_link_title_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__wrap {
      background-color: ".$background_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__title {
      color: ".$elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__link--left {
      color: ".$left_side_link_title_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__form {
      background-color: ".$form_background_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__form-title,
    ".$b__uniq_for_css." ".$b__for_css."__field,
    ".$b__uniq_for_css." ".$b__for_css."__submit
    {
      color: ".$form_elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__link--right {
      color: ".$left_side_link_title_color."!important;
    }
  "
])
<div class="{{$b}} {{$b__uniq}}">
  <div class="{{$b}}__wrap">
    @if ($title)
      <h3 class="{{$b}}__title">
        {{$title}}
      </h3>
    @endif
    @if ($left_side_link_image)
      <a class="{{$b}}__link {{$b}}__link--left" href="{{$left_side_link_url}}" style='background-image: url({{$left_side_link_image["url"]}})'>
        {{$left_side_link_title}}
      </a>
    @endif

    <form class="{{$b}}__form flo-form--newsletter" method="post">
      @if ($form_title)
        <h4 class="{{$b}}__form-title">
          {{$form_title}}
        </h4>
      @endif
      <input class="{{$b}}__field" type="text" name="" value="" placeholder="FIRST NAME">
      <input class="{{$b}}__field" type="email" name="EMAIL" placeholder="EMAIL ADDRESS">
      <input class="{{$b}}__submit" type="submit" name="" value="{{$form_submit_button_label}}">
    </form>
    @if( isset($data['mailchimp_code']) && strlen($data['mailchimp_code']) )
        <noscript type="text/template" class="embed_code" data-onready="flo_newsletter_signup">
          {{ addslashes( $data['mailchimp_code'] ) }}
        </noscript>
    @endif
    @if ($right_side_link_image)
      <a class="{{$b}}__link {{$b}}__link--right" href="{{$right_side_link_url}}" style='background-image: url({{$right_side_link_image["url"]}})'>
        {{$right_side_link_title}}
      </a>
    @endif
  </div>
</div>
