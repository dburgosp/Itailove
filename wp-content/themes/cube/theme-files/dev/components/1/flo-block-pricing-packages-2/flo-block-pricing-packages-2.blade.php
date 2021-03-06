<?php
$b = "flo-block-pricing-packages-2"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$title_area_background_color = flo_data($data, "title_area_background_color");
$title_area_elements_color = flo_data($data, "title_area_elements_color");
$title = flo_data($data, "title");
$title_font = flo_data($data, "title_font");
$subtitle = flo_data($data, "subtitle");
$subtitle_font = flo_data($data, "subtitle_font");
$side_link_label = flo_data($data, "side_link_label");
$side_link_font = flo_data($data, "side_link_font");
$side_link_url = flo_data($data, "side_link_url");

$packages = flo_data($data, "packages");
$package_label_on_image_font = flo_data($data, "package_label_on_image_font");
$packages_bottom_elements_color = flo_data($data, "packages_bottom_elements_color");
$package_pretitle_font = flo_data($data, "package_pretitle_font");
$package_title_font = flo_data($data, "package_title_font");
$package_price_font = flo_data($data, "package_price_font");
$package_price_color = flo_data($data, "package_price_color");
$package_price_background_color = flo_data($data, "package_price_background_color");
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
    $b__uniq_for_css." ".$b__for_css."__subtitle",
    $subtitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__side-link",
    $side_link_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__package-image",
    $package_label_on_image_font
    )
    ."
    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__package-pretitle",
    $package_pretitle_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__package-title",
    $package_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__package-price",
    $package_price_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__wrap:before {
      background-color: ".$title_area_background_color.";
    }

    ".$b__uniq_for_css." ".$b__for_css."__pretitle,
    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__side-link
    {
      color: ".$title_area_elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__package-pretitle,
    ".$b__uniq_for_css." ".$b__for_css."__package-title
    {
      color: ".$packages_bottom_elements_color."!important;
    }

    ".$b__uniq_for_css." ".$b__for_css."__package-price {
      background-color: ".$package_price_background_color.";
      color: ".$package_price_color."!important;
    }

  ",
  "breakpoint__small_only" => "
    ".$b__uniq_for_css." ".$b__for_css."__title-area-wrap:before {
      background-color: ".$title_area_background_color.";
    }
  "
])
<div class="{{$b}} {{$b__uniq}}">
  <div class="{{$b}}__wrap">
    <div class="{{$b}}__title-area-wrap">
      <div class="{{$b}}__title-area-side-wrap"></div>

      <div class="{{$b}}__title-area">
        <h3 class="{{$b}}__title">
          {{$title}}
        </h3>
        @if ($subtitle)
          <h5 class="{{$b}}__subtitle">
            {{$subtitle}}
          </h5>
        @endif
      </div>

      <div class="{{$b}}__title-area-side-wrap {{$b}}__title-area-side-wrap--left">
        @if ($side_link_label)
          <a class="{{$b}}__side-link" href="{{$side_link_url}}">
            {{$side_link_label}}
          </a>
        @endif
      </div>
    </div>

    <div class="{{$b}}__packages">
      @foreach ($packages as $package)
        <?php
          $package_image = flo_data($package, "image");
          $package_image_url = flo_data($package_image, "url");
          $package_elements_on_image_color = flo_data($package, "elements_on_image_color");
          $package_label_on_image = flo_data($package, "label_on_image");
          $package_pretitle = flo_data($package, "pretitle");
          $package_title = flo_data($package, "title");
          $package_price = flo_data($package, "price");
        ?>
        @if ($package_image)
          <div class="{{$b}}__package">
            <div class="{{$b}}__package-image" style="color: {{$package_elements_on_image_color}}; background-image: url({{$package_image_url}})">
              <div class="{{$b}}__package-image-border" style="border-color: {{$package_elements_on_image_color}}"></div>
              {{$package_label_on_image}}
            </div>
            <div class="{{$b}}__package-bottom-area">
              @if ($package_pretitle)
                <h4 class="{{$b}}__package-pretitle">
                  {{$package_pretitle}}
                </h4>
              @endif
              @if ($package_title)
                <h3 class="{{$b}}__package-title">
                  {{$package_title}}
                </h3>
              @endif
              @if ($package_price)
                <div class="{{$b}}__package-price">
                  {{$package_price}}
                </div>
              @endif
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </div>
</div>
