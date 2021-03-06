{{--  scope data is provided by
/theme-files/scope/gallery/single.php  --}}
<?php

if (get_field("flo-cube-portfolio-gallery__custom-content-position")){
  $content_position = get_field("flo-cube-portfolio-gallery__title-and-content-block-type");
} else {
  $content_position = flo_get_option('flo-cube-portfolio-gallery__title-and-content-block-type', 'under');
}

if('above' == $content_position ) {
  $header__hero_post = $post;
}
$feat_img_enabled = flo_get_option('gallery_featured_image', true);

?>
@extends('layout.default')

@section('before-content')

    {{-- START: GRID POPUP --}}
        @if( !post_password_required() && 'grid' == $flo_gallery__gallery_type && sizeof($flo_gallery__gallery_images))
            <div class="flo-popup-gallery full reveal" id="popup-gallery" data-reveal data-overlay="true" data-full-screen="true" data-animation-in="fade-in" data-animation-out="fade-out" data-show-delay="0"data-v-offset="0" data-hide-delay="0">
                <div class="flo-popup-gallery__content">
                    <div class="flo-popup-gallery__top-wrap">
                        <span class="flo-icon-close close-button flo-popup-gallery__close" data-close aria-label="Close reveal" type="button"></span>
                    </div>
                    @include("components.slider__slider", [
                      "class" => "flo-hero-3--visible-nearby",
                      "use_bgi" => false,
                      "slick_configuration" => '{
                        "slidesToShow": 1,
                        "centerMode": true,
                        "variableWidth": true
                      }'
                    ])
                </div>

            </div>
        @endif
    {{-- END: GRID POPUP --}}

@endsection

@section('content')
	@if ($post)


        {{-- Start: Title Above --}}
            @if('above' == $content_position)
                @if(has_post_thumbnail() && $feat_img_enabled)
                    <?php
                    $active_slide__elements_color = flo_get_option("flo-cube-portfolio-gallery__elements-color","#ffffff");
                    ?>
                    <div class="gallery-elements-color" style="display: none" data-elements-color="{{ $active_slide__elements_color }}"></div>
                    @include('core.style', [
                      "breakpoint__general" => join([
                      ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) .flo-header__logo, ",
                      ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) .flo-header, ",
                      ".flo-header-section .flo-header-sticky-wrapper:not(.is-sticky) .flo-header__menu > div > ul > li > a, ",

                      ".flo-header-section .flo-header__logo, ",
                      ".flo-header-section .flo-header, ",
                      ".flo-header-section .flo-header__menu > div > ul > li > a, ",
                      ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__search-trigger, ",
                      ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__social-links, ",

                      ".flo-page-hero__arrow, ",
                      ".flo-page-hero__slide-pretitle, ",
                      ".flo-page-hero__slide-title, ",
                      ".flo-page-hero__slide-subtitle, ",

                      ".flo-page-hero__scroll-down, ",

                      ".flo-header-section--layout-page-title .flo-page-hero__slide-category, ",
                      ".flo-header-section--layout-page-title .flo-page-hero__slide-title, ",
                      ".flo-header-section--layout-page-title .flo-page-hero__slide-description",
                      "{",
                        "color: " . $active_slide__elements_color . ";",
                      "}",

                      "body:not(.body--flo-page-hero-video-is-playing) .flo-page-hero__video-button",
                      "{",
                        "color: " . $active_slide__elements_color . ";",
                        "border-color: " . $active_slide__elements_color . ";",
                      "}",

                      ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__hamburger",
                      "{",
                        // "border-color: " . active_slide__elements_color . ";",
                        "box-shadow: 0px 0px 15px -5px " . $active_slide__elements_color . ";",
                      "}",

                      ".flo-header-sticky-wrapper:not(.is-sticky) .flo-header__search-input",
                      "{",
                        "color: " . $active_slide__elements_color . ";",
                        "border-color: " . $active_slide__elements_color . ";",
                      "}"], " ")
                    ])
                    <section class="flo-section">
                        <article class="flo-section__content">
                            <div class="flo-post flo-post--gallery-post">
                                <?php
                                  if(post_password_required()){
                                    the_content();
                                  }else{
                                    echo apply_filters('the_content', $post->content() );
                                  }

                                ?>
                            </div>
                        </article>
                    </section>
                @else
                    <section class="flo-section flo-section--padding-medium">
                        <article class="flo-section__content">
                            <div class="flo-post-title-wrap">
                              <div class="flo-post-title-wrap__left-wrap">

                                <!-- use here the proper options name specific to galleries -->
                                @if(flo_get_option('flo-cube-portfolio-gallery__show-categories',true))
                                    <div class="flo-post-title-wrap__category">
                                        {{ $categories_list }}
                                    </div>
                                @endif

                                <div class="flo-post-title-wrap__title">
                                    {{ $post->title() }}
                                </div>

                                @if(!post_password_required())
                                <div class="flo-post-title-wrap__excerpt">
                                    {{ $post->post_excerpt }}
                                </div>
                                @endif
                              </div>

                            </div>
                        </article>
                    </section>

                    <section class="flo-section flo-section--padding-medium">
                        <article class="flo-section__content">
                            <div class="flo-post flo-gallery-content">
                                <?php
                                    if(post_password_required()){
                                        the_content();
                                    }else{
                                        echo apply_filters('the_content', $post->content() );
                                    }
                                ?>
                            </div>
                        </article>
                    </section>

                @endif

            @endif
        {{-- End: Title Above --}}
        
        <?php
          $flo_gallery__gallery_type_is_slideshow = strpos($flo_gallery__gallery_type, "slideshow") !== false;
        ?>

        {{-- START: GALLERY GRID  --}}
            @if( !post_password_required() && 'grid' == $flo_gallery__gallery_type && sizeof($flo_gallery__gallery_images))
                @include('components.flo_gallery__'.$flo_gallery__gallery_type)
            @endif
        {{-- END: GALLERY GRID  --}}

        <?php if (!post_password_required()): 

          $sl_c_d_slick_conf = '{
                                  "fade": '.$slider_c_d_fade.'
                                }';
        ?>


          {{-- START: SLIDESHOWS --}}
              <?php

                if ($gap_between = flo_get_option("flo-cube-portfolio-gallery__image-gap")) {
                  $gap_between = ($gap_between / 16 / 2)."rem";
                }
                $arrows_color = flo_get_option("flo-cube-portfolio-gallery__arrows-color","#000000");
              ?>

              @if ($flo_gallery__gallery_type_is_slideshow)
                @include('core.style', [
                  "breakpoint__general" => "
                    .flo-hero-3 .flo-hero-3__arrow {
                      color: ".$arrows_color.";
                    }
                  ",
                  "breakpoint__medium_up" => "
                    .flo-hero-3 .flo-hero-3__slide-img, .flo-hero-3 .flo-hero-3__slide-content {
                      height: ".$slideshow_height.";
                    }
                  "
                ])
              @endif

              @if('slideshow' == $flo_gallery__gallery_type && sizeof($flo_gallery__gallery_images))
                  @include('core.style', [
                    "breakpoint__medium_up" => "
                      .flo-hero-3 .flo-hero-3__slide {
                        margin: 0 ".$gap_between.";
                      }
                    "
                  ])
                  @include('components.flo_gallery__slideshow', [
                    "class" => "flo-hero-3--visible-nearby",
                    "use_bgi" => false,
                    "slick_configuration" => '{
                      "slidesToShow": 1,
                      "centerMode": true,
                      "variableWidth": true
                    }'
                  ])
              @endif

              @if('slideshow__no-crop' == $flo_gallery__gallery_type && sizeof($flo_gallery__gallery_images))
                  @include('components.flo_gallery__slideshow', [
                    "class" => "flo-hero-3--no-crop",
                    "use_bgi" => true,
                    "slick_configuration" => $sl_c_d_slick_conf
                  ])
              @endif

              @if('slideshow__crop' == $flo_gallery__gallery_type && sizeof($flo_gallery__gallery_images))
                  @include('components.flo_gallery__slideshow', [
                    "class" => "flo-hero-3--crop",
                    "use_bgi" => true,
                    "slick_configuration" => $sl_c_d_slick_conf
                  ])
              @endif


          {{-- END: SLIDESHOWS --}}
        <?php endif ?>

        <!-- Start: Flo Portfolio Post-->
        <section class="flo-section flo-section--padding-small flo-section--portfolio-post">
            <article class="flo-section__content">

                {{-- Start: Title Under --}}
                    @if('under' == $content_position)
                        <div class="flo-portfolio-post flo-portfolio-post--title-under">
                            <div class="flo-portfolio-post__left">

                                <span class="flo-portfolio-post__subtitle to-appear">
                                  @if(flo_get_option('flo-cube-portfolio-gallery__show-categories',true))
                                    {{ flo_get_the_first_term( $post->ID, "gallery-category" ) }}
                                  @endif
                                </span>

                                <h2 class="flo-portfolio-post__title to-appear">
                                    {{ $post->title() }}
                                </h2>
                                @if( !post_password_required() )
                                <div class="flo-post-title-wrap__excerpt to-appear">
                                    {{ $post->post_excerpt }}
                                </div>
                                @endif
                            </div>

                            <div class="flo-portfolio-post__text flo-post">
                                @if( !post_password_required() &&flo_get_option('flo-portfolio-gallery__slideshow-thumbnails-display', true) &&  sizeof($flo_gallery__gallery_images) && $flo_gallery__gallery_type_is_slideshow)
                                    <div class="flo-portfolio-post__thumbnails">
                                      <?php
                                        $counter = 0;
                                        foreach ($gallery_items as $item) {

                                            $slide = $item;
                                            $video_code = false;

                                            switch ($the_real_gallery_type) {
                                              case 'image':
                                                //$img_url = wp_get_attachment_url($slide, 'full');
                                                if(is_array($slide) && isset($slide['url'])){
                                                    $img_url = $slide['url'];
                                                }else{
                                                    // compatibility with the galleries created before ACF
                                                    $img_url = wp_get_attachment_url($slide, 'full');
                                                }
                                                $slide = $item;
                                              break;

                                              case 'video':
                                                $slide = $item["image"];
                                                $img_url = $slide;
                                                $video_code = $item["video_embed_code"];
                                              break;

                                              case 'prius':
                                                $slide = $item["image"];
                                                $img_url = $slide;
                                                $video_code = $item["video_embed_code"];
                                              break;
                                            }

                                          $img = aq_resize( $img_url, $width = 364, $height = 242, $crop = true, $single = true, $upscale = false );

                                          ?>
                                            <img src="{{$img}}">
                                          <?php
                                          $counter ++;
                                        }
                                      ?>
                                    </div>
                                @endif
                                <div class="flo-portfolio-post__text-content">
                                    <?php
                                      if(post_password_required()){
                                        the_content();
                                      }else{
                                        echo apply_filters('the_content', $post->content() );
                                      }

                                    ?>
                                </div>
                            </div>

                        </div>
                    @endif
                {{-- End: Title Under --}}

            </article>
        </section>

        {{-- Start: Social Share Block --}}
            @if($flo_gallery_content__enable_share)
                <section class="flo-section flo-social-share-block to-appear">
                    @include('components.flo-share-wrap')
                </section>
            @endif
        {{-- End: Social Share Block --}}

        @include('components.single-bottom')
	@endif
@stop
