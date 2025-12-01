<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.18.0
 */
class DIPL_TableOfContents extends ET_Builder_Module {
	public $slug       = 'dipl_table_of_contents';
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
		$this->name             = esc_html__( 'DP Table of Contents', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';

		add_filter( 'the_content', array( $this, 'dipl_update_heading_tags_with_id' ), 99 );
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Configuration', 'divi-plus' ),
					
				)
			),
			'advanced' => array(
				'toggles' => array(
					'title'       => esc_html__( 'Title', 'divi-plus' ),
					'number_text' => esc_html__( 'Number Text', 'divi-plus' ),
					'content'     => esc_html__( 'Content', 'divi-plus' ),
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
							'max'  => '200',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color' => array(
                    	'default' => '#ffffff',
                    ),
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl-table-of-contents-title",
					),
					'depends_on'      => array( 'hide_title' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'title',
				),
				'number' => array(
					'label'     => esc_html__( 'Number', 'divi-plus' ),
					'font_size' => array(
						'default'        => '16px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'hide_text_align' => true,
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl-table-of-contents-content sectionnum",
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'number_text',
				),
				'link' => array(
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
					'text_color' => array(
						'default' => '#0073aa'
					),
					'hide_text_align' => true,
					'css'             => array(
						'main' => "{$this->main_css_element} .dipl-table-of-contents-content sectionsubject",
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'content',
				),
			),
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '%%order_class%% .dipl-table-of-contents-wrapper',
							'border_radii'  => '%%order_class%% .dipl-table-of-contents-wrapper',
						),
					),
					'defaults' => array(
						'border_radii'  => 'on||||',
						'border_styles' => array(
							'width' => '1px',
							'color' => '#dddddd',
							'style' => 'solid',
						),
					),
				)
			),
			'box_shadow' => array(
				'default' => array(
					'css' => array(
						'main' => '%%order_class%% .dipl-table-of-contents-wrapper',
					),
				)
			),
			'table_of_contents_spacing' => array(
				'title' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl-table-of-contents-title",
							'important'  => 'all',
						),
					),
				),
				'content' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl-table-of-contents-content",
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
		return array(
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'dynamic_content'  => 'text',
				'default'          => esc_html__( 'Table of Contents', 'divi-plus' ),
				'default_on_front' => esc_html__( 'Table of Contents', 'divi-plus' ),
				'description'      => esc_html__( 'Enter the text to display as a table of contents.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'level_one_tag' => array(
				'label'            => esc_html__( 'Level One Tag', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'1' => esc_html__( 'H1', 'divi-plus' ),
					'2' => esc_html__( 'H2', 'divi-plus' ),
					'3' => esc_html__( 'H3', 'divi-plus' ),
					'4' => esc_html__( 'H4', 'divi-plus' ),
					'5' => esc_html__( 'H5', 'divi-plus' ),
					'6' => esc_html__( 'H6', 'divi-plus' ),
				),
				'mobile_options'   => false,
				'default'          => '2',
				'default_on_front' => '2',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Select the level one heading tag to generate table of contents.', 'divi-plus' ),
				'computed_affects' => array(
					'__table_of_contents_data',
				),
			),
			'level_two_tag' => array(
				'label'            => esc_html__( 'Level Two Tag', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'options'          => array(
					'none' => esc_html__( 'Do not generate', 'divi-plus' ),
					'1'    => esc_html__( 'H1', 'divi-plus' ),
					'2'    => esc_html__( 'H2', 'divi-plus' ),
					'3'    => esc_html__( 'H3', 'divi-plus' ),
					'4'    => esc_html__( 'H4', 'divi-plus' ),
					'5'    => esc_html__( 'H5', 'divi-plus' ),
					'6'    => esc_html__( 'H6', 'divi-plus' ),
				),
				'mobile_options'   => false,
				'default'          => '3',
				'default_on_front' => '3',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Select the level two heading tag to generate table of contents.', 'divi-plus' ),
				'computed_affects' => array(
					'__table_of_contents_data',
				),
			),
			'hide_title' => array(
				'label'            => esc_html__( 'Hide Title', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default'          => 'off',
				'default_on_front' => 'off',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Here you can choose whether or not to display table of contents title.', 'divi-plus' ),
			),
			'title_background' => array(
				'label'            => esc_html__( 'Title Background', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'mobile_options'   => false,
				'hover'            => 'tabs',
				'default'          => '#101010',
				'default_on_front' => '#101010',
				'show_if'          => array( 'hide_title' => 'off' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'title',
				'description'      => esc_html__( 'Select the background color of title.', 'divi-plus' ),
			),
			'title_custom_padding' => array(
				'label'           => esc_html__( 'Title Padding', 'divi-plus' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'mobile_options'  => true,
				'default'         => '20px|20px|20px|20px|true|true',
				'show_if'         => array( 'hide_title' => 'off' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'title',
				'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'content_custom_padding' => array(
				'label'           => esc_html__( 'Content Padding', 'divi-plus' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'mobile_options'  => true,
				'default'         => '20px|20px|20px|20px|true|true',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'content',
				'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'__table_of_contents_data' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_TableOfContents', 'get_computed_table_of_contents_data' ),
				'computed_depends_on' => array(
					'level_one_tag',
					'level_two_tag'
				)
			)
		);
	}

	public static function get_computed_table_of_contents_data( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'level_one_tag' => 2,
			'level_two_tag' => 3,
		);
		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $args, $key, $default ) );
		}

		// WordPress' native conditional tag is only available during page load. It'll fail during component update because
		// et_pb_process_computed_property() is loaded in admin-ajax.php. Thus, use WordPress' conditional tags on page load and
		// rely to passed $conditional_tags for AJAX call.
		$current_post_id = isset( $current_page['id'] ) ? (int) $current_page['id'] : 0;

		// Get current post.
		$post = get_post( $current_post_id );

		if ( ! $post ) {
			return '';
		}

		// Temporarily remove this module's shortcode before running do_shortcode.
		$backup_shortcode_tags = $shortcode_tags;
		unset( $shortcode_tags['dipl_table_of_contents'] ); // <-- Replace with your shortcode name.

		$post_content = do_shortcode( $post->post_content );

		// Restore shortcodes.
		// $shortcode_tags = $backup_shortcode_tags;
		
		// Get fully rendered content (Divi/shortcodes expanded).
		$table_of_contents = self::get_table_of_contents( $post_content, $level_one_tag, $level_two_tag );
		if ( ! empty( $table_of_contents ) ) {
			return sprintf( '<tableOfContents>%1$s</tableOfContents>', et_core_esc_previously( $table_of_contents ) );
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

		global $post, $shortcode_tags;

		$hide_title    = sanitize_text_field( $this->props['hide_title'] ) ?? 'off';
		$level_one_tag = absint( $this->props['level_one_tag'] ) ?? 2;
		$level_two_tag = absint( $this->props['level_two_tag'] ) ?? 3;

		// Temporarily remove this module's shortcode before running do_shortcode.
		$backup_shortcode_tags = $shortcode_tags;
		unset( $shortcode_tags['dipl_table_of_contents'] ); // <-- Replace with your shortcode name.

		$post_content = do_shortcode( $post->post_content );

		// Restore shortcodes.
		// $shortcode_tags = $backup_shortcode_tags;

		// Get fully rendered content (Divi/shortcodes expanded).
		$table_of_contents = self::get_table_of_contents( $post_content, $level_one_tag, $level_two_tag );

		$render_output = '';
		if ( ! empty( $table_of_contents ) ) {

			// Load style and script.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-table-of-contents-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/TableOfContents/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'dipl-table-of-contents-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/TableOfContents/dipl-table-of-contents-custom.min.js", array('jquery'), '1.0.0', true );

			$title = '';
			if ( ! empty( $this->props['title'] ) && 'off' === $hide_title ) {
				$title = sprintf(
					'<div class="dipl-table-of-contents-title">%1$s</div>',
					esc_html( $this->props['title'] )
				);
			}

			// Final output.
			$render_output = sprintf(
				'<div class="dipl-table-of-contents-wrapper" role="navigation" aria-label="%1$s">
					%2$s
					<div class="dipl-table-of-contents-content">
						<tableOfContents>%3$s</tableOfContents>
					</div>
				</div>',
				esc_html( $this->props['title'] ),
				et_core_esc_previously( $title ),
				et_core_esc_previously( $table_of_contents )
			);

			// Title background color.
			if ( 'off' === $hide_title ) {
				$this->generate_styles( array(
					'hover'          => true,
					'base_attr_name' => 'title_background',
					'selector'       => '%%order_class%% .dipl-table-of-contents-title',
					'hover_selector' => '%%order_class%% .dipl-table-of-contents-title:hover',
					'css_property'   => 'background-color',
					'render_slug'    => $render_slug,
					'type'           => 'color',
				) );
			}

			$fields = array( 'table_of_contents_spacing' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );
		}

		self::$rendering = false;
		return $render_output;
	}

	/**
	 * Table of content.
	 */
	public static function get_table_of_contents( $content, $level_one_tag = 2, $level_two_tag = 3 ) {
		if ( empty( $content ) ) {
			return '';
		}

		// Match the tags, like H2 + H3.
		if ( empty( $level_two_tag ) || 'none' === $level_two_tag ) {
			preg_match_all( '/<h([' . intval( $level_one_tag ) . ']).*?>(.*?)<\/h[' . intval( $level_one_tag ) . ']>/', $content, $matches, PREG_SET_ORDER );
		} else {
			// preg_match_all( '/<h([' . intval( $level_one_tag ) . '|' . intval( $level_two_tag ) . ']).*? >(.*?)<\/h[' . absint( $level_one_tag ) . '|' . absint( $level_two_tag ) . ']>/', $content, $matches, PREG_SET_ORDER );
			preg_match_all( '/<h(' . intval($level_one_tag) . '|' . intval($level_two_tag) . ')\b[^>]*>([\s\S]*?)<\/h\1>/i', $content, $matches, PREG_SET_ORDER );
		}

		// no headings.
		if ( empty( $matches ) ) {
			return '';
		}

		$toc_inner        = '';
		$section_counter  = 0;
		$sub_counters     = [];
		$current_h2_entry = '';
		foreach ( $matches as $heading ) {
			$level   = intval( $heading[1] );
			$text    = wp_strip_all_tags( $heading[2] );
			$fullTag = $heading[0];

			// Check for the id.
			if ( preg_match( '/id=["\']([^"\']+)["\']/', $fullTag, $id_match ) ) {
				$id = sanitize_title( $id_match[1] );
			} else {
				$id = sanitize_title( $text );
				// Inject ID only if missing.
				$newTag = preg_replace(
					'/^<h' . $level . '\b(?![^>]*\bid=)/i',
					'<h' . $level . ' id="' . $id . '"',
					$fullTag
				);
				if ( $newTag !== $fullTag ) {
					$content = str_replace( $fullTag, $newTag, $content );
				}
			}

			if ( $level === intval( $level_one_tag ) ) {
				// Close previous H2 block if open.
				if ( $current_h2_entry ) {
					// If sub points exist, close nested tableOfContents.
					if ( ! empty( $sub_counters ) ) {
						$current_h2_entry .= '</tableOfContents>';
						$sub_counters = [];
					}
					$current_h2_entry .= '</tocSecEntry>';
					$toc_inner .= $current_h2_entry;
				}

				$section_counter++;
				$current_h2_entry = sprintf(
					'<tocSecEntry target="%1$s"><a href="#%1$s">
						<sectionNum>%2$s</sectionNum>
						<sectionSubject>%3$s</sectionSubject>
					</a>',
					esc_attr( $id ),
					esc_html( $section_counter ),
					esc_html( $text )
				);
			} elseif ( $level === intval( $level_two_tag ) && $current_h2_entry ) {
				// Start nested TOC if not started.
				if ( empty( $sub_counters ) ) {
					$current_h2_entry .= '<tableOfContents>';
				}

				$sub_counters[] = true; // track sub count.
				$sub_num = count( $sub_counters );

				$current_h2_entry .= sprintf(
					'<tocSecEntry target="%1$s"><a href="#%1$s">
						<sectionNum>%2$s.%3$s</sectionNum>
						<sectionSubject>%4$s</sectionSubject>
					</a></tocSecEntry>',
					esc_attr( $id ),
					esc_html( $section_counter ),
					esc_html( $sub_num ),
					esc_html( $text )
				);
			}
		}

		// Close the last H2 entry.
		if ( $current_h2_entry ) {
			if ( ! empty( $sub_counters ) ) {
				$current_h2_entry .= '</tableOfContents>';
			}
			$current_h2_entry .= '</tocSecEntry>';
			$toc_inner .= $current_h2_entry;
		}

		return $toc_inner;
	}

	/**
	 * Update content with id.
	 */
	public function dipl_update_heading_tags_with_id( $content ){
		$level_one_tag  = absint( $this->props['level_one_tag'] ?? 2 );
		$level_two_tag  = $this->props['level_two_tag'] ?? 3;
		$allowed_levels = [];

		// Always add primary heading level.
		if ( $level_one_tag >= 1 && $level_one_tag <= 6 ) {
			$allowed_levels[] = $level_one_tag;
		}

		// Add secondary heading if not "none".
		if ( ! empty( $level_two_tag ) && 'none' !== $level_two_tag ) {
			$level_two_tag = absint( $level_two_tag );
			if ( $level_two_tag >= 1 && $level_two_tag <= 6 ) {
				$allowed_levels[] = $level_two_tag;
			}
		}

		// If no valid levels, return unchanged.
		if ( empty( $allowed_levels ) ) {
			return $content;
		}

		// Build regex dynamically for only selected levels.
		$levels_regex = implode( '|', $allowed_levels );

		return preg_replace_callback(
			'/<h(' . $levels_regex . ')\b([^>]*)>(.*?)<\/h\1>/i',
			function ( $matches ) {
				$level = $matches[1];
				$attrs = $matches[2];
				$text  = $matches[3];

				// If already has ID → keep it.
				if ( preg_match( '/id=["\']([^"\']+)["\']/', $attrs, $id_match ) ) {
					$id = sanitize_title( $id_match[1] );
				} else {
					$id = sanitize_title( $text );
					$attrs .= ' id="' . $id . '"';
				}

				return "<h{$level}{$attrs}>{$text}</h{$level}>";
			},
			$content
		);
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_table_of_contents', $modules ) ) {
		new DIPL_TableOfContents();
	}
} else {
	new DIPL_TableOfContents();
}
