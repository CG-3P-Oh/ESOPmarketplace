<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */
class DIPL_AccordionSlider extends ET_Builder_Module {
	public $slug       = 'dipl_accordion_slider';
	public $child_slug = 'dipl_accordion_slider_item';
	public $vb_support = 'on';

	/**
	 * Track if the module is currently rendering,
	 * to prevent unnecessary rendering and recursion.
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
		$this->name 			= esc_html__( 'DP Accordion Slider', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Accordion Slide', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'slider_settings' => array(
						'title' => esc_html__( 'Slider', 'divi-plus' ),
						'sub_toggles' => array(
							'general'    => array( 'name' => esc_html__( 'General', 'divi-plus' ) ),
							'navigation' => array( 'name' => esc_html__( 'Navigation', 'divi-plus' ) )
						),
						'tabbed_subtoggles' => true,
					),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'text'       => esc_html__( 'Text', 'divi-plus' ),
					'icon_style' => esc_html__( 'Icon', 'divi-plus' ),
					'title'      => esc_html__( 'Title', 'divi-plus' ),
					'desc'       => array(
						'title' => esc_html__( 'Description', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'bb_icons_support'  => true,
						'sub_toggles'       => array(
							'p' => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a' => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
							'ul' => array(
								'name' => 'UL',
								'icon' => 'list',
							),
							'ol' => array(
								'name' => 'OL',
								'icon' => 'numbered-list',
							),
							'quote' => array(
								'name' => 'QUOTE',
								'icon' => 'text-quote',
							),
						),
					),
					'item_style'    => esc_html__( 'Accordion Item', 'divi-plus' ),
					'slider_styles' => esc_html__( 'Slider', 'divi-plus' ),
					'slider_dots'   => esc_html__( 'Slider Pagination', 'divi-plus' ),
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
						'default' => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css'           => array(
						'main'      => "{$this->main_css_element} .dipl_accordion_slider_item_title",
						'hover'     => "{$this->main_css_element} .dipl_accordion_slider_item_title:hover",
						'important' => 'all',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'title',
				),
				'desc_text' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.3',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_accordion_slider_item_description, {$this->main_css_element} .dipl_accordion_slider_item_description p",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'p',
				),
				'desc_link' => array(
					'label'     => esc_html__( 'Description Link', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.3',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'   => '0',
							'max'   => '10',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_accordion_slider_item_description a",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'a',
				),
				'desc_ul' => array(
					'label'     => esc_html__( 'Description Unordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.3',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_accordion_slider_item_description ul li",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'ul',
				),
				'desc_ol' => array(
					'label'     => esc_html__( 'Description Ordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'   => '1',
							'max'   => '100',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.3',
						'range_settings' => array(
							'min'   => '0.1',
							'max'   => '10',
							'step'  => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'   => '0',
							'max'   => '10',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_accordion_slider_item_description ol li",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'ol',
				),
				'desc_quote' => array(
					'label'     => esc_html__( 'Description Blockquote', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'   => '1',
							'max'   => '100',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.3',
						'range_settings' => array(
							'min'   => '0.1',
							'max'   => '10',
							'step'  => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'   => '0',
							'max'   => '10',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_accordion_slider_item_description blockquote",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'quote',
				),
			),
			'text' => array(
				'use_background_layout' => true,
				'options'               => array(
					'background_layout' => array(
						'default'           => 'dark',
						'default_on_front'  => 'dark',
						'hover'             => 'tabs',
					)
				),
			),
			'borders' => array(
				'item' => array(
					'css' => array(
						'main' => array(
						    'border_radii'	=> "%%order_class%% .dipl_accordion_slider_item_wrapper",
							'border_styles'	=> "%%order_class%% .dipl_accordion_slider_item_wrapper",
						)
					),
					'label_prefix' => esc_html__( 'Item', 'divi-plus' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'item_style',
				),
				'arrows' => array(
					'css' => array(
						'main' => array(
						    'border_radii'	=> "%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next",
							'border_styles'	=> "%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next",
						),
						'important' => 'all',
					),
					'label_prefix' => esc_html__( 'Arrows', 'divi-plus' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_styles',
				),
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => $this->main_css_element,
							'border_radii'  => $this->main_css_element,
						),
					),
				),
			),
			'accordion_slider_margin_padding' => array(
				'content_box' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_accordion_slider_item_content",
							'important'  => 'all',
						),
					),
				),
				'slider_container' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl-accordion-slider-layout > .swiper-container",
							'important'  => 'all',
						),
					),
				),
				"arrows" => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next",
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
			'text_shadow' => false,
			'background' => array(
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'collapsed_width' => array(
				'label'            => esc_html__( 'Collapsed Width', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '50',
					'max'  => '300',
					'step' => '1',
				),
				'default'          => '150px',
				'default_on_front' => '150px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px', '%' ),
				'mobile_options'   => false,
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'general',
				'description'      => esc_html__( 'Increase or decrease the slide custom width when it collapsed.', 'divi-plus' ),
			),
			'gap' => array(
				'label'            => esc_html__( 'Space Between Slides', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'default'          => '20px',
				'default_on_front' => '20px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'mobile_options'   => false,
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'general',
				'description'      => esc_html__( 'Increase or decrease the slide custom width when it collapsed.', 'divi-plus' ),
			),
			'autoplay' => array(
				'label'            => esc_html__( 'Autoplay', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'on',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'general',
				'description'      => esc_html__( 'This controls the auto play the slider.', 'divi-plus' ),
			),
			'enable_linear_transition' => array(
				'label'           => esc_html__( 'Enable Linear Transition', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'         => 'off',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'	  => 'general',
				'description'     => esc_html__( 'Here you can choose whether or not to enable linear transition between slides.', 'divi-plus' ),
			),
			'autoplay_speed' => array(
				'label'            => esc_html__( 'Autoplay Delay', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'          => '3000',
				'show_if'          => array(
					'autoplay' => 'on',
				),
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'general',
				'description'      => esc_html__( 'This controls the time of the slide before the transition.', 'divi-plus' ),
			),
			'pause_on_hover' => array(
				'label'            => esc_html__( 'Pause On Hover', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'on',
				'show_if'          => array(
					'autoplay' => 'on',
				),
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'general',
				'description'      => esc_html__( 'Control for pausing slides on mouse hover.', 'divi-plus' ),
			),
			'slide_transition_duration' => array(
				'label'           => esc_html__( 'Transition Duration', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '1000',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'	  => 'general',
				'description'     => esc_html__( 'Here you can specify the duration of transition for each slide in miliseconds.', 'divi-plus' ),
			),
			'show_arrow' => array(
				'label'            => esc_html__( 'Show Arrows', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'on',
				'default_on_front' => 'on',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'navigation',
				'description'      => esc_html__( 'Choose whether or not the previous & next arrows should be visible.', 'divi-plus' ),
			),
			'previous_slide_arrow' => array(
				'label'           => esc_html__( 'Previous Arrow', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array(
					'et-pb-font-icon',
				),
				'show_if'         => array(
					'show_arrow' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'	  => 'navigation',
				'description'     => esc_html__( 'Here you can select the icon to be used for the previous slide navigation.', 'divi-plus' ),
			),
			'next_slide_arrow' => array(
				'label'           => esc_html__( 'Next Arrow', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array(
					'et-pb-font-icon',
				),
				'show_if'         => array(
					'show_arrow' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'	  => 'navigation',
				'description'     => esc_html__( 'Here you can select the icon to be used for the next slide navigation.', 'divi-plus' ),
			),
			'show_arrow_on_hover' => array(
				'label'            => esc_html__( 'Show Arrows On Hover', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'show_if'          => array(
					'show_arrow' => 'on',
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'navigation',
				'description'      => esc_html__( 'Choose whether or not the previous and next arrows should be visible.', 'divi-plus' ),
			),
			'arrows_position' => array(
				'label'           => esc_html__( 'Arrows Position', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'inside' 		=> esc_html__( 'Inside', 'divi-plus' ),
					'outside'		=> esc_html__( 'Outside', 'divi-plus' ),
					'top_left'      => esc_html__( 'Top Left', 'divi-plus' ),
					'top_right'     => esc_html__( 'Top Right', 'divi-plus' ),
					'top_center'    => esc_html__( 'Top Center', 'divi-plus' ),
					'bottom_left'   => esc_html__( 'Bottom Left', 'divi-plus' ),
					'bottom_right'  => esc_html__( 'Bottom Right', 'divi-plus' ),
					'bottom_center' => esc_html__( 'Bottom Center', 'divi-plus' ),
				),
				'default'         => 'inside',
				'mobile_options'  => true,
				'show_if'         => array(
					'show_arrow' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'	  => 'navigation',
				'description'     => esc_html__( 'Here you can choose the arrows position.', 'divi-plus' ),
			),
			'show_control_dot' => array(
				'label'            => esc_html__( 'Show Dots Pagination', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'navigation',
				'description'      => esc_html__( 'This setting will turn on and off the pagination of the slider.', 'divi-plus' ),
			),
			'control_dot_style' => array(
				'label'            => esc_html__( 'Dots Pagination Style', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'solid_dot'       => esc_html__( 'Solid Dot', 'divi-plus' ),
					'transparent_dot' => esc_html__( 'Transparent Dot', 'divi-plus' ),
					'stretched_dot'   => esc_html__( 'Stretched Dot', 'divi-plus' ),
					'line'            => esc_html__( 'Line', 'divi-plus' ),
					'rounded_line'    => esc_html__( 'Rounded Line', 'divi-plus' ),
					'square_dot'      => esc_html__( 'Squared Dot', 'divi-plus' ),
					'number_dot'      => esc_html__( 'Number Dot', 'divi-plus' ),
				),
				'show_if'          => array(
					'show_control_dot' => 'on',
				),
				'default'          => 'solid_dot',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'navigation',
				'description'      => esc_html__( 'control dot style', 'divi-plus' ),
			),
			'enable_dynamic_dots' => array(
				'label'           => esc_html__( 'Enable Dynamic Dots', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'off',
				'show_if'          => array(
					'show_control_dot' => 'on',
					'control_dot_style' => array(
						'solid_dot',
						'transparent_dot',
						'square_dot',
						'number_dot'
					),
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'      => 'navigation',
				'description'     => esc_html__( 'This setting will turn on and off the dynamic pagination of the slider.', 'divi-plus' ),
			),
			'slider_container_custom_padding' => array(
				'label'            => esc_html__( 'Slider Container Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||on|on',
				'default_on_front' => '||||on|on',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'margin_padding',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'arrows_custom_padding' => array(
				'label'           => esc_html__( 'Arrows Padding', 'divi-plus' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'show_if'         => array(
					'show_arrow'  => 'on',
				),
				'default'		   => '5px|10px|5px|10px|true|true',
				'default_on_front' => '5px|10px|5px|10px|true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'slider_styles',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'arrow_font_size' => array(
				'label'           => esc_html__( 'Arrow Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '100',
					'step' => '1',
				),
				'show_if'         => array(
					'show_arrow' => 'on',
				),
				'default'         => '24px',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'slider_styles',
				'description'     => esc_html__( 'Here you can choose the arrow font size.', 'divi-plus' ),
			),
			'arrow_color' => array(
				'label'        => esc_html__( 'Arrow Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'show_if'      => array(
					'show_arrow' => 'on',
				),
				'hover'        => 'tabs',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'slider_styles',
				'description'  => esc_html__( 'Here you can define color for the arrow', 'divi-plus' ),
			),
			'arrow_background_color' => array(
				'label'        => esc_html__( 'Arrow Background', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'show_if'      => array(
					'show_arrow' => 'on',
				),
				'hover'        => 'tabs',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'slider_styles',
				'description'  => esc_html__( 'Here you can choose a custom color to be used for the shape background of arrows.', 'divi-plus' ),
			),
			'control_dot_active_color' => array(
				'label'        => esc_html__( 'Active Dot Pagination Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'show_if'      => array(
					'show_control_dot' => 'on',
				),
				'default'      => '#000000',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'slider_dots',
				'description'  => esc_html__( 'Here you can define color for the active pagination item.', 'divi-plus' ),
			),
			'control_dot_inactive_color' => array(
				'label'        => esc_html__( 'Inactive Dot Pagination Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'show_if'	   => array(
					'show_control_dot' => 'on',
				),
				'default'       => '#cccccc',
				'tab_slug'      => 'advanced',
				'toggle_slug'   => 'slider_dots',
				'description'   => esc_html__( 'Here you can define color for the inactive pagination item.', 'divi-plus' ),
			),
			'number_dot_text_color' => array(
				'label'        => esc_html__( 'Number Dot Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'show_if'      => array(
					'show_control_dot'  => 'on',
					'control_dot_style' => 'number_dot',
				),
				'default'       => '#ffffff',
				'tab_slug'      => 'advanced',
				'toggle_slug'  	=> 'slider_dots',
				'description'  	=> esc_html__( 'Here you can define color for the number of pagination item.', 'divi-plus' ),
			),
			'icon_fontsize' => array(
				'label'            => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'default'          => '38px',
				'default_on_front' => '38px',
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon_style',
				'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'          => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_style',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'content_valign' => array(
				'label'            => esc_html__( 'Content Vertical Align', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'flex-start' => esc_html__( 'Top', 'divi-plus' ),
					'center'     => esc_html__( 'Center', 'divi-plus' ),
					'flex-end'   => esc_html__( 'Bottom', 'divi-plus' ),
				),
				'default'          => 'center',
				'default_on_front' => 'center',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'item_style',
				'description'      => esc_html__( 'Here you can choose the vertical alignment of content box.', 'divi-plus' ),
			),
			'content_box_custom_padding' => array(
				'label'            => esc_html__( 'Content Box Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||on|on',
				'default_on_front' => '||||on|on',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'item_style',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'content_box_bg_color' => array(
				'label'          	=> esc_html__( 'Content background Color', 'divi-plus' ),
				'type'            	=> 'color-alpha',
				'hover'           	=> 'tabs',
				'mobile_options'  	=> true,
				'default'         	=> 'rgba(0,0,0,0.3)',
				'default_on_front'  => 'rgba(0,0,0,0.3)',
				'tab_slug'        	=> 'advanced',
				'toggle_slug'     	=> 'item_style',
				'description'     	=> esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'item_height' => array(
				'label'            => esc_html__( 'Item Height', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '50',
					'max'  => '1000',
					'step' => '1',
				),
				'default'          => '350px',
				'default_on_front' => '350px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'item_style',
				'description'      => esc_html__( 'Increase or decrease the item custom height.', 'divi-plus' ),
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

		$render_output = '';
		if ( ! empty( $this->content ) ) {
			// Get props.
			$show_arrow        = esc_attr( $this->props['show_arrow'] );
			$show_control_dot  = esc_attr( $this->props['show_control_dot'] );
			$control_dot_style = esc_attr( $this->props['control_dot_style'] );
			$arrows_position   = et_pb_responsive_options()->get_property_values( $this->props, 'arrows_position' );
			$arrows_position   = array_filter( $arrows_position );

			// Load style and script files.
			wp_enqueue_script( 'elicus-swiper-script' );
			wp_enqueue_style( 'elicus-swiper-style' );
			wp_enqueue_style( 'dipl-swiper-style' );
			
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-accordion-slider-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AccordionSlider/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'dipl-accordion-slider-script', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/AccordionSlider/dipl-accordion-slider.min.js", array('jquery'), '1.0.0', true );

			// Arrows nav.
			$arrows_nav = '';
			if ( 'on' === $show_arrow ) {
				$next = sprintf(
					'<div class="swiper-button-next"%1$s></div>',
					'' !== $this->props['next_slide_arrow'] ?
					sprintf(
						' data-next_slide_arrow="%1$s"',
						esc_attr( et_pb_process_font_icon( $this->props['next_slide_arrow'] ) )
					) :
					''
				);
				$prev = sprintf(
					'<div class="swiper-button-prev"%1$s></div>',
					'' !== $this->props['previous_slide_arrow'] ?
					sprintf(
						' data-previous_slide_arrow="%1$s"',
						esc_attr( et_pb_process_font_icon( $this->props['previous_slide_arrow'] ) )
					) :
					''
				);
				if ( ! empty( $arrows_position ) ) {
					$arrows_position_data = '';
					foreach( $arrows_position as $device => $value ) {
						$arrows_position_data .= ' data-arrows_' . $device . '="' . $value . '"';
					}
				}
				$arrows_nav = sprintf(
					'<div class="dipl_swiper_navigation"%3$s>%1$s %2$s</div>',
					et_core_esc_previously( $next ),
					et_core_esc_previously( $prev ),
					! empty( $arrows_position ) ? $arrows_position_data : ''
				);
			}

			// Control dots.
			$render_control_dot = '';
			if ( 'on' === $show_control_dot ) {
				$render_control_dot = sprintf(
					'<div class="dipl_swiper_pagination"><div class="swiper-pagination %1$s"></div></div>',
					esc_attr( $control_dot_style )
				);
			}

			// Data props.
			$data_props = array(
				'collapsed_width',
				'gap',
				'autoplay',
				'autoplay_speed',
				'pause_on_hover',
				'show_arrow',
				'show_control_dot',
				'enable_dynamic_dots'
			);
			$data_atts = $this->props_to_html_data_attrs( $data_props );

			// Final rendering.
			$render_output = sprintf(
				'<div class="dipl_accordion_slider_wrapper dipl_swiper_wrapper" %1$s>
					<div class="dipl_swiper_inner_wrap dipl-accordion-slider-layout">
						<div class="swiper-container">
							<div class="swiper-wrapper">%2$s</div>
						</div><!-- /.swiper-container -->
						%3$s
					</div>
					%4$s
				</div>',
				et_core_esc_previously( $data_atts ),
				et_core_intentionally_unescaped( $this->content, 'html' ),
				et_core_esc_previously( $arrows_nav ),
				et_core_esc_previously( $render_control_dot )
			);

			// Icon style.
			$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, '%%order_class%% .dipl_accordion_slider_item_icon .et-pb-icon', 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => '%%order_class%% .dipl_accordion_slider_item_icon .et-pb-icon',
				'hover_selector' => '%%order_class%% .dipl_accordion_slider_item_icon .et-pb-icon:hover',
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Item.
			$item_height = et_pb_responsive_options()->get_property_values( $this->props, 'item_height' );
			et_pb_responsive_options()->generate_responsive_css( $item_height, '%%order_class%% .dipl_accordion_slider_item_inner', 'height', $render_slug, '!important;', 'range' );

			// Item content.
			$content_valign = et_pb_responsive_options()->get_property_values( $this->props, 'content_valign' );
			if ( ! empty( array_filter( $content_valign ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $content_valign, "%%order_class%% .dipl_accordion_slider_item_content", 'justify-content', $render_slug, '', 'type' );
			}
			$this->generate_styles( array(
				'base_attr_name' => 'content_box_bg_color',
				'selector'       => '%%order_class%% .dipl_accordion_slider_item_content',
				'hover_selector' => '%%order_class%% .dipl_accordion_slider_item_content:hover',
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Arrows.
			if ( 'on' === $show_arrow ) {
				// Arrows icons.
				if ( '' !== $this->props['next_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl-accordion-slider-layout > .dipl_swiper_navigation .swiper-button-next::after',
						'declaration' => 'display: flex; align-items: center; height: 100%; content: attr(data-next_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'next_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl-accordion-slider-layout > .dipl_swiper_navigation .swiper-button-next::after',
							'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
						) );
					}
				}
				if ( '' !== $this->props['previous_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl-accordion-slider-layout > .dipl_swiper_navigation .swiper-button-prev::after',
						'declaration' => 'display: flex; align-items: center; height: 100%; content: attr(data-previous_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'previous_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl-accordion-slider-layout > .dipl_swiper_navigation .swiper-button-prev::after',
							'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
						) );
					}
				}

				// Font size for arrows.
				$arrow_font_size = et_pb_responsive_options()->get_property_values( $this->props, 'arrow_font_size' );
				if ( ! empty( array_filter( $arrow_font_size ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $arrow_font_size, '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next', 'font-size', $render_slug, '!important;', 'range' );
				}
				$show_arrow_on_hover = $this->props['show_arrow_on_hover'];
				if ( 'on' === $show_arrow_on_hover ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev',
						'declaration' => 'visibility: hidden; opacity: 0; transition: all 300ms ease;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => 'visibility: hidden; opacity: 0; transition: all 300ms ease;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_swiper_navigation .swiper-button-prev, %%order_class%%:hover .dipl_swiper_navigation .swiper-button-next',
						'declaration' => 'visibility: visible; opacity: 1;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_swiper_navigation .swiper-button-prev.swiper-button-disabled, %%order_class%%:hover .dipl_swiper_navigation .swiper-button-next.swiper-button-disabled',
						'declaration' => 'opacity: 0.35;',
					) );
					// Outside Slider.
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_outside .swiper-button-prev',
						'declaration' => 'left: 50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_outside .swiper-button-next',
						'declaration' => 'right: 50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_outside .swiper-button-prev',
						'declaration' => 'left: 0;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_outside .swiper-button-next',
						'declaration' => 'right: 0;',
					) );
					// Inside Slider.
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_inside .swiper-button-prev',
						'declaration' => 'left: -50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_inside .swiper-button-next',
						'declaration' => 'right: -50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_inside .swiper-button-prev',
						'declaration' => 'left: 0;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_inside .swiper-button-next',
						'declaration' => 'right: 0;',
					) );
				}

				$arrow_color = $this->props['arrow_color'];
				if ( $arrow_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => sprintf( 'color: %1$s !important;', esc_attr( $arrow_color ) ),
					) );
				}
				$arrow_color_hover = $this->get_hover_value( 'arrow_color' );
				if ( $arrow_color_hover ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev:hover, %%order_class%% .dipl_swiper_navigation .swiper-button-next:hover',
						'declaration' => sprintf( 'color: %1$s !important;', esc_attr( $arrow_color_hover ) ),
					) );
				}

				$arrow_background_color = $this->props['arrow_background_color'];
				if ( '' !== $arrow_background_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $arrow_background_color ) ),
					) );
				}
				$arrow_background_color_hover = $this->get_hover_value( 'arrow_background_color' );
				if ( '' !== $arrow_background_color_hover ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev:hover, %%order_class%% .dipl_swiper_navigation .swiper-button-next:hover',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $arrow_background_color_hover ) ),
					) );
				}
			}

			if ( 'on' === $show_control_dot ) {
				$control_dot_inactive_color = $this->props['control_dot_inactive_color'];
				if ( $control_dot_inactive_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .swiper-pagination-bullet',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $control_dot_inactive_color ) ),
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .transparent_dot .swiper-pagination-bullet',
						'declaration' => sprintf( 'border-color: %1$s;', esc_attr( $control_dot_inactive_color ) ),
					) );
				}
				$control_dot_active_color = $this->props['control_dot_active_color'];
				if ( $control_dot_active_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .swiper-pagination-bullet.swiper-pagination-bullet-active',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $control_dot_active_color ) ),
					) );
				}
				$slide_transition_duration = $this->props['control_dot_active_color'];
				if ( 'stretched_dot' === $this->props['control_dot_style'] && $slide_transition_duration ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .stretched_dot .swiper-pagination-bullet',
						'declaration' => sprintf( 'transition: all %1$sms ease;', intval( $slide_transition_duration ) ),
					) );
				}
				$number_dot_text_color = $this->props['number_dot_text_color'];
				if ( ! empty( $number_dot_text_color ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .swiper-pagination-bullet.number-bullet',
						'declaration' => sprintf( 'color: %1$s;', esc_attr( $number_dot_text_color ) ),
					) );
				}
			}

			// Liner transition.
			if ( 'on' === $this->props['enable_linear_transition'] ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .swiper-wrapper',
					'declaration' => 'transition-timing-function : linear !important;',
				) );
			}

			$fields = array( 'accordion_slider_margin_padding' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );
		}

		// Add module classes.
		$background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class( $this->props );
		$this->add_classname( array(
			$this->get_text_orientation_classname(),
			$background_layout_class_names[0],
		) );

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_accordion_slider', $modules, true ) ) {
		new DIPL_AccordionSlider();
	}
} else {
	new DIPL_AccordionSlider();
}
