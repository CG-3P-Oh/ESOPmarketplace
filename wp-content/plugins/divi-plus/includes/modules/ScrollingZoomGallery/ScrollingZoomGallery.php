<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2025 Elicus Technologies Private Limited
 * @version     1.16.0
 */
class DIPL_ScrollingZoomGallery extends ET_Builder_Module {
	public $slug       = 'dipl_scrolling_zoom_gallery';
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
		$this->name             = esc_html__( 'DP Scrolling Zoom Gallery', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'images' => esc_html__( 'Images', 'divi-plus' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'images_style' => esc_html__( 'Images', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(),
			'borders' => array(
				'image' => array(
					'label_prefix' => esc_html__( 'Images', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dipl_scroll_zoom_gallery_item img",
							'border_styles' => "{$this->main_css_element} .dipl_scroll_zoom_gallery_item img",
						),
						'important' => 'all',
					),
					'tab_slug'     => 'advanced',
					'toggle_slug'  => 'images_style',
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
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%%',
					'important' => 'all',
				),
			),
			'text'         => false,
			'text_shadow'  => false,
			'height'       => false,
			'background'   => array(
				'use_background_video' => false,
				'css' => array(
					'main' => '%%order_class%% .dipl_scroll_zoom_gallery_scroller',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'image_ids' => array(
				'label'            => esc_html__( 'Images', 'divi-plus' ),
				'type'             => 'upload-gallery',
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'images',
				'description'      => esc_html__( 'Choose the images that you would like to appear in the scrolling zoom gallery.', 'divi-plus' ),
				'computed_affects' => array(
					'__gallery_images',
				),
			),
			'image_size' => array(
				'label'             => esc_html__( 'Image Size', 'divi-plus' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'thumbnail' => esc_html__( 'Thumbnail', 'divi-gallery-extended' ),
					'medium' 	=> esc_html__( 'Medium', 'divi-gallery-extended' ),
					'large' 	=> esc_html__( 'Large', 'divi-gallery-extended' ),
					'full' 		=> esc_html__( 'Full', 'divi-gallery-extended' ),
				),
				'default'           => 'full',
				'default_on_front'  => 'full',
				'toggle_slug'       => 'images',
				'description'       => esc_html__( 'Here you can select the size of images in gallery.', 'divi-plus' ),
				'computed_affects' 	=> array(
					'__gallery_images',
				),
			),
			'start_opacity' => array(
				'label'           => esc_html__( 'On Load Visibility', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '0.9',
					'step' => '0.1',
				),
				'mobile_options'  => false,
				'unitless'        => true,
				'default'         => '0',
				'toggle_slug'     => 'images',
				'description'     => esc_html__( 'Move the slider or input the value to increse or decrease the the visibility/opacity on page load.', 'divi-plus' ),
			),
			'no_images_text' => array(
				'label'            => esc_html__( 'No Images Text', 'divi-plus' ),
				'type'             => 'text',
				'option_category'  => 'configuration',
				'default'		   => esc_html__( 'No images found, please upload images to view gallery.', 'divi-plus' ),
				'toggle_slug'      => 'images',
				'description'      => esc_html__( 'Here you can define custom no result text.', 'divi-plus' ),
			),
			'__gallery_images' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_ScrollingZoomGallery', 'get_gallery_images' ),
				'computed_depends_on' => array(
					'image_ids',
					'image_size',
				),
			),

		);
	}

	public static function get_gallery_images( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'image_ids'  => '',
			'image_size' => 'full',
		);
		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $args, $key, $default ) );
		}

		// Explode images string to array.
		$image_ids_arr = array();
		if ( ! empty( $image_ids ) ) {
			$image_ids_arr = explode( ',', $image_ids );
		}

		// If gallery image found.
		$items = [];
		if ( ! empty( $image_ids_arr ) ) {
			foreach ( $image_ids_arr as $attachment_id ) {
				$items[] = wp_get_attachment_image( $attachment_id, $image_size, false, array(
					'class' => 'dipl_scroll_zoom_gallery_image',
				) );
			}
		}

		return $items;
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a DIPL Woo Product module while a DIPL Woo Product module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$image_ids     = sanitize_text_field( $this->props['image_ids'] ) ?? '';
		$image_size    = sanitize_text_field( $this->props['image_size'] ) ?? 'full';
		$start_opacity = sanitize_text_field( $this->props['start_opacity'] ) ?? 0;

		// Explode images string to array.
		$image_ids_arr = array();
		if ( ! empty( $image_ids ) ) {
			$image_ids_arr = explode( ',', $image_ids );
		}

		// If gallery image found.
		if ( ! empty( $image_ids_arr ) ) {

			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-scrolling-zoom-gallery-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ScrollingZoomGallery/' . $file . '.min.css', array(), '1.0.0' );

			wp_enqueue_script( 'elicus-scroll-trigger-script' );
			wp_enqueue_script( 'elicus-gsap-script' );
			wp_enqueue_script( 'dipl-scrolling-zoom-gallery-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/ScrollingZoomGallery/dipl-scrolling-zoom-gallery-custom.min.js", array('jquery'), '1.0.0', true );

			$items = '';
			foreach ( $image_ids_arr as $attachment_id ) {

				// Get attachment image.
				$image = wp_get_attachment_image( $attachment_id, 'orignal', false, array(
					'class' => 'dipl_scroll_zoom_gallery_image',
				) );
 
				if ( ! empty( $image  ) ) {
					$items .= sprintf(
						'<div class="dipl_scroll_zoom_gallery_item">%1$s</div>',
						et_core_esc_previously( $image )
					);
				}
			}

			// Final output.
			$render_output = sprintf(
				'<div class="dipl_scroll_zoom_gallery_scroller" data-start_opacity="%2$s">
					<div class="dipl_scroll_zoom_gallery_wrapper">
						<div class="dipl_scroll_zoom_gallery_inner">%1$s</div>
					</div>
				</div>',
				et_core_esc_previously( $items ),
				esc_attr( $start_opacity )
			);
		} else {
			// No gallery text.
			$render_output  = sprintf(
				'<div class="entry">%1$s</div>',
				esc_html( $this->props['no_images_text'] ?? '' )
			);
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_scrolling_zoom_gallery', $modules ) ) {
		new DIPL_ScrollingZoomGallery();
	}
} else {
	new DIPL_ScrollingZoomGallery();
}
