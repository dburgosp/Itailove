<!DOCTYPE html>
<html <?php language_attributes(); ?> class="layout-sections layout-sections--scroll-{{ get_field("page-home__scroll-type") }}">
  @include('layout.head')
  <body {{ body_class() }}>

    @include("layout.header")

    @yield('content')

    @if(isset($footer_custom) && $footer_custom == 1)
    @else
      @include('layout.footer')
    @endif
    {{ wp_footer() }}

  </body>
</html>
