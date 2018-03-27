@if ($data["section-text-content__enabled"])

  <?php

    $content = apply_filters('the_content', $data["section-text-content__content"]);

    $title = $data["section-text-content__title"];

    $width = $data["section-text-content__width"] . "%";

    $top_gutter = $data["section-text-content__top-gutter"] / 16 . "rem";
    $bottom_gutter = $data["section-text-content__bottom-gutter"] / 16 . "rem";

    $title_font = $data["section-text-content__title-font"];
    $title_bottom_margin = $data["section-text-content__title-bottom-margin"] / 16 . "rem";
  ?>

  <div class="flo-section flo-section--full-width full-scrollable-section flo-section--default-content">

      <article class="flo-section__content flo-post">
        <div class="section-text-content flo-post" style="width: {{ $width }}; padding-top: {{ $top_gutter }}; padding-bottom: {{ $bottom_gutter }}; ">

          <h2 class="section-text-content__title" style="{{ $title_font["default"] }} margin-bottom: {{ $title_bottom_margin }}">
            {{ $title }}
          </h2>

          <div class="section-text-content__content">
            {{ $content }}
          </div>

        </div>
      </article>

  </div>


@endif
