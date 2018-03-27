<div class="flo-header flo-header--menu-center">

  <div class="flo-header__menu-donor">
    {{
      wp_nav_menu(
        array(
        'theme_location' => 'primary'
        )
      );
    }}
  </div>

  <div class="flo-header__menu-wrap flo-header__menu-wrap--left">

    <div class="flo-header__top-wrap">
      <nav class="flo-header__menu ">
        <div class="">
          <ul>

          </ul>
        </div>
      </nav>
    </div>
  </div>

  @include("components.flo-header__logo")

  <div class="flo-header__menu-wrap flo-header__menu-wrap--right">

    <div class="flo-header__top-wrap">
      <nav class="flo-header__menu ">
        <div class="">
          <ul>

          </ul>
        </div>
      </nav>
      @include('components.flo-header__search')
    </div>

    <div class="flo-header__bottom-wrap">
      @include('components.flo-header__social-links')
      @include("components.flo-header__language-swithcher-area")
    </div>

  </div>

</div>
