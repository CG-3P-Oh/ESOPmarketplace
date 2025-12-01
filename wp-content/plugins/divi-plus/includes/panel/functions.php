<?php
/**
 * @author      Elicus Technologies <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2020 Elicus Technologies Private Limited
 * @version     1.3.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Encrypt a string using AES-256-CBC with a random IV.
 *
 * @param string $plaintext The plain text to encrypt.
 * @param string $security_check the unique key to encrypt the data.
 * @return string Base64 encoded string with IV prefix.
 */
function dipl_encrypt_string( $plaintext, $security_check = 'dipl-secret-data' ) {
	$key = hash( 'sha256', $security_check, true ); // 32-byte key
	$iv  = openssl_random_pseudo_bytes( 16 );     // 16-byte IV

	$ciphertext = openssl_encrypt( $plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv );

	return base64_encode( $iv . $ciphertext ); // Prepend IV
}

/**
 * Decrypt a string encrypted by my_custom_encrypt().
 *
 * @param string $encoded The Base64 encoded encrypted string.
 * @param string $security_check the unique key to decrypt the data, will be same used for encription.
 * @return string|false The decrypted string or false on failure.
 */
function dipl_decrypt_string( $encoded, $security_check = 'dipl-secret-data' ) {
	$key        = hash( 'sha256', $security_check, true );
	$decoded    = base64_decode( $encoded );
	$iv         = substr( $decoded, 0, 16 ); // Extract IV
	$ciphertext = substr( $decoded, 16 );

	return openssl_decrypt( $ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv );
}

/**
 * Save theme settings to database
 * @version 1.0.2
 * @return string
 */
if ( ! function_exists('el_dipl_panel_save_settings') ) {
	function el_dipl_panel_save_settings(){
		check_ajax_referer( 'divi-plus-panel-nonce', 'nonce', true );
		// Sanitizing $_POST['options'] in below foreach loop as it contains json values.
		// phpcs:ignore ET.Sniffs.ValidatedSanitizedInput.InputNotSanitized
		$options = isset( $_POST['options'] ) ? wp_unslash( $_POST['options'] ) : '';
		if ( is_array( $options ) ) {
			foreach ( $options as $option ) {
				$type  = isset( $option['type'] ) ? sanitize_text_field( $option['type'] ) : '';
				$name  = isset( $option['name'] ) ? sanitize_text_field( $option['name'] ) : '';
				$value = isset( $option['value'] ) ? sanitize_text_field( $option['value'] ) : '';
				if ( 'elicus-option' === $type ) {
					$elicus_option = get_option( ELICUS_DIVI_PLUS_OPTION );
					if ( '' === $value ) {
						if ( isset( $elicus_option[ $name ] ) ) {
							if ( 'dipl-modules' !== $name ) {
								unset( $elicus_option[ $name ] );
							} else {
								$elicus_option[ $name ] = $value;    
							}
						} else {
							$elicus_option[ $name ] = $value;
						}
					} else {
						$elicus_option[ $name ] = $value;
					}
					update_option( ELICUS_DIVI_PLUS_OPTION, $elicus_option, true );
				} else if ( 'elicus-encrypted-option' === $type ) {
					$elicus_option = get_option( ELICUS_DIVI_PLUS_OPTION );
					if ( '' === $value ) {
						if ( isset( $elicus_option[ $name ] ) ) {
							if ( 'dipl-modules' !== $name ) {
								unset( $elicus_option[ $name ] );
							} else {
								$elicus_option[ $name ] = $value;    
							}
						} else {
							$elicus_option[ $name ] = $value;
						}
					} elseif ( ! empty( $value ) ) {
						if ( false === strpos( $value, '***' ) ) {
							$value = dipl_encrypt_string( $value, $name );
							$elicus_option[ $name ] = $value;
						}
					}
					update_option( ELICUS_DIVI_PLUS_OPTION, $elicus_option, true );
				}
			}
		}
		exit;
	}
	add_action( 'wp_ajax_dipl_panel_save_settings', 'el_dipl_panel_save_settings' );
}
