<?php
$block_class = "flo-special-link--" . uniqid();
?>

@include('core.style', [
  "breakpoint__general" => "

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-special-link__pretitle",
        $data['flo-special-link__pretitle-font']
      )
    ."

    ".
      flo_render_typography_styles(
        ".".$block_class." .flo-special-link__title",
        $data['flo-special-link__title-font']
      )
    ."

  "
])

<div class="flo-section">
  <div class="flo-section__content">

    <div class="flo-special-link {{ $block_class }}">

      <div class="flo-special-link__pretitle">
        {{ $data["flo-special-link__pretitle"] }}
      </div>

      <a href="{{ $data["flo-special-link__title-url"] }}" class="flo-special-link__title">
        {{ $data["flo-special-link__title"] }}
      </a>

    </div>

  </div>
</div>
