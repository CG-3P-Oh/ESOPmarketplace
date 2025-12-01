<?php
/**
 * The Template for displaying Layout 1
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/woo-products-categories-carousel/layout1.php
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

// Category Thumbnail.
if ( 'on' === $show_thumbnail ) {
	$thumbnail = sprintf(
		'<div class="dipl_woo_product_category_thumbnail">
			<a href="%2$s" title="%3$s">%1$s</a>
		</div>',
		dipl_woocommerce_category_thumbnail( $product_cat, $thumbnail_size ),
		esc_url( $category_link ),
		esc_html( $product_cat->name )
	);
}

// Category Name.
$category_name = sprintf(
	'<%2$s class="dipl_woo_product_category_name">
		<a href="%3$s" title="%1$s">%1$s</a>
	</%2$s>',
	esc_html( $product_cat->name ),
	esc_html( $title_level ),
	esc_url( $category_link )
);

// Show product count.
if ( 'on' === $show_product_count ) {
	$product_count = sprintf(
		'<div class="dipl_woo_product_category_count">
			<a href="%2$s" title="%1$s">%1$s</a>
		</div>',
		sprintf(
			esc_html( _nx( '%s Product', '%s Products', absint( $product_cat->count ), 'number of products', 'divi-plus' ) ),
			absint( $product_cat->count )
		),
		esc_url( $category_link )
	);
}

// Final category item.
$category_items .= sprintf(
	'<div class="dipl_woo_product_category product_cat_%1$s %2$s">
		%3$s
		<div class="dipl_woo_product_category_content">
			%4$s %5$s
		</div>
		<a href="%6$s" class="dipl_abs_link">%7$s</a>
	</div>',
    esc_attr( $product_cat->slug ),
	( 'off' === $show_thumbnail || empty( $thumbnail ) ) ? 'dipl-no-thumb' : '',
	'on' === $show_thumbnail ? $thumbnail : '',
	et_core_intentionally_unescaped( $category_name, 'html' ),
	'on' === $show_product_count ? $product_count : '',
	esc_url( $category_link ),
	esc_html( $product_cat->name )
);
