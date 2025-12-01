<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.18.0
 */
class DIPL_WavyGallery extends ET_Builder_Module {
	public $slug       = 'dipl_wavy_gallery';
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
		$this->name             = esc_html__( 'DP Wavy Gallery', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Images', 'divi-plus' ),
					'display'      => esc_html__( 'Display', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'lightbox'       => esc_html__( 'Lightbox', 'divi-plus' ),
					'lightbox_title' => esc_html__( 'Lightbox Title', 'divi-plus' )
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
				'lightbox_title' => array(
					'label'     => esc_html__( 'Lightbox Title', 'divi-plus' ),
					'font_size' => array(
						'default'        => '20px',
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'text_color' => array(
                    	'default' => '#ffffff',
                    ),
					'css'             => array(
						'main' => "{$this->main_css_element}_lightbox .dipl_wavy_gallery_overlay_item_title",
					),
					'depends_on'      => array( 'show_overlay_title' ),
					'depends_show_if' => 'on',
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'lightbox_title',
				),
			),
			'borders' => array(
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
			'image_ids' => array(
				'label'            => esc_html__( 'Select Images', 'divi-plus' ),
				'type'             => 'upload-gallery',
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Choose the images from which you would like to appear randomly.', 'divi-plus' ),
				'computed_affects' => array(
					'__wavy_gallery_data',
				),
			),
			'js_init_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'configuration',
				'toggle_slug'     => 'display',
				'value'           => true,
				'display_if'      => true,
				'message'         => esc_html__( 'Wavy Gallery does not work in the Visual Builder. Please check the frontend to see the effect correctly.', 'divi-plus' )
			),
			'images_width' => array(
				'label'            => esc_html__( 'Images Width', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '60px',
				'default_on_front' => '60px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'range_settings'   => array(
					'min'  => '10',
					'max'  => '300',
					'step' => '1',
				),
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Increase or decrease the images width to display in gallery.', 'divi-plus' ),
			),
			'images_height' => array(
				'label'            => esc_html__( 'Images Height', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '240px',
				'default_on_front' => '240px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'range_settings'   => array(
					'min'  => '10',
					'max'  => '700',
					'step' => '1',
				),
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Increase or decrease the images height to display in gallery.', 'divi-plus' ),
			),
			'images_gap' => array(
				'label'            => esc_html__( 'Images Gap', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '15px',
				'default_on_front' => '15px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '150',
					'step' => '1',
				),
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Increase or decrease the gap between images in  gallery.', 'divi-plus' ),
			),
			'show_overlay_title' => array(
				'label'            => esc_html__( 'Show Title on Overlay', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => esc_html__( 'No', 'divi-plus' ),
					'on'  => esc_html__( 'Yes', 'divi-plus' ),
				),
				'default_on_front' => 'on',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Show/Hide the title on the overlay.', 'divi-plus' ),
			),
			'lightbox_background' => array(
				'label'            => esc_html__( 'Lighbox Background', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'mobile_options'   => false,
				'hover'            => 'tabs',
				'default'          => '#111111',
				'default_on_front' => '#111111',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'lightbox',
				'description'      => esc_html__( 'Select the background color of lightbox.', 'divi-plus' ),
			),
			'__wavy_gallery_data' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_WavyGallery', 'get_computed_wavy_gallery_data' ),
				'computed_depends_on' => array(
					'image_ids',
				)
			)
		);
	}

	public static function get_computed_wavy_gallery_data( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'image_ids' => '',
		);

		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $args, $key, $default ) );
		}

		$image_ids_arr = explode( ',', $image_ids );

		$image_items   = array();
		foreach ( $image_ids_arr as $image_id ) {
			$image_items[] = wp_get_attachment_image( $image_id, 'full', false, array( 'title' => get_the_title( $image_id ) ) );
		}

		return $image_items;
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$image_ids     = sanitize_text_field( $this->props['image_ids'] ) ?? '';
		$image_ids_arr = ! empty( $image_ids ) ? explode( ',', $image_ids ) : array();

		$render_output = '';
		if ( ! empty( $image_ids_arr ) ) {

			// Load style and script.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-wavy-gallery-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/WavyGallery/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'elicus-gsap-script' );
			wp_enqueue_script( 'dipl-wavy-gallery-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/WavyGallery/dipl-wavy-gallery-custom.min.js", array('jquery', 'elicus-gsap-script'), '1.0.0', true );

			$image_items = '';
			foreach ( $image_ids_arr as $image_id ) {
				$image_items .= sprintf(
					'<div class="dipl_wavy_gallery_item">%1$s</div>',
					wp_get_attachment_image( $image_id, 'full', false, array( 'title' => get_the_title( $image_id ) ) )
				);
			}

			// Data props.
			$data_props = array( 'show_overlay_title' );
			$data_atts  = $this->props_to_html_data_attrs( $data_props );

			// Final output.
			$render_output = sprintf(
				'<div class="dipl_wavy_gallery_wrapper" %2$s>
					<div class="dipl_wavy_gallery_items">%1$s</div>
				</div>',
				et_core_esc_previously( $image_items ),
				et_core_esc_previously( $data_atts )
			);

			$images_width = et_pb_responsive_options()->get_property_values( $this->props, 'images_width' );
			if ( ! empty( array_filter( $images_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $images_width, '%%order_class%% .dipl_wavy_gallery_item', 'width', $render_slug, '', 'range' );
			}
			$images_height = et_pb_responsive_options()->get_property_values( $this->props, 'images_height' );
			if ( ! empty( array_filter( $images_height ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $images_height, '%%order_class%% .dipl_wavy_gallery_item', 'height', $render_slug, '', 'range' );
			}
			$images_gap = et_pb_responsive_options()->get_property_values( $this->props, 'images_gap' );
			if ( ! empty( array_filter( $images_gap ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $images_gap, '%%order_class%% .dipl_wavy_gallery_items', 'gap', $render_slug, '', 'range' );
			}

			// Lightbox background color.
			$this->generate_styles( array(
				'hover'          => true,
				'base_attr_name' => 'lightbox_background',
				'selector'       => '%%order_class%%_lightbox',
				'hover_selector' => '%%order_class%%_lightbox:hover',
				'css_property'   => 'background',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_wavy_gallery', $modules ) ) {
		new DIPL_WavyGallery();
	}
} else {
	new DIPL_WavyGallery();
}
