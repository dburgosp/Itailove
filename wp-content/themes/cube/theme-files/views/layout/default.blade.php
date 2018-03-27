@include('layout.default__header')
    @include('core.style', [
      "breakpoint__medium_up" => "
        body {
          padding: ". ( flo_get_option("flo-cube-pages__vertical-border") / 16 ) ."rem ". ( flo_get_option("flo-cube-pages__horizontal-border") / 16) ."rem;
        }
        .flo-header-section,
        .flo-page-hero .flo-page-hero__slide-content {
          height: calc(100vh - ". ( flo_get_option("flo-cube-pages__horizontal-border") / 16) ."rem);
        }
      "
    ])
    @yield('before-content')

    <main class="flo_page_wrap {{ $flo_page_wrap__padding_top or "" }}">
      <div class="flo_page">
        @yield('content')
      </div>
      @if($layout_type && $layout_type != 'full_width')
      <div class="flo_sidebar flo-post {{ $sidebar_container_class }}">
        <?php dynamic_sidebar($sidebar) ?>
      </div>
      @endif
    </main>
    @yield('after-content')
    @include('components.flo-splash')
@include('layout.default__footer')
