<?php
/**
 * The Template for displaying Layout 3
 *
 * This template can be overridden by copying it to yourtheme/divi-plus/layouts/coupon/layout3.php
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

$offer_output = '';
if ( 'on' === $show_offer && ! empty( $offer_discount ) ) {
	$offer_output = sprintf(
		'<div class="dipl_coupon_offer_wrapper dipl_offer_box">
			%1$s %2$s
		</div>',
		et_core_esc_previously( $offer_discount ),
		et_core_esc_previously( $offer_discount_label )
	);
}

// Final coupon output.
$render_coupon .= sprintf(
	'<div class="dipl_coupon_inner">
		%1$s
		<div class="dipl_coupon_content_wrapper">
			%2$s %3$s
            <div class="dipl_coupon_code_wrapper">
                %4$s %5$s
            </div>
		</div>
	</div>',
	et_core_esc_previously( $offer_output ),
	et_core_esc_previously( $title ),
	et_core_esc_previously( $description ),
	et_core_esc_previously( $coupon_code ),
	et_core_esc_previously( $expiry_message )
);
