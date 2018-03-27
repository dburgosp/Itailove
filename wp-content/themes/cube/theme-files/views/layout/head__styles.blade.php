<style media="screen">
  @yield('head__styles')

  /* START: SMALL ONLY */
    @media (max-width: 767px) {
      @yield('head__styles--small-only')
    }
  /* END: SMALL ONLY */

  /* START: MEDIUM ONLY */
    @media (min-width: 768px) and (max-width: 1024px) {
      @yield('head__styles--medium-only')
    }
  /* END: MEDIUM ONLY */

  /* START: MEDIUM UP*/
    @media (min-width: 768px) {
      @yield('head__styles--medium-up')
    }
  /* END: MEDIUM UP*/

  /* START: LARGE UP*/
    @media (min-width: 1025px) {
      @yield('head__styles--large-up')
    }
  /* END: LARGE UP*/
</style>
