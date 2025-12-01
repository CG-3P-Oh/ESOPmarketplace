<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */
class DIPL_StickyVideo extends ET_Builder_Module {
	public $slug       = 'dipl_sticky_video';
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
		$this->name             = esc_html__( 'DP Sticky Video', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';

		add_filter( 'et_disable_js_on_demand', '__return_true' );
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Video', 'divi-plus' ),
					'overlay'      => esc_html__( 'Overlay', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'sticky_video' => esc_html__( 'Sticky Video', 'divi-plus' ),
					'play_icon'    => esc_html__( 'Play Icon', 'divi-plus' ),
					'overlay'      => esc_html__( 'Overlay', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'   => false,
			'borders' => array(
				'sticky_video' => array(
					'css' => array(
						'main' => array(
						    'border_radii'	=> "%%order_class%% .dipl_sticky_video_inner.is-sticky",
							'border_styles'	=> "%%order_class%% .dipl_sticky_video_inner.is-sticky",
						)
					),
					'label_prefix' => esc_html__( 'Sticky Video', 'divi-plus' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'sticky_video',
				),
				'default' => array(
					'css' => array(
						'main' => array(
							'border_radii' => '%%order_class%%, %%order_class%% iframe',
						),
					),
				),
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'overlay' => 'inset',
					),
				),
			),
			'sticky_video_margin_padding' => array(
				'sticky_video' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl_sticky_video_inner.is-sticky",
							'padding'   => "{$this->main_css_element} .dipl_sticky_video_inner.is-sticky",
							'important' => 'all',
						),
					),
				),
			),
			'margin_padding'  => array(
				'css' => array(
					'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling
				),
				'custom_padding' => array(
					'responsive_affects' => array(
						'background_color',
					),
				),
			),
			'text'            => false,
			'button'          => false,
			'link_options'    => false,
			'position_fields' => array(
				'default' => 'relative',
			),
			'background' => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'sticky_video_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'configuration',
				'value'           => true,
				'display_if'      => true,
				'toggle_slug'     => 'main_content',
				'message'         => esc_html__( 'The sticky video does not appear sticky in the Visual Builder. Please check the frontend, play the video, and scroll the preview out of view to see the effect correctly.', 'divi-plus' )
			),
			'src' => array(
				'label'              => esc_html__( 'Video MP4 File Or Youtube URL', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => esc_attr__( 'Upload a video', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose a Video MP4 File', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Video', 'divi-plus' ),
				'mobile_options'     => true,
				'hover'              => 'tabs',
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Upload your desired video in .MP4 format, or type in the URL to the video you would like to display', 'divi-plus' ),
				'computed_affects'   => array(
					'__video',
				),
			),
			'src_webm' => array(
				'label'              => esc_html__( 'Video WEBM File', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => esc_attr__( 'Upload a video', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose a Video WEBM File', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Video', 'divi-plus' ),
				'mobile_options'     => true,
				'hover'              => 'tabs',
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Upload the .WEBM version of your video here. All uploaded videos should be in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers.', 'divi-plus' ),
				'computed_affects'   => array(
					'__video',
				),
			),
			'image_src' => array(
				'label'                   => esc_html__( 'Overlay Image', 'divi-plus' ),
				'type'                    => 'upload',
				'option_category'         => 'basic_option',
				'upload_button_text'      => et_builder_i18n( 'Upload an image' ),
				'choose_text'             => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'             => esc_attr__( 'Set As Image', 'divi-plus' ),
				'additional_button'       => sprintf(
					'<input type="button" class="button et-pb-video-image-button" value="%1$s" />',
					esc_attr__( 'Generate Image From Video', 'divi-plus' )
				),
				'additional_button_type'  => 'generate_image_url_from_video',
				'additional_button_attrs' => array(
					'video_source' => 'src',
				),
				'classes'                 => 'et_pb_video_overlay',
				'dynamic_content'         => 'image',
				'mobile_options'          => true,
				'hover'                   => 'tabs',
				'toggle_slug'             => 'overlay',
				'description'             => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display over your video. You can also generate a still image from your video.', 'divi-plus' ),
				'computed_affects'        => array(
					'__video_cover_src',
				),
			),
			'sticky_video_custom_margin' => array(
                'label'             => esc_html__( 'Sticky Video Margin', 'divi-plus' ),
                'type'              => 'custom_padding',
                'option_category'   => 'layout',
                'mobile_options'    => true,
                'hover'             => true,
                'default'           => '20px|20px|20px|20px|px|px',
                'default_on_front'  => '20px|20px|20px|20px|px|px',
                'tab_slug'     		=> 'advanced',
				'toggle_slug'  		=> 'sticky_video',
                'description'       => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the toggle and toggle content.', 'divi-plus' ),
            ),
			'sticky_video_custom_padding' => array(
                'label'             => esc_html__( 'Sticky Video Padding', 'divi-plus' ),
                'type'              => 'custom_padding',
                'option_category'   => 'layout',
                'mobile_options'    => true,
                'hover'             => true,
                'default'           => '',
                'default_on_front'  => '',
                'tab_slug'     		=> 'advanced',
				'toggle_slug'  		=> 'sticky_video',
                'description'       => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the toggle and toggle content.', 'divi-plus' ),
            ),
			'sticky_video_position' => array(
				'label'             => esc_html__( 'Sticky Video Position', 'divi-plus' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'top_left'     => esc_html__( 'Top Left', 'divi-plus' ),
					'top_right'    => esc_html__( 'Top Right', 'divi-plus' ),
					'bottom_left'  => esc_html__( 'Bottom Left', 'divi-plus' ),
					'bottom_right' => esc_html__( 'Bottom Right', 'divi-plus' ),
				),
				'default'			=> 'bottom_right',
				'default_on_front'	=> 'bottom_right',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'sticky_video',
				'description'       => esc_html__( 'Here you can select the position of sticky video to display.', 'divi-plus' ),
			),
			'sticky_video_width' => array(
				'label'            => esc_html__( 'Sticky Video Width', 'et_builder' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'allowed_units'    => array( 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'default'          => '350px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '700',
					'step' => '1',
				),
				'mobile_options'   => true,
				'responsive'       => true,
				'sticky'           => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'sticky_video',
				'description'      => esc_html__( 'Control the size of the sticky video by increasing or decreasing the width.', 'et_builder' ),
			),
			'play_icon_color' => array(
				'label'          => esc_html__( 'Play Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'custom_color'   => true,
				'hover'          => 'tabs',
				'mobile_options' => true,
				'sticky'         => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'play_icon',
				'description'    => esc_html__( 'Here you can define a custom color for the play icon.', 'divi-plus' ),
			),
			'play_icon' => array(
				'label'          => esc_html__( 'Icon', 'divi-plus' ),
				'type'           => 'select_icon',
				'class'          => array( 'et-pb-font-icon' ),
				'mobile_options' => true,
				'hover'          => 'tabs',
				'sticky'         => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'play_icon',
				'description'    => esc_html__( 'Choose an icon to display with your blurb.', 'divi-plus' ),
			),
			'use_icon_font_size' => array(
				'label'            => esc_html__( 'Use Custom Icon Size', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'options'          => array(
					'off' => et_builder_i18n( 'No' ),
					'on'  => et_builder_i18n( 'Yes' ),
				),
				'default_on_front' => 'off',
				'affects'          => array(
					'icon_font_size',
				),
				'option_category'  => 'font_option',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'play_icon',
				'description'      => esc_html__( 'If you would like to control the size of the icon, you must first enable this option.', 'divi-plus' ),
			),
			'icon_font_size' => array(
				'label'            => esc_html__( 'Play Icon Font Size', 'et_builder' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'default'          => '58px',
				'default_unit'     => 'px',
				'default_on_front' => '',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'mobile_options'   => true,
				'depends_show_if'  => 'on',
				'responsive'       => true,
				'sticky'           => true,
				'hover'            => 'tabs',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'play_icon',
				'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'et_builder' ),
			),
			'thumbnail_overlay_color' => array(
				'label'            => esc_html__( 'Overlay Background Color', 'et_builder' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'default_on_front' => 'rgba(0,0,0,.6)',
				'mobile_options'   => true,
				'sticky'           => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'overlay',
				'description'      => esc_html__( 'Pick a color to use for the overlay that appears behind the play icon when hovering over the video.', 'et_builder' ),
			),
			'__video' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_StickyVideo', 'get_computed_video' ),
				'computed_depends_on' => array(
					'src',
					'src_webm',
				),
				'computed_minimum'    => array(
					'src',
					'src_webm',
				),
			),
			'__video_cover_src' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_StickyVideo', 'get_video_cover_src' ),
				'computed_depends_on' => array(
					'image_src',
				),
				'computed_minimum'    => array(
					'image_src',
				),
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

		$multi_view         = et_pb_multi_view_options( $this );
		$src                = sanitize_text_field( $this->props['src'] );
		$src_webm           = sanitize_text_field( $this->props['src_webm'] );
		$image_src          = sanitize_text_field( $this->props['image_src'] );
		$sticky_position    = sanitize_text_field( $this->props['sticky_video_position'] );
		$use_icon_font_size = sanitize_text_field( $this->props['use_icon_font_size'] );

		// Load style and script.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-sticky-video-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/StickyVideo/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'dipl-sticky-video-script', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/StickyVideo/dipl-sticky-video.min.js", array('jquery'), '1.0.1', true );

		$youtubeJSLoaded = $vimeoJSLoaded = false;
		foreach ( $multi_view->get_modes() as $mode ) {

			$video_src = $multi_view->get_inherit_value( 'src', $mode );

			// Check if it's a YouTube URL
			if ( strpos( $video_src, 'youtube.com' ) !== false || strpos( $video_src, 'youtu.be' ) !== false ) {
				$video_src = self::add_youtube_query_args( $video_src );
				if ( false === $youtubeJSLoaded ) {
					wp_enqueue_script( 'elicus-youtube-js-api-script' );
					$youtubeJSLoaded = true;
				}
			} elseif ( strpos( $video_src, 'vimeo.com' ) !== false ) {
				if ( false === $vimeoJSLoaded ) {
					wp_enqueue_script( 'elicus-vimeo-js-api-script' );
					$vimeoJSLoaded = true;
				}
			}

			$video_srcs[ $mode ] = self::get_video( array(
				'src'      => et_core_esc_previously( $video_src ),
				'src_webm' => $multi_view->get_inherit_value( 'src_webm', $mode ),
			) );
		}

		$multi_view->set_custom_prop( 'video_srcs', $video_srcs );
		$video_src = $multi_view->render_element( array(
			'tag'     => 'div',
			'content' => '{{video_srcs}}',
			'attrs'   => array(
				'class' => 'et_pb_video_box',
			),
		) );

		// Render overlay.
		$muti_view_video_overlay = $multi_view->render_element( array(
			'tag'        => 'div',
			'content'    => '<div class="et_pb_video_overlay_hover"><a href="#" class="et_pb_video_play"></a></div>',
			'attrs'      => array(
				'class' => 'et_pb_video_overlay',
			),
			'styles'     => array(
				'background-image' => 'url({{image_src}})',
			),
			'visibility' => array(
				'image_src' => '__not_empty',
			),
			'required'   => 'image_src',
		) );

		// Final render.
		$render_output = sprintf(
			'<div class="dipl_sticky_video_wrapper">
				<div class="dipl_sticky_video_inner dipl_position_%1$s">
					%2$s %3$s
				</div>
			</div>',
			esc_attr( $sticky_position ),
			( '' !== $video_src ) ? et_core_esc_previously( $video_src ) : '',
			et_core_esc_previously( $muti_view_video_overlay )
		);

		// Sticky video size.
		$this->generate_styles( array(
			'hover'          => false,
			'base_attr_name' => 'sticky_video_width',
			'selector'       => '%%order_class%% .dipl_sticky_video_inner.is-sticky',
			'css_property'   => 'width',
			'render_slug'    => $render_slug,
			'type'           => 'range',
		) );

		// Play Icon Styles.
		$this->generate_styles( array(
			'utility_arg'    => 'icon_font_family_and_content',
			'render_slug'    => $render_slug,
			'base_attr_name' => 'play_icon',
			'important'      => true,
			'selector'       => '%%order_class%% .et_pb_video_overlay .et_pb_video_play:before',
			'processor'      => array(
				'ET_Builder_Module_Helper_Style_Processor',
				'process_extended_icon',
			) )
		);
		// Play Icon color.
		$this->generate_styles( array(
			'hover'          => true,
			'base_attr_name' => 'play_icon_color',
			'selector'       => '%%order_class%% .et_pb_video_overlay .et_pb_video_play',
			'hover_selector' => '%%order_class%% .et_pb_video_overlay .et_pb_video_play:hover',
			'css_property'   => 'color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		// Play Icon Size.
		if ( 'off' !== $use_icon_font_size ) {
			// Icon Font Size.
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'icon_font_size',
				'selector'       => '%%order_class%% .et_pb_video_overlay .et_pb_video_play',
				'css_property'   => 'font-size',
				'render_slug'    => $render_slug,
				'type'           => 'range',
			) );
		}

		// Thumbnail Overlay Color.
		$this->generate_styles( array(
			'hover'          => false,
			'base_attr_name' => 'thumbnail_overlay_color',
			'selector'       => '%%order_class%% .et_pb_video_overlay_hover:hover',
			'css_property'   => 'background-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		$fields = array( 'sticky_video_margin_padding' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $render_output;
	}

	static function get_video( $args = array() ) {
		$defaults = array(
			'src'      => '',
			'src_webm' => '',
		);
		$args = wp_parse_args( $args, $defaults );
		if ( empty( $args['src'] ) && empty( $args['src_webm'] ) ) {
			return '';
		}

		$video_src = '';
		if ( false !== et_pb_check_oembed_provider( esc_url( $args['src'] ) ) ) {
			$video_src = et_builder_get_oembed( esc_url( $args['src'] ) );
		} elseif ( false !== et_pb_validate_youtube_url( esc_url( $args['src'] ) ) ) {
			$args['src'] = et_pb_normalize_youtube_url( esc_url( $args['src'] ) );
			$video_src   = et_builder_get_oembed( esc_url( $args['src'] ) );
		} else {
			$video_src = sprintf(
				'<video controls>%1$s %2$s</video>',
				( '' !== $args['src'] ? sprintf( '<source type="video/mp4" src="%s" />', esc_url( $args['src'] ) ) : '' ),
				( '' !== $args['src_webm'] ? sprintf( '<source type="video/webm" src="%s" />', esc_url( $args['src_webm'] ) ) : '' )
			);

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		return $video_src;
	}
	
	static function get_computed_video( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'src'      => '',
			'src_webm' => '',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		// Get video html.
		$video_srcs = self::get_video( array(
			'src'      => $src,
			'src_webm' => $src_webm,
		) );

		// Add fluid video wrapper.
		if ( ! empty( $video_srcs ) ) {
			// Get provider data.
			$oembed			= _wp_oembed_get_object();
			$provider		= $oembed->get_data( $src );
			$provider_name	= isset( $provider->provider_name ) ? $provider->provider_name : 'custom-upload';

			$video_srcs = sprintf(
				'<div class="fluid-width-video-wrapper %2$s">%1$s</div>',
				et_core_esc_previously( $video_srcs ),
				esc_attr( strtolower( $provider_name ) )
			);
		}

		self::$rendering = false;
		return $video_srcs;
	}

	static function get_video_cover_src( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$post_id  = isset( $current_page['id'] ) ? $current_page['id'] : self::get_current_post_id();
		$defaults = array(
			'image_src' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( isset( $args['image_src'] ) ) {
			$dynamic_value = et_builder_parse_dynamic_content( stripslashes( $args['image_src'] ) );
			if ( $dynamic_value->is_dynamic() && current_user_can( 'edit_post', $post_id ) ) {
				$args['image_src'] = $dynamic_value->resolve( $post_id );
			}
		}

		$image_output = '';
		if ( '' !== $args['image_src'] ) {
			$image_output = et_pb_set_video_oembed_thumbnail_resolution( $args['image_src'], 'high' );
		}

		return $image_output;
	}

	/**
	 * Add youtube query args.
	 *
	 * @param [type] $video_src
	 * @return void
	 */
	static function add_youtube_query_args( $video_src ) {
		// Parse URL components.
		$url_parts = parse_url( $video_src );
		
		// Parse existing query params.
		$query = [];
		if ( isset( $url_parts['query'] ) ) {
			parse_str( $url_parts['query'], $query );
		}

		// Add / override query params.
		$query['rel'] = '0';
		// $query['controls'] = '0';
		$query['modestbranding'] = '1';
		$query['enablejsapi'] = '1';

		// Rebuild query string.
		$url_parts['query'] = http_build_query( $query );

		// Rebuild the full URL.
		$video_src = (isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '')
				. (isset($url_parts['host']) ? $url_parts['host'] : '')
				. (isset($url_parts['path']) ? $url_parts['path'] : '')
				. '?' . $url_parts['query']
				. (isset($url_parts['fragment']) ? '#' . $url_parts['fragment'] : '');

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
	 *
	 * @return mixed
	 */
	public function multi_view_filter_value( $raw_value, $args ) {
		$name = isset( $args['name'] ) ? $args['name'] : '';
		if ( $raw_value && 'image_src' === $name ) {
			$raw_value = self::get_video_cover_src( array(
				'image_src' => $raw_value,
			) );
		}

		return $raw_value;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_sticky_video', $modules ) ) {
		new DIPL_StickyVideo();
	}
} else {
	new DIPL_StickyVideo();
}
