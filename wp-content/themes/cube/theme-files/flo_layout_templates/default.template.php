<?php

global $templates;

$templates[] = [
  "name" => "default",
  "title" => "Default",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-default.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-default.preview.jpg",
  "blocks" => [
    "wordpress_title",
    "wordpress_content",
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]

];

?>
