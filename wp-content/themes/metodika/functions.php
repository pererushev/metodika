<?php
/**
 * Тема Metodika.
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'metodika_setup' );
add_action( 'after_setup_theme', 'metodika_boot_carbon_fields' );
add_action( 'wp_enqueue_scripts', 'metodika_enqueue_assets' );

/**
 * Логотип, меню и служебная поддержка ядра.
 */
function metodika_setup() {
	load_theme_textdomain( 'metodika', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'navigation-lists', 'style', 'script' ) );

	add_theme_support(
		'custom-logo',
		array(
			'height'               => 62,
			'width'                => 290,
			'flex-height'          => true,
			'flex-width'           => true,
			'unlink-homepage-logo' => false,
		)
	);

	register_nav_menus(
		array(
			'primary'         => __( 'Шапка — меню', 'metodika' ),
			'header_contacts' => __( 'Шапка — контакты', 'metodika' ),
		)
	);
}

/**
 * Стили шапки и скрипт бургера. Hero подключится отдельным файлом позже.
 */
function metodika_enqueue_assets() {
	$theme_uri = get_template_directory_uri();
	$theme_dir = get_template_directory();
	$ver       = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'metodika-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Onest:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	$css = $theme_dir . '/assets/css/main.css';
	wp_enqueue_style(
		'metodika-main',
		$theme_uri . '/assets/css/main.css',
		array( 'metodika-fonts' ),
		file_exists( $css ) ? (string) filemtime( $css ) : $ver
	);

	$js = $theme_dir . '/assets/js/header.js';
	wp_enqueue_script(
		'metodika-header',
		$theme_uri . '/assets/js/header.js',
		array(),
		file_exists( $js ) ? (string) filemtime( $js ) : $ver,
		true
	);
}

/**
 * Carbon Fields лежит в теме (Composer), не отдельным плагином.
 */
function metodika_boot_carbon_fields() {
	$autoload = get_template_directory() . '/vendor/autoload.php';

	if ( ! is_readable( $autoload ) ) {
		return;
	}

	require_once $autoload;
	\Carbon_Fields\Carbon_Fields::boot();
}

require get_template_directory() . '/inc/carbon-fields.php';
require get_template_directory() . '/inc/menus.php';
