<?php
/**
 * Поля шапки и hero: клиент меняет тексты в админке, без правки PHP.
 *
 * Значения читаются через metodika_get_header() / metodika_get_hero().
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'metodika_register_carbon_fields' );

/**
 * Экран «Главная» в админке: шапка и hero.
 */
function metodika_register_carbon_fields() {
	Container::make( 'theme_options', __( 'Главная', 'metodika' ) )
		->set_page_file( 'metodika-hero' )
		->set_icon( 'dashicons-cover-image' )
		->add_fields(
			array(
				Field::make( 'separator', 'header_sep', __( 'Шапка', 'metodika' ) )
					->set_help_text( __( 'Логотип: Внешний вид → Настроить → Свойства сайта. Меню и телефон: Внешний вид → Меню.', 'metodika' ) ),
				Field::make( 'text', 'header_whatsapp', __( 'WhatsApp', 'metodika' ) )
					->set_attribute( 'type', 'url' )
					->set_width( 50 ),
				Field::make( 'text', 'header_telegram', __( 'Telegram', 'metodika' ) )
					->set_attribute( 'type', 'url' )
					->set_width( 50 ),
				Field::make( 'text', 'header_hours', __( 'Часы работы', 'metodika' ) )
					->set_width( 50 ),
				Field::make( 'text', 'header_rating', __( 'Рейтинг', 'metodika' ) )
					->set_width( 50 ),
				Field::make( 'separator', 'hero_sep', __( 'Hero-блок', 'metodika' ) ),
				Field::make( 'text', 'hero_title', __( 'Заголовок', 'metodika' ) )
					->set_help_text( __( 'Главный заголовок баннера. Слишком длинный текст на сайте обрежется, сетка не разъедется.', 'metodika' ) ),
				Field::make( 'textarea', 'hero_text', __( 'Текст', 'metodika' ) )
					->set_rows( 3 ),
				Field::make( 'text', 'hero_button_text', __( 'Текст кнопки', 'metodika' ) )
					->set_width( 50 ),
				Field::make( 'text', 'hero_button_url', __( 'Ссылка кнопки', 'metodika' ) )
					->set_attribute( 'type', 'url' )
					->set_width( 50 ),
				Field::make( 'image', 'hero_image', __( 'Картинка', 'metodika' ) )
					->set_help_text( __( 'Иллюстрация hero из медиабиблиотеки.', 'metodika' ) ),
			)
		);
}

/**
 * Собранные поля hero для шаблона.
 *
 * @return array{title: string, text: string, button_text: string, button_url: string, image_id: int}
 */
function metodika_get_hero() {
	$empty = array(
		'title'       => '',
		'text'        => '',
		'button_text' => '',
		'button_url'  => '',
		'image_id'    => 0,
	);

	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $empty;
	}

	return array(
		'title'       => (string) carbon_get_theme_option( 'hero_title' ),
		'text'        => (string) carbon_get_theme_option( 'hero_text' ),
		'button_text' => (string) carbon_get_theme_option( 'hero_button_text' ),
		'button_url'  => (string) carbon_get_theme_option( 'hero_button_url' ),
		'image_id'    => absint( carbon_get_theme_option( 'hero_image' ) ),
	);
}

/**
 * Тексты шапки, которых нет в меню: мессенджеры, часы, рейтинг.
 *
 * @return array{whatsapp: string, telegram: string, hours: string, rating: string}
 */
function metodika_get_header() {
	$defaults = array(
		'whatsapp' => 'https://wa.me/74958950051',
		'telegram' => 'https://t.me/',
		'hours'    => 'Пн–Пт: 09:00–18:00',
		'rating'   => '4,8 на Яндекс.Картах и 2ГИС',
	);

	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $defaults;
	}

	$whatsapp = (string) carbon_get_theme_option( 'header_whatsapp' );
	$telegram = (string) carbon_get_theme_option( 'header_telegram' );
	$hours    = (string) carbon_get_theme_option( 'header_hours' );
	$rating   = (string) carbon_get_theme_option( 'header_rating' );

	return array(
		'whatsapp' => '' !== $whatsapp ? $whatsapp : $defaults['whatsapp'],
		'telegram' => '' !== $telegram ? $telegram : $defaults['telegram'],
		'hours'    => '' !== $hours ? $hours : $defaults['hours'],
		'rating'   => '' !== $rating ? $rating : $defaults['rating'],
	);
}
