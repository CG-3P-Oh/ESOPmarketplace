<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_ImageHoverEffect extends ET_Builder_Module {
	public $slug       = 'dipl_image_hover_effect';
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
		$this->name             = esc_html__( 'DP Image Hover Effect', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
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
					'alignment' => esc_html__( 'Alignment', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '%%order_class%% .dipl_image_hover_effect_inner',
							'border_radii'  => '%%order_class%% .dipl_image_hover_effect_inner',
						),
					),
				)
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main'    => '%%order_class%% .dipl_image_hover_effect_inner',
						'overlay' => 'inset',
					),
				)
			),
			'max_width'      => array(
				'options' => array(
					'width'     => array(
						'depends_show_if' => 'off',
					),
					'max_width' => array(
						'depends_show_if' => 'off',
					),
				),
			),
			'height'         => array(
				'css' => array(
					'main' => '%%order_class%% .dipl_image_hover_effect_inner img',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%%',
					'important' => 'all',
				),
			),
			'background' => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
			'fonts'  => false,
			'text'   => false,
			'button' => false,
		);
	}

	public function get_fields() {
		return array(
			'image' => array(
				'label'              => esc_html__( 'Select Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can upload the image to display.', 'divi-plus' ),
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image Alternative Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input the text to be used for the image as alternative text.', 'divi-plus'),
			),
			'hover_effect' => array(
				'label'            => esc_html__( 'Hover Effect', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'radial'        => esc_html__( 'Radial', 'divi-plus' ),
					'glow'          => esc_html__( 'Glow', 'divi-plus' ),
					'rotate'        => esc_html__( 'Rotate', 'divi-plus' ),
					'flash'         => esc_html__( 'Flash', 'divi-plus' ),
					'flash_instant' => esc_html__( 'Flash Instant', 'divi-plus' ),
					'diagonal'      => esc_html__( 'Diagonal', 'divi-plus' ),
					'glitch'        => esc_html__( 'Glitch', 'divi-plus' ),
					'slide_glitch'  => esc_html__( 'Slide Glitch', 'divi-plus' ),
				),
				'default'          => 'radial',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can choose what type of content you want to display inside modal body.', 'divi-plus'),
			),
			'force_fullwidth'     => array(
				'label'            => esc_html__( 'Force Fullwidth', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => et_builder_i18n( 'No' ),
					'on'  => et_builder_i18n( 'Yes' ),
				),
				'default_on_front' => 'off',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'width',
				// 'show_if_not'      => array(
				// 	'hover_effect' => 'slide_glitch'
				// ),
				'affects'          => array(
					'max_width',
					'width',
				),
				'description'      => esc_html__( "When enabled, this will force your image to extend 100% of the width of the column it's in.", 'divi-plus' ),
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

		$multi_view      = et_pb_multi_view_options( $this );
		$force_fullwidth = $this->props['force_fullwidth'];

		$hover_effect    = sanitize_text_field( $this->props['hover_effect'] ) ?? 'radial';

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-image-hover-effect-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ImageHoverEffect/' . $file . '.min.css', array(), '1.0.0' );

		// Render image.
		$render_image = $multi_view->render_element( array(
			'tag'      => 'img',
			'attrs'    => array(
				'src' => '{{image}}',
				'alt' => '{{image_alt}}',
			),
			'required' => 'image',
		) );

		// If image, wrap it with output.
		$render_output = '';
		if ( ! empty( $render_image ) ) {

			// Effect classes.
			$classes = array( 'dipl_image_hover_effect_inner' );
			if ( 'radial' === $hover_effect ) {
				$classes[] = 'circle zoom';
			} elseif ( 'glow' === $hover_effect ) {
				$classes[] = 'filter zoom';
			} elseif ( 'rotate' === $hover_effect ) {
				$classes[] = 'rotate';
			} elseif ( 'flash' === $hover_effect ) {
				$classes[] = 'flash filter zoom';
			} elseif ( 'flash_instant' === $hover_effect ) {
				$classes[] = 'flash_inst_rev filter zoom';
			} elseif ( 'diagonal' === $hover_effect ) {
				$classes[] = 'diagonal zoom';
			} elseif ( 'glitch' === $hover_effect ) {
				$classes[] = 'dipl-glitch-image';
			} elseif ( 'slide_glitch' === $hover_effect ) {
				$classes[]     = 'dipl-slide-glitch';
				$render_image .= (
					'<div class="dipl-slide-glitch-overlay">
						<div class="quadrant quad1"></div>
						<div class="quadrant quad2"></div>
						<div class="quadrant quad3"></div>
						<div class="quadrant quad4"></div>
					</div>'
				);
			}

			// Render final output.
			$render_output = $multi_view->render_element( array(
				'tag'     => 'div',
				'attrs'   => array(
					'class' => sprintf( 'dipl_image_hover_effect_wrapper dipl-effect-%1$s', esc_attr( $hover_effect ) )
				),
				'content' => sprintf(
					'<div class="%1$s">%2$s</div>',
					implode( ' ', $classes ),
					et_core_esc_previously( $render_image ),
				),
			) );
		}

		// Force fullwidth.
		if ( 'on' === $force_fullwidth ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%%',
				'declaration' => 'width: 100%; max-width: 100% !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_image_hover_effect_inner, %%order_class%% img',
				'declaration' => 'width: 100%;',
			) );
		}

		if ( 'glitch' === $hover_effect && ! empty( $this->props['image'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-glitch-image::before',
				'declaration' => sprintf( 'background-image: url(%1$s)', esc_url( $this->props['image'] ) ),
			) );
		}
		if ( 'slide_glitch' === $hover_effect && ! empty( $this->props['image'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-slide-glitch',
				'declaration' => sprintf( 'background-image: url(%1$s)', esc_url( $this->props['image'] ) ),
			) );
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_image_hover_effect', $modules ) ) {
		new DIPL_ImageHoverEffect();
	}
} else {
	new DIPL_ImageHoverEffect();
}
