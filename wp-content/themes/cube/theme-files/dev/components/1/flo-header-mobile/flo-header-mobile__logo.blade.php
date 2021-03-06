<a href="{{ get_home_url() }}" class="flo-header-mobile__logo">
  <?php
  $logo_type = flo_get_option("flo-cube-header__logo-type", "site-title");
  ?>
  @if($logo_type == "image")
    <?php
    if(isset($flo_options['flo-header__logo-image-mobile']) && strlen(trim($flo_options['flo-header__logo-image-mobile'])) ){
      $mobile_logo_url = $flo_options['flo-header__logo-image-mobile'];
    }else if(isset($flo_options['flo-cube-header__logo-image']) && strlen(trim($flo_options['flo-cube-header__logo-image'])) ){
      $mobile_logo_url = $flo_options['flo-cube-header__logo-image'];
    }else{
      $mobile_logo_url = '';
    }


    // START: Light Logo = mobile light logo - if not -> desktop light logo - if not -> mobile logo
      $light_mobile_logo_url = flo_get_option_strict(
        "flo-header__logo-image-mobile--light",
        flo_get_option_strict(
          "flo-cube-header__logo-image--light",
          $mobile_logo_url
        )
      );
    // END: Light Logo = mobile light logo - if not -> desktop light logo - if not -> mobile logo
    ?>
    <img class="flo-header-mobile__logo-image flo-header-mobile__logo-image--default"
    src="{{ $mobile_logo_url }}"
    alt=""/>

    <img class="flo-header-mobile__logo-image flo-header-mobile__logo-image--light"
    src="{{ $light_mobile_logo_url }}"
    alt=""/>

  @elseif($logo_type == "text")
    {{ flo_get_option("flo-cube-header__logo-text", "") }}
  @elseif($logo_type == "site-title" || !$logo_type)
    {{ get_bloginfo( 'name' ); }}
  @endif
</a>
