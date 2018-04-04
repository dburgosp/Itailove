<?php

$b = 'flo-admin-site-preview';

/* START: ENQUEUE SCRIPTS */
  wp_enqueue_script( 'flo_admin_site_preview_js', get_stylesheet_directory_uri() . '/app/flo-admin-site-preview/flo-admin-site-preview.js' );
/* END: ENQUEUE SCRIPTS */

/* START: PROCESS PREVIEW INITIAL SRC */
  if (isset($_GET['post'])) {
    $preview_src = get_permalink($_GET['post']);
  } else {
    $preview_src = site_url();
  }
/* END: PROCESS PREVIEW INITIAL SRC */

/* START: PREVIEW ASSETS */
  $preview_mobile_cursor_url = get_stylesheet_directory_uri() . '/assets/admin/img/mobile-cursor.svg';
/* END: PREVIEW ASSETS */
?>

<div class="<?php echo $b ?>">

  <div class="<?php echo $b ?>__trigger">
    Preview Your Site
  </div>

  <div class="<?php echo $b ?>__wrap">

    <div class="<?php echo $b ?>__tools">
      <div class="<?php echo $b ?>__tool--close">
        <i class="flo-admin-icon-angle-left"></i>
        BACK
      </div>

      <div class="<?php echo $b ?>__tool-set">
        <div class="<?php echo $b ?>__tool <?php echo $b ?>__tool--device <?php echo $b ?>__tool--active" data-size="desktop">
          <i class="flo-admin-icon-desktop"></i>
        </div>
        <div class="<?php echo $b ?>__tool <?php echo $b ?>__tool--device" data-size="laptop">
          <i class="flo-admin-icon-laptop"></i>
        </div>
        <div class="<?php echo $b ?>__tool <?php echo $b ?>__tool--device" data-size="tablet">
          <i class="flo-admin-icon-tablet"></i>
        </div>
        <div class="<?php echo $b ?>__tool <?php echo $b ?>__tool--device" data-size="phone">
          <i class="flo-admin-icon-phone"></i>
        </div>
        <div class="<?php echo $b ?>__tool <?php echo $b ?>__tool--refresh">
          <i class="flo-admin-icon-refresh"></i>
        </div>
      </div>

      <div class="<?php echo $b ?>__tool <?php echo $b ?>__tool--note" title="Click to close preview">
        Note: to see changes first update/publish the page.
      </div>
    </div>

    <div class="<?php echo $b ?>__preview-wrap loading" data-size="laptop">
      <iframe class="<?php echo $b ?>__preview" data-src="<?php echo $preview_src; ?>" width="100%" height="100%" data-mobile-cursor-url="<?php echo $preview_mobile_cursor_url ?>"></iframe>
    </div>
  </div>

</div>
