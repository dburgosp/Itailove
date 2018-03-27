<?php

global $templates;

$templates[] = [
  "name" => "listing",
  "title" => "Listing",
  "thumb_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-listing.thumb.jpg",
  "preview_url" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/cube/template-listing.preview.jpg",
  "blocks" => [
    "section-listing-title",
    "wordpress-content",
    "section-listing-featured-item",
    "section-listing",
    "section-listing-pagination"
  ],
  "hide_if" => [
    "is-gallery-options",
    "is-post-options"
  ],
  "show_if" => [

  ]

];

?>
