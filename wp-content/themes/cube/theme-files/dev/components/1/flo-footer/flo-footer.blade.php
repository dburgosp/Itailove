<?php
    ob_start();
    ob_clean();
    dynamic_sidebar('full-content-area1');
    $full_content_1 = ob_get_clean();

    ob_start();
    ob_clean();
    dynamic_sidebar('full-content-area2');
    $full_content_2 = ob_get_clean();

    ob_start();
    ob_clean();
    dynamic_sidebar( 'footer-first' );
    $f1 = ob_get_clean();

    ob_start();
    ob_clean();
    dynamic_sidebar('footer-second');
    $f2 = ob_get_clean();

    ob_start();
    ob_clean();
    dynamic_sidebar('footer-third');
    $f3 = ob_get_clean();
?>

<?php
  $layout = flo_get_option("flo-cube-footer__layout", "a");
?>

@if ($layout == "a")
  @include('components.flo-footer-a')
@elseif ($layout == "b")
  @include('components.flo-footer-b')
@endif
