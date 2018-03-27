@if ( has_nav_menu( 'primary' ) )
    <nav class="flo-header__menu flo-header__menu--left"> <!-- change the menu class if necessary -->
        {{
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container' => '',
                'menu_class'     => 'menu menu-desktop',
            ) );
        }}
    </nav>
@endif
