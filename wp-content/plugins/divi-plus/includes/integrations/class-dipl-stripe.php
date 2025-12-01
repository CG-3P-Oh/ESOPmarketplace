<?php
/**
 * Stripe Class
 *
 * Manage some stipe payment related functions..
 *
 * @author     Elicus <hello@elicus.com>
 * @link       https://elicus.com
 * @since      1.16.0
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

class DIPL_Stripe{

	/**
	 * The plugin settings.
	 *
	 * @since   1.0.0
	 */
	private $settings = [];

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @access  protected
	 */
	protected static $_instance = null;

	/**
	 * The instance of this class.
	 * Ensures only one instance of DIPL_Stripe is loaded or can be loaded.
	 *
	 * @since   1.0.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Stripe class constructor.
	 *
	 * @since   1.16.0
	 */
	public function __construct() {
		// Get public settings.
		$this->settings = get_option( ELICUS_DIVI_PLUS_OPTION );
	}

	/**
	 * Get secret key.
	 * 
	 * @since   1.16.0
	 */
	public function get_secret_key( $mode ) {
		$key        = 'dipl-stripe-' . esc_attr( $mode ) . '-secret-key';
		$secret_key = $this->settings[$key] ?? '';
		if ( ! empty( $secret_key ) ) {
			$secret_key = dipl_decrypt_string( $secret_key, $key );
		}

		return $secret_key;
	}

	/**
	 * Get currency.
	 * 
	 * @since   1.16.0
	 */
	public function get_currency( $default = 'usd' ) {
		$key = 'dipl-stripe-currency';
		return ( $this->settings[ $key ] ) ?? $default;
	}

	/**
	 * Generate checkout session URL.
	 * 
	 * @since   1.16.0
	 */
	public function generate_checkout_session_url( $args ) {

		// Get argument data.
		$payment_mode  = ! empty( $args['payment_mode'] ) ? $args['payment_mode'] : 'test';
		$success_url   = ! empty( $args['success_url'] ) ? $args['success_url'] : '';
		$cancel_url    = ! empty( $args['cancel_url'] ) ? $args['cancel_url'] : '';

		// Get serect key by mode.
		$stripe_secret = $this->get_secret_key( $payment_mode );
		$currency      = $this->get_currency();

		$product_name  = sanitize_text_field( $args['product_name'] );
		$product_price = intval( $args['product_price'] ); // in cents
		$quantity      = intval( $args['quantity'] );

		// Create post data.
		$post_data = [
			'payment_method_types[]' => 'card',
			'mode'                   => 'payment',
			'success_url'            => esc_url( $success_url ),
			'cancel_url'             => esc_url( $cancel_url ),
		];
		// Add product / line item.
		if ( ! empty( $product_name ) ) {
			$post_data[ 'line_items' ] = array( array(
				'price_data' => array(
					'currency'     => sanitize_text_field( $currency ),
					'unit_amount'  => ( $product_price * 100 ),
					'product_data' => array(
						'name' => $product_name
					)
				),
				'quantity'         => $quantity 
			) );
		}

		// API call to stripe.
		$response = wp_remote_post( 'https://api.stripe.com/v1/checkout/sessions', [
			'method'    => 'POST',
			'headers'   => [
				'Authorization' => 'Bearer ' . $stripe_secret,
				'Content-Type'  => 'application/x-www-form-urlencoded',
			],
			'body'      => $post_data,
		] );

		// If wp error return.
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Get response body.
		$body = json_decode( wp_remote_retrieve_body($response), true );

		// If error.
		if ( ! isset( $body['url'] ) || ! empty( $body['error'] ) ) {
			$error_msg = $body['error']['message'] ?? 'Unknown error';
			return new WP_Error( 'stripe_checkout_error', $error_msg );
		}

		return $body['url'];
	}

}
