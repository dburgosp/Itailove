<?php
$b = "flo-listing-title"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$show_title_block = flo_data($data, "page-listing-title__show-title-block");

?>

@if($show_title_block)
  <?php
    $title_type = flo_data($data, "page-listing-title__title-type", "page");
    $title = false;
    if ($title_type == "page") {
      $title = $post->title();
    } elseif ($title_type == "custom") {
      $title = flo_data($data, "page-listing-title__title");
    }
    $title_font = flo_data($data, "page-listing-title__title-font");

    $subtitle = flo_data($data, "page-listing-title__subtitle");
    $subtitle_font = flo_data($data, "page-listing-title__subtitle-font");

    /* START: CATEGORIES */
      $display_category_switcher = flo_data($data, "page-listing-title__category-switcher");
      $categories_source = flo_data($data, "page-listing-title__categories-source", "old");

      $current_page_permalink = get_permalink();
      $raw_categories_taxonomy = false;
      $raw_categories = false;
      $categories = false;

      /* START: PREPARE RAW CATEGORIES */
        switch ($categories_source) {
          case 'old':
            $raw_categories_post_type = get_field("page-listing__post-type");
            if ($raw_categories_post_type) {
              if($raw_categories_post_type == "gallery"){
                $raw_categories_taxonomy = "gallery-category";
                $raw_categories = get_field("page-listing-listing__gallery-categories");
              }else{
                $raw_categories_taxonomy = "category";
                $raw_categories = get_field("page-listing-listing__post-categories");
              }
            }
          break;

          case 'post_categories':
            $raw_categories_taxonomy = "category";
            $raw_categories = flo_data($data, "page-listing-title__categories-post");
          break;

          case 'gallery_categories':
            $raw_categories_taxonomy = "gallery-category";
            $raw_categories = flo_data($data, "page-listing-title__categories-gallery");
          break;
        }

      /* END: PREPARE RAW CATEGORIES */

      /* START: PREPARE CATEGORIES FOR RENDER */
        if (is_array($raw_categories)) {
          foreach ($raw_categories as $raw_category) {
            $category = get_term_by("id", $raw_category, $raw_categories_taxonomy);
            $link_filter_cat = add_query_arg( 'categ', $category->slug, $current_page_permalink );

            $categories[] = Array(
              "title" => $category->name,
              "url" => $link_filter_cat
            );
          }
        }
        if ($categories_source == "links") {
          $categories = flo_data($data, "page-listing-title__categories-links");
        }
      /* END: PREPARE CATEGORIES FOR RENDER */

      if (!$categories) {
        $display_category_switcher = false;
      }

    /* END: CATEGORIES */
  ?>

  @include('core.style', [
    "breakpoint__general" => "

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-page-head__title",
      $title_font
      )
      ."

      ".
      flo_render_typography_styles(
      $b__uniq_for_css." .flo-page-head__subtitle",
      $subtitle_font
      )
      ."

    "
  ])
  <div class="{{$b}} {{$b__uniq}} flo-page-head flo-page-head--has-dropdown-menu">
    <h1 class="flo-page-head__title">{{ $title }}</h1>
    @if ($subtitle)
      <h5 class="flo-page-head__subtitle">{{ $subtitle }}</h5>
    @endif

    @if( $display_category_switcher )
      <div class="flo-section-categories">
        <ul class="flo-section-categories__list">
          <li class="flo-section-categories__link"><a href="{{get_the_permalink($post->ID)}}">{{ __('All','flotheme') }}</a></li>
          @foreach($categories as $category)
            <li class="flo-section-categories__link"><a href="{{$category["url"]}}">{{ $category["title"] }}</a></li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>
@endif
