<?php
$block_class = "flo-testimonials-slideshow--" . uniqid();
?>

@include('core.style', [
  "breakpoint__general" => "

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-testimonials-slideshow__title",
        $data['flo-testimonials-slideshow__title-font']
      )
    ."

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-testimonials-slideshow__testimonial-text",
        $data['flo-testimonials-slideshow__text-font']
      )
    ."

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-testimonials-slideshow__testimonial-name",
        $data['flo-testimonials-slideshow__author-name-font']
      )
    ."

    }
  "
])

<div class="flo-section">
  <div class="flo-section__content">

    <div class="flo-testimonials-slideshow {{ $block_class }}">

      <div class="flo-testimonials-slideshow__title">
        {{ $data["flo-testimonials-slideshow__title"] }}
      </div>

      <div class="flo-testimonials-slideshow__testimonials">
        @foreach ($data["flo-testimonials-slideshow__testimonials"] as $testimonial)

          <div class="flo-testimonials-slideshow__testimonial">
            <div class="flo-testimonials-slideshow__testimonial-text">
              {{ $testimonial["text"] }}
            </div>
            <div class="flo-testimonials-slideshow__testimonial-name">
              -
              <br>
              {{ $testimonial["author_name"] }}
            </div>
          </div>

        @endforeach
      </div>

    </div>

  </div>
</div>
