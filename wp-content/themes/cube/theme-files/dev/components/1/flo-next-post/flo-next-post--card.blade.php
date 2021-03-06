<a href="{{ get_permalink($next_post->ID) }}" class="flo-next-post flo-next-post--card to-appear">

    <div class="flo-card-5__content">
        <div class="flo-card-5__text">
            <div class="flo-card-5__category">
                {{ $next_post__title }}
            </div>
            <div class="flo-card-5__title">
                {{ get_the_title($next_post->ID) }}
            </div>
            <div class="flo-card-5__excerpt">
                {{ flo_trim_excerpt($next_post->post_content) }}
            </div>
        </div>
        <figure class="flo-card-5__img" style="background-image: url({{ get_the_post_thumbnail_url($next_post->ID) }})"></figure>
    </div>
</a>
