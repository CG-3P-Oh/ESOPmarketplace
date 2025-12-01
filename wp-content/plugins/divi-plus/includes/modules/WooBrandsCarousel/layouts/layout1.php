<?php
/**
 * The Template for displaying Layout 1
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/woo-brands-carousel/layout1.php
 *
 * HOWEVER, on occasion divi-plus will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 *
 * @author     Elicus Technologies <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.18.0
 */
if ( 'on' === $show_thumbnail ) {
	$thumbnail = sprintf(
		'<div class="dipl_woo_product_brand_thumbnail">
			<a href="%2$s" title="%3$s">%1$s</a>
		</div>',
		dipl_woocommerce_category_thumbnail( $product_brand, $thumbnail_size ),
		esc_url( $brand_link ),
		esc_html( $product_brand->name )
	);
}

$brand_name = sprintf(
	'<%2$s class="dipl_woo_product_brand_name">
		<a href="%3$s" title="%1$s">%1$s</a>
	</%2$s>',
	esc_html( $product_brand->name ),
	esc_html( $title_level ),
	esc_url( $brand_link )
);

if ( 'on' === $show_product_count ) {
	$product_count = sprintf(
		'<div class="dipl_woo_product_brand_count">
			<a href="%2$s" title="%1$s">%1$s</a>
		</div>',
		sprintf(
			esc_html( _nx( '%s Product', '%s Products', absint( $product_brand->count ), 'number of products', 'divi-plus' ) ),
			absint( $product_brand->count )
		),
		esc_url( $brand_link )
	);
}

$brand_items .= sprintf(
	'<div class="dipl_woo_product_brand">
		%1$s
		<div class="dipl_woo_product_brand_content">
			%2$s %3$s
		</div>
		<a href="%4$s" class="dipl_abs_link">%5$s</a>
	</div>',
	'on' === $show_thumbnail ? $thumbnail : '',
	et_core_intentionally_unescaped( $brand_name, 'html' ),
	'on' === $show_product_count ? $product_count : '',
	esc_url( $brand_link ),
	esc_html( $product_brand->name )
);
