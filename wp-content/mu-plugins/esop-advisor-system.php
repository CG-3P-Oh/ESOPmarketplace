<?php
/**
 * Plugin Name: ESOP Advisor System
 * Description: Complete ESOP Advisor directory with MapBox map integration. Self-contained MU plugin with no third-party dependencies.
 * Version: 1.0.0
 * Author: ESOP Marketplace
 * Text Domain: esop-advisor
 *
 * INSTALLATION INSTRUCTIONS:
 * 1. Place this file in /wp-content/mu-plugins/
 * 2. Add MapBox access token to wp-config.php:
 *    define( 'MAPBOX_ACCESS_TOKEN', 'your_mapbox_token_here' );
 * 3. Plugin will auto-activate (MU plugins always active)
 * 4. Navigate to "ESOP Advisors" in WordPress admin
 *
 * USAGE:
 * - Add advisors via WordPress admin
 * - Use geocoding tool to get coordinates from address
 * - Display map on any page with shortcode: [esop_advisor_map]
 *
 * SHORTCODE ATTRIBUTES:
 * [esop_advisor_map height="500px" zoom="4" style="streets-v12" center_lat="39.8283" center_lng="-98.5795" cluster="true"]
 *
 * HELPER FUNCTIONS FOR THEME DEVELOPERS:
 * - esop_get_advisor_meta( $post_id, $key )
 * - esop_advisor_has_coordinates( $post_id )
 * - esop_get_advisor_location( $post_id )
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ============================================================================
 * CUSTOM POST TYPE REGISTRATION
 * ============================================================================
 */

add_action( 'init', 'esop_advisor_register_post_type' );

function esop_advisor_register_post_type() {
	$labels = array(
		'name'                  => _x( 'ESOP Advisors', 'Post type general name', 'esop-advisor' ),
		'singular_name'         => _x( 'ESOP Advisor', 'Post type singular name', 'esop-advisor' ),
		'menu_name'             => _x( 'ESOP Advisors', 'Admin Menu text', 'esop-advisor' ),
		'name_admin_bar'        => _x( 'ESOP Advisor', 'Add New on Toolbar', 'esop-advisor' ),
		'add_new'               => __( 'Add New', 'esop-advisor' ),
		'add_new_item'          => __( 'Add New Advisor', 'esop-advisor' ),
		'new_item'              => __( 'New Advisor', 'esop-advisor' ),
		'edit_item'             => __( 'Edit Advisor', 'esop-advisor' ),
		'view_item'             => __( 'View Advisor', 'esop-advisor' ),
		'all_items'             => __( 'All Advisors', 'esop-advisor' ),
		'search_items'          => __( 'Search Advisors', 'esop-advisor' ),
		'parent_item_colon'     => __( 'Parent Advisors:', 'esop-advisor' ),
		'not_found'             => __( 'No advisors found.', 'esop-advisor' ),
		'not_found_in_trash'    => __( 'No advisors found in Trash.', 'esop-advisor' ),
		'featured_image'        => _x( 'Advisor Photo', 'Overrides the "Featured Image" phrase', 'esop-advisor' ),
		'set_featured_image'    => _x( 'Set advisor photo', 'Overrides the "Set featured image" phrase', 'esop-advisor' ),
		'remove_featured_image' => _x( 'Remove advisor photo', 'Overrides the "Remove featured image" phrase', 'esop-advisor' ),
		'use_featured_image'    => _x( 'Use as advisor photo', 'Overrides the "Use as featured image" phrase', 'esop-advisor' ),
		'archives'              => _x( 'Advisor archives', 'The post type archive label', 'esop-advisor' ),
		'insert_into_item'      => _x( 'Insert into advisor', 'Overrides the "Insert into post" phrase', 'esop-advisor' ),
		'uploaded_to_this_item' => _x( 'Uploaded to this advisor', 'Overrides the "Uploaded to this post" phrase', 'esop-advisor' ),
		'filter_items_list'     => _x( 'Filter advisors list', 'Screen reader text for the filter links', 'esop-advisor' ),
		'items_list_navigation' => _x( 'Advisors list navigation', 'Screen reader text for the pagination', 'esop-advisor' ),
		'items_list'            => _x( 'Advisors list', 'Screen reader text for the items list', 'esop-advisor' ),
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

/**
 * ============================================================================
 * ADMIN META BOXES
 * ============================================================================
 */

add_action( 'add_meta_boxes', 'esop_advisor_add_meta_boxes' );

function esop_advisor_add_meta_boxes() {
	// Address Geocoder Tool (high priority)
	add_meta_box(
		'esop_advisor_geocoder',
		__( 'Address Geocoder', 'esop-advisor' ),
		'esop_advisor_geocoder_meta_box',
		'esop_advisor',
		'normal',
		'high'
	);

	// Advisor Details
	add_meta_box(
		'esop_advisor_details',
		__( 'Advisor Details', 'esop-advisor' ),
		'esop_advisor_details_meta_box',
		'esop_advisor',
		'normal',
		'default'
	);

	// Coordinates (sidebar)
	add_meta_box(
		'esop_advisor_coordinates',
		__( 'Coordinates', 'esop-advisor' ),
		'esop_advisor_coordinates_meta_box',
		'esop_advisor',
		'side',
		'default'
	);
}

/**
 * Geocoder Meta Box
 */
function esop_advisor_geocoder_meta_box( $post ) {
	wp_nonce_field( 'esop_advisor_save_meta', 'esop_advisor_meta_nonce' );

	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		echo '<div class="notice notice-error inline"><p>';
		echo esc_html__( 'MapBox access token not configured. Please add MAPBOX_ACCESS_TOKEN to wp-config.php', 'esop-advisor' );
		echo '</p></div>';
		return;
	}

	$address = get_post_meta( $post->ID, '_esop_advisor_address', true );
	$city    = get_post_meta( $post->ID, '_esop_advisor_city', true );
	$state   = get_post_meta( $post->ID, '_esop_advisor_state', true );
	$zip     = get_post_meta( $post->ID, '_esop_advisor_zip', true );

	?>
	<div class="esop-geocoder-tool">
		<p class="description">
			<?php esc_html_e( 'Use this tool to look up coordinates from an address using the MapBox Geocoding API.', 'esop-advisor' ); ?>
		</p>

		<div class="esop-geocoder-input-group">
			<input
				type="text"
				id="esop_geocoder_address"
				class="regular-text"
				placeholder="<?php esc_attr_e( 'Enter address to search...', 'esop-advisor' ); ?>"
				value="<?php echo esc_attr( trim( $address . ' ' . $city . ' ' . $state . ' ' . $zip ) ); ?>"
			>
			<button type="button" id="esop_geocoder_search" class="button button-secondary">
				<?php esc_html_e( 'Search Address', 'esop-advisor' ); ?>
			</button>
		</div>

		<div id="esop_geocoder_results" class="esop-geocoder-results" style="display: none;">
			<p class="description"><?php esc_html_e( 'Select a result to auto-fill the address and coordinates:', 'esop-advisor' ); ?></p>
			<div id="esop_geocoder_results_list"></div>
		</div>

		<div id="esop_geocoder_loading" class="esop-geocoder-loading" style="display: none;">
			<span class="spinner is-active"></span>
			<?php esc_html_e( 'Searching...', 'esop-advisor' ); ?>
		</div>

		<div id="esop_geocoder_error" class="notice notice-error inline" style="display: none;">
			<p></p>
		</div>

		<div id="esop_geocoder_success" class="notice notice-success inline" style="display: none;">
			<p><?php esc_html_e( 'Address and coordinates updated successfully!', 'esop-advisor' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Advisor Details Meta Box
 */
function esop_advisor_details_meta_box( $post ) {
	$company  = get_post_meta( $post->ID, '_esop_advisor_company', true );
	$address  = get_post_meta( $post->ID, '_esop_advisor_address', true );
	$city     = get_post_meta( $post->ID, '_esop_advisor_city', true );
	$state    = get_post_meta( $post->ID, '_esop_advisor_state', true );
	$zip      = get_post_meta( $post->ID, '_esop_advisor_zip', true );
	$phone    = get_post_meta( $post->ID, '_esop_advisor_phone', true );
	$email    = get_post_meta( $post->ID, '_esop_advisor_email', true );
	$website  = get_post_meta( $post->ID, '_esop_advisor_website', true );
	$linkedin = get_post_meta( $post->ID, '_esop_advisor_linkedin', true );
	$bio      = get_post_meta( $post->ID, '_esop_advisor_bio', true );

	$us_states = esop_advisor_get_us_states();
	?>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="esop_advisor_company"><?php esc_html_e( 'Company', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="text" id="esop_advisor_company" name="esop_advisor_company" value="<?php echo esc_attr( $company ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_address"><?php esc_html_e( 'Street Address', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="text" id="esop_advisor_address" name="esop_advisor_address" value="<?php echo esc_attr( $address ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_city"><?php esc_html_e( 'City', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="text" id="esop_advisor_city" name="esop_advisor_city" value="<?php echo esc_attr( $city ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_state"><?php esc_html_e( 'State', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<select id="esop_advisor_state" name="esop_advisor_state" class="regular-text">
					<option value=""><?php esc_html_e( '-- Select State --', 'esop-advisor' ); ?></option>
					<?php foreach ( $us_states as $abbr => $name ) : ?>
						<option value="<?php echo esc_attr( $abbr ); ?>" <?php selected( $state, $abbr ); ?>>
							<?php echo esc_html( $name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_zip"><?php esc_html_e( 'ZIP Code', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="text" id="esop_advisor_zip" name="esop_advisor_zip" value="<?php echo esc_attr( $zip ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_phone"><?php esc_html_e( 'Phone', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="tel" id="esop_advisor_phone" name="esop_advisor_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_email"><?php esc_html_e( 'Email', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="email" id="esop_advisor_email" name="esop_advisor_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_website"><?php esc_html_e( 'Website', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="url" id="esop_advisor_website" name="esop_advisor_website" value="<?php echo esc_attr( $website ); ?>" class="regular-text">
				<p class="description"><?php esc_html_e( 'Include http:// or https://', 'esop-advisor' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_linkedin"><?php esc_html_e( 'LinkedIn URL', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<input type="url" id="esop_advisor_linkedin" name="esop_advisor_linkedin" value="<?php echo esc_attr( $linkedin ); ?>" class="regular-text">
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="esop_advisor_bio"><?php esc_html_e( 'Short Bio', 'esop-advisor' ); ?></label>
			</th>
			<td>
				<textarea id="esop_advisor_bio" name="esop_advisor_bio" rows="5" class="large-text"><?php echo esc_textarea( $bio ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Brief biography to display in map popup.', 'esop-advisor' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Coordinates Meta Box
 */
function esop_advisor_coordinates_meta_box( $post ) {
	$latitude  = get_post_meta( $post->ID, '_esop_advisor_latitude', true );
	$longitude = get_post_meta( $post->ID, '_esop_advisor_longitude', true );
	$has_coords = esop_advisor_has_coordinates( $post->ID );
	?>

	<div class="esop-coordinates-status">
		<?php if ( $has_coords ) : ?>
			<p class="esop-coord-status esop-coord-valid">
				<span class="dashicons dashicons-yes-alt"></span>
				<?php esc_html_e( 'Valid coordinates', 'esop-advisor' ); ?>
			</p>
		<?php else : ?>
			<p class="esop-coord-status esop-coord-invalid">
				<span class="dashicons dashicons-dismiss"></span>
				<?php esc_html_e( 'Missing or invalid coordinates', 'esop-advisor' ); ?>
			</p>
		<?php endif; ?>
	</div>

	<p>
		<label for="esop_advisor_latitude">
			<strong><?php esc_html_e( 'Latitude', 'esop-advisor' ); ?></strong>
		</label>
		<input
			type="text"
			id="esop_advisor_latitude"
			name="esop_advisor_latitude"
			value="<?php echo esc_attr( $latitude ); ?>"
			class="widefat"
			placeholder="-90 to 90"
		>
		<span class="description"><?php esc_html_e( 'Range: -90 to 90', 'esop-advisor' ); ?></span>
	</p>

	<p>
		<label for="esop_advisor_longitude">
			<strong><?php esc_html_e( 'Longitude', 'esop-advisor' ); ?></strong>
		</label>
		<input
			type="text"
			id="esop_advisor_longitude"
			name="esop_advisor_longitude"
			value="<?php echo esc_attr( $longitude ); ?>"
			class="widefat"
			placeholder="-180 to 180"
		>
		<span class="description"><?php esc_html_e( 'Range: -180 to 180', 'esop-advisor' ); ?></span>
	</p>

	<p class="description">
		<?php esc_html_e( 'Coordinates are optional. Use the Address Geocoder above to automatically populate these fields, or leave empty if location is not needed for map display.', 'esop-advisor' ); ?>
	</p>
	<?php
}

/**
 * Get US States Array
 */
function esop_advisor_get_us_states() {
	return array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);
}

/**
 * ============================================================================
 * SAVE META DATA
 * ============================================================================
 */

add_action( 'save_post_esop_advisor', 'esop_advisor_save_meta', 10, 2 );

function esop_advisor_save_meta( $post_id, $post ) {
	// Check nonce
	if ( ! isset( $_POST['esop_advisor_meta_nonce'] ) || ! wp_verify_nonce( $_POST['esop_advisor_meta_nonce'], 'esop_advisor_save_meta' ) ) {
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

	// Save text fields
	$text_fields = array(
		'esop_advisor_company',
		'esop_advisor_address',
		'esop_advisor_city',
		'esop_advisor_state',
		'esop_advisor_zip',
		'esop_advisor_phone',
	);

	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
		}
	}

	// Save email
	if ( isset( $_POST['esop_advisor_email'] ) ) {
		update_post_meta( $post_id, '_esop_advisor_email', sanitize_email( $_POST['esop_advisor_email'] ) );
	}

	// Save URLs
	if ( isset( $_POST['esop_advisor_website'] ) ) {
		update_post_meta( $post_id, '_esop_advisor_website', esc_url_raw( $_POST['esop_advisor_website'] ) );
	}

	if ( isset( $_POST['esop_advisor_linkedin'] ) ) {
		update_post_meta( $post_id, '_esop_advisor_linkedin', esc_url_raw( $_POST['esop_advisor_linkedin'] ) );
	}

	// Save textarea
	if ( isset( $_POST['esop_advisor_bio'] ) ) {
		update_post_meta( $post_id, '_esop_advisor_bio', sanitize_textarea_field( $_POST['esop_advisor_bio'] ) );
	}

	// Save and validate coordinates
	if ( isset( $_POST['esop_advisor_latitude'] ) ) {
		$latitude = sanitize_text_field( $_POST['esop_advisor_latitude'] );
		if ( $latitude !== '' && ( ! is_numeric( $latitude ) || $latitude < -90 || $latitude > 90 ) ) {
			$latitude = ''; // Invalid, clear it
		}
		update_post_meta( $post_id, '_esop_advisor_latitude', $latitude );
	}

	if ( isset( $_POST['esop_advisor_longitude'] ) ) {
		$longitude = sanitize_text_field( $_POST['esop_advisor_longitude'] );
		if ( $longitude !== '' && ( ! is_numeric( $longitude ) || $longitude < -180 || $longitude > 180 ) ) {
			$longitude = ''; // Invalid, clear it
		}
		update_post_meta( $post_id, '_esop_advisor_longitude', $longitude );
	}
}

/**
 * ============================================================================
 * AJAX GEOCODING HANDLER
 * ============================================================================
 */

add_action( 'wp_ajax_esop_advisor_geocode', 'esop_advisor_ajax_geocode' );

function esop_advisor_ajax_geocode() {
	// Check nonce
	check_ajax_referer( 'esop_advisor_geocode', 'nonce' );

	// Check permissions
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( array( 'message' => __( 'Permission denied.', 'esop-advisor' ) ) );
	}

	// Check for MapBox token
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		wp_send_json_error( array( 'message' => __( 'MapBox access token not configured.', 'esop-advisor' ) ) );
	}

	// Get search query
	$query = isset( $_POST['query'] ) ? sanitize_text_field( $_POST['query'] ) : '';

	if ( empty( $query ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter an address to search.', 'esop-advisor' ) ) );
	}

	// Build MapBox Geocoding API URL
	$url = sprintf(
		'https://api.mapbox.com/geocoding/v5/mapbox.places/%s.json?access_token=%s&country=US&limit=5',
		rawurlencode( $query ),
		MAPBOX_ACCESS_TOKEN
	);

	// Make API request
	$response = wp_remote_get( $url, array( 'timeout' => 15 ) );

	if ( is_wp_error( $response ) ) {
		wp_send_json_error( array( 'message' => $response->get_error_message() ) );
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( ! isset( $data['features'] ) || empty( $data['features'] ) ) {
		wp_send_json_error( array( 'message' => __( 'No results found. Try a different address.', 'esop-advisor' ) ) );
	}

	// Format results
	$results = array();
	foreach ( $data['features'] as $feature ) {
		$results[] = array(
			'place_name' => $feature['place_name'],
			'center'     => $feature['center'], // [longitude, latitude]
			'context'    => isset( $feature['context'] ) ? $feature['context'] : array(),
			'address'    => isset( $feature['address'] ) ? $feature['address'] : '',
			'text'       => isset( $feature['text'] ) ? $feature['text'] : '',
		);
	}

	wp_send_json_success( array( 'results' => $results ) );
}

/**
 * ============================================================================
 * ADMIN COLUMNS
 * ============================================================================
 */

add_filter( 'manage_esop_advisor_posts_columns', 'esop_advisor_custom_columns' );

function esop_advisor_custom_columns( $columns ) {
	$new_columns = array();

	// Checkbox
	$new_columns['cb'] = $columns['cb'];

	// Title
	$new_columns['title'] = $columns['title'];

	// Custom columns
	$new_columns['company']     = __( 'Company', 'esop-advisor' );
	$new_columns['city']        = __( 'City', 'esop-advisor' );
	$new_columns['state']       = __( 'State', 'esop-advisor' );
	$new_columns['phone']       = __( 'Phone', 'esop-advisor' );
	$new_columns['coordinates'] = __( 'Coordinates', 'esop-advisor' );

	// Date
	$new_columns['date'] = $columns['date'];

	return $new_columns;
}

add_action( 'manage_esop_advisor_posts_custom_column', 'esop_advisor_custom_column_content', 10, 2 );

function esop_advisor_custom_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'company':
			$company = get_post_meta( $post_id, '_esop_advisor_company', true );
			echo esc_html( $company ? $company : '—' );
			break;

		case 'city':
			$city = get_post_meta( $post_id, '_esop_advisor_city', true );
			echo esc_html( $city ? $city : '—' );
			break;

		case 'state':
			$state = get_post_meta( $post_id, '_esop_advisor_state', true );
			echo esc_html( $state ? $state : '—' );
			break;

		case 'phone':
			$phone = get_post_meta( $post_id, '_esop_advisor_phone', true );
			echo esc_html( $phone ? $phone : '—' );
			break;

		case 'coordinates':
			if ( esop_advisor_has_coordinates( $post_id ) ) {
				echo '<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>';
			} else {
				echo '<span class="dashicons dashicons-dismiss" style="color: #dc3232;"></span>';
			}
			break;
	}
}

/**
 * Make columns sortable
 */
add_filter( 'manage_edit-esop_advisor_sortable_columns', 'esop_advisor_sortable_columns' );

function esop_advisor_sortable_columns( $columns ) {
	$columns['company'] = 'company';
	$columns['city']    = 'city';
	$columns['state']   = 'state';
	return $columns;
}

add_action( 'pre_get_posts', 'esop_advisor_column_sorting' );

function esop_advisor_column_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$orderby = $query->get( 'orderby' );

	if ( 'company' === $orderby ) {
		$query->set( 'meta_key', '_esop_advisor_company' );
		$query->set( 'orderby', 'meta_value' );
	} elseif ( 'city' === $orderby ) {
		$query->set( 'meta_key', '_esop_advisor_city' );
		$query->set( 'orderby', 'meta_value' );
	} elseif ( 'state' === $orderby ) {
		$query->set( 'meta_key', '_esop_advisor_state' );
		$query->set( 'orderby', 'meta_value' );
	}
}

/**
 * ============================================================================
 * ADMIN NOTICES
 * ============================================================================
 */

add_action( 'admin_notices', 'esop_advisor_admin_notices' );

function esop_advisor_admin_notices() {
	// MapBox token missing notice (on esop_advisor screens)
	$screen = get_current_screen();
	if ( $screen && $screen->post_type === 'esop_advisor' && ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		?>
		<div class="notice notice-warning">
			<p>
				<strong><?php esc_html_e( 'MapBox Configuration Required', 'esop-advisor' ); ?></strong><br>
				<?php esc_html_e( 'The MapBox access token is not configured. Please add the following to your wp-config.php file:', 'esop-advisor' ); ?>
				<br><code>define( 'MAPBOX_ACCESS_TOKEN', 'your_mapbox_token_here' );</code>
			</p>
		</div>
		<?php
	}

	// Optional: Show info notice if advisor is published without coordinates
	global $post;
	if ( $post && $post->post_type === 'esop_advisor' && $post->post_status === 'publish' && ! esop_advisor_has_coordinates( $post->ID ) && isset( $_GET['message'] ) && $_GET['message'] == '6' ) {
		?>
		<div class="notice notice-info is-dismissible">
			<p>
				<strong><?php esc_html_e( 'Advisor published successfully!', 'esop-advisor' ); ?></strong>
				<?php esc_html_e( 'Note: This advisor does not have coordinates set and will not appear on the map. Use the Address Geocoder tool to add location coordinates.', 'esop-advisor' ); ?>
			</p>
		</div>
		<?php
	}
}

/**
 * ============================================================================
 * FRONTEND MAP SHORTCODE
 * ============================================================================
 */

add_shortcode( 'esop_advisor_map', 'esop_advisor_map_shortcode' );

function esop_advisor_map_shortcode( $atts ) {
	// Check for MapBox token
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		return '<p class="esop-map-error">' . esc_html__( 'MapBox access token not configured.', 'esop-advisor' ) . '</p>';
	}

	// Parse attributes
	$atts = shortcode_atts( array(
		'height'     => '500px',
		'zoom'       => '4',
		'style'      => 'streets-v12',
		'center_lat' => '39.8283',
		'center_lng' => '-98.5795',
		'cluster'    => 'true',
	), $atts, 'esop_advisor_map' );

	// Get all published advisors with coordinates
	$args = array(
		'post_type'      => 'esop_advisor',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => '_esop_advisor_latitude',
				'value'   => '',
				'compare' => '!=',
			),
			array(
				'key'     => '_esop_advisor_longitude',
				'value'   => '',
				'compare' => '!=',
			),
		),
	);

	$advisors = get_posts( $args );

	if ( empty( $advisors ) ) {
		return '<p class="esop-map-notice">' . esc_html__( 'No advisors with coordinates available yet.', 'esop-advisor' ) . '</p>';
	}

	// Build GeoJSON features
	$features = array();
	foreach ( $advisors as $advisor ) {
		$latitude  = get_post_meta( $advisor->ID, '_esop_advisor_latitude', true );
		$longitude = get_post_meta( $advisor->ID, '_esop_advisor_longitude', true );

		// Validate coordinates
		if ( ! is_numeric( $latitude ) || ! is_numeric( $longitude ) ) {
			continue;
		}

		$company  = get_post_meta( $advisor->ID, '_esop_advisor_company', true );
		$city     = get_post_meta( $advisor->ID, '_esop_advisor_city', true );
		$state    = get_post_meta( $advisor->ID, '_esop_advisor_state', true );
		$phone    = get_post_meta( $advisor->ID, '_esop_advisor_phone', true );
		$email    = get_post_meta( $advisor->ID, '_esop_advisor_email', true );
		$bio      = get_post_meta( $advisor->ID, '_esop_advisor_bio', true );
		$location = esop_get_advisor_location( $advisor->ID );

		// Get featured image
		$image_url = get_the_post_thumbnail_url( $advisor->ID, 'thumbnail' );

		$features[] = array(
			'type'       => 'Feature',
			'geometry'   => array(
				'type'        => 'Point',
				'coordinates' => array( floatval( $longitude ), floatval( $latitude ) ), // GeoJSON is [lng, lat]
			),
			'properties' => array(
				'id'       => $advisor->ID,
				'title'    => get_the_title( $advisor->ID ),
				'company'  => $company,
				'location' => $location,
				'phone'    => $phone,
				'email'    => $email,
				'bio'      => $bio,
				'image'    => $image_url,
				'url'      => get_permalink( $advisor->ID ),
			),
		);
	}

	// Generate unique ID for this map
	static $map_id = 0;
	$map_id++;
	$map_container_id = 'esop-advisor-map-' . $map_id;

	// Enqueue MapBox GL JS and CSS
	wp_enqueue_script(
		'mapbox-gl',
		'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js',
		array(),
		'2.15.0',
		true
	);

	wp_enqueue_style(
		'mapbox-gl',
		'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css',
		array(),
		'2.15.0'
	);

	// Build map initialization script
	$map_data = array(
		'containerId' => $map_container_id,
		'accessToken' => MAPBOX_ACCESS_TOKEN,
		'style'       => 'mapbox://styles/mapbox/' . esc_attr( $atts['style'] ),
		'center'      => array( floatval( $atts['center_lng'] ), floatval( $atts['center_lat'] ) ),
		'zoom'        => floatval( $atts['zoom'] ),
		'cluster'     => $atts['cluster'] === 'true',
		'features'    => $features,
	);

	// Add inline script
	add_action( 'wp_footer', function() use ( $map_data ) {
		?>
		<script>
		(function() {
			if (typeof mapboxgl === 'undefined') {
				console.error('MapBox GL JS not loaded');
				return;
			}

			var mapData = <?php echo wp_json_encode( $map_data ); ?>;

			mapboxgl.accessToken = mapData.accessToken;

			var map = new mapboxgl.Map({
				container: mapData.containerId,
				style: mapData.style,
				center: mapData.center,
				zoom: mapData.zoom
			});

			// Add navigation controls
			map.addControl(new mapboxgl.NavigationControl(), 'top-right');

			// Add fullscreen control
			map.addControl(new mapboxgl.FullscreenControl(), 'top-right');

			map.on('load', function() {
				// Add source
				map.addSource('advisors', {
					type: 'geojson',
					data: {
						type: 'FeatureCollection',
						features: mapData.features
					},
					cluster: mapData.cluster,
					clusterMaxZoom: 14,
					clusterRadius: 50
				});

				// Add cluster layer
				if (mapData.cluster) {
					map.addLayer({
						id: 'clusters',
						type: 'circle',
						source: 'advisors',
						filter: ['has', 'point_count'],
						paint: {
							'circle-color': [
								'step',
								['get', 'point_count'],
								'#51bbd6',
								10,
								'#f1f075',
								30,
								'#f28cb1'
							],
							'circle-radius': [
								'step',
								['get', 'point_count'],
								20,
								10,
								30,
								30,
								40
							]
						}
					});

					map.addLayer({
						id: 'cluster-count',
						type: 'symbol',
						source: 'advisors',
						filter: ['has', 'point_count'],
						layout: {
							'text-field': '{point_count_abbreviated}',
							'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
							'text-size': 12
						}
					});

					// Click cluster to zoom
					map.on('click', 'clusters', function(e) {
						var features = map.queryRenderedFeatures(e.point, {
							layers: ['clusters']
						});
						var clusterId = features[0].properties.cluster_id;
						map.getSource('advisors').getClusterExpansionZoom(
							clusterId,
							function(err, zoom) {
								if (err) return;

								map.easeTo({
									center: features[0].geometry.coordinates,
									zoom: zoom
								});
							}
						);
					});

					map.on('mouseenter', 'clusters', function() {
						map.getCanvas().style.cursor = 'pointer';
					});

					map.on('mouseleave', 'clusters', function() {
						map.getCanvas().style.cursor = '';
					});
				}

				// Add unclustered points layer
				map.addLayer({
					id: 'unclustered-point',
					type: 'circle',
					source: 'advisors',
					filter: mapData.cluster ? ['!', ['has', 'point_count']] : null,
					paint: {
						'circle-color': '#11b4da',
						'circle-radius': 8,
						'circle-stroke-width': 2,
						'circle-stroke-color': '#fff'
					}
				});

				// Click marker to show popup
				map.on('click', 'unclustered-point', function(e) {
					var coordinates = e.features[0].geometry.coordinates.slice();
					var props = e.features[0].properties;

					// Ensure popup appears over the marker
					while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
						coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
					}

					var popupHTML = '<div class="esop-advisor-popup">';

					if (props.image) {
						popupHTML += '<img src="' + props.image + '" alt="' + props.title + '" class="esop-popup-image">';
					}

					popupHTML += '<h3 class="esop-popup-title">' + props.title + '</h3>';

					if (props.company) {
						popupHTML += '<p class="esop-popup-company">' + props.company + '</p>';
					}

					if (props.location) {
						popupHTML += '<p class="esop-popup-location">' + props.location + '</p>';
					}

					if (props.bio) {
						popupHTML += '<p class="esop-popup-bio">' + props.bio + '</p>';
					}

					popupHTML += '<div class="esop-popup-actions">';

					if (props.phone) {
						popupHTML += '<a href="tel:' + props.phone + '" class="esop-popup-button esop-popup-call">Call</a>';
					}

					if (props.email) {
						popupHTML += '<a href="mailto:' + props.email + '" class="esop-popup-button esop-popup-email">Email</a>';
					}

					popupHTML += '<a href="' + props.url + '" class="esop-popup-button esop-popup-view">View Profile</a>';
					popupHTML += '</div></div>';

					new mapboxgl.Popup()
						.setLngLat(coordinates)
						.setHTML(popupHTML)
						.addTo(map);
				});

				map.on('mouseenter', 'unclustered-point', function() {
					map.getCanvas().style.cursor = 'pointer';
				});

				map.on('mouseleave', 'unclustered-point', function() {
					map.getCanvas().style.cursor = '';
				});

				// Fit bounds to show all advisors
				if (mapData.features.length > 0) {
					var bounds = new mapboxgl.LngLatBounds();
					mapData.features.forEach(function(feature) {
						bounds.extend(feature.geometry.coordinates);
					});
					map.fitBounds(bounds, { padding: 50, maxZoom: 12 });
				}
			});
		})();
		</script>
		<?php
	}, 999 );

	// Return map container HTML
	return sprintf(
		'<div id="%s" class="esop-advisor-map-container" style="height: %s;"></div>',
		esc_attr( $map_container_id ),
		esc_attr( $atts['height'] )
	);
}

/**
 * ============================================================================
 * HELPER FUNCTIONS FOR THEME DEVELOPERS
 * ============================================================================
 */

/**
 * Get advisor meta by key (without prefix)
 *
 * @param int    $post_id Post ID
 * @param string $key     Meta key without prefix (e.g., 'company', 'phone')
 * @return mixed Meta value or empty string
 */
function esop_get_advisor_meta( $post_id, $key ) {
	if ( get_post_type( $post_id ) !== 'esop_advisor' ) {
		return '';
	}
	return get_post_meta( $post_id, '_esop_advisor_' . $key, true );
}

/**
 * Check if advisor has valid coordinates
 *
 * @param int $post_id Post ID
 * @return bool True if valid coordinates exist
 */
function esop_advisor_has_coordinates( $post_id ) {
	$latitude  = get_post_meta( $post_id, '_esop_advisor_latitude', true );
	$longitude = get_post_meta( $post_id, '_esop_advisor_longitude', true );

	$lat_valid = is_numeric( $latitude ) && $latitude >= -90 && $latitude <= 90;
	$lng_valid = is_numeric( $longitude ) && $longitude >= -180 && $longitude <= 180;

	return $lat_valid && $lng_valid;
}

/**
 * Get formatted location string (City, State)
 *
 * @param int $post_id Post ID
 * @return string Formatted location or empty string
 */
function esop_get_advisor_location( $post_id ) {
	$city  = get_post_meta( $post_id, '_esop_advisor_city', true );
	$state = get_post_meta( $post_id, '_esop_advisor_state', true );

	$parts = array_filter( array( $city, $state ) );
	return implode( ', ', $parts );
}

/**
 * ============================================================================
 * ADMIN STYLES AND SCRIPTS
 * ============================================================================
 */

add_action( 'admin_enqueue_scripts', 'esop_advisor_admin_scripts' );

function esop_advisor_admin_scripts( $hook ) {
	global $post_type;

	if ( 'esop_advisor' !== $post_type ) {
		return;
	}

	// Inline admin CSS
	add_action( 'admin_head', 'esop_advisor_admin_styles' );

	// Only load on edit screen
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		// Inline admin JavaScript
		add_action( 'admin_footer', 'esop_advisor_admin_javascript' );
	}
}

function esop_advisor_admin_styles() {
	?>
	<style>
	/* Geocoder Tool Styles */
	.esop-geocoder-tool {
		background: #f9f9f9;
		padding: 15px;
		border: 1px solid #ddd;
		border-radius: 4px;
	}

	.esop-geocoder-input-group {
		display: flex;
		gap: 10px;
		margin-bottom: 15px;
	}

	.esop-geocoder-input-group input {
		flex: 1;
	}

	.esop-geocoder-results {
		background: #fff;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 10px;
		margin-top: 15px;
		max-height: 300px;
		overflow-y: auto;
	}

	.esop-geocoder-result-item {
		padding: 10px;
		border-bottom: 1px solid #eee;
		cursor: pointer;
		transition: background 0.2s;
	}

	.esop-geocoder-result-item:last-child {
		border-bottom: none;
	}

	.esop-geocoder-result-item:hover {
		background: #f0f0f0;
	}

	.esop-geocoder-result-item strong {
		display: block;
		margin-bottom: 5px;
		color: #2271b1;
	}

	.esop-geocoder-result-item small {
		color: #666;
	}

	.esop-geocoder-loading {
		padding: 10px;
		color: #666;
	}

	.esop-geocoder-loading .spinner {
		float: none;
		margin: 0 5px 0 0;
		vertical-align: middle;
	}

	/* Coordinates Status */
	.esop-coordinates-status {
		margin-bottom: 15px;
	}

	.esop-coord-status {
		padding: 8px 12px;
		border-radius: 4px;
		font-weight: 500;
	}

	.esop-coord-valid {
		background: #d4edda;
		color: #155724;
	}

	.esop-coord-invalid {
		background: #f8d7da;
		color: #721c24;
	}

	.esop-coord-status .dashicons {
		vertical-align: middle;
		margin-right: 5px;
	}

	.required {
		color: #dc3232;
		font-weight: bold;
	}

	/* Form Table Enhancements */
	.form-table th label {
		font-weight: 600;
	}

	.form-table td .description {
		font-style: italic;
		color: #666;
	}
	</style>
	<?php
}

function esop_advisor_admin_javascript() {
	if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var searchBtn = document.getElementById('esop_geocoder_search');
		var addressInput = document.getElementById('esop_geocoder_address');
		var resultsContainer = document.getElementById('esop_geocoder_results');
		var resultsList = document.getElementById('esop_geocoder_results_list');
		var loadingIndicator = document.getElementById('esop_geocoder_loading');
		var errorContainer = document.getElementById('esop_geocoder_error');
		var successContainer = document.getElementById('esop_geocoder_success');

		if (!searchBtn || !addressInput) {
			return;
		}

		// Search button click
		searchBtn.addEventListener('click', function(e) {
			e.preventDefault();
			performGeocode();
		});

		// Enter key in address field
		addressInput.addEventListener('keypress', function(e) {
			if (e.key === 'Enter') {
				e.preventDefault();
				performGeocode();
			}
		});

		function performGeocode() {
			var query = addressInput.value.trim();

			if (!query) {
				showError('Please enter an address to search.');
				return;
			}

			// Hide previous results/messages
			resultsContainer.style.display = 'none';
			errorContainer.style.display = 'none';
			successContainer.style.display = 'none';

			// Show loading
			loadingIndicator.style.display = 'block';

			// Make AJAX request
			var data = new FormData();
			data.append('action', 'esop_advisor_geocode');
			data.append('nonce', '<?php echo wp_create_nonce( 'esop_advisor_geocode' ); ?>');
			data.append('query', query);

			fetch(ajaxurl, {
				method: 'POST',
				credentials: 'same-origin',
				body: data
			})
			.then(function(response) {
				return response.json();
			})
			.then(function(response) {
				loadingIndicator.style.display = 'none';

				if (response.success && response.data.results) {
					displayResults(response.data.results);
				} else {
					showError(response.data.message || 'An error occurred.');
				}
			})
			.catch(function(error) {
				loadingIndicator.style.display = 'none';
				showError('Network error: ' + error.message);
			});
		}

		function displayResults(results) {
			resultsList.innerHTML = '';

			results.forEach(function(result) {
				var item = document.createElement('div');
				item.className = 'esop-geocoder-result-item';

				var title = document.createElement('strong');
				title.textContent = result.place_name;

				var coords = document.createElement('small');
				coords.textContent = 'Lat: ' + result.center[1].toFixed(6) + ', Lng: ' + result.center[0].toFixed(6);

				item.appendChild(title);
				item.appendChild(coords);

				item.addEventListener('click', function() {
					selectResult(result);
				});

				resultsList.appendChild(item);
			});

			resultsContainer.style.display = 'block';
		}

		function selectResult(result) {
			// Parse address components
			var streetNumber = '';
			var streetName = result.text || '';
			var city = '';
			var state = '';
			var zip = '';

			if (result.address) {
				streetNumber = result.address;
			}

			if (result.context) {
				result.context.forEach(function(item) {
					var parts = item.id.split('.');
					var type = parts[0];

					if (type === 'place') {
						city = item.text;
					} else if (type === 'region') {
						state = item.short_code ? item.short_code.replace('US-', '') : '';
					} else if (type === 'postcode') {
						zip = item.text;
					}
				});
			}

			// Build street address
			var streetAddress = streetNumber ? streetNumber + ' ' + streetName : streetName;

			// Update form fields
			document.getElementById('esop_advisor_address').value = streetAddress;
			document.getElementById('esop_advisor_city').value = city;
			document.getElementById('esop_advisor_state').value = state;
			document.getElementById('esop_advisor_zip').value = zip;
			document.getElementById('esop_advisor_latitude').value = result.center[1].toFixed(8);
			document.getElementById('esop_advisor_longitude').value = result.center[0].toFixed(8);

			// Hide results, show success
			resultsContainer.style.display = 'none';
			successContainer.style.display = 'block';

			// Auto-hide success message after 3 seconds
			setTimeout(function() {
				successContainer.style.display = 'none';
			}, 3000);
		}

		function showError(message) {
			errorContainer.querySelector('p').textContent = message;
			errorContainer.style.display = 'block';
		}
	})();
	</script>
	<?php
}

/**
 * ============================================================================
 * FRONTEND STYLES
 * ============================================================================
 */

add_action( 'wp_head', 'esop_advisor_frontend_styles' );

function esop_advisor_frontend_styles() {
	?>
	<style>
	/* Map Container */
	.esop-advisor-map-container {
		width: 100%;
		margin: 20px 0;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	}

	/* Map Popup */
	.esop-advisor-popup {
		min-width: 250px;
		max-width: 350px;
	}

	.esop-popup-image {
		width: 100%;
		height: auto;
		display: block;
		margin-bottom: 12px;
		border-radius: 4px;
	}

	.esop-popup-title {
		margin: 0 0 8px 0;
		font-size: 18px;
		font-weight: 600;
		color: #333;
	}

	.esop-popup-company {
		margin: 0 0 4px 0;
		font-size: 14px;
		color: #666;
		font-weight: 500;
	}

	.esop-popup-location {
		margin: 0 0 10px 0;
		font-size: 13px;
		color: #999;
	}

	.esop-popup-bio {
		margin: 0 0 12px 0;
		font-size: 13px;
		line-height: 1.5;
		color: #555;
	}

	.esop-popup-actions {
		display: flex;
		gap: 8px;
		flex-wrap: wrap;
	}

	.esop-popup-button {
		display: inline-block;
		padding: 8px 14px;
		font-size: 13px;
		font-weight: 500;
		text-decoration: none;
		border-radius: 4px;
		transition: all 0.2s;
		text-align: center;
		flex: 1;
		min-width: 70px;
	}

	.esop-popup-call {
		background: #28a745;
		color: #fff;
	}

	.esop-popup-call:hover {
		background: #218838;
		color: #fff;
	}

	.esop-popup-email {
		background: #17a2b8;
		color: #fff;
	}

	.esop-popup-email:hover {
		background: #138496;
		color: #fff;
	}

	.esop-popup-view {
		background: #007bff;
		color: #fff;
	}

	.esop-popup-view:hover {
		background: #0056b3;
		color: #fff;
	}

	/* Map Error/Notice */
	.esop-map-error,
	.esop-map-notice {
		padding: 15px 20px;
		border-radius: 4px;
		margin: 20px 0;
	}

	.esop-map-error {
		background: #f8d7da;
		color: #721c24;
		border: 1px solid #f5c6cb;
	}

	.esop-map-notice {
		background: #d1ecf1;
		color: #0c5460;
		border: 1px solid #bee5eb;
	}

	/* Responsive */
	@media (max-width: 768px) {
		.esop-advisor-popup {
			min-width: 200px;
			max-width: 280px;
		}

		.esop-popup-actions {
			flex-direction: column;
		}

		.esop-popup-button {
			width: 100%;
		}
	}

	/* MapBox Popup Overrides */
	.mapboxgl-popup-content {
		padding: 15px;
		border-radius: 8px;
	}

	.mapboxgl-popup-close-button {
		font-size: 20px;
		padding: 5px;
		color: #999;
	}

	.mapboxgl-popup-close-button:hover {
		color: #333;
		background: transparent;
	}
	</style>
	<?php
}
