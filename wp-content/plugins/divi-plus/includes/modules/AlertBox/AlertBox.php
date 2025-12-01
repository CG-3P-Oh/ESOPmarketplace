<?php
class DIPL_AlertBox extends ET_Builder_Module {
	public $slug       = 'dipl_alert_box';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	/**
	 * Track if the module is currently rendering to prevent unnecessary rendering and recursion.
	 *
	 * @var bool
	 */
	protected static $rendering = false;

	public function init() {
		$this->name = esc_html__( 'DP Alert Box', 'divi-plus' );
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
					'text'         => esc_html__( 'Global Text', 'divi-plus' ),
					'alert_text' => array(
						'title'        => esc_html__( 'Alert Text', 'divi-plus' ),
						'sub_toggles'  => array(
							'title'       => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'description' => array( 'name' => esc_html__( 'Description', 'divi-plus' ) )
						),
						'tabbed_subtoggles' => true,
					),
					'icon_image'   => esc_html__( 'Icon/Image', 'divi-plus' ),
					'close_icon'   => esc_html__( 'Close Icon', 'divi-plus' ),
					'alert_button' => esc_html__( 'Alert Button', 'divi-plus' ),
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
						'default' => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'header_level' => array(
						'default' => 'h4',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_alert_box_title",
						'hover'     => "{$this->main_css_element} .dipl_alert_box_title:hover",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'alert_text',
					'sub_toggle'  => 'title',
				),
				'description' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_alert_box_description",
						'hover'     => "{$this->main_css_element} .dipl_alert_box_description:hover",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'alert_text',
					'sub_toggle'  => 'description',
				),
			),
			'text' => array(
				'use_text_orientation'  => false,
				'use_background_layout' => true,
				'options'               => array(
					'background_layout' => array(
						'default'           => 'light',
						'default_on_front'  => 'light',
						'hover'             => 'tabs',
					)
				),
			),
			'button' => array(
				'button' => array(
					'label' => esc_html__( 'Button', 'divi-plus' ),
					'css' => array(
						'main'      => "{$this->main_css_element} .et_pb_button",
						'alignment' => "{$this->main_css_element} .et_pb_button_wrapper",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .et_pb_button_wrapper",
							'padding'   => "{$this->main_css_element} .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => true,
					'box_shadow'      => true,
					'depends_on'      => array( 'show_button' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'alert_button',
				),
			),
			'borders' => array(
				'icon_image' => array(
					'label_prefix' => esc_html__( 'Icon/Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_alert_box_image_wrap",
							'border_styles' => "{$this->main_css_element} .dipl_alert_box_image_wrap",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'icon_image',
				),
				'default' => array(
					'css' => array(
						'main'      => array(
							'border_radii'  => "{$this->main_css_element}",
							'border_styles' => "{$this->main_css_element}",
						),
						'important' => 'all',
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
			'alert_box_spacing' => array(
				'icon_image' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_alert_box_image_wrap",
							'important'  => 'all',
						),
					),
				),
			),
			'margin_padding' => array(
				'custom_padding' => array(
					'default' => '15px|15px|15px|15px|on|on'
				),
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'filters'      => false,
			'link_options' => false,
			'background'   => array(
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'            => esc_html__( 'Alert Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default_on_front' => esc_html__( 'Your Title Goes Here', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can enter the title for the alert box.', 'divi-plus' ),
			),
			'description' => array(
				'label'           => esc_html__( 'Description', 'divi-plus' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input the description to be used for alert box.', 'divi-plus'),
			),
			'use_image' => array(
				'label'           => esc_html__( 'Use Image', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can choose whether or not use the image instead of icon.', 'divi-plus' ),
			),
			'icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'show_if'         => array( 'use_image' => 'off' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Choose an icon to display with the alert box.', 'divi-plus' ),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'show_if'            => array( 'use_image' => 'on' ),
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can upload the image for to display for the alert box.', 'divi-plus' ),
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image ALT Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => '',
				'show_if'         => array( 'use_image' => 'on' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter the ALT text for the alert box.', 'divi-plus' ),
			),
			'layout' => array(
				'label'           => esc_html__( 'Layout', 'divi-plus' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
					'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
				),
				'default'          => 'layout1',
				'default_on_front' => 'layout1',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Select the layout for the alert box.', 'divi-plus' ),
			),
			'show_close_button' => array(
				'label'     		=> esc_html__( 'Show Close Button', 'divi-plus' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'basic_option',
				'options'           => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'           => 'on',
				'toggle_slug'       => 'display',
				'description'       => esc_html__( 'Here you can choose whether or not show close icon.', 'divi-plus' ),
			),
			'show_button' => array(
				'label'     		=> esc_html__( 'Show Alert Button', 'divi-plus' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'basic_option',
				'options'           => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'affects' 			=> array( 'custom_button' ),
				'default'           => 'off',
				'toggle_slug'       => 'display',
				'description'       => esc_html__( 'Here you can choose whether or not use the button.', 'divi-plus' ),
			),
			'button_text' => array(
				'label'    			=> esc_html__( 'Button Text', 'divi-plus' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'show_if'           => array(
					'show_button' => 'on',
				),
				'default'			=> esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front'	=> esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'       => 'display',
				'description'       => esc_html__( 'Here you can input the text to be used for the Read More Button.', 'divi-plus' ),
			),
			'button_url' => array(
				'label'           	=> esc_html__( 'Button Link URL', 'divi-plus' ),
				'type'            	=> 'text',
				'option_category' 	=> 'basic_option',
				'dynamic_content' 	=> 'url',
				'show_if'           => array(
					'show_button' => 'on',
				),
				'toggle_slug'     	=> 'display',
				'description'     	=> esc_html__( 'Here you can input the destination URL for the button to open when clicked.', 'divi-plus' ),
			),
			'button_url_new_window' => array(
				'label'            	=> esc_html__( 'Button Link Target', 'divi-plus' ),
				'type'             	=> 'select',
				'option_category'  	=> 'configuration',
				'options'          	=> array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'show_if'           => array(
					'show_button' => 'on',
				),
				'toggle_slug'      	=> 'display',
				'description'      	=> esc_html__( 'Here you can choose whether or not your link opens in a new window', 'divi-plus' ),
			),
			'alert_valign' =>  array(
				'label'            => esc_html__( 'Vertical Align', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'flex-start' => esc_html__( 'Top', 'divi-plus' ),
					'center'     => esc_html__( 'Center', 'divi-plus' ),
					'flex-end'   => esc_html__( 'Bottom', 'divi-plus' ),
				),
				'default'          => 'center',
				'default_on_front' => 'center',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'text',
				'description'      => esc_html__( 'Here you can choose the vertical alignment of alert box.', 'divi-plus' ),
			),
			'icon_image_custom_padding' => array(
				'label'            => esc_html__( 'Icon/Image Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'		   => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'icon_image',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'image_icon_bg_color' => array(
				'label'          => esc_html__( 'Icon/Image Background Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => true,
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_image',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'icon_fontsize' => array(
				'label'           => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'default'         => '34px',
				'mobile_options'  => true,
				'show_if'         => array(
					'use_image'   => 'off',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'icon_image',
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'icon_color' => array(
				'label'          => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'mobile_options' => true,
				'show_if'        => array(
					'use_image'  => 'off',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'icon_image',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
			),
			'image_width' => array(
				'label'           => esc_html__( 'Image Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '700',
					'step' => '1',
				),
				'default'         => '50px',
				'default_unit'    => 'px',
				'allowed_units'   => array( '%', 'em', 'rem', 'px' ),
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'width' ),
				'mobile_options'  => true,
				'show_if'         => array(
					'use_image' => 'on',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'icon_image',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the image.', 'divi-plus' ),
			),
			'close_icon_fontsize' => array(
				'label'           => esc_html__( 'Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'font_option',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '120',
					'step' => '1',
				),
				'default'         => '28px',
				'mobile_options'  => true,
				'show_if'         => array(
					'show_close_button' => 'on',
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'close_icon',
				'description'     => esc_html__( 'Control the size of the icon by increasing or decreasing the font size.', 'divi-plus' ),
			),
			'close_icon_color' => array(
				'label'          => esc_html__( 'Icon Color', 'divi-plus' ),
				'type'           => 'color-alpha',
				'hover'          => 'tabs',
				'default'        => '#ff0000',
				'mobile_options' => true,
				'show_if'        => array(
					'show_close_button' => 'on',
				),
				'tab_slug'       => 'advanced',
				'toggle_slug'    => 'close_icon',
				'description'    => esc_html__( 'Here you can define a custom color for your icon.', 'divi-plus' ),
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

		$multi_view        = et_pb_multi_view_options( $this );

		$layout            = sanitize_text_field( $this->props['layout'] ) ?? 'layout1';
		$use_image         = sanitize_text_field( $this->props['use_image'] ) ?? 'off';
		$show_close_button = sanitize_text_field( $this->props['show_close_button'] ) ?? 'on';

		$title_level       = et_pb_process_header_level( $this->props['title_level'], 'h3' );
		$title_level       = esc_html( $title_level );

		// Load style and script files.
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-alert-box-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AlertBox/' . $file . '.min.css', array(), '1.0.0' );

		// Alert Box Image.
		$image_icon = '';
		if ( 'on' === $use_image ) {
			$image_icon = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src' => '{{image}}',
					'alt' => '{{image_alt}}',
				),
				'required' => 'image',
			) );
		} else {
			$image_icon = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array(
					'class' => 'et-pb-icon',
				),
				'required' => 'icon',
			) );
		}

		// Title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_alert_box_title',
			),
			'required' => 'title',
		) );
		// Render Description.
		$description = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{description}}',
			'attrs'    => array(
				'class' => 'dipl_alert_box_description',
			),
			'required' => 'description',
		) );
		// Render Button.
		$button = '';
		if ( 'off' !== $this->props['show_button'] && '' !== $this->props['button_url'] ) {
			$button = $this->render_button( array(
				'button_text'         => esc_html( $this->props['button_text'] ),
				'button_text_escaped' => true,
				'has_wrapper'      	  => true,
				'button_url'          => esc_url( $this->props['button_url'] ),
				'url_new_window'      => esc_attr( $this->props['button_url_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
				'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
			) );
		}

		// Close button.
		$close_button = '';
		if ( 'on' === $show_close_button ) {
			$close_icon   = 'M||divi||400';
			$close_button = sprintf(
				'<a href="#" class="dipl-alert-box-close-btn">
					<span class="et-pb-icon">%1$s</span>
				</a>',
				et_pb_process_font_icon( $close_icon )
			);
		}

		// Alert box.
		$alert_output = '';
		if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/alert-box/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include get_stylesheet_directory() . '/divi-plus/layouts/alert-box/' . sanitize_file_name( $layout ) . '.php';
		} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
		}

		// Final output.
		$render_output = sprintf(
			'<div class="dipl_alert_box_wrapper %1$s">%2$s</div>',
			esc_attr( $layout ),
			et_core_esc_previously( $alert_output )
		);

		// Add close icon script to hide alert box.
		if ( 'on' === $show_close_button ) {
			$render_output .= $this->dipl_render_alert_box_script();
		}

		// Item content.
		$alert_valign = et_pb_responsive_options()->get_property_values( $this->props, 'alert_valign' );
		if ( ! empty( array_filter( $alert_valign ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $alert_valign, "%%order_class%% .dipl_alert_box_inner", 'align-items', $render_slug, '!important', 'type' );
		}

		// Icon/Image style.
		if ( 'on' === $use_image ) {
			$image_width = et_pb_responsive_options()->get_property_values( $this->props, 'image_width' );
			if ( ! empty( array_filter( $image_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $image_width, "%%order_class%% .dipl_alert_box_image_wrap.dipl-used-image img", 'width', $render_slug, '!important', 'range' );
			}
		} else {
			$this->generate_styles( array(
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'icon',
				'important'      => true,
				'selector'       => '%%order_class%% .dipl_alert_box_image_wrap .et-pb-icon',
				'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
			) );
			$icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $icon_fontsize, "{$this->main_css_element} .dipl_alert_box_image_wrap .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'icon_color',
				'selector'       => "{$this->main_css_element} .dipl_alert_box_image_wrap .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl_alert_box_image_wrap .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}
		// Image/icon background color.
		$this->generate_styles( array(
			'base_attr_name' => 'image_icon_bg_color',
			'selector'       => "{$this->main_css_element} .dipl_alert_box_image_wrap",
			'hover_selector' => "{$this->main_css_element} .dipl_alert_box_image_wrap:hover",
			'important'      => true,
			'css_property'   => 'background-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		// Close icon styling.
		if ( 'on' === $show_close_button ) {
			$close_icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'close_icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $close_icon_fontsize, "{$this->main_css_element} .dipl-alert-box-close-btn .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'close_icon_color',
				'selector'       => "{$this->main_css_element} .dipl-alert-box-close-btn .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl-alert-box-close-btn .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		$fields = array( 'alert_box_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		// Add module classes.
		$background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class( $this->props );
		$this->add_classname( array(
			$this->get_text_orientation_classname(),
			$background_layout_class_names[0],
		) );

		self::$rendering = false;
		return $render_output;
	}

	/**
	 * This function dynamically creates script 
	 * parameters according to the user settings.
	 *
	 * @return string
	 */
	public function dipl_render_alert_box_script() {

		// Get order class.
		$order_class = $this->get_module_order_class( 'dipl_alert_box' );

		$script  = '<script type="text/javascript">';
		$script .= 'jQuery( function($) {';
			$script .= '$( ".' . esc_attr( $order_class ) . '" ).on( \'click\', \'.dipl-alert-box-close-btn\', function(e) {
				e.preventDefault();
				$( ".' . esc_attr( $order_class ) . '" ).fadeOut();
			} );';
		$script .= '} );</script>';

		return $script;
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
	if ( in_array( 'dipl_alert_box', $modules ) ) {
		new DIPL_AlertBox();
	}
} else {
	new DIPL_AlertBox();
}
