<?php
  $background_color = flo_get_option("flo-cube-mobile-menu__background-color", "white");
  $elements_color = flo_get_option("flo-cube-mobile-menu__elements-color", "black");
?>
@include('core.style', [
  "breakpoint__general" => "
    .flo-mobile-menu,
    .flo-mobile-menu__search-wrap
    {
      background-color: ".$background_color.";
    }

    .flo-mobile-menu__close,
    .flo-mobile-menu .flo-header-mobile__logo,
    .flo-mobile-menu .flo-social-links__link,
    .flo-mobile-menu__search-toggle,
    .flo-mobile-menu__search-input,
    .flo-mobile-menu__search-submit,
    .flo-mobile-menu__menu a, .flo-mobile-menu__menu-dropdown-toggle
    {
      color: ". $elements_color ."!important;
    }

    .flo-mobile-menu__search-input {
      border-bottom-color: ". $elements_color ."!important;
    }
  "
])
<div class="flo-mobile-menu">
  <div class="flo-mobile-menu__top-wrap">
    @include('components.flo-header-mobile__logo')
    <div class="flo-mobile-menu__close flo-icon-close"></div>
  </div>

  <nav class="flo-mobile-menu__menu flo-mobile-menu__menu--style-{{ flo_get_option("flo-cube-mobile-menu__subemenu-style", "collapsed") }}">
    {{
      wp_nav_menu(
        array(
          'container' => '',
          'menu_class' => 'flo-mobile-menu__ul',
          'theme_location' => 'primary'
        )
      );
    }}
  </nav>

  <div class="flo-mobile-menu__search-wrap">
    <form class="flo-mobile-menu__search-form" action="{{ home_url("/") }}" method="get">
      <input type="text" name="s" value="" placeholder="type here to search.." class="flo-mobile-menu__search-input">
      <button type="submit" name="submit" class="flo-mobile-menu__search-submit">
        <i class="flo-icon-search"></i>
      </button>
    </form>
  </div>

  <div class="flo-mobile-menu__bottom-wrap">

    @include("components.flo-header__language-swithcher-area")
    @if (flo_get_option("flo-cube-mobile-menu__social-icons-display", true))
      <div class="flo-mobile-menu__social-links">
        @include('components.social-links')
      </div>
    @endif

    @if (flo_get_option("flo-cube-mobile-menu__search-display", true))
      <div class="flo-mobile-menu__search-toggle">
        <i class="flo-mobile-menu__search-toggle-icon-open flo-icon-search"></i>
      </div>
    @endif

  </div>

</div>
