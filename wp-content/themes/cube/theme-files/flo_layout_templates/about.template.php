<?php

global $templates;

$templates[] = [
  "name" => "about",
  "title" => "About",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-about.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-about.preview.jpg",
  "blocks" => [
    "top_block",
    "image_block",
    "info_block",
    "quote_block"
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]
];

?>
