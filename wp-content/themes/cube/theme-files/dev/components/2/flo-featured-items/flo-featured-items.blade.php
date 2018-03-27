{{--

Uses as data:
- $section_featured_items__items
--}}

@if($data["featured-items__enabled"])
  <?php
    $section_featured_items__items = $data['featured-items__items'];

    //featured-items__items-title-font
    //featured-items__items-category-font
    //featured-items__items-excerpt-font

    $block_class = "feat-posts-2-block--".uniqid();
    $feat_posts_block_2_font_styles = '';

    if(isset($data['featured-items__items-title-font'])){
      $the_selector = '.' . $block_class . ' .flo-card-side__tittle';
      $feat_posts_block_2_font_styles .= flo_render_typography_styles( $the_selector, $data['featured-items__items-title-font']);
    }

    if(isset($data['featured-items__items-category-font'])){
      $the_selector = '.' . $block_class . ' .flo-card-side__category';
      $feat_posts_block_2_font_styles .= flo_render_typography_styles( $the_selector, $data['featured-items__items-category-font']);
    }

    if(isset($data['featured-items__items-excerpt-font'])){
      $the_selector = '.' . $block_class . ' .flo-card-side__subtitle';
      $feat_posts_block_2_font_styles .= flo_render_typography_styles( $the_selector, $data['featured-items__items-excerpt-font']);
    }

  ?>
  @include('core.style', [
    "breakpoint__general" => $feat_posts_block_2_font_styles
  ])
  <div class="flo-section flo-featured-items full-scrollable-section {{$block_class}}">
      <article>

          <?php $i = 1; ?>
          @if($featured_items__items = $section_featured_items__items)
              @foreach($featured_items__items as $post_object)
                  <?php
                    $category_term = flo_get_category_term($post_object);
                  ?>
                  <div class="flo-card-side to-appear @if($i % 2 > 0) flo-card-side--inverted @endif">
                      <div class="flo-card-side__content">
                          <div class="flo-card-side__item">
                            @if (get_field("flo-cube-post__video-embed-code", $post_object->ID))
                              <figure class="flo-card-side__img" style="background-image: url({{ get_the_post_thumbnail_url($post_object->ID) }})">
                                  <img src="{{ get_the_post_thumbnail_url($post_object->ID) }}" alt="">
                                  @include('components.flo-video-embed', [
                                    "embed_code" => get_field("flo-cube-post__video-embed-code", $post_object->ID)
                                  ])
                              </figure>
                            @else
                              <a href="{{ get_permalink($post_object->ID) }}">
                                <figure class="flo-card-side__img" style="background-image: url({{ get_the_post_thumbnail_url($post_object->ID) }})">
                                  <img src="{{ get_the_post_thumbnail_url($post_object->ID) }}" alt="">
                                </figure>
                              </a>
                            @endif
                          </div>
                          <div class="flo-card-side__item flo-card-side__item--right">
                              <h4 class="flo-card-side__category">
                                  {{ flo_get_the_first_term( $id = $post_object->ID, $taxonomy = $category_term, $before = '', $sep = ', ', $after = '' ); }}
                              </h4>
                              <h3 class="flo-card-side__tittle">
                                  <a href="{{ get_permalink($post_object->ID) }}">{{ get_the_title($post_object->ID) }}</a>
                              </h3>
                              <p class="flo-card-side__subtitle">
                                  {{ get_the_excerpt($post_object) }}
                              </p>
                          </div>
                      </div>
                  </div>

                  <?php $i++; ?>
              @endforeach
          @endif

      </article>
  </div>

@endif
