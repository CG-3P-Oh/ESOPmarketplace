<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.16.0
 */
class DIPL_ScrollStackCards extends ET_Builder_Module {
	public $slug       = 'dipl_scroll_stack_cards';
	public $child_slug = 'dipl_scroll_stack_cards_item';
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
		$this->name             = esc_html__( 'DP Scroll Stack Cards', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Scroll Stack Cards Item', 'divi-plus' );
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
					'text'         => esc_html__( 'Text', 'divi-plus' ),
					'title_text'   => esc_html__( 'Title Text', 'divi-plus' ),
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
					'icon'      => esc_html__( 'Icon', 'divi-plus' ),
					'image'     => esc_html__( 'Image', 'divi-plus' ),
					'content'   => esc_html__( 'Content Styling', 'divi-plus' ),
					'card_item' => esc_html__( 'Card Item', 'divi-plus' ),
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
						'default'        => '28px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '200',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color' => array(
						'default'  => '#000000',
					),
					'hide_text_align' => true,
					'css' => array(
						'main' => "{$this->main_css_element} .dipl_scroll_stack_cards_title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title_text',
				),
				'content_text' => array(
					'label'     => esc_html__( 'Text', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color' => array(
						'default'  => '#000000',
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_scroll_stack_cards_content, {$this->main_css_element} .dipl_scroll_stack_cards_content p",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'p',
				),
				'content_text_link' => array(
					'label'     => esc_html__( 'Link', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_scroll_stack_cards_content a",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'a',
				),
				'content_text_ul' => array(
					'label'     => esc_html__( 'Unordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_scroll_stack_cards_content ul li",
						'important' => 'all',
					),
					'toggle_slug'    => 'content_text',
					'sub_toggle'     => 'ul',
				),
				'content_text_ol' => array(
					'label'     => esc_html__( 'Ordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_scroll_stack_cards_content ol li",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'ol',
				),
				'content_text_quote' => array(
					'label'     => esc_html__( 'Blockquote', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_scroll_stack_cards_content blockquote",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'quote',
				),
			),
			'borders' => array(
				'image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_image_wrapper img",
							'border_styles' => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_image_wrapper img",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'image',
				),
				'content_wrap' => array(
					'label_prefix' => esc_html__( 'Content Wrap', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_content_wrapper",
							'border_styles' => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_content_wrapper",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'content',
				),
				'card_item' => array(
					'label_prefix' => esc_html__( 'Card Item', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_scroll_stack_cards_item",
							'border_styles' => "{$this->main_css_element} .dipl_scroll_stack_cards_item",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'card_item',
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
				'image' => array(
					'label'       => esc_html__( 'Image Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_image_wrapper img',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image',
				),
				'card_item' => array(
					'label'       => esc_html__( 'Card Item Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl_scroll_stack_cards_item',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'card_item',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'scroll_stack_cards_spacing' => array(
				'image_icon' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_image_wrapper",
							'important'  => 'all',
						),
					),
				),
				'content_wrap' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_content_wrapper",
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
					'text_orientation' => "{$this->main_css_element}",
					'important' => 'all',
				)
			),
			'link_options' => false,
			'background'   => array(
				'use_background_video' => false,
				'css' => array(
					'main' => '%%order_class%% .dipl-scroll-stack-cards-items',
				),
			),
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'js_init_notice' => array(
					'label'           => '',
					'type'            => 'warning',
					'option_category' => 'configuration',
					'toggle_slug'     => 'display',
					'value'           => true,
					'display_if'      => true,
					'message'         => esc_html__( 'Cards scrolling does not work in the Visual Builder. Please check the frontend to see the effect correctly.', 'divi-plus' )
				),
				'layout' => array(
					'label'        	  => esc_html__( 'Layout', 'divi-plus' ),
					'type'        	  => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'vertical'   => esc_html__( 'Vertical', 'divi-plus' ),
						'horizontal' => esc_html__( 'Horizontal', 'divi-plus' ),
					),
					'default'         => 'vertical',
					'toggle_slug'     => 'display',
					'description'  	  => esc_html__( 'Here you can choose the layout for the cards.', 'divi-plus' ),
				),
				// 'animation_start_element_pos' => array(
				// 	'label'           => esc_html__( 'Animation Start Element', 'divi-plus' ),
				// 	'type'            => 'range',
				// 	'option_category' => 'layout',
				// 	'range_settings'  => array(
				// 		'min'  => '1',
				// 		'max'  => '100',
				// 		'step' => '1',
				// 	),
				// 	'default'         => '0%',
				// 	'default_unit'    => '%',
				// 	'allowed_units'   => array( '%', 'px' ),
				// 	'toggle_slug'     => 'display',
				// 	'description'     => esc_html__( 'Increase or decrease the element\'s start position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
				// ),
				'animation_start_viewport_pos' => array(
					'label'           => esc_html__( 'Animation Start Viewport', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '1',
						'max'  => '100',
						'step' => '1',
					),
					'default'         => '0%',
					'default_unit'    => '%',
					'allowed_units'   => array( '%', 'px' ),
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Increase or decrease the viewport\'s start position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
				),
				'collapsed_width' => array(
					'label'            => esc_html__( 'Collapsed Width', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '10',
						'max'  => '500',
						'step' => '1',
					),
					'show_if'          => array(
						'layout' => 'horizontal',
					),
					'default'          => '200px',
					'default_on_front' => '200px',
					'default_unit'     => 'px',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease the card item width after collapsed.', 'divi-plus' ),
				),
				'icon_size' => array(
					'label'           => esc_html__( 'Icon size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '1',
						'max'  => '250',
						'step' => '1',
					),
					'default'         => '40px',
					'mobile_options'  => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease size of the icon.', 'divi-plus' ),
				),
				'icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'      => '',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'icon',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for icon.', 'divi-plus' ),
				),
				'image_icon_custom_padding' => array(
					'label'            => esc_html__( 'Image Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '20px|20px|20px|20px|on|on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'image',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'image_size' => array(
					'label'            => esc_html__( 'Image size', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '100',
						'step' => '1',
					),
					'show_if'          => array(
						'layout' => 'vertical',
					),
					'default'          => '30%',
					'default_on_front' => '30%',
					'default_unit'     => '%',
					'allowed_units'    => array( '%', 'px' ),
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'image',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease size of the icon.', 'divi-plus' ),
				),
				'content_wrap_bg_color' => array(
	                'label'                 => esc_html__( 'Content Wrap Background', 'divi-plus' ),
	                'type'                  => 'background-field',
	                'base_name'             => 'content_wrap_bg',
	                'context'               => 'content_wrap_bg_color',
	                'option_category'       => 'button',
	                'custom_color'          => true,
	                'background_fields'     => $this->generate_background_options( 'content_wrap_bg', 'button', 'advanced', 'background', 'content_wrap_bg_color' ),
	                'hover'                 => 'tabs',
	                'mobile_options'        => true,
	                'tab_slug'              => 'advanced',
	                'toggle_slug'           => 'content',
	                'description'           => esc_html__( 'Customize the background style of the card item content wrap by adjusting the background color, gradient, and image.', 'divi-plus' ),
	            ),
				'content_wrap_custom_padding' => array(
					'label'            => esc_html__( 'Content Wrap Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '20px|20px|20px|20px|on|on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'content',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'content_margin_right' => array(
					'label'            => esc_html__( 'Content Margin Right', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '200',
						'step' => '1',
					),
					'show_if'          => array(
						'layout' => 'vertical',
					),
					'default'          => '20px',
					'default_on_front' => '20px',
					'default_unit'     => 'px',
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'content',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease size of the icon.', 'divi-plus' ),
				),
				'card_item_width' => array(
					'label'            => esc_html__( 'Card Item Width', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'layout',
					'range_settings'   => array(
						'min'  => '100',
						'max'  => '1000',
						'step' => '1',
					),
					'show_if'          => array(
						'layout' => 'horizontal',
					),
					'default'          => '400px',
					'default_on_front' => '400px',
					'default_unit'     => 'px',
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'card_item',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease the card item width.', 'divi-plus' ),
				),
			),
			$this->generate_background_options( 'content_wrap_bg', 'skip', 'advanced', 'content', 'content_wrap_bg_color' ),
		);
	}

	public function before_render() {
		global $dp_scroll_stack_cards_parent_attrs;

		$dp_scroll_stack_cards_parent_attrs = array(
			'layout' => $this->props['layout'] ?? 'vertical'
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
        wp_enqueue_style( 'dipl-scroll-stack-cards-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ScrollStackCards/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'elicus-scroll-trigger-script' );
		wp_enqueue_script( 'elicus-gsap-script' );
		wp_enqueue_script( 'dipl-scroll-stack-cards-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/ScrollStackCards/dipl-scroll-stack-cards-custom.min.js", array('jquery'), '1.0.0', true );

		// Get attributes.
		$layout = $this->props['layout'] ?? 'vertical';

		// Get props.
		$data_props = array(
			// 'animation_start_element_pos',
			'animation_start_viewport_pos'
		);
		if ( 'horizontal' === $layout ) {
			$data_props[] = 'collapsed_width';
		}
		$data_atts = $this->props_to_html_data_attrs( $data_props );

		// Render final output.
		$render_output = sprintf(
			'<div class="dipl-scroll-stack-cards-wrapper layout-%2$s" data-layout="%2$s" %3$s>
				<div class="dipl-scroll-stack-cards-items">%1$s</div>
			</div>',
			et_core_intentionally_unescaped( $this->content, 'html' ),
			esc_attr( $layout ),
			et_core_esc_previously( $data_atts )
		);

		// Icon style.
		$this->generate_styles( array(
			'base_attr_name' => 'icon_color',
			'selector'       => '%%order_class%% .dipl_scroll_stack_cards_icon_wrapper .et-pb-icon',
			'hover_selector' => '%%order_class%% .dipl_scroll_stack_cards_icon_wrapper .et-pb-icon:hover',
			'important'      => true,
			'css_property'   => 'color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );
		$icon_size = et_pb_responsive_options()->get_property_values( $this->props, 'icon_size' );
		if ( ! empty( array_filter( $icon_size ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $icon_size, '%%order_class%% .dipl_scroll_stack_cards_icon_wrapper .et-pb-icon', 'font-size', $render_slug, '!important;', 'range' );
		}

		// Style for vertical layout.
		if ( 'vertical' === $layout ) {
			// Image size.
			$image_size = et_pb_responsive_options()->get_property_values( $this->props, 'image_size' );
			foreach ( $image_size as &$size ) {
				$size = ! empty( $size ) ? sprintf( '0 0 %1$s', $size ) : '';
			}
			if ( ! empty( array_filter( $image_size ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $image_size, '%%order_class%% .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_image_wrapper', 'flex', $render_slug, '!important;', '' );
			}

			// Content margin right.
			$content_margin_right = et_pb_responsive_options()->get_property_values( $this->props, 'content_margin_right' );
			if ( ! empty( array_filter( $content_margin_right ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $content_margin_right, '%%order_class%% .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_content_wrapper', 'margin-right', $render_slug, '!important;', 'range' );
			}
		} elseif ( 'horizontal' === $layout ) {
			// Card item width.
			$card_item_width = et_pb_responsive_options()->get_property_values( $this->props, 'card_item_width' );
			if ( ! empty( array_filter( $card_item_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $card_item_width, '%%order_class%% .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_item_inner', 'width', $render_slug, '!important;', 'range' );
			}
		}

		$args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'content_wrap_bg' => array(
					'normal' => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_content_wrapper",
					'hover'  => "{$this->main_css_element} .dipl-scroll-stack-cards-wrapper .dipl_scroll_stack_cards_content_wrapper:hover",
	 			),
			),
		);
		DiviPlusHelper::process_background( $args );

		$fields = array( 'scroll_stack_cards_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_scroll_stack_cards', $modules ) ) {
		new DIPL_ScrollStackCards();
	}
} else {
	new DIPL_ScrollStackCards();
}
