<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2024 Elicus Technologies Private Limited
 * @version     1.10.0
 */
class DIPL_HorizontalScrollingCardItem extends ET_Builder_Module {
	public $slug       = 'dipl_horizontal_scrolling_card_item';
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
		$this->name                        = esc_html__( 'DP Horizontal Scrolling Card Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Horizontal Scrolling Card Item', 'divi-plus' );
		$this->child_title_var             = 'title';
		$this->main_css_element            = '.dipl_horizontal_scrolling_card %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'button'       => esc_html__( 'Button', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'card_text' => array(
						'title'             => esc_html__( 'Card Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'       => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'description' => array( 'name' => esc_html__( 'Description', 'divi-plus' ) )
						),
					),
					'tag' => esc_html__( 'Tag', 'divi-plus' ),
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
						'default'        => '22px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '150',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'header_level' => array(
						'default'  => 'h4',
					),
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'card_text',
					'sub_toggle'  => 'title',
				),
				'description' => array(
					'label'     => esc_html__( 'Description', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_description",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'card_text',
					'sub_toggle'  => 'description',
				),
				'tag' => array(
					'label'     => esc_html__( 'Tag', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'       => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_tag_wrapper .dipl_horizontal_scrolling_card_tag",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'tag',
				),
			),
			'button' => array(
			    'button' => array(
				    'label' => esc_html__( 'Button', 'divi-plus' ),
				    'css' => array(
						'main'      => "%%order_class%% .et_pb_button_wrapper .et_pb_button",
						'alignment' => "%%order_class%% .et_pb_button_wrapper",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "%%order_class%% .et_pb_button_wrapper",
							'padding'   => "%%order_class%% .et_pb_button_wrapper .et_pb_button",
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
							'border_styles' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_wrapper",
							'border_radii'  => "{$this->main_css_element} .dipl_horizontal_scrolling_card_wrapper",
						),
					),
				)
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_wrapper",
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
			'background' => array(
				'css' => array(
					'main'      => "{$this->main_css_element} .dipl_horizontal_scrolling_card_wrapper",
					'important' => 'all',
				),
				'use_background_video' => false,
			),
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'default'          => esc_html__( 'Your title goes here.', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Your title goes here.', 'divi-plus' ),
				'description'      => esc_html__( 'This is the title displayed for the card.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'description' => array(
				'label'            => esc_html__( 'Description', 'divi-plus' ),
				'type'             => 'textarea',
				'option_category'  => 'basic_option',
				'description'      => esc_html__( 'This is the description displayed for the card.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'card_image' => array(
				'label'              => esc_html__( 'Card Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'affects'            => array( 'alt' ),
				'dynamic_content'  	 => 'image',
				'description'        => esc_html__( 'Here you can upload the image to set on the card.', 'divi-plus' ),
				'toggle_slug'        => 'main_content',
			),
			'card_image_alt' => array(
				'label'           => esc_html__( 'Image Alternative Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'on',
				'depends_on'      => array( 'card_image' ),
				'description'     => esc_html__( 'This defines the HTML ALT text. A short description of your image can be placed here.', 'divi-plus' ),
				'toggle_slug'     => 'main_content',
				'dynamic_content' => 'text',
			),
			'tag' => array(
				'label'           => esc_html__( 'Tag', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => '',
				'description'     => esc_html__( 'Enter the tag to display on the card.', 'divi-plus' ),
				'toggle_slug'     => 'main_content',
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
				'label'            => esc_html__( 'Button Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'show_if'          => array( 'show_button' => 'on' ),
				'default'          => esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'      => 'button',
				'description'      => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
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
			'tag_bg_color' => array(
				'label'        	   => esc_html__( 'Tag Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '',
				'default_on_front' => '',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'tag',
				'description'      => esc_html__( 'Here you can select the backround color for the image overlay.', 'divi-plus' ),
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

		global $dp_sticky_card_parent_attrs;

		$layout      = $dp_sticky_card_parent_attrs['layout'] ?? 'layout1';

		$multi_view  = et_pb_multi_view_options( $this );

		$title_level = et_pb_process_header_level( $this->props['title_level'], 'h4' );
		$show_button = esc_attr( $this->props['show_button'] );
		$button_url  = esc_url( $this->props['button_url'] );

		// Render image.
		$image = $multi_view->render_element( array(
			'tag'      => 'img',
			'attrs'    => array(
				'src'   => '{{card_image}}',
				'alt'   => '{{card_image_alt}}',
				'class' => 'et_pb_image dipl_horizontal_scrolling_card_image'
			),
			'required' => 'card_image',
		) );

		// Render tag.
		$tag = $multi_view->render_element( array(
			'tag'      => 'span',
			'content'  => '{{tag}}',
			'attrs'    => array(
				'class' => 'dipl_horizontal_scrolling_card_tag',
			),
			'required' => 'tag',
		) );
		if ( ! empty( $tag ) ) {
			$tag = sprintf(
				'<div class="dipl_horizontal_scrolling_card_tag_wrapper">%1$s</div>',
				et_core_esc_previously( $tag )
			);
		}

		// Content elements.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array(
				'class' => 'dipl_horizontal_scrolling_card_title'
			),
			'required' => 'title',
		) );
		$description = $multi_view->render_element( array(
			'tag'        => 'div',
			'content'    => '{{description}}',
			'attrs'      => array(
				'class' => 'dipl_horizontal_scrolling_card_description',
			),
			'required' => 'description',
		) );
		$render_button = '';
		if ( 'on' === $show_button && ! empty( $button_url ) ) {
			$render_button = $this->render_button( array(
				'button_text'         => esc_attr( $this->props['button_text'] ),
				'button_text_escaped' => false,
				'has_wrapper'      	  => false,
				'button_url'          => esc_url( $button_url ),
				'url_new_window'      => esc_attr( $this->props['button_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
				'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
			) );
			$render_button = sprintf(
				'<div class="et_pb_button_wrapper">%1$s</div>',
				et_core_esc_previously( $render_button )
			);
		}

		// Final output.
		$render_output = sprintf( '<div class="dipl_horizontal_scrolling_card_wrapper dipl_horizontal_scrolling_card_item_%1$s">', esc_attr( $layout ) );
		if ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
			include( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' );
		}
		$render_output .= '</div>';

		// Add background image for layout 2.
		if ( 'layout2' === $layout && ! empty( $this->props['card_image'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '.dipl_horizontal_scrolling_card %%order_class%% .dipl_horizontal_scrolling_card_item_layout2 .dipl_horizontal_scrolling_card_inner',
				'declaration' => sprintf( 'background-image: url( %1$s );', esc_url( $this->props['card_image'] ) ),
			) );
		}

		// Tag style.
		$this->generate_styles( array(
			'base_attr_name' => 'tag_bg_color',
			'selector'       => "{$this->main_css_element} .dipl_horizontal_scrolling_card_wrapper .dipl_horizontal_scrolling_card_tag",
			'hover_selector' => "{$this->main_css_element} .dipl_horizontal_scrolling_card_wrapper:hover .dipl_horizontal_scrolling_card_tag",
			'important'      => true,
			'css_property'   => 'background-color',
			'render_slug'    => $render_slug,
			'type'           => 'color',
		) );

		if ( ! empty( $this->props['button_icon'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => "{$this->main_css_element} .et_pb_button_wrapper .et_pb_button::after, {$this->main_css_element} .et_pb_button_wrapper .et_pb_button::before",
				'declaration' => 'content: attr(data-icon) !important;',
			) );
		}

		self::$rendering = false;
		return $render_output;
	}

	protected function _render_module_wrapper( $output = '', $render_slug = '' ) {
		$wrapper_settings    = $this->get_wrapper_settings( $render_slug );
		$slug                = $render_slug;
		$outer_wrapper_attrs = $wrapper_settings['attrs'];
		$inner_wrapper_attrs = $wrapper_settings['inner_attrs'];

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
				%2$s
				%3$s
				%4$s
				%5$s
				%6$s
			</div>',
			et_html_attrs( $outer_wrapper_attrs ),
			$wrapper_settings['parallax_background'],
			$wrapper_settings['video_background'],
			et_()->array_get( $wrapper_settings, 'video_background_tablet', '' ),
			et_()->array_get( $wrapper_settings, 'video_background_phone', '' ),
			$output
		);
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_horizontal_scrolling_card', $modules ) ) {
		new DIPL_HorizontalScrollingCardItem();
	}
} else {
	new DIPL_HorizontalScrollingCardItem();
}
