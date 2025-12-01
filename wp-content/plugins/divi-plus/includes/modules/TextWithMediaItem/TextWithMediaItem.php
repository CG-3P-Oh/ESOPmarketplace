<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_TextWithMediaItem extends ET_Builder_Module {
	public $slug       = 'dipl_text_with_media_item';
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
		$this->name                        = esc_html__( 'DP Text with Media Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Text with Media Item', 'divi-plus' );
		$this->child_title_var             = 'content_type';
		$this->main_css_element            = '.dipl_text_with_media %%order_class%%';
	}

    public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
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
					'hide_text_align' => true,
					'css'       => array(
						'main'      => "{$this->main_css_element} .dipl_text_media_element.dipl_element_content",
						'important'	=> 'all',
					),
					'depends_on'      => array( 'content_type' ),
					'depends_show_if' => 'text',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'content_text',
				),
			),
            'borders' => array(
				'image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} img.dipl_text_media_element.dipl_element_image",
							'border_styles' => "{$this->main_css_element} img.dipl_text_media_element.dipl_element_image",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'content_type' ),
					'depends_show_if' => 'image',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image',
				),
				'video' => array(
					'label_prefix' => esc_html__( 'Video', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_text_media_element.dipl_element_video",
							'border_styles' => "{$this->main_css_element} .dipl_text_media_element.dipl_element_video",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'content_type' ),
					'depends_show_if' => 'video',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'video',
				),
				'default' => false
			),
			'box_shadow' => array(
				'image' => array(
					'label'       => esc_html__( 'Image Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "{$this->main_css_element} img.dipl_text_media_element.dipl_element_image",
						'important' => 'all',
					),
					'depends_on'      => array( 'content_type' ),
					'depends_show_if' => 'image',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image',
				),
				'video' => array(
					'label'       => esc_html__( 'Video Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "{$this->main_css_element} .dipl_text_media_element.dipl_element_video",
						'important' => 'all',
					),
					'depends_on'      => array( 'content_type' ),
					'depends_show_if' => 'video',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'video',
				),
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element}",
					),
				)
			),
			'margin_padding' => array(
				'custom_margin' => array(
					'default_on_front' => '|20px|20px|||',
				),
				'css'           => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'max_width'      => false,
			'height'         => false,
			'text'           => false,
			'transform'      => false,
			'background'     => array(
				'use_background_video' => false,
				'css' => array(
					'main'      => "{$this->main_css_element}, {$this->main_css_element} .dipl_vb_shape_wrap",
					'important' => 'all',
				),
			),
        );
    }

    public function get_fields() {
		return array(
			'content_type' => array(
				'label'        	  => esc_html__( 'Content Type', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'text'  => esc_html__( 'Text', 'divi-plus' ),
					'image' => esc_html__( 'Image', 'divi-plus' ),
					'icon'  => esc_html__( 'Icon', 'divi-plus' ),
					'video' => esc_html__( 'Video', 'divi-plus' ),
				),
				'default'         => 'text',
				'toggle_slug'     => 'main_content',
				'description'  	  => esc_html__( 'Here you can choose the content type to show.', 'divi-plus' ),
				'affects'         => array(
					'content_text'
				),
				'computed_affects' => array(
					'__computed_video_html',
				),
			),
			'content' => array(
				'label'            => esc_html__( 'Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'show_if'          => array( 'content_type' => 'text' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can input the text to display.', 'divi-plus' ),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'show_if'            => array( 'content_type' => 'image' ),
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can upload the image to show.', 'divi-plus' ),
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image Alternative Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array( 'content_type' => 'image' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input the text to be used for the image of modal trigger as alternative text.', 'divi-plus'),
			),
			'icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'default'         => '',
				'show_if'         => array( 'content_type' => 'icon' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can select the icon to show.', 'divi-plus' ),
			),
			'video_src' => array(
				'label'              => esc_html__( 'Video MP4 File Or YouTube/Vimeo URL', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'show_if'            => array( 'content_type' => 'video' ),
				'upload_button_text' => esc_attr__( 'Upload a video', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose a Video MP4 File', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Video', 'divi-plus' ),
				'description'        => esc_html__( 'Upload your desired video in .MP4 format, or type in the URL to the video you would like to display.', 'divi-plus' ),
				'toggle_slug'        => 'main_content',
				'computed_affects' => array(
					'__computed_video_html',
				),
			),
			'video_src_webm' => array(
				'label'              => esc_html__( 'Video WEBM File', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'show_if'            => array( 'content_type' => 'video' ),
				'upload_button_text' => esc_attr__( 'Upload a video', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose a Video WEBM File', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Video', 'divi-plus' ),
				'description'        => esc_html__( 'Upload the .WEBM version of your video here. All uploaded videos should be in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers.', 'divi-plus' ),
				'toggle_slug'        => 'main_content',
				'computed_affects' => array(
					'__computed_video_html',
				),
			),
			'item_shape' => array(
				'label'        	  => esc_html__( 'Shape', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'none'   => esc_html__( 'None', 'divi-plus' ),
					'circle' => esc_html__( 'Circle', 'divi-plus' ),
					'square' => esc_html__( 'Square', 'divi-plus' ),
					'arrow'  => esc_html__( 'Arrow', 'divi-plus' ),
				),
				'show_if'         => array(
					'content_type' => array( 'text', 'icon' )
				),
				'default'         => 'none',
				'toggle_slug'     => 'main_content',
				'description'  	  => esc_html__( 'Here you can choose the content type to show.', 'divi-plus' ),
			),
			'image_width' => array(
				'label'            => esc_html__( 'Image Width', 'divi-plus' ),
				'type'             => 'range',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '500',
					'step' => '1',
				),
				'default'          => '',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px', 'em', 'rem' ),
				'mobile_options'   => true,
				'show_if'          => array( 'content_type' => 'image' ),
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
				'default'          => '',
				'mobile_options'   => true,
				'show_if'          => array( 'content_type' => 'icon' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon',
				'description'      => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'            => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'hover'            => 'tabs',
				'default'          => '',
				'show_if'          => array( 'content_type' => 'icon' ),
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
				'default'          => '',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px', 'em', 'rem' ),
				'mobile_options'   => true,
				'show_if'          => array( 'content_type' => 'video' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'video',
				'description'      => esc_html__( 'Increase or decrease the video custom width.', 'divi-plus' ),
			),
			'__computed_video_html' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_TextWithMediaItem', 'get_computed_video_html' ),
				'computed_depends_on' => array(
					'content_type',
					'video_src',
					'video_src_webm',
				)
			)
        );
    }

	public static function get_computed_video_html( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'content_type'   => 'text',
			'video_src'      => '',
			'video_src_webm' => '',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		$video_html = [];
		if ( 'video' === $content_type && ! empty( $video_src ) ) {
			// Get provider data.
			$oembed			= _wp_oembed_get_object();
			$provider		= $oembed->get_data( $video_src );
			$provider_name	= isset( $provider->provider_name ) ? $provider->provider_name : 'custom-upload';

			$video = self::get_video( array(
				'src'      => $video_src,
				'src_webm' => $video_src_webm,
			) );

			$video_html['type'] = esc_attr( strtolower( $provider_name ) );
			$video_html['html'] = sprintf(
				'<div class="fluid-width-video-wrapper">%1$s</div>',
				et_core_esc_previously( $video ),
			);
		}

		self::$rendering = false;
		return $video_html;
	}

    public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$multi_view   = et_pb_multi_view_options( $this );

		// Get the attrs.
		$content_type = sanitize_text_field( $this->props['content_type'] ) ?? 'text';
		$item_shape   = sanitize_text_field( $this->props['item_shape'] ) ?? 'none';

		// Render content based on the content type.
        $render_output = '';
		if ( 'text' === $content_type ) {
			$render_output = $multi_view->render_element( array(
				'tag'     => 'div',
				'content' => '{{content}}',
				'attrs'   => array(
					'class' => 'dipl_text_media_element dipl_element_content',
				),
				'required' => 'content',
			) );
		} elseif ( 'image' === $content_type ) {
			$render_output = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{image}}',
					'alt'   => '{{image_alt}}',
					'class' => 'dipl_text_media_element dipl_element_image',
				),
				'required' => 'image',
			) );
		} elseif ( 'icon' === $content_type ) {
			$render_output = $multi_view->render_element( array(
				'tag'      => 'span',
				'content'  => '{{icon}}',
				'attrs'    => array(
					'class' => 'dipl_text_media_element dipl_element_icon et-pb-icon',
				),
				'required' => 'icon',
			) );
		} elseif ( 'video' === $content_type ) {

			// Get provider data.
			$oembed			= _wp_oembed_get_object();
			$provider		= $oembed->get_data( $this->props['video_src'] );
			$provider_name	= isset( $provider->provider_name ) ? $provider->provider_name : 'custom-upload';

			$video_srcs = self::get_video( array(
				'src'      => $this->props['video_src'],
				'src_webm' => $this->props['video_src_webm'],
			) );

			$render_output = $multi_view->render_element( array(
				'tag'     => 'div',
				'content' => $video_srcs,
				'attrs'   => array( 'class' => 'dipl_text_media_element dipl_element_video ' . esc_attr( strtolower( $provider_name ) ) ),
			) );
		}

		// Style based on selected element.
		if ( 'image' === $content_type ) {
			// Image width.
			$image_width = et_pb_responsive_options()->get_property_values( $this->props, 'image_width' );
			if ( ! empty( array_filter( $image_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $image_width, "{$this->main_css_element} img.dipl_text_media_element.dipl_element_image", 'width', $render_slug, '!important;', 'range' );
			}
		} elseif ( 'icon' === $content_type ) {
			// Icon font family and weight.
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => "{$this->main_css_element} .dipl_element_icon.et-pb-icon",
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				) );
			}
			$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
			if ( ! empty( array_filter( $icon_fontsize ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, "{$this->main_css_element} .dipl_element_icon.et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			}
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => "{$this->main_css_element} .dipl_element_icon.et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_element_icon.et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		} elseif ( 'video' === $content_type ) {
			// Video width.
			$video_width = et_pb_responsive_options()->get_property_values( $this->props, 'video_width' );
			if ( ! empty( array_filter( $video_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $video_width, "{$this->main_css_element} .dipl_text_media_element.dipl_element_video", 'width', $render_slug, '!important;', 'range' );
			}
		}

		// Add class for the shapes.
		if ( 'none' !== $item_shape && ( 'text' === $content_type || 'icon' === $content_type ) ) {
			$this->add_classname( array(
				'dipl_text_media_content_' . esc_attr( $content_type ),
				'dipl_text_media_shape_' . esc_attr( $item_shape )
			) );
		}

        self::$rendering = false;
		return $render_output;
	}

	static function get_video( $args = array() ) {
		$defaults = array(
			'src'      => '',
			'src_webm' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$video_src = '';
		if ( false !== et_pb_check_oembed_provider( esc_url( $args['src'] ) ) ) {
			$video_src = et_builder_get_oembed( esc_url( $args['src'] ) );
		} elseif ( false !== et_pb_validate_youtube_url( esc_url( $args['src'] ) ) ) {
			$args['src'] = et_pb_normalize_youtube_url( esc_url( $args['src'] ) );
			$video_src   = et_builder_get_oembed( esc_url( $args['src'] ) );
		} else {
			$video_src = sprintf(
				'<video controls>
					 %1$s
					 %2$s
				 </video>',
				( '' !== $args['src'] ? sprintf( '<source type="video/mp4" src="%1$s" />', esc_url( $args['src'] ) ) : '' ),
				( '' !== $args['src_webm'] ? sprintf( '<source type="video/webm" src="%1$s" />', esc_url( $args['src_webm'] ) ) : '' )
			);

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		return $video_src;
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
			</div>',
			et_html_attrs( $outer_wrapper_attrs ),
			$output
		);
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_text_with_media', $modules ) ) {
		new DIPL_TextWithMediaItem();
	}
} else {
	new DIPL_TextWithMediaItem();
}
