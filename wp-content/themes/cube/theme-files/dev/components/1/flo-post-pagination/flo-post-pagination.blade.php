{{--

Uses as data:
- $flo_post_pagination__link_back_title
- $flo_post_pagination__link_back

- $flo_post_pagination__link_prev
- $flo_post_pagination__link_next

- $flo_post_pagination__navigation_enabled

--}}

<div class="flo-post-pagination flo-post-pagination--margin-bottom">
  @if(isset($flo_post_pagination__link_back))
    <a href="{{ $flo_post_pagination__link_back}}" class="flo-post-pagination__back-to-list">
        <span class="flo-icon flo-icon-flo-arrow-left flo-post-pagination__back-icon"></span>
        {{ $flo_post_pagination__link_back_title or "back to list"}}
    </a>
  @else
    <div class="flo-post-pagination__spacer"></div>
  @endif


  <div class="flo-post-pagination__pager">
    @if(isset($flo_post_pagination__navigation_enabled) && $flo_post_pagination__navigation_enabled)

      @if(isset($flo_post_pagination__link_prev))
        <a href="{{ $flo_post_pagination__link_prev }}" class="flo-post-pagination__pager-item"><?php _e('PREV. POST','flotheme') ?> </a>
      @endif
      @if(isset($flo_post_pagination__link_next))
        <a href="{{ $flo_post_pagination__link_next }}" class="flo-post-pagination__pager-item"><?php _e('NEXT. POST','flotheme') ?></a>
      @endif

    @endif
  </div>
</div>
