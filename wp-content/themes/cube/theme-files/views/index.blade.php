<?php
	global $wp_query, $flo_options;
//deb_e($flo_options);
	$the_query = $wp_query;

	if (isset($posts) && sizeof($posts)){
		// Do the  stuff below only if there are any posts

		if(is_search()){

			// we need to use hre get_field, DO NOT replace with $flo_options
			$page_options = get_field('flo-search','options');
			//deb_e($page_options); die();
		}else{
			$page_options = get_field('flo-archives','options');
			//$page_options = $flo_options['flo-archives'];
		}

		$listing_layout = $page_options["page-listing-listing__layout"];

		if( (isset($page_options['flo-page-listing-listing__show-category']) && $page_options['flo-page-listing-listing__show-category']) || !isset($page_options['flo-page-listing-listing__show-category']) ){
		  $show_grid_categ = true;
		}else{
		  $show_grid_categ = false;
		}

	    
		$listing_layout__views = array(
		  "classic"             => "components.flo-grid-2",
		  "grid"                => "components.flo-grid",
		  "grid_2"              => "components.flo-grid-2-items",
		  "grid_3"              => "components.flo-listing-grid-3",
		  "grid_4"              => "components.flo-listing-grid-4",
		  "cards"               => "components.flo-grid-3",
		  "cards_2"             => "components.flo-listing-cards-2",
		  "wide_cards"          => "components.flo-listing-wide-cards"
		);

		
		if(isset($listing_layout__views[$listing_layout])){
			$listing_layout__view = $listing_layout__views[$listing_layout];
		}else{
			$listing_layout__view = $listing_layout__views['classic'];
		}

		// clasic view data
		if(isset($page_options['page-listing-listing__show-content'])){
			$show_content = $page_options['page-listing-listing__show-content'];
			//deb_e($page_options['page-listing-listing__show-content']);
		}else{
			$show_content = false;
		}

		if(isset($page_options['page-listing-listing__show-excerpt'])){
			$show_excerpt = $page_options['page-listing-listing__show-excerpt'];
		}else{
			$show_excerpt = false;
		}

		$flo_listing_page_btn = __('READ MORE','flotheme');

		// grid view data
		if(isset($page_options['page-listing-listing__columns-count'])){
			$columns_count = $page_options['page-listing-listing__columns-count'];
		}else{
			$columns_count = 3;
		}

		if(isset($page_options['page-listing-listing__gutter'])){
			$gutter = $page_options['page-listing-listing__gutter'];
		}else{
			$gutter = '';
		}

		// Grid 2 data
		if($listing_layout == 'grid_2'){
			// check for the excerpt
			if(isset($page_options['flo-page-listing-listing__grid2-show-excerpt']) && $page_options['flo-page-listing-listing__grid2-show-excerpt'] ){
				$show_grid_excerpt = true;
			}
		}
	}


?>

@extends('layout.default')

@section('content')

	<div class="flo-page-head">
		@if(is_search())
      		<h1 class="flo-page-head__title page-title">{{ $page_title }}</h1>
      	@else
	      	<?php
				the_archive_title( '<h1 class="flo-page-head__title page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		@endif
  	</div>

	@if (isset($posts) && sizeof($posts))
		<?php if(isset($page_options)) { $data = $page_options; }  ?>
		@include($listing_layout__view)
	@else
		<div class="flo-section flo-section--padding-small flo-section--journal-grid-section">
		    <article class="flo-section__content center-text flo-post" style="text-align: center">

	          	<h1>{{ get_field("flo-not-found-page__title", "options") }}</h1>
	          	<br>
			    <h3>
			      	@if(isset($flo_options["flo-not-found-page__description"]) )
		        		{{ $flo_options["flo-not-found-page__description"] }}
		        	@else 
		        		We couldn't find the page you were looking for.
		        	@endif
			    </h3>
	          	<br>
	          	<a href="{{ get_home_url() }}"> {{ get_field("flo-not-found-page__link-title", "options") }} </a>
			</article>
		</div>
	@endif

	<?php 
		$pages_more = $the_query->max_num_pages > 1;
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	?>
  	@if($pages_more)
	    <?php

	    $pagination_number_links = [];
	    for ($i=1; $i <= $the_query->max_num_pages ; $i++) {
	      $pagination_number_links[] = get_pagenum_link($i);
	    }
	    $prev_page_link = $paged-1 >= 1 ? get_pagenum_link($paged-1) : "";
	    $next_page_link = $paged+1 <= $the_query->max_num_pages ? get_pagenum_link($paged+1) : "";

	    ?>
	    @include('components.flo-pagination', [
	      "flo_pagination__prev_link"          => $prev_page_link,
	      "flo_pagination__next_link"          => $next_page_link,
	      "flo_pagination__number_link_active" => $paged,
	      "flo_pagination__number_links"       => $pagination_number_links
	    ])
  	@endif

@stop
