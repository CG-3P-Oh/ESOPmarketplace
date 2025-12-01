/**
 * Handles making sure that any Cloudflare Turnstile fields render if they are on this part.
 * 
 * @package Ninja Forms Multi-Part
 * @subpackage Front-End Controllers
 * @copyright (c) 2024 WP Ninjas
 * @since 3.0
 */
define( [], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'nfMP' ), 'change:part', this.changePart, this );
		},

		changePart: function( conditionModel, then ) {
			// Wait for part transition to complete before re-rendering
			setTimeout( function() {
				jQuery( '.cf-turnstile, .nf-cf-turnstile' ).each( function() {
					var element = this;
					var sitekey = jQuery( element ).data( 'sitekey' );
					var fieldID = jQuery( element ).data( 'fieldid' );
					
					// Skip if no sitekey or already rendered
					if ( ! sitekey || jQuery( element ).children().length > 0 ) {
						return;
					}

					// Ensure turnstile API is loaded
					if ( typeof turnstile === 'undefined' ) {
						return;
					}

					try {
						// Create callback function for this specific field
						var callbackName = 'nfTurnstileCallback_' + fieldID;
						
						// Define the callback function
						window[ callbackName ] = function( token ) {
							// Update the hidden input field
							var input = document.getElementById( 'nf-field-' + fieldID );
							if ( input ) {
								input.value = token;
								jQuery( input ).trigger( 'change' );
								
								// Remove error states
								jQuery( input ).closest( '.field-wrap' ).removeClass( 'nf-error' );
								jQuery( input ).closest( '.field-wrap' ).find( '.nf-error-msg' ).remove();
							}

							// Update via radio channel if available
							if ( typeof nfRadio !== 'undefined' && nfRadio.channel ) {
								try {
									nfRadio.channel( 'turnstile' ).request( 'update:response', token, fieldID );
								} catch( e ) {
									// Silent fail
								}
							}
						};

						// Render the turnstile widget
						turnstile.render( element, {
							sitekey: sitekey,
							theme: jQuery( element ).data( 'theme' ) || 'auto',
							size: jQuery( element ).data( 'size' ) || 'normal',
							callback: callbackName
						} );
						
					} catch( e ) {
						// Silent fail for turnstile render errors
						console.warn( 'Turnstile render error:', e );
					}
				} );
			}, 100 ); // Small delay to ensure DOM is ready
		}
	});

	return controller;
} );