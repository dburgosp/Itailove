{{--

Uses as data:
- $the_query (from the parent)
- $columns_count (from the parent)

--}}
<?php
    $grid_tyles = '';

    if(isset($gutter) && is_numeric($gutter)){
        $grid_tyles = '.flo-section__content--block { padding: '.($gutter/2).'px}';
    }


    // render the grid block title font
    if(isset($page_options['flo-page-listing-listing__grid-title-font']['default'])){
        $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__grid-title-font', $post_id = 'options', $options_value = $page_options);
    }

    // render the gid block category font

    if(isset($page_options['flo-page-listing-listing__grid-category-font']['default'])){
        $grid_tyles .= flo_render_typography_styles_by_option_name('flo-page-listing-listing__grid-category-font', $post_id = 'options', $options_value = $page_options);
    }
?>
@if(strlen($grid_tyles) )
    @section('head__styles')
      <style class="page-home__styles" media="screen">
        {{ $grid_tyles }}
      </style>
    @endsection
@endif
<div class="flo-section flo-section--padding-small flo-section--journal-grid-section">
        <div class="flo-grid">
            <div class="row row-flex">

              @while($the_query->have_posts())
                <?php $the_query->the_post() ?>
                <?php
                    $category_term = flo_get_category_term($the_query->post);

                    if(has_post_thumbnail()){
                        $bg_img = get_the_post_thumbnail_url($the_query->post->ID);

                        switch ($columns_count) {
                            case 2:
                                $thumb_width = 920;
                                break;
                            
                            case 3:
                                $thumb_width = 600;
                                break;

                            case 4:
                                $thumb_width = 450;
                                break;    
                            default:
                                $thumb_width = 600;
                                break;
                        }

                        $bg_img = aq_resize($bg_img, $width = $thumb_width, 9999, $crop = false, true, true); //
                    }else{
                        $bg_img = get_template_directory_uri().'/theme-files/public/img/placeholder-img.jpg';
                    }
                ?>
                <div href="{{ the_permalink() }}" class="column medium-{{ 12 / $columns_count }} small-12 to-appear flo-section__content--block">
                    <div class="flo-card-3">
                        <a href="{{ the_permalink() }}">
                            <figure class="flo-card-3__img" style="background-image: url({{ $bg_img }})">
                                <img src="{{ get_the_post_thumbnail_url() }}">
                            </figure>
                        </a>
                        <div class="flo-card-3__text">
                            <div class="flo-card-3__title">
                                <a href="{{ the_permalink() }}">
                                    {{ the_title() }}
                                </a>
                            </div>
                            @if(strlen($category_term) && $show_grid_categ)
                            <div class="flo-card-3__category">
                                {{ flo_get_the_first_term( $id = $the_query->post->ID, $taxonomy = $category_term, $before = '', $sep = ', ', $after = '' ); }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <?php wp_reset_postdata(); ?>
              @endwhile

            </div>
        </div>
</div>
