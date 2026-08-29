<?php
/**
 * Поля hero: клиент меняет тексты и картинку в админке, без правки PHP.
 *
 * Значения читаются через carbon_get_theme_option() / metodika_get_hero().
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'metodika_register_carbon_fields' );

/**
 * Экран «Главная» в админке: только поля hero.
 */
function metodika_register_carbon_fields() {
	Container::make( 'theme_options', __( 'Главная', 'metodika' ) )
		->set_page_file( 'metodika-hero' )
		->set_icon( 'dashicons-cover-image' )
		->add_fields(
			array(
				Field::make( 'separator', 'hero_sep', __( 'Hero-блок', 'metodika' ) )
					->set_help_text( __( 'Логотип меняется отдельно: Внешний вид → Настроить → Свойства сайта → Логотип.', 'metodika' ) ),
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
