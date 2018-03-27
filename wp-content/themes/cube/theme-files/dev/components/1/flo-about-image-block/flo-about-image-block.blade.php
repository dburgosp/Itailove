@if(flo_data($data, 'about-page__image-block-enabled'))
  <div class="flo-section flo-section--padding-large about-page--image-block">
    <article class="flo-section__content">
      <figure>
        <a href="{{ flo_data($data, 'about-page__image-block-image-link') }}">
          <img src="{{ flo_data($data, 'about-page__image-block-image') }}" alt="">
        </a>
      </figure>
    </article>
  </div>
@endif
