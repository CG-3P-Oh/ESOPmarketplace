<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.19.0
 */
class DIPL_Blog extends ET_Builder_Module {

	public $slug       = 'dipl_blog';
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
		$this->name             = esc_html__( 'DP Blog', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content'    => array(
						'title' => esc_html__( 'Layout', 'divi-plus' ),
					),
					'loop_query' => array(
						'title' => esc_html__( 'Query', 'divi-plus' ),
					),
					'display_settings' => array(
						'title' => esc_html__( 'Display Settings', 'divi-plus' ),
					),
					'pagination' => array(
						'title' => esc_html__( 'Pagination', 'divi-plus' ),
					),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text' => array(
						'title'    => esc_html__( 'Text', 'divi-plus' ),
						'priority' => 10,
					),
					'title_text' => array(
						'title'    => esc_html__( 'Title', 'divi-plus' ),
						'priority' => 20,
					),
					'body_text' => array(
						'title'             => esc_html__( 'Body', 'divi-plus' ),
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
						),
						'priority' => 30,
					),
					'post_meta' => array(
						'title' => esc_html__( 'Post Meta', 'divi-plus' ),
						'priority' => 40,
					),
					'category' => array(
						'title' => esc_html__( 'Category', 'divi-plus' ),
						'priority' => 50,
					),
					'read_more_settings' => array(
						'title' => esc_html__( 'Read More Settings', 'divi-plus' ),
						'priority' => 60,
					),
					'pagination' => array(
						'title'    => esc_html__( 'Pagination', 'divi-plus' ),
						'priority' => 70,
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'divi-plus' ),
						'priority' => 80,
					),
					'post_content' => array(
						'title'	=> esc_html__( 'Post Content', 'divi-plus' ),
						'priority' => 90,
					)
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
						'default'        => '20px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
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
						'validate_unit'  => true,
					),
					'css'            => array(
						'main'      => "{$this->main_css_element} .dipl_blog_post_title, {$this->main_css_element} .dipl_blog_post_title a",
						'important' => 'all',
					),
					'header_level'   => array(
						'default' => 'h2',
					),
				),
				'body'      => array(
					'label'          => esc_html__( 'Body', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
						'default'        => '1.5em',
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
					'css'            => array(
						'main' => "{$this->main_css_element} .dipl_blog_post_content, {$this->main_css_element} .dipl_blog_post_content p",
					),
					'tab_slug'		=> 'advanced',
					'toggle_slug'   => 'body_text',
					'sub_toggle'   	=> 'p',
				),
				'body_link' => array(
					'label'           => esc_html__( 'Link', 'divi-plus' ),
					'font_size'       => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.5em',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '5',
							'step' => '0.1',
						),
					),
					'letter_spacing'  => array(
						'default'        => '0px',
						'range_settings' => array(
							'min'  => '0',
							'max'  => '10',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl_blog_post_content a",
					),
					'tab_slug'		=> 'advanced',
					'toggle_slug'   => 'body_text',
					'sub_toggle'    => 'a',
				),
				'meta' => array(
					'label'          => esc_html__( 'Meta', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
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
						'validate_unit'  => true,
					),
					'css'            => array(
						'main' => "{$this->main_css_element} .dipl_blog_post_meta, {$this->main_css_element} .dipl_blog_post_meta span, {$this->main_css_element} .dipl_blog_post_meta a",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'post_meta',
				),
				'category' => array(
					'label'          => esc_html__( 'Category', 'divi-plus' ),
					'font_size'      => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'    => array(
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
						'validate_unit'  => true,
					),
					'css'            => array(
						'main' => "{$this->main_css_element} .dipl_blog_post_categories a",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'category',
				),
			),
			'button' => array(
				'read_more' => array(
					'label'           => esc_html__( 'Read More Button', 'divi-plus' ),
					'text_size'       => array(
						'default' => '16px',
					),
					'css'             => array(
						'main'      => "{$this->main_css_element} .dipl_blog_post_read_more_link .et_pb_button",
						'alignment' => "{$this->main_css_element} .dipl_blog_post_read_more_link",
						'important' => 'all',
					),
					'margin_padding'  => array(
						'css' => array(
							'margin'    => "{$this->main_css_element} .dipl_blog_post_read_more_link",
							'padding'   => "{$this->main_css_element} .dipl_blog_post_read_more_link .et_pb_button",
							'important' => 'all',
						),
					),
					'no_rel_attr'     => true,
					'use_alignment'   => true,
					'box_shadow'      => false,
					'depends_on'      => array( 'show_read_more' ),
					'depends_show_if' => 'on',
				),
			),
			'borders' => array(
				'image' => array(
					'label_prefix'    => esc_html__( 'Image', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_blog_post_image',
							'border_styles' => '%%order_class%% .dipl_blog_post_image',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'image',
				),
				'single_post' => array(
					'label_prefix' => esc_html__( 'Single Post', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_blog_post',
							'border_styles' => '%%order_class%% .dipl_blog_post',
							'important'     => 'all',
						),
					),
				),
				'default'     => array(
					'css' => array(
						'main'      => array(
							'border_radii'  => $this->main_css_element,
							'border_styles' => $this->main_css_element,
						),
						'important' => 'all',
					),
				),
			),
			'box_shadow' => array(
				'single_post' => array(
					'label'       => esc_html__( 'Single Post', 'divi-plus' ),
					'css'         => array(
						'main' => "{$this->main_css_element} .dipl_blog_post",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'box_shadow',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				),
			),
			'blog_margin_padding' => array(
                'post_content_wrapper' => array(
                    'margin_padding' => array(
                        'css' => array(
                            'padding'   	=> "{$this->main_css_element} .dipl_blog_post_content_wrapper",
                            'important'	 	=> 'all',
                        ),
                    ),
                ),
                'category' => array(
                    'margin_padding' => array(
                        'css' => array(
                            'margin'   		=> "{$this->main_css_element} .dipl_blog_layout1 .dipl_blog_post_categories a",
                            'padding'   	=> "{$this->main_css_element} .dipl_blog_layout1 .dipl_blog_post_categories a",
                            'important'	 	=> 'all',
                        ),
                    ),
                ),
                'meta_icon' => array(
                    'margin_padding' => array(
                        'css' => array(
                            'padding'   	=> "{$this->main_css_element} .dipl_blog_layout2 .dipl_blog_post_meta .et-pb-icon",
                            'important'	 	=> 'all',
                        ),
                    ),
                ),
            ),
			'margin_padding' => array(
				'css' => array(
					'margin'    => $this->main_css_element,
					'padding'   => $this->main_css_element,
					'important' => 'all',
				),
			),
			'height' => array(
				'css' => array(
					'main' => "{$this->main_css_element}",
				),
			),
			'text' 				=> false,
			'text_shadow'       => false,
		);
	}

	public function get_fields() {
		return array_merge(
			array(
				'layout' => array(
					'label'            => esc_html__( 'Layout', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'layout',
					'options'          => array(
						'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
						'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
						'layout3' => esc_html__( 'Layout 3', 'divi-plus' ),
					),
					'default'          => 'layout1',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Here you can select the layout.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'number_of_columns' => array(
	                'label'             => esc_html__( 'Number Of Columns', 'divi-plus' ),
	                'type'              => 'select',
	                'option_category'   => 'configuration',
	                'options'           => array(
	                    '1'         => esc_html( '1' ),
	                    '2'         => esc_html( '2' ),
	                    '3'         => esc_html( '3' ),
	                    '4'			=> esc_html( '4' ),
	                    '5'			=> esc_html( '5' ),
	                    '6'			=> esc_html( '6' ),
	                ),
	                'mobile_options'	=> true,
	                'default'			=> '3',
	                'default_on_front'	=> '3',
	                'show_if_not'       => array(
						'layout' => 'layout2',
					),
	                'tab_slug'          => 'general',
	                'toggle_slug'       => 'main_content',
	                'description'       => esc_html__( 'Here you can select the number of columns to display posts.', 'divi-plus' ),
	            ),
	            'column_spacing' => array(
	                'label'             => esc_html__( 'Column Spacing', 'divi-plus' ),
					'type'              => 'range',
					'option_category'  	=> 'layout',
					'range_settings'    => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'fixed_unit'		=> 'px',
					'fixed_range'       => true,
					'validate_unit'		=> true,
					'mobile_options'    => true,
					'default'           => '15px',
					'default_on_front'  => '15px',
					'show_if_not'       => array(
						'layout' => 'layout2',
					),
					'tab_slug'        	=> 'general',
					'toggle_slug'     	=> 'main_content',
					'description'       => esc_html__( 'Increase or decrease spacing between columns.', 'divi-plus' ),
	            ),
	            'row_spacing' => array(
	                'label'             => esc_html__( 'Row Spacing', 'divi-plus' ),
					'type'              => 'range',
					'option_category'  	=> 'layout',
					'range_settings'    => array(
						'min'   => '0',
						'max'   => '100',
						'step'  => '1',
					),
					'fixed_unit'		=> 'px',
					'fixed_range'       => true,
					'validate_unit'		=> true,
					'mobile_options'    => true,
					'default'           => '30px',
					'default_on_front'  => '30px',
					'tab_slug'        	=> 'general',
					'toggle_slug'     	=> 'main_content',
					'description'       => esc_html__( 'Increase or decrease spacing between posts.', 'divi-plus' ),
	            ),
	            'use_masonry' => array(
					'label'            => esc_html__( 'Enable Masonry', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'off',
					'default_on_front' => 'off',
					'show_if_not'       => array(
						'layout' => 'layout2',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'main_content',
					'description'      => esc_html__( 'Enable Masonry for posts.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'posts_number' => array(
					'label'            => esc_html__( 'Number of Posts', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'          => '10',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'Here you can define the value of number of posts you would like to display.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'offset_number' => array(
					'label'            => esc_html__( 'Post Offset Number', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'          => 0,
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'post_order' => array(
					'label'            => esc_html__( 'Order', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'DESC' => esc_html__( 'DESC', 'divi-plus' ),
						'ASC'  => esc_html__( 'ASC', 'divi-plus' ),
					),
					'default'          => 'DESC',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'Here you can choose the order of your posts.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'post_order_by' => array(
					'label'            => esc_html__( 'Order by', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'date'      => esc_html__( 'Date', 'divi-plus' ),
						'modified'  => esc_html__( 'Modified Date', 'divi-plus' ),
						'title'     => esc_html__( 'Title', 'divi-plus' ),
						'name'      => esc_html__( 'Slug', 'divi-plus' ),
						'ID'        => esc_html__( 'ID', 'divi-plus' ),
						'rand'      => esc_html__( 'Random', 'divi-plus' ),
						'relevance' => esc_html__( 'Relevance', 'divi-plus' ),
						'none'      => esc_html__( 'None', 'divi-plus' ),
					),
					'default'          => 'date',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'Here you can choose the order type of your posts.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'include_categories' => array(
					'label'            => esc_html__( 'Select Categories', 'divi-plus' ),
					'type'             => 'categories',
					'option_category'  => 'basic_option',
					'renderer_options' => array(
						'use_terms' => false,
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'Choose which categories you would like to include in the feed', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'post_date' => array(
					'label'            => esc_html__( 'Post Date Format', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'          => 'M j, Y',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'If you would like to adjust the date format, input the appropriate PHP date format here.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'ignore_sticky_posts' => array(
					'label'            => esc_html__( 'Ignore Sticky Posts', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'off',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'This will decide whether to ingnore sticky posts or not.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_thumbnail' => array(
					'label'            => esc_html__( 'Show Featured Image', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'This will turn thumbnails on or off.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'featured_image_size' => array(
					'label'            => esc_html__( 'Featured Image Size', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'medium' => esc_html__( 'Medium', 'divi-plus' ),
						'large'  => esc_html__( 'Large', 'divi-plus' ),
						'full'   => esc_html__( 'Full', 'divi-plus' ),
					),
					'show_if'          => array(
						'show_thumbnail' => 'on',
					),
					'default'          => 'large',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Here you can select the size of the featured image.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_content' => array(
					'label'            => esc_html__( 'Content', 'divi-plus' ),
					'type'             => 'select',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'Show Excerpt', 'divi-plus' ),
						'on'  => esc_html__( 'Show Content', 'divi-plus' ),
					),
					'default'          => 'off',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Showing the full content will not truncate your posts on the index page. Showing the excerpt will only display your excerpt text.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'excerpt_length' => array(
					'label'            => esc_html__( 'Excerpt Length', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'show_if'          => array(
						'show_content' => 'off',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Here you can define excerpt length in characters, if 0 no excerpt will be shown. However this won\'t work with the manual excerpt defined in the post.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'no_result_text' => array(
					'label'            => esc_html__( 'No Result Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'		   => esc_html__( 'The posts you requested could not be found. Try changing your module settings or create some new posts.', 'divi-plus' ),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'loop_query',
					'description'      => esc_html__( 'Here you can define custom no result text.', 'divi-plus' ),
				),
				'show_author' => array(
					'label'            => esc_html__( 'Show Author', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Turn on or off the Author link.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_date' => array(
					'label'            => esc_html__( 'Show Date', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Turn the Date on or off.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_categories' => array(
					'label'            => esc_html__( 'Show Categories/Terms', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Turn the category/terms links on or off.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_comments' => array(
					'label'            => esc_html__( 'Show Comment Count', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
						'off' => esc_html__( 'No', 'divi-plus' ),
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Turn Comment Count on and off.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_read_more' => array(
					'label'            => esc_html__( 'Show Read More', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'Off', 'divi-plus' ),
						'on'  => esc_html__( 'On', 'divi-plus' ),
					),
					'show_if'          => array(
						'show_content' => 'off',
					),
					'affects'          => array(
						'custom_read_more',
					),
					'default'          => 'on',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Here you can define whether to show "read more" link after the excerpts or not.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'read_more_text' => array(
					'label'            => esc_html__( 'Read More Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'show_if'          => array(
						'show_content'   => 'off',
						'show_read_more' => 'on',
					),
					'default'          => 'Read More',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'display_settings',
					'description'      => esc_html__( 'Here you can define "read more" button/link text.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'meta_separator_color' => array(
					'label'        => esc_html__( 'Meta Separator Color', 'divi-plus' ),
					'type'         => 'color',
					'custom_color' => true,
					'show_if'      => array(
						'layout' => array( 'layout1', 'layout2' )
					),
					'default'      => '#ddd',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_meta',
					'description'  => esc_html__( 'Here you can define a custom color for the post content meta separator.', 'divi-plus' ),
				),
				'meta_icon_font_size'  => array(
					'label'           => esc_html__( 'Meta Icon Font Size', 'divi-plus' ),
					'type'            => 'range',
					'option_category' => 'layout',
					'range_settings'  => array(
						'min'  => '10',
						'max'  => '100',
						'step' => '1',
					),
					'show_if'         => array(
						'layout' => array( 'layout2' ),
					),
					'mobile_options'  => true,
					'default'         => '22px',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'post_meta',
					'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the size of meta icons.', 'divi-plus' ),
				),
				'meta_icon_color'    => array(
					'label'        => esc_html__( 'Meta Icon Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'layout' => array( 'layout2' ),
					),
					'default'      => '#000',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_meta',
					'description'  => esc_html__( 'Here you can choose a custom color to be used for meta icon.', 'divi-plus' ),
				),
				'meta_icon_background_color'    => array(
					'label'        => esc_html__( 'Meta Icon Background Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'show_if'      => array(
						'layout' => array( 'layout2' ),
					),
					'default'      => '#ddd',
					'hover'        => 'tabs',
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_meta',
					'description'  => esc_html__( 'Here you can choose a custom background color to be used for meta icon.', 'divi-plus' ),
				),
				'meta_icon_custom_padding' => array(
					'label'            => esc_html__( 'Meta Icon Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'      => array(
						'layout' => array( 'layout2' ),
					),
					'default'          => '4px|10px|4px|10px|on|on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_meta',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'category_custom_margin' => array(
					'label'            => esc_html__( 'Category Margin', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'category',
					'description'      => esc_html__( 'Margin adds extra space to the outside of the element, increasing the distance between the element and other items on the page.', 'divi-plus' ),
				),
				'category_custom_padding' => array(
					'label'            => esc_html__( 'Category Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'show_if'      => array(
						'layout' => array( 'layout1' ),
					),
					'default'          => '4px|8px|4px|8px|on|on',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'category',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'category_background_color' => array(
	                'label'             => esc_html__( 'Category Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'button_bg',
	                'context'           => 'button_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'category_background', 'button', 'advanced', 'category', 'category_background_color' ),
	                'hover'             => 'tabs',
	                'mobile_options'    => true,
	                'default'      		=> '',
	                'show_if'      => array(
						'layout' => array( 'layout1' ),
					),
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'category',
	                'description'       => esc_html__( 'Customize the background style of the post content by adjusting the background color, gradient, and image.', 'divi-plus' ),
	            ),
				'show_pagination' => array(
					'label'            => esc_html__( 'Show Pagination', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'default'          => 'off',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'pagination',
					'description'      => esc_html__( 'Show Pagination or not.', 'divi-plus' ),
					'computed_affects' => array(
						'__blog_data',
					),
				),
				'show_prev_next' => array(
					'label'            => esc_html__( 'Show Previous Next Links', 'divi-plus' ),
					'type'             => 'yes_no_button',
					'option_category'  => 'configuration',
					'options'          => array(
						'off' => esc_html__( 'No', 'divi-plus' ),
						'on'  => esc_html__( 'Yes', 'divi-plus' ),
					),
					'show_if'      => array(
						'show_pagination' => 'on',
					),
					'default'          => 'off',
					'tab_slug'         => 'general',
					'toggle_slug'      => 'pagination',
					'description'      => esc_html__( 'Show Previous Next Links or not.', 'divi-plus' ),
				),
				'next_text' => array(
					'label'            => esc_html__( 'Next Link Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'		   => 'Next',
					'show_if'      => array(
						'show_pagination' => 'on',
						'show_prev_next'  => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'pagination',
					'description'      => esc_html__( 'Here you can define Next Link text in numbered pagination.', 'divi-plus' ),
				),
				'prev_text' => array(
					'label'            => esc_html__( 'Prev Link Text', 'divi-plus' ),
					'type'             => 'text',
					'option_category'  => 'configuration',
					'default'		   => 'Prev',
					'show_if'      => array(
						'show_pagination' => 'on',
						'show_prev_next'  => 'on',
					),
					'tab_slug'         => 'general',
					'toggle_slug'      => 'pagination',
					'description'      => esc_html__( 'Here you can define Previous Link text in numbered pagination.', 'divi-plus' ),
				),
				'pagination_link_background_color' => array(
					'label'        => esc_html__( 'Pagination Link Background Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'	   => 'transparent',
					'hover'		   => 'tabs',
					'show_if'      => array(
						'show_pagination' => 'on',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'pagination',
					'description'  => esc_html__( 'Here you can define a custom background color for the pagination link.', 'divi-plus' ),
				),
				'pagination_link_color' => array(
					'label'        => esc_html__( 'Pagination Link Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'hover'		   => 'tabs',
					'default'	   => '#000',
					'show_if'      => array(
						'show_pagination' => 'on',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'pagination',
					'description'  => esc_html__( 'Here you can define a custom color for the pagination link.', 'divi-plus' ),
				),
				'active_pagination_link_background_color' => array(
					'label'        => esc_html__( 'Active Pagination Link Background Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'	   => '#000',
					'hover'		   => 'tabs',
					'show_if'      => array(
						'show_pagination' => 'on',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'pagination',
					'description'  => esc_html__( 'Here you can define a custom background color for the active pagination link.', 'divi-plus' ),
				),
				'active_pagination_link_color' => array(
					'label'        => esc_html__( 'Active Pagination Link Color', 'divi-plus' ),
					'type'         => 'color-alpha',
					'custom_color' => true,
					'default'	   => '#fff',
					'hover'		   => 'tabs',
					'show_if'      => array(
						'show_pagination' => 'on',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'pagination',
					'description'  => esc_html__( 'Here you can define a custom color for the active pagination link.', 'divi-plus' ),
				),
				'post_content_wrapper_background_color' => array(
	                'label'             => esc_html__( 'Post Content Background', 'divi-plus' ),
	                'type'              => 'background-field',
	                'base_name'         => 'button_bg',
	                'context'           => 'button_bg_color',
	                'option_category'   => 'button',
	                'custom_color'      => true,
	                'background_fields' => $this->generate_background_options( 'post_content_wrapper_background', 'button', 'advanced', 'post_content', 'post_content_wrapper_background_color' ),
	                'hover'             => 'tabs',
	                'mobile_options'    => true,
	                'default'      		=> '',
	                'tab_slug'          => 'advanced',
	                'toggle_slug'       => 'post_content',
	                'description'       => esc_html__( 'Customize the background style of the post content by adjusting the background color, gradient, and image.', 'divi-plus' ),
	            ),
				'post_content_wrapper_custom_padding' => array(
					'label'            => esc_html__( 'Content Padding', 'divi-plus' ),
					'type'             => 'custom_padding',
					'option_category'  => 'layout',
					'mobile_options'   => true,
					'hover'            => false,
					'default'          => '',
					'default_on_front' => '',
					'tab_slug'         => 'advanced',
					'toggle_slug'      => 'post_content',
					'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
				),
				'__blog_data'                    => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'DIPL_Blog', 'get_blog_posts' ),
					'computed_depends_on' => array(
						'layout',
						'posts_number',
						'offset_number',
						'post_order',
						'post_order_by',
						'include_categories',
						'post_date',
						'ignore_sticky_posts',
						'use_masonry',
						'show_thumbnail',
						'featured_image_size',
						'show_content',
						'excerpt_length',
						'show_author',
						'show_date',
						'show_categories',
						'show_comments',
						'show_read_more',
						'read_more_text',
						'show_pagination',
						'custom_read_more',
						'read_more_icon',
						'title_level',
					),
				),
			),
			$this->generate_background_options( 'post_content_wrapper_background', 'skip', 'advanced', 'post_content', 'post_content_wrapper_background_color' ),
			$this->generate_background_options( 'category_background', 'skip', 'advanced', 'category', 'category_background_color' )
		);

	}

	public static function get_blog_posts( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		global $et_fb_processing_shortcode_object, $et_pb_rendering_column_content;

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

		$global_processing_original_value = $et_fb_processing_shortcode_object;

		$defaults = array(
			'layout'       		  => 'layout1',
			'posts_number'        => '10',
			'offset_number'       => '0',
			'post_date'           => 'M j, Y',
			'post_order'          => 'DESC',
			'post_order_by'       => 'date',
			'include_categories'  => '',
			'ignore_sticky_posts' => 'off',
			'use_masonry' 		  => 'off',
			'show_thumbnail'      => 'on',
			'featured_image_size' => 'large',
			'show_content'        => 'off',
			'excerpt_length'      => '',
			'show_read_more'      => 'on',
			'read_more_text'      => esc_html__( 'Read More', 'divi-plus' ),
			'show_author'         => 'on',
			'show_date'           => 'on',
			'show_categories'     => 'on',
			'show_comments'       => 'on',
			'show_pagination'	  => 'off',
			'custom_read_more'    => 'off',
			'read_more_icon'      => '',
			'title_level'         => 'h2',
		);

		// WordPress' native conditional tag is only available during page load. It'll fail during component update because
		// et_pb_process_computed_property() is loaded in admin-ajax.php. Thus, use WordPress' conditional tags on page load and
		// rely to passed $conditional_tags for AJAX call.
		$is_front_page     = (bool) et_fb_conditional_tag( 'is_front_page', $conditional_tags );
		$is_single         = (bool) et_fb_conditional_tag( 'is_single', $conditional_tags );
		$is_user_logged_in = (bool) et_fb_conditional_tag( 'is_user_logged_in', $conditional_tags );
		$current_post_id   = isset( $current_page['id'] ) ? (int) $current_page['id'] : 0;

		// remove all filters from WP audio shortcode to make sure current theme doesn't add any elements into audio module.
		remove_all_filters( 'wp_audio_shortcode_library' );
		remove_all_filters( 'wp_audio_shortcode' );
		remove_all_filters( 'wp_audio_shortcode_class' );

		$attrs = wp_parse_args( $attrs, $defaults );

		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		$processed_title_level = et_pb_process_header_level( $title_level, 'h2' );
		$processed_title_level = esc_html( $processed_title_level );

		if ( 'on' !== $show_content ) {
			$excerpt_length = ( '' === $excerpt_length ) ? 270 : intval( $excerpt_length );
		}

		$args = array(
			'post_type'      		=> 'post',
			'posts_per_page' 		=> intval( $posts_number ),
			'post_status'    		=> 'publish',
			'offset'         		=> 0,
			'orderby'        		=> 'date',
			'order'          		=> 'DESC',
		);

		if ( $is_user_logged_in ) {
			$args['post_status'] = array( 'publish', 'private' );
		}

		if ( 'on' === $ignore_sticky_posts ) {
			$query_args['ignore_sticky_posts'] = true;
		}

		if ( '' !== $include_categories ) {
			$args['cat'] = sanitize_text_field( $include_categories );
		}

		if ( '' !== $offset_number && ! empty( $offset_number ) ) {
			$args['offset'] = intval( $offset_number );
		}

		if ( 0 !== $args['offset'] && -1 === intval( $args['posts_per_page'] ) ) {
			$count_posts            = wp_count_posts( 'post', 'readable' );
			$published_posts        = $count_posts->publish;
			$args['posts_per_page'] = intval( $published_posts );
		}

		if ( isset( $post_order_by ) && '' !== $post_order_by ) {
			$args['orderby'] = sanitize_text_field( $post_order_by );
		}

		if ( isset( $post_order ) && '' !== $post_order ) {
			$args['order'] = sanitize_text_field( $post_order );
		}

		if ( $is_single && ! isset( $args['post__not_in'] ) ) {
			$args['post__not_in'] = array( intval( get_the_ID() ) );
		}

		if ( 'on' !== $show_content ) {
			$excerpt_length = ( '' === $excerpt_length ) ? 270 : intval( $excerpt_length );
		}

		if ( 'on' === $show_read_more ) {
			$read_more_text = ( ! isset( $read_more_text ) || '' === $read_more_text ) ?
			esc_html__( 'Read More', 'divi-plus' ) :
			sprintf(
				esc_html__( '%s', 'divi-plus' ),
				esc_html( $read_more_text )
			);
		}

		global $wp_the_query;
		$query_backup = $wp_the_query;

		$query = new WP_Query( $args );

		self::$rendering = true;

		$output = '';
		if ( $query->have_posts() ) {

			$output = '<div class="dipl_blog_wrapper dipl_blog_' . esc_attr( $layout ) . '">';

			if ( 'on' === $use_masonry ) {
				$output .= '<div class="dipl_blog_isotope_container">';
				$output .= '<div class="dipl_blog_isotope_item_gutter"></div>';
			}
			while ( $query->have_posts() ) {
				$query->the_post();

				$post_id        = intval( get_the_ID() );
				$thumb          = '';
				$thumb          = dipl_get_post_thumbnail( $post_id, esc_html( $featured_image_size ), 'dipl_blog_post_image' );
				$no_thumb_class = ( '' === $thumb || 'off' === $show_thumbnail ) ? ' dipl_blog_post_no_thumb' : '';

				$post_classes = array_map( 'sanitize_html_class', get_post_class( 'dipl_blog_post' . $no_thumb_class ) );
				$post_classes = implode( ' ', $post_classes );

				$read_more_button = dipl_render_divi_button(
					array(
						'button_text'         => et_core_esc_previously( $read_more_text ),
						'button_text_escaped' => true,
						'button_url'          => esc_url( get_permalink( $post_id ) ),
						'button_custom'       => et_core_esc_previously( $custom_read_more ),
						'custom_icon'         => et_core_esc_previously( $read_more_icon ),
						'has_wrapper'         => false,
					)
				);

				if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/blog/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include get_stylesheet_directory() . '/divi-plus/layouts/blog/' . sanitize_file_name( $layout ) . '.php';
				} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
				}
			}

			wp_reset_postdata();

			//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$wp_the_query = $query_backup;

			if ( 'on' === $use_masonry ) {
				$output .= '</div>';
			}

			$output .= '</div>';

			if ( 'on' === $show_pagination ) {
				if ( -1 === intval( $posts_number ) ) {
					$total_pages = 1;
				} else if ( '' !== $offset_number && ! empty( $offset_number ) ) {
					$total_pages = intval( ceil( ( $query->found_posts - $offset_number ) / intval( $posts_number ) ) );
				} else {
					$total_pages = intval( ceil( $query->found_posts / intval( $posts_number ) ) );
				}

				$pagination = sprintf(
					'<div class="dipl_blog_pagination_wrapper" data-total_pages="%1$s">
						<ul></ul>
					</div>',
					$total_pages
				);

				$output .= $pagination;
			}

		}

		self::$rendering = false;

		return $output;
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

		$layout                   		= $this->props['layout'];
		$posts_number                   = $this->props['posts_number'];
		$post_date                      = $this->props['post_date'];
		$offset_number                  = $this->props['offset_number'];
		$post_order                     = $this->props['post_order'];
		$post_order_by                  = $this->props['post_order_by'];
		$include_categories             = $this->props['include_categories'];
		$ignore_sticky_posts            = $this->props['ignore_sticky_posts'];
		$use_masonry					= $this->props['use_masonry'];
		$show_thumbnail                 = $this->props['show_thumbnail'];
		$featured_image_size            = $this->props['featured_image_size'];
		$show_content                   = $this->props['show_content'];
		$show_read_more                 = $this->props['show_read_more'];
		$read_more_text                 = $this->props['read_more_text'];
		$custom_read_more               = $this->props['custom_read_more'];
		$read_more_icon                 = $this->props['read_more_icon'];
		$excerpt_length                 = $this->props['excerpt_length'];
		$show_author                    = $this->props['show_author'];
		$show_date                      = $this->props['show_date'];
		$show_categories 				= $this->props['show_categories'];
		$show_comments                  = $this->props['show_comments'];
		$no_result_text				    = $this->props['no_result_text'];
		$show_pagination				= $this->props['show_pagination'];
		$read_more_icon 				= et_pb_responsive_options()->get_property_values( $this->props, 'read_more_icon' );
		$read_more_icon 				= array_filter( $read_more_icon );

		$title_level  = esc_html( $this->props['title_level'] );
		$order_class  = $this->get_module_order_class( $render_slug );
		$order_number = esc_attr( preg_replace( '/[^0-9]/', '', esc_attr( $order_class ) ) );

		$video_background          = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();
		$processed_title_level     = et_pb_process_header_level( $title_level, 'h2' );
		$processed_title_level     = esc_html( $processed_title_level );

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => intval( $posts_number ),
			'post_status'    => 'publish',
			'offset'         => 0,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( is_user_logged_in() ) {
			$args['post_status'] = array( 'publish', 'private' );
		}
		if ( 'on' === $ignore_sticky_posts ) {
			$args['ignore_sticky_posts'] = true;
		}
		if ( '' !== $include_categories ) {
			$args['cat'] = sanitize_text_field( $include_categories );
		}
		if ( '' !== $offset_number && ! empty( $offset_number ) ) {
			$args['offset'] = intval( $offset_number );
		}
		if ( 0 !== $args['offset'] && -1 === intval( $args['posts_per_page'] ) ) {
			$count_posts            = wp_count_posts( 'post', 'readable' );
			$published_posts        = $count_posts->publish;
			$args['posts_per_page'] = intval( $published_posts );
		}
		if ( isset( $post_order_by ) && '' !== $post_order_by ) {
			$args['orderby'] = sanitize_text_field( $post_order_by );
		}
		if ( isset( $post_order ) && '' !== $post_order ) {
			$args['order'] = sanitize_text_field( $post_order );
		}
		if ( is_single() && ! isset( $args['post__not_in'] ) ) {
			$args['post__not_in'] = array( intval( get_the_ID() ) );
		}

		if ( 'on' === $show_read_more ) {
			$read_more_text = ( ! isset( $read_more_text ) || '' === $read_more_text ) ?
			esc_html__( 'Read More', 'divi-plus' ) :
			sprintf( esc_html__( '%s', 'divi-plus' ), esc_html( $read_more_text ) );
		}

		global $wp_the_query;
		$query_backup = $wp_the_query;

		$args = apply_filters( 'dipl_blog_args', $args );

		$query = new WP_Query( $args );

		self::$rendering = true;

		if ( $query->have_posts() ) {

			wp_enqueue_script( 'dipl-blog-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/Blog/dipl-blog-custom.min.js', array('jquery'), '1.0.0', true );
			wp_localize_script( 'dipl-blog-custom', 'DiviPlusBlogData', array(
	            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
	            'ajaxnonce' => wp_create_nonce( 'elicus-divi-plus-blog-nonce' ),
	            'siteurl'   => site_url(),
	        ) );
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        	wp_enqueue_style( 'dipl-blog-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/Blog/' . $file . '.min.css', array(), '1.0.0' );

			// some themes do not include these styles/scripts so we need to enqueue them in this module to support audio post format.
			wp_enqueue_style( 'wp-mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );

			// include easyPieChart which is required for loading Blog module content via ajax correctly.
			wp_enqueue_script( 'easypiechart' );

			// include ET Shortcode scripts.
			wp_enqueue_script( 'et-shortcodes-js' );

			// remove all filters from WP audio shortcode to make sure current theme doesn't add any elements into audio module.
			remove_all_filters( 'wp_audio_shortcode_library' );
			remove_all_filters( 'wp_audio_shortcode' );
			remove_all_filters( 'wp_audio_shortcode_class' );

			if ( 'on' !== $show_content ) {
				$excerpt_length = ( '' === $excerpt_length ) ? 270 : intval( $excerpt_length );
			}

			if ( 'on' === $show_pagination ) {
				wp_enqueue_script( 'elicus-twbs-pagination' );

				$data_props = array(
					'layout',
					'posts_number',
					'offset_number',
					'post_order' ,
					'post_order_by',
					'include_categories',
					'post_date',
					'ignore_sticky_posts',
					'use_masonry',
					'show_thumbnail',
					'featured_image_size',
					'show_content',
					'excerpt_length',
					'show_author',
					'show_date',
					'show_categories',
					'show_comments',
					'show_read_more',
					'read_more_text',
					'custom_read_more',
					'read_more_icon',
					'title_level',
				);

				if ( 'on' === $this->props['show_prev_next'] ) {
					array_push( $data_props, 'show_prev_next', 'prev_text', 'next_text' );
				}

				$data_atts = $this->props_to_html_data_attrs( $data_props );

				if ( -1 === $args['posts_per_page'] ) {
					$total_pages = 1;
				} else if ( '' !== $args['offset'] && ! empty( $args['offset'] ) ) {
					$total_pages = intval( ceil( ( $query->found_posts - $args['offset'] ) / $args['posts_per_page'] ) );
				} else {
					$total_pages = intval( ceil( $query->found_posts / $args['posts_per_page'] ) );
				}

				if ( ! empty( $read_more_icon ) ) {
		            $icons = array();
		            foreach ( $read_more_icon as $attr => $value ) {
		                $icons[] = 'data-read_more_icon_' . esc_attr( $attr ) . '="' . esc_attr( et_pb_process_font_icon( $value ) ) . '"';
		            }
		            $icons = implode( ' ', $icons );
		        }

				$pagination = sprintf(
					'<div class="dipl_blog_pagination_wrapper" data-posts_number="%8$s" data-total_pages="%1$s" data-is_user_logged_in="%2$s" data-order_class="%3$s" %4$s %5$s data-is_single="%6$s" data-current_post_id="%7$s">
						<ul></ul>
					</div>',
					$total_pages,
					esc_attr( is_user_logged_in() ),
					esc_attr( $this->get_module_order_class( 'dipl_blog' ) ),
					$data_atts,
					! empty( $read_more_icon ) ? $icons : '',
					esc_attr( is_single() ),
					is_single() ? intval( get_the_ID() ) : intval( 0 ),
					intval( $args['posts_per_page'] )
				);
			}

			
			$output = '<div class="dipl_blog_wrapper dipl_blog_' . esc_attr( $layout ) . '">';

			if ( 'on' === $use_masonry && 'layout2' !== $layout ) {
				wp_enqueue_script( 'elicus-images-loaded-script' );
				wp_enqueue_script( 'elicus-isotope-script' );
				$output .= '<div class="dipl_blog_isotope_container">';
				$output .= '<div class="dipl_blog_isotope_item_gutter"></div>';
			}

			while ( $query->have_posts() ) {
				$query->the_post();

				$post_id = intval( get_the_ID() );

				$read_more_icon_array = array(
					'button_text'         => et_core_esc_previously( $read_more_text ),
					'button_text_escaped' => true,
					'button_url'          => esc_url( get_permalink( $post_id ) ),
					'button_custom'       => isset( $custom_read_more ) ? esc_attr( $custom_read_more ) : 'off',
					'custom_icon'         => isset( $read_more_icon['desktop'] ) ? esc_attr( $read_more_icon['desktop'] ) : '',
					'has_wrapper'         => false,
				);

				if ( ! empty( $read_more_icon['tablet'] ) ) {
					$read_more_icon_array['custom_icon_tablet'] = $read_more_icon['tablet'];
				}
				if ( ! empty( $read_more_icon['phone'] ) ) {
					$read_more_icon_array['custom_icon_phone'] = $read_more_icon['phone'];
				}

				$read_more_button = $this->render_button( $read_more_icon_array );

				$thumb          = '';
				$thumb          = dipl_get_post_thumbnail( $post_id, esc_html( $featured_image_size ), 'dipl_blog_post_image' );
				$no_thumb_class = ( '' === $thumb || 'off' === $show_thumbnail ) ? ' dipl_blog_post_no_thumb' : '';

				$post_classes = array_map( 'sanitize_html_class', get_post_class( 'dipl_blog_post dipl_blog_post_page_1' .  $no_thumb_class ) );
				$post_classes = implode( ' ', $post_classes );

				add_filter( 'wp_kses_allowed_html', array( $this, 'dipl_wp_kses_allowed_html' ), 10, 2 );

				if ( file_exists( get_stylesheet_directory() . '/divi-plus/layouts/blog/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include get_stylesheet_directory() . '/divi-plus/layouts/blog/' . sanitize_file_name( $layout ) . '.php';
				} elseif ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php';
				}

				remove_filter( 'wp_kses_allowed_html', array( $this, 'dipl_wp_kses_allowed_html' ), 10 );
			}

			wp_reset_postdata();

			// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, ET.Sniffs.GlobalVariablesOverride.Prohibited
			$wp_the_query = $query_backup;

			if ( 'on' === $use_masonry && 'layout2' !== $layout ) {
				$output .= '</div>';
			}

			$output .= '</div>';

			if ( 'on' === $show_pagination ) {
				$output .= $pagination;
			}


			$number_of_columns 	= et_pb_responsive_options()->get_property_values( $this->props, 'number_of_columns' );
			$column_spacing 	= et_pb_responsive_options()->get_property_values( $this->props, 'column_spacing' );
			$row_spacing 		= et_pb_responsive_options()->get_property_values( $this->props, 'row_spacing' );
			
			$number_of_columns['tablet'] = '' !== $number_of_columns['tablet'] ? $number_of_columns['tablet'] : $number_of_columns['desktop'];
			$number_of_columns['phone']  = '' !== $number_of_columns['phone'] ? $number_of_columns['phone'] : $number_of_columns['tablet'];

			$column_spacing['tablet'] = '' !== $column_spacing['tablet'] ? $column_spacing['tablet'] : $column_spacing['desktop'];
			$column_spacing['phone']  = '' !== $column_spacing['phone'] ? $column_spacing['phone'] : $column_spacing['tablet'];
			
			$breakpoints 	= array( 'desktop', 'tablet', 'phone' );
			$width 			= array();

			foreach ( $breakpoints as $breakpoint ) {
				if ( 1 === absint( $number_of_columns[$breakpoint] ) ) {
					$width[$breakpoint] = '100%';
				} else {
					$divided_width 	= 100 / absint( $number_of_columns[$breakpoint] );
					if ( 0.0 !== floatval( $column_spacing[$breakpoint] ) ) {
						$gutter = floatval( ( floatval( $column_spacing[$breakpoint] ) * ( absint( $number_of_columns[$breakpoint] ) - 1 ) ) / absint( $number_of_columns[$breakpoint] ) );
						$width[$breakpoint] = 'calc(' . $divided_width . '% - ' . $gutter . 'px)';
					} else {
						$width[$breakpoint] = $divided_width . '%';
					}
				}
			}

			if ( 'off' === $use_masonry ) {
				self::set_style( $render_slug, array(
                    'selector'    => '%%order_class%% .dipl_blog_wrapper',
                    'declaration' => 'display: flex; flex-wrap: wrap;',
                ) );
			}

			if ( 'layout2' !== $layout ) {
				et_pb_responsive_options()->generate_responsive_css( $width, '%%order_class%% .dipl_blog_post', 'width', $render_slug, '!important;', 'range' );
			}
			et_pb_responsive_options()->generate_responsive_css( $row_spacing, '%%order_class%% .dipl_blog_post', array( 'margin-bottom' ), $render_slug, '!important;', 'range' );

			if ( 'on' === $use_masonry && 'layout2' !== $layout ) {
				et_pb_responsive_options()->generate_responsive_css( $column_spacing, '%%order_class%% .dipl_blog_isotope_item_gutter', 'width', $render_slug, '!important;', 'range' );
			} else if ( 'layout2' !== $layout && 'off' === $use_masonry ) {
				foreach ( $number_of_columns as $device => $cols ) {
					if ( 'desktop' === $device ) {
						self::set_style( $render_slug, array(
		                    'selector'    => '%%order_class%% .dipl_blog_post:not(:nth-child(' . absint( $cols ) . 'n+' . absint( $cols ) . '))',
		                    'declaration' => sprintf( 'margin-right: %1$s;', esc_attr( $column_spacing['desktop'] ) ),
		                    'media_query' => self::get_media_query( 'min_width_981' ),
		                ) );
		                if ( '' !== $cols ) {
							self::set_style( $render_slug, array(
			                    'selector'    => '%%order_class%% .dipl_blog_post:nth-child(' . absint( $cols ) . 'n+1)',
			                    'declaration' => 'clear: left;',
			                    'media_query' => self::get_media_query( 'min_width_981' ),
			                ) );
						}
					} else if ( 'tablet' === $device ) {
						self::set_style( $render_slug, array(
		                    'selector'    => '%%order_class%% .dipl_blog_post:not(:nth-child(' . absint( $cols ) . 'n+' . absint( $cols ) . '))',
		                    'declaration' => sprintf( 'margin-right: %1$s;', esc_attr( $column_spacing['tablet'] ) ),
		                    'media_query' => self::get_media_query( '768_980' ),
		                ) );
		                if ( '' !== $cols ) {
							self::set_style( $render_slug, array(
			                    'selector'    => '%%order_class%% .dipl_blog_post:nth-child(' . absint( $cols ) . 'n+1)',
			                    'declaration' => 'clear: left;',
			                    'media_query' => self::get_media_query( '768_980' ),
			                ) );
						}
					} else if ( 'phone' === $device ) {
						self::set_style( $render_slug, array(
		                    'selector'    => '%%order_class%% .dipl_blog_post:not(:nth-child(' . absint( $cols ) . 'n+' . absint( $cols ) . '))',
		                    'declaration' => sprintf( 'margin-right: %1$s;', esc_attr( $column_spacing['phone'] ) ),
		                    'media_query' => self::get_media_query( 'max_width_767' ),
		                ) );
		                if ( '' !== $cols ) {
							self::set_style( $render_slug, array(
			                    'selector'    => '%%order_class%% .dipl_blog_post:nth-child(' . absint( $cols ) . 'n+1)',
			                    'declaration' => 'clear: left;',
			                    'media_query' => self::get_media_query( 'max_width_767' ),
			                ) );
						}
					}
				}
			}

			if ( 'on' === $show_pagination ) {
				$this->generate_styles(
					array(
						'base_attr_name' => 'pagination_link_background_color',
						'selector'       => '%%order_class%% .dipl_blog_pagination_wrapper li a',
						'hover_selector' => '%%order_class%% .dipl_blog_pagination_wrapper li a:hover',
						'css_property'   => 'background-color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);

				$this->generate_styles(
					array(
						'base_attr_name' => 'pagination_link_color',
						'selector'       => '%%order_class%% .dipl_blog_pagination_wrapper li a',
						'hover_selector' => '%%order_class%% .dipl_blog_pagination_wrapper li a:hover',
						'css_property'   => 'color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);

				$this->generate_styles(
					array(
						'base_attr_name' => 'active_pagination_link_background_color',
						'selector'       => '%%order_class%% .dipl_blog_pagination_wrapper li.active a',
						'hover_selector' => '%%order_class%% .dipl_blog_pagination_wrapper li.active a:hover',
						'css_property'   => 'background-color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);

				$this->generate_styles(
					array(
						'base_attr_name' => 'active_pagination_link_color',
						'selector'       => '%%order_class%% .dipl_blog_pagination_wrapper li.active a',
						'hover_selector' => '%%order_class%% .dipl_blog_pagination_wrapper li.active a:hover',
						'css_property'   => 'color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);
			}

			if ( 'layout2' === $layout ) {
				$this->generate_styles(
					array(
						'base_attr_name' => 'meta_icon_font_size',
						'selector'       => '%%order_class%% .dipl_blog_post_meta .et-pb-icon',
						'css_property'   => 'font-size',
						'render_slug'    => $render_slug,
						'type'           => 'range',
						'important'		 => true,
					)
				);

				$this->generate_styles(
					array(
						'base_attr_name' => 'meta_icon_color',
						'selector'       => '%%order_class%% .dipl_blog_post_meta .et-pb-icon',
						'hover_selector' => '%%order_class%% .dipl_blog_post:hover .dipl_blog_post_meta .et-pb-icon',
						'css_property'   => 'color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);

				$this->generate_styles(
					array(
						'base_attr_name' => 'meta_icon_background_color',
						'selector'       => '%%order_class%% .dipl_blog_post_meta .et-pb-icon',
						'hover_selector' => '%%order_class%% .dipl_blog_post:hover .dipl_blog_post_meta .et-pb-icon',
						'css_property'   => 'background-color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);
			}

			if ( 'layout2' === $layout || 'layout1' === $layout ) {
				$this->generate_styles(
					array(
						'base_attr_name' => 'meta_separator_color',
						'selector'       => '%%order_class%% .dipl_blog_post_meta ',
						'hover_selector' => '%%order_class%% .dipl_blog_post:hover .dipl_blog_post_meta',
						'css_property'   => 'border-top-color',
						'render_slug'    => $render_slug,
						'type'           => 'color',
						'important'		 => true,
					)
				);
			}

			$bg_args = array(
				'render_slug' => $render_slug,
				'props'		  => $this->props,
				'fields'	  => $this->fields_unprocessed,
				'module'	  => $this,
				'backgrounds' => array(
					'post_content_wrapper_background' => array(
		                'normal' => "{$this->main_css_element} .dipl_blog_post_content_wrapper",
		                'hover'  => "{$this->main_css_element} .dipl_blog_post:hover .dipl_blog_post_content_wrapper",
		            )
				),
			);
			if ( 'layout1' === $layout ) {
				$bg_args['backgrounds']['category_background'] = array(
	                'normal' => "{$this->main_css_element} .dipl_blog_post_categories a",
	                'hover'  => "{$this->main_css_element} .dipl_blog_post:hover .dipl_blog_post_categories a",
		        );
			}
	        DiviPlusHelper::process_background( $bg_args );

			$fields = array( 'blog_margin_padding' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

		} else {
			$output = '<div className="entry">' . esc_html( $no_result_text ) . '</div>';
		}

		self::$rendering = false;

		return $output;
		
	}

	public function dipl_wp_kses_allowed_html( $allowed_tags, $context ) {
		if ( 'post' === $context ) {
			$allowed_tags['iframe'] = array(
				'src' => true,
				'title' => true,
				'name' => true,
				'allow' => true,
				'allowfullscreen' => true,
			);
		}
		
		return $allowed_tags;
	}
}
$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_blog', $modules ) ) {
		new DIPL_Blog();
	}
} else {
	new DIPL_Blog();
}
