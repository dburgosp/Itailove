<?php

  function flo_admin_customisation() {
    $flo_theme = wp_get_theme();

    $theme_version = $flo_theme->get( 'Version' );

    /* Start: Styles */
      wp_register_style('AdminACFStyle', get_template_directory_uri().'/assets/admin/css/admin.css' );
      wp_enqueue_style('AdminACFStyle', 'acf-input');
    /* End: Styles */

    /* Start: Scripts */
      wp_register_script("AdminScripts", get_template_directory_uri().'/assets/admin/js/admin.js', array("jquery"), $theme_version);
      wp_enqueue_script("AdminScripts");
    /* End: Scripts */

  }

  add_action('admin_init', 'flo_admin_customisation');

  function sample_admin_notice__success() {
    include 'app/flo-admin-site-preview/flo-admin-site-preview.php';
  }
  $enable_admin_preview = get_field('flo-admin-settings__disable-flo-admin-preview', 'options');
  if( !$enable_admin_preview ) add_action( 'admin_notices', 'sample_admin_notice__success' );

?>
