
{{--

Uses as data:
- $flo_links_block_1__title
- $flo_links_block_1__links
  - pretitle
  - title
  - href

--}}

<div class="flo-links-block-1">
    <h2 class="flo-links-block-1__title title-line-bottom title-line-bottom--center">{{ $flo_links_block_1__title or "2016" }}</h2>
    <div class="flo-links-block-1__row">
      @if($flo_links_block_1__links)
        @foreach($flo_links_block_1__links as $link)
          <div class="flo-links-block-1__field">
            <span  class="flo-links-block-1__date">{{$link["pretitle"]}}</span>
            <h6 class="flo-links-block-1__location"><a href="{{ $link["link"] }}">{{ $link["title"] }}</a></h6>
          </div>
        @endforeach
      @endif
    </div>
</div>
