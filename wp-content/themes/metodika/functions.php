<?php
/**
 * Тема Metodika.
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'metodika_setup' );
add_action( 'after_setup_theme', 'metodika_boot_carbon_fields' );

/**
 * Логотип из кастомайзера и служебная поддержка ядра.
 */
function metodika_setup() {
	load_theme_textdomain( 'metodika', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );

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
