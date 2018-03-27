<?php
	require_once "vc/vc.php";

  add_action('wp_head','flo_header_css',10);
  if(!function_exists('flo_header_css')){
    function flo_header_css(){
      //include_once get_template_directory() . '/theme-files/css/css.php';
      include_once("css/css-options.php");
    }
  }


  define('_FLO_DOCS_', 'http://docs.flothemes.com/cube-general-description/');

	// Include the default fonts that are shipped with this theme
	include_once 'app/fonts-theme-default.php';

  /* START: ACF LOCAL JSON */
    add_filter('acf/settings/save_json', 'my_acf_json_save_point');
    function my_acf_json_save_point( $path ) {

      // update path
      $path = get_template_directory() . '/theme-files/acf-fields/local_json';
      // return
      return $path;

    }

    add_filter('acf/settings/load_json', 'my_acf_json_load_point');
    function my_acf_json_load_point( $paths ) {
        // remove original path (optional)
        unset($paths[0]);

        // append path
        $paths[] = get_template_directory() . '/theme-files/acf-fields/local_json';

        // return
        return $paths;

    }
  /* END: ACF LOCAL JSON */

  /* START: FOR FRONTEND */
    if (!function_exists("flo_get_the_date")) {
      function flo_get_the_date ($post_id) {
        return date_i18n(get_option('date_format'), get_the_time('U', $post_id));
      }
    }

    // Start: WP Comments Move textarea to bottom
      function wpb_move_comment_field_to_bottom( $fields ) {
        $comment_field = $fields['comment'];
        unset( $fields['comment'] );
        $fields['comment'] = $comment_field;
        return $fields;
      }

      add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );
    // Start: WP Comments Move textarea to bottom

  /* END: FOR FRONTEND */

  /* START: ADMIN -> FLOTHEME PAGE DATA */
  if (!function_exists("admin_flotheme_page_data")) {
    function admin_flotheme_page_data() {
      return array(
        "page-flotheme__title" => "Cube",
        "page-flotheme__items" => array(

          array(
                  "title" => __("INTRODUCTION","flotheme"),
                  "description" => __("We recommend that you start your journey here and learn about the backend and theme structure. This will make the setup process easier.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-1-introduction"
                ),
                array(
                  "title" => __("GETTING STARTED","flotheme"),
                  "description" => __("Experienced, or first time Flotheme user, your first setup steps will begin here.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-2-getting-started"
                ),
                array(
                  "title" => __("HEADER","flotheme"),
                  "description" => __("In this area you will find all the necessary info and settings to adjust the website Header to fit your style.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-3-header"
                ),
                array(
                  "title" => __("PAGES","flotheme"),
                  "description" => __("Each Page is unique and has its own set of features. Click here to learn more about these options.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-4-pages"
                ),
                array(
                  "title" => __("GENERICS","flotheme"),
                  "description" => __("The following area includes a few general and miscellaneous settings such as Social Media links, CSS, etc.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-5-generics"
                ),
                array(
                  "title" => __("SIDEBARS","flotheme"),
                  "description" => __("Widgets are great 'add-on' options to your site that can help you customize the site and add more feature to the back-end as well.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-6-sidebars"
                ),
                array(
                  "title" => __("FOOTER","flotheme"),
                  "description" => __("Copyrights and other important footer related can be added in the following section.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-7-footer"
                ),
                array(
                  "title" => __("TYPOGRAPHY","flotheme"),
                  "description" => __("Fonts and all settings related to them are located in here.","flotheme")
                  ,
                  "url" => get_admin_url() . "admin.php?page=acf-options-8-typography"
                ),
                array(
                  "title" => __("STYLEKIT 2","flotheme"),
                  "description" => __("Here you will find the tutorial on how to replicate the second stylekit.","flotheme")
                  ,
                  "url" => "http://docs.flothemes.com/cube-cube-2/",
                  "extra_attr" => "target='_blank'"
                ),

        )
      );
    }
  }
/* END: ADMIN -> FLOTHEME PAGE DATA */


  /**
   *
   * Add the necessary body classed depending on options
   *
   */
  if(!function_exists('flo_cube_body_class')){
    function flo_cube_body_class($classes) {
      global $flo_options;
      if(isset($flo_options['flo-cube-header__sticky-header']) && $flo_options['flo-cube-header__sticky-header'] ){
        $classes[] = 'has-sticky';
      }

      if(flo_get_option('flo-cube-setions__fade-effect-disable')){
        $classes[] = 'flo-appear-disabled'; // disable the blocks fade in effect
      }

      return $classes;
    }
  }

  add_filter('body_class', 'flo_cube_body_class');

  if(!function_exists('flo_add_menu_stuff')){
    function flo_add_menu_stuff($items, $args){
      // deb_e($items);
      // deb_e($args);
    }
  }
//  add_filter( 'wp_nav_menu_items', 'flo_add_menu_stuff', 10, 2 );

if (!has_action('flo_footer_credits')) {
  add_action('flo_footer_credits', 'flo_get_footer_credits', 10);
}

if (!function_exists('flo_get_footer_credits')) {
  function flo_get_footer_credits()
  {
    echo ' <a class="flo-footer__flothemes-logo flo-footer__copyrights-flo" href="http://flothemes.com/" target="_blank"><i class="flo-core-icon-flothemes"></i></a>';
  }

}

/**
 *
 * We need to overwrite the default registered menus
 *
 */
add_filter('flo_maybe_register_menus','flo_register_como_menus');
if(!function_exists('flo_register_como_menus')){
  function flo_register_como_menus(){
    $theme_menus = array(
        'primary' => __('Primary Menu', 'flotheme'),
        'footer-menu' => __( 'Footer Menu', 'flotheme' ),
      );

    return $theme_menus;
  }
}



/* Built in form submission */
add_action('wp_ajax_floSendContact' , 'flo_send_contact' );
add_action('wp_ajax_nopriv_floSendContact' , 'flo_send_contact' );

/**
 *
 * Fuction that validates the contact form and sends the email
 *
 */
if(!function_exists('flo_send_contact')){
  function flo_send_contact(){
    if (isset($_POST['action']) && $_POST['action'] == 'floSendContact') {

      if(isset($_POST['pid']) && (is_numeric($_POST['pid']) || 'options' == $_POST['pid'] ) ){

        $post_id = $_POST['pid'];
        $tomail   = get_field( 'flo-contact-page__contact_email' ,$_POST['pid']);

        if(!is_email($tomail)){
          $result['error_message'] = '<p class="text-error">' . __('Please add a valid contact email. ', 'flotheme') . '</p>';
        }

        $frommail = '';

        if (isset($_POST['flo-name']) && strlen($_POST['flo-name'])) {
          $name = trim($_POST['flo-name']);
        } else {
          $result['contact_name'] = '<p class="text-error">' . __('Error, name is required field. ', 'flotheme') . '</p>';
          $name                   = '';
        }

        if (isset($_POST['flo-email']) && is_email($_POST['flo-email'])) {
          $frommail = trim($_POST['flo-email']);
        } else {

          $result['contact_email'] = '<p class="text-error">' . __('Error, please enter a valid email address. ', 'flotheme') . '</p>';

        }

        $message = '';
        if (isset($_POST['flo-name'])) {
          $message .= __('Contact name: ', 'flotheme') . trim($_POST['flo-name']) . "\n";
        }
        if (isset($_POST['flo-email'])) {
          $message .= __('Contact email: ', 'flotheme') . trim($_POST['flo-email']) . "\n";
        }
        if (isset($_POST['flo-phone'])) {
          $message .= __('Phone: ', 'flotheme') . trim($_POST['flo-phone']) . "\n\n";
        }
        if (isset($_POST['flo-date'])) {
          $message .= __('Date: ', 'flotheme') . trim($_POST['flo-date']) . "\n\n";
        }
        if (isset($_POST['flo-location'])) {
          $message .= __('Location: ', 'flotheme') . trim($_POST['flo-location']) . "\n\n";
        }
        if (isset($_POST['flo-subject'])) {
          $message .= __('Subject: ', 'flotheme') . trim($_POST['flo-subject']) . "\n\n";
        }
        $message .= trim($_POST['flo-message']);

        if (is_email($tomail) && strlen($tomail) && strlen($frommail) && strlen($name) && strlen($message)) {

          //flo-contact_email_address
          $email_from = get_field('page-contact__use-reply-to-header', $post_id);



          // if this option is enabled, then we will use the visitor email in the Form field
            if( $email_from ){

                $headers = array();

                $headers[] = 'From: ' . trim($_POST['flo-name']) . ' <' . trim($_POST['flo-email']). '>';


                $headers[] = 'Reply-To: ' . trim($_POST['flo-email']);


            }else{
                    $headers = array();
            }


          $subject = __('New email from', 'flotheme') . ' ' . get_bloginfo('name') . '.' . __(' Sent via contact form.', 'flotheme');

          $maybe_send_email = wp_mail($tomail, $subject, $message, $headers);

          if(true !== $maybe_send_email ){
            $result['error_message'] = '<p class="text-error">' . __('The email could not be sent. ', 'flotheme') . '</p>';
          }else{
            if (isset($_POST['thx_msg']) && strlen(trim($_POST['thx_msg']))) {
              $thx_msg = urldecode($_POST['thx_msg']);
            } else {
              $thx_msg = __('The email was sent successfully ', 'flotheme');
            }

            $result['message'] = '<span class="text-success" >' . $thx_msg . '</span>';
          }



        }

        echo json_encode($result);

      }

    }
    exit();
  }
}


// BOF WooCommerce support
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'flo_cube_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'flo_cube_wrapper_end', 10);

function flo_cube_wrapper_start() {
  echo '<div class="flo-post-wrap">';
  echo    '<div class="flo-section flo-section--padding-small">';
  echo      '<div class="flo-section__content ">';

}

function flo_cube_wrapper_end() {
  echo      '</div>';
  echo    '</div>';
  echo '</div>';
}


/**
 *
 * Define the page templates that should not have sidebar
 * This will overwrite the templates defined in Core
 */
add_filter('flo_theme_templates_without_sidebars', 'flo_cube_templates_without_sidebars');
function flo_cube_templates_without_sidebars(){
  return array(
    'template-home',
    'template-listing',
    'template-about',
    'template-contact',
    'template-press'
  );
}

/**
 *
 * There are options where user should provide the image ration in the formmat like '3:2'
 * We get the value of that option and if the format is correct, then we return the
 * width and height for the requested ratio.
 * In case the format is not correct, then we will return predefined values
 */
if(!function_exists('flo_cube_get_custom_ratio_dimensions_archives')){
  function flo_cube_get_custom_ratio_dimensions_archives($option_name, $nr_columns = false, $archive_type = false){

    global $flo_options;

    if($archive_type){
        if(isset($flo_options[$archive_type]['flo-page-listing-listing__grid-image-sizes'][$option_name])){
          $ratio = $flo_options[$archive_type]['flo-page-listing-listing__grid-image-sizes'][$option_name];
        }else{
          // fallback
          $ratio = flo_get_option($option_name);
        }

    }


    $ratio_array = explode(':',$ratio);
    if(isset($ratio_array[0]) && is_numeric(trim($ratio_array[0])) && isset($ratio_array[1]) && is_numeric(trim($ratio_array[1]) ) ){

      // the width will be differnet depending on the nr columns
      if($nr_columns && is_numeric($nr_columns)){
        switch ($nr_columns) {
          case 2:
              $result['width'] = 800;
            break;

          case 2:
              $result['width'] = 400;
            break;

          default: // 3 columns
              $result['width'] = 560;
            break;
        }
      }else{
        $result['width'] = 500;
      }

      $result['height'] = round($result['width']*trim($ratio_array[1])/trim($ratio_array[0]) );
    }else{
      // we will return something like 3:2
      $result['width'] = 510; // this dimmension should work for 3 columns on large screens
      $result['height'] = 340;
    }
//deb_e($result);
    return $result;
  }
}


/**
 *
 * There are options where user should provide the image size in the formmat like '600x400'
 * We get the value of that option and if the format is correct, then we return the
 * width and height.
 * In case the format is not correct, then we will return predefined values
 */
if(!function_exists('flo_cube_get_grid_advanced_size_dimensions_archives')){
  function flo_cube_get_grid_advanced_size_dimensions_archives($option_name, $archive_type = false){
//var_dump($archive_type);
    global $flo_options;
    if($archive_type){
        if(isset($flo_options[$archive_type]['flo-page-listing-listing__grid-image-sizes'][$option_name])){
          $ratio = $flo_options[$archive_type]['flo-page-listing-listing__grid-image-sizes'][$option_name];
        }else{
          // fallback
          $ratio = get_field($option_name,'option');
        }

    }
    

    $ratio_array = explode('x',$ratio);

    if(isset($ratio_array[0]) && is_numeric(trim($ratio_array[0])) && isset($ratio_array[1]) && is_numeric(trim($ratio_array[1]) ) ){
      $result['width'] = trim($ratio_array[0]);
      $result['height'] = trim($ratio_array[1]);
    }else{
      // we will return something like 3:2
      $result['width'] = 510; // this dimmension should work for 3 columns on large screens
      $result['height'] = 340;
    }

    return $result;
  }
}

if(!function_exists('flo_cube_grid_ratio')){
  function flo_cube_grid_ratio($nr_columns, $post_id = false, $blocks_data = false){

    if(is_numeric($post_id)){

      if($blocks_data){

        if(isset($blocks_data['listing_layout_options']['flo-page-listing-listing__grid-image-sizes'])){
          
          $custom_ratio = flo_get_custom_ratio_dimensions('flo-cube-grid__thumb-size-custom',$nr_columns, $post_id, $block_data = $blocks_data['listing_layout_options']['flo-page-listing-listing__grid-image-sizes']);

          $advanced_size = flo_get_grid_advanced_size_dimensions('flo-cube-grid__thumb-size-px', $post_id, $blocks_data['listing_layout_options']['flo-page-listing-listing__grid-image-sizes']);
        }
      }else{
        $custom_ratio = flo_get_custom_ratio_dimensions('flo-cube-grid__thumb-size-custom',$nr_columns, $post_id);
        $advanced_size = flo_get_grid_advanced_size_dimensions('flo-cube-grid__thumb-size-px', $post_id);
      }
      
      // var_dump($custom_ratio);
      // var_dump($advanced_size);
    }else{
      $custom_ratio = flo_get_custom_ratio_dimensions('flo-cube-portfolio-gallery__grid-thumb-size-custom',$nr_columns);
      $advanced_size = flo_get_grid_advanced_size_dimensions('flo-cube-portfolio-gallery__grid-thumb-size-px');

      if( is_search() || is_archive() ){
        if(is_archive()){
          $archive_type = 'flo-archives';
        }

        if(is_search()){
          $archive_type = 'flo-search';
        }

        $custom_ratio = flo_cube_get_custom_ratio_dimensions_archives($option_name = 'flo-cube-grid__thumb-size-custom', $nr_columns, $archive_type);
        //$advanced_size = flo_get_grid_advanced_size_dimensions('flo-cube-grid__thumb-size-px');
        $advanced_size = flo_cube_get_grid_advanced_size_dimensions_archives('flo-cube-grid__thumb-size-px', $archive_type);

        ///var_dump($custom_ratio);
      }
    }


    $grid_ratio_info = array(
      2 => array(
          '3_2' => array(
              'width' => 800,
              'height' => 533,
            ),
          '2_3' => array(
              'width' => 800,
              'height' => 1200,
            ),
          '4_3' => array(
              'width' => 800,
              'height' => 600,
            ),
          '3_4' => array(
              'width' => 800,
              'height' => 1067,
            ),
          'masonry' => array(
              'width' => 800,
              'height' => 99999,
            ),
          'custom' => array(
              'width' => $custom_ratio['width'],
              'height' => $custom_ratio['height'],
            ),
          'advanced' => array(
              'width' => $advanced_size['width'],
              'height' => $advanced_size['height'],
            ),
        ),
      3 => array(
          '3_2' => array(
              'width' => 560,
              'height' => 373,
            ),
          '2_3' => array(
              'width' => 560,
              'height' => 840,
            ),
          '4_3' => array(
              'width' => 560,
              'height' => 420,
            ),
          '3_4' => array(
              'width' => 560,
              'height' => 747,
            ),
          'masonry' => array(
              'width' => 560,
              'height' => 99999,
            ),
          'custom' => array(
              'width' => $custom_ratio['width'],
              'height' => $custom_ratio['height'],
            ),
          'advanced' => array(
              'width' => $advanced_size['width'],
              'height' => $advanced_size['height'],
            ),
        ),
      4 => array(
          '3_2' => array(
              'width' => 400,
              'height' => 267,
            ),
          '2_3' => array(
              'width' => 400,
              'height' => 600,
            ),
          '4_3' => array(
              'width' => 400,
              'height' => 300,
            ),
          '3_4' => array(
              'width' => 410,
              'height' => 533,
            ),
          'masonry' => array(
              'width' => 600,
              'height' => 99999,
            ),
          'custom' => array(
              'width' => $custom_ratio['width'],
              'height' => $custom_ratio['height'],
            ),
          'advanced' => array(
              'width' => $advanced_size['width'],
              'height' => $advanced_size['height'],
            ),
        ),

    );

    if(isset($grid_ratio_info[$nr_columns])){
      return $grid_ratio_info[$nr_columns];
    }else{
      $grid_ratio_info[3];
    }
    //return $grid_ratio_info;
  }
}

if ( ! function_exists( 'flo_lightroom_gallery' ) ){
    /**
     * This function is ment for the compatibility with FloLightroom plugin
     *
     * It returns the available gallery types and the meta name where the gallery type should be saved
     *
     */
    function flo_lightroom_gallery(){

        $gallery_layouts = array(
          'grid' => 'thumbnails-under.png',
          'slideshow' => 'visible-nearby-fixed-width.png',
          'slideshow__no-crop' => 'one-image.png',
          'slideshow__crop' => 'full-width.png'
        );

        $gallery_type_meta = "flo-portfolio-gallery__gallery-view-type";

        return array('gallery_layouts' => $gallery_layouts,
            'gallery_type_meta' => $gallery_type_meta,
            'custom_gallery_view' => array(
                'meta_name' => 'custom_gallery_view', // the name of the meta
                'meta_value' => true
            )
            // th meta above is optional. It is available only for the themes that have the option to enable custom view
        );
    }
}

if (!function_exists('flo_pp_gallery_types')) {
  function flo_pp_gallery_types()
  {
    $img_path = 'http://flothemes-dashboard-images.s3.amazonaws.com/cube/';


    // we have support only for slideshow typo for now
    $gallery_types = array(
      //'grid' => $img_path . 'page-gallery__gallery-view-thumb--grid.jpg',
      'slideshow' => $img_path . 'page-gallery__gallery-view-thumb--nearby.jpg',
      // 'slideshow__no-crop' => $img_path . 'page-gallery__gallery-view-thumb--no-crop.jpg',
      // 'slideshow__crop' => $img_path . 'page-gallery__gallery-view-thumb--slideshow.jpg',
    );

    return $gallery_types;
  }
}

/**
 *
 * this function is used to render the gallery shortcode
 * added by the PP content migration plugin
 *
 */
if (!function_exists('flo_render_gallery_images')) {
  function flo_render_gallery_images($gallery_type, $images, $before_images = '', $after_images = ''){

    global $flo_options;



    $supported_gallery_types = array('grid', 'slideshow', 'slideshow__crop', "slideshow__no-crop");
    // in case the value for the gallery type is not supported by this theme, we will choose the first one from the supported list
    // this may happen when migrating from one theme to another
    if(!in_array($gallery_type,$supported_gallery_types )){
      $gallery_type = $supported_gallery_types[0];
    }

    // only Slideshow view is supported for now
    //The other views have issues and I don't have time to fix them. 
    $gallery_type = 'slideshow';


    $img_id_array = array_filter(explode(',', $images));

    $gallery__grid_columns = flo_get_option('flo-cube-portfolio-gallery__grid-columns',3);
    if(!is_numeric($gallery__grid_columns)){
      $gallery__grid_columns = 3;
    }

    $gallery_grid_columns_class = flo_columns_arabic_to_chars($gallery__grid_columns);

    $grid_ratio_info = flo_cube_grid_ratio($gallery__grid_columns);

    //deb_e($grid_ratio_info);

    $selected_grid_ratio = flo_get_option('flo-cube-portfolio-gallery__grid-thumb-size','3_2');
    if(!in_array($selected_grid_ratio,array('3_2','2_3','4_3','3_4','masonry','custom','advanced'))){
      $selected_grid_ratio = '3_2';
    }

    //deb_e($grid_ratio_info); die();
    $grid_sizes = $grid_ratio_info[$selected_grid_ratio];

    if('masonry' == $selected_grid_ratio){
      $crop_tumbs = false;
    }else{
      $crop_tumbs = true;
    }

    /* END: THUMBS SIZE */

    

    $max_slider_image_height = flo_get_option('flo-cube-portfolio-gallery__max_slider_image_height',715);
    if(!is_numeric($max_slider_image_height)){
      $max_slider_image_height = 715;
    }

    $slider_height_sizing = flo_get_option('flo-cube-portfolio-gallery__max_slider_image_height_sizing','px');

    if('rem' == $slider_height_sizing){
      $slideshow_height = ($max_slider_image_height / 16)."rem";
    }else{
      $slideshow_height = $max_slider_image_height ."px";
    }


    /* START: COLUMNS */
      if(isset($flo_options['flo-portfolio-gallery__fields']['flo-portfolio-gallery__grid-columns'])){
        $gallery__grid_columns = $flo_options['flo-portfolio-gallery__fields']['flo-portfolio-gallery__grid-columns'];
      }else{
        $gallery__grid_columns = 3;
      }
      
      if(!is_numeric($gallery__grid_columns)){
        $gallery__grid_columns = 3;
      }

      $gallery_grid_columns_class = flo_columns_arabic_to_chars($gallery__grid_columns);

    
    $data = array(
      
      'flo_gallery__max_slider_image_height' => $max_slider_image_height,
      'flo_gallery__gallery_type' => $gallery_type,
      'flo_gallery__gallery_images' => $flo_gallery__gallery_images = $img_id_array,
      'gallery_items' => $img_id_array,
      'selected_grid_ratio' => $selected_grid_ratio,
      'grid_sizes' => $grid_sizes,
      'crop_tumbs' => $crop_tumbs,
      'gallery_grid_columns_class' => $gallery_grid_columns_class,
      'slideshow_height' => $slideshow_height

    );

    if( 'grid' == $gallery_type && sizeof($img_id_array)){
      ?>
      <div class="flo-popup-gallery full reveal" id="popup-gallery" data-reveal data-overlay="true" data-full-screen="true" data-animation-in="fade-in" data-animation-out="fade-out" data-show-delay="0" data-v-offset="0" data-hide-delay="0">
          <div class="flo-popup-gallery__content">
              <div class="flo-popup-gallery__top-wrap">
                  <span class="flo-icon-close close-button flo-popup-gallery__close" data-close aria-label="Close reveal" type="button"></span>
              </div>
              <?php
                $view_data = array(
                    "class" => "flo-hero-3--visible-nearby",
                    "use_bgi" => false,
                    'the_real_gallery_type' => 'image',
                    "slick_configuration" => '{
                      "slidesToShow": 1,
                      "centerMode": true,
                      "variableWidth": true
                    }'
                  );
                Classy\Classy::render( 'components.slider__slider', array_merge($view_data, $data) );
                Classy\Classy::render( 'components.flo_gallery__'.$gallery_type, array_merge($view_data, $data) );

              ?>

          </div>

      </div>
      <?php
    } // end  IF

    if('slideshow' == $gallery_type && sizeof($flo_gallery__gallery_images)){
      if ($gap_between = flo_get_option("flo-cube-portfolio-gallery__image-gap")) {
        $gap_between = ($gap_between / 16 / 2)."rem";
      }
        $view_data = array(
            "class" => "flo-hero-3--visible-nearby",
            'the_real_gallery_type' => 'image',
            "use_bgi" => false,
            "slick_configuration" => '{
              "slidesToShow": 1,
              "centerMode": true,
              "variableWidth": true
            }'
          );
        Classy\Classy::render( 'components.flo_gallery__slideshow', array_merge($view_data, $data) );
        
    }

    if('slideshow__no-crop' == $gallery_type && sizeof($flo_gallery__gallery_images)){
        $view_data = array(
            "class" => "flo-hero-3--no-crop",
            'the_real_gallery_type' => 'image',
            "use_bgi" => true,
            "slick_configuration" => '{
            }'
          );
        Classy\Classy::render( 'components.flo_gallery__slideshow', array_merge($view_data, $data) );

    }

    if('slideshow__crop' == $gallery_type && sizeof($flo_gallery__gallery_images)){
        $view_data = array(
            "class" => "flo-hero-3--crop",
            'the_real_gallery_type' => 'image',
            "use_bgi" => true,
            "slick_configuration" => '{
            }'
          );
        Classy\Classy::render( 'components.flo_gallery__slideshow', array_merge($view_data, $data) );

    }
  }
}
/* EOF PP migration support */

/**
 * Allows for excerpt generation outside the loop.
 * 
 * @param string $text  The text to be trimmed
 * @return string       The trimmed text
 */
function flo_trim_excerpt( $text='' )
{
    $text = strip_shortcodes( $text );
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]>', ']]&gt;', $text);
    $excerpt_length = apply_filters('excerpt_length', 25);
    $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
    return wp_trim_words( $text, $excerpt_length, $excerpt_more );
}


/**
 * The pages that are using the default page template and use some custom layout blocks, have some problems with the 
 * blocks data in 0.6 update.
 * 
 * Show a warning message for the users to update some of the pages that are using the default page template.
 * 
 */
add_action('admin_notices', 'flo_maybe_default_page_templates_warning');
function flo_maybe_default_page_templates_warning() {
  $flo_theme = wp_get_theme('cube');

  $theme_version = $flo_theme->get( 'Version' );

  // we will show this message only for versions < 0.6.3 and if user did not click the button that hides the message.
  if(get_option('cube_06_templates') != 'ok' &&  $theme_version > '0.5.7' && $theme_version < '0.6.3'){
    $args = array(
        'post_type'  => 'page', 
        'meta_query' => array( 
            array(
                'key'   => '_wp_page_template', 
                'value' => 'default'
            )
        )
    ); 
    
    // The Query
    $the_query1 = get_posts($args);
  
      if(count($the_query1)){
        echo '<div class="notice notice-warning is-dismissible default_page_templates_warning">';
          echo '<h4>The following pages may need your attention:</h4>';


          echo '<ul>';
            foreach($the_query1 as $p) : setup_postdata($p);
              echo '<li>'.edit_post_link( $p->post_title, '', '', $p->ID ).'</li>';
            endforeach;  
          echo '</ul>';
          echo '<p>If some blocks are not showig after the theme update, please edit those pages and just click Update.</p>';
          echo '<p>Check the <a href="https://docs.flothemes.com/cube-0-6-default-template-issue/" target="_blank">following tutorial </a> to see how it is done. </p>';
          echo '<p>If everything is fine <button onclick="flo_disable_default_page_templates_warning()">Click this button</button></p>';
        echo '</div>';
      }
    
  }
  

}

add_action('wp_ajax_disable_default_page_templates_warning' , 'flo_disable_default_page_templates_warning' );
function flo_disable_default_page_templates_warning() {
  update_option('cube_06_templates','ok');
}


add_filter('flo_layout_field_id', 'flo_cube_layout_field_id');
function flo_cube_layout_field_id() {
  return 'field_59ef268a3ffac';
}

?>
