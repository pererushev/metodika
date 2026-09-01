<?php
/**
 * Шапка: логотип, контакты и меню из WP.
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

$header = metodika_get_header();

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'К содержанию', 'metodika' ); ?></a>

<header class="site-header">
	<div class="site-header__inner">
		<?php metodika_the_logo(); ?>

		<button
			class="header-burger"
			type="button"
			aria-expanded="false"
			aria-controls="header-panel"
		>
			<span class="header-burger__box" aria-hidden="true">
				<span class="header-burger__line"></span>
				<span class="header-burger__line"></span>
				<span class="header-burger__line"></span>
			</span>
			<span class="header-burger__text"><?php esc_html_e( 'Меню', 'metodika' ); ?></span>
		</button>

		<div class="header-panel" id="header-panel">
			<div class="header-toolbar">
				<?php metodika_the_header_socials(); ?>

				<div class="header-phone">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'header_contacts',
							'container'      => false,
							'menu_class'     => 'header-contacts',
							'fallback_cb'    => false,
							'depth'          => 1,
						)
					);
					?>
					<?php if ( $header['hours'] ) : ?>
						<p class="header-phone__hours"><?php echo esc_html( $header['hours'] ); ?></p>
					<?php endif; ?>
				</div>

				<?php metodika_the_header_cta(); ?>
			</div>

			<div class="header-bar">
				<nav class="header-nav" aria-label="<?php esc_attr_e( 'Основное меню', 'metodika' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'header-menu',
							'fallback_cb'    => false,
							'depth'          => 2,
						)
					);
					?>
				</nav>

				<?php metodika_the_header_rating(); ?>
			</div>
		</div>
	</div>
</header>

<main id="content" class="site-main">
