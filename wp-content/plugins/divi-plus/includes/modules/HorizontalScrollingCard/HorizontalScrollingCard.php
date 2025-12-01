<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.15.0
 */
class DIPL_HorizontalScrollingCard extends ET_Builder_Module {
	public $slug       = 'dipl_horizontal_scrolling_card';
	public $child_slug = 'dipl_horizontal_scrolling_card_item';
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
		$this->name             = esc_html__( 'DP Horizontal Scrolling Card', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Horizontal Scrolling Card Item', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'display' => esc_html__( 'Display', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'text'       => esc_html__( 'Text', 'divi-plus' ),
					'card_text'  => array(
						'title'             => esc_html__( 'Card Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'       => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'description' => array( 'name' => esc_html__( 'Description', 'divi-plus' ) )
						),
					),
					'tag'          => esc_html__( 'Tag', 'divi-plus' ),
					'card_item'    => esc_html__( 'Card Item', 'divi-plus' ),
					'card_content' => esc_html__( 'Card Content', 'divi-plus' ),
					'card_image'   => esc_html__( 'Card Image', 'divi-plus' ),
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
						'default'        => '22px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'card_text',
					'sub_toggle'  => 'title',
				),
				'description' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_description",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'card_text',
					'sub_toggle'  => 'description',
				),
				'tag' => array(
					'label'     => esc_html__( 'Tag', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color' => array(
						'default' => '#ffffff',
					),
					'hide_text_align' => true,
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_tag_wrapper .dipl_horizontal_scrolling_card_tag",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'tag',
				),
			),
			'borders' => array(
				'card_item' => array(
					'label_prefix' => esc_html__( 'Card', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper',
							'important' => 'all',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'card_item',
				),
				'card_content' => array(
					'label_prefix' => esc_html__( 'Content', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_card_content_wrapper',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_card_content_wrapper',
							'important' => 'all',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'card_content',
				),
				'card_image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_image',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_image',
							'important' => 'all',
						),
					),
					'depends_on'      => array( 'layout' ),
					'depends_show_if' => 'layout1',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'card_image',
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
				'card_item' => array(
					'label'       => esc_html__( 'Card Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'card_item',
				),
				'card_image' => array(
					'label'       => esc_html__( 'Image Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_image',
						'important' => 'all',
					),
					'depends_on'      => array( 'layout' ),
					'depends_show_if' => 'layout1',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'card_image',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'sticky_card_margin_padding' => array(
				'card_item' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .layout1 .dipl_horizontal_scrolling_card_wrapper, {$this->main_css_element} .layout2 .dipl_horizontal_scrolling_card_inner",
							'important'  => 'all',
						),
					),
				),
				'card_content' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_horizontal_scrolling_card_content_wrapper",
							'important'  => 'all',
						),
					),
				),
				'card_image' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_image",
							'important'  => 'all',
						),
					),
				),
				'tag' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_tag",
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
			'text' => array(
				'text_orientation' => array(),
				'use_background_layout' => true,
				'options'               => array(
					'text_orientation'  => array(
						'default'           => 'left',
						'default_on_front'  => 'left',
					),
					'background_layout' => array(
                        'default'           => 'light',
						'default_on_front'  => 'light',
						'hover'             => 'tabs',
					)
				),
				'css' => array(
                    'text_orientation' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_content_wrapper",
                    'important' => 'all',
                )
			),
			'filters'      => false,
			'link_options' => false,
			'background' => array(
				'use_background_video' => false,
				'css' => array(
					'main' => '%%order_class%% .dipl-sticky-cards-scroller',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'js_init_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'configuration',
				'toggle_slug'     => 'display',
				'value'           => true,
				'display_if'      => true,
				'message'         => esc_html__( 'Horizontal scrolling does not work in the Visual Builder. Please check the frontend to see the effect correctly.', 'divi-plus' )
			),
			'layout' => array(
				'label'        	  => esc_html__( 'Layout', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
					'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
				),
				'default'         => 'layout1',
				'toggle_slug'     => 'display',
				'description'  	  => esc_html__( 'Here you can choose the layout for the cards.', 'divi-plus' ),
			),
			'space_between_cards' => array(
				'label'           => esc_html__( 'Space Between Cards', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '150',
					'step' => '1',
				),
				'default'         => '20px',
				'default_unit'    => 'px',
				'allowed_units'   => array( 'px' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Increase or decrease the space between the cards.', 'divi-plus' ),
			),
			'animation_start_element_pos' => array(
				'label'           => esc_html__( 'Animation Start Element', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '50%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Increase or decrease the element\'s start position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'animation_start_viewport_pos' => array(
				'label'           => esc_html__( 'Animation Start Viewport', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '50%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Increase or decrease the viewport\'s start position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'tag_bg_color' => array(
				'label'        	   => esc_html__( 'Tag Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '#000000',
				'default_on_front' => '#000000',
				'hover'            => 'tabs',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'tag',
				'description'      => esc_html__( 'Here you can select the backround color for the image overlay.', 'divi-plus' ),
			),
			'tag_custom_padding' => array(
				'label'            => esc_html__( 'Tag Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '5px|5px|5px|5px|true|true',
				'default_on_front' => '5px|5px|5px|5px|true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'tag',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'card_item_bg_color' => array(
				'label'            => esc_html__( 'Card Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '#f0f0f0',
				'default_on_front' => '#f0f0f0',
				'hover'            => 'tabs',
				'show_if'          => array( 'layout' => 'layout1' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'card_item',
				'description'      => esc_html__( 'Select the card background color.', 'divi-plus' ),
			),
			'card_overlay_color' => array(
				'label'            => esc_html__( 'Card Overlay Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => 'rgba(0, 0, 0, 0.28)',
				'default_on_front' => 'rgba(0, 0, 0, 0.28)',
				'hover'            => 'tabs',
				'show_if'          => array( 'layout' => 'layout2' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'card_item',
				'description'      => esc_html__( 'Select the card overlay background color.', 'divi-plus' ),
			),
			'card_item_custom_padding' => array(
				'label'            => esc_html__( 'Card Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '20px|20px|20px|20px|true|true',
				'default_on_front' => '20px|20px|20px|20px|true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'card_item',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'card_width' => array(
				'label'           => esc_html__( 'Card Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'default'         => '300px',
				'default_unit'    => 'px',
				'allowed_units'   => array( 'px' ),
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'card_item',
				'description'     => esc_html__( 'Increase or decrease the element\'s position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'card_height' => array(
				'label'           => esc_html__( 'Card Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'default'         => '500px',
				'default_unit'    => 'px',
				'allowed_units'   => array( 'px' ),
				'show_if'         => array( 'layout' => 'layout2' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'card_item',
				'description'     => esc_html__( 'Increase or decrease the element\'s position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'card_content_custom_padding' => array(
				'label'            => esc_html__( 'Content Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'card_content',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'card_content_bg_color' => array(
				'label'            => esc_html__( 'Content Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '',
				'hover'            => 'tabs',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'card_content',
				'description'      => esc_html__( 'Select the card content background color.', 'divi-plus' ),
			),
			'card_image_custom_padding' => array(
				'label'            => esc_html__( 'Image Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'show_if'          => array( 'layout' => 'layout1' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'card_image',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'enable_image_height' => array(
				'label'     	  => esc_html__( 'Enable Custom Height', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'tab_slug'        => 'advanced',
				'show_if'         => array( 'layout' => 'layout1' ),
				'toggle_slug'     => 'card_image',
				'description'     => esc_html__( 'Here you can choose whether enable the custom height for image or not.', 'divi-plus' ),
			),
			'image_height' => array(
				'label'           => esc_html__( 'Image Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'mobile_options'  => 'true',
				'validate_unit'   => true,
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'height' ),
				'default'         => 'auto',
				'default_unit'    => 'px',
				'show_if'         => array( 'layout' => 'layout1', 'enable_image_height' => 'on' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'card_image',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease the image height.', 'divi-plus' ),
			),
		);
	}

	public function before_render() {
		global $dp_sticky_card_parent_attrs;

		$dp_sticky_card_parent_attrs = array(
			'layout' => $this->props['layout'] ?? 'layout1'
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
        wp_enqueue_style( 'dipl-sticky-card-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/HorizontalScrollingCard/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'elicus-scroll-trigger-script' );
		wp_enqueue_script( 'elicus-gsap-script' );
		wp_enqueue_script( 'dipl-sticky-card-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/HorizontalScrollingCard/dipl-horizontal-scrolling-card-custom.min.js", array('jquery'), '1.0.0', true );

		$layout = $this->props['layout'] ?? 'layout1';

		// Get props.
		$data_props = array(
			'animation_start_element_pos',
			'animation_start_viewport_pos'
		);
		$data_atts = $this->props_to_html_data_attrs( $data_props );

		// Render final output.
		$render_output = sprintf(
			'<div class="dipl-sticky-cards-scroller">
				<div class="dipl-sticky-cards-wrapper %1$s" %2$s>
					<div class="dipl-sticky-cards-inner">%3$s</div>
				</div>
			</div>',
			et_core_esc_previously( $layout ),
			et_core_esc_previously( $data_atts ),
			et_core_intentionally_unescaped( $this->content, 'html' )
		);

		// Space between cards.
		$space_between_cards = et_pb_responsive_options()->get_property_values( $this->props, 'space_between_cards' );
		if ( ! empty( array_filter( $space_between_cards ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $space_between_cards, '%%order_class%% .dipl_horizontal_scrolling_card_item:not(:last-child) .dipl_horizontal_scrolling_card_wrapper', 'margin-right', $render_slug, '!important;', 'range' );
		}

		// Tag style.
		$this->generate_styles( array(
			'base_attr_name' => 'tag_bg_color',
			'selector'       => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_tag',
			'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper:hover .dipl_horizontal_scrolling_card_tag',
			'important'      => true,
			'css_property'   => 'background-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		// layout one style.
		if ( 'layout1' === $layout ) {
			$this->generate_styles( array(
				'base_attr_name' => 'card_item_bg_color',
				'selector'       => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper',
				'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper:hover',
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Image style.
			if ( 'on' === $this->props['enable_image_height'] ) {
				// Card height.
				$image_height = et_pb_responsive_options()->get_property_values( $this->props, 'image_height' );
				if ( ! empty( array_filter( $image_height ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $image_height, '%%order_class%% .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_image', 'height', $render_slug, '!important;', 'range' );
				}
			}
		} elseif ( 'layout2' === $layout ) {
			$this->generate_styles( array(
				'base_attr_name' => 'card_overlay_color',
				'selector'       => '%%order_class%% .dipl_horizontal_scrolling_card_inner::before',
				'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_card_inner:hover::before',
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Card height.
			$card_height = et_pb_responsive_options()->get_property_values( $this->props, 'card_height' );
			if ( ! empty( array_filter( $card_height ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $card_height, '%%order_class%% .layout2 .dipl_horizontal_scrolling_card_wrapper', 'min-height', $render_slug, '!important;', 'range' );
			}
		}

		// Card width.
		$card_width = et_pb_responsive_options()->get_property_values( $this->props, 'card_width' );
		if ( ! empty( array_filter( $card_width ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $card_width, '%%order_class%% .dipl_horizontal_scrolling_card_wrapper', 'width', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $card_width, '%%order_class%% .dipl_horizontal_scrolling_card_wrapper', 'min-width', $render_slug, '!important;', 'range' );
		}

		// Card Content.
		$this->generate_styles( array(
			'base_attr_name' => 'card_content_bg_color',
			'selector'       => '%%order_class%% .dipl_horizontal_scrolling_card_content_wrapper',
			'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_card_wrapper:hover .dipl_horizontal_scrolling_card_content_wrapper',
			'important'      => true,
			'css_property'   => 'background-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		$fields = array( 'sticky_card_margin_padding' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		// Add text classes.
		$background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class( $this->props );
		$this->add_classname( array(
			$this->get_text_orientation_classname(),
			$background_layout_class_names[0]
		) );

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_horizontal_scrolling_card', $modules ) ) {
		new DIPL_HorizontalScrollingCard();
	}
} else {
	new DIPL_HorizontalScrollingCard();
}
