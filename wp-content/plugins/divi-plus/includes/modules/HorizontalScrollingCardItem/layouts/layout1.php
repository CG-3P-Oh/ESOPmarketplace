<?php
/**
 * The Template for displaying Layout 1
 *
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2021 Elicus Technologies Private Limited
 * @version     1.15.0
 */

// Image wrapper.
$render_image = '';
if ( ! empty( $image ) ) {
	$render_output .= sprintf(
		'<div class="dipl_horizontal_scrolling_card_image_wrapper">
			%1$s %2$s
		</div>',
		et_core_esc_previously( $image ),
		et_core_esc_previously( $tag )
	);
}

// Content wrapper.
if ( ! empty( $title ) || ! empty( $description ) || ! empty( $render_button ) ) {
	$render_output .= sprintf(
		'<div class="dipl_horizontal_scrolling_card_content_wrapper">
			%4$s %1$s %2$s %3$s
		</div>',
		et_core_esc_previously( $title ),
		et_core_esc_previously( $description ),
		et_core_esc_previously( $render_button ),
		( empty( $image ) && ! empty( $tag ) ) ? et_core_esc_previously( $tag ) : ''
	);
}

