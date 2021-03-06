<?php
$navigation_type = flo_get_option('flo-cube-pagination__next-type', 'classic');
?>

{{-- Start: Classic Pagination --}}
  @if($navigation_type == 'classic' )
    <?php
    if (($prev_post = get_previous_post()) != "") {
      $flo_post_pagination__link_prev = get_the_permalink($prev_post->ID);
    }

    if (($next_post = get_next_post()) != "") {
      $flo_post_pagination__link_next = get_the_permalink($next_post->ID);
    }
    ?>
    @include('components.flo-pagination--bottom')
  @endif
{{-- End: Classic Pagination --}}

{{-- Start: Comments --}}
  <?php
    if(get_post_type() == 'gallery'){
      // $show_gal_comments is defined in single gallery scope
      $show_comments = $show_gal_comments;
    }else{
      // for other post types we let WP to handle it
      $show_comments = true;
    }
  ?>
  @if ($show_comments && (comments_open() || $post->get_comments() ) && $comments_options && is_array($comments_options) )
      <?php foreach ($comments_options as $key => $value) {
        switch ($value) {
          case 'wp':

              comments_template();

            break;

          case 'fb_comments':
            ?>
              <div class="fb-root"></div>
              <script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript"></script>
              <div class="fb-comments" data-href="<?php the_permalink(); ?>" data-numposts="5"></div>
            <?php
            break;
        }
      } ?>
  @endif

{{-- End: Comments --}}

{{-- Start: Big Pagination --}}
  @if($navigation_type == 'card' || $navigation_type == "block")
    @include("components.pagination", [
      "next_post" => get_previous_post(),
    ])
  @endif
{{-- End: Big Pagination --}}

{{-- START: RELATED POSTS --}}
  @include('components.flo-related-items')
{{-- END: RELATED POSTS --}}
