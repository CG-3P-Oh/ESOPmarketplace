<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2023 Elicus Technologies Private Limited
 * @version     1.16.0
 */
class DIPL_StripeButton extends ET_Builder_Module {
	public $slug       = 'dipl_stripe_button';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	/**
	 * Track if the module is currently rendering to prevent unnecessary rendering and recursion.
	 *
	 * @var bool
	 */
	protected static $rendering = false;

	public function init() {
		$this->name             = esc_html__( 'DP Stripe Button', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'configuration' => esc_html__( 'Configuration', 'divi-plus' ),
					'product'       => esc_html__( 'Product', 'divi-plus' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'error_message' => esc_html__( 'Error Message', 'divi-plus' ),
					'stripe_button' => esc_html__( 'Stripe Button', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'text' => array(
					'label' => esc_html__( 'Error Message', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '200',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color' => array(
						'default' => '#ff0000'
					),
					'css' => array(
						'main'     => "%%order_class%% .dipl_stripe_error",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'error_message',
				),
			),
			'button' => array(
				'stripe_button' => array(
					'label' => esc_html__( 'Stripe Button', 'divi-plus' ),
					'css' => array(
						'main'      => "%%order_class%% .dipl_stripe_button_wrapper .et_pb_button",
						'alignment' => "%%order_class%% .dipl_stripe_button_wrapper",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "%%order_class%% .dipl_stripe_button_wrapper",
							'padding'   => "%%order_class%% .dipl_stripe_button_wrapper .et_pb_button",
							'important' => 'all',
						),
					),
					'box_shadow'  => array(
						'css' => array(
							'main'      => "%%order_class%% .dipl_stripe_button_wrapper .et_pb_button",
							'important' => 'all',
						),
					),
					'use_icon'        => false,
					'use_alignment'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'stripe_button',
				),
			),
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '%%order_class%%',
							'border_radii'  => '%%order_class%%',
						),
					),
				)
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%%',
					'important' => 'all',
				),
			),
			'text'         => false,
			'text_shadow'  => false,
			'link_options' => false,
			'filters'      => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {

		// Settings URL.
		$settings_link = admin_url( 'options-general.php?page=divi-plus-options' );

		return array(
			'secret_key_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'configuration',
				'toggle_slug'     => 'configuration',
				'value'           => true,
				'display_if'      => true,
				'message'         => sprintf(
					esc_html__( 'Please make sure to configure the global Stripe settings. Click the link below and navigate to Integration > Stripe, then fill in all the required fields: %3$s%3$s %1$sClick here to configure Stripe settings%2$s', 'divi-plus' ),
					'<a href="' . esc_url( $settings_link ) . '" target="_blank">',
					'</a>',
					"<br />"
				),
			),
			'payment_mode' => array(
				'label'        	   => esc_html__( 'Payment Mode', 'divi-plus' ),
				'type'        	   => 'select',
				'option_category'  => 'configuration',
				'default'          => 'test',
				'default_on_front' => 'test',
				'options'          => array(
					'test' => esc_html__( 'Test', 'divi-plus' ),
					'live' => esc_html__( 'Live', 'divi-plus' ),
				),
				'toggle_slug'      => 'configuration',
				'description'  	   => esc_html__( 'Here you can choose whether payment is test or live mode.', 'divi-plus' ),
			),
			'success_url' => array(
				'label'           => esc_html__( 'Success URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => '',
				'dynamic_content' => 'url',
				'toggle_slug'     => 'configuration',
				'description'     => esc_html__( 'please enter success page url.', 'divi-plus' ),
			),
			'cancel_url' => array(
				'label'           => esc_html__( 'Cancel URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => '',
				'dynamic_content' => 'url',
				'toggle_slug'     => 'configuration',
				'description'     => esc_html__( 'please enter cancel page url.', 'divi-plus' ),
			),
			'button_text' => array(
				'label'           => esc_html__( 'Stripe Button Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => esc_html__( 'Pay Now', 'divi-plus' ),
				'toggle_slug'     => 'configuration',
				'description'     => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
			),
			// 'button_new_window' => array(
			// 	'label'        	  => esc_html__( 'Stripe Button Link Target', 'divi-plus' ),
			// 	'type'        	  => 'select',
			// 	'option_category' => 'configuration',
			// 	'options'         => array(
			// 		'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
			// 		'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
			// 	),
			// 	'toggle_slug'     => 'configuration',
			// 	'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
			// ),
			'product_name' => array(
				'label'           => esc_html__( 'Product Name', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => esc_html__( 'Product name here', 'divi-plus' ),
				'toggle_slug'     => 'product',
				'description'     => esc_html__( 'Enter the product name to use for stripe payment.', 'divi-plus' ),
			),
			'product_price' => array(
				'label'           => esc_html__( 'Product Price', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => '1',
				'toggle_slug'     => 'product',
				'description'     => esc_html__( 'Here you can input the product price for one quantity.', 'divi-plus' ),
			),
			'product_qty' => array(
				'label'           => esc_html__( 'Product Quantity', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '1',
				'unitless'	      => true,
				'toggle_slug'     => 'product',
				'description'     => esc_html__( 'Increase or decrease the space between the cards.', 'divi-plus' ),
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a DIPL Woo Product module while a DIPL Woo Product module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		wp_enqueue_script( 'dipl-stripe-button-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/StripeButton/dipl-stripe-button-custom.min.js", array('jquery'), '1.0.0', true );

		// Get props.
		$data_props = array(
			'payment_mode',
			'product_name',
			'product_price',
			'product_qty',
			'success_url',
			'cancel_url'
		);
		$data_atts = $this->props_to_html_data_attrs( $data_props );

		// Render button.
		$render_button = $this->render_button( array(
			'button_text'         => esc_attr( $this->props['button_text'] ),
			'button_text_escaped' => true,
			'has_wrapper'      	  => false,
			'button_url'          => '#',
			'url_new_window'      => 'off',
			'button_custom'       => isset( $this->props['custom_stripe_button'] ) ? esc_attr( $this->props['custom_stripe_button'] ) : 'off',
			'custom_icon'         => isset( $this->props['stripe_button_icon'] ) ? $this->props['stripe_button_icon'] : '',
			'button_rel'          => isset( $this->props['stripe_button_rel'] ) ? esc_attr( $this->props['stripe_button_rel'] ) : '',
		) );

		// Render output.
		$render_output = sprintf(
			'<div class="et_pb_button_wrapper dipl_stripe_button_wrapper"%1$s>%2$s</div>
			<div class="dipl_stripe_error"></div>',
			et_core_esc_previously( $data_atts ),
			et_core_esc_previously( $render_button )
		);

		// Button style.
		if ( 'on' === $this->props['custom_stripe_button'] && ! empty( $this->props['stripe_button_icon'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => "%%order_class%% .dipl_stripe_button_wrapper .et_pb_button::after, %%order_class%% .dipl_stripe_button_wrapper .et_pb_button::before",
				'declaration' => 'content: attr(data-icon) !important;',
			) );
		}

		self::$rendering = false;
		return $render_output;
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_stripe_button', $modules ) ) {
		new DIPL_StripeButton();
	}
} else {
	new DIPL_StripeButton();
}
