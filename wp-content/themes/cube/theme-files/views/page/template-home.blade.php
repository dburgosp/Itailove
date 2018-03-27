{{-- Template Name: 4.3. Home Page --}}

<?php

$data = get_fields();

if($field = get_field("page-home__slider-enabled")) {
  $header__slider_id = get_field("page-home__slider");
}
if($field = get_field("page-home__slider-layout")) {
  $header__slider_layout = $field;
}

if($field = get_field("custom-header__addons-enabled")) {
  $header__section_title_enabled = $field;
}

if($field = get_field("custom-header__addons-scroll")) {
  $header__section_scroll_enabled = $field;
}

if($field = get_field("custom-header__menu-position")) {
  $header__menu_position = $field;
}
?>

@extends('layout.default')

@section('content')

<!-- Start: Image Blocks -->
  @include('components.flo-featured-posts', [
    'data' => $data
  ])
<!-- End: Featured Items 1 -->

<!-- Start: Text Blocks -->
  @include("components.flo-information-block", [
    "data" => $data
  ])
<!-- End: Text Blocks -->

<!-- Start: Featured Items 2 -->
  @include('components.flo-featured-items', [
    "data" => $data
  ])
<!-- End: Featured Items 2 -->

@endsection
