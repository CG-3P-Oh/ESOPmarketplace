<?php
class DIPL_PromotionBar extends ET_Builder_Module {
	public $slug       = 'dipl_promotion_bar';
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
		$this->name = esc_html__( 'DP Promotion Bar', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Date & Content', 'divi-plus' ),
					'display'      => esc_html__( 'Display', 'divi-plus' )
				)
			),
			'advanced' => array(
				'toggles' => array(
					'bar_content_box' => esc_html__( 'Content Box', 'divi-plus' ),
					'image_styling'   => esc_html__( 'Image', 'divi-plus' ),
					'bar_content'     => array(
						'title'             => esc_html__( 'Promotion Bar Content', 'divi-plus' ),
						'tabbed_subtoggles' => true,
						'sub_toggles'       => array(
							'title'       => array( 'name' => esc_html__( 'Title', 'divi-plus' ) ),
							'description' => array( 'name' => esc_html__( 'Description', 'divi-plus' ) )
						),
					),
					'timer_box'    => esc_html__( 'Timer/Clock Box', 'divi-plus' ),
					'timer_digits' => esc_html__( 'Timer/Clock Digits', 'divi-plus' ),
					'timer_labels' => esc_html__( 'Timer/Clock Labels', 'divi-plus' ),
					'timer_seps'   => esc_html__( 'Timer Separator ', 'divi-plus' ),
					'sale_button'  => esc_html__( 'Sale Button', 'divi-plus' ),
					'close_icon'   => esc_html__( 'Close Icon', 'divi-plus' ),
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
						'default'        => '26px',
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
						'main'     => "{$this->main_css_element} .dipl-pb-title",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'bar_content',
					'sub_toggle'  => 'title',
				),
				'description' => array(
					'label'        => esc_html__( 'Description', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'          => array(
						'main'     => "{$this->main_css_element} .dipl-pb-desc",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'bar_content',
					'sub_toggle'  => 'description',
				),
				'digits_text' => array(
					'label'          => esc_html__( 'Digit', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '28px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '200',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'            => array(
						'main' => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-number",
					),
					'hide_text_align' => true,
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'timer_digits',
				),
				'label_text' => array(
					'label'          => esc_html__( 'Label', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-label",
					),
					'hide_text_align' => true,
					'depends_on'          => array( 'show_separator' ),
					'depends_show_if_not' => 'none',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'timer_labels',
				),
				'separator_text' => array(
					'label'           => esc_html__( 'Separator', 'divi-plus' ),
					'font_size'       => array(
						'default'        => '40px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '200',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl-promotion-bar-timer .dipl-pb-separator",
					),
					'hide_text_align' => true,
					'depends_on'      => array( 'show_separator' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'timer_seps',
				),
			),
			'borders' => array(
				'timer_box' => array(
					'label_prefix'    => esc_html__( 'Timer Clock Box', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl-pb-timer-box',
							'border_styles' => '%%order_class%% .dipl-pb-timer-box',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'timer_box'
				),
				'digits' => array(
					'label_prefix'  => esc_html__( 'Digit', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl-pb-timer-box .dipl-pb-number',
							'border_styles' => '%%order_class%% .dipl-pb-timer-box .dipl-pb-number',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'timer_digits',
				),
				'labels' => array(
					'label_prefix'  => esc_html__( 'Label', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl-pb-timer-box .dipl-pb-label',
							'border_styles' => '%%order_class%% .dipl-pb-timer-box .dipl-pb-label',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'timer_labels',
				),
				'image' => array(
					'label_prefix'  => esc_html__( 'Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl-promotion-bar-image',
							'border_styles' => '%%order_class%% .dipl-promotion-bar-image',
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'show_image' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_styling',
				),
				'default' => array(
					'css' => array(
						'main'      => array(
							'border_radii'  => $this->main_css_element,
							'border_styles' => $this->main_css_element,
						),
						'important' => 'all',
					),
				)
			),
			'promotion_bar_spacing' => array(
				'timer_box' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl-pb-timer-box",
							'padding'   => "{$this->main_css_element} .dipl-pb-timer-box",
							'important' => 'all',
						),
					),
				),
				'digits' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-number",
							'padding'   => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-number",
							'important' => 'all',
						),
					),
				),
				'labels' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-label",
							'padding'   => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-label",
							'important' => 'all',
						),
					),
				),
				'image' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl-promotion-bar-image",
							'important'  => 'all',
						),
					),
				),
			),
			'button' => array(
			    'sale_button' => array(
				    'label' => esc_html__( 'Sale Button', 'divi-plus' ),
				    'css' => array(
						'main'      => "{$this->main_css_element} .dipl-promotion-bar-btn-wrap .et_pb_button",
						'alignment' => "{$this->main_css_element} .dipl-promotion-bar-btn-wrap",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'custom_padding' => array(
							'default_on_front' => '10px|15px|10px|15px|on|on',
							'hover'            => false,
						),
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl-promotion-bar-btn-wrap",
							'padding'   => "{$this->main_css_element} .dipl-promotion-bar-btn-wrap .et_pb_button",
							'important' => 'all',
						),
					),
					'use_alignment'   	=> true,
					'box_shadow'      	=> false,
					'depends_on'        => array( 'show_sale_button' ),
					'depends_show_if'   => 'on',
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'sale_button',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'box_shadow' => array(
				'timer_box' => array(
					'label'       => esc_html__( 'Timer Box - Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => '%%order_class%% .dipl-pb-timer-box',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'timer_box',
				),
				'digits' => array(
					'label'       => esc_html__( 'Digit Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => '%%order_class%% .dipl-pb-timer-box .dipl-pb-number',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'timer_digits',
				),
				'labels' => array(
					'label'       => esc_html__( 'Label Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => '%%order_class%% .dipl-pb-timer-box .dipl-pb-label',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'timer_labels',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				),
			),
			'max_width' => array(
				'extra' => array(
					'timer_box' => array(
						'options' => array(
							'width' => array(
								'label'          => esc_html__( 'Digit Box Width', 'divi-plus' ),
								'range_settings' => array(
									'min'  => 100,
									'max'  => 350,
									'step' => 1,
								),
								'hover'          => false,
								'default_unit'   => 'px',
								'default'		 => '100px',
								'default_tablet' => '',
								'default_phone'  => '',
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'timer_digits',
							),
						),
						'use_max_width'        => false,
						'use_module_alignment' => false,
						'css'                  => array(
							'main' => "{$this->main_css_element} .dipl-pb-timer-box .dipl-pb-number",
							'important' => 'all',
						),
					),
					'content_box' => array(
						'options' => array(
							'width' => array(
								'label'          => esc_html__( 'Content Box Width', 'divi-plus' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'default_unit'   => '%',
								'default'		 => '50%',
								'default_on_front' => '50%',
								'default_tablet' => '',
								'default_phone'  => '',
								'show_if'        => array(
                                    'layout' => array( 'layout2', 'layout3' )
                                ),
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'bar_content_box',
							),
						),
						'use_max_width'            => false,
						'use_module_alignment' => false,
						'css'                  => array(
							'main' => "{$this->main_css_element} .dipl-promotion-bar-wrap.layout2 .dipl-promotion-bar-content, {$this->main_css_element} .dipl-promotion-bar-wrap.layout3 .dipl-promotion-bar-content",
							'important' => 'all',
						),
					)
				),
				'default' => array(
					'css' => array(
						'main'             => '%%order_class%%',
						'module_alignment' => '%%order_class%%',
					),
				),
			),
			'background' => array(
				'css' => array(
					'main' => $this->main_css_element,
				),
			),
			'link_options' => false,
			'text'         => false,
			'text_shadow'  => false
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'date_time' => array(
					'label'           => esc_html__( 'Select Date & Time', 'divi-plus' ),
					'type'            => 'date_picker',
					'option_category' => 'basic_option',
					'description'     => et_get_safe_localization( sprintf( __( 'This is the date the countdown timer is counting down to. Your countdown timer is based on your timezone settings in your <a href="%1$s" target="_blank" title="WordPress General Settings">WordPress General Settings</a>', 'divi-plus' ), esc_url( admin_url( 'options-general.php' ) ) ) ),
					'toggle_slug'     => 'main_content',
				),
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
				'show_image' => array(
					'label'            => esc_html__( 'Show Image', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default_on_front' => 'off',
					'description'      => esc_html__( 'Enable this display the image.', 'divi-plus' ),
					'toggle_slug'      => 'main_content',
				),
				'image' => array(
					'label'              => esc_html__( 'Select Image', 'divi-plus' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
					'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
					'dynamic_content'  	 => 'image',
					'show_if'            => array(
						'show_image' => 'on'
					),
					'description'        => esc_html__( 'Here you can upload the image to be used for the promotion bar.', 'divi-plus' ),
					'toggle_slug'        => 'main_content',
				),
				'image_alt_text' => array(
					'label'           => esc_html__( 'Image Alt Text', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'show_image' => 'on'
					),
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the text to be used for the image as HTML ALT text.', 'divi-plus' ),
				),
				'layout' => array(
					'label'            => esc_html__( 'Layout', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
						'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
						'layout3' => esc_html__( 'Layout 3', 'divi-plus' ),
					),
					'default'          => 'layout1',
					'default_on_front' => 'layout1',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Select to the layout to display.', 'divi-plus' ),
				),
				'hide_days' => array(
					'label'            => esc_html__( 'Hide Days', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default_on_front' => 'off',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Enable this to do not display the days box in timer.', 'divi-plus' ),
				),
				'display_label' => array(
					'label'            => esc_html__( 'Display Labels', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'none'   => esc_html__( 'None', 'divi-plus' ),
						'full'   => esc_html__( 'Full Label', 'divi-plus' ),
						'short'  => esc_html__( 'Short Label', 'divi-plus' ),
						'single' => esc_html__( 'Single Character', 'divi-plus' ),
					),
					'default'          => 'full',
					'default_on_front' => 'full',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Select to display the type of timer labels.', 'divi-plus' ),
				),
				'stack_label' => array(
					'label'            => esc_html__( 'Display Labels In Stack', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default_on_front' => 'on',
					'show_if_not'      => array(
						'display_label' => 'none'
					),
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Enable this to display the stacked (In new line) label.', 'divi-plus' ),
				),
				'show_close_button' => array(
					'label'           => esc_html__( 'Display Close Button', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'layout',
					'options'         => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default'         => 'off',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Enable this to display the close button. This will hide the whole promotion bar, and displayed again on page reload.', 'divi-plus' ),
				),
				'show_sale_button' => array(
					'label'     	  => esc_html__( 'Show Button', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'basic_option',
					'options'         => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default'         => 'on',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can choose whether or not show the button.', 'divi-plus' ),
				),
				'sale_button_text' => array(
					'label'    			=> esc_html__( 'Button Text', 'divi-plus' ),
					'type'              => 'text',
					'option_category'   => 'basic_option',
					'show_if'           => array( 'show_sale_button' => 'on' ),
					'default'			=> esc_html__( 'Get the deal', 'divi-plus' ),
					'default_on_front'	=> esc_html__( 'Get the deal', 'divi-plus' ),
					'toggle_slug'       => 'display',
					'description'       => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
				),
				'sale_button_url' => array(
					'label'           	=> esc_html__( 'Button Link URL', 'divi-plus' ),
					'type'            	=> 'text',
					'option_category' 	=> 'basic_option',
					'show_if'           => array( 'show_sale_button' => 'on' ),
					'dynamic_content' 	=> 'url',
					'toggle_slug'     	=> 'display',
					'description'     	=> esc_html__( 'Here you can input the destination URL for the button to open when clicked.', 'divi-plus' ),
				),
				'sale_button_new_window' => array(
					'label'            	=> esc_html__( 'Button Link Target', 'divi-plus' ),
					'type'             	=> 'select',
					'option_category'  	=> 'configuration',
					'show_if'           => array( 'show_sale_button' => 'on' ),
					'options'          	=> array(
						'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
						'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
					),
					'toggle_slug'      	=> 'display',
					'description'      	=> esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
				),
				'content_box_alignment' => array(
					'label'                 => esc_html__( 'Content Box Alignment', 'divi-plus' ),
					'type'                  => 'text_align',
					'option_category'       => 'layout',
					'options'               => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'mobile_options'        => true,
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'bar_content_box',
					'description'           => esc_html__( 'Here you can choose the alignment of the buttons in the left, right, or center of the module.', 'divi-plus' ),
				),
				'image_custom_padding' => array(
					'label'           => esc_html__( 'Image Padding', 'divi-plus' ),
					'type'            => 'custom_padding',
					'option_category' => 'layout',
					'mobile_options'  => true,
					'hover'           => false,
					'default'         => '||||true|true',
					'show_if'         => array( 'show_image' => 'on' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image_styling',
					'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'enable_image_width' => array(
					'label'            => esc_html__( 'Enable Image Min/Max Width', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if'          => array(
						'show_image' => 'on',
					),
					'default'           => 'off',
					'default_on_front'  => 'off',
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'image_styling',
					'description'       => esc_html__( 'Whether or not to enable custom image minimum width.', 'divi-plus' ),
				),
				'image_width' => array(
					'label'             => esc_html__( 'Image Minimum Width', 'divi-plus' ),
					'description'       => esc_html__( 'Adjust the image minimum width within the slide.', 'divi-plus' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'default'           => '100px',
					'default_on_front'  => '100px',
					'default_unit'      => 'px',
					'allowed_units'     => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
					'range_settings'    => array(
						'min'  => '1',
						'max'  => '1000',
						'step' => '1',
					),
					'show_if'           => array(
						'show_image'         => 'on',
						'enable_image_width' => 'on',
					),
					'mobile_options'    => true,
					'sticky'            => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'image_styling',
				),
				'image_max_width' => array(
					'label'             => esc_html__( 'Image Maximum Width', 'divi-plus' ),
					'description'       => esc_html__( 'Adjust the image maximum width within the slide.', 'divi-plus' ),
					'type'              => 'range',
					'option_category'   => 'layout',
					'default'           => '',
					'default_on_front'  => '',
					'default_unit'      => 'px',
					'allowed_units'     => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
					'range_settings'    => array(
						'min'  => '10',
						'max'  => '1000',
						'step' => '1',
					),
					'show_if'           => array(
						'show_image'         => 'on',
						'enable_image_width' => 'on',
					),
					'mobile_options'    => true,
					'sticky'            => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'image_styling',
				),
				'timer_box_alignment' => array(
					'label'                 => esc_html__( 'Timer Box Alignment', 'divi-plus' ),
					'type'                  => 'text_align',
					'option_category'       => 'layout',
					'options'               => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'mobile_options'        => true,
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'timer_box',
					'description'           => esc_html__( 'Here you can choose the alignment of the buttons in the left, right, or center of the module.', 'divi-plus' ),
				),
				'timer_box_background_color' => array(
					'label'             => esc_html__( 'Timer Box Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'button_bg',
					'context'           => 'button_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'timer_box_background', 'button', 'advanced', 'timer_box', 'timer_box_background_color' ),
					'hover'             => false,
					'mobile_options'    => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'timer_box',
					'description'       => esc_html__( 'Customize the background style of the number box by adjusting the background color, gradient, and image.', 'divi-plus' ),
				),
				'timer_box_custom_padding' => array(
					'label'            => esc_html__( 'Timer Box Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '10px|5px|10px|5px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'timer_box',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'timer_box_custom_margin' => array(
					'label'            => esc_html__( 'Timer Box Margin', 'divi-plus' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '5px|5px|5px|5px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'timer_box',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'digits_background_color' => array(
					'label'          => esc_html__( 'Digit Background', 'divi-plus' ),
					'type'           => 'color-alpha',
					'custom_color'   => true,
					'mobile_options' => false,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'timer_digits',
					'description'    => esc_html__( 'Customize the background style of the number box by adjusting the background color, gradient, and image.', 'divi-plus' ),
				),
				'digits_custom_padding' => array(
					'label'            => esc_html__( 'Digit Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '5px||5px||true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'timer_digits',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'digits_custom_margin' => array(
					'label'            => esc_html__( 'Digit Margin', 'divi-plus' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '||||true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'timer_digits',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'labels_background_color' => array(
					'label'          => esc_html__( 'Label Background', 'divi-plus' ),
					'type'           => 'color-alpha',
					'custom_color'   => true,
					'mobile_options' => false,
					'hover'          => 'tabs',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'timer_labels',
					'description'    => esc_html__( 'Customize the background style of the number box by adjusting the background color, gradient, and image.', 'divi-plus' ),
				),
				'labels_custom_padding' => array(
					'label'           => esc_html__( 'Label Padding', 'divi-plus' ),
					'type'            => 'custom_padding',
					'option_category' => 'layout',
					'mobile_options'  => true,
					'hover'           => false,
					'default'         => '||||true|true',
					'show_if_not'     => array( 'display_label' => 'none' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'timer_labels',
					'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'labels_custom_margin' => array(
					'label'           => esc_html__( 'Label Margin', 'divi-plus' ),
					'type'            => 'custom_margin',
					'option_category' => 'layout',
					'mobile_options'  => true,
					'hover'           => false,
					'default'         => '||||true|true',
					'show_if_not'     => array( 'display_label' => 'none' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'timer_labels',
					'description'     => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'show_separator' => array(
					'label'            => esc_html__( 'Show Separator', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default_on_front' => 'off',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'timer_seps',
					'description'      => esc_html__( 'Enable this to do not display the separator between box in timer.', 'divi-plus' ),
				),
				'separator_text' => array(
					'label'           	=> esc_html__( 'Separator Text', 'divi-plus' ),
					'type'            	=> 'text',
					'option_category' 	=> 'basic_option',
					'show_if'           => array( 'show_separator' => 'on' ),
					'default_on_front'  => ':',
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'timer_seps',
					'description'     	=> esc_html__( 'Here you can input the destination URL for the sale button to open when clicked.', 'divi-plus' ),
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
			),
			$this->generate_background_options( 'timer_box_background', 'skip', 'advanced', 'timer_box', 'timer_box_background_color' )
		);
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a DIPL Woo Product module while a DIPL Woo Product module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		// Check date time is there.
		$date_time = $this->props['date_time'];
		if ( empty( $date_time ) ) {
			return '';
		}

		// Get the props.
		$multi_view        = et_pb_multi_view_options( $this );
		$layout            = esc_attr( $this->props['layout'] );
		$stack_label       = esc_attr( $this->props['stack_label'] );
		$display_label     = esc_attr( $this->props['display_label'] );
		$hide_days         = esc_attr( $this->props['hide_days'] );
		$show_separator    = esc_attr( $this->props['show_separator'] );
		$separator_text    = esc_attr( $this->props['separator_text'] );
		$show_close_button = esc_attr( $this->props['show_close_button'] ) ?? 'off';

		$show_sale_button  = esc_attr( $this->props['show_sale_button'] );

		// Date conversion.
		$timer_date        = gmdate( 'M d, Y H:i:s', strtotime( $date_time ) );
		$gmt_offset        = strval( get_option( 'gmt_offset' ) );
		$gmt_divider       = '-' === substr( $gmt_offset, 0, 1 ) ? '-' : '+';
		$gmt_offset_hour   = str_pad( abs( intval( $gmt_offset ) ), 2, '0', STR_PAD_LEFT );
		$gmt_offset_minute = str_pad( ( ( abs( $gmt_offset ) * 100 ) % 100 ) * ( 60 / 100 ), 2, '0', STR_PAD_LEFT );
		$gmt               = "GMT{$gmt_divider}{$gmt_offset_hour}{$gmt_offset_minute}";

		$title_level = et_pb_process_header_level( $this->props['title_level'], 'h2' );
		$title = '';
		if ( ! empty( $this->props['title'] ) ) {
			$title = sprintf( '<%1$s class="dipl-pb-title">%2$s</%1$s>', esc_attr( $title_level ), esc_html( $this->props['title'] ) );
		}

		$description = '';
		if ( ! empty( $this->props['description'] ) ) {
			$description = sprintf( '<div class="dipl-pb-desc">%1$s</div>', esc_html( $this->props['description'] ) );
		}

		// Get labels.
		$lables = array(
			'days' => array(
				'full'   => esc_html__( 'Days', 'divi-plus' ),
				'short'  => esc_html__( 'Days', 'divi-plus' ),
				'single' => esc_html__( 'D', 'divi-plus' ),
			),
			'hours' => array(
				'full'   => esc_html__( 'Hours', 'divi-plus' ),
				'short'  => esc_html__( 'Hrs', 'divi-plus' ),
				'single' => esc_html__( 'H', 'divi-plus' ),
			),
			'minutes' => array(
				'full'   => esc_html__( 'Minutes', 'divi-plus' ),
				'short'  => esc_html__( 'Min', 'divi-plus' ),
				'single' => esc_html__( 'M', 'divi-plus' ),
			),
			'seconds' => array(
				'full'   => esc_html__( 'Seconds', 'divi-plus' ),
				'short'  => esc_html__( 'Sec', 'divi-plus' ),
				'single' => esc_html__( 'S', 'divi-plus' ),
			)
		);

		// Get the labels.
		$day_label = $hour_label = $minute_label = $second_label = '';
		if ( 'none' !== $display_label ) {
			$day_label    = ( $lables['days'][ $display_label ] ) ? $lables['days'][ $display_label ] : esc_html__( 'Days', 'divi-plus' );
			$hour_label   = ( $lables['hours'][ $display_label ] ) ? $lables['hours'][ $display_label ] : esc_html__( 'Hours', 'divi-plus' );
			$minute_label = ( $lables['minutes'][ $display_label ] ) ? $lables['minutes'][ $display_label ] : esc_html__( 'Minutes', 'divi-plus' );
			$second_label = ( $lables['seconds'][ $display_label ] ) ? $lables['seconds'][ $display_label ] : esc_html__( 'Seconds', 'divi-plus' );
		}

		$render_seps = '';
		if ( 'on' === $show_separator ) {
			$render_seps = sprintf( '<span class="dipl-pb-separator">%1$s</span>', $separator_text );
		}

		// Get box for the days, only if enabled.
		$daysLableBox = '';
		if ( 'off' === $hide_days ) {
			$daysLableBox = sprintf(
				'<div class="dipl-pb-timer-box dipl-pb-days">
					<span class="dipl-pb-number">00</span>%1$s
				</div>%2$s',
				( ! empty( $day_label ) ) ? sprintf( '<span class="dipl-pb-label">%1$s</span>', $day_label ) : '',
				et_core_esc_previously( $render_seps )
			);
		}

		$render_button = '';
		if ( 'on' === $show_sale_button ) {
			$render_button = $this->render_button( array(
				'display_button'	  => '' !== $this->props['sale_button_url'] && 'off' !== $show_sale_button ? true : false,
				'button_text'         => sprintf( '<span class="dipl_btn_text">%1$s</span>', esc_attr( $this->props['sale_button_text'] ) ),
				'button_text_escaped' => true,
				'has_wrapper'      	  => false,
				'button_url'          => esc_url( $this->props['sale_button_url'] ),
				'url_new_window'      => esc_attr( $this->props['sale_button_new_window'] ),
				'button_custom'       => isset( $this->props['custom_sale_button'] ) ? esc_attr( $this->props['custom_sale_button'] ) : 'off',
				'custom_icon'         => isset( $this->props['sale_button_icon'] ) ? $this->props['sale_button_icon'] : '',
				'button_rel'          => isset( $this->props['sale_button_rel'] ) ? esc_attr( $this->props['sale_button_rel'] ) : '',
			) );

			$render_button = sprintf(
				'<div class="et_pb_button_wrapper dipl-promotion-bar-btn-wrap">%1$s</div>',
				et_core_esc_previously( $render_button )
			);
		}

		// Image render.
		$image = '';
		if ( 'on' === $this->props['show_image'] ) {
			$image = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{image}}',
					'class' => 'dipl-promotion-bar-image',
					'alt'   => '{{image_alt_text}}',
				),
				'required' => 'image',
			) );
			if ( ! empty($image) ) {
				$image = sprintf( '<div class="et_pb_image_wrap">%1$s</div>', et_core_esc_previously( $image ) );
			}
		}
	
		// Close button.
		$close_button = '';
		if ( 'on' === $show_close_button ) {
			$close_icon   = 'M||divi||400';
			$close_button = sprintf(
				'<a href="#" class="dipl-promotion-bar-close-btn">
					<span class="et-pb-icon">%1$s</span>
				</a>',
				et_pb_process_font_icon( $close_icon )
			);
		}

		$output = sprintf(
			'<div class="dipl-promotion-bar-wrap %1$s" data-timestamp="%2$s">
				<div class="dipl-promotion-bar-inner">
					<div class="dipl-promotion-bar-content">
						%12$s
						<div class="dipl-promotion-bar-content-inner">%3$s %4$s</div>
						%9$s
					</div>
					<div class="dipl-promotion-bar-timer">
						%5$s
						<div class="dipl-pb-timer-box dipl-pb-hours">
							<span class="dipl-pb-number">00</span>%6$s
						</div>%11$s
						<div class="dipl-pb-timer-box dipl-pb-minutes">
							<span class="dipl-pb-number">00</span>%7$s
						</div>%11$s
						<div class="dipl-pb-timer-box dipl-pb-seconds">
							<span class="dipl-pb-number">00</span>%8$s
						</div>
					</div>
					%10$s
					%13$s
				</div>
			</div>',
			esc_attr( $layout ),
			esc_attr( strtotime( "{$timer_date} {$gmt}" ) ),
			et_core_esc_previously( $title ),
			et_core_esc_previously( $description ),
			et_core_esc_previously( $daysLableBox ),
			( ! empty( $hour_label ) ) ? sprintf( '<span class="dipl-pb-label">%1$s</span>', $hour_label ) : '',
			( ! empty( $minute_label ) ) ? sprintf( '<span class="dipl-pb-label">%1$s</span>', $minute_label ) : '',
			( ! empty( $second_label ) ) ? sprintf( '<span class="dipl-pb-label">%1$s</span>', $second_label ) : '',
			( 'layout2' === $layout ) ? et_core_esc_previously( $render_button ) : '',
			( 'layout2' !== $layout ) ? et_core_esc_previously( $render_button ) : '',
			et_core_esc_previously( $render_seps ),
			et_core_esc_previously( $image ),
			et_core_esc_previously( $close_button )
		);

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-promotion-bar-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/PromotionBar/' . $file . '.min.css', array(), '1.0.0' );

		wp_enqueue_script( 'dipl-promotion-bar-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/PromotionBar/dipl-promotion-bar-custom.min.js", array('jquery'), '1.0.0', true );

		// Alignments.
		$content_box_alignment = et_pb_responsive_options()->get_property_values( $this->props, 'content_box_alignment' );
		if ( ! empty( array_filter( $content_box_alignment ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $content_box_alignment, '%%order_class%% .dipl-promotion-bar-content', 'text-align', $render_slug, '!important;', 'type' );
		}
		$timer_box_alignment = et_pb_responsive_options()->get_property_values( $this->props, 'timer_box_alignment' );
		foreach( $timer_box_alignment as &$align ) {
			$align = str_replace( array( 'left', 'right', 'justify' ), array( 'flex-start', 'flex-end', 'flex-start' ), $align);
		}
		if ( ! empty( array_filter( $timer_box_alignment ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $timer_box_alignment, '%%order_class%% .dipl-promotion-bar-timer', 'justify-content', $render_slug, '!important;', 'type' );
		}

		// Image styling.
		if ( 'on' === $this->props['show_image'] ) {
			if ( 'on' === $this->props['enable_image_width'] ) {
				$this->generate_styles( array(
					'hover'                           => false,
					'base_attr_name'                  => 'image_width',
					'selector'                        => '%%order_class%% .dipl-promotion-bar-image',
					'sticky_pseudo_selector_location' => 'prefix',
					'css_property'                    => 'min-width',
					'important'                       => true,
					'render_slug'                     => $render_slug,
					'type'                            => 'range',
				) );
				$this->generate_styles( array(
					'hover'                           => false,
					'base_attr_name'                  => 'image_max_width',
					'selector'                        => '%%order_class%% .dipl-promotion-bar-image',
					'sticky_pseudo_selector_location' => 'prefix',
					'css_property'                    => 'max-width',
					'important'                       => true,
					'render_slug'                     => $render_slug,
					'type'                            => 'range',
				) );
			}
		}

		// Stack labels.
		if ( 'off' === $stack_label ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-pb-timer-box',
				'declaration' => 'display: flex; align-items: center; justify-content: center;',
			) );
		}

		// Digits background color.
		if ( ! empty( $this->props['digits_background_color'] ) ) {
			$this->generate_styles( array(
				'hover'          => false,
				'base_attr_name' => 'digits_background_color',
				'selector'       => '%%order_class%% .dipl-pb-timer-box .dipl-pb-number',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		// Lable background color.
		if ( 'none' !== $display_label && ! empty( $this->props['labels_background_color'] ) ) {
			$this->generate_styles( array(
				'hover'          => false,
				'base_attr_name' => 'labels_background_color',
				'selector'       => '%%order_class%% .dipl-pb-timer-box .dipl-pb-label',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		// Button icon style based on position.
		if ( 'on' === $this->props['sale_button_use_icon'] && 'on' === $this->props['custom_sale_button'] ) {
			
			$sale_button_on_hover       = et_pb_responsive_options()->get_property_values( $this->props, 'sale_button_on_hover' );
			$sale_button_icon_placement = et_pb_responsive_options()->get_property_values( $this->props, 'sale_button_icon_placement' );

			$sale_button_icon_placement['desktop'] = $sale_button_icon_placement['desktop'] ?? 'right';
			$sale_button_icon_placement['tablet']  = $sale_button_icon_placement['tablet'] ?? $sale_button_icon_placement['desktop'];
			$sale_button_icon_placement['phone']   = $sale_button_icon_placement['phone'] ?? $sale_button_icon_placement['tablet'];

			if ( ! empty( array_filter( $sale_button_on_hover ) ) ) {
				foreach( $sale_button_on_hover as $device => $value ) {
					$media_query = array();
					if ( 'tablet' === $device ) {
						$media_query = array( 'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ) );
					} elseif ( 'phone' === $device ) {
						$media_query = array( 'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ) );
					}
					if ( 'on' === $value ) {
						self::set_style( $render_slug, array_merge( array(
							'selector'    => '%%order_class%% .dipl-promotion-bar-wrap .dipl-promotion-bar-btn-wrap .et_pb_button:after',
							'declaration' => 'transform: translateX(-1.6em);',
						), $media_query ) );
						self::set_style( $render_slug, array_merge( array(
							'selector'    => '%%order_class%% .dipl-promotion-bar-wrap .dipl-promotion-bar-btn-wrap .et_pb_button:before',
							'declaration' => 'transform: translateX(1.6em);',
						), $media_query ) );
						if ( ! empty( $sale_button_icon_placement[ $device ] ) && 'left' === $sale_button_icon_placement[ $device ] ) {
							self::set_style( $render_slug, array_merge( array(
								'selector'    => '%%order_class%% .dipl-promotion-bar-btn-wrap .dipl_btn_text',
								'declaration' => 'transform: translateX(-0.8em);',
							), $media_query ) );
						} else {
							self::set_style( $render_slug, array_merge( array(
								'selector'    => '%%order_class%% .dipl-promotion-bar-btn-wrap .dipl_btn_text',
								'declaration' => 'transform: translateX(0.8em);',
							), $media_query ) );
						}
					} else {
						self::set_style( $render_slug, array_merge( array(
							'selector'    => "{$this->main_css_element} .dipl-promotion-bar-wrap .dipl-promotion-bar-btn-wrap .et_pb_button:after," . 
											"{$this->main_css_element} .dipl-promotion-bar-wrap .dipl-promotion-bar-btn-wrap .et_pb_button:before," .
											"{$this->main_css_element} .dipl-promotion-bar-btn-wrap .dipl_btn_text",
							'declaration' => 'transform: translateX(0);',
						), $media_query ) );
					}
				}

				$settings = array( 'css' => array(
					'padding'   => "body #page-container {$this->main_css_element} .dipl-promotion-bar-inner .dipl-promotion-bar-btn-wrap .et_pb_button:hover",
					'important' => 'all',
				) );
				$this->overrite_button_hover_padding( 'sale_button', $settings, $render_slug, $this->margin_padding );
			}
		}

		// Close icon styling.
		if ( 'on' === $show_close_button ) {
			$close_icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'close_icon_fontsize' );
			et_pb_responsive_options()->generate_responsive_css( $close_icon_fontsize, "{$this->main_css_element} .dipl-promotion-bar-close-btn .et-pb-icon", 'font-size', $render_slug, '!important;', 'range' );
			$this->generate_styles( array(
				'base_attr_name' => 'close_icon_color',
				'selector'       => "{$this->main_css_element} .dipl-promotion-bar-close-btn .et-pb-icon",
				'hover_selector' => "{$this->main_css_element} .dipl-promotion-bar-close-btn .et-pb-icon:hover",
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		$args = array(
			'render_slug' => $render_slug,
			'props'		  => $this->props,
			'fields'	  => $this->fields_unprocessed,
			'module'	  => $this,
			'backgrounds' => array(
				'timer_box_background' => array(
					'normal' => "{$this->main_css_element} .dipl-pb-timer-box",
					'hover'  => "{$this->main_css_element} .dipl-pb-timer-box:hover",
				)
			),
		);
		DiviPlusHelper::process_background( $args );

		$fields = array( 'promotion_bar_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $output;
	}

	public function overrite_button_hover_padding( $button, $settings, $render_slug, $margin_padding ) {
		$utils = ET_Core_Data_Utils::instance();

		$padding_key = "{$button}_custom_padding";
		if ( '' !== $utils->array_get( $this->props, $padding_key, '' ) ) {
			// Ensure main selector exists.
			$form_field_margin_padding_css = $utils->array_get( $settings, 'css.main', '' );
			if ( empty( $form_field_margin_padding_css ) ) {
				$utils->array_set( $settings, 'css.main', $utils->array_get( $button, 'css.main', '' ) );
			}

			$margin_padding->update_styles( $this, $button, $settings, $render_slug, $this->advanced_field );
		}
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_promotion_bar', $modules ) ) {
		new DIPL_PromotionBar();
	}
} else {
	new DIPL_PromotionBar();
}
