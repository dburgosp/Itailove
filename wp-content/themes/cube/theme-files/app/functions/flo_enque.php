<?php
  function flo_enque() {
    $flo_theme = wp_get_theme('cube');

    $theme_version = $flo_theme->get( 'Version' );

    $siteurl = get_option('siteurl');
    if (!empty($siteurl)) {
      $siteurl = rtrim($siteurl, '/') . '/wp-admin/admin-ajax.php';
    } else {
      $siteurl = home_url('/wp-admin/admin-ajax.php');
    }

    // Start: Core Icons
      wp_register_style("core-icons", get_template_directory_uri() . "/public/fonts/fontello/css/flo-core-icons.css", false, $theme_version );
      wp_enqueue_style("core-icons");
    // End: Core Icons

    // Start: Vendor CSS
      wp_register_style("vendor-css", get_template_directory_uri() . "/theme-files/public/css/vendor.css", false, $theme_version );
      wp_enqueue_style("vendor-css");
    // End: Vendor CSS

    // Default theme stylesheet
      wp_register_style( 'default_stylesheet',get_stylesheet_directory_uri() . '/style.css', false, $theme_version );
      wp_enqueue_style( 'default_stylesheet' );

    // we need this for comment-reply
    if (is_singular()) {
        wp_enqueue_script("comment-reply");
    }

    

    
      

    if ( defined('FLO_ENVIROMENT') && FLO_ENVIROMENT == 'DEV') {
      // Start: Theme CSS
      wp_register_style("theme-css", get_template_directory_uri() . "/theme-files/public/css/style.css", false, $theme_version );

      //Vendor JS
      wp_register_script("vendor-js", get_template_directory_uri() . "/theme-files/public/js/vendor.js", array("jquery"), $theme_version, $in_footer = true);

      //Theme JS
      wp_register_script("theme-js", get_template_directory_uri() . "/theme-files/public/js/scripts.js", array("jquery"), $theme_version, $in_footer = true);
    }else{
      //Theme CSS
      wp_register_style("theme-css", get_template_directory_uri() . "/theme-files/public/css/style.min.css", false, $theme_version );

      //Vendor JS
      wp_register_script("vendor-js", get_template_directory_uri() . "/theme-files/public/js/vendor.js", array("jquery"), $theme_version, $in_footer = true);

      //Theme JS
      wp_register_script("theme-js", get_template_directory_uri() . "/theme-files/public/js/scripts.min.js", array("jquery"), $theme_version, $in_footer = true);
    }

    wp_enqueue_style("theme-css");

    wp_enqueue_script("vendor-js");
    wp_enqueue_script("theme-js");

    $siteurl = get_option('siteurl');
    if (!empty($siteurl)) {
      $siteurl = rtrim($siteurl, '/') . '/wp-admin/admin-ajax.php';
    } else {
      $siteurl = home_url('/wp-admin/admin-ajax.php');
    }
    wp_localize_script( 'theme-js', 'ajaxurl', $siteurl );

    // Start: Options CSS
      // wp_register_style("options-css", get_template_directory_uri() . "/theme-files/public/css/options.css" );
      // wp_enqueue_style("options-css");
    // End: Options CSS
  }

  add_action('wp_enqueue_scripts', 'flo_enque');
?>
