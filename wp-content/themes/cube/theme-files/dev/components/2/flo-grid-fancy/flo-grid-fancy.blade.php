<section class="flo-section flo-section--padding-medium flo-portfolio-2--section">
    <article class="flo-section__content">
        <div class="flo-portfolio-2">
            <div class="flo-portfolio-title">
                @if(get_field("page-category-listing__decoration-image"))
                  <figure class="flo-portfolio-title__decoration">
                      <img src="{{ get_field("page-category-listing__decoration-image") }}">
                  </figure>
                @endif
                <h1 class="flo-portfolio-title__text">{{ $post->title() }}</h1>
            </div>
            <div class="flo-portfolio-2__categories">
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
                  <div class="flo-portfolio-2__link-block">
                      <div class="flo-portfolio-2__link-content">
                          <a href="" class="flo-portfolio-2__link title-line-up">{{ $category->name }}</a>
                          <?php
                          $i = 0;
                          $post_previews__classes = [
                            "flo-portfolio-2__img-display-hover--top-left",
                            "flo-portfolio-2__img-display-hover--top-left-auto",
                            "flo-portfolio-2__img-display-hover--top-right-auto",
                            "flo-portfolio-2__img-display-hover--center-right",
                            "flo-portfolio-2__img-display-hover--left-bottom",
                            "flo-portfolio-2__img-display-hover--right-bottom"
                          ]
                          ?>
                          @while($the_query->have_posts())
                            <?php $the_query->the_post() ?>
                            <figure class="flo-portfolio-2__img-display-hover {{ $post_previews__classes[$i] }}" style="background-image: url({{ get_the_post_thumbnail_url() }})"></figure>
                            <?php wp_reset_postdata(); ?>
                            <?php $i++; ?>
                          @endwhile
                      </div>
                  </div>
                @endforeach
            </div>
        </div>
    </article>
</section>
