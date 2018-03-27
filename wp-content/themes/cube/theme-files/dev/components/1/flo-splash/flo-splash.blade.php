<div class="flo-splash" data-animation="{{ flo_get_option("flo-cube-pages__splash-animation", "fade") }}" style="background-color: {{ flo_get_option("flo-cube-pages__splash-background-color", "#FFFFFF") }};">
  @if (flo_get_option("flo-cube-pages__splash-logo-display", true))
    <div class="flo-splash__content flo-splash__content--medium-up">
      @include('components.flo-header__logo')
    </div>
    <div class="flo-splash__content flo-splash__content--small-only">
      @include('components.flo-header-mobile__logo')
    </div>
  @endif
</div>
