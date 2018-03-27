<!-- Start: Mobile Menu -->
<div class="flo-mobile-menu flo-mobile-menu--full-height flo-mobile-menu--top">
    @if ( has_nav_menu( 'primary' ) )
        <nav class="flo-mobile-menu__nav">
            <a href="" class="flo-mobile-menu__logo">Mobile Logo (hrdcoded)</a>
            {{
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container' => '',
                    'menu_class'     => 'primary-menu menu flo-mobile-menu__ul',
                ) );
            }}
        </nav>
        <span class="flo-mobile-menu__hamburger flo-icon-menu"></span>
        <span class="flo-mobile-menu__close flo-icon-cancel"></span>
    @endif

</div>
<!-- End: Mobile Menu -->
