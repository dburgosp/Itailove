@foreach($post_type__categories as $category)
  <?php
  $category = get_term_by("id", $category, $post_type__category_taxonomy);
  $the_query = new WP_Query(
    array(
      'post_type'                   => $post_type,
      $post_type__category_taxonomy => $category
    )
  );
  ?>
    <section class="flo-section flo-section--full-width">
        <article class="flo-section__content">
            <div class="flo-hero-2">
                <div class="flo-hero-2__slider">
                  <?php $i = 0; ?>
                  @while($the_query->have_posts())
                    <?php $the_query->the_post() ?>
                    @if($i < 1)
                      <div class="flo-hero-2__slide">
                          <a href="#" class="flo-hero-2__slide-content" style="background-image: url({{ get_the_post_thumbnail_url() }})"></a>
                          <a  href="" class="flo-hero-2__text-block">
                              <div class="flo-hero-2__text flo-hero-2__text--large">
                                  <div class="flo-hero-2__title title-line-up">
                                      {{ $category->name }}
                                      {{-- <br>
                                      - {{ the_title() }} --}}
                                  </div>
                              </div>
                          </a>
                      </div>
                      <?php $i++; ?>
                    @endif
                    <?php wp_reset_postdata(); ?>
                  @endwhile
                </div>
            </div>
        </article>
    </section>
@endforeach
