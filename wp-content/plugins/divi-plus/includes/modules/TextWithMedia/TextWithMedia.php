<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_TextWithMedia extends ET_Builder_Module {
	public $slug       = 'dipl_text_with_media';
	public $child_slug = 'dipl_text_with_media_item';
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
		$this->name             = esc_html__( 'DP Text with Media', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Text with Media Item', 'divi-plus' );
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
					'general'      => esc_html__( 'General', 'divi-plus' ),
					'content_text' => esc_html__( 'Text', 'divi-plus' ),
					'image'        => esc_html__( 'Image', 'divi-plus' ),
					'icon'         => esc_html__( 'Icon', 'divi-plus' ),
					'video'        => esc_html__( 'Video', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'content_text' => array(
					'label'     => esc_html__( '', 'divi-plus' ),
					'font_size' => array(
						'default' => '24px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '500',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default_on_front' => '1.4em',
						'range_settings'   => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'hide_text_align' => true,
					'css'       => array(
						'main'      => "%%order_class%% .dipl_text_media_element.dipl_element_content",
						'important'	=> 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'content_text',
				),
			),
			'borders' => array(
				'image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% img.dipl_text_media_element.dipl_element_image",
							'border_styles' => "%%order_class%% img.dipl_text_media_element.dipl_element_image",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image',
				),
				'video' => array(
					'label_prefix' => esc_html__( 'Video', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_text_media_element.dipl_element_video",
							'border_styles' => "%%order_class%% .dipl_text_media_element.dipl_element_video",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'video',
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
						'main'      => "%%order_class%% img.dipl_text_media_element.dipl_element_image",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image',
				),
				'video' => array(
					'label'       => esc_html__( 'Video Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "%%order_class%% .dipl_text_media_element.dipl_element_video",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'video',
				),
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
			'text'		   => false,
			'link_options' => false,
			'background'   => array(
				'use_background_video' => false,
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'module_align' => array(
					'label'            => esc_html__( 'Alignment', 'divi-plus' ),
					'type'             => 'align',
					'option_category'  => 'layout',
					'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'default'          => 'center',
					'default_on_front' => 'center',
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'general',
					'description'      => esc_html__( 'Align the container to the left, right or center.', 'divi-plus' ),
				),
				'image_width' => array(
					'label'            => esc_html__( 'Image Width', 'divi-plus' ),
					'type'             => 'range',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '500',
						'step' => '1',
					),
					'default'          => '150px',
					'default_on_front' => '150px',
					'default_unit'     => 'px',
					'allowed_units'    => array( 'px', 'em', 'rem' ),
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'image',
					'description'      => esc_html__( 'Increase or decrease the image custom width.', 'divi-plus' ),
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
					'default'          => '52px',
					'default_on_front' => '52px',
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'icon',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
				),
				'icon_color' => array(
					'label'            => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'             => 'color-alpha',
					'hover'            => 'tabs',
					'default'          => '#000000',
					'default_on_front' => '#000000',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'icon',
					'description'      => esc_html__( 'Here you can define a custom color to be used for the trigger icon.', 'divi-plus' ),
				),
				'video_width' => array(
					'label'            => esc_html__( 'Video Width', 'divi-plus' ),
					'type'             => 'range',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '500',
						'step' => '1',
					),
					'default'          => '150px',
					'default_on_front' => '150px',
					'default_unit'     => 'px',
					'allowed_units'    => array( 'px', 'em', 'rem' ),
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'video',
					'description'      => esc_html__( 'Increase or decrease the video custom width.', 'divi-plus' ),
				),
			)
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

		$multi_view = et_pb_multi_view_options( $this );

		// enqueue styles and scripts.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-text-with-media-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/TextWithMedia/' . $file . '.min.css', array(), '1.0.0' );

		// Final output.
		$render_output = $multi_view->render_element( array(
			'tag'     => 'div',
			'attrs'   => array(
				'class' => 'dipl_text_with_media_wrap',
			),
			'content' => sprintf(
				'<div class="dipl_text_with_media_inner">%1$s</div>',
				et_core_esc_previously( $this->content )
			)
		) );

		// Module alignment.
		$module_align = et_pb_responsive_options()->get_property_values( $this->props, 'module_align' );
		foreach ( $module_align as &$align ) {
			$align = str_replace( array( 'left', 'right' ), array( 'flex-start', 'flex-end' ), $align );
		}
		if ( ! empty( array_filter( $module_align ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $module_align, '%%order_class%% .dipl_text_with_media_inner', 'justify-content', $render_slug, '!important;', 'type' );
		}

		// Image width.
		$image_width = et_pb_responsive_options()->get_property_values( $this->props, 'image_width' );
		if ( ! empty( array_filter( $image_width ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $image_width, '%%order_class%% img.dipl_text_media_element.dipl_element_image', 'width', $render_slug, '!important;', 'range' );
		}

		// Icon font family and weight.
		if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
			$this->generate_styles( array(
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'icon',
				'important'      => true,
				'selector'       => '%%order_class%% .dipl_element_icon.et-pb-icon',
				'processor'      => array(
					'ET_Builder_Module_Helper_Style_Processor',
					'process_extended_icon',
				),
			) );
		}
		$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
		if ( ! empty( array_filter( $icon_fontsize ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, '%%order_class%% .dipl_element_icon.et-pb-icon', 'font-size', $render_slug, '!important;', 'range' );
		}
		$this->generate_styles( array(
			'base_attr_name' => 'icon_color',
			'selector'       => '%%order_class%% .dipl_element_icon.et-pb-icon',
			'hover_selector' => '%%order_class%% .dipl_element_icon.et-pb-icon:hover',
			'important'      => true,
			'css_property'   => 'color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		// Video width.
		$video_width = et_pb_responsive_options()->get_property_values( $this->props, 'video_width' );
		if ( ! empty( array_filter( $video_width ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $video_width, "%%order_class%% .dipl_text_media_element.dipl_element_video", 'width', $render_slug, '!important;', 'range' );
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_text_with_media', $modules ) ) {
		new DIPL_TextWithMedia();
	}
} else {
	new DIPL_TextWithMedia();
}
