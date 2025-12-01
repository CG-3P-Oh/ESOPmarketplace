<?php
/**
 * @author    Elicus <hello@elicus.com>
 * @link      https://www.elicus.com/
 * @copyright 2024 Elicus Technologies Private Limited
 * @version   1.10.0
 */
class DIPL_ImageStackItem extends ET_Builder_Module {
	public $slug       = 'dipl_image_stack_item';
	public $type       = 'child';
	public $vb_support = 'on';

	/**
	 * Track if the module is currently rendering
	 * to prevent unnecessary rendering and recursion.
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
		$this->name                        = esc_html__( 'DP Image Stack Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Image Item', 'divi-plus' );
		$this->child_title_var             = 'title';
		$this->main_css_element            = '.dipl_image_stack %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(

				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(

			),
			'margin_padding' => array(
				'use_margin' => false,
				'css' => array(
					'main'      => "{$this->main_css_element}",
					'important' => 'all',
				),
			),
			'max_width'  => false,
			'height'     => false,
			'text'       => false,
			'filters'    => false,
			'transform'  => false,
			'background' => array(
				'use_background_video' => false,
				'options' => array(
					'parallax' => array( 'type' => 'skip' ),
				),
				'css' => array(
					'main' => "{$this->main_css_element}",
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'title' => array(
				'label'            => esc_html__( 'Title', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'basic_option',
				'dynamic_content'  => 'text',
				'default'          => esc_html__( 'Your title', 'divi-plus' ),
				'description'      => esc_html__( 'Enter the title, to display as tooltip when mouse hover, keep tooltip enable on parent settings.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
			'use_icon' => array(
				'label'           => esc_html__( 'Use Icon', 'divi-plus' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' )
				),
				'default'         => 'off',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can choose whether use image or icon.', 'divi-plus' ),
			),
			'image' => array(
				'label'              => esc_html__( 'Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'	 => 'image',
				'show_if'            => array(
					'use_icon' => 'off',
				),
				'toggle_slug'        => 'main_content',
				'description'        => esc_html__( 'Upload an image to display.', 'divi-plus' ),
			),
			'image_alt' => array(
				'label'           => esc_html__( 'Image Alt Text', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'use_icon' => 'off',
				),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input the text to be used for the image as HTML ALT text.', 'divi-plus' ),
			),
			'icon' => array(
				'label'           => esc_html__( 'Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'basic_option',
				'class'           => array( 'et-pb-font-icon' ),
				'show_if'         => array(
					'use_icon' => 'on',
				),
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can select the icon to display.', 'divi-plus' ),
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$multi_view = et_pb_multi_view_options( $this );

		$use_icon   = esc_attr( $this->props['use_icon'] );

		$render_output = '';
		if ( 'on' === $use_icon ) {
			// Render icon.
			$render_output = $multi_view->render_element( array(
				'content'  => '{{icon}}',
				'attrs'    => array(
					'class' => 'et-pb-icon dipl-stack-icon',
					'title' => '{{title}}',
				),
				'required' => 'icon',
			) );
		} else {
			// Render image.
			$render_output = $multi_view->render_element( array(
				'tag'      => 'img',
				'attrs'    => array(
					'src'   => '{{image}}',
					'alt'   => '{{image_alt}}',
					'title' => '{{title}}',
					'class' => 'dipl-stack-image',
				),
				'required' => 'image',
			) );
		}

		if ( 'on' === $use_icon && '' !== $this->props['icon'] ) {
			if ( class_exists( 'ET_Builder_Module_Helper_Style_Processor' ) && method_exists( 'ET_Builder_Module_Helper_Style_Processor', 'process_extended_icon' ) ) {
				$this->generate_styles( array(
					'utility_arg'    => 'icon_font_family',
					'render_slug'    => $render_slug,
					'base_attr_name' => 'icon',
					'important'      => true,
					'selector'       => '%%order_class%% .dipl-stack-icon',
					'processor'      => array(
						'ET_Builder_Module_Helper_Style_Processor',
						'process_extended_icon',
					),
				) );
			}
		}

		self::$rendering = false;
		return $render_output;
	}

	protected function _render_module_wrapper( $output = '', $render_slug = '' ) {
		$wrapper_settings    = $this->get_wrapper_settings( $render_slug );
		$slug                = $render_slug;
		$outer_wrapper_attrs = $wrapper_settings['attrs'];

		/**
		 * Filters the HTML attributes for the module's outer wrapper. The dynamic portion of the
		 * filter name, '$slug', corresponds to the module's slug.
		 *
		 * @since 3.23 Add support for responsive video background.
		 * @since 3.1
		 *
		 * @param string[]           $outer_wrapper_attrs
		 * @param ET_Builder_Element $module_instance
		 */
		$outer_wrapper_attrs = apply_filters( "et_builder_module_{$slug}_outer_wrapper_attrs", $outer_wrapper_attrs, $this );

		return sprintf(
			'<div%1$s>
				%3$s
				%4$s
				%2$s
			</div>',
			et_html_attrs( $outer_wrapper_attrs ),
			$output,
			et_core_esc_previously( $wrapper_settings['pattern_background'] ), // #3
			et_core_esc_previously( $wrapper_settings['mask_background'] ) // #4
		);
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
		if ( $raw_value && 'icon' === $name ) {
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
	if ( in_array( 'dipl_image_stack', $modules ) ) {
		new DIPL_ImageStackItem();
	}
} else {
	new DIPL_ImageStackItem();
}
