<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */
class DIPL_SVGAnimator extends ET_Builder_Module {
	public $slug       = 'dipl_svg_animator';
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
		$this->name             = esc_html__( 'DP SVG Animator', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'animation'    => esc_html__( 'Animation', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'svg_style' => esc_html__( 'SVG Styling', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(

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
			'text'         => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'svg_image' => array(
				'label'              => esc_html__( 'SVG Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload a SVG', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose a SVG', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As SVG', 'divi-plus' ),
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can upload the SVG image file to animate.', 'divi-plus' ),
				'computed_affects' 	=> array(
					'__svg_image_data',
				),
			),
			'svg_anim_type' => array(
				'label'           => esc_html__( 'Animation Type', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'delayed'  => esc_html__( 'Delayed', 'divi-plus' ),
					'sync'     => esc_html__( 'Sync', 'divi-plus' ),
					'onebyone' => esc_html__( 'One By One', 'divi-plus' ),
				),
				'default'         => 'delayed',
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Select the animation type for SVG image.', 'divi-plus' ),
			),
			'svg_anim_duration' => array(
				'label'           => esc_html__( 'Animation Duration', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'configuration',
				'range_settings'  => array(
					'min'   => '10',
					'max'   => '500',
					'step'  => '1',
				),
				'unitless'        => true,
				'default'         => '100',
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the animation duration for the SVG image.', 'divi-plus' ),
			),
			'svg_anim_curves' => array(
				'label'           => esc_html__( 'Animation Curves', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'linear'      => esc_html__( 'Linear', 'divi-plus' ),
					'ease'        => esc_html__( 'Ease', 'divi-plus' ),
					// 'ease-in'  => esc_html__( 'Ease-in', 'divi-plus' ),
					// 'ease-out' => esc_html__( 'Ease-out', 'divi-plus' ),
					'ease-in-out' => esc_html__( 'Ease-in-out', 'divi-plus' ),
					'bounce'      => esc_html__( 'Ease-out Bounce', 'divi-plus' ),
					// 'back'     => esc_html__( 'Ease-out Back', 'divi-plus' ),
				),
				'default'         => 'linear',
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Select the animation Curves/Easing Functions for SVG image.', 'divi-plus' ),
			),
			're_animate_on_click' => array(
				'label'           => esc_html__( 'Re-Animate on Click', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Here you can choose whether or not to re-animate the SVG image on click.', 'divi-plus' ),
			),
			'svg_align' => array(
				'label'            => esc_html__( 'SVG Alignment', 'divi-plus' ),
				'type'             => 'align',
				'option_category'  => 'layout',
				'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'svg_style',
				'description'      => esc_html__( 'Here you can choose the alignment of SVG image.', 'divi-plus' ),
			),
			'svg_width' => array(
				'label'           => esc_html__( 'SVG Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '700',
					'step' => '1',
				),
				'default'         => '100%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'em', 'rem', 'px' ),
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'svg_style',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the SVG image.', 'divi-plus' ),
			),
			'svg_color' => array(
				'label'          => esc_html__( 'SVG Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'svg_style',
				'description'    => esc_html__( 'Here you can define a custom color for your SVG image.', 'divi-plus' ),
			),
			'svg_stroke_width' => array(
				'label'           => esc_html__( 'SVG Stroke/Line Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '20',
					'step' => '1',
				),
				'default'         => '2px',
				'default_unit'    => 'px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'svg_style',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the SVG Stroke/Line Width.', 'divi-plus' ),
			),
			'__svg_image_data' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_SVGAnimator', 'get_computed_svg_image' ),
				'computed_depends_on' => array(
					'svg_image',
				),
			),
		);
	}

	public static function get_computed_svg_image( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'svg_image' => '',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		// Get svg image content.
		$svg_content = self::get_svg_image_content( $svg_image );

		self::$rendering = false;
		return $svg_content;
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		// Get attrs.
		$svg_image = esc_url( $this->props['svg_image'] ) ?? '';

		// Get svg image content.
		$svg_content = self::get_svg_image_content( $svg_image );

		$render_output = '';
		if ( ! empty( $svg_content ) ) {

			// Load style and script files.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-svg-animator-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/SVGAnimator/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'dipl-svg-animator-script', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/SVGAnimator/dipl-svg-animator.min.js', array('jquery'), '1.0.0', true );

			// Data props for the wrapper.
			$data_props = array(
				'svg_anim_type',
				'svg_anim_duration',
				'svg_anim_curves',
				're_animate_on_click',
			);
			$data_atts = $this->props_to_html_data_attrs( $data_props );

			// Final output.
			$render_output = sprintf(
				'<div class="dipl_svg_animator_wrapper" %1$s>
					<div class="dipl_svg_animator_inner">%2$s</div>
				</div>',
				et_core_esc_previously( $data_atts ),
				et_core_esc_previously( $svg_content )
			);

			// SVG alignment.
			$svg_align = et_pb_responsive_options()->get_property_values( $this->props, 'svg_align' );
			if ( ! empty( array_filter( $svg_align ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $svg_align, "%%order_class%% .dipl_svg_animator_inner", 'text-align', $render_slug, '!important;', 'type' );
			}
			// SVG image width.
			$svg_width = et_pb_responsive_options()->get_property_values( $this->props, 'svg_width' );
			if ( ! empty( array_filter( $svg_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $svg_width, "%%order_class%% .dipl_svg_animator_wrapper svg", 'width', $render_slug, '!important;', 'range' );
			}
			// SVG Color.
			$this->generate_styles( array(
				'base_attr_name' => 'svg_color',
				'selector'       => "{$this->main_css_element} .dipl_svg_animator_wrapper svg *",
				'hover_selector' => "{$this->main_css_element} .dipl_svg_animator_wrapper svg:hover *",
				'important'      => true,
				'css_property'   => 'stroke',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			// SVG stroke width.
			$svg_stroke_width = et_pb_responsive_options()->get_property_values( $this->props, 'svg_stroke_width' );
			if ( ! empty( array_filter( $svg_stroke_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $svg_stroke_width, "%%order_class%% .dipl_svg_animator_wrapper svg *", 'stroke-width', $render_slug, '!important;', 'range' );
			}
		}

		self::$rendering = false;
		return $render_output;
	}

	/**
	 * Get the contents of an SVG file from a URL or local path.
	 *
	 * This function first attempts to read the file using `file_get_contents`
	 * (with relaxed SSL verification for HTTPS if necessary). If that fails,
	 * it falls back to using WordPress's HTTP API `wp_remote_get()`.
	 *
	 * @param string $svg_file URL or local path to the SVG file.
	 * @return string The raw SVG contents, or an empty string if it cannot be retrieved.
	 */
	private static function get_svg_image_content( $svg_file ) {
		if ( empty( $svg_file ) ) {
			return '';
		}

		// If allow_url_fopen is enabled, try file_get_contents first.
		if ( ini_get( 'allow_url_fopen' ) ) {
			$context = [];
			if ( strpos( $svg_file, 'https://' ) === 0 ) {
				$context = array( 'ssl' => [
					'verify_peer'      => false,
					'verify_peer_name' => false,
				] );
			}
			$content = @file_get_contents( $svg_file, false, stream_context_create( $context ) );
			if ( $content !== false ) {
				return $content;
			}
		}

		// Fallback to WordPress HTTP API.
		$response = wp_remote_get( $svg_file, [
			'timeout'   => 10,
			'user-agent'=> 'Mozilla/5.0',
			'sslverify' => false, // optional, only if necessary.
		] );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$body = wp_remote_retrieve_body( $response );

		return $body ?: '';
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_svg_animator', $modules ) ) {
		new DIPL_SVGAnimator();
	}
} else {
	new DIPL_SVGAnimator();
}
