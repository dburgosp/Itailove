@include('core.style', [
  "breakpoint__small_only" => "
    .flo-header-mobile {
      background-color: ". flo_get_option("flo-cube-header__background-color", "white") .";
    }
      .flo-header-mobile__logo {
        color: ". flo_get_option("flo-cube-header__elements-color", "black") .";
      }
        .flo-header-mobile__logo img {
          max-width: ". (flo_get_option("flo-cube-header__logo-max-width--phone") / 16)."rem;
        }
      .flo-header-mobile__menu-trigger {
        background-color: ". flo_get_option("flo-header-mobile__menu-trigger-background-color") .";
        color: ". flo_get_option("flo-header-mobile__menu-trigger-icon-color") .";
      }
  "
])
<?php
  $sticky_class = flo_get_option("flo-cube-header__sticky-header", false) ? "sticky" : "";
?>
<div class="flo-header-mobile {{ $sticky_class }} {{$custom_class or ""}}">
  @include('components.flo-header-mobile__logo')

  <nav class="flo-header-mobile__menu-trigger">
    <span class="flo-icon-burger flo-header-mobile__menu-trigger-icon"></span>
  </nav>
</div>
