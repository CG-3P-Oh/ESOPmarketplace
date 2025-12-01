<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */
class DIPL_AccordionSliderItem extends ET_Builder_Module {

	public $slug       = 'dipl_accordion_slider_item';
	public $type       = 'child';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name 						= esc_html__( 'DP Accordion Slider Item', 'divi-plus' );
		$this->advanced_setting_title_text  = esc_html__( 'Accordion Slider Item', 'divi-plus' );
		$this->child_title_var              = 'title';
		$this->main_css_element 			= '.dipl_accordion_slider %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'button'       => esc_html__( 'Button', 'divi-plus' ),
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
					'button'     => esc_html__( 'Button', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'          => esc_html__( 'Title', 'divi-plus' ),
					'font_size'      => array(
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
					'header_level' => array(
						'default' => 'h4',
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl_accordion_slider_item_title",
						'hover'      => "{$this->main_css_element} .dipl_accordion_slider_item_title:hover",
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
					'use_alignment'   => true,
					'box_shadow'      => true,
					'depends_on'      => array( 'show_button' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'button',
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
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element} .dipl_accordion_slider_item_wrapper",
							'border_radii'  => "{$this->main_css_element} .dipl_accordion_slider_item_wrapper",
						),
						'important' => 'all',
					),
				),
			),
			'margin_padding' => array(
				// 'custom_padding' => array(
				// 	'default_on_front' => '20px|20px|20px|20px|true|true',
				// ),
				'css' => array(
					'margin'    => "{$this->main_css_element} .dipl_accordion_slider_item_wrapper",
					'padding'   => "{$this->main_css_element} .dipl_accordion_slider_item_wrapper",
					'important' => 'all',
				),
			),
			'text_shadow' => false,
			'max_width' => false,
			'height' => false,
			'background' => array(
				'use_background_video' => false,
				'css' => array(
					'main' => "{$this->main_css_element} .dipl_accordion_slider_item_content",
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'image' => array(
				'label'              => esc_html__( 'Slide Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'default'			 => 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTA4MCIgaGVpZ2h0PSI1NDAiIHZpZXdCb3g9IjAgMCAxMDgwIDU0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPHBhdGggZmlsbD0iI0VCRUJFQiIgZD0iTTAgMGgxMDgwdjU0MEgweiIvPgogICAgICAgIDxwYXRoIGQ9Ik00NDUuNjQ5IDU0MGgtOTguOTk1TDE0NC42NDkgMzM3Ljk5NSAwIDQ4Mi42NDR2LTk4Ljk5NWwxMTYuMzY1LTExNi4zNjVjMTUuNjItMTUuNjIgNDAuOTQ3LTE1LjYyIDU2LjU2OCAwTDQ0NS42NSA1NDB6IiBmaWxsLW9wYWNpdHk9Ii4xIiBmaWxsPSIjMDAwIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz4KICAgICAgICA8Y2lyY2xlIGZpbGwtb3BhY2l0eT0iLjA1IiBmaWxsPSIjMDAwIiBjeD0iMzMxIiBjeT0iMTQ4IiByPSI3MCIvPgogICAgICAgIDxwYXRoIGQ9Ik0xMDgwIDM3OXYxMTMuMTM3TDcyOC4xNjIgMTQwLjMgMzI4LjQ2MiA1NDBIMjE1LjMyNEw2OTkuODc4IDU1LjQ0NmMxNS42Mi0xNS42MiA0MC45NDgtMTUuNjIgNTYuNTY4IDBMMTA4MCAzNzl6IiBmaWxsLW9wYWNpdHk9Ii4yIiBmaWxsPSIjMDAwIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz4KICAgIDwvZz4KPC9zdmc+Cg==',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can upload the image for accordion slide.', 'divi-plus' ),
			),
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can define the title which will appear in the active state.', 'divi-plus' ),
			),
			'content' => array(
				'label'           => esc_html__( 'Description', 'divi-plus' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can define the content which will appear in the active state.', 'divi-plus' ),
			),
			'use_icon' => array(
				'label'           => esc_html__( 'Show Icon', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'affects'         => array( 'icon' ),
				'default'         => 'off',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can choose whether or not use the button.', 'divi-plus' ),
			),
			'icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Choose an icon to display with the accordion.', 'divi-plus' ),
			),
			'show_button' => array(
				'label'     		=> esc_html__( 'Show Button', 'divi-plus' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'basic_option',
				'options'           => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'affects' 			=> array( 'custom_button' ),
				'default'           => 'off',
				'toggle_slug'       => 'button',
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
				'toggle_slug'       => 'button',
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
				'toggle_slug'     	=> 'button',
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
				'toggle_slug'      	=> 'button',
				'description'      	=> esc_html__( 'Here you can choose whether or not your link opens in a new window', 'divi-plus' ),
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
				'mobile_options'   => true,
				'show_if'          => array(
					'show_button'  => 'on',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon_style',
				'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'          	=> esc_html__( 'Icon Color', 'divi-plus' ),
				'type'            	=> 'color-alpha',
				'hover'           	=> 'tabs',
				'mobile_options'  	=> true,
				'show_if'           => array(
					'show_button'   => 'on',
				),
				'tab_slug'        	=> 'advanced',
				'toggle_slug'     	=> 'icon_style',
				'description'     	=> esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'animation' => array(
				'label'            => esc_html__( 'Accordion Content Animation', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'top'    => esc_html__( 'Top To Bottom', 'divi-plus' ),
					'left'   => esc_html__( 'Left To Right', 'divi-plus' ),
					'right'  => esc_html__( 'Right To Left', 'divi-plus' ),
					'bottom' => esc_html__( 'Bottom To Top', 'divi-plus' ),
					'off'    => esc_html__( 'No Animation', 'divi-plus' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'animation',
				'description'      => esc_html__( 'This controls the direction of the lazy-loading accordion content animation.', 'divi-plus' ),
			),
		);

	}

	public function render( $attrs, $content, $render_slug ) {

		$multi_view  = et_pb_multi_view_options( $this );

		$use_icon    = sanitize_text_field( $this->props['use_icon'] ) ?? 'off';
		$title_level = et_pb_process_header_level( $this->props['title_level'], 'h4' );
		$title_level = esc_html( $title_level );
		$animation   = sanitize_text_field( $this->props['animation'] );

		// Slide Image.
		$slide_image = $multi_view->render_element( array(
			'tag'      => 'img',
			'attrs'    => array(
				'src'   => '{{image}}',
				'alt'   => '{{image_alt}}',
				'class' => 'dipl_accordion_slider_item_image',
			),
			'required' => 'image',
		) );

		// Render Icon.
		$icon = '';
		if ( 'on' === $use_icon ) {
			$icon = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array(
					'class' => 'et-pb-icon',
				),
				'required' => 'icon',
			) );

			// Not empty, wrap it.
			if ( ! empty( $icon ) ) {
				$icon = sprintf(
					'<div class="dipl_accordion_slider_item_icon">%1$s</div>',
					et_core_esc_previously( $icon )
				);
			}
		}

		// Render title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_accordion_slider_item_title',
			),
			'required' => 'title',
		) );
		// Render Content.
		$content = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{content}}',
			'attrs'    => array(
				'class' => 'dipl_accordion_slider_item_description',
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
				'button_url'          => esc_url( $this->props['button_url'] ),
				'url_new_window'      => esc_attr( $this->props['button_url_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
				'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
			) );
		}

		// Content wrapper.
		$content_wrapper = '';
		if ( ! empty( $icon ) || ! empty( $title ) || ! empty( $content ) || ! empty( $button ) ) {
			$content_wrapper = sprintf(
				'<div class="dipl_accordion_slider_item_content">
					<div class="dipl_accordion_slider_item_content_inner et_pb_animation_%1$s">%2$s%3$s%4$s%5$s</div>
				</div>',
				esc_attr( $animation ),
				et_core_esc_previously( $icon ),
				et_core_esc_previously( $title ),
				et_core_esc_previously( $content ),
				et_core_esc_previously( $button )
			);
		}

		// Final rendering.
		$render_output = sprintf(
			'<div class="dipl_accordion_slider_item_wrapper">
				<div class="dipl_accordion_slider_item_inner">
					%1$s %2$s
				</div>
			</div>',
			et_core_intentionally_unescaped( $slide_image, 'html' ),
			et_core_intentionally_unescaped( $content_wrapper, 'html' )
		);

		// Icon style.
		if ( 'on' === $use_icon ) {
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && 
				method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' )
			) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => '%%order_class%% .dipl_accordion_slider_item_icon .et-pb-icon',
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					) )
				);
			}
			$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, "{$this->main_css_element} .dipl_accordion_slider_item_icon .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => "{$this->main_css_element} .dipl_accordion_slider_item_icon .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_accordion_slider_item_icon .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		// Add module classes.
		$background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class( $this->props );
		$this->add_classname( array(
			$this->get_text_orientation_classname(),
			$background_layout_class_names[0],
			'dipl_accordion_slider_slide',
			'swiper-slide'
		) );

		return et_core_intentionally_unescaped( $render_output, 'html' );
	}

	protected function _render_module_wrapper( $output = '', $render_slug = '' ) {
		$wrapper_settings    = $this->get_wrapper_settings( $render_slug );
		$slug                = $render_slug;
		$outer_wrapper_attrs = $wrapper_settings['attrs'];

		/**
		 * Filters the HTML attributes for the module's outer wrapper. The dynamic portion of the
		 * filter name, '$slug', corresponds to the module's slug.
		 *
		 * @since 3.23 Add support for responsive video background.
		 * @since 3.1
		 *
		 * @param string[]           $outer_wrapper_attrs
		 * @param ET_Builder_Element $module_instance
		 */
		$outer_wrapper_attrs = apply_filters( "et_builder_module_{$slug}_outer_wrapper_attrs", $outer_wrapper_attrs, $this );

		return sprintf(
			'<div%1$s>
				%2$s
				%3$s
				%4$s
				%5$s
				%6$s
			</div>',
			et_html_attrs( $outer_wrapper_attrs ),
			$wrapper_settings['parallax_background'],
			$wrapper_settings['video_background'],
			et_()->array_get( $wrapper_settings, 'video_background_tablet', '' ),
			et_()->array_get( $wrapper_settings, 'video_background_phone', '' ),
			$output
		);
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
	if ( in_array( 'dipl_accordion_slider', $modules, true ) ) {
		new DIPL_AccordionSliderItem();
	}
} else {
	new DIPL_AccordionSliderItem();
}
