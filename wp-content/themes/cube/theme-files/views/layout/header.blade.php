
<?php

  $dropdown__background_color = flo_get_option("flo-cube-header__menu-dropdown-background-color","#000000");
  $dropdown__items_color = flo_get_option("flo-cube-header__menu-dropdown-items-color","#ffffff");

?>

@include('core.style', [
  "breakpoint__medium_up" => "
    .flo-header__menu .menu-item ul {
      background-color: ". $dropdown__background_color .";
    }
    .flo-header__menu .menu-item ul a {
      color: ". $dropdown__items_color .";
    }
  "
])
<?php
    $feat_img_enabled = true;
    if($post->post_type == 'gallery'){
        $feat_img_enabled = flo_get_option('gallery_featured_image', true);
    }
?>

@if(isset($header__slider_id))
    @include("components.flo-header-section")
@elseif(isset($header__hero_post) && has_post_thumbnail($header__hero_post) && $feat_img_enabled)
    @include("components.flo-mobile-menu")
    @include('components.flo-header-mobile')
    <header class="full-scrollable-section">
        <!-- Start: Flo Header Section -->
        <div class="flo-section flo-header-section flo-header-section--layout-page-title">
            @include("components.flo-header")
            @include("components.flo-page-hero")
        </div>
        <!-- End: Flo Header Section -->
    </header>
@else
    @include("components.flo-mobile-menu")
    @include('components.flo-header-mobile')
    <header>
        @include("components.flo-header")
    </header>
@endif

<?php 
  ob_start();
  ob_clean();
  dynamic_sidebar('top-sidebar');
  $top_sidebar = ob_get_clean();

  if($top_sidebar){
?>
    <div class="flo-header-sidebars flo-section flo-section--padding-small">
                {{$top_sidebar}}
    </div>
<?php
  }
?>
