<?php
  if(flo_data($data, 'about-page__information-block-enabled') ){
    $flo_text_image_block__enabled = flo_data($data, 'about-page__information-block-enabled');
    $flo_text_image_block__image = flo_data($data, 'about-page__information-block-image');
    $flo_text_image_block__image_description = flo_data($data, 'about-page__information-block-image-description');
    $flo_text_image_block__title = flo_data($data, 'about-page__information-block-content-title');
    $flo_text_image_block__content = flo_data($data, 'about-page__information-block-content-text');

    // the following variables must be named the same on the default page with custom blocks
    $info_block_content_title_font = flo_data($data, 'about-page__information-block-content-title-font');
    $info_block_content_text_font = flo_data($data, 'about-page__information-block-content-text-font');
    $info_block_featimg_description_font = flo_data($data, 'about-page__info-block-featimg-description-font');

    // feat image position
    if( 'left' == flo_data($data, 'about-page__information-block-image-position') ){
      $feat_img_position_style = 'order: 2;';
    }else{
      $feat_img_position_style = '';
    }
  ?>

  <?php
      $info_block_class = "info-block--".uniqid();


      $info_block_styles = '';

      $info_block_content_title_selector = '.' . $info_block_class . ' .flo-text-image-block__title';
      $info_block_styles .= flo_render_typography_styles($selector = $info_block_content_title_selector, $typography_info = $info_block_content_title_font);

      $info_block_content_text_selector = '.' . $info_block_class . ' .flo-text-image-block__content';
      $info_block_styles .= flo_render_typography_styles( $info_block_content_text_selector, $info_block_content_text_font);

      $featimg_description_font_selector = '.' . $info_block_class . ' .flo-text-image-block__image-description';
      $info_block_styles .= flo_render_typography_styles( $featimg_description_font_selector, $info_block_featimg_description_font);
  ?>

  @if(strlen($info_block_styles) )
      @include('core.style', [
          "breakpoint__general" => $info_block_styles
      ])
  @endif
  <div class="flo-section flo-section--padding-large flo-section--margin-left about-page--information-block">
      <article class="flo-section__content">
          <div class="flo-text-image-block {{$info_block_class}}">
              <div class="flo-text-image-block__text" style="{{$feat_img_position_style}}">
                  <h1 class="flo-text-image-block__title">
                      {{ $flo_text_image_block__title }}
                  </h1>
                  <div class="flo-text-image-block__content">
                      {{ $flo_text_image_block__content }}
                  </div>
              </div>

              <figure class="flo-text-image-block__image">
                  <img src="{{ $flo_text_image_block__image }}" alt="">
                  <figcaption class="flo-text-image-block__image-description">
                      {{ $flo_text_image_block__image_description }}
                  </figcaption>
              </figure>
          </div>
      </article>
  </div>

<?php } // END IF ?>
