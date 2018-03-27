<div class="flo-hero-3 {{ $class or "" }}">
    <div class="flo-hero-3__slider" data-slick='{{ $slick_configuration or "" }}'>
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

            if(isset($slide['alt']) && strlen($slide['alt']) ){
                $alt_text = $slide['alt'];
            }else{
                $alt_text = '';
            }
            $img = aq_resize( $img_url, $width = 99999, $height = $flo_gallery__max_slider_image_height, false, $single = true, $upscale = false );

            if(isset($slide['width']) && isset($slide['height']) ){
                $img_lqip = flo_lqip($img, $crop_tumbs = false, $image_width = $slide['width'], $image_height = $slide['height']);
            }else{
                $img_lqip = flo_lqip($img);
            }

            ?>
            <div class="flo-hero-3__slide">
              @if ($use_bgi)
                <?php
                    if(isset($slide['width']) && isset($slide['height']) ){
                        $lqip_attr = flo_lqip__set_attr($img, true, true, $slide['width'], $slide['height']);
                    }else{
                        $lqip_attr = flo_lqip__set_attr($img, true, true);
                    }
                ?>
                <div class="flo-hero-3__slide-content" aria-label="{{ $alt_text }}" {{ $lqip_attr }}></div>
                <img alt="{{$alt_text}}" src="{{ $img_lqip }}" data-lqip-src="{{ $img_lqip }}" data-original-src="{{ $img }}" class="flo-hero-3__slide-img" data-lqip--is-manual="true">
              @else
                <img alt="{{$alt_text}}" src="{{ $img_lqip }}" data-lqip-src="{{ $img_lqip }}" data-original-src="{{ $img }}" class="flo-hero-3__slide-img" data-lqip--is-manual="true">
              @endif

              @if ($video_code)
                @include('components.flo-video-embed', [
                  "embed_code" => $video_code
                ])
              @endif
            </div>
            <?php
        $counter ++;
        }

        ?>
    </div>

</div>
