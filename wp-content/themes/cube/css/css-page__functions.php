<?php
  $css_page__css = array();

  /* START: ADD CSS TO PAGE CSS ARRAY */
    function css_page__add_start() {
      ob_start();
    }

    function css_page__add_end() {

      $css = ob_get_contents();
      ob_end_clean();

      global $css_page__css;
      $css_page__css[] = $css;
    }
  /* END: ADD CSS TO PAGE CSS ARRAY */

  /* START: ON PAGE LOAD ADD CSS FROM PAGE CSS ARRAY TO HEAD */
    add_action('init', 'flo_css_page');
    function flo_css_page() {
      add_action('wp_head', 'flo_css_page__wp_head', 20);
    }

    if (!function_exists("flo_css_page__wp_head")) {
      function flo_css_page__wp_head() {
        global $css_page__css;

        // array_unshift($css_page__css, "<style>");
        // $css_page__css[] = "</style>";

        echo join(" ", $css_page__css);
      }
    }
  /* END: ON PAGE LOAD ADD CSS FROM PAGE CSS ARRAY TO HEAD */
?>
