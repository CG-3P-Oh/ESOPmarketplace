<?php
/**
 * @author      Elicus <hello@elicus.com>
 * @link        https://www.elicus.com/
 * @copyright   2024 Elicus Technologies Private Limited
 * @version     1.10.0
 */
class DIPL_ComparisonListItem extends ET_Builder_Module {

	public $slug       = 'dipl_comparison_list_item';
	public $type       = 'child';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://diviextended.com/product/divi-plus/',
		'author'     => 'Elicus',
		'author_uri' => 'https://elicus.com/',
	);

	public function init() {
		$this->name                        = esc_html__( 'DP Comparison List Item', 'divi-plus' );
		$this->advanced_setting_title_text = esc_html__( 'Comparison List Item', 'divi-plus' );
		$this->child_title_var             = 'item_title';
		$this->main_css_element            = '.dipl_comparison_list .dipl_comparison_list_wrapper %%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'main_content' => array(
						'title'    => esc_html__( 'Content', 'divi-plus' ),
						'priority' => 1,
						'tab'      => 'active',
					),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'list_item' => array(
						'title' => esc_html__( 'List Item', 'divi-plus' )
					),
					'list_item_title' => array(
						'title' => esc_html__( 'List Item Title', 'divi-plus' )
					),
				),
			),
		);
	}

	public function get_advanced_fields_config() {
		return array(
			'fonts'          => array(
				'list_item' => array(
					'label'           => esc_html__( 'List Item Text', 'divi-plus' ),
					'font_size'       => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
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
						'main'      => "{$this->main_css_element} .dipl_comparison_list_text",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'list_item',			
				),
				'list_item_title' => array(
					'label'           => esc_html__( 'List Item Title Text', 'divi-plus' ),
					'font_size'       => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
						'validate_unit'  => true,
					),
					'line_height'     => array(
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
						'main'      => "{$this->main_css_element} .dipl_comparison_list_item_title",
						'important' => 'all',
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'list_item_title',			
				)
			),
			'borders' => array(
				'default' => array(
					'css' => array(
						'main' => array(
							'border_styles' => '.dipl_comparison_list .dipl_comparison_list_wrapper %%order_class%% .dipl_comparison_list_content_row',
							'border_radii'  => '.dipl_comparison_list .dipl_comparison_list_wrapper %%order_class%% .dipl_comparison_list_content_row',
						),
						'important' => 'all',
					),
					'defaults' => array(
	                    'border_styles' => array(
	                        'width' => '1px',
	                        'style' => 'solid',
	                        'color' => '#eef2f8',
	                    ),
	                ),
				),
			),
			'margin_padding' => array(
				'css'     => array(
					'main'      => '.dipl_comparison_list .dipl_comparison_list_wrapper %%order_class%% .dipl_comparison_list_content_row',
					'important' => 'all',
				),
				'default' => array(
					'css' => array(
						'main'      => '.dipl_comparison_list .dipl_comparison_list_wrapper %%order_class%% .dipl_comparison_list_content_row',
						'important' => 'all',
					),
				),
			),
			'background' => array(
				'use_background_video' => false,
				'css' => array(
					'main' => '.dipl_comparison_list .dipl_comparison_list_wrapper %%order_class%% .dipl_comparison_list_content_row',
					'important' => 'all',
				),
			),
			'text'           => false,
			'module'         => false,
			'text_shadow'    => false,
			'max_width' 	 => false,
		);
	}

	public function get_fields() {
		$et_accent_color = et_builder_accent_color();
		return array(
			'item_title'             => array(
				'label'           => esc_html__( 'Title', 'divi-plus' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can input the title for the list item.', 'divi-plus' ),
			),
			'item_description'    => array(
				'label'           => esc_html__( 'Description', 'divi-plus' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'dynamic_content' => 'text',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'main_content',
				'description'     => esc_html__( 'Here you can enter description for the list item.', 'divi-plus' ),
			),
			'comparison_item_value' => array(
				'label'           	=> esc_html__( 'Comparison Item Value', 'divi-plus' ),
				'type'            	=> 'textarea',
				'option_category' 	=> 'basic_option',
				'tab_slug'        	=> 'general',
				'toggle_slug'     	=> 'main_content',
			),
			'comparison_item_value_notice' => array(
				'label'           => '',
				'type'            => 'warning',
				'option_category' => 'basic_option',
				'value'           => true,
				'display_if'      => true,
				'message'         => esc_html__( 'You can use <strong>{1}</strong> for a ✅ tick icon, <strong>{0}</strong> for ❌ cross icon, or text for special values like Included, Full, Standard, etc. <strong>Tip:</strong> Use the “|” symbol to separate each ability.', 'divi-plus' ),
				'toggle_slug'      => 'main_content',
			),
		);
	}

	public function render( $attrs, $content, $render_slug ) {

		$props = $this->props;

		$item_title        = $this->props['item_title'];
		$item_description  = $this->props['item_description'];
		$comparison_values = $this->props['comparison_item_value'];

		$values = explode( '|', $comparison_values );

		ob_start();
		?>
		<div class="dipl_comparison_list_content_row">
			<div class="dipl_comparison_list_column dipl_comparison_list_item_title">
				<span class="et-pb-icon et-pb-normal-icon dipl_toggle_icon">L</span>
				<?php echo esc_html( $item_title ); ?>
			</div>

			<?php if ( ! empty( $values ) ) : ?>
				<?php foreach ( $values as $value ) :
					$value = trim( $value );
					$value = str_replace(
			            array('{0}', '{1}'),
			            array(
			                '<span class="et-pb-icon et-pb-fa-icon et-pb-black-icon dipl_comparison_list_icon_close"></span>',
			                '<span class="et-pb-icon et-pb-fa-icon et-pb-black-icon dipl_comparison_list_icon_check"></span>'
			            ),
			            $value
			        );
					?>
					<div class="dipl_comparison_list_column">
						<?php
						echo '<span class="dipl_comparison_list_text">' . et_core_intentionally_unescaped( $value, 'html' ) . '</span>';
						?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $item_description ) ) : ?>
			<div class="dipl_comparison_list_description">
				<?php echo et_core_intentionally_unescaped( $item_description, 'html' ); ?>
			</div>
		<?php endif; ?>

		<?php
		$dipl_comparison_list_item_wrap = ob_get_clean();

		return et_core_intentionally_unescaped( $dipl_comparison_list_item_wrap, 'html' );
	}

}

$plugin_options = get_option( ELICUS_DIVI_PLUS_OPTION );
if ( isset( $plugin_options['dipl-modules'] ) ) {
	$modules = explode( ',', $plugin_options['dipl-modules'] );
	if ( in_array( 'dipl_list', $modules ) ) {
		new DIPL_ComparisonListItem();
	}
} else {
	new DIPL_ComparisonListItem();
}