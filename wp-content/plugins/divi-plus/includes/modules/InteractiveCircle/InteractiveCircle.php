<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.21.0
 */
class DIPL_InteractiveCircle extends ET_Builder_Module {
	public $slug       = 'dipl_interactive_circle';
	public $child_slug = 'dipl_interactive_circle_item';
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
		$this->name 			= esc_html__( 'DP Interactive Circle', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Interactive Circle Item', 'divi-plus' );
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
					'info_circle' => array(
						'title'        => esc_html__( 'Info Circle', 'divi-plus' ),
						'sub_toggles'  => array(
							'normal' => array( 'name' => esc_html__( 'Normal', 'divi-plus' ) ),
							'active' => array( 'name' => esc_html__( 'Active', 'divi-plus' ) )
						),
						'tabbed_subtoggles' => true,
					),
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
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_icircle_item_title",
						'hover'     => "{$this->main_css_element} .dipl_icircle_item_title:hover",
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
			'borders' => array(
				'info_circle' => array(
					'label_prefix' => esc_html__( 'Circle', 'divi-plus' ),
					'defaults' => array(
						'border_radii' => 'on|50px|50px|50px|50px',
					),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_interactive_circle_item__main",
							'border_styles' => "{$this->main_css_element} .dipl_interactive_circle_item__main",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'info_circle',
					'sub_toggle'  => 'normal',
				),
				'info_circle_active' => array(
					'label_prefix' => esc_html__( 'Active Circle', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main",
							'border_styles' => "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'info_circle',
					'sub_toggle'  => 'active',
				),
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element}",
							'border_radii'  => "{$this->main_css_element}",
						),
					),
				),
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main'      => "{$this->main_css_element}",
						'important' => 'all',
					),
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'max_width' => array(
				'options' => array(
					'max_width' => array(
						'default'        => '600px',
						'default_unit'   => 'px',
						'range_settings' => array(
							'min'  => 1,
							'max'  => 1200,
							'step' => 1,
						),
					),
				),
				'css' => array(
					'main'             => '%%order_class%%',
					'module_alignment' => '%%order_class%%',
				),
			),
			'text'         => false,
			'text_shadow'  => false,
			'link_options' => false,
			'background'   => array(
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'click_binding_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'configuration',
				'toggle_slug'     => 'display',
				'value'           => true,
				'display_if'      => true,
				'message'         => esc_html__( 'Interactive Circle click hide/show content will not work on visual builder.', 'divi-plus' )
			),
			'circle_size' => array(
				'label'           => esc_html__( 'Info Circle Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '200',
					'step' => '1',
				),
				'default'         => '80px',
				'default_unit'    => 'px',
				'mobile_options'  => true,
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease size of the circle.', 'divi-plus' ),
			),
			'info_circle_bg_color' => array(
				'label'          => esc_html__( 'Circle Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'default'        => '#f4f4f4',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'info_circle',
				'sub_toggle'     => 'normal',
				'description'    => esc_html__( 'Here you can define a custom background color for your info circle.', 'divi-plus' ),
			),
			'info_circle_active_bg_color' => array(
				'label'          => esc_html__( 'Active Circle Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'info_circle',
				'sub_toggle'     => 'active',
				'description'    => esc_html__( 'Here you can define a custom background color for your info circle when active.', 'divi-plus' ),
			),
			'info_circle_icon_fontsize' => array(
				'label'           => esc_html__( 'Circle Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'default'         => '32px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'info_circle',
				'sub_toggle'      => 'normal',
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'info_circle_active_icon_fontsize' => array(
				'label'           => esc_html__( 'Active Circle Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'info_circle',
				'sub_toggle'      => 'active',
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'info_circle_icon_color' => array(
				'label'          => esc_html__( 'Circle Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'info_circle',
				'sub_toggle'     => 'normal',
				'description'    => esc_html__( 'Here you can define a custom icon color for your info circle.', 'divi-plus' ),
			),
			'info_circle_active_icon_color' => array(
				'label'          => esc_html__( 'Active Circle Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'info_circle',
				'sub_toggle'     => 'active',
				'description'    => esc_html__( 'Here you can define a custom icon color for your info circle when active.', 'divi-plus' ),
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

		if ( ! empty( $this->content ) ) {

			// Load style and script files.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-interactive-circle-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/InteractiveCircle/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'dipl-interactive-circle-script', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/InteractiveCircle/dipl-interactive-circle.min.js", array('jquery'), '1.0.0', true );

			// Final rendering.
			$render_output = sprintf(
				'<div class="dipl_interactive_circle_wrap">
					%1$s
				</div>',
				et_core_intentionally_unescaped( $this->content, 'html' ),
			);

			// Circle size.
			$circle_size = et_pb_responsive_options()->get_property_values( $this->props, 'circle_size' );
			if ( ! empty( array_filter( $circle_size ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $circle_size, '%%order_class%% .dipl_interactive_circle_item__main', 'width', $render_slug, '!important;', 'range' );
				et_pb_responsive_options()->generate_responsive_css( $circle_size, '%%order_class%% .dipl_interactive_circle_item__main', 'height', $render_slug, '!important;', 'range' );

				// Content size.
				$content_size = array();
				foreach ( $circle_size as $device => $value ) {
					if ( empty( $value ) ) {
						continue;
					}
					$content_size[ $device ] = 'calc( 100% - ' . esc_attr( $value ) . ' )';
				}
				et_pb_responsive_options()->generate_responsive_css( $content_size, '%%order_class%% .dipl_interactive_circle_item__main_content', 'width', $render_slug, '!important;', '' );
				et_pb_responsive_options()->generate_responsive_css( $content_size, '%%order_class%% .dipl_interactive_circle_item__main_content', 'height', $render_slug, '!important;', '' );
			}

			// Info Circle background color.
			$this->generate_styles( array(
				'base_attr_name' => 'info_circle_bg_color',
				'selector'       => "{$this->main_css_element} .dipl_interactive_circle_item__main",
				'hover_selector' => "{$this->main_css_element} .dipl_interactive_circle_item__main:hover",
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'base_attr_name' => 'info_circle_active_bg_color',
				'selector'       => "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main",
				'hover_selector' => "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main:hover",
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Info Circle icon text color.
			$this->generate_styles( array(
				'base_attr_name' => 'info_circle_icon_color',
				'selector'       => "{$this->main_css_element} .dipl_interactive_circle_item__main .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_interactive_circle_item__main:hover .et-pb-icon",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'base_attr_name' => 'info_circle_active_icon_color',
				'selector'       => "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main:hover .et-pb-icon",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$info_circle_icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'info_circle_icon_fontsize' );
			if ( ! empty( array_filter( $info_circle_icon_fontsize ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $info_circle_icon_fontsize, "{$this->main_css_element} .dipl_interactive_circle_item__main .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			}
			$info_circle_active_icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'info_circle_active_icon_fontsize' );
			if ( ! empty( array_filter( $info_circle_icon_fontsize ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $info_circle_active_icon_fontsize, "{$this->main_css_element} .dipl_interactive_circle_item.active .dipl_interactive_circle_item__main .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			}
		}

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_interactive_circle', $modules, true ) ) {
		new DIPL_InteractiveCircle();
	}
} else {
	new DIPL_InteractiveCircle();
}
