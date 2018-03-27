@include('core.style', [
  'breakpoint__medium_up' => '

    .flo-header-popup__background,
    .flo-header-popup__scene,
    .flo-header-popup__top-bar{
      background-color: '.flo_get_option("flo-cube-header-popup__background-color","#FFFFFF").'
    }

    .flo-header-popup__menu > div > ul > li:before {
      background-color: '.flo_get_option("flo-cube-header-popup__elements-color","#000000").';
    }
    .flo-header-popup__menu ul ul a{
      color: '.flo_get_option("flo-cube-header-popup__menu-dropdown-items-color","#ffffff").';
    }
    
    .flo-header-popup__menu ul ul  {
      background-color: '.flo_get_option("flo-cube-header-popup__menu-dropdown-background-color","#000000").';
    }

  '
])
<div class="flo-header-popup">
  <div class="flo-header-popup__background"></div>
  <div class="flo-header-popup__scene">

    <div class="flo-header-popup__top-bar">
      <div class="flo-header-popup__title">{{ flo_get_option("flo-cube-header-popup__title") }}</div>
      <div class="flo-header-popup__close-button">
        <i class="flo-icon-cancel"></i>
      </div>
    </div>

    <div class="flo-header-popup__views" data-orbit data-auto-play="false" data-orbit data-options="animInFromLeft:fade-in; animInFromRight:fade-in; animOutToLeft:fade-out; animOutToRight:fade-out;">

      {{-- START: MAIN VIEW --}}
        <div class="orbit-slide flo-header-popup__view flo-header-popup__view--main" data-title="{{ flo_get_option("flo-header-popup__title") }}">
          <div class="flo-header-popup__view-content">

            <div class="flo-header-popup__view-half">
              <div class="flo-header-popup__menu">
                {{
                  wp_nav_menu(
                    array(
                    'theme_location' => 'primary'
                    )
                  );
                }}
              </div>
              @if ($featured_links = flo_get_option("flo-cube-header-popup__featured-links"))
                <div class="flo-header-popup__special-links">
                  @foreach ($featured_links as $link)
                    <a href="{{ $link["url"] }}" class="flo-header-popup__special-link">{{ $link["title"] }}</a>
                  @endforeach
                </div>
              @endif
              <div class="flo-header-popup__addons">
                @if (flo_get_option("flo-cube-header-popup__social-links-display"))
                  @if(isset($flo_options["flo-social-links"]) && is_array($flo_options["flo-social-links"]) && sizeof($flo_options["flo-social-links"]))

                  <div class="flo-header-popup__social-links">
                    @include('components.social-links')
                  </div>
                  @endif
                @endif
                @if (flo_get_option("flo-cube-header-popup__search-display"))
                  <div class="flo-header-popup__search-trigger" data-show-view="search">
                    <i class="flo-icon-search"></i>
                  </div>
                @endif
              </div>
              <?php
              	ob_start();
              	ob_clean();
              	dynamic_sidebar('header-translation');
              	$header_translation = ob_get_clean();
              ?>
              @if($header_translation)
                <div class="flo-header-popup__language-switcher">
              		{{$header_translation}}
                </div>
              @endif
            </div>

            <div class="flo-header-popup__view-half">
              @if ($contact_blocks = flo_get_option("flo-cube-header-popup__contact-blocks"))
                @foreach ($contact_blocks as $block)
                  <div class="flo-header-popup__contact-block">
                    <div class="flo-header-popup__contact-block-title">
                      {{ $block["title"] }}
                    </div>
                    <div class="flo-header-popup__contact-block-content flo-post">
                      {{ $block["content"] }}
                    </div>
                  </div>
                @endforeach
              @endif
            </div>

          </div>
        </div>
      {{-- END: MAIN VIEW --}}

      {{-- START: SEARCH VIEW --}}
        <div class="orbit-slide flo-header-popup__view flo-header-popup__view--search iss-active" data-title="SEARCH">
          <div class="flo-header-popup__view-content">

            <div class="flo-header-popup__search-wrap">
              <form class="flo-header-popup__search-form" action="{{ home_url("/") }}" method="get">
                <input type="text" name="s" value="" placeholder="type here to search.." class="flo-header-popup__search-input">
                <input type="submit" name="" value="SEARCH" class="flo-header-popup__search-form-submit flo-btn">
              </form>
            </div>

          </div>
        </div>
      {{-- END: SEARCH VIEW --}}

    </div>

  </div>
</div>