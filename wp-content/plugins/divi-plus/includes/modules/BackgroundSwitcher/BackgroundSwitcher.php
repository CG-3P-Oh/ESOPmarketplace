<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.13.0
 */
class DIPL_BackgroundSwitcher extends ET_Builder_Module {
	public $slug       = 'dipl_background_switcher';
	public $child_slug = 'dipl_background_switcher_item';
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
		$this->name             = esc_html__( 'DP Background Switcher', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Background Item', 'divi-plus' );
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
					'text' => esc_html__( 'Text', 'divi-plus' ),
					'glob_switcher_content' => array(
						'title'             => esc_html__( 'Switcher Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'glob_title'       => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'glob_description' => array( 'name' => esc_html__( 'Description', 'divi-plus' ) )
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
					'label'        => esc_html__( 'Title', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '22px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "{$this->main_css_element} .dipl-bg-switcher-title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'glob_switcher_content',
					'sub_toggle'  => 'glob_title',
				),
				'description' => array(
					'label'        => esc_html__( 'Description', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "{$this->main_css_element} .dipl-bg-switcher-desc",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'glob_switcher_content',
					'sub_toggle'  => 'glob_description',
				),
			),
			'borders' => array(
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
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
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
				'use_text_orientation'  => false,
				'use_background_layout' => true,
				'options'               => array(
					'background_layout' => array(
                        'default'           => 'dark',
						'default_on_front'  => 'dark',
						'hover'             => 'tabs',
					)
				),
			),
			'filters'      => false,
			'link_options' => false,
			'background' => array(
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'switcher_orientation' => array(
				'label'            => esc_html__( 'Switcher Orientation', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'horizontal' => esc_html__( 'Horizontal', 'divi-plus' ),
					'vertical'   => esc_html__( 'Vertical', 'divi-plus' ),
				),
				'mobile_options'   => true,
				'default'          => 'horizontal',
				'default_on_front' => 'horizontal',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can select the accordion orientation.', 'divi-plus' ),
			),
			'content_valign' => array(
				'label'            => esc_html__( 'Content Vertical Align', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'flex-start' => esc_html__( 'Top', 'divi-plus' ),
					'center'     => esc_html__( 'Center', 'divi-plus' ),
					'flex-end'   => esc_html__( 'Bottom', 'divi-plus' ),
				),
				'default'          => 'flex-end',
				'default_on_front' => 'flex-end',
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Showing the full content will not truncate your posts on the index page. Showing the excerpt will only display your excerpt text.', 'divi-plus' ),
			),
			'item_height' => array(
				'label'            => esc_html__( 'Item Height', 'all-in-one-carousel-for-divi' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '1000',
					'step' => '1',
				),
				'fixed_unit'	   => 'px',
				'default'          => '350px',
				'default_on_front' => '350px',
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Increase or decrease the item height.', 'all-in-one-carousel-for-divi' ),
			),
			// 'overlay_bg_color' => array(
			// 	'label'        	   => esc_html__( 'Overlay Background', 'divi-plus' ),
			// 	'type'         	   => 'color-alpha',
			// 	'custom_color' 	   => true,
			// 	'default'      	   => 'rgba( 0, 0, 0, .35 )',
			// 	'default_on_front' => 'rgba( 0, 0, 0, .35 )',
			// 	'toggle_slug'      => 'display',
			// 	'description'      => esc_html__( 'Here you can select the backround color for the image overlay.', 'divi-plus' ),
			// ),
			'hover_blur_level' => array(
				'label'             => esc_html__( 'Blur on Hover', 'divi-plus' ),
				'type'              => 'range',
				'option_category'   => 'basic_option',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '50',
					'step' => '1',
				),
				'fixed_unit'        => 'px',
				'default'           => '5px',
				'default_on_front'  => '5px',
				'toggle_slug'       => 'display',
				'description'       => esc_html__( 'Here you can enter the transition duration.', 'divi-plus' ),
			),
			'transition_duration' => array(
				'label'            => esc_html__( 'Transition Duration', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'basic_option',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '2000',
					'step' => '100',
				),
				'fixed_unit'       => 'ms',
				'default'          => '300ms',
				'default_on_front' => '300ms',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can enter the transition duration.', 'divi-plus' ),
			),
		);
	}

	// Get Tabs Title
	public function before_render() {
		global $dipl_bg_switcher_item_counts;
		$dipl_bg_switcher_item_counts = 0;
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		wp_enqueue_script( 'dipl-background-switcher-script', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/BackgroundSwitcher/dipl-background-switcher.min.js', array('jquery'), '1.0.0', true );

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-background-switcher-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/BackgroundSwitcher/' . $file . '.min.css', array(), '1.0.0' );
		
		$render_output = sprintf(
			'<div class="dipl_background_switcher_wrap">
				<div class="dipl_background_switcher_inner">%1$s</div>
			</div>',
			$this->content
		);

		// Switcher Orientation.
		$switcher_orientation = et_pb_responsive_options()->get_property_values( $this->props, 'switcher_orientation' );
		foreach ( $switcher_orientation as &$orientation ) {
			$orientation = str_replace( array( 'horizontal', 'vertical' ), array( 'row', 'column' ), $orientation);
		}
		if ( ! empty( array_filter( $switcher_orientation ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $switcher_orientation, "%%order_class%% .dipl_background_switcher_inner", 'flex-direction', $render_slug, '', 'type' );
		}

		// item.
		$item_height = et_pb_responsive_options()->get_property_values( $this->props, 'item_height' );
		if ( ! empty( array_filter( $item_height ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $item_height, '%%order_class%% .dipl_background_switcher_item', 'height', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $item_height, '%%order_class%% .dipl_background_switcher_item', 'min-height', $render_slug, '!important;', 'range' );
			// et_pb_responsive_options()->generate_responsive_css( $item_height, '%%order_class%% .dipl_background_switcher_item:hover .dipl_bg_switcher_hover_content', 'max-height', $render_slug, '!important;', 'range' );
		}

		// $this->generate_styles( array(
		// 	'base_attr_name' => 'overlay_bg_color',
		// 	'selector'       => '%%order_class%% .dipl_background_switcher_image::after',
		// 	'hover_selector' => '%%order_class%% .dipl_background_switcher_item:hover .dipl_background_switcher_image::after',
		// 	'important'      => true,
		// 	'css_property'   => 'background-color',
		// 	'render_slug'    => $render_slug,
		// 	'type'           => 'color',
		// ) );

		if ( ! empty( $this->props['hover_blur_level'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_background_switcher_item:hover',
				'declaration' => sprintf( 'backdrop-filter: blur( %1$s ) !important;', esc_attr( $this->props['hover_blur_level'] ) ),
			) );
		}

		if ( ! empty( $this->props['transition_duration'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_background_switcher_image',
				'declaration' => sprintf( 'transition-duration: %1$s !important;', esc_attr( $this->props['transition_duration'] ) ),
			) );
			// self::set_style( $render_slug, array(
			// 	'selector'    => '%%order_class%% .dipl_bg_switcher_hover_content',
			// 	'declaration' => sprintf( 'transition: %1$s !important;', esc_attr( $this->props['transition_duration'] ) ),
			// ) );
		}

		// Content alignment.
		$content_valign = et_pb_responsive_options()->get_property_values( $this->props, 'content_valign' );
		if ( ! empty( array_filter( $content_valign ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $content_valign, '%%order_class%% .dipl_bg_switcher_item_wrap', 'justify-content', $render_slug, '!important;', 'type' );
		}

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
	if ( in_array( 'dipl_background_switcher', $modules ) ) {
		new DIPL_BackgroundSwitcher();
	}
} else {
	new DIPL_BackgroundSwitcher();
}
