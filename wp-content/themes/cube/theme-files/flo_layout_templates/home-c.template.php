<?php

global $templates;

$templates[] = [
  "name" => "home-c",
  "title" => "Home Type C",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-home-c.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-home-c.preview.jpg",
  "blocks" => [
    "video",
    "information-block-2",
    "image-links-2",
    "image-link-with-parallax"
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]

];

?>
