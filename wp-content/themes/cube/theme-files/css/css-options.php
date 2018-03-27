<?php

global $flo_options,
    $wp_head__options_css,
    $wp_head__options_css_desktop,
    $wp_head__options_css_tablet,
    $wp_head__options_css_phone;

$wp_head__options_css = array();
$wp_head__options_css_desktop = array();
$wp_head__options_css_tablet = array();
$wp_head__options_css_phone = array();

/* START: MIXINS */
/* -------------------------------- */
  // Start: Rem Calc
  if(!function_exists('rem_calc')){
    function rem_calc($value) {
      if (is_numeric($value)){
        return $value / 16 . "rem";
      } else return $value;
    }
  }
  // End: Rem Calc
/* END: MIXINS */

/* START: HEADER */
/* -------------------------------- */
  // Start: Logo Width
    if(isset($flo_options['flo-cube-header__logo-max-width']) && is_numeric($flo_options['flo-cube-header__logo-max-width']) ){
      $wp_head__options_css_desktop[] = "
        .flo-header__logo {
          max-width: ". rem_calc($flo_options['flo-cube-header__logo-max-width']) .";
        }

        /* by default teach side has reserved 35%. If the logo is much smaller it makes sense to use more space for the menu.*/
        .flo-header--menu-center .flo-header__menu-wrap{
          width: calc( (100% -  ".rem_calc($flo_options['flo-cube-header__logo-max-width']).")/2 );
        }
      ";
    }

    if(isset($flo_options['flo-cube-header__logo-max-width--tablet']) && is_numeric($flo_options['flo-cube-header__logo-max-width--tablet']) ){
      $wp_head__options_css_tablet[] = "
        .flo-header__logo {
          max-width: ". rem_calc($flo_options['flo-cube-header__logo-max-width--tablet']) .";
        }
        /* by default teach side has reserved 35%. If the logo is much smaller it makes sense to use more space for the menu.*/
        .flo-header--menu-center .flo-header__menu-wrap{
          width: calc( (100% -  ".rem_calc($flo_options['flo-cube-header__logo-max-width--tablet']).")/2 );
        }
      ";
    }

    if(isset($flo_options['flo-cube-header__logo-max-width--phone']) && is_numeric($flo_options['flo-cube-header__logo-max-width--phone']) ){
      $wp_head__options_css_phone[] = "
        .flo-header__logo {
          max-width: ". rem_calc($flo_options['flo-cube-header__logo-max-width--phone']) .";
        }
      ";
    }
  // End: Logo Width

  // Start: Colors
    if(isset($flo_options['flo-cube-header__background-color']) && strlen($flo_options['flo-cube-header__background-color'])){
      $header_bg_color = $flo_options['flo-cube-header__background-color'];
    }else{
      $header_bg_color = '#ffffff';
    }

    if(isset($flo_options['flo-cube-header__elements-color']) && strlen($flo_options['flo-cube-header__elements-color'])){
      $header_elements_color = $flo_options['flo-cube-header__elements-color'];
    }else{
      $header_elements_color = '#000000';
    }

    $wp_head__options_css[] = "
      .flo-header-sticky-wrapper:not(.is-sticky) .flo-header,
      .flo-header:not(.is-sticky) .flo-header,
      .flo-header
      {
        background-color: ".$header_bg_color.";
      }

      .flo-header-sticky-wrapper:not(.is-sticky) .flo-header,
      .flo-header-sticky-wrapper:not(.is-sticky) .flo-header .flo-header__logo,
      .flo-header-sticky-wrapper:not(.is-sticky) .flo-header input,
      .flo-header-sticky-wrapper:not(.is-sticky) .flo-header__menu > div > ul > .menu-item > a,

      header > .flo-header .flo-header__menu > div > ul > .menu-item > a,
      header > .flo-header .flo-header__top-wrap,
      header > .flo-header .flo-header__bottom-wrap,
      header > .flo-header .flo-header__logo,
      header > .flo-header input.flo-header__search-input
      {
        color: ".$header_elements_color.";
      }

      .flo-header-sticky-wrapper:not(.is-sticky) .flo-header input.flo-header__search-input,
      header > .flo-header input.flo-header__search-input {
        color: ".$header_elements_color.";
        border-color: ".$header_elements_color.";
      }
    ";
  // End: Colors

  // Start: Sticky Colors
    if(isset($flo_options['flo-cube-header__sticky-header-bg-color']) && strlen($flo_options['flo-cube-header__sticky-header-bg-color'])){
      $sticky_header_bg_color = $flo_options['flo-cube-header__sticky-header-bg-color'];
    }else{
      $sticky_header_bg_color = '#181818';
    }

    if(isset($flo_options['flo-cube-header__sticky-header-bg-opacity']) && is_numeric($flo_options['flo-cube-header__sticky-header-bg-opacity'])){
      $sticky_header_bg_opacity = $flo_options['flo-cube-header__sticky-header-bg-opacity'];
    }else{
      $sticky_header_bg_opacity = 100;
    }




    if(isset($flo_options['flo-cube-header__sticky-header-text-color']) && strlen($flo_options['flo-cube-header__sticky-header-text-color'])){
      $sticky_header_text_color = $flo_options['flo-cube-header__sticky-header-text-color'];
    }else{
      $sticky_header_text_color = '#ffffff';
    }

    $wp_head__options_css[] = "
      .flo-header-section .flo-header-sticky-wrapper.is-sticky .flo-header,
      .flo-header-section > .flo-header.is-sticky .flo-header,
      .is-sticky .flo-header
      {
        background-color: ".$sticky_header_bg_color.";
        opacity: ".($sticky_header_bg_opacity/100).";
      }

      .flo-header-sticky-wrapper.is-sticky .flo-header,
      .flo-header-sticky-wrapper.is-sticky .flo-header .flo-header__logo,
      .flo-header-sticky-wrapper.is-sticky .flo-header__menu > div > ul > .menu-item > a,
      .flo-header-sticky-wrapper.is-sticky .flo-header__search-wrap,
      .flo-header-sticky-wrapper.is-sticky .flo-header__bottom-wrap
      {
        color: ".$sticky_header_text_color.";
      }

      .flo-header-sticky-wrapper.is-sticky .flo-header input.flo-header__search-input {
        color: ".$sticky_header_text_color.";
        border-color: ".$sticky_header_text_color.";
      }
    ";
  // End: Sticky Colors

    // Header type 2: distance between menu items
    $menu_items_gutter_left = flo_get_option('flo-cube-header__menu-items-gutter-left',0);
    $menu_items_gutter_right = flo_get_option('flo-cube-header__menu-items-gutter-right',0);

    if($menu_items_gutter_left != 0){
      
      $wp_head__options_css_desktop[] = "
        .flo-header--menu-center .flo-header__menu-wrap--left .flo-header__menu > div > ul > li {
          margin-right: ".rem_calc($menu_items_gutter_left).";
        }
      ";
    }

    if($menu_items_gutter_right != 0){
      
      $wp_head__options_css_desktop[] = "
        .flo-header--menu-center .flo-header__menu-wrap--right .flo-header__menu > div > ul > li {
          margin-left: ".rem_calc($menu_items_gutter_right).";
        }
      ";
    }

/* END: HEADER */

/* START: GENERICS */
/* -------------------------------- */

  // Start: Button Styles
    $wp_head__options_css[] = "
      input[type='submit'], .flo-btn, .flo-card-4__open-post, .woocommerce button.button.alt, .woocommerce button.button.alt:hover,
      .woocommerce a.button.alt, .woocommerce a.button.alt:hover, .woocommerce input.button.alt, .woocommerce input.button.alt:hover,
      .woocommerce input.button, .woocommerce input.button:hover, .woocommerce button.button.alt.disabled, .woocommerce button.button.alt.disabled:hover
      {
        color: ".flo_get_option("flo-cube-button__text-color","#FFFFFF").";
        background-color: ".flo_get_option("flo-cube-button__background-color","#212121").";
      }
    ";
  // End: Button Styles

  $sidebar_width = flo_get_option("flo-sidebars__sidebar_width",3); // 3 olumns by default
  switch ($sidebar_width) {
    case 2: // 1/6
      $sidebar_width_val = '16%';
      break;
    case 3: // 1/4
      $sidebar_width_val = '25%';
      break;
    case 4: // 1/3
      $sidebar_width_val = '33%';
      break;
    case 5: // 5/12
      $sidebar_width_val = '42%';
      break;

    default: // 1/4
      $sidebar_width_val = '25%';
      break;
  }

  $wp_head__options_css[] = "
      .flo_sidebar {
        width: ".$sidebar_width_val.";
      }
    ";

/* END: GENERICS */

/* START: FLO POST */
/* -------------------------------- */

  // Start: Post Width
    if ($flo_post__width = get_field("flo-blog-post__content-width", "options")) {
      $flo_post__units = flo_get_option("flo-blog-post__content-width-sizing", "px");
      switch ($flo_post__units) {
        case 'px':
          $flo_post__width = $flo_post__width . "px";
        break;

        case 'rem':
          $flo_post__width = ($flo_post__width / 16). "rem";
        break;
      }
      $wp_head__options_css[] = "

        @media (min-width: 768px) {
          .flo-special-block,
          .flo-post--content,
          .flo-grid-2__row
          {
            width: " . $flo_post__width . ";
          }
          .flo-grid-2__row{
            max-width: " . $flo_post__width . ";
          }
        }
      ";
    }

    if ( isset($flo_options['flo-cube-blog-post__content-distance-below-images']) && is_numeric($flo_options['flo-cube-blog-post__content-distance-below-images']) ) {
      $wp_head__options_css[] = "
        .flo-post a img, .flo-post img, .flo-post img.alignleft, .flo-post img.alignright, .flo-post img.aligncenter {
          margin-bottom: " . $flo_options['flo-cube-blog-post__content-distance-below-images'] . "px;
        }
      ";
    }
  // End: Post Width

  // Horizintal border correction when we have horizontal padding for the body
  $horizontal_border = flo_get_option("flo-cube-pages__horizontal-border",0);

  if(is_numeric($horizontal_border) && $horizontal_border > 0){

    // 2 cols: 695 - (106x/95)
    $height_2_columns = 695 - (1.2*$horizontal_border);

    //http://www.wolframalpha.com/input/?i=interpolate+%5B(0,464),+(200,+348.5)+%5D
    $height_3_columns = 464 - (0.5775 * $horizontal_border);

    //347.5 - 0.075 x
    $height_4_columns = 347.5 - (0.17875 * $horizontal_border);

    $wp_head__options_css_desktop[] = "
        .flo-featured-posts-block__img {
          height: " . rem_calc($height_2_columns). ";
        }
        .medium-4 .flo-featured-posts-block__img{
          height: " . rem_calc($height_3_columns). ";
        }
        .medium-3 .flo-featured-posts-block__img{
          height: " . rem_calc($height_4_columns). ";
        }
    ";
  }

/* END: FLO POST */

/* START: FLO DEFAULT PAGE */
  if ( isset($flo_options['flo-page__default-content-width']) && is_numeric($flo_options['flo-page__default-content-width']) ) {
      $wp_head__options_css[] = "
        .page-content-wrap {
          max-width: " . $flo_options['flo-page__default-content-width'] . "%;
        }
        .press-page .page-content-wrap {
          width: " . $flo_options['flo-page__default-content-width'] . "%;
        }
      ";
    }
/* END: FLO DEFAULT PAGE */

//////GALLERY VIEW

$flo_gallery__grid_gutter = flo_get_option("flo-cube-portfolio-gallery__grid-gutter", 20) / 16;

$wp_head__options_css[] = "

    @media screen and (min-width: 768px)  {
        .flo-portfolio-grid .column,
        .flo-portfolio-grid .column {
          margin-left: ".($flo_gallery__grid_gutter / 2)."rem!important;
          margin-right: ".($flo_gallery__grid_gutter / 2)."rem!important;
        }

        .flo-portfolio-grid .column.medium-3, .flo-portfolio-grid .medium-3.columns {
          width: calc(100% / 4 - ".($flo_gallery__grid_gutter)."rem)!important;
        }

        .flo-portfolio-grid .column.medium-6, .flo-portfolio-grid .medium-6.columns {
          width: calc(100% / 2 - ".($flo_gallery__grid_gutter)."rem)!important;
        }

        .flo-portfolio-grid .column.medium-4, .flo-portfolio-grid .medium-4.columns {
          width: calc(100% / 3 - ".($flo_gallery__grid_gutter)."rem)!important;
        }

        .flo-portfolio-grid .row {
          margin-left: -".($flo_gallery__grid_gutter)."rem!important;
          margin-right: -".($flo_gallery__grid_gutter)."rem!important;
        }
    }

    .flo-portfolio-grid .column, .flo-portfolio-grid .columns{
        margin-bottom: ".$flo_gallery__grid_gutter."rem;
    }

    .flo-portfolio-grid .row:last-of-type {
        margin-bottom: ".$flo_gallery__grid_gutter."rem; }

";

/* START: FLO FOOTER */
  if(isset($flo_options['flo-cube-footer__background-color']) && strlen($flo_options['flo-cube-footer__background-color']) ){
    $wp_head__options_css[] = "
      .flo-footer{
        background-color: ".$flo_options['flo-cube-footer__background-color']."
      }
    ";
  }

  if(isset($flo_options['flo-cube-footer__top-padding']) && is_numeric($flo_options['flo-cube-footer__top-padding']) ){
    $footer_top_padding = 'padding-top: ' .($flo_options['flo-cube-footer__top-padding'] / 16).'rem; ';
  }

  if(isset($flo_options['flo-cube-footer__bottom-padding']) && is_numeric($flo_options['flo-cube-footer__bottom-padding']) ){
    $footer_bottom_padding = 'padding-bottom: ' .($flo_options['flo-cube-footer__bottom-padding'] / 16).'rem; ';
  }

  if(isset($footer_top_padding) || isset($footer_bottom_padding) ){
    $wp_head__options_css[] = "
      .flo-footer__content{
        ".$footer_top_padding."
        ".$footer_bottom_padding."
      }
    ";
  }

  if(isset($flo_options['flo-cube-footer__text-color']) && strlen($flo_options['flo-cube-footer__text-color']) ){
    $wp_head__options_css[] = "
      .flo-footer,
      .flo-footer__logo,
      .flo-footer__menu > div > ul > li > a,
      .flo-footer input[type]:not([type='submit']),
      .flo-footer button,
      .flo-footer__addons-copyright,
      .flo-footer__contact-block-message,
      .flo-footer__contact-block .flo-footer__contact-block-email,
      .flo-footer__contact-block .flo-footer__contact-block-phone
      {
        color: ".$flo_options['flo-cube-footer__text-color']."!important;
      }
    ";
  }
/* END: FLO FOOTER */


/* START: FINAL STYLE FOR WP_HEAD */
/* -------------------------------- */
  add_action('wp_head','wp_head__options_css',12);
  function wp_head__options_css() {
    global $wp_head__options_css;
    global $wp_head__options_css_desktop;
    global $wp_head__options_css_tablet;
    global $wp_head__options_css_phone;

    echo
      "<style type='text/css'>
        ". join("", $wp_head__options_css) ."
        @media screen and (min-width: 1025px) {
          ". join("", $wp_head__options_css_desktop) ."
        }

        @media screen and (min-width: 768px) and (max-width: 1024px) {
          ". join("", $wp_head__options_css_tablet) ."
        }

        @media screen and (max-width: 767px) {
          ". join("", $wp_head__options_css_phone) ."
        }
      </style>"
    ;
  }
/* END: FINAL STYLE FOR WP_HEAD */

?>
