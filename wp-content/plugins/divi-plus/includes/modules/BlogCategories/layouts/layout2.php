<?php
/**
 * The Template for displaying Layout 2
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/blog-categories/layout2.php
 *
 * HOWEVER, on occasion divi-plus will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 *
 * @author     Elicus Technologies <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */

// Post count text.
$post_count = '';
if ( 'on' === $show_post_count ) {
	// Get post count number.
	$post_count_num = ( 'on' === $show_sup_number ) ? sprintf(
		'(%s)', absint( $category->count )
	) : absint( $category->count );

	$post_count = sprintf(
		'<span class="dipl_blog_category_count %1$s">%2$s %3$s</span>',
		( 'on' === $show_sup_number ) ? 'dipl_sup_number' : '',
		esc_html( $post_count_num ),
		( 'off' === $show_sup_number ) ? esc_html( $post_count_text ) : ''
	);

	// Wrap post
	if ( 'off' === $show_sup_number ) {
		$post_count = sprintf(
			'<div class="dipl_blog_category_count_wrap">%1$s</div>',
			et_core_esc_previously( $post_count )
		);
	}
}

// Category Name.
$category_name = sprintf(
	'<%2$s class="dipl_blog_category_name">%1$s %3$s</%2$s>',
	esc_html( $category->name ),
	esc_html( $title_level ),
	( 'on' === $show_sup_number ) ? et_core_esc_previously( $post_count ) : ''
);

// Final category item.
$category_items .= sprintf(
	'<div class="dipl_blog_category_item_inner">
		<div class="dipl_blog_category_content">
			<div class="dipl_category_image_wrapper">%1$s</div>
			%2$s %3$s
		</div>
	</div>
	<a href="%4$s" class="dipl_abs_link">%5$s</a>',
	! empty( $thumbnail_id ) ? wp_get_attachment_image( $thumbnail_id, 'full' ) : '',
	et_core_esc_previously( $category_name ),
	( 'off' === $show_sup_number ) ? et_core_esc_previously( $post_count ) : '',
	esc_url( $category_link ),
	esc_html( $category->name )
);
