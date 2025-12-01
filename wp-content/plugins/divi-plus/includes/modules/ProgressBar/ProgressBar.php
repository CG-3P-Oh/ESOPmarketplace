<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.18.0
 */
class DIPL_ProgressBar extends ET_Builder_Module {
	public $slug       = 'dipl_progress_bar';
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
		$this->name             = esc_html__( 'DP Progress Bar', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'configuration' => esc_html__( 'Configuration', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'bar_styling'  => esc_html__( 'Bar Styling', 'divi-plus' ),
					'percent_text' => esc_html__( 'Percentage Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'percent_text' => array(
					'label'     => esc_html__( 'Percentage', 'divi-plus' ),
					'font_size' => array(
						'default'        => '12px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align'  => true,
					'hide_line_height' => true,
					'css'              => array(
						'main' => "{$this->main_css_element} .dipl_progress_bar_percent",
					),
					'depends_on'      => array( 'show_number' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'percent_text',
				),
			),
			'borders' => array(
				'bar' => array(
					'label_prefix' => esc_html__( 'Bar', 'divi-plus' ),
					'defaults' => array(
						'border_radii' => 'on|50px|50px|50px|50px',
					),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_progress_bar_layout_bar",
							'border_styles' => "%%order_class%% .dipl_progress_bar_layout_bar",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'layout' ),
					'depends_show_if' => 'bar',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'bar_styling',
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
			'link_options' => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'layout' => array(
				'label'            => esc_html__( 'Layout', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'bar'         => esc_html__( 'Bar', 'divi-plus' ),
					'circle'      => esc_html__( 'Circle', 'divi-plus' ),
					'half_circle' => esc_html__( 'Half Circle', 'divi-plus' ),
				),
				'default'          => 'bar',
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Select to the layout to display of progress bar.', 'divi-plus' ),
			),
			'bar_direction' => array(
				'label'            => esc_html__( 'Bar Direction', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'horizontal' => esc_html__( 'Horizontal', 'divi-plus' ),
					'vertical'   => esc_html__( 'Vertical', 'divi-plus' ),
				),
				'show_if'          => array( 'layout' => 'bar' ),
				'default'          => 'horizontal',
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Select to the direction of progress bar.', 'divi-plus' ),
			),
			'show_striped' => array(
				'label'            => esc_html__( 'Show Striped', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'show_if'          => array( 'layout' => 'bar' ),
				'default_on_front' => 'off',
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Show/Hide striped bar for the progress bar.', 'divi-plus' ),
			),
			'show_number' => array(
				'label'            => esc_html__( 'Show Progress Number', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default_on_front' => 'on',
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Show/Hide progress number for the progress bar.', 'divi-plus' ),
			),
			'bar_size' => array(
				'label'            => esc_html__( 'Bar Size', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '30px',
				'default_on_front' => '30px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'range_settings'   => array(
					'min'  => '5',
					'max'  => '150',
					'step' => '1',
				),
				'show_if'          => array( 'layout' => 'bar' ),
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_styling',
				'description'      => esc_html__( 'Adjust the bar size.', 'divi-plus' ),
			),
			'bar_height' => array(
				'label'            => esc_html__( 'Bar Height', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'layout',
				'allowed_values'   => et_builder_get_acceptable_css_string_values( 'height' ),
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '1200',
					'step' => '1',
				),
				'show_if'          => array(
					'layout'        => 'bar',
					'bar_direction' => 'vertical'
				),
				'default'          => '500px',
				'default_unit'     => 'px',
				'mobile_options'   => true,
				'validate_unit'    => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_styling',
				'description'      => esc_html__( 'Adjust the bar height, percentage height will only work on fixed position and have to manage module size too.', 'divi-plus' ),
			),
			'circle_size' => array(
				'label'            => esc_html__( 'Circle Size', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '150px',
				'default_on_front' => '150px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'range_settings'   => array(
					'min'  => '50',
					'max'  => '700',
					'step' => '1',
				),
				'show_if_not'      => array( 'layout' => 'bar' ),
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_styling',
				'description'      => esc_html__( 'Adjust the circle/half circle size.', 'divi-plus' ),
			),
			'circle_fill_color' => array(
				'label'            => esc_html__( 'Circle Background Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'mobile_options'   => false,
				'hover'            => 'tabs',
				'default'          => '#131313',
				'default_on_front' => '#131313',
				'show_if_not'      => array( 'layout' => 'bar' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_styling',
				'description'      => esc_html__( 'Select the background color of circle or half circle.', 'divi-plus' ),
			),
			'bar_empty_color' => array(
				'label'            => esc_html__( 'Bar Empty Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'mobile_options'   => false,
				'hover'            => 'tabs',
				'default'          => '#eeeeee',
				'default_on_front' => '#eeeeee',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_styling',
				'description'      => esc_html__( 'Select the bar empty color.', 'divi-plus' ),
			),
			'bar_filled_color' => array(
				'label'            => esc_html__( 'Bar Filled Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'mobile_options'   => false,
				'hover'            => 'tabs',
				'default'          => '#007bff',
				'default_on_front' => '#007bff',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_styling',
				'description'      => esc_html__( 'Select the bar filled color.', 'divi-plus' ),
			),
			'percent_align' => array(
                'label'           => esc_html__( 'Percentage Align', 'divi-plus' ),
                'type'            => 'text_align',
                'option_category' => 'layout',
                'options'         => et_builder_get_text_orientation_options( array( 'justified' ) ),
                'mobile_options'  => false,
				'default'         => 'center',
				'show_if'         => array( 'layout' => 'bar' ),
                'tab_slug'     	  => 'advanced',
				'toggle_slug'     => 'percent_text',
                'description'     => esc_html__( 'Here you can select the alignment of the toggle switch in the left, right, or center of the module.', 'divi-plus' ),
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
		$layout       = sanitize_text_field( $this->props['layout'] ) ?? 'bar';
		$show_number  = sanitize_text_field( $this->props['show_number'] ) ?? 'on';
		$show_striped = sanitize_text_field( $this->props['show_striped'] ) ?? 'off';

		// Load style and script.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-progress-bar-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ProgressBar/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'dipl-progress-bar-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/ProgressBar/dipl-progress-bar-custom.min.js", array('jquery'), '1.0.0', true );

		// Wrapper classes and other attrs.
		$wrapper_class   = array( 'dipl_progress_bar_wrapper' );
		$wrapper_class[] = 'dipl_progress_bar_layout_' . esc_attr( $layout );

		$data_props   = array( 'show_number' );
		$layout_shape = '';
		if ( 'circle' === $layout ) {
			$layout_shape = '<svg class="dipl_progress_bar_circle" viewBox="0 0 100 100">
				<circle class="dipl_fill_progress_bar_bg" cx="50" cy="50" r="45"/>
				<circle class="dipl_circle_bg" cx="50" cy="50" r="45"/>
				<circle class="dipl_circle_fg" cx="50" cy="50" r="45"/>
			</svg>';
		} elseif ( 'half_circle' === $layout ) {
			$layout_shape = '<svg class="dipl_half_circle" viewBox="0 0 200 100">
				<path class="dipl_circle_bg" d="M 10 100 A 90 90 0 0 1 190 100"></path>
				<path class="dipl_circle_fg" d="M 10 100 A 90 90 0 0 1 190 100"></path>
			</svg>';
		} else {
			$data_props = array_merge( $data_props, array( 'bar_direction' ) );
			if ( 'on' === $show_striped ) {
				$wrapper_class[] = 'dipl_progress_bar_striped';
			}
		}

		// Convert to data attrs.
		$data_atts = $this->props_to_html_data_attrs( $data_props );

		// Number bar.
		$percent_bar = '';
		if ( 'on' === $show_number ) {
			$percent_bar = '<span class="dipl_progress_bar_percent">0%</span>';
		}

		$render_output = sprintf(
			'<div class="%1$s" %2$s>
				<div class="dipl_progress_bar_inner">%3$s %4$s</div>
			</div>',
			implode( ' ', $wrapper_class ),
			et_core_esc_previously( $data_atts ),
			et_core_esc_previously( $layout_shape ),
			et_core_esc_previously( $percent_bar )
		);

		// Bar styling.
		if ( 'bar' === $layout ) {
			$size_property = ( 'vertical' === $this->props['bar_direction'] ) ? 'width' : 'height';
			$bar_size      = et_pb_responsive_options()->get_property_values( $this->props, 'bar_size' );
			if ( ! empty( array_filter( $bar_size ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $bar_size, '%%order_class%% .dipl_progress_bar_layout_bar', $size_property, $render_slug, '!important;', 'range' );
			}

			// Bar height.
			$this->generate_styles( array(
				'hover'          => false,
				'base_attr_name' => 'bar_height',
				'selector'       => '%%order_class%% .dipl_progress_bar_wrapper[data-bar_direction="vertical"]',
				'css_property'   => 'height',
				'render_slug'    => $render_slug,
				'type'           => 'range',
			) );
			
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'bar_empty_color',
				'selector'       => '%%order_class%% .dipl_progress_bar_wrapper',
				'hover_selector' => '%%order_class%% .dipl_progress_bar_wrapper:hover',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'bar_filled_color',
				'selector'       => '%%order_class%% .dipl_progress_bar_layout_bar .dipl_progress_bar_inner',
				'hover_selector' => '%%order_class%% .dipl_progress_bar_layout_bar:hover .dipl_progress_bar_inner',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Alignment of percentage number.
			$percent_align = et_pb_responsive_options()->get_property_values( $this->props, 'percent_align' );
			foreach ( $percent_align as &$align ) {
				$align = str_replace( array( 'left', 'right', 'justify' ), array( 'flex-start', 'flex-end', 'flex-start' ), $align );
			}
			if ( ! empty( array_filter( $percent_align ) ) ) {
				$align_property = ( 'vertical' === $this->props['bar_direction'] ) ? 'align-items' : 'justify-content';
				et_pb_responsive_options()->generate_responsive_css( $percent_align, '%%order_class%% .dipl_progress_bar_layout_bar .dipl_progress_bar_inner', $align_property, $render_slug, '!important;', 'type' );
			}
		} else {
			$this->generate_styles( array(
				'hover'          => false,
				'base_attr_name' => 'circle_size',
				'selector'       => '%%order_class%% .dipl_progress_bar_wrapper',
				'css_property'   => 'width',
				'important'      => true,
				'render_slug'    => $render_slug,
				'type'           => 'range',
			) );
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'circle_fill_color',
				'selector'       => '%%order_class%% .dipl_progress_bar_inner svg .dipl_circle_bg',
				'hover_selector' => '%%order_class%% .dipl_progress_bar_inner:hover svg .dipl_circle_bg',
				'css_property'   => 'fill',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'bar_empty_color',
				'selector'       => '%%order_class%% .dipl_progress_bar_inner svg .dipl_circle_bg',
				'hover_selector' => '%%order_class%% .dipl_progress_bar_inner:hover svg .dipl_circle_bg',
				'css_property'   => 'stroke',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'bar_filled_color',
				'selector'       => '%%order_class%% .dipl_progress_bar_inner svg .dipl_circle_fg',
				'hover_selector' => '%%order_class%% .dipl_progress_bar_inner:hover svg .dipl_circle_fg',
				'css_property'   => 'stroke',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_progress_bar', $modules ) ) {
		new DIPL_ProgressBar();
	}
} else {
	new DIPL_ProgressBar();
}
