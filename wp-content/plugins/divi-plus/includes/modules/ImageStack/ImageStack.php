<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.13.0
 */
class DIPL_ImageStack extends ET_Builder_Module {
	public $slug       = 'dipl_image_stack';
	public $child_slug = 'dipl_image_stack_item';
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
		$this->name             = esc_html__( 'DP Image Stack', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Image', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'display' => esc_html__( 'Display', 'divi-plus' )
				)
			),
			'advanced' => array(
				'toggles' => array(
					'icon_settings'   => esc_html__( 'Icon', 'divi-plus' ),
					'tooltip_styling' => esc_html__( 'Tooltip Styling', 'divi-plus' ),
					'tooltip_text'    => esc_html__( 'Tooltip Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'     => esc_html__( 'Tooltip Title', 'divi-plus' ),
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
					'css' => array(
						'main'      => ".tippy-box[data-theme='%%order_class%%_0'] .tippy-content",
						'important' => 'all',
					),
					'depends_on'      => array( 'enable_tooltip' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_text',
				),
			),
			'image_stack_spacing' => array(
				'tooltip' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => ".tippy-box[data-theme='%%order_class%%_0'] .tippy-content",
							'important'  => 'all',
						),
					),
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%%',
					'important' => 'all',
				),
			),
			'max_width' => array(
				'use_module_alignment' => false,
				'css' => array(
					'main' => '%%order_class%%'
				),
			),
			'text'         => false,
			'filters'      => false,
			'link_options' => false,
			'background'   => array(
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'image_icon_align' => array(
					'label'            => esc_html__( 'Alignment', 'divi-plus' ),
					'type'             => 'align',
					'option_category'  => 'layout',
					'default'          => 'center',
					'default_on_front' => 'center',
					'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'mobile_options'   => true,
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Align the container to the left, right or center.', 'divi-plus' ),
				),
				'image_icon_size' => array(
					'label'            => esc_html__( 'Image/Icon Size', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '10',
						'max'  => '500',
						'step' => '1',
					),
					'fixed_unit'	   => 'px',
					'default'          => '90px',
					'default_on_front' => '90px',
					'mobile_options'   => true,
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Increase or decrease the image/icon size.', 'all-in-one-carousel-for-divi' ),
				),
				'image_icon_shrink' => array(
					'label'            => esc_html__( 'Image/Icon Shrink', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '250',
						'step' => '1',
					),
					'fixed_unit'	   => 'px',
					'default'          => '40px',
					'default_on_front' => '40px',
					'mobile_options'   => true,
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Increase or decrease the image/icon shrink.', 'all-in-one-carousel-for-divi' ),
				),
				'image_icon_spacing' => array(
					'label'            => esc_html__( 'Image/Icon Spacing', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '150',
						'step' => '1',
					),
					'fixed_unit'	   => 'px',
					'default'          => '10px',
					'default_on_front' => '10px',
					'mobile_options'   => true,
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Increase or decrease the image/icon spacing.', 'all-in-one-carousel-for-divi' ),
				),
				'enable_tooltip' => array(
					'label'            => esc_html__( 'Enable Tooltip', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' )
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Switch on this to display the title as a tooltip of image/icon.', 'divi-plus' ),
				),
				'icon_font_size' => array(
					'label'            => esc_html__( 'Icon Font Size', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '120',
						'step' => '1',
					),
					'mobile_options'   => true,
					'default'          => '38px',
					'default_on_front' => '38px',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'icon_settings',
					'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
				),
				'icon_color' => array(
					'label'          => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'           => 'color-alpha',
					'hover'          => 'tabs',
					'mobile_options' => false,
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'icon_settings',
					'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
				),
				'tooltip_entrance_animation' => array(
					'label'           => esc_html__( 'Entrance Animation', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'fade'                 => esc_html__( 'Fade', 'divi-plus' ),
						'scale'                => esc_html__( 'Scale', 'divi-plus' ),
						'scale-subtle'         => esc_html__( 'Scale Subtle', 'divi-plus' ),
						'scale-extreme'        => esc_html__( 'Scale Extreme', 'divi-plus' ),
						'shift-away'           => esc_html__( 'Shift Away', 'divi-plus' ),
						'shift-away-subtle'    => esc_html__( 'Shift Away Subtle', 'divi-plus' ),
						'shift-away-extreme'   => esc_html__( 'Shift Away Extreme', 'divi-plus' ),
						'shift-toward'         => esc_html__( 'Shift Toward', 'divi-plus' ),
						'shift-toward-subtle'  => esc_html__( 'Shift Toward Subtle', 'divi-plus' ),
						'shift-toward-extreme' => esc_html__( 'Shift Toward Extreme', 'divi-plus' ),
						'perspective'          => esc_html__( 'Perspective', 'divi-plus' ),
						'perspective-subtle'   => esc_html__( 'Perspective Subtle', 'divi-plus' ),
						'perspective-extreme'  => esc_html__( 'Perspective Extreme', 'divi-plus' ),
					),
					'show_if'         => array(
						'enable_tooltip' => 'on',
					),
					'default'         => 'fade',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_styling',
					'description'     => esc_html__( 'Here you can select the animation effect to be used for the tooltip entrance.', 'divi-plus' ),
				),
				'tooltip_animation_durartion' => array(
					'label'          => esc_html__( 'Animation Duration', 'divi-plus' ),
					'type'           => 'range',
					'range_settings' => array(
						'min'  => '100',
						'max'  => '5000',
						'step' => '10',
					),
					'show_if'        => array(
						'enable_tooltip' => 'on',
					),
					'default'        => '350ms',
					'default_unit'   => 'ms',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'tooltip_styling',
					'description'    => esc_html__( 'Move the slider, or input the value to set the tooltip animation duration.', 'divi-plus' ),
				),
				'tooltip_show_speech_bubble' => array(
					'label'            => esc_html__( 'Show Speech Bubble', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'show_if'          => array(
						'enable_tooltip' => 'on',
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'tooltip_styling',
					'description'      => esc_html__( 'Here you can choose whether or not to display speech bubble.', 'divi-plus' ),
				),
				'tooltip_interactive' => array(
					'label'           => esc_html__( 'Make Interactive Tooltip', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'layout',
					'options'         => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'show_if'         => array(
						'enable_tooltip' => 'on',
					),
					'default'         => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_styling',
					'description'     => esc_html__( 'Here you can choose whether or not to display interactive tooltip. This would provide users the possibility to interact with the content of the tooltip.', 'divi-plus' ),
				),
				'tooltip_width' => array(
					'label'          => esc_html__( 'Tooltip Width', 'divi-plus' ),
					'type'           => 'range',
					'range_settings' => array(
						'min'  => '1',
						'max'  => '1000',
						'step' => '1',
					),
					'mobile_options' => true,
					'show_if'        => array(
						'enable_tooltip' => 'on',
					),
					'default'        => '200px',
					'default_unit'   => 'px',
					'allowed_units'  => array( '%', 'em', 'rem', 'px', 'vh', 'vw', 'cm', 'mm', 'in', 'pt', 'pc', 'ex' ),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'tooltip_styling',
					'description'    => esc_html__( 'Move the slider, or input the value to set the maximum width of the tooltip.', 'divi-plus' ),
				),
				'tooltip_custom_padding' => array(
					'label'           => esc_html__( 'Tooltip Padding', 'divi-plus' ),
					'type'            => 'custom_padding',
					'option_category' => 'layout',
					'mobile_options'  => true,
					'show_if'         => array(
						'enable_tooltip' => 'on',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_styling',
					'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'tooltip_background_color' => array(
					'label'             => esc_html__( 'Tooltip Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'tooltip_background',
					'context'           => 'tooltip_background_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => array_merge(
						$this->generate_background_options( 'tooltip_background', 'color', 'advanced', 'background', 'tooltip_background_color' ),
						$this->generate_background_options( 'tooltip_background', 'gradient', 'advanced', 'background', 'tooltip_background_color' ),
					),
					'mobile_options'    => true,
					'show_if'           => array(
						'enable_tooltip' => 'on',
					),
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'tooltip_styling',
					'description'       => esc_html__( 'Adjust color, gradient, and image to customize the background style of the tooltip.' ),
				),
			),
			$this->generate_background_options( 'tooltip_background', 'skip', 'general', 'background', 'tooltip_background_color' ),
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

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-image-stack-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ImageStack/' . $file . '.min.css', array(), '1.0.0' );

		$render_output = sprintf(
			'<div class="dipl-image-stack-wrap">
				<div class="dipl-image-stack-inner">%1$s</div>
			</div>',
			et_core_esc_previously( $this->content )
		);

		$order_class    = esc_attr( $this->get_module_order_class( 'dipl_image_stack' ) );
		$tp_theme_class = '.' . esc_attr( $order_class ) . '_0';

		// If enable tooltip.
		if ( 'on' === $this->props['enable_tooltip'] ) {
			wp_enqueue_script( 'elicus-tippy-script' );

			$entrance_animation = esc_attr( $this->props['tooltip_entrance_animation'] );
			if ( ! empty( $entrance_animation ) && 'fade' !== $entrance_animation ) {
				wp_enqueue_style( 'dipl-tippy-animation-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/assets/css/tippy-animations/' . $entrance_animation . '.css', array(), '6.3.7' );
			}
			$render_output .= $this->init_tooltip( $this->props, $order_class, $tp_theme_class );
		}

		// Module alignment.
		$image_icon_align = et_pb_responsive_options()->get_property_values( $this->props, 'image_icon_align' );
		foreach ( $image_icon_align as &$align ) {
			$align = str_replace( array( 'left', 'right' ), array( 'flex-start', 'flex-end' ), $align );
		}
		if ( ! empty( array_filter( $image_icon_align ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $image_icon_align, '%%order_class%% .dipl-image-stack-wrap', 'justify-content', $render_slug, '!important;', 'type' );
		}

		// Set width and height.
		$image_icon_size = et_pb_responsive_options()->get_property_values( $this->props, 'image_icon_size' );
		if ( ! empty( array_filter( $image_icon_size ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $image_icon_size, '%%order_class%% .dipl_image_stack_item', 'width', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $image_icon_size, '%%order_class%% .dipl_image_stack_item', 'height', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $image_icon_size, '%%order_class%% .dipl_image_stack_item .dipl-stack-icon', 'line-height', $render_slug, '!important;', 'range' );
		}

		// Shirink images.
		$image_icon_shrink = et_pb_responsive_options()->get_property_values( $this->props, 'image_icon_shrink' );
		foreach ( $image_icon_shrink as &$val ) {
			$val = sprintf( '-%1$s', $val );
		}
		if ( ! empty( array_filter( $image_icon_shrink ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $image_icon_shrink, '%%order_class%% .dipl_image_stack_item:not( :first-child )', 'margin-left', $render_slug, '!important;', 'range' );
		}

		// Image spacing.
		$image_icon_spacing = et_pb_responsive_options()->get_property_values( $this->props, 'image_icon_spacing' );
		if ( ! empty( array_filter( $image_icon_spacing ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $image_icon_spacing, '%%order_class%% .dipl_image_stack_item:not( :last-child )', 'margin-right', $render_slug, '!important;', 'range' );
		}

		// Icon size.
		$icon_font_size = et_pb_responsive_options()->get_property_values( $this->props, 'icon_font_size' );
		et_pb_responsive_options()->generate_responsive_css( $icon_font_size, '%%order_class%% .dipl_image_stack_item .dipl-stack-icon', 'font-size', $render_slug, '!important;', 'range' );
		$this->generate_styles( array(
			'base_attr_name' => 'icon_color',
			'selector'       => '%%order_class%% .dipl_image_stack_item .dipl-stack-icon',
			'hover_selector' => '%%order_class%% .dipl_image_stack_item:hover .dipl-stack-icon',
			'important'      => true,
			'css_property'   => 'color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		if ( 'on' === $this->props['tooltip_show_speech_bubble'] ) {
			$tp_box_class = ".tippy-box[data-theme=\"{$tp_theme_class}\"]";
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}:before",
				'declaration' => 'content: "" !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='top']",
				'declaration' => 'margin-bottom: 15px !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='bottom']",
				'declaration' => 'margin-top: 15px !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='right']",
				'declaration' => 'margin-left: 15px !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='left']",
				'declaration' => 'margin-right: 15px !important;',
			) );
		}

		if ( 'on' === $this->props['enable_tooltip'] ) {
			$args = array(
				'render_slug' => $render_slug,
				'props'		  => $this->props,
				'fields'	  => $this->fields_unprocessed,
				'module'      => $this,
				'backgrounds' => array(
					'tooltip_background' => array(
						'normal' => ".tippy-box[data-theme~='" . esc_attr( $tp_theme_class ) . "']",
						'hover' => ".tippy-box[data-theme~='" . esc_attr( $tp_theme_class ) . "']:hover",
					),
				),
			);
			DiviPlusHelper::process_background( $args );
		}

		$fields = array( 'image_stack_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}

	public function init_tooltip( $props, $order_class, $tp_theme_class ) {
		$entrance_animation  = esc_attr( $props['tooltip_entrance_animation'] );
		$animation_durartion = intval( esc_attr( $props['tooltip_animation_durartion'] ) );
		$interactive         = ( 'on' === esc_attr( $props['tooltip_interactive'] ) ) ? 'true' : 'false';
		$tooltip_width       = intval( esc_attr( $props['tooltip_width'] ) );

		return '<script type="text/javascript">
			jQuery(document).ready( function($) {
				tippy( \'.' . esc_attr( $order_class ) . ' .dipl-stack-image, .' . esc_attr( $order_class ) . ' .dipl-stack-icon\', {
					trigger: "mouseenter",
					// trigger: "click",
					theme: "' . esc_attr( $tp_theme_class ) . '" ,
					interactive: ' . esc_attr( $interactive ) . ',
					animation: "' . esc_attr( $entrance_animation ) . '",
					duration: ' . esc_attr( $animation_durartion ) . ',
					appendTo: () => document.body,
					arrow: false,
					content: (reference) => reference.getAttribute("title"),
					maxWidth: ' . esc_attr( $tooltip_width ) . ',
				} );
			} );
		</script>';
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_image_stack', $modules ) ) {
		new DIPL_ImageStack();
	}
} else {
	new DIPL_ImageStack();
}
