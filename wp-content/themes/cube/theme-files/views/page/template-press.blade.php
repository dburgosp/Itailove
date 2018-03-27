{{-- Template Name: 4.11. Press Page --}}

@extends('layout.default')

<?php

$data = get_fields();

$page_custom_css = '';
$page_padding_top = get_field('page-press__top-padding');
$page_padding_bottom = get_field('page-press__bottom-padding');
if(is_numeric($page_padding_top)){
    $padding_top = ($page_padding_top / 16). "rem";
    $page_custom_css .= '.press-page{ padding-top: '.$padding_top.' }';
}
if(is_numeric($page_padding_bottom)){
    $padding_bottom = ($page_padding_bottom / 16). "rem";
    $page_custom_css .= '.press-page{ padding-bottom: '.$padding_bottom.' }';
}

?>
@if(strlen($page_custom_css) )
    @section('head__styles')
      <style class="page-press__styles" media="screen">
        {{ $page_custom_css }}
      </style>
    @endsection
@endif
@section('content')

    @include("components.press-page", [
      "data" => $data,
      "page_content" => $post->content()
    ])


@stop
