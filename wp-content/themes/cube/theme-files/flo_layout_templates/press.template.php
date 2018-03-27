<?php

global $templates;

$templates[] = [
  "name" => "press",
  "title" => "Press",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-press.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-press.preview.jpg",
  "blocks" => [
    "press_block"
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]

];

?>
