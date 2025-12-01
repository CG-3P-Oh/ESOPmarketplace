<?php
/**
 * Plugin Name: ESOP Advisor System
 * Plugin URI: https://new.esopmarketplace.com/
 * Description: Custom advisor directory with MapBox map integration for ESOP Marketplace. Creates an "Advisors" CPT with location data and interactive map display.
 * Version: 1.0.0
 * Author: 3PRIME
 * Author URI: https://3prime.io/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: esop-advisor
 * 
 * INSTALLATION:
 * 1. Upload this file to /wp-content/mu-plugins/esop-advisor-system.php
 * 2. Define MAPBOX_ACCESS_TOKEN in wp-config.php:
 *    define('MAPBOX_ACCESS_TOKEN', 'your-mapbox-token-here');
 * 3. Flush permalinks: Settings > Permalinks > Save Changes
 * 
 * USAGE:
 * - Add advisors via the "Advisors" menu in WordPress admin
 * - Use shortcode [esop_advisor_map] to display the map
 * - Shortcode options: [esop_advisor_map height="600px" zoom="4" style="streets-v12"]
 * 
 * @package ESOP_Advisor_System
 * @since 1.0.0
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * =============================================================================
 * SECTION 1: CONSTANTS AND INITIALIZATION
 * =============================================================================
 * Define plugin constants and set up initialization hooks.
 */

// Plugin version for cache busting and updates
define( 'ESOP_ADVISOR_VERSION', '1.0.0' );

// Plugin directory path for file includes
define( 'ESOP_ADVISOR_PATH', __FILE__ );

/**
 * =============================================================================
 * SECTION 2: ADMIN NOTICES FOR CONFIGURATION
 * =============================================================================
 * Display helpful notices when MapBox token is not configured.
 */

/**
 * Check if MapBox token is defined and display admin notice if missing.
 * 
 * This function runs on every admin page load to ensure administrators
 * are aware when the MapBox integration is not configured.
 *
 * @since 1.0.0
 * @return void
 */
function esop_advisor_check_mapbox_token() {
    // Only show to users who can manage options (administrators)
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Check if token is defined and not empty
    if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) || empty( MAPBOX_ACCESS_TOKEN ) ) {
        ?>
        <div class="notice notice-error">
            <h3 style="margin-bottom: 10px;">⚠️ ESOP Advisor System: MapBox Token Required</h3>
            <p>The MapBox access token is not configured. The advisor map will not function until this is set up.</p>
            <p><strong>To fix this, add the following line to your <code>wp-config.php</code> file:</strong></p>
            <pre style="background: #f5f5f5; padding: 15px; border-left: 4px solid #dc3545; margin: 10px 0;">define( 'MAPBOX_ACCESS_TOKEN', 'your-mapbox-token-here' );</pre>
            <p><strong>To get a MapBox token:</strong></p>
            <ol>
                <li>Go to <a href="https://www.mapbox.com/" target="_blank">mapbox.com</a> and create a free account</li>
                <li>Navigate to your <a href="https://account.mapbox.com/access-tokens/" target="_blank">Access Tokens page</a></li>
                <li>Copy your default public token or create a new one</li>
                <li>Add the token to wp-config.php as shown above</li>
            </ol>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'esop_advisor_check_mapbox_token' );

/**
 * =============================================================================
 * SECTION 3: CUSTOM POST TYPE REGISTRATION
 * =============================================================================
 * Register the "Advisors" custom post type with all necessary labels and settings.
 */

/**
 * Register the Advisors custom post type.
 * 
 * Creates a fully-featured CPT with admin UI, REST API support,
 * and proper capability mapping.
 *
 * @since 1.0.0
 * @return void
 */
function esop_advisor_register_post_type() {
    
    // Define all labels for the CPT admin interface
    $labels = array(
        'name'                  => _x( 'Advisors', 'Post type general name', 'esop-advisor' ),
        'singular_name'         => _x( 'Advisor', 'Post type singular name', 'esop-advisor' ),
        'menu_name'             => _x( 'Advisors', 'Admin Menu text', 'esop-advisor' ),
        'name_admin_bar'        => _x( 'Advisor', 'Add New on Toolbar', 'esop-advisor' ),
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
        'archives'              => _x( 'Advisor Archives', 'The post type archive label', 'esop-advisor' ),
        'insert_into_item'      => _x( 'Insert into advisor', 'Overrides the "Insert into post" phrase', 'esop-advisor' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this advisor', 'Overrides the "Uploaded to this post" phrase', 'esop-advisor' ),
        'filter_items_list'     => _x( 'Filter advisors list', 'Screen reader text', 'esop-advisor' ),
        'items_list_navigation' => _x( 'Advisors list navigation', 'Screen reader text', 'esop-advisor' ),
        'items_list'            => _x( 'Advisors list', 'Screen reader text', 'esop-advisor' ),
    );

    // Define CPT arguments
    $args = array(
        'labels'             => $labels,
        'public'             => true,                    // Show in front-end
        'publicly_queryable' => true,                    // Allow front-end queries
        'show_ui'            => true,                    // Show admin UI
        'show_in_menu'       => true,                    // Show in admin menu
        'query_var'          => true,                    // Enable query var
        'rewrite'            => array( 'slug' => 'advisor', 'with_front' => false ),
        'capability_type'    => 'post',                  // Use default post capabilities
        'has_archive'        => 'advisors',              // Enable archive at /advisors/
        'hierarchical'       => false,                   // Not hierarchical (no parent/child)
        'menu_position'      => 25,                      // Position in admin menu (after Comments)
        'menu_icon'          => 'dashicons-businessman', // Admin menu icon
        'supports'           => array(
            'title',           // Advisor name
            'editor',          // Biography/description
            'thumbnail',       // Advisor photo
            'excerpt',         // Short bio
            'revisions',       // Track changes
        ),
        'show_in_rest'       => true,                    // Enable Gutenberg & REST API
        'rest_base'          => 'advisors',              // REST API base slug
    );

    register_post_type( 'esop_advisor', $args );
}
add_action( 'init', 'esop_advisor_register_post_type' );

/**
 * Flush rewrite rules on plugin activation.
 * 
 * Since MU plugins don't have activation hooks, we check if rules need flushing
 * by looking for our post type in the rewrite rules.
 *
 * @since 1.0.0
 * @return void
 */
function esop_advisor_maybe_flush_rewrite_rules() {
    // Only check in admin, and only once per page load
    if ( ! is_admin() ) {
        return;
    }
    
    // Check if we've already flushed for this version
    $flushed_version = get_option( 'esop_advisor_flushed_version', '' );
    
    if ( $flushed_version !== ESOP_ADVISOR_VERSION ) {
        // Register post type first (in case it hasn't been registered yet)
        esop_advisor_register_post_type();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Mark as flushed for this version
        update_option( 'esop_advisor_flushed_version', ESOP_ADVISOR_VERSION );
    }
}
add_action( 'admin_init', 'esop_advisor_maybe_flush_rewrite_rules' );

/**
 * =============================================================================
 * SECTION 4: META BOX REGISTRATION
 * =============================================================================
 * Register custom meta boxes for advisor data entry.
 */

/**
 * Register meta boxes for the Advisor edit screen.
 * 
 * Creates two meta boxes:
 * 1. Address Geocoder - prominent tool for looking up coordinates
 * 2. Advisor Details - all custom fields for the advisor
 *
 * @since 1.0.0
 * @return void
 */
function esop_advisor_register_meta_boxes() {
    
    // Address Geocoder meta box - positioned at top for prominence
    add_meta_box(
        'esop_advisor_geocoder',           // Unique ID
        '📍 Address Geocoder - Look Up Coordinates', // Title with icon
        'esop_advisor_geocoder_callback',  // Callback function
        'esop_advisor',                    // Post type
        'normal',                          // Context (normal = main column)
        'high'                             // Priority (high = top of column)
    );
    
    // Advisor Details meta box - contains all custom fields
    add_meta_box(
        'esop_advisor_details',            // Unique ID
        'Advisor Details',                 // Title
        'esop_advisor_details_callback',   // Callback function
        'esop_advisor',                    // Post type
        'normal',                          // Context
        'high'                             // Priority
    );
}
add_action( 'add_meta_boxes', 'esop_advisor_register_meta_boxes' );

/**
 * Render the Address Geocoder meta box.
 * 
 * Provides a user-friendly interface for looking up coordinates
 * from a street address using MapBox's geocoding API.
 *
 * @since 1.0.0
 * @param WP_Post $post The current post object.
 * @return void
 */
function esop_advisor_geocoder_callback( $post ) {
    // Get existing values for pre-populating the geocoder
    $address = get_post_meta( $post->ID, '_esop_advisor_address', true );
    $city    = get_post_meta( $post->ID, '_esop_advisor_city', true );
    $state   = get_post_meta( $post->ID, '_esop_advisor_state', true );
    $zip     = get_post_meta( $post->ID, '_esop_advisor_zip', true );
    $lat     = get_post_meta( $post->ID, '_esop_advisor_latitude', true );
    $lng     = get_post_meta( $post->ID, '_esop_advisor_longitude', true );
    
    // Build full address string for geocoder input
    $full_address = trim( implode( ', ', array_filter( array( $address, $city, $state, $zip ) ) ) );
    
    // Check if coordinates exist for visual feedback
    $has_coords = ! empty( $lat ) && ! empty( $lng );
    ?>
    
    <div class="esop-geocoder-box" style="background: <?php echo $has_coords ? '#d4edda' : '#fff3cd'; ?>; border: 1px solid <?php echo $has_coords ? '#c3e6cb' : '#ffc107'; ?>; border-radius: 5px; padding: 20px; margin-bottom: 15px;">
        
        <?php if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) || empty( MAPBOX_ACCESS_TOKEN ) ) : ?>
            <!-- Warning when MapBox token is not configured -->
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; padding: 15px; margin-bottom: 15px;">
                <strong>⚠️ MapBox Token Not Configured</strong><br>
                The geocoder requires a MapBox access token. Please add <code>define('MAPBOX_ACCESS_TOKEN', 'your-token');</code> to wp-config.php
            </div>
        <?php endif; ?>
        
        <!-- Status indicator showing if coordinates are set -->
        <div style="margin-bottom: 15px; padding: 10px; border-radius: 4px; background: <?php echo $has_coords ? '#28a745' : '#ffc107'; ?>; color: <?php echo $has_coords ? '#fff' : '#856404'; ?>;">
            <?php if ( $has_coords ) : ?>
                ✅ <strong>Coordinates Set:</strong> <?php echo esc_html( $lat ); ?>, <?php echo esc_html( $lng ); ?>
            <?php else : ?>
                ⚠️ <strong>Coordinates Required:</strong> Use the lookup tool below or enter manually in Advisor Details
            <?php endif; ?>
        </div>
        
        <h4 style="margin-top: 0;">How to Get Coordinates:</h4>
        <ol style="margin-left: 20px; color: #666;">
            <li>Enter the full address below (or it will auto-populate from Advisor Details)</li>
            <li>Click "Look Up Coordinates" to search</li>
            <li>Select the correct result from the dropdown</li>
            <li>Coordinates will automatically populate in Advisor Details below</li>
        </ol>
        
        <div style="display: flex; gap: 10px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <label for="esop_geocoder_address" style="display: block; margin-bottom: 5px; font-weight: bold;">
                    Full Address:
                </label>
                <input type="text" 
                       id="esop_geocoder_address" 
                       style="width: 100%; padding: 8px;" 
                       placeholder="123 Main St, City, State ZIP"
                       value="<?php echo esc_attr( $full_address ); ?>">
            </div>
            <button type="button" 
                    id="esop_geocoder_lookup" 
                    class="button button-primary button-large"
                    style="height: 38px;"
                    <?php echo ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) || empty( MAPBOX_ACCESS_TOKEN ) ) ? 'disabled' : ''; ?>>
                🔍 Look Up Coordinates
            </button>
        </div>
        
        <!-- Results dropdown (hidden by default) -->
        <div id="esop_geocoder_results" style="margin-top: 15px; display: none;">
            <label for="esop_geocoder_select" style="display: block; margin-bottom: 5px; font-weight: bold;">
                Select Location:
            </label>
            <select id="esop_geocoder_select" style="width: 100%; padding: 8px;">
                <option value="">-- Select a result --</option>
            </select>
        </div>
        
        <!-- Feedback message area -->
        <div id="esop_geocoder_feedback" style="margin-top: 15px; display: none; padding: 10px; border-radius: 4px;"></div>
        
    </div>
    
    <?php
}

/**
 * Render the Advisor Details meta box.
 * 
 * Contains all custom fields for storing advisor information
 * including contact details and location coordinates.
 *
 * @since 1.0.0
 * @param WP_Post $post The current post object.
 * @return void
 */
function esop_advisor_details_callback( $post ) {
    // Add nonce for security verification on save
    wp_nonce_field( 'esop_advisor_save_meta', 'esop_advisor_nonce' );
    
    // Retrieve existing meta values
    $company   = get_post_meta( $post->ID, '_esop_advisor_company', true );
    $address   = get_post_meta( $post->ID, '_esop_advisor_address', true );
    $city      = get_post_meta( $post->ID, '_esop_advisor_city', true );
    $state     = get_post_meta( $post->ID, '_esop_advisor_state', true );
    $zip       = get_post_meta( $post->ID, '_esop_advisor_zip', true );
    $phone     = get_post_meta( $post->ID, '_esop_advisor_phone', true );
    $email     = get_post_meta( $post->ID, '_esop_advisor_email', true );
    $website   = get_post_meta( $post->ID, '_esop_advisor_website', true );
    $latitude  = get_post_meta( $post->ID, '_esop_advisor_latitude', true );
    $longitude = get_post_meta( $post->ID, '_esop_advisor_longitude', true );
    
    // US States array for dropdown
    $us_states = array(
        '' => '-- Select State --',
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
    ?>
    
    <style>
        .esop-meta-table { width: 100%; border-collapse: collapse; }
        .esop-meta-table th { text-align: left; padding: 12px 10px; width: 150px; vertical-align: top; }
        .esop-meta-table td { padding: 8px 10px; }
        .esop-meta-table input[type="text"],
        .esop-meta-table input[type="email"],
        .esop-meta-table input[type="url"],
        .esop-meta-table input[type="tel"],
        .esop-meta-table select { width: 100%; padding: 8px; }
        .esop-meta-table .description { color: #666; font-size: 12px; margin-top: 5px; }
        .esop-coord-row { background: #f9f9f9; }
        .esop-required { color: #dc3545; }
        .esop-coord-field { background: #fffde7 !important; }
    </style>
    
    <table class="esop-meta-table">
        <!-- Company Name -->
        <tr>
            <th><label for="esop_advisor_company">Company Name</label></th>
            <td>
                <input type="text" 
                       id="esop_advisor_company" 
                       name="esop_advisor_company" 
                       value="<?php echo esc_attr( $company ); ?>" 
                       placeholder="ESOP Advisory Group, Inc.">
            </td>
        </tr>
        
        <!-- Street Address -->
        <tr>
            <th><label for="esop_advisor_address">Street Address</label></th>
            <td>
                <input type="text" 
                       id="esop_advisor_address" 
                       name="esop_advisor_address" 
                       value="<?php echo esc_attr( $address ); ?>" 
                       placeholder="123 Main Street, Suite 100">
                <p class="description">Full street address including suite/unit number</p>
            </td>
        </tr>
        
        <!-- City -->
        <tr>
            <th><label for="esop_advisor_city">City</label></th>
            <td>
                <input type="text" 
                       id="esop_advisor_city" 
                       name="esop_advisor_city" 
                       value="<?php echo esc_attr( $city ); ?>" 
                       placeholder="San Francisco">
            </td>
        </tr>
        
        <!-- State -->
        <tr>
            <th><label for="esop_advisor_state">State</label></th>
            <td>
                <select id="esop_advisor_state" name="esop_advisor_state">
                    <?php foreach ( $us_states as $abbr => $name ) : ?>
                        <option value="<?php echo esc_attr( $abbr ); ?>" <?php selected( $state, $abbr ); ?>>
                            <?php echo esc_html( $name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        
        <!-- ZIP Code -->
        <tr>
            <th><label for="esop_advisor_zip">ZIP Code</label></th>
            <td>
                <input type="text" 
                       id="esop_advisor_zip" 
                       name="esop_advisor_zip" 
                       value="<?php echo esc_attr( $zip ); ?>" 
                       placeholder="94105"
                       style="width: 150px;">
            </td>
        </tr>
        
        <!-- Phone -->
        <tr>
            <th><label for="esop_advisor_phone">Phone</label></th>
            <td>
                <input type="tel" 
                       id="esop_advisor_phone" 
                       name="esop_advisor_phone" 
                       value="<?php echo esc_attr( $phone ); ?>" 
                       placeholder="(555) 123-4567">
            </td>
        </tr>
        
        <!-- Email -->
        <tr>
            <th><label for="esop_advisor_email">Email</label></th>
            <td>
                <input type="email" 
                       id="esop_advisor_email" 
                       name="esop_advisor_email" 
                       value="<?php echo esc_attr( $email ); ?>" 
                       placeholder="advisor@example.com">
            </td>
        </tr>
        
        <!-- Website -->
        <tr>
            <th><label for="esop_advisor_website">Website</label></th>
            <td>
                <input type="url" 
                       id="esop_advisor_website" 
                       name="esop_advisor_website" 
                       value="<?php echo esc_attr( $website ); ?>" 
                       placeholder="https://www.example.com">
            </td>
        </tr>
        
        <!-- Coordinates Section Header -->
        <tr class="esop-coord-row">
            <td colspan="2" style="background: #e9ecef; padding: 15px;">
                <strong>📍 Location Coordinates</strong> <span class="esop-required">* Required for map display</span>
                <p class="description" style="margin-top: 5px; margin-bottom: 0;">
                    Use the Address Geocoder above to automatically populate these fields, or enter manually.
                    Format: Decimal degrees (e.g., 37.7749 for latitude, -122.4194 for longitude)
                </p>
            </td>
        </tr>
        
        <!-- Latitude -->
        <tr class="esop-coord-row">
            <th><label for="esop_advisor_latitude">Latitude <span class="esop-required">*</span></label></th>
            <td>
                <input type="text" 
                       id="esop_advisor_latitude" 
                       name="esop_advisor_latitude" 
                       value="<?php echo esc_attr( $latitude ); ?>" 
                       placeholder="37.7749"
                       class="esop-coord-field"
                       style="width: 200px;"
                       pattern="-?[0-9]*\.?[0-9]+"
                       title="Enter a valid decimal number (e.g., 37.7749)">
                <p class="description">Valid range: -90 to 90</p>
            </td>
        </tr>
        
        <!-- Longitude -->
        <tr class="esop-coord-row">
            <th><label for="esop_advisor_longitude">Longitude <span class="esop-required">*</span></label></th>
            <td>
                <input type="text" 
                       id="esop_advisor_longitude" 
                       name="esop_advisor_longitude" 
                       value="<?php echo esc_attr( $longitude ); ?>" 
                       placeholder="-122.4194"
                       class="esop-coord-field"
                       style="width: 200px;"
                       pattern="-?[0-9]*\.?[0-9]+"
                       title="Enter a valid decimal number (e.g., -122.4194)">
                <p class="description">Valid range: -180 to 180</p>
            </td>
        </tr>
    </table>
    
    <?php
}

/**
 * =============================================================================
 * SECTION 5: META DATA SAVING AND VALIDATION
 * =============================================================================
 * Handle saving of custom meta data with validation.
 */

/**
 * Save advisor meta data when the post is saved.
 * 
 * Includes validation for coordinates and sanitization of all inputs.
 *
 * @since 1.0.0
 * @param int $post_id The ID of the post being saved.
 * @return void
 */
function esop_advisor_save_meta( $post_id ) {
    
    // Security checks
    
    // Verify nonce
    if ( ! isset( $_POST['esop_advisor_nonce'] ) || 
         ! wp_verify_nonce( $_POST['esop_advisor_nonce'], 'esop_advisor_save_meta' ) ) {
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
    
    // Check post type
    if ( get_post_type( $post_id ) !== 'esop_advisor' ) {
        return;
    }
    
    // Define fields to save with their sanitization callbacks
    $fields = array(
        'esop_advisor_company'   => 'sanitize_text_field',
        'esop_advisor_address'   => 'sanitize_text_field',
        'esop_advisor_city'      => 'sanitize_text_field',
        'esop_advisor_state'     => 'sanitize_text_field',
        'esop_advisor_zip'       => 'sanitize_text_field',
        'esop_advisor_phone'     => 'sanitize_text_field',
        'esop_advisor_email'     => 'sanitize_email',
        'esop_advisor_website'   => 'esc_url_raw',
        'esop_advisor_latitude'  => 'esop_advisor_sanitize_coordinate',
        'esop_advisor_longitude' => 'esop_advisor_sanitize_coordinate',
    );
    
    // Save each field
    foreach ( $fields as $field => $sanitize_callback ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize_callback, $_POST[ $field ] );
            update_post_meta( $post_id, '_' . $field, $value );
        }
    }
}
add_action( 'save_post', 'esop_advisor_save_meta' );

/**
 * Sanitize coordinate values.
 * 
 * Ensures coordinates are valid decimal numbers within acceptable ranges.
 *
 * @since 1.0.0
 * @param string $value The coordinate value to sanitize.
 * @return string Sanitized coordinate or empty string if invalid.
 */
function esop_advisor_sanitize_coordinate( $value ) {
    // Remove whitespace
    $value = trim( $value );
    
    // Return empty if no value
    if ( $value === '' ) {
        return '';
    }
    
    // Check if it's a valid decimal number
    if ( ! is_numeric( $value ) ) {
        return '';
    }
    
    // Convert to float and format
    $coord = floatval( $value );
    
    // Return formatted to 6 decimal places
    return number_format( $coord, 6, '.', '' );
}

/**
 * Validate coordinates before publishing.
 * 
 * Prevents publishing advisors without valid coordinates.
 *
 * @since 1.0.0
 * @param array $data    Sanitized post data.
 * @param array $postarr Raw post data.
 * @return array Modified post data.
 */
function esop_advisor_validate_before_publish( $data, $postarr ) {
    
    // Only validate esop_advisor posts
    if ( $data['post_type'] !== 'esop_advisor' ) {
        return $data;
    }
    
    // Only validate when trying to publish
    if ( $data['post_status'] !== 'publish' ) {
        return $data;
    }
    
    // Check for coordinates
    $latitude  = isset( $postarr['esop_advisor_latitude'] ) ? trim( $postarr['esop_advisor_latitude'] ) : '';
    $longitude = isset( $postarr['esop_advisor_longitude'] ) ? trim( $postarr['esop_advisor_longitude'] ) : '';
    
    // If this is an existing post, also check saved meta
    if ( ! empty( $postarr['ID'] ) ) {
        if ( empty( $latitude ) ) {
            $latitude = get_post_meta( $postarr['ID'], '_esop_advisor_latitude', true );
        }
        if ( empty( $longitude ) ) {
            $longitude = get_post_meta( $postarr['ID'], '_esop_advisor_longitude', true );
        }
    }
    
    $errors = array();
    
    // Validate latitude
    if ( empty( $latitude ) ) {
        $errors[] = 'Latitude is required';
    } elseif ( ! is_numeric( $latitude ) || floatval( $latitude ) < -90 || floatval( $latitude ) > 90 ) {
        $errors[] = 'Latitude must be a number between -90 and 90';
    }
    
    // Validate longitude
    if ( empty( $longitude ) ) {
        $errors[] = 'Longitude is required';
    } elseif ( ! is_numeric( $longitude ) || floatval( $longitude ) < -180 || floatval( $longitude ) > 180 ) {
        $errors[] = 'Longitude must be a number between -180 and 180';
    }
    
    // If there are errors, prevent publishing and set to draft
    if ( ! empty( $errors ) ) {
        $data['post_status'] = 'draft';
        
        // Store error messages in transient for display
        set_transient( 'esop_advisor_errors_' . $postarr['ID'], $errors, 30 );
    }
    
    return $data;
}
add_filter( 'wp_insert_post_data', 'esop_advisor_validate_before_publish', 10, 2 );

/**
 * Display validation error messages.
 *
 * @since 1.0.0
 * @return void
 */
function esop_advisor_display_validation_errors() {
    global $post;
    
    if ( ! $post || $post->post_type !== 'esop_advisor' ) {
        return;
    }
    
    $errors = get_transient( 'esop_advisor_errors_' . $post->ID );
    
    if ( $errors ) {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><strong>⚠️ Could not publish advisor - please fix the following issues:</strong></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <?php foreach ( $errors as $error ) : ?>
                    <li><?php echo esc_html( $error ); ?></li>
                <?php endforeach; ?>
            </ul>
            <p>Use the <strong>Address Geocoder</strong> at the top of this page to look up coordinates.</p>
        </div>
        <?php
        
        // Delete the transient after displaying
        delete_transient( 'esop_advisor_errors_' . $post->ID );
    }
}
add_action( 'admin_notices', 'esop_advisor_display_validation_errors' );

/**
 * =============================================================================
 * SECTION 6: ADMIN LIST COLUMNS
 * =============================================================================
 * Add and configure custom columns for the advisors list table.
 */

/**
 * Add custom columns to the advisors list table.
 *
 * @since 1.0.0
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function esop_advisor_add_admin_columns( $columns ) {
    // Build new column order
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        
        // Add custom columns after title
        if ( $key === 'title' ) {
            $new_columns['company'] = __( 'Company', 'esop-advisor' );
            $new_columns['city']    = __( 'City', 'esop-advisor' );
            $new_columns['state']   = __( 'State', 'esop-advisor' );
            $new_columns['phone']   = __( 'Phone', 'esop-advisor' );
            $new_columns['coords']  = __( 'Coordinates', 'esop-advisor' );
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_esop_advisor_posts_columns', 'esop_advisor_add_admin_columns' );

/**
 * Populate custom column content.
 *
 * @since 1.0.0
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 * @return void
 */
function esop_advisor_populate_admin_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'company':
            $company = get_post_meta( $post_id, '_esop_advisor_company', true );
            echo esc_html( $company ?: '—' );
            break;
            
        case 'city':
            $city = get_post_meta( $post_id, '_esop_advisor_city', true );
            echo esc_html( $city ?: '—' );
            break;
            
        case 'state':
            $state = get_post_meta( $post_id, '_esop_advisor_state', true );
            echo esc_html( $state ?: '—' );
            break;
            
        case 'phone':
            $phone = get_post_meta( $post_id, '_esop_advisor_phone', true );
            echo esc_html( $phone ?: '—' );
            break;
            
        case 'coords':
            $lat = get_post_meta( $post_id, '_esop_advisor_latitude', true );
            $lng = get_post_meta( $post_id, '_esop_advisor_longitude', true );
            
            if ( $lat && $lng ) {
                echo '<span style="color: #28a745;">✓</span> ';
                echo '<small>' . esc_html( round( $lat, 4 ) . ', ' . round( $lng, 4 ) ) . '</small>';
            } else {
                echo '<span style="color: #dc3545;">✗ Missing</span>';
            }
            break;
    }
}
add_action( 'manage_esop_advisor_posts_custom_column', 'esop_advisor_populate_admin_columns', 10, 2 );

/**
 * Make custom columns sortable.
 *
 * @since 1.0.0
 * @param array $columns Sortable columns.
 * @return array Modified sortable columns.
 */
function esop_advisor_sortable_columns( $columns ) {
    $columns['company'] = 'company';
    $columns['city']    = 'city';
    $columns['state']   = 'state';
    return $columns;
}
add_filter( 'manage_edit-esop_advisor_sortable_columns', 'esop_advisor_sortable_columns' );

/**
 * Handle sorting by custom meta fields.
 *
 * @since 1.0.0
 * @param WP_Query $query The query object.
 * @return void
 */
function esop_advisor_sort_by_custom_columns( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    $meta_sorts = array(
        'company' => '_esop_advisor_company',
        'city'    => '_esop_advisor_city',
        'state'   => '_esop_advisor_state',
    );
    
    if ( isset( $meta_sorts[ $orderby ] ) ) {
        $query->set( 'meta_key', $meta_sorts[ $orderby ] );
        $query->set( 'orderby', 'meta_value' );
    }
}
add_action( 'pre_get_posts', 'esop_advisor_sort_by_custom_columns' );

/**
 * =============================================================================
 * SECTION 7: QUICK EDIT SUPPORT
 * =============================================================================
 * Enable quick edit for custom fields.
 */

/**
 * Add quick edit fields.
 *
 * @since 1.0.0
 * @param string $column_name Column name.
 * @param string $post_type   Post type.
 * @return void
 */
function esop_advisor_quick_edit_fields( $column_name, $post_type ) {
    if ( $post_type !== 'esop_advisor' ) {
        return;
    }
    
    // Only add once, use company column as trigger
    if ( $column_name !== 'company' ) {
        return;
    }
    
    // US States array
    $us_states = array(
        '' => '-- Select --',
        'AL' => 'AL', 'AK' => 'AK', 'AZ' => 'AZ', 'AR' => 'AR', 'CA' => 'CA',
        'CO' => 'CO', 'CT' => 'CT', 'DE' => 'DE', 'DC' => 'DC', 'FL' => 'FL',
        'GA' => 'GA', 'HI' => 'HI', 'ID' => 'ID', 'IL' => 'IL', 'IN' => 'IN',
        'IA' => 'IA', 'KS' => 'KS', 'KY' => 'KY', 'LA' => 'LA', 'ME' => 'ME',
        'MD' => 'MD', 'MA' => 'MA', 'MI' => 'MI', 'MN' => 'MN', 'MS' => 'MS',
        'MO' => 'MO', 'MT' => 'MT', 'NE' => 'NE', 'NV' => 'NV', 'NH' => 'NH',
        'NJ' => 'NJ', 'NM' => 'NM', 'NY' => 'NY', 'NC' => 'NC', 'ND' => 'ND',
        'OH' => 'OH', 'OK' => 'OK', 'OR' => 'OR', 'PA' => 'PA', 'RI' => 'RI',
        'SC' => 'SC', 'SD' => 'SD', 'TN' => 'TN', 'TX' => 'TX', 'UT' => 'UT',
        'VT' => 'VT', 'VA' => 'VA', 'WA' => 'WA', 'WV' => 'WV', 'WI' => 'WI',
        'WY' => 'WY',
    );
    ?>
    
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <h4>Advisor Details</h4>
            
            <label>
                <span class="title">Company</span>
                <span class="input-text-wrap">
                    <input type="text" name="esop_advisor_company" class="esop_advisor_company" value="">
                </span>
            </label>
            
            <label>
                <span class="title">City</span>
                <span class="input-text-wrap">
                    <input type="text" name="esop_advisor_city" class="esop_advisor_city" value="">
                </span>
            </label>
            
            <label>
                <span class="title">State</span>
                <span class="input-text-wrap">
                    <select name="esop_advisor_state" class="esop_advisor_state">
                        <?php foreach ( $us_states as $abbr => $name ) : ?>
                            <option value="<?php echo esc_attr( $abbr ); ?>"><?php echo esc_html( $name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </span>
            </label>
        </div>
    </fieldset>
    
    <?php
}
add_action( 'quick_edit_custom_box', 'esop_advisor_quick_edit_fields', 10, 2 );

/**
 * Save quick edit data.
 *
 * @since 1.0.0
 * @param int $post_id Post ID.
 * @return void
 */
function esop_advisor_save_quick_edit( $post_id ) {
    // Check post type
    if ( get_post_type( $post_id ) !== 'esop_advisor' ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Check if this is a quick edit (no nonce for meta box)
    if ( isset( $_POST['esop_advisor_nonce'] ) ) {
        return; // This is from the full edit, handled elsewhere
    }
    
    // Save quick edit fields
    $quick_edit_fields = array(
        'esop_advisor_company' => 'sanitize_text_field',
        'esop_advisor_city'    => 'sanitize_text_field',
        'esop_advisor_state'   => 'sanitize_text_field',
    );
    
    foreach ( $quick_edit_fields as $field => $sanitize ) {
        if ( isset( $_POST[ $field ] ) ) {
            $value = call_user_func( $sanitize, $_POST[ $field ] );
            update_post_meta( $post_id, '_' . $field, $value );
        }
    }
}
add_action( 'save_post', 'esop_advisor_save_quick_edit' );

/**
 * =============================================================================
 * SECTION 8: ADMIN SCRIPTS AND STYLES
 * =============================================================================
 * Enqueue JavaScript and CSS for admin functionality.
 */

/**
 * Enqueue admin scripts and styles.
 *
 * @since 1.0.0
 * @param string $hook Current admin page hook.
 * @return void
 */
function esop_advisor_admin_scripts( $hook ) {
    global $post_type;
    
    // Only load on advisor screens
    if ( $post_type !== 'esop_advisor' ) {
        return;
    }
    
    // Enqueue on edit screens
    if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
        
        // Add inline script for geocoder functionality
        wp_add_inline_script( 'jquery', esop_advisor_get_geocoder_script(), 'after' );
    }
    
    // Enqueue on list screen for quick edit
    if ( $hook === 'edit.php' ) {
        wp_add_inline_script( 'jquery', esop_advisor_get_quick_edit_script(), 'after' );
    }
}
add_action( 'admin_enqueue_scripts', 'esop_advisor_admin_scripts' );

/**
 * Get the geocoder JavaScript code.
 *
 * @since 1.0.0
 * @return string JavaScript code.
 */
function esop_advisor_get_geocoder_script() {
    $mapbox_token = defined( 'MAPBOX_ACCESS_TOKEN' ) ? MAPBOX_ACCESS_TOKEN : '';
    
    ob_start();
    ?>
    jQuery(document).ready(function($) {
        var mapboxToken = '<?php echo esc_js( $mapbox_token ); ?>';
        
        // Geocoder lookup button click handler
        $('#esop_geocoder_lookup').on('click', function() {
            var address = $('#esop_geocoder_address').val().trim();
            var $button = $(this);
            var $results = $('#esop_geocoder_results');
            var $select = $('#esop_geocoder_select');
            var $feedback = $('#esop_geocoder_feedback');
            
            // Validate input
            if (!address) {
                showFeedback('Please enter an address to look up.', 'warning');
                return;
            }
            
            if (!mapboxToken) {
                showFeedback('MapBox token is not configured.', 'error');
                return;
            }
            
            // Update button state
            $button.prop('disabled', true).text('🔄 Searching...');
            $feedback.hide();
            
            // Call MapBox Geocoding API
            var apiUrl = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + 
                         encodeURIComponent(address) + '.json?access_token=' + 
                         mapboxToken + '&country=US&types=address,place&limit=5';
            
            $.ajax({
                url: apiUrl,
                dataType: 'json',
                success: function(data) {
                    $button.prop('disabled', false).text('🔍 Look Up Coordinates');
                    
                    if (data.features && data.features.length > 0) {
                        // Populate results dropdown
                        $select.empty().append('<option value="">-- Select a result --</option>');
                        
                        $.each(data.features, function(i, feature) {
                            var coords = feature.geometry.coordinates;
                            var optionValue = coords[1] + '|' + coords[0]; // lat|lng
                            var optionText = feature.place_name;
                            
                            $select.append(
                                $('<option></option>')
                                    .val(optionValue)
                                    .text(optionText)
                                    .data('feature', feature)
                            );
                        });
                        
                        $results.slideDown();
                        showFeedback('Found ' + data.features.length + ' result(s). Select one to populate coordinates.', 'success');
                    } else {
                        showFeedback('No results found for that address. Try a different format or check spelling.', 'warning');
                    }
                },
                error: function(xhr, status, error) {
                    $button.prop('disabled', false).text('🔍 Look Up Coordinates');
                    
                    var errorMsg = 'Error looking up address. ';
                    if (xhr.status === 401) {
                        errorMsg += 'Invalid MapBox token.';
                    } else if (xhr.status === 429) {
                        errorMsg += 'Rate limit exceeded. Please wait and try again.';
                    } else {
                        errorMsg += 'Please check your internet connection and try again.';
                    }
                    
                    showFeedback(errorMsg, 'error');
                }
            });
        });
        
        // Result selection handler
        $('#esop_geocoder_select').on('change', function() {
            var value = $(this).val();
            var $feedback = $('#esop_geocoder_feedback');
            
            if (!value) {
                return;
            }
            
            var coords = value.split('|');
            var lat = coords[0];
            var lng = coords[1];
            
            // Get selected feature data for additional info
            var feature = $(this).find(':selected').data('feature');
            
            // Populate coordinate fields
            $('#esop_advisor_latitude').val(parseFloat(lat).toFixed(6));
            $('#esop_advisor_longitude').val(parseFloat(lng).toFixed(6));
            
            // Visual feedback - highlight the fields
            $('#esop_advisor_latitude, #esop_advisor_longitude')
                .css('background-color', '#d4edda')
                .animate({backgroundColor: '#fffde7'}, 2000);
            
            // Try to extract and populate address components
            if (feature && feature.context) {
                $.each(feature.context, function(i, ctx) {
                    if (ctx.id.indexOf('place') === 0) {
                        $('#esop_advisor_city').val(ctx.text);
                    }
                    if (ctx.id.indexOf('region') === 0) {
                        $('#esop_advisor_state').val(ctx.short_code.replace('US-', ''));
                    }
                    if (ctx.id.indexOf('postcode') === 0) {
                        $('#esop_advisor_zip').val(ctx.text);
                    }
                });
            }
            
            showFeedback('✅ Coordinates populated! Latitude: ' + lat + ', Longitude: ' + lng, 'success');
            
            // Update the status indicator at the top
            $('.esop-geocoder-box > div:first-child')
                .css('background', '#28a745')
                .css('color', '#fff')
                .html('✅ <strong>Coordinates Set:</strong> ' + lat + ', ' + lng);
        });
        
        // Helper function to show feedback messages
        function showFeedback(message, type) {
            var $feedback = $('#esop_geocoder_feedback');
            var bgColor = type === 'success' ? '#d4edda' : 
                          type === 'warning' ? '#fff3cd' : '#f8d7da';
            var textColor = type === 'success' ? '#155724' : 
                            type === 'warning' ? '#856404' : '#721c24';
            
            $feedback
                .css({
                    'background': bgColor,
                    'color': textColor,
                    'border': '1px solid ' + (type === 'success' ? '#c3e6cb' : 
                                              type === 'warning' ? '#ffc107' : '#f5c6cb')
                })
                .html(message)
                .slideDown();
        }
        
        // Auto-populate geocoder address from existing fields on page load
        function updateGeocoderAddress() {
            var parts = [
                $('#esop_advisor_address').val(),
                $('#esop_advisor_city').val(),
                $('#esop_advisor_state').val(),
                $('#esop_advisor_zip').val()
            ].filter(function(p) { return p && p.trim(); });
            
            if (parts.length > 0) {
                $('#esop_geocoder_address').val(parts.join(', '));
            }
        }
        
        // Update geocoder address when detail fields change
        $('#esop_advisor_address, #esop_advisor_city, #esop_advisor_state, #esop_advisor_zip')
            .on('change blur', updateGeocoderAddress);
    });
    <?php
    return ob_get_clean();
}

/**
 * Get the quick edit JavaScript code.
 *
 * @since 1.0.0
 * @return string JavaScript code.
 */
function esop_advisor_get_quick_edit_script() {
    ob_start();
    ?>
    jQuery(document).ready(function($) {
        // Store the original quick edit function
        var $wp_inline_edit = inlineEditPost.edit;
        
        // Override the quick edit function
        inlineEditPost.edit = function(id) {
            // Call the original function
            $wp_inline_edit.apply(this, arguments);
            
            // Get the post ID
            var post_id = 0;
            if (typeof(id) === 'object') {
                post_id = parseInt(this.getId(id));
            }
            
            if (post_id > 0) {
                // Get the row data
                var $row = $('#post-' + post_id);
                var $editRow = $('#edit-' + post_id);
                
                // Extract values from the row
                var company = $row.find('.column-company').text().trim();
                var city = $row.find('.column-city').text().trim();
                var state = $row.find('.column-state').text().trim();
                
                // Handle empty values shown as dashes
                company = company === '—' ? '' : company;
                city = city === '—' ? '' : city;
                state = state === '—' ? '' : state;
                
                // Populate the quick edit fields
                $editRow.find('input.esop_advisor_company').val(company);
                $editRow.find('input.esop_advisor_city').val(city);
                $editRow.find('select.esop_advisor_state').val(state);
            }
        };
    });
    <?php
    return ob_get_clean();
}

/**
 * =============================================================================
 * SECTION 9: FRONTEND MAP SHORTCODE
 * =============================================================================
 * Create the shortcode for displaying the advisor map.
 */

/**
 * Register the advisor map shortcode.
 * 
 * Usage: [esop_advisor_map]
 * Options:
 *   - height: Map height (default: 500px)
 *   - zoom: Initial zoom level (default: 4)
 *   - style: MapBox style (default: streets-v12)
 *   - center_lat: Initial center latitude (default: 39.8283)
 *   - center_lng: Initial center longitude (default: -98.5795)
 *   - cluster: Enable clustering (default: true)
 *
 * @since 1.0.0
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function esop_advisor_map_shortcode( $atts ) {
    
    // Check for MapBox token
    if ( ! defined( 'MAPBOX_ACCESS_TOKEN' ) || empty( MAPBOX_ACCESS_TOKEN ) ) {
        if ( current_user_can( 'manage_options' ) ) {
            return '<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 5px; color: #721c24;">'
                 . '<strong>Admin Notice:</strong> MapBox access token is not configured. '
                 . 'Please add <code>define(\'MAPBOX_ACCESS_TOKEN\', \'your-token\');</code> to wp-config.php'
                 . '</div>';
        }
        return '<!-- ESOP Advisor Map: MapBox token not configured -->';
    }
    
    // Parse shortcode attributes
    $atts = shortcode_atts( array(
        'height'     => '500px',
        'zoom'       => 4,
        'style'      => 'streets-v12',
        'center_lat' => 39.8283,  // Center of US
        'center_lng' => -98.5795,
        'cluster'    => 'true',
    ), $atts, 'esop_advisor_map' );
    
    // Query published advisors with coordinates
    $advisors = get_posts( array(
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
    ) );
    
    // Build GeoJSON features array
    $features = array();
    
    foreach ( $advisors as $advisor ) {
        $lat       = get_post_meta( $advisor->ID, '_esop_advisor_latitude', true );
        $lng       = get_post_meta( $advisor->ID, '_esop_advisor_longitude', true );
        $company   = get_post_meta( $advisor->ID, '_esop_advisor_company', true );
        $address   = get_post_meta( $advisor->ID, '_esop_advisor_address', true );
        $city      = get_post_meta( $advisor->ID, '_esop_advisor_city', true );
        $state     = get_post_meta( $advisor->ID, '_esop_advisor_state', true );
        $zip       = get_post_meta( $advisor->ID, '_esop_advisor_zip', true );
        $phone     = get_post_meta( $advisor->ID, '_esop_advisor_phone', true );
        $email     = get_post_meta( $advisor->ID, '_esop_advisor_email', true );
        $website   = get_post_meta( $advisor->ID, '_esop_advisor_website', true );
        
        // Build location string
        $location_parts = array_filter( array( $city, $state, $zip ) );
        $location = implode( ', ', $location_parts );
        
        $features[] = array(
            'type'       => 'Feature',
            'geometry'   => array(
                'type'        => 'Point',
                'coordinates' => array( floatval( $lng ), floatval( $lat ) ),
            ),
            'properties' => array(
                'id'        => $advisor->ID,
                'name'      => $advisor->post_title,
                'company'   => $company,
                'address'   => $address,
                'location'  => $location,
                'city'      => $city,
                'state'     => $state,
                'phone'     => $phone,
                'email'     => $email,
                'website'   => $website,
                'permalink' => get_permalink( $advisor->ID ),
            ),
        );
    }
    
    // Build GeoJSON object
    $geojson = array(
        'type'     => 'FeatureCollection',
        'features' => $features,
    );
    
    // Generate unique ID for this map instance
    $map_id = 'esop-advisor-map-' . wp_rand( 1000, 9999 );
    
    // Build output HTML
    ob_start();
    ?>
    
    <!-- ESOP Advisor Map Container -->
    <div id="<?php echo esc_attr( $map_id ); ?>" 
         class="esop-advisor-map" 
         style="width: 100%; height: <?php echo esc_attr( $atts['height'] ); ?>; border-radius: 8px; overflow: hidden;">
    </div>
    
    <!-- MapBox GL JS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.3.0/mapbox-gl.js"></script>
    
    <style>
        /* Custom popup styling */
        .esop-advisor-popup {
            max-width: 300px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .esop-advisor-popup h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #1a1a1a;
        }
        .esop-advisor-popup .company {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .esop-advisor-popup .details {
            font-size: 13px;
            line-height: 1.5;
            color: #444;
        }
        .esop-advisor-popup .details p {
            margin: 4px 0;
        }
        .esop-advisor-popup .details a {
            color: #0066cc;
            text-decoration: none;
        }
        .esop-advisor-popup .details a:hover {
            text-decoration: underline;
        }
        .esop-advisor-popup .view-profile {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            background: #0066cc;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }
        .esop-advisor-popup .view-profile:hover {
            background: #0052a3;
            color: #fff;
        }
        
        /* Cluster styling */
        .esop-cluster {
            background: #0066cc;
            border-radius: 50%;
            color: #fff;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        
        /* MapBox popup overrides */
        .mapboxgl-popup-content {
            padding: 15px;
            border-radius: 8px;
        }
    </style>
    
    <script>
    (function() {
        // Wait for MapBox GL JS to load
        function initMap() {
            if (typeof mapboxgl === 'undefined') {
                setTimeout(initMap, 100);
                return;
            }
            
            // Set MapBox access token
            mapboxgl.accessToken = '<?php echo esc_js( MAPBOX_ACCESS_TOKEN ); ?>';
            
            // GeoJSON data
            var geojsonData = <?php echo wp_json_encode( $geojson ); ?>;
            
            // Configuration
            var config = {
                zoom: <?php echo intval( $atts['zoom'] ); ?>,
                center: [<?php echo floatval( $atts['center_lng'] ); ?>, <?php echo floatval( $atts['center_lat'] ); ?>],
                style: 'mapbox://styles/mapbox/<?php echo esc_js( $atts['style'] ); ?>',
                cluster: <?php echo $atts['cluster'] === 'true' ? 'true' : 'false'; ?>
            };
            
            // Initialize map
            var map = new mapboxgl.Map({
                container: '<?php echo esc_js( $map_id ); ?>',
                style: config.style,
                center: config.center,
                zoom: config.zoom
            });
            
            // Add navigation controls
            map.addControl(new mapboxgl.NavigationControl(), 'top-right');
            
            // Add fullscreen control
            map.addControl(new mapboxgl.FullscreenControl(), 'top-right');
            
            // When map loads, add data
            map.on('load', function() {
                
                // Add source
                map.addSource('advisors', {
                    type: 'geojson',
                    data: geojsonData,
                    cluster: config.cluster,
                    clusterMaxZoom: 14,
                    clusterRadius: 50
                });
                
                // Add cluster circles
                map.addLayer({
                    id: 'clusters',
                    type: 'circle',
                    source: 'advisors',
                    filter: ['has', 'point_count'],
                    paint: {
                        'circle-color': [
                            'step',
                            ['get', 'point_count'],
                            '#51bbd6',  // < 10
                            10, '#f1f075', // 10-29
                            30, '#f28cb1'  // >= 30
                        ],
                        'circle-radius': [
                            'step',
                            ['get', 'point_count'],
                            20,   // < 10
                            10, 30, // 10-29
                            30, 40  // >= 30
                        ],
                        'circle-stroke-width': 2,
                        'circle-stroke-color': '#fff'
                    }
                });
                
                // Add cluster count labels
                map.addLayer({
                    id: 'cluster-count',
                    type: 'symbol',
                    source: 'advisors',
                    filter: ['has', 'point_count'],
                    layout: {
                        'text-field': '{point_count_abbreviated}',
                        'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                        'text-size': 14
                    },
                    paint: {
                        'text-color': '#333'
                    }
                });
                
                // Add individual advisor markers
                map.addLayer({
                    id: 'unclustered-point',
                    type: 'circle',
                    source: 'advisors',
                    filter: ['!', ['has', 'point_count']],
                    paint: {
                        'circle-color': '#0066cc',
                        'circle-radius': 10,
                        'circle-stroke-width': 2,
                        'circle-stroke-color': '#fff'
                    }
                });
                
                // Click on cluster to zoom in
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
                
                // Click on individual marker to show popup
                map.on('click', 'unclustered-point', function(e) {
                    var coordinates = e.features[0].geometry.coordinates.slice();
                    var props = e.features[0].properties;
                    
                    // Build popup content
                    var html = '<div class="esop-advisor-popup">';
                    html += '<h3>' + escapeHtml(props.name) + '</h3>';
                    
                    if (props.company) {
                        html += '<div class="company">' + escapeHtml(props.company) + '</div>';
                    }
                    
                    html += '<div class="details">';
                    
                    if (props.address) {
                        html += '<p>📍 ' + escapeHtml(props.address) + '</p>';
                    }
                    if (props.location) {
                        html += '<p>' + escapeHtml(props.location) + '</p>';
                    }
                    if (props.phone) {
                        html += '<p>📞 <a href="tel:' + escapeHtml(props.phone) + '">' + escapeHtml(props.phone) + '</a></p>';
                    }
                    if (props.email) {
                        html += '<p>✉️ <a href="mailto:' + escapeHtml(props.email) + '">' + escapeHtml(props.email) + '</a></p>';
                    }
                    if (props.website) {
                        html += '<p>🌐 <a href="' + escapeHtml(props.website) + '" target="_blank">Website</a></p>';
                    }
                    
                    html += '</div>';
                    html += '<a href="' + escapeHtml(props.permalink) + '" class="view-profile">View Full Profile</a>';
                    html += '</div>';
                    
                    // Ensure popup is on top of marker
                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                    }
                    
                    new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(html)
                        .addTo(map);
                });
                
                // Change cursor on hover
                map.on('mouseenter', 'clusters', function() {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'clusters', function() {
                    map.getCanvas().style.cursor = '';
                });
                map.on('mouseenter', 'unclustered-point', function() {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'unclustered-point', function() {
                    map.getCanvas().style.cursor = '';
                });
                
                // If we have features, fit map to bounds
                if (geojsonData.features.length > 0) {
                    var bounds = new mapboxgl.LngLatBounds();
                    
                    geojsonData.features.forEach(function(feature) {
                        bounds.extend(feature.geometry.coordinates);
                    });
                    
                    map.fitBounds(bounds, {
                        padding: 50,
                        maxZoom: 12
                    });
                }
            });
            
            // Helper function to escape HTML
            function escapeHtml(text) {
                if (!text) return '';
                var div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }
        }
        
        // Start initialization
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMap);
        } else {
            initMap();
        }
    })();
    </script>
    
    <?php
    return ob_get_clean();
}
add_shortcode( 'esop_advisor_map', 'esop_advisor_map_shortcode' );

/**
 * =============================================================================
 * SECTION 10: REST API ENDPOINTS
 * =============================================================================
 * Register custom REST API endpoints for AJAX access to advisor data.
 */

/**
 * Register REST API routes.
 *
 * @since 1.0.0
 * @return void
 */
function esop_advisor_register_rest_routes() {
    
    // Endpoint to get all advisors as GeoJSON
    register_rest_route( 'esop-advisor/v1', '/geojson', array(
        'methods'             => 'GET',
        'callback'            => 'esop_advisor_rest_get_geojson',
        'permission_callback' => '__return_true',
    ) );
    
    // Endpoint to get single advisor details
    register_rest_route( 'esop-advisor/v1', '/advisor/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'esop_advisor_rest_get_advisor',
        'permission_callback' => '__return_true',
        'args'                => array(
            'id' => array(
                'validate_callback' => function( $param ) {
                    return is_numeric( $param );
                },
            ),
        ),
    ) );
}
add_action( 'rest_api_init', 'esop_advisor_register_rest_routes' );

/**
 * REST callback to get GeoJSON data.
 *
 * @since 1.0.0
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object.
 */
function esop_advisor_rest_get_geojson( $request ) {
    
    $advisors = get_posts( array(
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
    ) );
    
    $features = array();
    
    foreach ( $advisors as $advisor ) {
        $features[] = array(
            'type'       => 'Feature',
            'geometry'   => array(
                'type'        => 'Point',
                'coordinates' => array(
                    floatval( get_post_meta( $advisor->ID, '_esop_advisor_longitude', true ) ),
                    floatval( get_post_meta( $advisor->ID, '_esop_advisor_latitude', true ) ),
                ),
            ),
            'properties' => array(
                'id'       => $advisor->ID,
                'name'     => $advisor->post_title,
                'company'  => get_post_meta( $advisor->ID, '_esop_advisor_company', true ),
                'city'     => get_post_meta( $advisor->ID, '_esop_advisor_city', true ),
                'state'    => get_post_meta( $advisor->ID, '_esop_advisor_state', true ),
            ),
        );
    }
    
    return rest_ensure_response( array(
        'type'     => 'FeatureCollection',
        'features' => $features,
    ) );
}

/**
 * REST callback to get single advisor.
 *
 * @since 1.0.0
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response Response object.
 */
function esop_advisor_rest_get_advisor( $request ) {
    
    $post_id = intval( $request->get_param( 'id' ) );
    $advisor = get_post( $post_id );
    
    if ( ! $advisor || $advisor->post_type !== 'esop_advisor' || $advisor->post_status !== 'publish' ) {
        return new WP_Error( 'not_found', 'Advisor not found', array( 'status' => 404 ) );
    }
    
    return rest_ensure_response( array(
        'id'        => $advisor->ID,
        'name'      => $advisor->post_title,
        'content'   => apply_filters( 'the_content', $advisor->post_content ),
        'excerpt'   => $advisor->post_excerpt,
        'company'   => get_post_meta( $post_id, '_esop_advisor_company', true ),
        'address'   => get_post_meta( $post_id, '_esop_advisor_address', true ),
        'city'      => get_post_meta( $post_id, '_esop_advisor_city', true ),
        'state'     => get_post_meta( $post_id, '_esop_advisor_state', true ),
        'zip'       => get_post_meta( $post_id, '_esop_advisor_zip', true ),
        'phone'     => get_post_meta( $post_id, '_esop_advisor_phone', true ),
        'email'     => get_post_meta( $post_id, '_esop_advisor_email', true ),
        'website'   => get_post_meta( $post_id, '_esop_advisor_website', true ),
        'latitude'  => get_post_meta( $post_id, '_esop_advisor_latitude', true ),
        'longitude' => get_post_meta( $post_id, '_esop_advisor_longitude', true ),
        'permalink' => get_permalink( $post_id ),
    ) );
}

/**
 * =============================================================================
 * SECTION 11: SINGLE ADVISOR TEMPLATE
 * =============================================================================
 * Modify single advisor display (optional template override).
 */

/**
 * Filter the content for single advisor posts.
 * 
 * Adds structured contact information below the main content.
 *
 * @since 1.0.0
 * @param string $content The post content.
 * @return string Modified content.
 */
function esop_advisor_filter_content( $content ) {
    
    // Only modify single advisor posts
    if ( ! is_singular( 'esop_advisor' ) || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }
    
    global $post;
    
    // Get all meta data
    $company   = get_post_meta( $post->ID, '_esop_advisor_company', true );
    $address   = get_post_meta( $post->ID, '_esop_advisor_address', true );
    $city      = get_post_meta( $post->ID, '_esop_advisor_city', true );
    $state     = get_post_meta( $post->ID, '_esop_advisor_state', true );
    $zip       = get_post_meta( $post->ID, '_esop_advisor_zip', true );
    $phone     = get_post_meta( $post->ID, '_esop_advisor_phone', true );
    $email     = get_post_meta( $post->ID, '_esop_advisor_email', true );
    $website   = get_post_meta( $post->ID, '_esop_advisor_website', true );
    
    // Build location string
    $location_parts = array_filter( array( $city, $state, $zip ) );
    $location = implode( ', ', $location_parts );
    
    // Build additional content
    $additional = '<div class="esop-advisor-details" style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">';
    $additional .= '<h3 style="margin-top: 0;">Contact Information</h3>';
    
    if ( $company ) {
        $additional .= '<p><strong>Company:</strong> ' . esc_html( $company ) . '</p>';
    }
    
    if ( $address || $location ) {
        $additional .= '<p><strong>Address:</strong><br>';
        if ( $address ) {
            $additional .= esc_html( $address ) . '<br>';
        }
        if ( $location ) {
            $additional .= esc_html( $location );
        }
        $additional .= '</p>';
    }
    
    if ( $phone ) {
        $additional .= '<p><strong>Phone:</strong> <a href="tel:' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></p>';
    }
    
    if ( $email ) {
        $additional .= '<p><strong>Email:</strong> <a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></p>';
    }
    
    if ( $website ) {
        $additional .= '<p><strong>Website:</strong> <a href="' . esc_url( $website ) . '" target="_blank" rel="noopener">' . esc_html( $website ) . '</a></p>';
    }
    
    $additional .= '</div>';
    
    return $content . $additional;
}
add_filter( 'the_content', 'esop_advisor_filter_content' );

/**
 * =============================================================================
 * SECTION 12: HELPER FUNCTIONS
 * =============================================================================
 * Utility functions that can be used in themes or other plugins.
 */

/**
 * Get all advisors as an array.
 * 
 * Helper function for theme developers.
 *
 * @since 1.0.0
 * @param array $args Optional. Query arguments.
 * @return array Array of advisor data.
 */
function esop_advisor_get_all( $args = array() ) {
    
    $defaults = array(
        'post_type'      => 'esop_advisor',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    $posts = get_posts( $args );
    
    $advisors = array();
    
    foreach ( $posts as $post ) {
        $advisors[] = array(
            'id'        => $post->ID,
            'name'      => $post->post_title,
            'company'   => get_post_meta( $post->ID, '_esop_advisor_company', true ),
            'address'   => get_post_meta( $post->ID, '_esop_advisor_address', true ),
            'city'      => get_post_meta( $post->ID, '_esop_advisor_city', true ),
            'state'     => get_post_meta( $post->ID, '_esop_advisor_state', true ),
            'zip'       => get_post_meta( $post->ID, '_esop_advisor_zip', true ),
            'phone'     => get_post_meta( $post->ID, '_esop_advisor_phone', true ),
            'email'     => get_post_meta( $post->ID, '_esop_advisor_email', true ),
            'website'   => get_post_meta( $post->ID, '_esop_advisor_website', true ),
            'latitude'  => get_post_meta( $post->ID, '_esop_advisor_latitude', true ),
            'longitude' => get_post_meta( $post->ID, '_esop_advisor_longitude', true ),
            'permalink' => get_permalink( $post->ID ),
        );
    }
    
    return $advisors;
}

/**
 * Get advisors by state.
 *
 * @since 1.0.0
 * @param string $state State abbreviation (e.g., 'CA', 'NY').
 * @return array Array of advisor data.
 */
function esop_advisor_get_by_state( $state ) {
    
    return esop_advisor_get_all( array(
        'meta_query' => array(
            array(
                'key'   => '_esop_advisor_state',
                'value' => strtoupper( $state ),
            ),
        ),
    ) );
}

/**
 * Get advisor count.
 *
 * @since 1.0.0
 * @return int Number of published advisors.
 */
function esop_advisor_get_count() {
    $count = wp_count_posts( 'esop_advisor' );
    return isset( $count->publish ) ? intval( $count->publish ) : 0;
}

/**
 * =============================================================================
 * END OF PLUGIN
 * =============================================================================
 */