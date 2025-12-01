<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */
class DIPL_BarChart extends ET_Builder_Module {
	public $slug       = 'dipl_bar_chart';
	public $child_slug = 'dipl_bar_chart_item';
	public $vb_support = 'on';

	/**
	 * Track if the module is currently rendering,
	 * to prevent unnecessary rendering and recursion.
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
		$this->name 			= esc_html__( 'DP Bar Chart', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Bar Chart Item', 'divi-plus' );
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
					'bar_chart' => esc_html__( 'Bar Chart', 'divi-plus' )
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'   => false,
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element}",
							'border_radii'  => "{$this->main_css_element}",
						),
					),
				),
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element}",
					),
				)
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'text'        => false,
			'text_shadow' => false,
			'background'  => array(
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'default'          => esc_html__( 'My Bar Chart', 'divi-plus' ),
				'dynamic_content'  => 'text',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can define the title which will appear in the active state.', 'divi-plus' ),
			),
			'chart_height' => array(
				'label'            => esc_html__( 'Bar Chart Height', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'default'          => '400',
				'unitless'         => true,
				'mobile_options'   => true,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_chart',
				'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'chart_border_size' => array(
				'label'            => esc_html__( 'Bar Border Size', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'font_option',
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '20',
					'step' => '1',
				),
				'default'          => '1',
				'unitless'         => true,
				'mobile_options'   => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'bar_chart',
				'description'      => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
		);
	}

	public function before_render() {
		global $dipl_bar_chart_item_data;
		$dipl_bar_chart_item_data = array();

		$this->props['chart_height']        = ! empty( $this->props['chart_height'] ) ? $this->props['chart_height'] : '400';
		$this->props['chart_height_tablet'] = ! empty( $this->props['chart_height_tablet'] ) ? $this->props['chart_height_tablet'] : $this->props['chart_height'];
		$this->props['chart_height_phone']  = ! empty( $this->props['chart_height_phone'] ) ? $this->props['chart_height_phone'] : $this->props['chart_height_tablet'];
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		global $dipl_bar_chart_item_data;

		// Get order class.
		$order_class       = $this->get_module_order_class( $render_slug );

		$title             = sanitize_text_field( $this->props['title'] ) ?? '';
		$chart_border_size = sanitize_text_field( $this->props['chart_border_size'] ) ?? '1';

		// Get data from childern.
		$render_output = '';
		if ( ! empty( $dipl_bar_chart_item_data ) ) {

			// Load style and script files.
			wp_enqueue_script( 'elicus-chartjs-script' );
			wp_enqueue_script( 'dipl-bar-chart-script', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/BarChart/dipl-bar-chart.min.js", array('jquery'), '1.0.0', true );

			$labels      = [];
			$values      = [];
			$bgColor     = [];
			$borderColor = [];
			foreach ( $dipl_bar_chart_item_data as $child ) {
				if ( $child['title'] && $child['value'] ) {
					$labels[]      = esc_html__( $child['title'], 'divi-plus' );
					$values[]      = esc_attr( $child['value'] );
					$bgColor[]     = esc_attr( $child['bar_bg_color'] ) ?? 'rgba(0,0,0,0.2)';
					$borderColor[] = esc_attr( $child['bar_border_color'] ) ?? 'rgb(0,0,0)';
				}
			}

			// Get chart data.
			$chart_data = array(
				'labels'   => $labels,
				'datasets' => array( array(
					'label'           => esc_html__( $title, 'divi-plus' ),
					'data'            => $values,
					'backgroundColor' => $bgColor,
					'borderColor'     => $borderColor,
					'borderWidth'     => $chart_border_size,
				) )
			);

			// Get chart height.
			$chart_height = et_pb_responsive_options()->get_property_values( $this->props, 'chart_height' );

			// Final output.
			$render_output = sprintf(
				'<div class="dipl_bar_chart_wrapper"
					data-chart="%1$s"
					data-chart_height="%2$s"
					data-chart_height_tablet="%3$s"
					data-chart_height_phone="%4$s"
				>
					<div class="dipl_bar_chart_inner">
						<canvas id="%5$s--canvas"></canvas>
					</div>
				</div>',
				htmlspecialchars(json_encode( $chart_data ), ENT_QUOTES, 'UTF-8'),
				esc_attr( $chart_height['desktop'] ),
				esc_attr( $chart_height['tablet'] ),
				esc_attr( $chart_height['phone'] ),
				esc_attr( $order_class )
			);
		}

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}
}

$plugin_options = DIPL_DiviPlus::dipl_get_plugin_options();
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_bar_chart', $modules, true ) ) {
		new DIPL_BarChart();
	}
} else {
	new DIPL_BarChart();
}
