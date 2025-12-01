<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */
class DIPL_Coupon extends ET_Builder_Module {
	public $slug       = 'dipl_coupon';
	public $vb_support = 'on';

	/**
	 * Track if the module is currently rendering to prevent unnecessary rendering and recursion.
	 *
	 * @var bool
	 */
	protected static $rendering = false;

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name             = esc_html__( 'DP Coupon', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'display'      => esc_html__( 'Display', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'title'          => esc_html__( 'Title', 'divi-plus' ),
					'description'    => esc_html__( 'Description', 'divi-plus' ),
					'coupon_code'    => esc_html__( 'Coupon Code', 'divi-plus' ),
					'offer_discount' => array(
						'title'        => esc_html__( 'Offer Discount', 'divi-plus' ),
						'sub_toggles'  => array(
							'discount' => array( 'name' => esc_html__( 'Discount', 'divi-plus' ) ),
							'label'    => array( 'name' => esc_html__( 'Label', 'divi-plus' ) )
						),
						'tabbed_subtoggles' => true,
					),
					'expired_msg'    => esc_html__( 'Expiry Date Message', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'     => esc_html__( 'Title', 'divi-plus' ),
					'font_size' => array(
						'default' => '24px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'header_level' => array(
						'default' => 'h3',
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_coupon_title",
						'hover'     => "{$this->main_css_element} .dipl_coupon_title:hover",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title',
				),
				'description' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
					'font_size' => array(
						'default' => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_coupon_description, {$this->main_css_element} .dipl_coupon_description p",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
				),
				'coupon_code' => array(
					'label'     => esc_html__( 'Coupon Code', 'divi-plus' ),
					'font_size' => array(
						'default' => '24px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'text_color' => array(
						'default' => '#0095f6'
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_coupon_code",
						'hover'     => "{$this->main_css_element} .dipl_coupon_code:hover",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'coupon_code',
				),
				'offer_discount' => array(
					'label'     => esc_html__( 'Discont', 'divi-plus' ),
					'font_size' => array(
						'default' => '24px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_coupon_offer_discount",
						'hover'     => "{$this->main_css_element} .dipl_coupon_offer_discount:hover",
						'important' => 'all',
					),
					'depends_on'      => array( 'show_offer' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'offer_discount',
					'sub_toggle'      => 'discount',
				),
				'offer_discount_lable' => array(
					'label'     => esc_html__( 'Label', 'divi-plus' ),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_coupon_offer_label",
						'hover'     => "{$this->main_css_element} .dipl_coupon_offer_label:hover",
						'important' => 'all',
					),
					'depends_on'      => array( 'show_offer' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'offer_discount',
					'sub_toggle'      => 'label',
				),
				'expired_msg' => array(
					'label'      => esc_html__( 'Expired', 'divi-plus' ),
					'font_size'  => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_coupon_expiry_message",
						'hover'     => "{$this->main_css_element} .dipl_coupon_expiry_message:hover",
						'important' => 'all',
					),
					'depends_on'      => array( 'show_expiry_date' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'expired_msg',
				),
			),
			'borders' => array(
				'coupon_code' => array(
					'label_prefix' => esc_html__( 'Coupon Code', 'divi-plus' ),
					'defaults' => array(
						'border_radii'  => 'on|5px|5px|5px|5px',
						'border_styles' => array(
				            'width' => '2px',
				            'color' => '#dddddd',
							'style' => 'dashed',
				        ),
					),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_coupon_code",
							'border_styles' => "%%order_class%% .dipl_coupon_code",
						),
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'coupon_code',
				),
				'coupon_code' => array(
					'label_prefix' => esc_html__( 'Coupon Code', 'divi-plus' ),
					'defaults' => array(
						'border_radii'  => 'on|5px|5px|5px|5px',
					),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_coupon_wrapper .dipl_coupon_offer_wrapper",
							'border_styles' => "%%order_class%% .dipl_coupon_wrapper .dipl_coupon_offer_wrapper",
						),
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'offer_discount',
					'sub_toggle'      => 'discount',
				),
				'default' => array(
					'defaults' => array(
						'border_radii'  => 'on|5px|5px|5px|5px',
						'border_styles' => array(
				            'width' => '1px',
				            'color' => '#333333',
							'style' => 'solid',
				        ),
					),
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
				'custom_padding' => array(
					'default' => '15px|15px|15px|15px|on|on'
				),
				'css' => array(
					'main'      => '%%order_class%%',
					'important' => 'all',
				),
			),
			'text'         => false,
			'filters'      => false,
			'link_options' => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default_on_front' => esc_html__( 'Your title goes here', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can enter the title for coupon code.', 'divi-plus' ),
			),
			'coupon_code' => array(
				'label'            => esc_html__( 'Coupon Code', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default_on_front' => esc_html__( 'COUPON-50', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can enter the coupon code.', 'divi-plus' ),
			),
			'description' => array(
				'label'           => esc_html__( 'Description', 'divi-plus' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input the description to be used for coupon code.', 'divi-plus'),
			),
			'show_expiry_date' => array(
				'label'           => esc_html__( 'Show Expiry Date', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can choose whether or not display the expiry date for the coupon code.', 'divi-plus' ),
			),
			'expiry_date' => array(
				'label'           => esc_html__( 'Expiry Date', 'divi-plus' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'show_if'         => array( 'show_expiry_date' => 'on' ),
				'toggle_slug'     => 'main_content',
				'description'     => et_get_safe_localization( sprintf( __( 'This is the expiry date the coupon code. Your countdown timer is based on your timezone settings in your <a href="%1$s" target="_blank" title="WordPress General Settings">WordPress General Settings</a>', 'divi-plus' ), esc_url( admin_url( 'options-general.php' ) ) ) ),
			),
			'expiry_date_format' => array(
				'label'            => esc_html__( 'Expiry Date Format', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'          => 'M j, Y',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'If you would like to adjust the date format, input the appropriate PHP date format here.', 'divi-plus' ),
			),
			'layout' => array(
				'label'           => esc_html__( 'Layout', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
					'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
					'layout3' => esc_html__( 'Layout 3', 'divi-plus' ),
				),
				'default'          => 'layout1',
				'default_on_front' => 'layout1',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Select the layout for the coupon.', 'divi-plus' ),
			),
			'show_offer' => array(
				'label'           => esc_html__( 'Show Offer Discount', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'on',
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here you can choose whether or not display the expiry date for the coupon code.', 'divi-plus' ),
			),
			'offer_discount' => array(
				'label'            => esc_html__( 'Offer Discount', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'show_if'          => array( 'show_offer' => 'on' ),
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can enter the discount for the coupon code, for e.g. 50%.', 'divi-plus' ),
			),
			'offer_discount_label' => array(
				'label'            => esc_html__( 'Offer Discount Label', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'show_if'          => array( 'show_offer' => 'on' ),
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can enter the discount label for the coupon code, for e.g. Coupon.', 'divi-plus' ),
			),
			'discount_bg_color' => array(
				'label'          => esc_html__( 'Discount Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => false,
				'show_if'        => array(
					'show_offer' => 'on',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'offer_discount',
				'sub_toggle'     => 'discount',
				'description'    => esc_html__( 'Here you can define a custom background color for your offer.', 'divi-plus' ),
			),
			'discount_label_bg_color' => array(
				'label'          => esc_html__( 'Label Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => false,
				'show_if'        => array(
					'show_offer' => 'on',
					'layout'     => array( 'layout1', 'layout3' ),
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'offer_discount',
				'sub_toggle'     => 'label',
				'description'    => esc_html__( 'Here you can define a custom background color for your offer.', 'divi-plus' ),
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}
	
		$multi_view         = et_pb_multi_view_options( $this );

		$layout             = sanitize_text_field( $this->props['layout'] ) ?? 'layout1';
		$show_offer         = sanitize_text_field( $this->props['show_offer'] ) ?? 'on';
		$show_expiry_date   = sanitize_text_field( $this->props['show_expiry_date'] ) ?? 'on';
		$expiry_date        = sanitize_text_field( $this->props['expiry_date'] ) ?? '';
		$expiry_date_format = sanitize_text_field( $this->props['expiry_date_format'] ) ?? 'M j, Y';

		$title_level        = et_pb_process_header_level( $this->props['title_level'], 'h3' );
		$title_level        = esc_html( $title_level );

		// Load style and script files.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-coupon-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/Coupon/' . $file . '.min.css', array(), '1.0.0' );

		// Offer discount.
		$offer_discount       = '';
		$offer_discount_label = '';
		if ( 'on' === $show_offer ) {
			// Get offer discount.
			$offer_discount = $multi_view->render_element( array(
				'tag'      => 'span',
				'content'  => '{{offer_discount}}',
				'attrs'    => array( 'class' => 'dipl_coupon_offer_discount' ),
				'required' => 'offer_discount',
			) );
			$offer_discount_label = $multi_view->render_element( array(
				'tag'      => 'span',
				'content'  => '{{offer_discount_label}}',
				'attrs'    => array( 'class' => 'dipl_coupon_offer_label' ),
				'required' => 'offer_discount_label',
			) );
		}

		// Title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array( 'class' => 'dipl_coupon_title' ),
			'required' => 'title',
		) );
		// Description.
		$description = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{description}}',
			'attrs'    => array( 'class' => 'dipl_coupon_description' ),
			'required' => 'description',
		) );

		// Coupon code.
		$coupon_code = $multi_view->render_element( array(
			'tag'      => 'span',
			'content'  => '{{coupon_code}}',
			'attrs'    => array( 'class' => 'dipl_coupon_code_text' ),
			'required' => 'coupon_code',
		) );
		if ( ! empty( $coupon_code ) ) {
			$coupon_code = sprintf(
				'<div class="dipl_coupon_code">%1$s</div>',
				et_core_esc_previously( $coupon_code )
			);
		}

		// Expiration date/message.
		$expiry_message = '';
		if ( 'on' === $show_expiry_date && ! empty( $expiry_date ) ) {
			// Convert both dates to timestamps using WordPress time zone.
			$expiry_timestamp  = strtotime( $expiry_date );
			$current_timestamp = current_time( 'timestamp' );
			// Check if expired.
			if ( $current_timestamp > $expiry_timestamp ) {
				// Expired date message.
				$expiry_message = sprintf(
					'<div class="dipl_coupon_expiry_message date-expired">%1$s</div>',
					esc_html__( 'Expired', 'divi-plus' )
				);
			} else {
				// Active date message.
				$expiry_message = sprintf(
					'<div class="dipl_coupon_expiry_message date-active">%1$s %2$s</div>',
					esc_html__( 'Expires On', 'divi-plus' ),
					date_i18n( $expiry_date_format, strtotime( $expiry_date ) )
				);
			}
		}

		// Based on layout get the coupon html.
		$render_coupon = '';
		if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/coupon/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include get_stylesheet_directory() . '/divi-plus/layouts/coupon/' . sanitize_file_name( $layout ) . '.php';
		} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
		}

		// Final rendering.
		$render_output = sprintf(
			'<div class="dipl_coupon_wrapper %1$s">%2$s</div>',
			esc_attr( $layout ),
			et_core_esc_previously( $render_coupon )
		);

		// Offer discount.
		if ( 'on' === $show_offer ) {
			$this->generate_styles( array(
				'base_attr_name' => 'discount_bg_color',
				'selector'       => "{$this->main_css_element} .dipl_coupon_wrapper .dipl_coupon_offer_wrapper",
				'hover_selector' => "{$this->main_css_element} .dipl_coupon_wrapper .dipl_coupon_offer_wrapper:hover",
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			// On Layout, for offer box.
			if ( 'layout2' !== $layout ) {
				$this->generate_styles( array(
					'base_attr_name' => 'discount_label_bg_color',
					'selector'       => "{$this->main_css_element} .dipl_coupon_wrapper .dipl_coupon_offer_label",
					'hover_selector' => "{$this->main_css_element} .dipl_coupon_wrapper .dipl_coupon_offer_label:hover",
					'important'      => true,
					'css_property'   => 'background-color',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );
			}
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_coupon', $modules ) ) {
		new DIPL_Coupon();
	}
} else {
	new DIPL_Coupon();
}
