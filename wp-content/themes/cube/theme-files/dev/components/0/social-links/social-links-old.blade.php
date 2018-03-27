<div class="flo-footer__social flo-footer__addons-social">
	<div class="flo-social-block">
@foreach($flo_social_links = $flo_options["flo-social-links"] as $key => $social_profile)

	@if($social_profile['type']['value'] == 'custom')
		<?php
		if(isset($social_profile['custom-icon']) && strlen($social_profile['custom-icon']) ){
			$custom_class = 'hide-label'; // if the custom icon is available, then we hide the label
		}else{
			$custom_class = '';
		}
		?>
		<a href="{{$social_profile['link']}}" target="_blank" class="flo-icon flo-icon__{{$social_profile['type']['value']}} flo-footer__social-link custom-icon {{$custom_class}}">
			@if(isset($social_profile['custom-icon']) && strlen($social_profile['custom-icon']) )
				<img src="{{$social_profile['custom-icon']}}" class="flo-footer__social-custom-icon" />
			@endif
			@if(isset($social_profile['custom-icon--hover']) && strlen($social_profile['custom-icon--hover']) )
				<img src="{{$social_profile['custom-icon--hover']}}" class="flo-footer__social-custom-icon-hover" />
			@endif
			<span class="flo-footer__social-label">{{$social_profile['custom-label']}}</span>
		</a>
	@else
  	<a href="{{$social_profile['link']}}" target="_blank"
  	class="flo-icon flo-icon-{{$social_profile['type']['value']}} flo-social-block__link"></a>
  @endif

@endforeach
	</div>
</div>
