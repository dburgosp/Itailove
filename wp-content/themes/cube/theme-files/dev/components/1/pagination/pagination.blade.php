<?php
  $next_post__type = flo_get_option("flo-cube-pagination__next-type", "block");
  $next_post__title = flo_get_option("flo-cube-pagination__next-block-title", __("Next One",'flotheme') );
  $next_post__button_title = flo_get_option("flo-cube-pagination__next-button-title", __("CONTINUE","flotheme") );
?>
@if($next_post && flo_get_option("flo-cube-pagination__display-next-link", true))
    @if('block' == $next_post__type)
        @include('components.flo-next-post')
    @elseif('card' == $next_post__type)
        @include('components.flo-next-post--card')
    @endif
@endif
