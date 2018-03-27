<div class="flo-header flo-header--menu-right sticky">
  @include("components.flo-header__logo")

  <div class="flo-header__menu-wrap">

    <div class="flo-header__top-wrap">
      <nav class="flo-header__menu ">
        {{
          wp_nav_menu(
            array(
            'theme_location' => 'primary'
            )
          );
        }}
          <span class="flo-icon-menu flo-header__hamburger"></span>
      </nav>
      @include('components.flo-header__search')
    </div>

    <div class="flo-header__bottom-wrap">
      @include('components.flo-header__social-links')
      @include("components.flo-header__language-swithcher-area")
    </div>

  </div>
</div>
