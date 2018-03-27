<?php

  if(isset($data['press-page__links']) && is_array($data['press-page__links']) ){
    $press_page__links = $data['press-page__links'];

    //$press_page__featured_image = $data['press-page__featured-image'];
    //$press_page__featured_image_description = $data['press-page__featured-image-description'];
    $press_page__featured_image_description_font = $data['press-page__featured-image-description-font'];

    $press_page__page_title = $data['press-page__page-title'];

    $press_page_title_font = $data['press-page__page-title-font'];
    $press_page_link_title_font = $data['press-page__link-title-font'];
    $press_page_link_subtitle_font = $data['press-page__link-subtitle-font'];
    $link_title_color = $data['press-page__link-title-color'];
    $link_title_hover_color = $data['press-page__link-title-hover-color'];

    if(isset($data['press-page__link-margin-bottom'])){
      $link_margin_bottom = ($data['press-page__link-margin-bottom'] / 16). "rem";
    }else{
      $link_margin_bottom = '2.8125rem';
    }
  ?>

  <?php
      $press_block_class = "press-block--".uniqid();

      $press_page_font_styles = '';
      $title_font_selector = '.' . $press_block_class . ' .press-page__title';
      $press_page_font_styles .= flo_render_typography_styles( $title_font_selector, $press_page_title_font);

      $link_title_font_selector = '.' . $press_block_class . ' .press-page__link-title';
      $press_page_font_styles .= flo_render_typography_styles( $link_title_font_selector, $press_page_link_title_font);

      $link_subtitle_font_selector = '.' . $press_block_class . ' .press-page__link-subtitle';
      $press_page_font_styles .= flo_render_typography_styles( $link_subtitle_font_selector, $press_page_link_subtitle_font);

      $image_description_font_selector = '.' . $press_block_class . ' .press-page__featured-image-description';
      $press_page_font_styles .= flo_render_typography_styles( $image_description_font_selector, $press_page__featured_image_description_font);

      if(strlen($link_title_color)){
          $press_page_font_styles .= $link_title_font_selector . ',' .$link_subtitle_font_selector . "{ color: ".$link_title_color." }";
      }


      if(strlen($link_title_hover_color)){
          $hover_selector = '.'.$press_block_class.' .press-page__link-url:hover .press-page__link-title';
          $hover_selector .= ', .'.$press_block_class.' .press-page__link-url:hover .press-page__link-subtitle';
          $press_page_font_styles .= $hover_selector . "{ color: ".$link_title_hover_color." }";
      }

      $link_margin_bottom_selector = '.' . $press_block_class . ' .press-page__link';
      $press_page_font_styles .= $link_margin_bottom_selector . '{margin-bottom: '.$link_margin_bottom.'}';


  ?>

  @include('core.style', [
    "breakpoint__general" => $press_page_font_styles
  ])
  <div class="flo-section flo-section--full-width press-page {{ $press_block_class }}">
      <article class="press-page__content">

          <div class="press-page__links">
              <h1 class="press-page__title">{{ $press_page__page_title }}</h1>

              @foreach($press_page__links as $link)
                  <?php 
                    if(isset($link['url']) && strlen($link['url']) ){
                      $link_start = '<a href="'.$link['url'].'">';
                      $link_end = '</a>';
                    }
                  ?>
                  <div class="press-page__link" data-img="{{ $link['image'] }}" data-description="{{ $link['image_description'] }}" data-block_class="{{ $press_block_class }}">
                      <a href="{{ $link['url'] }}" class="press-page__link-url">
                          <span class="press-page__link-title">{{ $link['title'] }}</span>
                          <span class="press-page__link-subtitle">{{ $link['subtitle'] }}</span>
                      </a>
                      

                      <div class="press-page__mobile-content">
                        {{ $link_start }}
                          <img src="{{ $link['image'] }}" />
                          <div class="press-page__mobile-content-img-desc">{{ $link['image_description'] }}</div>
                        {{ $link_end }}
                      </div>
                  </div>
              @endforeach
          </div>

          <figure class="press-page__featured-image figure-{{ $press_block_class }}">
              <img src="{{ $press_page__links[0]['image'] }}" alt="">
              <figcaption class="press-page__featured-image-description">{{ $press_page__links[0]['image_description'] }}</figcaption>
          </figure>
      </article>
      @if(isset($page_content))
      <div class="flo-post page-content-wrap ">
        <?php  echo (apply_filters('the_content', $page_content )); ?>
      </div>
      @endif
  </div>
<?php } // END IF $data['press-page__links'] ?>
