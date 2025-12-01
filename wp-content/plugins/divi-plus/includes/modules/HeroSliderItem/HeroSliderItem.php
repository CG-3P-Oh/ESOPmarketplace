<?php
/**
 * @author    Elicus <hello@elicus.com>
 * @link      https://www.elicus.com/
 * @copyright 2024 Elicus Technologies Private Limited
 * @version   1.10.0
 */
class DIPL_HeroSliderItem extends ET_Builder_Module {
	public $slug       = 'dipl_hero_slider_item';
	public $type       = 'child';
	public $vb_support = 'on';

	/**
	 * Track if the module is currently rendering
	 * to prevent unnecessary rendering and recursion.
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
		$this->name                        = esc_html__( 'DP Hero Slide', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Hero Slide', 'divi-plus' );
		$this->child_title_var             = 'title';
		$this->main_css_element            = '.dipl_hero_slider %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'image_video'  => esc_html__( 'Image/Video', 'divi-plus' ),
					'buttons'      => array(
						'title' => esc_html__( 'Buttons', 'divi-plus' ),
						'sub_toggles'   => array(
							'btnone' => array( 'name' => esc_html__( 'Button One', 'divi-plus' ) ),
							'btntwo' => array( 'name' => esc_html__( 'Button Two', 'divi-plus' ) )
						),
						'tabbed_subtoggles' => true,
					)
				)
			),
			'advanced' => array(
				'toggles' => array(
					'slide_text' => array(
						'title'             => esc_html__( 'Title & Subtitle Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'    => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'subtitle' => array( 'name' => esc_html__( 'Subtitle', 'divi-plus' ) )
						),
					),
					'content_text' => array(
						'title'             => esc_html__( 'Content Text', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'bb_icons_support'  => true,
						'sub_toggles'       => array(
							'p'     => array(
								'name' => 'P',
								'icon' => 'text-left',
							),
							'a'     => array(
								'name' => 'A',
								'icon' => 'text-link',
							),
							'ul'    => array(
								'name' => 'UL',
								'icon' => 'list',
							),
							'ol'    => array(
								'name' => 'OL',
								'icon' => 'numbered-list',
							),
							'quote' => array(
								'name' => 'QUOTE',
								'icon' => 'text-quote',
							),
						),
					),
					'content_box' => esc_html__( 'Content Box', 'divi-plus' ),
					'button_one'  => esc_html__( 'Button One', 'divi-plus' ),
					'button_two'  => esc_html__( 'Button Two', 'divi-plus' ),
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
						'default'        => '24px',
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
						'main'     => "{$this->main_css_element} .dipl-hero-slide-title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'slide_text',
					'sub_toggle'  => 'title',
				),
				'subtitle' => array(
					'label'     => esc_html__( 'Subtitle', 'divi-plus' ),
					'font_size' => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "{$this->main_css_element} .dipl-hero-slide-subtitle",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'slide_text',
					'sub_toggle'  => 'subtitle',
				),
				'content_text' => array(
					'label'     => esc_html__( 'Content Text', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main' => "{$this->main_css_element} .dipl-hero-slide-content, {$this->main_css_element} .dipl-hero-slide-content p",
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'p',
				),
				'content_text_link' => array(
					'label'     => esc_html__( 'Link', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl-hero-slide-content a",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'a',
				),
				'content_text_ul' => array(
					'label'     => esc_html__( 'Unordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl-hero-slide-content ul li",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'ul',
				),
				'content_text_ol' => array(
					'label'     => esc_html__( 'Ordered List', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl-hero-slide-content ol li",
						'important' => 'all',
					),
					'toggle_slug'    => 'content_text',
					'sub_toggle'     => 'ol',
				),
				'content_text_quote' => array(
					'label'          => esc_html__( 'Blockquote', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl-hero-slide-content blockquote",
						'important' => 'all',
					),
					'toggle_slug' => 'content_text',
					'sub_toggle'  => 'quote',
				),
			),
			'button' => array(
			    'button_one' => array(
				    'label' => esc_html__( 'Button One', 'divi-plus' ),
				    'css' => array(
						'main'      => "{$this->main_css_element} .dipl_hero_slide_btn_one",
						'alignment' => "{$this->main_css_element} .dipl-hero-slide-btn-wrap",
						'important' => 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl_hero_slide_btn_one",
							'padding'   => "{$this->main_css_element} .dipl_hero_slide_btn_one",
							'important' => 'all',
						),
					),
					'border_width' => array(
						'default' => '2px',
					),
					'text_size' => array(
						'default'          => '16px',
						'default_on_front' => '16px',
					),
					'use_alignment'   	=> true,
					'box_shadow'      	=> false,
				    'depends_on'        => array( 'show_button_one' ),
		            'depends_show_if'   => 'on',
					'tab_slug'          => 'advanced',
				    'toggle_slug'       => 'button_one',
			    ),
				'button_two' => array(
				    'label' => esc_html__( 'Button Two', 'divi-plus' ),
				    'css' => array(
						'main'      => "{$this->main_css_element} .dipl_hero_slide_btn_two",
						'important' => 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl_hero_slide_btn_two",
							'padding'   => "{$this->main_css_element} .dipl_hero_slide_btn_two",
							'important' => 'all',
						),
					),
					'border_width' => array(
						'default' => '2px',
					),
					'text_size' => array(
						'default'          => '16px',
						'default_on_front' => '16px',
					),
					'use_alignment'   	=> false,
					'box_shadow'      	=> false,
				    'depends_on'        => array( 'show_button_two' ),
		            'depends_show_if'   => 'on',
					'tab_slug'          => 'advanced',
				    'toggle_slug'       => 'button_two',
			    ),
			),
			'borders' => array(
				'content_box' => array(
					'label_prefix' => esc_html__( 'Content Box', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl-hero-slide-content-inner",
							'border_styles' => "{$this->main_css_element} .dipl-hero-slide-content-inner",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'content_box',
				),
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
				'content_box' => array(
					'label'       => esc_html__( 'Content Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "{$this->main_css_element} .dipl-hero-slide-content-inner",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'content_box',
				),
				'default' => array(
					'css' => array(
						'main' => "{$this->main_css_element}",
					),
				)
			),
			'margin_padding' => array(
				'custom_padding' => array(
					'default_on_front' => '30px|30px|30px|30px|on|on',
				),
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'max_width'  => false,
			'height'     => false,
			'text'       => false,
			'filters'    => false,
			'background' => array(
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
				'default'          => esc_html__( 'Your title goes here', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Your title goes here', 'divi-plus' ),
				'description'      => esc_html__( 'This is the title displayed for the slide.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'subtitle' => array(
				'label'            => esc_html__( 'Subtitle', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'default'          => '',
				'description'      => esc_html__( 'This is the subtitle displayed for the slide.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'content' => array(
				'label'            => esc_html__( 'Content', 'divi-plus' ),
				'type'             => 'tiny_mce',
				'option_category'  => 'basic_option',
				'description'      => esc_html__( 'This is the subtitle displayed for the slide.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'use_video' => array(
				'label'           => esc_html__( 'Use Video', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' )
				),
				'default'         => 'off',
				'toggle_slug'     => 'image_video',
				'description'     => esc_html__( 'Here you can choose whether use image or video for the hero slide.', 'divi-plus' ),
				'computed_affects' => array(
					'__computed_video_html',
				),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'	 => 'image',
				'show_if'            => array(
					'use_video' => 'off',
				),
				'toggle_slug'        => 'image_video',
				'description'        => esc_html__( 'Upload an image to display at the hero slide.', 'divi-plus' ),
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image Alt Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'use_video' => 'off',
				),
				'toggle_slug'     => 'image_video',
				'description'     => esc_html__( 'Here you can input the text to be used for the image as HTML ALT text.', 'divi-plus' ),
			),
			'video_src' => array(
				'label'              => esc_html__( 'Video MP4 File Or YouTube/Vimeo URL', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'show_if'            => array( 'use_video' => 'on' ),
				'upload_button_text' => esc_attr__( 'Upload a video', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose a Video MP4 File', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Video', 'divi-plus' ),
				'description'        => esc_html__( 'Upload your desired video in .MP4 format, or type in the URL to the video you would like to display', 'divi-plus' ),
				'toggle_slug'        => 'image_video',
				'computed_affects' => array(
					'__computed_video_html',
				),
			),
			'show_button_one' => array(
				'label'     	  => esc_html__( 'Show Button One', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'buttons',
				'sub_toggle'	  => 'btnone',
				'description'     => esc_html__( 'Here you can choose whether or not show the button one.', 'divi-plus' ),
			),
			'button_one_text' => array(
				'label'    			=> esc_html__( 'Button One Text', 'divi-plus' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'show_if'           => array( 'show_button_one' => 'on' ),
				'default'			=> esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front'	=> esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'       => 'buttons',
				'sub_toggle'	    => 'btnone',
				'description'       => esc_html__( 'Here you can input the text to be used for the button one.', 'divi-plus' ),
			),
			'button_one_url' => array(
				'label'           => esc_html__( 'Button One Link URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array( 'show_button_one' => 'on' ),
				'dynamic_content' => 'url',
				'toggle_slug'     => 'buttons',
				'sub_toggle'	  => 'btnone',
				'description'  	  => esc_html__( 'Here you can input the destination URL for the button one to open when clicked.', 'divi-plus' ),
			),
			'button_one_new_window' => array(
				'label'        	  => esc_html__( 'Button One Link Target', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'show_if'         => array( 'show_button_one' => 'on' ),
				'options'         => array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'toggle_slug'     => 'buttons',
				'sub_toggle'	  => 'btnone',
				'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button one.', 'divi-plus' ),
			),
			'show_button_two' => array(
				'label'     	  => esc_html__( 'Show Button Two', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'off',
				'toggle_slug'     => 'buttons',
				'sub_toggle'	  => 'btntwo',
				'description'     => esc_html__( 'Here you can choose whether or not show the button two.', 'divi-plus' ),
			),
			'button_two_text' => array(
				'label'    			=> esc_html__( 'Button Two Text', 'divi-plus' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'show_if'           => array( 'show_button_two' => 'on' ),
				'default'			=> esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front'	=> esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'       => 'buttons',
				'sub_toggle'	    => 'btntwo',
				'description'       => esc_html__( 'Here you can input the text to be used for the button two.', 'divi-plus' ),
			),
			'button_two_url' => array(
				'label'           => esc_html__( 'Button Two Link URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array( 'show_button_two' => 'on' ),
				'dynamic_content' => 'url',
				'toggle_slug'     => 'buttons',
				'sub_toggle'	  => 'btntwo',
				'description'  	  => esc_html__( 'Here you can input the destination URL for the button two to open when clicked.', 'divi-plus' ),
			),
			'button_two_new_window' => array(
				'label'        	  => esc_html__( 'Button Two Link Target', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'show_if'         => array( 'show_button_two' => 'on' ),
				'options'         => array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'toggle_slug'     => 'buttons',
				'sub_toggle'	  => 'btntwo',
				'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button two.', 'divi-plus' ),
			),
			'content_box_bg_color' => array(
				'label'        	   => esc_html__( 'Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'hover'            => 'tab',
				'default'      	   => '',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'content_box',
				'description'      => esc_html__( 'Here you can select background color for the content box.', 'divi-plus' ),
			),
			'__computed_video_html' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_HeroSliderItem', 'get_computed_video_html' ),
				'computed_depends_on' => array(
					'use_video',
					'video_src'
				)
			)
		);
	}

	public static function get_computed_video_html( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'use_video' => 'off',
			'video_src' => '',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		$video_html = '';
		if ( 'on' === $use_video && ! empty( $video_src ) ) {

			// Get provider data.
			$oembed			= _wp_oembed_get_object();
			$provider		= $oembed->get_data( $video_src );
			$provider_name	= isset( $provider->provider_name ) ? $provider->provider_name : 'custom-upload';

			$video_html = self::get_video( array(
				'src' => $video_src,
			) );

			$video_html = sprintf(
				'<div class="dipl-hero-slide-media-inner %2$s">
					<div class="fluid-width-video-wrapper">%1$s</div>
				</div>',
				et_core_esc_previously( $video_html ),
				esc_attr( strtolower( $provider_name ) )
			);
		}

		self::$rendering = false;
		return $video_html;
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$multi_view  = et_pb_multi_view_options( $this );
		$title_level = et_pb_process_header_level( $this->props['title_level'], 'h2' );

		// Render title.
		$title = $multi_view->render_element( array(
			'tag'      => $title_level,
			'content'  => '{{title}}',
			'attrs'    => array( 'class' => 'dipl-hero-slide-title' ),
			'required' => 'title',
		) );
		// Render subtitle.
		$subtitle = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{subtitle}}',
			'attrs'    => array( 'class' => 'dipl-hero-slide-subtitle' ),
			'required' => 'subtitle',
		) );
		// Render content.
		$content = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => $content,
			'attrs'    => array( 'class' => 'dipl-hero-slide-content' ),
			'required' => 'content',
		) );

		// Button one.
		$buttons = '';
		$show_button_one = esc_attr( $this->props['show_button_one'] );
		$button_one_url  = esc_url( $this->props['button_one_url'] );
		if ( 'on' === $show_button_one && ! empty( $button_one_url ) ) {
			$buttons .= $this->render_button( array(
				'button_text'         => esc_attr( $this->props['button_one_text'] ),
				'button_text_escaped' => true,
				'button_classname'    => array( 'dipl_hero_slide_btn_one' ),
				'has_wrapper'      	  => false,
				'button_url'          => esc_url( $button_one_url ),
				'url_new_window'      => esc_attr( $this->props['button_one_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button_one'] ) ? esc_attr( $this->props['custom_button_one'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_one_icon'] ) ? $this->props['button_one_icon'] : '',
				'button_rel'          => isset( $this->props['button_one_rel'] ) ? esc_attr( $this->props['button_one_rel'] ) : '',
			) );
			if ( ! empty( $this->props['button_one_icon'] ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .dipl_hero_slide_btn_one::after',
					'declaration' => 'content: attr(data-icon);',
				) );
			}
		}
		$show_button_two = esc_attr( $this->props['show_button_two'] );
		$button_two_url  = esc_url( $this->props['button_two_url'] );
		if ( 'on' === $show_button_two && ! empty( $button_two_url ) ) {
			$buttons .= $this->render_button( array(
				'button_text'         => esc_attr( $this->props['button_two_text'] ),
				'button_text_escaped' => true,
				'button_classname'    => array( 'dipl_hero_slide_btn_two' ),
				'has_wrapper'      	  => false,
				'button_url'          => esc_url( $button_two_url ),
				'url_new_window'      => esc_attr( $this->props['button_two_new_window'] ),
				'button_custom'       => isset( $this->props['custom_button_two'] ) ? esc_attr( $this->props['custom_button_two'] ) : 'off',
				'custom_icon'         => isset( $this->props['button_two_icon'] ) ? $this->props['button_two_icon'] : '',
				'button_rel'          => isset( $this->props['button_two_rel'] ) ? esc_attr( $this->props['button_two_rel'] ) : '',
			) );
			if ( ! empty( $this->props['button_two_icon'] ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .dipl_hero_slide_btn_two::after',
					'declaration' => 'content: attr(data-icon);',
				) );
			}
		}
		$button_wrap = '';
		if ( ! empty( $buttons ) ) {
			$button_wrap = sprintf(
				'<div class="et_pb_button_wrapper dipl-hero-slide-btn-wrap">%1$s</div>',
				et_core_esc_previously( $buttons )
			);
		}

		$content_wrap = '';
		if ( ! empty( $title ) || ! empty( $subtitle ) || ! empty( $content ) || ! empty( $button_wrap ) ) {
			$content_wrap = sprintf(
				'<div class="dipl-hero-slide-content-wrap">
					<div class="dipl-hero-slide-content-inner">
						%1$s %2$s %3$s %4$s
					</div>
				</div>',
				et_core_esc_previously( $title ),
				et_core_esc_previously( $subtitle ),
				et_core_esc_previously( $content ),
				et_core_esc_previously( $button_wrap )
			);
		}

		// Use video or image.
		$use_video   = $this->props['use_video'] ?? 'off';
		$image_video = '';
		if ( 'on' === $use_video ) {
			if ( ! empty( $this->props['video_src'] ) ) {

				// Get provider data.
				$oembed			= _wp_oembed_get_object();
				$provider		= $oembed->get_data( $this->props['video_src'] );
				$provider_name	= isset( $provider->provider_name ) ? $provider->provider_name : 'custom-upload';

				$video_srcs = self::get_video( array(
					'src' => $this->props['video_src'],
				) );
				$image_video = $multi_view->render_element( array(
					'tag'     => 'div',
					'content' => $video_srcs,
					'attrs'   => array( 'class' => 'dipl-hero-slide-media-inner ' . esc_attr( strtolower( $provider_name ) ) ),
				) );
			}
		} else {
			$image_video = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{image}}',
					'alt'   => '{{image_alt}}',
					'class' => 'dipl-hero-slide-image',
				),
				'required' => 'image',
			) );
			if ( ! empty( $image_video ) ) {
				$image_video = sprintf(
					'<div class="dipl-hero-slide-media-inner">%1$s</div>',
					et_core_esc_previously( $image_video )
				);
			}
		}
		$image_media_wrap = '';
		if ( ! empty( $image_video ) ) {
			$image_media_wrap = sprintf(
				'<div class="dipl-hero-slide-media-wrap">%1$s</div>',
				et_core_esc_previously( $image_video )
			);
		}

		// Final slide output.
		$render_output = sprintf(
			'<div class="dipl-hero-slide-wrap">
				<div class="dipl-hero-slide-inner">
					%1$s %2$s
				</div>
			</div>',
			et_core_esc_previously( $content_wrap ),
			et_core_esc_previously( $image_media_wrap )
		);

		if ( ! empty( $this->props['content_box_bg_color'] ) ) {
			$this->generate_styles( array(
				'base_attr_name' => 'content_box_bg_color',
				'selector'       => "{$this->main_css_element} .dipl-hero-slide-content-inner",
				'hover_selector' => "{$this->main_css_element} .dipl-hero-slide-content-inner:hover",
				'css_property'   => 'background',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		$background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class( $this->props );
		$this->add_classname( array(
			$this->get_text_orientation_classname(),
			$background_layout_class_names[0],
			'dipl-hero-slider-item',
			'swiper-slide'
		) );

		self::$rendering = false;
		return $render_output;
	}

	static function get_video( $args = array() ) {
		$defaults = array(
			'src'      => '',
			'src_webm' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$video_src = '';
		if ( false !== et_pb_check_oembed_provider( esc_url( $args['src'] ) ) ) {
			$video_src = et_builder_get_oembed( esc_url( $args['src'] ) );
		} elseif ( false !== et_pb_validate_youtube_url( esc_url( $args['src'] ) ) ) {
			$args['src'] = et_pb_normalize_youtube_url( esc_url( $args['src'] ) );
			$video_src   = et_builder_get_oembed( esc_url( $args['src'] ) );
		} else {
			$video_src = sprintf(
				'<video controls>
					 %1$s
					 %2$s
				 </video>',
				( '' !== $args['src'] ? sprintf( '<source type="video/mp4" src="%1$s" />', esc_url( $args['src'] ) ) : '' ),
				( '' !== $args['src_webm'] ? sprintf( '<source type="video/webm" src="%1$s" />', esc_url( $args['src_webm'] ) ) : '' )
			);

			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}

		return $video_src;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_hero_slider', $modules ) ) {
		new DIPL_HeroSliderItem();
	}
} else {
	new DIPL_HeroSliderItem();
}
