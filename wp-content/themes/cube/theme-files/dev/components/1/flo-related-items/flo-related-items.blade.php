<?php
  $related_items_display = flo_get_option("flo-cube-post-bottom__related-posts-display", true);
  $related_items_title = flo_get_option("flo-cube-post-bottom__related-posts-title", "Related Posts");
?>
@if($related_items_display)
  <?php
  $b = "flo-related-items";
  $related_posts__query = similar_query($post->ID, 'category', 4);
  ?>
  @if(sizeof($related_posts__query) && $related_posts__query->have_posts())
    <section class="flo-section flo-section--related flo-section--padding-small ">
      <article class="flo-section__content">

          <div class="{{$b}}">
            <div class="{{$b}}__title-wrap flo-post">
              <h2 class="{{$b}}__title">
                {{ $related_items_title }}
              </h2>
            </div>
            <div class="{{$b}}__items">
              @while($related_posts__query->have_posts())
                <?php $related_posts__query->the_post() ?>
                <?php
                $post_feat_img_url = get_the_post_thumbnail_url();
                $post_feat_img_url = aq_resize($post_feat_img_url, 450, 420, true, true); // crop img
                ?>
                  <a href="{{ the_permalink() }}" class="{{$b}}__item to-appear">
                    @if(strlen($post_feat_img_url))
                      <figure class="{{$b}}__item-image" style="background-image: url({{ $post_feat_img_url }})"></figure>
                    @endif
                    <h3 class="{{$b}}__item-title" style="">
                      {{ the_title() }}
                    </h3>
                  </a>
                <?php wp_reset_postdata(); ?>
              @endwhile
            </div>
          </div>

      </article>
    </section>
  @endif
@endif
