<?php
add_settings_section(
	'el-settings-extensions-unfold-section',
	'', 
	'', 
	self::$menu_slug
);

// $unfold_areas = array(
// 	'sections' 	=> esc_html__( 'Sections', 'divi-plus' ),
// 	'rows' 		=> esc_html__( 'Rows', 'divi-plus' ),
// 	'columns' 	=> esc_html__( 'Columns', 'divi-plus' ),
// 	'modules' 	=> esc_html__( 'Modules', 'divi-plus' ),
// );

// Removed option because, currently we have integrated for only modules.
// add_settings_field(
// 	'el-dipl-unfold-areas',
// 	esc_html__( 'Enable Unfold for', 'divi-plus' ),
// 	array( $this, 'el_mutiple_checkbox_render' ),
// 	esc_html( self::$menu_slug ),
// 	'el-settings-extensions-unfold-section',
// 	array(
// 		'field_id'     => 'dipl-unfold-areas',
// 		'setting'      => esc_html( self::$option ),
// 		'default'      => '',
// 		'id'           => 'el-dipl-unfold-areas',
// 		'data-type'    => 'elicus-option',
// 		'list_options' => $unfold_areas,
// 		'info'         => esc_html__( 'Here you can select the builder areas where you want to use unfold.', 'divi-plus' ),
// 	)
// );

add_settings_field(
	'el-dipl-unfold-areas',
	esc_html__( 'Enable Unfold For All Modules', 'divi-plus' ),
	array( $this, 'el_toggle_render' ),
	esc_html( self::$menu_slug ),
	'el-settings-extensions-unfold-section',
	array(
		'field_id'   => 'dipl-unfold-areas',
		'setting'    => esc_html( self::$option ),
		'default'    => 'off',
		'id'         => 'el-dipl-unfold-areas',
		'data-type'  => 'elicus-option',
		'info'       => esc_html__( 'Here you can enable to use unfold feature for all modules.', 'divi-plus' ),
	)
);
