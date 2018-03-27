{{--

Uses as data:
- $the_query (from the parent)

--}}
<?php
  $grid_tyles = '';

  // render the cards block title font
  if(isset($page_options['flo-page-listing-listing__cards-title-font']['default'])){
      $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__cards-title-font', $post_id = 'options', $options_value = $page_options);
  }

  // render the cards category font
  if(isset($page_options['flo-page-listing-listing__cards-category-font']['default'])){
      $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__cards-category-font', $post_id = 'options', $options_value = $page_options);
  }

  // render the cards excerpt font
  if(isset($page_options['flo-page-listing-listing__cards-excerpt-font']['default'])){
      $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__cards-excerpt-font', $post_id = 'options', $options_value = $page_options);
  }
?>

@if(strlen($grid_tyles) )
    @section('head__styles')
      <style class="page-home__styles" media="screen">
        {{ $grid_tyles }}
      </style>
    @endsection
@endif

<?php $i = 1; ?>
@while($the_query->have_posts())
  <?php $the_query->the_post() ?>
  <?php
      $category_term = flo_get_category_term($the_query->post);

      if( $thumb_url = get_the_post_thumbnail_url()){
          $thumb_url = aq_resize($thumb_url, $width = 1000, 9999, $crop = false, true, true); //;
      }else{
          $thumb_url = get_template_directory_uri().'/theme-files/public/img/placeholder-img.jpg';
      }
  ?>

  <div class="flo-section flo-section--full-width">
      <article class="flo-section__content">
        @if($i % 2 == 0)
          <div class="flo-card-5 flo-card-5--inverted hover-block">
        @elseif($i == 1 || $i % 1 == 0)
          <div class="flo-card-5 hover-block">
        @endif

            <div class="flo-card-5__content">
                <div class="flo-card-5__text">
                    @if(strlen($category_term) && $show_grid_categ)
                    <div class="flo-card-5__category">
                        {{ flo_get_the_first_term( $id = $the_query->post->ID, $taxonomy = $category_term, $before = '', $sep = ', ', $after = '' ); }}
                    </div>
                    @endif
                    <a href="{{ the_permalink() }}">
                      <div class="flo-card-5__title">
                          {{ the_title() }}
                      </div>

                      @if(isset($the_query->post->post_excerpt) && strlen(trim($the_query->post->post_excerpt)))
                      <div class="flo-card-5__excerpt">
                          {{ the_excerpt() }}
                      </div>
                      @endif
                    </a>
                </div>
                <a href="{{ the_permalink() }}" class="flo-card-5__img" style="background-image: url({{ $thumb_url }})">
                  <img src="{{ $thumb_url }}" class="flo-card-5__img-real" />
                </a>
            </div>

          </div>
      </article>
  </div>

  <?php wp_reset_postdata(); ?>
  <?php $i++; ?>
@endwhile
