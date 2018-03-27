<?php
/**
 * Data that will be accessible on archive page (index).
 */
$f = get_theme_framework();
$data = array(
	'posts' => $f::get_posts(),
	'page_title' => $f::archives_title(),
	'pagination' => $f::get_pagination(),
);
