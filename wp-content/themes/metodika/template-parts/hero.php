<?php
/**
 * Первый экран: заголовок, две карточки пути, иллюстрация.
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

$hero    = metodika_get_hero();
$person  = $hero['person'];
$company = $hero['company'];

$image_html = '';
if ( $hero['image_id'] ) {
	$image_html = wp_get_attachment_image(
		$hero['image_id'],
		'large',
		false,
		array(
			'class' => 'hero__img',
		)
	);
}

if ( ! $image_html ) {
	$src        = get_template_directory_uri() . '/assets/images/hero.svg';
	$image_html = sprintf(
		'<img class="hero__img" src="%s" alt="" width="802" height="534">',
		esc_url( $src )
	);
}

$arrow = metodika_icon_arrow();

?>
<section class="hero" aria-labelledby="hero-title">
	<div class="hero__left">
		<h1 id="hero-title" class="hero__title">
			<?php
			$title_lines = preg_split( '/\R/u', (string) $hero['title'] );
			$title_lines = array_values( array_filter( array_map( 'trim', $title_lines ) ) );
			foreach ( $title_lines as $line ) :
				?>
				<span class="hero__title-line"><?php echo esc_html( $line ); ?></span>
			<?php endforeach; ?>
		</h1>

		<?php if ( $hero['text'] ) : ?>
			<p class="hero__text"><?php echo nl2br( esc_html( $hero['text'] ), false ); ?></p>
		<?php endif; ?>

		<?php if ( $hero['path_text'] ) : ?>
			<p class="hero__path"><?php echo esc_html( $hero['path_text'] ); ?></p>
		<?php endif; ?>
	</div>

	<article class="hero-card hero-card--person" id="put">
		<p class="hero-card__tag"><?php echo esc_html( $person['tag'] ); ?></p>
		<h2 class="hero-card__title"><?php echo esc_html( $person['title'] ); ?></h2>
		<p class="hero-card__text"><?php echo esc_html( $person['text'] ); ?></p>
		<div class="hero-card__actions">
			<?php if ( $person['btn1_text'] ) : ?>
				<a class="btn btn--brand" href="<?php echo esc_url( $person['btn1_url'] ); ?>">
					<?php echo esc_html( $person['btn1_text'] ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $person['btn2_text'] ) : ?>
				<a class="btn btn--ghost" href="<?php echo esc_url( $person['btn2_url'] ); ?>">
					<?php echo esc_html( $person['btn2_text'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</article>

	<article class="hero-card hero-card--company" id="rabotodatelyam">
		<p class="hero-card__tag"><?php echo esc_html( $company['tag'] ); ?></p>
		<h2 class="hero-card__title"><?php echo esc_html( $company['title'] ); ?></h2>
		<p class="hero-card__text"><?php echo esc_html( $company['text'] ); ?></p>
		<div class="hero-card__actions">
			<?php if ( $company['btn_text'] ) : ?>
				<a class="btn btn--brand" href="<?php echo esc_url( $company['btn_url'] ); ?>">
					<?php echo esc_html( $company['btn_text'] ); ?>
					<?php echo $arrow; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inline SVG. ?>
				</a>
			<?php endif; ?>
		</div>
	</article>

	<div class="hero__media">
		<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image / escaped src. ?>
	</div>

	<?php if ( $hero['offices'] ) : ?>
		<?php
		$office_groups = preg_split( '/\R\s*\R/u', (string) $hero['offices'] );
		$office_groups = array_values( array_filter( array_map( 'trim', $office_groups ) ) );
		?>
		<div class="hero__badge">
			<?php foreach ( $office_groups as $group ) : ?>
				<p class="hero__badge-group"><?php echo nl2br( esc_html( $group ), false ); ?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</section>
