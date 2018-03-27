<a href="{{ get_home_url() }}" class="flo-header__logo">
  <?php
    $logo_type = flo_get_option("flo-cube-header__logo-type", "site-title");
  ?>
  @if($logo_type == "image")
  	<?php

  		$main_logo_url = '';
  		if(isset($flo_options['flo-cube-header__logo-image']) && strlen(trim($flo_options['flo-cube-header__logo-image']))){
  			$main_logo_url = $flo_options['flo-cube-header__logo-image'];
  		}

  		if(isset($flo_options['flo-cube-header__logo-image-sticky-header']) && strlen(trim($flo_options['flo-cube-header__logo-image-sticky-header']))){
  			$sticky_logo_url = $flo_options['flo-cube-header__logo-image-sticky-header'];
  		}else{
  			$sticky_logo_url = $main_logo_url;
  		}

  		if(isset($flo_options['flo-cube-header__logo-image--light']) && strlen(trim($flo_options['flo-cube-header__logo-image--light']))){
  			$light_logo_url = $flo_options['flo-cube-header__logo-image--light'];
  		}else{
  			$light_logo_url = $main_logo_url;
  		}
  	?>
    <img class="flo-header__logo-image flo-header__logo-image--default" src="{{ $main_logo_url }}" alt="" />
    <img class="flo-header__logo-image flo-header__logo-image--sticky" src="{{ $sticky_logo_url }}" alt="" />
    <img class="flo-header__logo-image flo-header__logo-image--light" src="{{ $light_logo_url }}" alt="" />
  @elseif($logo_type == "text")
    {{ flo_get_option("flo-cube-header__logo-text", "") }}
  @elseif($logo_type == "site-title" || !$logo_type)
    {{ get_bloginfo( 'name' ); }}
  @endif
</a>
