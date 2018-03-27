{{--

Uses as data:
- $flo_card_2__image
- $flo_card_2__href
- $flo_card_2__title

--}}

<div class="flo-card-2">
  <a href="{{ $flo_card_2__href or "#" }}" class="hover-block">
    @if(isset($flo_card_2__image) && $flo_card_2__image != "")
      <figure class="flo-card-2__img" style="background-image: url({{ $flo_card_2__image }})">
        <img src="{{ $flo_card_2__image }}">
      </figure>
    @endif
    <h2 class="flo-card-2__title text-underline">{{ $flo_card_2__title or "Weddings" }}</h2>
  </a>
</div>
