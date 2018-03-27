{{-- Template Name: 4.2. Contact Page --}}
@extends("layout.default")

@section("content")
  @include('components.flo-contact-block', [
    "data" => get_fields()
  ])
@stop
