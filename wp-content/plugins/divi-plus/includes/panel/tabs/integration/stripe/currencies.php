<?php
/**
 * Currencies
 *
 * Returns an array of currencies and codes.
 *
 * @author  Elicus <hello@elicus.com>
 * @link    https://elicus.com
 * @since   1.16.0
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

return apply_filters( 'divi_plus_stripe_currencies', array(
    'USD' => esc_html__( 'United States (US) Dollar', 'divi-plus' ),
    'EUR' => esc_html__( 'Euro', 'divi-plus' ),
    'GBP' => esc_html__( 'British Pound', 'divi-plus' ),
    'INR' => esc_html__( 'Indian Rupee', 'divi-plus' ),
    'AUD' => esc_html__( 'Australian Dollar', 'divi-plus' ),
    'CAD' => esc_html__( 'Canadian Dollar', 'divi-plus' ),
    'SGD' => esc_html__( 'Singapore Dollar', 'divi-plus' ),
    'HKD' => esc_html__( 'Hong Kong Dollar', 'divi-plus' ),
    'NZD' => esc_html__( 'New Zealand Dollar', 'divi-plus' ),
    'CHF' => esc_html__( 'Swiss Franc', 'divi-plus' ),
    'DKK' => esc_html__( 'Danish Krone', 'divi-plus' ),
    'NOK' => esc_html__( 'Norwegian Krone', 'divi-plus' ),
    'SEK' => esc_html__( 'Swedish Krona', 'divi-plus' ),
    'PLN' => esc_html__( 'Polish Zloty', 'divi-plus' ),
    'CZK' => esc_html__( 'Czech Koruna', 'divi-plus' ),
    'HUF' => esc_html__( 'Hungarian Forint', 'divi-plus' ),
    'ILS' => esc_html__( 'Israeli New Shekel', 'divi-plus' ),
    'MXN' => esc_html__( 'Mexican Peso', 'divi-plus' ),
    'BRL' => esc_html__( 'Brazilian Real', 'divi-plus' ),
    'MYR' => esc_html__( 'Malaysian Ringgit', 'divi-plus' ),
    'ZAR' => esc_html__( 'South African Rand', 'divi-plus' ),
    'CNY' => esc_html__( 'Chinese Yuan', 'divi-plus' ),
    'THB' => esc_html__( 'Thai Baht', 'divi-plus' ),
    'TWD' => esc_html__( 'New Taiwan Dollar', 'divi-plus' ),
    'RUB' => esc_html__( 'Russian Ruble', 'divi-plus' ),
    'PHP' => esc_html__( 'Philippine Peso', 'divi-plus' ),
    'TRY' => esc_html__( 'Turkish Lira', 'divi-plus' ),
    'ARS' => esc_html__( 'Argentine Peso', 'divi-plus' ),
    'RON' => esc_html__( 'Romanian Leu', 'divi-plus' ),
    'HRK' => esc_html__( 'Croatian Kuna', 'divi-plus' ),
    'BGN' => esc_html__( 'Bulgarian Lev', 'divi-plus' ),
    'ISK' => esc_html__( 'Icelandic Kr&oacute;na', 'divi-plus' ),
    'UAH' => esc_html__( 'Ukrainian Hryvnia', 'divi-plus' ),
    'QAR' => esc_html__( 'Qatari Riyal', 'divi-plus' ),
    'KWD' => esc_html__( 'Kuwaiti Dinar', 'divi-plus' ),
    'BHD' => esc_html__( 'Bahraini Dinar', 'divi-plus' ),
) );
