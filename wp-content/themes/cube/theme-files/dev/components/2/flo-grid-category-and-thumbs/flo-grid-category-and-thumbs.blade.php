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
  <div class="flo-section flo-section--padding-small flo-section--portfolio-2-category">
    <article class="flo-section__content">
      <div class="flo-portfolio-category-grid">
        <div class="flo-portfolio-category-grid__wrap">
          <h4 class="flo-portfolio-category-grid__title title-line-up">
            {{ $category->name }}
            <a href="#" class="flo-portfolio-category-grid__link-all">VIEW ALL</a>
          </h4>
        </div>
        <div class="row row-flex">
          @while($the_query->have_posts())
            <?php $the_query->the_post() ?>
            <a href="{{the_permalink()}}" class="column medium-3 small-12">
              <span class="flo-portfolio-card" style="background-image: url({{ get_the_post_thumbnail_url() }})">
                  <img src="{{ get_the_post_thumbnail_url() }}">
              </span>
            </a>
            <?php wp_reset_postdata(); ?>
          @endwhile
        </div>
      </div>
    </article>
  </div>
@endforeach
