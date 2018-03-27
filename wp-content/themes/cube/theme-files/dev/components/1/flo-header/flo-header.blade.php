<?php
$flo_header__layout = flo_get_option("flo-cube-header__layout", "menu-right");
?>

@if($flo_header__layout == 'menu-left')
    @include('components.flo-header--menu-left')
@elseif($flo_header__layout == 'menu-right')
    @include('components.flo-header--menu-right')
@elseif($flo_header__layout == 'menu-center')
    @include('components.flo-header--menu-center')
  {{-- Start: Burger Types --}}
@elseif($flo_header__layout == "burger-right" || $flo_header__layout == "burger-left" || $flo_header__layout == "logo-center-burger-left" || $flo_header__layout == "logo-center-burger-right")

      <?php
        // Burger
        $flo_header__menu_trigger_background_color = flo_get_option("flo-cube-header__menu-trigger-background-color",'#f7f7f7');
        $flo_header__menu_trigger_icon_color = flo_get_option("flo-cube-header__menu-trigger-icon-color","#000000");

        // Burger + Hover
        $flo_header__menu_trigger_background_color_hover = flo_get_option("flo-cube-header__menu-trigger-background-color--hover","#000000");
        $flo_header__menu_trigger_icon_color_hover = flo_get_option("flo-cube-header__menu-trigger-icon-color--hover","#f7f7f7");

        // Burger on Sticky
        $flo_header__menu_trigger_background_color_sticky = flo_get_option("flo-cube-header__menu-trigger-background-color--sticky",'#f7f7f7');
        $flo_header__menu_trigger_icon_color_sticky = flo_get_option("flo-cube-header__menu-trigger-icon-color--sticky","#000000");

        // Burger on Sticky + Hover
        $flo_header__menu_trigger_background_color_sticky_hover = flo_get_option("flo-cube-header__menu-trigger-background-color--sticky-hover","#000000");
        $flo_header__menu_trigger_icon_color_sticky_hover = flo_get_option("flo-cube-header__menu-trigger-icon-color--sticky-hover","#ffffff");
      ?>
      @include('core.style', [
        'breakpoint__medium_up' => "
          .flo-header__hamburger {
            background-color: ".$flo_header__menu_trigger_background_color.";
            color: ".$flo_header__menu_trigger_icon_color.";
          }
          .flo-header__hamburger:hover {
            background-color: ".$flo_header__menu_trigger_background_color_hover.";
            color: ".$flo_header__menu_trigger_icon_color_hover.";
          }

          .is-sticky .flo-header__hamburger {
            background-color: ".$flo_header__menu_trigger_background_color_sticky.";
            color: ".$flo_header__menu_trigger_icon_color_sticky.";
          }
          .is-sticky .flo-header__hamburger:hover {
            background-color: ".$flo_header__menu_trigger_background_color_sticky_hover.";
            color: ".$flo_header__menu_trigger_icon_color_sticky_hover.";
          }
        "
      ])

      @if($flo_header__layout == 'burger-left')
        @include('components.flo-header--burger-left')
      @elseif($flo_header__layout == 'burger-right')
        @include('components.flo-header--burger-right')

      @elseif($flo_header__layout == 'logo-center-burger-left')
        @include('components.flo-header--logo-center-burger-left')

      @elseif($flo_header__layout == 'logo-center-burger-right')
        @include('components.flo-header--logo-center-burger-right')

      @endif

      @include('components.flo-header-popup')
  {{-- End: Burger Types --}}
@endif
