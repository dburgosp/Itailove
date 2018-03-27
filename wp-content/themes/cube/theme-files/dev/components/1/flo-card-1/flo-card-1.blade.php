{{--

Uses as data:
- $flo_card_1__decoration_src
- $flo_card_1__text
- $flo_card_1__btn_href
- $flo_card_1__btn_title

 --}}

<div class="flo-card-1">
  <a href=" {{$flo_card_1__btn_href or "#" }}"  class="hover-block">
    @if(isset($flo_card_1__decoration_src) && $flo_card_1__decoration_src != "")
      <figure class="flo-card-1__decoration" style="background-image: url('{{ $flo_card_1__decoration_src }}')"></figure>
    @endif
    <div class="flo-card-1__text">
      {{ $flo_card_1__text or "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor" }}
    </div>
    @if(isset($flo_card_1__btn_href) && $flo_card_1__btn_href != "")
      <a href="{{ $flo_card_1__btn_href }}" class="flo-btn flo-btn--link flo-card-1__btn">{{ $flo_card_1__btn_title or "Read more"}}</a>
    @endif
  </a>
</div>
