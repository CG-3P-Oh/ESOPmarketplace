<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_HoverList extends ET_Builder_Module {
	public $slug       = 'dipl_hover_list';
	public $child_slug = 'dipl_hover_list_item';
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
		$this->name             = esc_html__( 'DP Hover List', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Hover List Item', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
				)
			),
			'advanced' => array(
				'toggles' => array(
					'title'         => esc_html__( 'Title', 'divi-plus' ),
					'subtitle_text' => esc_html__( 'Subtitle Text', 'divi-plus' ),
					'description'   => esc_html__( 'Description', 'divi-plus' ),
					'icon_styling'  => esc_html__( 'Icon', 'divi-plus' ),
					'hover_image'   => esc_html__( 'Hover Image', 'divi-plus' ),
					'divider'       => esc_html__( 'Divider', 'divi-plus' ),
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
					'hide_text_align' => true,
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_hover_list_title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title',
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
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_hover_list_description",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'description',
				),
			),
			'borders' => array(
				'hover_image' => array(
					'label_prefix' => esc_html__( 'Hover Image', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl-hover-list-cursor',
							'border_styles' => '%%order_class%% .dipl-hover-list-cursor',
							'important' => 'all',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'hover_image',
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
				'hover_image' => array(
					'label'       => esc_html__( 'Hover Image Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => '%%order_class%% .dipl-hover-list-cursor',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'hover_image',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'hover_list_spacing' => array(
				'description' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_hover_list_description",
							'important'  => 'all',
						),
					),
				),
				'divider' => array(
					'margin_padding' => array(
						'css' => array(
							'use_padding' => false,
							'margin'      => "{$this->main_css_element} .dipl-hover-list-item-divider",
							'important'   => 'all',
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
			'background' => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'title_width' => array(
				'label'           => esc_html__( 'Title Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '20%',
				'default_unit'    => '%',
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'width' ),
				'validate_unit'   => true,
				'mobile_options'  => false,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'title',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
			),
			'title_align' => array(
				'label'            => esc_html__( 'Alignment', 'divi-plus' ),
				'type'             => 'align',
				'option_category'  => 'layout',
				'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'title',
				'description'      => esc_html__( 'Align the container to the left, right or center.', 'divi-plus' ),
			),
			'description_width' => array(
				'label'           => esc_html__( 'Description Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '40%',
				'default_unit'    => '%',
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'width' ),
				'validate_unit'   => true,
				'mobile_options'  => false,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'description',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
			),
			'description_custom_padding' => array(
				'label'            => esc_html__( 'Description Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'mobile_options'   => true,
				'hover'            => false,
				'default'          => '|10px||10px|true|true',
				'default_on_front' => '|10px||10px|true|true',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'description',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'icon_fontsize' => array(
				'label'            => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '250',
					'step' => '1',
				),
				'default'          => '40px',
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon',
				'description'      => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'            => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'hover'            => 'tabs',
				'default'          => '',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon',
				'description'      => esc_html__( 'Here you can define a custom color to be used for the trigger icon.', 'divi-plus' ),
			),
			'hover_image_size' => array(
				'label'            => esc_html__( 'Hover Image Size', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'layout',
				'range_settings'   => array(
					'min'  => '10',
					'max'  => '1000',
					'step' => '1',
				),
				'default'          => '400px',
				'default_on_front' => '400px',
				'default_unit'     => 'px',
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'hover_image',
				'description'      => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
			),
			'divider_size' => array(
				'label'           => esc_html__( 'Divider Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '20',
					'step' => '1',
				),
				'default'         => '0',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'divider',
				'description'     => esc_html__( 'Here you can set the divider width.', 'divi-plus' ),
			),
			'divider_style' => array(
				'label'           => esc_html__( 'Divider Style', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'layout',
				'options'         => array(
					'solid'  => esc_html__( 'Solid', 'divi-plus' ),
					'dashed' => esc_html__( 'Dashed', 'divi-plus' ),
					'dotted' => esc_html__( 'Dotted', 'divi-plus' ),
					'double' => esc_html__( 'Double', 'divi-plus' ),
					'groove' => esc_html__( 'Groove', 'divi-plus' ),
					'ridge'  => esc_html__( 'Ridge', 'divi-plus' ),
					'inset'  => esc_html__( 'Inset', 'divi-plus' ),
					'outset' => esc_html__( 'Outset', 'divi-plus' ),
				),
				'default'         => 'solid',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'divider',
				'description'     => esc_html__( 'Here you can select the divider style.', 'divi-plus' ),
			),
			'divider_color' => array(
				'label'       => esc_html__( 'Divider Color', 'divi-plus' ),
				'type'        => 'color-alpha',
				'hover'       => 'tabs',
				'default'     => '#d3d3d3',
				'tab_slug'    => 'advanced',
				'toggle_slug' => 'divider',
				'description' => esc_html__( 'Here you can define a custom color for your divider.', 'divi-plus' ),
			),
			'divider_custom_margin' => array(
				'label'            => esc_html__( 'Divider Margin', 'divi-plus' ),
				'type'             => 'custom_margin',
				'option_category'  => 'layout',
				'mobile_options'   => true,
				'hover'            => false,
				'default'          => '||||true|true',
				'default_on_front' => '||||true|true',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'divider',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'divider_hide_last' => array(
				'label'            => esc_html__( 'Hide Last Divider?', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'basic_option',
				'options'          => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'description'      => esc_html__( 'Here you can choose whether the last divider hide or show.', 'divi-plus' ),
				'default_on_front' => 'off',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'divider',
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

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-hover-list-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/HoverList/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'elicus-gsap-script' );
		wp_enqueue_script( 'dipl-hover-list-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/HoverList/dipl-hover-list-custom.min.js", array('jquery'), '1.0.0', true );

		// Render final output.
		$render_output = sprintf(
			'<div class="dipl-hover-list-wrapper">
				<div class="dipl-hover-list-cursor"></div>
				<div class="dipl-hover-list-inner">%1$s</div>
			</div>',
			et_core_intentionally_unescaped( $this->content, 'html' )
		);

		// Title.
		$title_width = et_pb_responsive_options()->get_property_values( $this->props, 'title_width' );
		if ( ! empty( array_filter( $title_width ) ) ) {
			foreach ( $title_width as &$width ) {
				$width = ! empty( $width ) ? sprintf( '0 0 %1$s', $width ) : '';
			}
			et_pb_responsive_options()->generate_responsive_css( $title_width, '%%order_class%% .dipl_hover_list_title_wrapper', 'flex', $render_slug, '!important;', '' );
		}
		$title_align = et_pb_responsive_options()->get_property_values( $this->props, 'title_align' );
		foreach ( $title_align as &$align ) {
			$align = str_replace( array( 'left', 'right' ), array( 'flex-start', 'flex-end' ), $align );
		}
		if ( ! empty( array_filter( $title_align ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $title_align, '%%order_class%% .dipl_hover_list_title_wrapper', 'justify-content', $render_slug, '!important;', '' );
		}

		// Description.
		$description_width = et_pb_responsive_options()->get_property_values( $this->props, 'description_width' );
		if ( ! empty( array_filter( $description_width ) ) ) {
			foreach ( $description_width as &$width ) {
				$width = ! empty( $width ) ? sprintf( '0 0 %1$s', $width ) : '';
			}
			et_pb_responsive_options()->generate_responsive_css( $description_width, '%%order_class%% .dipl_hover_list_description', 'flex', $render_slug, '!important;', '' );
		}

		// Icon.
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

		// Hover image size.
		$hover_image_size = et_pb_responsive_options()->get_property_values( $this->props, 'hover_image_size' );
		if ( ! empty( array_filter( $hover_image_size ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $hover_image_size, '%%order_class%% .dipl-hover-list-cursor', 'width', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $hover_image_size, '%%order_class%% .dipl-hover-list-cursor', 'height', $render_slug, '!important;', 'range' );
		}

		// Divider.
		$divider_size = et_pb_responsive_options()->get_property_values( $this->props, 'divider_size' );
		et_pb_responsive_options()->generate_responsive_css( $divider_size, '%%order_class%% .dipl-hover-list-item-divider', 'border-top-width', $render_slug, 'px!important;', 'border' );
		if ( ! empty( $this->props['divider_style'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-hover-list-item-divider',
				'declaration' => sprintf( 'border-style: %1$s !important;', esc_attr( $this->props['divider_style'] ) ),
			) );
		}
		$this->generate_styles( array(
			'base_attr_name' => 'divider_color',
			'selector'       => '%%order_class%% .dipl-hover-list-item-divider',
			'hover_selector' => '%%order_class%% .dipl_hover_list_item:hover .dipl-hover-list-item-divider',
			'css_property'   => 'border-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );
		if ( 'on' === $this->props['divider_hide_last'] ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_hover_list_item:last-child .dipl-hover-list-item-divider',
				'declaration' => 'display: none !important;',
			) );
		}

		$fields = array( 'hover_list_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_hover_list', $modules ) ) {
		new DIPL_HoverList();
	}
} else {
	new DIPL_HoverList();
}
