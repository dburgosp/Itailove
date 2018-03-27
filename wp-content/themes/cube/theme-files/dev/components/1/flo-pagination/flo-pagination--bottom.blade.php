{{--

Uses as data:

- $flo_post_pagination__link_prev
- $flo_post_pagination__link_next

- $flo_post_pagination__navigation_enabled

--}}

<div class="flo-post-pagination flo-post-pagination--bottom">

  <div class="flo-post-pagination__pager">
      @if(isset($flo_post_pagination__link_prev))
        <a href="{{ $flo_post_pagination__link_prev }}" class="flo-post-pagination__pager-item"><?php _e('PREV','flotheme') ?> </a>
      @endif
      @if(isset($flo_post_pagination__link_next))
        <a href="{{ $flo_post_pagination__link_next }}" class="flo-post-pagination__pager-item"><?php _e('NEXT','flotheme') ?></a>
      @endif
  </div>
</div>
