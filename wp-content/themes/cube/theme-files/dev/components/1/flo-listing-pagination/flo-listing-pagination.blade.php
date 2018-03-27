<?php

  global $the_query;
  global $paged;

  $b = "flo-listing-pagination"; // To be used inside HTML

  // Start: Class name automation
    $b__for_css = ".".$b; // To be used inside CSS
    $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
    $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
  // End: Class name automation


?>

@if ($the_query)
  <?php
    $pages_more = $the_query->max_num_pages > 1;
  ?>
  @if($pages_more)
    <?php
    $pagination_font = flo_data($data, "page-listing__page-numbers-font");

    $pagination_numbers_enabled = flo_data($data, 'page-listing__page-numbers-enabled');
    $pagination_buttons_enabled = flo_data($data, 'page-listing__navigation-buttons-enabled');

    $pagination_number_links = [];
    for ($i=1; $i <= $the_query->max_num_pages ; $i++) {
      $pagination_number_links[] = get_pagenum_link($i);
    }
    $prev_page_link = $paged-1 >= 1 ? get_pagenum_link($paged-1) : "";
    $next_page_link = $paged+1 <= $the_query->max_num_pages ? get_pagenum_link($paged+1) : "";

    ?>
    @include('core.style', [
      "breakpoint__general" => "

        ".
        flo_render_typography_styles(
        $b__uniq_for_css." .flo-pagination a, ".$b__uniq_for_css." .flo-pagination span",
        $pagination_font
        )
        ."

      "
    ])
    <div class="{{$b}} {{$b__uniq}}">
      @include('components.flo-pagination', [
        "flo_pagination__prev_link"          => $prev_page_link,
        "flo_pagination__next_link"          => $next_page_link,
        "flo_pagination__number_link_active" => $paged,
        "flo_pagination__number_links"       => $pagination_number_links,
        "wp_query"              => $the_query
      ])
    </div>
  @else
    @include('components.flo-pagination--dummy',[
      "wp_query"              => $the_query
    ])
  @endif
@endif
