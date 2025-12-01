<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */
class DIPL_WooProductsCategoriesCarousel extends ET_Builder_Module {
	public $slug       = 'dipl_woo_products_categories_carousel';
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
		$this->name             = esc_html__( 'DP Woo Products Categories Carousel', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content'    => esc_html__( 'Content', 'divi-plus' ),
					'display'         => esc_html__( 'Display', 'divi-plus' ),
					'slider_settings' => array(
						'title' => esc_html__( 'Slider', 'divi-plus' ),
						'sub_toggles' => array(
							'general'    => array( 'name' => esc_html__( 'General', 'divi-plus' ) ),
							'navigation' => array( 'name' => esc_html__( 'Navigation', 'divi-plus' ) )
						),
						'tabbed_subtoggles' => true,
					),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'thumbnail'     => esc_html__( 'Thumbnail', 'divi-plus' ),
					'title_text'    => esc_html__( 'Title', 'divi-plus' ),
					'product_count' => esc_html__( 'Product Count', 'divi-plus' ),
					'content_box'   => esc_html__( 'Content Box', 'divi-plus' ),
					'category_item' => esc_html__( 'Category Item', 'divi-plus' ),
					'slider_styles' => esc_html__( 'Slider', 'divi-plus' ),
					'slider_dots'   => esc_html__( 'Slider Pagination', 'divi-plus' ),
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
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit' => true,
					),
					'line_height' => array(
						'default'        => '1.5em',
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
						'validate_unit' => true,
					),
					'header_level' => array(
						'default' => 'h4',
					),
					'text_align' => array(
						'default' => 'center',
					),
					'css'            => array(
						'main'      => "{$this->main_css_element} .dipl_woo_product_category_name, {$this->main_css_element} .dipl_woo_product_category_name a",
						'important' => 'all',
					),
					'tab_slug'	  => 'advanced',
                    'toggle_slug' => 'title_text',
				),
				'product_count' => array(
					'label'     => esc_html__( 'Product Count', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align'     => array(
						'default' => 'center',
					),
					'css'            => array(
						'main'       => "{$this->main_css_element} .dipl_woo_product_category_count, {$this->main_css_element} .dipl_woo_product_category_count a",
						'important' => 'all',
					),
					'show_if'         => array( 'show_product_count' => 'on' ),
					'depends_on'      => array( 'show_product_count' ),
					'depends_show_if' => 'on',
					'tab_slug'	      => 'advanced',
					'toggle_slug'     => 'product_count',
				),
			),
			'borders' => array(
				'thumbnail' => array(
					'label_prefix' => esc_html__( 'Thumbnail', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_woo_product_category_thumbnail",
							'border_styles' => "%%order_class%% .dipl_woo_product_category_thumbnail",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'thumbnail',
				),
				'content_box' => array(
					'label_prefix' => esc_html__( 'Content Box', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_woo_product_category_content",
							'border_styles' => "%%order_class%% .dipl_woo_product_category_content",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'content_box',
				),
				'category_item' => array(
					'label_prefix' => esc_html__( 'Category', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_woo_products_category_item",
							'border_styles' => "%%order_class%% .dipl_woo_products_category_item",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'category_item',
				),
				'arrows' => array(
					'css' => array(
						'main' => array(
						    'border_radii'	=> "%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next",
							'border_styles'	=> "%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next",
						),
						'important' => 'all',
					),
					'label_prefix' => esc_html__( 'Arrows', 'divi-plus' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_styles',
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
				'thumbnail' => array(
					'label'       => esc_html__( 'Thumbnail Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "%%order_class%% .dipl_woo_product_category_thumbnail",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'thumbnail',
				),
				'category_item' => array(
					'label'       => esc_html__( 'Category Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "%%order_class%% .dipl_woo_products_category_item",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'category_item',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'categories_carousel_spacing' => array(
				'thumbnail' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_woo_product_category_thumbnail",
							'important'  => 'all',
						),
					),
				),
				'content_box' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_woo_product_category_content",
							'important'  => 'all',
						),
					),
				),
				'category_item' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_woo_products_category_item",
							'important'  => 'all',
						),
					),
				),
				'slider_container' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_woo_products_categories_carousel_wrapper .swiper-container",
							'important'  => 'all',
						),
					),
				),
				'arrows' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next",
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
			'link_options' => false,
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
		return array_merge(
			array(
				'number_of_categories' => array(
					'label'            => esc_html__( 'Number of Categories', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'          => 10,
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can specify the total number of products categories to display.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'include_categories' => array(
					'label'            => esc_html__( 'Include Categories', 'divi-plus' ),
					'type'             => 'categories',
					'renderer_options' => array(
						'use_terms'  => true,
						'term_name'  => 'product_cat',
						'field_name' => 'et_pb_include_dipl_product_cat',
					),
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Select Categories. If no category is selected, all categories will be displayed.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'order_by' => array(
					'label'            => esc_html__( 'Order by', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'name'       => esc_html__( 'Name', 'divi-plus' ),
						'slug'       => esc_html__( 'Slug', 'divi-plus' ),
						'term_id'    => esc_html__( 'Term Id', 'divi-plus' ),
						'count'      => esc_html__( 'Product Count', 'divi-plus' ),
						'menu_order' => esc_html__( 'Menu Order', 'divi-plus' ),
					),
					'default'          => 'name',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can specify the order in which the categories will be displayed.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
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
					'default'          => 'ASC',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can specify the sorting order for the categories.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'hide_empty' => array(
					'label'           => esc_html__( 'Hide Empty', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'on',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Choose whether or not hide the brands which does not have products.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'no_result_text' => array(
					'label'           => esc_html__( 'No Result Text', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'default'		  => esc_html__( 'The categories you requested could not be found. Try changing your module settings or create some new categories.', 'divi-plus' ),
					'tab_slug'        => 'general',
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can define custom no result text.', 'divi-plus' ),
				),
				'category_layout' => array(
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
					'description'      => esc_html__( 'Here you can select the layout to display categories.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'show_thumbnail' => array(
					'label'            => esc_html__( 'Show Thumbnail', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Choose whether or not the product thumbnail should be visible.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'thumbnail_size' => array(
					'label'            => esc_html__( 'Thumbnail Size', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'woocommerce_thumbnail'	=> esc_html__( 'Woocommerce Thumbnail', 'divi-plus' ),
						'woocommerce_single'	=> esc_html__( 'Woocommerce Single', 'divi-plus' ),
					),
					'default'          => 'woocommerce_thumbnail',
					'default_on_front' => 'woocommerce_thumbnail',
					'show_if'      	   => array(
						'show_thumbnail' => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can specify the size of category image.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'show_product_count' => array(
					'label'           => esc_html__( 'Show Product Count', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'on',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Choose whether or not the product count should be visible.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'show_content_on_hover' => array(
					'label'           => esc_html__( 'Show Content on Hover', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if' 		  => array(
						'category_layout' => 'layout2',
					),
					'default'         => 'off',
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Choose whether or not the content should show on hover.', 'divi-plus' ),
				),
				'items_per_slide' => array(
					'label'           => esc_html__( 'Number of Categories Per View', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'layout',
					'options'         => array(
						'1'  => esc_html__( '1', 'divi-plus' ),
						'2'  => esc_html__( '2', 'divi-plus' ),
						'3'  => esc_html__( '3', 'divi-plus' ),
						'4'  => esc_html__( '4', 'divi-plus' ),
						'5'  => esc_html__( '5', 'divi-plus' ),
						'6'  => esc_html__( '6', 'divi-plus' ),
						'7'  => esc_html__( '7', 'divi-plus' ),
						'8'  => esc_html__( '8', 'divi-plus' ),
						'9'  => esc_html__( '9', 'divi-plus' ),
						'10' => esc_html__( '10', 'divi-plus' ),
						'11' => esc_html__( '11', 'divi-plus' ),
						'12' => esc_html__( '12', 'divi-plus' ),
						'13' => esc_html__( '13', 'divi-plus' ),
						'14' => esc_html__( '14', 'divi-plus' ),
						'15' => esc_html__( '15', 'divi-plus' ),
					),
					'default'         => '5',
					'mobile_options'  => true,
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can choose the number of brands you want to display per slide.', 'divi-plus' ),
				),
				'slides_per_group' => array(
					'label'           => esc_html__( 'Number of Slides Per Group', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'layout',
					'options'         => array(
						'1'  => esc_html__( '1', 'divi-plus' ),
						'2'  => esc_html__( '2', 'divi-plus' ),
						'3'  => esc_html__( '3', 'divi-plus' ),
						'4'  => esc_html__( '4', 'divi-plus' ),
						'5'  => esc_html__( '5', 'divi-plus' ),
						'6'  => esc_html__( '6', 'divi-plus' ),
						'7'  => esc_html__( '7', 'divi-plus' ),
						'8'  => esc_html__( '8', 'divi-plus' ),
						'9'  => esc_html__( '9', 'divi-plus' ),
						'10' => esc_html__( '10', 'divi-plus' ),
						'11' => esc_html__( '11', 'divi-plus' ),
						'12' => esc_html__( '12', 'divi-plus' ),
						'13' => esc_html__( '13', 'divi-plus' ),
						'14' => esc_html__( '14', 'divi-plus' ),
						'15' => esc_html__( '15', 'divi-plus' ),
					),
					'default'         => '1',
					'mobile_options'  => true,
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can choose the number of slides per group to slide by.', 'divi-plus' ),
				),
				'space_between_slides' => array(
					'label'           => esc_html__( 'Space between Slides', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '10',
						'max'  => '150',
						'step' => '1',
					),
					'fixed_unit'	  => 'px',
					'default'         => '20px',
					'mobile_options'  => true,
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the space between slides.', 'divi-plus' ),
				),
				'slider_loop' => array(
					'label'           => esc_html__( 'Enable Loop', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'off',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can choose whether or not to enable loop for the logo slider.', 'divi-plus' ),
				),
				'autoplay' => array(
					'label'           => esc_html__( 'Autoplay', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'on',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can choose whether or not to autoplay logo slider.', 'divi-plus' ),
				),
				'enable_linear_transition' => array(
					'label'           => esc_html__( 'Enable Linear Transition', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'off',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can choose whether or not to enable linear transition between slides.', 'divi-plus' ),
				),
				'autoplay_speed' => array(
					'label'           => esc_html__( 'Autoplay Delay', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'default'         => '3000',
					'show_if'         => array(
						'autoplay' => 'on',
					),
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can input the value to delay autoplay after a complete transition of the logo slider.', 'divi-plus' ),
				),
				'pause_on_hover' => array(
					'label'           => esc_html__( 'Pause On Hover', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'on',
					'show_if'         => array(
						'autoplay' => 'on',
					),
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can choose whether or not to pause slides on mouse hover.', 'divi-plus' ),
				),
				'transition_duration' => array(
					'label'           => esc_html__( 'Transition Duration', 'divi-plus' ),
					'type'            => 'text',
					'option_category' => 'configuration',
					'default'         => '1000',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'general',
					'description'     => esc_html__( 'Here you can input the value to delay each slide in a transition.', 'divi-plus' ),
				),
				'show_arrow' => array(
					'label'           => esc_html__( 'Show Arrows', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'on',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'navigation',
					'description'     => esc_html__( 'Here you can choose whether or not to display previous & next arrow on the slider.', 'divi-plus' ),
				),
				'previous_slide_arrow' => array(
					'label'           => esc_html__( 'Previous Arrow', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'show_if'         => array( 'show_arrow' => 'on' ),
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'navigation',
					'description'     => esc_html__( 'Here you can select the icon to be used for the previous slide navigation.', 'divi-plus' ),
				),
				'next_slide_arrow' => array(
					'label'           => esc_html__( 'Next Arrow', 'divi-plus' ),
					'type'            => 'select_icon',
					'option_category' => 'basic_option',
					'show_if'         => array( 'show_arrow' => 'on' ),
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'navigation',
					'description'     => esc_html__( 'Here you can select the icon to be used for the next slide navigation.', 'divi-plus' ),
				),
				'show_arrow_on_hover' => array(
					'label'           => esc_html__( 'Show Arrows Only On Hover', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if'         => array( 'show_arrow' => 'on' ),
					'default'         => 'off',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'navigation',
					'description'     => esc_html__( 'Here you can choose whether or not to display previous and next arrow on hover.', 'divi-plus' ),
				),
				'arrows_position' => array(
					'label'           => esc_html__( 'Arrows Position', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'layout',
					'options'         => array(
						'inside' 		=> esc_html__( 'Inside', 'divi-plus' ),
						'outside'		=> esc_html__( 'Outside', 'divi-plus' ),
						'top_left'      => esc_html__( 'Top Left', 'divi-plus' ),
						'top_right'     => esc_html__( 'Top Right', 'divi-plus' ),
						'top_center'    => esc_html__( 'Top Center', 'divi-plus' ),
						'bottom_left'   => esc_html__( 'Bottom Left', 'divi-plus' ),
						'bottom_right'  => esc_html__( 'Bottom Right', 'divi-plus' ),
						'bottom_center' => esc_html__( 'Bottom Center', 'divi-plus' ),
					),
					'default'         => 'inside',
					'mobile_options'  => true,
					'show_if'         => array( 'show_arrow' => 'on' ),
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'navigation',
					'description'     => esc_html__( 'Here you can choose the arrows position.', 'divi-plus' ),
				),
				'show_control_dot' => array(
					'label'           => esc_html__( 'Show Dots Pagination', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'         => 'on',
					'toggle_slug'     => 'slider_setting',
					'sub_toggle'	  => 'navigation',
					'description'     => esc_html__( 'Here you choose whether or not to display pagination on the brand slider.', 'divi-plus' ),
				),
				'control_dot_style' => array(
					'label'            => esc_html__( 'Dots Pagination Style', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'layout',
					'options'          => array(
						'solid_dot'       => esc_html__( 'Solid Dot', 'divi-plus' ),
						'transparent_dot' => esc_html__( 'Transparent Dot', 'divi-plus' ),
						'stretched_dot'   => esc_html__( 'Stretched Dot', 'divi-plus' ),
						'line'            => esc_html__( 'Line', 'divi-plus' ),
						'rounded_line'    => esc_html__( 'Rounded Line', 'divi-plus' ),
						'square_dot'      => esc_html__( 'Squared Dot', 'divi-plus' ),
					),
					'show_if'          => array( 'show_control_dot' => 'on' ),
					'default'          => 'solid_dot',
					'toggle_slug'      => 'slider_setting',
					'sub_toggle'	   => 'navigation',
					'description'      => esc_html__( 'Control dot style.', 'divi-plus' ),
				),
				'enable_dynamic_dots' => array(
					'label'            => esc_html__( 'Enable Dynamic Dots', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'off',
					'show_if'          => array(
						'show_control_dot'  => 'on',
						'control_dot_style' => array( 'solid_dot', 'transparent_dot', 'square_dot' ),
					),
					'toggle_slug'      => 'slider_setting',
					'sub_toggle'	   => 'navigation',
					'description'      => esc_html__( 'This setting will turn on and off the dynamic pagination of the slider.', 'divi-plus' ),
				),
				'thumbnail_height' => array(
					'label'           => esc_html__( 'Thumbnail Height', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '100',
						'max'  => '700',
						'step' => '1',
					),
					'default'         => 'auto',
					'default_unit'    => 'px',
					'allowed_units'   => array( '%', 'em', 'rem', 'px' ),
					'allowed_values'  => et_builder_get_acceptable_css_string_values( 'height' ),
					'mobile_options'  => true,
					'show_if'      	  => array( 'show_thumbnail' => 'on' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'thumbnail',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the logo.', 'divi-plus' ),
				),
				'thumbnail_bg_color' => array(
					'label'        => esc_html__( 'Thumbnail Background Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'      => '',
					'hover'        => 'tabs',
					'show_if'      => array( 'show_thumbnail' => 'on' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'thumbnail',
					'description'  => esc_html__( 'Here you can choose a custom background color to be used for thumbnail.', 'divi-plus' ),
				),
				'thumbnail_custom_padding' => array(
					'label'            => esc_html__( 'Thumbnail Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '||||on|on',
					'default_on_front' => '||||on|on',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'          => array( 'show_thumbnail' => 'on' ),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'thumbnail',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'content_box_bg_color' => array(
					'label'        => esc_html__( 'Content Box Background Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'      => '',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'content_box',
					'description'  => esc_html__( 'Here you can choose a custom background color to be used for content box.', 'divi-plus' ),
				),
				'content_box_custom_padding' => array(
					'label'            => esc_html__( 'Content Box Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '10px|10px|10px|10px|on|on',
					'default_on_front' => '10px|10px|10px|10px|on|on',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'content_box',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'category_item_custom_padding' => array(
					'label'            => esc_html__( 'Category Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '||||on|on',
					'default_on_front' => '||||on|on',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'category_item',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'category_background_bg_color' => array(
					'label'             => esc_html__( 'Item Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'category_background_bg',
					'context'           => 'category_background_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => $this->generate_background_options( 'category_background_bg', 'button', 'advanced', 'category_item', 'category_background_bg_color' ),
					'hover'             => true,
					'mobile_options'    => true,
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'category_item',
					'description'       => esc_html__( 'Here you can set the background category item, color, and gradient to show on mouse hover.', 'divi-plus' ),
				),
				'slider_container_custom_padding' => array(
					'label'            => esc_html__( 'Slider Container Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '||||on|on',
					'default_on_front' => '||||on|on',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'margin_padding',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'arrows_custom_padding' => array(
					'label'           => esc_html__( 'Arrows Padding', 'divi-plus' ),
					'type'            => 'custom_padding',
					'option_category' => 'layout',
					'show_if'         => array(
						'show_arrow'  => 'on',
					),
					'default'		   => '5px|10px|5px|10px|true|true',
					'default_on_front' => '5px|10px|5px|10px|true|true',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'slider_styles',
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
					'default'         => '24px',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'slider_styles',
					'description'     => esc_html__( 'Here you can choose the arrow font size.', 'divi-plus' ),
				),
				'arrow_color' => array(
					'label'        => esc_html__( 'Arrow Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_arrow' => 'on',
					),
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_styles',
					'description'  => esc_html__( 'Here you can define color for the arrow', 'divi-plus' ),
				),
				'arrow_background_color' => array(
					'label'        => esc_html__( 'Arrow Background', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_arrow' => 'on',
					),
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_styles',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for the shape background of arrows.', 'divi-plus' ),
				),
				'control_dot_active_color' => array(
					'label'        => esc_html__( 'Active Dot Pagination Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_control_dot' => 'on',
					),
					'default'      => '#000000',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'slider_dots',
					'description'  => esc_html__( 'Here you can define color for the active pagination item.', 'divi-plus' ),
				),
				'control_dot_inactive_color' => array(
					'label'        => esc_html__( 'Inactive Dot Pagination Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'	   => array(
						'show_control_dot' => 'on',
					),
					'default'       => '#cccccc',
					'tab_slug'      => 'advanced',
					'toggle_slug'   => 'slider_dots',
					'description'   => esc_html__( 'Here you can define color for the inactive pagination item.', 'divi-plus' ),
				),
				'number_dot_text_color' => array(
					'label'        => esc_html__( 'Number Dot Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'show_control_dot'  => 'on',
						'control_dot_style' => 'number_dot',
					),
					'default'       => '#ffffff',
					'tab_slug'      => 'advanced',
					'toggle_slug'  	=> 'slider_dots',
					'description'  	=> esc_html__( 'Here you can define color for the number of pagination item.', 'divi-plus' ),
				),
				'__categories_data' => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'DIPL_WooProductsCategoriesCarousel', 'get_catgories_computed_data' ),
					'computed_depends_on' => array(
						'category_layout',
						'number_of_categories',
						'include_categories',
						'order_by',
						'order',
						'hide_empty',
						'show_thumbnail',
						'thumbnail_size',
						'show_product_count',
						'title_level',
					),
				),
			),
			$this->generate_background_options( 'category_background_bg', 'skip', 'advanced', 'category_item', 'category_background_bg_color' ),
		);
	}

	/**
	 * This function return values to react for front end builder.
	 */
	public static function get_catgories_computed_data( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'category_layout'      => 'layout1',
			'number_of_categories' => '10',
			'include_categories'   => '',
			'order_by'             => 'name',
			'order'	               => 'ASC',
			'hide_empty'           => 'off',
			'show_thumbnail'       => 'on',
			'thumbnail_size'       => 'woocommerce_thumbnail',
			'show_product_count'   => 'on',
			'title_level'          => 'h4'
		);
		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = sanitize_text_field( et_()->array_get( $args, $key, $default ) );
		}

		$query_args = array(
			'taxonomy'   => 'product_cat',
			'number'     => absint( $number_of_categories ),
			'orderby'    => $order_by,
			'order'      => $order,
			'include'    => $include_categories,
			'hide_empty' => ( 'on' === $hide_empty ) ? true : false,
		);
		$product_categories = get_terms( $query_args );

		$output_array = array();
		if ( $product_categories && ! is_wp_error( $product_categories ) ) {
			foreach ( $product_categories as $product_cat ) {
				
				$category_link = get_term_link( $product_cat->term_id, 'product_cat' );
				
				$category_items = '';
				if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/woo-products-categories-carousel/' . sanitize_file_name( $category_layout ) . '.php' ) ) {
					include get_stylesheet_directory() . '/divi-plus/layouts/woo-products-categories-carousel/' . sanitize_file_name( $category_layout ) . '.php';
				} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $category_layout ) . '.php' ) ) {
					include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $category_layout ) . '.php';
				}

				array_push( $output_array, $category_items );
			}
		}

		return $output_array;
	}

	public function before_render() {
		$is_responsive = et_pb_responsive_options()->is_responsive_enabled( $this->props, 'items_per_slide' );
		if ( ! $is_responsive ) {
			$items_per_slide = $this->props['items_per_slide'];
			$items_per_slide_tablet = $items_per_slide < 4 ? $items_per_slide : 3;
			$items_per_slide_mobile = 1;
			if ( isset( $items_per_slide_tablet ) && '' !== $items_per_slide_tablet ) {
				$this->props['items_per_slide_tablet'] = $items_per_slide_tablet;
			}
			if ( isset( $items_per_slide_mobile ) && '' !== $items_per_slide_mobile ) {
				$this->props['items_per_slide_phone'] = $items_per_slide_mobile;
			}
		}
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$category_layout       = sanitize_text_field( $this->props['category_layout'] ) ?? 'layout1';
		$number_of_categories  = sanitize_text_field( $this->props['number_of_categories'] ) ?? 10;
		$include_categories    = sanitize_text_field( $this->props['include_categories'] ) ?? '';
		$order_by              = sanitize_text_field( $this->props['order_by'] ) ?? 'name';
		$order                 = sanitize_text_field( $this->props['order'] ) ?? 'ASC';
		$hide_empty            = sanitize_text_field( $this->props['hide_empty'] ) ?? 'on';
		$no_result_text        = sanitize_text_field( $this->props['no_result_text'] ) ?? '';

		$show_thumbnail        = sanitize_text_field( $this->props['show_thumbnail'] );
		$thumbnail_size        = sanitize_text_field( $this->props['thumbnail_size'] );
		$show_product_count    = sanitize_text_field( $this->props['show_product_count'] );
		$show_content_on_hover = sanitize_text_field( $this->props['show_content_on_hover'] );
		$title_level           = sanitize_text_field( $this->props['title_level'] );
		$processed_title_level = et_pb_process_header_level( $title_level, 'h4' );

		$show_arrow         = sanitize_text_field( $this->props['show_arrow'] ) ?? 'on';
		$arrows_position    = et_pb_responsive_options()->get_property_values( $this->props, 'arrows_position' );
		$arrows_position    = array_filter( $arrows_position );
		$show_control_dot   = sanitize_text_field( $this->props['show_control_dot'] ) ?? 'off';
		$control_dot_style  = sanitize_text_field( $this->props['control_dot_style'] ) ?? 'solid_dot';

		$cat_args = array(
			'taxonomy'   => 'product_cat',
			'number'     => absint( $number_of_categories ),
		    'orderby'    => $order_by,
		    'order'      => $order,
		    'include'    => $include_categories,
		    'hide_empty' => ( 'on' === $hide_empty ) ? true : false,
		);

		$product_categories = get_terms( $cat_args );
		if ( $product_categories && ! is_wp_error( $product_categories ) ) {

			// Load the scripts.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        	wp_enqueue_style( 'dipl-woo-categories-carousel-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/WooProductsCategoriesCarousel/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'elicus-swiper-script' );
			wp_enqueue_style( 'elicus-swiper-style' );
			wp_enqueue_style( 'dipl-swiper-style' );

			$category_items = '';
			foreach ( $product_categories as $product_cat ) {

				$category_link = get_term_link( $product_cat->term_id, 'product_cat' );

				$category_items .= '<div class="swiper-slide dipl_woo_products_category_item">';
				if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/woo-products-categories-carousel/' . sanitize_file_name( $category_layout ) . '.php' ) ) {
					include get_stylesheet_directory() . '/divi-plus/layouts/woo-products-categories-carousel/' . sanitize_file_name( $category_layout ) . '.php';
				} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $category_layout ) . '.php' ) ) {
					include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $category_layout ) . '.php';
				}
				$category_items .= '</div>';
			}

			// Arrows.
			$slider_arrows = '';
			if ( 'on' === $show_arrow ) {
				// Next arrow.
				$next_arrow = ! empty( $this->props['next_slide_arrow'] ) ? et_pb_process_font_icon( $this->props['next_slide_arrow'] ) : '';
				$next = sprintf(
					'<div class="swiper-button-next"%1$s></div>',
					'' !== $next_arrow ? sprintf( ' data-next_slide_arrow="%1$s"', esc_attr( $next_arrow ) ) : ''
				);
				// Previous arrow.
				$prev_arrow = ! empty( $this->props['previous_slide_arrow'] ) ? et_pb_process_font_icon( $this->props['previous_slide_arrow'] ) : '';
				$prev = sprintf(
					'<div class="swiper-button-prev"%1$s></div>',
					'' !== $prev_arrow ? sprintf( ' data-previous_slide_arrow="%1$s"', esc_attr( $prev_arrow ) ) : ''
				);

				if ( ! empty( $arrows_position ) ) {
					wp_enqueue_script( 'dipl-woo-categories-carousel-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/WooProductsCategoriesCarousel/dipl-woo-categories-carousel.min.js", array('jquery'), '1.0.0', true );
					$arrows_position_data = '';
					foreach( $arrows_position as $device => $value ) {
						$arrows_position_data .= ' data-arrows_' . $device . '="' . $value . '"';
					}
				}

				$slider_arrows = sprintf(
					'<div class="dipl_swiper_navigation"%3$s>%1$s %2$s</div>',
					et_core_esc_previously( $next ),
					et_core_esc_previously( $prev ),
					! empty( $arrows_position ) ? $arrows_position_data : ''
				);
			}

			// Control dots.
			$control_dots = '';
			if ( 'on' === $show_control_dot ) {
				$control_dots = sprintf(
					'<div class="dipl_swiper_pagination"><div class="swiper-pagination %1$s"></div></div>',
					esc_attr( $control_dot_style )
				);
			}

			// Final render.
			$render_output = sprintf(
				'<div class="dipl_swiper_wrapper dipl_woo_products_categories_carousel_wrapper %1$s">
					<div class="dipl_woo_products_categories_carousel_layout dipl_swiper_inner_wrap">
						<div class="swiper-container">
							<div class="swiper-wrapper">%2$s</div>
						</div><!-- /.swiper-container -->
						%3$s
					</div>
					%4$s
				</div>',
				esc_attr( $category_layout ),
				et_core_esc_previously( $category_items ),
				et_core_esc_previously( $slider_arrows ),
				et_core_esc_previously( $control_dots )
			);

			// Add script for slider.
			$render_output .= $this->dipl_render_slider_script();

			// Thumbnail style.
			if ( 'on' === $show_thumbnail ) {
				$thumbnail_height = et_pb_responsive_options()->get_property_values( $this->props, 'thumbnail_height' );
				if ( ! empty( array_filter( $thumbnail_height ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $thumbnail_height, '%%order_class%% .dipl_woo_product_category_thumbnail img', 'height', $render_slug, '!important;', 'range' );
				}
				if ( ! empty( $this->props['thumbnail_bg_color'] ) ) {
					$this->generate_styles( array(
						'base_attr_name' => 'thumbnail_bg_color',
						'selector'       => '%%order_class%% .dipl_woo_product_category_thumbnail',
						'hover_selector' => '%%order_class%% .dipl_woo_product_category_thumbnail:hover',
						'css_property'   => 'background',
						'render_slug'    => $render_slug,
						'type'           => 'color',
					) );
				}
			}

			// Content box.
			if ( ! empty( $this->props['content_box_bg_color'] ) ) {
				$this->generate_styles( array(
					'base_attr_name' => 'content_box_bg_color',
					'selector'       => '%%order_class%% .dipl_woo_product_category_content',
					'hover_selector' => '%%order_class%% .dipl_woo_product_category_content:hover',
					'css_property'   => 'background',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );
			}

			// Arrows.
			if ( 'on' === $show_arrow ) {
				// Arrows icons.
				if ( '' !== $this->props['next_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-next::after',
						'declaration' => 'display: flex; align-items: center; height: 100%; content: attr(data-next_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'next_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl_swiper_navigation .swiper-button-next::after',
							'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
						) );
					}
				}
				if ( '' !== $this->props['previous_slide_arrow'] ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev::after',
						'declaration' => 'display: flex; align-items: center; height: 100%; content: attr(data-previous_slide_arrow);',
					) );
					if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
						$this->generate_styles( array(
							'utility_arg'    => 'icon_font_family',
							'render_slug'    => $render_slug,
							'base_attr_name' => 'previous_slide_arrow',
							'important'      => true,
							'selector'       => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev::after',
							'processor'      => array( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ),
						) );
					}
				}

				// Font size for arrows.
				$arrow_font_size = et_pb_responsive_options()->get_property_values( $this->props, 'arrow_font_size' );
				if ( ! empty( array_filter( $arrow_font_size ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $arrow_font_size, '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next', 'font-size', $render_slug, '!important;', 'range' );
				}
				$show_arrow_on_hover = $this->props['show_arrow_on_hover'];
				if ( 'on' === $show_arrow_on_hover ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev',
						'declaration' => 'visibility: hidden; opacity: 0; transition: all 300ms ease;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => 'visibility: hidden; opacity: 0; transition: all 300ms ease;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_swiper_navigation .swiper-button-prev, %%order_class%%:hover .dipl_swiper_navigation .swiper-button-next',
						'declaration' => 'visibility: visible; opacity: 1;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_swiper_navigation .swiper-button-prev.swiper-button-disabled, %%order_class%%:hover .dipl_swiper_navigation .swiper-button-next.swiper-button-disabled',
						'declaration' => 'opacity: 0.35;',
					) );
					// Outside Slider.
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_outside .swiper-button-prev',
						'declaration' => 'left: 50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_outside .swiper-button-next',
						'declaration' => 'right: 50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_outside .swiper-button-prev',
						'declaration' => 'left: 0;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_outside .swiper-button-next',
						'declaration' => 'right: 0;',
					) );
					// Inside Slider.
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_inside .swiper-button-prev',
						'declaration' => 'left: -50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_arrows_inside .swiper-button-next',
						'declaration' => 'right: -50px;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_inside .swiper-button-prev',
						'declaration' => 'left: 0;',
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%%:hover .dipl_arrows_inside .swiper-button-next',
						'declaration' => 'right: 0;',
					) );
				}

				$arrow_color = $this->props['arrow_color'];
				if ( $arrow_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => sprintf( 'color: %1$s !important;', esc_attr( $arrow_color ) ),
					) );
				}
				$arrow_color_hover = $this->get_hover_value( 'arrow_color' );
				if ( $arrow_color_hover ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev:hover, %%order_class%% .dipl_swiper_navigation .swiper-button-next:hover',
						'declaration' => sprintf( 'color: %1$s !important;', esc_attr( $arrow_color_hover ) ),
					) );
				}

				$arrow_background_color = $this->props['arrow_background_color'];
				if ( '' !== $arrow_background_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev, %%order_class%% .dipl_swiper_navigation .swiper-button-next',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $arrow_background_color ) ),
					) );
				}
				$arrow_background_color_hover = $this->get_hover_value( 'arrow_background_color' );
				if ( '' !== $arrow_background_color_hover ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_navigation .swiper-button-prev:hover, %%order_class%% .dipl_swiper_navigation .swiper-button-next:hover',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $arrow_background_color_hover ) ),
					) );
				}
			}

			if ( 'on' === $show_control_dot ) {
				$control_dot_inactive_color = $this->props['control_dot_inactive_color'];
				if ( $control_dot_inactive_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .swiper-pagination-bullet',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $control_dot_inactive_color ) ),
					) );
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .transparent_dot .swiper-pagination-bullet',
						'declaration' => sprintf( 'border-color: %1$s;', esc_attr( $control_dot_inactive_color ) ),
					) );
				}
				$control_dot_active_color = $this->props['control_dot_active_color'];
				if ( $control_dot_active_color ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .swiper-pagination-bullet.swiper-pagination-bullet-active',
						'declaration' => sprintf( 'background: %1$s !important;', esc_attr( $control_dot_active_color ) ),
					) );
				}
				$slide_transition_duration = $this->props['control_dot_active_color'];
				if ( 'stretched_dot' === $this->props['control_dot_style'] && $slide_transition_duration ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .dipl_swiper_pagination .stretched_dot .swiper-pagination-bullet',
						'declaration' => sprintf( 'transition: all %1$sms ease;', intval( $slide_transition_duration ) ),
					) );
				}
				$number_dot_text_color = $this->props['number_dot_text_color'];
				if ( ! empty( $number_dot_text_color ) ) {
					self::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .swiper-pagination-bullet.number-bullet',
						'declaration' => sprintf( 'color: %1$s;', esc_attr( $number_dot_text_color ) ),
					) );
				}
			}

			// Liner transition.
			if ( 'on' === $this->props['enable_linear_transition'] ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .swiper-wrapper',
					'declaration' => 'transition-timing-function : linear !important;',
				) );
			}

			$fields = array( 'categories_carousel_spacing' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

			$args = array(
				'render_slug'	=> $render_slug,
				'props'			=> $this->props,
				'fields'		=> $this->fields_unprocessed,
				'module'		=> $this,
				'backgrounds' 	=> array(
					'category_background_bg' => array(
						'normal' => "{$this->main_css_element} .dipl_woo_products_category_item",
						'hover' => "{$this->main_css_element} .dipl_woo_products_category_item:hover",
					)
				),
			);
			DiviPlusHelper::process_background( $args );
			
		} else {
			$no_result_text = esc_html( $this->props['no_result_text'] ) ?? '';
			$render_output  = sprintf( '<div class="entry">%1$s</div>', esc_html( $no_result_text ) );
		}

		self::$rendering = false;
		return $render_output;
	}

	/**
	 * This function dynamically creates script parameters according to the user settings
	 *
	 * @return string
	 * */
	public function dipl_render_slider_script() {
		$order_class     		= $this->get_module_order_class( 'dipl_woo_products_categories_carousel' );
		$show_arrow            	= esc_attr( $this->props['show_arrow'] );
		$show_control_dot       = esc_attr( $this->props['show_control_dot'] );
		$loop                  	= esc_attr( $this->props['slider_loop'] );
		$autoplay              	= esc_attr( $this->props['autoplay'] );
		$autoplay_speed        	= intval( $this->props['autoplay_speed'] );
		$transition_duration  	= intval( $this->props['transition_duration'] );
		$pause_on_hover        	= esc_attr( $this->props['pause_on_hover'] );
		$items_per_slide 		= et_pb_responsive_options()->get_property_values( $this->props, 'items_per_slide', '', true );
		$space_between_slides 	= et_pb_responsive_options()->get_property_values( $this->props, 'space_between_slides', '', true );
		$slides_per_group 		= et_pb_responsive_options()->get_property_values( $this->props, 'slides_per_group', '', true );
		$dynamic_bullets		= 'on' === $this->props['enable_dynamic_dots'] && in_array( $this->props['control_dot_style'], array( 'solid_dot', 'transparent_dot', 'square_dot' ), true ) ? 'true' : 'false';

		$autoplay_speed      		= '' !== $autoplay_speed || 0 !== $autoplay_speed ? $autoplay_speed : 3000;
		$transition_duration 		= '' !== $transition_duration || 0 !== $transition_duration ? $transition_duration : 1000;
		$loop          				= 'on' === $loop ? 'true' : 'false';
		$arrows 					= 'false';
		$dots 						= 'false';
		$autoplaySlides				= 0;
		$slidesPerGroup 			= 1;
		$slidesPerGroupIpad			= 1;
		$slidesPerGroupMobile		= 1;
		$slidesPerGroupSkip			= 0;
		$slidesPerGroupSkipIpad		= 0;
		$slidesPerGroupSkipMobile	= 0;

		$items_per_view        		= $items_per_slide['desktop'];
		$items_per_view_ipad   		= '' !== $items_per_slide['tablet'] ? $items_per_slide['tablet'] : $this->props['items_per_slide_tablet'];
		$items_per_view_mobile 		= '' !== $items_per_slide['phone'] ? $items_per_slide['phone'] : $this->props['items_per_slide_phone'];
		$items_space_between   		= $space_between_slides['desktop'];
		$items_space_between_ipad   = '' !== $space_between_slides['tablet'] ? $space_between_slides['tablet'] : $items_space_between;
		$items_space_between_mobile = '' !== $space_between_slides['phone'] ? $space_between_slides['phone'] : $items_space_between_ipad;
		$slidesPerGroup 			= $slides_per_group['desktop'];
		$slidesPerGroupIpad			= '' !== $slides_per_group['tablet'] ? $slides_per_group['tablet'] : $slidesPerGroup;
		$slidesPerGroupMobile		= '' !== $slides_per_group['phone'] ? $slides_per_group['phone'] : $slidesPerGroupIpad;

		if ( $items_per_view > $slidesPerGroup && 1 !== $slidesPerGroup ) {
			$slidesPerGroupSkip = $items_per_view - $slidesPerGroup;
		}
		if ( $items_per_view_ipad > $slidesPerGroupIpad && 1 !== $slidesPerGroupIpad ) {
			$slidesPerGroupSkipIpad = $items_per_view_ipad - $slidesPerGroupIpad;
		}
		if ( $items_per_view_mobile > $slidesPerGroupMobile && 1 !== $slidesPerGroupMobile ) {
			$slidesPerGroupSkipMobile = $items_per_view_mobile - $slidesPerGroupMobile;
		}

		if ( 'on' === $show_arrow ) {
			$arrows = "{    
				nextEl: '." . esc_attr( $order_class ) . " .swiper-button-next',
				prevEl: '." . esc_attr( $order_class ) . " .swiper-button-prev',
			}";
		}
		if ( 'on' === $show_control_dot ) {
			$dots = "{
				el: '." . esc_attr( $order_class ) . " .swiper-pagination',
				dynamicBullets: " . $dynamic_bullets . ",
				clickable: true,
			}";
		}
		if ( 'on' === $autoplay ) {
			if ( 'on' === $pause_on_hover ) {
				$autoplaySlides = '{
					delay:' . $autoplay_speed . ',
					disableOnInteraction: true,
				}';
			} else {
				$autoplaySlides = '{
					delay:' . $autoplay_speed . ',
					disableOnInteraction: false,
				}';
			}
		}

		$script  = '<script>';
		$script .= 'jQuery(function($) {';
		$script .= 'var ' . esc_attr( $order_class ) . '_swiper = new Swiper(\'.' . esc_attr( $order_class ) . ' .swiper-container\', {
					slidesPerView: ' . $items_per_view . ',
					slidesPerGroup: ' . $slidesPerGroup . ',
					slidesPerGroupSkip: ' . $slidesPerGroupSkip . ',
					autoplay: ' . $autoplaySlides . ',
					spaceBetween: ' . intval( $items_space_between ) . ',
					speed: ' . $transition_duration . ',
					loop: ' . $loop . ',
					pagination: ' . $dots . ',
					navigation: ' . $arrows . ',
					grabCursor: \'true\',
					observer: true,
					observeParents: true,
					breakpoints: {
						981: {
							slidesPerView: ' . $items_per_view . ',
							spaceBetween: ' . intval( $items_space_between ) . ',
							slidesPerGroup: ' . $slidesPerGroup . ',
							slidesPerGroupSkip: ' . $slidesPerGroupSkip . ',
						},
						768: {
							slidesPerView: ' . $items_per_view_ipad . ',
							spaceBetween: ' . intval( $items_space_between_ipad ) . ',
							slidesPerGroup: ' . $slidesPerGroupIpad . ',
							slidesPerGroupSkip: ' . $slidesPerGroupSkipIpad . ',
						},
						0: {
							slidesPerView: ' . $items_per_view_mobile . ',
							spaceBetween: ' . intval( $items_space_between_mobile ) . ',
							slidesPerGroup: ' . $slidesPerGroupMobile . ',
							slidesPerGroupSkip: ' . $slidesPerGroupSkipMobile . ',
						}
					},
			} );';

		if ( 'on' === $pause_on_hover && 'on' === $autoplay ) {
			$script .= '$(".' . esc_attr( $order_class ) . ' .swiper-container").on("mouseenter", function(e) {
				if ( typeof ' . esc_attr( $order_class ) . '_swiper.autoplay.stop === "function" ) {
					' . esc_attr( $order_class ) . '_swiper.autoplay.stop();
				}
			} );';
            $script .= '$(".' . esc_attr( $order_class ) . ' .swiper-container").on("mouseleave", function(e) {
				if ( typeof ' . esc_attr( $order_class ) . '_swiper.autoplay.start === "function" ) {
					' . esc_attr( $order_class ) . '_swiper.autoplay.start();
				}
			} );';
		}
		if ( 'true' !== $loop ) {
			$script .=  esc_attr( $order_class ) . '_swiper.on(\'reachEnd\', function(){
				' . esc_attr( $order_class ) . '_swiper.autoplay = false;
			} );';
		}

		$script .= '});</script>';

		return $script;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if (
		in_array( 'dipl_woo_products_categories_carousel', $modules, true ) &&
		et_is_woocommerce_plugin_active()
	) {
		new DIPL_WooProductsCategoriesCarousel();
	}
} else {
	if ( et_is_woocommerce_plugin_active() ) {
		new DIPL_WooProductsCategoriesCarousel();
	}
}
