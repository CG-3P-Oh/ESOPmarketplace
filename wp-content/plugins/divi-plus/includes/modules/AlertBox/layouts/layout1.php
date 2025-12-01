<?php
/**
 * The Template for displaying Layout 1
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/alert-box/layout1.php
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

// Image output.
if ( ! empty( $image_icon ) ) {
	$image_icon = sprintf(
		'<div class="dipl_alert_box_image_wrap%1$s">%2$s</div>',
		( 'on' === $use_image ) ? ' dipl-used-image' : '',
		et_core_esc_previously( $image_icon )
	);
}

// Alert output.
$alert_output = sprintf(
	'<div class="dipl_alert_box_inner">
		%1$s
		<div class="dipl_alert_box_content">
			%2$s %3$s
		</div>
		%4$s %5$s
	</div>',
	et_core_esc_previously( $image_icon ),
	et_core_esc_previously( $title ),
	et_core_esc_previously( $description ),
	et_core_esc_previously( $button ),
	et_core_esc_previously( $close_button )
);
