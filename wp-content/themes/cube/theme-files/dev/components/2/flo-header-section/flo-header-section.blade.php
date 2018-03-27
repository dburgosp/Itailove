{{--

Uses as data:
- $header__slider_id
- $header__slider_logo

- $header__section_title_enabled
- $header__section_count_enabled
- $header__section_scroll_enabled
- $header__section_label

- $header__menu_position

--}}
<?php

$header_logo_display = get_field("custom-header__logo-display");

?>

<header>
    <!-- Start: Flo Header Section -->
    <div class="flo-section flo-header-section flo-header-section--layout-{{ $header__slider_layout }} ">
        @include("components.flo-mobile-menu")
        @include('components.flo-header-mobile')
        @include("components.flo-header")

        <!-- Start: Flo Page hero -->
            <div class="flo-page-hero">
                @if(isset($header__slider_id))
                    <?php
                    $slides = get_field("_post_image_gallery", $header__slider_id);
                    ?>
                    @if($slides)

                        <div class="flo-page-hero__slider">
                            <?php
                            $fade = get_field("slideshow_slide_effect", $header__slider_id) == "fade" ? "true" : "false";
                            $transition_speed = get_field("slideshow_transition_speed", $header__slider_id);
                            $autoplay = get_field("slideshow_autoplay", $header__slider_id) ? "true" : "false";
                            $autoplay_speed = get_field("autoplay_speed", $header__slider_id) * 1000;
                            $pause_on_hover = get_field("slideshow_pause_on_hover", $header__slider_id) ? "true" : "false";
                            ?>
                            <div class="flo-page-hero__slides" data-slick='{ "speed": {{ $transition_speed }}, "fade": {{ $fade }}, "autoplay": {{ $autoplay }}, "autoplaySpeed": {{ $autoplay_speed }}, "pauseOnHover": {{ $pause_on_hover }} }' data-autoplay="{{ $autoplay }}">
                                @foreach($slides as $slide)
                                    <?php
                                      if( isset($slide["slide_image"]['crop_position']) ){
                                        $crop_position = $slide["slide_image"]['crop_position'];
                                        
                                        // the background position is calculated using the following formula:
                                        // y = 1.8x - 40
                                        // http://www.wolframalpha.com/input/?i=interpolate+%5B(22.22,+0),(77.77,100)+%5D
                                        $mobile_crop_position = (1.8*$crop_position - 40).'%';

                                      }else{
                                        $mobile_crop_position = '50';
                                      }

                                      $slide_type = $slide["slide_type"];


                                      switch ($slide_type) {
                                        case 'image':

                                        break;
                                        case 'image_and_video_embed':
                                          $video_embed_code = $slide["slide_video_url"];
                                        break;

                                        case 'video_slide':
                                          $video_url = $slide["slide_video"];
                                        break;

                                        default:
                                        break;
                                      }

                                      if(isset($slide["slide_image"]['alt'])){
                                          $alt_text = $slide["slide_image"]['alt'];
                                      }else{
                                          $alt_text = '';
                                      }

                                      $pretitle = false;
                                      if (isset($slide["slide_info"][0]["pretitle"])) {
                                        $pretitle = $slide["slide_info"][0]["pretitle"];
                                      }
                                      $title = false;
                                      if (isset($slide["slide_info"][0]["title"])) {
                                        $title = $slide["slide_info"][0]["title"];
                                      }
                                      $subtitle = false;
                                      if (isset($slide["slide_info"][0]["subtitle"])) {
                                        $subtitle = $slide["slide_info"][0]["subtitle"];
                                      }

                                      $title_url_start = '';
                                      $title_url_end = '';
                                      if (isset($slide["slide_info"][0]["title_url"]) && strlen($slide["slide_info"][0]["title_url"])) {
                                        $title_url = $slide["slide_info"][0]["title_url"];
                                        $title_url_start = '<a href="'.$title_url.'" target="_blank" >';
                                        $title_url_end = '</a>';
                                      }

                                      $slide__logo_class = $header_logo_display ? "flo-page-hero__slide--has-logo" : "";

                                      $block_class = "flo-hero-1__slide--".uniqid();
                                    ?>

                                    <div class="flo-page-hero__slide flo-page-hero__slide--{{ $slide_type }} {{ $slide__logo_class }} {{$block_class}}" data-elements-color="{{ $slide["slide_info"][0]["elements_color"] }}"

                                      @if ($slide_type == "image_and_video_embed")
                                        data-embed-code="{{ htmlentities($video_embed_code) }}"
                                      @endif

                                    >
                                        <style media="screen">
                                          @media screen and (max-width: 768px) and (orientation: portrait) {
                                            .{{$block_class}} .flo-page-hero__slide-content {
                                              background-position: {{$mobile_crop_position}} center;
                                            }
                                          }
                                        </style>

                                        {{-- START: VIDEO BUTTON --}}
                                          @if ($slide_type == "image_and_video_embed")

                                            <div class="flo-page-hero__slide-video-embed-container">
                                            </div>

                                            <div class="flo-page-hero__video-button">
                                              <i class="flo-page-hero__video-button-icon flo-page-hero__video-button-icon--play flo-icon-play"></i>
                                              <i class="flo-page-hero__video-button-icon flo-page-hero__video-button-icon--stop flo-icon-close"></i>
                                            </div>

                                          @endif
                                        {{-- END: VIDEO BUTTON --}}

                                        {{-- START: LOGO --}}
                                          @if ($header_logo_display)
                                            <div class="flo-page-hero__logo" style="max-width: {{ get_field("custom-header__logo-width") / 16 }}rem;">
                                              @if ($custom_logo_url = get_field("custom-header__logo-custom"))
                                                <?php
                                                  $custom_logo_light_url = get_field("custom-header__logo-custom--light");
                                                  $custom_logo_light_url = $custom_logo_light_url ? $custom_logo_light_url : $custom_logo_url;
                                                ?>
                                                <a href="{{ get_home_url() }}">
                                                  <img src="{{ $custom_logo_url }}" class="flo-page-hero__logo-image flo-page-hero__logo-image--default" alt="">
                                                  <img src="{{ $custom_logo_light_url }}" class="flo-page-hero__logo-image flo-page-hero__logo-image--light" alt="">
                                                </a>
                                              @else
                                                @include('components.flo-header__logo')
                                                @include('components.flo-header-mobile__logo')
                                              @endif
                                            </div>
                                          @endif
                                        {{-- END: LOGO --}}

                                        {{-- START: TITLE AREA --}}
                                          <div class="flo-page-hero__slide-content" aria-label="{{ $alt_text }}"
                                            @if ($slide_type == "image" || $slide_type == "image_and_video_embed")
                                              style="background-image: url({{ $slide["slide_image"]["url"] }})"
                                            @endif
                                            @if ($slide_type == "video_slide")
                                              style="background-color: black"
                                            @endif
                                          >
                                              @if ($slide_type == "video_slide")
                                                <div class="flo-page-hero__slide-background-video flo-page-hero__slide-background-video--{{ $slide["slide_video_size"] }}">
                                                  <video muted playsinline loop autoplay>
                                                    <source src="{{ $video_url }}" type="video/mp4">
                                                  </video>
                                                </div>
                                              @endif

                                              <div class="flo-page-hero__slide-info">
                                                  @if ($pretitle)
                                                    {{ $title_url_start }}
                                                      <div class="flo-page-hero__slide-pretitle">{{ $pretitle }}</div>
                                                    {{ $title_url_end }}
                                                  @endif

                                                  @if ($title)
                                                    <div class="flo-page-hero__slide-title">
                                                      {{ $title_url_start }}
                                                        {{ $title }}
                                                      {{ $title_url_end }}
                                                    </div>
                                                  @endif

                                                  @if ($subtitle)
                                                    {{ $title_url_start }}
                                                      <div class="flo-page-hero__slide-subtitle">{{ $subtitle }}</div>
                                                    {{ $title_url_end }}
                                                  @endif
                                                  @if (get_field("custom-header__addons-enabled"))
                                                    <div class="flo-page-hero__scroll-down">
                                                      <i class="flo-icon-arrow-down"></i>
                                                    </div>
                                                  @endif
                                              </div>
                                          </div>
                                        {{-- END: TITLE AREA --}}

                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @endif
                @endif


            </div>
        <!-- End: Flo Page hero -->
    </div>
    <!-- End: Flo Header Section -->
</header>
