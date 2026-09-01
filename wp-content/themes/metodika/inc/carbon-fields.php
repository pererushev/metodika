<?php
/**
 * Поля шапки и hero: клиент меняет тексты в админке, без правки PHP.
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'metodika_register_carbon_fields' );

/**
 * Экран «Главная»: шапка и первый экран.
 */
function metodika_register_carbon_fields() {
	Container::make( 'theme_options', __( 'Главная', 'metodika' ) )
		->set_page_file( 'metodika-hero' )
		->set_icon( 'dashicons-cover-image' )
		->add_tab(
			__( 'Шапка', 'metodika' ),
			array(
				Field::make( 'separator', 'header_sep', __( 'Шапка', 'metodika' ) )
					->set_help_text( __( 'Логотип: Внешний вид → Настроить. Меню и телефон: Внешний вид → Меню.', 'metodika' ) ),
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
			)
		)
		->add_tab(
			__( 'Hero', 'metodika' ),
			array(
				Field::make( 'textarea', 'hero_title', __( 'Заголовок', 'metodika' ) )
					->set_rows( 2 )
					->set_help_text( __( 'Перенос строки задаёт разбиение, как на макете.', 'metodika' ) ),
				Field::make( 'textarea', 'hero_text', __( 'Текст', 'metodika' ) )
					->set_rows( 3 )
					->set_help_text( __( 'Перенос строки задаёт разбиение, как на макете.', 'metodika' ) ),
				Field::make( 'text', 'hero_path_text', __( 'Подпись над карточками', 'metodika' ) ),
				Field::make( 'image', 'hero_image', __( 'Картинка справа', 'metodika' ) ),
				Field::make( 'textarea', 'hero_offices', __( 'Бейдж офисов', 'metodika' ) )
					->set_rows( 2 ),
			)
		)
		->add_tab(
			__( 'Иностранцам', 'metodika' ),
			array(
				Field::make( 'text', 'hero_person_tag', __( 'Подпись', 'metodika' ) ),
				Field::make( 'text', 'hero_person_title', __( 'Заголовок карточки', 'metodika' ) ),
				Field::make( 'textarea', 'hero_person_text', __( 'Текст', 'metodika' ) )
					->set_rows( 3 ),
				Field::make( 'text', 'hero_person_btn1_text', __( 'Кнопка 1', 'metodika' ) )
					->set_width( 50 ),
				Field::make( 'text', 'hero_person_btn1_url', __( 'Ссылка 1', 'metodika' ) )
					->set_attribute( 'type', 'url' )
					->set_width( 50 ),
				Field::make( 'text', 'hero_person_btn2_text', __( 'Кнопка 2', 'metodika' ) )
					->set_width( 50 ),
				Field::make( 'text', 'hero_person_btn2_url', __( 'Ссылка 2', 'metodika' ) )
					->set_attribute( 'type', 'url' )
					->set_width( 50 ),
			)
		)
		->add_tab(
			__( 'Работодателям', 'metodika' ),
			array(
				Field::make( 'text', 'hero_company_tag', __( 'Подпись', 'metodika' ) ),
				Field::make( 'text', 'hero_company_title', __( 'Заголовок карточки', 'metodika' ) ),
				Field::make( 'textarea', 'hero_company_text', __( 'Текст', 'metodika' ) )
					->set_rows( 3 ),
				Field::make( 'text', 'hero_company_btn_text', __( 'Кнопка', 'metodika' ) )
					->set_width( 50 ),
				Field::make( 'text', 'hero_company_btn_url', __( 'Ссылка', 'metodika' ) )
					->set_attribute( 'type', 'url' )
					->set_width( 50 ),
			)
		);
}

/**
 * Строка из Carbon Fields или запасной текст с макета.
 *
 * @param string $key     Имя поля.
 * @param string $default Запасное значение.
 * @return string
 */
function metodika_carbon_text( $key, $default = '' ) {
	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $default;
	}

	$value = (string) carbon_get_theme_option( $key );
	return '' !== $value ? $value : $default;
}

/**
 * Заголовок hero: две строки, как на макете.
 *
 * @return string
 */
function metodika_hero_title_text() {
	$fresh = "Миграционные услуги\nв Москве и Московской области";
	$old   = 'Миграционные услуги в Москве и Московской области';
	$value = metodika_carbon_text( 'hero_title', '' );

	if ( '' === $value || $value === $old ) {
		return $fresh;
	}

	return $value;
}

/**
 * Текст бейджа офисов: две группы строк, как на макете.
 *
 * @return string
 */
function metodika_hero_offices_text() {
	$fresh = "Офисы в Подольске\nи Одинцово\n\nРаботаем по Москве\nи Московской области";
	$old   = "Офисы в Подольске и Одинцово\nРаботаем по Москве и Московской области";
	$value = metodika_carbon_text( 'hero_offices', '' );

	if ( '' === $value || $value === $old ) {
		return $fresh;
	}

	return $value;
}

/**
 * Данные первого экрана.
 *
 * @return array<string, mixed>
 */
function metodika_get_hero() {
	$home = home_url( '/' );

	return array(
		'title'     => metodika_hero_title_text(),
		'text'      => metodika_carbon_text(
			'hero_text',
			"РВП, ВНЖ, гражданство РФ: собираем полный пакет документов и ведём дело\nдо результата. Компаниям — легальное оформление иностранных сотрудников."
		),
		'path_text' => metodika_carbon_text( 'hero_path_text', 'Выберите свой путь.' ),
		'image_id'  => function_exists( 'carbon_get_theme_option' ) ? absint( carbon_get_theme_option( 'hero_image' ) ) : 0,
		'offices'   => metodika_hero_offices_text(),
		'person'    => array(
			'tag'       => metodika_carbon_text( 'hero_person_tag', 'Иностранным гражданам' ),
			'title'     => metodika_carbon_text( 'hero_person_title', 'Оформляю статус себе или семье' ),
			'text'      => metodika_carbon_text(
				'hero_person_text',
				'РВП, ВНЖ, гражданство РФ. Проверим основание, соберём документы, подадим без ошибок. Не знаете, с чего начать — начните с консультации.'
			),
			'btn1_text' => metodika_carbon_text( 'hero_person_btn1_text', 'Бесплатная консультация' ),
			'btn1_url'  => metodika_carbon_text( 'hero_person_btn1_url', $home . '#konsultaciya' ),
			'btn2_text' => metodika_carbon_text( 'hero_person_btn2_text', 'Смотреть услуги и цены' ),
			'btn2_url'  => metodika_carbon_text( 'hero_person_btn2_url', $home . '#uslugi' ),
		),
		'company'   => array(
			'tag'      => metodika_carbon_text( 'hero_company_tag', 'Работодателям' ),
			'title'    => metodika_carbon_text( 'hero_company_title', 'Оформляю иностранных сотрудников' ),
			'text'     => metodika_carbon_text(
				'hero_company_text',
				'Разрешения на работу, ВКС, кадровые уведомления в МВД. Более 10 лет в миграционном праве, работаем с компаниями Москвы и области.'
			),
			'btn_text' => metodika_carbon_text( 'hero_company_btn_text', 'Решение для работодателей' ),
			'btn_url'  => metodika_carbon_text( 'hero_company_btn_url', $home . '#rabotodatelyam' ),
		),
	);
}

/**
 * Тексты шапки, которых нет в меню.
 *
 * @return array{whatsapp: string, telegram: string, hours: string, rating: string}
 */
function metodika_get_header() {
	return array(
		'whatsapp' => metodika_carbon_text( 'header_whatsapp', 'https://wa.me/74958590051' ),
		'telegram' => metodika_carbon_text( 'header_telegram', 'https://t.me/' ),
		'hours'    => metodika_carbon_text( 'header_hours', 'Пн–Пт: 09:00–18:00' ),
		'rating'   => metodika_carbon_text( 'header_rating', '4,8 на Яндекс.Картах и 2ГИС' ),
	);
}

/**
 * Стрелка ↗ у кнопок из макета.
 *
 * @return string
 */
function metodika_icon_arrow() {
	return '<svg class="icon-arrow" viewBox="0 0 12 12" width="12" height="12" aria-hidden="true"><path fill="currentColor" d="M2.5 2h7.5v7.5H8.6V4.16L3.08 9.67l-.75-.75 5.51-5.52H2.5V2Z"/></svg>';
}
