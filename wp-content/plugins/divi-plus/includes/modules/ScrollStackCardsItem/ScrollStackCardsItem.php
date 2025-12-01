<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.16.0
 */
class DIPL_ScrollStackCardsItem extends ET_Builder_Module {
	public $slug       = 'dipl_scroll_stack_cards_item';
	public $type       = 'child';
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
		$this->name                        = esc_html__( 'DP Scroll Stack Cards Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Scroll Stack Cards Item', 'divi-plus' );
		$this->child_title_var             = 'title';
		$this->main_css_element            = '.dipl_scroll_stack_cards %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'icon'         => esc_html__( 'Icon', 'divi-plus' ),
					'button'       => esc_html__( 'Button', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'title'  => esc_html__( 'Title', 'divi-plus' ),
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
					'icon'   => esc_html__( 'Icon', 'divi-plus' ),
					'button' => esc_html__( 'Button', 'divi-plus' ),
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
					'header_level' => array(
						'default'  => 'h2',
					),
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_scroll_stack_cards_title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title',
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
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'divi-plus' ),
					'css' => array(
						'main'      => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
						'alignment' => "{$this->main_css_element} .et_pb_button_wrapper",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .et_pb_button_wrapper",
							'padding'   => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => true,
					'box_shadow'      => false,
					'depends_on'      => array( 'show_button' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'button',
				),
			),
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element}",
							'border_radii'  => "{$this->main_css_element}",
						),
					),
				)
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element}",
					),
				)
			),
			'margin_padding' => array(
				'custom_padding' => array(
					'default_on_front' => '20px|20px|20px|20px|true|true',
				),
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'max_width'  => false,
			'height'     => false,
			'text'       => false,
			'background' => array(
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'default'          => esc_html__( 'Your title goes here.', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Your title goes here.', 'divi-plus' ),
				'description'      => esc_html__( 'Enter the title to display on your card item.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'divi-plus' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Enter the content to display on your card item.', 'divi-plus' ),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'affects'            => array( 'alt' ),
				'dynamic_content'  	 => 'image',
				'description'        => esc_html__( 'Here you can upload the image to display on the card item.', 'divi-plus' ),
				'toggle_slug'        => 'main_content',
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image Alternative Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'on',
				'depends_on'      => array( 'image' ),
				'description'     => esc_html__( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'divi-plus' ),
				'toggle_slug'     => 'main_content',
				'dynamic_content' => 'text',
			),
			'show_icon' => array(
				'label'     	  => esc_html__( 'Show Icon', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'icon',
				'description'     => esc_html__( 'Here you can choose whether show or hide the card icon.', 'divi-plus' ),
			),
			'icon' => array(
				'label'           => esc_html__( 'Select Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'show_icon'    => 'on',
				),
				'default'         => '',
				'toggle_slug'     => 'icon',
				'description'     => esc_html__( 'Here you can select the icon to be display above the title.', 'divi-plus' ),
			),
			'show_button' => array(
				'label'     	  => esc_html__( 'Show Button', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'button',
				'description'     => esc_html__( 'Here you can choose whether or not show the button.', 'divi-plus' ),
			),
			'button_text' => array(
				'label'            => esc_html__( 'Button Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'show_if'          => array( 'show_button' => 'on' ),
				'default'          => esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'      => 'button',
				'description'      => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
			),
			'button_url' => array(
				'label'           => esc_html__( 'Button Link URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array( 'show_button' => 'on' ),
				'dynamic_content' => 'url',
				'toggle_slug'     => 'button',
				'description'  	  => esc_html__( 'Here you can input the destination URL for the button to open when clicked.', 'divi-plus' ),
			),
			'button_new_window' => array(
				'label'        	  => esc_html__( 'Button Link Target', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'show_if'         => array( 'show_button' => 'on' ),
				'options'         => array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'toggle_slug'     => 'button',
				'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
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
				'default'         => '',
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

		global $dp_scroll_stack_cards_parent_attrs;

		$layout      = $dp_scroll_stack_cards_parent_attrs['layout'] ?? 'vertical';

		$multi_view  = et_pb_multi_view_options( $this );

		$title_level = et_pb_process_header_level( $this->props['title_level'], 'h2' );
		$show_icon   = esc_attr( $this->props['show_icon'] ) ?? 'off';
		$show_button = esc_attr( $this->props['show_button'] );
		$button_url  = esc_url( $this->props['button_url'] );

		// Render icon.
		$render_icon = '';
		if ( 'on' === $show_icon ) {
			$render_icon = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array( 'class' => 'et-pb-icon' ),
				'required' => 'icon',
			) );
			if ( ! empty( $render_icon ) ) {
				$render_icon = sprintf( '<div class="dipl_scroll_stack_cards_icon_wrapper">%1$s</div>', et_core_esc_previously( $render_icon ) );
			}
		}

		// Title.
		$card_title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_scroll_stack_cards_title'
			),
			'required' => 'title',
		) );
		// content.
		$content = '';
		if ( ! empty( $this->content ) ) {
			$content = sprintf(
				'<div class="dipl_scroll_stack_cards_content">%1$s</div>',
				wp_kses_post( $this->content )
			);
		}
		// Button.
		$render_button = '';
		if ( 'on' === $show_button && ! empty( $button_url ) ) {
			$render_button = $this->render_button( array(
				'button_text'         => esc_attr( $this->props['button_text'] ),
				'button_text_escaped' => false,
				'has_wrapper'      	  => true,
				'button_url'          => esc_url( $button_url ),
				'url_new_window'      => esc_attr( $this->props['button_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
				'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
			) );
		}

		// Render image.
		$image = $multi_view->render_element( array(
			'tag'      => 'img',
			'attrs'    => array(
				'src'   => '{{image}}',
				'alt'   => '{{image_alt}}',
				'class' => 'et_pb_image dipl_scroll_stack_cards_image'
			),
			'required' => 'image',
		) );
		if ( ! empty( $image ) ) {
			$image = sprintf(
				'<div class="dipl_scroll_stack_cards_image_wrapper">%1$s</div>',
				et_core_esc_previously( $image )
			);
		}

		// Final output.
		$render_output = '';
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' );
		}

		// Icon style.
		if ( 'on' === $show_icon ) {
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => "{$this->main_css_element} .dipl_scroll_stack_cards_icon_wrapper .et-pb-icon",
				'hover_selector' => "{$this->main_css_element}.dipl_scroll_stack_cards_icon_wrapper .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$icon_size = et_pb_responsive_options()->get_property_values( $this->props, 'icon_size' );
			if ( ! empty( array_filter( $icon_size ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $icon_size, "{$this->main_css_element} .dipl_scroll_stack_cards_icon_wrapper .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			}
		}

		self::$rendering = false;
		return $render_output;
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed   $raw_value Props raw value.
	 * @param array   $args {
	 *     Context data.
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
	if ( in_array( 'dipl_scroll_stack_cards', $modules ) ) {
		new DIPL_ScrollStackCardsItem();
	}
} else {
	new DIPL_ScrollStackCardsItem();
}
