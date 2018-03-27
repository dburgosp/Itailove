<?php
/**
 * Data that will be accessible on pages with "Example" template.
 */
$theme_framework = get_theme_framework();
$data = array(
	'post' => $theme_framework::get_post(),
);
