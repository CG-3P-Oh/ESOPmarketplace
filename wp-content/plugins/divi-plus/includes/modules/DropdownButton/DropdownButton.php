<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_DropdownButton extends ET_Builder_Module {
	public $slug       = 'dipl_dropdown_button';
	public $child_slug = 'dipl_dropdown_button_item';
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
		$this->name             = esc_html__( 'DP Dropdown Button', 'divi-plus' );
		$this->child_item_text  = esc_html__( 'Dropdown Item', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'display' => esc_html__( 'Display', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'dropdown_button' => esc_html__( 'Dropdown Button', 'divi-plus' ),
					'dropdown_menu'   => esc_html__( 'Dropdown Menu', 'divi-plus' ),
					'link_text'       => esc_html__( 'Dropdown Link Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'link_text' => array(
					'label'     => esc_html__( 'Link', 'divi-plus' ),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '500',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css' => array(
						'main'      => "%%order_class%% .dipl_dropdown_button_item a",
						'important'	=> 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'link_text',
				),
			),
			'button' => array(
				'dropdown_button' => array(
					'label' => esc_html__( 'Dropdown Button', 'divi-plus' ),
					'css'   => array(
						'main'      => "%%order_class%% .dipl_dropdown_button_wrap .et_pb_button",
						'alignment' => "%%order_class%% .dipl_dropdown_button_wrap",
						'important' => 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "%%order_class%% .dipl_dropdown_button_wrap",
							'padding'   => "%%order_class%% .dipl_dropdown_button_wrap .et_pb_button",
							'important' => 'all',
						),
					),
					'box_shadow' => array(
						'css' => array(
							'main'      => "%%order_class%% .dipl_dropdown_button_wrap .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dropdown_button',
				),
			),
			'borders' => array(
				'submenu' => array(
					'label_prefix' => esc_html__( 'Dropdown', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_dropdown_menu_items',
							'border_styles' => '%%order_class%% .dipl_dropdown_menu_items',
							'important'     => 'all',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'dropdown_menu',
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
				'submenu' => array(
					'label'       => esc_html__( 'Dropdown Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => '%%order_class%% .dipl_dropdown_menu_items',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'dropdown_menu',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'dropdow_button_spacing' => array(
				'submenu' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_dropdown_menu_items",
							'important'  => 'all',
						),
					),
				),
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
				'use_background_video' => false,
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'button_text' => array(
					'label'            => esc_html__( 'Button Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => esc_html__( 'Click here', 'divi-plus' ),
					'default_on_front' => esc_html__( 'Click here', 'divi-plus' ),
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
				),
				'trigger_type' => array(
					'label'           => esc_html__( 'Trigger Action', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'click' => esc_html__( 'Click', 'divi-plus' ),
						'hover' => esc_html__( 'Hover', 'divi-plus' ),
					),
					'default'         => 'click',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the dropdown trigger action.', 'divi-plus' ),
				),
				'dropdown_direction' => array(
					'label'           => esc_html__( 'Dropdown Direction', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'bottom' => esc_html__( 'Bottom', 'divi-plus' ),
						'top'    => esc_html__( 'Top', 'divi-plus' ),
						'left'   => esc_html__( 'Left', 'divi-plus' ),
						'right'  => esc_html__( 'Right', 'divi-plus' ),
					),
					'default'         => 'bottom',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the dropdown trigger action.', 'divi-plus' ),
				),
				'submenu_minwidth' => array(
					'label'            => esc_html__( 'Dropdown Min Width', 'divi-plus' ),
					'type'             => 'range',
					'range_settings'   => array(
						'min'  => '10',
						'max'  => '1000',
						'step' => '1',
					),
					'default'          => '180px',
					'default_on_front' => '180px',
					'mobile_options'   => true,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'dropdown_menu',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease dropdown menu width.', 'divi-plus' ),
				),
				'submenu_custom_padding' => array(
					'label'           => esc_html__( 'Dropdown Padding', 'divi-plus' ),
					'type'            => 'custom_padding',
					'option_category' => 'layout',
					'mobile_options'  => true,
					'default'         => '5px||5px||true|true',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'dropdown_menu',
					'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'submenu_background_color' => array(
					'label'             => esc_html__( 'Dropdown Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'submenu_background',
					'context'           => 'submenu_background_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => array_merge(
						$this->generate_background_options( 'submenu_background', 'button', 'advanced', 'dropdown_menu', 'submenu_background_color' ),
					),
					'mobile_options'    => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'dropdown_menu',
					'description'       => esc_html__( 'Adjust color, gradient, and image to customize the background style of the tooltip.' ),
				)
			),
			$this->generate_background_options( 'submenu_background', 'skip', 'advanced', 'dropdown_menu', 'submenu_background_color' ),
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

		// Load style and scripts.
		wp_enqueue_script( 'dipl-dropdown-button-script', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/DropdownButton/dipl-dropdown-button.min.js', array('jquery'), '1.0.0', true );

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-dropdown-button-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/DropdownButton/' . $file . '.min.css', array(), '1.0.0' );

		$multi_view             = et_pb_multi_view_options( $this );

		$button_text            = sanitize_text_field( $this->props['button_text'] ) ?? esc_html__( 'Click here', 'divi-plus' );
		$custom_dropdown_button = sanitize_text_field( $this->props['custom_dropdown_button'] );
		$dropdown_button_icon   = sanitize_text_field( $this->props['dropdown_button_icon'] );
		$trigger_type           = sanitize_text_field( $this->props['trigger_type'] ) ?? 'click';
		$direction              = sanitize_text_field( $this->props['dropdown_direction'] ) ?? 'bottom';

		self::$rendering = true;

		// Render button.
		$render_button = $this->render_button( array(
			'button_text'         => esc_html( $button_text ),
			'button_text_escaped' => true,
			'button_url'          => '#dipl-dropdown-button',
			'button_classname'    => array(),
			'button_custom'       => ! empty( $custom_dropdown_button ) ? $custom_dropdown_button : 'off',
			'custom_icon'         => $dropdown_button_icon,
			'has_wrapper'         => false,
			'button_rel'          => esc_html( $this->props['dropdown_button_rel'] ),
		) );

		// Final output.
		$render_output = $multi_view->render_element( array(
			'tag'     => 'div',
			'attrs'   => array(
				'class'             => 'dipl_dropdown_button_wrap',
				'data-trigger-type' => esc_attr( $trigger_type ),
				'data-direction'    => esc_attr( $direction )
			),
			'content' => sprintf(
				'<div class="dipl_dropdown_button_inner">
					%1$s<div class="dipl_dropdown_menu_items">%2$s</div>
				</div>',
				et_core_esc_previously( $render_button ),
				et_core_esc_previously( $this->content )
			)
		) );

		// Dropdown min width.
		$submenu_minwidth = et_pb_responsive_options()->get_property_values( $this->props, 'submenu_minwidth' );
		if ( ! empty( array_filter( $submenu_minwidth ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $submenu_minwidth, '%%order_class%% .dipl_dropdown_menu_items', 'min-width', $render_slug, '!important;', 'range' );
		}

		$args = array(
			'render_slug' => $render_slug,
			'props'		  => $this->props,
			'fields'	  => $this->fields_unprocessed,
			'module'      => $this,
			'backgrounds' => array(
				'submenu_background' => array(
					'normal' => "%%order_class%% .dipl_dropdown_menu_items",
					'hover' => "%%order_class%% .dipl_dropdown_menu_items:hover",
				),
			),
		);
		DiviPlusHelper::process_background( $args );
		
		$fields = array( 'dropdow_button_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_dropdown_button', $modules ) ) {
		new DIPL_DropdownButton();
	}
} else {
	new DIPL_DropdownButton();
}
