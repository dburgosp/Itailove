<?php
	$default_sharing_services = array(
    	array(  'value' => 'facebook', 'label' => 'Facebook'),
		array(  'value' => 'twitter', 'label' => 'Twitter' )
	);
	$flo_sharing_options = flo_get_option('flo-cube-sharing-options', $default_sharing_services);

?>
@if(isset($flo_sharing_options) && is_array( $flo_sharing_options) && sizeof($flo_sharing_options))
	<?php

		$post_permalink = urlencode(get_permalink($post->ID));
		$social_mapping = array(
			'facebook' => 'http://www.facebook.com/sharer/sharer.php?u='.$post_permalink,
			'twitter' => 'http://twitter.com/home?status='.urlencode($post->post_title).'+'.$post_permalink,
			'gplus' => 'https://plus.google.com/share?url='.$post_permalink,
		);

	?>
	@if((get_post_type($post) != 'post') && (get_post_type($post) != 'gallery'))
		<h3 class="flo-social-share-block__title">{{ __('share','flotheme') }} </h3>
	@elseif(get_post_type($post) == 'gallery')
		<?php $share_label = flo_get_option('flo-cube-portfolio-gallery__content-share-label', 'share');?>
		<h3 class="flo-social-share-block__title">{{$share_label}} </h3>
	@endif
	<div class="flo-share-wrap">
	    @foreach($flo_sharing_options as $key => $social_profile)
	    	<a href="{{$social_mapping[$social_profile['value']]}}" class="flo-share-wrap__link flo-icon-{{$social_profile['value']}} " target="_blank">
	    	</a>

	    @endforeach

	</div>
@endif
