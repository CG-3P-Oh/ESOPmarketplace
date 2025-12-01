<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2024 Elicus Technologies Private Limited
 * @version     1.10.0
 */
class DIPL_BackgroundSwitcherItem extends ET_Builder_Module {
	public $slug       = 'dipl_background_switcher_item';
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
		$this->name                        = esc_html__( 'DP Background Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Background Item', 'divi-plus' );
		$this->child_title_var             = 'title';
		$this->main_css_element            = '.dipl_background_switcher %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'button'       => esc_html__( 'Button', 'divi-plus' )
				)
			),
			'advanced' => array(
				'toggles' => array(
					'switcher_content' => array(
						'title'             => esc_html__( 'Switcher Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'       => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'description' => array( 'name' => esc_html__( 'Description', 'divi-plus' ) )
						),
					),
					'button' => array(
						'title' => esc_html__( 'Button', 'divi-plus' ),
					)
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'        => esc_html__( 'Title', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '22px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'header_level' => array(
						'default'  => 'h2',
					),
					'css'          => array(
						'main'     => "{$this->main_css_element} .dipl-bg-switcher-title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'switcher_content',
					'sub_toggle'  => 'title',
				),
				'description' => array(
					'label'        => esc_html__( 'Description', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "{$this->main_css_element} .dipl-bg-switcher-desc",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'switcher_content',
					'sub_toggle'  => 'description',
				),
			),
			'button' => array(
			    'button' => array(
				    'label' => esc_html__( 'Button', 'divi-plus' ),
				    'css' => array(
						'main'      => "%%order_class%% .dipl-bg-switcher-btn-wrap .et_pb_button",
						'alignment' => "%%order_class%% .dipl-bg-switcher-btn-wrap",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "%%order_class%% .dipl-bg-switcher-btn-wrap",
							'padding'   => "%%order_class%% .dipl-bg-switcher-btn-wrap .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => true,
					'box_shadow'      => false,
					'depends_on'      => array( 'show_button' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'button',
				),
			),
			'borders' => array(
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
			'max_width'  => false,
            'height'     => false,
			'text'       => false,
			'filters'    => false,
			'transform'  => false,
			'background' => false,
		);
	}

	public function get_fields() {

		// Generate background fields and make require changes.
		$background_fields = array_merge(
			$this->generate_background_options( 'item_background_bg', 'color', 'general', 'main_content', 'item_background_bg_color' ),
			$this->generate_background_options( 'item_background_bg', 'gradient', 'general', 'main_content', 'item_background_bg_color' ),
			$this->generate_background_options( 'item_background_bg', 'image', 'general', 'main_content', 'item_background_bg_color' )
		);

		// Remove parallax fields.
		unset( $background_fields['item_background_bg_parallax'] );
		unset( $background_fields['item_background_bg_parallax_method'] );
		unset( $background_fields['item_background_bg_size']['show_if'] );
		unset( $background_fields['item_background_bg_size']['options']['custom'] );
		unset( $background_fields['item_background_bg_image_width'] );
		unset( $background_fields['item_background_bg_image_height'] );
		unset( $background_fields['item_background_bg_position']['show_if_not'] );
		unset( $background_fields['item_background_bg_horizontal_offset'] );
		unset( $background_fields['item_background_bg_vertical_offset'] );
		unset( $background_fields['item_background_bg_repeat']['show_if_not'] );

		return array_merge( 
			array(
				'title' => array(
					'label'            => esc_html__( 'Title', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'dynamic_content'  => 'text',
					'default'          => esc_html__( 'Your title goes here.', 'divi-plus' ),
					'default_on_front' => esc_html__( 'Your title goes here.', 'divi-plus' ),
					'description'      => esc_html__( 'This is the title displayed for the countdown timer.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				'description' => array(
					'label'            => esc_html__( 'Description', 'divi-plus' ),
					'type'             => 'textarea',
					'option_category'  => 'basic_option',
					'description'      => esc_html__( 'This is the title displayed for the countdown timer.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				// 'background_image' => array(
				// 	'label'              => esc_html__( 'Background Image', 'divi-plus' ),
				// 	'type'               => 'upload',
				// 	'option_category'    => 'basic_option',
				// 	'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				// 	'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				// 	'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				// 	'dynamic_content'  	 => 'image',
				// 	'description'        => esc_html__( 'Here you can upload the image to set on background on mouse hover.', 'divi-plus' ),
				// 	'toggle_slug'        => 'main_content',
				// ),
				'item_background_bg_color' => array(
					'label'                 => esc_html__( 'Item Background', 'divi-plus' ),
					'type'                  => 'background-field',
					'base_name'             => 'item_background_bg',
					'context'               => 'item_background_bg_color',
					'option_category'       => 'button',
					'custom_color'          => true,
					'background_fields'     => $background_fields,
					'hover'                 => false,
					'mobile_options'        => true,
					'toggle_slug'           => 'main_content',
					'description'           => esc_html__( 'Here you can set the background image, color, and gradient to show on mouse hover.', 'divi-plus' ),
				),
				'show_button' => array(
					'label'     	  => esc_html__( 'Show Button', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'basic_option',
					'options'         => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default'         => 'off',
					'toggle_slug'     => 'button',
					'description'     => esc_html__( 'Here you can choose whether or not show the button.', 'divi-plus' ),
				),
				'button_text' => array(
					'label'    			=> esc_html__( 'Button Text', 'divi-plus' ),
					'type'              => 'text',
					'option_category'   => 'basic_option',
					'show_if'           => array( 'show_button' => 'on' ),
					'default'			=> esc_html__( 'Read more', 'divi-plus' ),
					'default_on_front'	=> esc_html__( 'Read more', 'divi-plus' ),
					'toggle_slug'       => 'button',
					'description'       => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
				),
				'button_url' => array(
					'label'           => esc_html__( 'Button Link URL', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array( 'show_button' => 'on' ),
					'dynamic_content' => 'url',
					'toggle_slug'     => 'button',
					'description'  	  => esc_html__( 'Here you can input the destination URL for the button to open when clicked.', 'divi-plus' ),
				),
				'button_new_window' => array(
					'label'        	  => esc_html__( 'Button Link Target', 'divi-plus' ),
					'type'        	  => 'select',
					'option_category' => 'configuration',
					'show_if'         => array( 'show_button' => 'on' ),
					'options'         => array(
						'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
						'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
					),
					'toggle_slug'     => 'button',
					'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
				),
			),
			$this->generate_background_options( 'item_background_bg', 'skip', 'general', 'main_content', 'item_background_bg_color' ),
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

		global $dipl_bg_switcher_item_counts;

		// Increase it, as default zero.
		$dipl_bg_switcher_item_counts++;

		$multi_view       = et_pb_multi_view_options( $this );

		$title_text       = esc_html( $this->props['title'] );
		$description_text = esc_html( $this->props['description'] );

		$show_button      = esc_attr( $this->props['show_button'] );
		$button_url       = esc_url( $this->props['button_url'] );

		$title_level      = et_pb_process_header_level( $this->props['title_level'], 'h2' );

		$title = '';
		if ( ! empty( $title_text ) ) {
			$title = $multi_view->render_element( array(
				'tag'        => $title_level,
				'content'    => $title_text,
				'attrs'      => array(
					'class' => 'dipl-bg-switcher-title',
				)
			) );
		}
		$description = '';
		if ( ! empty( $description_text ) ) {
			$description = $multi_view->render_element( array(
				'tag'        => 'div',
				'content'    => $description_text,
				'attrs'      => array(
					'class' => 'dipl-bg-switcher-desc',
				)
			) );
		}

		$render_button = '';
		if ( 'on' === $show_button && ! empty( $button_url ) ) {
			$render_button = $this->render_button( array(
				'button_text'         => esc_attr( $this->props['button_text'] ),
				'button_text_escaped' => true,
				'has_wrapper'      	  => false,
				'button_url'          => esc_url( $button_url ),
				'url_new_window'      => esc_attr( $this->props['button_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
				'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
			) );
			$render_button = sprintf(
				'<div class="et_pb_button_wrapper dipl-bg-switcher-btn-wrap">%1$s</div>',
				et_core_esc_previously( $render_button )
			);
		}

		$render_hover_content = '';
		if ( ! empty( $description ) || ! empty( $render_button ) ) {
			$render_hover_content = sprintf(
				'<div class="dipl_bg_switcher_hover_content">%1$s %2$s</div>',
				et_core_esc_previously( $description ),
				et_core_esc_previously( $render_button )
			);
		}

		$render_output = sprintf(
			'<div class="dipl_bg_switcher_item_wrap">
				<div class="dipl_bg_switcher_content">
					%1$s %2$s
				</div>
			</div>',
			et_core_esc_previously( $title ),
			et_core_esc_previously( $render_hover_content )
		);

		$args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'item_background_bg' => array(
					'normal' => "{$this->main_css_element} + .dipl_background_switcher_image .dipl_switcher_item_background",
					'hover' => "{$this->main_css_element} + .dipl_background_switcher_image .dipl_switcher_item_background:hover",
	 			)
			),
		);
		DiviPlusHelper::process_background( $args );

		if ( 1 === $dipl_bg_switcher_item_counts ) {
			$this->add_classname( 'dipl-bg-switcher-hover' );
		}

		self::$rendering = false;
		return $render_output;
	}

	protected function _render_module_wrapper( $output = '', $render_slug = '' ) {
		$wrapper_settings    = $this->get_wrapper_settings( $render_slug );
		$slug                = $render_slug;
		$outer_wrapper_attrs = $wrapper_settings['attrs'];
		$inner_wrapper_attrs = $wrapper_settings['inner_attrs'];

		// $switcher_image = '';
		// if ( ! empty( $this->props['background_image'] ) ) {
		// 	$switcher_image = sprintf(
		// 		'<div class="dipl_background_switcher_image">
		// 			<img src="%1$s" alt="%2$s" />
		// 		</div>',
		// 		esc_url( $this->props['background_image'] ),
		// 		esc_html__( 'Background Switcher Image', 'divi-plus' )
		// 	);
		// }
		$switcher_background = '<div class="dipl_background_switcher_image"><div class="dipl_switcher_item_background"></div></div>';

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

		/**
		 * Filters the HTML attributes for the module's inner wrapper. The dynamic portion of the
		 * filter name, '$slug', corresponds to the module's slug.
		 *
		 * @since 3.1
		 *
		 * @param string[]           $inner_wrapper_attrs
		 * @param ET_Builder_Element $module_instance
		 */
		$inner_wrapper_attrs = apply_filters( "et_builder_module_{$slug}_inner_wrapper_attrs", $inner_wrapper_attrs, $this );

		return sprintf(
			'<div%1$s>
				<div%2$s>
					%3$s
				</div>
			</div>
			%4$s',
			et_html_attrs( $outer_wrapper_attrs ),
			et_html_attrs( $inner_wrapper_attrs ),
			$output,
			et_core_esc_previously( $switcher_background ) // #4
		);
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_background_switcher', $modules ) ) {
		new DIPL_BackgroundSwitcherItem();
	}
} else {
	new DIPL_BackgroundSwitcherItem();
}
