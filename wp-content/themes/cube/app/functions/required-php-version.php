<?php


/**
 * Check if ACF is activated, then disable it
 * if acf plugins is activated, we disable it to avoid conflicts
 * @since    1.0.0
 */
if(!function_exists('flo_maybe_deactivate_acf')){
  function flo_maybe_deactivate_acf() {
    if(is_admin()){
      
      // this should make the 'is_plugin_active' function available
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  
      if(function_exists('is_plugin_active')){
        if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) || 
           is_plugin_active( 'advanced-custom-fields-pro/acf.php') ||
           is_plugin_active( 'acf-plugin/acf.php')
          ) {

          deactivate_plugins('advanced-custom-fields/acf.php');
          deactivate_plugins('advanced-custom-fields-pro/acf.php');
          deactivate_plugins('acf-plugin/acf.php');
          add_action( 'admin_notices', array($this,'flo_acf_deactivated_admin_notice') );

          // reload the current page to avoid warnings
          header("Location: ".$_SERVER['PHP_SELF']);
        }
      }

      if(function_exists('is_plugin_active_for_network')){
        if ( is_plugin_active_for_network( 'advanced-custom-fields/acf.php' ) || 
           is_plugin_active_for_network( 'advanced-custom-fields-pro/acf.php') ||
           is_plugin_active_for_network( 'acf-plugin/acf.php')
          ) {

          deactivate_plugins('advanced-custom-fields/acf.php');
          deactivate_plugins('advanced-custom-fields-pro/acf.php');
          deactivate_plugins('acf-plugin/acf.php');
          add_action( 'admin_notices', array($this,'flo_acf_deactivated_admin_notice') );

          // reload the current page to avoid warnings
          header("Location: ".$_SERVER['PHP_SELF']);
        }
      }
      

    }

  }
}
add_action( 'init', 'flo_maybe_deactivate_acf' );
add_action( 'customize_preview_init', 'flo_maybe_deactivate_acf' );

add_action( 'customize_preview_init', 'flo_customizer_live_preview' );
function flo_customizer_live_preview(){
  if ( version_compare(phpversion(), THEME_REQUIRED_PHP_VERSION, '<') ){
    die(
      sprintf(__( 'This server is using PHP version %s, but this theme requires at least version %s. %s Contact please your hosting or webmaster and ask them to upgrade the PHP to a newer version.','flotheme'),PHP_VERSION, THEME_REQUIRED_PHP_VERSION, '<br/>' ) );
  }
}

add_action( 'after_switch_theme', 'flo_check_theme_setup' );
function flo_check_theme_setup($old_theme_name){

  // Compare versions.
  if ( version_compare(phpversion(), THEME_REQUIRED_PHP_VERSION, '<') ) :

  // Theme not activated info message.
  //add_action( 'admin_notices', 'flo_admin_notice' );
  function flo_admin_notice() {
  ?>
    <div class="notice notice-warning" style="padding: 20px; font-size: 24px; line-height: 1.4;">
      <?php echo sprintf(__( 'You need to update your PHP version to run %s.', 'flotheme' ),_TN_); ?> <br />
      <?php echo sprintf(__( 'Actual version is: %s, while the required is %s', 'flotheme' ),'<b><u>'.phpversion().'</u></b>', '<b><u>'.THEME_REQUIRED_PHP_VERSION.'</u></b>') ?>
    </div>
  <?php
  }

  // Switch back to previous theme.
  switch_theme( get_option( 'theme_switched' ) );
    return false;

  endif;
}

?>
