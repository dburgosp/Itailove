{{--

Uses as data:
- $the_query (from the parent)

--}}

<?php
    global $flo_options;

    $page_id = $post->ID; // used to get the page meta data
    $grid_tyles = '';

    $selected_grid_ratio = get_field('flo-cube-grid__thumb-size', $page_id);

    if(is_search() || is_archive()){
      if(is_archive()){
        $archive_type = 'flo-archives';
      }

      if(is_search()){
        $archive_type = 'flo-search';
      }
      if(isset( $flo_options[$archive_type]['flo-page-listing-listing__grid-image-sizes']['flo-cube-grid__thumb-size'] )){
        $selected_grid_ratio = $flo_options[$archive_type]['flo-page-listing-listing__grid-image-sizes']['flo-cube-grid__thumb-size'];
      }

    }else if(isset($data)){
      // for the default page template that uses the Listing block
      
      if(isset($data['flo-page-listing-listing__grid-image-sizes']['flo-cube-grid__thumb-size'])){
        $selected_grid_ratio = $data['flo-page-listing-listing__grid-image-sizes']['flo-cube-grid__thumb-size'];
      }else if(isset($data['listing_layout_options']['flo-page-listing-listing__grid-image-sizes']['flo-cube-grid__thumb-size'])){
        $selected_grid_ratio = $data['listing_layout_options']['flo-page-listing-listing__grid-image-sizes']['flo-cube-grid__thumb-size'];
      }else{
        $selected_grid_ratio = array('custom');
      }
      


    }

    if(!$selected_grid_ratio || !in_array($selected_grid_ratio,array('3_2','2_3','4_3','3_4','masonry','custom','advanced'))){
      $selected_grid_ratio = 'custom';
    }

    // grid padding
    if(isset($gutter) && is_numeric($gutter)){
        $grid_tyles = '.flo-grid .flo-featured-posts--layout-grid { padding: '.($gutter/2).'px}';
    }

    // render the grid 2 block title font
    if(isset($page_options['flo-page-listing-listing__grid2-title-font']['default'])){
        $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__grid2-title-font', $post_id = 'options', $options_value = $page_options);
    }

    // render the grid 2 category font
    if(isset($page_options['flo-page-listing-listing__grid2-category-font']['default'])){
        $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__grid2-category-font', $post_id = 'options', $options_value = $page_options);
    }

    $text_color = get_field("flo-page-listing-listing__text-color");

    if(!isset($show_grid_excerpt)){ // it is set usuall when we are on the search or archive page
      $show_grid_excerpt = get_field('flo-page-listing-listing__grid2-show-excerpt');
    }


    if($show_grid_excerpt){
      $excerpt_class = 'flo-featured-posts--layout-grid-has-excerpt';
    }else{
      $excerpt_class = '';
    }

    // we need to enable masonry if the excerpt is enabled
    $excerpt_masonry = '';
    if( 'masonry' != $selected_grid_ratio &&  $show_grid_excerpt){
      $excerpt_masonry = 'masonry';
    }
?>
@if(strlen($grid_tyles) )
    @section('head__styles')
      <style class="page-home__styles" media="screen">
        {{ $grid_tyles }}
      </style>
    @endsection
@endif
 
{{-- @include('core.style', [
  "breakpoint__medium_up" => "
    .flo-featured-posts__text a,
    .flo-featured-posts__text div
    {
      color: ". $text_color .";
    }
  "
]) --}}

<?php if(!$columns_count){ $columns_count = 2;} ?>
<div class="flo-section flo-section--padding-small flo-section--journal-grid-section">
    <article class="flo-grid">
        <div class="row row-flex {{$selected_grid_ratio}} {{ $excerpt_masonry }}">

            @while($the_query->have_posts())
                <?php $the_query->the_post() ?>
                <?php
                  $category_term = flo_get_category_term($the_query->post);

                  if(has_post_thumbnail()){
                      $bg_img = get_the_post_thumbnail_url($the_query->post->ID);
                  }else{
                      if($show_grid_excerpt){
                        $bg_img = '';
                      }else{
                        $bg_img = get_template_directory_uri().'/theme-files/public/img/placeholder-img.jpg';
                      }
                  }

                  if(is_search() || is_archive()){
                    $page_id = '';
                  }

                  $gallery__grid_columns = flo_get_option('flo-cube-portfolio-gallery__grid-columns',3);
                  if(!is_numeric($gallery__grid_columns)){
                    $gallery__grid_columns = 3;
                  }

                  $gallery_grid_columns_class = flo_columns_arabic_to_chars($gallery__grid_columns);

                  if(isset($data)){
                    // for the default page template that uses the Listing block
                    $grid_ratio_info = flo_cube_grid_ratio($gallery__grid_columns, $page_id, $data);
                  }else{
                    $grid_ratio_info = flo_cube_grid_ratio($gallery__grid_columns, $page_id);
                  }
                  


                      //deb_e($grid_ratio_info); die();
                      $grid_sizes = $grid_ratio_info[$selected_grid_ratio];


                      if('masonry' == $selected_grid_ratio){
                        $crop_tumbs = false;
                      }else{
                        $crop_tumbs = true;
                      }

                      if(strlen($bg_img)){
                        $bg_img = aq_resize(
                          $bg_img,
                          $width = $grid_sizes['width'],
                          $height = $grid_sizes['height'],
                          $crop = $crop_tumbs,
                          $single = true,
                          $upscale = false
                        );
                      }

                  $post_id = $the_query->post->ID;
                  $item_data = get_fields($post_id);

                  $block_class = "flo-featured-posts__featured-post--".uniqid();

                  /* START: VIDEO EMBED */
                    $video_embed_code = get_field("flo-cube-post__video-embed-code", $post_id);
                    $has_video = $video_embed_code ? true : false;
                    $video_class = $has_video ? "flo-featured-posts__featured-post--has-video" : "";
                  /* END: VIDEO EMBED */

                  /* START: DECORATIVE LAYER */
                    $has_decorative_layer = get_field("decorative_overlay", $post_id) && !$has_video;
                    $has_decorative_layer_class = $has_decorative_layer ? "flo-featured-posts__featured-post--has-decorative-layer" : "";
                    $decorative_image = get_field("decorative_image", $post_id);
                    $decorative_color = get_field("decorative_overlay_color", $post_id);
                    $decorative_elements_color = get_field("decorative_overlay_elements_color", $post_id);
                  /* END: DECORATIVE LAYER */

                  if ($has_decorative_layer) {

                    $item_text_color = "color: ".$decorative_elements_color;

                  } else {

                    if(isset(get_field('item_info', $post_id)[0]['text_color'])){
                      $item_text_color = 'color:'. get_field('item_info', $post_id)[0]['text_color'];
                    }else{
                      $item_text_color = '';
                    }

                  }

                  $post_permalink = get_the_permalink();
                ?>

                @if ($has_decorative_layer)
                  @include('core.style', [
                    "breakpoint__general" => "
                      .".$block_class.".".$has_decorative_layer_class.":before,
                      .".$block_class.".".$has_decorative_layer_class.":after {
                        background-color: ".$decorative_color.";
                        border-color: ".$decorative_color.";
                      }
                    "
                  ])
                @endif

                <div class="column medium-{{ 12 / $columns_count }} small-12">
                    <div class="flo-featured-posts__featured-post {{ $block_class }} {{ $excerpt_class }} {{ $video_class }} {{ $has_decorative_layer_class }} flo-featured-posts--layout-grid">
                        @if(strlen($bg_img))
                        <a href="{{ $post_permalink }}">
                            <figure class="flo-featured-posts__img">
                                <img src="{{ $bg_img }}">
                            </figure>
                        </a>
                        @endif
                        <div class="flo-featured-posts__text">

                            @if ($has_decorative_layer)
                              <img src="{{ $decorative_image }}" class="flo-featured-posts__decorative-image" alt="">
                            @endif

                            <a href="{{ $post_permalink }}">
                                <div class="flo-featured-posts__title" style="{{ $item_text_color }}">
                                    {{ get_the_title() }}
                                </div>
                            </a>
                            @if(strlen($category_term) && $show_grid_categ)
                            <div class="flo-featured-posts__category" style="{{ $item_text_color }}">
                                {{ flo_get_the_first_term( $id = $the_query->post->ID, $taxonomy = $category_term, $before = '', $sep = ', ', $after = '' ); }}
                            </div>
                            @endif

                            @if($show_grid_excerpt && $excerpt = get_the_excerpt())
                              <div class="flo-featured-posts--layout-grid-excerpt">
                                <a href="{{ $post_permalink }}">
                                  {{ $excerpt }}
                                </a>
                              </div>
                            @endif
                        </div>
                      @if ($has_video)
                        @include('components.flo-video-embed', [
                          "embed_code" => $video_embed_code
                        ])
                      @endif
                    </div>
                </div>
                <?php wp_reset_postdata(); ?>
            @endwhile
        </div>
    </article>
</div>
