<?php
$b = "flo-listing"; // To be used inside HTML

global $the_query;
global $paged;

// Start: Class name automation
  $b__for_css = ".".$b; // To be used inside CSS
  $b__uniq = $b."--".mt_rand(1, 999); // To be used inside HTML
  $b__uniq_for_css = ".".$b__uniq; // To be used inside CSS
// End: Class name automation

$classic_title_font = flo_data($data, "flo-page-listing-listing__classic-title-font");
$classic_category_font = flo_data($data, "page-listing-listing__classic-category-font");
$classic_excerpt_font = flo_data($data, "page-listing-listing__classic-excerpt-font");

$grid_title_font = flo_data($data, "flo-page-listing-listing__grid-title-font");
$grid_category_font = flo_data($data, "flo-page-listing-listing__grid-category-font");

$grid2_category_font = flo_data($data, "flo-page-listing-listing__grid2-category-font");
$grid2_title_font = flo_data($data, "flo-page-listing-listing__grid2-title-font");
$grid2_excerpt_font = flo_data($data, "flo-page-listing-listing__grid2-excerpt-font");

// $cards_title_font = flo_data($data, "flo-page-listing-listing__cards-title-font");
// $cards_category_font = flo_data($data, "flo-page-listing-listing__cards-category-font");

// the following 2 options are switched (mixed)
$cards_category_font = flo_data($data, "flo-page-listing-listing__cards-title-font");
$cards_title_font = flo_data($data, "flo-page-listing-listing__cards-category-font");

$cards_excerpt_font = flo_data($data, "flo-page-listing-listing__cards-excerpt-font");

$post_type = flo_data($data,"page-listing__post-type", 'post');
if(!isset($post_type) || ! is_string($post_type) ){
  $post_type = 'post';
}

$post_type__category_taxonomy = $post_type == "gallery" ? "gallery-category" : "category";

if($post_type == "gallery"){
  $post_type__categories = flo_data($data,"page-listing-listing__gallery-categories");
}else{
  $post_type__categories = flo_data($data,"page-listing-listing__post-categories");
}


if( !(is_array($post_type__categories) && sizeof($post_type__categories)) ){

  $post_type__categories = array();
}
$show_grid_categ = flo_data($data,'flo-page-listing-listing__show-category');


    $listing_layout__field = flo_data($data,"page-listing-listing__layout", 'grid');

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

if(isset($listing_layout__views[$listing_layout__field])){
  $listing_layout__view = $listing_layout__views[$listing_layout__field];
}else{
  $listing_layout__view = $listing_layout__views['classic'];
}

$posts_per_page = flo_data($data, "page-listing-listing__posts-per-page", 10);

$columns_count = flo_data($data, "page-listing-listing__columns-count", 3);
$gutter = flo_data($data,"page-listing-listing__gutter", 0);

$show_content = flo_data($data,'page-listing-listing__show-content', false);
$show_excerpt = flo_data($data,'page-listing-listing__show-excerpt', true);

$show_title_block = get_field("page-listing-title__show-title-block");

//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if ( get_query_var('paged') ) {
  $paged = get_query_var('paged');
} else if ( get_query_var('page') ) {
  $paged = get_query_var('page');
} else {
  $paged = 1;
}

$the_query__args = array(
  'post_type'                   => $post_type,
  'ignore_sticky_posts'         => true,
  'paged'                       => $paged
);

if(isset($_GET['categ']) && strlen($_GET['categ']) ){
  // when the category filter is passed via URL
  $the_query__args['tax_query'] = array(
    array(
        'taxonomy' => $post_type__category_taxonomy,
        'field'    => 'slug',
        'terms'    => $_GET['categ'],
      )
  );

}else if( sizeof($post_type__categories) ){
  $the_query__args['tax_query'] = array(
    array(
        'taxonomy' => $post_type__category_taxonomy,
        'field'    => 'term_id',
        'terms'    => $post_type__categories,
        'operator' => 'IN'
      )
  );
}

if(is_numeric($posts_per_page)){
  $the_query__args['posts_per_page'] = $posts_per_page;
}

//deb_e($the_query__args); die(0);
$the_query = new WP_Query( $the_query__args );
?>

@include('core.style', [
  "breakpoint__general" => "

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-4__title",
    $classic_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-4__category",
    $classic_category_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-4__excerpt",
    $classic_excerpt_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-3__title",
    $grid_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-3__category",
    $grid_category_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-featured-posts__category",
    $grid2_category_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-featured-posts__title",
    $grid2_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-featured-posts--layout-grid-excerpt",
    $grid2_excerpt_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-5__category",
    $cards_category_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-5__title",
    $cards_title_font
    )
    ."

    ".
    flo_render_typography_styles(
    $b__uniq_for_css." .flo-card-5__excerpt, ".$b__uniq_for_css." .flo-card-5__excerpt p",
    $cards_excerpt_font
    )
    ."


  "
])

<div class="{{$b}} {{$b__uniq}}">
  @include( $listing_layout__view , [
    "data" => $data,
    'page_options' => $data
  ])
</div>
