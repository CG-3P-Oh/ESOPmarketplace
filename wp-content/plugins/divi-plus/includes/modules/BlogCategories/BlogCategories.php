<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.19.0
 */
class DIPL_BlogCategories extends ET_Builder_Module {
	public $slug       = 'dipl_blog_categories';
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
		$this->name             = esc_html__( 'DP Blog Categories', 'divi-plus' );
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
					'title_text'    => esc_html__( 'Title', 'divi-plus' ),
					'post_count'    => esc_html__( 'Post Count', 'divi-plus' ),
					'thumbnail'     => esc_html__( 'Thumbnail', 'divi-plus' ),
					'category_item' => esc_html__( 'Category Item', 'divi-plus' ),
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
						'default'        => '20px',
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
					'text_align' => array(
						'default' => 'center',
					),
					'css' => array(
						'main'      => "{$this->main_css_element} .dipl_blog_category_name",
						'important' => 'all',
					),
					'tab_slug'	  => 'advanced',
                    'toggle_slug' => 'title_text',
				),
				'post_count' => array(
					'label'     => esc_html__( 'Post Count', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_align' => array(
						'default' => 'center',
					),
					'css' => array(
						'main'       => "{$this->main_css_element} .dipl_blog_category_count",
						'text_align' => "{$this->main_css_element} .dipl_blog_category_count_wrap",
						'important'  => 'all',
					),
					'show_if'         => array( 'show_post_count' => 'on' ),
					'depends_on'      => array( 'show_post_count' ),
					'depends_show_if' => 'on',
					'tab_slug'	      => 'advanced',
					'toggle_slug'     => 'post_count',
				),
			),
			'borders' => array(
				'thumbnail' => array(
					'label_prefix' => esc_html__( 'Thumbnail', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_blog_category_item .dipl_category_image_wrapper img",
							'border_styles' => "%%order_class%% .dipl_blog_category_item .dipl_category_image_wrapper img",
						),
						'important' => 'all',
					),
					'depends_on'          => array( 'layout' ),
					'depends_show_if_not' => 'layout1',
					'tab_slug'            => 'advanced',
					'toggle_slug'         => 'thumbnail',
				),
				'category_item' => array(
					'label_prefix' => esc_html__( 'Category', 'divi-plus' ),
					'defaults' => array(
						'border_radii' => 'on||||',
						'border_styles' => array(
							'width' => '1px',
							'color' => '#dddddd',
							'style' => 'solid',
						),
					),
					'css' => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_blog_categories_wrapper .dipl_blog_category_item",
							'border_styles' => "%%order_class%% .dipl_blog_categories_wrapper .dipl_blog_category_item",
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'category_item',
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
						'main'      => "%%order_class%% .dipl_blog_category_image_wrapper",
						'important' => 'all',
					),
					'depends_on'          => array( 'layout' ),
					'depends_show_if_not' => 'layout1',
					'tab_slug'            => 'advanced',
					'toggle_slug'         => 'thumbnail',
				),
				'category_item' => array(
					'label'       => esc_html__( 'Category Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main'      => "%%order_class%% .dipl_blog_categories_wrapper .dipl_blog_category_item",
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
				'post_count' => array(
					'margin_padding' => array(
						'css' => array(
							'margin'    => "%%order_class%% .dipl_blog_category_count",
							'padding'   => "%%order_class%% .dipl_blog_category_count",
							'important' => 'all',
						),
					),
				),
				'thumbnail' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_blog_category_item .dipl_category_image_wrapper",
							'important'  => 'all',
						),
					),
				),
				'category_item' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_blog_categories_wrapper .dipl_blog_category_item",
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
					'description'      => esc_html__( 'Here you can specify the total number of categories to display.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'include_categories' => array(
					'label'            => esc_html__( 'Include Categories', 'divi-plus' ),
					'type'             => 'categories',
					'renderer_options' => array(
						'use_terms'  => true,
						'term_name'  => 'category',
						'field_name' => 'et_pb_include_dipl_category',
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
						'count'      => esc_html__( 'Post Count', 'divi-plus' ),
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
					'toggle_slug'     => 'main_content',
					'description'     => esc_html__( 'Here you can define custom no result text.', 'divi-plus' ),
				),
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
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can select the layout to display categories.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'number_of_columns' => array(
					'label'           => esc_html__( 'Number of Columns', 'divi-plus' ),
					'type'            => 'select',
					'option_category' => 'layout',
					'options'         => array(
						'1' => esc_html__( '1', 'divi-plus' ),
						'2' => esc_html__( '2', 'divi-plus' ),
						'3' => esc_html__( '3', 'divi-plus' ),
						'4' => esc_html__( '4', 'divi-plus' ),
						'5' => esc_html__( '5', 'divi-plus' ),
						'6' => esc_html__( '6', 'divi-plus' ),
						'7' => esc_html__( '7', 'divi-plus' ),
					),
					'default'         => '3',
					'mobile_options'  => true,
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Here you can choose the number of categories you want to display row.', 'divi-plus' ),
				),
				'items_gap' => array(
					'label'           => esc_html__( 'Items Gap', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '0',
						'max'  => '150',
						'step' => '1',
					),
					'fixed_unit'	  => 'px',
					'default'         => '30px',
					'mobile_options'  => true,
					'toggle_slug'     => 'display',
					'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the gap/space between items.', 'divi-plus' ),
				),
				'show_post_count' => array(
					'label'            => esc_html__( 'Show Post Count', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Choose whether or not the post count should be visible.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'show_sup_number' => array(
					'label'            => esc_html__( 'Show as Super Number', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'off',
					'show_if' 		   => array(
						'show_post_count' => 'on',
					),
					'show_if_not'      => array(
						'layout' => 'layout3',
					),
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Enable or disable to display post count as super number with category title.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'post_count_text' => array(
					'label'            => esc_html__( 'Post Count Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'          => esc_html__( 'Articles', 'divi-plus' ),
					'show_if' 		   => array(
						'show_post_count' => 'on',
						'show_sup_number' => 'off'
					),
					'show_if_not'      => array(
						'layout' => 'layout3',
					),
					'toggle_slug'      => 'display',
					'description'      => esc_html__( 'Here you can specify the text to display with post count number.', 'divi-plus' ),
					'computed_affects' => array(
						'__categories_data',
					),
				),
				'post_count_bg_color' => array(
					'label'        => esc_html__( 'Post Count Background', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'      => '',
					'hover'        => 'tabs',
					'show_if'      => array( 'show_post_count' => 'on' ),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_count',
					'description'  => esc_html__( 'Here you can choose a custom background color to be used for post count.', 'divi-plus' ),
				),
				'post_count_custom_margin' => array(
					'label'            => esc_html__( 'Post Count Margin', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '||||true|true',
					'default_on_front' => '||||true|true',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'          => array(
						'show_post_count' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_count',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'post_count_custom_padding' => array(
					'label'            => esc_html__( 'Post Count Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '||||true|true',
					'default_on_front' => '||||true|true',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'          => array(
						'show_post_count' => 'on',
					),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_count',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'enable_image_width' => array(
					'label'           => esc_html__( 'Enable Image Custom Width', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if_not'     => array( 'layout' => 'layout1' ),
					'default'         => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'thumbnail',
					'description'     => esc_html__( 'Choose whether or not to enable the custom width of category image.', 'divi-plus' ),
				),
				'image_width' => array(
					'label'           => esc_html__( 'Image Width', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '100',
						'max'  => '700',
						'step' => '1',
					),
					'default'         => '120px',
					'default_unit'    => 'px',
					'allowed_units'   => array( '%', 'em', 'rem', 'px' ),
					'allowed_values'  => et_builder_get_acceptable_css_string_values( 'width' ),
					'mobile_options'  => true,
					'show_if'         => array( 'enable_image_width' => 'on' ),
					'show_if_not'     => array( 'layout' => 'layout1' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'thumbnail',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the logo.', 'divi-plus' ),
				),
				'enable_image_height' => array(
					'label'           => esc_html__( 'Enable Image Custom Height', 'divi-plus' ),
					'type'            => 'yes_no_button',
					'option_category' => 'configuration',
					'options'         => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'show_if_not'     => array( 'layout' => 'layout1' ),
					'default'         => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'thumbnail',
					'description'     => esc_html__( 'Choose whether or not to enable the custom height of category image.', 'divi-plus' ),
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
					'default'         => '120px',
					'default_unit'    => 'px',
					'allowed_units'   => array( '%', 'em', 'rem', 'px' ),
					'allowed_values'  => et_builder_get_acceptable_css_string_values( 'height' ),
					'mobile_options'  => true,
					'show_if'         => array( 'enable_image_height' => 'on' ),
					'show_if_not'     => array( 'layout' => 'layout1' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'thumbnail',
					'description'     => esc_html__( 'Move the slider or input the value to increase or decrease width of the logo.', 'divi-plus' ),
				),
				'thumbnail_custom_padding' => array(
					'label'            => esc_html__( 'Thumbnail Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '||||true|true',
					'default_on_front' => '||||true|true',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if_not'      => array( 'layout' => 'layout1' ),
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'thumbnail',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'category_overlay_bg_color' => array(
					'label'             => esc_html__( 'Item Overlay Background', 'divi-plus' ),
					'type'              => 'background-field',
					'base_name'         => 'category_background_bg',
					'context'           => 'category_background_bg_color',
					'option_category'   => 'button',
					'custom_color'      => true,
					'background_fields' => array_merge(
						$this->generate_background_options( 'category_overlay_bg', 'color', 'advanced', 'category_item', 'category_overlay_bg_color' ),
						$this->generate_background_options( 'category_overlay_bg', 'gradient', 'advanced', 'category_item', 'category_overlay_bg_color' ),
					),
					'hover'             => true,
					'mobile_options'    => true,
					'show_if'           => array(
						'layout' => 'layout1',
					),
					'tab_slug'          => 'advanced',
					'toggle_slug'       => 'category_item',
					'description'       => esc_html__( 'Here you can set the background category item, color, and gradient to show on mouse hover.', 'divi-plus' ),
				),
				'category_item_bg_color' => array(
	                'label'             => esc_html__( 'Category Item Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'category_item_bg',
	                'context'           => 'category_item_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'category_item_bg', 'button', 'advanced', 'category_item', 'category_item_bg_color' ),
	                'hover'             => true,
					'mobile_options'    => true,
					'show_if_not'       => array(
						'layout' => 'layout1',
					),
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'category_item',
	                'description'       => esc_html__( 'Here you can adjust the background style of the play/pause button by customizing the background color, gradient, and image.', 'divi-plus' ),
	            ),
				'category_item_custom_padding' => array(
					'label'            => esc_html__( 'Category Item Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'default'		   => '10px|10px|10px|10px|true|true',
					'default_on_front' => '10px|10px|10px|10px|true|true',
					'mobile_options'   => true,
					'hover'            => false,
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'category_item',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'__categories_data' => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'DIPL_BlogCategories', 'get_catgories_computed_data' ),
					'computed_depends_on' => array(
						'layout',
						'number_of_categories',
						'include_categories',
						'order_by',
						'order',
						'hide_empty',
						'show_post_count',
						'show_sup_number',
						'post_count_text',
						'title_level',
					),
				),
			),
			$this->generate_background_options( 'category_overlay_bg', 'skip', 'advanced', 'category_item', 'category_overlay_bg_color' ),
			$this->generate_background_options( 'category_item_bg', 'skip', 'advanced', 'category_item', 'category_item_bg_color' ),
		);
	}

	/**
	 * This function return values to react for front end builder.
	 */
	public static function get_catgories_computed_data( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'layout'               => 'layout1',
			'number_of_categories' => '10',
			'include_categories'   => '',
			'order_by'             => 'name',
			'order'	               => 'ASC',
			'hide_empty'           => 'off',
			'show_post_count'      => 'on',
			'show_sup_number'      => 'off',
			'post_count_text'      => '',
			'title_level'          => 'h3'
		);
		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = sanitize_text_field( et_()->array_get( $args, $key, $default ) );
		}

		$query_args = array(
			'taxonomy'   => 'category',
			'number'     => absint( $number_of_categories ),
			'orderby'    => $order_by,
			'order'      => $order,
			'include'    => $include_categories,
			'hide_empty' => ( 'on' === $hide_empty ) ? true : false,
		);
		$categories = get_terms( $query_args );

		$output_array = array();
		if ( $categories && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {

				$category_link = get_category_link( $category );
				$thumbnail_id  = get_term_meta( $category->term_id, 'dipl_category_thumbnail', true );

				$image_url = '';
				if ( 'layout1' === $layout && ! empty( $thumbnail_id ) ) {
					$image_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
				}

				$category_items = sprintf(
					'<div id="dipl_blog_category_%1$s" class="dipl_blog_category_item category_%2$s" %3$s>',
					esc_attr( $category->term_id ),
					esc_attr( $category->slug ),
					( 'layout1' === $layout && ! empty( $image_url ) ) ? "style='background-image: url($image_url);'" : ''
				);
				if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/blog-categories/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include get_stylesheet_directory() . '/divi-plus/layouts/blog-categories/' . sanitize_file_name( $layout ) . '.php';
				} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
				}
				$category_items .= '</div>';

				array_push( $output_array, $category_items );
			}
		}

		return $output_array;
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$layout                = sanitize_text_field( $this->props['layout'] ) ?? 'layout1';
		$number_of_categories  = sanitize_text_field( $this->props['number_of_categories'] ) ?? 10;
		$include_categories    = sanitize_text_field( $this->props['include_categories'] ) ?? '';
		$order_by              = sanitize_text_field( $this->props['order_by'] ) ?? 'name';
		$order                 = sanitize_text_field( $this->props['order'] ) ?? 'ASC';
		$hide_empty            = sanitize_text_field( $this->props['hide_empty'] ) ?? 'on';
		$no_result_text        = sanitize_text_field( $this->props['no_result_text'] ) ?? '';

		$show_post_count       = sanitize_text_field( $this->props['show_post_count'] ) ?? 'on';
		$show_sup_number       = sanitize_text_field( $this->props['show_sup_number'] ) ?? 'off';
		$post_count_text       = sanitize_text_field( $this->props['post_count_text'] );
		$title_level           = sanitize_text_field( $this->props['title_level'] );
		$processed_title_level = et_pb_process_header_level( $title_level, 'h3' );

		$enable_image_width    = $this->props['enable_image_width'] ?? 'off';
		$enable_image_height   = $this->props['enable_image_height'] ?? 'off';

		$cat_args = array(
			'taxonomy'   => 'category',
			'number'     => absint( $number_of_categories ),
		    'orderby'    => $order_by,
		    'order'      => $order,
		    'include'    => $include_categories,
		    'hide_empty' => ( 'on' === $hide_empty ) ? true : false,
		);

		$categories = get_terms( $cat_args );
		if ( $categories && ! is_wp_error( $categories ) ) {

			// Load the scripts.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        	wp_enqueue_style( 'dipl-blog-categories-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/BlogCategories/' . $file . '.min.css', array(), '1.0.0' );

			$category_items = '';
			foreach ( $categories as $category ) {

				$category_link = get_category_link( $category );
				$thumbnail_id  = get_term_meta( $category->term_id, 'dipl_category_thumbnail', true );

				$image_url = '';
				if ( 'layout1' === $layout && ! empty( $thumbnail_id ) ) {
					$image_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
				}

				$category_items .= sprintf(
					'<div id="dipl_blog_category_%1$s" class="dipl_blog_category_item category_%2$s" %3$s>',
					esc_attr( $category->term_id ),
					esc_attr( $category->slug ),
					( 'layout1' === $layout && ! empty( $image_url ) ) ? "style='background-image: url($image_url);'" : ''
				);
				if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/blog-categories/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include get_stylesheet_directory() . '/divi-plus/layouts/blog-categories/' . sanitize_file_name( $layout ) . '.php';
				} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
				}
				$category_items .= '</div>';
			}

			// Final render.
			$render_output = sprintf(
				'<div class="dipl_blog_categories_wrapper %1$s">
					<div class="dipl_blog_categories_inner">%2$s</div>
				</div>',
				esc_attr( $layout ),
				et_core_esc_previously( $category_items )
			);

			// Number of columns.
			$number_of_columns = et_pb_responsive_options()->get_property_values( $this->props, 'number_of_columns' );
			if ( ! empty( array_filter( $number_of_columns ) ) ) {
				foreach ( $number_of_columns as $device => &$value ) {
					$value = 'repeat(' . $value . ', 1fr)';
				}
				et_pb_responsive_options()->generate_responsive_css( $number_of_columns, '%%order_class%% .dipl_blog_categories_inner', 'grid-template-columns', $render_slug, '!important;', 'type' );
			}
			// Gap.
			$items_gap = et_pb_responsive_options()->get_property_values( $this->props, 'items_gap' );
			if ( ! empty( array_filter( $items_gap ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $items_gap, '%%order_class%% .dipl_blog_categories_inner', 'gap', $render_slug, '!important;', 'range' );
			}

			// If not layout 1.
			if ( 'layout1' !== $layout ) {
				// Image width.
				if ( 'on' === $enable_image_width ) {
					$image_width = et_pb_responsive_options()->get_property_values( $this->props, 'image_width' );
					if ( ! empty( array_filter( $image_width ) ) ) {
						et_pb_responsive_options()->generate_responsive_css( $image_width, '%%order_class%% .dipl_blog_category_item .dipl_category_image_wrapper img', 'width', $render_slug, '!important;', 'range' );
						et_pb_responsive_options()->generate_responsive_css( $image_width, '%%order_class%% .dipl_blog_category_item .dipl_category_image_wrapper img', 'min-width', $render_slug, '!important;', 'range' );
					}
				}
				// Image height.
				if ( 'on' === $enable_image_height ) {
					$image_height = et_pb_responsive_options()->get_property_values( $this->props, 'image_height' );
					if ( ! empty( array_filter( $image_height ) ) ) {
						et_pb_responsive_options()->generate_responsive_css( $image_height, '%%order_class%% .dipl_blog_category_item .dipl_category_image_wrapper img', 'height', $render_slug, '!important;', 'range' );
					}
				}
			}

			// Post count.
			if ( 'on' === $show_post_count ) {
				if ( ! empty( $this->props['post_count_bg_color'] ) ) {
					$this->generate_styles( array(
						'base_attr_name' => 'post_count_bg_color',
						'selector'       => '%%order_class%% .dipl_blog_category_count',
						'hover_selector' => '%%order_class%% .dipl_blog_category_count:hover',
						'css_property'   => 'background',
						'render_slug'    => $render_slug,
						'type'           => 'color',
					) );
				}
			}

			$fields = array( 'categories_carousel_spacing' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

			$args = array(
				'render_slug'	=> $render_slug,
				'props'			=> $this->props,
				'fields'		=> $this->fields_unprocessed,
				'module'		=> $this,
				'backgrounds' 	=> array(
					'category_overlay_bg' => array(
						'normal' => "{$this->main_css_element} .dipl_blog_categories_wrapper.layout1 .dipl_blog_category_item::before",
						'hover' => "{$this->main_css_element} .dipl_blog_categories_wrapper.layout1 .dipl_blog_category_item:hover::before",
					),
					'category_item_bg' => array(
						'normal' => "{$this->main_css_element} .dipl_blog_categories_wrapper:not(.layout1) .dipl_blog_category_item",
						'hover' => "{$this->main_css_element} .dipl_blog_categories_wrapper:not(.layout1) .dipl_blog_category_item:hover",
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
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_blog_categories', $modules ) ) {
		new DIPL_BlogCategories();
	}
} else {
	new DIPL_BlogCategories();
}
