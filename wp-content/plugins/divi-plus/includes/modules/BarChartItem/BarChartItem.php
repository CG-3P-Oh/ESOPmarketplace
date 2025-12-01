<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */
class DIPL_BarChartItem extends ET_Builder_Module {
	public $slug        = 'dipl_bar_chart_item';
	public $type        = 'child';
	public $vb_support  = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name 						= esc_html__( 'DP Bar Chart Item', 'divi-plus' );
		$this->advanced_setting_title_text  = esc_html__( 'Bar Chart Item', 'divi-plus' );
		$this->child_title_var              = 'title';
		$this->main_css_element 			= '.dipl_bar_chart %%order_class%%';
		$this->has_advanced_fields          = false;
		$this->advanced_fields              = false;
		$this->custom_css_tab               = false;
	}

	public function get_settings_modal_toggles() {
		return array(
			'general' => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => false
			)
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'           => esc_html__( 'Label', 'divi-plus' ),
				'type'            => 'text',
				'dynamic_content' => 'text',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter the label for the bar to show on bar chart.', 'divi-plus' ),
			),
			'value' => array(
				'label'           => esc_html__( 'Value', 'divi-plus' ),
				'type'            => 'text',
				'dynamic_content' => 'text',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter the value/digits for the bar to show on bar chart.', 'divi-plus' ),
			),
			'bar_bg_color' => array(
				'label'       => esc_html__( 'Bar Background Color', 'divi-plus' ),
				'type'        => 'color-alpha',
				'default'     => 'rgba(54, 162, 235, 0.2)',
				'toggle_slug' => 'main_content',
				'description' => esc_html__( 'Here you can define a custom background color for the bar.', 'divi-plus' ),
			),
			'bar_border_color' => array(
				'label'       => esc_html__( 'Bar Border Color', 'divi-plus' ),
				'type'        => 'color-alpha',
				'default'     => 'rgb(54, 162, 235)',
				'toggle_slug' => 'main_content',
				'description' => esc_html__( 'Here you can define a custom background color for the bar.', 'divi-plus' ),
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {

		global $dipl_bar_chart_item_data;

		// Return settings so parent can use them.
        $dipl_bar_chart_item_data[] = array(
            'title'            => $this->props['title'] ?? '',
            'value'            => $this->props['value'] ?? '',
			'bar_bg_color'     => $this->props['bar_bg_color'] ?? 'rgba(54, 162, 235, 0.2)',
			'bar_border_color' => $this->props['bar_border_color'] ?? 'rgb(54, 162, 235)',
        );

		return '';
	}
}

$plugin_options = DIPL_DiviPlus::dipl_get_plugin_options();
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_bar_chart', $modules, true ) ) {
		new DIPL_BarChartItem();
	}
} else {
	new DIPL_BarChartItem();
}
