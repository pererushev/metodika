<?php
/**
 * Меню шапки: пункты из «Внешний вид → Меню», не из HTML.
 *
 * Класс пункта в админке:
 * - is-button — кнопка «Бесплатная консультация» (верхний ряд справа)
 * У пункта с дочерними страницами появляется стрелка, как у «Услуги».
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'nav_menu_css_class', 'metodika_menu_item_class', 10, 3 );
add_filter( 'nav_menu_link_attributes', 'metodika_menu_link_atts', 10, 3 );
add_filter( 'nav_menu_submenu_css_class', 'metodika_menu_submenu_class', 10, 2 );
add_filter( 'wp_nav_menu_objects', 'metodika_skip_header_cta', 10, 2 );
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

		if ( in_array( 'menu-item-has-children', $classes, true ) ) {
			$classes[] = 'header-menu__item--parent';
		}
	}

	if ( 'header_contacts' === $args->theme_location ) {
		$classes[] = 'header-contacts__item';
	}

	return $classes;
}

/**
 * Класс и ARIA на ссылку пункта меню.
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
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' header-menu__link' );

		if ( in_array( 'menu-item-has-children', $item_classes, true ) ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}
	}

	if ( 'header_contacts' === $args->theme_location ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' header-contacts__link' );
	}

	return $atts;
}

/**
 * Класс выпадающего списка в шапке.
 *
 * @param string[] $classes Классы <ul>.
 * @param stdClass $args    Аргументы wp_nav_menu().
 * @return string[]
 */
function metodika_menu_submenu_class( $classes, $args ) {
	if ( ! empty( $args->theme_location ) && 'primary' === $args->theme_location ) {
		$classes[] = 'header-menu__sub';
	}

	return $classes;
}

/**
 * Кнопка «Бесплатная консультация» живёт в верхнем ряду, не в меню.
 *
 * @param WP_Post[] $items Пункты.
 * @param stdClass  $args  Аргументы wp_nav_menu().
 * @return WP_Post[]
 */
function metodika_skip_header_cta( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	return array_values(
		array_filter(
			$items,
			static function ( $item ) {
				$classes = is_array( $item->classes ) ? $item->classes : array();
				return ! in_array( 'is-button', $classes, true );
			}
		)
	);
}

/**
 * Пункт-кнопка из главного меню.
 *
 * @return WP_Post|null
 */
function metodika_get_header_cta() {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return null;
	}

	$items = wp_get_nav_menu_items( (int) $locations['primary'] );
	if ( empty( $items ) ) {
		return null;
	}

	foreach ( $items as $item ) {
		$classes = is_array( $item->classes ) ? $item->classes : array();
		if ( in_array( 'is-button', $classes, true ) ) {
			return $item;
		}
	}

	return null;
}

/**
 * WhatsApp и Telegram справа от логотипа.
 */
function metodika_the_header_socials() {
	$header = metodika_get_header();
	$links  = array();

	if ( $header['whatsapp'] ) {
		$links[] = array(
			'url'   => $header['whatsapp'],
			'label' => __( 'WhatsApp', 'metodika' ),
			'mod'   => 'whatsapp',
			'icon'  => 'whatsapp.svg',
		);
	}

	if ( $header['telegram'] ) {
		$links[] = array(
			'url'   => $header['telegram'],
			'label' => __( 'Telegram', 'metodika' ),
			'mod'   => 'telegram',
			'icon'  => 'tg.svg',
		);
	}

	if ( ! $links ) {
		return;
	}
	?>
	<ul class="header-socials">
		<?php foreach ( $links as $link ) : ?>
			<li>
				<a
					class="header-social header-social--<?php echo esc_attr( $link['mod'] ); ?>"
					href="<?php echo esc_url( $link['url'] ); ?>"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php echo esc_attr( $link['label'] ); ?>"
				>
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $link['icon'] ); ?>"
						alt=""
						width="34"
						height="34"
						aria-hidden="true"
					>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Красная кнопка в верхнем ряду шапки.
 */
function metodika_the_header_cta() {
	$cta = metodika_get_header_cta();
	if ( ! $cta ) {
		return;
	}
	?>
	<a class="header-cta" href="<?php echo esc_url( $cta->url ); ?>">
		<?php echo esc_html( $cta->title ); ?>
	</a>
	<?php
}

/**
 * Звёзды и текст рейтинга справа в нижнем ряду.
 */
function metodika_the_header_rating() {
	$header = metodika_get_header();
	if ( ! $header['rating'] ) {
		return;
	}

	$star = '<svg viewBox="0 0 20 20" width="16" height="16" aria-hidden="true"><path fill="currentColor" d="M10 1.5 12.4 7l6.1.5-4.7 3.9 1.4 5.9L10 14.6 4.8 17.3l1.4-5.9L1.5 7.5 7.6 7 10 1.5Z"/></svg>';
	?>
	<p class="header-rating">
		<span class="header-rating__stars" aria-hidden="true">
			<?php echo str_repeat( $star, 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG. ?>
		</span>
		<span class="header-rating__text"><?php echo esc_html( $header['rating'] ); ?></span>
	</p>
	<?php
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
					'title' => 'Работодателям',
					'url'   => home_url( '/#rabotodatelyam' ),
				),
				array(
					'title' => 'О нас',
					'url'   => home_url( '/#o-nas' ),
				),
				array(
					'title' => 'База знаний',
					'url'   => home_url( '/#baza' ),
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
					'title' => '+7 (495) 859-00-51',
					'url'   => 'tel:+74958590051',
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
