<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_DropdownButtonItem extends ET_Builder_Module {
	public $slug       = 'dipl_dropdown_button_item';
	public $type       = 'child';
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
		$this->name                        = esc_html__( 'DP Dropdown Button Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Dropdown Item', 'divi-plus' );
		$this->child_title_var             = 'dropdown_item_text';
		$this->main_css_element            = '.dipl_dropdown_button %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'dropdown_link' => esc_html__( 'Dropdown Link', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'link_text' => esc_html__( 'Dropdown Link Text', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} a",
						'important'	=> 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'link_text',
				),
			),
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => "{$this->main_css_element}",
							'border_radii'  => "{$this->main_css_element}",
						),
					),
				)
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element}",
					),
				)
			),
			'margin_padding' => array(
				'use_margin'    => false,
				'custom_padding' => array(
                    'default_on_front' => '10px|15px|10px|15px|true|true',
                ),
				'css'           => array(
					'main'      => "{$this->main_css_element} a",
					'important' => 'all',
				),
			),
			'max_width'      => false,
			'height'         => false,
			'text'           => false,
			'filters'        => false,
			'transform'      => false,
			'link_options'   => false,
			'background'     => array(
				'use_background_video' => false,
				'css' => array(
					'main' => "{$this->main_css_element} a",
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'dropdown_item_text' => array(
				'label'            => esc_html__( 'Dropdown Item Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'default'          => esc_html__( 'Dropdown Item', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Dropdown Item', 'divi-plus' ),
				'toggle_slug'      => 'dropdown_link',
				'description'      => esc_html__( 'Here you can input the dropdown item link text.', 'divi-plus' ),
			),
			'dropdown_item_link' => array(
				'label'            => esc_html__( 'Dropdown Item URL', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'url',
				'default'          => '',
				'toggle_slug'      => 'dropdown_link',
				'description'      => esc_html__( 'Here you can input the dropdown item link text.', 'divi-plus' ),
			),
			'link_new_window' => array(
				'label'        	  => esc_html__( 'Dropdown Link Target', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'dropdown_link',
				'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
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

		// Get the props.
		$dropdown_item_text = sanitize_text_field( $this->props['dropdown_item_text'] ) ?? esc_html__( 'Dropdown Item', 'divi-plus' );
		$dropdown_item_link = sanitize_text_field( $this->props['dropdown_item_link'] ) ?? '';
		$link_new_window    = sanitize_text_field( $this->props['link_new_window'] ) ?? 'off';

		$render_output = '';
		if ( ! empty( $dropdown_item_link ) ) {
			$render_output = sprintf(
				'<a href="%1$s"%3$s>%2$s</a>',
				esc_url( $dropdown_item_link ),
				esc_html( $dropdown_item_text ),
				( 'on' === $link_new_window ) ? ' target="_blank"' : ''
			);
		}

		self::$rendering = false;
		return $render_output;
	}

	protected function _render_module_wrapper( $output = '', $render_slug = '' ) {
		$wrapper_settings    = $this->get_wrapper_settings( $render_slug );
		$slug                = $render_slug;
		$outer_wrapper_attrs = $wrapper_settings['attrs'];
		
		/**
		 * Filters the HTML attributes for the module's outer wrapper. The dynamic portion of the
		 * filter name, '$slug', corresponds to the module's slug.
		 *
		 * @since 3.23 Add support for responsive video background.
		 * @since 3.1
		 *
		 * @param string[]           $outer_wrapper_attrs
		 * @param ET_Builder_Element $module_instance
		 */
		$outer_wrapper_attrs = apply_filters( "et_builder_module_{$slug}_outer_wrapper_attrs", $outer_wrapper_attrs, $this );

		return sprintf(
			'<div%1$s>
				%2$s
			</div>',
			et_html_attrs( $outer_wrapper_attrs ),
			$output
		);
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_dropdown_button', $modules ) ) {
		new DIPL_DropdownButtonItem();
	}
} else {
	new DIPL_DropdownButtonItem();
}
