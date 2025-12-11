<?php
/**
 * Plugin Name: ESOP Advisor System
 * Description: Complete ESOP Advisor directory with MapBox map integration. Self-contained MU plugin with no third-party dependencies.
 * Version: 1.11.1
 * Author: ESOP Marketplace / 3PRIME
 * Text Domain: esop-advisor
 *
 * INSTALLATION:
 * 1. Place this file in /wp-content/mu-plugins/
 * 2. Add MapBox access token to wp-config.php:
 *    define( 'MAPBOX_ACCESS_TOKEN', 'pk.your_token_here' );
 * 3. Go to Settings > Permalinks and click Save
 *
 * SHORTCODES:
 * [esop_advisor_map height="500px" zoom="4" style="streets-v12"]
 * [esop_advisor_location_map] - Single advisor location map
 *   Attributes: post_id, height, zoom, style, marker_color, interactive, show_popup
 * [esop_advisor_case_studies posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_articles posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_ratings posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_blog posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_testimonial_category format="slug|id|name"] - Get advisor's testimonial category
 *
 * Field Shortcodes (for Divi templates and Link fields):
 * [esop_advisor_field field="FIELD_NAME"] - Universal field shortcode
 * [esop_name], [esop_company], [esop_title], [esop_address], [esop_city],
 * [esop_state], [esop_zip], [esop_phone], [esop_cell], [esop_fax],
 * [esop_email], [esop_website], [esop_about_url], [esop_services_url],
 * [esop_linkedin], [esop_bio], [esop_education], [esop_videos],
 * [esop_location], [esop_full_address]
 *
 * Row Shortcodes (conditional display with labels/icons - hide when empty):
 * [esop_company_row], [esop_title_row], [esop_email_row], [esop_phone_row],
 * [esop_cell_row], [esop_fax_row], [esop_website_row], [esop_about_row],
 * [esop_services_row], [esop_address_block], [esop_contact_button]
 *
 * Raw Email Shortcodes (for Divi button URLs - mailto:[esop_email_raw]):
 * [esop_email_raw], [esop_email_only]
 *
 * Field shortcode attributes: post_id, link, text, target, before, after, empty
 * Row shortcode attributes: post_id (URL rows also support: text)
 * URL shortcodes ([esop_website], [esop_linkedin], etc.) return raw URLs for Divi Link fields
 *
 * NOTE: Post shortcodes only display if advisor has a linked user AND posts exist.
 * If no posts exist for a category, nothing is displayed (no empty sections).
 *
 * DIVI INTEGRATION:
 * - URL shortcodes ([esop_website], etc.) work in Divi module Link URL fields
 * - Divi Blog modules on advisor pages auto-filter to show only that advisor's posts
 *
 * DIVI CONDITION META FIELDS (for "Display Only If" conditions):
 * These meta fields are auto-computed when an advisor is saved:
 * - esop_advisor_articles: '1' if advisor has articles, absent if not
 * - esop_advisor_ratings: '1' if advisor has ratings/reviews, absent if not
 * - esop_advisor_blog: '1' if advisor has blog posts, absent if not
 * - esop_advisor_case_studies: '1' if advisor has case studies, absent if not
 * - esop_bio: '1' if advisor has bio content, absent if not
 * - esop_advisor_videos: '1' if advisor has videos, absent if not
 * Use these exact field names in Divi's "Manual Custom Field Name" with "Is Any Value"
 * To refresh all advisors: Tools > ESOP Advisor Tools > Refresh Condition Meta
 * - Body class 'esop-advisor-page' added on advisor single pages
 * - Body class 'esop-advisor-user-{ID}' added when advisor has linked user
 *
 * META FIELDS:
 * - company, title, address, city, state, zip, phone, cell, fax, email
 * - website, about_url, services_url, linkedin
 * - bio (rich text), education (rich text), videos (rich text)
 * - latitude, longitude, user_id
 *
 * HELPER FUNCTIONS:
 * esop_get_advisor_field( $post_id, $field ) - Get advisor field value
 * esop_the_advisor_field( $post_id, $field ) - Echo advisor field with escaping
 * esop_get_advisor_user_id( $advisor_id ) - Get linked WordPress user ID
 * esop_get_advisor_user( $advisor_id ) - Get linked WordPress user object
 * esop_advisor_has_posts_in_category( $advisor_id, $category ) - Check if advisor has posts
 * esop_get_advisor_testimonial_category_id( $advisor_id ) - Get DP Testimonial category ID
 * esop_advisor_has_testimonials( $advisor_id ) - Check if advisor has testimonials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ============================================================================
// DEBUG LOGGING FOR SHORTCODE TROUBLESHOOTING
// ============================================================================

// Enable debug logging: add define('ESOP_ADVISOR_DEBUG', true); to wp-config.php
if ( ! defined( 'ESOP_ADVISOR_DEBUG' ) ) {
	define( 'ESOP_ADVISOR_DEBUG', false );
}

/**
 * Debug logging helper for ESOP Advisor shortcodes
 *
 * @param string $message Log message
 * @param mixed  $data    Optional data to log
 */
function esop_advisor_debug_log( $message, $data = null ) {
	if ( ! ESOP_ADVISOR_DEBUG ) {
		return;
	}

	$log_entry = '[ESOP Advisor Debug] ' . $message;
	if ( $data !== null ) {
		$log_entry .= ' | Data: ' . print_r( $data, true );
	}
	error_log( $log_entry );
}

// ============================================================================
// CUSTOM POST TYPE
// ============================================================================

add_action( 'init', 'esop_advisor_register_post_type' );

function esop_advisor_register_post_type() {
	$labels = array(
		'name'                  => _x( 'ESOP Advisors', 'Post type general name', 'esop-advisor' ),
		'singular_name'         => _x( 'ESOP Advisor', 'Post type singular name', 'esop-advisor' ),
		'menu_name'             => _x( 'ESOP Advisors', 'Admin Menu text', 'esop-advisor' ),
		'add_new'               => __( 'Add New', 'esop-advisor' ),
		'add_new_item'          => __( 'Add New Advisor', 'esop-advisor' ),
		'edit_item'             => __( 'Edit Advisor', 'esop-advisor' ),
		'view_item'             => __( 'View Advisor', 'esop-advisor' ),
		'all_items'             => __( 'All Advisors', 'esop-advisor' ),
		'search_items'          => __( 'Search Advisors', 'esop-advisor' ),
		'not_found'             => __( 'No advisors found.', 'esop-advisor' ),
		'not_found_in_trash'    => __( 'No advisors found in Trash.', 'esop-advisor' ),
		'featured_image'        => _x( 'Advisor Photo', 'Overrides the "Featured Image"', 'esop-advisor' ),
		'set_featured_image'    => _x( 'Set advisor photo', 'Overrides the "Set featured image"', 'esop-advisor' ),
		'remove_featured_image' => _x( 'Remove advisor photo', 'Overrides the "Remove featured image"', 'esop-advisor' ),
		'use_featured_image'    => _x( 'Use as advisor photo', 'Overrides the "Use as featured image"', 'esop-advisor' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'advisor' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-businessperson',
		'show_in_rest'       => true,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
	);

	register_post_type( 'esop_advisor', $args );
}

// ============================================================================
// META BOXES
// ============================================================================

add_action( 'add_meta_boxes', 'esop_advisor_add_meta_boxes' );

function esop_advisor_add_meta_boxes() {
	add_meta_box( 'esop_advisor_geocoder', __( 'Address Geocoder', 'esop-advisor' ), 'esop_advisor_geocoder_meta_box', 'esop_advisor', 'normal', 'high' );
	add_meta_box( 'esop_advisor_details', __( 'Advisor Details', 'esop-advisor' ), 'esop_advisor_details_meta_box', 'esop_advisor', 'normal', 'default' );
	add_meta_box( 'esop_advisor_education', __( 'Education & Expertise', 'esop-advisor' ), 'esop_advisor_education_meta_box', 'esop_advisor', 'normal', 'default' );
	add_meta_box( 'esop_advisor_videos', __( 'Videos', 'esop-advisor' ), 'esop_advisor_videos_meta_box', 'esop_advisor', 'normal', 'default' );
	add_meta_box( 'esop_advisor_coordinates', __( 'Coordinates', 'esop-advisor' ), 'esop_advisor_coordinates_meta_box', 'esop_advisor', 'side', 'default' );
	add_meta_box( 'esop_advisor_user_link', __( 'Linked WordPress User', 'esop-advisor' ), 'esop_advisor_user_link_meta_box', 'esop_advisor', 'side', 'default' );

	// Add meta box to Posts for advisor association
	add_meta_box( 'esop_post_advisor_link', __( 'Associated Advisor', 'esop-advisor' ), 'esop_post_advisor_link_meta_box', 'post', 'side', 'default' );
}

/**
 * Meta box for associating a Post with an Advisor
 */
function esop_post_advisor_link_meta_box( $post ) {
	wp_nonce_field( 'esop_post_advisor_link_nonce_action', 'esop_post_advisor_link_nonce' );

	$selected_advisor = get_post_meta( $post->ID, '_esop_associated_advisor', true );

	// Get all advisors
	$advisors = get_posts( array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	?>
	<p>
		<label for="esop_associated_advisor"><strong><?php esc_html_e( 'Link to Advisor:', 'esop-advisor' ); ?></strong></label>
	</p>
	<select name="esop_associated_advisor" id="esop_associated_advisor" style="width:100%;">
		<option value=""><?php esc_html_e( '— No Advisor —', 'esop-advisor' ); ?></option>
		<?php foreach ( $advisors as $advisor ) : ?>
			<option value="<?php echo esc_attr( $advisor->ID ); ?>" <?php selected( $selected_advisor, $advisor->ID ); ?>>
				<?php echo esc_html( $advisor->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description">
		<?php esc_html_e( 'Associate this post with an advisor. Posts will appear on the advisor\'s profile page.', 'esop-advisor' ); ?>
	</p>
	<?php
}

/**
 * Save post advisor association
 */
add_action( 'save_post', 'esop_save_post_advisor_link', 10, 2 );

function esop_save_post_advisor_link( $post_id, $post ) {
	// Check post type
	if ( $post->post_type !== 'post' ) {
		return;
	}

	// Verify nonce
	if ( ! isset( $_POST['esop_post_advisor_link_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['esop_post_advisor_link_nonce'], 'esop_post_advisor_link_nonce_action' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save the association
	if ( isset( $_POST['esop_associated_advisor'] ) ) {
		$advisor_id = intval( $_POST['esop_associated_advisor'] );
		if ( $advisor_id > 0 ) {
			update_post_meta( $post_id, '_esop_associated_advisor', $advisor_id );
		} else {
			delete_post_meta( $post_id, '_esop_associated_advisor' );
		}
	}
}

function esop_advisor_geocoder_meta_box( $post ) {
	wp_nonce_field( 'esop_advisor_save_meta', 'esop_advisor_meta_nonce' );

	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		echo '<div class="notice notice-error inline"><p>' . esc_html__( 'MapBox access token not configured. Add MAPBOX_ACCESS_TOKEN to wp-config.php', 'esop-advisor' ) . '</p></div>';
		return;
	}

	$address = get_post_meta( $post->ID, '_esop_advisor_address', true );
	$city    = get_post_meta( $post->ID, '_esop_advisor_city', true );
	$state   = get_post_meta( $post->ID, '_esop_advisor_state', true );
	$zip     = get_post_meta( $post->ID, '_esop_advisor_zip', true );
	?>
	<div class="esop-geocoder-tool">
		<p class="description"><?php esc_html_e( 'Use this tool to look up coordinates from an address.', 'esop-advisor' ); ?></p>
		<div class="esop-geocoder-input-group">
			<input type="text" id="esop_geocoder_address" class="regular-text" placeholder="<?php esc_attr_e( 'Enter address...', 'esop-advisor' ); ?>" value="<?php echo esc_attr( trim( "$address $city $state $zip" ) ); ?>">
			<button type="button" id="esop_geocoder_search" class="button button-secondary"><?php esc_html_e( 'Search', 'esop-advisor' ); ?></button>
		</div>
		<div id="esop_geocoder_results" class="esop-geocoder-results" style="display:none;"><p class="description"><?php esc_html_e( 'Select a result:', 'esop-advisor' ); ?></p><div id="esop_geocoder_results_list"></div></div>
		<div id="esop_geocoder_loading" class="esop-geocoder-loading" style="display:none;"><span class="spinner is-active"></span> <?php esc_html_e( 'Searching...', 'esop-advisor' ); ?></div>
		<div id="esop_geocoder_error" class="notice notice-error inline" style="display:none;"><p></p></div>
		<div id="esop_geocoder_success" class="notice notice-success inline" style="display:none;"><p><?php esc_html_e( 'Address and coordinates updated!', 'esop-advisor' ); ?></p></div>
	</div>
	<?php
}

function esop_advisor_details_meta_box( $post ) {
	$fields = array(
		'company'      => get_post_meta( $post->ID, '_esop_advisor_company', true ),
		'title'        => get_post_meta( $post->ID, '_esop_advisor_title', true ),
		'address'      => get_post_meta( $post->ID, '_esop_advisor_address', true ),
		'city'         => get_post_meta( $post->ID, '_esop_advisor_city', true ),
		'state'        => get_post_meta( $post->ID, '_esop_advisor_state', true ),
		'zip'          => get_post_meta( $post->ID, '_esop_advisor_zip', true ),
		'phone'        => get_post_meta( $post->ID, '_esop_advisor_phone', true ),
		'cell'         => get_post_meta( $post->ID, '_esop_advisor_cell', true ),
		'fax'          => get_post_meta( $post->ID, '_esop_advisor_fax', true ),
		'email'        => get_post_meta( $post->ID, '_esop_advisor_email', true ),
		'website'      => get_post_meta( $post->ID, '_esop_advisor_website', true ),
		'about_url'    => get_post_meta( $post->ID, '_esop_advisor_about_url', true ),
		'services_url' => get_post_meta( $post->ID, '_esop_advisor_services_url', true ),
		'linkedin'     => get_post_meta( $post->ID, '_esop_advisor_linkedin', true ),
		'bio'          => get_post_meta( $post->ID, '_esop_advisor_bio', true ),
	);
	$us_states = esop_advisor_get_us_states();
	?>
	<table class="form-table">
		<tr><th><label for="esop_advisor_company"><?php esc_html_e( 'Company', 'esop-advisor' ); ?></label></th>
		<td><input type="text" id="esop_advisor_company" name="esop_advisor_company" value="<?php echo esc_attr( $fields['company'] ); ?>" class="regular-text"></td></tr>

		<tr><th><label for="esop_advisor_title"><?php esc_html_e( 'Title/Position', 'esop-advisor' ); ?></label></th>
		<td><input type="text" id="esop_advisor_title" name="esop_advisor_title" value="<?php echo esc_attr( $fields['title'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'e.g., Senior ESOP Consultant', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_address"><?php esc_html_e( 'Street Address', 'esop-advisor' ); ?></label></th>
		<td><input type="text" id="esop_advisor_address" name="esop_advisor_address" value="<?php echo esc_attr( $fields['address'] ); ?>" class="regular-text"></td></tr>

		<tr><th><label for="esop_advisor_city"><?php esc_html_e( 'City', 'esop-advisor' ); ?></label></th>
		<td><input type="text" id="esop_advisor_city" name="esop_advisor_city" value="<?php echo esc_attr( $fields['city'] ); ?>" class="regular-text"></td></tr>

		<tr><th><label for="esop_advisor_state"><?php esc_html_e( 'State', 'esop-advisor' ); ?></label></th>
		<td><select id="esop_advisor_state" name="esop_advisor_state" class="regular-text">
			<option value=""><?php esc_html_e( '-- Select State --', 'esop-advisor' ); ?></option>
			<?php foreach ( $us_states as $abbr => $name ) : ?>
				<option value="<?php echo esc_attr( $abbr ); ?>" <?php selected( $fields['state'], $abbr ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select></td></tr>

		<tr><th><label for="esop_advisor_zip"><?php esc_html_e( 'ZIP Code', 'esop-advisor' ); ?></label></th>
		<td><input type="text" id="esop_advisor_zip" name="esop_advisor_zip" value="<?php echo esc_attr( $fields['zip'] ); ?>" class="regular-text"></td></tr>

		<tr><th><label for="esop_advisor_phone"><?php esc_html_e( 'Phone', 'esop-advisor' ); ?></label></th>
		<td><input type="tel" id="esop_advisor_phone" name="esop_advisor_phone" value="<?php echo esc_attr( $fields['phone'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'Format: (XXX) XXX-XXXX', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_cell"><?php esc_html_e( 'Cell Phone', 'esop-advisor' ); ?></label></th>
		<td><input type="tel" id="esop_advisor_cell" name="esop_advisor_cell" value="<?php echo esc_attr( $fields['cell'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'Format: (XXX) XXX-XXXX', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_fax"><?php esc_html_e( 'Fax Number', 'esop-advisor' ); ?></label></th>
		<td><input type="tel" id="esop_advisor_fax" name="esop_advisor_fax" value="<?php echo esc_attr( $fields['fax'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'Format: (XXX) XXX-XXXX', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_email"><?php esc_html_e( 'Email', 'esop-advisor' ); ?></label></th>
		<td><input type="email" id="esop_advisor_email" name="esop_advisor_email" value="<?php echo esc_attr( $fields['email'] ); ?>" class="regular-text"></td></tr>

		<tr><th><label for="esop_advisor_website"><?php esc_html_e( 'Website', 'esop-advisor' ); ?></label></th>
		<td><input type="url" id="esop_advisor_website" name="esop_advisor_website" value="<?php echo esc_attr( $fields['website'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'Include https://', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_about_url"><?php esc_html_e( 'About Us Page', 'esop-advisor' ); ?></label></th>
		<td><input type="url" id="esop_advisor_about_url" name="esop_advisor_about_url" value="<?php echo esc_attr( $fields['about_url'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'Link to advisor\'s about page on their site', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_services_url"><?php esc_html_e( 'Services Page', 'esop-advisor' ); ?></label></th>
		<td><input type="url" id="esop_advisor_services_url" name="esop_advisor_services_url" value="<?php echo esc_attr( $fields['services_url'] ); ?>" class="regular-text"><p class="description"><?php esc_html_e( 'Link to advisor\'s services page on their site', 'esop-advisor' ); ?></p></td></tr>

		<tr><th><label for="esop_advisor_linkedin"><?php esc_html_e( 'LinkedIn URL', 'esop-advisor' ); ?></label></th>
		<td><input type="url" id="esop_advisor_linkedin" name="esop_advisor_linkedin" value="<?php echo esc_attr( $fields['linkedin'] ); ?>" class="regular-text"></td></tr>
	</table>

	<h3 style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;"><?php esc_html_e( 'Biography', 'esop-advisor' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Full biography with rich text formatting. A plain text excerpt is used for map popups.', 'esop-advisor' ); ?></p>
	<?php
	wp_editor(
		$fields['bio'],
		'esopadvisorbio',
		array(
			'textarea_name' => 'esop_advisor_bio',
			'textarea_rows' => 15,
			'media_buttons' => true,
			'teeny'         => false,
			'quicktags'     => true,
			'tinymce'       => true,
		)
	);
}

function esop_advisor_coordinates_meta_box( $post ) {
	$latitude  = get_post_meta( $post->ID, '_esop_advisor_latitude', true );
	$longitude = get_post_meta( $post->ID, '_esop_advisor_longitude', true );
	$has_coords = esop_advisor_has_coordinates( $post->ID );
	?>
	<div class="esop-coordinates-status">
		<?php if ( $has_coords ) : ?>
			<p class="esop-coord-status esop-coord-valid"><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Valid coordinates', 'esop-advisor' ); ?></p>
		<?php else : ?>
			<p class="esop-coord-status esop-coord-invalid"><span class="dashicons dashicons-dismiss"></span> <?php esc_html_e( 'Missing coordinates', 'esop-advisor' ); ?></p>
		<?php endif; ?>
	</div>
	<p><label><strong><?php esc_html_e( 'Latitude', 'esop-advisor' ); ?></strong></label>
	<input type="text" id="esop_advisor_latitude" name="esop_advisor_latitude" value="<?php echo esc_attr( $latitude ); ?>" class="widefat" placeholder="-90 to 90"></p>
	<p><label><strong><?php esc_html_e( 'Longitude', 'esop-advisor' ); ?></strong></label>
	<input type="text" id="esop_advisor_longitude" name="esop_advisor_longitude" value="<?php echo esc_attr( $longitude ); ?>" class="widefat" placeholder="-180 to 180"></p>
	<p class="description"><?php esc_html_e( 'Use the Address Geocoder to auto-fill, or leave empty if not needed on map.', 'esop-advisor' ); ?></p>
	<?php
}

/**
 * Education & Expertise meta box callback
 */
function esop_advisor_education_meta_box( $post ) {
	$education = get_post_meta( $post->ID, '_esop_advisor_education', true );
	?>
	<p class="description"><?php esc_html_e( 'Education background, certifications, areas of expertise, and professional qualifications.', 'esop-advisor' ); ?></p>
	<?php
	wp_editor(
		$education,
		'esopadvisoreducation',
		array(
			'textarea_name' => 'esop_advisor_education',
			'textarea_rows' => 15,
			'media_buttons' => true,
			'teeny'         => false,
			'quicktags'     => true,
			'tinymce'       => true,
		)
	);
}

/**
 * Videos meta box callback
 */
function esop_advisor_videos_meta_box( $post ) {
	$videos = get_post_meta( $post->ID, '_esop_advisor_videos', true );
	?>
	<p class="description"><?php esc_html_e( 'Add video embed codes, links, or descriptions. Supports YouTube, Vimeo, and other video embeds.', 'esop-advisor' ); ?></p>
	<?php
	wp_editor(
		$videos,
		'esopadvvideos',
		array(
			'textarea_name' => 'esop_advisor_videos',
			'textarea_rows' => 15,
			'media_buttons' => true,
			'teeny'         => false,
			'quicktags'     => true,
			'tinymce'       => true,
		)
	);
}

/**
 * Linked WordPress User meta box callback
 */
function esop_advisor_user_link_meta_box( $post ) {
	wp_nonce_field( 'esop_advisor_user_link_nonce_action', 'esop_advisor_user_link_nonce' );

	$selected_user = get_post_meta( $post->ID, '_esop_advisor_user_id', true );

	// Get users who can author posts
	$users = get_users( array(
		'role__in' => array( 'administrator', 'editor', 'author', 'contributor' ),
		'orderby'  => 'display_name',
		'order'    => 'ASC',
	) );
	?>
	<p>
		<label for="esop_advisor_user_id"><strong><?php esc_html_e( 'Associated User:', 'esop-advisor' ); ?></strong></label>
	</p>
	<select id="esop_advisor_user_id" name="esop_advisor_user_id" class="widefat">
		<option value=""><?php esc_html_e( '-- Select User --', 'esop-advisor' ); ?></option>
		<?php foreach ( $users as $user ) : ?>
			<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $selected_user, $user->ID ); ?>>
				<?php echo esc_html( $user->display_name . ' (' . $user->user_email . ')' ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description">
		<?php esc_html_e( 'Links this advisor to a WordPress user. Their articles, case studies, and reviews will display on the advisor page.', 'esop-advisor' ); ?>
	</p>
	<?php
}

function esop_advisor_get_us_states() {
	return array(
		'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
		'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
		'DC' => 'District of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii',
		'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
		'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine',
		'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota',
		'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska',
		'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico',
		'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
		'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island',
		'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas',
		'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington',
		'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming',
	);
}

// ============================================================================
// SAVE META DATA
// ============================================================================

add_action( 'save_post_esop_advisor', 'esop_advisor_save_meta', 10, 2 );

function esop_advisor_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['esop_advisor_meta_nonce'] ) || ! wp_verify_nonce( $_POST['esop_advisor_meta_nonce'], 'esop_advisor_save_meta' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	// Text fields (sanitize_text_field)
	$text_fields = array( 'company', 'title', 'address', 'city', 'state', 'zip', 'phone', 'cell', 'fax' );
	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ 'esop_advisor_' . $field ] ) ) {
			update_post_meta( $post_id, '_esop_advisor_' . $field, sanitize_text_field( $_POST[ 'esop_advisor_' . $field ] ) );
		}
	}

	// Email field
	if ( isset( $_POST['esop_advisor_email'] ) ) {
		update_post_meta( $post_id, '_esop_advisor_email', sanitize_email( $_POST['esop_advisor_email'] ) );
	}

	// URL fields (esc_url_raw)
	$url_fields = array( 'website', 'about_url', 'services_url', 'linkedin' );
	foreach ( $url_fields as $field ) {
		if ( isset( $_POST[ 'esop_advisor_' . $field ] ) ) {
			update_post_meta( $post_id, '_esop_advisor_' . $field, esc_url_raw( $_POST[ 'esop_advisor_' . $field ] ) );
		}
	}

	// Rich text fields (wp_kses_post for HTML content)
	$rich_text_fields = array( 'bio', 'education', 'videos' );
	foreach ( $rich_text_fields as $field ) {
		if ( isset( $_POST[ 'esop_advisor_' . $field ] ) ) {
			update_post_meta( $post_id, '_esop_advisor_' . $field, wp_kses_post( $_POST[ 'esop_advisor_' . $field ] ) );
		}
	}

	// Coordinate fields with validation
	if ( isset( $_POST['esop_advisor_latitude'] ) ) {
		$lat = sanitize_text_field( $_POST['esop_advisor_latitude'] );
		if ( $lat !== '' && ( ! is_numeric( $lat ) || $lat < -90 || $lat > 90 ) ) $lat = '';
		update_post_meta( $post_id, '_esop_advisor_latitude', $lat );
	}
	if ( isset( $_POST['esop_advisor_longitude'] ) ) {
		$lng = sanitize_text_field( $_POST['esop_advisor_longitude'] );
		if ( $lng !== '' && ( ! is_numeric( $lng ) || $lng < -180 || $lng > 180 ) ) $lng = '';
		update_post_meta( $post_id, '_esop_advisor_longitude', $lng );
	}

	// User link - check its own nonce
	if ( isset( $_POST['esop_advisor_user_link_nonce'] ) &&
	     wp_verify_nonce( $_POST['esop_advisor_user_link_nonce'], 'esop_advisor_user_link_nonce_action' ) ) {
		if ( isset( $_POST['esop_advisor_user_id'] ) ) {
			$user_id = intval( $_POST['esop_advisor_user_id'] );
			if ( $user_id > 0 ) {
				update_post_meta( $post_id, '_esop_advisor_user_id', $user_id );
			} else {
				delete_post_meta( $post_id, '_esop_advisor_user_id' );
			}
		}
	}
}

// ============================================================================
// DIVI CONDITION META FIELDS
// Computed fields that Divi can check for conditional display
// ============================================================================

add_action( 'save_post_esop_advisor', 'esop_advisor_update_condition_meta', 20, 2 );

/**
 * Update computed meta fields for Divi conditional display
 * These fields allow Divi's "Display Only If" conditions to work with advisor content
 * Checks both author-based posts AND manually associated posts
 *
 * Creates meta fields:
 * - esop_advisor_articles: '1' if advisor has articles, deleted if not
 * - esop_advisor_ratings: '1' if advisor has ratings, deleted if not
 * - esop_advisor_blog: '1' if advisor has blog posts, deleted if not
 * - esop_advisor_case_studies: '1' if advisor has case studies, deleted if not
 * - esop_bio: '1' if advisor has bio content, deleted if not
 * - esop_advisor_videos: '1' if advisor has videos, deleted if not
 */
function esop_advisor_update_condition_meta( $post_id, $post ) {
	// Don't run on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Get the linked WordPress user ID
	$user_id = get_post_meta( $post_id, '_esop_advisor_user_id', true );

	// Check for bio content
	$bio = get_post_meta( $post_id, '_esop_advisor_bio', true );
	if ( ! empty( trim( strip_tags( $bio ) ) ) ) {
		update_post_meta( $post_id, 'esop_bio', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_bio' );
	}

	// Check for videos content
	$videos = get_post_meta( $post_id, '_esop_advisor_videos', true );
	if ( ! empty( trim( strip_tags( $videos ) ) ) ) {
		update_post_meta( $post_id, 'esop_advisor_videos', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_advisor_videos' );
	}

	// Helper function to check for posts (author-based OR manually associated)
	$has_posts_in_category = function( $category_slug ) use ( $post_id, $user_id ) {
		// Check manually associated posts first
		$associated_query = new WP_Query( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'category_name'  => $category_slug,
			'meta_query'     => array(
				array(
					'key'   => '_esop_associated_advisor',
					'value' => intval( $post_id ),
				),
			),
		) );
		if ( $associated_query->have_posts() ) {
			wp_reset_postdata();
			return true;
		}
		wp_reset_postdata();

		// Check author-based posts if user_id exists
		if ( ! empty( $user_id ) ) {
			$author_query = new WP_Query( array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'author'         => intval( $user_id ),
				'category_name'  => $category_slug,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			) );
			if ( $author_query->have_posts() ) {
				wp_reset_postdata();
				return true;
			}
			wp_reset_postdata();
		}

		return false;
	};

	// Check for articles
	if ( $has_posts_in_category( 'articles' ) ) {
		update_post_meta( $post_id, 'esop_advisor_articles', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_advisor_articles' );
	}

	// Check for ratings/reviews
	if ( $has_posts_in_category( 'ratings' ) ) {
		update_post_meta( $post_id, 'esop_advisor_ratings', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_advisor_ratings' );
	}

	// Check for case studies
	if ( $has_posts_in_category( 'case-studies' ) ) {
		update_post_meta( $post_id, 'esop_advisor_case_studies', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_advisor_case_studies' );
	}

	// Check for blog posts (excluding case-studies, articles, ratings)
	$exclude_cats = array();
	$exclude_slugs = array( 'case-studies', 'articles', 'ratings' );
	foreach ( $exclude_slugs as $slug ) {
		$cat = get_category_by_slug( $slug );
		if ( $cat ) {
			$exclude_cats[] = $cat->term_id;
		}
	}

	$has_blog = false;

	// Check manually associated blog posts
	$associated_blog_query = new WP_Query( array(
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'category__not_in' => $exclude_cats,
		'posts_per_page'   => 1,
		'fields'           => 'ids',
		'meta_query'       => array(
			array(
				'key'   => '_esop_associated_advisor',
				'value' => intval( $post_id ),
			),
		),
	) );
	if ( $associated_blog_query->have_posts() ) {
		$has_blog = true;
	}
	wp_reset_postdata();

	// Check author-based blog posts if user_id exists
	if ( ! $has_blog && ! empty( $user_id ) ) {
		$author_blog_query = new WP_Query( array(
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'author'           => intval( $user_id ),
			'category__not_in' => $exclude_cats,
			'posts_per_page'   => 1,
			'fields'           => 'ids',
		) );
		if ( $author_blog_query->have_posts() ) {
			$has_blog = true;
		}
		wp_reset_postdata();
	}

	if ( $has_blog ) {
		update_post_meta( $post_id, 'esop_advisor_blog', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_advisor_blog' );
	}
}

/**
 * Bulk update condition meta for all advisors
 * Useful after initial setup or data migration
 * Can be triggered via: do_action('esop_advisor_refresh_all_conditions')
 */
add_action( 'esop_advisor_refresh_all_conditions', 'esop_advisor_refresh_all_condition_meta' );

function esop_advisor_refresh_all_condition_meta() {
	$advisors = get_posts( array(
		'post_type'      => 'esop_advisor',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	) );

	foreach ( $advisors as $advisor_id ) {
		$post = get_post( $advisor_id );
		esop_advisor_update_condition_meta( $advisor_id, $post );
	}

	return count( $advisors );
}

// ============================================================================
// AJAX GEOCODING
// ============================================================================

add_action( 'wp_ajax_esop_advisor_geocode', 'esop_advisor_ajax_geocode' );

function esop_advisor_ajax_geocode() {
	check_ajax_referer( 'esop_advisor_geocode', 'nonce' );
	if ( ! current_user_can( 'edit_posts' ) ) wp_send_json_error( array( 'message' => 'Permission denied.' ) );
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) wp_send_json_error( array( 'message' => 'MapBox token not configured.' ) );

	$query = isset( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';
	if ( empty( $query ) ) wp_send_json_error( array( 'message' => 'Please enter an address.' ) );

	$url = sprintf( 'https://api.mapbox.com/geocoding/v5/mapbox.places/%s.json?access_token=%s&country=US&limit=5', rawurlencode( $query ), MAPBOX_ACCESS_TOKEN );
	$response = wp_remote_get( $url, array( 'timeout' => 15 ) );

	if ( is_wp_error( $response ) ) wp_send_json_error( array( 'message' => $response->get_error_message() ) );

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( ! isset( $data['features'] ) || empty( $data['features'] ) ) wp_send_json_error( array( 'message' => 'No results found.' ) );

	$results = array();
	foreach ( $data['features'] as $f ) {
		$results[] = array(
			'place_name' => $f['place_name'],
			'center'     => $f['center'],
			'context'    => isset( $f['context'] ) ? $f['context'] : array(),
			'address'    => isset( $f['address'] ) ? $f['address'] : '',
			'text'       => isset( $f['text'] ) ? $f['text'] : '',
		);
	}
	wp_send_json_success( array( 'results' => $results ) );
}

// ============================================================================
// ADMIN COLUMNS
// ============================================================================

add_filter( 'manage_esop_advisor_posts_columns', 'esop_advisor_custom_columns' );
function esop_advisor_custom_columns( $columns ) {
	$new = array( 'cb' => $columns['cb'], 'title' => $columns['title'] );
	$new['company'] = __( 'Company', 'esop-advisor' );
	$new['city'] = __( 'City', 'esop-advisor' );
	$new['state'] = __( 'State', 'esop-advisor' );
	$new['phone'] = __( 'Phone', 'esop-advisor' );
	$new['coordinates'] = __( 'Coords', 'esop-advisor' );
	$new['date'] = $columns['date'];
	return $new;
}

add_action( 'manage_esop_advisor_posts_custom_column', 'esop_advisor_custom_column_content', 10, 2 );
function esop_advisor_custom_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'company': echo esc_html( get_post_meta( $post_id, '_esop_advisor_company', true ) ?: '—' ); break;
		case 'city': echo esc_html( get_post_meta( $post_id, '_esop_advisor_city', true ) ?: '—' ); break;
		case 'state': echo esc_html( get_post_meta( $post_id, '_esop_advisor_state', true ) ?: '—' ); break;
		case 'phone': echo esc_html( get_post_meta( $post_id, '_esop_advisor_phone', true ) ?: '—' ); break;
		case 'coordinates': echo esop_advisor_has_coordinates( $post_id ) ? '<span class="dashicons dashicons-yes-alt" style="color:#46b450;"></span>' : '<span class="dashicons dashicons-dismiss" style="color:#dc3232;"></span>'; break;
	}
}

add_filter( 'manage_edit-esop_advisor_sortable_columns', 'esop_advisor_sortable_columns' );
function esop_advisor_sortable_columns( $columns ) {
	$columns['company'] = 'company';
	$columns['city'] = 'city';
	$columns['state'] = 'state';
	return $columns;
}

add_action( 'pre_get_posts', 'esop_advisor_column_sorting' );
function esop_advisor_column_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) return;
	if ( $query->get( 'post_type' ) !== 'esop_advisor' ) return;
	$orderby = $query->get( 'orderby' );
	$map = array( 'company' => '_esop_advisor_company', 'city' => '_esop_advisor_city', 'state' => '_esop_advisor_state' );
	if ( isset( $map[ $orderby ] ) ) {
		$query->set( 'meta_key', $map[ $orderby ] );
		$query->set( 'orderby', 'meta_value' );
	}
}

// ============================================================================
// ADMIN NOTICES
// ============================================================================

add_action( 'admin_notices', 'esop_advisor_admin_notices' );
function esop_advisor_admin_notices() {
	$screen = get_current_screen();
	if ( $screen && $screen->post_type === 'esop_advisor' && ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'MapBox Configuration Required', 'esop-advisor' ) . '</strong><br>';
		echo esc_html__( 'Add to wp-config.php:', 'esop-advisor' ) . ' <code>define( \'MAPBOX_ACCESS_TOKEN\', \'pk.your_token_here\' );</code></p></div>';
	}
}

// ============================================================================
// MAP SHORTCODE
// ============================================================================

add_shortcode( 'esop_advisor_map', 'esop_advisor_map_shortcode' );

function esop_advisor_map_shortcode( $atts ) {
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		return '<p class="esop-map-error">' . esc_html__( 'MapBox access token not configured.', 'esop-advisor' ) . '</p>';
	}

	$atts = shortcode_atts( array(
		'height'     => '500px',
		'zoom'       => '4',
		'style'      => 'streets-v12',
		'center_lat' => '39.8283',
		'center_lng' => '-98.5795',
		'cluster'    => 'true',
		'autofit'    => 'true',
	), $atts, 'esop_advisor_map' );

	$advisors = get_posts( array(
		'post_type'      => 'esop_advisor',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_query'     => array(
			'relation' => 'AND',
			array( 'key' => '_esop_advisor_latitude', 'value' => '', 'compare' => '!=' ),
			array( 'key' => '_esop_advisor_longitude', 'value' => '', 'compare' => '!=' ),
		),
	) );

	if ( empty( $advisors ) ) {
		return '<p class="esop-map-notice">' . esc_html__( 'No advisors available yet.', 'esop-advisor' ) . '</p>';
	}

	// Prime the meta cache to avoid individual queries per advisor
	update_postmeta_cache( wp_list_pluck( $advisors, 'ID' ) );

	$features = array();
	foreach ( $advisors as $advisor ) {
		$lat = get_post_meta( $advisor->ID, '_esop_advisor_latitude', true );
		$lng = get_post_meta( $advisor->ID, '_esop_advisor_longitude', true );
		if ( ! is_numeric( $lat ) || ! is_numeric( $lng ) ) continue;

		$address = get_post_meta( $advisor->ID, '_esop_advisor_address', true );
		$city    = get_post_meta( $advisor->ID, '_esop_advisor_city', true );
		$state   = get_post_meta( $advisor->ID, '_esop_advisor_state', true );
		$zip     = get_post_meta( $advisor->ID, '_esop_advisor_zip', true );
		
		// Build full address for Google Maps link
		$full_address = trim( implode( ', ', array_filter( array( $address, $city, $state, $zip ) ) ) );
		$google_maps_url = $full_address ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $full_address ) : '';

		$features[] = array(
			'type'     => 'Feature',
			'geometry' => array( 'type' => 'Point', 'coordinates' => array( floatval( $lng ), floatval( $lat ) ) ),
			'properties' => array(
				'id'              => $advisor->ID,
				'name'            => get_the_title( $advisor->ID ),
				'title'           => get_post_meta( $advisor->ID, '_esop_advisor_title', true ),
				'company'         => get_post_meta( $advisor->ID, '_esop_advisor_company', true ),
				'address'         => $address,
				'city'            => $city,
				'state'           => $state,
				'zip'             => $zip,
				'full_address'    => $full_address,
				'google_maps_url' => $google_maps_url,
				'phone'           => get_post_meta( $advisor->ID, '_esop_advisor_phone', true ),
				'email'           => get_post_meta( $advisor->ID, '_esop_advisor_email', true ),
				'bio'             => wp_strip_all_tags( get_post_meta( $advisor->ID, '_esop_advisor_bio', true ) ),
				'image'           => get_the_post_thumbnail_url( $advisor->ID, 'medium' ),
				'url'             => get_permalink( $advisor->ID ),
			),
		);
	}

	static $map_id = 0;
	$map_id++;
	$container_id = 'esop-advisor-map-' . $map_id;

	wp_enqueue_script( 'mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js', array(), '2.15.0', true );
	wp_enqueue_style( 'mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css', array(), '2.15.0' );

	// Output frontend styles - use wp_head if not fired yet, otherwise output directly
	if ( ! did_action( 'wp_head' ) ) {
		add_action( 'wp_head', 'esop_advisor_frontend_styles' );
	} else {
		esop_advisor_frontend_styles();
	}

	$map_data = array(
		'containerId' => $container_id,
		'accessToken' => MAPBOX_ACCESS_TOKEN,
		'style'       => 'mapbox://styles/mapbox/' . esc_attr( $atts['style'] ),
		'center'      => array( floatval( $atts['center_lng'] ), floatval( $atts['center_lat'] ) ),
		'zoom'        => floatval( $atts['zoom'] ),
		'cluster'     => $atts['cluster'] === 'true',
		'autofit'     => $atts['autofit'] === 'true',
		'features'    => $features,
	);

	// Store map data for footer output
	esop_advisor_store_map_data( $map_data );
	add_action( 'wp_footer', 'esop_advisor_output_map_scripts', 999 );

	return sprintf( '<div id="%s" class="esop-advisor-map-container" style="height:%s;"></div>', esc_attr( $container_id ), esc_attr( $atts['height'] ) );
}

// ============================================================================
// SINGLE ADVISOR LOCATION MAP
// ============================================================================

add_shortcode( 'esop_advisor_location_map', 'esop_advisor_location_map_shortcode' );

/**
 * Single advisor location map shortcode
 *
 * Displays a MapBox map centered on a single advisor's location.
 *
 * @param array $atts Shortcode attributes
 * @return string Map HTML or empty string
 */
function esop_advisor_location_map_shortcode( $atts ) {
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		return '';
	}

	$atts = shortcode_atts( array(
		'post_id'      => '',
		'height'       => '300px',
		'zoom'         => '14',
		'style'        => 'streets-v12',
		'marker_color' => '#0066cc',
		'interactive'  => 'true',
		'show_popup'   => 'false',
	), $atts, 'esop_advisor_location_map' );

	// Get advisor post ID
	$post_id = ! empty( $atts['post_id'] ) ? intval( $atts['post_id'] ) : get_the_ID();

	// Verify it's an advisor post
	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return '';
	}

	// Get coordinates
	$latitude  = get_post_meta( $post_id, '_esop_advisor_latitude', true );
	$longitude = get_post_meta( $post_id, '_esop_advisor_longitude', true );

	// Validate coordinates
	if ( ! is_numeric( $latitude ) || ! is_numeric( $longitude ) ) {
		return '';
	}

	// Get advisor info for popup
	$address = get_post_meta( $post_id, '_esop_advisor_address', true );
	$city    = get_post_meta( $post_id, '_esop_advisor_city', true );
	$state   = get_post_meta( $post_id, '_esop_advisor_state', true );
	$zip     = get_post_meta( $post_id, '_esop_advisor_zip', true );

	$full_address    = trim( implode( ', ', array_filter( array( $address, $city, $state, $zip ) ) ) );
	$google_maps_url = $full_address ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $full_address ) : '';

	// Enqueue MapBox scripts/styles
	wp_enqueue_script( 'mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js', array(), '2.15.0', true );
	wp_enqueue_style( 'mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css', array(), '2.15.0' );

	// Output frontend styles
	if ( ! did_action( 'wp_head' ) ) {
		add_action( 'wp_head', 'esop_advisor_frontend_styles' );
	} else {
		esop_advisor_frontend_styles();
	}

	// Generate unique container ID
	static $map_id = 0;
	$map_id++;
	$container_id = 'esop-location-map-' . $map_id;

	// Build map data
	$map_data = array(
		'containerId'  => $container_id,
		'accessToken'  => MAPBOX_ACCESS_TOKEN,
		'style'        => 'mapbox://styles/mapbox/' . esc_attr( $atts['style'] ),
		'center'       => array( floatval( $longitude ), floatval( $latitude ) ),
		'zoom'         => floatval( $atts['zoom'] ),
		'interactive'  => $atts['interactive'] === 'true',
		'showPopup'    => $atts['show_popup'] === 'true',
		'markerColor'  => sanitize_hex_color( $atts['marker_color'] ) ?: '#0066cc',
		'advisor'      => array(
			'name'    => get_the_title( $post_id ),
			'url'     => get_permalink( $post_id ),
			'address' => $full_address,
			'mapsUrl' => $google_maps_url,
		),
	);

	// Store map data for footer output
	esop_advisor_store_single_map_data( $map_data );
	add_action( 'wp_footer', 'esop_advisor_output_single_map_scripts', 999 );

	return sprintf(
		'<div id="%s" class="esop-advisor-location-map" style="height:%s;"></div>',
		esc_attr( $container_id ),
		esc_attr( $atts['height'] )
	);
}

/**
 * Store single advisor map data for later output in footer.
 *
 * @param array|null $map_data Map data to store, or null to retrieve
 * @return array Stored map data
 */
function esop_advisor_store_single_map_data( $map_data = null ) {
	static $stored_maps = array();
	if ( $map_data !== null ) {
		$stored_maps[] = $map_data;
	}
	return $stored_maps;
}

/**
 * Output single advisor map initialization scripts in footer.
 */
function esop_advisor_output_single_map_scripts() {
	$maps = esop_advisor_store_single_map_data();
	if ( empty( $maps ) ) {
		return;
	}
	?>
	<script>
	(function() {
		if (typeof mapboxgl === 'undefined') {
			console.error('MapBox GL JS not loaded');
			return;
		}

		var singleMaps = <?php echo wp_json_encode( $maps ); ?>;

		singleMaps.forEach(function(d) {
			mapboxgl.accessToken = d.accessToken;

			var mapOptions = {
				container: d.containerId,
				style: d.style,
				center: d.center,
				zoom: d.zoom
			};

			// Disable interaction if specified
			if (!d.interactive) {
				mapOptions.interactive = false;
				mapOptions.attributionControl = false;
			}

			var map = new mapboxgl.Map(mapOptions);

			// Add navigation controls only if interactive
			if (d.interactive) {
				map.addControl(new mapboxgl.NavigationControl(), 'top-right');
			}

			// Create popup HTML
			var popupHTML = '<div class="esop-single-popup">';
			popupHTML += '<strong><a href="' + d.advisor.url + '">' + d.advisor.name + '</a></strong>';
			if (d.advisor.address && d.advisor.mapsUrl) {
				popupHTML += '<br><a href="' + d.advisor.mapsUrl + '" target="_blank" rel="noopener noreferrer">' + d.advisor.address + '</a>';
			}
			popupHTML += '</div>';

			// Create popup
			var popup = new mapboxgl.Popup({
				offset: 25,
				closeButton: true,
				closeOnClick: false
			}).setHTML(popupHTML);

			// Create marker
			var markerEl = document.createElement('div');
			markerEl.style.cssText = 'width:24px;height:24px;background:' + d.markerColor + ';border:3px solid #fff;border-radius:50%;box-shadow:0 2px 6px rgba(0,0,0,0.3);cursor:pointer;';

			var marker = new mapboxgl.Marker({
				element: markerEl,
				anchor: 'center'
			})
			.setLngLat(d.center)
			.setPopup(popup)
			.addTo(map);

			// Show popup on load if specified
			if (d.showPopup) {
				marker.togglePopup();
			}
		});
	})();
	</script>
	<?php
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function esop_get_advisor_meta( $post_id, $key ) {
	if ( get_post_type( $post_id ) !== 'esop_advisor' ) return '';
	return get_post_meta( $post_id, '_esop_advisor_' . $key, true );
}

/**
 * Store map data for later output in footer.
 */
function esop_advisor_store_map_data( $map_data = null ) {
	static $stored_maps = array();
	if ( $map_data !== null ) {
		$stored_maps[] = $map_data;
	}
	return $stored_maps;
}

/**
 * Output map initialization scripts in footer.
 */
function esop_advisor_output_map_scripts() {
	$maps = esop_advisor_store_map_data();
	if ( empty( $maps ) ) {
		return;
	}
	?>
	<script>
	(function() {
		if (typeof mapboxgl === 'undefined') { console.error('MapBox GL JS not loaded'); return; }

		// SVG Icons
		var icons = {
			user: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.7 0 4.875-2.175 4.875-4.875S14.7 2.25 12 2.25 7.125 4.425 7.125 7.125 9.3 12 12 12zm0 2.25c-3.262 0-9.75 1.631-9.75 4.875V21h19.5v-1.875c0-3.244-6.488-4.875-9.75-4.875z"/></svg>',
			location: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
			phone: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
			email: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>'
		};

		function buildPopupHTML(p) {
			var html = '<div class="esop-popup">';

			// Header section with blue gradient
			html += '<div class="esop-popup__header">';

			// Avatar
			html += '<div class="esop-popup__avatar';
			if (!p.image) html += ' esop-popup__avatar--placeholder';
			html += '">';
			if (p.image) {
				html += '<img src="' + p.image + '" alt="' + p.name + '">';
			} else {
				html += icons.user;
			}
			html += '</div>';

			// Info
			html += '<div class="esop-popup__info">';
			html += '<h3 class="esop-popup__name"><a href="' + p.url + '">' + p.name + '</a></h3>';
			if (p.title) html += '<p class="esop-popup__title">' + p.title + '</p>';
			if (p.company) html += '<p class="esop-popup__company">' + p.company + '</p>';
			html += '</div>';

			html += '</div>'; // end header

			// Content section
			html += '<div class="esop-popup__content">';

			// Contact card
			var hasContact = p.email || p.phone || (p.full_address && p.google_maps_url);
			if (hasContact) {
				html += '<div class="esop-popup__contact">';

				if (p.email) {
					html += '<a href="mailto:' + p.email + '" class="esop-popup__contact-item">';
					html += '<span class="esop-popup__contact-icon">' + icons.email + '</span>';
					html += '<span>' + p.email + '</span>';
					html += '</a>';
				}

				if (p.phone) {
					html += '<a href="tel:' + p.phone + '" class="esop-popup__contact-item">';
					html += '<span class="esop-popup__contact-icon">' + icons.phone + '</span>';
					html += '<span>' + p.phone + '</span>';
					html += '</a>';
				}

				if (p.full_address && p.google_maps_url) {
					html += '<a href="' + p.google_maps_url + '" target="_blank" rel="noopener" class="esop-popup__contact-item">';
					html += '<span class="esop-popup__contact-icon">' + icons.location + '</span>';
					html += '<span>' + p.full_address + '</span>';
					html += '</a>';
				}

				html += '</div>'; // end contact
			}

			// CTA Button
			html += '<a href="' + p.url + '" class="esop-popup__cta">View Full Profile</a>';

			html += '</div>'; // end content
			html += '</div>'; // end popup

			return html;
		}

		var maps = <?php echo wp_json_encode( $maps ); ?>;
		maps.forEach(function(d, idx) {
			mapboxgl.accessToken = d.accessToken;
			var map = new mapboxgl.Map({ container: d.containerId, style: d.style, center: d.center, zoom: d.zoom });
			var srcId = 'advisors-' + idx, clsId = 'clusters-' + idx, cntId = 'cluster-count-' + idx, ptId = 'unclustered-point-' + idx;
			map.addControl(new mapboxgl.NavigationControl(), 'top-right');
			map.addControl(new mapboxgl.FullscreenControl(), 'top-right');

			map.on('load', function() {
				map.addSource(srcId, { type: 'geojson', data: { type: 'FeatureCollection', features: d.features }, cluster: d.cluster, clusterMaxZoom: 14, clusterRadius: 50 });

				if (d.cluster) {
					map.addLayer({ id: clsId, type: 'circle', source: srcId, filter: ['has', 'point_count'],
						paint: { 'circle-color': ['step', ['get', 'point_count'], '#0066cc', 10, '#f1a32b', 30, '#dc3545'], 'circle-radius': ['step', ['get', 'point_count'], 22, 10, 30, 30, 40], 'circle-stroke-width': 3, 'circle-stroke-color': '#fff' }
					});
					map.addLayer({ id: cntId, type: 'symbol', source: srcId, filter: ['has', 'point_count'],
						layout: { 'text-field': '{point_count_abbreviated}', 'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'], 'text-size': 14 },
						paint: { 'text-color': '#ffffff' }
					});
					map.on('click', clsId, function(e) {
						var f = map.queryRenderedFeatures(e.point, { layers: [clsId] });
						map.getSource(srcId).getClusterExpansionZoom(f[0].properties.cluster_id, function(err, zoom) { if (!err) map.easeTo({ center: f[0].geometry.coordinates, zoom: zoom }); });
					});
					map.on('mouseenter', clsId, function() { map.getCanvas().style.cursor = 'pointer'; });
					map.on('mouseleave', clsId, function() { map.getCanvas().style.cursor = ''; });
				}

				map.addLayer({ id: ptId, type: 'circle', source: srcId, filter: d.cluster ? ['!', ['has', 'point_count']] : null,
					paint: { 'circle-color': '#0066cc', 'circle-radius': 10, 'circle-stroke-width': 3, 'circle-stroke-color': '#fff' }
				});

				map.on('click', ptId, function(e) {
					var coords = e.features[0].geometry.coordinates.slice();
					var p = e.features[0].properties;
					while (Math.abs(e.lngLat.lng - coords[0]) > 180) { coords[0] += e.lngLat.lng > coords[0] ? 360 : -360; }

					var html = buildPopupHTML(p);
					var popup = new mapboxgl.Popup({ maxWidth: '300px', className: 'esop-mapbox-popup' }).setLngLat(coords).setHTML(html).addTo(map);

					setTimeout(function() {
						var popupEl = popup.getElement();
						if (popupEl) {
							var popupRect = popupEl.getBoundingClientRect();
							var mapRect = map.getContainer().getBoundingClientRect();
							var offset = [0, 0];
							if (popupRect.bottom > mapRect.bottom - 10) offset[1] = (popupRect.bottom - mapRect.bottom) + 30;
							if (popupRect.top < mapRect.top + 10) offset[1] = -(mapRect.top - popupRect.top) - 30;
							if (popupRect.right > mapRect.right - 10) offset[0] = (popupRect.right - mapRect.right) + 30;
							if (popupRect.left < mapRect.left + 10) offset[0] = -(mapRect.left - popupRect.left) - 30;
							if (offset[0] !== 0 || offset[1] !== 0) map.panBy(offset, { duration: 300 });
						}
					}, 100);
				});

				map.on('mouseenter', ptId, function() { map.getCanvas().style.cursor = 'pointer'; });
				map.on('mouseleave', ptId, function() { map.getCanvas().style.cursor = ''; });

				if (d.autofit && d.features.length > 0) {
					var bounds = new mapboxgl.LngLatBounds();
					d.features.forEach(function(f) { bounds.extend(f.geometry.coordinates); });
					map.fitBounds(bounds, { padding: 60, maxZoom: 12 });
				}
			});
		});
	})();
	</script>
	<?php
}

function esop_advisor_has_coordinates( $post_id ) {
	$lat = get_post_meta( $post_id, '_esop_advisor_latitude', true );
	$lng = get_post_meta( $post_id, '_esop_advisor_longitude', true );
	return is_numeric( $lat ) && $lat >= -90 && $lat <= 90 && is_numeric( $lng ) && $lng >= -180 && $lng <= 180;
}

function esop_get_advisor_location( $post_id ) {
	$city  = get_post_meta( $post_id, '_esop_advisor_city', true );
	$state = get_post_meta( $post_id, '_esop_advisor_state', true );
	return implode( ', ', array_filter( array( $city, $state ) ) );
}

// ============================================================================
// ADMIN STYLES & SCRIPTS
// ============================================================================

add_action( 'admin_enqueue_scripts', 'esop_advisor_admin_scripts' );
function esop_advisor_admin_scripts( $hook ) {
	global $post_type;
	if ( 'esop_advisor' !== $post_type ) return;
	add_action( 'admin_head', 'esop_advisor_admin_styles' );
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) add_action( 'admin_footer', 'esop_advisor_admin_javascript' );
}

function esop_advisor_admin_styles() { ?>
<style>
.esop-geocoder-tool{background:#f9f9f9;padding:15px;border:1px solid #ddd;border-radius:4px}
.esop-geocoder-input-group{display:flex;gap:10px;margin-bottom:15px}
.esop-geocoder-input-group input{flex:1}
.esop-geocoder-results{background:#fff;border:1px solid #ddd;border-radius:4px;padding:10px;margin-top:15px;max-height:300px;overflow-y:auto}
.esop-geocoder-result-item{padding:10px;border-bottom:1px solid #eee;cursor:pointer;transition:background .2s}
.esop-geocoder-result-item:last-child{border-bottom:none}
.esop-geocoder-result-item:hover{background:#f0f0f0}
.esop-geocoder-result-item strong{display:block;margin-bottom:5px;color:#2271b1}
.esop-geocoder-result-item small{color:#666}
.esop-geocoder-loading{padding:10px;color:#666}
.esop-geocoder-loading .spinner{float:none;margin:0 5px 0 0;vertical-align:middle}
.esop-coordinates-status{margin-bottom:15px}
.esop-coord-status{padding:8px 12px;border-radius:4px;font-weight:500}
.esop-coord-valid{background:#d4edda;color:#155724}
.esop-coord-invalid{background:#f8d7da;color:#721c24}
.esop-coord-status .dashicons{vertical-align:middle;margin-right:5px}
</style>
<?php }

function esop_advisor_admin_javascript() {
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) return; ?>
<script>
(function(){
	var searchBtn=document.getElementById('esop_geocoder_search'),addressInput=document.getElementById('esop_geocoder_address'),resultsContainer=document.getElementById('esop_geocoder_results'),resultsList=document.getElementById('esop_geocoder_results_list'),loadingIndicator=document.getElementById('esop_geocoder_loading'),errorContainer=document.getElementById('esop_geocoder_error'),successContainer=document.getElementById('esop_geocoder_success');
	if(!searchBtn||!addressInput)return;
	searchBtn.addEventListener('click',function(e){e.preventDefault();performGeocode();});
	addressInput.addEventListener('keypress',function(e){if(e.key==='Enter'){e.preventDefault();performGeocode();}});
	function performGeocode(){
		var query=addressInput.value.trim();
		if(!query){showError('Please enter an address.');return;}
		resultsContainer.style.display='none';errorContainer.style.display='none';successContainer.style.display='none';loadingIndicator.style.display='block';
		var data=new FormData();data.append('action','esop_advisor_geocode');data.append('nonce','<?php echo wp_create_nonce('esop_advisor_geocode'); ?>');data.append('query',query);
		fetch(ajaxurl,{method:'POST',credentials:'same-origin',body:data}).then(function(r){return r.json();}).then(function(r){loadingIndicator.style.display='none';if(r.success&&r.data.results){displayResults(r.data.results);}else{showError(r.data.message||'Error occurred.');}}).catch(function(e){loadingIndicator.style.display='none';showError('Network error: '+e.message);});
	}
	function displayResults(results){
		resultsList.innerHTML='';
		results.forEach(function(r){
			var item=document.createElement('div');item.className='esop-geocoder-result-item';
			var title=document.createElement('strong');title.textContent=r.place_name;
			var coords=document.createElement('small');coords.textContent='Lat: '+r.center[1].toFixed(6)+', Lng: '+r.center[0].toFixed(6);
			item.appendChild(title);item.appendChild(coords);
			item.addEventListener('click',function(){selectResult(r);});
			resultsList.appendChild(item);
		});
		resultsContainer.style.display='block';
	}
	function selectResult(r){
		var streetNumber='',streetName=r.text||'',city='',state='',zip='';
		if(r.address)streetNumber=r.address;
		if(r.context){r.context.forEach(function(c){var t=c.id.split('.')[0];if(t==='place')city=c.text;else if(t==='region')state=c.short_code?c.short_code.replace('US-',''):'';else if(t==='postcode')zip=c.text;});}
		var street=streetNumber?streetNumber+' '+streetName:streetName;
		document.getElementById('esop_advisor_address').value=street;
		document.getElementById('esop_advisor_city').value=city;
		document.getElementById('esop_advisor_state').value=state;
		document.getElementById('esop_advisor_zip').value=zip;
		document.getElementById('esop_advisor_latitude').value=r.center[1].toFixed(8);
		document.getElementById('esop_advisor_longitude').value=r.center[0].toFixed(8);
		resultsContainer.style.display='none';successContainer.style.display='block';
		setTimeout(function(){successContainer.style.display='none';},3000);
	}
	function showError(msg){errorContainer.querySelector('p').textContent=msg;errorContainer.style.display='block';}
})();
</script>
<?php }

// ============================================================================
// FRONTEND STYLES
// ============================================================================

function esop_advisor_frontend_styles() {
	static $already_output = false;
	if ( $already_output ) {
		return;
	}
	$already_output = true;
	?>
<style>
/* Map Container */
.esop-advisor-map-container{width:100%;margin:20px 0;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.12)}

/* Popup Base */
.esop-mapbox-popup .mapboxgl-popup-content{padding:0;border-radius:12px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,0.18)}
.esop-mapbox-popup .mapboxgl-popup-close-button{font-size:20px;padding:6px 10px;color:#fff;z-index:10;opacity:0.8;transition:opacity .2s}
.esop-mapbox-popup .mapboxgl-popup-close-button:hover{opacity:1;background:transparent}

/* Popup Structure */
.esop-mapbox-popup .esop-popup{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;width:280px;max-width:280px}

/* Header with blue gradient */
.esop-mapbox-popup .esop-popup__header{background:linear-gradient(135deg,#515f68 0%,#3d474f 100%);padding:16px;display:flex;align-items:center;gap:8px}
.esop-mapbox-popup .esop-popup__avatar{width:64px;height:64px;border-radius:50%;overflow:hidden;flex-shrink:0;border:3px solid rgba(255,255,255,0.3);background:#e8f4fc}
.esop-mapbox-popup .esop-popup__avatar img{width:100%;height:100%;object-fit:cover;display:block}
.esop-mapbox-popup .esop-popup__avatar--placeholder{display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#e8f4fc 0%,#d0e8f7 100%)}
.esop-mapbox-popup .esop-popup__avatar--placeholder svg{width:36px;height:36px;opacity:0.5}
.esop-mapbox-popup .esop-popup__info{flex:1;min-width:0}
.esop-mapbox-popup .esop-popup__name{margin:0 0 4px;font-size:16px;font-weight:700;line-height:1.25;color:#fff}
.esop-mapbox-popup .esop-popup__name a{color:#fff;text-decoration:none}
.esop-mapbox-popup .esop-popup__name a:hover{text-decoration:underline}
.esop-mapbox-popup .esop-popup__title{margin:0;padding:0;font-size:12px;color:rgba(255,255,255,0.85);font-weight:400}
.esop-mapbox-popup .esop-popup__company{margin:4px 0 0;font-size:14px;color:rgba(255,255,255,0.85);font-weight:500;letter-spacing:0.2px}

/* Content area */
.esop-mapbox-popup .esop-popup__content{padding:14px 16px 16px}

/* Contact card */
.esop-mapbox-popup .esop-popup__contact{background:#f7fafc;border-radius:8px;padding:12px;margin-bottom:14px}
.esop-mapbox-popup .esop-popup__contact-item{display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;font-size:13px;line-height:1.4;color:#374151;text-decoration:none;transition:color .2s;text-align:left}
.esop-mapbox-popup .esop-popup__contact-item:last-child{margin-bottom:0}
.esop-mapbox-popup a.esop-popup__contact-item:hover{color:#515f68}
.esop-mapbox-popup .esop-popup__contact-icon{flex-shrink:0;width:18px;height:18px;color:#515f68}
.esop-mapbox-popup .esop-popup__contact-icon svg{width:18px;height:18px;display:block}

/* CTA Button */
.esop-mapbox-popup .esop-popup__cta{display:block;padding:12px 16px;background:linear-gradient(135deg,#515f68 0%,#3d474f 100%);color:#fff!important;text-decoration:none;border-radius:8px;font-size:13px;font-weight:600;text-align:center;transition:all .2s;box-shadow:0 2px 8px rgba(81,95,104,0.25)}
.esop-mapbox-popup .esop-popup__cta:hover{background:linear-gradient(135deg,#3d474f 0%,#2d353b 100%);transform:translateY(-1px);box-shadow:0 4px 12px rgba(81,95,104,0.35)}

/* Error/Notice Messages */
.esop-map-error,.esop-map-notice{padding:15px 20px;border-radius:8px;margin:20px 0}
.esop-map-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
.esop-map-notice{background:#d1ecf1;color:#0c5460;border:1px solid #bee5eb}

/* Responsive */
@media(max-width:768px){
	.esop-mapbox-popup .esop-popup{width:260px;max-width:260px}
	.esop-mapbox-popup .esop-popup__header{padding:14px}
	.esop-mapbox-popup .esop-popup__avatar{width:56px;height:56px}
	.esop-mapbox-popup .esop-popup__name{font-size:15px}
	.esop-mapbox-popup .esop-popup__content{padding:12px 14px 14px}
}

/* ============================================
   ADVISOR RELATED POSTS SECTIONS
   ============================================ */
.esop-posts-section {
	margin: 40px 0;
}

.esop-posts-section__title {
	font-size: 24px;
	font-weight: 700;
	color: #1a365d;
	margin-bottom: 20px;
	padding-bottom: 10px;
	border-bottom: 2px solid #e2e8f0;
}

.esop-posts-grid {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.esop-post-card {
	display: flex;
	gap: 20px;
	padding: 20px;
	background: #fff;
	border: 1px solid #e2e8f0;
	border-radius: 8px;
	transition: box-shadow 0.2s ease;
}

.esop-post-card:hover {
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.esop-post-card__image {
	flex-shrink: 0;
	width: 150px;
	height: 120px;
	overflow: hidden;
	border-radius: 6px;
	background: #f7fafc;
}

.esop-post-card__image a {
	display: block;
	width: 100%;
	height: 100%;
}

.esop-post-card__image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.esop-post-card__no-image {
	flex-shrink: 0;
	width: 150px;
	height: 120px;
	background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
	border-radius: 6px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.esop-post-card__no-image svg {
	width: 40px;
	height: 40px;
	fill: #a0aec0;
}

.esop-post-card__content {
	flex: 1;
	display: flex;
	flex-direction: column;
	min-width: 0;
}

.esop-post-card__title {
	font-size: 18px;
	font-weight: 600;
	color: #1a365d;
	margin: 0 0 10px 0;
	line-height: 1.3;
}

.esop-post-card__title a {
	color: inherit;
	text-decoration: none;
}

.esop-post-card__title a:hover {
	color: #2b6cb0;
}

.esop-post-card__excerpt {
	font-size: 14px;
	color: #4a5568;
	line-height: 1.6;
	margin: 0 0 15px 0;
	flex: 1;
}

.esop-post-card__link {
	align-self: flex-end;
	font-size: 14px;
	font-weight: 600;
	color: #2b6cb0;
	text-decoration: none;
	display: inline-flex;
	align-items: center;
	gap: 5px;
}

.esop-post-card__link:hover {
	color: #1a365d;
}

.esop-post-card__link svg {
	width: 16px;
	height: 16px;
	fill: currentColor;
	transition: transform 0.2s ease;
}

.esop-post-card__link:hover svg {
	transform: translateX(3px);
}

/* Load More Button */
.esop-load-more-btn {
	display: block;
	margin: 30px auto 0;
	padding: 12px 30px;
	background: linear-gradient(135deg, #2b6cb0 0%, #1a365d 100%);
	color: #fff;
	border: none;
	border-radius: 8px;
	font-size: 14px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.2s ease;
}

.esop-load-more-btn:hover:not(:disabled) {
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(26, 54, 93, 0.3);
}

.esop-load-more-btn:disabled {
	opacity: 0.7;
	cursor: not-allowed;
}

/* Empty State */
.esop-posts-empty {
	padding: 40px;
	text-align: center;
	background: #f7fafc;
	border-radius: 8px;
	color: #718096;
	font-size: 14px;
}

/* Responsive */
@media (max-width: 600px) {
	.esop-post-card {
		flex-direction: column;
	}

	.esop-post-card__image,
	.esop-post-card__no-image {
		width: 100%;
		height: 180px;
	}

	.esop-post-card__link {
		align-self: flex-start;
	}
}

/* ============================================
   ADVISOR POSTS - HORIZONTAL LAYOUT
   For Case Studies and Articles
   ============================================ */
.esop-posts-horizontal .esop-posts-grid {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.esop-posts-horizontal .esop-post-card {
    display: flex;
    flex-direction: row;
    align-items: flex-start;
    gap: 24px;
    padding: 24px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.esop-posts-horizontal .esop-post-card__image,
.esop-posts-horizontal .esop-post-card__no-image {
    flex-shrink: 0;
    width: 180px;
    height: 140px;
}

.esop-posts-horizontal .esop-post-card__content {
    flex: 1;
}

.esop-posts-horizontal .esop-post-card__link {
    display: inline-block;
    padding: 8px 20px;
    background: transparent;
    color: #c75a23;
    border: 2px solid #c75a23;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.esop-posts-horizontal .esop-post-card__link:hover {
    background: #c75a23;
    color: #fff;
}

.esop-posts-horizontal .esop-post-card__link svg {
    display: none;
}

@media (max-width: 768px) {
    .esop-posts-horizontal .esop-post-card {
        flex-direction: column;
    }

    .esop-posts-horizontal .esop-post-card__image,
    .esop-posts-horizontal .esop-post-card__no-image {
        width: 100%;
        height: 200px;
    }
}

/* ============================================
   ADVISOR POSTS - GRID LAYOUT (3 columns)
   For Blog Posts
   ============================================ */
.esop-posts-grid-layout .esop-posts-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.esop-posts-grid-layout .esop-post-card {
    flex-direction: column;
    padding: 0;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.esop-posts-grid-layout .esop-post-card__image,
.esop-posts-grid-layout .esop-post-card__no-image {
    width: 100%;
    height: 180px;
    border-radius: 0;
}

.esop-posts-grid-layout .esop-post-card__content {
    padding: 20px;
}

.esop-posts-grid-layout .esop-post-card__title {
    font-size: 16px;
}

.esop-posts-grid-layout .esop-post-card__link {
    display: inline-block;
    padding: 8px 16px;
    background: transparent;
    color: #c75a23;
    border: 2px solid #c75a23;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}

.esop-posts-grid-layout .esop-post-card__link:hover {
    background: #c75a23;
    color: #fff;
}

.esop-posts-grid-layout .esop-post-card__link svg {
    display: none;
}

@media (max-width: 980px) {
    .esop-posts-grid-layout .esop-posts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .esop-posts-grid-layout .esop-posts-grid {
        grid-template-columns: 1fr;
    }
}

/* ============================================
   DIVI BLOG MODULE STYLES ON ADVISOR PAGES
   ============================================ */
.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post {
    display: flex !important;
    flex-direction: row !important;
    align-items: flex-start !important;
    gap: 24px;
    padding: 24px;
    margin-bottom: 24px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .entry-featured-image-url {
    flex-shrink: 0;
    width: 180px;
    margin: 0 !important;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .entry-featured-image-url img {
    width: 180px;
    height: 140px;
    object-fit: cover;
    border-radius: 6px;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .post-content-wrapper {
    flex: 1;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .entry-title {
    font-size: 18px;
    font-weight: 600;
    color: #1a365d;
    margin: 0 0 8px 0;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .post-meta {
    font-size: 12px;
    color: #718096;
    margin-bottom: 10px;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .post-content {
    font-size: 14px;
    color: #4a5568;
    line-height: 1.6;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link {
    display: inline-block;
    padding: 8px 20px;
    background: transparent;
    color: #c75a23;
    border: 2px solid #c75a23;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    margin-top: 15px;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link:hover,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link:hover {
    background: #c75a23;
    color: #fff;
}

@media (max-width: 768px) {
    .esop-advisor-page .et_pb_blog_fullwidth .et_pb_post {
        flex-direction: column;
    }

    .esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .entry-featured-image-url {
        width: 100%;
    }

    .esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .entry-featured-image-url img {
        width: 100%;
        height: 200px;
    }
}

.esop-advisor-page .et_pb_blog_grid .et_pb_post {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}

.esop-advisor-page .et_pb_blog_grid .et_pb_post .entry-featured-image-url img {
    height: 180px;
    object-fit: cover;
}

.esop-advisor-page .et_pb_blog_grid .et_pb_post .post-content {
    padding: 20px;
}

/* ============================================
   SINGLE ADVISOR LOCATION MAP
   ============================================ */
.esop-advisor-location-map {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.esop-single-popup {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    padding: 5px 0;
}

.esop-single-popup a {
    color: #0066cc;
    text-decoration: none;
}

.esop-single-popup a:hover {
    text-decoration: underline;
}

.esop-single-popup strong a {
    color: #1a365d;
    font-size: 15px;
}

/* ============================================
   ROW SHORTCODES - Contact & Resource Rows
   ============================================ */

/* Hide br tags between row shortcodes (Divi adds these) */
.esop-row + br,
br + .esop-row,
.esop-address-block + br,
br + .esop-address-block {
    display: none;
}

/* Row shortcode base styles - zero margin for tight spacing */
.esop-row {
    margin: 0;
    padding: 0;
    line-height: 1.6;
    display: block;
}

/* Row icons - inline with text */
.esop-row-icon {
    width: 16px;
    height: 16px;
    vertical-align: text-bottom;
    margin-right: 6px;
    display: inline-block;
}

/* Link icon is already orange via fill attribute */
.esop-row-icon--link {
    fill: #e35205;
}

/* Phone/Cell/Fax rows - ORANGE links */
.esop-row--phone a,
.esop-row--cell a {
    color: #e35205;
    text-decoration: none;
}

.esop-row--phone a:hover,
.esop-row--cell a:hover {
    color: #c94700;
    text-decoration: underline;
}

.esop-row--fax span {
    color: #e35205;
}

/* Resource links - ORANGE */
.esop-row--website a,
.esop-row--about a,
.esop-row--services a {
    color: #e35205;
    text-decoration: none;
}

.esop-row--website a:hover,
.esop-row--about a:hover,
.esop-row--services a:hover {
    color: #c94700;
    text-decoration: underline;
}

/* Company/Title/Email rows - dark text */
.esop-row--company,
.esop-row--title {
    color: #333;
}

.esop-row--email a {
    color: #333;
    text-decoration: none;
}

.esop-row--email a:hover {
    text-decoration: underline;
}

/* Address block */
.esop-address-block {
    line-height: 1.6;
    margin: 0;
}

/* Contact Button - Orange bordered with arrow */
.esop-contact-button,
a.esop-contact-button,
a.esop-contact-button:link,
a.esop-contact-button:visited {
    display: inline-block;
    padding: 12px 28px 12px 24px;
    background: #fff !important;
    background-color: #fff !important;
    color: #e35205 !important;
    border: 2px solid #e35205 !important;
    border-radius: 0 !important;
    text-decoration: none !important;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.4;
    transition: all 0.3s ease;
    cursor: pointer;
    box-sizing: border-box;
}

.esop-contact-button:hover,
.esop-contact-button:focus,
a.esop-contact-button:hover,
a.esop-contact-button:focus {
    background: #e35205 !important;
    background-color: #e35205 !important;
    color: #fff !important;
    border-color: #e35205 !important;
    text-decoration: none !important;
}

.esop-contact-button::after {
    content: " \203A";
    font-weight: 400;
    margin-left: 8px;
    font-size: 18px;
}
</style>
<?php }

// ============================================================================
// ADVISOR RELATED POSTS SHORTCODES
// ============================================================================

/**
 * Get category configuration map (shared between shortcode and AJAX)
 * Uses static caching for category IDs to avoid repeated DB lookups
 *
 * @param bool $reset Force rebuild of cache (useful for debugging)
 * @return array Category configuration with cached term IDs
 */
function esop_advisor_get_category_config( $reset = false ) {
	static $category_map = null;

	if ( $category_map !== null && ! $reset ) {
		esop_advisor_debug_log( 'get_category_config: returning cached config' );
		return $category_map;
	}

	esop_advisor_debug_log( 'get_category_config: building category map' );

	// Get category IDs once and cache them
	$case_studies_cat = get_category_by_slug( 'case-studies' );
	$articles_cat     = get_category_by_slug( 'articles' );
	$ratings_cat      = get_category_by_slug( 'ratings' );

	esop_advisor_debug_log( 'get_category_config: category lookups', array(
		'case_studies' => $case_studies_cat ? $case_studies_cat->term_id : 'NOT FOUND',
		'articles'     => $articles_cat ? $articles_cat->term_id : 'NOT FOUND',
		'ratings'      => $ratings_cat ? $ratings_cat->term_id : 'NOT FOUND',
	) );

	$exclude_ids = array();
	if ( $case_studies_cat ) $exclude_ids[] = $case_studies_cat->term_id;
	if ( $articles_cat )     $exclude_ids[] = $articles_cat->term_id;
	if ( $ratings_cat )      $exclude_ids[] = $ratings_cat->term_id;

	$category_map = array(
		'esop_advisor_case_studies' => array(
			'slug'  => 'case-studies',
			'title' => __( 'Case Studies', 'esop-advisor' ),
			'type'  => 'include',
		),
		'esop_advisor_articles' => array(
			'slug'  => 'articles',
			'title' => __( 'Articles', 'esop-advisor' ),
			'type'  => 'include',
		),
		'esop_advisor_ratings' => array(
			'slug'  => 'ratings',
			'title' => __( 'Reviews', 'esop-advisor' ),
			'type'  => 'include',
		),
		'esop_advisor_blog' => array(
			'slug'        => 'case-studies,articles,ratings',
			'title'       => __( 'Blog', 'esop-advisor' ),
			'type'        => 'exclude',
			'exclude_ids' => $exclude_ids, // Pre-cached for performance
		),
	);

	return $category_map;
}

/**
 * Build WP_Query args for advisor posts with category filtering
 * Supports both author-based posts and manually associated posts
 *
 * @param array    $category_info Category configuration from esop_advisor_get_category_config()
 * @param int|null $author_id     WordPress user ID (can be empty if using manual association)
 * @param int      $advisor_id    Advisor post ID for manual association lookup
 * @param int      $per_page      Posts per page
 * @param int      $offset        Query offset
 * @return array WP_Query arguments
 */
function esop_advisor_build_posts_query_args( $category_info, $author_id, $per_page, $offset = 0, $advisor_id = 0 ) {
	$query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'offset'         => $offset,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	// Build post selection criteria (author OR manual association)
	if ( ! empty( $author_id ) && ! empty( $advisor_id ) ) {
		// Both author-based and manually associated posts
		// Get post IDs associated with this advisor
		$associated_posts = get_posts( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => '_esop_associated_advisor',
					'value' => intval( $advisor_id ),
				),
			),
		) );

		// Get author's posts
		$author_posts = get_posts( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'author'         => intval( $author_id ),
		) );

		// Merge and deduplicate
		$all_post_ids = array_unique( array_merge( $associated_posts, $author_posts ) );

		if ( empty( $all_post_ids ) ) {
			$query_args['post__in'] = array( 0 ); // No posts found
		} else {
			$query_args['post__in'] = $all_post_ids;
		}
	} elseif ( ! empty( $author_id ) ) {
		// Only author-based posts
		$query_args['author'] = intval( $author_id );
	} elseif ( ! empty( $advisor_id ) ) {
		// Only manually associated posts
		$query_args['meta_query'] = array(
			array(
				'key'   => '_esop_associated_advisor',
				'value' => intval( $advisor_id ),
			),
		);
	} else {
		// No criteria - return empty
		$query_args['post__in'] = array( 0 );
	}

	// Category filtering
	if ( $category_info['type'] === 'include' ) {
		$query_args['category_name'] = $category_info['slug'];
	} elseif ( ! empty( $category_info['exclude_ids'] ) ) {
		// Use pre-cached exclude IDs for blog section
		$query_args['category__not_in'] = $category_info['exclude_ids'];
	}

	return $query_args;
}

/**
 * Register all advisor post shortcodes
 */
add_action( 'init', 'esop_advisor_register_post_shortcodes' );

function esop_advisor_register_post_shortcodes() {
	add_shortcode( 'esop_advisor_case_studies', 'esop_advisor_posts_shortcode' );
	add_shortcode( 'esop_advisor_articles', 'esop_advisor_posts_shortcode' );
	add_shortcode( 'esop_advisor_ratings', 'esop_advisor_posts_shortcode' );
	add_shortcode( 'esop_advisor_blog', 'esop_advisor_posts_shortcode' );
}

/**
 * Unified shortcode handler for all post sections
 *
 * Debug info logged when ESOP_ADVISOR_DEBUG is true:
 * - Shortcode tag and attributes
 * - Advisor ID resolution (get_the_ID vs global $post)
 * - Category config lookup
 * - Query args and results
 * - Output buffer levels
 * - Final output length
 */
function esop_advisor_posts_shortcode( $atts, $content, $tag ) {
	global $post;

	// Debug: Log entry into shortcode
	esop_advisor_debug_log( "=== SHORTCODE START: {$tag} ===" );
	esop_advisor_debug_log( "posts_shortcode: output buffer level at start", ob_get_level() );

	// Debug: Log context information
	esop_advisor_debug_log( "posts_shortcode: context", array(
		'tag'            => $tag,
		'get_the_ID'     => get_the_ID(),
		'global_post_id' => $post ? $post->ID : 'null',
		'global_post_type' => $post ? $post->post_type : 'null',
		'is_singular'    => is_singular() ? 'yes' : 'no',
		'current_filter' => current_filter(),
		'did_action_wp'  => did_action( 'wp' ),
		'did_action_wp_head' => did_action( 'wp_head' ),
		'in_the_loop'    => in_the_loop() ? 'yes' : 'no',
	) );

	$category_map  = esop_advisor_get_category_config();
	$category_info = isset( $category_map[ $tag ] ) ? $category_map[ $tag ] : null;

	esop_advisor_debug_log( "posts_shortcode: category_info lookup", array(
		'tag_exists_in_map' => isset( $category_map[ $tag ] ) ? 'yes' : 'no',
		'category_info'     => $category_info,
	) );

	if ( ! $category_info ) {
		esop_advisor_debug_log( "posts_shortcode: EARLY RETURN - no category_info for tag: {$tag}" );
		return '';
	}

	// Parse attributes - use esop_advisor_get_current_advisor_id() for better Divi compatibility
	$default_advisor_id = esop_advisor_get_current_advisor_id();
	if ( ! $default_advisor_id ) {
		$default_advisor_id = get_the_ID();
	}

	$atts = shortcode_atts( array(
		'posts_per_load' => 3,
		'show_title'     => 'false',
		'advisor_id'     => $default_advisor_id,
		'layout'         => 'default',
	), $atts, $tag );

	$posts_per_load = intval( $atts['posts_per_load'] );
	$advisor_id     = intval( $atts['advisor_id'] );
	$show_title     = $atts['show_title'] === 'true';
	$layout         = sanitize_key( $atts['layout'] );

	esop_advisor_debug_log( "posts_shortcode: parsed attributes", array(
		'posts_per_load'     => $posts_per_load,
		'advisor_id'         => $advisor_id,
		'default_advisor_id' => $default_advisor_id,
		'show_title'         => $show_title ? 'true' : 'false',
		'layout'             => $layout,
	) );

	// Validate advisor_id - must be an esop_advisor post
	if ( $advisor_id && get_post_type( $advisor_id ) !== 'esop_advisor' ) {
		esop_advisor_debug_log( "posts_shortcode: WARNING - advisor_id {$advisor_id} is not an esop_advisor post, type: " . get_post_type( $advisor_id ) );
	}

	// Map layout to CSS class
	$layout_classes = array(
		'default'    => 'esop-posts-default',
		'horizontal' => 'esop-posts-horizontal',
		'grid'       => 'esop-posts-grid-layout',
	);
	$layout_class = isset( $layout_classes[ $layout ] ) ? $layout_classes[ $layout ] : 'esop-posts-default';

	// Get linked user ID (may be empty if posts are manually associated)
	$author_id = get_post_meta( $advisor_id, '_esop_advisor_user_id', true );

	esop_advisor_debug_log( "posts_shortcode: author lookup", array(
		'advisor_id' => $advisor_id,
		'author_id'  => $author_id ? $author_id : 'none (manual association only)',
	) );

	// Build query args using shared helper (supports both author and manual association)
	$query_args  = esop_advisor_build_posts_query_args( $category_info, $author_id, $posts_per_load, 0, $advisor_id );

	esop_advisor_debug_log( "posts_shortcode: query args", $query_args );

	$posts_query = new WP_Query( $query_args );

	esop_advisor_debug_log( "posts_shortcode: query results", array(
		'found_posts'   => $posts_query->found_posts,
		'post_count'    => $posts_query->post_count,
		'have_posts'    => $posts_query->have_posts() ? 'yes' : 'no',
		'request'       => $posts_query->request,
	) );

	// Check if there are any posts
	if ( ! $posts_query->have_posts() ) {
		esop_advisor_debug_log( "posts_shortcode: EARLY RETURN - no posts found" );
		return '';
	}

	// Ensure frontend styles are loaded
	if ( ! did_action( 'wp_head' ) ) {
		add_action( 'wp_head', 'esop_advisor_frontend_styles' );
	} else {
		esop_advisor_frontend_styles();
	}

	// Enqueue the AJAX script
	esop_advisor_enqueue_posts_scripts();

	// Calculate if there are more posts
	$total_posts = $posts_query->found_posts;
	$has_more    = $total_posts > $posts_per_load;

	// Generate unique ID for this section
	$section_id = 'esop-posts-' . wp_rand( 1000, 9999 );

	// Start output
	ob_start();
	?>
	<div class="esop-posts-section <?php echo esc_attr( $layout_class ); ?>" id="<?php echo esc_attr( $section_id ); ?>" data-category="<?php echo esc_attr( $tag ); ?>">

		<?php if ( $show_title ) : ?>
			<h3 class="esop-posts-section__title"><?php echo esc_html( $category_info['title'] ); ?></h3>
		<?php endif; ?>

		<div class="esop-posts-grid">
			<?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
				<?php echo esop_advisor_render_post_card( get_the_ID() ); ?>
			<?php endwhile; ?>
		</div>

		<?php if ( $has_more ) : ?>
			<button type="button"
			        class="esop-load-more-btn"
			        data-category="<?php echo esc_attr( $tag ); ?>"
			        data-offset="<?php echo esc_attr( $posts_per_load ); ?>"
			        data-per-page="<?php echo esc_attr( $posts_per_load ); ?>"
			        data-author-id="<?php echo esc_attr( $author_id ); ?>"
			        data-advisor-id="<?php echo esc_attr( $advisor_id ); ?>"
			        data-nonce="<?php echo esc_attr( wp_create_nonce( 'esop_advisor_posts_nonce' ) ); ?>">
				<?php esc_html_e( 'View More', 'esop-advisor' ); ?>
			</button>
		<?php endif; ?>

	</div>
	<?php
	wp_reset_postdata();

	$output = ob_get_clean();

	// Debug: Log output details
	esop_advisor_debug_log( "posts_shortcode: output generated", array(
		'output_length'    => strlen( $output ),
		'output_preview'   => substr( $output, 0, 200 ) . '...',
		'ob_level_after'   => ob_get_level(),
		'contains_html'    => strpos( $output, '<div' ) !== false ? 'yes' : 'no',
	) );
	esop_advisor_debug_log( "=== SHORTCODE END: {$tag} ===" );

	// Wrap output with debug marker if debug mode enabled
	if ( ESOP_ADVISOR_DEBUG && ! empty( $output ) ) {
		$output = "<!-- ESOP_SHORTCODE_START:{$tag} -->" . $output . "<!-- ESOP_SHORTCODE_END:{$tag} -->";
	}

	return $output;
}

/**
 * Render a single post card
 */
function esop_advisor_render_post_card( $post_id ) {
	$title     = get_the_title( $post_id );
	$permalink = get_permalink( $post_id );
	$excerpt   = get_the_excerpt( $post_id );
	$has_image = has_post_thumbnail( $post_id );

	// Arrow icon SVG
	$arrow_icon = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>';

	// Document icon for no-image state
	$doc_icon = '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>';

	ob_start();
	?>
	<div class="esop-post-card">
		<?php if ( $has_image ) : ?>
			<div class="esop-post-card__image">
				<a href="<?php echo esc_url( $permalink ); ?>">
					<?php echo get_the_post_thumbnail( $post_id, 'medium', array( 'loading' => 'lazy' ) ); ?>
				</a>
			</div>
		<?php else : ?>
			<div class="esop-post-card__no-image">
				<?php echo $doc_icon; ?>
			</div>
		<?php endif; ?>

		<div class="esop-post-card__content">
			<h4 class="esop-post-card__title">
				<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
			</h4>
			<div class="esop-post-card__excerpt">
				<?php echo esc_html( wp_trim_words( $excerpt, 25, '...' ) ); ?>
			</div>
			<a href="<?php echo esc_url( $permalink ); ?>" class="esop-post-card__link">
				<?php esc_html_e( 'Read More', 'esop-advisor' ); ?>
				<?php echo $arrow_icon; ?>
			</a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * AJAX handler for loading more posts
 */
add_action( 'wp_ajax_esop_load_more_posts', 'esop_advisor_ajax_load_more_posts' );
add_action( 'wp_ajax_nopriv_esop_load_more_posts', 'esop_advisor_ajax_load_more_posts' );

function esop_advisor_ajax_load_more_posts() {
	// Verify nonce
	if ( ! check_ajax_referer( 'esop_advisor_posts_nonce', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}

	// Get parameters
	$category_tag = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
	$offset       = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;
	$per_page     = isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 3;
	$author_id    = isset( $_POST['author_id'] ) ? intval( $_POST['author_id'] ) : 0;
	$advisor_id   = isset( $_POST['advisor_id'] ) ? intval( $_POST['advisor_id'] ) : 0;

	// Validate
	if ( $per_page < 1 || $per_page > 20 ) {
		$per_page = 3;
	}

	// Use shared category config (with cached category IDs)
	$category_map  = esop_advisor_get_category_config();
	$category_info = isset( $category_map[ $category_tag ] ) ? $category_map[ $category_tag ] : null;

	if ( ! $category_info || ( $author_id < 1 && $advisor_id < 1 ) ) {
		wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
	}

	// Build query using shared helper (supports both author and manual association)
	$query_args  = esop_advisor_build_posts_query_args( $category_info, $author_id, $per_page, $offset, $advisor_id );
	$posts_query = new WP_Query( $query_args );

	// Build HTML
	$html = '';
	if ( $posts_query->have_posts() ) {
		while ( $posts_query->have_posts() ) {
			$posts_query->the_post();
			$html .= esop_advisor_render_post_card( get_the_ID() );
		}
	}
	wp_reset_postdata();

	// Calculate if more posts exist
	$total_posts  = $posts_query->found_posts;
	$loaded_count = $offset + $per_page;
	$has_more     = $loaded_count < $total_posts;

	wp_send_json_success( array(
		'html'     => $html,
		'has_more' => $has_more,
	) );
}

/**
 * Enqueue scripts for posts AJAX functionality
 */
function esop_advisor_enqueue_posts_scripts() {
	static $already_enqueued = false;
	if ( $already_enqueued ) {
		return;
	}
	$already_enqueued = true;

	// Add inline script to footer
	add_action( 'wp_footer', 'esop_advisor_posts_inline_script', 100 );
}

/**
 * Output inline JavaScript for AJAX load more
 */
function esop_advisor_posts_inline_script() {
	?>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.esop-load-more-btn').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var button = this;
				var container = button.closest('.esop-posts-section');
				var postsContainer = container.querySelector('.esop-posts-grid');
				var category = button.dataset.category;
				var offset = parseInt(button.dataset.offset, 10);
				var perPage = parseInt(button.dataset.perPage, 10);
				var authorId = parseInt(button.dataset.authorId, 10) || 0;
				var advisorId = parseInt(button.dataset.advisorId, 10) || 0;
				var nonce = button.dataset.nonce;

				// Disable button and show loading
				button.textContent = '<?php echo esc_js( __( 'Loading...', 'esop-advisor' ) ); ?>';
				button.disabled = true;

				// Make AJAX request
				fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: new URLSearchParams({
						action: 'esop_load_more_posts',
						nonce: nonce,
						category: category,
						offset: offset,
						per_page: perPage,
						author_id: authorId,
						advisor_id: advisorId
					})
				})
				.then(function(response) {
					return response.json();
				})
				.then(function(data) {
					if (data.success) {
						// Append new posts
						postsContainer.insertAdjacentHTML('beforeend', data.data.html);

						// Update offset
						button.dataset.offset = offset + perPage;

						// Hide button if no more posts
						if (!data.data.has_more) {
							button.style.display = 'none';
						} else {
							button.textContent = '<?php echo esc_js( __( 'View More', 'esop-advisor' ) ); ?>';
							button.disabled = false;
						}
					} else {
						console.error('ESOP Posts Error:', data.data.message);
						button.textContent = '<?php echo esc_js( __( 'Error - Try Again', 'esop-advisor' ) ); ?>';
						button.disabled = false;
					}
				})
				.catch(function(error) {
					console.error('ESOP Posts Fetch Error:', error);
					button.textContent = '<?php echo esc_js( __( 'Error - Try Again', 'esop-advisor' ) ); ?>';
					button.disabled = false;
				});
			});
		});
	});
	</script>
	<?php
}

// ============================================================================
// HELPER FUNCTIONS FOR DIVI TEMPLATES
// ============================================================================

/**
 * Get advisor field value by meta key
 *
 * @param int|null $post_id  Advisor post ID (uses current post if null)
 * @param string   $field    Field name without prefix (e.g., 'company', 'bio')
 * @return mixed             Field value or empty string
 */
function esop_get_advisor_field( $post_id = null, $field = '' ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}

	if ( empty( $field ) ) {
		return '';
	}

	$meta_key = '_esop_advisor_' . sanitize_key( $field );
	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Echo advisor field with proper escaping
 * Rich text fields output with wp_kses_post, others with esc_html
 *
 * @param int|null $post_id  Advisor post ID
 * @param string   $field    Field name without prefix
 */
function esop_the_advisor_field( $post_id = null, $field = '' ) {
	$value = esop_get_advisor_field( $post_id, $field );

	if ( empty( $value ) ) {
		return;
	}

	// Rich text fields - allow HTML
	$rich_text_fields = array( 'bio', 'education', 'videos' );

	if ( in_array( $field, $rich_text_fields, true ) ) {
		echo wp_kses_post( $value );
	} else {
		echo esc_html( $value );
	}
}

/**
 * Get advisor's linked WordPress user ID
 *
 * @param int|null $advisor_id  Advisor post ID
 * @return int|string           User ID or empty string
 */
function esop_get_advisor_user_id( $advisor_id = null ) {
	if ( null === $advisor_id ) {
		$advisor_id = get_the_ID();
	}

	return get_post_meta( $advisor_id, '_esop_advisor_user_id', true );
}

/**
 * Check if advisor has any posts in a category
 *
 * @param int|null $advisor_id  Advisor post ID
 * @param string   $category    Category slug (empty for all posts)
 * @return bool
 */
function esop_advisor_has_posts_in_category( $advisor_id = null, $category = '' ) {
	$user_id = esop_get_advisor_user_id( $advisor_id );

	if ( empty( $user_id ) ) {
		return false;
	}

	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'author'         => intval( $user_id ),
		'posts_per_page' => 1,
		'fields'         => 'ids',
	);

	if ( ! empty( $category ) ) {
		$args['category_name'] = $category;
	}

	$query = new WP_Query( $args );

	return $query->have_posts();
}

/**
 * Get advisor's linked WordPress user object
 *
 * @param int|null $advisor_id  Advisor post ID
 * @return WP_User|false        User object or false
 */
function esop_get_advisor_user( $advisor_id = null ) {
	$user_id = esop_get_advisor_user_id( $advisor_id );

	if ( empty( $user_id ) ) {
		return false;
	}

	return get_user_by( 'id', $user_id );
}

/**
 * Get current advisor post ID - works in Theme Builder and regular contexts
 *
 * This is more reliable than get_the_ID() or is_singular() inside Divi module context
 *
 * @return int|false Post ID if advisor, false otherwise
 */
function esop_advisor_get_current_advisor_id() {
	global $post;

	// Check global $post first
	if ( $post && isset( $post->post_type ) && $post->post_type === 'esop_advisor' ) {
		return $post->ID;
	}

	// Check queried object
	$queried = get_queried_object();
	if ( $queried && isset( $queried->post_type ) && $queried->post_type === 'esop_advisor' ) {
		return $queried->ID;
	}

	// Fallback to get_the_ID() if we're in a loop
	$current_id = get_the_ID();
	if ( $current_id && get_post_type( $current_id ) === 'esop_advisor' ) {
		return $current_id;
	}

	return false;
}

// ============================================================================
// FIELD SHORTCODES
// ============================================================================

/**
 * Register all field shortcodes
 */
add_action( 'init', 'esop_advisor_register_field_shortcodes' );

function esop_advisor_register_field_shortcodes() {
	// Universal field shortcode
	add_shortcode( 'esop_advisor_field', 'esop_advisor_field_shortcode' );

	// Individual field shortcodes
	$fields = array(
		'esop_name',
		'esop_company',
		'esop_title',
		'esop_address',
		'esop_city',
		'esop_state',
		'esop_zip',
		'esop_phone',
		'esop_cell',
		'esop_fax',
		'esop_email',
		'esop_website',
		'esop_about_url',
		'esop_services_url',
		'esop_linkedin',
		'esop_bio',
		'esop_education',
		'esop_videos',
		'esop_location',
		'esop_full_address',
	);

	foreach ( $fields as $shortcode ) {
		add_shortcode( $shortcode, 'esop_advisor_individual_field_shortcode' );
	}
}

/**
 * Universal field shortcode handler
 *
 * Usage: [esop_advisor_field field="company" post_id="123" link="url" text="Custom Text"]
 *
 * @param array $atts Shortcode attributes
 * @return string
 */
function esop_advisor_field_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'field'   => '',
		'post_id' => '',
		'link'    => '',
		'text'    => '',
		'target'  => '',
		'before'  => '',
		'after'   => '',
		'empty'   => '',
	), $atts, 'esop_advisor_field' );

	if ( empty( $atts['field'] ) ) {
		return '';
	}

	return esop_advisor_render_field( $atts['field'], $atts );
}

/**
 * Individual field shortcode handler
 *
 * Determines field name from shortcode tag and delegates to renderer
 *
 * @param array  $atts Shortcode attributes
 * @param string $content Shortcode content (unused)
 * @param string $tag Shortcode tag name
 * @return string
 */
function esop_advisor_individual_field_shortcode( $atts, $content, $tag ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'link'    => '',
		'text'    => '',
		'target'  => '',
		'before'  => '',
		'after'   => '',
		'empty'   => '',
	), $atts, $tag );

	// Extract field name from shortcode tag (e.g., 'esop_company' -> 'company')
	$field = str_replace( 'esop_', '', $tag );

	return esop_advisor_render_field( $field, $atts );
}

/**
 * Render a field value with optional link wrapping
 *
 * CRITICAL: URL fields return raw URLs by default for Divi Link field compatibility.
 * Only wrap in HTML when `link` attribute is explicitly set.
 *
 * @param string $field Field name without prefix
 * @param array  $atts  Shortcode attributes
 * @return string
 */
function esop_advisor_render_field( $field, $atts ) {
	// Determine post ID - use explicit post_id attribute, or detect advisor context
	if ( ! empty( $atts['post_id'] ) ) {
		$post_id = intval( $atts['post_id'] );
	} else {
		// Try multiple methods to get the advisor post ID
		$post_id = esop_advisor_get_current_advisor_id();
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
	}

	// Verify we have a valid post
	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return '';
	}

	// Field type definitions
	$rich_text_fields = array( 'bio', 'education', 'videos' );
	$url_fields       = array( 'website', 'about_url', 'services_url', 'linkedin' );

	// Get field value based on field type
	$value = esop_advisor_get_field_value( $field, $post_id );

	// Handle empty values
	if ( $value === '' || $value === null ) {
		if ( $atts['empty'] !== '' ) {
			return esc_html( $atts['empty'] );
		}
		return '';
	}

	// Rich text fields: output with wpautop + wp_kses_post to preserve formatting
	if ( in_array( $field, $rich_text_fields, true ) ) {
		// Apply wpautop to convert line breaks to paragraphs, then sanitize
		$output = wp_kses_post( wpautop( $value ) );
		return $atts['before'] . $output . $atts['after'];
	}

	// URL fields: use esc_url() for raw output (Divi Link field compatibility)
	if ( in_array( $field, $url_fields, true ) ) {
		$escaped_value = esc_url( $value );

		// If no link attribute, return raw URL (for Divi Link fields)
		if ( empty( $atts['link'] ) ) {
			return $atts['before'] . $escaped_value . $atts['after'];
		}

		// With link attribute, wrap in anchor tag
		$link_text = ! empty( $atts['text'] ) ? esc_html( $atts['text'] ) : $escaped_value;
		$output    = esop_advisor_wrap_with_link( $escaped_value, $link_text, $atts, $field, $post_id );
		return $atts['before'] . $output . $atts['after'];
	}

	// Plain text fields: use esc_html()
	$escaped_value = esc_html( $value );

	// Determine link text
	$link_text = ! empty( $atts['text'] ) ? esc_html( $atts['text'] ) : $escaped_value;

	// Handle link wrapping (only if link attribute is set)
	$output = esop_advisor_wrap_with_link( $escaped_value, $link_text, $atts, $field, $post_id );

	return $atts['before'] . $output . $atts['after'];
}

/**
 * Get the value for a specific field
 *
 * Handles special composite fields like 'location' and 'full_address'
 *
 * @param string $field   Field name
 * @param int    $post_id Post ID
 * @return string
 */
function esop_advisor_get_field_value( $field, $post_id ) {
	switch ( $field ) {
		case 'name':
			return get_the_title( $post_id );

		case 'location':
			$city  = esop_get_advisor_field( $post_id, 'city' );
			$state = esop_get_advisor_field( $post_id, 'state' );
			if ( empty( $city ) && empty( $state ) ) {
				return '';
			}
			return implode( ', ', array_filter( array( $city, $state ) ) );

		case 'full_address':
			$address = esop_get_advisor_field( $post_id, 'address' );
			$city    = esop_get_advisor_field( $post_id, 'city' );
			$state   = esop_get_advisor_field( $post_id, 'state' );
			$zip     = esop_get_advisor_field( $post_id, 'zip' );
			if ( empty( $address ) && empty( $city ) && empty( $state ) && empty( $zip ) ) {
				return '';
			}
			// Build: "123 Main St, City, ST 12345"
			$parts      = array();
			$parts[]    = $address;
			$city_state = implode( ', ', array_filter( array( $city, $state ) ) );
			if ( ! empty( $city_state ) && ! empty( $zip ) ) {
				$parts[] = $city_state . ' ' . $zip;
			} elseif ( ! empty( $city_state ) ) {
				$parts[] = $city_state;
			} elseif ( ! empty( $zip ) ) {
				$parts[] = $zip;
			}
			return implode( ', ', array_filter( $parts ) );

		default:
			return esop_get_advisor_field( $post_id, $field );
	}
}

/**
 * Wrap value with appropriate link based on link type
 *
 * @param string $value     Escaped field value
 * @param string $link_text Link display text
 * @param array  $atts      Shortcode attributes
 * @param string $field     Field name (for map link building)
 * @param int    $post_id   Post ID (for map link building)
 * @return string
 */
function esop_advisor_wrap_with_link( $value, $link_text, $atts, $field, $post_id ) {
	if ( empty( $atts['link'] ) ) {
		return $value;
	}

	$link_type = strtolower( $atts['link'] );
	$target    = '';
	$rel       = '';
	$href      = '';

	switch ( $link_type ) {
		case 'tel':
			// Clean phone number: keep only digits and +
			$clean_phone = preg_replace( '/[^0-9+]/', '', $value );
			if ( empty( $clean_phone ) ) {
				return $value;
			}
			$href = 'tel:' . $clean_phone;
			break;

		case 'mailto':
			if ( empty( $value ) || ! is_email( wp_specialchars_decode( $value ) ) ) {
				return $value;
			}
			$href = 'mailto:' . $value;
			break;

		case 'url':
			$url = wp_specialchars_decode( $value );
			// Auto-prepend https:// if no protocol
			if ( ! preg_match( '~^https?://~i', $url ) ) {
				$url = 'https://' . $url;
			}
			$href   = esc_url( $url );
			$target = ! empty( $atts['target'] ) ? $atts['target'] : '_blank';
			$rel    = 'noopener noreferrer';
			break;

		case 'map':
			// Build full address for Google Maps
			$map_address = esop_advisor_build_map_address( $field, $value, $post_id );
			if ( empty( $map_address ) ) {
				return $value;
			}
			$href   = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $map_address );
			$target = ! empty( $atts['target'] ) ? $atts['target'] : '_blank';
			$rel    = 'noopener noreferrer';
			break;

		default:
			return $value;
	}

	if ( empty( $href ) ) {
		return $value;
	}

	// Build link attributes
	$link_atts = 'href="' . esc_attr( $href ) . '"';
	if ( ! empty( $target ) ) {
		$link_atts .= ' target="' . esc_attr( $target ) . '"';
	}
	if ( ! empty( $rel ) ) {
		$link_atts .= ' rel="' . esc_attr( $rel ) . '"';
	}

	return '<a ' . $link_atts . '>' . $link_text . '</a>';
}

/**
 * Build full address string for Google Maps link
 *
 * @param string $field   Current field name
 * @param string $value   Current field value (already escaped)
 * @param int    $post_id Post ID
 * @return string
 */
function esop_advisor_build_map_address( $field, $value, $post_id ) {
	// For full_address field, the value already contains the combined address
	if ( $field === 'full_address' ) {
		return wp_specialchars_decode( $value );
	}

	// For address field or others, build full address from components
	$address = esop_get_advisor_field( $post_id, 'address' );
	$city    = esop_get_advisor_field( $post_id, 'city' );
	$state   = esop_get_advisor_field( $post_id, 'state' );
	$zip     = esop_get_advisor_field( $post_id, 'zip' );

	return trim( implode( ', ', array_filter( array( $address, $city, $state, $zip ) ) ) );
}

// ============================================================================
// ROW SHORTCODES - Conditional display with labels and icons
// ============================================================================

/**
 * Get inline SVG icon for row shortcodes
 *
 * @param string $icon Icon name (phone, cell, fax, link)
 * @return string SVG markup
 */
function esop_advisor_get_row_icon( $icon ) {
	$icons = array(
		// Phone icon - simple filled style matching Divi/ETmodules
		'phone' => '<svg class="esop-row-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#666"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>',
		// Mobile/Cell icon - simple filled style
		'cell'  => '<svg class="esop-row-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#666"><path d="M16 1H8C6.34 1 5 2.34 5 4v16c0 1.66 1.34 3 3 3h8c1.66 0 3-1.34 3-3V4c0-1.66-1.34-3-3-3zm-2 20h-4v-1h4v1zm3.25-3H6.75V4h10.5v14z"/></svg>',
		// Fax/Print icon - simple filled style
		'fax'   => '<svg class="esop-row-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#666"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>',
		// External link icon - orange for resources
		'link'  => '<svg class="esop-row-icon esop-row-icon--link" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e35205"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Sanitize phone number for tel: links
 *
 * @param string $phone Phone number
 * @return string Cleaned phone number (digits and + only)
 */
function esop_advisor_sanitize_phone_for_tel( $phone ) {
	return preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Register row shortcodes
 */
add_action( 'init', 'esop_advisor_register_row_shortcodes' );

function esop_advisor_register_row_shortcodes() {
	// Raw email shortcodes (for Divi button URLs)
	add_shortcode( 'esop_email_raw', 'esop_advisor_email_raw_shortcode' );
	add_shortcode( 'esop_email_only', 'esop_advisor_email_raw_shortcode' );

	// Row shortcodes with labels/icons
	add_shortcode( 'esop_company_row', 'esop_advisor_company_row_shortcode' );
	add_shortcode( 'esop_title_row', 'esop_advisor_title_row_shortcode' );
	add_shortcode( 'esop_email_row', 'esop_advisor_email_row_shortcode' );
	add_shortcode( 'esop_phone_row', 'esop_advisor_phone_row_shortcode' );
	add_shortcode( 'esop_cell_row', 'esop_advisor_cell_row_shortcode' );
	add_shortcode( 'esop_fax_row', 'esop_advisor_fax_row_shortcode' );
	add_shortcode( 'esop_website_row', 'esop_advisor_website_row_shortcode' );
	add_shortcode( 'esop_about_row', 'esop_advisor_about_row_shortcode' );
	add_shortcode( 'esop_services_row', 'esop_advisor_services_row_shortcode' );
	add_shortcode( 'esop_address_block', 'esop_advisor_address_block_shortcode' );

	// Contact button shortcode
	add_shortcode( 'esop_contact_button', 'esop_advisor_contact_button_shortcode' );
}

/**
 * Get advisor post ID from shortcode attributes or context
 *
 * @param array $atts Shortcode attributes
 * @return int|false Post ID or false if not found/invalid
 */
function esop_advisor_get_row_post_id( $atts ) {
	if ( ! empty( $atts['post_id'] ) ) {
		$post_id = intval( $atts['post_id'] );
	} else {
		$post_id = esop_advisor_get_current_advisor_id();
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
	}

	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return false;
	}

	return $post_id;
}

/**
 * Raw email shortcode - outputs just the email address with no formatting
 *
 * Usage: [esop_email_raw] or [esop_email_only]
 * For use in Divi button URL fields: mailto:[esop_email_raw]
 */
function esop_advisor_email_raw_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$email = esop_get_advisor_field( $post_id, 'email' );
	if ( empty( $email ) ) {
		return '';
	}

	return sanitize_email( $email );
}

/**
 * Company row shortcode
 *
 * Output: <span class="esop-row"><strong>Company:</strong> {value}</span>
 */
function esop_advisor_company_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$company = esop_get_advisor_field( $post_id, 'company' );
	if ( empty( $company ) ) {
		return '';
	}

	return '<div class="esop-row esop-row--company"><strong>Company:</strong> ' . esc_html( $company ) . '</div>';
}

/**
 * Title row shortcode
 *
 * Output: <span class="esop-row"><strong>Title:</strong> {value}</span>
 */
function esop_advisor_title_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$title = esop_get_advisor_field( $post_id, 'title' );
	if ( empty( $title ) ) {
		return '';
	}

	return '<div class="esop-row esop-row--title"><strong>Title:</strong> ' . esc_html( $title ) . '</div>';
}

/**
 * Email row shortcode
 *
 * Output: <span class="esop-row"><strong>Email:</strong> <a href="mailto:{email}">{email}</a></span>
 */
function esop_advisor_email_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$email = esop_get_advisor_field( $post_id, 'email' );
	if ( empty( $email ) ) {
		return '';
	}

	$sanitized_email = sanitize_email( $email );
	return '<div class="esop-row esop-row--email"><strong>Email:</strong> <a href="mailto:' . esc_attr( $sanitized_email ) . '">' . esc_html( $sanitized_email ) . '</a></div>';
}

/**
 * Phone row shortcode
 *
 * Output: <p>{icon} <a href="tel:{clean_number}">{display_number}</a></p>
 */
function esop_advisor_phone_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$phone = esop_get_advisor_field( $post_id, 'phone' );
	if ( empty( $phone ) ) {
		return '';
	}

	$clean_phone = esop_advisor_sanitize_phone_for_tel( $phone );
	// Format display: convert periods to dashes
	$display_phone = str_replace( '.', '-', $phone );
	$icon = esop_advisor_get_row_icon( 'phone' );

	return '<div class="esop-row esop-row--phone">' . $icon . '<a href="tel:' . esc_attr( $clean_phone ) . '">' . esc_html( $display_phone ) . '</a></div>';
}

/**
 * Cell row shortcode
 *
 * Output: <p>{icon} <a href="tel:{clean_number}">{display_number}</a></p>
 */
function esop_advisor_cell_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$cell = esop_get_advisor_field( $post_id, 'cell' );
	if ( empty( $cell ) ) {
		return '';
	}

	$clean_cell = esop_advisor_sanitize_phone_for_tel( $cell );
	// Format display: convert periods to dashes
	$display_cell = str_replace( '.', '-', $cell );
	$icon = esop_advisor_get_row_icon( 'cell' );

	return '<div class="esop-row esop-row--cell">' . $icon . '<a href="tel:' . esc_attr( $clean_cell ) . '">' . esc_html( $display_cell ) . '</a></div>';
}

/**
 * Fax row shortcode
 *
 * Output: <p>{icon} {fax_number}</p> (NOT clickable)
 */
function esop_advisor_fax_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$fax = esop_get_advisor_field( $post_id, 'fax' );
	if ( empty( $fax ) ) {
		return '';
	}

	// Format display: convert periods to dashes
	$display_fax = str_replace( '.', '-', $fax );
	$icon = esop_advisor_get_row_icon( 'fax' );

	return '<div class="esop-row esop-row--fax">' . $icon . '<span>' . esc_html( $display_fax ) . '</span></div>';
}

/**
 * Website row shortcode
 *
 * Output: <p>{icon} <a href="{url}" target="_blank">Our Home Page</a></p>
 */
function esop_advisor_website_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'text'    => 'Our Home Page',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$website = esop_get_advisor_field( $post_id, 'website' );
	if ( empty( $website ) ) {
		return '';
	}

	$icon = esop_advisor_get_row_icon( 'link' );

	return '<div class="esop-row esop-row--website">' . $icon . '<a href="' . esc_url( $website ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['text'] ) . '</a></div>';
}

/**
 * About row shortcode
 *
 * Output: <p>{icon} <a href="{url}" target="_blank">About Us</a></p>
 */
function esop_advisor_about_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'text'    => 'About Us',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$about_url = esop_get_advisor_field( $post_id, 'about_url' );
	if ( empty( $about_url ) ) {
		return '';
	}

	$icon = esop_advisor_get_row_icon( 'link' );

	return '<div class="esop-row esop-row--about">' . $icon . '<a href="' . esc_url( $about_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['text'] ) . '</a></div>';
}

/**
 * Services row shortcode
 *
 * Output: <p>{icon} <a href="{url}" target="_blank">Our Services</a></p>
 */
function esop_advisor_services_row_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'text'    => 'Our Services',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$services_url = esop_get_advisor_field( $post_id, 'services_url' );
	if ( empty( $services_url ) ) {
		return '';
	}

	$icon = esop_advisor_get_row_icon( 'link' );

	return '<div class="esop-row esop-row--services">' . $icon . '<a href="' . esc_url( $services_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['text'] ) . '</a></div>';
}

/**
 * Address block shortcode
 *
 * Output: {street_address}<br>{city}, {state} {zip}
 * Returns empty string only if BOTH address AND city are empty
 */
function esop_advisor_address_block_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$address = esop_get_advisor_field( $post_id, 'address' );
	$city    = esop_get_advisor_field( $post_id, 'city' );
	$state   = esop_get_advisor_field( $post_id, 'state' );
	$zip     = esop_get_advisor_field( $post_id, 'zip' );

	// Return empty if both address AND city are empty
	if ( empty( $address ) && empty( $city ) ) {
		return '';
	}

	$output = '<div class="esop-address-block">';

	// First line: street address
	if ( ! empty( $address ) ) {
		$output .= esc_html( $address );
	}

	// Second line: city, state zip
	$city_line = '';
	if ( ! empty( $city ) ) {
		$city_line = esc_html( $city );
		if ( ! empty( $state ) ) {
			$city_line .= ', ' . esc_html( $state );
		}
	} elseif ( ! empty( $state ) ) {
		$city_line = esc_html( $state );
	}
	if ( ! empty( $zip ) ) {
		$city_line .= ( ! empty( $city_line ) ? ' ' : '' ) . esc_html( $zip );
	}

	if ( ! empty( $city_line ) ) {
		if ( ! empty( $address ) ) {
			$output .= '<br>';
		}
		$output .= $city_line;
	}

	$output .= '</div>';

	return $output;
}

/**
 * Contact button shortcode
 *
 * Output: <a href="mailto:{email}" class="esop-contact-button">Connect with this Advisor</a>
 */
function esop_advisor_contact_button_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'text'    => 'Connect with this Advisor',
		'class'   => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$email = esop_get_advisor_field( $post_id, 'email' );
	if ( empty( $email ) ) {
		return '';
	}

	$class = 'esop-contact-button';
	if ( ! empty( $atts['class'] ) ) {
		$class .= ' ' . sanitize_html_class( $atts['class'] );
	}

	return sprintf(
		'<a href="mailto:%s" class="%s">%s</a>',
		esc_attr( sanitize_email( $email ) ),
		esc_attr( $class ),
		esc_html( $atts['text'] )
	);
}

// ============================================================================
// DIAGNOSTIC SHORTCODE FOR TROUBLESHOOTING
// ============================================================================

/**
 * Diagnostic shortcode to debug post shortcode issues
 * Usage: [esop_advisor_diagnostic]
 *
 * This outputs debug information as an HTML comment (invisible to users)
 * and logs detailed info to error_log when ESOP_ADVISOR_DEBUG is enabled.
 */
add_shortcode( 'esop_advisor_diagnostic', 'esop_advisor_diagnostic_shortcode' );

function esop_advisor_diagnostic_shortcode( $atts ) {
	global $post;

	$advisor_id = esop_advisor_get_current_advisor_id();
	$author_id  = $advisor_id ? get_post_meta( $advisor_id, '_esop_advisor_user_id', true ) : null;

	// Get category config
	$category_map = esop_advisor_get_category_config();

	// Test query for articles
	$test_query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'   => '_esop_associated_advisor',
				'value' => intval( $advisor_id ),
			),
		),
	);
	$associated_posts = get_posts( $test_query_args );

	$diagnostic_data = array(
		'timestamp'       => current_time( 'mysql' ),
		'advisor_id'      => $advisor_id,
		'get_the_ID'      => get_the_ID(),
		'global_post_id'  => $post ? $post->ID : 'null',
		'global_post_type' => $post ? $post->post_type : 'null',
		'queried_object'  => get_queried_object_id(),
		'is_singular'     => is_singular() ? 'yes' : 'no',
		'author_id'       => $author_id,
		'associated_posts' => $associated_posts,
		'associated_count' => count( $associated_posts ),
		'category_map_keys' => array_keys( $category_map ),
		'ob_level'        => ob_get_level(),
		'did_action_wp'   => did_action( 'wp' ),
		'current_filter'  => current_filter(),
		'is_admin'        => is_admin() ? 'yes' : 'no',
	);

	// Always log to error_log if debug is enabled
	esop_advisor_debug_log( 'DIAGNOSTIC SHORTCODE', $diagnostic_data );

	// Return HTML comment with diagnostic data (invisible but viewable in source)
	$output = "\n<!-- ESOP Advisor Diagnostic\n";
	$output .= "Advisor ID: {$diagnostic_data['advisor_id']}\n";
	$output .= "get_the_ID(): {$diagnostic_data['get_the_ID']}\n";
	$output .= "Global \$post ID: {$diagnostic_data['global_post_id']}\n";
	$output .= "Global \$post type: {$diagnostic_data['global_post_type']}\n";
	$output .= "Associated posts count: {$diagnostic_data['associated_count']}\n";
	$output .= "Associated post IDs: " . implode( ', ', $associated_posts ) . "\n";
	$output .= "Output buffer level: {$diagnostic_data['ob_level']}\n";
	$output .= "Current filter: {$diagnostic_data['current_filter']}\n";
	$output .= "-->\n";

	return $output;
}

// ============================================================================
// DIVI THEME BUILDER POST CONTEXT FIX
// ============================================================================

/**
 * Ensure global $post is properly set for Divi Theme Builder templates
 *
 * Divi Theme Builder can process content before the main query is fully set up.
 * This hook ensures the advisor post context is available for our shortcodes.
 */
add_action( 'wp', 'esop_advisor_setup_theme_builder_context', 5 );

function esop_advisor_setup_theme_builder_context() {
	// Only run if Divi is active and we're on a singular advisor page
	if ( ! is_singular( 'esop_advisor' ) ) {
		return;
	}

	global $post;

	// Ensure post is set
	if ( ! $post ) {
		$queried = get_queried_object();
		if ( $queried && $queried instanceof WP_Post && $queried->post_type === 'esop_advisor' ) {
			$post = $queried;
			setup_postdata( $post );
			esop_advisor_debug_log( 'setup_theme_builder_context: set global $post from queried object', $post->ID );
		}
	} else {
		esop_advisor_debug_log( 'setup_theme_builder_context: global $post already set', $post->ID );
	}
}

/**
 * Additional hook for Divi's template rendering
 * Runs before Divi renders the theme builder template body
 */
add_action( 'et_theme_builder_template_before_body', 'esop_advisor_ensure_post_context', 5 );

function esop_advisor_ensure_post_context() {
	global $post;

	$advisor_id = esop_advisor_get_current_advisor_id();
	if ( ! $advisor_id ) {
		return;
	}

	// If global $post doesn't match the advisor, set it up
	if ( ! $post || $post->ID !== $advisor_id ) {
		$advisor_post = get_post( $advisor_id );
		if ( $advisor_post ) {
			$post = $advisor_post;
			setup_postdata( $post );
			esop_advisor_debug_log( 'ensure_post_context: set global $post to advisor', $advisor_id );
		}
	}
}

/**
 * Disable Divi static CSS caching for advisor pages
 * This ensures shortcodes are always processed fresh
 */
add_filter( 'et_builder_enable_dynamic_module_cache', 'esop_advisor_disable_divi_cache', 10 );

function esop_advisor_disable_divi_cache( $enabled ) {
	if ( esop_advisor_get_current_advisor_id() ) {
		esop_advisor_debug_log( 'disable_divi_cache: disabling dynamic module cache for advisor page' );
		return false;
	}
	return $enabled;
}

/**
 * Force Divi to re-render shortcodes by marking content as dynamic
 */
add_filter( 'et_builder_should_wrap_styles', 'esop_advisor_force_dynamic_rendering', 10 );

function esop_advisor_force_dynamic_rendering( $should_wrap ) {
	if ( esop_advisor_get_current_advisor_id() ) {
		return false; // Don't wrap/cache styles for advisor pages
	}
	return $should_wrap;
}

// ============================================================================
// DIVI BLOG INTEGRATION
// ============================================================================

/**
 * Process shortcodes in Divi module props (for URL fields)
 *
 * This allows shortcodes like [esop_website] to work in Divi module Link URL fields.
 * Without this filter, Divi may treat the shortcode as a relative URL.
 */
add_filter( 'et_pb_module_shortcode_attributes', 'esop_advisor_process_divi_shortcodes', 10, 3 );

function esop_advisor_process_divi_shortcodes( $props, $attrs, $render_slug ) {
	// Only process if we have an advisor context
	if ( ! esop_advisor_get_current_advisor_id() ) {
		return $props;
	}

	// Process ALL string props that might contain shortcodes
	foreach ( $props as $key => $value ) {
		if ( is_string( $value ) && strpos( $value, '[esop_' ) !== false ) {
			$props[ $key ] = do_shortcode( $value );
		}
	}

	return $props;
}

/**
 * Process shortcodes in Divi's dynamic content
 */
add_filter( 'et_builder_resolve_dynamic_content', 'esop_advisor_process_divi_dynamic_content', 10, 3 );

function esop_advisor_process_divi_dynamic_content( $value, $name, $settings ) {
	// Process esop shortcodes in dynamic content regardless of context
	// The shortcode itself will validate the post type
	if ( is_string( $value ) && strpos( $value, '[esop_' ) !== false ) {
		$value = do_shortcode( $value );
	}

	return $value;
}

/**
 * Helper function to decode HTML entities in shortcodes
 * Divi sometimes encodes brackets as HTML entities
 */
function esop_advisor_decode_shortcode_entities( $content ) {
	if ( ! is_string( $content ) ) {
		return $content;
	}

	// Decode HTML entities for square brackets
	$content = str_replace(
		array( '&#91;', '&#93;', '&#x5B;', '&#x5D;', '&lsqb;', '&rsqb;', '&lbrack;', '&rbrack;' ),
		array( '[', ']', '[', ']', '[', ']', '[', ']' ),
		$content
	);

	// Also decode URL-encoded brackets
	$content = str_replace(
		array( '%5B', '%5D', '%5b', '%5d' ),
		array( '[', ']', '[', ']' ),
		$content
	);

	return $content;
}

/**
 * Helper function to check if content has ESOP shortcodes (after decoding)
 */
function esop_advisor_has_shortcodes( $content ) {
	if ( ! is_string( $content ) ) {
		return false;
	}
	$decoded = esop_advisor_decode_shortcode_entities( $content );
	return strpos( $decoded, '[esop_' ) !== false;
}

/**
 * Process shortcodes in Divi text module output
 */
add_filter( 'et_pb_text_content', 'esop_advisor_process_text_module_shortcodes', 10, 1 );

function esop_advisor_process_text_module_shortcodes( $content ) {
	// Decode any HTML entities first
	$content = esop_advisor_decode_shortcode_entities( $content );

	// Process esop shortcodes in text module content
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( 'text_module: found shortcode, processing' );
		$content = do_shortcode( $content );
	}

	return $content;
}

/**
 * Process shortcodes in Divi blurb module output
 */
add_filter( 'et_pb_blurb_content', 'esop_advisor_process_blurb_module_shortcodes', 10, 1 );

function esop_advisor_process_blurb_module_shortcodes( $content ) {
	$content = esop_advisor_decode_shortcode_entities( $content );
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( 'blurb_module: found shortcode, processing' );
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * Process shortcodes in Divi Code module output
 * Code modules don't have a specific content filter, so we catch them via et_pb_shortcode_output
 */
add_filter( 'et_pb_code_content', 'esop_advisor_process_code_module_shortcodes', 10, 1 );

function esop_advisor_process_code_module_shortcodes( $content ) {
	$content = esop_advisor_decode_shortcode_entities( $content );
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( 'code_module: found shortcode, processing' );
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * Ensure shortcodes work in the_content for Theme Builder templates
 */
add_filter( 'the_content', 'esop_advisor_process_content_shortcodes', 5 );

function esop_advisor_process_content_shortcodes( $content ) {
	$content = esop_advisor_decode_shortcode_entities( $content );
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( 'the_content: found shortcode, processing' );
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * Process shortcodes in widget text
 */
add_filter( 'widget_text', 'esop_advisor_process_widget_shortcodes', 10, 1 );

function esop_advisor_process_widget_shortcodes( $content ) {
	if ( is_string( $content ) && strpos( $content, '[esop_' ) !== false ) {
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * CRITICAL: Process shortcodes in Divi's rendered module output
 * This catches shortcodes that slip through other filters
 */
add_filter( 'et_pb_shortcode_output', 'esop_advisor_process_module_output', 10, 3 );

function esop_advisor_process_module_output( $output, $render_slug, $module ) {
	if ( ! is_string( $output ) ) {
		return $output;
	}

	// Decode HTML entities first
	$output = esop_advisor_decode_shortcode_entities( $output );

	if ( strpos( $output, '[esop_' ) !== false ) {
		esop_advisor_debug_log( "process_module_output: found shortcode in module", array(
			'render_slug'   => $render_slug,
			'output_length' => strlen( $output ),
			'shortcode_pos' => strpos( $output, '[esop_' ),
		) );
		$output = do_shortcode( $output );
		esop_advisor_debug_log( "process_module_output: after do_shortcode", strlen( $output ) );
	}
	return $output;
}

/**
 * Process shortcodes in Divi Theme Builder body layout
 * This specifically targets the Theme Builder's body area where most advisor content lives
 */
add_filter( 'et_theme_builder_template_after_body', 'esop_advisor_process_theme_builder_body', 10, 1 );
add_filter( 'et_theme_builder_body_layout', 'esop_advisor_process_theme_builder_body', 10, 1 );

function esop_advisor_process_theme_builder_body( $content ) {
	if ( ! is_string( $content ) ) {
		return $content;
	}

	$content = esop_advisor_decode_shortcode_entities( $content );
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( "process_theme_builder_body: found shortcode", strlen( $content ) );
		$content = do_shortcode( $content );
		esop_advisor_debug_log( "process_theme_builder_body: after do_shortcode", strlen( $content ) );
	}
	return $content;
}

/**
 * Process shortcodes in Divi builder content before rendering
 */
add_filter( 'et_pb_builder_post_content_capability', 'esop_advisor_process_builder_content', 10, 1 );

function esop_advisor_process_builder_content( $content ) {
	if ( ! is_string( $content ) ) {
		return $content;
	}
	$content = esop_advisor_decode_shortcode_entities( $content );
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( 'builder_content: found shortcode, processing' );
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * Process shortcodes in Divi row/section/column content
 * These filters catch shortcodes placed directly in layout elements
 */
add_filter( 'et_pb_row_inner_content', 'esop_advisor_process_layout_content', 10, 1 );
add_filter( 'et_pb_section_inner_content', 'esop_advisor_process_layout_content', 10, 1 );
add_filter( 'et_pb_column_inner_content', 'esop_advisor_process_layout_content', 10, 1 );

function esop_advisor_process_layout_content( $content ) {
	if ( ! is_string( $content ) ) {
		return $content;
	}
	$content = esop_advisor_decode_shortcode_entities( $content );
	if ( strpos( $content, '[esop_' ) !== false ) {
		esop_advisor_debug_log( 'layout_content: found shortcode, processing' );
		$content = do_shortcode( $content );
	}
	return $content;
}

/**
 * AGGRESSIVE: Process shortcodes in ALL Divi module content via render filter
 * This runs at priority 999 to be the last filter
 */
add_filter( 'et_module_shortcode_output', 'esop_advisor_process_all_module_output', 999, 3 );

function esop_advisor_process_all_module_output( $output, $render_slug = '', $module = null ) {
	if ( ! is_string( $output ) ) {
		return $output;
	}
	$output = esop_advisor_decode_shortcode_entities( $output );
	if ( strpos( $output, '[esop_' ) !== false ) {
		esop_advisor_debug_log( "all_module_output: found shortcode in {$render_slug}", strlen( $output ) );
		$output = do_shortcode( $output );
	}
	return $output;
}

/**
 * Final fallback: Process any remaining shortcodes in the full page output
 * This runs on template_redirect to process the entire page if needed
 */
add_action( 'template_redirect', 'esop_advisor_setup_output_buffer' );

function esop_advisor_setup_output_buffer() {
	// Check multiple ways if this is an advisor page
	$advisor_id = esop_advisor_get_current_advisor_id();
	$is_advisor_page = $advisor_id || is_singular( 'esop_advisor' );

	// Also check by queried object as fallback
	if ( ! $is_advisor_page ) {
		$queried = get_queried_object();
		if ( $queried && isset( $queried->post_type ) && $queried->post_type === 'esop_advisor' ) {
			$is_advisor_page = true;
			$advisor_id = $queried->ID;
		}
	}

	if ( ! $is_advisor_page ) {
		return;
	}

	esop_advisor_debug_log( "setup_output_buffer: starting buffer for advisor", $advisor_id );
	ob_start( 'esop_advisor_process_final_output' );
}

function esop_advisor_process_final_output( $output ) {
	$original_length = strlen( $output );

	// First, decode ALL HTML entities for square brackets
	$output = esop_advisor_decode_shortcode_entities( $output );

	// Check for shortcodes after decoding
	$has_shortcodes = strpos( $output, '[esop_' ) !== false;

	esop_advisor_debug_log( "process_final_output: received output", array(
		'length'          => $original_length,
		'has_esop_shortcodes' => $has_shortcodes ? 'yes' : 'no',
	) );

	// Process any remaining esop shortcodes in the final output
	if ( $has_shortcodes ) {

		// Handle href attributes that contain ONLY a shortcode (e.g., href="[esop_email]")
		// This needs to run BEFORE general shortcode processing
		$output = preg_replace_callback(
			'/href="(\[esop_([a-z_]+)([^\]]*)\])"/',
			function( $matches ) {
				$shortcode = $matches[1];
				$field = $matches[2];
				$value = do_shortcode( $shortcode );

				// Handle email - add mailto: prefix
				if ( $field === 'email' && is_email( $value ) ) {
					return 'href="mailto:' . esc_attr( $value ) . '"';
				}
				// Handle phone - add tel: prefix
				if ( in_array( $field, array( 'phone', 'cell', 'fax' ), true ) && ! empty( $value ) ) {
					$clean_phone = preg_replace( '/[^0-9+]/', '', $value );
					return 'href="tel:' . esc_attr( $clean_phone ) . '"';
				}
				// Handle URLs
				if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
					return 'href="' . esc_url( $value ) . '"';
				}
				// Default - just use the value
				return 'href="' . esc_attr( $value ) . '"';
			},
			$output
		);

		// Handle href with site URL prepended to shortcode (Divi's relative URL handling)
		// Pattern: href="https://site.com/path/[esop_xxx]" -> extract and use shortcode result
		$output = preg_replace_callback(
			'/href="(https?:\/\/[^"]*)\[esop_([a-z_]+)([^\]]*)\]"/',
			function( $matches ) {
				$field = $matches[2];
				$shortcode = '[esop_' . $field . $matches[3] . ']';
				$value = do_shortcode( $shortcode );

				// Handle email - add mailto: prefix
				if ( $field === 'email' && is_email( $value ) ) {
					return 'href="mailto:' . esc_attr( $value ) . '"';
				}
				// Handle phone - add tel: prefix
				if ( in_array( $field, array( 'phone', 'cell', 'fax' ), true ) && ! empty( $value ) ) {
					$clean_phone = preg_replace( '/[^0-9+]/', '', $value );
					return 'href="tel:' . esc_attr( $clean_phone ) . '"';
				}
				// Handle URLs - use the shortcode result directly
				if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
					return 'href="' . esc_url( $value ) . '"';
				}
				// Default - just use the value
				return 'href="' . esc_attr( $value ) . '"';
			},
			$output
		);

		// Process any remaining shortcodes in general content
		$output = preg_replace_callback(
			'/\[esop_([a-z_]+)([^\]]*)\]/',
			function( $matches ) {
				$shortcode = '[esop_' . $matches[1] . $matches[2] . ']';
				esop_advisor_debug_log( "process_final_output: processing shortcode in final output", $shortcode );
				$result = do_shortcode( $shortcode );
				esop_advisor_debug_log( "process_final_output: shortcode result length", strlen( $result ) );
				return $result;
			},
			$output
		);
	}

	$final_length = strlen( $output );
	esop_advisor_debug_log( "process_final_output: completed", array(
		'original_length' => $original_length,
		'final_length'    => $final_length,
		'change'          => $final_length - $original_length,
	) );

	return $output;
}

/**
 * Ensure output buffer is flushed on shutdown
 */
add_action( 'shutdown', 'esop_advisor_flush_output_buffer', 0 );

function esop_advisor_flush_output_buffer() {
	if ( esop_advisor_get_current_advisor_id() && ob_get_level() > 0 ) {
		ob_end_flush();
	}
}

/**
 * Setup Divi Blog filtering on advisor pages
 */
add_action( 'wp', 'esop_advisor_setup_divi_filtering' );

function esop_advisor_setup_divi_filtering() {
	if ( ! is_singular( 'esop_advisor' ) ) {
		return;
	}

	// Add pre_get_posts filter for Divi Blog modules
	add_filter( 'pre_get_posts', 'esop_advisor_filter_divi_blog_queries' );
}

/**
 * Filter Divi Blog queries to show only current advisor's posts
 *
 * @param WP_Query $query The query object
 */
function esop_advisor_filter_divi_blog_queries( $query ) {
	// Skip admin and main queries
	if ( is_admin() || $query->is_main_query() ) {
		return;
	}

	// Only filter post queries (Divi Blog typically queries 'post')
	$post_type = $query->get( 'post_type' );
	if ( ! empty( $post_type ) && $post_type !== 'post' ) {
		return;
	}

	// Don't override if author is already set
	if ( $query->get( 'author' ) ) {
		return;
	}

	// Get current advisor's linked user ID
	$advisor_id = get_the_ID();
	$user_id    = get_post_meta( $advisor_id, '_esop_advisor_user_id', true );

	if ( ! empty( $user_id ) ) {
		$query->set( 'author', intval( $user_id ) );
	}
}

/**
 * Add body classes for advisor pages
 *
 * @param array $classes Existing body classes
 * @return array Modified body classes
 */
add_filter( 'body_class', 'esop_advisor_body_class' );

function esop_advisor_body_class( $classes ) {
	if ( ! is_singular( 'esop_advisor' ) ) {
		return $classes;
	}

	$classes[] = 'esop-advisor-page';

	// Add user-specific class if advisor has linked user
	$user_id = get_post_meta( get_the_ID(), '_esop_advisor_user_id', true );
	if ( ! empty( $user_id ) ) {
		$classes[] = 'esop-advisor-user-' . intval( $user_id );
	}

	return $classes;
}

// ============================================================================
// DP TESTIMONIALS INTEGRATION
// ============================================================================

/**
 * Register DP Testimonials helper shortcode
 */
add_action( 'init', 'esop_advisor_register_testimonial_shortcode' );

function esop_advisor_register_testimonial_shortcode() {
	add_shortcode( 'esop_advisor_testimonial_category', 'esop_advisor_testimonial_category_shortcode' );
}

/**
 * Shortcode to get advisor's testimonial category
 *
 * Usage: [esop_advisor_testimonial_category format="slug|id|name"]
 *
 * @param array $atts Shortcode attributes
 * @return string Category slug, ID, or name
 */
function esop_advisor_testimonial_category_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'format'  => 'slug',
	), $atts, 'esop_advisor_testimonial_category' );

	$post_id = ! empty( $atts['post_id'] ) ? intval( $atts['post_id'] ) : get_the_ID();
	$format  = sanitize_key( $atts['format'] );

	// Get the testimonial term
	$term = esop_advisor_get_testimonial_term( $post_id );

	if ( ! $term ) {
		return '';
	}

	switch ( $format ) {
		case 'id':
			return (string) $term->term_id;
		case 'name':
			return esc_html( $term->name );
		case 'slug':
		default:
			return esc_attr( $term->slug );
	}
}

/**
 * Get advisor's testimonial category term ID
 *
 * @param int|null $advisor_id Advisor post ID
 * @return int Term ID or 0 if not found
 */
function esop_get_advisor_testimonial_category_id( $advisor_id = null ) {
	$term = esop_advisor_get_testimonial_term( $advisor_id );
	return $term ? $term->term_id : 0;
}

/**
 * Check if advisor has testimonials in their category
 *
 * @param int|null $advisor_id Advisor post ID
 * @return bool True if advisor has testimonials
 */
function esop_advisor_has_testimonials( $advisor_id = null ) {
	$term_id = esop_get_advisor_testimonial_category_id( $advisor_id );

	if ( ! $term_id ) {
		return false;
	}

	// Try different DP Testimonials post types
	$post_types = array( 'dipl-testimonial', 'dp_testimonial', 'testimonial' );

	foreach ( $post_types as $post_type ) {
		if ( ! post_type_exists( $post_type ) ) {
			continue;
		}

		// Get the taxonomy for this post type
		$taxonomy = esop_advisor_get_testimonial_taxonomy();
		if ( ! $taxonomy ) {
			continue;
		}

		$query = new WP_Query( array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'tax_query'      => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
		) );

		if ( $query->have_posts() ) {
			return true;
		}
	}

	return false;
}

/**
 * Get the testimonial term for an advisor
 *
 * Searches by advisor name slug and post slug
 *
 * @param int|null $advisor_id Advisor post ID
 * @return WP_Term|null Term object or null
 */
function esop_advisor_get_testimonial_term( $advisor_id = null ) {
	if ( null === $advisor_id ) {
		$advisor_id = get_the_ID();
	}

	if ( ! $advisor_id || get_post_type( $advisor_id ) !== 'esop_advisor' ) {
		return null;
	}

	$taxonomy = esop_advisor_get_testimonial_taxonomy();
	if ( ! $taxonomy ) {
		return null;
	}

	// Try advisor name slug first
	$advisor_name = get_the_title( $advisor_id );
	$name_slug    = sanitize_title( $advisor_name );

	$term = get_term_by( 'slug', $name_slug, $taxonomy );
	if ( $term && ! is_wp_error( $term ) ) {
		return $term;
	}

	// Try the advisor's post slug
	$post         = get_post( $advisor_id );
	$post_slug    = $post ? $post->post_name : '';

	if ( $post_slug && $post_slug !== $name_slug ) {
		$term = get_term_by( 'slug', $post_slug, $taxonomy );
		if ( $term && ! is_wp_error( $term ) ) {
			return $term;
		}
	}

	return null;
}

/**
 * Get the DP Testimonials taxonomy name
 *
 * Checks for different possible taxonomy names
 *
 * @return string|null Taxonomy name or null
 */
function esop_advisor_get_testimonial_taxonomy() {
	static $taxonomy = null;

	if ( $taxonomy !== null ) {
		return $taxonomy ?: null;
	}

	// Check possible taxonomy names (in order of likelihood)
	$possible_taxonomies = array(
		'dipl_testimonial_category',
		'dp_testimonial_category',
		'testimonial_category',
	);

	foreach ( $possible_taxonomies as $tax_name ) {
		if ( taxonomy_exists( $tax_name ) ) {
			$taxonomy = $tax_name;
			return $taxonomy;
		}
	}

	$taxonomy = '';
	return null;
}

// ============================================================================
// ADMIN TOOLS PAGE
// ============================================================================

add_action( 'admin_menu', 'esop_advisor_add_tools_page' );

/**
 * Add ESOP Advisor Tools submenu under Tools
 */
function esop_advisor_add_tools_page() {
	add_submenu_page(
		'tools.php',
		'ESOP Advisor Tools',
		'ESOP Advisor Tools',
		'manage_options',
		'esop-advisor-tools',
		'esop_advisor_tools_page_render'
	);
}

/**
 * Handle tools page actions
 */
add_action( 'admin_init', 'esop_advisor_tools_handle_actions' );

function esop_advisor_tools_handle_actions() {
	if ( ! isset( $_POST['esop_advisor_tool_action'] ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['esop_advisor_tools_nonce'], 'esop_advisor_tools' ) ) {
		return;
	}

	$action = sanitize_text_field( $_POST['esop_advisor_tool_action'] );

	if ( $action === 'refresh_condition_meta' ) {
		$count = esop_advisor_refresh_all_condition_meta();
		add_settings_error(
			'esop_advisor_tools',
			'refresh_success',
			sprintf( 'Successfully refreshed condition meta for %d advisors.', $count ),
			'success'
		);
	}
}

/**
 * Render the tools page
 */
function esop_advisor_tools_page_render() {
	?>
	<div class="wrap">
		<h1>ESOP Advisor Tools</h1>

		<?php settings_errors( 'esop_advisor_tools' ); ?>

		<div class="card">
			<h2>Refresh Divi Condition Meta</h2>
			<p>This tool updates the computed meta fields used by Divi's "Display Only If" conditions for all advisors.</p>
			<p>Meta fields updated:</p>
			<ul style="list-style: disc; margin-left: 20px;">
				<li><code>esop_advisor_articles</code> - Set to '1' if advisor has articles</li>
				<li><code>esop_advisor_ratings</code> - Set to '1' if advisor has ratings/reviews</li>
				<li><code>esop_advisor_blog</code> - Set to '1' if advisor has blog posts</li>
				<li><code>esop_advisor_case_studies</code> - Set to '1' if advisor has case studies</li>
				<li><code>esop_bio</code> - Set to '1' if advisor has bio content</li>
				<li><code>esop_advisor_videos</code> - Set to '1' if advisor has videos</li>
			</ul>
			<p><strong>Note:</strong> These fields are automatically updated whenever an advisor is saved. Use this tool to bulk-refresh all advisors after initial setup or data migration.</p>

			<form method="post" style="margin-top: 15px;">
				<?php wp_nonce_field( 'esop_advisor_tools', 'esop_advisor_tools_nonce' ); ?>
				<input type="hidden" name="esop_advisor_tool_action" value="refresh_condition_meta">
				<button type="submit" class="button button-primary">Refresh All Advisors</button>
			</form>
		</div>
	</div>
	<?php
}
