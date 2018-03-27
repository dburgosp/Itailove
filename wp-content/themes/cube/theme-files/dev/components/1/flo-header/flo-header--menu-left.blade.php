<div class="flo-header flo-header--menu-left">
  <div class="flo-header__menu-wrap">

    <div class="flo-header__top-wrap">

      @include('components.flo-header__search')

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
    </div>

    <div class="flo-header__bottom-wrap">
      @include("components.flo-header__language-swithcher-area")
      @include('components.flo-header__social-links')
    </div>

  </div>

  @include("components.flo-header__logo")
</div>
