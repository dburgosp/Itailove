<?php
$b = "flo-footer-b"; // To be used inside HTML

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
// End: Class name automation

$bgc = flo_get_option("flo-cube-footer-b__background-color", false);
$elements_color = flo_get_option("flo-cube-footer-b__text-color", false);
$copyright_notice = flo_get_option("flo-cube-footer__copyright-notice");
$display_social_links = flo_get_option("flo-cube-footer__addons-display-social-links");
$display_back_to_top = flo_get_option("flo-cube-footer__addons-display-back-to-top");
$back_to_top_button_label = flo_get_option("flo-cube-footer__addons-back-to-top-button-label");

?>
<footer class="{{$b}}">
  <div class="{{$b}}__copyright-notice">
    {{ do_action("flo_footer_credits"); }}
    @if ($copyright_notice)
      {{$copyright_notice}}
    @endif
  </div>
  <div class="{{$b}}__addons">
    @if ($display_social_links)
      <div class="{{$b}}__social-links">
        @include('components.social-links')
      </div>
      @if ($display_back_to_top)
        <div class="{{$b}}__back-to-top">
          {{$back_to_top_button_label}}
          <i class="flo-icon-arrow-up"></i>
        </div>
      @endif
    @endif
  </div>
</footer>
