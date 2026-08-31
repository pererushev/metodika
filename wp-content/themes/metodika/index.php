<?php
/**
 * Запасной шаблон. На главной — тот же hero.
 *
 * @package Metodika
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/hero' );
get_footer();
