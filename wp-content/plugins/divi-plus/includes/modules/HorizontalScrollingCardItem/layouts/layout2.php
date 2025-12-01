<?php
/**
 * The Template for displaying Layout 1
 *
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2021 Elicus Technologies Private Limited
 * @version     1.15.0
 */

$render_output .= '<div class="dipl_horizontal_scrolling_card_inner">';
	$render_output .= '<div class="dipl_horizontal_scrolling_card_content_wrapper">';

		// Add tag.
		if ( ! empty( $tag ) ) $render_output .= et_core_esc_previously( $tag );

		// Add title.
		if ( ! empty( $title ) ) $render_output .= et_core_esc_previously( $title );

		// Add description.
		if ( ! empty( $description ) ) $render_output .= et_core_esc_previously( $description );

		// Add button.
		if ( ! empty( $render_button ) ) $render_output .= et_core_esc_previously( $render_button );

	$render_output .= '</div>';
$render_output .= '</div>';
