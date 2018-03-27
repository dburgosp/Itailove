@if (flo_get_option("flo-cube-header__enable-search-field") )
  <div class="flo-header__search-wrap">
    <div class="flo-header__search-trigger">
      <i class="flo-header__search-icon flo-header__search-icon--open flo-icon-search"></i>
      <i class="flo-header__search-icon flo-header__search-icon--close flo-icon-cancel"></i>
    </div>
    <form class="flo-header__search-form" action="{{ home_url("/") }}" method="get">
      <input type="text" name="s" value="" placeholder="type here to search.." class="flo-header__search-input">
    </form>
  </div>
@endif