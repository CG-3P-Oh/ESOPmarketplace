<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2023 Elicus Technologies Private Limited
 * @version     1.9.11
 */
class DIPL_AudioPlayer extends ET_Builder_Module {

	public $slug       = 'dipl_audio_player';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name = esc_html__( 'DP Audio Player', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => array(
						'title' => esc_html__( 'Content', 'divi-plus' ),
					),
					'audio' => array(
						'title' => esc_html__( 'Audio', 'divi-plus' ),
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'divi-plus' ),
					),
					'player' => array(    
						'title' => esc_html__( 'Player Settings', 'divi-plus' ),
					),
				),
			),
			'advanced'   => array(
				'toggles' => array(
					'player' => array(
						'title' => esc_html__( 'Player', 'divi-plus' ),
					),
					'title' => array(
						'title' => esc_html__( 'Ttitle', 'divi-plus' ),
					),
					'artist' => array(
						'title' => esc_html__( 'Artist', 'divi-plus' ),
					),
					'play_btn' => array(
						'title' => esc_html__( 'Play/Pause Button', 'divi-plus' ),
					),
					'current_time_text' => array(
						'title' => esc_html__( 'Current Time', 'divi-plus' ),
					),
					'duration_text' => array(
						'title' => esc_html__( 'Duration', 'divi-plus' ),
					),
					'seek_bar' => array(
						'title' => esc_html__( 'Seek Bar', 'divi-plus' ),
					),
					'volume_btn' => array(
						'title' => esc_html__( 'Volume Button', 'divi-plus' ),
					),
					'volume_bar' => array(
						'title' => esc_html__( 'Volume Bar', 'divi-plus' ),
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'title' => array(
					'label'          => esc_html__( 'Title', 'divi-plus' ),
					'font_size'      => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'header_level'   => array(
						'default'     => 'h2',
						'label'       => esc_html__( 'Title Heading Tag', 'divi-plus' ),
						'description' => esc_html__( 'Select which HTML tag should be used for the title text.', 'divi-plus' ),
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl-audio-card .dipl-audio-title",
					),
					'tab_slug'	=> 'advanced',
                    'toggle_slug' => 'title',
				),
				'artist' => array(
					'label'          => esc_html__( 'Artist', 'divi-plus' ),
					'font_size'      => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl-audio-card .dipl-audio-author",
					),
					'tab_slug'	=> 'advanced',
                    'toggle_slug' => 'artist',
				),
				'current_time' => array(
					'label'          => esc_html__( 'Current Time', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl-audio-card .jp-current-time",
					),
					'tab_slug'	=> 'advanced',
                    'toggle_slug' => 'current_time_text',
				),
				'duration' => array(
					'label'          => esc_html__( 'Duration', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1em',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
							'step' => '0.1',
						),
					),
					'letter_spacing' => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl-audio-card .jp-duration",
					),
					'tab_slug'	=> 'advanced',
                    'toggle_slug' => 'duration_text',
				)
			),
			'advanced_player_spacing' => array(
				'player' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl-audio-card",
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
			'max_width' => array(
				'css' => array(
					'main'             => '%%order_class%%',
					'module_alignment' => '%%order_class%%',
				),
			),
			'borders' => array(
				'player' => array(
					'label_prefix' => esc_html__( 'Player', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl-audio-card",
							'border_styles' => "%%order_class%% .dipl-audio-card",
						),
						'important' => 'all',
					),
	                'defaults' => array(
	                    'border_radii'  => 'on|16px|16px|16px|16px',
	                ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'player',
				),
				'play_btn' => array(
					'label_prefix' => esc_html__( 'Play/Pause Button', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .jp-play, {$this->main_css_element} .jp-pause",
							'border_styles' => "{$this->main_css_element} .jp-play, {$this->main_css_element} .jp-pause",
						),
						'important' => 'all',
					),
					'defaults' => array(
	                    'border_styles' => array(
	                        'width' => '2px',
	                        'style' => 'solid',
	                        'color' => '#6c8faf',
	                    ),
	                ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'play_btn'
				),
				'volume_btn' => array(
					'label_prefix' => esc_html__( 'Volume Button', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .jp-mute, {$this->main_css_element} .jp-unmute",
							'border_styles' => "{$this->main_css_element} .jp-mute, {$this->main_css_element} .jp-unmute",
						),
						'important' => 'all',
					),
					'defaults' => array(
	                    'border_styles' => array(
	                        'width' => '2px',
	                        'style' => 'solid',
	                        'color' => '#6c8faf',
	                    ),
	                ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'volume_btn'
				),
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '%%order_class%%',
							'border_radii'  => '%%order_class%%',
						),
					),
				),
			),
			'box_shadow' => array(
				'player' => array(
					'label'       => esc_html__( 'Player Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => "{$this->main_css_element} .dipl-audio-card",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug'  => 'player'
				),
				'play_btn' => array(
					'label'       => esc_html__( 'Play/Pause Button Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => "{$this->main_css_element} .jp-play, {$this->main_css_element} .jp-pause",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug'  => 'play_btn'
				),
				'volume_btn' => array(
					'label'       => esc_html__( 'Volume Button Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => "{$this->main_css_element} .jp-mute, {$this->main_css_element} .jp-unmute",
					),
					'tab_slug'    => 'advanced',
					'toggle_slug'  => 'volume_btn'
				),
				'default' => array(
					'css' => array(
						'main' => $this->main_css_element,
						'important' => 'all',
					),
				),
			),
			'background' => array(
				'use_background_video' => false,
				'options' => array(
					'parallax' => array( 'type' => 'skip' ),
				),
			),
			'text' => false,
			'filters' => false,
		);
	}

	public function get_fields() {
		$accent_color = et_get_option( 'accent_color', '#2ea3f2' );
		return array_merge(
			array(
				'audio_src' => array(
					'label'           => esc_html__( 'Audio File', 'my-divi-modules' ),
					'type'            => 'upload',
					'data_type'       => 'audio',
					'upload_button_text' => esc_attr__( 'Upload an audio file', 'divi-plus' ),
					'choose_text'        => esc_attr__( 'Choose an Audio file', 'divi-plus' ),
					'update_text'        => esc_attr__( 'Set As Audio for the module', 'divi-plus' ),
					'option_category' => 'basic_option',
					'tab_slug'        => 'general',
					'description'     => esc_html__( 'Upload an audio file or enter a URL.', 'divi-plus' ),
					'toggle_slug'     => 'audio',
				),
				'title'             => array(
					'label'           => esc_html__( 'Title', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'default'         => 'Your Title Goes Here',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the audio Title.', 'divi-plus' ),
				),
				'artist'             => array(
					'label'           => esc_html__( 'Artist', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'default'         => 'By: Artist Name',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can input the audio artist name.', 'divi-plus' ),
				),
				'image' => array(
					'label'                 => esc_html__( 'Image', 'divi-plus' ),
					'type'                  => 'upload',
					'option_category'       => 'basic_option',
					'upload_button_text'    => esc_attr__( 'Upload an image', 'divi-plus' ),
					'choose_text'           => esc_attr__( 'Choose an Image', 'divi-plus' ),
					'update_text'           => esc_attr__( 'Set As Image', 'divi-plus' ),
					'dynamic_content'  		=> 'image',
					'tab_slug'              => 'general',
					'toggle_slug'           => 'image',
					'description'           => esc_html__( 'Upload an image to display at the side of audio player.', 'divi-plus' ),
				),
				'image_alt' => array(
					'label'                 => esc_html__( 'Image Alt Text', 'divi-plus' ),
					'type'                  => 'text',
					'option_category'       => 'basic_option',
					'tab_slug'              => 'general',
					'toggle_slug'           => 'image',
					'description'           => esc_html__( 'Here you can input the text to be used for the image as HTML ALT text.', 'divi-plus' ),
				),
				'player_layout' => array(
					'label'            => esc_html__( 'Layout', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'basic_option',
					'options'           => array(
                        'layout1'   => esc_html__( 'Layout 1', 'divi-plus' ),
                        'layout2'   => esc_html__( 'Layout 2', 'divi-plus' ),
                    ),
                    'default'           => 'layout1',
                    'default_on_front'  => 'layout1',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'player',
					'description'      => esc_html__( 'Here you can select the layout for your player.', 'divi-plus' ),
					'computed_affects' 	=> array(
						'__audio_data',
					),
				),
				'show_seek_bar' => array(
					'label'            => esc_html__( 'Seek Bar', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'basic_option',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default' 			=> 'on',
					'tab_slug'          => 'general',
					'toggle_slug'      	=> 'player',
					'description'      	=> esc_html__( 'Whether or not to show the seek bar.', 'divi-plus' ),
					'computed_affects' 	=> array(
						'__audio_data',
					),
				),
				'show_current_time' => array(
					'label'            => esc_html__( 'Current Time', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'basic_option',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if'         	=> array( 'player_layout' => 'layout1' ),
					'default' 			=> 'on',
					'tab_slug'          => 'general',
					'toggle_slug'      	=> 'player',
					'description'      	=> esc_html__( 'Whether or not to show the current time.', 'divi-plus' ),
					'computed_affects' 	=> array(
						'__audio_data',
					),
				),
				'show_duration' => array(
					'label'            => esc_html__( 'Duration', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'basic_option',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if'         	=> array( 'player_layout' => 'layout1' ),
					'default' 			=> 'on',
					'tab_slug'          => 'general',
					'toggle_slug'      	=> 'player',
					'description'      	=> esc_html__( 'Whether or not to show the duration.', 'divi-plus' ),
					'computed_affects' 	=> array(
						'__audio_data',
					),
				),
				'show_mute_unmute' => array(
					'label'            => esc_html__( 'Volume Mute/Unmute', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'basic_option',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default' 			=> 'on',
					'tab_slug'          => 'general',
					'toggle_slug'      	=> 'player',
					'description'      	=> esc_html__( 'Whether or not to show the volume mute/unmute.', 'divi-plus' ),
					'computed_affects' 	=> array(
						'__audio_data',
					),
				),
				'show_volume_bar' => array(
					'label'            => esc_html__( 'Volume Bar', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'basic_option',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if'         	=> array( 'player_layout' => 'layout1' ),
					'default' 			=> 'on',
					'tab_slug'          => 'general',
					'toggle_slug'      	=> 'player',
					'description'      	=> esc_html__( 'Whether or not to show the volume bar.', 'divi-plus' ),
					'computed_affects' 	=> array(
						'__audio_data',
					),
				),
				'player_height' => array(
					'label'                 => esc_html__( 'Player Height', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'button',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '1000',
						'step'  => '1',
					),
					'default_unit'     => 'px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'player',
					'description'           => esc_html__( 'Increase or decrease the height of the player.', 'divi-plus' ),
				),
				'player_bg_color' => array(
	                'label'             => esc_html__( 'Player Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'player_bg',
	                'context'           => 'player_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'player_bg', 'button', 'advanced', 'player', 'player_bg_color' ),
	                'show_if'         	=> array( 'player_layout' => 'layout1' ),
	                'hover'             => 'tabs',
	                'default' 			=> et_builder_accent_color(),
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'player',
	                'description'       => esc_html__( 'Here you can adjust the background style of the player by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'player_custom_padding' => array(
					'label'            => esc_html__( 'Player Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'player',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' )
				),
				'play_btn_icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'hover'		   => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'play_btn',
					'description'  => esc_html__( 'Here you can define a custom color for the button icon.', 'divi-plus' ),
				),
				'play_btn_icon_size' => array(
					'label'                 => esc_html__( 'Icon Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'default'				=> '20px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'play_btn',
					'description'           => esc_html__( 'Increase or decrease the size of the play/pause button icon.', 'divi-plus' ),
				),
				'play_btn_size' => array(
					'label'                 => esc_html__( 'Play/Pause Button Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'font_option',
					'range_settings'        => array(
						'min'   => '1',
						'max'   => '120',
						'step'  => '1',
					),
					'mobile_options'        => true,
					'default'               => '50px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'play_btn',
				),
				'play_btn_bg_color' => array(
	                'label'             => esc_html__( 'Play/Pause Button Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'play_btn_bg',
	                'context'           => 'play_btn_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'play_btn_bg', 'button', 'advanced', 'play_btn', 'play_btn_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'play_btn',
	                'description'       => esc_html__( 'Here you can adjust the background style of the play/pause button by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'seek_bar_color' => array(
					'label'        => esc_html__( 'Bar Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'seek_bar',
					'description'  => esc_html__( 'Here you can define a custom color for the seek bar.', 'divi-plus' ),
				),
				'active_seek_bar_color' => array(
					'label'        => esc_html__( 'Active Bar Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'seek_bar',
					'description'  => esc_html__( 'Here you can define a custom color for the active seek bar.', 'divi-plus' ),
				),
	            'seek_bar_height' => array(
					'label'                 => esc_html__( 'Seek Bar Height', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '1000',
						'step'  => '1',
					),
					'default'				=> '8px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'seek_bar',
					'description'           => esc_html__( 'Increase or decrease the height of the Seek Bar.', 'divi-plus' ),
				),
				'volume_btn_icon_color' => array(
					'label'        => esc_html__( 'Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'hover'		   => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'volume_btn',
					'description'  => esc_html__( 'Here you can define a custom color for the button icon.', 'divi-plus' ),
				),
				'volume_btn_icon_size' => array(
					'label'                 => esc_html__( 'Icon Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'default'				=> '20px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'volume_btn',
					'description'           => esc_html__( 'Increase or decrease the size of the button icon.', 'divi-plus' ),
				),
				'volume_btn_size' => array(
					'label'                 => esc_html__( 'Volume Button Size', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'font_option',
					'range_settings'        => array(
						'min'   => '1',
						'max'   => '120',
						'step'  => '1',
					),
					'mobile_options'        => true,
					'default'               => '50px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'volume_btn',
				),
				'volume_btn_bg_color' => array(
	                'label'             => esc_html__( 'Volume Button Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'volume_btn_bg',
	                'context'           => 'volume_btn_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'volume_btn_bg', 'button', 'advanced', 'volume_btn', 'volume_btn_bg_color' ),
	                'hover'             => 'tabs',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'volume_btn',
	                'description'       => esc_html__( 'Here you can adjust the background style of the volume button by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
	            'volume_bar_color' => array(
					'label'        => esc_html__( 'Bar Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'volume_bar',
					'description'  => esc_html__( 'Here you can define a custom color for the volume bar.', 'divi-plus' ),
				),
				'active_volume_bar_color' => array(
					'label'        => esc_html__( 'Active Bar Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'volume_bar',
					'description'  => esc_html__( 'Here you can define a custom color for the active volume bar.', 'divi-plus' ),
				),
	            'volume_bar_height' => array(
					'label'                 => esc_html__( 'Volume Bar Height', 'divi-plus' ),
					'type'                  => 'range',
					'option_category'       => 'layout',
					'mobile_options'        => true,
					'validate_unit'         => true,
					'allow_empty'           => true,
					'range_settings'        => array(
						'min'   => '0',
						'max'   => '1000',
						'step'  => '1',
					),
					'default'				=> '6px',
					'tab_slug'              => 'advanced',
					'toggle_slug'           => 'volume_bar',
					'description'           => esc_html__( 'Increase or decrease the height of the volume Bar.', 'divi-plus' ),
				),
				'__audio_data' => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'DIPL_AudioPlayer', 'get_audio_player' ),
					'computed_depends_on' => array(
						'player_layout',
						'audio_src',
						'show_seek_bar',
						'show_current_time',
						'show_duration',
						'show_mute_unmute',
						'show_volume_bar'
					),
				),
			),
			$this->generate_background_options( 'player_bg', 'skip', 'advanced', 'player', 'player_bg_color' ),
			$this->generate_background_options( 'play_btn_bg', 'skip', 'advanced', 'play_btn', 'play_btn_bg_color' ),
			$this->generate_background_options( 'volume_btn_bg', 'skip', 'advanced', 'volume_btn', 'volume_btn_bg_color' )
		);

	}

	public static function get_audio_player( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		return '';
	}

	public function render( $attrs, $content, $render_slug ) {

		$props = $this->props;
		$utils = et_pb_responsive_options();

		$player_id    = 'jquery_jplayer_' . wp_rand( 1000, 9999 );
		$container_id = 'jp_container_' . wp_rand( 1000, 9999 );

		wp_enqueue_style( 'elicus-jquery-ui-style' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'elicus-jplayer-script' );
		
		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-audio-player-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AudioPlayer/' . $file . '.min.css', array(), '1.19.0' );

		//PLAYER HEIGHT
		$player_height = et_pb_responsive_options()->get_property_values( $props, 'player_height' );
		et_pb_responsive_options()->generate_responsive_css(
			$player_height,
			"%%order_class%% .dipl-audio-card",
			'height',
			$render_slug,
			'!important;'
		);

		//PLAY BUTTON ICON SIZE
		$play_btn_icon_size = et_pb_responsive_options()->get_property_values( $props, 'play_btn_icon_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$play_btn_icon_size,
			"%%order_class%% .jp-play .et-pb-icon, %%order_class%% .jp-pause .et-pb-icon",
			'font-size',
			$render_slug,
			'!important;'
		);

		//PLAY BUTTON SIZE
		$play_btn_size = et_pb_responsive_options()->get_property_values( $props, 'play_btn_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$play_btn_size,
			"%%order_class%% .jp-play, %%order_class%% .jp-pause",
			array(
				'width' ,
				'height'
			),
			$render_slug,
			'!important;'
		);

		//PLAY BUTTON ICON COLOR
		if ( '' !== $props['play_btn_icon_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .jp-play .et-pb-icon, %%order_class%% .jp-pause .et-pb-icon',
				'declaration' => sprintf( 'color: %1$s;', esc_attr( $props['play_btn_icon_color'] ) ),
			) );
		}

		//SEEK BAR COLORS
		if ( '' !== $props['seek_bar_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-audio-card .jp-progress',
				'declaration' => sprintf( 'background: %1$s;', esc_attr( $props['seek_bar_color'] ) ),
			) );
		}
		if ( '' !== $props['active_seek_bar_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-audio-card .jp-play-bar',
				'declaration' => sprintf( 'background: %1$s;', esc_attr( $props['active_seek_bar_color'] ) ),
			) );
		}

		//SEEK BAR HEIGHT
		$seek_bar_height = et_pb_responsive_options()->get_property_values( $props, 'seek_bar_height' );
		et_pb_responsive_options()->generate_responsive_css(
			$seek_bar_height,
			"%%order_class%% .jp-progress, %%order_class%% .jp-play-bar",
			'height',
			$render_slug,
			'!important;'
		);

		//VOLUME BUTTON ICON SIZE
		$volume_btn_icon_size = et_pb_responsive_options()->get_property_values( $props, 'volume_btn_icon_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$volume_btn_icon_size,
			"%%order_class%% .jp-mute .et-pb-icon, %%order_class%% .jp-unmute .et-pb-icon",
			'font-size',
			$render_slug,
			'!important;'
		);

		//VOLUME BUTTON SIZE
		$volume_btn_size = et_pb_responsive_options()->get_property_values( $props, 'volume_btn_size' );
		et_pb_responsive_options()->generate_responsive_css(
			$volume_btn_size,
			"%%order_class%% .jp-mute, %%order_class%% .jp-unmute",
			array(
				'width',
				'height'
			),
			$render_slug,
			'!important;'
		);

		//VOLUME BUTTON ICON COLOR
		if ( '' !== $props['volume_btn_icon_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .jp-mute .et-pb-icon, %%order_class%% .jp-unmute .et-pb-icon',
				'declaration' => sprintf( 'color: %1$s;', esc_attr( $props['volume_btn_icon_color'] ) ),
			) );
		}

		//VOLUME BAR COLORS
		if ( '' !== $props['volume_bar_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-audio-card .jp-volume-bar',
				'declaration' => sprintf( 'background: %1$s;', esc_attr( $props['volume_bar_color'] ) ),
			) );
		}
		if ( '' !== $props['active_volume_bar_color'] ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl-audio-card .jp-volume-bar-value',
				'declaration' => sprintf( 'background: %1$s;', esc_attr( $props['active_volume_bar_color'] ) ),
			) );
		}

		//VOLUME BAR HEIGHT
		$volume_bar_height = et_pb_responsive_options()->get_property_values( $props, 'volume_bar_height' );
		et_pb_responsive_options()->generate_responsive_css(
			$volume_bar_height,
			"%%order_class%% .jp-volume-bar, %%order_class%% .jp-volume-bar-value",
			'height',
			$render_slug,
			'!important;'
		);

		$this->add_force_border_radii(
			'_player',
			array(
				'normal' => '%%order_class%% .dipl-audio-card',
				'hover'  => '%%order_class%% .dipl-audio-card:hover',
			),
			$render_slug
		);

		$args = array(
			'render_slug'	=> $render_slug,
			'props'			=> $this->props,
			'fields'		=> $this->fields_unprocessed,
			'module'		=> $this,
			'backgrounds' 	=> array(
				'player_bg' => array(
					'normal' => "{$this->main_css_element} .dipl-audio-card",
					'hover' => "{$this->main_css_element} .dipl-audio-card:hover",
	 			),
				'play_btn_bg' => array(
					'normal' => "{$this->main_css_element} .jp-play, {$this->main_css_element} .jp-pause",
					'hover' => "{$this->main_css_element} .jp-play:hover, {$this->main_css_element} .jp-pause:hover",
	 			),
	 			'volume_btn_bg' => array(
					'normal' => "{$this->main_css_element} .jp-mute, {$this->main_css_element} .jp-unmute",
					'hover' => "{$this->main_css_element} .jp-mute:hover, {$this->main_css_element} .jp-unmute:hover",
	 			),
			),
		);
		DiviPlusHelper::process_background( $args );
		$fields = array( 'advanced_player_spacing' );
		DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		wp_enqueue_script( 'dipl-filterable-gallery-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/AudioPlayer/dipl-audio-player-custom.min.js', array('jquery'), '1.18.0', true );

		$title     		= $this->props['title'];
		$title_output 	= '';
		$title_level      = et_pb_process_header_level( $this->props['title_level'], 'h2' );

		if ( $title ) {
			$title_output .= sprintf(
				'<%1$s class="dipl-audio-title">%2$s</%1$s>',
				esc_html( $title_level ),
				et_core_esc_previously( $title )
			);
		}

		if ( 'layout1' === $props['player_layout'] ) {
			ob_start();
			?>
			<div class="dipl-audio-card layout1" 
				data-audio-src="<?php echo esc_url( $props['audio_src'] ); ?>" 
				data-player-id="<?php echo esc_attr( $player_id ); ?>"
				data-container-id="<?php echo esc_attr( $container_id ); ?>"
			>
				<div class="dipl-audio-info">
					<?php echo et_core_intentionally_unescaped( $title_output, 'html' ); ?>

					<?php if ( '' !== $props['artist'] ) : ?>
						<p class="dipl-audio-author"><?php echo esc_html( $props['artist'] ); ?></p>
					<?php endif; ?>

					<?php if ( '' !== $props['audio_src'] ) : ?>
						<div id="<?php echo esc_attr( $player_id ); ?>" class="jp-jplayer"></div>

						<div
							id="<?php echo esc_attr( $container_id ); ?>"
							class="jp-audio"
							role="application"
							aria-label="media player"
						>
							<div class="jp-controls">
								<button class="jp-play et_pb_icon_wrap" role="button" tabindex="0">
									<span class="et-pb-icon et-pb-fa-icon et-pb-black-icon"></span>
								</button>
								<button class="jp-pause" role="button" tabindex="0">
									<span class="et-pb-icon et-pb-fa-icon et-pb-black-icon"></span>
								</button>

								<?php if ( 'on' === $props['show_current_time'] ) : ?>
									<div class="jp-current-time"></div>
								<?php endif; ?>

								<?php if ( 'on' === $props['show_seek_bar'] ) : ?>
									<div class="jp-progress">
										<div class="jp-seek-bar">
											<div class="jp-play-bar"></div>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( 'on' === $props['show_duration'] ) : ?>
									<div class="jp-duration"></div>
								<?php endif; ?>

								<?php if ( 'on' === $props['show_mute_unmute'] ) : ?>
									<button class="jp-mute" role="button" tabindex="0">
										<span class="et-pb-icon et-pb-normal-icon"></span>
									</button>
									<button class="jp-unmute" role="button" tabindex="0">
										<span class="et-pb-icon et-pb-normal-icon"></span>
									</button>
								<?php endif; ?>

								<?php if ( 'on' === $props['show_volume_bar'] ) : ?>
									<div class="jp-volume-bar">
										<div class="jp-volume-bar-value"></div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( '' !== $props['image'] ) : ?>
					<div class="dipl-audio-image">
						<img src="<?php echo esc_url( $props['image'] ); ?>" alt="<?php echo esc_attr( $props['image_alt'] ); ?>" />
					</div>
				<?php endif; ?>
			</div>
			<?php

			$output = ob_get_clean();
		}

		if ( 'layout2' === $props['player_layout'] ) {

			$placeholder_bg = ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/assets/music-background.jpg';
			$background_image = ( '' !== $props['image'] ) ? $props['image'] : $placeholder_bg;

			ob_start();
			?>
			<div class="dipl-audio-card layout2" 
				data-audio-src="<?php echo esc_url( $props['audio_src'] ); ?>" 
				data-player-id="<?php echo esc_attr( $player_id ); ?>"
				data-container-id="<?php echo esc_attr( $container_id ); ?>"
			>
				<div class="dipl-audio-bg" style="background-image: url(<?php echo esc_url( $background_image ); ?>);"></div>

				<div class="dipl-audio-overlay">
					<?php echo et_core_intentionally_unescaped( $title_output, 'html' ); ?>

					<?php if ( '' !== $props['artist'] ) : ?>
						<p class="dipl-audio-author"><?php echo esc_html( $props['artist'] ); ?></p>
					<?php endif; ?>

					<div class="dipl-audio-center">
						<?php if ( '' !== $props['audio_src'] ) : ?>

							<div id="<?php echo esc_attr( $player_id ); ?>" class="jp-jplayer"></div>
							<div
								id="<?php echo esc_attr( $container_id ); ?>"
								class="jp-audio"
								role="application"
								aria-label="media player"
							>
								<div class="jp-controls">
									<button class="jp-play et_pb_icon_wrap" role="button" tabindex="0">
										<span class="et-pb-icon et-pb-fa-icon et-pb-black-icon"></span>
									</button>
									<button class="jp-pause" role="button" tabindex="0">
										<span class="et-pb-icon et-pb-fa-icon et-pb-black-icon"></span>
									</button>
									<?php if ( 'on' === $props['show_seek_bar'] ) : ?>
										<div class="jp-progress">
											<div class="jp-seek-bar">
												<div class="jp-play-bar"></div>
											</div>
										</div>
									<?php endif; ?>

									<?php if ( 'on' === $props['show_mute_unmute'] ) : ?>
										<button class="jp-mute" role="button" tabindex="0">
											<span class="et-pb-icon et-pb-normal-icon"></span>
										</button>
										<button class="jp-unmute" role="button" tabindex="0">
											<span class="et-pb-icon et-pb-normal-icon"></span>
										</button>
									<?php endif; ?>
								</div>
							</div>

						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php

			$output = ob_get_clean();
		}

		return et_core_intentionally_unescaped( $output, 'html' );
	}

	public function dipl_builder_processed_range_value( $result, $range, $range_string ) {
		if ( false !== strpos( $result, '0calc' ) ) {
			return $range;
		}
		return $result;
	}

	public function add_force_border_radii( $toggle_name, $selectors, $render_slug ) {

		$devices      = et_pb_responsive_options()->get_modes();
		$border_field = null;

		if ( class_exists( 'ET_Builder_Module_Fields_Factory' ) ) {
			$border_field = ET_Builder_Module_Fields_Factory::get( 'Border' );
		}

		if ( ! empty( $devices ) ) {
			foreach ( $devices  as $device ) {
				if ( ! empty( $border_field ) ) {

					$border_radii_style = $border_field->get_radii_style( $this->props, $this->advanced_fields, $toggle_name, false, false, $device );
					$border_radii_style = str_replace( ';', ' !important;', $border_radii_style );

					$border_radii_attrs = array(
						'selector'    => $selectors['normal'],
						'declaration' => $border_radii_style,
					);

					if ( 'desktop' !== $device ) {
						$media_query                       = 'tablet' === $device ? 'max_width_980' : 'max_width_767';
						$border_radii_attrs['media_query'] = self::get_media_query( $media_query );
					}

					self::set_style( $render_slug, $border_radii_attrs );

				}
			}
		}

		if ( ! empty( $border_field ) && ! empty( $selectors['hover'] ) ) {

			$border_radii_hover_style = $border_field->get_radii_style( $this->props, $this->advanced_fields, $toggle_name, false, true );
			$border_radii_hover_style = str_replace( ';', ' !important;', $border_radii_hover_style );

			$border_radii_hover_attrs = array(
				'selector'    => $selectors['hover'],
				'declaration' => $border_radii_hover_style,
			);

			self::set_style( $render_slug, $border_radii_hover_attrs );

		}
	}

}
$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_audio_player', $modules ) ) {
		new DIPL_AudioPlayer();
	}
} else {
	new DIPL_AudioPlayer();
}