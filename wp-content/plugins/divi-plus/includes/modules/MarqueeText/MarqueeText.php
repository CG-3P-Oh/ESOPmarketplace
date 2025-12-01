<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.13.0
 */
class DIPL_MarqueeText extends ET_Builder_Module {
	public $slug       = 'dipl_marquee_text';
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
		$this->name             = esc_html__( 'DP Marquee Text', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' )
				)
			),
			'advanced' => array(
				'toggles' => array(
					'marquee_text' => esc_html__( 'Marquee Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'        => esc_html__( 'Marquee', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '20px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default' => '1.3',
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'     => "{$this->main_css_element} .dipl-marquee-text-inner .dipl-mt-text",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'marquee_text',
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
			'text'		   => false,
			'filters'      => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'marquee_text' => array(
				'label'            => esc_html__( 'Marquee Text', 'divi-plus' ),
				'type'             => 'textarea',
				'dynamic_content'  => 'text',
				'default'          => '',
				'default_on_front' => esc_html__( 'Your content goes here. Edit or remove this text inline or in the module Content settings. You can also style every aspect of this content as well.', 'divi-plus' ),
				'description'      => esc_html__( 'Enter the text to display as a marquee text.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'direction' => array(
				'label'            => esc_html__( 'Marquee Direction', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'default' => esc_html__( 'Left', 'divi-plus' ),
					'reverse' => esc_html__( 'Right', 'divi-plus' ),
				),
				'default'          => 'default',
				'default_on_front' => 'default',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can select the direction of marquee text.', 'divi-plus' ),
			),
			'marquee_speed' => array(
				'label'            => esc_html__( 'Speed (in milliseconds)', 'divi-plus' ),
				'type'             => 'range',
				'range_settings'   => array(
					'min'  => '1000',
					'max'  => '60000',
					'step' => '100',
				),
				'fixed_unit'       => 'ms',
				'default'          => '15000ms',
				'default_on_front' => '15000ms',
				'mobile_options'   => true,
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Increase or decrease the marquee animation/scroll speed.', 'divi-plus' ),
			),
			'hover_action' => array(
				'label'            => esc_html__( 'Hover Action', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'default'        => esc_html__( 'Default', 'divi-plus' ),
					'stop_on_hover'  => esc_html__( 'Stop on Hover', 'divi-plus' ),
					'start_on_hover' => esc_html__( 'Start on Hover', 'divi-plus' ),
				),
				'default'          => 'default',
				'default_on_front' => 'default',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can select the action to be perform on the hover of marquee.', 'divi-plus' ),
			),
			'text_spacing' => array(
				'label'            => esc_html__( 'Marquee Text Spacing', 'divi-plus' ),
				'type'             => 'range',
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '200',
					'step' => '1',
				),
				'fixed_unit'       => 'px',
				'default'          => '10px',
				'default_on_front' => '10px',
				'mobile_options'   => true,
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Increase or decrease the marquee text spacing when one loop of text gets completed.', 'divi-plus' ),
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

		// Get the attrs.
		$direction    = sanitize_text_field( $this->props['direction'] ) ?? 'default';
		$hover_action = sanitize_text_field( $this->props['hover_action'] ) ?? 'default';
		$marquee_text = esc_textarea( $this->props['marquee_text'] ) ?? '';

		$render_output = '';
		if ( ! empty( $marquee_text ) ) {

			// Load the css file.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-marquee-text-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/MarqueeText/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'dipl-marquee-text-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/MarqueeText/dipl-marquee-text-custom.min.js", array('jquery'), '1.0.0', true );

			// Final output.
			$render_output = sprintf(
				'<div class="dipl-marquee-text-wrap">
					<div class="dipl-marquee-text-inner dipl_marquee_%1$s dipl_marquee_on_hover_%2$s">
						<div class="dipl-mt-text">%3$s</div>
					</div>
				</div>',
				esc_attr( $direction ),
				esc_attr( $hover_action ),
				et_core_esc_previously( $marquee_text )
			);
		}

		// Speed of marquee scroll.
		if ( ! empty( $this->props['marquee_speed'] ) ) {
			$this->generate_styles( array(
				'base_attr_name' => 'marquee_speed',
				'selector'       => '%%order_class%% .dipl-marquee-text-inner',
				'css_property'   => 'animation-duration',
				'render_slug'    => $render_slug,
				'type'           => 'range',
			) );
		}

		// Marquee text spacing.
		$this->generate_styles( array(
			'base_attr_name' => 'text_spacing',
			'selector'       => '%%order_class%% .dipl-mt-text',
			'css_property'   => 'padding-right',
			'render_slug'    => $render_slug,
			'type'           => 'range',
		) );

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_marquee_text', $modules ) ) {
		new DIPL_MarqueeText();
	}
} else {
	new DIPL_MarqueeText();
}
