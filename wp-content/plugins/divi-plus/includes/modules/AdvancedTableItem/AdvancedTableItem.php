<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2024 Elicus Technologies Private Limited
 * @version     1.10.0
 */
class DIPL_AdvancedTableItem extends ET_Builder_Module {

	public $slug       = 'dipl_advanced_table_item';
	public $type       = 'child';
	public $vb_support = 'on';

	public $order_number = 0;
	public $render_count = 1;
	public $cell_count = 0;
	public $header_cells = [];

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name                        = esc_html__( 'DP Advanced Table Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Table Cell', 'divi-plus' );
		$this->main_css_element            = '.dipl_advanced_table .dipl_advanced_table_wrap %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => array(
						'title'    => esc_html__( 'Content', 'divi-plus' ),
						'priority' => 1,
						'tab'      => 'active',
					),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'cell_text' => array(
						'title' => esc_html__( 'Cell Text', 'divi-plus' )
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'          => array(
				'cell_text' => array(
					'label'           => esc_html__( 'Cell Text', 'divi-plus' ),
					'font_size'       => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'      => ".dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), .dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'cell_text',			
				),
			),
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '.dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), .dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)',
							'border_radii'  => '.dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), .dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)',
						),
						'important' => 'all',
					),
				),
			),
			'margin_padding' => array(
				'css'     => array(
					'main'      => '.dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), .dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)',
					'important' => 'all',
				),
				'default' => array(
					'css' => array(
						'main'      => '.dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), .dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)',
						'important' => 'all',
					),
				),
			),
			'background' => array(
				'use_background_video' => false,
				'css' => array(
					'main' => ".dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), .dipl_advanced_table .dipl_advanced_table_wrap %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
					'important' => 'all',
				),
			),
			'text'           => false,
			'module'         => false,
			'text_shadow'    => false,
		);
	}

	public function get_fields() {
		$et_accent_color = et_builder_accent_color();
		return array(
			'cell_value'    => array(
				'label'           => esc_html__( 'Table Cell Value', 'divi-plus' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'dynamic_content' => 'text',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter single table cell value.', 'divi-plus' ),
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {

		global $dipl_advanced_table_columns_number,
			$dipl_advanced_table_order_number;

		if ( $this->order_number !== intval( $dipl_advanced_table_order_number ) ) {
			$this->render_count = 1;
			$this->cell_count = 0;
			$this->header_cells = [];
		}
		$this->order_number = intval( $dipl_advanced_table_order_number );

		$props 		= $this->props;
		$cell_value = $this->props['cell_value'];
		$columns_number = (int) $dipl_advanced_table_columns_number;
		$is_header_cell = ( $this->render_count <= $columns_number );

		if ( $is_header_cell  ) {
			$this->header_cells[] = $cell_value;
		}

		$header_cell_value = $this->header_cells[$this->cell_count];
		$header_cell_class = $is_header_cell ? ' dipl_advanced_table_item_cell_heading' : '';

		ob_start();
		?>
			<div class="dipl_advanced_table_item_cell_wrap">
				<div class="dipl_advanced_table_item_responsive_heading">
					<?php echo et_core_intentionally_unescaped( $header_cell_value, 'html' ); ?>
				</div>
				<div class="dipl_advanced_table_item_cell<?php echo esc_attr( $header_cell_class ); ?>">
					<?php echo et_core_intentionally_unescaped( $cell_value, 'html' ); ?>
				</div>
			</div>
		<?php
		$dipl_advanced_table_item_wrap = ob_get_clean();
		$this->render_count++;
		$this->cell_count++;

		if ( $this->cell_count > ( $columns_number - 1 ) ) {
			$this->cell_count = 0;
		}

		return et_core_intentionally_unescaped( $dipl_advanced_table_item_wrap, 'html' );
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_list', $modules ) ) {
		new DIPL_AdvancedTableItem();
	}
} else {
	new DIPL_AdvancedTableItem();
}