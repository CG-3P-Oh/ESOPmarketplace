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

// Category thumbnail.
$thumbnail = '';
if ( ! empty( $thumbnail_id ) ) {
	$thumbnail = sprintf(
		'<div class="dipl_category_image_wrapper">%1$s</div>',
		wp_get_attachment_image( $thumbnail_id, 'full' )
	);
}

// Category Name.
$category_name = sprintf(
	'<div class="dipl_blog_category_content">
		<%1$s class="dipl_blog_category_name">%2$s</%1$s>
	</div>',
	esc_html( $title_level ),
	esc_html( $category->name )
);

// Post count text.
$post_count = '';
if ( 'on' === $show_post_count ) {
	$post_count = sprintf(
		'<span class="dipl_blog_category_count">%1$s</span>',
		absint( $category->count )
	);
}

// Final category item.
$category_items .= sprintf(
	'<div class="dipl_blog_category_item_inner">
		%1$s %2$s %3$s
	</div>
	<a href="%4$s" class="dipl_abs_link">%5$s</a>',
	et_core_esc_previously( $thumbnail ),
	et_core_esc_previously( $category_name ),
	! empty( $post_count ) ? et_core_esc_previously( $post_count ) : '',
	esc_url( $category_link ),
	esc_html( $category->name )
);
