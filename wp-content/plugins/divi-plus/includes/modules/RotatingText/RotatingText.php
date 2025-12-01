<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.13.0
 */
class DIPL_RotatingText extends ET_Builder_Module {
	public $slug       = 'dipl_rotating_text';
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
		$this->name = esc_html__( 'DP Rotating Text', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'main_box'   => esc_html__( 'Wrapper Box', 'divi-plus' ),
					'text'       => esc_html__( 'Text', 'divi-plus' ),
					'image_icon' => esc_html__( 'Image/Icon', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'text' => array(
					'label' => esc_html__( '', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'hide_letter_spacing' => true,
					'hide_line_height' => true,
					'css' => array(
						'main'     => "%%order_class%% .dipl-rotating-text-inner p, %%order_class%% .dipl-rotating-text-inner span",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'text',
				),
			),
			'borders' => array(
				'main_circle' => array(
					'label_prefix' => esc_html__( 'Circle', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl-rotating-text-wrap',
							'border_styles' => '%%order_class%% .dipl-rotating-text-wrap',
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'main_box',
				),
				'image_icon' => array(
					'label_prefix' => esc_html__( 'Image/Icon', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_rotating_text_icon_wrapper',
							'border_styles' => '%%order_class%% .dipl_rotating_text_icon_wrapper',
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'image_icon',
				),
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
				'main_circle' => array(
					'label'       => esc_html__( 'Circle Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl-rotating-text-wrap',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'main_box',
				),
				'image_icom' => array(
					'label'       => esc_html__( 'Image/Icon Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl_rotating_text_icon_wrapper',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image_icon',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'text_margin_padding' => array(
				'image_icon' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_rotating_text_icon_wrapper",
							'important'  => 'all',
						),
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
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'text' => array(
					'label'            => esc_html__( 'Rotating Text', 'divi-plus' ),
					'type'             => 'text',
					'dynamic_content'  => 'text',
					'default'          => esc_html__( 'Your content goes here', 'divi-plus' ),
					'default_on_front' => esc_html__( 'Your content goes here', 'divi-plus' ),
					'description'      => esc_html__( 'Enter the text to display as a rotating text.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				'use_image' => array(
					'label'            => esc_html__( 'Use Image', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => et_builder_i18n( 'No' ),
						'on'  => et_builder_i18n( 'Yes' ),
					),
					'default_on_front' => 'off',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Display the image instead of icon at the center.', 'divi-plus' ),
				),
				'icon' => array(
					'label'           => esc_html__( 'Select Icon', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'use_image' => 'off',
					),
					'default'         => '&#xf015;||fa||900',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can select the icon to be used in center of the text.', 'divi-plus' ),
				),
				'image' => array(
					'label'              => esc_html__( 'Select Image', 'divi-plus' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
					'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
					'show_if'            => array(
						'use_image' => 'on',
					),
					'toggle_slug'        => 'main_content',
					'description'        => esc_html__( 'Here you can select the image to be used in center of the text.', 'divi-plus' ),
				),
				'image_alt' => array(
					'label'           => esc_html__( 'Image Alt Text', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'dynamic_content' => 'text',
					'show_if'         => array(
						'use_image' => 'on',
					),
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can add an image alt text.', 'divi-plus' ),
				),
				'text_background_bg_color' => array(
					'label'             => esc_html__( 'Background Color', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'text_background_bg',
					'context'           => 'text_background_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => array_merge(
						$this->generate_background_options( 'text_background_bg', 'color', 'advanced', 'main_box', 'text_background_bg_color' ),
						$this->generate_background_options( 'text_background_bg', 'gradient', 'advanced', 'main_box', 'text_background_bg_color' ),
					),
					'hover'             => true,
					'mobile_options'    => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'main_box',
					'description'       => esc_html__( 'Here you can set the background color, and gradient color for the whole text box.', 'divi-plus' ),
				),
				'circle_size' => array(
					'label'           => esc_html__( 'Circle Size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '1',
						'max'  => '350',
						'step' => '1',
					),
					'default'         => '150px',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'main_box',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the image.', 'divi-plus' ),
				),
				'circle_animation_speed' => array(
					'label'           => esc_html__( 'Circle Animation Speed', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '1000',
						'max'  => '25000',
						'step' => '100',
					),
					'default'         => '8000ms',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'main_box',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the image.', 'divi-plus' ),
				),
				'image_icon_size' => array(
					'label'           => esc_html__( 'Image/Icon Wrapper size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '1',
						'max'  => '300',
						'step' => '1',
					),
					'default'         => '55px',
					'mobile_options'  => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_icon',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the image.', 'divi-plus' ),
				),
				'image_icon_background' => array(
					'label'        => esc_html__( 'Image/Icon Background', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'      => '#ffffff',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'image_icon',
					'description'  => esc_html__( 'Here you can choose a custom background color to be used for icon.', 'divi-plus' ),
				),
				'image_icon_custom_padding' => array(
					'label'            => esc_html__( 'Image/Icon Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '10px|10px|10px|10px|on|on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'image_icon',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'icon_size' => array(
					'label'           => esc_html__( 'Icon size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '1',
						'max'  => '200',
						'step' => '1',
					),
					'show_if'         => array(
						'use_image' => 'off',
					),
					'default'         => '28px',
					'mobile_options'  => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_icon',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease size of the icon.', 'divi-plus' ),
				),
				'icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'      => '',
					'hover'        => 'tabs',
					'show_if'      => array(
						'use_image' => 'off',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'image_icon',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for icon.', 'divi-plus' ),
				),
			),
			$this->generate_background_options( 'text_background_bg', 'skip', 'advanced', 'main_box', 'text_background_bg_color' ),
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

		$multi_view = et_pb_multi_view_options( $this );

		// Load the css file.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-rotating-text-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/RotatingText/' . $file . '.min.css', array(), '1.0.1' );

		wp_enqueue_script( 'dipl-rotating-text-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/RotatingText/dipl-rotating-text-custom.min.js", array('jquery'), '1.0.1', true );

		// Get the attrs.
		$use_image = esc_attr( $this->props['use_image'] ) ?? 'off';
		$text      = esc_html( $this->props['text'] ) ?? esc_html__( 'Your content goes here.', 'divi-plus' );

		// icon/image.
		$icon_image = '';
		if ( 'on' === $use_image ) {
			$icon_image = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{image}}',
					'alt'   => '{{image_alt}}',
					'class' => 'dipl-rotating-text-img',
				),
				'required' => 'image',
			) );
		} else {
			// Render icon.
			$icon_image = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array( 'class' => 'et-pb-icon' ),
				'required' => 'icon',
			) );
		}

		// If not icon/image empty.
		if ( ! empty( $icon_image ) ) {
			$icon_image = sprintf(
				'<div class="dipl_rotating_text_icon_wrapper">%1$s</div>',
				et_core_esc_previously( $icon_image )
			);
		}

		// Final output.
		$output = sprintf(
			'<div class="dipl-rotating-text-wrap">
				<p class="dipl-rotating-text-inner">%1$s</p>
				%2$s
			</div>',
			esc_html( $text ),
			et_core_esc_previously( $icon_image ),
		);

		// Circle size.
		$this->generate_styles( array(
			'base_attr_name' => 'circle_size',
			'selector'       => '%%order_class%% .dipl-rotating-text-wrap',
			'important'      => true,
			'css_property'   => 'width',
			'render_slug'    => $render_slug,
			'type'           => 'range',
		) );
		$this->generate_styles( array(
			'base_attr_name' => 'circle_size',
			'selector'       => '%%order_class%% .dipl-rotating-text-wrap',
			'important'      => true,
			'css_property'   => 'height',
			'render_slug'    => $render_slug,
			'type'           => 'range',
		) );

		// Circle speed.
		if ( ! empty( $this->props['circle_animation_speed'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-rotating-text-inner',
				'declaration' => sprintf( 'animation: dipl-text-rotation %1$s linear infinite !important;', esc_attr( $this->props['circle_animation_speed'] ) ),
			) );
		}

		// Image icon background.
		if ( ! empty( $this->props['image_icon_background'] ) ) {
			$this->generate_styles( array(
				'base_attr_name' => 'image_icon_background',
				'selector'       => '%%order_class%% .dipl_rotating_text_icon_wrapper',
				'hover_selector' => '%%order_class%% .dipl-rotating-text-wrap:hover .dipl_rotating_text_icon_wrapper',
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		// Image size.
		$this->generate_styles( array(
			'base_attr_name' => 'image_icon_size',
			'selector'       => '%%order_class%% .dipl_rotating_text_icon_wrapper',
			'important'      => true,
			'css_property'   => 'width',
			'render_slug'    => $render_slug,
			'type'           => 'range',
		) );
		$this->generate_styles( array(
			'base_attr_name' => 'image_icon_size',
			'selector'       => '%%order_class%% .dipl_rotating_text_icon_wrapper',
			'important'      => true,
			'css_property'   => 'height',
			'render_slug'    => $render_slug,
			'type'           => 'range',
		) );

		if ( 'off' === $use_image ) {
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => '%%order_class%% .dipl_rotating_text_icon_wrapper .et-pb-icon',
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				) );
			}
			// Icon size.
			$this->generate_styles( array(
				'base_attr_name' => 'icon_size',
				'selector'       => '%%order_class%% .dipl_rotating_text_icon_wrapper .et-pb-icon',
				'important'      => true,
				'css_property'   => 'font-size',
				'render_slug'    => $render_slug,
				'type'           => 'range',
			) );
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => '%%order_class%% .dipl_rotating_text_icon_wrapper .et-pb-icon',
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		$args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'text_background_bg' => array(
					'normal' => "{$this->main_css_element} .dipl-rotating-text-wrap",
					'hover' => "{$this->main_css_element} .dipl-rotating-text-wrap:hover",
	 			)
			),
		);
		DiviPlusHelper::process_background( $args );

		$fields = array( 'text_margin_padding' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $output;
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed                                     $raw_value Props raw value.
	 * @param array                                     $args {
	 *                                         Context data.
	 *
	 *     @type string $context      Context param: content, attrs, visibility, classes.
	 *     @type string $name         Module options props name.
	 *     @type string $mode         Current data mode: desktop, hover, tablet, phone.
	 *     @type string $attr_key     Attribute key for attrs context data. Example: src, class, etc.
	 *     @type string $attr_sub_key Attribute sub key that availabe when passing attrs value as array such as styes. Example: padding-top, margin-botton, etc.
	 * }
	 * @param ET_Builder_Module_Helper_MultiViewOptions $multi_view Multiview object instance.
	 *
	 * @return mixed
	 */
	public function multi_view_filter_value( $raw_value, $args, $multi_view ) {
		$name = isset( $args['name'] ) ? $args['name'] : '';
		$mode = isset( $args['mode'] ) ? $args['mode'] : '';

		if ( $raw_value && 'icon' === $name ) {
			$processed_value = html_entity_decode( et_pb_process_font_icon( $raw_value ) );
			if ( '%%1%%' === $raw_value ) {
				$processed_value = '"';
			}
			return $processed_value;
		}

		return $raw_value;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_rotating_text', $modules ) ) {
		new DIPL_RotatingText();
	}
} else {
	new DIPL_RotatingText();
}
