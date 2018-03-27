<?php
$b = "flo-listing-wide-cards"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$columns = flo_data($data, "page-listing-listing__columns-count", 3);
$gap = flo_data($data, "page-listing-listing__gutter", 20);
$gap_per_item = $gap * ($columns - 1) / $columns / 16 . "rem";
$gap_for_css = $gap / 16 . "rem";

$display_category = flo_data($data, "flo-page-listing-listing__show-category");
$title_font = flo_data($data, "flo-page-listing-listing__cards-category-font");
$category_font = flo_data($data, "flo-page-listing-listing__grid-category-font");
$display_date = flo_data($data, "flo-page-listing-listing__grid-date-near-cat");
$overlay_color = flo_data($data, "flo-page-listing-listing__overlay-color");
$overlay_opacity = flo_data($data, "flo-page-listing-listing__overlay-opacity", 27) / 100;
$overlay_text_color = flo_data($data, "flo-page-listing-listing__overlay-text-color");

$button_label = flo_data($data, "flo-page-listing-listing__cards-button-label");
$button_font = flo_data($data, "flo-page-listing-listing__cards-button-font");
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
    $b__uniq_for_css." ".$b__for_css."__date-and-category",
    $category_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." ".$b__for_css."__button",
    $button_font
    )
    ."

    ".$b__uniq_for_css." ".$b__for_css."__overlay {
      background-color: ".hex2rgba($overlay_color, $overlay_opacity).";
    }

    ".$b__uniq_for_css." ".$b__for_css."__title,
    ".$b__uniq_for_css." ".$b__for_css."__date-and-category,
    ".$b__uniq_for_css." ".$b__for_css."__button
    {
      color: ".$overlay_text_color.";
    }
  ",
  "breakpoint__medium_up" => "
    ".$b__uniq_for_css." ".$b__for_css."__item:hover ".$b__for_css."__overlay {
      background-color: ".hex2rgba($overlay_color, $overlay_opacity).";
    }
    ".$b__uniq_for_css." ".$b__for_css."__overlay {
      background-color: transparent;
    }
  "
])
<div class="{{$b}} {{$b__uniq}}">
  @while($the_query->have_posts())
    <?php $the_query->the_post() ?>
      <?php
        $title = get_the_title();
        $link = get_the_permalink();

        $post_thumbnail_url = get_the_post_thumbnail_url();
        // $post_thumbnail_url = aq_resize($post_thumbnail_url, $width = 1450, 9999, $crop = false, true, true); //resize img

        $category_term = flo_get_category_term($the_query->post);
        $category = "Weddings";

        $date = get_the_date("");
      ?>
      <a href="{{$link}}" class="{{$b}}__item">
        <span class="{{$b}}__image" style="background-image: url({{$post_thumbnail_url}})"></span>
        <span class="{{$b}}__overlay">
          <span class="{{$b}}__title">
            {{$title}}
          </span>
          @if ($display_category)
            <span class="{{$b}}__date-and-category">
              @if ($display_date)
                {{$date}} /
              @endif
              {{$category}}
            </span>
          @endif
          @if ($button_label)
            <span class="{{$b}}__button">
              {{$button_label}}
            </span>
          @endif
        </span>
      </a>

    <?php wp_reset_postdata(); ?>
  @endwhile
</div>
