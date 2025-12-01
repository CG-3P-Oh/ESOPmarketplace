<?php
class DIPL_AdvancedTable extends ET_Builder_Module {

	public $slug       = 'dipl_advanced_table';
	public $child_slug = 'dipl_advanced_table_item';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public $builder_js_data = array(
        'columns_number',
    );

	public function init() {
		$this->name             = esc_html__( 'DP Advanced Table', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Table Cell', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
		add_filter( 'et_builder_processed_range_value', array( $this, 'dipl_builder_processed_range_value' ), 10, 3 );
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Configuration', 'divi-plus' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'header' => array(
						'title' => esc_html__( 'Header', 'divi-plus' )
					),
					'header_text' => array(
						'title' => esc_html__( 'Header Text', 'divi-plus' )
					),
					'table_cell' => array(
						'title' => esc_html__( 'Table Cell', 'divi-plus' )
					),
					'table_cell_text' => array(
						'title' => esc_html__( 'Table Cell Text', 'divi-plus' )
					)
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'          => array(
				'header_text' => array(
					'label'           => esc_html__( 'Header Text', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading), {$this->main_css_element} .dipl_advanced_table_item_responsive_heading",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'header_text',			
				),
				'table_cell_text' => array(
					'label'           => esc_html__( 'Table Cell Text', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'table_cell_text',			
				)
			),
			'borders' => array(
				'header' => array(
					'label_prefix' => esc_html__( 'Header', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading), %%order_class%% .dipl_advanced_table_item_responsive_heading",
							'border_styles' => "%%order_class%% .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading), %%order_class%% .dipl_advanced_table_item_responsive_heading",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'header'
				),
				'table_cell' => array(
					'label_prefix' => esc_html__( 'Table Cell', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
							'border_styles' => "%%order_class%% .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'table_cell'
				),
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '%%order_class%%',
							'border_radii'  => '%%order_class%%',
						),
					),
				),
			),
			'advanced_table_spacing' => array(
				'header' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading), {$this->main_css_element} .dipl_advanced_table_item_responsive_heading",
							'important'  => 'all',
						),
					),
				),
				'table_cell' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
							'important'  => 'all',
						),
					),
				)
			),
			'margin_padding' => array(
				'css'     => array(
					'important' => 'all',
				),
				'default' => array(
					'css' => array(
						'main'      => '%%order_class%%',
						'important' => 'all',
					),
				),
			),
			'text'           => false,
			'text_shadow'    => false,
			'link_options'   => false,
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'columns_number' => array(
					'label'            => esc_html__( 'Number Of Columns', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'basic_option',
					'range_settings'   => array(
						'min'  => '0',
						'max'  => '100',
						'step' => '1',
					),
					'default'          => '4',
					'default_on_front' => '4',
					'default_unit'     => '',
					'allowed_units'    => false,
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Choose how many columns should be displayed in the table.', 'divi-plus' ),
				),
				'comparison_header_notice' => array(
					'label'           => '',
					'type'            => 'warning',
					'option_category' => 'configuration',
					'value'           => true,
					'display_if'      => true,
					'message'         => esc_html__( '<strong>Tip:</strong> The first set of table cells will be treated as column headers. The number of header cells is determined by the column count specified in this settings. All remaining cells will be rendered as standard table cells.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				'header_bg_color' => array(
	                'label'             => esc_html__( 'Header Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'header_bg',
	                'context'           => 'header_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'header_bg', 'button', 'advanced', 'header', 'header_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'header',
	                'description'       => esc_html__( 'Here you can adjust the background style of the table header by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'header_custom_padding' => array(
					'label'            => esc_html__( 'Header Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'header',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' )
				),
				'table_cell_bg_color' => array(
	                'label'             => esc_html__( 'Table Cell Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'table_cell_bg',
	                'context'           => 'table_cell_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'table_cell_bg', 'button', 'advanced', 'table_cell', 'table_cell_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'table_cell',
	                'description'       => esc_html__( 'Here you can adjust the background style of the table cell by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'table_cell_custom_padding' => array(
					'label'            => esc_html__( 'Table Cell Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'table_cell',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' )
				),
	        ),
			$this->generate_background_options( 'header_bg', 'skip', 'advanced', 'header', 'header_bg_color' ),
			$this->generate_background_options( 'table_cell_bg', 'skip', 'advanced', 'table_cell', 'table_cell_bg_color' ),
		);
	}

	function before_render() {
		global $dipl_advanced_table_columns_number,
		$dipl_advanced_table_order_number;

		$dipl_advanced_table_columns_number = $this->props['columns_number'];

		$order_class  = $this->get_module_order_class( 'dipl_advanced_table' );
		$order_number = str_replace( '_', '', str_replace( 'dipl_advanced_table', '', $order_class ) );
		$dipl_advanced_table_order_number = $order_number;
	}

	public function render( $attrs, $content, $render_slug ) {

		$props 					= $this->props;
		$main_text             	= $this->props['main_text'];
	    $advanced_table_texts 	= $this->props['advanced_table_texts'];

		if ( '' !== $props['columns_number'] ) {
			$style = sprintf(
				"flex: 0 0 calc(100%% / %s);", 
				$props['columns_number']
			);
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_advanced_table_item',
				'declaration' => $style
			) );
			$style = sprintf(
				"grid-template-columns: repeat(%s, 1fr);", 
				$props['columns_number']
			);
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_advanced_table_wrap',
				'declaration' => $style
			) );
		}

		$header_all = $props['border_width_all_header'];
		$top        = $props['border_width_top_header'];
		$right      = $props['border_width_right_header'];
		$bottom     = $props['border_width_bottom_header'];
		$left       = $props['border_width_left_header'];

		if ( $header_all ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:nth-child('.$props['columns_number'].'):has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-right-width: '. $header_all .' !important;'
			) );
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:first-child:has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-left-width: '. $header_all .' !important;'
			) );
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-right-width: 0px !important;border-left-width: 0px !important;'
			) );
		}

		if ( ! empty( $right ) ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:nth-child(-n+'.( $props['columns_number'] - 1 ).'):has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-right-width: 0px !important;'
			) );
		}

		if ( ! empty( $left ) ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:not(:first-child):has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-left-width: 0px !important;'
			) );
		}

		
		$radii_all   = $props['border_radii_header'];
		$parts = explode( '|', $radii_all );

		$enabled       = $parts[0] ?? '';
		$top_left      = $parts[1] ?? '';
		$top_right     = $parts[2] ?? '';
		$bottom_right  = $parts[3] ?? '';
		$bottom_left   = $parts[4] ?? '';

		/**
		 * Apply "ALL" header radius
		 */
		if ( 
			! empty( $top_left ) ||
			! empty( $top_right ) ||
			! empty( $bottom_right ) ||
			! empty( $bottom_left )
		) {
			// Reset inner cells radius
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-radius: 0px !important;'
			));
		}
		/**
		 * TOP RIGHT Radius individual
		 */
		if ( ! empty( $top_right ) ) {

			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:nth-child('. $props['columns_number'] .'):has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-top-right-radius: '. $top_right .' !important;'
			));
		}
		/**
		 * BOTTOM LEFT Radius individual
		 */
		if ( ! empty( $bottom_left ) ) {

			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:first-child:has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-bottom-left-radius: '. $bottom_left .' !important;'
			));
		}
		/**
		 * TOP LEFT Radius individual
		 */
		if ( ! empty( $top_left ) ) {

			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:first-child:has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-top-left-radius: '. $top_left .' !important'
			));
		}
		/**
		 * BOTTOM RIGHT Radius individual
		 */
		if ( ! empty( $bottom_right ) ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%.dipl_advanced_table .dipl_advanced_table_wrap .dipl_advanced_table_item:nth-child('. $props['columns_number'] .'):has(.dipl_advanced_table_item_cell_heading)',
				'declaration' => 'border-bottom-right-radius: '. $bottom_right .' !important;'
			));
		}


	    $args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'header_bg' => array(
					'normal' => "{$this->main_css_element} .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading), {$this->main_css_element} .dipl_advanced_table_item_responsive_heading",
					'hover' => "{$this->main_css_element} .dipl_advanced_table_item:has(.dipl_advanced_table_item_cell_heading):hover, {$this->main_css_element} .dipl_advanced_table_item_responsive_heading:hover",
	 			),
				'table_cell_bg' => array(
					'normal' => "{$this->main_css_element} .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)), %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading)",
					'hover' => "{$this->main_css_element} .dipl_advanced_table_item:not(:has(.dipl_advanced_table_item_cell_heading)):hover, %%order_class%% .dipl_advanced_table_item_cell:not(.dipl_advanced_table_item_cell_heading):hover",
	 			)
			),
		);
		DiviPlusHelper::process_background( $args );
	    $fields = array( 'advanced_table_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

	    $file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-advanced-table-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AdvancedTable/' . $file . '.min.css', array(), time() );


	    ob_start(); ?>

	    <div class="dipl_advanced_table_wrap">
	        <?php if ( ! empty( $this->content ) ) : ?>
	            <?php echo et_core_intentionally_unescaped( $this->content, 'html' ); ?>
	        <?php endif; ?>
	    </div>

	    <?php
    	$advanced_table_wrapper = ob_get_clean();

		return et_core_intentionally_unescaped( $advanced_table_wrapper, 'html' );
	}

	public function add_new_child_text() {
		return esc_html__( 'Add New Table Cell', 'divi-plus' );
	}

	public function dipl_builder_processed_range_value( $result, $range, $range_string ) {
		if ( false !== strpos( $result, '0calc' ) ) {
			return $range;
		}
		return $result;
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_advanced_table', $modules ) ) {
		new DIPL_AdvancedTable();
	}
} else {
	new DIPL_AdvancedTable();
}