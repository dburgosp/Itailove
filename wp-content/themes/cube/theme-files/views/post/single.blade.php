@extends('layout.default')

@section('content')
	<?php while ( have_posts() ) : the_post(); ?>
    <?php
      //flo-special_block__text
      $special_block__text_enabled = flo_get_option("flo-cube-blog-post__display-special-block",false);

      if (get_field("flo-blog-post__different-title-block-position")) {
        $title__position = get_field("flo-cube-blog-post__title-block-position");
      } else {
        $title__position = flo_get_option("flo-cube-blog-post__title-block-position", "under");
      }

      if(!$special_block__text_enabled){
        // if the special block is disabled then we consider the type to be 'additional_information'
        // we do that because if the 'related_posts' class does not function well without the special block
        $special_block__type = 'additional_information';
      }else{
        if ( $special_block_type_single = get_field("flo-blog-post__different-special-block-type")) {
          // check the single Meta which has priority
          $special_block__type = get_field("flo-cube-blog-post__special-block-type");
        } else {
          // if single meta is not available, we check the global options
          $special_block__type = flo_get_option("flo-cube-blog-post__special-block-type", "related_posts");
        }
      }


    ?>

    <div class="flo-post-wrap flo-post-wrap--special-block-{{ $special_block__type }}">

      <!-- Start: Post -->
        <div class="flo-section flo-section--padding-small">
          <article class="flo-section__content flo-section__content--post flo-section__content--post-title-{{ $title__position }}">

              {{-- Start: Title --}}
                @section('post-title')
                  <?php
                    $title_centered = flo_get_option("flo-cube-blog-post__title-block-centered", false);
                    $title_centered_class = $title_centered ? "flo-post-title-wrap--centered" : "";
                  ?>

                  <div class="flo-post-title-wrap {{$title_centered_class}}">

                    <div class="flo-post-title-wrap__left-wrap">

                      @if( strlen($categories_list) )
                        <div class="flo-post-title-wrap__category">
                          {{ $categories_list }}
                        </div>
                      @endif

                      <h1 class="flo-post-title-wrap__title">
                        {{ $post->title() }}
                      </h1>

                      @if (strlen($post->post_excerpt))
                        <div class="flo-post-title-wrap__excerpt">
                          {{ $post->post_excerpt }}
                        </div>
                      @endif

                    </div>

                    <div class="flo-post-title-wrap__right-wrap">

                      <div class="flo-post-title-wrap__ritgh-wrap-top-part">
                        @if(flo_get_option("flo-cube-blog-post__title-area-date", true))
                          <div class="flo-post-title-wrap__date entry-date">
                            {{ flo_get_the_date($post->ID) }}
                          </div>
                        @endif

                      </div>

                      @if(flo_get_option("flo-cube-blog-post__display-share-options", true))
                          @include('components.flo-share-wrap')
                      @endif

                    </div>

                  </div>
                @endsection

                @if ($title__position == "above")
                  @section('above-featured-image')
                    @yield('post-title')
                  @endsection
                @endif
                @if ($title__position == "under")
                  @section('under-featured-image')
                    @yield('post-title')
                  @endsection
                @endif
              {{-- End: Title --}}

              {{-- Start: Featured Image --}}
                  @yield("above-featured-image")

                  <?php
                    $featured_image_size = flo_get_option("flo-cube-blog-post__featured-image-size", "full-width");
                    $featured_image_height = flo_get_option("flo-cube-blog-post__featured-image-height", "650") / 16 . "rem";
                  ?>
                  @if( has_post_thumbnail() && flo_get_option("flo-cube-blog-post__featured-image-display") && !(post_password_required()))
                    <figure class="flo-featured-image flo-featured-image__size-{{ $featured_image_size }}" style="background-image: url({{ get_the_post_thumbnail_url() }}); height: {{ $featured_image_height }};">
                      {{ the_post_thumbnail() }}
                      @if (get_field("flo-cube-post__video-embed-code"))
                        @include('components.flo-video-embed', [
                          "embed_code" => get_field("flo-cube-post__video-embed-code")
                        ])
                      @endif
                    </figure>
                  @endif

                  @yield("under-featured-image")
              {{-- End: Featured Image --}}

              {{-- Start: Special Block --}}
                  <?php
                    $special_block_content = false;

                    if($special_block__type == "additional_information" && !post_password_required()){
                      $special_block__additional_information = get_field("flo-blog-post__additional-information");

                      if(is_array($special_block__additional_information) &&  sizeof($special_block__additional_information)){
                        $special_block_content = true;
                      }

                    }


                    // init this with false. We will check later if the related posts are enbaled and if there are any realated posts
                    $related_posts_enabled = false;
                    if('related_posts' == $special_block__type && !post_password_required()){
                      $special_block__related_posts_count = flo_get_option("flo-cube-blog-post__related-posts_count", 6);
                      $special_block__related_posts = similar_query($post->ID, 'category', $special_block__related_posts_count);

                      if(sizeof($special_block__related_posts)){
                        $special_block_content = true;
                      }
                    }

                  ?>
                  @if($special_block__text_enabled && $special_block_content && !post_password_required())
                      @include('components.flo-special-block')
                  @endif
              {{-- End: Special Block --}}

              {{-- Start: Post Content --}}
                  <div class="flo-post flo-post--content ">
                      <?php
                        if(post_password_required()){
                          the_content();
                        }else{
                          the_content();
                          //echo (apply_filters('the_content', $post->content() ));
                        }


                      ?>
                  </div>
              {{-- End: Post Content --}}



            {{-- Start: Bottom Wrap --}}
              <div class="flo-post-bottom-wrap to-appear">
                <div class="flo-post-bottom-wrap__tags">
                  <?php
                  if(flo_get_option('flo-cube-blog-post__show-tags',true)){
                    $tags_list = get_the_tag_list( '', " " );
                  }else{
                    $tags_list = false;
                  }
                  ?>
                  {{ $tags_list }}
                </div>
                <div class="flo-post-bottom-wrap__share-wrap">
                  @if (flo_get_option('flo-cube-blog-post-bottom__share-display',true) )
                    <div class="flo-post-bottom-wrap__share-label">
                      {{ flo_get_option("flo-cube-blog-post-bottom__share-label",__('SHARE','flotheme')) }}
                    </div>
                    <div class="flo-post-bottom-wrap__share-links">
                      @include('components.flo-share-wrap')
                    </div>
                  @endif
                </div>
              </div>
            {{-- End: Bottom Wrap --}}
          </article>
        </div>
      <!-- End: Post -->


      @include('components.single-bottom')
    </div>

	<?php endwhile; ?>
@stop
