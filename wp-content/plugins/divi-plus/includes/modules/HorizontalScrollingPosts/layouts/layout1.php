<?php
/**
 * The Template for displaying Layout 1
 *
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.16.0
 */

if ( ! empty( $post_cats ) ) {
	$post_cats = sprintf(
		'<div class="dipl_horizontal_scrolling_post_tag_wrapper">%1$s</div>',
		et_core_esc_previously( $post_cats )
	);
}

// Image wrapper.
$render_image = '';
if ( ! empty( $image ) ) {
	$post_item .= sprintf(
		'<div class="dipl_horizontal_scrolling_post_image_wrapper">
			%1$s %2$s
		</div>',
		et_core_esc_previously( $image ),
		et_core_esc_previously( $post_cats )
	);
}

// Content wrapper.
if ( ! empty( $title ) || ! empty( $description ) || ! empty( $render_button ) ) {
	$post_item .= sprintf(
		'<div class="dipl_horizontal_scrolling_post_content_wrapper">
			%4$s %1$s %2$s %3$s
		</div>',
		et_core_esc_previously( $title ),
		et_core_esc_previously( $description ),
		et_core_esc_previously( $render_button ),
		( empty( $image ) && ! empty( $post_cats ) ) ? et_core_esc_previously( $post_cats ) : ''
	);
}

// Post footer meta.
if ( ! empty( $post_footer_meta ) && ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_comments ) ) {
	$post_item .= sprintf(
		'<div class="dipl_horizontal_scrolling_post_meta_wrapper">%1$s</div>',
		implode( '<span class="dipl_post_meta_divider">|</span>', $post_footer_meta )
	);
}
