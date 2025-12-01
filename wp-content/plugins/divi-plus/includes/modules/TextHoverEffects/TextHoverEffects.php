<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */
class DIPL_TextHoverEffects extends ET_Builder_Module {
	public $slug       = 'dipl_text_hover_effects';
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
		$this->name             = esc_html__( 'DP Text Hover Effects', 'divi-plus' );
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
					'effect_text' => esc_html__( 'Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'text' => array(
					'label'     => esc_html__( 'Title Style', 'divi-plus' ),
					'font_size' => array(
						'default'        => '8em',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'font_weight' => array(
						'default' => '700',
					),
					'text_align' => array(
						'default' => 'center',
					),
					'hide_text_color' => true,
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_text_hover_effects_text",
						'important' => 'all',
					),
					'tab_slug'	  => 'advanced',
                    'toggle_slug' => 'effect_text',
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
			'text_effect' => array(
				'label'           => esc_html__( 'Text Effect', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'effect1' => esc_html__( 'Effect 1', 'divi-plus' ),
					'effect2' => esc_html__( 'Effect 2', 'divi-plus' ),
					'effect3' => esc_html__( 'Effect 3', 'divi-plus' ),
					'effect4' => esc_html__( 'Effect 4', 'divi-plus' ),
					'effect5' => esc_html__( 'Effect 5', 'divi-plus' ),
					'effect6' => esc_html__( 'Effect 6', 'divi-plus' ),
					'effect7' => esc_html__( 'Effect 7', 'divi-plus' ),
				),
				'default'          => 'effect1',
				'default_on_front' => 'effect1',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Select the effect for the text.', 'divi-plus' ),
			),
			'text' => array(
				'label'            => esc_html__( 'Text to Effect', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'          => esc_html__( 'Lorem', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Lorem', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can enter the text for hover effect.', 'divi-plus' ),
			),
			'normal_text_color' => array(
				'label'        => esc_html__( 'Normal Text Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => '#b1b1b1',
				'hover'        => false,
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'effect_text',
				'description'  => esc_html__( 'Here you can choose a custom color to be used for the text.', 'divi-plus' ),
			),
			'hover_text_color' => array(
				'label'        => esc_html__( 'Hover Text Color ', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => '#333333',
				'hover'        => false,
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'effect_text',
				'description'  => esc_html__( 'Here you can choose a custom hover color to be used for the text.', 'divi-plus' ),
			),
			'bar_color' => array(
				'label'        => esc_html__( 'Bar/Line Color ', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => '#f7f7f7',
				'hover'        => false,
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'effect_text',
				'description'  => esc_html__( 'Here you can choose a custom color to be used for bars or lines.', 'divi-plus' ),
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

		// Get attributes.
		$text_effect      = sanitize_text_field( $this->props['text_effect'] ) ?? 'effect1';
		$text             = trim( sanitize_text_field( $this->props['text'] ) ) ?? esc_html__( 'Lorem', 'divi-plus' );
		
		$hover_text_color = sanitize_text_field( $this->props['hover_text_color'] ) ?? '';
		$bar_color        = sanitize_text_field( $this->props['bar_color'] ) ?? '';

		// Load the scripts.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-text-hover-effects-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/TextHoverEffects/' . $file . '.min.css', array(), '1.0.0' );

		// Get text for differnt layouts.
		$text_html = '';
		if ( 'effect7' === $text_effect ) {
			$text_html = sprintf(
				'<span data-text="%1$s">%1$s</span>',
				esc_html( $text )
			);
		} elseif ( 'effect6' === $text_effect ) {
			$text_html = sprintf(
				'<span>%1$s<span data-text="%1$s"></span><span data-text="%1$s"></span></span>',
				esc_html( $text )
			);
		} elseif ( 'effect5' === $text_effect ) {
			$words     = explode( ' ', $text );
			$delay     = 0; // running delay in seconds.
			$span_text = '';
			foreach ( $words as $word ) {
				$chars = str_split( $word );
				$inner_spans = '';
				foreach ( $chars as $ch ) {
					$inner_spans .= '<span style="-webkit-transition-delay:' . $delay . 's;transition-delay:' . $delay . 's;">' . $ch . '</span>';
					$delay += 0.1; // increase delay per letter.
				}
				$span_text .= '<span>' . $inner_spans . '</span> '; // outer span per word + space.
			}

			$text_html = sprintf(
				'<span data-text="%1$s">%2$s</span>',
				esc_html( $text ),
				et_core_esc_previously( $span_text )
			);
		} elseif ( 'effect4' === $text_effect ) {
			$text_html = sprintf( '<span><span data-text="%1$s">%1$s</span></span>', esc_html( $text ) );
		} elseif ( 'effect3' === $text_effect ) {

			$len   = strlen( $text );
			$left  = substr( $text, 0, ceil( $len / 2) );
			$right = substr( $text, ceil( $len / 2 ) );

			$text_html = sprintf(
				'<span><span data-text-left="%1$s" data-text-right="%2$s">%3$s</span></span>',
				esc_html( $left ),
				esc_html( $right ),
				esc_html( $text )
			);

		} elseif ( 'effect2' === $text_effect ) {
			$text_html = sprintf( '<span>%1$s</span>', esc_html( $text ) );
		} else {
			$text_html = sprintf( '<span data-text="%1$s">%1$s</span>', esc_html( $text ) );
		}

		// Final output.
		$render_output = sprintf(
			'<div class="dipl_text_hover_effects_wrapper dipl_text_%1$s">
				<div class="dipl_text_hover_effects_text">%2$s</div>
			</div>',
			esc_attr( $text_effect ),
			et_core_esc_previously( $text_html )
		);

		// Text color.
		$this->generate_styles( array(
			'base_attr_name' => 'normal_text_color',
			'selector'       => '%%order_class%% .dipl_text_hover_effects_text',
			'css_property'   => 'color',
			'important'      => true,
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		// Styling by effects.
		if ( 'effect7' === $text_effect ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect7 .dipl_text_hover_effects_text > span:hover',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect7 .dipl_text_hover_effects_text > span::before',
				'declaration' => "background-color: {$bar_color} !important;",
			) );
		} elseif ( 'effect6' === $text_effect ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect6 .dipl_text_hover_effects_text > span span::before',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect6 .dipl_text_hover_effects_text > span::before',
				'declaration' => "background-color: {$bar_color} !important;",
			) );
		} elseif ( 'effect5' === $text_effect ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect5 .dipl_text_hover_effects_text > span:hover span',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect5 .dipl_text_hover_effects_text > span::before',
				'declaration' => "border-color: {$bar_color} !important;",
			) );
		} elseif ( 'effect4' === $text_effect ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect4 .dipl_text_hover_effects_text > span span::before',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect4 .dipl_text_hover_effects_text > span::after',
				'declaration' => "background-color: {$bar_color} !important;",
			) );
		} elseif ( 'effect3' === $text_effect ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect3 .dipl_text_hover_effects_text > span span::before, %%order_class%% .dipl_text_effect3 .dipl_text_hover_effects_text > span span::after',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect3 .dipl_text_hover_effects_text > span::before, %%order_class%% .dipl_text_effect3 .dipl_text_hover_effects_text > span::after',
				'declaration' => "background-color: {$bar_color} !important;",
			) );
		} elseif ( 'effect2' === $text_effect ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect2 .dipl_text_hover_effects_text > span:hover',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect2 .dipl_text_hover_effects_text > span::before',
				'declaration' => "background-color: {$bar_color} !important;",
			) );
		} else {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect1 .dipl_text_hover_effects_text > span::before',
				'declaration' => "color: {$hover_text_color} !important;",
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_text_effect1 .dipl_text_hover_effects_text > span::after',
				'declaration' => "background-color: {$bar_color} !important;",
			) );
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_text_hover_effects', $modules ) ) {
		new DIPL_TextHoverEffects();
	}
} else {
	new DIPL_TextHoverEffects();
}
