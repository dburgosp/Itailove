@if($data["home-featured-posts__enabled"])

  <?php
  $section_home_featured_posts__layout = $data["home-featured-posts__layout"];

    if(isset($data['home-featured-items1__content'])){
      $feat_items1_data = $data['home-featured-items1__content'];
    }else{
      $feat_items1_data = false;
    }

    $images_padding = 0; // init this option

    // echo '<pre>';
    //   var_dump($data['home-featured-posts__item-title-font']);
    // echo '</pre>';
  ?>
  @if($section_home_featured_posts__layout == 'grid')
    <?php
      $columns_count = flo_data($data,'home-featured-items1__number-columns', 2);
      $images_padding = flo_data($data,'home-featured-items1__images-padding', 0);
      
    ?>
  @elseif($section_home_featured_posts__layout == 'full_width')
    {{-- Include full width --}}
    <?php $columns_count = 1; ?>
  @endif

<?php
    $feat1_block_class = "featured-items1-block--".uniqid();

    $feat1_page_font_styles = '';

    if(isset($data['home-featured-posts__item-title-font'])){
      $grid_title_font_selector = '.' . $feat1_block_class .' .flo-featured-posts--layout-grid .flo-featured-posts__title';
      $feat1_page_font_styles .= flo_render_typography_styles( $grid_title_font_selector, $data['home-featured-posts__item-title-font']);
    }

    if(isset($data['home-featured-posts__item-category-font'])){
      $grid_category_font_selector = '.' . $feat1_block_class . ' .flo-featured-posts--layout-grid .flo-featured-posts__category';
      $feat1_page_font_styles .= flo_render_typography_styles( $grid_category_font_selector, $data['home-featured-posts__item-category-font']);
    }

    if(isset($data['home-featured-posts__list-item-title-font'])){
      $grid_title_font_selector = '.' . $feat1_block_class .' .flo-featured-posts--layout-full_width .flo-featured-posts__title';
      $feat1_page_font_styles .= flo_render_typography_styles( $grid_title_font_selector, $data['home-featured-posts__list-item-title-font']);
    }


    if(isset($data['home-featured-posts__list-item-category-font'])){
      $grid_category_font_selector = '.' . $feat1_block_class . ' .flo-featured-posts--layout-full_width .flo-featured-posts__category';
      $feat1_page_font_styles .= flo_render_typography_styles( $grid_category_font_selector, $data['home-featured-posts__list-item-category-font']);
    }

    $images_padding_styles = ' .featured-items1-block--item{ padding-left: '. $images_padding/(2*16) .'rem; padding-right: '. $images_padding/(2*16) .'rem; padding-top: '. $images_padding/16 .'rem; } .featured-items1-block--item.medium-6:nth-of-type(even){ padding-right: '.($images_padding/16).'rem; } .featured-items1-block--item.medium-6:nth-of-type(odd){ padding-left: '.($images_padding/16).'rem; } .featured-items1-block--item.medium-4:nth-of-type(3n+1){ padding-left: '.($images_padding/16).'rem; }
.featured-items1-block--item.medium-4:nth-of-type(3n+3){ padding-right: '.($images_padding/16).'rem; } 
.featured-items1-block--item.medium-3:nth-of-type(4n+1){ padding-left: '.($images_padding/16).'rem; }
.featured-items1-block--item.medium-3:nth-of-type(4n+4){ padding-right: '.($images_padding/16).'rem; } ';
?>

@include('core.style', [
  "breakpoint__general" => $feat1_page_font_styles,
  "breakpoint__medium_up" => $images_padding_styles
])
  <div class="flo-section flo-section--padding-small flo-section--journal-grid-section {{$feat1_block_class}}">
      <article class="flo-grid">
          <div class="row row-flex">

              @if ($feat_items1_data)

                @foreach($feat_items1_data as $item_data)
                    <?php
                      if(isset($item_data['image']['url'])){
                        $img_url = $item_data['image']['url'];
                      }else{
                        $img_url = '';
                      }

                      if(isset($item_data['item_info'][0]['title'])){
                        $item_title = $item_data['item_info'][0]['title'];
                      }else{
                        $item_title = '';
                      }

                      if(isset($item_data['item_info'][0]['title_url'])){
                        $item_title_url = $item_data['item_info'][0]['title_url'];
                      }else{
                        $item_title_url = '';
                      }

                      if(isset($item_data['item_info'][0]['subtitle'])){
                        $item_subtitle = $item_data['item_info'][0]['subtitle'];
                      }else{
                        $item_subtitle = '';
                      }



                      $block_class = "flo-featured-posts__featured-post--".uniqid();

                      /* START: VIDEO EMBED */
                        $video_embed_code = $item_data["video_embed_code"];
                        $has_video = $video_embed_code ? true : false;
                        $video_class = $has_video ? "flo-featured-posts__featured-post--has-video" : "";
                      /* END: VIDEO EMBED */

                      /* START: DECORATIVE LAYER */
                        $has_decorative_layer = $item_data["decorative_overlay"] && !$has_video;
                        $has_decorative_layer_class = $has_decorative_layer ? "flo-featured-posts__featured-post--has-decorative-layer" : "";
                        $decorative_image = $item_data["decorative_image"];
                        $decorative_color = $item_data["decorative_overlay_color"];
                        $decorative_elements_color = $item_data["decorative_overlay_elements_color"];
                      /* END: DECORATIVE LAYER */

                      if ($has_decorative_layer) {

                        $item_text_color = "color: ".$decorative_elements_color;

                      } else {

                        if(isset($item_data['item_info'][0]['text_color'])){
                          $item_text_color = 'color:'. $item_data['item_info'][0]['text_color'];
                        }else{
                          $item_text_color = 'color: #ffffff';
                        }

                      }

                    ?>

                    @if ($has_decorative_layer)
                      @include('core.style', [
                        "breakpoint__general" => "
                          .".$block_class.".".$has_decorative_layer_class.":before,
                          .".$block_class.".".$has_decorative_layer_class.":after {
                            background-color: ".$decorative_color.";
                            border-color: ".$decorative_color.";
                          }
                        "
                      ])
                    @endif

                    <div class="column medium-{{ 12 / $columns_count }} small-12 to-appear to-appear--uniform featured-items1-block--item">
                        <div class="flo-featured-posts__featured-post {{ $block_class }} flo-featured-posts--layout-{{ $section_home_featured_posts__layout }} {{ $video_class }} {{ $has_decorative_layer_class }} ">


                            @if(strlen($item_title_url))
                            <a href="{{ $item_title_url }}" class="flo-featured-posts__link-box">
                            @endif
                              {{-- <figure class="flo-featured-posts__img" style="background-image: url({{ $img_url }})">
                                  <img src="{{$img_url}}"> --}}
                              <figure class="flo-featured-posts__img flo-featured-posts-block__img" data-bg-src="{{$img_url}}" style="background-image: url({{ $img_url }})">
                                  <img crossorigin src="{{$img_url}}">
                              </figure>
                            @if(strlen($item_title_url))
                            </a>
                            @endif
                            <div class="flo-featured-posts__text">

                                @if ($has_decorative_layer)
                                  <img src="{{ $decorative_image }}" class="flo-featured-posts__decorative-image" alt="">
                                @endif

                                @if(strlen($item_title))
                                <div class="flo-featured-posts__title" style="{{$item_text_color}}">
                                  @if(strlen($item_title_url))
                                  <a href="{{$item_title_url}}" >
                                  @endif
                                    {{$item_title}}
                                  @if(strlen($item_title_url))
                                  </a>
                                  @endif
                                </div>
                                @endif
                                @if(strlen($item_subtitle))
                                  <div class="flo-featured-posts__category" style="{{$item_text_color}}">
                                      {{$item_subtitle}}
                                  </div>
                                @endif
                            </div>
                        </div>

                        @if ($has_video)
                          @include('components.flo-video-embed', [
                            "embed_code" => $item_data["video_embed_code"]
                          ])
                        @endif
                    </div>
                @endforeach

              @endif

          </div>
      </article>
  </div>

@endif
