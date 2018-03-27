<div class="flo-social-links">
@if(isset($flo_options["flo-social-links"]) && is_array($flo_options["flo-social-links"]) && sizeof($flo_options["flo-social-links"]))
  @foreach($flo_social_links = $flo_options["flo-social-links"] as $key => $social_profile)

  	@if($social_profile['type']['value'] == 'custom')
  		<?php
  		if(isset($social_profile['custom-icon']) && strlen($social_profile['custom-icon']) ){
  			$custom_class = 'hide-label'; // if the custom icon is available, then we hide the label
  		}else{
  			$custom_class = '';
  		}

      $has_hover_icon_class = "";
    	if(isset($social_profile['custom-icon--hover']) && strlen($social_profile['custom-icon--hover']) ) {
        $has_hover_icon_class = "flo-social-links__link--has-hover-icon";
      }
  		?>
  		<a href="{{$social_profile['link']}}" target="_blank" class="flo-social-links__link flo-social-links--link-custom {{ $has_hover_icon_class }}">

        <span class="flo-social-links__custom-icon-wrap">
    			@if(isset($social_profile['custom-icon']) && strlen($social_profile['custom-icon']) )
    				<img src="{{$social_profile['custom-icon']}}" class="flo-social-links__custom-icon flo-social-links__custom-icon--default" />
    			@endif
    			@if(isset($social_profile['custom-icon--hover']) && strlen($social_profile['custom-icon--hover']) )
    				<img src="{{$social_profile['custom-icon--hover']}}" class="flo-social-links__custom-icon flo-social-links__custom-icon--hover" />
    			@endif
        </span>

  			{{-- <span class="flo-social-links__label">{{$social_profile['custom-label']}}</span> --}}
  		</a>
  	@else
    	<a href="{{$social_profile['link']}}" target="_blank"
    	class="flo-icon-{{$social_profile['type']['value']}} flo-social-links__link"></a>
    @endif

  @endforeach
@endif
</div>
