<?php

define('_TN_', wp_get_theme());
define('FLOTHEME_CUSTOMIZED', false); // set to TRUE if you changed something in the source code.
define('_FLO_NEWS_', 'https://flothemes.com/blog');
define('_FLO_SUPPORT_', 'https://flothemes.zendesk.com/anonymous_requests/new');
define('_FLO_CORE_', 'Enzo');
/*============================================*/
// Minimum required version.
/*============================================*/
define( 'THEME_REQUIRED_PHP_VERSION', '5.4.0' );

/**
 * Init our WordPress Theme.
 */

// check if the required PHP version is available
include_once get_template_directory() . '/app/functions/required-php-version.php';

// Include Theme Functions
include_once get_template_directory() . '/theme-files/theme-functions.php';

//Include ACF
include_once get_template_directory() . '/theme-files/app/include-acf.php';

require_once( __DIR__ . '/vendor/autoload.php' );
\Classy\Classy::get_instance();

if ( ! isset( $content_width ) ) {
	// $content_width is used by Jetpack Photon and other plugins
	$max_content_width = get_field("flo-blog-post__content-width", "options");

  $max_content_width =  apply_filters('flo_max_content_width', $max_content_width);

	if($max_content_width && is_numeric($max_content_width) && $max_content_width > 0){
		$content_width = $max_content_width;
	}else{
		$content_width = 690;
	}

}

// Include Flo Main
include_once get_template_directory() . '/theme-files/app/functions/flo_main.php';


/**
 *
 * Run stuff after WP is initiated
 *
 */
add_action('init','flo_include_stuff');
function flo_include_stuff(){
	define('FLOTHEME_THEME_VERSION', flo_theme_data_variable('Version'));

	require_once('app/functions/update.php');
}

/* START: WOOCOMMERCE */
  remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
  remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);


  add_action('woocommerce_before_main_content', 'flo_theme_wrapper_start', 10);
  add_action('woocommerce_after_main_content', 'flo_theme_wrapper_end', 10);

  function flo_theme_wrapper_start() {
    echo '<div class="flo-woocommerce-wrap">';
  }

  function flo_theme_wrapper_end() {
    echo '</div>';
  }

  add_action( 'after_setup_theme', 'woocommerce_support' );
  function woocommerce_support() {
      add_theme_support( 'woocommerce' );
  }
/* END: WOOCOMMERCE */

include_once get_template_directory() . '/theme-build.php';

// Include Actions
include_once get_template_directory() . '/theme-files/app/functions/actions-register.php';



//Include ACF Options page
include_once get_template_directory() . '/theme-files/app/acf-options-page.php';

/************ Plugin recommendations **********/
include_once get_template_directory() . '/theme-files/app/plugins-recommendation/plugin-recommendations.php';

// Include theme enque
include_once get_template_directory() . '/theme-files/app/functions/flo_enque.php';

// Include CSS Processor
include_once get_template_directory() . '/css/css.php';

// Include Admin Functions
include_once 'flo-admin-functions.php';

// Include ACF Functions
include_once 'acf/acf__functions.php';
