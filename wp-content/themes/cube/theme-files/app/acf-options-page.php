<?php

if( function_exists('acf_add_options_page') ) {

	// create custom  settings menu
  add_action('admin_menu', 'flo_create_options_menu');
  if(!function_exists('flo_create_options_menu')){
    function flo_create_options_menu(){

      //create new top-level menu
      add_menu_page(__('Flothemes Settings','flotheme'), __('Flotheme','flotheme'), 'administrator', 'theme-general-settings' , 'flo_main_settings_page', get_template_directory_uri() . '/assets/img/flothemes.png' );

    }
  }

	// add in the array bellow the new subpages
	$subpages_options = array(
    array(
      'page_title' => __('1. Introduction','flotheme'),
      'menu_title' => __('1. Introduction','flotheme')
    ),
    array(
      'page_title' => __('2. Getting Started','flotheme'),
      'menu_title' => __('2. Getting Started','flotheme')
    ),
    array(
      'page_title' => __('3. Header Settings','flotheme'),
    	'menu_title' => __('3. Header','flotheme')
    ),
    array(
      'page_title' => __('4. Pages Settings','flotheme'),
      'menu_title' => __('4. Pages','flotheme')
    ),
    array(
      'page_title' => __('5. Generics Settings','flotheme'),
      'menu_title' => __('5. Generics','flotheme')
    ),
    array(
      'page_title' => __('6. Sidebars Settings','flotheme'),
      'menu_title' => __('6. Sidebars','flotheme')
    ),
    array(
      'page_title' => __('7. Footer Settings','flotheme'),
    	'menu_title' => __('7. Footer','flotheme')
    ),
    array(
      'page_title' => __('8. Typography','flotheme'),
    	'menu_title' => __('8. Typography','flotheme')
    ),
    array(
      'page_title' => __('9. Stylekit 2','flotheme'),
      'menu_title' => __('9. Stylekit 2','flotheme')
    ),
	);


	foreach ($subpages_options as $key => $subpage) {
		acf_add_options_sub_page(array(
			'page_title' 	=> $subpage['page_title'],
			'menu_title'	=> $subpage['menu_title'],
			'parent_slug'	=> 'theme-general-settings',
		));
	}

  if(!function_exists('flo_main_settings_page')){
    function flo_main_settings_page(){
      $page_flotheme_data = admin_flotheme_page_data();
      ?>

      <div class="page-flotheme">

        <div class="page-flotheme-title-wrap">
          <div class="page-flotheme-title-wrap__icon">
            <i class="flo-admin-icon-flothemes"></i>
          </div>
          <div class="page-flotheme-title-wrap__title">
            <?php echo $page_flotheme_data["page-flotheme__title"] ?>
          </div>
        </div>

        <div class="page-flotheme-items">
          <?php
            $i = 1;
            foreach ($page_flotheme_data["page-flotheme__items"] as $item) {
            ?>

              <a class="page-flotheme-items__item" <?php echo flo_data($item, "extra_attr", "") ?> href="<?php echo $item["url"] ?>">
                <span class="page-flotheme-items__item-index">
                  <?php echo $i; ?>.
                </span>
                <span class="page-flotheme-items__item-title">
                  <?php echo $item["title"] ?>
                </span>
                <span class="page-flotheme-items__item-description">
                  <?php echo $item["description"] ?>
                </span>
              </a>

            <?php
              $i++;
            }
          ?>
        </div>

      </div>
      <?php
    }
  }

}
