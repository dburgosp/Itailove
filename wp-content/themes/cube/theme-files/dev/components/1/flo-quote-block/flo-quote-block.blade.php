
<?php
  if(
    isset($data['about-page__quote-block-enabled']) && $data['about-page__quote-block-enabled']
  ){
    $block_class = "about-page--quote-block-".uniqid();
    $flo_quote_block_enabled = $data['about-page__quote-block-enabled'];
    $flo_quote_block_quote = $data['about-page__quote'];
    $flo_quote_block_quote_font_css = flo_render_typography_styles(
      ".".$block_class." .flo-quote-block__quote",
      $data['about-page__quote-font']
    );

    $flo_quote_block_signature_image = $data['about-page__quote-signature-image'];
    if(isset($data['about-page__quote-padding-bottom']) && is_numeric($data['about-page__quote-padding-bottom'])){
      $flo_quote_padding_bottom = $data['about-page__quote-padding-bottom']/16;
    }else{
      $flo_quote_padding_bottom = 13.5625;
    }

    if(isset($data['about-page__quote-padding-top']) && is_numeric($data['about-page__quote-padding-top'])){
      $flo_quote_padding_top = $data['about-page__quote-padding-top']/16;
    }else{
      $flo_quote_padding_top = 3.4375;
    }

    $has_no_image_class = $flo_quote_block_signature_image ? "" : "flo-quote-block--has-no-image";
?>
  @include('core.style', [
    "breakpoint__general" => $flo_quote_block_quote_font_css
  ])
  <div class="flo-section flo-section--padding-large flo-section--margin-left about-page--quote-block {{ $block_class }}" style="padding-bottom:{{$flo_quote_padding_bottom}}rem; ">
      <article class="flo-section__content">
      	<div class="flo-quote-block {{ $has_no_image_class }}" style="padding-top:{{$flo_quote_padding_top}}rem;">
  		    <div class="flo-quote-block__quote">{{ $flo_quote_block_quote }}</div>
          @if ( $flo_quote_block_signature_image )
    		    <figure class="flo-quote-block__signature-image">
    		        <img src="{{ $flo_quote_block_signature_image }}" alt="">
    		    </figure>
          @endif
  		</div>
  	</article>
  </div>
<?php } // END IF ?>
