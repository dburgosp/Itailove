<!-- Start: Flo Page hero -->
    <div class="flo-page-hero">
        <div class="flo-page-hero__slider">
            <div class="flo-page-hero__slides">
                <div class="flo-page-hero__slide">
                    <div class="flo-page-hero__slide-content"
                       style="background-image: url({{ get_the_post_thumbnail_url($header__hero_post) }})">
                        <div class="flo-page-hero__slide-info">
                            <div class="flo-page-hero__slide-category">{{$categories_list}}</div>
                            <div class="flo-page-hero__slide-title">{{ $post->title() }}</div>
                            @if(!post_password_required())
                            <div class="flo-page-hero__slide-description">{{ get_the_excerpt($header__hero_post) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End: Flo Page hero -->
