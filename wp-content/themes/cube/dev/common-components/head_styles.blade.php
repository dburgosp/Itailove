<style media="screen" class="head_styles">
  @stack('head_styles')

  /* START: SMALL ONLY */
    @media (max-width: 767px) {
      @stack('head_styles__small_only')
    }
  /* END: SMALL ONLY */

  /* START: MEDIUM ONLY */
    @media (min-width: 768px) and (max-width: 1024px) {
      @stack('head_styles__medium_only')
    }
  /* END: MEDIUM ONLY */

  /* START: MEDIUM UP*/
    @media (min-width: 768px) {
      @stack('head_styles__medium_up')
    }
  /* END: MEDIUM UP*/

  /* START: LARGE UP*/
    @media (min-width: 1025px) {
      @stack('head_styles__large_up')
    }
  /* END: LARGE UP*/
</style>
