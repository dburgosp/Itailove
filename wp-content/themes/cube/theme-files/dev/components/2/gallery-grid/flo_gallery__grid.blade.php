{{--  scope data is provided by
/monte/theme-files/scope/gallery/single.php  --}}

<div class="flo-section flo-section--padding-small flo-section--portfolio-grid-images">
    <div class="flo-section__content">
        <div class="flo-portfolio-grid">
            <div class="row row-flex {{$selected_grid_ratio}}">
            <?php
                $counter = 0;

                foreach ($gallery_items as $item) {

                    $slide = $item;
                    $video_code = false;

                    switch ($the_real_gallery_type) {
                      case 'image':
                        //$img_url = wp_get_attachment_url($slide, 'full');
                        if(is_array($slide) && isset($slide['url'])){
                            $img_url = $slide['url'];
                        }else{
                            // compatibility with the galleries created before ACF
                            $img_url = wp_get_attachment_url($slide, 'full');
                        }
                        $slide = $item;
                      break;

                      case 'video':
                        $slide = $item["image"];
                        $img_url = $slide;
                        $video_code = $item["video_embed_code"];
                      break;

                      case 'prius':
                        $slide = $item["image"];
                        $img_url = $slide;
                        $video_code = $item["video_embed_code"];
                      break;
                    }

                    // we want set the image quality as low as possible and generate a smaller proportional thumbnail in order
                    // to have a faster page load
                    //$img_lqip = flo_lqip($img_url = $img_url, $crop_tumbs = $crop_tumbs, $image_width = $grid_sizes['width'], $image_height = $grid_sizes['height'] );

                    $img = aq_resize(
                      $img_url,
                      $width = $grid_sizes['width'],
                      $height = $grid_sizes['height'],
                      $crop = $crop_tumbs,
                      $single = true,
                      $upscale = false
                    );

                    if(isset($slide['alt'])){
                        $alt_text = $slide['alt'];
                    }else{
                        $alt_text = '';
                    }
                    ?>
                    <div class="flo-portfolio-grid__thumbnail to-appear column {{$gallery_grid_columns_class}}"  class="flo-portfolio-card" data-open="popup-gallery" data-img-index="{{ $counter }}" aria-controls="popup-gallery" aria-haspopup="true" tabindex="0">
                      @if ($counter < 10)
                        <img src="{{ $img }}" alt="{{ $alt_text }}" class="flo-gallery-type-c__image">
                      @else
                        <img data-src="{{ $img }}" alt="{{ $alt_text }}" class="flo-gallery-type-c__image">
                      @endif
                    </div>

                    <?php
                    $counter ++;
                }
            ?>
            </div>
        </div>
    </div>
</div>
