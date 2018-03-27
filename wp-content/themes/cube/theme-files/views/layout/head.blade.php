<head>
  <meta charset="{{ bloginfo( 'charset' ) }}" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <link rel="alternate" type="application/rss+xml" title="{{ bloginfo('name') }} RSS Feed" href="{{ bloginfo('rss2_url') }}" />
  @if ( strlen($flo_custom_favicon))
      <link rel="shortcut icon" href="{{ $flo_custom_favicon }}" />
  @else
      <link rel="shortcut icon" href="{{ CLASSY_THEME_DIR }}assets/favicon.ico" />
  @endif
  <link rel="profile" href="http://gmpg.org/xfn/11">
  @if  ( is_singular() && pings_open( get_queried_object() ) )
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  @endif
  {{-- <title>{{ wp_title('|', true, 'right'); }}</title> --}}
  {{ wp_head() }}
  @yield('head__styles')

</head>
