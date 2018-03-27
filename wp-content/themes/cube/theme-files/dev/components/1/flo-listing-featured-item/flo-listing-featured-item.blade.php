<?php
$b = "flo-listing-featured-item"; // To be used inside HTML

// Start: Class name automation
$b__for_css = ".".$b; // To be used inside CSS
$b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
$b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$items = flo_data($data, "page-listing-featured-post__post");
$elements_color = flo_data($data, "page-listing-featured-post__elements-color");
$category_font = flo_data($data, "page-listing-featured-post__category-font");
$title_font = flo_data($data, "page-listing-featured-post__title-font");
$excerpt_font = flo_data($data, "page-listing-featured-post__excerpt-font");
$button_text = flo_data($data, "page-listing-featured-post__button-text");
$button_bgc = flo_data($data, "page-listing-featured-post__button-background-color");
$button_text_color = flo_data($data, "page-listing-featured-post__button-text-color");
?>
@if($items)
  @include('core.style', [
    "breakpoint__general" => "

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-hero-2__category",
      $category_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-hero-2__title",
      $title_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-hero-2__excerpt",
      $excerpt_font
      )
      ."

      ".$b__uniq_for_css." .flo-hero-2__category a,
      ".$b__uniq_for_css." .flo-hero-2__excerpt,
      ".$b__uniq_for_css." .flo-hero-2__title {
        color: ".$elements_color."!important;
      }

      ".$b__uniq_for_css." .flo-hero-2__read-more-button {
        color: ".$button_text_color.";
        background-color: ".$button_bgc.";
      }

    "
  ])
  <div class="flo-hero-2 {{$b}} {{$b__uniq}}">
    <div class="flo-hero-2__slider">
      @foreach($items as $item)
        <?php
        $feat_post_category_term = flo_get_category_term($item);
        ?>
        <div class="flo-hero-2__slide">
          <a href="{{ get_permalink($item->ID) }}" class="flo-hero-2__slide-content" style="background-image: url({{ get_the_post_thumbnail_url($item->ID) }})"></a>
          <div class="flo-hero-2__text-block">
            <div class="flo-hero-2__text">
              <div class="flo-hero-2__category">
                {{ flo_get_the_first_term( $id = $item->ID, $taxonomy = $feat_post_category_term, $before = '', $sep = ', ', $after = '' ); }}
              </div>
              <a href="{{ get_permalink($item->ID) }}" class="flo-hero-2__title">
                {{ get_the_title($item) }}
              </a>
              <a href="{{ get_permalink($item->ID) }}" class="flo-hero-2__excerpt">
                {{ apply_filters( 'the_excerpt', get_the_excerpt($item->ID) ) }}
              </a>
              <a href="{{ get_permalink($item->ID) }}"  class="flo-hero-2__read-more-button">
                {{ $button_text }}
              </a>
            </div>
          </div>
        </div>

      @endforeach
    </div>
  </div>

@endif
