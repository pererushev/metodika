<?php
/**
 * Меню шапки: пункты из «Внешний вид → Меню», не из HTML.
 *
 * Класс пункта в админке:
 * - is-button — кнопка «Бесплатная консультация» (справа)
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'nav_menu_css_class', 'metodika_menu_item_class', 10, 3 );
add_filter( 'nav_menu_link_attributes', 'metodika_menu_link_atts', 10, 3 );
add_action( 'after_setup_theme', 'metodika_seed_header_menus', 30 );

/**
 * BEM-класс на <li>, плюс модификаторы из админки.
 *
 * @param string[] $classes Классы пункта.
 * @param WP_Post  $item    Пункт меню.
 * @param stdClass $args    Аргументы wp_nav_menu().
 * @return string[]
 */
function metodika_menu_item_class( $classes, $item, $args ) {
	unset( $item );

	if ( empty( $args->theme_location ) ) {
		return $classes;
	}

	if ( 'primary' === $args->theme_location ) {
		$classes[] = 'header-menu__item';
	}

	if ( 'header_contacts' === $args->theme_location ) {
		$classes[] = 'header-contacts__item';
	}

	return $classes;
}

/**
 * Класс на ссылку: обычный пункт, акцент или кнопка.
 *
 * @param array    $atts Атрибуты <a>.
 * @param WP_Post  $item Пункт меню.
 * @param stdClass $args Аргументы wp_nav_menu().
 * @return array
 */
function metodika_menu_link_atts( $atts, $item, $args ) {
	if ( empty( $args->theme_location ) ) {
		return $atts;
	}

	$item_classes = is_array( $item->classes ) ? $item->classes : array();

	if ( 'primary' === $args->theme_location ) {
		$class = 'header-menu__link';

		if ( in_array( 'is-button', $item_classes, true ) ) {
			$class .= ' header-menu__link--button';
		}

		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' ' . $class );
	}

	if ( 'header_contacts' === $args->theme_location ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' header-contacts__link' );
	}

	return $atts;
}

/**
 * Логотип из кастомайзера, иначе картинка из макета.
 */
function metodika_the_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}

	$src = get_template_directory_uri() . '/assets/images/logo.png';
	?>
	<a class="custom-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<img
			class="custom-logo"
			src="<?php echo esc_url( $src ); ?>"
			alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
			width="287"
			height="61"
		>
	</a>
	<?php
}

/**
 * Один раз создаёт меню по макету, чтобы шапка не была пустой.
 * Дальше пункты правятся только в админке.
 */
function metodika_seed_header_menus() {
	$locations = get_theme_mod( 'nav_menu_locations' );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	$changed = false;

	if ( empty( $locations['primary'] ) ) {
		$primary_id = metodika_create_menu(
			'Главное меню',
			array(
				array(
					'title' => 'Услуги',
					'url'   => home_url( '/#uslugi' ),
				),
				array(
					'title' => 'База знаний',
					'url'   => home_url( '/#baza' ),
				),
				array(
					'title' => 'О нас',
					'url'   => home_url( '/#o-nas' ),
				),
				array(
					'title' => 'Отзывы',
					'url'   => home_url( '/#otzyvy' ),
				),
				array(
					'title' => 'Контакты',
					'url'   => home_url( '/#kontakty' ),
				),
				array(
					'title' => 'Работодателям',
					'url'   => home_url( '/#rabotodatelyam' ),
				),
				array(
					'title'   => 'Бесплатная консультация',
					'url'     => home_url( '/#konsultaciya' ),
					'classes' => 'is-button',
				),
			)
		);

		if ( $primary_id ) {
			$locations['primary'] = $primary_id;
			$changed              = true;
		}
	}

	if ( empty( $locations['header_contacts'] ) ) {
		$contacts_id = metodika_create_menu(
			'Контакты в шапке',
			array(
				array(
					'title' => '+7 (495) 895-00-51',
					'url'   => 'tel:+74958950051',
				),
			)
		);

		if ( $contacts_id ) {
			$locations['header_contacts'] = $contacts_id;
			$changed                      = true;
		}
	}

	if ( $changed ) {
		set_theme_mod( 'nav_menu_locations', $locations );
	}
}

/**
 * @param string $name  Имя меню в админке.
 * @param array  $items Пункты.
 * @return int ID меню или 0.
 */
function metodika_create_menu( $name, $items ) {
	$existing = wp_get_nav_menu_object( $name );
	if ( $existing ) {
		$already = wp_get_nav_menu_items( $existing->term_id );
		if ( ! empty( $already ) ) {
			return (int) $existing->term_id;
		}

		$menu_id = (int) $existing->term_id;
	} else {
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			return 0;
		}
	}

	foreach ( $items as $item ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'   => $item['title'],
				'menu-item-url'     => $item['url'],
				'menu-item-status'  => 'publish',
				'menu-item-type'    => 'custom',
				'menu-item-classes' => $item['classes'] ?? '',
			)
		);
	}

	return (int) $menu_id;
}
