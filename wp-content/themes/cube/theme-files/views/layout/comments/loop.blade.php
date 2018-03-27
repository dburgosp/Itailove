<?php

if(!function_exists('flo_comment')){
  function flo_comment($comment, $args, $depth) {
    global $flo_options;
?>

  <div {{ comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) }} id="comment-{{ comment_ID() }}">

    <div id="div-comment-{{ comment_ID() }}" class="comment-body to-appear">

      <!-- Start: Comment Header -->
        <div class="comment-header">

          <!-- Start: Avatar -->
            @if (flo_get_option("flo-cube-comments-comment__avatars-display",true) )
              <div class="comment-avatar">
                @if ($args['avatar_size'] != 0)
                  {{ get_avatar( $comment, $args['avatar_size'] ) }}
                @endif
              </div>
            @endif
          <!-- End: Avatar -->

          <!-- Start: Name and Date -->
            <div class="comment-name-and-date">

              <!-- Start: Author -->
                <div class="comment-author vcard">
                    <?php printf( __( '<div class="comment-name">%s</div>' ), get_comment_author_link() ); ?>
                </div>
              <!-- End: Author -->

              <!-- Start: Date -->
                @if (flo_get_option("flo-cube-comments-comment__date-display",true) )
                  <div class="comment-meta commentmetadata">
                    <a href="{{ htmlspecialchars( get_comment_link( $comment->comment_ID ) ) }}">
                    <?php
                    /* translators: 1: date, 2: time */
                    printf( __('%1$s at %2$s','flotheme' ), get_comment_date(),  get_comment_time() ); ?></a>
                    {{ edit_comment_link( __( '(Edit)','flotheme' ), '  ', '' ) }}
                  </div>
                @endif
              <!-- End: Date -->

            </div>
          <!-- End: Name and Date -->

        </div>
      <!-- End: Comment Header -->

      @if ( $comment->comment_approved == '0' )
        <em class="comment-awaiting-moderation">{{ _e( 'Your comment is awaiting moderation.','flotheme' ) }}</em>
        <br />
      @endif

      <div class="comment-content">
        {{ comment_text() }}

        <!-- Start: Reply Button -->
          <div class="reply">
            {{ comment_reply_link( array_merge( $args, array( 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) }}
          </div>
        <!-- End: Reply Button -->
      </div>

    </div>

<?php
  }
}
?>

  @include('core.style', [
    "breakpoint__general" => "
      .comment .comment .comment-body {
        background-color: ". flo_get_option("flo-cube-comments-reply__background-color","#fbfbfb") .";
      }
    "
  ])
<div class="flo-section flo-section--padding-small flo-section--post-comments">
  <article class="flo-section__content">
    <div class="comments-block__title to-appear" id="comments">
      {{ flo_get_option("flo-cube-comments-top-bar__comments-title",'') }}
    </div>
    @if ($comments = $post->get_comments())
      <div class="comments-block">
        <article class="comments-block__post">
          {{ wp_list_comments( array(
            'style'       => 'div',
            'short_ping'  => true,
            'avatar_size' => 56,
            'callback'    => "flo_comment"
          ) ) }}
        </article>
      </div>
    @else

    @endif
  </article>
</div>
