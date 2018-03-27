@if ( ($page_saved = isset($data["section-text-content__enabled"]) && $data["section-text-content__enabled"])
      || !isset($data["section-text-content__enabled"]) || ( isset($block_content) && $block_content )
    )

  <?php

    $title = $post->title();

    if(isset($data["section-text-content__width"])){
      $width = $data["section-text-content__width"] . "%";
    }else{
      $width = "80%";
    }
    

    if(isset($data["section-text-content__top-gutter"])){
      $top_gutter = $data["section-text-content__top-gutter"] / 16 . "rem";
    }else{
      $top_gutter = 80 / 16 . "rem";
    }
    
    if(isset($data["section-text-content__bottom-gutter"])){
      $bottom_gutter = $data["section-text-content__bottom-gutter"] / 16 . "rem";
    }else{
      $bottom_gutter = 40 / 16 . "rem";
    }
    

    if(isset($data["section-text-content__title-font"])){
      $title_font = $data["section-text-content__title-font"];
    }else{
      // if the font meta does not exist, we will create it
      update_field('section-text-content__title-font', 'heading_3', $post->ID); 
      $title_font = get_field('section-text-content__title-font');
    }

    if(!isset($title_font['default'])){ 
      // surpress warnigns when migrating frm older themes
      $title_font = array();
      $title_font['default'] = '';
    }
    
    if(isset($data["section-text-content__title-bottom-margin"])){
      $title_bottom_margin = $data["section-text-content__title-bottom-margin"] / 16 . "rem";
    }else{
      $title_bottom_margin = 40 / 16 . "rem";
    }
  ?>

  <div class="flo-section flo-section--full-width full-scrollable-section flo-section--default-content">

      <article class="flo-section__content flo-post">
        <div class="section-text-content" style="width: {{ $width }}; padding-top: {{ $top_gutter }}; padding-bottom: {{ $bottom_gutter }}; ">
          @if ( (isset($data["section-text-content__show-title"]) && $data["section-text-content__show-title"]) || !isset($data["section-text-content__show-title"] ) )
            <h1 class="section-text-content__title" style="{{ $title_font["default"] }} margin-bottom: {{ $title_bottom_margin }}">
              {{ $title }}
            </h1>
          @endif
          <?php while ( have_posts() ) : the_post(); ?>
          <div class="section-text-content__content">
            {{ the_content(); }}
          </div>
        <?php endwhile; ?>

        </div>
      </article>
  </div>
@elseif(post_password_required())
  <div class="flo-section flo-section--full-width full-scrollable-section">
      <article class="flo-section__content flo-post">
          <?php while ( have_posts() ) : the_post(); ?>
              <div class="section-text-content__content">
                {{ the_content(); }}
              </div>
          <?php endwhile; ?>
      </article>
  </div>
@endif
