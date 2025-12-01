<?php
/**
 * The Template for displaying Layout 1
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/advanced-counter/layout1.php
 *
 * HOWEVER, on occasion divi-plus will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 *
 * @author     Elicus Technologies <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */

// Wrap image with div.
if ( ! empty( $image_icon ) ) {
	$image_icon = sprintf(
		'<div class="dipl_advanced_counter_image%1$s">%2$s</div>',
		( 'on' === $use_icon ) ? ' dipl-used-icon' : '',
		et_core_esc_previously( $image_icon )
	);
}

$counter_output = sprintf(
	'<div class="dipl_advanced_counter_inner">%1$s %2$s %3$s %4$s</div>',
	et_core_esc_previously( $image_icon ),
	et_core_esc_previously( $number ),
	et_core_esc_previously( $title ),
	et_core_esc_previously( $content )
);
