@foreach($flo_social_links as $key => $social_profile)
	@if($social_profile['type']['value'] == 'custom')
		<?php
			if(isset($social_profile['custom-icon--hover']) && strlen($social_profile['custom-icon--hover']) ){
				$data_img_hover = 'data-icon_hover="'.$social_profile['custom-icon--hover'].'"';
			}else{
				$data_img_hover = '';
			}
		?>
		<a href="{{$social_profile['link']}}" target="_blank" class="flo-icon flo-icon__{{$social_profile['type']['value']}}  flo-social-links--link-custom ">
			@if(isset($social_profile['custom-icon']) && strlen($social_profile['custom-icon']) )
				<img src="{{$social_profile['custom-icon']}}" class="flo-footer__social-custom-icon" {{$data_img_hover}} />
			@endif
		</a>
	@else
    	<a href="{{$social_profile['link']}}" target="_blank" class="flo-icon flo-icon-{{$social_profile['type']['value']}} flo-social-links__link "></a>
    @endif

@endforeach
