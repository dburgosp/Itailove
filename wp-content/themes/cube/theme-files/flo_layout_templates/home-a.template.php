<?php

global $templates;

$templates[] = [
  "name" => "home",
  "title" => "Home Type A",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-home.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-home.preview.jpg",
  "blocks" => [
    "image_blocks",
    "home_text_block",
    "featured_posts"
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]

];

?>
