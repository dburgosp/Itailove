{{-- Note! The comment textarea is moved down using a filter specified in theme-functions.php. --}}
<div class="flo-section flo-section--padding-small flo-comment-form">
    <article class="flo-section__content">
        <div class="flo-comment-form__title to-appear">
          {{ flo_get_option("flo-cube-comments-form__title",__('YOUR COMMENT','flotheme')) }}
        </div>
        <div class="flo-comment-form__wrap">
            <?php
            $commenter = wp_get_current_commenter();
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );
            ?>
            <script type="text/javascript">
              jQuery(function($){
                $(".flo-comment-form__form").parsley();
              });
            </script>
            {{ comment_form([
              "class_form"    => "flo-comment-form__form",
              'title_reply'   => "",
              'logged_in_as'  => "",
              'comment_notes_before' => "",
              'fields'        => array(
                'author' =>
                '
                <div class="flo-comment-form__field-wrap flo-comment-form__field-wrap--third to-appear">
                  <div class="flo-comment-form__field-label">
                    NAME
                  </div>
                  <div class="flo-comment-form__field">
                    <input class="flo-comment-form__name" data-parsley-required id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    '" size="30"' . $aria_req . ' />
                  </div>
                </div>
                ',

                'email' =>
                  '
                  <div class="flo-comment-form__field-wrap flo-comment-form__field-wrap--third to-appear">
                    <div class="flo-comment-form__field-label">
                      EMAIL
                    </div>
                    <div class="flo-comment-form__field">
                      <input class="flo-comment-form__email" data-parsley-required data-parsley-trigger="change" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                  '" size="30"' . $aria_req . ' />
                    </div>
                  </div>
                  ',

                'url' => '
                <div class="flo-comment-form__field-wrap flo-comment-form__field-wrap--third to-appear">
                  <div class="flo-comment-form__field-label">
                    WEBSITE
                  </div>
                  <div class="flo-comment-form__field">
                    <input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
                  </div>
                </div>
                ',
              ),
              'comment_field' => '
                <div class="flo-comment-form__field-wrap flo-comment-form__field-wrap--full to-appear">
                  <div class="flo-comment-form__field-label">
                    COMMENT
                  </div>
                  <div class="flo-comment-form__field">
                    <textarea class="flo-comment-form__message" data-parsley-required id="comment" name="comment" aria-required="true"></textarea>
                  </div>
                </div>
                ',
              "cancel_reply_link" => "<i class='flo-icon-close'></i>",
              "class_submit" => "to-appear",
              "label_submit"   => flo_get_option("flo-cube-comments-form__submit-label",__('SUBMIT','flotheme') )

            ]) }}

        </div>
    </article>
</div>
