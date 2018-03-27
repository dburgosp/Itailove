{{--

Uses as data:
- $section_contact_block__social_links_show
- $section_contact_block__decoration_image
- $section_contact_block__instagram_feed_show

--}}
<div class="flo-section flo-section--padding-small flo-section--full-height full-scrollable-footer">
  <article class="flo-section__content">
      <div class="flo-section__decoration flo-section__decoration--right" style="background-image: url({{
        $section_contact_block__decoration_image or ""
      }})">
      </div>
      <div class="flo-instagram-with-form">
          <div class="row">
              <div class="column small-12 medium-6">
                  <div class="flo-social-section">
                      <div class="flo-social-block">
                        @if($section_contact_block__social_links_show)

                          @if($social_links = get_field("social-links", "options"))
                            @foreach($social_links as $link)
                              <a href="{{ $link["link"] }}" target="_blank" class="flo-social-block__link title-line-up">{{
                                $link["type"]["label"]
                              }}</a>
                            @endforeach
                          @endif

                        @endif
                      </div>
                      @if($section_contact_block__instagram_feed_show)
                        <nav class="flo-instagram-images flo-instagram-images--grid">
                            <?php echo do_shortcode('[flo_instagram padding="0" use_pattern="" crop="0" nr_columns="3" limit="6" ]'); ?>
                        </nav>
                      @endif
                  </div>
              </div>
              <div class="column small-12 medium-6">
                  <form class="flo-form">
                      <div class="flo-form__field">
                          <input type="text" placeholder="YOUR NAME">
                      </div>
                      <div class="flo-form__field">
                          <input type="text" placeholder="YOUR EMAIL">
                      </div>
                      <div class="flo-form__field">
                          <textarea placeholder="YOUR MESSAGE"></textarea>
                      </div>
                      <input type="submit" value="SEND">
                  </form>
              </div>
          </div>
      </div>
  </article>
  @include('components.flo-footer__bottom')
</div>
@include("components.newsletter-popup")
