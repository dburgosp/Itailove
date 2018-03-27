<?php
/**
 * Data that will be accessible on every page view. In case some other templates don't overwrite it with their own scope.
 */
$theme_framework = get_theme_framework();
$data = array(
	'post' => $theme_framework::get_post(),
);
