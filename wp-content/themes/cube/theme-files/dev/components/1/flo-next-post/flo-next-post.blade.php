@include('core.style', [
  "breakpoint__general" => "
    .flo-next-post__title,
    .flo-next-post__post-title
    {
      color: ". $flo_options["flo-cube-pagination__next-block-elements-color"] .";
    }
    .flo-next-post .button{
      background-color: ". flo_get_option("flo-cube-pagination__next-block-button-background_color","#ffffff") .";
    }
    .flo-next-post .button{
      color: ". flo_get_option("flo-cube-pagination__next-block-button_color",'#000000') .";
    }
  "
])
<?php
    if(has_post_thumbnail()){
        $bg_img = get_the_post_thumbnail_url($next_post->ID);
    }else{
        $bg_img = get_template_directory_uri().'/theme-files/public/img/placeholder-img.jpg';
    }
?>
<a href="{{ get_permalink($next_post->ID) }}" class="flo-next-post" style="background-image: url({{ $bg_img }});">
    <h3 class="flo-next-post__title to-appear">
        {{ $next_post__title }}
    </h3>
    <h2 class="flo-next-post__post-title to-appear">
        {{ get_the_title($next_post->ID) }}
    </h2>
    <button class="button to-appear">
        {{ $next_post__button_title }}
    </button>
</a>
