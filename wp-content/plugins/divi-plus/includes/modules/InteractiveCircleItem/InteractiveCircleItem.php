<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */
class DIPL_InteractiveCircleItem extends ET_Builder_Module {
	public $slug       = 'dipl_interactive_circle_item';
	public $type       = 'child';
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
		$this->name 						= esc_html__( 'DP Interactive Circle Item', 'divi-plus' );
		$this->advanced_setting_title_text  = esc_html__( 'Interactive Circle Item', 'divi-plus' );
		$this->child_title_var              = 'title';
		$this->main_css_element 			= '.dipl_interactive_circle %%order_class%%';
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
					'title'       => esc_html__( 'Title', 'divi-plus' ),
					'description' => array(
						'title'   => esc_html__( 'Description', 'divi-plus' ),
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
					'icon_image' => esc_html__( 'Content Icon/Image', 'divi-plus' ),
					'button'     => esc_html__( 'Content Button', 'divi-plus' ),
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
						'default' => '22px',
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
					'header_level' => array(
						'default' => 'h3',
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl_icircle_item_title",
						'hover'      => "{$this->main_css_element} .dipl_icircle_item_title:hover",
						'important'	 => 'all',
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
						'default' => '1.3',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_icircle_item_description, {$this->main_css_element} .dipl_icircle_item_description p",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
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
						'default' => '1.3',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_icircle_item_description a",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
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
						'default' => '1.3',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_icircle_item_description ul li",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
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
						'default' => '1.3',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_icircle_item_description ol li",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
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
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_icircle_item_description blockquote",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
					'sub_toggle'  => 'quote',
				),
			),
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'divi-plus' ),
					'css' => array(
						'main'      => "{$this->main_css_element} .et_pb_button",
						'alignment' => "{$this->main_css_element} .et_pb_button_wrapper",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .et_pb_button_wrapper",
							'padding'   => "{$this->main_css_element} .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => false,
					'box_shadow'      => true,
					'depends_on'      => array( 'show_button' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'button',
				),
			),
			'borders' => array(
				'info_circle' => array(
					'label_prefix' => esc_html__( 'Icon/Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_circle_item_image_wrap",
							'border_styles' => "{$this->main_css_element} .dipl_circle_item_image_wrap",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'icon_image',
				),
				'default' => array(
					'defaults' => array(
						'border_radii' => 'on|50px|50px|50px|50px',
					),
					'css' => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element} .dipl_interactive_circle_item__main_content",
							'border_radii'  => "{$this->main_css_element} .dipl_interactive_circle_item__main_content",
						),
						'important' => 'all',
					),
				),
			),
			'interactive_circle_spacing' => array(
				'icon_image' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_circle_item_image_wrap",
							'important'  => 'all',
						),
					),
				),
			),
			'margin_padding' => array(
				'use_margin'     => false,
				'custom_padding' => array(
					'default_on_front' => '50px|50px|50px|50px|on|on'
				),
				'css' => array(
					'padding'    => "{$this->main_css_element} .dipl_interactive_circle_item__main_content",
					'important'  => 'all',
				),
			),
			'max_width'    => false,
			'height'       => false,
			'text'         => false,
			'box_shadow'   => false,
			'text_shadow'  => false,
			'link_options' => false,
			'filters'      => false,
			'background'   => array(
				'use_background_video' => false,
				'css' => array(
					'main' => "{$this->main_css_element} .dipl_interactive_circle_item__main_content",
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'use_info_icon' => array(
				'label'           => esc_html__( 'Use Icon', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here, you can choose whether or not to use the image for the highlighted small circle.', 'divi-plus' ),
			),
			'info_image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'default'			 => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTA4MCIgaGVpZ2h0PSI1NDAiIHZpZXdCb3g9IjAgMCAxMDgwIDU0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHBhdGggZmlsbD0iI0VCRUJFQiIgZD0iTTAgMGgxMDgwdjU0MEgweiIvPgogICAgICAgIDxwYXRoIGQ9Ik00NDUuNjQ5IDU0MGgtOTguOTk1TDE0NC42NDkgMzM3Ljk5NSAwIDQ4Mi42NDR2LTk4Ljk5NWwxMTYuMzY1LTExNi4zNjVjMTUuNjItMTUuNjIgNDAuOTQ3LTE1LjYyIDU2LjU2OCAwTDQ0NS42NSA1NDB6IiBmaWxsLW9wYWNpdHk9Ii4xIiBmaWxsPSIjMDAwIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz4KICAgICAgICA8Y2lyY2xlIGZpbGwtb3BhY2l0eT0iLjA1IiBmaWxsPSIjMDAwIiBjeD0iMzMxIiBjeT0iMTQ4IiByPSI3MCIvPgogICAgICAgIDxwYXRoIGQ9Ik0xMDgwIDM3OXYxMTMuMTM3TDcyOC4xNjIgMTQwLjMgMzI4LjQ2MiA1NDBIMjE1LjMyNEw2OTkuODc4IDU1LjQ0NmMxNS42Mi0xNS42MiA0MC45NDgtMTUuNjIgNTYuNTY4IDBMMTA4MCAzNzl6IiBmaWxsLW9wYWNpdHk9Ii4yIiBmaWxsPSIjMDAwIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz4KICAgIDwvZz4KPC9zdmc+Cg==',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'show_if'            => array( 'use_info_icon' => 'off' ),
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can upload the image for accordion slide.', 'divi-plus' ),
			),
			'info_image_alt' => array(
				'label'            => esc_html__( 'Image ALT Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'show_if'          => array( 'use_info_icon' => 'off' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can define the image alt text.', 'divi-plus' ),
			),
			'info_icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'show_if'         => array( 'use_info_icon' => 'on' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Choose an icon to display with the accordion.', 'divi-plus' ),
			),
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'default'          => esc_html__( 'Your title goes here.', 'divi-plus' ),
				'dynamic_content'  => 'text',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can define the title which will appear in the active state.', 'divi-plus' ),
			),
			'content' => array(
				'label'            => esc_html__( 'Description', 'divi-plus' ),
				'type'             => 'tiny_mce',
				'dynamic_content'  => 'text',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can define the title which will appear in the active state.', 'divi-plus' ),
			),
			'use_content_image' => array(
				'label'           => esc_html__( 'Use Content Image', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here, you can choose whether or not to use the image for the circle content.', 'divi-plus' ),
			),
			'content_icon' => array(
				'label'           => esc_html__( 'Content Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'show_if'         => array( 'use_content_image' => 'off' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Choose an icon to display for the circle.', 'divi-plus' ),
			),
			'content_image' => array(
				'label'              => esc_html__( 'Content Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'show_if'            => array( 'use_content_image' => 'on' ),
				'toggle_slug'        => 'display',
				'description'        => esc_html__( 'Here you can upload the image for the circle.', 'divi-plus' ),
			),
			'content_image_alt' => array(
				'label'            => esc_html__( 'Content Image ALT Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'show_if'          => array( 'use_content_image' => 'on' ),
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can define the image alt text.', 'divi-plus' ),
			),
			'show_button' => array(
				'label'     		=> esc_html__( 'Show Content Button', 'divi-plus' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'basic_option',
				'options'           => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'affects' 			=> array( 'custom_button' ),
				'default'           => 'off',
				'toggle_slug'       => 'display',
				'description'       => esc_html__( 'Here you can choose whether or not use the button.', 'divi-plus' ),
			),
			'button_text' => array(
				'label'    			=> esc_html__( 'Button Text', 'divi-plus' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'show_if'           => array(
					'show_button' => 'on',
				),
				'default'			=> esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front'	=> esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'       => 'display',
				'description'       => esc_html__( 'Here you can input the text to be used for the  Read More Button.', 'divi-plus' ),
			),
			'button_url' => array(
				'label'           	=> esc_html__( 'Button Link URL', 'divi-plus' ),
				'type'            	=> 'text',
				'option_category' 	=> 'basic_option',
				'dynamic_content' 	=> 'url',
				'show_if'           => array(
					'show_button' => 'on',
				),
				'toggle_slug'     	=> 'display',
				'description'     	=> esc_html__( 'Here you can input the destination URL for the button to open when clicked.', 'divi-plus' ),
			),
			'button_url_new_window' => array(
				'label'            	=> esc_html__( 'Button Link Target', 'divi-plus' ),
				'type'             	=> 'select',
				'option_category'  	=> 'configuration',
				'options'          	=> array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'show_if'           => array(
					'show_button' => 'on',
				),
				'toggle_slug'      	=> 'display',
				'description'      	=> esc_html__( 'Here you can choose whether or not your link opens in a new window', 'divi-plus' ),
			),
			'icon_image_custom_padding' => array(
				'label'            => esc_html__( 'Icon/Image Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon_image',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'icon_image_bg_color' => array(
				'label'          => esc_html__( 'Icon/Image Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_image',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'content_icon_fontsize' => array(
				'label'           => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'default'         => '50px',
				'mobile_options'  => true,
				'show_if'         => array(
					'use_content_image' => 'off',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'icon_image',
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'content_icon_color' => array(
				'label'          => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => true,
				'show_if'        => array(
					'use_content_image'  => 'off',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_image',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'content_image_height' => array(
				'label'           => esc_html__( 'Image Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '350',
					'step' => '1',
				),
				'default'         => '120px',
				'default_unit'    => 'px',
				'allowed_units'   => array( 'em', 'rem', 'px' ),
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'height' ),
				'mobile_options'  => true,
				'show_if'         => array(
					'use_content_image' => 'on',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'icon_image',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the image.', 'divi-plus' ),
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

		$multi_view        = et_pb_multi_view_options( $this );

		$use_info_icon     = sanitize_text_field( $this->props['use_info_icon'] ) ?? 'off';
		$use_content_image = sanitize_text_field( $this->props['use_content_image'] ) ?? 'off';
		$title_level       = et_pb_process_header_level( $this->props['title_level'], 'h3' );
		$title_level       = esc_html( $title_level );

		// Info icon.
		$info_icon_image = '';
		if ( 'on' === $use_info_icon ) {
			$info_icon_image = $multi_view->render_element( array(
				'content'  => '{{info_icon}}',
				'attrs'    => array( 'class' => 'et-pb-icon' ),
				'required' => 'info_icon',
			) );
		} else {
			$info_icon_image = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src' => '{{info_image}}',
					'alt' => '{{info_image_alt}}',
				),
				'required' => 'info_image',
			) );
		}

		// Content Icon.
		$content_icon_image = '';
		if ( 'on' === $use_content_image ) {
			$content_icon_image = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src' => '{{content_image}}',
					'alt' => '{{content_image_alt}}',
				),
				'required' => 'content_image',
			) );
		} else {
			$content_icon_image = $multi_view->render_element( array(
				'content'  => '{{content_icon}}',
				'attrs'    => array( 'class' => 'et-pb-icon' ),
				'required' => 'content_icon',
			) );
		}
		// Info icon image.
		if ( ! empty( $content_icon_image ) ) {
			$content_icon_image = sprintf(
				'<div class="dipl_icircle_item_image %1$s">%2$s</div>',
				( 'off' === $use_content_image ) ? 'dipl-used-icon' : '',
				et_core_esc_previously( $content_icon_image )
			);
		}

		// Render title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_icircle_item_title',
			),
			'required' => 'title',
		) );
		// Render Content.
		$content = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{content}}',
			'attrs'    => array(
				'class' => 'dipl_icircle_item_description',
			),
			'required' => 'content',
		) );
		// Render Button.
		$button = '';
		if ( 'off' !== $this->props['show_button'] && '' !== $this->props['button_url'] ) {
			$button = $this->render_button( array(
				'button_text'         => esc_html( $this->props['button_text'] ),
				'button_text_escaped' => true,
				'has_wrapper'      	  => true,
				'button_classname'    => array( 'dipl_icircle_item_button' ),
				'button_url'          => esc_url( $this->props['button_url'] ),
				'url_new_window'      => esc_attr( $this->props['button_url_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
				'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
			) );
		}

		// Final output.
		$render_output = sprintf(
			'<div class="dipl_interactive_circle_item_wrap">
				<div class="dipl_interactive_circle_item__main">
					%1$s
				</div>
				<div class="dipl_interactive_circle_item__main_content">
					<div class="dipl_interactive_circle_item__main_content_inner">
						%2$s %3$s %4$s %5$s
					</div>
				</div>
			</div>',
			et_core_esc_previously( $info_icon_image ),
			et_core_esc_previously( $content_icon_image ),
			et_core_esc_previously( $title ),
			et_core_esc_previously( $content ),
			et_core_esc_previously( $button )
		);

		// Info circle icon.
		if ( 'on' === $use_info_icon ) {
			$this->generate_styles( array(
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'info_icon',
				'important'      => true,
				'selector'       => '%%order_class%% .dipl_interactive_circle_item__main .et-pb-icon',
				'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
			) );
		}

		// Content Icon/Image style.
		if ( 'on' === $use_content_image ) {
			$content_image_height = et_pb_responsive_options()->get_property_values( $this->props, 'content_image_height' );
			if ( ! empty( array_filter( $content_image_height ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $content_image_height, "%%order_class%% .dipl_icircle_item_image img", 'height', $render_slug, '!important', 'range' );
			}
		} else {
			$this->generate_styles( array(
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'content_icon',
				'important'      => true,
				'selector'       => '%%order_class%% .dipl_icircle_item_image.dipl-used-icon .et-pb-icon',
				'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
			) );
			$content_icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'content_icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $content_icon_fontsize, "{$this->main_css_element} .dipl_icircle_item_image.dipl-used-icon .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'content_icon_color',
				'selector'       => "{$this->main_css_element} .dipl_icircle_item_image.dipl-used-icon .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_icircle_item_image.dipl-used-icon .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}
		// Image/icon background color.
		$this->generate_styles( array(
			'base_attr_name' => 'icon_image_bg_color',
			'selector'       => "{$this->main_css_element} .dipl_icircle_item_image",
			'hover_selector' => "{$this->main_css_element} .dipl_icircle_item_image:hover",
			'important'      => true,
			'css_property'   => 'background-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed  $raw_value Props raw value.
	 * @param array  $args {
	 *     Context data.
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
		if ( $raw_value && ( 'info_icon' === $name || 'content_icon' === $name ) ) {
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
	if ( in_array( 'dipl_interactive_circle', $modules, true ) ) {
		new DIPL_InteractiveCircleItem();
	}
} else {
	new DIPL_InteractiveCircleItem();
}
