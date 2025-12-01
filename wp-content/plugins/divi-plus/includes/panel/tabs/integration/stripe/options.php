<?php
add_settings_section(
	'el-settings-integration-stripe-section',
	'',
	'',
	self::$menu_slug
);

add_settings_field(
	'el-dipl-stripe-test-secret-key',
	esc_html__( 'Test Mode - Secret Key', 'divi-plus' ),
	array( $this, 'el_passwordfield_render' ),
	esc_html( self::$menu_slug ),
	'el-settings-integration-stripe-section',
	array(
		'field_id'       => 'dipl-stripe-test-secret-key',
		'setting'        => esc_html( self::$option ),
		'default'        => '',
		'id'             => 'el-dipl-stripe-test-secret-key',
		'data-type'      => 'elicus-encrypted-option',
		'info'		     => esc_html__( 'Here you can enter the Stripe secret key for the test mode.', 'divi-plus' ),
	)
);

add_settings_field(
	'el-dipl-stripe-live-secret-key',
	esc_html__( 'Live Mode - Secret Key', 'divi-plus' ),
	array( $this, 'el_passwordfield_render' ),
	esc_html( self::$menu_slug ),
	'el-settings-integration-stripe-section',
	array(
		'field_id'       => 'dipl-stripe-live-secret-key',
		'setting'        => esc_html( self::$option ),
		'default'        => '',
		'id'             => 'el-dipl-stripe-live-secret-key',
		'data-type'      => 'elicus-encrypted-option',
		'info'		     => esc_html__( 'Here you can enter the Stripe secret key for the live mode.', 'divi-plus' ),
	)
);

$currencies = require_once( ELICUS_DIVI_PLUS_DIR_PATH . 'includes/panel/tabs/integration/stripe/currencies.php' );

add_settings_field(
	'el-dipl-stripe-currency',
	esc_html__( 'Payment Currency', 'divi-plus' ),
	array( $this, 'el_dropdown_render' ),
	esc_html( self::$menu_slug ),
	'el-settings-integration-stripe-section',
	array(
		'field_id'     => 'dipl-stripe-currency',
		'setting'      => esc_html( self::$option ),
		'list_options' => $currencies,
		'default'      => 'usd',
		'use_select2'  => 'yes',
		'placeholder'  => esc_html__( 'Select the currency', 'divi-plus' ),
		'id'           => 'el-dipl-stripe-currency',
		'data-type'    => 'elicus-option',
		'info'		   => esc_html__( 'Select the currency to use to make the payment.', 'divi-plus' ),
	)
);
