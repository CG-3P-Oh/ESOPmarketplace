<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.20.0
 */
class DIPL_AdvancedCounter extends ET_Builder_Module {
	public $slug       = 'dipl_advanced_counter';
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
		$this->name             = esc_html__( 'DP Advanced Counter', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'display'      => esc_html__( 'Display', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'title'  => esc_html__( 'Title', 'divi-plus' ),
					'number' => esc_html__( 'Number', 'divi-plus' ),
					'desc'   => array(
						'title' => esc_html__( 'Description', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'bb_icons_support'  => true,
						'sub_toggles'       => array(
							'p' => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a' => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
							'ul' => array(
								'name' => 'UL',
								'icon' => 'list',
							),
							'ol' => array(
								'name' => 'OL',
								'icon' => 'numbered-list',
							),
							'quote' => array(
								'name' => 'QUOTE',
								'icon' => 'text-quote',
							),
						),
					),
					'image_style' => esc_html__( 'Image', 'divi-plus' ),
					'icon_style'  => esc_html__( 'Icon', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'     => esc_html__( 'Title', 'divi-plus' ),
					'font_size' => array(
						'default' => '32px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'header_level' => array(
						'default' => 'h3',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_title",
						'hover'     => "{$this->main_css_element} .dipl_advanced_counter_title:hover",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'title',
				),
				'number' => array(
					'label'     => esc_html__( 'Number', 'divi-plus' ),
					'font_size' => array(
						'default' => '50px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'text_color' => array(
						'default' => '#000000',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_number",
						'hover'     => "{$this->main_css_element} .dipl_advanced_counter_number:hover",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'number',
				),
				'desc_text' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_description, {$this->main_css_element} .dipl_advanced_counter_description p",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'p',
				),
				'desc_link' => array(
					'label'     => esc_html__( 'Description Link', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_description a",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'a',
				),
				'desc_ul' => array(
					'label'     => esc_html__( 'Description Unordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_description ul li",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'ul',
				),
				'desc_ol' => array(
					'label'     => esc_html__( 'Description Ordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'   => '1',
							'max'   => '100',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_description ol li",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'ol',
				),
				'desc_quote' => array(
					'label'     => esc_html__( 'Description Blockquote', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'   => '1',
							'max'   => '100',
							'step'  => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_advanced_counter_description blockquote",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'desc',
					'sub_toggle'  => 'quote',
				),
			),
			'borders' => array(
				'image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_advanced_counter_image img",
							'border_styles' => "%%order_class%% .dipl_advanced_counter_image img",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'use_icon' ),
					'depends_show_if' => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_style',
				),
				'icon' => array(
					'label_prefix' => esc_html__( 'Icon', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_advanced_counter_image .et-pb-icon",
							'border_styles' => "%%order_class%% .dipl_advanced_counter_image .et-pb-icon",
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'use_icon' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'icon_style',
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
				'image' => array(
					'label' => esc_html__( 'Image Box Shadow', 'divi-plus' ),
					'css'   => array(
						'main'      => "%%order_class%% .dipl_advanced_counter_image img",
						'important' => 'all',
					),
					'depends_on'      => array( 'use_icon' ),
					'depends_show_if' => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_style',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'advanced_counter_spacing' => array(
				'image' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_advanced_counter_image img",
							'important'  => 'all',
						),
					),
				),
				'icon' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_advanced_counter_image .et-pb-icon",
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
			'text'         => false,
			'filters'      => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'layout' => array(
				'label'           => esc_html__( 'Layout', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
					'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
					'layout3' => esc_html__( 'Layout 3', 'divi-plus' ),
				),
				'default'          => 'layout1',
				'default_on_front' => 'layout1',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Select the layout for the advanced couter.', 'divi-plus' ),
			),
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default_on_front' => esc_html__( 'Your Title Goes Here', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can enter the title for counter.', 'divi-plus' ),
			),
			'number' => array(
				'label'           => esc_html__( 'Counter Number', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => esc_html__( '90+', 'divi-plus' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter the number which will be animated on counter.', 'divi-plus' ),
			),
			'content' => array(
				'label'           => esc_html__( 'Description', 'divi-plus' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can define the content which will appear in the counter.', 'divi-plus' ),
			),
			'use_icon' => array(
				'label'           => esc_html__( 'Use Icon', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here you can choose whether or not use the icon instead of image.', 'divi-plus' ),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'show_if'            => array( 'use_icon' => 'off' ),
				'toggle_slug'        => 'display',
				'description'        => esc_html__( 'Here you can upload the image for to display with the counter.', 'divi-plus' ),
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image ALT Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '',
				'show_if'         => array( 'use_icon' => 'off' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Here you can enter the ALT text for counter image.', 'divi-plus' ),
			),
			'icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'show_if'            => array( 'use_icon' => 'on' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Choose an icon to display with the counter.', 'divi-plus' ),
			),
			'enable_image_height' => array(
				'label'           => esc_html__( 'Enable Image Custom Height', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'show_if'         => array( 'use_icon' => 'off' ),
				'default'         => 'off',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'image_style',
				'description'     => esc_html__( 'Choose whether or not to enable the custom height of image.', 'divi-plus' ),
			),
			'image_height' => array(
				'label'           => esc_html__( 'Image Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '700',
					'step' => '1',
				),
				'default'         => '90px',
				'default_unit'    => 'px',
				'allowed_units'   => array( '%', 'em', 'rem', 'px' ),
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'height' ),
				'mobile_options'  => true,
				'show_if'         => array(
					'use_icon'            => 'off',
					'enable_image_height' => 'on'
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'image_style',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease height of the image.', 'divi-plus' ),
			),
			'image_custom_padding' => array(
				'label'            => esc_html__( 'Image Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'show_if'          => array(
					'use_icon' => 'off',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'image_style',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'icon_custom_padding' => array(
				'label'            => esc_html__( 'Icon Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'show_if'          => array(
					'use_icon' => 'on',
				),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon_style',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'icon_fontsize' => array(
				'label'           => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '250',
					'step' => '1',
				),
				'default'         => '70px',
				'mobile_options'  => true,
				'show_if'         => array(
					'use_icon'  => 'on',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'icon_style',
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'          => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => false,
				'show_if'        => array(
					'use_icon'   => 'on',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_style',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'icon_bg_color' => array(
				'label'          => esc_html__( 'Icon Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => false,
				'show_if'        => array(
					'use_icon'   => 'on',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_style',
				'description'    => esc_html__( 'Here you can define a custom background color for your icon.', 'divi-plus' ),
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

		$multi_view          = et_pb_multi_view_options( $this );

		$layout              = sanitize_text_field( $this->props['layout'] ) ?? 'layout1';
		$use_icon            = sanitize_text_field( $this->props['use_icon'] ) ?? 'off';
		$title_level         = et_pb_process_header_level( $this->props['title_level'], 'h3' );
		$title_level         = esc_html( $title_level );

		$enable_image_height = sanitize_text_field( $this->props['enable_image_height'] ) ?? 'off';
		$image_height        = sanitize_text_field( $this->props['image_height'] );

		// Load style and script files.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-advanced-counter-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AdvancedCounter/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'dipl-advanced-counter-script', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/AdvancedCounter/dipl-advanced-counter.min.js", array('jquery'), '1.0.0', true );

		// Counter Image.
		$image_icon = '';
		if ( 'on' === $use_icon ) {
			$image_icon = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array(
					'class' => 'et-pb-icon',
				),
				'required' => 'icon',
			) );
		} else {
			$image_icon = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src' => '{{image}}',
					'alt' => '{{image_alt}}',
				),
				'required' => 'image',
			) );
		}

		// Counter number.
		$number = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '00',
			'attrs'    => array(
				'class'       => 'dipl_advanced_counter_number',
				'data-target' => '{{number}}'
			),
		) );

		// Title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_advanced_counter_title',
			),
			'required' => 'title',
		) );
		// Render Content.
		$content = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{content}}',
			'attrs'    => array(
				'class' => 'dipl_advanced_counter_description',
			),
			'required' => 'content',
		) );

		// Get html by layout.
		$counter_output = '';
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
		}

		// Final rendering.
		$render_output = sprintf(
			'<div class="dipl_advanced_counter_wrapper %1$s">%2$s</div>',
			esc_attr( $layout ),
			et_core_esc_previously( $counter_output )
		);

		// Use icon.
		if ( 'on' === $use_icon ) {
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && 
				method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' )
			) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => '%%order_class%% .dipl_advanced_counter_image .et-pb-icon',
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					) )
				);
			}
			$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, "{$this->main_css_element} .dipl_advanced_counter_image .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => "{$this->main_css_element} .dipl_advanced_counter_image .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_advanced_counter_image .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'base_attr_name' => 'icon_bg_color',
				'selector'       => "{$this->main_css_element} .dipl_advanced_counter_image .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_advanced_counter_image .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		} else {
			// Image height.
			if ( 'on' === $enable_image_height ) {
				$image_height = et_pb_responsive_options()->get_property_values( $this->props, 'image_height' );
				if ( ! empty( array_filter( $image_height ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $image_height, '%%order_class%% .dipl_advanced_counter_image img', 'height', $render_slug, '!important;', 'range' );
				}
			}
		}

		$fields = array( 'advanced_counter_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $render_output;
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed  $raw_value Props raw value.
	 * @param array  $args {
	 *     Context data.
	 *     @type string $context      Context param: content, attrs, visibility, classes.
	 *     @type string $name         Module options props name.
	 *     @type string $mode         Current data mode: desktop, hover, tablet, phone.
	 *     @type string $attr_key     Attribute key for attrs context data. Example: src, class, etc.
	 *     @type string $attr_sub_key Attribute sub key that availabe when passing attrs value as array such as styes. Example: padding-top, margin-botton, etc.
	 * }
	 * @param ET_Builder_Module_Helper_MultiViewOptions $multi_view Multiview object instance.
	 *
	 * @return mixed
	 */
	public function multi_view_filter_value( $raw_value, $args, $multi_view ) {
		$name = isset( $args['name'] ) ? $args['name'] : '';
		if ( $raw_value && 'icon' === $name ) {
			$processed_value = html_entity_decode( et_pb_process_font_icon( $raw_value ) );
			if ( '%%1%%' === $raw_value ) {
				$processed_value = '"';
			}
			return $processed_value;
		}
		return $raw_value;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_advanced_counter', $modules ) ) {
		new DIPL_AdvancedCounter();
	}
} else {
	new DIPL_AdvancedCounter();
}
