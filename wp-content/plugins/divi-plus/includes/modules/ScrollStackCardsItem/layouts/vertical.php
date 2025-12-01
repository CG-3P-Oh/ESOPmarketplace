<?php
/**
 * The Template for displaying Layout 1
 *
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2021 Elicus Technologies Private Limited
 * @version     1.16.0
 */

if ( ! empty( $card_title ) ) {
	$card_title = sprintf(
		'<div class="dipl_scroll_stack_cards_title_wrap">%1$s</div>',
		et_core_esc_previously( $card_title )
	);
}

// Create content wrapper.
$content_wrapper = '';
if ( ! empty( $render_icon ) || ! empty( $card_title ) || ! empty( $content ) || ! empty( $render_button ) ) {
	$content_wrapper = sprintf(
		'<div class="dipl_scroll_stack_cards_content_wrapper">
			%1$s %2$s %3$s %4$s
		</div>',
		et_core_esc_previously( $render_icon ),
		et_core_esc_previously( $card_title ),
		et_core_esc_previously( $content ),
		et_core_esc_previously( $render_button )
	);
}

// Final output.
$render_output = sprintf(
	'<div class="dipl_scroll_stack_cards_item_inner">
		%1$s %2$s
	</div>',
	et_core_esc_previously( $content_wrapper ),
	et_core_esc_previously( $image )
);
