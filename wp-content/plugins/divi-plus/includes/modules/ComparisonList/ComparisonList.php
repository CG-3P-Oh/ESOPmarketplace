<?php
class DIPL_ComparisonList extends ET_Builder_Module {

	public $slug       = 'dipl_comparison_list';
	public $child_slug = 'dipl_comparison_list_item';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name             = esc_html__( 'DP Comparison List', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Comparison List Item', 'divi-plus' );
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
					'list_header' => array(
						'title' => esc_html__( 'List Header', 'divi-plus' )
					),
					'list_header_title' => array(
						'title' => esc_html__( 'List Header Title', 'divi-plus' )
					),
					'list_item' => array(
						'title' => esc_html__( 'List Item', 'divi-plus' )
					),
					'list_item_title' => array(
						'title' => esc_html__( 'List Item Title', 'divi-plus' )
					),
					'list_item_description' => array(
						'title' => esc_html__( 'List Item Description', 'divi-plus' )
					),
					'icon_check' => array(
						'title' => esc_html__( 'Icon Check', 'divi-plus' )
					),
					'icon_cross' => array(
						'title' => esc_html__( 'Icon Cross', 'divi-plus' )
					),
					'toggle_icon' => array(
						'title' => esc_html__( 'Toggle Icon', 'divi-plus' )
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'          => array(
				'list_header_title' => array(
					'label'           => esc_html__( 'Header Title', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_comparison_list_header .dipl_comparison_list_column",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'list_header_title',			
				),
				'list_item_title' => array(
					'label'           => esc_html__( 'List Item Title', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_comparison_list_item_title",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'list_item_title',			
				),
				'list_item_description' => array(
					'label'           => esc_html__( 'List Item Description', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_comparison_list_description",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'list_item_description',			
				),
				'list_item' => array(
					'label'           => esc_html__( 'List Item Text', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_comparison_list_text",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'list_item',			
				)
			),
			'borders' => array(
				'list_header' => array(
					'label_prefix' => esc_html__( 'List Header', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_comparison_list_header",
							'border_styles' => "%%order_class%% .dipl_comparison_list_header",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'list_header'
				),
				'list_item' => array(
					'label_prefix' => esc_html__( 'List Item', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_comparison_list_content_row",
							'border_styles' => "%%order_class%% .dipl_comparison_list_content_row",
						),
						'important' => 'all',
					),
					'defaults' => array(
	                    'border_styles' => array(
	                        'width' => '1px',
	                        'style' => 'solid',
	                        'color' => '#eef2f8',
	                    ),
	                ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'list_item'
				),
				'list_item_description' => array(
					'label_prefix' => esc_html__( 'List Item Description', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_comparison_list_description",
							'border_styles' => "%%order_class%% .dipl_comparison_list_description",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'list_item_description'
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
			'comparison_list_spacing' => array(
				'list_header' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_comparison_list_header",
							'important'  => 'all',
						),
					),
				),
				'list_item' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_comparison_list_content_row",
							'important'  => 'all',
						),
					),
				),
				'list_description' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_comparison_list_item .dipl_comparison_list_description",
							'important'  => 'all',
						),
					),
				),
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
				'main_title'             => array(
					'label'           => esc_html__( 'Feature List Title', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the feature list title.', 'divi-plus' ),
				),
				'comparison_list_titles' => array(
					'label'            => esc_html__( 'Comparison List Titles', 'divi-plus' ),
					'type'             => 'textarea',
					'option_category'  => 'basic_option',
					'description'      => esc_html__( 'This is the column titles shown at the top of your comparison table. The first title represents the feature list, and others represent different plans.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				'comparison_header_notice' => array(
					'label'           => '',
					'type'            => 'warning',
					'option_category' => 'configuration',
					'value'           => true,
					'display_if'      => true,
					'message'         => esc_html__( '<strong>Tip:</strong> Use the “|” symbol to separate each title.<br>Example: <code>Feature List | Free | Ultimate.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				'list_header_bg_color' => array(
	                'label'             => esc_html__( 'List Header Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'list_header_bg',
	                'context'           => 'list_header_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'list_header_bg', 'button', 'advanced', 'list_header', 'list_header_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'list_header',
	                'description'       => esc_html__( 'Here you can adjust the background style of the list header by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'list_header_custom_padding' => array(
					'label'            => esc_html__( 'List Header Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'list_header',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' )
				),
				'list_item_bg_color' => array(
	                'label'             => esc_html__( 'List Item Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'list_item_bg',
	                'context'           => 'list_item_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'list_item_bg', 'button', 'advanced', 'list_item', 'list_item_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'list_item',
	                'description'       => esc_html__( 'Here you can adjust the background style of the list item by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'list_item_custom_padding' => array(
					'label'            => esc_html__( 'List Item Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'list_item',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' )
				),
				'list_description_bg_color' => array(
	                'label'             => esc_html__( 'List Item Description Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'list_description_bg',
	                'context'           => 'list_description_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'list_description_bg', 'button', 'advanced', 'list_description', 'list_description_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'list_item_description',
	                'description'       => esc_html__( 'Here you can adjust the background style of the list description by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'list_description_custom_padding' => array(
					'label'            => esc_html__( 'List Item Description Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'list_item_description',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' )
				),
				'toggle_icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'hover'		   => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'toggle_icon',
					'description'  => esc_html__( 'Here you can define a custom color for the icon.', 'divi-plus' ),
				),
				'toggle_icon_size' => array(
					'label'                 => esc_html__( 'Icon Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'default'				=> '20px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'toggle_icon',
					'description'           => esc_html__( 'Increase or decrease the size of the icon.', 'divi-plus' ),
				),
				'icon_check_icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'hover'		   => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'icon_check',
					'description'  => esc_html__( 'Here you can define a custom color for the icon.', 'divi-plus' ),
				),
				'icon_check_icon_size' => array(
					'label'                 => esc_html__( 'Icon Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'default'				=> '20px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'icon_check',
					'description'           => esc_html__( 'Increase or decrease the size of the icon.', 'divi-plus' ),
				),
				'icon_cross_icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'hover'		   => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'icon_cross',
					'description'  => esc_html__( 'Here you can define a custom color for the icon.', 'divi-plus' ),
				),
				'icon_cross_icon_size' => array(
					'label'                 => esc_html__( 'Icon Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'default'				=> '20px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'icon_cross',
					'description'           => esc_html__( 'Increase or decrease the size of the icon.', 'divi-plus' ),
				),
			),
			$this->generate_background_options( 'list_header_bg', 'skip', 'advanced', 'list_header', 'list_header_bg_color' ),
			$this->generate_background_options( 'list_item_bg', 'skip', 'advanced', 'list_item', 'list_item_bg_color' ),
			$this->generate_background_options( 'list_description_bg', 'skip', 'advanced', 'list_item_description', 'list_description_bg_color' )
		);
	}

	public function render( $attrs, $content, $render_slug ) {

		$props 					= $this->props;
		$main_title             = $this->props['main_title'];
	    $comparison_list_titles = $this->props['comparison_list_titles'];

	    //check icon styling.
		$icon_check_icon_size = et_pb_responsive_options()->get_property_values( $props, 'icon_check_icon_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$icon_check_icon_size,
			"%%order_class%% .dipl_comparison_list_icon_check",
			'font-size',
			$render_slug,
			'!important;'
		);
		if ( '' !== $props['icon_check_icon_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_comparison_list_icon_check',
				'declaration' => sprintf( 'color: %1$s;', esc_attr( $props['icon_check_icon_color'] ) ),
			) );
		}

		//cross icon styling.
		$icon_cross_icon_size = et_pb_responsive_options()->get_property_values( $props, 'icon_cross_icon_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$icon_cross_icon_size,
			"%%order_class%% .dipl_comparison_list_icon_close",
			'font-size',
			$render_slug,
			'!important;'
		);
		if ( '' !== $props['icon_cross_icon_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_comparison_list_icon_close',
				'declaration' => sprintf( 'color: %1$s;', esc_attr( $props['icon_cross_icon_color'] ) ),
			) );
		}

		//toggle icon styling.
		$toggle_icon_size = et_pb_responsive_options()->get_property_values( $props, 'toggle_icon_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$toggle_icon_size,
			"%%order_class%% .dipl_toggle_icon",
			'font-size',
			$render_slug,
			'!important;'
		);
		if ( '' !== $props['toggle_icon_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_toggle_icon',
				'declaration' => sprintf( 'color: %1$s;', esc_attr( $props['toggle_icon_color'] ) ),
			) );
		}

	    $args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'list_header_bg' => array(
					'normal' => "{$this->main_css_element} .dipl_comparison_list_header",
					'hover' => "{$this->main_css_element} .dipl_comparison_list_header:hover",
	 			),
				'list_item_bg' => array(
					'normal' => "{$this->main_css_element} .dipl_comparison_list_item .dipl_comparison_list_content_row",
					'hover' => "{$this->main_css_element} .dipl_comparison_list_item .dipl_comparison_list_content_row:hover",
	 			),
	 			'list_description_bg' => array(
					'normal' => "{$this->main_css_element} .dipl_comparison_list_description",
					'hover' => "{$this->main_css_element} .dipl_comparison_list_description:hover",
	 			)
			),
		);
		DiviPlusHelper::process_background( $args );
	    $fields = array( 'comparison_list_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

	    wp_enqueue_script( 'dipl-comparison-list-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ComparisonList/dipl-comparison-list-custom.min.js', array('jquery'), '1.20.0', true );
	    $file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-comparison-list-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ComparisonList/' . $file . '.min.css', array(), '1.20.0' );

	    // Split header titles
	    $header_titles = array_map( 'trim', explode( '|', $comparison_list_titles ) );

	    ob_start(); ?>

	    <div class="dipl_comparison_list_wrapper">
	        <div class="dipl_comparison_list_header">
	            <?php if ( ! empty( $main_title ) ) : ?>
	                <div class="dipl_comparison_list_column">
	                    <?php echo esc_html( $main_title ); ?>
	                </div>
	            <?php endif; ?>

	            <?php if ( ! empty( $header_titles ) ) : ?>
		            <?php foreach ( $header_titles as $item ) : ?>
		                <div class="dipl_comparison_list_column">
		                    <?php echo esc_html( $item ); ?>
		                </div>
		            <?php endforeach; ?>
		        <?php endif; ?>
	        </div>

	        <?php if ( ! empty( $this->content ) ) : ?>
	            <?php echo et_core_intentionally_unescaped( $this->content, 'html' ); ?>
	        <?php endif; ?>

	    </div>

	    <?php
    	$comparison_list_wrapper = ob_get_clean();

		return et_core_intentionally_unescaped( $comparison_list_wrapper, 'html' );
	}

	public function add_new_child_text() {
		return esc_html__( 'Add New Comparison List Item', 'divi-plus' );
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
	if ( in_array( 'dipl_comparison_list', $modules ) ) {
		new DIPL_ComparisonList();
	}
} else {
	new DIPL_ComparisonList();
}