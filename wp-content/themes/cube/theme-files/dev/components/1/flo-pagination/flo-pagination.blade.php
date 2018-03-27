{{--

Uses as data:
- $flo_pagination__style (can be: all, buttons, numbers)

- $flo_pagination__prev_title
- $flo_pagination__prev_link

- $flo_pagination__next_title
- $flo_pagination__next_link

- $flo_pagination__number_link_active
- $flo_pagination__number_links

--}}

<?php

  if(!isset($pagination_numbers_enabled)){
    $pagination_numbers_enabled = true;
  }

  if(!isset($pagination_buttons_enabled)){
    $pagination_buttons_enabled = true;
  }

  if ( get_query_var('paged') ) { 
    $pag_page = get_query_var('paged'); 
  } else if ( get_query_var('page') ) {
    $pag_page = get_query_var('page'); 
  } else {
    $pag_page = 1;
  }
?>
<div class="flo-section flo-section--padding-small">
  <div class="flo-section__content">
    <div class="flo-pagination flo-pagination--style-all flo-pagination--gap-bottom">

        @if($pagination_numbers_enabled)

        <div class="flo-pagination__items">
          <?php

            $big = 999999999; // need an unlikely integer

            echo paginate_links( array(
              'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
              'format' => '?paged=%#%',
              'current' => max( 1, $pag_page ),
              'prev_text'          => '',
              'next_text'          => '',
              'total' => $wp_query->max_num_pages,
              //'before_page_number' => 
            ) );
          ?>
        </div>
        @endif

        @if($pagination_buttons_enabled)
        <div class="flo-pagination__navigation">
            @if(isset($flo_pagination__prev_link) && $flo_pagination__prev_link != "")
                <div class="flo-pagination__prev">
                    <a href="{{ $flo_pagination__prev_link }}">
                      {{-- {{ $flo_pagination__prev_title or "&lt;" }} --}}
                      <i class="flo-icon-angle-left"></i>
                    </a>
                </div>
            @endif
            @if(isset($flo_pagination__next_link) && $flo_pagination__next_link != "")
                <div class="flo-pagination__next">
                    <a href="{{ $flo_pagination__next_link }}">
                      {{-- {{ $flo_pagination__next_title or "&gt;" }} --}}
                      <i class="flo-icon-angle-right"></i>
                    </a>
                </div>
            @endif
        </div>
        @endif

    </div>
  </div>
</div>
