{{-- Template Name: 4.1. About Page --}}
<?php
$data = get_fields();
?>
@extends('layout.default')

@section('content')
  {{-- Start: About Block--}}
    @include("components.flo-about-block", [
      "data" => $data
    ])
  {{-- End: About Block--}}

  {{-- Start: Image Block --}}
    @include('components.flo-about-image-block', [
      "data" => $data
    ])
  {{-- End: Image Block --}}

  {{-- Start: Information Block --}}
    @include("components.flo-text-image-block", [
      "data" => $data
    ])
  {{-- End: Information Block --}}

  {{-- Start: Quote Block --}}
    @include("components.flo-quote-block", [
      "data" => $data
    ])
  {{-- End: Quote Block --}}
@stop
