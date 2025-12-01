<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.14.0
 */
class DIPL_ScrollText extends ET_Builder_Module {
	public $slug       = 'dipl_scroll_text';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	/**
	 * Track if the module is currently rendering to prevent unnecessary rendering and recursion.
	 *
	 * @var bool
	 */
	protected static $rendering = false;

	public function init() {
		$this->name = esc_html__( 'DP Scroll Text', 'divi-plus' );
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
					'text_style' => esc_html__( 'Text Style', 'divi-plus' ),
					'text'       => esc_html__( 'Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'text' => array(
					'label' => esc_html__( '', 'divi-plus' ),
					'font_size' => array(
						'default'        => '55px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '500',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'letter_spacing' => array(
						'default' => '1.3',
					),
					'css' => array(
						'main'     => "%%order_class%% .dipl-scroll-text-inner, %%order_class%% .dipl-scroll-text-inner span",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'text',
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
			'text'         => false,
			'text_shadow'  => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		$et_accent_color = et_builder_accent_color();
		return array(
			'text' => array(
				'label'            => esc_html__( 'Text', 'divi-plus' ),
				'type'             => 'textarea',
				'dynamic_content'  => 'text',
				'default'          => esc_html__( "Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.", 'divi-plus' ),
				'default_on_front' => esc_html__( "Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.", 'divi-plus' ),
				'description'      => esc_html__( 'Enter the text to display as a marquee text.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'scroll_effect' => array(
				'label'            => esc_html__( 'Scroll Effect', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'fade'    => esc_html__( 'Fade', 'divi-plus' ),
					'blur'    => esc_html__( 'Blur', 'divi-plus' ),
					'color'   => esc_html__( 'Color', 'divi-plus' ),
					'slide'   => esc_html__( 'Slide', 'divi-plus' ),
					'3d_flip' => esc_html__( '3D Flip', 'divi-plus' ),
					'skew'    => esc_html__( 'Skew', 'divi-plus' ),
				),
				'mobile_options'   => false,
				'default'          => 'fade',
				'default_on_front' => 'fade',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Select the animation effect to perform on scroll.', 'divi-plus' ),
			),
			'split' => array(
				'label'            => esc_html__( 'Text Split By', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'word'   => esc_html__( 'Word', 'divi-plus' ),
					'letter' => esc_html__( 'Letter', 'divi-plus' ),
				),
				'mobile_options'   => false,
				'default'          => 'word',
				'default_on_front' => 'word',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Select the animation effect to perform on scroll.', 'divi-plus' ),
			),
			'slide_effect_start' => array(
				'label'           => esc_html__( 'Slide Effect Start Position', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '40px',
				'default_unit'    => 'px',
				'allowed_units'   => array( 'px' ),
				'show_if'         => array(
					'scroll_effect' => 'slide',
				),
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the slide effect start position.', 'divi-plus' ),
			),
			'skew_effect_start' => array(
				'label'           => esc_html__( 'Skew Effect Start Position', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '35deg',
				'default_unit'    => 'deg',
				'allowed_units'   => array( 'px' ),
				'show_if'         => array(
					'scroll_effect' => 'skew',
				),
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the slide effect start position.', 'divi-plus' ),
			),
			'animation_start_element_pos' => array(
				'label'           => esc_html__( 'Start Element Position', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '0%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the element\'s position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'animation_start_viewport_pos' => array(
				'label'           => esc_html__( 'Start Viewport Position', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '80%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the viewport\'s position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'animation_end_element_pos' => array(
				'label'           => esc_html__( 'End Element Position', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '0%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the element\'s position to trigger the animation when it ends the specified location.', 'divi-plus' ),
			),
			'animation_end_viewport_pos' => array(
				'label'           => esc_html__( 'End Viewport Position', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '40%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'animation',
				'description'     => esc_html__( 'Increase or decrease the viewport\'s position to trigger the animation when it ends the specified location.', 'divi-plus' ),
			),
			'active_text_color' => array(
				'label'            => esc_html__( 'Active/Visible Text Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'default'          => $et_accent_color,
				'default_on_front' => $et_accent_color,
				'hover'            => 'tabs',
				'show_if'          => array(
					'scroll_effect' => 'color',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'text_style',
				'description'      => esc_html__( 'Please select a color when the text gets visible on the scroll.', 'divi-plus' ),
			),
			'text_blur_level' => array(
				'label'           => esc_html__( 'Normal Text Blur', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '20',
					'step' => '1',
				),
				'show_if'         => array(
					'scroll_effect' => 'blur',
				),
				'default'         => '6px',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'text_style',
				'description'     => esc_html__( 'Increase or decrease the nomral text blur level.', 'divi-plus' ),
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a DIPL Woo Product module while a DIPL Woo Product module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		// Load the css file.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-scroll-text-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ScrollText/' . $file . '.min.css', array(), '1.0.0' );

		// Enqueue ScrollTrigger, and gsap script.
		wp_enqueue_script( 'elicus-scroll-trigger-script' );
		wp_enqueue_script( 'elicus-gsap-script' );
		wp_enqueue_script( 'dipl-scroll-text-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/ScrollText/dipl-scroll-text-custom.min.js", array('jquery'), '1.0.0', true );

		// Get attrs.
		$scroll_effect = sanitize_text_field( $this->props['scroll_effect'] ) ?? 'fade';
		$text          = sanitize_textarea_field( $this->props['text'] ) ?? esc_html__( "Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content in the module Design settings and even apply custom CSS to this text in the module Advanced settings.", 'divi-plus' );

		// Get props.
		$data_props = array(
			'scroll_effect',
			'split',
			'animation_start_element_pos',
			'animation_start_viewport_pos',
			'animation_end_element_pos',
			'animation_end_viewport_pos'
		);
		$data_atts = $this->props_to_html_data_attrs( $data_props );

		// Final output.
		$output = sprintf(
			'<div class="dipl-scroll-text-wrap" %2$s>
				<div class="dipl-scroll-text-inner">%1$s</div>
			</div>',
			nl2br( $text, false ),
			et_core_esc_previously( $data_atts )
		);

		// Scroll effect.
		if ( 'color' === $this->props['scroll_effect'] ) {
			$this->generate_styles( array(
				'base_attr_name' => 'active_text_color',
				'selector'       => '%%order_class%% .dipl_scroll_word_color .dipl_st_word.visible, %%order_class%% .dipl_scroll_letter_color .dipl_st_letter.visible',
				'hover_selector' => '%%order_class%% .dipl_scroll_word_color .dipl_st_word.visible:hover, %%order_class%% .dipl_scroll_letter_color .dipl_st_letter.visible:hover',
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		} elseif ( 'blur' === $this->props['scroll_effect'] && ! empty( $this->props['text_blur_level'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_scroll_word_blur .dipl_st_word, %%order_class%% .dipl_scroll_letter_blur .dipl_st_letter',
				'declaration' => sprintf( 'filter: blur( %1$s );', esc_attr( $this->props['text_blur_level'] ) ),
			) );
		}

		// If skew scroll effect.
		if ( 'slide' === $scroll_effect && ! empty( $this->props['slide_effect_start'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_scroll_word_slide .dipl_st_word, %%order_class%% .dipl_scroll_letter_slide .dipl_st_letter',
				'declaration' => sprintf( 'transform: translateY( %1$s );', esc_attr( $this->props['slide_effect_start'] ) ),
			) );
		}
		if ( 'skew' === $scroll_effect && ! empty( $this->props['skew_effect_start'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_scroll_word_skew .dipl_st_word, %%order_class%% .dipl_scroll_letter_skew .dipl_st_letter',
				'declaration' => sprintf( 'transform: skewY( %1$s );', esc_attr( $this->props['skew_effect_start'] ) ),
			) );
		}

		self::$rendering = false;
		return $output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_scroll_text', $modules ) ) {
		new DIPL_ScrollText();
	}
} else {
	new DIPL_ScrollText();
}
