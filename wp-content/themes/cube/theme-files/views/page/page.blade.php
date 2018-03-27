<?php
	$data = get_fields();

// echo '<pre>';
// var_dump($data);
// echo '</pre>';

	if(isset($data['page-home__slider-enabled']) && $data['page-home__slider-enabled'] && isset($data['page-home__slider']) ){
		$header__slider_id = $data['page-home__slider'];
	}

	// if($field = get_field("page-home__slider-logo")) {
	//   $header__slider_logo = $field;
	// }

	if( isset($data["custom-header__addons-enabled"]) && $data["custom-header__addons-enabled"] ) { // scroll down_button
	  $header__section_title_enabled = $data["custom-header__addons-enabled"];
	}


	if( isset($data["page-home__slider-layout"]) ) {
	  //$slider_title_layout = $data["page-home__slider-layout"];
	  $header__slider_layout = $data["page-home__slider-layout"];
	}else{
		$header__slider_layout = 'center';
	}
?>
@extends('layout.default')

@section('custom_classes')
  page-default
@endsection


@section('content')
	@include('components.section-text-content--only-wp-content', [
	    "data" => $data
	])

	@if( !post_password_required() && isset($data["layout"]) && is_array($data["layout"]) && sizeof($data["layout"]))
      <?php
        $section_index = 0;
        // var_dump($data["layout"]);
      ?>
    	@foreach ($data["layout"] as $section_data)
        {{-- Open Block --}}
          @include('layout.block-opening')

    	      <?php
    	        $section_name = $section_data["acf_fc_layout"];
    	      ?>

            {{-- START: ABOUT TEMPLATE --}}
              @if($section_name == "top_block")
                @include("components.flo-about-block", [
                    "data" => $section_data
                  ])
              @elseif($section_name == "image_block")
                @include("components.flo-about-image-block", [
                    "data" => $section_data
                  ])
              @elseif($section_name == "info_block")
                @include("components.flo-text-image-block", [
                  "data" => $section_data
                ])
              @elseif($section_name == "quote_block")
                @include("components.flo-quote-block", [
                  "data" => $section_data
                ])
            {{-- END: ABOUT TEMPLATE --}}

            {{-- START: PRESS TEMPLATE --}}
      	      @elseif($section_name == "press_block")
      	        @include("components.press-page", [
      	          "data" => $section_data
      	        ])
            {{-- END: PRESS TEMPLATE --}}

            {{-- START: CONTACT TEMPLATE --}}
              @elseif($section_name == "contact")
      	        @include("components.flo-contact-block", [
      	          "data" => $section_data
      	        ])
            {{-- END: CONTACT TEMPLATE --}}

            {{-- START: HOME TEMPLATE --}}
              @elseif($section_name == "image_blocks")
      	        @include('components.flo-featured-posts', [
      	          "data" => $section_data
      	        ])

      	      @elseif($section_name == "home_text_block")
      	        @include("components.flo-information-block", [
      	          "data" => $section_data
      	        ])

      	      @elseif($section_name == "featured_posts")
      	        @include('components.flo-featured-items', [
      	          "data" => $section_data
      	        ])
            {{-- END: HOME TEMPLATE --}}

            {{-- START: MISC --}}
      	      @elseif($section_name == "section-text-content")
      	        @include("components.section-text-content", [
      	          "data" => $section_data
      	        ])
			  @elseif($section_name == "wordpress_content")
				  @include("components.section-text-content--only-wp-content", [
					"data" => $section_data,
					"block_content" => true
				  ])
      	      @elseif($section_name == "section-pricing-packages")
      	        @include("components.flo-pricing-packages", [
      	          "data" => $section_data
      	        ])

      	      @elseif($section_name == "section-testimonials-slideshow")
      	        @include("components.flo-testimonials-slideshow", [
      	          "data" => $section_data
      	        ])

      	      @elseif($section_name == "section-special-link")
      	        @include("components.flo-special-link", [
      	          "data" => $section_data
      	        ])

      	      @elseif($section_name == "slideshow-1")
      	        @include("components.flo-block-slideshow-1", [
      	          "data" => $section_data
      	        ])

      	      @elseif($section_name == "slideshow-2")
      	        @include("components.flo-block-slideshow-2", [
      	          "data" => $section_data
      	        ])
            {{-- END: MISC --}}

            {{-- START: LISTING --}}
      	      @elseif($section_name == "section-listing-title")
      	        @include("components.flo-listing-title", [
      	          "data" => $section_data
      	        ])
      	      @elseif($section_name == "section-listing-featured-item")
      	        @include("components.flo-listing-featured-item", [
      	          "data" => $section_data
      	        ])
      	      @elseif($section_name == "section-listing")
      	        @include("components.flo-listing", [
      	          "data" => $section_data
      	        ])
      	      @elseif($section_name == "section-listing-pagination")
      	        @include("components.flo-listing-pagination", [
      	          "data" => $section_data
      	        ])
            {{-- END: LISTING --}}

            {{-- START: OLD LISTING --}}
      	      @elseif($section_name == "section-listing-layout")
      	        @include("components.flo-listing", [
      	          "data" => $section_data
      	        ])
      	        @include("components.flo-listing-pagination", [
      	          "data" => $section_data
      	        ])
            {{-- END: OLD LISTING --}}

            {{-- START: TEMPLATE HOME A --}}
              @elseif($section_name == "title-1")
      	        @include("components.flo-block-title-1", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "image-links")
      	        @include("components.flo-block-image-links", [
      	          "data" => $section_data
      	        ])
            {{-- END: TEMPLATE HOME A --}}

            {{-- START: TEMPLATE HOME B --}}
              @elseif($section_name == "video")
      	        @include("components.flo-block-video", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "information-block-2")
      	        @include("components.flo-block-information-block-2", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "image-links-2")
      	        @include("components.flo-block-image-links-2", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "image-link-with-parallax")
      	        @include("components.flo-block-image-link-with-parallax", [
      	          "data" => $section_data
      	        ])
            {{-- END: TEMPLATE HOME B --}}

            {{-- START: TEMPLATE HOME C --}}
              @elseif($section_name == "featured-slideshow-1")
      	        @include("components.flo-block-featured-slideshow-1", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "information-block-3")
      	        @include("components.flo-block-information-block-3", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "divider")
      	        @include("components.flo-block-divider", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "featured-links-1")
      	        @include("components.flo-block-featured-links-1", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "newsletter-2")
      	        @include("components.flo-block-newsletter-2", [
      	          "data" => $section_data
      	        ])
            {{-- END: TEMPLATE HOME C --}}

            {{-- START: FIJI INVESTMENT --}}
              @elseif($section_name == "title-2")
      	        @include("components.flo-block-title-2", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "pricing-packages-2")
      	        @include("components.flo-block-pricing-packages-2", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "testimonials-2")
      	        @include("components.flo-block-testimonials-2", [
      	          "data" => $section_data
      	        ])
              @elseif($section_name == "instagram")
      	        @include("components.flo-block-instagram", [
      	          "data" => $section_data
      	        ])
            {{-- END: FIJI INVESTMENT --}}


  	      @endif
          <?php
            $section_index++;
          ?>
        @include('layout.block-closing')
      {{-- Close Block --}}
	  @endforeach
	@endif

@endsection
