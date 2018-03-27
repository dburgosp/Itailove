{{-- NOTE: GENERATED BY GULP !!! --}}{{--

Uses as data:
- $the_query (from the parent)

--}}
<?php  
  //deb_e(get_field('flo-search','options'));
  if(isset($page_options['flo-page-listing-listing__classic-title-font']['default'])){
    $post_title_font = $page_options['flo-page-listing-listing__classic-title-font']['default'];
  }else{
    $post_title_font = '';
  }

  if(isset($page_options['page-listing-listing__classic-category-font']['default'])){
    $categ_font = $page_options['page-listing-listing__classic-category-font']['default'];
  }else{
    $categ_font = '';
  }

  if(isset($page_options['page-listing-listing__classic-excerpt-font']['default'])){
    $excerpt_font = $page_options['page-listing-listing__classic-excerpt-font']['default'];
  }else{
    $excerpt_font = '';
  }
  $category_term = flo_get_category_term($the_query->post);
?>
<div class="flo-section flo-section--padding-small flo-section--journal-grid-section">
   <article class="flo-section__content">
       <div class="flo-grid-2">
           <div class="flo-grid-2__row">
               <div class="row">
                @while($the_query->have_posts())
                  <?php $the_query->the_post() ?>
                   <div class="column medium-12 small-12 to-appear">
                       <div class="flo-card-4">
                              <?php 
                                $post_thumbnail_url = get_the_post_thumbnail_url();
                                $resized_post_thumbnail_url = aq_resize($post_thumbnail_url, $width = 1450, 9999, $crop = false, true, true); //resize img 
                              ?>

                               @if( strlen($post_thumbnail_url) )
                                <a href="{{ the_permalink() }}" class="hover-block">
                                   <figure class="flo-card-4__img" style="background-image: url({{ $resized_post_thumbnail_url }})">
                                       <img src="{{ $resized_post_thumbnail_url }}">
                                   </figure>
                                </a>
                               @endif
                               <div class="flo-card-4__text">
                                  @if($show_grid_categ)
                                  <div class="flo-card-4__category" style="{{ $categ_font }}">
                                    {{ flo_get_the_first_term( $id = $the_query->post->ID, $taxonomy = $category_term, $before = '', $sep = ', ', $after = '' ); }}
                                  </div>
                                  @endif
                                  <div class="flo-card-4__title" style="{{ $post_title_font }}">
                                    <a href="{{ the_permalink() }}" class="hover-block">
                                      {{ the_title() }}
                                    </a>
                                  </div>
                                   @if($show_content)
                                      <div class="flo-card-4__content flo-post">
                                          {{ the_content() }}

                                        <div class="flo-post-bottom-wrap to-appear">  
                                          <div class="flo-post-bottom-wrap__tags">
                                            <?php
                                            if(flo_get_option('flo-cube-blog-post__show-tags',true)){
                                              $tags_list = get_the_tag_list( '', " " );
                                            }else{
                                              $tags_list = false;
                                            }
                                            ?>
                                            {{ $tags_list }}
                                            <div class="flo-card-4__comments-number">
                                            @if (get_post_type() == 'post' && (comments_open() || $post->get_comments() ))
                                              <a href="{{ the_permalink() }}/#comments">
                                              <?php
                                                $comments_number = get_comments_number();
                                                if ( '1' === $comments_number ) {
                                                  /* translators: %s: post title */
                                                  _x( 'One Comment ', 'comments title', 'flotheme' );
                                                } else {
                                                  printf(
                                                    /* translators: 1: number of comments, 2: post title */
                                                    _nx(
                                                      '%1$s Comment ',
                                                      '%1$s Comments ',
                                                      $comments_number,
                                                      'comments title',
                                                      'flotheme'
                                                    ),
                                                    number_format_i18n( $comments_number )
                                                  );
                                                }
                                              ?>
                                              </a>
                                            @endif
                                            </div>
                                          </div>
                                          <div class="flo-post-bottom-wrap__share-wrap">
                                            @if (flo_get_option('flo-cube-blog-post-bottom__share-display',true) )
                                              <div class="flo-post-bottom-wrap__share-label">
                                                {{ flo_get_option("flo-cube-blog-post-bottom__share-label",__('SHARE','flotheme')) }}
                                              </div>
                                              <div class="flo-post-bottom-wrap__share-links">
                                                @include('components.flo-share-wrap',[
                                                'post' => $the_query->post
                                                ])
                                              </div>
                                            @endif
                                          </div>
                                        </div>
                                      </div>
                                   @elseif($show_excerpt)
                                       <div class="flo-card-4__excerpt flo-post" style="{{ $excerpt_font }}">
                                           {{ the_excerpt() }}
                                       </div>
                                   @endif
                                   @if(!$show_content)
                                   <a href="{{ the_permalink() }}" class="flo-card-4__open-post">{{__('Open','flotheme')}}</a>
                                   @endif
                               </div>
                           
                       </div>
                   </div>
                  <?php wp_reset_postdata(); ?>
                @endwhile
               </div>
           </div>
       </div>
   </article>
</div>