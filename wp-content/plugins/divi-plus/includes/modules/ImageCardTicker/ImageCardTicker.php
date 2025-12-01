<?php
/**
 * @author     Elicus <hello@elicus.com>
 * @link       https://www.elicus.com/
 * @copyright  2025 Elicus Technologies Private Limited
 * @version    1.17.0
 */
class DIPL_ImageCardTicker extends ET_Builder_Module {
	public $slug       = 'dipl_image_card_ticker';
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
		$this->name             = esc_html__( 'DP Image Card Ticker', 'divi-plus' );
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
					'wrap_style'   => esc_html__( 'Container Box', 'divi-plus' ),
					'images_style' => esc_html__( 'Images', 'divi-plus' ),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'borders' => array(
				'image' => array(
					'label_prefix' => esc_html__( 'Image', 'divi-plus' ),
					'defaults' => array(
						'border_radii' => 'on||||',
					),
					'css'          => array(
						'main' => array(
							'border_radii'  => "%%order_class%% .dipl_image_card_ticker_inner img",
							'border_styles' => "%%order_class%% .dipl_image_card_ticker_inner img",
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
				'images' => array(
					'label' => esc_html__( 'Image Box Shadow', 'divi-plus' ),
					'css'   => array(
						'main'      => "%%order_class%% .dipl_image_card_ticker_inner img",
						'important' => 'all',
					),
					'tab_slug'    => 'advanced',
					'toggle_slug' => 'images_style',
				),
				'default' => array(
					'css' => array(
						'main'    => '%%order_class%%',
						'overlay' => 'inset',
					),
				)
			),
			'image_card_ticker_spacing' => array(
				'wrap' => array(
					'margin_padding' => array(
						'css' => array(
							'use_margin' => false,
							'padding'    => "%%order_class%% .dipl_image_card_ticker_inner",
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
			'background' => array(
				'css' => array(
					'main' => '%%order_class%%',
				),
			),
			'fonts'  => false,
			'text'   => false,
		);
	}

	public function get_fields() {
		return array(
			'image_ids' => array(
				'label'            => esc_html__( 'Images', 'divi-plus' ),
				'type'             => 'upload-gallery',
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'main_content',
				'description'      => esc_html__( 'Choose the images that you would like to appear in the image card ticker.', 'divi-plus' ),
				'computed_affects' => array(
					'__ticker_images',
				),
			),
			'layout' => array(
				'label'            => esc_html__( 'Layout', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'marquee'     => esc_html__( 'Marquee', 'divi-plus' ),
					'3d_circular' => esc_html__( '3D Circular', 'divi-plus' ),
					'curve'       => esc_html__( 'Curve', 'divi-plus' ),
				),
				'default'          => 'marquee',
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can choose layout to display image ticker.', 'divi-plus'),
			),
			'marquee_direction' => array(
				'label'            => esc_html__( 'Marquee Direction', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'left'   => esc_html__( 'Left', 'divi-plus' ),
					'right'  => esc_html__( 'Right', 'divi-plus' ),
					'top'    => esc_html__( 'Top', 'divi-plus' ),
					'bottom' => esc_html__( 'Bottom', 'divi-plus' ),
				),
				'default'          => 'left',
				'mobile_options'   => true,
				'show_if'          => array( 'layout' => 'marquee' ),
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can choose direction of the marquee card images.', 'divi-plus'),
			),
			'direction' => array(
				'label'            => esc_html__( 'Direction', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'left'  => esc_html__( 'Left', 'divi-plus' ),
					'right' => esc_html__( 'Right', 'divi-plus' ),
				),
				'default'          => 'left',
				'mobile_options'   => true,
				'show_if_not'      => array( 'layout' => 'marquee' ),
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Here you can choose direction of the marquee card images.', 'divi-plus'),
			),
			'images_gap' => array(
				'label'            => esc_html__( 'Images Gap', 'divi-plus' ),
				'type'             => 'range',
				'range_settings'   => array(
					'min'  => '0',
					'max'  => '200',
					'step' => '1',
				),
				'default'          => '30',
				'default_on_front' => '30',
				'unitless'         => true,
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Move the slider or input the value to increase or decrease gap between images.', 'divi-plus' ),
			),
			'ticker_speed' => array(
				'label'            => esc_html__( 'Ticker Speed', 'divi-plus' ),
				'type'             => 'range',
				'range_settings'   => array(
					'min'  => '1',
					'max'  => '50',
					'step' => '1',
				),
				'default'          => '5',
				'default_on_front' => '5',
				'unitless'         => true,
				'mobile_options'   => true,
				'toggle_slug'      => 'display',
				'description'      => esc_html__( 'Move the slider or input the value to increase or decrease the speed of the ticker.', 'divi-plus' ),
			),
			// 'pause_on_hover' => array(
			// 	'label'     	  => esc_html__( 'Pause on Hover', 'divi-plus' ),
			// 	'type'            => 'yes_no_button',
			// 	'option_category' => 'basic_option',
			// 	'options'         => array(
			// 		'off' => esc_html__( 'No', 'divi-plus' ),
			// 		'on'  => esc_html__( 'Yes', 'divi-plus' ),
			// 	),
			// 	'default'         => 'on',
			// 	'toggle_slug'     => 'display',
			// 	'description'     => esc_html__( 'Here you can choose whether or push the ticker on hover.', 'divi-plus' ),
			// ),
			'wrap_custom_padding' => array(
				'label'           => esc_html__( 'Wrap Padding', 'divi-plus' ),
				'type'            => 'custom_padding',
				'option_category' => 'layout',
				'mobile_options'  => true,
				'default'         => '||||true|true',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'wrap_style',
				'description'     => esc_html__( 'Padding adds extra space to the inside of the element, increasing the distance between the edge of the element and its inner contents.', 'divi-plus' ),
			),
			'marquee_wrap_height' => array(
				'label'           => esc_html__( 'Container Box Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				"show_if"         => array(
					'layout'            => 'marquee',
					'marquee_direction' => array( 'top', 'bottom' )
				),
				'default'         => '500px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'wrap_style',
				'description'     => esc_html__( 'Move the slider, or input the value to increase or decrease the container height.', 'divi-plus' ),
			),
			'circle3d_wrap_height' => array(
				'label'           => esc_html__( 'Container Box Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '100',
					'max'  => '1000',
					'step' => '1',
				),
				"show_if"         => array(
					'layout' => '3d_circular',
				),
				'default'         => '500px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'wrap_style',
				'description'     => esc_html__( 'Move the slider, or input the value to increase or decrease the container height.', 'divi-plus' ),
			),
			'image_width' => array(
				'label'           => esc_html__( 'Image Width', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '50',
					'max'  => '700',
					'step' => '1',
				),
				'default'         => '200px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'images_style',
				'description'     => esc_html__( 'Move the slider, or input the value to increase or decrease the image width.', 'divi-plus' ),
			),
			'image_height' => array(
				'label'           => esc_html__( 'Image Height', 'divi-plus' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'range_settings'  => array(
					'min'  => '50',
					'max'  => '500',
					'step' => '1',
				),
				'default'         => '150px',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'images_style',
				'description'     => esc_html__( 'Move the slider, or input the value to increase or decrease the image height.', 'divi-plus' ),
			),
			'__ticker_images' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_ImageCardTicker', 'get_computed_ticker_images' ),
				'computed_depends_on' => array(
					'image_ids',
				)
			)
		);
	}

	public static function get_computed_ticker_images( $attrs = array(), $conditional_tags = array(), $current_page = array() ) {
		if ( self::$rendering ) {
			// We are trying to render a Blog module while a Blog module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$defaults = array(
			'image_ids' => '',
		);
		$attrs = wp_parse_args( $attrs, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $attrs, $key, $default ) );
		}

		$images = [];
		if ( ! empty( $image_ids ) ) {
			// Convert to array.
			$image_ids = explode( ',', $image_ids );
			foreach ( $image_ids as $image_id ) {
				// Get image id.
				$images[] = wp_get_attachment_image_url( $image_id, 'full' );
			}
		}

		return $images;
	}

	public function before_render() {
		// Get and set responsive values, to use in jsx file.
		$this->props['marquee_direction']          = ! empty( $this->props['marquee_direction'] ) ? $this->props['marquee_direction'] : 'left';
		$this->props['marquee_direction_tablet']   = ! empty( $this->props['marquee_direction_tablet'] ) ? $this->props['marquee_direction_tablet'] : $this->props['marquee_direction'];
		$this->props['marquee_direction_phone']    = ! empty( $this->props['marquee_direction_phone'] ) ? $this->props['marquee_direction_phone'] : $this->props['marquee_direction_tablet'];

		$this->props['direction']                  = ! empty( $this->props['direction'] ) ? $this->props['direction'] : 'left';
		$this->props['direction_tablet']           = ! empty( $this->props['direction_tablet'] ) ? $this->props['direction_tablet'] : $this->props['direction'];
		$this->props['direction_phone']            = ! empty( $this->props['direction_phone'] ) ? $this->props['direction_phone'] : $this->props['direction_tablet'];

		$this->props['ticker_speed']               = ! empty( $this->props['ticker_speed'] ) ? $this->props['ticker_speed'] : '5';
		$this->props['ticker_speed_tablet']        = ! empty( $this->props['ticker_speed_tablet'] ) ? $this->props['ticker_speed_tablet'] : $this->props['ticker_speed'];
		$this->props['ticker_speed_phone']         = ! empty( $this->props['ticker_speed_phone'] ) ? $this->props['ticker_speed_phone'] : $this->props['ticker_speed_tablet'];

		$this->props['images_gap']                 = ! empty( $this->props['images_gap'] ) ? $this->props['images_gap'] : '30';
		$this->props['images_gap_tablet']          = ! empty( $this->props['images_gap_tablet'] ) ? $this->props['images_gap_tablet'] : $this->props['images_gap'];
		$this->props['images_gap_phone']           = ! empty( $this->props['images_gap_phone'] ) ? $this->props['images_gap_phone'] : $this->props['images_gap_tablet'];

		$this->props['image_width']                = ! empty( $this->props['image_width'] ) ? $this->props['image_width'] : '200px';
		$this->props['image_width_tablet']         = ! empty( $this->props['image_width_tablet'] ) ? $this->props['image_width_tablet'] : $this->props['image_width'];
		$this->props['image_width_phone']          = ! empty( $this->props['image_width_phone'] ) ? $this->props['image_width_phone'] : $this->props['image_width_tablet'];

		$this->props['image_height']               = ! empty( $this->props['image_height'] ) ? $this->props['image_height'] : '200px';
		$this->props['image_height_tablet']        = ! empty( $this->props['image_height_tablet'] ) ? $this->props['image_height_tablet'] : $this->props['image_height'];
		$this->props['image_height_phone']         = ! empty( $this->props['image_height_phone'] ) ? $this->props['image_height_phone'] : $this->props['image_height_tablet'];

		$this->props['marquee_wrap_height']        = ! empty( $this->props['marquee_wrap_height'] ) ? $this->props['marquee_wrap_height'] : '500px';
		$this->props['marquee_wrap_height_tablet'] = ! empty( $this->props['marquee_wrap_height_tablet'] ) ? $this->props['marquee_wrap_height_tablet'] : $this->props['marquee_wrap_height'];
		$this->props['marquee_wrap_height_phone']  = ! empty( $this->props['marquee_wrap_height_phone'] ) ? $this->props['marquee_wrap_height_phone'] : $this->props['marquee_wrap_height_tablet'];
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

		// Get attrs.
		$image_ids      = sanitize_text_field( $this->props['image_ids'] ) ?? '';
		$layout         = sanitize_text_field( $this->props['layout'] ) ?? 'marquee';
		// $pause_on_hover = sanitize_text_field( $this->props['pause_on_hover'] ) ?? 'on';

		$image_width    = et_pb_responsive_options()->get_property_values( $this->props, 'image_width' );
		$image_height   = et_pb_responsive_options()->get_property_values( $this->props, 'image_height' );
		$ticker_speed   = et_pb_responsive_options()->get_property_values( $this->props, 'ticker_speed' );
		$images_gap     = et_pb_responsive_options()->get_property_values( $this->props, 'images_gap' );
		
		// Direction based on layout.
		if ( 'marquee' === $layout ) {
			$direction = et_pb_responsive_options()->get_property_values( $this->props, 'marquee_direction' );
		} else {
			$direction = et_pb_responsive_options()->get_property_values( $this->props, 'direction' );
		}

		// Responsive values.
		$image_width['desktop']  = ! empty( $image_width['desktop'] ) ? $image_width['desktop'] : '200px';
		$image_width['tablet']   = ! empty( $image_width['tablet'] ) ? $image_width['tablet'] : $image_width['desktop'];
		$image_width['phone']    = ! empty( $image_width['phone'] ) ? $image_width['phone'] : $image_width['tablet'];

		$image_height['desktop'] = ! empty( $image_height['desktop'] ) ? $image_height['desktop'] : '150px';
		$image_height['tablet']  = ! empty( $image_height['tablet'] ) ? $image_height['tablet'] : $image_height['desktop'];
		$image_height['phone']   = ! empty( $image_height['phone'] ) ? $image_height['phone'] : $image_height['tablet'];

		$ticker_speed_desktop   = ! empty( $ticker_speed['desktop'] ) ? $ticker_speed['desktop'] : '5';
		$ticker_speed_tablet    = ! empty( $ticker_speed['tablet'] ) ? $ticker_speed['tablet'] : $ticker_speed_desktop;
		$ticker_speed_mobile    = ! empty( $ticker_speed['phone'] ) ? $ticker_speed['phone'] : $ticker_speed_tablet;

		$images_gap_desktop     = ! empty( $images_gap['desktop'] ) ? $images_gap['desktop'] : '30';
		$images_gap_tablet      = ! empty( $images_gap['tablet'] ) ? $images_gap['tablet'] : $images_gap_desktop;
		$images_gap_mobile      = ! empty( $images_gap['phone'] ) ? $images_gap['phone'] : $images_gap_tablet;

		$direction_desktop      = ! empty( $direction['desktop'] ) ? $direction['desktop'] : 'left';
		$direction_tablet       = ! empty( $direction['tablet'] ) ? $direction['tablet'] : $direction_desktop;
		$direction_mobile       = ! empty( $direction['phone'] ) ? $direction['phone'] : $direction_tablet;
	
		$images = '';
		if ( ! empty( $image_ids ) ) {
			// Convert to array.
			$image_ids = explode( ',', $image_ids );
			foreach ( $image_ids as $image_id ) {
				// Get image id.
				$image = wp_get_attachment_image( $image_id, 'full' );
				if ( '3d_circular' === $layout || 'curve' === $layout ) {
					$image = sprintf(
						'<div class="dipl_image_card_ticker_image_wrapper">%1$s</div>',
						et_core_esc_previously( $image )
					);
				}

				// Add image to main images var.
				$images .= et_core_esc_previously( $image );
			}
		}

		// If images are there.
		$render_output = '';
		if ( ! empty( $images ) ) {

			// Load style.
			$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
			wp_enqueue_style( 'dipl-image-card-ticker-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ImageCardTicker/' . $file . '.min.css', array(), '1.0.0' );

			// Load scripts.
			wp_enqueue_script( 'elicus-images-loaded-script' );
			wp_enqueue_script( 'elicus-scroll-trigger-script' );
			wp_enqueue_script( 'elicus-gsap-script' );
			wp_enqueue_script( 'dipl-image-card-ticker-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/ImageCardTicker/dipl-image-card-ticker-custom.min.js', array('jquery'), '1.0.0', true );

			// Wrapper Classes.
			$classes   = [ 'dipl_image_card_ticker_wrapper' ];
			$classes[] = 'layout-' . esc_attr( $layout );
			
			// Direction class.
			if ( 'marquee' === $layout ) {
				$classes[] = 'direction-' . esc_attr( $direction_desktop );
				$classes[] = 'direction-tablet-' . esc_attr( $direction_tablet );
				$classes[] = 'direction-mobile-' . esc_attr( $direction_mobile );
			}

			/**
			 * Removed pause_on_hover because VB side not working.
			 * VB side also have issue when we change the layout.
			 * When we checked that issue, will check about this option as well.
			 */

			// Add svg for curve effect.
			$curve_svg = '';
			if ( 'curve' === $layout ) {
				$curve_svg = '<svg width="0" height="0">
					<defs>
						<mask id="dipl_image_card_ticker_curve_mask" x="0" y="0" width="1" height="1" maskContentUnits="objectBoundingBox">
						<rect x="0" y="0" width="1" height="1" fill="black"></rect>
						<path d="M0,0 Q0.5,0.25 1,0 V1 Q0.5,0.75 0,1 Z" fill="white"></path>
						</mask>
					</defs>
				</svg>';
			}

			// Final output.
			$render_output .= $multi_view->render_element( array(
				'tag'     => 'div',
				'attrs'   => array(
					'class'                    => implode( ' ', $classes ), // Wrapper.
					'data-layout'              => esc_attr( $layout ),
					'data-direction'           => esc_attr( $direction_desktop ),
					'data-direction_tablet'    => esc_attr( $direction_tablet ),
					'data-direction_mobile'    => esc_attr( $direction_mobile ),
					'data-image_gap'           => intval( $images_gap_desktop ),
					'data-image_gap_tablet'    => intval( $images_gap_tablet ),
					'data-image_gap_mobile'    => intval( $images_gap_mobile ),
					'data-ticker_speed'        => intval( $ticker_speed_desktop ),
					'data-ticker_speed_tablet' => intval( $ticker_speed_mobile ),
					'data-ticker_speed_mobile' => intval( $ticker_speed_tablet ),
					// 'data-pause_on_hover'   => esc_attr( $pause_on_hover ),
					'data-image_width'         => intval( $image_width['desktop'] ),
					'data-image_width_tablet'  => intval( $image_width['tablet'] ),
					'data-image_width_mobile'  => intval( $image_width['phone'] ),
					'data-image_height'        => intval( $image_height['desktop'] ),
					'data-image_height_tablet' => intval( $image_height['tablet'] ),
					'data-image_height_mobile' => intval( $image_height['phone'] ),
				),
				'content' => sprintf(
					'<div class="dipl_image_card_ticker_inner">%1$s</div>%2$s',
					et_core_esc_previously( $images ),
					et_core_esc_previously( $curve_svg )
				)
			) );

			// layout vise style.
			if ( 'marquee' === $layout ) {
				$marquee_wrap_height = et_pb_responsive_options()->get_property_values( $this->props, 'marquee_wrap_height' );
				foreach( $marquee_wrap_height as $device => $value ) {
					if ( 'desktop' === $device ) {
						if ( 'top' === $direction_desktop || 'bottom' === $direction_desktop ) {
							self::set_style( $render_slug, array(
								'selector'    => '%%order_class%% .dipl_image_card_ticker_inner',
								'declaration' => sprintf( 'height: %1$s important;', esc_attr( $value ) ),
								'media_query' => self::get_media_query( 'min_width_981' ),
							) );
						}
					} elseif ( 'tablet' === $device ) {
						if ( 'top' === $direction_tablet || 'bottom' === $direction_tablet ) {
							self::set_style( $render_slug, array(
								'selector'    => '%%order_class%% .dipl_image_card_ticker_inner',
								'declaration' => sprintf( 'height: %1$s important;', esc_attr( $value ) ),
								'media_query' => self::get_media_query( '768_980' ),
							) );
						}
					} elseif ( 'phone' === $device ) {
						if ( 'top' === $direction_mobile || 'bottom' === $direction_mobile ) {
							self::set_style( $render_slug, array(
								'selector'    => '%%order_class%% .dipl_image_card_ticker_inner',
								'declaration' => sprintf( 'height: %1$s important;', esc_attr( $value ) ),
								'media_query' => self::get_media_query( 'max_width_767' ),
							) );
						}
					}
				}
			} elseif ( '3d_circular' === $layout ) {
				$circle3d_wrap_height = et_pb_responsive_options()->get_property_values( $this->props, 'circle3d_wrap_height' );
				if ( ! empty( array_filter( $circle3d_wrap_height ) ) ) {
					et_pb_responsive_options()->generate_responsive_css( $circle3d_wrap_height, '%%order_class%% .dipl_image_card_ticker_inner', 'height', $render_slug, '!important;', 'range' );
				}
			}

			// Set image width and height.
			if ( ! empty( array_filter( $image_width ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $image_width, '%%order_class%% .dipl_image_card_ticker_wrapper .dipl_image_card_ticker_inner img', 'width', $render_slug, '', 'range' );
			}
			if ( ! empty( array_filter( $image_height ) ) ) {
				et_pb_responsive_options()->generate_responsive_css( $image_height, '%%order_class%% .dipl_image_card_ticker_wrapper .dipl_image_card_ticker_inner img', 'height', $render_slug, '', 'range' );
			}

			$fields = array( 'image_card_ticker_spacing' );
			DiviPlusHelper::process_advanced_margin_padding_css( $this, $render_slug, $this->margin_padding, $fields );
		}

		self::$rendering = false;
		return $render_output;
	}
}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_image_card_ticker', $modules ) ) {
		new DIPL_ImageCardTicker();
	}
} else {
	new DIPL_ImageCardTicker();
}
