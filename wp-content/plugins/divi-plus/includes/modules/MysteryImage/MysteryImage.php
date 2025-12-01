<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2023 Elicus Technologies Private Limited
 * @version     1.1.1
 */
class DIPL_MysteryImage extends ET_Builder_Module {
	public $slug       = 'dipl_mystery_image';
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
		$this->name             = esc_html__( 'DP Mystery Image', 'divi-plus' );
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Content', 'divi-plus' ),
					'link'         => esc_html__( 'Link', 'divi-plus' ),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'overlay'  => esc_html__( 'Overlay', 'divi-plus' ),
					'lightbox' => esc_html__( 'Lightbox', 'divi-plus' ),
					'width'    => array(
						'title'    => et_builder_i18n( 'Sizing' ),
						'priority' => 65,
					),
				)
			)
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => '%%order_class%% .dipl_mystery_image_wrap',
							'border_styles' => '%%order_class%% .dipl_mystery_image_wrap',
						),
					),
				),
			),
			'box_shadow'     => array(
				'default' => array(
					'css' => array(
						'main'    => '%%order_class%% .dipl_mystery_image_wrap',
						'overlay' => 'inset',
					),
				),
			),
			'max_width'      => array(
				'options' => array(
					'width'     => array(
						'depends_show_if' => 'off',
					),
					'max_width' => array(
						'depends_show_if' => 'off',
					),
				),
			),
			'height'         => array(
				'css' => array(
					'main' => '%%order_class%% .dipl_mystery_image_wrap img',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'important' => array( 'custom_margin' ),
				),
			),
			'background' => array(
				'use_background_video' => false,
				'options' => array(
					'parallax' => array( 'type' => 'skip' ),
				),
			),
			'fonts'          => false,
			'text'           => false,
			'button'         => false,
			'link_options'   => false,
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
					'__mystery_image_data',
				),
			),
			'show_in_lightbox' => array(
				'label'            => esc_html__( 'Open in Lightbox', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => et_builder_i18n( 'No' ),
					'on'  => et_builder_i18n( 'Yes' ),
				),
				'default_on_front' => 'off',
				'affects'          => array(
					'url', 'url_new_window', 'lightbox_effect',
					'lightbox_background_color', 'lightbox_close_icon_color'
				),
				'toggle_slug'      => 'link',
				'description'      => esc_html__( 'Here you can choose whether or not the image should open in Lightbox. Note: if you select to open the image in Lightbox, url options below will be ignored.', 'divi-plus' ),
			),
			'lightbox_effect' => array(
				'label'            => esc_html__( 'Lighbox Effect', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'none' => esc_html__( 'None', 'divi-plus' ),
					'zoom' => esc_html__( 'Zoom', 'divi-plus' ),
					'fade' => esc_html__( 'Fade', 'divi-plus' ),
				),
				'depends_show_if'  => 'on',
				'default_on_front' => 'none',
				'toggle_slug'      => 'link',
				'description'      => esc_html__( 'Here you can choose opening effect of lightbox.', 'divi-plus' ),
			),
			'lightbox_transition_duration' => array(
				'label'             => esc_html__( 'Transition Duration', 'divi-plus' ),
				'type'              => 'range',
				'option_category'   => 'layout',
				'range_settings'	=> array(
					'min'  => '100',
					'max'  => '2000',
					'step' => '100',
				),
				'show_if'          	=> array(
					'show_in_lightbox' => 'on',
					'lightbox_effect' => array( 'zoom', 'fade' ),
				),
				'unitless'          => true,
				'default_on_front'  => '300',
				'toggle_slug'       => 'link',
				'description'       => esc_html__( 'Here you can select the transition duration in miliseconds.', 'divi-plus' ),
			),
			'url' => array(
				'label'           => esc_html__( 'Image Link URL', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'off',
				'description'     => esc_html__( 'If you would like your image to be a link, input your destination URL here. No link will be created if this field is left blank.', 'divi-plus' ),
				'toggle_slug'     => 'link',
				'dynamic_content' => 'url',
			),
			'url_new_window' => array(
				'label'            => esc_html__( 'Image Link Target', 'divi-plus' ),
				'type'             => 'select',
				'option_category'  => 'configuration',
				'options'          => array(
					'off' => esc_html__( 'In The Same Window', 'divi-plus' ),
					'on'  => esc_html__( 'In The New Tab', 'divi-plus' ),
				),
				'default_on_front' => 'off',
				'depends_show_if'  => 'off',
				'toggle_slug'      => 'link',
				'description'      => esc_html__( 'Here you can choose whether or not your link opens in a new window', 'divi-plus' ),
			),
			'use_overlay' => array(
				'label'            => esc_html__( 'Enable Overlay', 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => et_builder_i18n( 'Off' ),
					'on'  => et_builder_i18n( 'On' ),
				),
				'default_on_front' => 'off',
				'affects'          => array(
					'overlay_bg_color',
					'overlay_icon_size',
					'overlay_icon_color',
					'overlay_icon',
				),
				'show_if'          => array(
					'function.showImageUseOverlayField' => 'on',
				),
				'description'      => esc_html__( 'If enabled, an overlay color and icon will be displayed when a visitors hovers over the image', 'divi-plus' ),
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'overlay',
			),
			'overlay_bg_color' => array(
				'label'           => esc_html__( 'Overlay Background Color', 'divi-plus' ),
				'type'            => 'color-alpha',
				'custom_color'    => true,
				'depends_show_if' => 'on',
				'description'     => esc_html__( 'Here you can define a custom color for the overlay', 'divi-plus' ),
				'mobile_options'  => true,
				'sticky'          => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'overlay',
			),
			'overlay_icon_size' => array(
				'label'            => esc_html__( 'Overlay Icon Size', 'divi-plus' ),
				'type'             => 'range',
				'option_category'  => 'layout',
				'range_settings'   => array(
					'min'   => '0',
					'max'   => '120',
					'step'  => '1',
				),
				'fixed_unit'       => 'px',
				'fixed_range'      => true,
				'validate_unit'    => true,
				'mobile_options'   => true,
				'default'          => '32px',
				'default_on_front' => '32px',
				'depends_show_if'  => 'on',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'overlay',
				'description'      => esc_html__( 'Increase or decrease icon font size.', 'divi-plus' ),
			),
			'overlay_icon_color'  => array(
				'label'           => esc_html__( 'Overlay Icon Color', 'divi-plus' ),
				'type'            => 'color-alpha',
				'custom_color'    => true,
				'depends_show_if' => 'on',
				'description'     => esc_html__( 'Here you can define a custom color for the overlay icon', 'divi-plus' ),
				'mobile_options'  => true,
				'sticky'          => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'overlay',
			),
			'overlay_icon' => array(
				'label'           => esc_html__( 'Overlay Icon', 'divi-plus' ),
				'type'            => 'select_icon',
				'option_category' => 'configuration',
				'class'           => array( 'et-pb-font-icon' ),
				'depends_show_if' => 'on',
				'description'     => esc_html__( 'Here you can define a custom icon for the overlay', 'divi-plus' ),
				'mobile_options'  => true,
				'sticky'          => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'overlay',
			),
			'force_fullwidth' => array(
				'label'            => esc_html__( 'Force Fullwidth', 'divi-plus' ),
				'description'      => esc_html__( "When enabled, this will force your image to extend 100% of the width of the column it's in.", 'divi-plus' ),
				'type'             => 'yes_no_button',
				'option_category'  => 'layout',
				'options'          => array(
					'off' => et_builder_i18n( 'No' ),
					'on'  => et_builder_i18n( 'Yes' ),
				),
				'default_on_front' => 'off',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'width',
				'affects'          => array(
					'max_width',
					'width',
				),
			),
			'lightbox_background_color' => array(
				'label'            => esc_html__( 'Lightbox Background Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'default'		   => 'rgba(0,0,0,0.8)',
				'default_on_front' => 'rgba(0,0,0,0.8)',
				'depends_show_if'  => 'on',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'lightbox',
				'description'      => esc_html__( 'Here you can define a custom background color for the lightbox.', 'divi-plus' ),
			),
			'lightbox_close_icon_color' => array(
				'label'            => esc_html__( 'Close Icon Color', 'divi-plus' ),
				'type'             => 'color-alpha',
				'custom_color'     => true,
				'default'		   => '#fff',
				'default_on_front' => '#fff',
				'depends_show_if'  => 'on',
				'tab_slug'         => 'advanced',
				'toggle_slug'      => 'lightbox',
				'description'      => esc_html__( 'Here you can define a custom color for the close icon.', 'divi-plus' ),
			),
			'__mystery_image_data' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DIPL_MysteryImage', 'get_computed_mystery_image_data' ),
				'computed_depends_on' => array(
					'image_ids',
				)
			)
		);
	}

	public static function get_computed_mystery_image_data( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'image_ids' => '',
		);

		$args = wp_parse_args( $args, $defaults );
		foreach ( $defaults as $key => $default ) {
			${$key} = esc_html( et_()->array_get( $args, $key, $default ) );
		}

		$image_ids_arr   = explode( ',', $image_ids );
		$random_image_id = array_rand( array_flip( $image_ids_arr ) );

		return wp_get_attachment_image_url( $random_image_id, 'orignal' );
	}

	public function render( $attrs, $content, $render_slug ) {
		if ( self::$rendering ) {
			// We are trying to render a DIPL Woo Product module while a DIPL Woo Product module is already being rendered
			// which means we have most probably hit an infinite recursion. While not necessarily
			// the case, rendering a post which renders a Blog module which renders a post
			// which renders a Blog module is not a sensible use-case.
			return '';
		}

		$sticky            = et_pb_sticky_options();
		$multi_view        = et_pb_multi_view_options( $this );

		$file = et_is_builder_plugin_active() ? 'style-dbp' : 'style';
        wp_enqueue_style( 'dipl-mystery-image-style', ELICUS_DIVI_PLUS_PLUGIN_URL . 'includes/modules/MysteryImage/' . $file . '.min.css', array(), '1.0.0' );

		$use_overlay       = $this->props['use_overlay'];
		$show_in_lightbox  = $this->props['show_in_lightbox'];
		$url               = $this->props['url'];
		$url_new_window    = $this->props['url_new_window'];
		$force_fullwidth   = $this->props['force_fullwidth'];

		// Get image ids.
		$image_ids       = $this->props['image_ids'];
		$image_ids_arr   = explode( ',', $image_ids );
		$random_image_id = array_rand( array_flip( $image_ids_arr ) );

		// overlay can be applied only if image has link or if lightbox enabled
		$is_overlay_applied = 'on' === $use_overlay && ( 'on' === $show_in_lightbox || ( 'off' === $show_in_lightbox && '' !== $url ) ) ? 'on' : 'off';
		$overlay_html       = '';
		if ( 'on' === $is_overlay_applied ) {

			// Get overlay icon values.
			$overlay_icon        = $this->props['overlay_icon'];
			$overlay_icon_tablet = $this->props['overlay_icon_tablet'];
			$overlay_icon_phone  = $this->props['overlay_icon_phone'];
			$overlay_icon_sticky = $sticky->get_value( 'overlay_icon', $this->props );

			$overlay_html = ET_Builder_Module_Helper_Overlay::render( array(
				'icon'        => $overlay_icon,
				'icon_tablet' => $overlay_icon_tablet,
				'icon_phone'  => $overlay_icon_phone,
				'icon_sticky' => $overlay_icon_sticky,
			) );
		}

		// Render image.
		$image_html = wp_get_attachment_image( $random_image_id, 'orignal', false, array(
			'class' => sprintf( 'dipl-mystery-img wp-image-%1$s', $random_image_id )
		) );

		$output = sprintf(
			'<span class="dipl_mystery_image_wrap">%1$s%2$s</span>',
			$image_html,
			$overlay_html
		);

		if ( 'on' === $show_in_lightbox ) {

			wp_enqueue_script( 'magnific-popup' );
			wp_enqueue_style( 'magnific-popup' );
			wp_enqueue_script( 'dipl-mystery-image-custom', ELICUS_DIVI_PLUS_PLUGIN_URL . "includes/modules/MysteryImage/dipl-mystery-image-custom.min.js", array('jquery'), '1.0.0', true );

			$data_props = array( 'lightbox_effect', 'lightbox_transition_duration' );
			$data_atts  = $this->props_to_html_data_attrs( $data_props );

			$output = sprintf(
				'<a href="%1$s" class="dipl-mystery-image-lightbox" %3$s>%2$s</a>',
				wp_get_attachment_image_url( $random_image_id, 'orignal' ),
				$output,
				$data_atts
			);
		} elseif ( '' !== $url ) {
			$output = sprintf(
				'<a href="%1$s" class="dipl-mystery-image-link"%3$s>%2$s</a>',
				esc_url( $url ),
				$output,
				( 'on' === $url_new_window ? ' target="_blank"' : '' )
			);
		}

		// overlay styles.
		if ( 'on' === $is_overlay_applied ) {
			$this->generate_styles( array(
				'hover'          => false,
				'base_attr_name' => 'overlay_bg_color',
				'selector'       => '%%order_class%% .et_overlay',
				'css_property'   => 'background-color',
				'render_slug'    => $render_slug,
				'type'           => 'color',
			) );
			$this->generate_styles( array(
				'hover'          => false,
				'base_attr_name' => 'overlay_icon_color',
				'selector'       => '%%order_class%% .et_overlay:before',
				'css_property'   => 'color',
				'render_slug'    => $render_slug,
				'important'      => true,
				'type'           => 'color',
			) );

			// Overlay Icon Styles.
			$this->generate_styles( array(
				'hover'          => false,
				'utility_arg'    => 'icon_font_family',
				'render_slug'    => $render_slug,
				'base_attr_name' => 'overlay_icon',
				'important'      => true,
				'selector'       => '%%order_class%% .et_overlay:before',
				'processor'      => array(
					'ET_Builder_Module_Helper_Style_Processor',
					'process_extended_icon',
				),
			) );

			$overlay_icon_size = et_pb_responsive_options()->get_property_values( $this->props, 'overlay_icon_size' );
			et_pb_responsive_options()->generate_responsive_css( $overlay_icon_size, '%%order_class%% .et_overlay:before', 'font-size', $render_slug, '', 'range' );
		}

		if ( 'on' === $show_in_lightbox ) {
			if ( ! empty( $this->props['lightbox_background_color'] ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%%_lightbox.mfp-bg',
					'declaration' => sprintf( 'background-color: %1$s;', esc_attr( $this->props['lightbox_background_color'] ) )
				) );
			}
			if ( ! empty( $this->props['lightbox_close_icon_color'] ) ) {
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%%_lightbox .mfp-close',
					'declaration' => sprintf( 'color: %1$s;', esc_attr( $this->props['lightbox_close_icon_color'] ) )
				) );
			}
			if ( 'none' !== $this->props['lightbox_effect'] ) {
				$lightbox_transition_duration = $this->props['lightbox_transition_duration'];
				self::set_style( $render_slug, array(
					'selector'    => '%%order_class%%_lightbox .mfp-container, %%order_class%%_lightbox.mfp-bg, %%order_class%%_lightbox.mfp-wrap .mfp-content',
					'declaration' => sprintf( 'transition-duration: %1$sms;', absint( $lightbox_transition_duration ) )
				) );
			}
		}

		if ( 'on' === $force_fullwidth ) {
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%%',
				'declaration' => 'width: 100%; max-width: 100% !important;',
			) );
			self::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dipl_mystery_image_wrap, %%order_class%% img',
				'declaration' => 'width: 100%;',
			) );
		}

		self::$rendering = false;
		return $output;
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_mystery_image', $modules ) ) {
		new DIPL_MysteryImage();
	}
} else {
	new DIPL_MysteryImage();
}
