@if($data["information-block__enabled"])
  <?php
    $section_information_block__title = $data["information-block__title"];
    $section_information_block__content = $data["information-block__content"];
    $section_information_block__link = $data["information-block__link"];
    $section_information_block__link_label = $data["information-block__link-label"];

    $section_information_block__block_2_title = $data["information-block__block_2_title"];
    $section_information_block__block_2_items = $data["information-block__block_2_items"];

    $section_information_block__block_3_title = $data["information-block__block_3_title"];
    $section_information_block__block_3_items = $data["information-block__block_3_items"];

    $section_information_block__view_all_projects_url = $data["information-block__view-all-projects-url"];
    $section_information_block__view_all_projects_label = $data["information-block__view-all-projects-label"];

    // echo '<pre>';
    //   var_dump($data);
    // echo '</pre>';
    $text_block_class = "text-block--".uniqid();

    $text_block_font_styles = '';

    if(isset($data['information-block__title_font'])){
      $info_block_title_selector = '.' . $text_block_class . ' .flo-information-block__title';
      $text_block_font_styles .= flo_render_typography_styles( $info_block_title_selector, $data['information-block__title_font']);
    }

    if(isset($data['information-block__content_font'])){
      $info_block_content_selector = '.' . $text_block_class . ' .flo-information-block__content';
      $text_block_font_styles .= flo_render_typography_styles( $info_block_content_selector, $data['information-block__content_font']);
    }


    if(isset($data['information-block__link-label_font'])){
      $info_block_link_label_selector = '.' . $text_block_class . ' .flo-information-block__btn';
      $text_block_font_styles .= flo_render_typography_styles( $info_block_link_label_selector, $data['information-block__link-label_font']);
    }



    if(isset($data['information-block__block_2_items_label_font'])){
      $info_block_item_label_selector = '.' . $text_block_class . ' .flo-information-block__featured-item';
      $text_block_font_styles .= flo_render_typography_styles( $info_block_item_label_selector, $data['information-block__block_2_items_label_font']);
    }


  ?>
  @include('core.style', [
    "breakpoint__general" => $text_block_font_styles
  ])
  <div class="flo-section full-scrollable-section {{$text_block_class}}">
      <article class="flo-section__content">
          <div class="flo-information-block">
              <div class="flo-information-block__text to-appear">
                  <h1 class="flo-information-block__title">
                      {{ $section_information_block__title or "" }}
                  </h1>
                  <div class="flo-information-block__content">
                      {{ $section_information_block__content or "" }}
                  </div>
                  <a href="{{ $section_information_block__link or "#" }}" class="flo-information-block__btn ">{{ $section_information_block__link_label or "" }}</a>
              </div>

              <div class="flo-information-block__featured-item-block">
                  @if(!empty($section_information_block__block_2_items))
                      <div class="flo-information-block__featured-items to-appear">
                          <div class="flo-information-block__title">
                              {{ $section_information_block__block_2_title }}
                          </div>
                          <ul class="flo-information-block__featured-items-list">
                              @foreach($section_information_block__block_2_items as $item)
                                  <li class="flo-information-block__featured-item">
                                      <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                  </li>
                              @endforeach
                          </ul>
                      </div>
                  @endif

                  @if(!empty($section_information_block__block_3_items))
                      <div class="flo-information-block__featured-items to-appear">
                          <div class="flo-information-block__title">
                              {{ $section_information_block__block_3_title }}
                          </div>
                          <ul class="flo-information-block__featured-items-list">
                              @foreach($section_information_block__block_3_items as $item)
                                  <li class="flo-information-block__featured-item">
                                      <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                                  </li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
              </div>

              <a href="{{ $section_information_block__view_all_projects_url }}" class="flo-information-block__more-link flo-information-block__btn">
                  {{ $section_information_block__view_all_projects_label }}
              </a>
          </div>

      </article>
  </div>
@endif
