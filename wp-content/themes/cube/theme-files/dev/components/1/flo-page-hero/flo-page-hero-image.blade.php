@if( has_post_thumbnail() && get_field('page__cover-image') )
<?php
  $featured_image_size = "full-width";
  $featured_image_height =  (650/16) . "rem";
?>
<figure class="flo-featured-image flo-featured-image__size-{{ $featured_image_size }}" style="background-image: url({{ get_the_post_thumbnail_url() }}); height: {{ $featured_image_height }};">
  {{ the_post_thumbnail() }}
</figure>
@endif
