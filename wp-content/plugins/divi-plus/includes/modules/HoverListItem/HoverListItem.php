<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_HoverListItem extends ET_Builder_Module {
	public $slug       = 'dipl_hover_list_item';
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
		$this->name                        = esc_html__( 'DP Hover List Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Hover List Item', 'divi-plus' );
		$this->child_title_var             = 'title';
		$this->main_css_element            = '.dipl_hover_list %%order_class%%';
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
					'title_text'    => esc_html__( 'Title Text', 'divi-plus' ),
					'subtitle_text' => esc_html__( 'Subtitle Text', 'divi-plus' ),
					'desc_text'     => esc_html__( 'Description Text', 'divi-plus' ),
					'icon_styling'  => esc_html__( 'Icon', 'divi-plus' ),
					'button'        => esc_html__( 'Button', 'divi-plus' ),
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
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'header_level' => array(
						'default'  => 'h4',
					),
					'hide_text_align' => true,
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_hover_list_title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title_text',
				),
				'subtitle' => array(
					'label'     => esc_html__( 'Subtitle', 'divi-plus' ),
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
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_hover_list_subtitle",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'subtitle_text',
				),
				'description' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
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
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_hover_list_description",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc_text',
				),
			),
			'button' => array(
			    'button' => array(
				    'label' => esc_html__( 'Button', 'divi-plus' ),
				    'css' => array(
						'main'       => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
						'alignment'  => "{$this->main_css_element} .et_pb_button_wrapper",
						'important'  => 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .et_pb_button_wrapper",
							'padding'   => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
							'important' => 'all',
						),
					),
					'box_shadow' => array(
						'css' => array(
							'main'      => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => true,
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
					'default'          => '15px|15px|15px|15px|true|true',
					'default_on_front' => '15px|15px|15px|15px|true|true',
				),
				'custom_margin' => array(
					'default'          => '0px|0px|0px|0px|true|true',
					'default_on_front' => '0px|0px|0px|0px|true|true',
				),
				'css' => array(
					'main'      => ".dipl_hover_list .dipl-hover-list-wrapper %%order_class%%",
					'important' => 'all',
				),
			),
			'text'         => false,
			'filters'      => false,
			'background' => array(
				'use_background_video' => false,
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
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
				'default'          => esc_html__( 'Your list item', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Your list item', 'divi-plus' ),
				'description'      => esc_html__( 'This is the title displayed for the hover list item.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'subtitle' => array(
				'label'            => esc_html__( 'Subtitle', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'default'          => '',
				'description'      => esc_html__( 'This is the title displayed for the hover list item item.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'description' => array(
				'label'            => esc_html__( 'Description', 'divi-plus' ),
				'type'             => 'textarea',
				'option_category'  => 'basic_option',
				'description'      => esc_html__( 'This is the description displayed for hover list item.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'hover_image' => array(
				'label'              => esc_html__( 'Hover Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'description'        => esc_html__( 'Here you can upload the image to display on hover of the list item.', 'divi-plus' ),
				'toggle_slug'        => 'main_content',
			),
			'use_icon' => array(
				'label'     	  => esc_html__( 'Show Icon', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here you can choose whether or not show the icon.', 'divi-plus' ),
			),
			'icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'show_if'         => array(
					'use_icon' => 'on',
				),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here you can select the icon to display.', 'divi-plus' ),
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
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here you can choose whether or not show the button.', 'divi-plus' ),
			),
			'button_text' => array(
				'label'            => esc_html__( 'Button Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'show_if'          => array( 'show_button' => 'on' ),
				'default'          => esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
			),
			'button_url' => array(
				'label'           => esc_html__( 'Button Link URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array( 'show_button' => 'on' ),
				'dynamic_content' => 'url',
				'toggle_slug'     => 'display',
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
				'toggle_slug'     => 'display',
				'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
			),
			'icon_fontsize' => array(
				'label'           => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '250',
					'step' => '1',
				),
				'default'         => '40px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'icon',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'       => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'        => 'color-alpha',
				'hover'       => 'tabs',
				'default'     => '',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'icon',
				'description' => esc_html__( 'Here you can define a custom color to be used for the trigger icon.', 'divi-plus' ),
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

		$multi_view  = et_pb_multi_view_options( $this );

		$title_level = et_pb_process_header_level( $this->props['title_level'], 'h4' );
		$use_icon    = sanitize_text_field( $this->props['use_icon'] ) ?? 'off';
		$show_button = sanitize_text_field( $this->props['show_button'] ) ?? 'off';
		$button_url  = esc_url( $this->props['button_url'] ) ?? '';

		// Title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_hover_list_title'
			),
			'required' => 'title',
		) );

		// Icon.
		$icon = '';
		if ( 'on' === $use_icon ) {
			$icon = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array(
					'class' => 'et-pb-icon dipl_hover_list_icon',
				),
				'required' => 'icon',
			) );
		}

		$title = sprintf(
			'<div class="dipl_hover_list_title_wrapper">%1$s %2$s</div>',
			et_core_esc_previously( $icon ),
			et_core_esc_previously( $title )
		);

		// Description.
		$description = $multi_view->render_element( array(
			'tag'        => 'div',
			'content'    => '{{description}}',
			'attrs'      => array(
				'class' => 'dipl_hover_list_description',
			),
			'required' => 'description',
		) );

		// Subtitle.
		$subtitle = $multi_view->render_element( array(
			'tag'        => 'div',
			'content'    => '{{subtitle}}',
			'attrs'      => array(
				'class' => 'dipl_hover_list_subtitle',
			),
			'required' => 'subtitle',
		) );
		// Adding defailt tag to manage spaces.
		if ( empty( $subtitle ) ) {
			$subtitle = '<div class="dipl_hover_list_subtitle"></div>';
		}

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

		// Final output.
		$render_output = $multi_view->render_element( array(
			'tag'      => 'div',
			'attrs'    => array(
				'class'      => 'dipl-hover-list-item-wrapper',
				'data-image' => ! empty( $this->props['hover_image'] ) ? esc_url( $this->props['hover_image'] ) : ''
			),
			'content'  => sprintf(
				'<div class="dipl-hover-list-item-inner">
					%1$s %2$s %3$s %4$s
				</div>
				<div class="dipl-hover-list-item-overlay"></div>
				<div class="dipl-hover-list-item-divider"></div>',
				et_core_esc_previously( $title ),
				et_core_esc_previously( $description ),
				et_core_esc_previously( $subtitle ),
				et_core_esc_previously( $render_button )
			),
		) );

		// Icon styling.
		if ( 'on' === $use_icon ) {
			// Icon font family and weight.
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => "{$this->main_css_element} .dipl_hover_list_title_wrapper .et-pb-icon",
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				) );
			}

			$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
			if ( ! empty( array_filter( $icon_fontsize ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, '%%order_class%% .dipl_hover_list_title_wrapper .et-pb-icon', 'font-size', $render_slug, '!important;', 'range' );
			}
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => '%%order_class%% .dipl_hover_list_title_wrapper .et-pb-icon',
				'hover_selector' => '%%order_class%% .dipl_hover_list_title_wrapper .et-pb-icon:hover',
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
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
	 * @param mixed $raw_value Props raw value.
	 * @param array $args {
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
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_hover_list', $modules ) ) {
		new DIPL_HoverListItem();
	}
} else {
	new DIPL_HoverListItem();
}
