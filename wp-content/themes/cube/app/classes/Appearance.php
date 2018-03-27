<?php
/**
 * Theme Appearance Class.
 *
 * Manages JS & CSS enqueuing of the theme.
 */

namespace Classy;

/**
 * Class Appearance.
 * 
 * @package Classy
 */
class Appearance {

	/**
	 * Appearance constructor.
	 */
	public function __construct() {

		add_action( 'wp_print_scripts', array( $this, 'init_js_vars' ) );

		add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );

	}


	/**
	 * Load needed options & translations into template.
	 */
	public function init_js_vars() {

		$options = array(
			'base_url'          => home_url( '' ),
			'blog_url'          => home_url( 'archives/' ),
			'template_dir'      => CLASSY_THEME_DIR,
			'ajax_load_url'     => admin_url( 'admin-ajax.php' ),
			'is_mobile'         => (int) wp_is_mobile(),
		);

		wp_localize_script(
			'theme_plugins',
			'theme',
			$options
		);

	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	public function setup_theme() {
		/**
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		$theme_menus = array(
				'primary' => __('Primary Menu', 'flotheme'),
				//'footer-menu' => __( 'Footer Menu', Classy::textdomain() ),
			);

		// filter to overwrite the registered menus if necessary
		$theme_menus = apply_filters('flo_maybe_register_menus', $theme_menus);

		register_nav_menus($theme_menus);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */

		add_theme_support('html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
         * Make theme available for translation.
         * Translations can be filed in the /theme-files/languages/ directory.
         */
		if(file_exists(WP_CONTENT_DIR . '/languages')){
			load_theme_textdomain('flotheme', WP_CONTENT_DIR . '/languages');
		}else{
			load_theme_textdomain('flotheme', get_template_directory() . '/theme-files/languages');
		}

        add_theme_support( 'automatic-feed-links' );

        add_theme_support( 'custom-background' ); 

	}
}
