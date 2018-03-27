<?php

  function wp_action_css_process () {
    // Start: Get CSS Process Data
      global $d;
      $d = [];

      

      include "css-process__data.php";

      $css_process__data = $d;
    // End: Get CSS Process Data

    $css_process__css = file_get_contents(__DIR__ . "/css-process__css.css");

    

    css_process($css_process__css, $css_process__data);
  }

  // Start: CSS Mixins and Variables
  if(!function_exists('rem_calc')){
    function rem_calc($value) {
      if (is_numeric($value)){
        return $value / 16 . "rem";
      } else return $value;
    }
  }

  // A shortcut function for future function acf requests
  function get_acf($field_name, $from_options = true) {
    $value = null;
    if ($from_options == true) {
      $value = get_field($field_name, 'options');
    } else if ($from_options == false) {
      $value = get_field($field_name);
    }
    return $value;
  }

  // Makes an entry in the $d array with the key == acf field name and value from that field
  function d_acf($field_name, $postfix = null, $from_options = true) {
    global $d;

    $d[$field_name] = get_acf($field_name, $from_options) . $postfix;
  }

  // Same as d acf but does rem_calc to the result
  function d_acf_rem($field_name, $from_options = true) {
    global $d;

    $d[$field_name] = rem_calc(get_acf($field_name, $from_options));
  }
// End: CSS Mixins and Variables
  function css_process($css, $data) {
    foreach ($data as $key => $value) {
      $css = str_replace('"$'.$key.'"', $value, $css);
    }

    // NOTE: Alex G said we need to use some super important WP stuff here
    file_put_contents(__DIR__ . "/../public/css/style.css", $css);
  }

  if ( defined("FLO_ENVIROMENT") && FLO_ENVIROMENT == "DEV" ) {
    wp_action_css_process();
    //add_action( 'plugins_loaded', 'wp_action_css_process' );
  }
  else {
    add_action('acf/save_post', 'wp_action_css_process', 20);
  }

?>
