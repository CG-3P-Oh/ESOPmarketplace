<?php
require_once plugin_dir_path( __DIR__ ) . 'extensions.php';

class DIPL_Unfold extends DIPL_Extensions {

	/**
	 * All divi modules list.
	 *
	 * @var array
	 */
	public $all_module_list = array();

	public function __construct() {

		$this->all_module_list = $this->dipl_get_modules();

		$unfold_areas = $this->dipl_get_option( 'dipl-unfold-areas' );

		// Currently not giving support for sections.
		// if ( in_array( 'sections', $unfold_areas ) ) {
		// 	add_filter( 'et_builder_get_parent_modules', array( $this, 'dipl_unfold_builder_add_section_toggles' ), 10, 2 );
		// 	add_filter( 'et_pb_all_fields_unprocessed_et_pb_section', array( $this, 'dipl_add_unfold_props' ) );
		// 	add_filter( 'et_module_shortcode_output', array( $this, 'dipl_unfold_section_output' ), 10, 3 );
		// }

		// Currently not giving support for rows too.
		// if ( in_array( 'rows', $unfold_areas ) ) {
		// 	add_filter( 'et_builder_get_parent_modules', array( $this, 'dipl_unfold_builder_add_row_toggles' ), 10, 2 );
		// 	add_filter( 'et_pb_all_fields_unprocessed_et_pb_row', array( $this, 'dipl_add_unfold_props' ) );
		// 	add_filter( 'et_module_shortcode_output', array( $this, 'dipl_unfold_row_output' ), 10, 3 );
		// }

		// Currently not giving support for columns, and also it creates the issue with html.

		if ( 'on' === $unfold_areas ) {
			foreach ( $this->all_module_list as $dipl_module ) {
				$dipl_module = esc_attr( $dipl_module );
				add_filter( 'et_pb_all_fields_unprocessed_' . $dipl_module, array( $this, 'dipl_add_unfold_props' ) );
			}
			add_filter( 'et_builder_ready', array( $this, 'dipl_unfold_builder_add_modules_toggles' ) );
			add_filter( 'et_module_shortcode_output', array( $this, 'dipl_unfold_module_output' ), 10, 3 );
		}
		
	}

	// Adding section's new toggle in custom css tab.
	// public function dipl_unfold_builder_add_section_toggles( $parent_modules, $post_type ) {
	// 	$this->dipl_unfold_builder_add_toggles_to_tab( 'et_pb_section', $parent_modules, $post_type );
	// 	return $parent_modules;
	// }

	// Adding row's new toggle in custom css tab.
	// public function dipl_unfold_builder_add_row_toggles( $parent_modules, $post_type ) {
	// 	$this->dipl_unfold_builder_add_toggles_to_tab( 'et_pb_row', $parent_modules, $post_type );
	// 	return $parent_modules;
	// }

	public function dipl_unfold_builder_add_modules_toggles() {
		// Return if class not exists.
		if ( ! class_exists( 'ET_Builder_Element' ) ) {
			return;
		}

		foreach ( ET_Builder_Element::get_modules() as $module_slug => $module ) {
			if ( in_array( $module_slug, $this->all_module_list ) ) {
				$module->settings_modal_toggles['custom_css']['toggles'] = array_merge(
					$module->settings_modal_toggles['custom_css']['toggles'],
					$this->dipl_unfold_get_toggles()
				);
			}
		}
	}

	// Add the tab toggles.
	// public function dipl_unfold_builder_add_toggles_to_tab( $module_type, $parent_modules, $post_type ) {
	// 	if ( isset( $parent_modules[ $module_type ] ) ) {
	// 		$module = $parent_modules[ $module_type ];
	// 		$module->settings_modal_toggles['custom_css']['toggles'] = array_merge(
	// 			$module->settings_modal_toggles['custom_css']['toggles'],
	// 			$this->dipl_unfold_get_toggles()
	// 		);
	// 	}
	// }

	public function dipl_add_unfold_props( $fields_unprocessed ) {
		$fields_unprocessed['dipl_enable_unfold'] = array(
			'label'            => esc_html__( 'Enable Unfold', 'divi-plus' ),
			'type'             => 'yes_no_button',
			'option_category'  => 'configuration',
			'options'          => array(
				'off' => esc_html__( 'No', 'divi-plus' ),
				'on'  => esc_html__( 'Yes', 'divi-plus' ),
			),
			'default' 		   => 'off',
			'default_on_front' => 'off',
			'priority'         => 100,
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can choose whether it will show or hide on the unfold.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_height'] = array(
			'label'            => esc_html__( 'Unfold Box Max Height', 'divi-plus' ),
			'type'             => 'range',
			'option_category'  => 'configuration',
			'mobile_options'   => true,
			'range_settings'   => array(
				'min'  => '10',
				'max'  => '1000',
				'step' => '1',
			),
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'default'     	   => '350px',
			'default_on_front' => '350px',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can select the height of unfold box to show.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_background_1'] = array(
			'label'       	   => esc_html__( 'Unfold Box Gradient Color 1', 'divi-plus' ),
			'type'         	   => 'color-alpha',
			'hover'        	   => 'tabs',
			'custom_color' 	   => true,
			'default'      	   => 'rgba( 255, 255, 255, 0 )',
			'default_on_front' => 'rgba( 255, 255, 255, 0 )',
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'tab_slug'        => 'custom_css',
			'toggle_slug'     => 'dipl_unfold_settings',
			'description'     => esc_html__( 'Customize the background style of the unfold box background by adjusting the background color, gradient.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_background_2'] = array(
			'label'       	   => esc_html__( 'Unfold Box Gradient Color 2', 'divi-plus' ),
			'type'         	   => 'color-alpha',
			'hover'        	   => 'tabs',
			'custom_color' 	   => true,
			'default'      	   => '#ffffff',
			'default_on_front' => '#ffffff',
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'tab_slug'        => 'custom_css',
			'toggle_slug'     => 'dipl_unfold_settings',
			'description'     => esc_html__( 'Customize the background style of the unfold box background by adjusting the background color, gradient.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_gradient_type'] = array(
			'label'       	   => esc_html__( 'Unfold Box Gradient Type', 'divi-plus' ),
			'type'             => 'select',
			'option_category'  => 'layout',
			'options'          => array(
				'linear-gradient' => esc_html__( 'Linear', 'divi-plus' ),
				'radial-gradient' => esc_html__( 'Radial', 'divi-plus' ),
			),
			'default'          => 'linear-gradient',
			'default_on_front' => 'linear-gradient',
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can select Gradient Type.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_linear_direction'] = array(
			'label'            => esc_html__( 'Unfold Box Gradient Direction', 'divi-plus' ),
			'type'             => 'range',
			'range_settings'   => array(
				'min'  => '1',
				'max'  => '360',
				'step' => '1',
			),
			'mobile_options'   => true,
			'show_if'          => array(
				'dipl_enable_unfold'   => 'on',
				'unfold_gradient_type' => 'linear-gradient',
			),
			'default'          => '180deg',
			'default_on_front' => '180deg',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can select Linear Gradient Direction', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_radial_direction'] = array(
			'label'            => esc_html__( 'Unfold Box Gradient Direction', 'divi-plus' ),
			'type'             => 'select',
			'option_category'  => 'layout',
			'options'          => array(
				'circle at center'       => esc_html__( 'Center', 'divi-plus' ),
				'circle at top left'     => esc_html__( 'Top Left', 'divi-plus' ),
				'circle at top'          => esc_html__( 'Top', 'divi-plus' ),
				'circle at top right'    => esc_html__( 'Top Right', 'divi-plus' ),
				'circle at bottom right' => esc_html__( 'Bottom Right', 'divi-plus' ),
				'circle at bottom'       => esc_html__( 'Bottom', 'divi-plus' ),
				'circle at bottom left'  => esc_html__( 'Bottom Left', 'divi-plus' ),
				'circle at left'         => esc_html__( 'Left', 'divi-plus' ),
			),
			'show_if'          => array(
				'dipl_enable_unfold'   => 'on',
				'unfold_gradient_type' => 'radial-gradient',
			),
			'default'          => 'circle at center',
			'default_on_front' => 'circle at center',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can select Linear Gradient Direction', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_gradient_start_position'] = array(
			'label'            => esc_html__( 'Gradient Start Position', 'divi-plus' ),
			'type'             => 'range',
			'range_settings'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'mobile_options'   => true,
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'default'          => '0%',
			'default_on_front' => '0%',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can select Unfold Box Gradient Start Position', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_gradient_end_position'] = array(
			'label'            => esc_html__( 'Gradient End Position', 'divi-plus' ),
			'type'             => 'range',
			'range_settings'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'mobile_options'   => true,
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'default'          => '100%',
			'default_on_front' => '100%',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Here you can select Unfold Box Gradient End Position', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_container_custom_padding'] = array(
			'label'            => esc_html__( 'Unfold Container Padding', 'divi-plus' ),
			'type'             => 'custom_padding',
			'option_category'  => 'layout',
			'mobile_options'   => true,
			'hover'            => 'tabs',
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'default'          => '150px||10px|||on',
			'default_on_front' => '150px||10px|||on',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_settings',
			'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
		);

		// Button settings.
		$fields_unprocessed['unfold_btn_text'] = array(
			'label'            => esc_html__( 'Unfold Button Text', 'divi-plus' ),
			'type'             => 'text',
			'option_category'  => 'configuration',
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'priority'         => 10,
			'default'     	   => esc_html__( 'View more', 'divi-plus' ),
			'default_on_front' => esc_html__( 'View more', 'divi-plus' ),
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Here you can enter the number of particles you want to show.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_custom_styling'] = array(
			'label'            => esc_html__( 'Use Custom Styles For Button', 'divi-plus' ),
			'type'             => 'yes_no_button',
			'option_category'  => 'configuration',
			'options'          => array(
				'off' => esc_html__( 'No', 'divi-plus' ),
				'on'  => esc_html__( 'Yes', 'divi-plus' ),
			),
			'default' 		   => 'off',
			'default_on_front' => 'off',
			'show_if'          => array(
				'dipl_enable_unfold' => 'on',
			),
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'If you would like to customize the appearance of this module\'s button, you must first enable custom button styles.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_font'] = array(
			'label'            => esc_html__( 'Unfold Button Font', 'divi-plus' ),
			'type'             => 'font',
			'hover'            => 'tabs',
			'mobile_options'   => true,
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '',
			'default_on_front' => '',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Increase or decrease the thickness of the border around the button. Setting this value to 0 will remove the border entirely.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_font_size'] = array(
			'label'            => esc_html__( 'Unfold Button Text Size', 'divi-plus' ),
			'type'             => 'range',
			'range_settings'   => array(
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			),
			'hover'            => 'tabs',
			'mobile_options'   => true,
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '20px',
			'default_on_front' => '20px',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Increase or decrease the size of the button text.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_color'] = array(
			'label'            => esc_html__( 'Unfold Button Text Color', 'divi-plus' ),
			'type'             => 'color-alpha',
			'mobile_options'   => true,
			'custom_color' 	   => true,
			'hover'            => 'tabs',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '',
			'default_on_front' => '',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Pick a color to be used for the button text.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_background'] = array(
			'label'            => esc_html__( 'Unfold Button Background', 'divi-plus' ),
			'type'             => 'color-alpha',
			'hover'            => 'tabs',
			'mobile_options'   => true,
			'custom_color' 	   => true,
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '',
			'default_on_front' => '',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Adjust the background style of the button by customizing the background color, gradient, and image.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_border_size'] = array(
			'label'            => esc_html__( 'Unfold Button Border Width', 'divi-plus' ),
			'type'             => 'range',
			'range_settings'   => array(
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			),
			'hover'            => 'tabs',
			'mobile_options'   => true,
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '2px',
			'default_on_front' => '2px',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Increase or decrease the thickness of the border around the button. Setting this value to 0 will remove the border entirely.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_border_color'] = array(
			'label'            => esc_html__( 'Unfold Button Border Color', 'divi-plus' ),
			'type'             => 'color-alpha',
			'mobile_options'   => true,
			'custom_color' 	   => true,
			'hover'            => 'tabs',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '',
			'default_on_front' => '',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Pick a color to be used for the button border.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_btn_border_radius'] = array(
			'label'            => esc_html__( 'Unfold Button Border Radius', 'divi-plus' ),
			'type'             => 'range',
			'range_settings'   => array(
				'min'  => '1',
				'max'  => '100',
				'step' => '1',
			),
			'mobile_options'   => true,
			'hover'            => 'tabs',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'default'          => '3px',
			'default_on_front' => '3px',
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Increasing the border radius will increase the roundness of the button\'s corners. Setting this value to 0 will result in squared corners.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_use_icon'] = array(
			'label'            => esc_html__( 'Use Icon on Unfold Button', 'divi-plus' ),
			'type'             => 'yes_no_button',
			'option_category'  => 'button',
			'options'          => array(
				'off'   => esc_html__( 'No', 'divi-plus' ),
				'on'    => esc_html__( 'Yes', 'divi-plus' ),
			),
			'default'          => 'off',
			'default_on_front' => 'off',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on'
			),
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Here you can choose whether or not to display a custom icon on the unfold button.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_icon'] = array(
			'label'           => esc_html__( 'Unfold Button Icon', 'divi-plus' ),
			'type'            => 'select_icon',
			'option_category' => 'button',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on',
				'unfold_button_use_icon'    => 'on',
			),
			'mobile_options'  => false,
			'tab_slug'        => 'custom_css',
			'toggle_slug'     => 'dipl_unfold_button',
			'description'     => esc_html__( 'Here you can choose an icon to be displayed on the unfold button.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_icon_color'] = array(
			'label'           => esc_html__( 'Button Icon Color', 'divi-plus' ),
			'type'            => 'color-alpha',
			'option_category' => 'button',
			'custom_color'    => true,
			'show_if'         => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on',
				'unfold_button_use_icon'    => 'on',
			),
			'hover'           => 'tabs',
			'mobile_options'  => true,
			'tab_slug'        => 'custom_css',
			'toggle_slug'     => 'dipl_unfold_button',
			'description'     => esc_html__( 'Here you can define a custom color for the button icon.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_icon_placement'] = array(
			'label'            => esc_html__( 'Button Icon Placement', 'divi-plus' ),
			'type'             => 'select',
			'option_category'  => 'button',
			'options'          => array(
				'right' => esc_html__( 'Right', 'divi-plus' ),
				'left'  => esc_html__( 'Left', 'divi-plus' ),
			),
			'default'          => 'right',
			'default_on_front' => 'right',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on',
				'unfold_button_use_icon'    => 'on',
			),
			'mobile_options'   => false,
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Here you can choose the area where you want to place the button icon within the button.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_on_hover'] = array(
			'label'           => esc_html__( 'Only Show Icon on Hover', 'divi-plus' ),
			'type'            => 'yes_no_button',
			'option_category' => 'button',
			'options'         => array(
				'on'    => esc_html__( 'Yes', 'divi-plus' ),
				'off'   => esc_html__( 'No', 'divi-plus' ),
			),
			'default'          => 'on',
			'default_on_front' => 'on',
			'show_if'          => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on',
				'unfold_button_use_icon'    => 'on',
			),
			'tab_slug'         => 'custom_css',
			'toggle_slug'      => 'dipl_unfold_button',
			'description'      => esc_html__( 'Here you can choose whether or not to display button icon on hover.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_custom_padding'] = array(
			'label'           => esc_html__( 'Unfold Button Padding', 'divi-plus' ),
			'type'            => 'custom_padding',
			'option_category' => 'layout',
			'mobile_options'  => true,
			'hover'           => 'tabs',
			'show_if'         => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on',
			),
			'tab_slug'        => 'custom_css',
			'toggle_slug'     => 'dipl_unfold_button',
			'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
		);
		$fields_unprocessed['unfold_button_custom_margin'] = array(
			'label'           => esc_html__( 'Unfold Button Margin', 'divi-plus' ),
			'type'            => 'custom_margin',
			'option_category' => 'layout',
			'mobile_options'  => true,
			'hover'           => 'tabs',
			'show_if'         => array(
				'dipl_enable_unfold'        => 'on',
				'unfold_btn_custom_styling' => 'on',
			),
			'tab_slug'        => 'custom_css',
			'toggle_slug'     => 'dipl_unfold_button',
			'description'     => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-plus' ),
		);

		return $fields_unprocessed;
	}

	// Section output.
	// public function dipl_unfold_section_output( $output, $render_slug, $section ) {
	// 	if ( 'et_pb_section' !== $render_slug ) {
	// 		return $output;
	// 	}
	// 	if ( is_array( $output ) ) {
	// 		return $output;
	// 	}

	// 	// If not set.
	// 	if ( ! isset( $section->props['dipl_enable_unfold'] ) || 'on' !== $section->props['dipl_enable_unfold'] ) {
	// 		return $output;
	// 	}

	// 	// Render common output for all.
	// 	$output = $this->render_unfold_output( $output, $section, $render_slug, 'et_pb_section' );

	// 	return $output;
	// }

	// Row output.
	// public function dipl_unfold_row_output( $output, $render_slug, $row ) {
	// 	if ( 'et_pb_row' !== $render_slug ) {
	// 		return $output;
	// 	}
	// 	if ( is_array( $output ) ) {
	// 		return $output;
	// 	}

	// 	// If not set.
	// 	if ( ! isset( $row->props['dipl_enable_unfold'] ) || 'on' !== $row->props['dipl_enable_unfold'] ) {
	// 		return $output;
	// 	}

	// 	// Render common output for all.
	// 	$output = $this->render_unfold_output( $output, $row, $render_slug, 'et_pb_row' );

	// 	return $output;
	// }

	// Module output.
	public function dipl_unfold_module_output( $output, $render_slug, $module ) {
		if ( ! in_array( $render_slug, $this->all_module_list ) ) {
			return $output;
		}
		if ( is_array( $output ) ) {
			return $output;
		}

		// If not set.
		if ( ! isset( $module->props['dipl_enable_unfold'] ) || 'on' !== $module->props['dipl_enable_unfold'] ) {
			return $output;
		}

		// Render common output for all.
		$output = $this->render_unfold_output( $output, $module, $render_slug );

		return $output;
	}

	public function render_unfold_output( $output, $module, $render_slug ) {

		$order_class = $module->get_module_order_class( $render_slug );

		// Get button.
		$button_text       = ! empty( $module->props['unfold_btn_text'] ) ? $module->props['unfold_btn_text'] : esc_html__( 'View more', 'divi-plus' );
		$enable_custom_btn = $module->props['unfold_btn_custom_styling'];
		$button_use_icon   = $module->props['unfold_button_use_icon'];
		$button_icon       = $module->props['unfold_button_icon'];

		// Create processed icon.
		$processed_icon = ( 'on' === $enable_custom_btn && 'on' === $button_use_icon && ! empty( $button_icon ) ) ? 
			sprintf( 'data-icon=%1$s', et_pb_process_font_icon( $button_icon ) ) 
		: '';

		$viewmore_btn = sprintf(
			'<a href="#%1$s" class="et_pb_button%3$s"%4$s>%2$s</a>',
			esc_attr( $order_class ),
			esc_html( $button_text ),
			( ! empty( $processed_icon ) ) ? ' et_pb_custom_button_icon' : '',
			esc_attr( $processed_icon )
		);

		$output = sprintf(
			'<div class="dipl-unfold-wrap %1$s__dipl-unfold_wrap">
				%2$s
				<div class="dipl-unfold-button %1$s__dipl-unfold">%3$s</div>
			</div>',
			esc_attr( $order_class ),
			$output,
			$viewmore_btn
		);

		// Custom styles.
		// -------------.
		ET_Builder_Element::set_style( $render_slug, array(
			'selector'    => '%%order_class%%__dipl-unfold_wrap',
			'declaration' => 'position: relative; overflow: hidden;',
		) );
		ET_Builder_Element::set_style( $render_slug, array(
			'selector'    => '%%order_class%%__dipl-unfold',
			'declaration' => 'position: absolute; display: flex; justify-content: center;
				width: 100%; bottom: 0; left: 0; transition: all .3s; z-index: 11;',
		) );
		ET_Builder_Element::set_style( $render_slug, array(
			'selector'    => '%%order_class%%__dipl-unfold .et_pb_button',
			'declaration' => 'display: inline-block;',
		) );

		// Set Height.
		$unfold_height = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_height' );
		et_pb_responsive_options()->generate_responsive_css( $unfold_height, '%%order_class%%__dipl-unfold_wrap', 'max-height', $render_slug, '!important;', 'range' );

		// Gradient background color.
		$background_1            = $module->props['unfold_background_1'];
		$background_2            = $module->props['unfold_background_2'];
		$background_2            = ! empty( $background_2 ) ? $background_2 : $background_1;

		$background_1_hover      = $module->get_hover_value( 'unfold_background_1' );
		$background_2_hover      = $module->get_hover_value( 'unfold_background_2' );
		$background_2_hover      = !empty( $background_2_hover ) ? $background_2_hover : $background_1_hover;

		$gradient_type           = $module->props['unfold_gradient_type'];
		$linear_direction        = $module->props['unfold_linear_direction'];
		$radial_direction        = $module->props['unfold_radial_direction'];
		$gradient_start_position = $module->props['unfold_gradient_start_position'];
		$gradient_end_position 	 = $module->props['unfold_gradient_end_position'];

		if ( 'linear-gradient' === $gradient_type ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%__dipl-unfold',
				'declaration' => sprintf(
					'background: linear-gradient(%1$s, %2$s %3$s, %4$s %5$s);',
					esc_attr( $linear_direction ),
					esc_attr( $background_1 ),
					esc_attr( $gradient_start_position ),
					esc_attr( $background_2 ),
					esc_attr( $gradient_end_position )
				)
			) );
			if ( ! empty( $background_1_hover ) && ! empty( $background_2_hover ) ) {
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%%__dipl-unfold:hover',
					'declaration' => sprintf(
						'background: linear-gradient(%1$s, %2$s %3$s, %4$s %5$s);',
						esc_attr( $linear_direction ),
						esc_attr( $background_1_hover ),
						esc_attr( $gradient_start_position ),
						esc_attr( $background_2_hover ),
						esc_attr( $gradient_end_position )
					)
				) );
			}
		}
		if ( 'radial-gradient' === $gradient_type ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%%__dipl-unfold',
				'declaration' => sprintf(
					'background: radial-gradient(%1$s, %2$s %3$s, %4$s %5$s);',
					esc_attr( $radial_direction ),
					esc_attr( $background_1 ),
					esc_attr( $gradient_start_position ),
					esc_attr( $background_2 ),
					esc_attr( $gradient_end_position )
				)
			) );
		}

		// Button styling.
		if ( 'on' === $enable_custom_btn ) {
			// Font size desktop.
			if ( ! empty( $module->props['unfold_btn_font'] ) ) {
				$font_styles = et_builder_set_element_font( $module->props['unfold_btn_font'] );
				if ( ! empty($font_styles) ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%%__dipl-unfold .et_pb_button',
						'declaration' => sanitize_text_field( $font_styles ),
					) );
				}
			}
			// Font size tablet.
			if ( ! empty( $module->props['unfold_btn_font_tablet'] ) ) {
				$font_styles = et_builder_set_element_font( $module->props['unfold_btn_font_tablet'] );
				if ( ! empty($font_styles) ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%%__dipl-unfold .et_pb_button',
						'declaration' => sanitize_text_field( $font_styles ),
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					) );
				}
			}
			// Font size mobile.
			if ( ! empty( $module->props['unfold_btn_font_phone'] ) ) {
				$font_styles = et_builder_set_element_font( $module->props['unfold_btn_font_phone'] );
				if ( ! empty($font_styles) ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%%__dipl-unfold .et_pb_button',
						'declaration' => sanitize_text_field( $font_styles ),
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					) );
				}
			}
			// Font size hover.
			if ( ! empty( $module->props['unfold_btn_font__hover'] ) ) {
				$font_styles = et_builder_set_element_font( $module->props['unfold_btn_font__hover'] );
				if ( ! empty($font_styles) ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%%__dipl-unfold .et_pb_button:hover',
						'declaration' => sanitize_text_field( $font_styles ),
					) );
				}
			}

			// Button font size.
			$btn_font_size = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_font_size' );
			et_pb_responsive_options()->generate_responsive_css( $btn_font_size, '%%order_class%%__dipl-unfold .et_pb_button', 'font-size', $render_slug, '', 'range' );

			// Button color.
			$btn_color = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_color' );
			et_pb_responsive_options()->generate_responsive_css( $btn_color, '%%order_class%%__dipl-unfold .et_pb_button', 'color', $render_slug, '', 'color' );
			$btn_color_hover = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_color__hover' );
			et_pb_responsive_options()->generate_responsive_css( $btn_color_hover, '%%order_class%%__dipl-unfold .et_pb_button:hover', 'color', $render_slug, '', 'color' );

			// Button background color.
			$btn_bg_color = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_background' );
			et_pb_responsive_options()->generate_responsive_css( $btn_bg_color, '%%order_class%%__dipl-unfold .et_pb_button', 'background-color', $render_slug, '', 'color' );
			$btn_bg_color_hover = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_background__hover' );
			et_pb_responsive_options()->generate_responsive_css( $btn_bg_color_hover, '%%order_class%%__dipl-unfold .et_pb_button:hover', 'background-color', $render_slug, '', 'color' );

			// Button Border size.
			$btn_border_size = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_border_size' );
			et_pb_responsive_options()->generate_responsive_css( $btn_border_size, '%%order_class%%__dipl-unfold .et_pb_button', 'border-width', $render_slug, '', 'range' );
			$btn_border_size_hover = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_border_size__hover' );
			et_pb_responsive_options()->generate_responsive_css( $btn_border_size_hover, '%%order_class%%__dipl-unfold .et_pb_button:hover', 'border-width', $render_slug, '', 'range' );

			// Button Border color.
			$btn_border_color = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_border_color' );
			et_pb_responsive_options()->generate_responsive_css( $btn_border_color, '%%order_class%%__dipl-unfold .et_pb_button', 'border-color', $render_slug, '', 'color' );
			$btn_border_color_hover = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_border_color__hover' );
			et_pb_responsive_options()->generate_responsive_css( $btn_border_color_hover, '%%order_class%%__dipl-unfold .et_pb_button:hover', 'border-color', $render_slug, '', 'color' );

			// Button Border radius.
			$btn_border_radius = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_border_radius' );
			et_pb_responsive_options()->generate_responsive_css( $btn_border_radius, '%%order_class%%__dipl-unfold .et_pb_button', 'border-radius', $render_slug, '', 'range' );
			$btn_border_radius_hover = et_pb_responsive_options()->get_property_values( $module->props, 'unfold_btn_border_radius__hover' );
			et_pb_responsive_options()->generate_responsive_css( $btn_border_radius_hover, '%%order_class%%__dipl-unfold .et_pb_button:hover', 'border-radius', $render_slug, '', 'range' );

			// Button icon styling.
			if ( 'on' === $button_use_icon && ! empty( $button_icon ) ) {

				$icon_placement = $module->props['unfold_button_icon_placement'];
				$icon_psudo     = ( 'left' === $icon_placement ) ? '::before' : '::after';

				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%%__dipl-unfold .et_pb_button' . esc_html( $icon_psudo ),
					'declaration' => 'content: attr(data-icon) !important;',
				) );
				if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
					$module->generate_styles( array(
						'utility_arg'    => 'icon_font_family',
						'render_slug'    => $render_slug,
						'base_attr_name' => 'unfold_button_icon',
						'important'      => true,
						'selector'       => '%%order_class%%__dipl-unfold .et_pb_button' . esc_html( $icon_psudo ),
						'processor'      => array(
							'ET_Builder_Module_Helper_Style_Processor',
							'process_extended_icon',
						),
					) );
				}

				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%%__dipl-unfold .et_pb_button' . esc_html( $icon_psudo ),
					'declaration' => 'display: inline-block; font-size: inherit !important; line-height: inherit !important;',
				) );

				if ( 'left' === $icon_placement ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%%__dipl-unfold .et_pb_button::after',
						'declaration' => 'display: none !important;',
					) );
				}

				$button_on_hover = $module->props['unfold_button_on_hover'];
				if ( 'on' === $button_on_hover ) {
					if ( 'left' === $icon_placement ) {
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%% + %%order_class%%__dipl-unfold .et_pb_button:hover',
							'declaration' => 'padding-left: 2em !important; padding-right: 0.7em !important; transition: all .2s;',
						) );
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%% + %%order_class%%__dipl-unfold .et_pb_button:hover:before',
							'declaration' => 'right: auto; margin-left: -1.3em; opacity: 1;',
						) );
					} else {
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%% + %%order_class%%__dipl-unfold .et_pb_button:hover',
							'declaration' => 'padding-right: 2em !important; padding-left: 0.7em !important; transition: all .2s;',
						) );
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%% + %%order_class%%__dipl-unfold .et_pb_button:hover:after',
							'declaration' => 'left: auto; margin-left: .3em; opacity: 1;',
						) );
					}
				} else {
					if ( 'left' === $icon_placement ) {
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%%__dipl-unfold .et_pb_button, %%order_class%%__dipl-unfold .et_pb_button:hover',
							'declaration' => 'padding-left: 2em !important; padding-right: 0.7em !important; transition: all .2s;',
						) );
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%%__dipl-unfold .et_pb_button:before, %%order_class%%__dipl-unfold .et_pb_button:hover:before',
							'declaration' => 'right: auto; margin-left: -1.3em; opacity: 1;',
						) );
					} else {
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%%__dipl-unfold .et_pb_button, %%order_class%%__dipl-unfold .et_pb_button:hover',
							'declaration' => 'padding-right: 2em !important; padding-left: 0.7em !important; transition: all .2s;',
						) );
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => '%%order_class%%__dipl-unfold .et_pb_button:after, %%order_class%%__dipl-unfold .et_pb_button:hover:after',
							'declaration' => 'left: auto; margin-left: .3em; opacity: 1;',
						) );
					}
				}
			} elseif ( 'off' === $button_use_icon ) {
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%%__dipl-unfold .et_pb_button::before, %%order_class%%__dipl-unfold .et_pb_button::after',
					'declaration' => 'display: none !important;',
				) );
			}
		}

		// Set advance settings to manage margin padding.
		$module->advanced_fields['unfold_spacings'] = array(
			'unfold_container' => array(
				'margin_padding' => array(
					'css' => array(
						'use_margin' => false,
						'padding'    => "%%order_class%% + %%order_class%%__dipl-unfold",
						'important'  => 'all',
					),
				)
			),
			'unfold_button' => array(
				'margin_padding' => array(
					'css' => array(
						'margin'    => "%%order_class%% + %%order_class%%__dipl-unfold .et_pb_button",
						'padding'   => "%%order_class%% + %%order_class%%__dipl-unfold .et_pb_button",
						'important' => 'all',
					),
				)
			)
		);

		$fields = array( 'unfold_spacings' );
		DiviPlusHelper::process_advanced_margin_padding_css( $module, $render_slug, $module->margin_padding, $fields );

		return $output;
	}

	public function dipl_unfold_get_toggles() {
		return array(
			'dipl_unfold_settings' => [
				'title'    => esc_html__( 'Unfold Settings', 'divi-popup' ),
				'priority' => 90,
			],
			'dipl_unfold_button' => [
				'title'    => esc_html__( 'Unfold Button', 'divi-popup' ),
				'priority' => 90,
			]
		);
	}
}

new DIPL_Unfold;
