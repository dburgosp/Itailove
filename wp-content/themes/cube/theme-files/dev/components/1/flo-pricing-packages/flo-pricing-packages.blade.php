<?php
  $block_class = "flo-pricing-packages--" . uniqid();

  // we need to decrease the height for 4 columns and we will use a special class for that
  if(sizeof($data["flo-cube-pricing-packages__packages"]) ==  4){
    $four_cols_height = 'flo-pricing-packages__package-image-4-cols';
  }else{
    $four_cols_height = '';
  }
?>

@include('core.style', [
  "breakpoint__general" => "

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-pricing-packages__package-title",
        $data['flo-cube-pricing-packages__packages-title-font']
      )
    ."

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-pricing-packages__package-subtitle",
        $data['flo-cube-pricing-packages__packages-subtitle-font']
      )
    ."

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-pricing-packages__package-description",
        $data['flo-cube-pricing-packages__packages-description-font']
      )
    ."

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-pricing-packages__package-price",
        $data['flo-cube-pricing-packages__packages-price-font']
      )
    ."

  "
])

<div class="flo-section">
  <div class="flo-section__content">
    <div class="flo-pricing-packages {{ $block_class }}">

      <div class="flo-pricing-packages__packages">

        @foreach ($data["flo-cube-pricing-packages__packages"] as $package)
          <?php 
            if(isset($package['description_bg_color']) && strlen($package['description_bg_color']) ){
              $bg_color_style = 'background-color: '.$package['description_bg_color'].'; padding-left: 20px; padding-right: 20px; ';
            }else{
              $bg_color_style = '';
            }

            if(isset($package['description_color']) && strlen($package['description_color']) ){
              $color_style = 'color: '.$package['description_color'].'; ';
            }else{
              $color_style = '';
            }
          ?>
          <div class="flo-pricing-packages__package to-appear">
            <div class="flo-pricing-packages__package-image {{$four_cols_height}}" style="background-image: url({{ $package["image"]}});">
              <div class="flo-pricing-packages__package-title" style="color: {{ $package["image_text_color"] }}">
                {{ $package["title"] }}
              </div>
              <div class="flo-pricing-packages__package-subtitle" style="color: {{ $package["image_text_color"] }}">
                {{ $package["subtitle"] }}
              </div>
            </div>

            <div class="flo-pricing-packages__package-description" style="{{$bg_color_style}} {{$color_style}}">
              {{ $package["description"] }}
            </div>

            <div class="flo-pricing-packages__package-price" style="{{$bg_color_style}} {{$color_style}}">
              {{ $package["price"] }}
            </div>
          </div>
        @endforeach

      </div>

      @if ($data["flo-cube-pricing-packages__button-display"])
        <a href="{{ $data["flo-cube-pricing-packages__button-url"] }}" class="flo-pricing-packages__button flo-btn to-appear">
          {{ $data["flo-cube-pricing-packages__button-label"] }}
        </a>
      @endif

    </div>
  </div>
</div>
