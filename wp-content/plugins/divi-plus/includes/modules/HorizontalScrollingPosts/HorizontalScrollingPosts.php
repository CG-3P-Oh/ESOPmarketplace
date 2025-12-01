<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.16.0
 */
class DIPL_HorizontalScrollingPosts extends ET_Builder_Module {
	public $slug       = 'dipl_horizontal_scrolling_posts';
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
		$this->name             = esc_html__( 'DP Horizontal Scrolling Posts', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'elements'     => esc_html__( 'Elements', 'divi-plus' ),
					'display'      => esc_html__( 'Display', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'text'         => esc_html__( 'Text', 'divi-plus' ),
					'post_title'   => esc_html__( 'Title Text', 'divi-plus' ),
					'excerpt'      => esc_html__( 'Excerpt Text', 'divi-plus' ),
					'post_item'    => esc_html__( 'Post Item', 'divi-plus' ),
					'post_content' => esc_html__( 'Post Content', 'divi-plus' ),
					'category'     => esc_html__( 'Category', 'divi-plus' ),
					'post_image'   => esc_html__( 'Post Image', 'divi-plus' ),
					'meta_icon'    => esc_html__( 'Meta Icon', 'divi-plus' ),
					'meta_text'    => esc_html__( 'Meta Text', 'divi-plus' ),
					'button'       => esc_html__( 'Button', 'divi-plus' ),
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
						'default'  => 'h2',
					),
					'hide_text_align' => true,
					'css'             => array(
						'main'      => "{$this->main_css_element} .dipl_horizontal_scrolling_post_title, {$this->main_css_element} .dipl_horizontal_scrolling_post_title a",
						'important' => 'all',
					),
					'depends_on'      => array( 'show_title' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'post_title',
				),
				'excerpt' => array(
					'label'     => esc_html__( 'Excerpt', 'divi-plus' ),
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
					'css'             => array(
						'main'      => "{$this->main_css_element} .dipl_horizontal_scrolling_post_excerpt",
						'important' => 'all',
					),
					'depends_on'      => array( 'show_excerpt' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'excerpt',
				),
				'category' => array(
					'label'     => esc_html__( 'Category', 'divi-plus' ),
					'font_size' => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color'    => array(
						'default' => '#ffffff'
					),
					'hide_text_align' => true,
					'css'       => array(
						'main'      => "{$this->main_css_element} .dipl_horizontal_scrolling_post_tag a",
						'important' => 'all',
					),
					'depends_on'      => array( 'show_categories' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'category',
				),
				'meta' => array(
					'label'     => esc_html__( 'Category', 'divi-plus' ),
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
						'main'      => "{$this->main_css_element} .dipl_horizontal_scrolling_post_meta_wrapper, {$this->main_css_element} .dipl_horizontal_scrolling_post_meta_wrapper span, {$this->main_css_element} .dipl_horizontal_scrolling_post_meta_wrapper a, {$this->main_css_element} .dipl_horizontal_scrolling_post_tag_wrapper span.published",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'meta_text',
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
				'post_item' => array(
					'label_prefix' => esc_html__( 'Post Item', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper',
							'important' => 'all',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_item',
				),
				'post_content' => array(
					'label_prefix' => esc_html__( 'Content', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_post_content_wrapper',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_post_content_wrapper',
							'important' => 'all',
						),
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'post_content',
				),
				'post_image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper .dipl_horizontal_scrolling_post_image',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper .dipl_horizontal_scrolling_post_image',
							'important' => 'all',
						),
					),
					'depends_on'      => array( 'layout' ),
					'depends_show_if' => 'layout1',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'post_image',
				),
				'category' => array(
					'label_prefix' => esc_html__( 'Category', 'divi-plus' ),
					'css'          => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_horizontal_scrolling_post_tag a',
							'border_styles' => '%%order_class%% .dipl_horizontal_scrolling_post_tag a',
							'important' => 'all',
						),
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'category',
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
				'post_item' => array(
					'label'       => esc_html__( 'Post Item Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper',
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'post_item',
				),
				'post_image' => array(
					'label'       => esc_html__( 'Image Box Shadow', 'divi-plus' ),
					'css'         => array(
						'main' => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper .dipl_horizontal_scrolling_post_image',
						'important' => 'all',
					),
					'depends_on'      => array( 'layout' ),
					'depends_show_if' => 'layout1',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'post_image',
				),
				'default' => array(
					'css' => array(
						'main' => '%%order_class%%',
					),
				)
			),
			'sticky_post_margin_padding' => array(
				'post_item' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "{$this->main_css_element} .layout1 .dipl_horizontal_scrolling_post_wrapper, {$this->main_css_element} .layout2 .dipl_horizontal_scrolling_post_inner",
							'important'  => 'all',
						),
					),
				),
				'post_content' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_horizontal_scrolling_post_content_wrapper",
							'important'  => 'all',
						),
					),
				),
				'post_image' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_horizontal_scrolling_post_wrapper .dipl_horizontal_scrolling_post_image",
							'important'  => 'all',
						),
					),
				),
				'category' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_horizontal_scrolling_post_tag a",
							'important'  => 'all',
						),
					),
				)
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%% .dipl-sticky-posts-scroller',
					'important' => 'all',
				),
			),
			'text' => array(
				'text_orientation' => array(),
				'use_background_layout' => true,
				'options'               => array(
					'text_orientation'  => array(
						'default'           => 'left',
						'default_on_front'  => 'left',
					),
					'background_layout' => array(
						'default'           => 'light',
						'default_on_front'  => 'light',
						'hover'             => 'tabs',
					)
				),
				'css' => array(
					'text_orientation' => "{$this->main_css_element} .dipl_horizontal_scrolling_post_content_wrapper",
					'important' => 'all',
				)
			),
			'filters'      => false,
			'link_options' => false,
			'use_background_video' => false,
			'background' => array(
				'use_background_video' => false,
				'css' => array(
					'main' => '%%order_class%% .dipl-sticky-posts-scroller',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'number_of_results' => array(
				'label'            => esc_html__( 'Number of Posts', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'default_on_front' => '10',
				'default'		   => '10',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can input the number of posts to be displayed in the ticker. Input -1 for all.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
				),
			),
			'offset_number' => array(
				'label'            => esc_html__( 'Post Offset Number', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'          => 0,
				'tab_slug'         => 'general',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Choose how many posts you would like to skip. These posts will not be shown in the feed.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
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
					'__scrolling_post_data',
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
					'__scrolling_post_data',
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
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Choose which categories you would like to include in the feed', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
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
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'This will decide whether to ingnore sticky posts or not.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
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
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Enable this exclude password protected posts.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
				),
			),
			'exclude_post_ids' => array(
				'label'           => esc_html__( 'Exclude Post IDs', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter post ids you want to exclude from ticker separated by commas.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
				),
			),
			'no_result_text' => array(
				'label'           => esc_html__( 'No Result Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'default'         => esc_html__( 'The posts you requested could not be found. Try changing your module settings or create some new posts.', 'divi-plus' ),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can define custom no result text.', 'divi-plus' ),
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
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'This will turn thumbnails on or off.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
				),
			),
			'image_size' => array(
				'label'             => esc_html__( 'Image Size', 'divi-plus' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'medium' => esc_html__( 'Medium', 'divi-plus' ),
					'large'  => esc_html__( 'Large', 'divi-plus' ),
					'full'   => esc_html__( 'Full', 'divi-plus' ),
				),
				'default'           => 'large',
				'show_if'           => array(
					'show_thumbnail' => 'on',
				),
				'toggle_slug'       => 'elements',
				'description'       => esc_html__( 'Here you can select the size of images in gallery.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
				),
			),
			'show_title' => array(
				'label'            => esc_html__( 'Show Title', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'This will turn title on or off.', 'divi-plus' ),
			),
			'show_excerpt' => array(
				'label'            => esc_html__( 'Show Excerpt', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'          => 'on',
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Showing the full content will not truncate your posts on the index page. Showing the excerpt will only display your excerpt text.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
				),
			),
			'excerpt_length' => array(
				'label'            => esc_html__( 'Excerpt Length', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'          => '150',
				'show_if'          => array(
					'show_excerpt' => 'on',
				),
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Here you can define excerpt length in characters, if 0 no excerpt will be shown. However this won\'t work with the manual excerpt defined in the post.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
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
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Turn the category/terms links on or off.', 'divi-plus' ),
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
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Turn on or off the Author link.', 'divi-plus' ),
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
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Turn the Date on or off.', 'divi-plus' ),
			),
			'date_format' => array(
				'label'            => esc_html__( 'Post Date Format', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'          => 'M j, Y',
				'show_if'          => array(
					'show_date' => 'on',
				),
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'If you would like to adjust the date format, input the appropriate PHP date format here.', 'divi-plus' ),
				'computed_affects' => array(
					'__scrolling_post_data',
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
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Turn Comment Count on and off.', 'divi-plus' ),
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
				'toggle_slug'     => 'elements',
				'description'     => esc_html__( 'Here you can choose whether or not show the button.', 'divi-plus' ),
			),
			'button_text' => array(
				'label'            => esc_html__( 'Button Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'show_if'          => array( 'show_button' => 'on' ),
				'default'          => esc_html__( 'Read more', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Read more', 'divi-plus' ),
				'toggle_slug'      => 'elements',
				'description'      => esc_html__( 'Here you can input the text to be used for the button.', 'divi-plus' ),
			),
			// 'button_new_window' => array(
			// 	'label'        	  => esc_html__( 'Button Link Target', 'divi-plus' ),
			// 	'type'        	  => 'select',
			// 	'option_category' => 'configuration',
			// 	'show_if'         => array( 'show_button' => 'on' ),
			// 	'options'         => array(
			// 		'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
			// 		'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
			// 	),
			// 	'toggle_slug'     => 'elements',
			// 	'description'  	  => esc_html__( 'Here you can choose whether or not your link opens in a new window for the button.', 'divi-plus' ),
			// ),
			'js_init_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'configuration',
				'toggle_slug'     => 'display',
				'value'           => true,
				'display_if'      => true,
				'message'         => esc_html__( 'Horizontal scrolling does not work in the Visual Builder. Please check the frontend to see the effect correctly.', 'divi-plus' )
			),
			'layout' => array(
				'label'        	  => esc_html__( 'Layout', 'divi-plus' ),
				'type'        	  => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'layout1' => esc_html__( 'Layout 1', 'divi-plus' ),
					'layout2' => esc_html__( 'Layout 2', 'divi-plus' ),
				),
				'default'         => 'layout1',
				'toggle_slug'     => 'display',
				'description'  	  => esc_html__( 'Here you can choose the layout for the post items.', 'divi-plus' ),
			),
			'space_between_cards' => array(
				'label'           => esc_html__( 'Space Between Cards', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '150',
					'step' => '1',
				),
				'default'         => '15px',
				'default_unit'    => 'px',
				'allowed_units'   => array( 'px' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Increase or decrease the space between the posts items.', 'divi-plus' ),
			),
			'animation_start_element_pos' => array(
				'label'           => esc_html__( 'Animation Start Element', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '50%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Increase or decrease the element\'s start position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'animation_start_viewport_pos' => array(
				'label'           => esc_html__( 'Animation Start Viewport', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '50%',
				'default_unit'    => '%',
				'allowed_units'   => array( '%', 'px' ),
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Increase or decrease the viewport\'s start position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'post_item_bg_color' => array(
				'label'            => esc_html__( 'Post Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '#f7f7f7',
				'default_on_front' => '#f7f7f7',
				'hover'            => 'tabs',
				'show_if'          => array( 'layout' => 'layout1' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'post_item',
				'description'      => esc_html__( 'Select the post item background color.', 'divi-plus' ),
			),
			'post_overlay_color' => array(
				'label'            => esc_html__( 'Post Overlay Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => 'rgba(244,244,244,0.7)',
				'default_on_front' => 'rgba(244,244,244,0.7)',
				'hover'            => 'tabs',
				'show_if'          => array( 'layout' => 'layout2' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'post_item',
				'description'      => esc_html__( 'Select the post item overlay background color.', 'divi-plus' ),
			),
			'post_item_custom_padding' => array(
				'label'            => esc_html__( 'Post Item Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'post_item',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'post_width' => array(
				'label'           => esc_html__( 'Post Item Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'default'         => '400px',
				'default_unit'    => 'px',
				'mobile_options'  => true,
				'allowed_units'   => array( 'px' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'post_item',
				'description'     => esc_html__( 'Increase or decrease the element\'s position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'post_height' => array(
				'label'           => esc_html__( 'Post Item Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'default'         => '500px',
				'default_unit'    => 'px',
				'mobile_options'  => true,
				'allowed_units'   => array( 'px' ),
				'show_if'         => array( 'layout' => 'layout2' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'post_item',
				'description'     => esc_html__( 'Increase or decrease the element\'s position to trigger the animation when it reaches the specified location.', 'divi-plus' ),
			),
			'post_content_custom_padding' => array(
				'label'            => esc_html__( 'Content Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'post_content',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'post_content_bg_color' => array(
				'label'            => esc_html__( 'Content Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '',
				'hover'            => 'tabs',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'post_content',
				'description'      => esc_html__( 'Select the post item content background color.', 'divi-plus' ),
			),
			'post_image_custom_padding' => array(
				'label'            => esc_html__( 'Image Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '||||true|true',
				'default_on_front' => '||||true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'show_if'          => array( 'layout' => 'layout1' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'post_image',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'enable_image_height' => array(
				'label'     	  => esc_html__( 'Enable Custom Height', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'         => 'on',
				'tab_slug'        => 'advanced',
				'show_if'         => array( 'layout' => 'layout1' ),
				'toggle_slug'     => 'post_image',
				'description'     => esc_html__( 'Here you can choose whether enable the custom height for image or not.', 'divi-plus' ),
			),
			'image_height' => array(
				'label'           => esc_html__( 'Image Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				'mobile_options'  => 'true',
				'validate_unit'   => true,
				'allowed_values'  => et_builder_get_acceptable_css_string_values( 'height' ),
				'default'         => '250px',
				'default_unit'    => 'px',
				'show_if'         => array( 'layout' => 'layout1', 'enable_image_height' => 'on' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'post_image',
				'description'     => esc_html__( 'Move the slider or input the value to increase or decrease the image height.', 'divi-plus' ),
			),
			'category_custom_padding' => array(
				'label'            => esc_html__( 'Category Padding', 'divi-plus' ),
				'type'             => 'custom_padding',
				'option_category'  => 'layout',
				'default'          => '5px|5px|5px|5px|true|true',
				'default_on_front' => '5px|5px|5px|5px|true|true',
				'mobile_options'   => true,
				'hover'            => false,
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'category',
				'description'      => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'category_bg_color' => array(
				'label'            => esc_html__( 'Category Background Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '#000000',
				'default_on_front' => '#000000',
				'hover'            => 'tabs',
				'show_if'          => array( 'show_categories' => 'on' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'category',
				'description'      => esc_html__( 'Select the post item overlay background color.', 'divi-plus' ),
			),
			'meta_icon_font_size' => array(
				'label'           => esc_html__( 'Meta Icon Font Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '100',
					'step' => '1',
				),
				'mobile_options'  => true,
				'default'         => '14px',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'meta_icon',
				'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the size of meta icons.', 'divi-plus' ),
			),
			'meta_icon_color' => array(
				'label'            => esc_html__( 'Meta Icon Color', 'divi-plus' ),
				'type'         	   => 'color-alpha',
				'custom_color' 	   => true,
				'default'      	   => '#007aff',
				'default_on_front' => '#007aff',
				'hover'            => 'tabs',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'meta_icon',
				'description'      => esc_html__( 'Select the post item overlay background color.', 'divi-plus' ),
			),
			'__scrolling_post_data' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_HorizontalScrollingPosts', 'get_computed_posts_data' ),
				'computed_depends_on' => array(
					'number_of_results',
					'orderby',
					'order',
					'offset_number',
					'include_categories',
					'ignore_sticky_posts',
					'exclude_pwd_protected',
					'exclude_post_ids',
					'image_size',
					'show_excerpt',
					'excerpt_length',
					'date_format'
				)
			)
		);
	}

	public static function get_computed_posts_data( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'number_of_results'     => '10',
			'orderby'               => 'post_date',
			'order'                 => 'DESC',
			'offset_number'         => '',
			'include_categories'    => '',
			'ignore_sticky_posts'   => 'off',
			'exclude_pwd_protected' => 'on',
			'exclude_post_ids'      => '',
			'image_size'            => 'medium',
			'show_excerpt'          => 'on',
			'excerpt_length'        => '150',
			'date_format'           => 'M j, Y',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		// WordPress' native conditional tag is only available during page load. It'll fail during component update because
		// et_pb_process_computed_property() is loaded in admin-ajax.php. Thus, use WordPress' conditional tags on page load and
		// rely to passed $conditional_tags for AJAX call.
		$is_user_logged_in = (bool) et_fb_conditional_tag( 'is_user_logged_in', $conditional_tags );

		// Collect post query args.
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => intval( $number_of_results ),
			'post_status'    => 'publish',
			'offset'         => 0,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		// if ( ! empty( $post_types ) ) {
		// 	$args['post_type'] = sanitize_text_field( $post_types );
		// }
		if ( $is_user_logged_in ) {
			$args['post_status'] = array( 'publish', 'private' );
		}
		if ( ! empty( $offset_number ) ) {
			$args['offset'] = intval( $offset_number );
		}
		if ( ! empty( $args['offset'] ) && -1 === intval( $args['posts_per_page'] ) ) {
			$count_posts            = wp_count_posts( 'post', 'readable' );
			$published_posts        = $count_posts->publish;
			$args['posts_per_page'] = intval( $published_posts );
		}
		if ( ! empty( $order ) ) {
			$args['order'] = sanitize_text_field( $order );
		}
		if ( ! empty( $orderby ) ) {
			$args['orderby'] = sanitize_text_field( $orderby );
		}
		if ( ! empty( $include_categories ) ) {
			$args['cat'] = sanitize_text_field( $include_categories );
		}
		if ( 'on' === $ignore_sticky_posts ) {
			$args['ignore_sticky_posts'] = true;
		}
		if ( 'on' === $exclude_pwd_protected ) {
			$args['has_password'] = false;
		}
		if ( ! empty( $exclude_post_ids ) ) {
			$args['post__not_in'] = explode( ',', sanitize_text_field( $exclude_post_ids ) );
		}

		global $wp_the_query;
		$query_backup = $wp_the_query;

		$query = new WP_Query( $args );

		self::$rendering = true;

		// Excerpt length.
		$excerpt_length = ( '' === $excerpt_length ) ? 150 : intval( $excerpt_length );

		$items_array = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$post_id = get_the_ID();

				// Get the excerpt.
				$excerpt = '';
				if ( 'on' === $show_excerpt && 0 !== intval( $excerpt_length ) ) {
					if ( has_excerpt() && '' !== trim( get_the_excerpt( $post_id ) ) ) {
						$excerpt = wpautop( dipl_strip_shortcodes( get_the_excerpt( $post_id ) ) );
					} else {
						$excerpt = wpautop( strip_shortcodes( dipl_truncate_post( $excerpt_length, false, $post_id, true ) ) );
					}
				}

				// Get post categories.
				$post_cats  = [];
				$categories = get_the_category(); 
				if ( ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						$post_cats[ $category->term_id ] = array(
							'name' => esc_html( $category->name ),
							'link' => esc_url( get_category_link( $category->term_id ) )
						);
					}
				}

				$post_item = array(
					'id'        => esc_attr( $post_id ),
					'title'     => get_the_title(),
					'image'     => get_the_post_thumbnail_url( $post_id, $image_size ),
					'link'      => get_the_permalink(),
					'excerpt'   => $excerpt,
					'post_cats' => $post_cats,
					'cat_name'  => ! empty( $categories[0]->name ) ? $categories[0]->name : '',
					'cat_link'  => ! empty( $categories[0]->name ) ? esc_url( get_category_link( $categories[0]->term_id ) ) : '',
					'author'    => get_the_author_posts_link(),
					'post_date' => get_the_date( $date_format ),
					'comments'  => number_format_i18n( get_comments_number( $post_id ) ),
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

		$multi_view            = et_pb_multi_view_options( $this );
		$layout                = esc_attr( $this->props['layout'] ) ?? 'layout1';
		$show_title            = esc_attr( $this->props['show_title'] ) ?? 'on';
		$show_button           = esc_attr( $this->props['show_button'] ) ?? 'off';
		$show_thumbnail        = esc_attr( $this->props['show_thumbnail'] ) ?? 'on';
		$image_size            = esc_attr( $this->props['image_size'] ) ?? 'medium';
		$show_excerpt          = esc_attr( $this->props['show_excerpt'] ) ?? 'on';
		$excerpt_length        = esc_attr( $this->props['excerpt_length'] ) ?? '150';
		$show_categories       = esc_attr( $this->props['show_categories'] ) ?? 'on';
		$show_date             = esc_attr( $this->props['show_date'] ) ?? 'on';
		$date_format           = esc_attr( $this->props['date_format'] ) ?? 'M j, Y';
		$show_author           = esc_attr( $this->props['show_author'] ) ?? 'on';
		$show_comments         = esc_attr( $this->props['show_comments'] ) ?? 'on';
		$title_level           = et_pb_process_header_level( $this->props['title_level'], 'h2' );

		$number_of_results     = esc_attr( $this->props['number_of_results'] ) ?? '10';
		$offset_number         = esc_attr( $this->props['offset_number'] ) ?? '0';
		$orderby               = esc_attr( $this->props['orderby'] ) ?? 'post_date';
		$order                 = esc_attr( $this->props['order'] ) ?? 'DESC';
		$include_categories    = esc_attr( $this->props['include_categories'] ) ?? '';
		$exclude_pwd_protected = esc_attr( $this->props['exclude_pwd_protected'] ) ?? 'on';
		$ignore_sticky_posts   = esc_attr( $this->props['ignore_sticky_posts'] ) ?? 'off';
		$exclude_post_ids      = esc_attr( $this->props['exclude_post_ids'] ) ?? '';

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => intval( $number_of_results ),
			'post_status'    => 'publish',
			'offset'         => 0,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		// if ( ! empty( $post_types ) ) {
		// 	$args['post_type'] = sanitize_text_field( $post_types );
		// }
		if ( is_user_logged_in() ) {
			$args['post_status'] = array( 'publish', 'private' );
		}
		if ( ! empty( $offset_number ) ) {
			$args['offset'] = intval( $offset_number );
		}
		if ( ! empty( $args['offset'] ) && -1 === intval( $args['posts_per_page'] ) ) {
			$count_posts            = wp_count_posts( 'post', 'readable' );
			$published_posts        = $count_posts->publish;
			$args['posts_per_page'] = intval( $published_posts );
		}
		if ( ! empty( $order ) ) {
			$args['order'] = sanitize_text_field( $order );
		}
		if ( ! empty( $orderby ) ) {
			$args['orderby'] = sanitize_text_field( $orderby );
		}
		if ( ! empty( $include_categories ) ) {
			$args['cat'] = sanitize_text_field( $include_categories );
		}
		if ( 'on' === $ignore_sticky_posts ) {
			$args['ignore_sticky_posts'] = true;
		}
		if ( 'on' === $exclude_pwd_protected ) {
			$args['has_password'] = false;
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

			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-horizontal-scrolling-post-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/HorizontalScrollingPosts/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'elicus-scroll-trigger-script' );
			wp_enqueue_script( 'elicus-gsap-script' );
			wp_enqueue_script( 'dipl-horizontal-scrolling-post-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/HorizontalScrollingPosts/dipl-horizontal-scrolling-posts-custom.min.js", array('jquery'), '1.0.0', true );

			$excerpt_length = ( '' === $excerpt_length ) ? 150 : intval( $excerpt_length );

			while ( $query->have_posts() ) {
				$query->the_post();

				$post_id = get_the_ID();

				// Get the excerpt.
				$excerpt = '';
				if ( 'on' === $show_excerpt && 0 !== intval( $excerpt_length ) ) {
					if ( has_excerpt() && '' !== trim( get_the_excerpt( $post_id ) ) ) {
						$excerpt = wpautop( dipl_strip_shortcodes( get_the_excerpt( $post_id ) ) );
					} else {
						$excerpt = wpautop( strip_shortcodes( dipl_truncate_post( $excerpt_length, false, $post_id, true ) ) );
					}
				}

				// Image.
				$image = '';
				if ( 'layout1' === $layout && 'on' === $show_thumbnail && has_post_thumbnail() ) {
					$image = sprintf(
						'<a href="%1$s">%2$s</a>',
						get_the_permalink(),
						get_the_post_thumbnail( $post_id, $image_size, array(
							'class' => 'et_pb_image dipl_horizontal_scrolling_post_image'
						) )
					);
				}

				// Title.
				$title = '';
				if ( 'on' === $show_title ) {
					$post_title = sprintf(
						'<a href="%1$s">%2$s</a>',
						get_the_permalink(),
						get_the_title()
					);
					$title = $multi_view->render_element( array(
						'tag'     => $title_level,
						'content' => $post_title,
						'attrs'   => array(
							'class' => 'dipl_horizontal_scrolling_post_title'
						),
					) );
				}
				// Excerpt.
				$description = '';
				if ( ! empty( $excerpt ) && 'on' === $show_excerpt ) {
					$description = $multi_view->render_element( array(
						'tag'        => 'div',
						'content'    => et_core_esc_previously( $excerpt ),
						'attrs'      => array(
							'class' => 'dipl_horizontal_scrolling_post_excerpt',
						)
					) );
				}

				$render_button = '';
				if ( 'on' === $show_button ) {
					$render_button = $this->render_button( array(
						'button_text'         => esc_attr( $this->props['button_text'] ),
						'button_text_escaped' => false,
						'has_wrapper'      	  => false,
						'button_url'          => get_the_permalink(),
						'url_new_window'      => 'off', //esc_attr( $this->props['button_new_window'] ),
						'button_custom'       => isset( $this->props['custom_button'] ) ? esc_attr( $this->props['custom_button'] ) : 'off',
						'custom_icon'         => isset( $this->props['button_icon'] ) ? $this->props['button_icon'] : '',
						'button_rel'          => isset( $this->props['button_rel'] ) ? esc_attr( $this->props['button_rel'] ) : '',
					) );
					$render_button = sprintf(
						'<div class="et_pb_button_wrapper">%1$s</div>',
						et_core_esc_previously( $render_button )
					);
				}

				$post_cats = '';
				if ( 'on' === $show_categories ) {
					$categories = get_the_category(); 
					if ( ! empty( $categories ) ) {
						foreach ( $categories as $category ) {
							$post_cats .= sprintf(
								'<span class="dipl_horizontal_scrolling_post_tag">
									<a href="%1$s">%2$s</a>
								</span>',
								esc_url( get_category_link( $category->term_id ) ),
								esc_html( $category->name ) 
							);
						}
					}
				}

				// Post footer meta.
				$post_footer_meta = array();
				if ( 'on' === $show_author ) {
					$post_footer_meta['author'] = et_get_safe_localization( sprintf(
						'<span class="author"><span class="et-pb-icon">&#xe08a;</span>%1$s</span>',
						get_the_author_posts_link()
					) );
				}
				$post_date = get_the_date( $date_format );
				if ( 'layout1' === $layout && 'on' === $show_date && ! empty( $post_date ) ) {
					$post_footer_meta['date'] = et_get_safe_localization( sprintf(
						'<span class="published"><span class="et-pb-icon">&#xe025;</span>%s</span>',
						esc_html( $post_date )
					) );
				}
				if ( 'on' === $show_comments ) {
					$post_footer_meta['comments'] = et_get_safe_localization( sprintf(
						'<span class="comments"><em class="et-pb-icon">&#xe065;</em>%s</span>',
						esc_html( number_format_i18n( get_comments_number( $post_id ) ) )
					) );
				}

				// Post classes.
				$post_class = get_post_class( 'dipl_horizontal_scrolling_post_item item_' . esc_attr( $layout ) );

				$post_item  = sprintf( '<article class="%1$s">', implode( ' ', $post_class ) );
				$post_item .= sprintf( '<div class="dipl_horizontal_scrolling_post_wrapper">' );
				if ( file_exists( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' ) ) {
					include( plugin_dir_path( __FILE__ ) . 'layouts/' . sanitize_file_name( $layout ) . '.php' );
				}
				$post_item .= '</div>';
				$post_item .= '</article>';

				// Add this to main array.
				array_push( $items_array, $post_item );

				// Set background image.
				if ( 'on' === $show_thumbnail && 'layout2' === $layout ) {
					$image_url = get_the_post_thumbnail_url( $post_id, $image_size );
					if ( ! empty( $image_url ) ) {
						self::set_style( $render_slug, array(
							'selector'    => sprintf( '%%order_class%% .dipl_horizontal_scrolling_post_item.item_layout2.post-%1$s .dipl_horizontal_scrolling_post_inner', esc_attr( $post_id ) ),
							'declaration' => sprintf( 'background-image: url( %1$s );', esc_url( $image_url ) ),
						) );
					}
				}
			}
		}

		wp_reset_postdata();

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wp_the_query = $query_backup;

		$render_output = '';
		if ( ! empty( $items_array ) ) {

			// Get props.
			$data_props = array(
				'animation_start_element_pos',
				'animation_start_viewport_pos'
			);
			$data_atts = $this->props_to_html_data_attrs( $data_props );

			// Render final output.
			$render_output = sprintf(
				'<div class="dipl-sticky-posts-scroller">
					<div class="dipl-sticky-posts-wrapper %1$s" %2$s>
						<div class="dipl-sticky-posts-inner">%3$s</div>
					</div>
				</div>',
				et_core_esc_previously( $layout ),
				et_core_esc_previously( $data_atts ),
				implode( '', $items_array )
			);

			// Space between cards.
			$space_between_cards = et_pb_responsive_options()->get_property_values( $this->props, 'space_between_cards' );
			if ( ! empty( array_filter( $space_between_cards ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $space_between_cards, '%%order_class%% .dipl_horizontal_scrolling_post_item:not(:last-child) .dipl_horizontal_scrolling_post_wrapper', 'margin-right', $render_slug, '!important;', 'range' );
			}

			// layout one style.
			if ( 'layout1' === $layout ) {
				$this->generate_styles( array(
					'base_attr_name' => 'post_item_bg_color',
					'selector'       => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper',
					'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper:hover',
					'important'      => true,
					'css_property'   => 'background-color',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );

				// Image style.
				if ( 'on' === $this->props['enable_image_height'] ) {
					// Post height.
					$image_height = et_pb_responsive_options()->get_property_values( $this->props, 'image_height' );
					if ( ! empty( array_filter( $image_height ) ) ) {
						et_pb_responsive_options()->generate_responsive_css( $image_height, '%%order_class%% .dipl_horizontal_scrolling_post_wrapper .dipl_horizontal_scrolling_post_image', 'height', $render_slug, '!important;', 'range' );
					}
				}
			} elseif ( 'layout2' === $layout ) {
				$this->generate_styles( array(
					'base_attr_name' => 'post_overlay_color',
					'selector'       => '%%order_class%% .dipl_horizontal_scrolling_post_inner::before',
					'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_post_inner:hover::before',
					'important'      => true,
					'css_property'   => 'background-color',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );

				// Post height.
				$post_height = et_pb_responsive_options()->get_property_values( $this->props, 'post_height' );
				if ( ! empty( array_filter( $post_height ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $post_height, '%%order_class%% .layout2 .dipl_horizontal_scrolling_post_wrapper', 'min-height', $render_slug, '!important;', 'range' );
				}
			}

			// Post width.
			$post_width = et_pb_responsive_options()->get_property_values( $this->props, 'post_width' );
			if ( ! empty( array_filter( $post_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $post_width, '%%order_class%% .dipl_horizontal_scrolling_post_wrapper', 'width', $render_slug, '!important;', 'range' );
				et_pb_responsive_options()->generate_responsive_css( $post_width, '%%order_class%% .dipl_horizontal_scrolling_post_wrapper', 'min-width', $render_slug, '!important;', 'range' );
			}

			// Post Content.
			$this->generate_styles( array(
				'base_attr_name' => 'post_content_bg_color',
				'selector'       => '%%order_class%% .dipl_horizontal_scrolling_post_content_wrapper',
				'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_post_wrapper:hover .dipl_horizontal_scrolling_post_content_wrapper',
				'important'      => true,
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );

			// Post category.
			if ( 'on' === $show_categories ) {
				$this->generate_styles( array(
					'base_attr_name' => 'category_bg_color',
					'selector'       => '%%order_class%% .dipl_horizontal_scrolling_post_tag a',
					'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_post_tag:hover a',
					'important'      => true,
					'css_property'   => 'background-color',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );
			}

			// Meta styles.
			if ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_comments ) {
				$meta_icon_font_size = et_pb_responsive_options()->get_property_values( $this->props, 'meta_icon_font_size' );
				if ( ! empty( array_filter( $meta_icon_font_size ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $meta_icon_font_size, '%%order_class%% .dipl_horizontal_scrolling_post_meta_wrapper .et-pb-icon', 'font-size', $render_slug, '!important;', 'range' );
				}
				$this->generate_styles( array(
					'base_attr_name' => 'meta_icon_color',
					'selector'       => '%%order_class%% .dipl_horizontal_scrolling_post_meta_wrapper .et-pb-icon, %%order_class%% .dipl_horizontal_scrolling_post_tag .et-pb-icon',
					'hover_selector' => '%%order_class%% .dipl_horizontal_scrolling_post_meta_wrapper:hover .et-pb-icon, %%order_class%% .dipl_horizontal_scrolling_post_tag:hover .et-pb-icon',
					'important'      => true,
					'css_property'   => 'color',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );
			}

			$fields = array( 'sticky_post_margin_padding' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );

			// Add text classes.
			$background_layout_class_names = et_pb_background_layout_options()->get_background_layout_class( $this->props );
			$this->add_classname( array(
				$this->get_text_orientation_classname(),
				$background_layout_class_names[0]
			) );
		} else {
			$render_output = sprintf(
				'<div class="entry">%1$s</div>',
				esc_html( $this->props['no_result_text'] ) ?? ''
			);
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_horizontal_scrolling_posts', $modules ) ) {
		new DIPL_HorizontalScrollingPosts();
	}
} else {
	new DIPL_HorizontalScrollingPosts();
}
