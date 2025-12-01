<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.18.0
 */
class DIPL_SplitImage extends ET_Builder_Module {
	public $slug       = 'dipl_split_image';
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
		$this->name             = esc_html__( 'DP Split Image', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'configuration' => esc_html__( 'Configuration', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'split_portion' => esc_html__( 'Split Portion', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts' => array(
			),
			'borders' => array(
				'split_portion' => array(
					'label_prefix'    => esc_html__( 'Portion', 'divi-plus' ),
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_split_image_portion',
							'border_styles' => '%%order_class%% .dipl_split_image_portion',
						),
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'split_portion'
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
			'text'		   => false,
			'filters'      => false,
			'background'   => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'image' => array(
				'label'              => esc_html__( 'Select Image', 'divi-plus' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-plus' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-plus' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-plus' ),
				'dynamic_content'  	 => 'image',
				'description'        => esc_html__( 'Here you can upload the image to be used to split.', 'divi-plus' ),
				'toggle_slug'        => 'configuration',
			),
			'image_aspect_ratio' => array( 'type' => 'hidden' ),
			'rows' => array(
				'label'            => esc_html__( 'Rows', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '3',
				'default_on_front' => '3',
				'default_unit'     => '',
				'allowed_units'    => '',
				'unitless'         => true,
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '20',
					'step' => '1',
				),
				'mobile_options'   => false,
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Adjust rows to split the image.', 'divi-plus' ),
			),
			'columns' => array(
				'label'            => esc_html__( 'Columns', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '3',
				'default_on_front' => '3',
				'default_unit'     => '',
				'allowed_units'    => '',
				'unitless'         => true,
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '20',
					'step' => '1',
				),
				'mobile_options'   => false,
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Adjust rows to split the image.', 'divi-plus' ),
			),
			'gap' => array(
				'label'            => esc_html__( 'Gap', 'divi-plus' ),
				'type'             => 'range',
				'default'          => '15px',
				'default_on_front' => '15px',
				'default_unit'     => 'px',
				'allowed_units'    => array( 'px' ),
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'mobile_options'   => false,
				'toggle_slug'      => 'configuration',
				'description'      => esc_html__( 'Adjust gap between the grid of split image.', 'divi-plus' ),
			),
		);
	}

	public function before_render() {
		if ( ! empty( $this->props['image'] ) ) {
			$this->props['image_aspect_ratio'] = self::get_image_aspect_ratio( $this->props['image'] );
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

		$rows    = absint( $this->props['rows'] ) ?? 3;
		$columns = absint( $this->props['columns'] ) ?? 3;

		// Load style and script.
		wp_enqueue_script( 'dipl-split-image-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/SplitImage/dipl-split-image-custom.min.js", array('jquery'), '1.0.0', true );

		$grid_boxes = '';
		for ( $i = 1; $i <= ( $rows * $columns ); $i++ ) {
			$grid_boxes .= '<span class="dipl_split_image_portion"></span>';
		}

		// Final output.
		$render_output = sprintf(
			'<div class="dipl_split_image_wrapper" data-image-rows="%1$s" data-image-columns="%2$s">%3$s</div>',
			esc_attr( $rows ),
			esc_attr( $columns ),
			et_core_esc_previously( $grid_boxes )
		);

		// Default style.
		self::set_style( $render_slug, array(
			'selector'    => '%%order_class%% .dipl_split_image_portion',
			'declaration' => 'border: 0px solid #000;',
		) );

		// Set background image.
		if ( ! empty( $this->props['image'] ) ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_split_image_portion',
				'declaration' => sprintf( 'background-image: url(%1$s);', esc_url( $this->props['image'] ) ),
			) );

			// Calc aspect ratio.
			$aspect_ratio = self::get_image_aspect_ratio( $this->props['image'] );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_split_image_wrapper',
				'declaration' => sprintf( 'display: grid; aspect-ratio: %1$s;', esc_attr( $aspect_ratio ) ),
			) );
		}

		// Alignment of percentage number.
		$gap = et_pb_responsive_options()->get_property_values( $this->props, 'gap' );
		if ( ! empty( array_filter( $gap ) ) ) {
			et_pb_responsive_options()->generate_responsive_css( $gap, '%%order_class%% .dipl_split_image_wrapper', 'gap', $render_slug, '!important;', 'range' );
		}

		self::$rendering = false;
		return et_core_intentionally_unescaped( $render_output, 'html' );
	}

	/**
	 * Get CSS aspect ratio from an image URL.
	 *
	 * @param string $image_url Image URL.
	 * @return string|false Aspect ratio string (e.g., "16 / 9") or false on failure.
	 */
	public static function get_image_aspect_ratio( $image_url ) {
		if ( empty( $image_url ) ) {
			return false;
		}

		// Suppress errors when image is invalid or inaccessible.
		$size = @getimagesize( $image_url );

		if ( false === $size ) {
			return false;
		}

		$width  = isset( $size[0] ) ? (int) $size[0] : 0;
		$height = isset( $size[1] ) ? (int) $size[1] : 0;
		if ( 0 === $height || 0 === $width ) {
			return false;
		}

		// Get greatest common divisor (GCD).
		$gcd = function( $a, $b ) use ( &$gcd ) {
			return ( 0 === $b ) ? $a : $gcd( $b, $a % $b );
		};

		$divisor = $gcd( $width, $height );

		$w = $width / $divisor;
		$h = $height / $divisor;

		return sprintf( '%d / %d', $w, $h );
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_split_image', $modules ) ) {
		new DIPL_SplitImage();
	}
} else {
	new DIPL_SplitImage();
}
