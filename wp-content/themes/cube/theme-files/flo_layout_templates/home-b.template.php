<?php

global $templates;

$templates[] = [
  "name" => "home-b",
  "title" => "Home Type B",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-home-b.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-home-b.preview.jpg",
  "blocks" => [
    "slideshow-1",
    "title-1",
    "image-links"
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]

];

?>
