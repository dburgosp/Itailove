<?php

$b = "flo-contact-block-1"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$padding_top = flo_data($data, 'page-contact__top-padding', 111) / 16 . "rem";
$padding_bottom = flo_data($data, 'page-contact__bottom-padding', 140) / 16 . "rem";

$pretitle = flo_data($data, "page-contact__pretitle");
$pretitle_font = flo_data($data, "page-contact__page-title-font");

$title = flo_data($data, 'page-contact__title');
$title_font = flo_data($data, "page-contact__form-title-font");

$content_type = flo_data($data, "page-contact__content-type", "page");
$content = false;
if ($content_type == "page") {
  $content = $post->content();
} elseif ($content_type == "custom") {
  $content = flo_data($data, "page-contact__content");
}
$content_font = flo_data($data, "page-contact__content-font");

$form_type = flo_data($data, 'page-contact__form-type');
$contact_blocks = flo_data($data, 'page-contact__contact_blocks');
$contact_blocks_title_font = flo_data($data, "page-contact__contact_blocks-title-font");
$contact_blocks_content_font = flo_data($data, "page-contact__contact_blocks-content-font");

$thank_you_message = flo_data($data, 'page-contact__custom-thank-you-message');

$featured_image_type = flo_data($data, "page-contact__featured-image-type", "page");
$featured_image_src = "";
if ($featured_image_type == "page") {
  $featured_image_src = get_the_post_thumbnail_url();
} elseif ($featured_image_type == "custom") {
  $featured_image = flo_data($data, "page-contact__featured-image");
  if ($featured_image) {
    $featured_image_src = $featured_image["url"];
  }
}

?>

@include('core.style', [
  "breakpoint__general" => "

    ".$b__uniq_for_css." {
      padding-top: ".$padding_top.";
      padding-bottom: ".$padding_bottom.";
    }

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__page-title",
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
    $b__uniq_for_css." ".$b__for_css."__text",
    $content_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__contact-block-title",
    $contact_blocks_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__contact-block-content",
    $contact_blocks_content_font
    )
    ."

  "
])

<div class="flo-section flo-section--padding-large flo-section--margin-left {{$b}} {{$b__uniq}}">
  <article class="flo-section__content">
    <div class="{{$b}}__content">
      <div class="{{$b}}__title-area">
        <h1 class="{{$b}}__page-title">{{ $pretitle }}</h1>
        <h2 class="{{$b}}__title">{{ $title }}</h2>
        <div class="{{$b}}__text flo-post__styles">
          {{$content}}
        </div>
      </div>

      <div class="{{$b}}__contact-blocks">
        @if( is_array($contact_blocks) && sizeof($contact_blocks))
          @foreach($contact_blocks as $block)
            <div class="{{$b}}__contact-block">
              <h3 class="{{$b}}__contact-block-title">
                {{ $block['title'] }}
              </h3>
              <div class="{{$b}}__contact-block-content">
                {{ $block['content'] }}
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>


    <div class="{{$b}}__form">

      @if($form_type == 'builtin')
        <!-- required block -->
        <div class="flo-modal" style="display: none;">
          <div class="thx-msg">
            <div class="flo-icon__close"></div>
            <div class="content">{{$thank_you_message}}</div>  <!--  use the correct field name here -->
          </div>
        </div>
        <form class="flo-form__built-in"> <!-- flo-form__built-in is required class -->

          <div class="flo-form__half">
            <div class="flo-form__field">
              <input type="text" class="flo-name" name="flo-name" placeholder="{{ __('YOUR NAME','flotheme') }}">
            </div>
            <div class="flo-form__field">
              <input type="text" class="flo-email" name="flo-email" placeholder="{{ __('EMAIL','flotheme') }}">
            </div>
            <div class="flo-form__field">
              <input type="text" class="flo-phone" name="flo-phone" placeholder="{{ __('PHONE','flotheme') }}">
            </div>
            <div class="flo-form__field">
              <input class="flo-subject" name="flo-subject" type="text" placeholder="{{ __('SUBJECT','flotheme') }}">
            </div>
          </div>

          <div class="flo-form__half">
            <div class="flo-form__field">
              <input type="text" class="flo-date" name="flo-date" placeholder="{{ __('EVENT DATE','flotheme') }}">
            </div>
            <div class="flo-form__field">
              <input type="text" class="flo-location" name="flo-location" placeholder="{{ __('LOCATION','flotheme') }}">
            </div>
            <div class="flo-form__field flo-form__field--full-height">
              <textarea class="flo-message" name="flo-message" placeholder="{{ __('YOUR MESSAGE','flotheme') }}"></textarea>
            </div>
          </div>

          <input type="hidden" value="{{ $post->ID }}" name="pid"> <!-- required -->

          <div class="flo-form__submit-wrap">
            <input type="submit" class="flo-form__submit--button flo-form-submit" value="{{ __('SEND','flotheme') }}"> <!-- should use class flo-form-submit -->
          </div>

          <div class="contact-response"></div>  <!-- required -->
        </form>
      @else
        <div class="flo-form__custom-form">
          <?php
          $form_shortcode = trim(flo_data($data, 'page-contact__form-shortcode'));
          echo do_shortcode( $form_shortcode );
          ?>
        </div>
      @endif

      <figure class="{{$b}}__featured-image">
        @if ($featured_image_src)
          <img src="{{$featured_image_src}}" alt="">
        @endif
      </figure>
    </div>

  </article>
</div>
