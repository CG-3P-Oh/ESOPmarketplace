<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_AdvancedTooltip extends ET_Builder_Module {
	public $slug       = 'dipl_advanced_tooltip';
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
		$this->name             = esc_html__( 'DP Advanced Tooltip', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'configuration' => esc_html__( 'Configuration', 'divi-plus' ),
					'content'       => esc_html__( 'Content', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'trigger_button' => esc_html__( 'Trigger Button', 'divi-plus' ),
					'trigger_image'  => esc_html__( 'Trigger Image', 'divi-plus' ),
					'trigger_icon'   => esc_html__( 'Trigger Icon', 'divi-plus' ),
					'trigger_text'   => esc_html__( 'Trigger Text', 'divi-plus' ),
					'tooltip_style'  => esc_html__( 'Tooltip Styling', 'divi-plus' ),
					'tooltip_text'   => esc_html__( 'Tooltip Content Text', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'tooltip_trigger_text' => array(
					'label'     => esc_html__( 'Trigger', 'divi-plus' ),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '500',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'       => array(
						'main'       => "{$this->main_css_element} .dipl_tooltip_trigger_text",
						'text_align' => "{$this->main_css_element} .dipl_tooltip_trigger_element_wrap.trigger_type_text",
						'important'  => 'all',
					),
					'show_if'         => array( 'trigger_element' => 'text' ),
					'depends_on'      => array( 'trigger_element' ),
					'depends_show_if' => 'text',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'trigger_text',
				),
				'tooltip_text' => array(
					'label'     => esc_html__( 'Tooltip Content', 'divi-plus' ),
					'font_size' => array(
						'default' => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '500',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'       => array(
						'main'      => ".tippy-box[data-theme='%%order_class%%_0'] .dipl_advanced_tooltip_content",
						'important'	=> 'all',
					),
					'show_if'         => array( 'tooltip_content_type' => 'text' ),
					'depends_on'      => array( 'tooltip_content_type' ),
					'depends_show_if' => 'text',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_text',
				),
			),
			'button' => array(
				'trigger_button' => array(
					'label' => esc_html__( 'Trigger Button', 'divi-plus' ),
					'css'   => array(
						'main'      => "%%order_class%% .dipl_tooltip_trigger_button",
						'alignment' => "%%order_class%% .dipl_tooltip_trigger_element_wrap .et_pb_button_wrapper",
						'important' => 'all',
					),
					'margin_padding' => array(
						'css' => array(
							'margin'    => "%%order_class%% .dipl_tooltip_trigger_element_wrap .et_pb_button_wrapper",
							'padding'   => "%%order_class%% .dipl_tooltip_trigger_button",
							'important' => 'all',
						),
					),
					'box_shadow' => array(
						'css' => array(
							'main'      => "%%order_class%% .dipl_tooltip_trigger_button",
							'important' => 'all',
						),
					),
					'use_alignment'   => true,
					'show_if'         => array( 'trigger_element' => 'button' ),
					'depends_on'      => array( 'trigger_element' ),
					'depends_show_if' => 'button',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'trigger_button',
				),
			),
			'max_width' => array(
				'extra' => array(
					'trigger_image' => array(
						'options'   => array(
							'width' => array(
								'label' => esc_html__( 'Image Width', 'divi-plus' ),
								'range_settings' => array(
									'min'  => 1,
									'max'  => 100,
									'step' => 1,
								),
								'hover'          => false,
								'default_unit'   => '%',
								'default_tablet' => '',
								'default_phone'  => '',
								'show_if'        => array( 'trigger_element' => 'image' ),
								'tab_slug'       => 'advanced',
								'toggle_slug'    => 'trigger_image',
							),
						),
						'use_max_width'        => false,
						'use_module_alignment' => false,
						'css'                  => array(
							'main' => "{$this->main_css_element} .dipl_tooltip_trigger_image",
							'important' => 'all',
						),
					),
				)
			),
			'borders' => array(
				'tooltip' => array(
					'label_prefix' => esc_html__( 'Tooltip', 'divi-plus' ),
					'defaults' => array(
						'border_radii' => 'on|10px|10px|10px|10px',
					),
					'css'          => array(
						'main' => array(
							'border_radii'  => ".tippy-box[data-theme='%%order_class%%_0']",
							'border_styles' => ".tippy-box[data-theme='%%order_class%%_0']",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'tooltip_style',
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
				'tooltip'   => array(
					'label'             => esc_html__( 'Tooltip Box Shadow', 'divi-plus' ),
					// 'default_on_fronts' => array(
					// 	'style'      => 'preset1',
					// 	'horizontal' => '0px',
					// 	'vertical'   => '2px',
					// 	'blur'       => '18px',
					// 	'spread'     => '0px',
					// 	'color'      => 'rgba(0,0,0,0.3)',
					// 	'position'   => 'outer',
					// ),
			        'css' => array(
						'main'      => ".tippy-box[data-theme='%%order_class%%_0']",
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
				    'toggle_slug'  => 'tooltip_style',
			     ),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'advanced_tooltip_spacing' => array(
				'tooltip' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => ".tippy-box[data-theme='%%order_class%%_0'] .dipl_advanced_tooltip_content",
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
			'link_options' => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {

		// Get divi library layout.
		$query_args = array(
			'post_type'      => 'et_pb_layout',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array( array(
				'key'     => '_et_pb_predefined_layout',
				'value'   => 'on',
				'compare' => 'NOT EXISTS',
			) ),
		);
		$library_layouts = get_posts( $query_args );

		// Create select var.
		$layout_list = array( '0' => esc_html__( 'Select', 'divi-plus' ) );
		if ( $library_layouts ) {
			foreach ( $library_layouts as $lib_layout )	{
				$layout_list[ $lib_layout->ID ] = $lib_layout->post_title;
			}
		}

		$et_accent_color = et_builder_accent_color();
		return array_merge(
			array(
				'tooltip_trigger_type' => array(
					'label'           => esc_html__( 'Trigger Action', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'mouseenter' => esc_html__( 'Hover', 'divi-plus' ),
						'click'      => esc_html__( 'Click', 'divi-plus' ),
					),
					'default'         => 'mouseenter',
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Here you can select the tooltip trigger action.', 'divi-plus' ),
				),
				'tooltip_placement' => array(
					'label'           => esc_html__( 'Tooltip Placement', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'auto'   => esc_html__( 'Auto', 'divi-plus' ),
						'top'    => esc_html__( 'Top', 'divi-plus' ),
						'bottom' => esc_html__( 'Bottom', 'divi-plus' ),
						'left'   => esc_html__( 'Left', 'divi-plus' ),
						'right'  => esc_html__( 'Right', 'divi-plus' ),
					),
					'default'         => 'auto',
					'mobile_options'  => true,
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Here you can select the tooltip trigger action.', 'divi-plus' ),
				),
				'trigger_element' => array(
					'label'           => esc_html__( 'Trigger Element', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'button' => esc_html__( 'Button', 'divi-plus' ),
						'image'  => esc_html__( 'Image', 'divi-plus' ),
						'icon'   => esc_html__( 'Icon', 'divi-plus' ),
						'text'   => esc_html__( 'Text', 'divi-plus' ),
						'id'     => esc_html__( 'Element CSS ID', 'divi-plus' ),
						'class'  => esc_html__( 'Element CSS Class', 'divi-plus' ),
					),
					'default'         => 'button',
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Select the element on which you want to trigger the tooltip.', 'divi-plus' ),
				),
				'trigger_button_text' => array(
					'label'            => esc_html__( 'Trigger Button Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => esc_html__( 'Click here', 'divi-plus' ),
					'default_on_front' => esc_html__( 'Click here', 'divi-plus' ),
					'show_if'          => array( 'trigger_element' => 'button' ),
					'toggle_slug'      => 'configuration',
					'description'      => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
				),
				'trigger_button_url' => array(
					'label'            => esc_html__( 'Trigger Button URL', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => '',
					'dynamic_content'  => 'url',
					'show_if'          => array(
						'trigger_element'      => 'button',
						'tooltip_trigger_type' => 'mouseenter'
					),
					'toggle_slug'      => 'configuration',
					'description'      => esc_html__( 'Here you can input the URL to be used to open on click of the button.', 'divi-plus' ),
				),
				'trigger_button_new_window' => array(
					'label'        	  => esc_html__( 'Trigger Button Link Target', 'divi-plus' ),
					'type'        	  => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
						'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
					),
					'show_if'          => array(
						'trigger_element'      => 'button',
						'tooltip_trigger_type' => 'mouseenter'
					),
					'toggle_slug'     => 'configuration',
					'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
				),
				'trigger_image' => array(
					'label'              => esc_html__( 'Trigger Image', 'divi-plus' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
					'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
					'dynamic_content'  	 => 'image',
					'show_if'            => array( 'trigger_element' => 'image' ),
					'toggle_slug'        => 'configuration',
					'description'        => esc_html__( 'Here you can upload the image to tigger the tooltip.', 'divi-plus' ),
				),
				'trigger_image_alt' => array(
					'label'           => esc_html__( 'Image Alternative Text', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array( 'trigger_element' => 'image' ),
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Here you can input the text to be used for the image of modal trigger as alternative text.', 'divi-plus'),
				),
				'trigger_icon' => array(
					'label'           => esc_html__( 'Trigger Icon', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'default'         => '',
					'show_if'         => array( 'trigger_element' => 'icon' ),
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Here you can select the icon to be used to trigger the tooltip.', 'divi-plus' ),
				),
				'trigger_text' => array(
					'label'           => esc_html__( 'Trigger Text', 'divi-plus' ),
					'type'            => 'textarea',
					'option_category' => 'basic_option',
					'dynamic_content' => 'text',
					'show_if'         => array( 'trigger_element' => 'text' ),
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Here you can input the text to be used to trigger the tooltip.', 'divi-plus'),
				),
				'trigger_selector' => array(
					'label'           => esc_html__( 'Trigger Selector', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'dynamic_content' => 'text',
					'show_if'         => array(
						'trigger_element' => array( 'class', 'id' ),
					),
					'toggle_slug'     => 'configuration',
					'description'     => esc_html__( 'Here you can input the selector to be used to trigger the tooltip. do not use dot(.) or hash(#) with the selector.', 'divi-plus'),
				),
				'tooltip_content_type' => array(
					'label'            => esc_html__( 'Tooltip Content Type', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'text'   => esc_html__( 'Text', 'divi-plus' ),
						'image'  => esc_html__( 'Image', 'divi-plus' ),
						'layout' => esc_html__( 'Divi Library Layout', 'divi-plus' ),
					),
					'default'          => 'text',
					'toggle_slug'      => 'content',
					'description'      => esc_html__( 'Here you can choose what type of content you want to display inside modal body.', 'divi-plus'),
				),
				'content' => array(
					'label'           => esc_html__( 'Tooltip Text', 'divi-plus' ),
					'type'            => 'tiny_mce',
					'option_category' => 'basic_option',
					'dynamic_content' => 'text',
					'show_if'         => array( 'tooltip_content_type' => 'text' ),
					'toggle_slug'     => 'content',
					'description'     => esc_html__( 'Here you can input the text to be used for the tooltip content.', 'divi-plus'),
				),
				'tooltip_image' => array(
					'label'              => esc_html__( 'Tooltip Image', 'divi-plus' ),
					'type'               => 'upload',
					'option_category'    => 'basic_option',
					'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
					'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
					'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
					'show_if'            => array( 'tooltip_content_type' => 'image' ),
					'toggle_slug'        => 'content',
					'description'        => esc_html__( 'Here you can add an image to be used for the tooltip content.', 'divi-plus'),
				),
				'tooltip_image_alt' => array(
					'label'           => esc_html__( 'Image Alternative Text', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'show_if'         => array( 'tooltip_content_type' => 'image' ),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'content',
					'description'     => esc_html__( 'Here you can input the text to be used for the tooltip content image as alternative text.', 'divi-plus'),
				),
				'tooltip_lib_layout' => array(
					'label'            => esc_html__( 'Divi Library Layout', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => $layout_list,
					'show_if'          => array( 'tooltip_content_type' => 'layout' ),
					'default'          => '0',
					'default_on_front' => '0',
					'toggle_slug'      => 'content',
					'description'      => esc_html__( 'Here you can select the layouts saved in your Divi library to be used for the modal content.', 'divi-plus' ),
				),
				'trigger_icon_align' => array(
					'label'            => esc_html__( 'Alignment', 'divi-plus' ),
					'type'             => 'align',
					'option_category'  => 'layout',
					'default'          => 'left',
					'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'mobile_options'   => true,
					'show_if'          => array( 'trigger_element' => 'icon' ),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'trigger_icon',
					'description'      => esc_html__( 'Align the container to the left, right or center.', 'divi-plus' ),
				),
				'trigger_icon_fontsize' => array(
					'label'            => esc_html__( 'Trigger Icon Font Size', 'divi-plus' ),
					'type'             => 'range',
					'option_category'  => 'font_option',
					'range_settings'   => array(
						'min'  => '1',
						'max'  => '200',
						'step' => '1',
					),
					'default'          => '52px',
					'default_on_front' => '52px',
					'mobile_options'   => true,
					'show_if'          => array( 'trigger_element' => 'icon' ),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'trigger_icon',
					'description'      => esc_html__( 'Move the slider or input the value to increase or decrease trigger icon size.', 'divi-plus' ),
				),
				'trigger_icon_color' => array(
					'label'            => esc_html__( 'Trigger Icon Color', 'divi-plus' ),
					'type'             => 'color-alpha',
					'hover'            => 'tabs',
					'default'          => $et_accent_color,
					'default_on_front' => $et_accent_color,
					'show_if'          => array( 'trigger_element' => 'icon' ),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'trigger_icon',
					'description'      => esc_html__( 'Here you can define a custom color to be used for the trigger icon.', 'divi-plus' ),
				),
				'image_icon_align' => array(
					'label'            => esc_html__( 'Alignment', 'divi-plus' ),
					'type'             => 'align',
					'option_category'  => 'layout',
					'default'          => 'left',
					'options'          => et_builder_get_text_orientation_options( array( 'justified' ) ),
					'mobile_options'   => true,
					'show_if'          => array( 'trigger_element' => 'image' ),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'trigger_image',
					'description'      => esc_html__( 'Align the container to the left, right or center.', 'divi-plus' ),
				),
				'tooltip_entrance_animation' => array(
					'label'           => esc_html__( 'Entrance Animation', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'configuration',
					'options'         => array(
						'fade'                 => esc_html__( 'Fade', 'divi-plus' ),
						'scale'                => esc_html__( 'Scale', 'divi-plus' ),
						'scale-subtle'         => esc_html__( 'Scale Subtle', 'divi-plus' ),
						'scale-extreme'        => esc_html__( 'Scale Extreme', 'divi-plus' ),
						'shift-away'           => esc_html__( 'Shift Away', 'divi-plus' ),
						'shift-away-subtle'    => esc_html__( 'Shift Away Subtle', 'divi-plus' ),
						'shift-away-extreme'   => esc_html__( 'Shift Away Extreme', 'divi-plus' ),
						'shift-toward'         => esc_html__( 'Shift Toward', 'divi-plus' ),
						'shift-toward-subtle'  => esc_html__( 'Shift Toward Subtle', 'divi-plus' ),
						'shift-toward-extreme' => esc_html__( 'Shift Toward Extreme', 'divi-plus' ),
						'perspective'          => esc_html__( 'Perspective', 'divi-plus' ),
						'perspective-subtle'   => esc_html__( 'Perspective Subtle', 'divi-plus' ),
						'perspective-extreme'  => esc_html__( 'Perspective Extreme', 'divi-plus' ),
					),
					'default'         => 'fade',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_style',
					'description'     => esc_html__( 'Here you can select the animation effect to be used for the tooltip entrance.', 'divi-plus' ),
				),
				'tooltip_animation_durartion' => array(
					'label'          => esc_html__( 'Animation Duration', 'divi-plus' ),
					'type'           => 'range',
					'range_settings' => array(
						'min'  => '100',
						'max'  => '5000',
						'step' => '10',
					),
					'default'        => '350ms',
					'default_unit'   => 'ms',
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'tooltip_style',
					'description'    => esc_html__( 'Move the slider, or input the value to set the tooltip animation duration.', 'divi-plus' ),
				),
				'tooltip_show_speech_bubble' => array(
					'label'            => esc_html__( 'Show Speech Bubble', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'layout',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default'          => 'on',
					'default_on_front' => 'on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'tooltip_style',
					'description'      => esc_html__( 'Here you can choose whether or not to display speech bubble.', 'divi-plus' ),
				),
				'tooltip_interactive' => array(
					'label'           => esc_html__( 'Make Interactive Tooltip', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'layout',
					'options'         => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default'         => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_style',
					'description'     => esc_html__( 'Here you can choose whether or not to display interactive tooltip. This would provide users the possibility to interact with the content of the tooltip.', 'divi-plus' ),
				),
				'tooltip_width' => array(
					'label'          => esc_html__( 'Tooltip Width', 'divi-plus' ),
					'type'           => 'range',
					'range_settings' => array(
						'min'  => '1',
						'max'  => '1000',
						'step' => '1',
					),
					'mobile_options' => false,
					'default'        => '350px',
					'default_unit'   => 'px',
					'allowed_units'  => array( '%', 'em', 'rem', 'px', 'vh', 'vw', 'cm', 'mm', 'in', 'pt', 'pc', 'ex' ),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'tooltip_style',
					'description'    => esc_html__( 'Move the slider, or input the value to set the maximum width of the tooltip.', 'divi-plus' ),
				),
				'tooltip_custom_padding' => array(
					'label'           => esc_html__( 'Tooltip Padding', 'divi-plus' ),
					'type'            => 'custom_padding',
					'option_category' => 'layout',
					'mobile_options'  => true,
					'default'         => '20px|20px|20px|20px|true|true',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'tooltip_style',
					'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'tooltip_background_color' => array(
					'label'             => esc_html__( 'Tooltip Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'tooltip_background',
					'context'           => 'tooltip_background_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => array_merge(
						$this->generate_background_options( 'tooltip_background', 'color', 'advanced', 'background', 'tooltip_background_color' ),
						$this->generate_background_options( 'tooltip_background', 'gradient', 'advanced', 'background', 'tooltip_background_color' ),
					),
					'mobile_options'    => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'tooltip_style',
					'description'       => esc_html__( 'Adjust color, gradient, and image to customize the background style of the tooltip.' ),
				),
				'__advanced_tooltip_data' => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'DIPL_AdvancedTooltip', 'get_computed_advanced_tooltip_data' ),
					'computed_depends_on' => array(
						'tooltip_content_type',
						'tooltip_lib_layout',
					)
				)
			),
			$this->generate_background_options( 'tooltip_background', 'skip', 'general', 'background', 'tooltip_background_color' ),
		);
	}

	public static function get_computed_advanced_tooltip_data( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'tooltip_content_type' => 'text',
			'tooltip_lib_layout'   => '0',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		// If not divi layout.
		$content = '';
		if ( 'layout' === $tooltip_content_type ) {
			$content = self::get_library_layout( array(
				'layout_id' => intval( $tooltip_lib_layout ),
			) );
		}

		return $content;
	}

	public function before_render() {
		// Get and set responsive values.
		$this->props['tooltip_placement']        = ! empty( $this->props['tooltip_placement'] ) ? $this->props['tooltip_placement'] : 'auto';
		$this->props['tooltip_placement_tablet'] = ! empty( $this->props['tooltip_placement_tablet'] ) ? $this->props['tooltip_placement_tablet'] : $this->props['tooltip_placement'];
		$this->props['tooltip_placement_phone']  = ! empty( $this->props['tooltip_placement_phone'] ) ? $this->props['tooltip_placement_phone'] : $this->props['tooltip_placement_tablet'];
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$multi_view            = et_pb_multi_view_options( $this );
		$order_class           = $this->get_module_order_class( $render_slug );
		$tp_theme_class        = '.' . esc_attr( $order_class ) . '_0';

		// Get the props.
		$trigger_element       = sanitize_text_field( $this->props['trigger_element'] ) ?? 'button';
		$trigger_button_text   = sanitize_text_field( $this->props['trigger_button_text'] ) ?? esc_html__( 'Click here', 'divi-plus' );
		$custom_trigger_button = sanitize_text_field( $this->props['custom_trigger_button'] );
		$trigger_button_icon   = sanitize_text_field( $this->props['trigger_button_icon'] );

		$tooltip_content_type  = sanitize_text_field( $this->props['tooltip_content_type'] ) ?? 'text';
		$tooltip_trigger_type  = esc_attr( $this->props['tooltip_trigger_type'] ) ?? 'mouseenter';
		$entrance_animation    = esc_attr( $this->props['tooltip_entrance_animation'] );
		$animation_durartion   = intval( esc_attr( $this->props['tooltip_animation_durartion'] ) );
		$interactive           = ( 'on' === esc_attr( $this->props['tooltip_interactive'] ) ) ? 'true' : 'false';
		$tooltip_width         = sanitize_text_field( $this->props['tooltip_width'] ) ?? '350';

		$tooltip_placement     = et_pb_responsive_options()->get_property_values( $this->props, 'tooltip_placement' );

		// Get and set responsive values.
		$tooltip_placement['desktop'] = ! empty( $tooltip_placement['desktop'] ) ? $tooltip_placement['desktop'] : 'auto';
		$tooltip_placement['tablet']  = ! empty( $tooltip_placement['tablet'] ) ? $tooltip_placement['tablet'] : $tooltip_placement['desktop'];
		$tooltip_placement['phone']   = ! empty( $tooltip_placement['phone'] ) ? $tooltip_placement['phone'] : $tooltip_placement['tablet'];

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-advanced-tooltip-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AdvancedTooltip/' . $file . '.min.css', array(), '1.0.0' );

		self::$rendering = true;

		// Get render element.
		$render_element = '';
		if ( 'button' === $trigger_element ) {
			$button_url = '#';
			$button_new_window = 'off';
			if ( 'mouseenter' === $tooltip_trigger_type && ! empty( $this->props['trigger_button_url'] ) ) {
				$button_url        = esc_url( $this->props['trigger_button_url'] ) ?? $button_url;
				$button_new_window = esc_attr( $this->props['trigger_button_new_window'] ) ?? $button_new_window;
			}

			$render_element = $this->render_button( array(
				'button_text'         => esc_html( $trigger_button_text ),
				'button_text_escaped' => true,
				'button_url'          => $button_url,
				'button_classname'    => array( 'dipl_tooltip_trigger_element dipl_tooltip_trigger_button' ),
				'url_new_window'      => esc_attr( $button_new_window ),
				'button_custom'       => ! empty( $custom_trigger_button ) ? $custom_trigger_button : 'off',
				'custom_icon'         => $trigger_button_icon,
				'has_wrapper'         => true,
				'button_rel'          => esc_html( $this->props['trigger_button_rel'] ),
			) );
		} elseif ( 'image' === $trigger_element ) {
			$render_element = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{trigger_image}}',
					'alt'   => '{{trigger_image_alt}}',
					'class' => 'dipl_tooltip_trigger_element dipl_tooltip_trigger_image',
				),
				'required' => 'trigger_image',
			) );
		} elseif ( 'icon' === $trigger_element ) {
			$render_element = $multi_view->render_element( array(
				'tag'      => 'span',
				'content'  => '{{trigger_icon}}',
				'attrs'    => array(
					'class' => 'dipl_tooltip_trigger_element dipl_tooltip_trigger_icon et-pb-icon',
				),
				'required' => 'trigger_icon',
			) );
		} elseif ( 'text' === $trigger_element ) {
			$render_element = $multi_view->render_element( array(
				'tag'      => 'span',
				'content'  => '{{trigger_text}}',
				'attrs'    => array(
					'class' => 'dipl_tooltip_trigger_element dipl_tooltip_trigger_text',
				),
				'required' => 'trigger_text',
			) );
		}

		// Get render tooltip content.
		$tooltip_content = '';
		if ( 'text' === $tooltip_content_type ) {
			$tooltip_content = $multi_view->render_element( array(
				'tag'     => 'div',
				'content' => '{{content}}',
				'attrs'   => array(
					'class' => 'dipl_tooltip_content_text',
				),
				'required' => 'content',
			) );
		} elseif ( 'image' === $tooltip_content_type ) {
			$tooltip_content = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{tooltip_image}}',
					'alt'   => '{{tooltip_image_alt}}',
					'class' => 'dipl_tooltip_content_image',
				),
				'required' => 'tooltip_image',
			) );
		} elseif ( 'layout' === $tooltip_content_type ) {
			$tooltip_content = self::get_library_layout( array(
				'layout_id' => intval( $this->props['tooltip_lib_layout'] ),
			) );
		}

		// Final output.
		$render_output = '';
		if ( ! empty( $render_element ) || ( 'id' === $trigger_element || 'class' === $trigger_element ) ) {
			$render_output = $multi_view->render_element( array(
				'tag'     => 'div',
				'content' => et_core_esc_previously( $render_element ),
				'attrs'   => array(
					'class'                  => 'dipl_tooltip_trigger_element_wrap trigger_type_' . esc_attr( $trigger_element ),
					'data-trigger-action'    => esc_attr( $tooltip_trigger_type ),
					'data-animation'         => esc_attr( $entrance_animation ),
					'data-durartion'         => esc_attr( $animation_durartion ),
					'data-interactive'       => esc_attr( $interactive ),
					'data-tooltip-width'     => intval( $tooltip_width ),
					'data-trigger-element'   => esc_attr( $trigger_element ),
					'data-trigger-selector'  => esc_attr( $this->props['trigger_selector'] ),
					'data-placement-desktop' => esc_attr( $tooltip_placement['desktop'] ),
					'data-placement-tablet'  => esc_attr( $tooltip_placement['tablet'] ),
					'data-placement-phone'   => esc_attr( $tooltip_placement['phone'] ),
				),
			) );
		}

		// Add tooltip content.
		if ( ! empty( $tooltip_content ) ) {
			if ( ! empty( $entrance_animation ) && 'fade' !== $entrance_animation ) {
				wp_enqueue_style( 'dipl-tippy-animation-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/assets/css/tippy-animations/' . $entrance_animation . '.css', array(), '6.3.7' );
			}

			wp_enqueue_script( 'elicus-tippy-script' );
			wp_enqueue_script( 'dipl-advanced-tooltip-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AdvancedTooltip/dipl-advanced-tooltip-custom.min.js', array('jquery'), '1.0.0', true );

			// Add tooltip content.
			$render_output .= sprintf(
				'<div class="dipl_advanced_tooltip_content_wrap" id="%1$s_content_wrap" style="display:none;">
					<div class="dipl_advanced_tooltip_content %1$s_content">%2$s</div>
				</div>',
				esc_attr( $order_class ),
				et_core_esc_previously( $tooltip_content )
			);
		}

		// Trigger element styles.
		if ( 'icon' === $trigger_element ) {
			// Icon font family and weight.
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'trigger_icon',
					'important'      => true,
					'selector'       => '%%order_class%% .dipl_tooltip_trigger_icon',
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				) );
			}

			
			$trigger_icon_align = et_pb_responsive_options()->get_property_values( $this->props, 'trigger_icon_align' );
			if ( ! empty( array_filter( $trigger_icon_align ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $trigger_icon_align, '%%order_class%% .dipl_tooltip_trigger_element_wrap.trigger_type_icon', 'text-align', $render_slug, '!important;', '' );
			}
			$trigger_icon_fontsize = et_pb_responsive_options()->get_property_values( $this->props, 'trigger_icon_fontsize' );
			if ( ! empty( array_filter( $trigger_icon_fontsize ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $trigger_icon_fontsize, '%%order_class%% .dipl_tooltip_trigger_icon', 'font-size', $render_slug, '!important;', 'range' );
			}
			$this->generate_styles( array(
				'base_attr_name' => 'trigger_icon_color',
				'selector'       => '%%order_class%% .dipl_tooltip_trigger_icon',
				'hover_selector' => '%%order_class%% .dipl_tooltip_trigger_icon:hover',
				'important'      => true,
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		} elseif ( 'image' === $trigger_element ) {
			$image_icon_align = et_pb_responsive_options()->get_property_values( $this->props, 'image_icon_align' );
			if ( ! empty( array_filter( $image_icon_align ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $image_icon_align, '%%order_class%% .dipl_tooltip_trigger_element_wrap.trigger_type_image', 'text-align', $render_slug, '!important;', '' );
			}
		}

		// Content selector.
		$tp_box_class = ".tippy-box[data-theme=\"{$tp_theme_class}\"]";

		// Default style.
		self::set_style( $render_slug, array(
			'selector'    => "{$tp_box_class}",
			'declaration' => 'border: 0 solid #000000;',
		) );

		// Show speach bubble.
		if ( 'on' === $this->props['tooltip_show_speech_bubble'] ) {
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}",
				'declaration' => 'overflow: visible !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}:before",
				'declaration' => 'content: "" !important; overflow: visible !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='top']",
				'declaration' => 'margin-bottom: 15px !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='bottom']",
				'declaration' => 'margin-top: 15px !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='right']",
				'declaration' => 'margin-left: 15px !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => "{$tp_box_class}[data-placement^='left']",
				'declaration' => 'margin-right: 15px !important;',
			) );
		}

		$args = array(
			'render_slug' => $render_slug,
			'props'		  => $this->props,
			'fields'	  => $this->fields_unprocessed,
			'module'      => $this,
			'backgrounds' => array(
				'tooltip_background' => array(
					'normal' => ".tippy-box[data-theme~='" . esc_attr( $tp_theme_class ) . "']",
					'hover' => ".tippy-box[data-theme~='" . esc_attr( $tp_theme_class ) . "']:hover",
				),
			),
		);
		DiviPlusHelper::process_background( $args );

		$fields = array( 'advanced_tooltip_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		self::$rendering = false;
		return $render_output;
	}

	public static function get_library_layout( $args = array() ) {
		$defaults = array(
			'layout_id' => 0,
		);
		$args = wp_parse_args( $args, $defaults );
		if ( 0 === intval( $args['layout_id'] ) ) {
			return '';
		}

		$layout = do_shortcode( get_the_content( null, false, intval( $args['layout_id'] ) ) );

		return $layout;
	}

	/**
	 * Filter multi view value.
	 *
	 * @since 3.27.1
	 *
	 * @see ET_Builder_Module_Helper_MultiViewOptions::filter_value
	 *
	 * @param mixed $raw_value Props raw value.
	 * @param array $args {
	 *     Context data.
	 *
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
		$mode = isset( $args['mode'] ) ? $args['mode'] : '';
		if ( $raw_value && 'trigger_icon' === $name ) {
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
	if ( in_array( 'dipl_advanced_tooltip', $modules ) ) {
		new DIPL_AdvancedTooltip();
	}
} else {
	new DIPL_AdvancedTooltip();
}
