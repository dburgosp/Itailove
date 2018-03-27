<?php

$b = "flo-about-block"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$flo_about_block__featured_image_type = flo_data($data, "about-page__featured-image-type", "page");
$flo_about_block__featured_image_src = false;
if ($flo_about_block__featured_image_type == "custom") {
  $flo_about_block__featured_image = flo_data($data, "about-page__featured-image");
  if ($flo_about_block__featured_image) {
    $flo_about_block__featured_image_src = $flo_about_block__featured_image["url"];
  }
} else {
  $flo_about_block__featured_image_src = get_the_post_thumbnail_url();
}

$flo_about_block__pretitle = flo_data($data, "about-page__pretitle");
$flo_about_block__pretitle_font = flo_data($data, "about-page__pretitle-font");
$flo_about_block__title = flo_data($data, 'about-page__title');
$flo_about_block__title_font = flo_data($data, "about-page__title-font");
$flo_about_block__subtitle = flo_data($data, 'about-page__subtitle');
$flo_about_block__subtitle_font = flo_data($data, 'about-page__subtitle-font');

$flo_about_block__content_type = flo_data($data, "about-page__content-type", "page");
$flo_about_block__content = "";
if ($flo_about_block__content_type == "page") {
  $flo_about_block__content = $post->content();
} elseif($flo_about_block__content_type == "custom") {
  $flo_about_block__content = flo_data($data, "about-page__content");
}

$flo_about_block__content_font = flo_data($data, "about-page__content-font");
$flo_about_block__content_block_title = flo_data($data, 'about-page__content-block-title');
$flo_about_block__content_block_title_font = flo_data($data, 'about-page__content-block-title-font');
$flo_about_block__content_block_links = flo_data($data, 'about-page__content-block-links');
$flo_about_block__content_block_links_font = flo_data($data, 'about-page__content-block2-links-font');
?>

@if( (isset($data['about-page__top-block-show']) && $data['about-page__top-block-show']) || !isset($data['about-page__top-block-show']) )
  @include('core.style', [
    "breakpoint__general" => "

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." ".$b__for_css."__pretitle",
      $flo_about_block__pretitle_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." ".$b__for_css."__title",
      $flo_about_block__title_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." ".$b__for_css."__subtitle",
      $flo_about_block__subtitle_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." ".$b__for_css."__content-text",
      $flo_about_block__content_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-information-block__title",
      $flo_about_block__content_block_title_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-information-block__featured-item",
      $flo_about_block__content_block_links_font
      )
      ."

    "
  ])
  <div class="flo-section flo-section--padding-large flo-section--margin-left about-page--top-section">
    <article class="flo-section__content">

      <div class="flo-about-block {{$b__uniq}}" >
        <div class="flo-about-block__section">
          <div class="flo-about-block__item-text" id="text">
            <div class="flo-about-block__text">
              <h2 class="flo-about-block__page-title flo-about-block__pretitle">
                {{ $flo_about_block__pretitle }}
              </h2>
              <h1 class="flo-about-block__title">
                {{ $flo_about_block__title }}
              </h1>
              <p class="flo-about-block__subtitle">
                {{ $flo_about_block__subtitle }}
              </p>
            </div>
          </div>
          @if(strlen($flo_about_block__featured_image_src))
            <div class="flo-about-block__item-img">
              <figure class="flo-about-block__img" style="background-image: url('{{ $flo_about_block__featured_image_src or "img/flo-section-about-img.png" }}')">
                <img src="{{ $flo_about_block__featured_image_src or "img/flo-section-about-img.png" }}">
              </figure>
            </div>
          @endif
        </div>

        <div class="flo-about-block__section">
          <section class="flo-about-block__content-text">
            {{
              $flo_about_block__content
            }}
          </section>

          <div class="flo-information-block__featured-item-block">
            @if(!empty($flo_about_block__content_block_links))
              <div class="flo-information-block__featured-items">
                <div class="flo-information-block__title">
                  {{ $flo_about_block__content_block_title }}
                </div>
                <ul class="flo-information-block__featured-items-list">
                  @foreach($flo_about_block__content_block_links as $item)
                    <li class="flo-information-block__featured-item">
                      <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        </div>
      </div>

    </article>
  </div>
@endif