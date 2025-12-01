<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2023 Elicus Technologies Private Limited
 * @version     1.9.15
 */
class DIPL_StarRating extends ET_Builder_Module {

	public $slug       = 'dipl_star_rating';
	public $vb_support = 'on';
	public $svg_icons  = [];

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name             = esc_html__( 'DP Star Rating', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Configuration', 'divi-plus' ),
					'display'      => esc_html__( 'Display', 'divi-plus' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text'          => array(
						'title' => esc_html__( 'Alignment', 'divi-plus' ),
					),
					'title'         => array(
						'title' => esc_html__( 'Title', 'divi-plus' ),
					),
					'desc'          => array(
						'title'             => esc_html__( 'Description', 'divi-plus' ),
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
					'rating_number' => array(
						'title' => esc_html__( 'Rating Number', 'divi-plus' ),
					),
					'stars'         => array(
						'title' => esc_html__( 'Rate Icons/Stars', 'divi-plus' ),
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'          => array(
				'title'         => array(
					'label'           => esc_html__( 'Title', 'divi-plus' ),
					'font_size'       => array(
						'default'        => '18px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.2',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
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
					'header_level'    => array(
						'default' => 'h4',
					),
					'hide_text_align' => true,
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl_star_rating_title",
						'important' => 'all',
					),
					'show_if'         => array( 'hide_title' => 'off' ),
					'depends_on'      => array( 'hide_title' ),
					'depends_show_if' => 'off',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'title',
				),
				'desc_text'     => array(
					'label'          => esc_html__( 'Description', 'divi-plus' ),
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
						'default'        => '1.3',
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
						'main' => "{$this->main_css_element} .dipl_star_rating_description, {$this->main_css_element} .dipl_star_rating_description p",
						'important' => 'all',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'desc',
					'sub_toggle'     => 'p',
				),
				'desc_link'     => array(
					'label'          => esc_html__( 'Link', 'divi-plus' ),
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
						'default'        => '1.3',
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
						'main' => "{$this->main_css_element} .dipl_star_rating_description a",
						'important' => 'all',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'desc',
					'sub_toggle'     => 'a',
				),
				'desc_ul'       => array(
					'label'          => esc_html__( 'Unordered List', 'divi-plus' ),
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
						'default'        => '1.3',
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
						'main' => "{$this->main_css_element} .dipl_star_rating_description ul li",
						'important' => 'all',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'desc',
					'sub_toggle'     => 'ul',
				),
				'desc_ol'       => array(
					'label'          => esc_html__( 'Ordered List', 'divi-plus' ),
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
						'default'        => '1.3',
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
						'main' => "{$this->main_css_element} .dipl_star_rating_description ol li",
						'important' => 'all',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'desc',
					'sub_toggle'     => 'ol',
				),
				'desc_quote'    => array(
					'label'          => esc_html__( 'Blockquote', 'divi-plus' ),
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
						'default'        => '1.3',
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
						'main' => "{$this->main_css_element} .dipl_star_rating_description blockquote",
						'important' => 'all',
					),
					'tab_slug'       => 'advanced',
					'toggle_slug'    => 'desc',
					'sub_toggle'     => 'quote',
				),
				'rating_number' => array(
					'label'           => esc_html__( 'Rating Number', 'divi-plus' ),
					'font_size'       => array(
						'default'        => '14px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
						'default'        => '1.2',
						'range_settings' => array(
							'min'  => '0.1',
							'max'  => '10',
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
						'main' => "{$this->main_css_element} .dipl_star_rating_number",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'rating_number',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => $this->main_css_element,
					'important' => 'all',
				),
			),
			'text'           => array(
				'css' => array(
					'text_orientation' => $this->main_css_element,
				),
			),
			'text_shadow'    => false,
			'link_options'   => false,
		);
	}

	public function get_fields() {
		$rating_out_of = array();
		for ( $i = 1; $i <= 10; $i++ ) {
			$rating_out_of[ $i ] = esc_html( sprintf( '%d', $i ) );
		}

		$fields = array(
			'title'              => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'default'          => 'Title',
				'default_on_front' => 'Title',
				'dynamic_content'  => 'text',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can input the title.', 'divi-plus' ),
			),
			'rating'             => array(
				'label'             => esc_html__( 'Rating', 'divi-plus' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'number_validation' => true,
				'value_type'        => 'float',
				'dynamic_content'  	=> 'text',
				'tab_slug'          => 'general',
				'toggle_slug'       => 'main_content',
				'description'       => esc_html__( 'Here you can choose the rating.', 'divi-plus' ),
			),
			'rating_out_of'      => array(
				'label'             => esc_html__( 'Rating Out Of', 'divi-plus' ),
				'type'              => 'number',
				'option_category'   => 'basic_option',
				'number_validation' => true,
				'value_type'        => 'integer',
				'default'           => '5',
				'default_on_front'  => '5',
				'show_if_not'       => array(
					'rating_icon' => 'smiley_scale'
				),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'main_content',
				'description'       => esc_html__( 'Here you can choose the rating out of.', 'divi-plus' ),
			),
			'image'              => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Here you can select the image.', 'divi-plus' ),
			),
			'image_alt'          => array(
				'label'           => esc_html__( 'Image Alt Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Define the HTML ALT text for your image here.', 'divi-plus' ),
			),
			'content'            => array(
				'label'           => esc_html__( 'Description', 'divi-plus' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input short description.', 'divi-plus' ),
			),
			'hide_title' => array(
				'label'           => esc_html__( 'Hide Title', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'         => 'off',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Choose whether or not the title should be visible.', 'divi-plus' ),
			),
			'rating_icon'    => array(
				'label'            => esc_html__( 'Rating Icon', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'default'      => esc_html__( 'Default/Stars', 'divi-plus' ),
					'like'         => esc_html__( 'Like', 'divi-plus' ),
					'heart'        => esc_html__( 'Heart', 'divi-plus' ),
					'smiley'       => esc_html__( 'Smiley', 'divi-plus' ),
					'smiley_scale' => esc_html__( 'Smiley Mood Scale', 'divi-plus' ),
					'trophy'       => esc_html__( 'Trophy', 'divi-plus' ),
					'sun'          => esc_html__( 'Sun', 'divi-plus' ),
					'drop'         => esc_html__( 'Drop', 'divi-plus' ),
				),
				'default'          => 'default',
				'default_on_front' => 'default',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can select the icon of rating.', 'divi-plus' ),
			),
			'rating_position'    => array(
				'label'            => esc_html__( 'Rating Position', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'layout',
				'options'          => array(
					'after_title' => esc_html__( 'After Title', 'divi-plus' ),
					'below_title' => esc_html__( 'Below Title', 'divi-plus' ),
				),
				'default'          => 'below_title',
				'default_on_front' => 'below_title',
				'tab_slug'         => 'general',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can select the position of star rating.', 'divi-plus' ),
			),
			'show_rating_number' => array(
				'label'           => esc_html__( 'Show Rating Number', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
					'off' => esc_html__( 'No', 'divi-plus' ),
				),
				'default'         => 'on',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'display',
				'description'     => esc_html__( 'Choose whether or not the rating number should be visible.', 'divi-plus' ),
			),
			'star_font_size'     => array(
				'label'           => esc_html__( 'Icon Size', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '24px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'stars',
				'description'     => esc_html__( 'Here you can choose the rate icon font size.', 'divi-plus' ),
			),
			'icon_spacing'     => array(
				'label'           => esc_html__( 'Space Between Icons', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'default'         => '2px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'stars',
				'description'     => esc_html__( 'Here you can choose the spacing between rate icons.', 'divi-plus' ),
			),
			'filled_star_color'  => array(
				'label'        => esc_html__( 'Filled Icon Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => '#fac917',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'stars',
				'description'  => esc_html__( 'Here you can define color for the filled star.', 'divi-plus' ),
			),
			'empty_star_color'   => array(
				'label'        => esc_html__( 'Empty Icon Color', 'divi-plus' ),
				'type'         => 'color-alpha',
				'custom_color' => true,
				'default'      => '#fac917',
				'tab_slug'     => 'advanced',
				'toggle_slug'  => 'stars',
				'description'  => esc_html__( 'Here you can define color for the empty star.', 'divi-plus' ),
			),
		);

		return $fields;
	}

	public function render( $attrs, $content, $render_slug ) {
		$multi_view            = et_pb_multi_view_options( $this );
		$rating                = abs( floatval( $this->props['rating'] ) );
		$rating_out_of         = absint( $this->props['rating_out_of'] ) ?? 5;
		$image_alt             = sanitize_text_field( $this->props['image_alt'] ) ?? '';
		$rating_position       = sanitize_text_field( $this->props['rating_position'] );
		$title_level           = $this->props['title_level'];
		$show_rating_number    = $this->props['show_rating_number'];
		$rating_icon           = $this->props['rating_icon'];
		$hide_title            = $this->props['hide_title'] ?? 'off';
		$processed_title_level = et_pb_process_header_level( $title_level, 'h4' );

		$title = '';
		if ( 'off' === $hide_title ) {
			$title = $multi_view->render_element( array(
				'tag'      => esc_html( $processed_title_level ),
				'content'  => '{{title}}',
				'attrs'    => array(
					'class' => 'dipl_star_rating_title',
				),
				'required' => 'title',
			) );
		}
		$image = $multi_view->render_element( array(
			'tag'      => 'img',
			'attrs'    => array(
				'src'   => '{{image}}',
				'class' => 'dipl_star_rating_image',
				'alt'   => esc_html( $image_alt ),
			),
			'required' => 'image',
		) );

		$content = $multi_view->render_element( array(
			'tag'      => 'div',
			'content'  => '{{content}}',
			'attrs'    => array(
				'class' => 'dipl_star_rating_description',
			),
			'required' => 'content',
		) );

		// For smily icons, out of icons must be 5.
		if ( in_array( $rating_icon, [ 'smiley_scale' ] ) && $rating_out_of !== 5 ) {
			$rating_out_of = 5;
		}

		if ( $rating > $rating_out_of ) {
			$rating = $rating_out_of;
		} else {
			$rating_mid_value = floatval( absint( $rating ) + 0.5 );

			if ( $rating > $rating_mid_value ) {
				$rating = ceil( $rating );
			} elseif ( $rating != absint( $rating ) ) {
				$rating = $rating_mid_value;
			}
		}

		// smiley_scale
		$rating_wrapper = '';
		if ( $rating && $rating > 0 ) {
			$moods = 1;
			$stars = '';
			for ( $i = 1; $i <= absint( $rating ); $i++ ) {
				$icon_svg = $this->get_svg_icon_content( $rating_icon, 'filled', $moods );
				if ( 'default' !== $rating_icon && ! empty( $icon_svg ) ) {
					$stars .= sprintf(
						'<span class="dipl_star_rating_star dipl-rating-icon-custom dipl_star_rating_filled dipl-rating-icon-%1$s">%2$s</span>',
						esc_attr( $rating_icon ),
						et_core_esc_previously( $icon_svg )
					);
				} else {
					$stars .= '<span class="dipl_star_rating_star dipl_star_rating_filled_star"></span>';
				}
				$moods++;
			}
			if ( $rating != absint( $rating ) ) {
				$icon_svg = $this->get_svg_icon_content( $rating_icon, 'half_filled', $moods );
				if ( 'default' !== $rating_icon && ! empty( $icon_svg ) ) {
					$stars .= sprintf(
						'<span class="dipl_star_rating_star dipl-rating-icon-custom dipl_star_rating_half_filled dipl-rating-icon-%1$s">%2$s</span>',
						esc_attr( $rating_icon ),
						et_core_esc_previously( $icon_svg )
					);
				} else {
					$stars .= '<span class="dipl_star_rating_star dipl_star_rating_half_filled_star"></span>';
				}
				$moods++;
				$unfilled_stars = $rating_out_of - absint( $rating ) - 1;
			} else {
				$unfilled_stars = $rating_out_of - absint( $rating );
			}
			for ( $i = 1; $i <= $unfilled_stars; $i++ ) {
				$icon_svg = $this->get_svg_icon_content( $rating_icon, 'empty', $moods );
				if ( 'default' !== $rating_icon && ! empty( $icon_svg ) ) {
					$stars .= sprintf(
						'<span class="dipl_star_rating_star dipl-rating-icon-custom dipl_star_rating_empty dipl-rating-icon-%1$s">%2$s</span>',
						esc_attr( $rating_icon ),
						et_core_esc_previously( $icon_svg )
					);
				} else {
					$stars .= '<span class="dipl_star_rating_star dipl_star_rating_empty_star"></span>';
				}
				$moods++;
			}

			if ( 'on' === $show_rating_number ) {
				$rating_number = sprintf(
					'<span class="dipl_star_rating_number">(%1$s/%2$s)</span>',
					esc_attr( $rating ),
					esc_attr( $rating_out_of )
				);
			}

			$rating_wrapper = sprintf(
				'<div class="dipl_star_rating_rating_wrapper">
					<span itemprop="starRating" itemscope itemtype="http://schema.org/Rating">
						<meta itemprop="ratingValue" content="%1$s" />
						<span class="dipl_star_rating_stars">%2$s</span>
						%3$s
					</span>
				</div>',
				esc_attr( $rating ),
				et_core_intentionally_unescaped( $stars, 'html' ),
				'on' === $show_rating_number ? et_core_intentionally_unescaped( $rating_number, 'html' ) : ''
			);
		}

		if ( '' !== $image ) {
			$image = sprintf(
				'<div class="dipl_star_rating_image_container">%1$s</div>',
				et_core_intentionally_unescaped( $image, 'html' )
			);
		}
		if ( '' !== $title || 0 !== $rating ) {
			$title = sprintf(
				'<div class="dipl_star_rating_title_container">%1$s%2$s</div>',
				et_core_intentionally_unescaped( $title, 'html' ),
				et_core_intentionally_unescaped( $rating_wrapper, 'html' )
			);
		}

		$wrapper = '';
		if ( '' !== $image || '' !== $title || 0 !== $rating || '' !== $content ) {
			$wrapper = sprintf(
				'<div class="dipl_star_rating_wrapper">%1$s%2$s%3$s</div>',
				et_core_intentionally_unescaped( $image, 'html' ),
				et_core_intentionally_unescaped( $title, 'html' ),
				et_core_intentionally_unescaped( $content, 'html' )
			);
		}

		if ( 'after_title' === $rating_position ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_star_rating_title_container .dipl_star_rating_title, %%order_class%% .dipl_star_rating_title_container .dipl_star_rating_rating_wrapper',
				'declaration' => 'display: inline-block;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_star_rating_title_container .dipl_star_rating_title',
				'declaration' => 'margin-right: 5px; padding: 0;',
			) );
		}

		// Set icon spacing.
		$icon_spacing = et_pb_responsive_options()->get_property_values( $this->props, 'icon_spacing' );
		et_pb_responsive_options()->generate_responsive_css( $icon_spacing, '%%order_class%% .dipl_star_rating_star:not(:last-child)', 'margin-right', $render_slug, '!important;', 'range' );

		$star_font_size    = et_pb_responsive_options()->get_property_values( $this->props, 'star_font_size' );
		$filled_star_color = et_pb_responsive_options()->get_property_values( $this->props, 'filled_star_color' );
		$empty_star_color  = et_pb_responsive_options()->get_property_values( $this->props, 'empty_star_color' );
		if ( 'default' !== $rating_icon ) {
			et_pb_responsive_options()->generate_responsive_css( $star_font_size, '%%order_class%% .dipl_star_rating_star svg', 'width', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $filled_star_color, '%%order_class%% .dipl_star_rating_filled svg, %%order_class%% .dipl_star_rating_half_filled svg', 'fill', $render_slug, '!important;', 'color' );
			et_pb_responsive_options()->generate_responsive_css( $empty_star_color, '%%order_class%% .dipl_star_rating_empty svg', 'fill', $render_slug, '!important;', 'color' );
		} else {
			et_pb_responsive_options()->generate_responsive_css( $star_font_size, '%%order_class%% .dipl_star_rating_star', 'font-size', $render_slug, '!important;', 'range' );
			et_pb_responsive_options()->generate_responsive_css( $filled_star_color, '%%order_class%% .dipl_star_rating_filled_star, %%order_class%% .dipl_star_rating_half_filled_star', 'color', $render_slug, '!important;', 'color' );
			et_pb_responsive_options()->generate_responsive_css( $empty_star_color, '%%order_class%% .dipl_star_rating_empty_star', 'color', $render_slug, '!important;', 'color' );
		}

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
		wp_enqueue_style( 'dipl-star-rating-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/StarRating/' . $file . '.min.css', array(), '1.0.0' );

		return et_core_intentionally_unescaped( $wrapper, 'html' );
	}

	/**
	 * Load the svg icon.
	 * 
	 * @since 1.16.0
	 */
	public function get_svg_icon_content( $icon, $type, $mood = '' ) {
		// Generate key and check if already exists.
		// This used in loop, because, used the smilies like:
		// Poor     : 😠
		// Bad      : 😞
		// Average  : 😐
		// Good     : 🙂
		// Excellent: 😄
		$key = "{$icon}-{$type}";
		if ( in_array( $icon, [ 'smiley_scale' ] ) && ! empty( $mood ) ) {
			$key = "{$icon}/{$mood}_{$type}";
		}

		if ( ! empty( $this->svg_icons[ $key ] ) ) {
			return $this->svg_icons[ $key ];
		}

		// Get svg icon path.
		$svg_path = ELICUS_DIVI_PLUS_DIR_PATH . 'includes/modules/StarRating/icons/' . esc_attr( $key ) . '.svg';
		if ( in_array( $icon, [ 'smiley_scale' ] ) && ! empty( $mood ) ) {
			$svg_path = ELICUS_DIVI_PLUS_DIR_PATH . "includes/modules/StarRating/icons/{$key}.svg";
		}

		if ( file_exists( $svg_path ) ) {
			$this->svg_icons[ $key ] = file_get_contents( $svg_path );
		}

		return isset( $this->svg_icons[ $key ] ) ? $this->svg_icons[ $key ] : '';
	}

}
$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_star_rating', $modules ) ) {
		new DIPL_StarRating();
	}
} else {
	new DIPL_StarRating();
}
