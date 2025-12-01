<?php
/**
 * The Template for displaying Layout 1
 *
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2021 Elicus Technologies Private Limited
 * @version     1.15.0
 */

$post_item .= '<div class="dipl_horizontal_scrolling_post_inner">';
	$post_item .= '<div class="dipl_horizontal_scrolling_post_content_wrapper">';

		// Add post category.
		if ( ! empty( $post_cats ) || ! empty( $post_date ) ) {
			$post_item .= sprintf(
				'<div class="dipl_horizontal_scrolling_post_tag_wrapper">%1$s %2$s</div>',
				! empty( $post_cats ) ? et_core_esc_previously( $post_cats ) : '',
				( 'on' === $show_date && ! empty( $post_date ) ) ? 
					'<span class="published">' . et_core_esc_previously( $post_date ) . '</span>' 
				: ''
			);
		}

		// Add title.
		if ( ! empty( $title ) ) $post_item .= et_core_esc_previously( $title );

		// Add description.
		if ( ! empty( $description ) ) $post_item .= et_core_esc_previously( $description );

		// Add button.
		if ( ! empty( $render_button ) ) $post_item .= et_core_esc_previously( $render_button );

		// Post footer meta.
		if ( ! empty( $post_footer_meta ) && ( 'on' === $show_author || 'on' === $show_comments ) ) {
			$post_item .= sprintf(
				'<div class="dipl_horizontal_scrolling_post_meta_wrapper">%1$s</div>',
				implode( '', $post_footer_meta )
			);
		}

	$post_item .= '</div>';
$post_item .= '</div>';
