<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.13.0
 */
class DIPL_PostsTicker extends ET_Builder_Module {
	public $slug       = 'dipl_posts_ticker';
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
		$this->name             = esc_html__( 'DP Posts Ticker', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => array(
						'title' => esc_html__( 'Content', 'divi-plus' ),
					),
					'display' => array(
						'title' => esc_html__( 'Display', 'divi-plus' ),
					),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'ticker_label' => array(
						'title' => esc_html__( 'Ticker Label', 'divi-plus' ),
					),
					'post_items_bar' => array(
						'title' => esc_html__( 'Post Items Bar', 'divi-plus' ),
					),
					'post_item' => array(
						'title' => esc_html__( 'Post Item', 'divi-plus' ),
					),
					'post_item_separator' => array(
						'title' => esc_html__( 'Post Item Separator', 'divi-plus' ),
					),
					'slider_arrows' => array(
						'title' => esc_html__( 'Arrows', 'divi-plus' ),
					),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'ticker_label' => array(
					'label'          => esc_html__( 'Ticker Label', 'divi-plus' ),
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
						'default'        => '1.3em',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '5',
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
					'text_color' =>  array(
						'default' => '#fff',
					),
					'hide_text_align' => true,
					'css' => array(
						'main' => "{$this->main_css_element} .dp-posts-ticker-label",
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'ticker_label',
				),
				'post_item' => array(
					'label'     => esc_html__( 'Post Item', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height' => array(
						'default'        => '1.3em',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '5',
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
					'text_color' =>  array(
						'default' => '#000',
					),
					'hide_text_align' => true,
					'css' => array(
						'main' => "{$this->main_css_element} .dp-posts-ticker-post-title",
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'post_item',
				)
			),
			'borders' => array(
				'ticker_label' => array(
					'label_prefix'    => esc_html__( 'Ticker Label', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dp-posts-ticker-label',
							'border_styles' => '%%order_class%% .dp-posts-ticker-label',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'ticker_label',
				),
				'post_items_bar' => array(
					'label_prefix'    => esc_html__( 'Item Bar', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dp-posts-ticker-items',
							'border_styles' => '%%order_class%% .dp-posts-ticker-items',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'post_items_bar',
				),
				'slider_arrow' => array(
					'label_prefix'    => esc_html__( 'Arrow', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .swiper-button-next, %%order_class%% .swiper-button-prev',
							'border_styles' => '%%order_class%% .swiper-button-next, %%order_class%% .swiper-button-prev',
						),
						'important' => 'all',
					),
					'depends_on'      => array( 'show_arrow' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slider_arrows',
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
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'ticker_spacing' => array(
				'post_items_bar' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dp-posts-ticker-items",
							'padding'   => "{$this->main_css_element} .dp-posts-ticker-items",
							'important' => 'all',
						),
					),
				),
				'ticker_label' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dp-posts-ticker-label",
							'important'  => 'all',
						),
					),
				),
				'post_item' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dp-posts-ticker-post-title",
							'important'  => 'all',
						),
					),
				),
				'post_separator' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dp-ticker-effect-scroll .dp-posts-ticker-item::after",
							'important'  => 'all',
						),
					),
				),
				'slider_arrows_container' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .dipl_swiper_navigation",
							'important'  => 'all',
						),
					),
				),
				'slider_arrows' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .swiper-button-next, {$this->main_css_element} .swiper-button-prev",
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
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		$raw_post_types = get_post_types( array(
			'public' => true,
			'show_ui' => true,
			'exclude_from_search' => false,
		), 'objects' );

		$blocklist = array( 'et_pb_layout', 'layout', 'attachment' );

		$post_types = array();
		foreach ( $raw_post_types as $post_type ) {
			$is_blocklisted = in_array( $post_type->name, $blocklist );
			
			if ( ! $is_blocklisted && post_type_exists( $post_type->name ) ) {
				if ( isset( $post_type->label ) ) {
					$label = esc_html( $post_type->label );
				} else if ( isset( $post_type->labels->name ) ) {
					$label = esc_html( $post_type->labels->name );
				} else if ( isset( $post_type->labels->singular_name ) ) {
					$label = esc_html( $post_type->labels->singular_name );
				} else {
					$label = esc_html( $post_type->name );
				}
				$slug  	= sanitize_text_field( $post_type->name );
				$post_types[$slug] = esc_html( $label );
			}
		}

		return array_merge(
			array(
				'number_of_results' => array(
					'label'            => esc_html__( 'Number of Posts', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default_on_front' => '5',
					'default'		   => '5',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can input the number of posts to be displayed in the ticker. Input -1 for all.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'post_types' => array(
					'label'            => esc_html__( 'Post Types', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'basic_option',
					'options'		   => $post_types,
					'default'		   => 'post',
					'default_on_front' => 'post',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can choose which post types you would like to show in ticker posts.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'orderby' => array(
					'label'            => esc_html__( 'Order by', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'post_date' 	=> esc_html__( 'Date', 'divi-plus' ),
						'post_modified'	=> esc_html__( 'Modified Date', 'divi-plus' ),
						'post_title'   	=> esc_html__( 'Title', 'divi-plus' ),
						'post_name'     => esc_html__( 'Slug', 'divi-plus' ),
						'ID'       		=> esc_html__( 'ID', 'divi-plus' ),
						'rand'     		=> esc_html__( 'Random', 'divi-plus' ),
					),
					'default'          => 'post_date',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can choose the order type of your results.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'order' => array(
					'label'            => esc_html__( 'Order', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'DESC' => esc_html__( 'DESC', 'divi-plus' ),
						'ASC'  => esc_html__( 'ASC', 'divi-plus' ),
					),
					'default'          => 'DESC',
					'show_if_not'      => array(
						'orderby' => 'rand',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can choose the order of your results.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'include_categories' => array(
					'label'            => esc_html__( 'Select Categories', 'divi-plus' ),
					'type'             => 'categories',
					'option_category'  => 'basic_option',
					'renderer_options' => array(
						'use_terms' => false,
					),
					'show_if'          => array(
						'post_types' => 'post',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Choose which categories you would like to include in the feed', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'exclude_pwd_protected' => array(
					'label'            => esc_html__( 'Exclude Password Protected', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Enable this exclude password protected posts.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'exclude_post_ids' => array(
					'label'           => esc_html__( 'Exclude Post IDs', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can enter post ids you want to exclude from ticker separated by commas.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'ticker_label' => array(
					'label'            => esc_html__( 'Ticker Label', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default_on_front' => esc_html__( 'Breaking News', 'divi-plus' ),
					'default'          => esc_html__( 'Breaking News', 'divi-plus' ),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can enter or update the label to display with the post ticker.', 'divi-plus' ),
				),
				'ticker_effect' => array(
					'label'            => esc_html__( 'Ticker Effect', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'scroll' => esc_html__( 'Scroll', 'divi-plus' ),
						'fade'   => esc_html__( 'Fade', 'divi-plus' ),
						'slide'  => esc_html__( 'Slide', 'divi-plus' ),
					),
					'default'          => 'scroll',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here scroll will display all items and slides in loop, fade will dislay one by one items with fade animation.', 'divi-plus' ),
					'computed_affects' => array(
						'__posts_ticker_data',
					),
				),
				'post_separator_type' => array(
					'label'            => esc_html__( 'Post Item Separator Type', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'icon'   => esc_html__( 'Icon', 'divi-plus' ),
						'custom' => esc_html__( 'Custom', 'divi-plus' ),
					),
					'default'         => 'custom',
					'show_if'         => array(
						'ticker_effect' => 'scroll',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the posts separator type, custom to input your text, or choose icon.', 'divi-plus' ),
				),
				'post_separator_icon' => array(
					'label'           => esc_html__( 'Post Item Separator Icon', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'default'         => '',
					'show_if'         => array(
						'post_separator_type' => 'icon',
						'ticker_effect'       => 'scroll',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the posts separator.', 'divi-plus' ),
				),
				'post_separator_text' => array(
					'label'           => esc_html__( 'Custom Post Item Separator', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'basic_option',
					'default'         => '|',
					'show_if'         => array(
						'post_separator_type' => 'custom',
						'ticker_effect'       => 'scroll',
					),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the posts separator.', 'divi-plus' ),
				),
				'scroll_effect_delay' => array(
					'label'            => esc_html__( 'Scrool Effect Speed', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => '70',
					'default_on_front' => '70',
					'show_if'          => array(
						'ticker_effect' => 'scroll',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can input the value to speed of the scroll for the ticker.', 'divi-plus' ),
				),
				'slide_align' => array(
					'label'            => esc_html__( 'Slide Alignment', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'horizontal' => esc_html__( 'Horizontal', 'divi-plus' ),
						'vertical'   => esc_html__( 'Vertical', 'divi-plus' ),
					),
					'default'          => 'horizontal',
					'default_on_front' => 'horizontal',
					'show_if'           => array(
						'ticker_effect' => 'slide',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can input the value to delay after a complete transition of ticker.', 'divi-plus' ),
				),
				'fade_effect_delay' => array(
					'label'            => esc_html__( 'Fade Effect Delay', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => '2500',
					'default_on_front' => '2500',
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can input the value to delay after a complete transition of ticker.', 'divi-plus' ),
				),
				'fade_effect_transition' => array(
					'label'            => esc_html__( 'Fade Transition Duration', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'basic_option',
					'default'          => '700',
					'default_on_front' => '700',
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can input the value to delay after a complete transition of ticker.', 'divi-plus' ),
				),
				'show_arrow' => array(
					'label'           => esc_html__( 'Show Arrows', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'default'         => 'off',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can choose whether or not to display previous & next arrow on the ticker.', 'divi-plus' ),
				),
				'previous_slide_arrow' => array(
					'label'           => esc_html__( 'Previous Arrow', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'show_arrow'    => 'on',
					),
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'default'         => ET_BUILDER_PRODUCT_VERSION < '4.13.0' ? '%%19%%' : '&#xf053;||fa||900',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the icon to be used for the previous slide navigation.', 'divi-plus' ),
				),
				'next_slide_arrow' => array(
					'label'           => esc_html__( 'Next Arrow', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'show_if'         => array(
						'show_arrow'    => 'on',
					),
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'default'         => ET_BUILDER_PRODUCT_VERSION < '4.13.0' ? '%%20%%' : '&#xf054;||fa||900',
					'tab_slug'        => 'general',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can select the icon to be used for the next slide navigation.', 'divi-plus' ),
				),
				'post_items_bar_bg' => array(
					'label'            => esc_html__( 'Items Bar Background Color', 'divi-plus' ),
					'type'             => 'color-alpha',
					'default'          => '#f5f5f5',
					'default_on_front' => '#f5f5f5',
					'custom_color'     => true,
					'hover'            => 'tabs',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_items_bar',
					'description'      => esc_html__( 'Here you can define a custom background color for the ticker background.', 'divi-plus' ),
				),
				'post_items_bar_custom_margin' => array(
					'label'            => esc_html__( 'Items Bar Margin', 'divi-plus' ),
					'type'             => 'custom_margin',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '||||true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_items_bar',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'ticker_label_bg' => array(
					'label'            => esc_html__( 'Label Background Color', 'divi-plus' ),
					'type'             => 'color-alpha',
					'default'          => '#000',
					'default_on_front' => '#000',
					'custom_color'     => true,
					'hover'            => 'tabs',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'ticker_label',
					'description'      => esc_html__( 'Here you can define a custom background color for the ticker background.', 'divi-plus' ),
				),
				'ticker_label_custom_padding' => array(
					'label'            => esc_html__( 'Ticker Label Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '10px|20px|10px|20px|true|true',
					'default_on_front' => '10px|20px|10px|20px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'ticker_label',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'post_item_custom_padding' => array(
					'label'            => esc_html__( 'Post Item Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '|5px||5px|true|true',
					'default_on_front' => '|5px||5px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_item',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'separator_icon_font_size' => array(
					'label'           => esc_html__( 'Separator Font Size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '10',
						'max'  => '100',
						'step' => '1',
					),
					'show_if'         => array(
						'ticker_effect' => 'scroll',
					),
					'mobile_options'  => true,
					'default'         => '20px',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'post_item_separator',
					'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the size of separator icons.', 'divi-plus' ),
				),
				'separator_icon_color' => array(
					'label'        => esc_html__( 'Separator Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'ticker_effect' => 'scroll',
					),
					'default'      => '#000',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_item_separator',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for separator icon.', 'divi-plus' ),
				),
				'post_separator_custom_padding' => array(
					'label'            => esc_html__( 'Post Item Separator Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'          => array(
						'ticker_effect' => 'scroll',
					),
					'default'          => '|10px||10px|true|true',
					'default_on_front' => '|10px||10px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_item_separator',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'slider_arrows_container_custom_padding' => array(
					'label'            => esc_html__( 'Arrows Container Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'          => array(
						'show_arrow' => 'on',
					),
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'default'          => '10px|10px|10px|10px|true|true',
					'default_on_front' => '10px|10px|10px|10px|true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'slider_arrows',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'arrows_container_bg' => array(
					'label'        => esc_html__( 'Arrows Container Background', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_arrow' => 'on',
					),
					'show_if_not'  => array(
						'ticker_effect' => 'scroll',
					),
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_arrows',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for the background of arrows container.', 'divi-plus' ),
				),
				'slider_arrows_custom_padding' => array(
					'label'            => esc_html__( 'Arrows Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'          => array(
						'show_arrow' => 'on',
					),
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'default'          => '||||true|true',
					'default_on_front' => '||||true|true',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'slider_arrows',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'arrow_font_size' => array(
					'label'           => esc_html__( 'Arrow Font Size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '10',
						'max'  => '100',
						'step' => '1',
					),
					'show_if'         => array(
						'show_arrow' => 'on',
					),
					'show_if_not'      => array(
						'ticker_effect' => 'scroll',
					),
					'mobile_options'  => true,
					'default'         => '18px',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slider_arrows',
					'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the size of arrows.', 'divi-plus' ),
				),
				'arrow_background_color' => array(
					'label'        => esc_html__( 'Arrow Background', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_arrow' => 'on',
					),
					'show_if_not'  => array(
						'ticker_effect' => 'scroll',
					),
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_arrows',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for the background of arrows.', 'divi-plus' ),
				),
				'arrow_color' => array(
					'label'        => esc_html__( 'Arrow Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_arrow' => 'on',
					),
					'show_if_not'  => array(
						'ticker_effect' => 'scroll',
					),
					'default'      => '#007aff',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_arrows',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for arrows.', 'divi-plus' ),
				),
				'__posts_ticker_data' => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'DIPL_PostsTicker', 'get_computed_posts_ticker_data' ),
					'computed_depends_on' => array(
						'number_of_results',
						'post_types',
						'orderby',
						'order',
						'include_categories',
						'exclude_pwd_protected',
						'exclude_post_ids',
					)
				)
			)
		);
	}

	public static function get_computed_posts_ticker_data( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		// WordPress' native conditional tag is only available during page load. It'll fail during component update because
		// et_pb_process_computed_property() is loaded in admin-ajax.php. Thus, use WordPress' conditional tags on page load and
		// rely to passed $conditional_tags for AJAX call.
		$is_user_logged_in = (bool) et_fb_conditional_tag( 'is_user_logged_in', $conditional_tags );

		$defaults = array(
			'number_of_results'     => '5',
			'post_types'            => 'post',
			'orderby'               => 'post_date',
			'order'                 => 'DESC',
			'include_categories'    => '',
			'exclude_pwd_protected' => 'on',
			'exclude_post_ids'      => '',
		);

		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => intval( $number_of_results ),
			'post_status'    => 'publish',
			'offset'         => 0,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		if ( 'on' === $exclude_pwd_protected ) {
			$args['has_password'] = false;
		}
		if ( ! empty( $post_types ) ) {
			$args['post_type'] = sanitize_text_field( $post_types );
		}
		if ( $is_user_logged_in ) {
			$args['post_status'] = array( 'publish', 'private' );
		}
		if ( ! empty( $orderby ) ) {
			$args['orderby'] = sanitize_text_field( $orderby );
		}
		if ( ! empty( $order ) ) {
			$args['order'] = sanitize_text_field( $order );
		}
		if ( 'post' === $post_types && ! empty( $include_categories ) ) {
			$args['cat'] = sanitize_text_field( $include_categories );
		}
		if ( ! empty( $exclude_post_ids ) ) {
			$args['post__not_in'] = explode( ',', sanitize_text_field( $exclude_post_ids ) );
		}

		global $wp_the_query;
		$query_backup = $wp_the_query;

		$query = new WP_Query( $args );

		self::$rendering = true;

		$items_array = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$post_item = sprintf(
					'<a class="%3$s" href="%1$s">%2$s</a>',
					get_the_permalink(),
					get_the_title(),
					esc_attr( implode( ' ', get_post_class( 'dp-posts-ticker-post-title' ) ) )
				);

				// Add this to main array.
				array_push( $items_array, $post_item );
			}
		}

		wp_reset_postdata();

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wp_the_query = $query_backup;

		self::$rendering = false;
		if ( ! empty( $items_array ) ) {
			return $items_array;
			// return implode( '', $items_array );
		}

		return '';
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		/*
		 * Cached $wp_filter so it can be restored at the end of the callback.
		 * This is needed because this callback uses the_content filter / calls a function
		 * which uses the_content filter. WordPress doesn't support nested filter
		 */
		global $wp_filter;
		$wp_filter_cache = $wp_filter;

		$number_of_results     = esc_attr( $this->props['number_of_results'] );
		$post_types            = esc_attr( $this->props['post_types'] );
		$orderby               = esc_attr( $this->props['orderby'] );
		$order                 = esc_attr( $this->props['order'] );
		$include_categories    = esc_attr( $this->props['include_categories'] );
		$exclude_pwd_protected = esc_attr( $this->props['exclude_pwd_protected'] );
		$exclude_post_ids      = esc_attr( $this->props['exclude_post_ids'] );
		$ticker_effect         = esc_attr( $this->props['ticker_effect'] );
		$show_arrow            = esc_attr( $this->props['show_arrow'] );

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => intval( $number_of_results ),
			'post_status'    => 'publish',
			'offset'         => 0,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		if ( 'on' === $exclude_pwd_protected ) {
			$args['has_password'] = false;
		}
		if ( ! empty( $post_types ) ) {
			$args['post_type'] = sanitize_text_field( $post_types );
		}
		if ( is_user_logged_in() ) {
			$args['post_status'] = array( 'publish', 'private' );
		}
		if ( ! empty( $orderby ) ) {
			$args['orderby'] = sanitize_text_field( $orderby );
		}
		if ( ! empty( $order ) ) {
			$args['order'] = sanitize_text_field( $order );
		}
		if ( 'post' === $post_types && ! empty( $include_categories ) ) {
			$args['cat'] = sanitize_text_field( $include_categories );
		}
		if ( ! empty( $exclude_post_ids ) ) {
			$args['post__not_in'] = explode( ',', sanitize_text_field( $exclude_post_ids ) );
		}

		global $wp_the_query;
		$query_backup = $wp_the_query;

		$query = new WP_Query( $args );

		self::$rendering = true;

		$item_classes = 'dp-posts-ticker-item';
		if ( 'scroll' !== $ticker_effect ) {
			$item_classes .= ' swiper-slide';
		}

		$items_array = array();
		if ( $query->have_posts() ) {

			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        	wp_enqueue_style( 'dipl-posts-ticker-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/PostsTicker/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'dipl-posts-ticker-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/PostsTicker/dipl-posts-ticker-custom.min.js", array('jquery'), '1.0.0', true );

			while ( $query->have_posts() ) {
				$query->the_post();

				$post_item = sprintf(
					'<div class="%4$s">
						<a class="%3$s" href="%1$s">%2$s</a>
					</div>',
					get_the_permalink(),
					get_the_title(),
					esc_attr( implode( ' ', get_post_class( 'dp-posts-ticker-post-title' ) ) ),
					esc_attr( $item_classes )
				);

				// Add this to main array.
				array_push( $items_array, $post_item );
			}
		}

		wp_reset_postdata();

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wp_the_query = $query_backup;

		$render_output = '';
		if ( ! empty( $items_array ) ) {
			// Render ticker title.
			$render_ticker_label = '';
			if ( ! empty( $this->props['ticker_label'] ) ) {
				$render_ticker_label = sprintf(
					'<div class="dp-posts-ticker-label">%1$s</div>',
					esc_html( $this->props['ticker_label'] )
				);
			}

			$data_props = array();

			// Items bar.
			$items_bar = '';
			if ( 'scroll' !== $ticker_effect ) {

				// Enqueue jquery file, to initialize slider.
				wp_enqueue_script( 'elicus-swiper-script' );
				wp_enqueue_style( 'elicus-swiper-style' );
				wp_enqueue_style( 'dipl-swiper-style' );

				// Swiper check if arrows enable.
				$arrows = '';
				if ( 'on' === $show_arrow ) {	
					// Get arrows icon.
					$next_arrow_icon = ! empty( $this->props['next_slide_arrow'] ) ? $this->props['next_slide_arrow'] : '';
					$next_arrow_icon = ! empty( $next_arrow_icon ) ? et_pb_process_font_icon( $next_arrow_icon ) : '';

					$prev_arrow_icon = ! empty( $this->props['previous_slide_arrow'] ) ? $this->props['previous_slide_arrow'] : '';
					$prev_arrow_icon = ! empty( $prev_arrow_icon ) ? et_pb_process_font_icon( $prev_arrow_icon ) : '';

					// Create buttons.
					$next = sprintf(
						'<div class="swiper-button-next"%1$s></div>',
						! empty( $next_arrow_icon ) ? sprintf( ' data-next_slide_arrow="%1$s"', esc_attr( $next_arrow_icon ) ) : ''
					);
					$prev = sprintf(
						'<div class="swiper-button-prev"%1$s></div>',
						! empty( $prev_arrow_icon ) ? sprintf( ' data-previous_slide_arrow="%1$s"', esc_attr( $prev_arrow_icon ) ) : ''
					);

					// Arrows wrapper.
					$arrows = sprintf( '<div class="dipl_swiper_navigation dipl_arrows_top_right">%1$s %2$s</div>', $next, $prev );
				}

				$items_bar = sprintf(
					'<div class="dp-posts-ticker-bar">
						<div class="swiper-container"><div class="swiper-wrapper">%1$s</div></div>
						%2$s
					</div>',
					implode( '', $items_array ),
					$arrows
				);

				$data_props = array( 'ticker_effect', 'slide_align', 'fade_effect_delay', 'fade_effect_transition', 'show_arrow' );
			} else {
				$items_bar = sprintf(
					'<div class="dp-posts-ticker-bar">%1$s</div>',
					implode( '', $items_array )
				);

				$data_props = array( 'scroll_effect_delay' );
			}

			// Convert props to data attrs.
			$data_atts = $this->props_to_html_data_attrs( $data_props );

			// Render output.
			$render_output = sprintf(
				'<div class="dp-posts-ticker-wrap dp-ticker-effect-%1$s" %4$s>
					%2$s
					<div class="dp-posts-ticker-items">%3$s</div>
				</div>',
				esc_attr( $ticker_effect ),
				$render_ticker_label,
				$items_bar,
				$data_atts
			);

			// Add custom styles.
			if ( ! empty( $this->props['ticker_label_bg'] ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .dp-posts-ticker-label',
					'declaration' => sprintf( 'background-color: %1$s;', esc_attr( $this->props['ticker_label_bg'] ) ),
				) );
			}
			$ticker_label_bg_hover = $this->get_hover_value( 'ticker_label_bg' );
			if ( ! empty( $ticker_label_bg_hover ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .dp-posts-ticker-label:hover',
					'declaration' => sprintf( 'background-color: %1$s;', esc_attr( $ticker_label_bg_hover ) ),
				) );
			}

			// Post items bar.
			if ( ! empty( $this->props['post_items_bar_bg'] ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .dp-posts-ticker-items',
					'declaration' => sprintf( 'background-color: %1$s;', esc_attr( $this->props['post_items_bar_bg'] ) ),
				) );
			}
			$post_items_bar_bg_hover = $this->get_hover_value( 'post_items_bar_bg' );
			if ( ! empty( $post_items_bar_bg_hover ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .dp-posts-ticker-items:hover',
					'declaration' => sprintf( 'background-color: %1$s;', esc_attr( $post_items_bar_bg_hover ) ),
				) );
			}

			// Post separator.
			if ( 'scroll' === $this->props['ticker_effect'] ) {
				// If icon selected.
				$post_separator_type = esc_attr( $this->props['post_separator_type'] );
				$post_separator_icon = esc_attr( $this->props['post_separator_icon'] );
				if ( 'icon' === $post_separator_type && ! empty( $post_separator_icon ) ) {
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'post_separator_icon',
							'important'      => true,
							'selector'       => '%%order_class%% .dp-ticker-effect-scroll .dp-posts-ticker-item::after',
							'processor'      => array(
								'ET_Builder_Module_Helper_Style_Processor',
								'process_extended_icon',
							),
						) );
					}
					$post_separator_icon = str_replace( '&#x', '\\', esc_attr( et_pb_process_font_icon( $post_separator_icon ) ) );
					$post_separator_icon = str_replace( ';', '', $post_separator_icon );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dp-ticker-effect-scroll .dp-posts-ticker-item::after',
						'declaration' => sprintf( 'content: "%1$s" !important; vertical-align: middle;', $post_separator_icon ),
					) );
				} elseif ( 'custom' === $post_separator_type ) {
					$post_separator_text = ! empty( $this->props['post_separator_text'] ) ? esc_attr( $this->props['post_separator_text'] ) : '|';
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dp-ticker-effect-scroll .dp-posts-ticker-item::after',
						'declaration' => sprintf( 'content: "%1$s" !important;', $post_separator_text ),
					) );
				}

				// Icon font size and color.
				$separator_icon_font_size = et_pb_responsive_options()->get_property_values( $this->props, 'separator_icon_font_size' );
				if ( ! empty( array_filter( $separator_icon_font_size ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $separator_icon_font_size, '%%order_class%% .dp-ticker-effect-scroll .dp-posts-ticker-item::after', 'font-size', $render_slug, '', 'type' );
				}
				if ( ! empty( $this->props['separator_icon_color'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dp-ticker-effect-scroll .dp-posts-ticker-item::after',
						'declaration' => sprintf( 'color: %1$s;', esc_attr( $this->props['separator_icon_color'] ) ),
					) );
				}
			}

			// Fade effect arrows.
			if ( 'scroll' !== $ticker_effect && 'on' === $show_arrow ) {
				// Next arrow style.
				if ( '' !== $this->props['next_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-next::after',
						'declaration' => 'content: attr(data-next_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'next_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl_swiper_navigation .swiper-button-next::after',
							'processor'      => array(
								'ET_Builder_Module_Helper_Style_Processor',
								'process_extended_icon',
							),
						) );
					}
				}
				// Previous icon style.
				if ( '' !== $this->props['previous_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev::after',
						'declaration' => 'content: attr(data-previous_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'previous_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev::after',
							'processor'      => array(
								'ET_Builder_Module_Helper_Style_Processor',
								'process_extended_icon',
							),
						) );
					}
				}

				// Arrow size.
				$arrow_font_size = et_pb_responsive_options()->get_property_values( $this->props, 'arrow_font_size' );
				if ( ! empty( array_filter( $arrow_font_size ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $arrow_font_size, '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next', 'font-size', $render_slug, '!important;', 'range' );
				}

				// Arrows container.
				if ( ! empty( $this->props['arrows_container_bg'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation',
						'declaration' => sprintf( 'background: %1$s;', esc_attr( $this->props['arrows_container_bg'] ) ),
					) );
				}
				if ( ! empty( $this->props['arrows_container_bg_hover'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation:hover',
						'declaration' => sprintf( 'background: %1$s;', esc_attr( $this->props['arrows_container_bg_hover'] ) ),
					) );
				}

				// Arrow background color.
				if ( ! empty( $this->props['arrow_background_color'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => sprintf( 'background: %1$s;', esc_attr( $this->props['arrow_background_color'] ) ),
					) );
				}
				if ( ! empty( $this->props['arrow_background_color_hover'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev:hover, %%order_class%% .dipl_swiper_navigation .swiper-button-next:hover',
						'declaration' => sprintf( 'background: %1$s;', esc_attr( $this->props['arrow_background_color_hover'] ) ),
					) );
				}
				// Arrow color.
				if ( ! empty( $this->props['arrow_color'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => sprintf( 'color: %1$s !important;', esc_attr( $this->props['arrow_color'] ) ),
					) );
				}
				if ( ! empty( $this->props['arrow_color_hover'] ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev:hover, %%order_class%% .dipl_swiper_navigation .swiper-button-next:hover',
						'declaration' => sprintf( 'color: %1$s !important;', esc_attr( $this->props['arrow_color_hover'] ) ),
					) );
				}
			}

			$fields = array( 'ticker_spacing' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );
		}

		self::$rendering = false;

		return $render_output;
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_posts_ticker', $modules ) ) {
		new DIPL_PostsTicker();
	}
} else {
	new DIPL_PostsTicker();
}
