<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.13.0
 */
class DIPL_HeroSlider extends ET_Builder_Module {
	public $slug       = 'dipl_hero_slider';
	public $child_slug = 'dipl_hero_slider_item';
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
		$this->name             = esc_html__( 'DP Hero Slider', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Hero Slide', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'display'         => esc_html__( 'Display', 'divi-plus' ),
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
					'slide_text' => array(
						'title'             => esc_html__( 'Title & Subtitle Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'    => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'subtitle' => array( 'name' => esc_html__( 'Subtitle', 'divi-plus' ) )
						),
					),
					'content_text' => array(
						'title'             => esc_html__( 'Content Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'bb_icons_support'  => true,
						'sub_toggles'       => array(
							'p'     => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a'     => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
							'ul'    => array(
								'name' => 'UL',
								'icon' => 'list',
							),
							'ol'    => array(
								'name' => 'OL',
								'icon' => 'numbered-list',
							),
							'quote' => array(
								'name' => 'QUOTE',
								'icon' => 'text-quote',
							),
						),
					),
					'content_box'   => esc_html__( 'Content Box', 'divi-plus' ),
					'image_video'   => esc_html__( 'Image/Video', 'divi-plus' ),
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
						'default'        => '24px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "%%order_class%% .dipl-hero-slide-title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'slide_text',
					'sub_toggle'  => 'title',
				),
				'subtitle' => array(
					'label'     => esc_html__( 'Subtitle', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "%%order_class%% .dipl-hero-slide-subtitle",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'slide_text',
					'sub_toggle'  => 'subtitle',
				),
				'content_text' => array(
					'label'     => esc_html__( 'Content Text', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main' => "%%order_class%% .dipl-hero-slide-content, %%order_class%% .dipl-hero-slide-content p",
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'p',
				),
				'content_text_link' => array(
					'label'     => esc_html__( 'Link', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "%%order_class%% .dipl-hero-slide-content a",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'a',
				),
				'content_text_ul' => array(
					'label'     => esc_html__( 'Unordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "%%order_class%% .dipl-hero-slide-content ul li",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'ul',
				),
				'content_text_ol' => array(
					'label'     => esc_html__( 'Ordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "%%order_class%% .dipl-hero-slide-content ol li",
						'important' => 'all',
					),
					'toggle_slug'    => 'content_text',
					'sub_toggle'     => 'ol',
				),
				'content_text_quote' => array(
					'label'          => esc_html__( 'Blockquote', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "%%order_class%% .dipl-hero-slide-content blockquote",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'quote',
				),
			),
			'borders' => array(
				'content_box' => array(
					'label_prefix' => esc_html__( 'Content Box', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl-hero-slide-content-inner",
							'border_styles' => "%%order_class%% .dipl-hero-slide-content-inner",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'content_box',
				),
				'image_video' => array(
					'label_prefix' => esc_html__( 'Image/Video Container', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl-hero-slide-media-inner",
							'border_styles' => "%%order_class%% .dipl-hero-slide-media-inner",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image_video',
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
							'border_styles' => '%%order_class%%',
							'border_radii'  => '%%order_class%%',
						),
					),
				)
			),
			'box_shadow' => array(
				'content_box' => array(
					'label'       => esc_html__( 'Content Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "%%order_class%% .dipl-hero-slide-content-inner",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'content_box',
				),
				'image_video' => array(
					'label'       => esc_html__( 'Image/Video Container Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "%%order_class%% .dipl-hero-slide-media-inner",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image_video',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'slider_margin_padding' => array(
				'slider_container' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl-hero-slider-layout > .swiper-container",
							'important'  => 'all',
						),
					),
				),
				'content_box' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "%%order_class%% .dipl-hero-slide-content-wrap",
							'padding'   => "%%order_class%% .dipl-hero-slide-content-inner",
							'important' => 'all',
						),
					),
				),
				'image_video_wrap' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl-hero-slide-media-inner",
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
			'text'         => false,
			'filters'      => false,
			'link_options' => false,
			'background'   => array(
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'content_position' => array(
				'label'            => esc_html__( 'Content Position', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'left'      => esc_html__( 'Left', 'divi-plus' ),
					'right'     => esc_html__( 'Right', 'divi-plus' ),
					'alternate' => esc_html__( 'Alternate', 'divi-plus' ),
				),
				'default'          => 'left',
				'default_on_front' => 'left',
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can choose the slide animation effect.', 'divi-plus' ),
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
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can choose the vertical alignment of content box.', 'divi-plus' ),
			),
			'slide_effect' => array(
				'label'           => esc_html__( 'Slide Effect', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'slide' => esc_html__( 'Slide', 'divi-plus' ),
					'fade'  => esc_html__( 'Fade', 'divi-plus' ),
				),
				'default'         => 'slide',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'slider_settings',
				'sub_toggle'	  => 'general',
				'description'     => esc_html__( 'Here you can choose the slide animation effect.', 'divi-plus' ),
			),
			'slider_loop' => array(
				'label'            => esc_html__( 'Enable Loop', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'off',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'general',
				'description'      => esc_html__( 'Here you can enable loop for the slides.', 'divi-plus' ),
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
				'default'          => 'off',
				'default_on_front' => 'off',
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
				'tab_slug'         => 'general',
				'toggle_slug'      => 'slider_settings',
				'sub_toggle'	   => 'navigation',
				'description'      => esc_html__( 'This setting will turn on and off the dynamic pagination of the slider.', 'divi-plus' ),
			),
			'content_box_custom_padding' => array(
				'label'            => esc_html__( 'Content Box Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '30px|30px|30px|30px|on|on',
				'default_on_front' => '30px|30px|30px|30px|on|on',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'content_box',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'content_box_custom_margin' => array(
				'label'            => esc_html__( 'Content Box Margin', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||on|on',
				'default_on_front' => '||||on|on',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'content_box',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'content_box_bg_color' => array(
				'label'        	   => esc_html__( 'Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'hover'            => 'tab',
				'default'      	   => 'rgba( 0, 0, 0, 0.1 )',
				'default_on_front' => 'rgba( 0, 0, 0, 0.1 )',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'content_box',
				'description'      => esc_html__( 'Here you can select background color for the content box.', 'divi-plus' ),
			),
			'image_video_width_enable' => array(
				'label'           => esc_html__( 'Image/Video Enable Width', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'         => 'off',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'image_video',
				'description'     => esc_html__( 'Whether or not to lazy load images.', 'divi-plus' ),
			),
			'image_video_width' => array(
				'label'            => esc_html__( 'Image/Video Width', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'          => '45%',
				'default_on_front' => '45%',
				'default_unit'     => '%',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex' ),
				'mobile_options'   => true,
				'show_if'          => array(
					'image_video_width_enable' => 'on',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'image_video',
				'description'      => esc_html__( 'Increase or decrease the image or video custom width.', 'divi-plus' ),
			),
			'image_video_wrap_custom_padding' => array(
				'label'            => esc_html__( 'Image/Video Container Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||on|on',
				'default_on_front' => '||||on|on',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'image_video',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
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

			$show_arrow        = esc_attr( $this->props['show_arrow'] );
			$show_control_dot  = esc_attr( $this->props['show_control_dot'] );
			$control_dot_style = esc_attr( $this->props['control_dot_style'] );
			$arrows_position   = et_pb_responsive_options()->get_property_values( $this->props, 'arrows_position' );
			$arrows_position   = array_filter( $arrows_position );

			$content_position  = et_pb_responsive_options()->get_property_values( $this->props, 'content_position' );

			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-hero-slider-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/HeroSlider/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'elicus-swiper-script' );
			wp_enqueue_style( 'elicus-swiper-style' );
			wp_enqueue_style( 'dipl-swiper-style' );

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
					wp_enqueue_script( 'dipl-hero-slider', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/HeroSlider/dipl-hero-slider.min.js", array('jquery'), '1.0.0', true );
					$arrows_position_data = '';
					foreach( $arrows_position as $device => $value ) {
						$arrows_position_data .= ' data-arrows_' . $device . '="' . $value . '"';
					}
				}
				$arrows_nav = sprintf(
					'<div class="dipl_swiper_navigation"%3$s>%1$s %2$s</div>',
					$next,
					$prev,
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

			$wrapper_classes = [ 'dipl_swiper_wrapper', 'dipl-hero-slider-wrap' ];
			if ( ! empty( $content_position['desktop'] ) ) {
				$wrapper_classes[] = sprintf( 'dipl_content_pos_%1$s', esc_attr( $content_position['desktop'] ) );
			}
			if ( ! empty( $content_position['tablet'] ) ) {
				$wrapper_classes[] = sprintf( 'dipl_content_pos_tablet_%1$s', esc_attr( $content_position['tablet'] ) );
			}
			if ( ! empty( $content_position['phone'] ) ) {
				$wrapper_classes[] = sprintf( 'dipl_content_pos_phone_%1$s', esc_attr( $content_position['phone'] ) );
			}

			// Final output.
			$render_output = sprintf(
				'<div class="%4$s">
					<div class="dipl_swiper_inner_wrap dipl-hero-slider-layout">
						<div class="swiper swiper-container">
							<div class="swiper-wrapper">%1$s</div>
						</div><!-- swiper-container -->
						%2$s
					</div>
					%3$s
				</div>',
				et_core_esc_previously( $this->content ),
				et_core_esc_previously( $arrows_nav ),
				et_core_esc_previously( $render_control_dot ),
				implode( ' ', $wrapper_classes )
			);

			// Add script.
			$render_output .= $this->dipl_render_hero_slider_script();

			$content_valign = et_pb_responsive_options()->get_property_values( $this->props, 'content_valign' );
			if ( ! empty( array_filter( $content_valign ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $content_valign, "%%order_class%% .dipl-hero-slide-content-wrap", 'align-items', $render_slug, '', 'type' );
			}

			if ( ! empty( $this->props['content_box_bg_color'] ) ) {
				$this->generate_styles( array(
					'base_attr_name' => 'content_box_bg_color',
					'selector'       => '%%order_class%% .dipl-hero-slide-content-inner',
					'hover_selector' => '%%order_class%% .dipl-hero-slide-content-inner:hover',
					'css_property'   => 'background',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );
			}

			// Image video width.
			if ( 'on' === $this->props['image_video_width_enable'] ) {
				$image_video_width = et_pb_responsive_options()->get_property_values( $this->props, 'image_video_width' );
				if ( ! empty( array_filter( $image_video_width ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $image_video_width, '%%order_class%% .dipl-hero-slide-media-wrap', 'width', $render_slug, '!important;', 'range' );
					et_pb_responsive_options()->generate_responsive_css( $image_video_width, '%%order_class%% .dipl-hero-slide-media-wrap', 'min-width', $render_slug, '!important;', 'range' );
				}
			}

			// Arrows.
			if ( 'on' === $show_arrow ) {
				// Arrows icons.
				if ( '' !== $this->props['next_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl-hero-slider-layout > .dipl_swiper_navigation .swiper-button-next::after',
						'declaration' => 'display: flex; align-items: center; height: 100%; content: attr(data-next_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'next_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl-hero-slider-layout > .dipl_swiper_navigation .swiper-button-next::after',
							'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
						) );
					}
				}
				if ( '' !== $this->props['previous_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl-hero-slider-layout > .dipl_swiper_navigation .swiper-button-prev::after',
						'declaration' => 'display: flex; align-items: center; height: 100%; content: attr(data-previous_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'previous_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl-hero-slider-layout > .dipl_swiper_navigation .swiper-button-prev::after',
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

			$fields = array( 'slider_margin_padding' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );
		}

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}

	/**
	 * This function dynamically creates script parameters according to the user settings
	 *
	 * @return string
	 * */
	public function dipl_render_hero_slider_script() {

		// Get order class
		$order_class = $this->get_module_order_class( 'dipl_hero_slider' );

		$slide_effect        = esc_attr( $this->props['slide_effect'] );
		$show_control_dot    = esc_attr( $this->props['show_control_dot'] );
		$loop				 = ( $this->props['slider_loop'] && 'on' === esc_attr($this->props['slider_loop']) ) ? 'true' : 'false';

		$transition_duration = intval( $this->props['slide_transition_duration'] );
		$transition_duration = '' !== $transition_duration || 0 !== $transition_duration ? $transition_duration : 1000;

		$arrows = 'false';
		if ( isset($this->props['show_arrow']) && 'on' === $this->props['show_arrow'] ) {
			$arrows = "{    
				nextEl: '." . esc_attr( $order_class ) . " .dipl-hero-slider-layout > .dipl_swiper_navigation .swiper-button-next',
				prevEl: '." . esc_attr( $order_class ) . " .dipl-hero-slider-layout > .dipl_swiper_navigation .swiper-button-prev',
			}";
		}
		$dots = 'false';
		if ( 'on' === $show_control_dot ) {
			$number_dot = 'false';
			if ( 'number_dot' === $this->props['control_dot_style'] ) {
				$number_dot = "function (index, className) {
					return '<span class=\"' + className + ' number-bullet\">' + (index + 1) + ' </span>';
				}";
			}
			$dynamic_bullets = 'on' === $this->props['enable_dynamic_dots'] && in_array( $this->props['control_dot_style'], array( 'solid_dot', 'transparent_dot', 'square_dot', 'number_dot' ), true ) ? 'true' : 'false';
			$dots = "{
				el: '." . esc_attr( $order_class ) . " .dipl-hero-slider-layout + .dipl_swiper_pagination .swiper-pagination',
				dynamicBullets: " . $dynamic_bullets . ",
				clickable: true,
				renderBullet: " . $number_dot . ",
			}";
		}

		$autoplay		= esc_attr( $this->props['autoplay'] );
		$pause_on_hover	= esc_attr( $this->props['pause_on_hover'] );

		$autoplaySlides = 0;
		if ( 'on' === $autoplay ) {
			$autoplay_speed = ( isset( $this->props['autoplay_speed'] ) ) ? intval( $this->props['autoplay_speed'] ) : 3000;
			if ( 'on' === $pause_on_hover ) {
				$autoplaySlides = '{ delay:' . $autoplay_speed . ', disableOnInteraction: true }';
			} else {
				$autoplaySlides = '{ delay:' . $autoplay_speed . ', disableOnInteraction: false }';
			}
		}

		$fade = 'false';
		if ( 'fade' === $slide_effect ) {
			$fade = '{ crossFade: true }';
		}

		$script  = '<script type="text/javascript">';
		$script .= 'jQuery( function($) {';
		$script .= 'var ' . esc_attr( $order_class ) . '_swiper = new Swiper( \'.' . esc_attr( $order_class ) . ' .dipl-hero-slider-layout > .swiper-container\', {
				slidesPerView: 1,
				spaceBetween: 0,
				slidesPerGroup: 1,
				slidesPerGroupSkip: 1,
				autoplay: ' . $autoplaySlides . ',
				effect: "' . $slide_effect . '",
				fadeEffect: ' . $fade . ',
				speed: ' . $transition_duration . ',
				loop: ' . $loop . ',
				autoHeight: false,
				pagination: ' . $dots . ',
				navigation: ' . $arrows . ',
				grabCursor: \'true\',
				observer: true,
				observeParents: true
			} );';

			if ( 'on' === $pause_on_hover && 'on' === $autoplay ) {
				$script .= 'jQuery(".' . esc_attr( $order_class ) . ' .swiper-container").on("mouseenter", function(e) {
					if ( typeof ' . esc_attr( $order_class ) . '_swiper.autoplay.stop === "function" ) {
						' . esc_attr( $order_class ) . '_swiper.autoplay.stop();
					}
				});';
				$script .= 'jQuery(".' . esc_attr( $order_class ) . ' .swiper-container").on("mouseleave", function(e) {
					if ( typeof ' . esc_attr( $order_class ) . '_swiper.autoplay.start === "function" ) {
						' . esc_attr( $order_class ) . '_swiper.autoplay.start();
					}
				});';
			}
			if ( 'true' !== $loop ) {
				$script .=  esc_attr( $order_class ) . '_swiper.on(\'reachEnd\', function(){
					' . esc_attr( $order_class ) . '_swiper.autoplay = false;
				});';
			}
		$script .= '} );</script>';

		return $script;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_hero_slider', $modules ) ) {
		new DIPL_HeroSlider();
	}
} else {
	new DIPL_HeroSlider();
}
