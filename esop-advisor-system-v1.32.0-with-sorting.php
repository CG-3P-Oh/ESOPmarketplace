<?php
/**
 * Plugin Name: ESOP Advisor System
 * Description: Complete ESOP Advisor directory with MapBox map integration. Self-contained MU plugin with no third-party dependencies.
 * Version: 1.32.2
 * Author: ESOP Marketplace / 3PRIME
 * Changelog: v1.32.2 - Fixed empty <p> tag appearing in grid; v1.32.1 - Fixed JS wrapping; v1.32.0 - Added sortable directory
 * Text Domain: esop-advisor
 *
 * INSTALLATION:
 * 1. Place this file in /wp-content/mu-plugins/
 * 2. Add MapBox access token to wp-config.php:
 *    define( 'MAPBOX_ACCESS_TOKEN', 'pk.your_token_here' );
 * 3. Go to Settings > Permalinks and click Save
 *
 * SHORTCODES:
 * [esop_advisor_directory] - Full advisor directory grid (6 columns desktop, responsive)
 *   Shows all advisors with image, name, title, and company. For use on Divi pages.
 * [esop_advisor_map height="500px" zoom="4" style="streets-v12"]
 * [esop_advisor_location_map] - Single advisor location map
 *   Attributes: post_id, height, zoom, style, marker_color, interactive, show_popup
 * [esop_advisor_case_studies posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_articles posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_ratings posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_blog posts_per_load="3" show_title="true" layout="default|horizontal|grid"]
 * [esop_advisor_testimonial_category format="slug|id|name"] - Get advisor's testimonial category
 * [advisor_specialty_title suffix="" tag="h1" class="" id=""] - Specialty page title (for Divi hero)
 *   Outputs specialty name on taxonomy archive pages. Default suffix: ", Consultants & Advisors"
 * [esop_specialty_advisors specialty="" columns="4"] - Advisor grid for specialty pages
 *   Auto-detects specialty on archive pages. Shows advisor cards in 4-column grid.
 * [esop_advisor_stages] - Three stages of ESOP implementation with specialty links
 *   Self-contained content block for the /esop-advisors/ page
 * [esop_history_timeline style="cards" autoplay="false" speed="5000"] - ESOP history coverflow carousel
 *   Attributes: style (cards|minimal), autoplay (true|false), speed (ms)
 * [esop_author_list] - Vertical list of WordPress post authors with links to author archives
 *   Attributes: show_post_count="no", orderby="name", order="ASC", minimum_posts="1", title="", exclude="admin"
 *
 * Field Shortcodes (for Divi templates and Link fields):
 * [esop_advisor_field field="FIELD_NAME"] - Universal field shortcode
 * [esop_name], [esop_company], [esop_title], [esop_address], [esop_address2], [esop_city],
 * [esop_state], [esop_zip], [esop_phone], [esop_cell], [esop_fax],
 * [esop_email], [esop_website], [esop_about_url], [esop_services_url],
 * [esop_linkedin], [esop_bio], [esop_education], [esop_expertise], [esop_videos],
 * [esop_location], [esop_full_address]
 *
 * [esop_address2] - Suite/unit/PO box number (raw value)
 * [esop_location] - Multi-line formatted address with HTML line breaks:
 *                   7500 Flying Cloud Drive<br>Suite 800<br>Eden Prairie, MN 55344
 *
 * Row Shortcodes (conditional display with labels/icons - hide when empty):
 * [esop_company_row], [esop_title_row], [esop_email_row], [esop_phone_row],
 * [esop_cell_row], [esop_fax_row], [esop_website_row], [esop_about_row],
 * [esop_services_row], [esop_address_block], [esop_contact_button]
 *
 * Combined Shortcodes (display multiple fields at once - only shows fields with values):
 * [esop_company_info] - Displays company, title, AND email
 * [esop_all_phones] - Displays phone, cell, AND fax
 * [esop_all_links] - Displays website, about, services, AND linkedin
 * [esop_contact_info] - Displays email AND all phones
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
 * - esop_advisor_expertise: '1' if advisor has expertise content, absent if not
 * - esop_advisor_videos: '1' if advisor has videos, absent if not
 * Use these exact field names in Divi's "Manual Custom Field Name" with "Is Any Value"
 * To refresh all advisors: Tools > ESOP Advisor Tools > Refresh Condition Meta
 * - Body class 'esop-advisor-page' added on advisor single pages
 * - Body class 'esop-advisor-user-{ID}' added when advisor has linked user
 *
 * META FIELDS:
 * - company, title, address, address2, city, state, zip, phone, cell, fax, email
 * - website, about_url, services_url, linkedin
 * - bio (rich text), education (rich text), expertise (rich text), videos (rich text)
 * - latitude, longitude, user_id
 *
 * Address Line 2 (address2): Suite, unit, PO box, or building number
 *
 * HELPER FUNCTIONS:
 * esop_get_advisor_field( $post_id, $field ) - Get advisor field value
 * esop_the_advisor_field( $post_id, $field ) - Echo advisor field with escaping
 * esop_get_advisor_user_id( $advisor_id ) - Get linked WordPress user ID
 * esop_get_advisor_user( $advisor_id ) - Get linked WordPress user object
 * esop_advisor_has_posts_in_category( $advisor_id, $category ) - Check if advisor has posts
 * esop_get_advisor_testimonial_category_id( $advisor_id ) - Get DP Testimonial category ID
 * esop_advisor_has_testimonials( $advisor_id ) - Check if advisor has testimonials
 *
 * JSON-LD SCHEMA (SEO):
 * Automatically outputs structured data on single advisor pages including:
 * - Person schema with name, job title, description, image
 * - Organization schema (worksFor) with company name and website
 * - PostalAddress with full address details
 * - Contact info: telephone, email
 * - sameAs links for LinkedIn and website
 * - knowsAbout for expertise/knowledge areas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ============================================================================
// DEBUG LOGGING FOR SHORTCODE TROUBLESHOOTING
// ============================================================================

// Debug logging is DISABLED by default.
// To enable: add define('ESOP_ADVISOR_DEBUG', true); to wp-config.php
// WARNING: Only enable temporarily for debugging - generates verbose logs!
if ( ! defined( 'ESOP_ADVISOR_DEBUG' ) ) {
	define( 'ESOP_ADVISOR_DEBUG', false );
}

/**
 * Debug logging helper for ESOP Advisor shortcodes
 * Only logs when ESOP_ADVISOR_DEBUG is true AND user is logged in as admin.
 * This prevents log spam from regular visitor page views.
 *
 * @param string $message Log message
 * @param mixed  $data    Optional data to log
 */
function esop_advisor_debug_log( $message, $data = null ) {
	// Must be explicitly enabled
	if ( ! ESOP_ADVISOR_DEBUG ) {
		return;
	}

	// Only log for admin users to prevent log spam from visitors
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$log_entry = '[ESOP Advisor Debug] ' . $message;
	if ( $data !== null ) {
		$log_entry .= ' | Data: ' . print_r( $data, true );
	}
	error_log( $log_entry );
}

// ============================================================================
// DISABLE WORDPRESS 404 GUESS REDIRECT
// ============================================================================
// Prevents WP from guessing and redirecting 404 URLs to similarly-slugged posts.
// e.g. /esop-trustees/ was redirecting to a blog post instead of 404-ing.
add_filter( 'do_redirect_guess_404_permalink', '__return_false' );

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
// ADVISOR SPECIALTIES TAXONOMY REGISTRATION
// ============================================================================

/**
 * Register Advisor Specialties Custom Taxonomy
 * 
 * This taxonomy allows advisors to be categorized by their areas of expertise
 * (e.g., ESOP Lawyers, ESOP Valuation, Feasibility Study, etc.)
 */
function esop_register_advisor_specialties_taxonomy() {
    $labels = array(
        'name'                       => 'Advisor Specialties',
        'singular_name'              => 'Advisor Specialty',
        'menu_name'                  => 'Specialties',
        'all_items'                  => 'All Specialties',
        'edit_item'                  => 'Edit Specialty',
        'view_item'                  => 'View Specialty',
        'update_item'                => 'Update Specialty',
        'add_new_item'               => 'Add New Specialty',
        'new_item_name'              => 'New Specialty Name',
        'parent_item'                => 'Parent Specialty',
        'parent_item_colon'          => 'Parent Specialty:',
        'search_items'               => 'Search Specialties',
        'popular_items'              => 'Popular Specialties',
        'separate_items_with_commas' => 'Separate specialties with commas',
        'add_or_remove_items'        => 'Add or remove specialties',
        'choose_from_most_used'      => 'Choose from most used specialties',
        'not_found'                  => 'No specialties found',
    );

    $args = array(
        'labels'                     => $labels,
        'public'                     => true,
        'publicly_queryable'         => true,
        'hierarchical'               => true, // Allows parent-child relationships
        'show_ui'                    => true,
        'show_in_menu'               => true,
        'show_in_nav_menus'          => true,
        'show_in_rest'               => true, // Enable block editor support
        'show_tagcloud'              => false,
        'show_in_quick_edit'         => true,
        'show_admin_column'          => true, // Show column in advisor list
        'meta_box_cb'                => 'post_categories_meta_box', // Hierarchical UI
        'rewrite'                    => array(
            'slug'                   => 'advisors',
            'with_front'             => false,
            'hierarchical'           => true,
        ),
        'query_var'                  => true,
        'capabilities'               => array(
            'manage_terms'           => 'manage_categories',
            'edit_terms'             => 'manage_categories',
            'delete_terms'           => 'manage_categories',
            'assign_terms'           => 'edit_posts',
        ),
    );

    register_taxonomy('advisor_specialty', array('esop_advisor'), $args);
}
add_action('init', 'esop_register_advisor_specialties_taxonomy', 0);


// ============================================================================
// TAXONOMY TERM META FIELDS
// ============================================================================

/**
 * Add custom fields to Advisor Specialty edit screen
 * 
 * Adds fields for:
 * - Descriptive content (WYSIWYG editor)
 * - CAKE category ID (for migration reference)
 * - Meta description (SEO)
 * - Meta title (SEO)
 * - Meta keywords (SEO)
 */
function esop_add_advisor_specialty_meta_fields($term) {
    $term_id = $term->term_id;

    // Get existing meta values
    $intro_heading       = get_term_meta($term_id, 'intro_heading', true);
    $intro_content       = get_term_meta($term_id, 'intro_content', true);
    $description_content = get_term_meta($term_id, 'description_content', true);
    $cake_category_id    = get_term_meta($term_id, 'cake_category_id', true);
    $meta_description    = get_term_meta($term_id, 'meta_description', true);
    $meta_title          = get_term_meta($term_id, 'meta_title', true);
    $meta_keywords       = get_term_meta($term_id, 'meta_keywords', true);
    ?>

    <tr class="form-field term-intro-heading-wrap">
        <th scope="row">
            <label for="intro_heading">Intro Section — Heading</label>
        </th>
        <td>
            <input type="text" name="intro_heading" id="intro_heading"
                   value="<?php echo esc_attr( $intro_heading ); ?>"
                   class="large-text" />
            <p class="description">
                H2 heading displayed directly below the hero on this specialty page (e.g. "Finding the Right ESOP Lawyer for Your Company").
                Leave blank to hide the intro section entirely.
            </p>
        </td>
    </tr>

    <tr class="form-field term-intro-content-wrap">
        <th scope="row">
            <label for="intro_content">Intro Section — Body Text</label>
        </th>
        <td>
            <?php
            wp_editor( $intro_content, 'intro_content', array(
                'textarea_name' => 'intro_content',
                'textarea_rows' => 8,
                'media_buttons' => false,
                'teeny'         => false,
                'tinymce'       => array(
                    'toolbar1' => 'bold,italic,bullist,numlist,link,unlink',
                    'toolbar2' => '',
                ),
            ) );
            ?>
            <p class="description">
                Body text displayed below the intro heading. Shown only when a heading is set.
            </p>
        </td>
    </tr>

    <tr class="form-field term-description-content-wrap">
        <th scope="row">
            <label for="description_content">Descriptive Content</label>
        </th>
        <td>
            <?php
            wp_editor($description_content, 'description_content', array(
                'textarea_name' => 'description_content',
                'textarea_rows' => 15,
                'media_buttons' => true,
                'teeny'         => false,
                'tinymce'       => array(
                    'toolbar1' => 'formatselect,bold,italic,bullist,numlist,link,unlink',
                    'toolbar2' => '',
                ),
            ));
            ?>
            <p class="description">
                Educational content displayed below advisor listings on this specialty page. 
                This content was migrated from the CAKE site and should include headings, 
                paragraphs, and bullet lists as appropriate.
            </p>
        </td>
    </tr>
    
    <tr class="form-field term-cake-id-wrap">
        <th scope="row">
            <label for="cake_category_id">CAKE Category ID</label>
        </th>
        <td>
            <input type="text" name="cake_category_id" id="cake_category_id" 
                   value="<?php echo esc_attr($cake_category_id); ?>" 
                   class="regular-text" readonly />
            <p class="description">
                Original CAKE category ID (for reference only - do not edit)
            </p>
        </td>
    </tr>
    
    <tr class="form-field term-meta-description-wrap">
        <th scope="row">
            <label for="meta_description">Meta Description</label>
        </th>
        <td>
            <textarea name="meta_description" id="meta_description" 
                      rows="3" class="large-text"><?php echo esc_textarea($meta_description); ?></textarea>
            <p class="description">
                SEO meta description for this specialty page (recommended: 150-160 characters)
            </p>
        </td>
    </tr>
    
    <tr class="form-field term-meta-title-wrap">
        <th scope="row">
            <label for="meta_title">Meta Title</label>
        </th>
        <td>
            <input type="text" name="meta_title" id="meta_title" 
                   value="<?php echo esc_attr($meta_title); ?>" 
                   class="large-text" />
            <p class="description">
                SEO page title (leave blank to use default: "[Specialty] | ESOP Marketplace")
            </p>
        </td>
    </tr>
    
    <tr class="form-field term-meta-keywords-wrap">
        <th scope="row">
            <label for="meta_keywords">Meta Keywords</label>
        </th>
        <td>
            <input type="text" name="meta_keywords" id="meta_keywords" 
                   value="<?php echo esc_attr($meta_keywords); ?>" 
                   class="large-text" />
            <p class="description">
                SEO keywords (comma-separated)
            </p>
        </td>
    </tr>
    
    <?php
}
add_action('advisor_specialty_edit_form_fields', 'esop_add_advisor_specialty_meta_fields', 10, 1);


/**
 * Sync TinyMCE editors before the taxonomy edit form submits.
 *
 * wp_editor() in taxonomy screens does not automatically flush visual-editor
 * content to its textarea on form submit, so $_POST values arrive empty when
 * the user is on the "Visual" tab. This script calls tinyMCE.triggerSave()
 * on submit, which forces all active editors to sync before the POST fires.
 * Affects both intro_content and description_content editors on this screen.
 */
add_action( 'admin_enqueue_scripts', 'esop_specialty_admin_tinymce_sync' );

function esop_specialty_admin_tinymce_sync( $hook ) {
	// Only run on the taxonomy term edit screen for advisor_specialty
	if ( $hook !== 'term.php' ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || $screen->taxonomy !== 'advisor_specialty' ) {
		return;
	}
	wp_add_inline_script(
		'jquery-core',
		"jQuery(function($){
			$('body').on('submit', '#edittag', function(){
				if ( typeof tinyMCE !== 'undefined' ) {
					tinyMCE.triggerSave();
				}
			});
		});"
	);
}


/**
 * Save Advisor Specialty custom meta fields
 */
function esop_save_advisor_specialty_meta($term_id) {
    // Verify nonce if available (WordPress handles this for taxonomy terms)

    // Save intro heading
    if ( isset( $_POST['intro_heading'] ) ) {
        update_term_meta(
            $term_id,
            'intro_heading',
            sanitize_text_field( $_POST['intro_heading'] )
        );
    }

    // Save intro body content
    if ( isset( $_POST['intro_content'] ) ) {
        update_term_meta(
            $term_id,
            'intro_content',
            wp_kses_post( $_POST['intro_content'] )
        );
    }

    // Save descriptive content
    if (isset($_POST['description_content'])) {
        update_term_meta(
            $term_id,
            'description_content',
            wp_kses_post($_POST['description_content'])
        );
    }
    
    // Save CAKE category ID (should only be set during import)
    if (isset($_POST['cake_category_id'])) {
        update_term_meta(
            $term_id,
            'cake_category_id',
            absint($_POST['cake_category_id'])
        );
    }
    
    // Save meta description
    if (isset($_POST['meta_description'])) {
        update_term_meta(
            $term_id,
            'meta_description',
            sanitize_textarea_field($_POST['meta_description'])
        );
    }
    
    // Save meta title
    if (isset($_POST['meta_title'])) {
        update_term_meta(
            $term_id,
            'meta_title',
            sanitize_text_field($_POST['meta_title'])
        );
    }
    
    // Save meta keywords
    if (isset($_POST['meta_keywords'])) {
        update_term_meta(
            $term_id,
            'meta_keywords',
            sanitize_text_field($_POST['meta_keywords'])
        );
    }
}
add_action('edited_advisor_specialty', 'esop_save_advisor_specialty_meta', 10, 1);
add_action('create_advisor_specialty', 'esop_save_advisor_specialty_meta', 10, 1);


/**
 * Add column for specialty advisor count in admin
 */
function esop_add_specialty_advisor_count_column($columns) {
    $columns['advisor_count'] = 'Advisors';
    return $columns;
}
add_filter('manage_edit-advisor_specialty_columns', 'esop_add_specialty_advisor_count_column');


/**
 * Display advisor count in specialty admin column
 */
function esop_display_specialty_advisor_count_column($content, $column_name, $term_id) {
    if ($column_name === 'advisor_count') {
        $count = get_term_meta($term_id, 'advisor_count_cache', true);
        
        if (empty($count)) {
            // Calculate and cache the count
            $term = get_term($term_id, 'advisor_specialty');
            $count = $term->count;
            update_term_meta($term_id, 'advisor_count_cache', $count);
        }
        
        $content = $count . ' advisor' . ($count != 1 ? 's' : '');
    }
    return $content;
}
add_filter('manage_advisor_specialty_custom_column', 'esop_display_specialty_advisor_count_column', 10, 3);


// ============================================================================
// META BOXES

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
		'address2'     => get_post_meta( $post->ID, '_esop_advisor_address2', true ),
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

		<tr><th><label for="esop_advisor_address2"><?php esc_html_e( 'Address Line 2', 'esop-advisor' ); ?></label></th>
		<td><input type="text" id="esop_advisor_address2" name="esop_advisor_address2" value="<?php echo esc_attr( $fields['address2'] ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Suite 100, Unit 5, PO Box 123', 'esop-advisor' ); ?>"><p class="description"><?php esc_html_e( 'Suite, unit, PO box, or building number', 'esop-advisor' ); ?></p></td></tr>

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

		<tr style="background: #fff9e6;"><th><label for="esop_advisor_retired"><?php esc_html_e( 'Retired Advisor', 'esop-advisor' ); ?></label></th>
		<td><label><input type="checkbox" id="esop_advisor_retired" name="esop_advisor_retired" value="1" <?php checked( get_post_meta( $post->ID, '_esop_advisor_retired', true ), '1' ); ?>> <?php esc_html_e( 'This advisor has retired', 'esop-advisor' ); ?></label>
		<p class="description"><?php esc_html_e( 'When checked, contact links will be disabled and a "Retired" badge will be displayed.', 'esop-advisor' ); ?></p></td></tr>

		<tr style="background: #e6f0ff;"><th><label for="esop_advisor_honorary"><?php esc_html_e( 'Honorary Advisor', 'esop-advisor' ); ?></label></th>
		<td><label><input type="checkbox" id="esop_advisor_honorary" name="esop_advisor_honorary" value="1" <?php checked( get_post_meta( $post->ID, '_esop_advisor_honorary', true ), '1' ); ?>> <?php esc_html_e( 'This is an honorary advisor', 'esop-advisor' ); ?></label>
		<p class="description"><?php esc_html_e( 'When checked, this advisor will only appear on the Honorary Advisors page and will be excluded from the main directory, map, and featured listings.', 'esop-advisor' ); ?></p></td></tr>
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
	$expertise = get_post_meta( $post->ID, '_esop_advisor_expertise', true );
	?>
	<h4 style="margin-top: 0;"><?php esc_html_e( 'Education', 'esop-advisor' ); ?></h4>
	<p class="description"><?php esc_html_e( 'Education background, certifications, and professional qualifications.', 'esop-advisor' ); ?></p>
	<?php
	wp_editor(
		$education,
		'esopadvisoreducation',
		array(
			'textarea_name' => 'esop_advisor_education',
			'textarea_rows' => 12,
			'media_buttons' => true,
			'teeny'         => false,
			'quicktags'     => true,
			'tinymce'       => true,
		)
	);
	?>
	<h4 style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;"><?php esc_html_e( 'Expertise', 'esop-advisor' ); ?></h4>
	<p class="description"><?php esc_html_e( 'Areas of expertise, specializations, and professional focus areas.', 'esop-advisor' ); ?></p>
	<?php
	wp_editor(
		$expertise,
		'esopadvisorexpertise',
		array(
			'textarea_name' => 'esop_advisor_expertise',
			'textarea_rows' => 12,
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
	$text_fields = array( 'company', 'title', 'address', 'address2', 'city', 'state', 'zip', 'phone', 'cell', 'fax' );
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
	$rich_text_fields = array( 'bio', 'education', 'expertise', 'videos' );
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

	// Retired status (checkbox - save as '1' or delete)
	if ( isset( $_POST['esop_advisor_retired'] ) && $_POST['esop_advisor_retired'] === '1' ) {
		update_post_meta( $post_id, '_esop_advisor_retired', '1' );
	} else {
		delete_post_meta( $post_id, '_esop_advisor_retired' );
	}

	// Honorary status (checkbox - save as '1' or delete)
	if ( isset( $_POST['esop_advisor_honorary'] ) && $_POST['esop_advisor_honorary'] === '1' ) {
		update_post_meta( $post_id, '_esop_advisor_honorary', '1' );
	} else {
		delete_post_meta( $post_id, '_esop_advisor_honorary' );
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
 * - esop_advisor_expertise: '1' if advisor has expertise content, deleted if not
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

	// Check for expertise content
	$expertise = get_post_meta( $post_id, '_esop_advisor_expertise', true );
	if ( ! empty( trim( strip_tags( $expertise ) ) ) ) {
		update_post_meta( $post_id, 'esop_advisor_expertise', '1' );
	} else {
		delete_post_meta( $post_id, 'esop_advisor_expertise' );
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
	$new['retired'] = __( 'Retired', 'esop-advisor' );
	$new['honorary'] = __( 'Honorary', 'esop-advisor' );
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
		case 'retired':
			$is_retired = get_post_meta( $post_id, '_esop_advisor_retired', true ) === '1';
			echo $is_retired ? '<span style="color:#dc3232;font-weight:500;">Yes</span>' : '<span style="color:#999;">No</span>';
			break;
		case 'honorary':
			$is_honorary = get_post_meta( $post_id, '_esop_advisor_honorary', true ) === '1';
			echo $is_honorary ? '<span style="color:#0073aa;font-weight:500;">Yes</span>' : '<span style="color:#999;">No</span>';
			break;
	}
}

add_filter( 'manage_edit-esop_advisor_sortable_columns', 'esop_advisor_sortable_columns' );
function esop_advisor_sortable_columns( $columns ) {
	$columns['company'] = 'company';
	$columns['city'] = 'city';
	$columns['state'] = 'state';
	$columns['retired'] = 'retired';
	$columns['honorary'] = 'honorary';
	return $columns;
}

add_action( 'pre_get_posts', 'esop_advisor_column_sorting' );
function esop_advisor_column_sorting( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) return;
	if ( $query->get( 'post_type' ) !== 'esop_advisor' ) return;
	$orderby = $query->get( 'orderby' );
	$map = array( 'company' => '_esop_advisor_company', 'city' => '_esop_advisor_city', 'state' => '_esop_advisor_state', 'retired' => '_esop_advisor_retired', 'honorary' => '_esop_advisor_honorary' );
	if ( isset( $map[ $orderby ] ) ) {
		$query->set( 'meta_key', $map[ $orderby ] );
		$query->set( 'orderby', 'meta_value' );
	}
}

// ============================================================================
// QUICK EDIT & BULK EDIT SUPPORT
// ============================================================================

/**
 * Add Quick Edit field for retired status
 */
add_action( 'quick_edit_custom_box', 'esop_advisor_quick_edit_retired', 10, 2 );
function esop_advisor_quick_edit_retired( $column_name, $post_type ) {
	if ( $post_type !== 'esop_advisor' || $column_name !== 'retired' ) {
		return;
	}
	wp_nonce_field( 'esop_advisor_quick_edit_nonce_action', 'esop_advisor_quick_edit_nonce' );
	?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="inline-edit-retired">
				<input type="checkbox" name="esop_advisor_retired" value="1">
				<span class="checkbox-title"><?php esc_html_e( 'Retired Advisor', 'esop-advisor' ); ?></span>
			</label>
		</div>
	</fieldset>
	<?php
}

/**
 * Add Quick Edit field for honorary status
 */
add_action( 'quick_edit_custom_box', 'esop_advisor_quick_edit_honorary', 10, 2 );
function esop_advisor_quick_edit_honorary( $column_name, $post_type ) {
	if ( $post_type !== 'esop_advisor' || $column_name !== 'honorary' ) {
		return;
	}
	?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="inline-edit-honorary">
				<input type="checkbox" name="esop_advisor_honorary" value="1">
				<span class="checkbox-title"><?php esc_html_e( 'Honorary Advisor', 'esop-advisor' ); ?></span>
			</label>
		</div>
	</fieldset>
	<?php
}

/**
 * Add Bulk Edit field for retired status
 */
add_action( 'bulk_edit_custom_box', 'esop_advisor_bulk_edit_retired', 10, 2 );
function esop_advisor_bulk_edit_retired( $column_name, $post_type ) {
	if ( $post_type !== 'esop_advisor' || $column_name !== 'retired' ) {
		return;
	}
	wp_nonce_field( 'esop_advisor_bulk_edit_nonce_action', 'esop_advisor_bulk_edit_nonce' );
	?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="inline-edit-retired">
				<span class="title"><?php esc_html_e( 'Retired Status', 'esop-advisor' ); ?></span>
				<select name="esop_advisor_retired_bulk">
					<option value="-1"><?php esc_html_e( '— No Change —', 'esop-advisor' ); ?></option>
					<option value="1"><?php esc_html_e( 'Mark as Retired', 'esop-advisor' ); ?></option>
					<option value="0"><?php esc_html_e( 'Mark as Active', 'esop-advisor' ); ?></option>
				</select>
			</label>
		</div>
	</fieldset>
	<?php
}

/**
 * Add Bulk Edit field for honorary status
 */
add_action( 'bulk_edit_custom_box', 'esop_advisor_bulk_edit_honorary', 10, 2 );
function esop_advisor_bulk_edit_honorary( $column_name, $post_type ) {
	if ( $post_type !== 'esop_advisor' || $column_name !== 'honorary' ) {
		return;
	}
	?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<label class="inline-edit-honorary">
				<span class="title"><?php esc_html_e( 'Honorary Status', 'esop-advisor' ); ?></span>
				<select name="esop_advisor_honorary_bulk">
					<option value="-1"><?php esc_html_e( '— No Change —', 'esop-advisor' ); ?></option>
					<option value="1"><?php esc_html_e( 'Mark as Honorary', 'esop-advisor' ); ?></option>
					<option value="0"><?php esc_html_e( 'Remove Honorary', 'esop-advisor' ); ?></option>
				</select>
			</label>
		</div>
	</fieldset>
	<?php
}

/**
 * Add data attribute to row for Quick Edit
 */
add_action( 'manage_esop_advisor_posts_custom_column', 'esop_advisor_add_retired_data_attr', 10, 2 );
function esop_advisor_add_retired_data_attr( $column, $post_id ) {
	if ( $column === 'retired' ) {
		$retired = get_post_meta( $post_id, '_esop_advisor_retired', true ) === '1' ? '1' : '0';
		echo '<span class="esop-retired-data" data-retired="' . esc_attr( $retired ) . '" style="display:none;"></span>';
	}
	if ( $column === 'honorary' ) {
		$honorary = get_post_meta( $post_id, '_esop_advisor_honorary', true ) === '1' ? '1' : '0';
		echo '<span class="esop-honorary-data" data-honorary="' . esc_attr( $honorary ) . '" style="display:none;"></span>';
	}
}

/**
 * Add Quick Edit JavaScript to admin footer
 */
add_action( 'admin_footer-edit.php', 'esop_advisor_quick_edit_js' );
function esop_advisor_quick_edit_js() {
	global $post_type;
	if ( $post_type !== 'esop_advisor' ) {
		return;
	}
	?>
	<script type="text/javascript">
	jQuery(function($) {
		// Store the original function
		var $wp_inline_edit = inlineEditPost.edit;

		// Override the inline edit function
		inlineEditPost.edit = function(id) {
			// Call the original function
			$wp_inline_edit.apply(this, arguments);

			// Get the post ID
			var post_id = 0;
			if (typeof(id) === 'object') {
				post_id = parseInt(this.getId(id));
			}

			if (post_id > 0) {
				var $row = $('#post-' + post_id);
				var retired = $row.find('.esop-retired-data').data('retired');
				var honorary = $row.find('.esop-honorary-data').data('honorary');
				var $editRow = $('#edit-' + post_id);

				// Set retired checkbox state
				if (retired === 1 || retired === '1') {
					$editRow.find('input[name="esop_advisor_retired"]').prop('checked', true);
				} else {
					$editRow.find('input[name="esop_advisor_retired"]').prop('checked', false);
				}

				// Set honorary checkbox state
				if (honorary === 1 || honorary === '1') {
					$editRow.find('input[name="esop_advisor_honorary"]').prop('checked', true);
				} else {
					$editRow.find('input[name="esop_advisor_honorary"]').prop('checked', false);
				}
			}
		};
	});
	</script>
	<?php
}

/**
 * Save Quick Edit retired status
 */
add_action( 'save_post_esop_advisor', 'esop_advisor_save_quick_edit', 10, 2 );
function esop_advisor_save_quick_edit( $post_id, $post ) {
	// Skip if not Quick Edit (check for nonce)
	if ( ! isset( $_POST['esop_advisor_quick_edit_nonce'] ) ) {
		return;
	}

	// Verify nonce
	if ( ! wp_verify_nonce( $_POST['esop_advisor_quick_edit_nonce'], 'esop_advisor_quick_edit_nonce_action' ) ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save retired status
	if ( isset( $_POST['esop_advisor_retired'] ) && $_POST['esop_advisor_retired'] === '1' ) {
		update_post_meta( $post_id, '_esop_advisor_retired', '1' );
	} else {
		delete_post_meta( $post_id, '_esop_advisor_retired' );
	}

	// Save honorary status
	if ( isset( $_POST['esop_advisor_honorary'] ) && $_POST['esop_advisor_honorary'] === '1' ) {
		update_post_meta( $post_id, '_esop_advisor_honorary', '1' );
	} else {
		delete_post_meta( $post_id, '_esop_advisor_honorary' );
	}
}

/**
 * Handle Bulk Edit save via AJAX
 */
add_action( 'wp_ajax_esop_advisor_bulk_edit_save', 'esop_advisor_bulk_edit_save' );
function esop_advisor_bulk_edit_save() {
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'esop_advisor_bulk_edit_nonce_action' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	// Check for post IDs
	if ( ! isset( $_POST['post_ids'] ) || ! is_array( $_POST['post_ids'] ) ) {
		wp_send_json_error( 'No posts selected' );
	}

	$retired_value = isset( $_POST['retired_value'] ) ? sanitize_text_field( $_POST['retired_value'] ) : '-1';
	$honorary_value = isset( $_POST['honorary_value'] ) ? sanitize_text_field( $_POST['honorary_value'] ) : '-1';

	if ( $retired_value === '-1' && $honorary_value === '-1' ) {
		wp_send_json_success( 'No change requested' );
	}

	$post_ids = array_map( 'intval', $_POST['post_ids'] );
	$updated = 0;

	foreach ( $post_ids as $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			continue;
		}

		if ( $retired_value !== '-1' ) {
			if ( $retired_value === '1' ) {
				update_post_meta( $post_id, '_esop_advisor_retired', '1' );
			} else {
				delete_post_meta( $post_id, '_esop_advisor_retired' );
			}
		}

		if ( $honorary_value !== '-1' ) {
			if ( $honorary_value === '1' ) {
				update_post_meta( $post_id, '_esop_advisor_honorary', '1' );
			} else {
				delete_post_meta( $post_id, '_esop_advisor_honorary' );
			}
		}

		$updated++;
	}

	wp_send_json_success( sprintf( '%d advisors updated', $updated ) );
}

/**
 * Add Bulk Edit save JavaScript
 */
add_action( 'admin_footer-edit.php', 'esop_advisor_bulk_edit_js' );
function esop_advisor_bulk_edit_js() {
	global $post_type;
	if ( $post_type !== 'esop_advisor' ) {
		return;
	}
	?>
	<script type="text/javascript">
	jQuery(function($) {
		// Intercept the bulk edit save
		$('#bulk_edit').on('click', function() {
			var $bulk_row = $('#bulk-edit');
			var retired_value = $bulk_row.find('select[name="esop_advisor_retired_bulk"]').val();
			var honorary_value = $bulk_row.find('select[name="esop_advisor_honorary_bulk"]').val();

			// Only process if a change was selected
			if (retired_value !== '-1' || honorary_value !== '-1') {
				var post_ids = [];
				$bulk_row.find('#bulk-titles-list .button-link').each(function() {
					post_ids.push($(this).attr('id').replace('_', ''));
				});

				if (post_ids.length > 0) {
					$.ajax({
						url: ajaxurl,
						type: 'POST',
						async: false,
						data: {
							action: 'esop_advisor_bulk_edit_save',
							nonce: $('input[name="esop_advisor_bulk_edit_nonce"]').val(),
							post_ids: post_ids,
							retired_value: retired_value,
							honorary_value: honorary_value
						}
					});
				}
			}
		});
	});
	</script>
	<?php
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
			array(
				'relation' => 'OR',
				array(
					'key'     => '_esop_advisor_honorary',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_esop_advisor_honorary',
					'value'   => '1',
					'compare' => '!=',
				),
			),
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

		$address  = get_post_meta( $advisor->ID, '_esop_advisor_address', true );
		$address2 = get_post_meta( $advisor->ID, '_esop_advisor_address2', true );
		$city     = get_post_meta( $advisor->ID, '_esop_advisor_city', true );
		$state    = get_post_meta( $advisor->ID, '_esop_advisor_state', true );
		$zip      = get_post_meta( $advisor->ID, '_esop_advisor_zip', true );

		// Build full address for Google Maps link (include address2 for geocoding accuracy)
		$full_address = trim( implode( ', ', array_filter( array( $address, $address2, $city, $state, $zip ) ) ) );
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
				'address2'        => $address2,
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
	$address  = get_post_meta( $post_id, '_esop_advisor_address', true );
	$address2 = get_post_meta( $post_id, '_esop_advisor_address2', true );
	$city     = get_post_meta( $post_id, '_esop_advisor_city', true );
	$state    = get_post_meta( $post_id, '_esop_advisor_state', true );
	$zip      = get_post_meta( $post_id, '_esop_advisor_zip', true );

	$full_address    = trim( implode( ', ', array_filter( array( $address, $address2, $city, $state, $zip ) ) ) );
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
					var displayEmail = p.email.length > 10 ? p.email.substring(0, 10) + '...' : p.email;
					html += '<a href="mailto:' + p.email + '" class="esop-popup__contact-item">';
					html += '<span class="esop-popup__contact-icon">' + icons.email + '</span>';
					html += '<span>' + displayEmail + '</span>';
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

/**
 * Ensure frontend styles are loaded on specialty archive pages
 * The styles function is also called by shortcodes, but this ensures
 * the styles are always available on taxonomy archive pages.
 */
add_action( 'wp_head', 'esop_advisor_maybe_load_frontend_styles', 5 );

function esop_advisor_maybe_load_frontend_styles() {
	// Load styles on specialty archive pages
	if ( is_tax( 'advisor_specialty' ) ) {
		esop_advisor_frontend_styles();
	}
}

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
	margin: 10px 0;
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

/* ===========================================
   UNIFIED BUTTON STYLES - Read More, View More, Contact
   Matches Divi et_pb_button styling from template page
   =========================================== */

/* Read More Button */
.esop-post-card__link {
	align-self: flex-start;
	display: inline-block;
	padding: .3em 1em;
	background-color: transparent;
	color: #f15f2a;
	border: 2px solid #f15f2a;
	border-radius: 3px;
	font-size: 15px;
	font-weight: 500;
	line-height: 1.7em;
	cursor: pointer;
	text-decoration: none;
	box-sizing: border-box;
	transition: all .2s ease-in-out;
	position: relative;
}

.esop-post-card__link::after {
	content: "\35";
	font-family: ETmodules !important;
	font-size: 1.6em;
	font-weight: 400;
	font-style: normal;
	font-variant: none;
	text-shadow: none;
	line-height: 1;
	opacity: 0;
	margin-left: -1em;
	transition: all 300ms ease 0ms;
	display: inline-block;
	vertical-align: middle;
}

.esop-post-card__link:hover,
.esop-post-card__link:focus {
	background-color: hsla(0,0%,100%,.2);
	color: #f15f2a;
	border-color: #f15f2a;
	text-decoration: none;
	padding: .3em .6em .3em .7em;
}

.esop-post-card__link:hover::after,
.esop-post-card__link:focus::after {
	opacity: 1;
	margin-left: 0;
}

/* Hide SVG icons in post card links */
.esop-post-card__link svg {
	display: none;
}

/* View More Button */
.esop-load-more-btn {
	display: inline-block;
	margin: 30px auto 0;
	padding: .3em 1em;
	background-color: transparent;
	color: #f15f2a;
	border: 2px solid #f15f2a;
	border-radius: 3px;
	font-size: 15px;
	font-weight: 500;
	line-height: 1.7em;
	cursor: pointer;
	text-decoration: none;
	box-sizing: border-box;
	transition: all .2s ease-in-out;
	position: relative;
}

.esop-load-more-btn::after {
	content: "\35";
	font-family: ETmodules !important;
	font-size: 1.6em;
	font-weight: 400;
	font-style: normal;
	font-variant: none;
	text-shadow: none;
	line-height: 1;
	opacity: 0;
	margin-left: -1em;
	transition: all 300ms ease 0ms;
	display: inline-block;
	vertical-align: middle;
}

.esop-load-more-btn:hover:not(:disabled),
.esop-load-more-btn:focus:not(:disabled) {
	background-color: hsla(0,0%,100%,.2);
	color: #f15f2a;
	border-color: #f15f2a;
	text-decoration: none;
	padding: .3em .6em .3em .7em;
}

.esop-load-more-btn:hover:not(:disabled)::after,
.esop-load-more-btn:focus:not(:disabled)::after {
	opacity: 1;
	margin-left: 0;
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

/* Horizontal layout inherits base button styles */

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

/* Grid layout inherits base button styles */

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
    padding: 2px 10px;
    background: transparent;
    color: #c75a23;
    border: 2px solid #c75a23;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.4;
    cursor: pointer;
    text-decoration: none;
    box-sizing: border-box;
    margin-top: 15px;
    transition: padding 0.35s ease-in;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link::after,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link::after {
    content: "";
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link:hover,
.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link:focus,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link:hover,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link:focus {
    background: transparent;
    color: #c75a23;
    border-color: #c75a23;
    text-decoration: none;
    padding: 2px 22px 2px 10px;
}

.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link:hover::after,
.esop-advisor-page .et_pb_blog_fullwidth .et_pb_post .more-link:focus::after,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link:hover::after,
.esop-advisor-page .et_pb_blog_grid .et_pb_post .more-link:focus::after {
    content: " >";
    font-size: 16px;
    font-weight: 700;
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
   ADVISOR PAGE H3 SECTION ICONS
   Uses inline SVG icons via ::before pseudo-element
   All icons are solid orange (#e35205)
   Icon hangs left so H3 text aligns with content below
   Add these CSS classes to Divi Text modules:
   - esop-section-location
   - esop-section-biography
   - esop-section-resources
   - esop-section-education
   - esop-section-case-studies
   - esop-section-articles
   - esop-section-blog
   - esop-section-ratings
   - esop-section-videos
   ============================================ */

/* Base styling for all advisor section H3s */
.esop-section-location h3,
.esop-section-biography h3,
.esop-section-resources h3,
.esop-section-education h3,
.esop-section-case-studies h3,
.esop-section-articles h3,
.esop-section-blog h3,
.esop-section-ratings h3,
.esop-section-videos h3 {
    position: relative;
    display: block;
}

.esop-section-location h3::before,
.esop-section-biography h3::before,
.esop-section-resources h3::before,
.esop-section-education h3::before,
.esop-section-case-studies h3::before,
.esop-section-articles h3::before,
.esop-section-blog h3::before,
.esop-section-ratings h3::before,
.esop-section-videos h3::before {
    content: "";
    display: inline-block;
    width: 30px;
    height: 30px;
    margin-right: 15px;
    margin-left: -45px;
    margin-bottom: 7px;
    vertical-align: middle;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
}

/* Location - Map pin (solid) */
.esop-section-location h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z'/%3E%3C/svg%3E");
}

/* Biography - Person (solid) */
.esop-section-biography h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
}

/* Resources - Link (solid) */
.esop-section-resources h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z'/%3E%3C/svg%3E");
}

/* Education & Expertise - Graduation cap (solid) */
.esop-section-education h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z'/%3E%3C/svg%3E");
}

/* Case Studies - Briefcase (solid) */
.esop-section-case-studies h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z'/%3E%3C/svg%3E");
}

/* Articles - Document (solid) */
.esop-section-articles h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z'/%3E%3C/svg%3E");
}

/* Blog Posts - Pencil/Edit (solid) */
.esop-section-blog h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z'/%3E%3C/svg%3E");
}

/* Ratings - Star (solid filled) */
.esop-section-ratings h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
}

/* Videos - Play button (solid) */
.esop-section-videos h3::before {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23e35205'%3E%3Cpath d='M8 5v14l11-7z'/%3E%3C/svg%3E");
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

/* Advisor page rows - more spacing between items (override Divi custom CSS) */
.esop-advisor-page .esop-row,
.esop-advisor-page .esop-row--company,
.esop-advisor-page .esop-row--title,
.esop-advisor-page .esop-row--email,
.esop-advisor-page .esop-row--phone,
.esop-advisor-page .esop-row--cell,
.esop-advisor-page .esop-row--fax,
.esop-advisor-page .esop-row--website,
.esop-advisor-page .esop-row--about,
.esop-advisor-page .esop-row--services,
.esop-advisor-page div[class*="esop-"][class*="-row"] {
    margin: 0 0 15px 0 !important;
}
.esop-advisor-page .esop-row:last-child,
.esop-advisor-page div[class*="esop-"][class*="-row"]:last-child {
    margin-bottom: 0 !important;
}

/* Advisor name - 25% larger (26px base -> ~32px) */
.esop-advisor-page .et_pb_column_2_tb_body .et_pb_text h2,
.esop-advisor-page .et_pb_column_2_tb_body .et_pb_text_inner h2,
.esop-advisor-page .et_pb_text_2_tb_body h2,
.esop-advisor-page .et_pb_text_2_tb_body .et_pb_text_inner h2 {
    font-size: 32px !important;
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

/* Contact Button - Solid Pill Style (matches retired badge) */
/* Using high specificity to override Divi theme styles */
.esop-contact-button,
.esop-contact-button:link,
.esop-contact-button:visited,
.esop-contact-button:active,
a.esop-contact-button,
a.esop-contact-button:link,
a.esop-contact-button:visited,
a.esop-contact-button:active,
.et_pb_module a.esop-contact-button,
.et_pb_module a.esop-contact-button:link,
.et_pb_module a.esop-contact-button:visited,
.et_pb_module a.esop-contact-button:active,
.et_pb_text a.esop-contact-button,
.et_pb_text a.esop-contact-button:link,
.et_pb_text a.esop-contact-button:visited,
.et_pb_text a.esop-contact-button:active,
body .esop-contact-button,
body a.esop-contact-button {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    width: 100% !important;
    padding: 14px 24px !important;
    background: linear-gradient(135deg, #F1602A 0%, #D94E1D 100%) !important;
    color: #fff !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    line-height: 1 !important;
    text-decoration: none !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border: none !important;
    border-radius: 50px !important;
    box-shadow: 0 2px 6px rgba(241, 96, 42, 0.3) !important;
    cursor: pointer !important;
    box-sizing: border-box !important;
    transition: all .2s ease-in-out !important;
}

/* Remove the ::after arrow icon */
.esop-contact-button::after,
a.esop-contact-button::after,
body .esop-contact-button::after,
body a.esop-contact-button::after {
    content: none !important;
    display: none !important;
}

/* Hover state - slightly lighter */
.esop-contact-button:hover,
.esop-contact-button:focus,
a.esop-contact-button:hover,
a.esop-contact-button:focus,
.et_pb_module a.esop-contact-button:hover,
.et_pb_module a.esop-contact-button:focus,
.et_pb_text a.esop-contact-button:hover,
.et_pb_text a.esop-contact-button:focus,
body .esop-contact-button:hover,
body .esop-contact-button:focus,
body a.esop-contact-button:hover,
body a.esop-contact-button:focus {
    background: linear-gradient(135deg, #FF7040 0%, #E35F2A 100%) !important;
    color: #fff !important;
    text-decoration: none !important;
    box-shadow: 0 4px 12px rgba(241, 96, 42, 0.4) !important;
    transform: translateY(-1px) !important;
}

/* Single Advisor Profile Image - 360px x 360px on desktop */
/* Scoped to #main-content to avoid affecting footer logo */
.esop-advisor-page #main-content .et_pb_image {
    width: 360px !important;
    max-width: 360px !important;
}
.esop-advisor-page #main-content .et_pb_image .et_pb_image_wrap {
    width: 360px !important;
    max-width: 360px !important;
}
.esop-advisor-page #main-content .et_pb_image img {
    width: 360px !important;
    height: 360px !important;
    max-width: 360px !important;
    object-fit: cover !important;
}

/* Tablet - scale down profile image */
@media (max-width: 980px) {
    .esop-advisor-page #main-content .et_pb_image {
        width: 280px !important;
        max-width: 280px !important;
    }
    .esop-advisor-page #main-content .et_pb_image .et_pb_image_wrap {
        width: 280px !important;
        max-width: 280px !important;
    }
    .esop-advisor-page #main-content .et_pb_image img {
        width: 280px !important;
        height: 280px !important;
        max-width: 280px !important;
    }
}

/* Mobile - scale down further */
@media (max-width: 767px) {
    .esop-advisor-page #main-content .et_pb_image {
        width: 240px !important;
        max-width: 100% !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
    .esop-advisor-page #main-content .et_pb_image .et_pb_image_wrap {
        width: 240px !important;
        max-width: 100% !important;
    }
    .esop-advisor-page #main-content .et_pb_image img {
        width: 240px !important;
        height: 240px !important;
        max-width: 100% !important;
    }
}

/* Retired Badge - Solid Pill Style with Clock Icon */
.esop-retired-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    padding: 6px 14px;
    background: linear-gradient(135deg, #F1602A 0%, #D94E1D 100%);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(241, 96, 42, 0.3);
    cursor: default;
    box-sizing: border-box;
}

/* No hover state - badge stays static */
.esop-retired-badge:hover,
.esop-retired-badge:focus {
    background: linear-gradient(135deg, #F1602A 0%, #D94E1D 100%);
    color: #fff;
    text-decoration: none;
}

/* Badge clock icon */
.esop-retired-badge .esop-badge-icon {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

/* Wrapper for auto-displayed badge - matches profile image width */
.esop-retired-badge-wrapper {
    width: 360px;
    margin: 15px auto;
}

@media (max-width: 980px) {
    .esop-retired-badge-wrapper {
        width: 280px;
    }
}

@media (max-width: 767px) {
    .esop-retired-badge-wrapper {
        width: 240px;
    }
}

/* Honorary Badge - Solid Pill Style with Star Icon */
.esop-honorary-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    padding: 6px 14px;
    background: linear-gradient(135deg, #0073aa 0%, #005f8d 100%);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 600;
    line-height: 1;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(0, 115, 170, 0.3);
    cursor: default;
    box-sizing: border-box;
}

.esop-honorary-badge:hover,
.esop-honorary-badge:focus {
    background: linear-gradient(135deg, #0073aa 0%, #005f8d 100%);
    color: #fff;
    text-decoration: none;
}

.esop-honorary-badge .esop-badge-icon {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

.esop-honorary-badge-wrapper {
    width: 360px;
    margin: 15px auto;
}

@media (max-width: 980px) {
    .esop-honorary-badge-wrapper {
        width: 280px;
    }
}

@media (max-width: 767px) {
    .esop-honorary-badge-wrapper {
        width: 240px;
    }
}

/* Gray styling for retired advisor rows (icons and text) */
.esop-row--retired {
    color: #999 !important;
}
.esop-row--retired span {
    color: #999 !important;
}
.esop-row--retired .esop-row-icon,
.esop-row--retired svg {
    fill: #999 !important;
}
.esop-row--retired svg path {
    fill: #999 !important;
}

/* =====================================================
   SPECIALTY ARCHIVE PAGE STYLES
   Scoped to .advisor-specialty-archive body class
   Only affects /advisors/[specialty-slug]/ pages
   ===================================================== */

/* -------------------------------------------------------
   Force Divi Theme Builder header section transparent
   initially on specialty archive pages so the white logo
   and nav text are visible over the hero background image.
   The .et-db #et-boc .et-l prefix matches Divi's generated
   CSS specificity for non-pagebuilder pages.
   ------------------------------------------------------- */
body.advisor-specialty-archive .et_pb_section.et_pb_section_0_tb_header,
body.advisor-specialty-archive.et-db .et_pb_section.et_pb_section_0_tb_header {
    background-color: transparent !important;
}
/* White nav menu text when header is transparent (initial load) */
body.advisor-specialty-archive .et_pb_section_0_tb_header .et_pb_menu__menu a,
body.advisor-specialty-archive .et_pb_section_0_tb_header .et-menu-nav > ul > li > a,
body.advisor-specialty-archive .et_pb_section_0_tb_header .et_pb_menu__logo-wrap .et_pb_menu__logo a,
body.advisor-specialty-archive.et-db #et-boc .et-l .et_pb_section_0_tb_header .et_pb_menu__menu a,
body.advisor-specialty-archive.et-db #et-boc .et-l .et_pb_section_0_tb_header .et-menu-nav > ul > li > a {
    color: #FFFFFF !important;
}
/* White dropdown arrow icons */
body.advisor-specialty-archive .et_pb_section_0_tb_header .et-menu-nav > ul > li > a:after,
body.advisor-specialty-archive .et_pb_section_0_tb_header .et_pb_menu__menu > nav > ul > li > a:after {
    border-top-color: #FFFFFF !important;
}

/* Sticky header styles handled via JavaScript (see esop_specialty_sticky_header_js) */

/* -------------------------------------------------------
   Specialty Hero Section — background-image hero matching
   the site-wide Divi fullwidth header hero style.
   Injected via et_before_main_content hook.
   ------------------------------------------------------- */
.esop-specialty-hero {
    position: relative;
    height: 300px;
    background-image: url('https://new.esopmarketplace.com/wp-content/uploads/2025/11/hero-home.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: flex-end;
    width: 100%;
}
.esop-specialty-hero-overlay {
    display: none;
}
.esop-specialty-hero-inner {
    position: relative;
    z-index: 2;
    width: 80%;
    max-width: 1800px;
    margin: 0 auto;
    padding: 0 0 30px 0;
}
.esop-specialty-hero-title {
    color: #ffffff !important;
    font-family: 'Roboto Slab', Georgia, "Times New Roman", serif;
    font-size: 2.5em !important;
    font-weight: 600 !important;
    line-height: 1.25em !important;
    text-align: left !important;
    text-shadow: 0.05em 0.05em 0em rgba(0,0,0,0.7);
    margin: 0 !important;
    padding: 0 !important;
}
@media (max-width: 980px) {
    .esop-specialty-hero {
        height: 250px;
    }
    .esop-specialty-hero-title {
        font-size: 2em !important;
    }
    .esop-specialty-hero-inner {
        width: 90%;
    }
}
@media (max-width: 767px) {
    .esop-specialty-hero {
        height: 200px;
    }
    .esop-specialty-hero-title {
        font-size: 1.5em !important;
    }
    .esop-specialty-hero-inner {
        width: 96%;
        padding: 0 0 20px 0;
    }
}

/* Ensure main content has lower z-index than header */
.advisor-specialty-archive #main-content,
.advisor-specialty-archive #content-area,
.advisor-specialty-archive #esop-specialty-content,
.advisor-specialty-archive .esop-advisor-directory {
    position: relative;
    z-index: 1;
}

/* Remove sidebar completely */
.advisor-specialty-archive #sidebar {
    display: none !important;
}

/* Remove #left-area as a container - make content flow directly in #content-area */
.advisor-specialty-archive #left-area {
    width: 100% !important;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
}

/* Main content area */
.advisor-specialty-archive #content-area {
    width: 100% !important;
    padding-bottom: 60px !important;
    border: none !important;
}

/* Remove vertical line from container:before pseudo-element */
.advisor-specialty-archive #main-content .container:before,
.advisor-specialty-archive.et_right_sidebar #main-content .container:before,
.advisor-specialty-archive .container:before {
    display: none !important;
    content: none !important;
    width: 0 !important;
    height: 0 !important;
    background: none !important;
    background-color: transparent !important;
}

.advisor-specialty-archive .et_right_sidebar #left-area {
    width: 100% !important;
    border: none !important;
}

/* Remove page title from archive template (using shortcode in Divi hero instead) */
.advisor-specialty-archive .archive-title,
.advisor-specialty-archive .page-title,
.advisor-specialty-archive header.entry-header,
.advisor-specialty-archive .entry-title:not(h2):not(h3) {
    display: none !important;
}

/* Intro section (H2 + body text) displayed below hero, above advisor grid */
.advisor-specialty-archive .esop-specialty-intro {
    padding: 0px 0 60px;
}
.advisor-specialty-archive .esop-specialty-intro h2 {
    font-size: 1.75em;
    font-weight: 700;
    color: #1a2a3a;
    margin: 0 0 20px 0;
    line-height: 1.25;
}
.advisor-specialty-archive .esop-specialty-intro .esop-specialty-intro-body {
    font-size: 15px;
    line-height: 1.8;
    color: #444;
}
.advisor-specialty-archive .esop-specialty-intro .esop-specialty-intro-body p {
    margin: 0 0 18px 0;
}
.advisor-specialty-archive .esop-specialty-intro .esop-specialty-intro-body p:last-child {
    margin-bottom: 0;
}

/* Advisor grid spacing on specialty archive pages */
.advisor-specialty-archive .esop-advisor-directory .advisor-grid {
    margin-bottom: 50px !important;
}

/* Hide default archive page H1 title - will be placed via shortcode in Divi hero */
.advisor-specialty-archive .et_pb_module h1.entry-title,
.advisor-specialty-archive header.entry-header h1,
.advisor-specialty-archive .page-title,
.advisor-specialty-archive h1.archive-title {
    display: none !important;
}
</style>
<?php }

// ============================================================================
// BLOG PAGE WIDTH FIX (v1.31.1)
// ============================================================================

/**
 * Fix blog page width to match the rest of the site.
 *
 * Problem: Blog index (/esopblog/), single posts, and category archives
 * render narrower than other pages because:
 * 1. Posts have _et_pb_page_layout = 'et_right_sidebar' which wraps Theme
 *    Builder body content inside #left-area (~67.5% width) + #sidebar.
 * 2. Advisor posts have NO _et_pb_page_layout set, so they get full width.
 *
 * Fix: Override the Divi sidebar container widths on blog-related pages so
 * #left-area takes 100% width. The Blog template (16071) has its own sidebar
 * column built into the template layout. For single posts and category pages,
 * the Divi #sidebar is also expanded within a full-width container.
 *
 * @since 1.31.1
 */
add_action( 'wp_head', 'esop_blog_page_width_fix', 15 );

function esop_blog_page_width_fix() {
	// Only apply on blog-related pages (not specialty archives — those are handled separately)
	if ( ! is_home() && ! is_singular( 'post' ) && ! is_category() && ! is_tag() && ! is_author() ) {
		return;
	}
	// Don't apply on advisor specialty archives (already handled)
	if ( is_tax( 'advisor_specialty' ) ) {
		return;
	}
	?>
<style>
/* ==========================================================================
   Blog Page Width Fix — match blog pages to site-wide full width
   Targets: .blog (index), .single-post, .category, .tag, .author, .archive
   ========================================================================== */

/* Expand the main container to full content width */
.blog #et-main-area .container,
.single-post #et-main-area .container,
.category #et-main-area .container,
.tag #et-main-area .container,
.author #et-main-area .container {
	width: 100%;
	max-width: 1920px;
	padding-left: 30px;
	padding-right: 30px;
}

/* Make #left-area full width — Theme Builder body content fills the container */
.blog .et_right_sidebar #left-area,
.single-post .et_right_sidebar #left-area,
.category .et_right_sidebar #left-area,
.tag .et_right_sidebar #left-area,
.author .et_right_sidebar #left-area {
	width: 100% !important;
	float: none !important;
	padding-right: 0 !important;
}

/* Hide Divi's default #sidebar — blog template has its own sidebar column;
   single post and category templates use full-width layout */
.blog .et_right_sidebar #sidebar,
.single-post .et_right_sidebar #sidebar,
.category .et_right_sidebar #sidebar,
.tag .et_right_sidebar #sidebar,
.author .et_right_sidebar #sidebar {
	display: none !important;
}

/* Remove the Divi container separator line (::before pseudo-element) */
.blog #et-main-area .container::before,
.single-post #et-main-area .container::before,
.category #et-main-area .container::before,
.tag #et-main-area .container::before,
.author #et-main-area .container::before {
	display: none !important;
	content: none !important;
}

/* Ensure Theme Builder body sections use full available width */
.blog .et-l--body .et_pb_section,
.single-post .et-l--body .et_pb_section,
.category .et-l--body .et_pb_section,
.tag .et-l--body .et_pb_section,
.author .et-l--body .et_pb_section {
	width: 100%;
}

/* Ensure fullwidth sections in Theme Builder header are not constrained */
.blog .et-l--header .et_pb_section,
.single-post .et-l--header .et_pb_section,
.category .et-l--header .et_pb_section,
.tag .et-l--header .et_pb_section,
.author .et-l--header .et_pb_section {
	width: 100%;
}

/* ---- Responsive ---- */

@media (max-width: 980px) {
	.blog #et-main-area .container,
	.single-post #et-main-area .container,
	.category #et-main-area .container,
	.tag #et-main-area .container,
	.author #et-main-area .container {
		width: 100%;
		padding-left: 20px;
		padding-right: 20px;
	}
}

@media (max-width: 767px) {
	.blog #et-main-area .container,
	.single-post #et-main-area .container,
	.category #et-main-area .container,
	.tag #et-main-area .container,
	.author #et-main-area .container {
		padding-left: 15px;
		padding-right: 15px;
	}
}
</style>
<?php }

// ============================================================================
// CONDITIONAL SECTION HIDING
// ============================================================================

/**
 * Output CSS to hide Divi sections when their corresponding field is empty
 *
 * Add these CSS classes to your Divi sections:
 * - esop-section-resources (checks website, about_url, services_url, linkedin - shows if ANY has content)
 * - esop-section-education (checks education AND expertise fields - shows if EITHER has content)
 * - esop-section-case-studies (checks for case studies posts)
 * - esop-section-articles (checks for articles posts)
 * - esop-section-blog (checks for blog posts - excludes case-studies, articles, ratings categories)
 * - esop-section-ratings (checks for ratings posts)
 * - esop-section-videos (checks videos field)
 *
 * Note: Location and Biography sections are always shown (not auto-hidden)
 */
add_action( 'wp_head', 'esop_advisor_hide_empty_sections', 20 );

function esop_advisor_hide_empty_sections() {
	// Only run on single advisor pages
	if ( ! is_singular( 'esop_advisor' ) ) {
		return;
	}

	// Use get_queried_object_id() which is more reliable during wp_head with Divi Theme Builder
	$advisor_id = get_queried_object_id();
	if ( ! $advisor_id || get_post_type( $advisor_id ) !== 'esop_advisor' ) {
		return;
	}

	$hidden_sections = array();

	// Helper function to check if rich text field has actual content
	// Strips HTML, decodes entities, removes non-breaking spaces (both &nbsp; and UTF-8 \xc2\xa0)
	$has_rich_text_content = function( $value ) {
		$text = wp_strip_all_tags( html_entity_decode( $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		// Remove regular spaces, non-breaking spaces (UTF-8), tabs, newlines
		$text = preg_replace( '/[\s\x{00a0}]+/u', '', $text );
		return $text !== '';
	};

	// Check education & expertise fields (both are in the same admin meta box)
	// Section should show if either field has content
	$education = (string) get_post_meta( $advisor_id, '_esop_advisor_education', true );
	$expertise = (string) get_post_meta( $advisor_id, '_esop_advisor_expertise', true );

	$has_education = $has_rich_text_content( $education ) || $has_rich_text_content( $expertise );

	if ( ! $has_education ) {
		$hidden_sections[] = '.esop-section-education';
	}

	$videos = (string) get_post_meta( $advisor_id, '_esop_advisor_videos', true );
	if ( ! $has_rich_text_content( $videos ) ) {
		$hidden_sections[] = '.esop-section-videos';
	}

	// Check resources (website, about_url, services_url, linkedin)
	// Section should only hide if ALL resource links are empty
	$website      = (string) get_post_meta( $advisor_id, '_esop_advisor_website', true );
	$about_url    = (string) get_post_meta( $advisor_id, '_esop_advisor_about_url', true );
	$services_url = (string) get_post_meta( $advisor_id, '_esop_advisor_services_url', true );
	$linkedin     = (string) get_post_meta( $advisor_id, '_esop_advisor_linkedin', true );

	$has_resources = trim( $website ) !== ''
		|| trim( $about_url ) !== ''
		|| trim( $services_url ) !== ''
		|| trim( $linkedin ) !== '';

	if ( ! $has_resources ) {
		$hidden_sections[] = '.esop-section-resources';
	}

	// Check for posts in each category
	if ( ! esop_advisor_has_posts_in_category( $advisor_id, 'case-studies' ) ) {
		$hidden_sections[] = '.esop-section-case-studies';
	}

	if ( ! esop_advisor_has_posts_in_category( $advisor_id, 'articles' ) ) {
		$hidden_sections[] = '.esop-section-articles';
	}

	if ( ! esop_advisor_has_posts_in_category( $advisor_id, 'blog' ) ) {
		$hidden_sections[] = '.esop-section-blog';
	}

	if ( ! esop_advisor_has_posts_in_category( $advisor_id, 'ratings' ) ) {
		$hidden_sections[] = '.esop-section-ratings';
	}

	// Define the order of the 6 alternating sections (after Biography which is always #f5f5dc)
	// Note: Resources is inside the Biography section, not its own Divi section
	$alternating_sections = array(
		'.esop-section-education',
		'.esop-section-case-studies',
		'.esop-section-articles',
		'.esop-section-blog',
		'.esop-section-ratings',
		'.esop-section-videos',
	);

	// Filter to get only visible sections (not in hidden list)
	$visible_sections = array_filter( $alternating_sections, function( $section ) use ( $hidden_sections ) {
		return ! in_array( $section, $hidden_sections, true );
	} );

	// Re-index the array
	$visible_sections = array_values( $visible_sections );

	// Build alternating background colors
	// Biography is #f5f5dc, so next visible section should be #fff (white)
	$bg_colors = array();
	foreach ( $visible_sections as $index => $selector ) {
		// Even index (0, 2, 4...) = white, Odd index (1, 3, 5...) = beige
		$bg_colors[ $selector ] = ( $index % 2 === 0 ) ? '#fff' : '#f5f5dc';
	}

	// Output CSS for hiding and alternating backgrounds
	echo "<style id=\"esop-advisor-section-styles\">\n";

	// Hidden sections
	foreach ( $hidden_sections as $selector ) {
		echo esc_html( $selector ) . " { display: none !important; }\n";
	}

	// Alternating background colors for visible sections
	foreach ( $bg_colors as $selector => $color ) {
		echo esc_html( $selector ) . " { background-color: " . esc_html( $color ) . " !important; }\n";
	}

	echo "</style>\n";
}

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
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'posts_per_page'   => $per_page,
		'offset'           => $offset,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'suppress_filters' => true, // Prevent any filters from modifying this query
	);

	// Build post selection criteria (author OR manual association)
	if ( ! empty( $author_id ) && ! empty( $advisor_id ) ) {
		// Both author-based and manually associated posts
		// Get post IDs associated with this advisor
		// IMPORTANT: suppress_filters=true prevents pre_get_posts from adding author constraints
		$associated_posts = get_posts( array(
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'fields'           => 'ids',
			'suppress_filters' => true, // Critical: bypass our own pre_get_posts filter
			'meta_query'       => array(
				array(
					'key'   => '_esop_associated_advisor',
					'value' => intval( $advisor_id ),
				),
			),
		) );

		// Get author's posts
		$author_posts = get_posts( array(
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'posts_per_page'   => -1,
			'fields'           => 'ids',
			'suppress_filters' => true, // Consistent with above
			'author'           => intval( $author_id ),
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
 * Check if an advisor is retired
 *
 * @param int|null $post_id Advisor post ID (uses current post if null)
 * @return bool True if advisor is retired, false otherwise
 */
function esop_advisor_is_retired( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = esop_advisor_get_current_advisor_id();
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
	}

	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return false;
	}

	return get_post_meta( $post_id, '_esop_advisor_retired', true ) === '1';
}

/**
 * Check if an advisor is an honorary advisor
 *
 * @param int|null $post_id Advisor post ID (uses current post if null)
 * @return bool True if advisor is honorary, false otherwise
 */
function esop_advisor_is_honorary( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = esop_advisor_get_current_advisor_id();
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
	}

	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return false;
	}

	return get_post_meta( $post_id, '_esop_advisor_honorary', true ) === '1';
}

/**
 * Truncate email for display (first 10 characters + ellipsis)
 *
 * @param string $email Full email address
 * @return string Truncated email for display
 */
function esop_truncate_email_display( $email ) {
	if ( strlen( $email ) > 10 ) {
		return substr( $email, 0, 10 ) . '...';
	}
	return $email;
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
	$rich_text_fields = array( 'bio', 'education', 'expertise', 'videos' );

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
 * Uses the same query logic as the shortcodes to ensure consistency.
 * Supports both author-based posts and manually associated posts.
 *
 * @param int|null $advisor_id  Advisor post ID
 * @param string   $category    Category key: 'case-studies', 'articles', 'ratings', or 'blog'
 * @return bool
 */
function esop_advisor_has_posts_in_category( $advisor_id = null, $category = '' ) {
	if ( ! $advisor_id ) {
		$advisor_id = esop_advisor_get_current_advisor_id();
	}

	if ( ! $advisor_id ) {
		return false;
	}

	// Map category slug to shortcode tag for category config lookup
	$category_to_tag = array(
		'case-studies' => 'esop_advisor_case_studies',
		'articles'     => 'esop_advisor_articles',
		'ratings'      => 'esop_advisor_ratings',
		'blog'         => 'esop_advisor_blog',
	);

	$tag = isset( $category_to_tag[ $category ] ) ? $category_to_tag[ $category ] : '';

	if ( empty( $tag ) ) {
		return false;
	}

	// Get category config (same as shortcode uses)
	$category_map  = esop_advisor_get_category_config();
	$category_info = isset( $category_map[ $tag ] ) ? $category_map[ $tag ] : null;

	if ( ! $category_info ) {
		return false;
	}

	// Get linked user ID (may be empty if posts are manually associated)
	$author_id = get_post_meta( $advisor_id, '_esop_advisor_user_id', true );

	// Build query args using the same helper as shortcodes
	$query_args = esop_advisor_build_posts_query_args( $category_info, $author_id, 1, 0, $advisor_id );

	// We only need to check if at least one post exists
	$query_args['posts_per_page'] = 1;
	$query_args['fields'] = 'ids';

	$query = new WP_Query( $query_args );

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
		'esop_address2',
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
		'esop_expertise',
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

	// Check if advisor is retired
	$is_retired = esop_advisor_is_retired( $post_id );

	// Field type definitions
	$rich_text_fields = array( 'bio', 'education', 'expertise', 'videos' );
	$url_fields       = array( 'website', 'about_url', 'services_url', 'linkedin' );

	// URL field display text for retired advisors
	$url_display_text = array(
		'website'      => 'Our Home Page',
		'about_url'    => 'About Us',
		'services_url' => 'Our Services',
		'linkedin'     => 'LinkedIn Profile',
	);

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

	// URL fields: handle retired advisors specially
	if ( in_array( $field, $url_fields, true ) ) {
		// For retired advisors, return display text instead of URL (no link)
		if ( $is_retired ) {
			$display_text = isset( $url_display_text[ $field ] ) ? $url_display_text[ $field ] : '';
			return $atts['before'] . esc_html( $display_text ) . $atts['after'];
		}

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
	// For retired advisors, skip link wrapping for contact-related links
	if ( $is_retired && ! empty( $atts['link'] ) ) {
		$link_type = strtolower( $atts['link'] );
		if ( in_array( $link_type, array( 'tel', 'mailto' ), true ) ) {
			// Return plain text without link for retired advisors
			return $atts['before'] . $escaped_value . $atts['after'];
		}
	}

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
			// Returns multi-line formatted address with HTML line breaks:
			// 7500 Flying Cloud Drive<br>Suite 800<br>Eden Prairie, MN 55344
			$address  = esop_get_advisor_field( $post_id, 'address' );
			$address2 = esop_get_advisor_field( $post_id, 'address2' );
			$city     = esop_get_advisor_field( $post_id, 'city' );
			$state    = esop_get_advisor_field( $post_id, 'state' );
			$zip      = esop_get_advisor_field( $post_id, 'zip' );
			if ( empty( $address ) && empty( $city ) && empty( $state ) ) {
				return '';
			}
			$lines = array();
			if ( ! empty( $address ) ) {
				$lines[] = esc_html( $address );
			}
			if ( ! empty( $address2 ) ) {
				$lines[] = esc_html( $address2 );
			}
			// Build city, state zip line
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
				$lines[] = $city_line;
			}
			return implode( '<br>', $lines );

		case 'full_address':
			// Returns comma-separated address: "123 Main St, Suite 100, City, ST 12345"
			$address  = esop_get_advisor_field( $post_id, 'address' );
			$address2 = esop_get_advisor_field( $post_id, 'address2' );
			$city     = esop_get_advisor_field( $post_id, 'city' );
			$state    = esop_get_advisor_field( $post_id, 'state' );
			$zip      = esop_get_advisor_field( $post_id, 'zip' );
			if ( empty( $address ) && empty( $city ) && empty( $state ) && empty( $zip ) ) {
				return '';
			}
			// Build: "123 Main St, Suite 100, City, ST 12345"
			$parts      = array();
			$parts[]    = $address;
			if ( ! empty( $address2 ) ) {
				$parts[] = $address2;
			}
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
	$address  = esop_get_advisor_field( $post_id, 'address' );
	$address2 = esop_get_advisor_field( $post_id, 'address2' );
	$city     = esop_get_advisor_field( $post_id, 'city' );
	$state    = esop_get_advisor_field( $post_id, 'state' );
	$zip      = esop_get_advisor_field( $post_id, 'zip' );

	return trim( implode( ', ', array_filter( array( $address, $address2, $city, $state, $zip ) ) ) );
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
		// Phone icon - orange to match other icons
		'phone' => '<svg class="esop-row-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e35205"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>',
		// Mobile/Cell icon - orange to match other icons
		'cell'  => '<svg class="esop-row-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e35205"><path d="M16 1H8C6.34 1 5 2.34 5 4v16c0 1.66 1.34 3 3 3h8c1.66 0 3-1.34 3-3V4c0-1.66-1.34-3-3-3zm-2 20h-4v-1h4v1zm3.25-3H6.75V4h10.5v14z"/></svg>',
		// Fax/Print icon - orange to match other icons
		'fax'   => '<svg class="esop-row-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e35205"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>',
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

	// Combined shortcodes - display all phones, all links, all contact info
	add_shortcode( 'esop_all_phones', 'esop_advisor_all_phones_shortcode' );
	add_shortcode( 'esop_all_links', 'esop_advisor_all_links_shortcode' );
	add_shortcode( 'esop_contact_info', 'esop_advisor_contact_info_shortcode' );
	add_shortcode( 'esop_company_info', 'esop_advisor_company_info_shortcode' );

	// Combined education & expertise shortcode
	add_shortcode( 'esop_education_expertise', 'esop_advisor_education_expertise_shortcode' );

	// Retired badge shortcode
	add_shortcode( 'esop_retired_badge', 'esop_advisor_retired_badge_shortcode' );
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
	$display_email = esop_truncate_email_display( $sanitized_email );

	// If advisor is retired, show plain text without link
	if ( esop_advisor_is_retired( $post_id ) ) {
		return '<div class="esop-row esop-row--email"><strong>Email:</strong> <span>' . esc_html( $display_email ) . '</span></div>';
	}

	return '<div class="esop-row esop-row--email"><strong>Email:</strong> <a href="mailto:' . esc_attr( $sanitized_email ) . '">' . esc_html( $display_email ) . '</a></div>';
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

	// Format display: convert periods to dashes
	$display_phone = str_replace( '.', '-', $phone );
	$icon = esop_advisor_get_row_icon( 'phone' );

	// If advisor is retired, show plain text without link (with retired class for gray styling)
	if ( esop_advisor_is_retired( $post_id ) ) {
		return '<div class="esop-row esop-row--phone esop-row--retired">' . $icon . '<span>' . esc_html( $display_phone ) . '</span></div>';
	}

	$clean_phone = esop_advisor_sanitize_phone_for_tel( $phone );
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

	// Format display: convert periods to dashes
	$display_cell = str_replace( '.', '-', $cell );
	$icon = esop_advisor_get_row_icon( 'cell' );

	// If advisor is retired, show plain text without link (with retired class for gray styling)
	if ( esop_advisor_is_retired( $post_id ) ) {
		return '<div class="esop-row esop-row--cell esop-row--retired">' . $icon . '<span>' . esc_html( $display_cell ) . '</span></div>';
	}

	$clean_cell = esop_advisor_sanitize_phone_for_tel( $cell );
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

	// Add retired class for gray styling when advisor is retired
	$retired_class = esop_advisor_is_retired( $post_id ) ? ' esop-row--retired' : '';

	return '<div class="esop-row esop-row--fax' . $retired_class . '">' . $icon . '<span>' . esc_html( $display_fax ) . '</span></div>';
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

	// If advisor is retired, show plain text without link
	if ( esop_advisor_is_retired( $post_id ) ) {
		return '<div class="esop-row esop-row--website">' . $icon . '<span>' . esc_html( $atts['text'] ) . '</span></div>';
	}

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

	// If advisor is retired, show plain text without link
	if ( esop_advisor_is_retired( $post_id ) ) {
		return '<div class="esop-row esop-row--about">' . $icon . '<span>' . esc_html( $atts['text'] ) . '</span></div>';
	}

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

	// If advisor is retired, show plain text without link
	if ( esop_advisor_is_retired( $post_id ) ) {
		return '<div class="esop-row esop-row--services">' . $icon . '<span>' . esc_html( $atts['text'] ) . '</span></div>';
	}

	return '<div class="esop-row esop-row--services">' . $icon . '<a href="' . esc_url( $services_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['text'] ) . '</a></div>';
}

/**
 * Address block shortcode
 *
 * Output: {street_address}<br>{address2}<br>{city}, {state} {zip}
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

	$address  = esop_get_advisor_field( $post_id, 'address' );
	$address2 = esop_get_advisor_field( $post_id, 'address2' );
	$city     = esop_get_advisor_field( $post_id, 'city' );
	$state    = esop_get_advisor_field( $post_id, 'state' );
	$zip      = esop_get_advisor_field( $post_id, 'zip' );

	// Return empty if both address AND city are empty
	if ( empty( $address ) && empty( $city ) ) {
		return '';
	}

	$output = '<div class="esop-address-block">';

	// First line: street address
	if ( ! empty( $address ) ) {
		$output .= esc_html( $address );
	}

	// Second line: address line 2 (suite/unit/PO box)
	if ( ! empty( $address2 ) ) {
		if ( ! empty( $address ) ) {
			$output .= '<br>';
		}
		$output .= esc_html( $address2 );
	}

	// Third line: city, state zip
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
		if ( ! empty( $address ) || ! empty( $address2 ) ) {
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

	// Do not display contact button for retired advisors
	if ( esop_advisor_is_retired( $post_id ) ) {
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

	$advisor_name = get_the_title( $post_id );
	$modal_id     = 'esop-lead-modal-' . intval( $post_id );

	// Build the button (use <a> to preserve Divi template CSS targeting a.esop-contact-button)
	$output = sprintf(
		'<a href="#" role="button" class="%s" data-advisor-id="%d" data-advisor-name="%s" data-modal-target="%s">%s</a>',
		esc_attr( $class ),
		intval( $post_id ),
		esc_attr( $advisor_name ),
		esc_attr( $modal_id ),
		esc_html( $atts['text'] )
	);

	// Render the modal HTML only once per page
	static $modal_rendered = false;
	if ( ! $modal_rendered ) {
		$modal_rendered = true;

		$output .= '<div id="' . esc_attr( $modal_id ) . '" class="esop-lead-modal" role="dialog" aria-modal="true" aria-labelledby="esop-lead-modal-title" style="display:none;">';
		$output .= '<div class="esop-lead-modal__container">';
		$output .= '<button type="button" class="esop-lead-modal__close" aria-label="Close">&times;</button>';
		$output .= '<h2 id="esop-lead-modal-title" class="esop-lead-modal__title">Contact ' . esc_html( $advisor_name ) . '</h2>';
		$output .= '<div class="esop-lead-modal__form-wrap">';
		$output .= do_shortcode( '[ninja_form id=5]' );
		$output .= '</div>';
		$output .= '</div>'; // __container
		$output .= '</div>'; // modal
	}

	return $output;
}

/**
 * All phones shortcode - displays phone, cell, and fax in one block
 *
 * Usage: [esop_all_phones]
 * Output: Shows all phone numbers that have values
 */
function esop_advisor_all_phones_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$output = '';
	$is_retired = esop_advisor_is_retired( $post_id );
	$retired_class = $is_retired ? ' esop-row--retired' : '';

	// Phone
	$phone = esop_get_advisor_field( $post_id, 'phone' );
	if ( ! empty( $phone ) ) {
		$display_phone = str_replace( '.', '-', $phone );
		$icon = esop_advisor_get_row_icon( 'phone' );
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--phone' . $retired_class . '">' . $icon . '<span>' . esc_html( $display_phone ) . '</span></div>';
		} else {
			$clean_phone = esop_advisor_sanitize_phone_for_tel( $phone );
			$output .= '<div class="esop-row esop-row--phone">' . $icon . '<a href="tel:' . esc_attr( $clean_phone ) . '">' . esc_html( $display_phone ) . '</a></div>';
		}
	}

	// Cell
	$cell = esop_get_advisor_field( $post_id, 'cell' );
	if ( ! empty( $cell ) ) {
		$display_cell = str_replace( '.', '-', $cell );
		$icon = esop_advisor_get_row_icon( 'cell' );
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--cell' . $retired_class . '">' . $icon . '<span>' . esc_html( $display_cell ) . '</span></div>';
		} else {
			$clean_cell = esop_advisor_sanitize_phone_for_tel( $cell );
			$output .= '<div class="esop-row esop-row--cell">' . $icon . '<a href="tel:' . esc_attr( $clean_cell ) . '">' . esc_html( $display_cell ) . '</a></div>';
		}
	}

	// Fax (always plain text, add retired class for gray styling)
	$fax = esop_get_advisor_field( $post_id, 'fax' );
	if ( ! empty( $fax ) ) {
		$display_fax = str_replace( '.', '-', $fax );
		$icon = esop_advisor_get_row_icon( 'fax' );
		$output .= '<div class="esop-row esop-row--fax' . $retired_class . '">' . $icon . '<span>' . esc_html( $display_fax ) . '</span></div>';
	}

	return $output;
}

/**
 * All links shortcode - displays website, about, services, and linkedin in one block
 *
 * Usage: [esop_all_links]
 * Output: Shows all links that have values
 */
function esop_advisor_all_links_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id'       => '',
		'website_text'  => 'Our Home Page',
		'about_text'    => 'About Us',
		'services_text' => 'Our Services',
		'linkedin_text' => 'LinkedIn Profile',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$output = '';
	$icon = esop_advisor_get_row_icon( 'link' );
	$is_retired = esop_advisor_is_retired( $post_id );

	// Website
	$website = esop_get_advisor_field( $post_id, 'website' );
	if ( ! empty( $website ) ) {
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--website">' . $icon . '<span>' . esc_html( $atts['website_text'] ) . '</span></div>';
		} else {
			$output .= '<div class="esop-row esop-row--website">' . $icon . '<a href="' . esc_url( $website ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['website_text'] ) . '</a></div>';
		}
	}

	// About URL
	$about_url = esop_get_advisor_field( $post_id, 'about_url' );
	if ( ! empty( $about_url ) ) {
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--about">' . $icon . '<span>' . esc_html( $atts['about_text'] ) . '</span></div>';
		} else {
			$output .= '<div class="esop-row esop-row--about">' . $icon . '<a href="' . esc_url( $about_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['about_text'] ) . '</a></div>';
		}
	}

	// Services URL
	$services_url = esop_get_advisor_field( $post_id, 'services_url' );
	if ( ! empty( $services_url ) ) {
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--services">' . $icon . '<span>' . esc_html( $atts['services_text'] ) . '</span></div>';
		} else {
			$output .= '<div class="esop-row esop-row--services">' . $icon . '<a href="' . esc_url( $services_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['services_text'] ) . '</a></div>';
		}
	}

	// LinkedIn
	$linkedin = esop_get_advisor_field( $post_id, 'linkedin' );
	if ( ! empty( $linkedin ) ) {
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--linkedin">' . $icon . '<span>' . esc_html( $atts['linkedin_text'] ) . '</span></div>';
		} else {
			$output .= '<div class="esop-row esop-row--linkedin">' . $icon . '<a href="' . esc_url( $linkedin ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $atts['linkedin_text'] ) . '</a></div>';
		}
	}

	return $output;
}

/**
 * Contact info shortcode - displays email + all phones in one block
 *
 * Usage: [esop_contact_info]
 * Output: Shows email and all phone numbers that have values
 */
function esop_advisor_contact_info_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$output = '';
	$is_retired = esop_advisor_is_retired( $post_id );

	// Email
	$email = esop_get_advisor_field( $post_id, 'email' );
	if ( ! empty( $email ) ) {
		$sanitized_email = sanitize_email( $email );
		$display_email = esop_truncate_email_display( $sanitized_email );
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--email"><strong>Email:</strong> <span>' . esc_html( $display_email ) . '</span></div>';
		} else {
			$output .= '<div class="esop-row esop-row--email"><strong>Email:</strong> <a href="mailto:' . esc_attr( $sanitized_email ) . '">' . esc_html( $display_email ) . '</a></div>';
		}
	}

	// Add all phones (handles retired status internally)
	$output .= esop_advisor_all_phones_shortcode( $atts );

	return $output;
}

/**
 * Company info shortcode - displays company, title, and email in one block
 *
 * Usage: [esop_company_info]
 * Output: Shows company, title, and email with labels (only those with values)
 */
function esop_advisor_company_info_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$output = '';
	$label_style = 'color: #666;';
	$is_retired = esop_advisor_is_retired( $post_id );

	// Company
	$company = esop_get_advisor_field( $post_id, 'company' );
	if ( ! empty( $company ) ) {
		$output .= '<div class="esop-row esop-row--company"><span class="esop-row__label" style="' . $label_style . '">Company: </span>' . esc_html( $company ) . '</div>';
	}

	// Title
	$title = esop_get_advisor_field( $post_id, 'title' );
	if ( ! empty( $title ) ) {
		$output .= '<div class="esop-row esop-row--title"><span class="esop-row__label" style="' . $label_style . '">Title: </span>' . esc_html( $title ) . '</div>';
	}

	// Email
	$email = esop_get_advisor_field( $post_id, 'email' );
	if ( ! empty( $email ) ) {
		$sanitized_email = sanitize_email( $email );
		$display_email = esop_truncate_email_display( $sanitized_email );
		if ( $is_retired ) {
			$output .= '<div class="esop-row esop-row--email"><span class="esop-row__label" style="' . $label_style . '">Email: </span><span>' . esc_html( $display_email ) . '</span></div>';
		} else {
			$output .= '<div class="esop-row esop-row--email"><span class="esop-row__label" style="' . $label_style . '">Email: </span><a href="mailto:' . esc_attr( $sanitized_email ) . '">' . esc_html( $display_email ) . '</a></div>';
		}
	}

	return $output;
}

/**
 * Combined Education & Expertise shortcode
 *
 * Usage: [esop_education_expertise]
 * Output: Displays both education and expertise content (whichever has values)
 * Use this in the Divi template instead of separate [esop_education] and [esop_expertise] shortcodes
 */
function esop_advisor_education_expertise_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	$output = '';

	// Education
	$education = get_post_meta( $post_id, '_esop_advisor_education', true );
	if ( ! empty( $education ) ) {
		$education_text = trim( wp_strip_all_tags( html_entity_decode( $education, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		$education_text = preg_replace( '/[\s\x{00a0}]+/u', '', $education_text );
		if ( $education_text !== '' ) {
			$output .= '<div class="esop-education-content">' . wp_kses_post( wpautop( $education ) ) . '</div>';
		}
	}

	// Expertise
	$expertise = get_post_meta( $post_id, '_esop_advisor_expertise', true );
	if ( ! empty( $expertise ) ) {
		$expertise_text = trim( wp_strip_all_tags( html_entity_decode( $expertise, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
		$expertise_text = preg_replace( '/[\s\x{00a0}]+/u', '', $expertise_text );
		if ( $expertise_text !== '' ) {
			$output .= '<div class="esop-expertise-content">' . wp_kses_post( wpautop( $expertise ) ) . '</div>';
		}
	}

	return $output;
}

// ============================================================================
// RETIRED BADGE SHORTCODE AND AUTO-DISPLAY
// ============================================================================

/**
 * Retired badge shortcode
 *
 * Usage: [esop_retired_badge]
 * Output: <span class="esop-retired-badge">Retired</span>
 * Only displays if advisor is retired
 */
function esop_advisor_retired_badge_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'text'    => 'Retired',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	// Only display badge if advisor is retired
	if ( ! esop_advisor_is_retired( $post_id ) ) {
		return '';
	}

	$clock_icon = '<svg class="esop-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
	return '<span class="esop-retired-badge">' . $clock_icon . esc_html( $atts['text'] ) . '</span>';
}

/**
 * Auto-display retired badge on single advisor pages
 *
 * Injects the badge after the featured image area via the_content filter
 */
add_filter( 'the_content', 'esop_advisor_auto_display_retired_badge', 5 );
function esop_advisor_auto_display_retired_badge( $content ) {
	// Only run on single advisor pages on the frontend
	if ( is_admin() || ! is_singular( 'esop_advisor' ) ) {
		return $content;
	}

	// Only run in the main query
	if ( ! is_main_query() ) {
		return $content;
	}

	$post_id = get_queried_object_id();
	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return $content;
	}

	// Only display if advisor is retired
	if ( ! esop_advisor_is_retired( $post_id ) ) {
		return $content;
	}

	// Build the badge HTML with clock icon
	$clock_icon = '<svg class="esop-badge-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
	$badge_html = '<div class="esop-retired-badge-wrapper">';
	$badge_html .= '<span class="esop-retired-badge">' . $clock_icon . 'Retired</span>';
	$badge_html .= '</div>';

	// Prepend badge to content (after featured image which is typically before the_content)
	return $badge_html . $content;
}

// ============================================================================
// HONORARY BADGE SHORTCODE AND AUTO-DISPLAY
// ============================================================================

/**
 * Honorary badge shortcode
 *
 * Usage: [esop_honorary_badge]
 * Output: <span class="esop-honorary-badge">Honorary Advisor</span>
 * Only displays if advisor is honorary
 */
add_shortcode( 'esop_honorary_badge', 'esop_advisor_honorary_badge_shortcode' );

function esop_advisor_honorary_badge_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'post_id' => '',
		'text'    => 'Honorary Advisor',
	), $atts );

	$post_id = esop_advisor_get_row_post_id( $atts );
	if ( ! $post_id ) {
		return '';
	}

	if ( ! esop_advisor_is_honorary( $post_id ) ) {
		return '';
	}

	$star_icon = '<svg class="esop-badge-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
	return '<span class="esop-honorary-badge">' . $star_icon . esc_html( $atts['text'] ) . '</span>';
}

/**
 * Auto-display honorary badge on single advisor pages
 *
 * Injects the badge after the featured image area via the_content filter
 */
add_filter( 'the_content', 'esop_advisor_auto_display_honorary_badge', 6 );
function esop_advisor_auto_display_honorary_badge( $content ) {
	if ( is_admin() || ! is_singular( 'esop_advisor' ) ) {
		return $content;
	}

	if ( ! is_main_query() ) {
		return $content;
	}

	$post_id = get_queried_object_id();
	if ( ! $post_id || get_post_type( $post_id ) !== 'esop_advisor' ) {
		return $content;
	}

	if ( ! esop_advisor_is_honorary( $post_id ) ) {
		return $content;
	}

	$star_icon = '<svg class="esop-badge-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
	$badge_html = '<div class="esop-honorary-badge-wrapper">';
	$badge_html .= '<span class="esop-honorary-badge">' . $star_icon . 'Honorary Advisor</span>';
	$badge_html .= '</div>';

	return $badge_html . $content;
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
	global $post, $wpdb;

	$advisor_id = esop_advisor_get_current_advisor_id();
	$author_id  = $advisor_id ? get_post_meta( $advisor_id, '_esop_advisor_user_id', true ) : null;

	// Get category config
	$category_map = esop_advisor_get_category_config();

	// DIRECT DATABASE QUERY to see what's actually stored
	$db_associated = $wpdb->get_results( $wpdb->prepare(
		"SELECT pm.post_id, pm.meta_value, p.post_title, p.post_status
		 FROM {$wpdb->postmeta} pm
		 JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		 WHERE pm.meta_key = '_esop_associated_advisor'
		 AND pm.meta_value = %s
		 LIMIT 20",
		strval( $advisor_id )
	) );

	// Also check with integer comparison
	$db_associated_int = $wpdb->get_results( $wpdb->prepare(
		"SELECT pm.post_id, pm.meta_value, p.post_title, p.post_status
		 FROM {$wpdb->postmeta} pm
		 JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		 WHERE pm.meta_key = '_esop_associated_advisor'
		 AND pm.meta_value = %d
		 LIMIT 20",
		intval( $advisor_id )
	) );

	// Check ALL posts with this meta key to see what values exist
	$all_associated = $wpdb->get_results(
		"SELECT pm.post_id, pm.meta_value, p.post_title
		 FROM {$wpdb->postmeta} pm
		 JOIN {$wpdb->posts} p ON pm.post_id = p.ID
		 WHERE pm.meta_key = '_esop_associated_advisor'
		 AND p.post_type = 'post'
		 LIMIT 30"
	);

	// Test query using WP_Query (original method)
	$test_query_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'   => '_esop_associated_advisor',
				'value' => strval( $advisor_id ), // Try string instead of int
			),
		),
	);
	$associated_posts = get_posts( $test_query_args );

	$diagnostic_data = array(
		'timestamp'          => current_time( 'mysql' ),
		'advisor_id'         => $advisor_id,
		'advisor_id_type'    => gettype( $advisor_id ),
		'get_the_ID'         => get_the_ID(),
		'global_post_id'     => $post ? $post->ID : 'null',
		'global_post_type'   => $post ? $post->post_type : 'null',
		'queried_object'     => get_queried_object_id(),
		'is_singular'        => is_singular() ? 'yes' : 'no',
		'author_id'          => $author_id,
		'wp_query_results'   => $associated_posts,
		'wp_query_count'     => count( $associated_posts ),
		'db_string_match'    => $db_associated,
		'db_int_match'       => $db_associated_int,
		'all_associations'   => $all_associated,
		'category_map_keys'  => array_keys( $category_map ),
		'ob_level'           => ob_get_level(),
		'did_action_wp'      => did_action( 'wp' ),
		'current_filter'     => current_filter(),
		'is_admin'           => is_admin() ? 'yes' : 'no',
	);

	// Always log to error_log if debug is enabled
	esop_advisor_debug_log( 'DIAGNOSTIC SHORTCODE', $diagnostic_data );

	// Return HTML comment with diagnostic data (invisible but viewable in source)
	$output = "\n<!-- ESOP Advisor Diagnostic\n";
	$output .= "Advisor ID: {$diagnostic_data['advisor_id']} (type: {$diagnostic_data['advisor_id_type']})\n";
	$output .= "get_the_ID(): {$diagnostic_data['get_the_ID']}\n";
	$output .= "Global \$post ID: {$diagnostic_data['global_post_id']}\n";
	$output .= "WP_Query associated count: {$diagnostic_data['wp_query_count']}\n";
	$output .= "DB string match count: " . count( $db_associated ) . "\n";
	$output .= "DB int match count: " . count( $db_associated_int ) . "\n";
	$output .= "Total posts with _esop_associated_advisor: " . count( $all_associated ) . "\n";
	if ( ! empty( $all_associated ) ) {
		$output .= "Sample associations:\n";
		foreach ( array_slice( $all_associated, 0, 10 ) as $assoc ) {
			$output .= "  - Post {$assoc->post_id} ({$assoc->post_title}): advisor_id={$assoc->meta_value}\n";
		}
	}
	$output .= "-->\n";

	return $output;
}

/**
 * Field diagnostic shortcode - shows all advisor field values
 * Usage: [esop_advisor_fields_diagnostic] - outputs visible table of all fields
 * Only visible to admin users for security
 */
add_shortcode( 'esop_advisor_fields_diagnostic', 'esop_advisor_fields_diagnostic_shortcode' );

function esop_advisor_fields_diagnostic_shortcode( $atts ) {
	// Only show to admin users
	if ( ! current_user_can( 'manage_options' ) ) {
		return '<!-- Fields diagnostic only visible to admins -->';
	}

	$advisor_id = esop_advisor_get_current_advisor_id();
	if ( ! $advisor_id ) {
		return '<p style="color:red;">Could not determine advisor ID</p>';
	}

	// All the fields we want to check
	$fields = array(
		'company'      => 'Company',
		'title'        => 'Title',
		'address'      => 'Address',
		'city'         => 'City',
		'state'        => 'State',
		'zip'          => 'ZIP',
		'phone'        => 'Phone',
		'cell'         => 'Cell',
		'fax'          => 'Fax',
		'email'        => 'Email',
		'website'      => 'Website',
		'about_url'    => 'About URL',
		'services_url' => 'Services URL',
		'linkedin'     => 'LinkedIn',
	);

	$output = '<div style="background:#f5f5f5; padding:20px; margin:20px 0; border:1px solid #ccc; font-family:monospace;">';
	$output .= '<h3 style="margin-top:0;">Advisor Fields Diagnostic (ID: ' . esc_html( $advisor_id ) . ')</h3>';
	$output .= '<table style="width:100%; border-collapse:collapse;">';
	$output .= '<tr><th style="text-align:left; padding:5px; border-bottom:1px solid #ccc;">Field</th>';
	$output .= '<th style="text-align:left; padding:5px; border-bottom:1px solid #ccc;">Meta Key</th>';
	$output .= '<th style="text-align:left; padding:5px; border-bottom:1px solid #ccc;">Value</th>';
	$output .= '<th style="text-align:left; padding:5px; border-bottom:1px solid #ccc;">Status</th></tr>';

	foreach ( $fields as $field => $label ) {
		$meta_key = '_esop_advisor_' . $field;
		$value = get_post_meta( $advisor_id, $meta_key, true );
		$status = ! empty( $value ) ? '<span style="color:green;">✓ Has value</span>' : '<span style="color:red;">✗ Empty</span>';
		$display_value = ! empty( $value ) ? esc_html( substr( $value, 0, 50 ) ) . ( strlen( $value ) > 50 ? '...' : '' ) : '<em>(empty)</em>';

		$output .= '<tr>';
		$output .= '<td style="padding:5px; border-bottom:1px solid #eee;">' . esc_html( $label ) . '</td>';
		$output .= '<td style="padding:5px; border-bottom:1px solid #eee; font-size:11px;">' . esc_html( $meta_key ) . '</td>';
		$output .= '<td style="padding:5px; border-bottom:1px solid #eee;">' . $display_value . '</td>';
		$output .= '<td style="padding:5px; border-bottom:1px solid #eee;">' . $status . '</td>';
		$output .= '</tr>';
	}

	$output .= '</table>';
	$output .= '</div>';

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
add_filter( 'et_pb_module_shortcode_attributes', 'esop_advisor_process_module_attributes', 10, 3 );

function esop_advisor_process_module_attributes( $props, $attrs, $render_slug ) {
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
 * Unified shortcode processor for Divi content filters
 * Decodes HTML entities and processes ESOP shortcodes
 *
 * @param string $content Content to process
 * @return string Processed content
 */
function esop_advisor_process_divi_shortcodes( $content ) {
	if ( ! is_string( $content ) ) {
		return $content;
	}

	// Decode HTML entities for square brackets
	$content = esop_advisor_decode_shortcode_entities( $content );

	// Process ESOP shortcodes if present
	if ( strpos( $content, '[esop_' ) !== false ) {
		$content = do_shortcode( $content );
	}

	return $content;
}

// Register unified shortcode processor on all Divi content filters
add_filter( 'the_content', 'esop_advisor_process_divi_shortcodes', 5 );
add_filter( 'widget_text', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_text_content', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_blurb_content', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_code_content', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_builder_post_content_capability', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_theme_builder_template_after_body', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_theme_builder_body_layout', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_row_inner_content', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_section_inner_content', 'esop_advisor_process_divi_shortcodes', 10 );
add_filter( 'et_pb_column_inner_content', 'esop_advisor_process_divi_shortcodes', 10 );

/**
 * Process shortcodes in Divi module output (accepts additional parameters)
 */
add_filter( 'et_pb_shortcode_output', 'esop_advisor_process_module_shortcodes', 10, 3 );
add_filter( 'et_module_shortcode_output', 'esop_advisor_process_module_shortcodes', 999, 3 );

function esop_advisor_process_module_shortcodes( $output, $render_slug = '', $module = null ) {
	return esop_advisor_process_divi_shortcodes( $output );
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

	// CRITICAL: Skip if post__in is already set
	// This means specific posts have been determined (e.g., by our shortcode)
	// Adding author constraint would filter out associated posts from other authors
	$post__in = $query->get( 'post__in' );
	if ( ! empty( $post__in ) ) {
		return;
	}

	// CRITICAL: Skip if querying by meta_query for associated advisor
	// This allows finding posts associated with an advisor regardless of author
	$meta_query = $query->get( 'meta_query' );
	if ( ! empty( $meta_query ) ) {
		foreach ( $meta_query as $meta ) {
			if ( is_array( $meta ) && isset( $meta['key'] ) && $meta['key'] === '_esop_associated_advisor' ) {
				return;
			}
		}
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
	// Add class for advisor_specialty taxonomy archive pages
	if ( is_tax( 'advisor_specialty' ) ) {
		$classes[] = 'advisor-specialty-archive';

		// Add slug-specific class for individual specialty pages
		$term = get_queried_object();
		if ( $term && isset( $term->slug ) ) {
			$classes[] = 'advisor-specialty-' . sanitize_html_class( $term->slug );
		}

		return $classes;
	}

	// Add classes for single advisor pages
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
// SUPPRESS ARCHIVE TITLE ON SPECIALTY PAGES
// ============================================================================

/**
 * Remove the archive title H1 output on advisor_specialty taxonomy pages
 *
 * The title will be placed via [advisor_specialty_title] shortcode in Divi hero section.
 * This filter returns empty string to prevent WordPress/Divi from outputting the
 * default archive title, so it won't appear in the page source at all.
 *
 * Filters applied:
 * - get_the_archive_title: Main WordPress archive title function
 * - get_the_archive_description: Archive description (also suppressed)
 *
 * CSS fallback is also in place in case any theme/plugin bypasses these filters.
 */
add_filter( 'get_the_archive_title', 'esop_suppress_specialty_archive_title', 20 );
add_filter( 'single_term_title', 'esop_suppress_specialty_term_title', 20 );

function esop_suppress_specialty_archive_title( $title ) {
	// Only suppress on advisor_specialty taxonomy archive pages
	if ( is_tax( 'advisor_specialty' ) ) {
		return ''; // Return empty string - no H1 output
	}
	return $title;
}

function esop_suppress_specialty_term_title( $title ) {
	// Suppress term title on specialty archive pages
	// This catches cases where themes use single_term_title() instead of get_the_archive_title()
	if ( is_tax( 'advisor_specialty' ) ) {
		return ''; // Return empty string - no title output
	}
	return $title;
}

/**
 * Optionally suppress the archive description as well
 * (Uncomment if needed - currently returns normal description)
 */
// add_filter( 'get_the_archive_description', 'esop_suppress_specialty_archive_description', 20 );
// function esop_suppress_specialty_archive_description( $description ) {
// 	if ( is_tax( 'advisor_specialty' ) ) {
// 		return '';
// 	}
// 	return $description;
// }

// ============================================================================
// SPECIALTY ARCHIVE HERO SECTION
// ============================================================================

/**
 * Inject hero section at the top of specialty archive pages
 *
 * Renders a full-width hero with a background image and dark overlay,
 * matching the site-wide Divi fullwidth header hero style used on pages
 * like /for-esops/. The hero sits behind the fixed Divi header so the
 * transparent header with white logo and white nav text is visible.
 *
 * Hooks into Divi's et_before_main_content action which fires right after
 * the header and before the Theme Builder body template renders.
 */
add_action( 'et_before_main_content', 'esop_specialty_archive_hero' );

function esop_specialty_archive_hero() {
	if ( ! is_tax( 'advisor_specialty' ) ) {
		return;
	}

	$term = get_queried_object();
	if ( ! $term || ! isset( $term->name ) ) {
		return;
	}

	$hero_title = esc_html( $term->name );
	?>
	<div class="esop-specialty-hero">
		<div class="esop-specialty-hero-inner">
			<h1 class="esop-specialty-hero-title"><?php echo $hero_title; ?></h1>
		</div>
	</div>
	<?php
}

/**
 * JavaScript: handle sticky header state on specialty archive pages.
 *
 * IMPORTANT: The initial-load CSS (transparent bg, white nav text) must NOT
 * be touched. This JS ONLY applies styles when the page is scrolled down.
 *
 * Uses window.scrollY to detect scroll state — NOT CSS classes, because
 * et_pb_section--fixed and pa-fixed-header are always present (config classes).
 */
add_action( 'wp_footer', 'esop_specialty_sticky_header_js', 100 );

function esop_specialty_sticky_header_js() {
	if ( ! is_tax( 'advisor_specialty' ) ) {
		return;
	}
	?>
	<script>
	(function(){
		var hdr = document.querySelector('.et_pb_section_0_tb_header');
		if (!hdr) return;

		var scrollThreshold = 10;

		function handleScroll() {
			if (window.scrollY > scrollThreshold) {
				// Scrolled: white background, dark nav text
				hdr.style.setProperty('background-color', '#ffffff', 'important');
				hdr.querySelectorAll('.et_pb_menu__menu a, .et-menu-nav > ul > li > a').forEach(function(a){
					a.style.setProperty('color', '#2d3a4a', 'important');
				});
			} else {
				// At top: remove inline styles, let CSS handle it (transparent bg, white text)
				hdr.style.removeProperty('background-color');
				hdr.querySelectorAll('.et_pb_menu__menu a, .et-menu-nav > ul > li > a').forEach(function(a){
					a.style.removeProperty('color');
				});
			}
		}

		window.addEventListener('scroll', handleScroll, { passive: true });
		// Do NOT call handleScroll() here — initial state is handled by CSS
	})();
	</script>
	<?php
}

// ============================================================================
// JSON-LD SCHEMA FOR ADVISOR PAGES
// ============================================================================

/**
 * Output JSON-LD structured data for single advisor pages
 *
 * Schema types used:
 * - Person: The advisor themselves
 * - Organization: Their company (via worksFor)
 * - PostalAddress: Their business address
 * - ContactPoint: Phone/email contact info
 */
add_action( 'wp_head', 'esop_advisor_output_schema', 5 );

function esop_advisor_output_schema() {
	// Only output on single advisor pages
	if ( ! is_singular( 'esop_advisor' ) ) {
		return;
	}

	$post_id = get_the_ID();

	// Ensure we have a valid post ID
	if ( ! $post_id || ! get_post( $post_id ) ) {
		return;
	}

	// Get advisor fields
	$name = get_the_title( $post_id );

	// Ensure we have a name
	if ( empty( $name ) ) {
		return;
	}

	$name = html_entity_decode( $name, ENT_QUOTES, 'UTF-8' );
	$company     = esop_get_advisor_field( $post_id, 'company' );
	$job_title   = esop_get_advisor_field( $post_id, 'title' );
	$address     = esop_get_advisor_field( $post_id, 'address' );
	$city        = esop_get_advisor_field( $post_id, 'city' );
	$state       = esop_get_advisor_field( $post_id, 'state' );
	$zip         = esop_get_advisor_field( $post_id, 'zip' );
	$phone       = esop_get_advisor_field( $post_id, 'phone' );
	$cell        = esop_get_advisor_field( $post_id, 'cell' );
	$email       = esop_get_advisor_field( $post_id, 'email' );
	$website     = esop_get_advisor_field( $post_id, 'website' );
	$linkedin    = esop_get_advisor_field( $post_id, 'linkedin' );
	$bio         = esop_get_advisor_field( $post_id, 'bio' );
	$expertise   = esop_get_advisor_field( $post_id, 'expertise' );

	// Build the schema array
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Person',
		'name'     => $name,
		'url'      => get_permalink( $post_id ),
	);

	// Job title
	if ( ! empty( $job_title ) ) {
		$schema['jobTitle'] = html_entity_decode( $job_title, ENT_QUOTES, 'UTF-8' );
	}

	// Description from bio (strip HTML, limit length, decode entities)
	if ( ! empty( $bio ) ) {
		$description = wp_strip_all_tags( $bio );
		$description = html_entity_decode( $description, ENT_QUOTES, 'UTF-8' );
		$description = wp_trim_words( $description, 50, '...' );
		$schema['description'] = $description;
	}

	// Featured image
	if ( has_post_thumbnail( $post_id ) ) {
		$image_id  = get_post_thumbnail_id( $post_id );
		$image_url = wp_get_attachment_image_url( $image_id, 'full' );
		if ( $image_url ) {
			$schema['image'] = array(
				'@type' => 'ImageObject',
				'url'   => $image_url,
			);
			$image_meta = wp_get_attachment_metadata( $image_id );
			if ( is_array( $image_meta ) && ! empty( $image_meta['width'] ) && ! empty( $image_meta['height'] ) ) {
				$schema['image']['width']  = (int) $image_meta['width'];
				$schema['image']['height'] = (int) $image_meta['height'];
			}
		}
	}

	// Works for (Organization)
	if ( ! empty( $company ) ) {
		$org_schema = array(
			'@type' => 'Organization',
			'name'  => html_entity_decode( $company, ENT_QUOTES, 'UTF-8' ),
		);

		// Add company website if available
		if ( ! empty( $website ) ) {
			$org_schema['url'] = $website;
		}

		$schema['worksFor'] = $org_schema;
	}

	// Postal address
	if ( ! empty( $address ) || ! empty( $city ) || ! empty( $state ) || ! empty( $zip ) ) {
		$address_schema = array(
			'@type' => 'PostalAddress',
		);

		if ( ! empty( $address ) ) {
			$address_schema['streetAddress'] = $address;
		}
		if ( ! empty( $city ) ) {
			$address_schema['addressLocality'] = $city;
		}
		if ( ! empty( $state ) ) {
			$address_schema['addressRegion'] = $state;
		}
		if ( ! empty( $zip ) ) {
			$address_schema['postalCode'] = $zip;
		}
		$address_schema['addressCountry'] = 'US';

		$schema['address'] = $address_schema;
	}

	// Primary telephone (prefer office phone, fallback to cell)
	$primary_phone = ! empty( $phone ) ? $phone : $cell;
	if ( ! empty( $primary_phone ) ) {
		// Clean phone for tel: format
		$clean_phone = preg_replace( '/[^0-9+]/', '', $primary_phone );
		if ( strlen( $clean_phone ) === 10 ) {
			$clean_phone = '+1' . $clean_phone;
		}
		$schema['telephone'] = $clean_phone;
	}

	// Email
	if ( ! empty( $email ) && is_email( $email ) ) {
		$schema['email'] = sanitize_email( $email );
	}

	// sameAs for social profiles and external URLs
	$same_as = array();
	if ( ! empty( $linkedin ) ) {
		$same_as[] = $linkedin;
	}
	if ( ! empty( $website ) ) {
		$same_as[] = $website;
	}
	if ( ! empty( $same_as ) ) {
		$schema['sameAs'] = $same_as;
	}

	// Knowledge/expertise areas
	$knows_about = array( 'ESOP', 'Employee Stock Ownership Plans' );
	if ( ! empty( $expertise ) ) {
		$expertise_text = wp_strip_all_tags( $expertise );
		$expertise_text = html_entity_decode( $expertise_text, ENT_QUOTES, 'UTF-8' );
		$expertise_text = trim( $expertise_text );
		// Limit expertise text to 200 characters to keep schema clean
		if ( strlen( $expertise_text ) > 200 ) {
			$expertise_text = substr( $expertise_text, 0, 197 ) . '...';
		}
		if ( ! empty( $expertise_text ) ) {
			$knows_about[] = $expertise_text;
		}
	} else {
		$knows_about[] = 'Employee Benefits';
	}
	$schema['knowsAbout'] = $knows_about;

	// Output the JSON-LD script
	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	echo "\n" . '</script>' . "\n";
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

// ============================================================================
// SPECIALTY PAGE TITLE SHORTCODE
// ============================================================================

/**
 * Shortcode: Advisor Specialty Page Title
 *
 * Displays the current specialty taxonomy term name for use in Divi Theme Builder.
 * Only outputs content on advisor_specialty taxonomy archive pages.
 *
 * Usage: [advisor_specialty_title]
 * With options: [advisor_specialty_title suffix=" Experts" tag="h1" class="hero-title"]
 *
 * Attributes:
 * - suffix: Text to append after the specialty name (default: ", Consultants & Advisors")
 * - tag: HTML tag to wrap the title in (h1, h2, h3, h4, h5, h6, p, span, div)
 * - class: CSS class(es) to add to the wrapper tag
 * - id: HTML ID to add to the wrapper tag
 *
 * Examples:
 * [advisor_specialty_title]                                    -> ESOP Trustees, Consultants & Advisors
 * [advisor_specialty_title suffix=""]                          -> ESOP Trustees
 * [advisor_specialty_title suffix=" Experts"]                  -> ESOP Trustees Experts
 * [advisor_specialty_title tag="h1"]                           -> <h1>ESOP Trustees, Consultants & Advisors</h1>
 * [advisor_specialty_title tag="h1" class="hero-title"]        -> <h1 class="hero-title">...</h1>
 *
 * @param array $atts Shortcode attributes
 * @return string The specialty title or empty string if not on specialty page
 */
add_shortcode( 'advisor_specialty_title', 'esop_advisor_specialty_title_shortcode' );

function esop_advisor_specialty_title_shortcode( $atts ) {
	// Only output on advisor_specialty taxonomy archives
	if ( ! is_tax( 'advisor_specialty' ) ) {
		return '';
	}

	// Get the current term
	$term = get_queried_object();

	if ( ! $term || ! isset( $term->name ) ) {
		return '';
	}

	// Parse attributes with defaults
	$atts = shortcode_atts( array(
		'suffix' => ', Consultants & Advisors',
		'tag'    => '',      // Optional: wrap in HTML tag like 'h1', 'h2', 'span'
		'class'  => '',      // Optional: add custom CSS class
		'id'     => '',      // Optional: add custom ID
	), $atts, 'advisor_specialty_title' );

	// Build the title text
	$title_text = esc_html( $term->name ) . esc_html( $atts['suffix'] );

	// If no tag specified, return plain text
	if ( empty( $atts['tag'] ) ) {
		return $title_text;
	}

	// Validate and wrap in specified HTML tag
	$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' );
	$tag = strtolower( sanitize_key( $atts['tag'] ) );

	if ( ! in_array( $tag, $allowed_tags, true ) ) {
		return $title_text; // Invalid tag, return plain text
	}

	// Build attributes
	$attrs = '';
	if ( ! empty( $atts['class'] ) ) {
		$attrs .= ' class="' . esc_attr( $atts['class'] ) . '"';
	}
	if ( ! empty( $atts['id'] ) ) {
		$attrs .= ' id="' . esc_attr( $atts['id'] ) . '"';
	}

	return '<' . $tag . $attrs . '>' . $title_text . '</' . $tag . '>';
}

/**
 * Shortcode: Advisor Specialty Listing
 *
 * Displays advisors filtered by specialty taxonomy term.
 * On taxonomy archive pages, automatically uses the current term.
 *
 * Usage: [esop_specialty_advisors]
 * With options: [esop_specialty_advisors specialty="corporate-ma" columns="4"]
 *
 * Attributes:
 * - specialty: Term slug to filter by (optional - auto-detected on archive pages)
 * - columns: Number of columns (default: 4)
 * - orderby: Order by field (default: title)
 * - order: Sort order ASC or DESC (default: ASC)
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
add_shortcode( 'esop_specialty_advisors', 'esop_specialty_advisors_shortcode' );

function esop_specialty_advisors_shortcode( $atts ) {
	// Trigger the shared directory CSS output in wp_footer
	global $esop_advisor_directory_used;
	$esop_advisor_directory_used = true;

	$atts = shortcode_atts( array(
		'specialty' => '',
		'orderby'   => 'title',
		'order'     => 'ASC',
	), $atts, 'esop_specialty_advisors' );

	// Determine specialty term
	$term = null;
	if ( ! empty( $atts['specialty'] ) ) {
		// Explicit specialty provided
		$term = get_term_by( 'slug', sanitize_title( $atts['specialty'] ), 'advisor_specialty' );
	} elseif ( is_tax( 'advisor_specialty' ) ) {
		// Auto-detect on archive page
		$term = get_queried_object();
	}

	// Build query args — exclude honorary advisors
	$query_args = array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => sanitize_key( $atts['orderby'] ),
		'order'          => strtoupper( $atts['order'] ) === 'DESC' ? 'DESC' : 'ASC',
		'meta_query'     => array(
			array(
				'relation' => 'OR',
				array(
					'key'     => '_esop_advisor_honorary',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_esop_advisor_honorary',
					'value'   => '1',
					'compare' => '!=',
				),
			),
		),
	);

	// Add taxonomy filter if we have a term
	if ( $term && ! is_wp_error( $term ) ) {
		$query_args['tax_query'] = array(
			array(
				'taxonomy' => 'advisor_specialty',
				'field'    => 'term_id',
				'terms'    => $term->term_id,
			),
		);
	}

	$advisors_query = new WP_Query( $query_args );

	// Default placeholder SVG
	$placeholder_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

	ob_start();

	?>
	<div class="esop-advisor-directory">
		<?php if ( $advisors_query->have_posts() ) : ?>
			<div class="advisor-grid">
				<?php while ( $advisors_query->have_posts() ) : $advisors_query->the_post(); ?>
					<?php
					$advisor_id   = get_the_ID();
					$advisor_name = get_the_title();
					$permalink    = get_permalink();
					$has_image    = has_post_thumbnail( $advisor_id );

					// Get advisor meta
					$title   = get_post_meta( $advisor_id, '_esop_advisor_title', true );
					$company = get_post_meta( $advisor_id, '_esop_advisor_company', true );
					$city    = get_post_meta( $advisor_id, '_esop_advisor_city', true );
					$state   = get_post_meta( $advisor_id, '_esop_advisor_state', true );
					$location = trim( $city . ( $city && $state ? ', ' : '' ) . $state );

					// Check if retired
					$is_retired = has_term( 'retired', 'advisor_specialty', $advisor_id );

					// Get image URL for background
					$image_url = $has_image ? get_the_post_thumbnail_url( $advisor_id, 'medium' ) : '';
					?>
					<div class="advisor-card">
						<div class="advisor-image<?php echo ! $has_image ? ' advisor-image--placeholder' : ''; ?>"<?php echo $has_image ? ' style="background-image: url(' . esc_url( $image_url ) . ');"' : ''; ?>>
							<a href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $advisor_name ); ?>">
								<?php if ( ! $has_image ) : ?>
									<?php echo $placeholder_svg; ?>
								<?php endif; ?>
							</a>
						</div>

						<div class="advisor-content">
							<h4 class="advisor-name">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $advisor_name ); ?></a>
							</h4>

							<?php if ( ! empty( $title ) ) : ?>
								<div class="advisor-title"><?php echo esc_html( $title ); ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $company ) ) : ?>
								<p class="advisor-company"><?php echo esc_html( $company ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $location ) ) : ?>
								<p class="advisor-location"><?php echo esc_html( $location ); ?></p>
							<?php endif; ?>

							<?php if ( $is_retired ) : ?>
								<div class="advisor-retired">Retired</div>
							<?php endif; ?>
						</div>

						<a href="<?php echo esc_url( $permalink ); ?>" class="advisor-view-btn">View Advisor</a>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<div class="esop-no-advisors-cta" style="text-align: center; padding: 80px 20px;">
				<p style="font-size: 22px; color: #333; margin: 0 0 30px 0; font-weight: 500;">There are no Advisors listed, let&rsquo;s get you placed here.</p>
				<a href="/contact-us/" class="esop-contact-button" style="display: inline-flex !important; width: auto !important; padding: 16px 40px !important; font-size: 0.85rem !important;">GET LISTED NOW</a>
			</div>
		<?php endif; ?>
	</div>
	<?php

	return ob_get_clean();
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
				<li><code>esop_advisor_expertise</code> - Set to '1' if advisor has expertise content</li>
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

// ============================================================================
// ADVISOR DIRECTORY SHORTCODE
// ============================================================================

/**
 * Advisor Directory Grid Shortcode
 *
 * Displays published advisors that have a featured image in a responsive card-based grid layout.
 * Advisors without a featured image are excluded (typically non-advisor/author entries).
 * Cards show: Image, Name (linked), Title, Company, and a "View Advisor" button.
 *
 * Usage: [esop_advisor_directory]
 *
 * Layout: 6 columns (desktop) → 4 columns (large tablet) → 3 columns (tablet) → 2 columns (mobile)
 *
 * For use on a Divi page with Text or Code module. The page will use
 * the global Divi header/footer automatically.
 */
add_shortcode( 'esop_advisor_directory', 'esop_advisor_directory_shortcode' );

/**
 * Output CSS for advisor directory in wp_footer (after Divi CSS loads)
 */
add_action( 'wp_footer', 'esop_advisor_directory_css', 5 );

function esop_advisor_directory_css() {
	// Only output if shortcode was used on this page
	global $esop_advisor_directory_used;
	if ( empty( $esop_advisor_directory_used ) ) {
		return;
	}
	?>
	<style id="esop-advisor-directory-css">
	/* =====================================================
	   ADVISOR DIRECTORY SHORTCODE STYLES
	   Scoped to .esop-advisor-directory wrapper class
	   Uses !important to override Divi styles
	   ===================================================== */

	/* Main container */
	.esop-advisor-directory {
		width: 100% !important;
		max-width: 100% !important;
		box-sizing: border-box !important;
	}

	/* 6-Column Grid Layout for desktop */
	.esop-advisor-directory .advisor-grid {
		display: grid !important;
		grid-template-columns: repeat(6, 1fr) !important;
		gap: 20px !important;
		width: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	/* Responsive: 4 columns on large tablet (max-width: 1200px) */
	@media (max-width: 1200px) {
		.esop-advisor-directory .advisor-grid {
			grid-template-columns: repeat(4, 1fr) !important;
		}
	}

	/* Responsive: 3 columns on tablet (max-width: 980px) */
	@media (max-width: 980px) {
		.esop-advisor-directory .advisor-grid {
			grid-template-columns: repeat(3, 1fr) !important;
		}
	}

	/* Responsive: 2 columns on mobile (max-width: 600px) */
	@media (max-width: 600px) {
		.esop-advisor-directory .advisor-grid {
			grid-template-columns: repeat(2, 1fr) !important;
			gap: 15px !important;
		}
	}

	/* Responsive: 1 column on very small screens (max-width: 400px) */
	@media (max-width: 400px) {
		.esop-advisor-directory .advisor-grid {
			grid-template-columns: 1fr !important;
		}
	}

	/* Advisor Card Container */
	.esop-advisor-directory .advisor-card {
		background: #fff !important;
		border-radius: 8px !important;
		overflow: hidden !important;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
		transition: transform 0.2s ease, box-shadow 0.2s ease !important;
		display: flex !important;
		flex-direction: column !important;
		height: 100% !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	.esop-advisor-directory .advisor-card:hover {
		transform: translateY(-3px) !important;
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12) !important;
	}

	/* Square images using background-image */
	.esop-advisor-directory .advisor-image {
		width: 100% !important;
		padding-top: 100% !important; /* 1:1 aspect ratio */
		position: relative !important;
		overflow: hidden !important;
		background-color: #f0f4f8 !important;
		background-size: cover !important;
		background-position: center top !important;
		background-repeat: no-repeat !important;
		flex-shrink: 0 !important;
	}

	.esop-advisor-directory .advisor-image a {
		position: absolute !important;
		top: 0 !important;
		left: 0 !important;
		right: 0 !important;
		bottom: 0 !important;
		display: block !important;
		width: 100% !important;
		height: 100% !important;
	}

	/* Placeholder for advisors without photos */
	.esop-advisor-directory .advisor-image--placeholder {
		background: linear-gradient(135deg, #e8f4fc 0%, #d0e8f7 100%) !important;
	}

	.esop-advisor-directory .advisor-image--placeholder a {
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
	}

	.esop-advisor-directory .advisor-image--placeholder svg {
		width: 40% !important;
		height: 40% !important;
		color: #94b8d4 !important;
		opacity: 0.7 !important;
	}

	/* Card Content Area - compact padding */
	.esop-advisor-directory .advisor-content {
		padding: 12px 10px 15px !important;
		text-align: center !important;
		flex-grow: 1 !important;
		display: flex !important;
		flex-direction: column !important;
	}

	/* Advisor Name */
	.esop-advisor-directory .advisor-name {
		font-size: 14px !important;
		font-weight: 600 !important;
		margin: 0 0 4px 0 !important;
		color: #1a1a1a !important;
		line-height: 1em !important;
		padding: 0 !important;
	}

	.esop-advisor-directory .advisor-name a {
		color: #1a1a1a !important;
		text-decoration: none !important;
		transition: color 0.2s ease !important;
	}

	.esop-advisor-directory .advisor-name a:hover {
		color: #0C71C3 !important;
	}

	/* Job Title */
	.esop-advisor-directory .advisor-title {
		font-size: 11px !important;
		font-weight: 600 !important;
		text-transform: uppercase !important;
		color: #0C71C3 !important;
		line-height: 1.25em !important;
		margin: 0 0 2px 0 !important;
		padding: 0 !important;
		letter-spacing: 0.3px !important;
	}

	/* Company Name */
	.esop-advisor-directory .advisor-company {
		font-size: 12px !important;
		color: #666 !important;
		line-height: 1em !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	/* Location (city, state) — specialty pages only */
	.esop-advisor-directory .advisor-location {
		font-size: 12px !important;
		color: #666 !important;
		line-height: 1em !important;
		margin: 3px 0 0 0 !important;
		padding: 0 !important;
	}

	/* Retired indicator */
	.esop-advisor-directory .advisor-retired {
		font-size: 10px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		color: #f1602b !important;
		margin-top: 4px !important;
		letter-spacing: 0.3px !important;
	}

	/* Badges wrapper — pushes honorary+retired labels together to the bottom */
	.esop-advisor-directory .advisor-badges {
		margin-top: auto !important;
		padding: 0 !important;
		line-height: 1 !important;
	}
	.esop-advisor-directory .advisor-badges div {
		margin: 0 !important;
		padding: 0 !important;
	}

	/* Honorary indicator */
	.esop-advisor-directory .advisor-honorary {
		font-size: 10px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		color: #0073aa !important;
		margin: 0 !important;
		padding: 20px 0 4px 0 !important;
		letter-spacing: 0.3px !important;
	}

	/* Retired inside badges wrapper — tight to honorary */
	.esop-advisor-directory .advisor-badges .advisor-retired {
		margin: 0 !important;
		padding: 0 !important;
	}

	/* No Results Message */
	.esop-advisor-directory .no-advisors {
		text-align: center !important;
		padding: 40px 20px !important;
		color: #666 !important;
	}

	.esop-advisor-directory .no-advisors p {
		margin: 0 !important;
		font-size: 16px !important;
	}

	/* View Advisor Button */
	.esop-advisor-directory .advisor-view-btn {
		display: block !important;
		margin: 10px !important;
		padding: 7px 12px !important;
		background-color: #f1602b !important;
		color: #fff !important;
		font-size: 11px !important;
		font-weight: 700 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
		text-decoration: none !important;
		text-align: center !important;
		border-radius: 3px !important;
		transition: background-color 0.2s ease, transform 0.2s ease !important;
		line-height: 1em !important;
	}

	.esop-advisor-directory .advisor-view-btn:hover {
		background-color: #d44e1f !important;
		color: #fff !important;
		transform: translateY(-1px) !important;
		text-decoration: none !important;
	}

	/* Sort Controls */
	.esop-advisor-directory .advisor-sort-controls {
		display: flex !important;
		align-items: center !important;
		gap: 12px !important;
		margin-bottom: 25px !important;
		padding: 15px 20px !important;
		background: #f8f9fa !important;
		border-radius: 8px !important;
		flex-wrap: wrap !important;
	}

	.esop-advisor-directory .sort-label {
		font-size: 14px !important;
		font-weight: 600 !important;
		color: #333 !important;
		margin-right: 5px !important;
	}

	.esop-advisor-directory .sort-btn {
		padding: 8px 18px !important;
		background: #fff !important;
		color: #555 !important;
		border: 2px solid #ddd !important;
		border-radius: 5px !important;
		font-size: 13px !important;
		font-weight: 600 !important;
		cursor: pointer !important;
		transition: all 0.2s ease !important;
		outline: none !important;
	}

	.esop-advisor-directory .sort-btn:hover {
		background: #f0f4f8 !important;
		border-color: #0C71C3 !important;
		color: #0C71C3 !important;
	}

	.esop-advisor-directory .sort-btn.active {
		background: #0C71C3 !important;
		color: #fff !important;
		border-color: #0C71C3 !important;
	}

	/* Responsive: Stack sort buttons on mobile */
	@media (max-width: 600px) {
		.esop-advisor-directory .advisor-sort-controls {
			flex-direction: column !important;
			align-items: flex-start !important;
			gap: 10px !important;
		}

		.esop-advisor-directory .sort-btn {
			width: 100% !important;
		}
	}
	</style>
	<?php
}

add_action( 'wp_footer', 'esop_advisor_directory_js', 10 );

function esop_advisor_directory_js() {
	global $esop_advisor_directory_used;
	if ( empty( $esop_advisor_directory_used ) ) {
		return;
	}
	?>
	<script id="esop-advisor-directory-js">
	(function() {
		document.addEventListener('DOMContentLoaded', function() {
			const sortButtons = document.querySelectorAll('.esop-advisor-directory .sort-btn');
			const grid = document.querySelector('.esop-advisor-directory .advisor-grid');

			if (!sortButtons.length || !grid) return;

			sortButtons.forEach(function(btn) {
				btn.addEventListener('click', function() {
					const sortBy = this.getAttribute('data-sort');

					// Update active state
					sortButtons.forEach(function(b) { b.classList.remove('active'); });
					this.classList.add('active');

					// Get all advisor cards
					const cards = Array.from(grid.querySelectorAll('.advisor-card'));

					// Sort cards
					cards.sort(function(a, b) {
						let aVal = a.getAttribute('data-' + sortBy) || '';
						let bVal = b.getAttribute('data-' + sortBy) || '';

						// Handle empty values - put them at the end
						if (!aVal && !bVal) return 0;
						if (!aVal) return 1;
						if (!bVal) return -1;

						return aVal.localeCompare(bVal);
					});

					// Clear grid and re-append cards in sorted order
					grid.innerHTML = '';
					cards.forEach(function(card) {
						grid.appendChild(card);
					});
				});
			});
		});
	})();
	</script>
	<?php
}

function esop_advisor_directory_shortcode( $atts ) {
	// Mark that shortcode is used so CSS outputs in footer
	global $esop_advisor_directory_used;
	$esop_advisor_directory_used = true;

	// Parse shortcode attributes (none currently, but extensible)
	$atts = shortcode_atts( array(), $atts, 'esop_advisor_directory' );

	// Query published advisors that have a featured image set.
	// Advisors without photos are typically non-advisor entries (authors, etc.)
	// and are excluded via the _thumbnail_id meta key check.
	// Honorary advisors are excluded (they appear only on the Honorary Advisors page).
	$advisors_query = new WP_Query( array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => '_esop_advisor_honorary',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_esop_advisor_honorary',
					'value'   => '1',
					'compare' => '!=',
				),
			),
		),
	) );

	// Default placeholder SVG for advisors without photos
	$placeholder_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

	ob_start();

	// Output the "Picking an ESOP Advisor" block here so it appears directly
	// under the Hero without requiring a separate Divi module placement.
	echo do_shortcode( '[esop_advisor_picking]' );
	?>
	<div class="esop-advisor-directory">
		<?php if ( $advisors_query->have_posts() ) : ?>
			<!-- Sort Controls -->
			<div class="advisor-sort-controls">
				<span class="sort-label">Sort by:</span>
				<button class="sort-btn active" data-sort="firstname">First Name</button>
				<button class="sort-btn" data-sort="lastname">Last Name</button>
				<button class="sort-btn" data-sort="company">Company</button>
			</div>

			<div class="advisor-grid">
				<?php while ( $advisors_query->have_posts() ) : $advisors_query->the_post(); ?>
					<?php
					$advisor_id   = get_the_ID();
					$advisor_name = get_the_title();
					$permalink    = get_permalink();
					$has_image    = has_post_thumbnail( $advisor_id );

					// Get advisor meta fields
					$title   = get_post_meta( $advisor_id, '_esop_advisor_title', true );
					$company = get_post_meta( $advisor_id, '_esop_advisor_company', true );

					// Check if retired
					$is_retired = has_term( 'retired', 'advisor_specialty', $advisor_id );

					// Parse name for sorting
					$name_parts = explode( ' ', trim( $advisor_name ) );
					$firstname  = $name_parts[0];
					$lastname   = count( $name_parts ) > 1 ? end( $name_parts ) : '';
					?>

					<div class="advisor-card"
						data-firstname="<?php echo esc_attr( strtolower( $firstname ) ); ?>"
						data-lastname="<?php echo esc_attr( strtolower( $lastname ) ); ?>"
						data-company="<?php echo esc_attr( strtolower( $company ) ); ?>">
						<?php
						// Get image URL for background
						$image_url = '';
						if ( $has_image ) {
							$image_url = get_the_post_thumbnail_url( $advisor_id, 'medium' );
						}
						?>
						<div class="advisor-image<?php echo ! $has_image ? ' advisor-image--placeholder' : ''; ?>"<?php echo $has_image ? ' style="background-image: url(' . esc_url( $image_url ) . ');"' : ''; ?>>
							<a href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $advisor_name ); ?>">
								<?php if ( ! $has_image ) : ?>
									<?php echo $placeholder_svg; ?>
								<?php endif; ?>
							</a>
						</div>

						<div class="advisor-content">
							<h4 class="advisor-name">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $advisor_name ); ?></a>
							</h4>

							<?php if ( ! empty( $title ) ) : ?>
								<div class="advisor-title"><?php echo esc_html( $title ); ?></div>
							<?php endif; ?>

							<?php if ( ! empty( $company ) ) : ?>
								<p class="advisor-company"><?php echo esc_html( $company ); ?></p>
							<?php endif; ?>

							<?php if ( $is_retired ) : ?>
								<div class="advisor-retired">Retired</div>
							<?php endif; ?>
						</div>

						<a href="<?php echo esc_url( $permalink ); ?>" class="advisor-view-btn">View Advisor</a>

					</div>
				<?php endwhile; ?>
			</div>

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<div class="no-advisors">
				<p><?php esc_html_e( 'No advisors found.', 'esop-advisor' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<?php

	return ob_get_clean();
}

// ============================================================================
// FEATURED ADVISORS SHORTCODE
// ============================================================================

/**
 * Featured Advisors Shortcode
 *
 * Displays a random selection of non-retired advisor cards in a vertical column layout.
 * Designed to be placed in a Divi column (e.g., sidebar/3rd column). On tablet, when
 * Divi stacks columns to full width, cards reflow to a 3-column grid. On mobile, single column.
 *
 * Usage: [esop_featured_advisors]
 * With options: [esop_featured_advisors count="6" title="Featured Advisors"]
 *
 * Attributes:
 * - count: Number of advisors to display (default: 6)
 * - title: Optional heading above the cards (default: "Featured Advisors")
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
add_shortcode( 'esop_featured_advisors', 'esop_featured_advisors_shortcode' );

/**
 * Output CSS for featured advisors in wp_footer
 */
add_action( 'wp_footer', 'esop_featured_advisors_css', 5 );

function esop_featured_advisors_css() {
	global $esop_featured_advisors_used;
	if ( empty( $esop_featured_advisors_used ) ) {
		return;
	}
	?>
	<style id="esop-featured-advisors-css">
	/* =====================================================
	   FEATURED ADVISORS — fully standalone styles
	   No shared classes with .esop-advisor-directory
	   ===================================================== */

	.esop-feat-list {
		display: flex;
		flex-direction: column;
		gap: 30px;
	}

	.esop-feat-card {
		background: #fff;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
		transition: transform 0.2s ease, box-shadow 0.2s ease;
		display: grid;
		grid-template-columns: 1fr 2fr;
		grid-template-rows: 1fr auto;
		min-height: 130px;
	}

	.esop-feat-card:hover {
		transform: translateY(-3px);
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
	}

	/* Image: spans full height on the left */
	.esop-feat-thumb {
		grid-column: 1;
		grid-row: 1 / -1;
		overflow: hidden;
		line-height: 0;
	}

	.esop-feat-thumb a {
		display: block;
		width: 100%;
		height: 100%;
	}

	.esop-feat-thumb img {
		width: 100%;
		height: 100%;
		object-fit: cover;
		object-position: top center;
		display: block;
	}

	/* Text block: name, title, company */
	.esop-feat-info {
		grid-column: 2;
		grid-row: 1;
		display: flex;
		flex-direction: column;
		justify-content: center;
		padding: 12px 12px 0px 12px;
	}

	h4.esop-feat-name {
		font-size: 15px !important;
		font-weight: 700 !important;
		line-height: 1.2 !important;
		margin: 0 0 1px 0 !important;
		padding: 0 !important;
		color: #333;
	}

	.esop-feat-name a {
		color: #333;
		text-decoration: none;
	}

	.esop-feat-name a:hover {
		color: #0C71C3;
	}

	.esop-feat-jobtitle {
		font-size: 12px;
		font-weight: 600;
		color: #0C71C3;
		text-transform: uppercase;
		margin: 0;
		line-height: 1.3;
	}

	.esop-feat-company {
		font-size: 12px;
		color: #666;
		margin: 0;
		line-height: 1.3;
	}

	/* Button: right column, bottom row */
	.esop-feat-btn {
		grid-column: 2;
		grid-row: 2;
		display: block;
		width: 100%;
		padding: 1px 10px;
		background-color: #f1602b;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		text-transform: uppercase;
		text-align: center;
		text-decoration: none;
		letter-spacing: 0.5px;
		border: none;
		border-radius: 0 0 8px 0;
		box-sizing: border-box;
		transition: background-color 0.2s ease;
	}

	.esop-feat-btn:hover,
	.esop-feat-btn:focus {
		background-color: #d44e1f;
		color: #fff;
		text-decoration: none;
	}

	/* Tablet (max-width: 980px) — Divi stacks columns, reflow to 3-col grid */
	@media (max-width: 980px) {
		.esop-feat-list {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 23px;
		}

		.esop-feat-info {
			padding: 5px 8px 0px 8px;
		}

		h4.esop-feat-name {
			font-size: 11px !important;
		}

		.esop-feat-jobtitle {
			font-size: 10px;
		}

		.esop-feat-company {
			font-size: 10px;
		}

		.esop-feat-btn {
			font-size: 10px;
		}
	}

	/* Small tablet (max-width: 768px) — 2 columns */
	@media (max-width: 768px) {
		.esop-feat-list {
			grid-template-columns: repeat(2, 1fr);
		}

		.esop-feat-info {
			padding: 6px 10px 0px 10px;
		}

		h4.esop-feat-name {
			font-size: 13px !important;
		}

		.esop-feat-jobtitle,
		.esop-feat-company {
			font-size: 11px;
		}

		.esop-feat-btn {
			font-size: 11px;
		}
	}

	/* Mobile (max-width: 600px) — single column, desktop font sizes */
	@media (max-width: 600px) {
		.esop-feat-list {
			display: flex;
			flex-direction: column;
			gap: 15px;
		}

		.esop-feat-info {
			padding: 12px 12px 0px 12px;
		}

		h4.esop-feat-name {
			font-size: 15px !important;
		}

		.esop-feat-jobtitle {
			font-size: 12px;
		}

		.esop-feat-company {
			font-size: 12px;
		}

		.esop-feat-btn {
			font-size: 12px;
		}
	}
	</style>
	<?php
}

function esop_featured_advisors_shortcode( $atts ) {
	// Mark that this shortcode is used so CSS outputs
	global $esop_featured_advisors_used;
	$esop_featured_advisors_used = true;

	$atts = shortcode_atts( array(
		'count' => 6,
	), $atts, 'esop_featured_advisors' );

	$count = absint( $atts['count'] );
	if ( $count < 1 ) {
		$count = 6;
	}

	// Query random, non-retired, non-honorary, published advisors
	$advisors_query = new WP_Query( array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => $count,
		'post_status'    => 'publish',
		'orderby'        => 'rand',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => '_esop_advisor_retired',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_esop_advisor_retired',
					'value'   => '1',
					'compare' => '!=',
				),
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => '_esop_advisor_honorary',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => '_esop_advisor_honorary',
					'value'   => '1',
					'compare' => '!=',
				),
			),
		),
	) );

	// Default placeholder SVG
	$placeholder_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

	ob_start();

	?>
	<div class="esop-featured-advisors">
		<?php if ( $advisors_query->have_posts() ) : ?>
			<div class="esop-feat-list">
				<?php while ( $advisors_query->have_posts() ) : $advisors_query->the_post(); ?>
					<?php
					$advisor_id   = get_the_ID();
					$advisor_name = get_the_title();
					$permalink    = get_permalink();
					$has_image    = has_post_thumbnail( $advisor_id );

					// Get advisor meta fields
					$title   = get_post_meta( $advisor_id, '_esop_advisor_title', true );
					$company = get_post_meta( $advisor_id, '_esop_advisor_company', true );

					// Get image URL
					$image_url = $has_image ? get_the_post_thumbnail_url( $advisor_id, 'medium' ) : '';
					?>
					<div class="esop-feat-card">
						<div class="esop-feat-thumb">
							<a href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php echo esc_attr( $advisor_name ); ?>">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $advisor_name ); ?>">
							</a>
						</div>
						<div class="esop-feat-info">
							<h4 class="esop-feat-name">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $advisor_name ); ?></a>
							</h4>
							<?php if ( ! empty( $title ) ) : ?>
								<p class="esop-feat-jobtitle"><?php echo esc_html( $title ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $company ) ) : ?>
								<p class="esop-feat-company"><?php echo esc_html( $company ); ?></p>
							<?php endif; ?>
						</div>
						<a href="<?php echo esc_url( $permalink ); ?>" class="esop-feat-btn">View Advisor</a>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p>No advisors found.</p>
		<?php endif; ?>
	</div>
	<?php

	return ob_get_clean();
}

// ============================================================================
// HONORARY ADVISORS SHORTCODE
// ============================================================================

/**
 * Honorary Advisors page hero section
 *
 * Injects a full-width hero with background image on the Honorary Advisors page,
 * matching the specialty archive hero style.
 */
add_action( 'et_before_main_content', 'esop_honorary_advisors_hero' );

function esop_honorary_advisors_hero() {
	if ( ! is_page( 'honorary-advisors' ) ) {
		return;
	}
	?>
	<div class="esop-honorary-hero">
		<div class="esop-honorary-hero-inner">
			<h1 class="esop-honorary-hero-title">Honorary Advisors</h1>
		</div>
	</div>
	<?php
}

/**
 * Honorary Advisors hero CSS
 */
add_action( 'wp_head', 'esop_honorary_advisors_hero_css' );

function esop_honorary_advisors_hero_css() {
	if ( ! is_page( 'honorary-advisors' ) ) {
		return;
	}

	$page_id = get_queried_object_id();
	?>
	<style id="esop-honorary-hero-css">
	/* Hero section */
	.esop-honorary-hero {
		position: relative;
		height: 300px;
		background-image: url('/wp-content/uploads/2026/01/hero-02.webp');
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		display: flex;
		align-items: flex-end;
		width: 100%;
	}
	.esop-honorary-hero-inner {
		position: relative;
		z-index: 2;
		width: 80%;
		max-width: 1800px;
		margin: 0 auto;
		padding: 0 0 30px 0;
	}
	.esop-honorary-hero-title {
		color: #ffffff !important;
		font-family: 'Roboto Slab', Georgia, "Times New Roman", serif;
		font-size: 2.5em !important;
		font-weight: 600 !important;
		line-height: 1.25em !important;
		text-align: left !important;
		text-shadow: 0.05em 0.05em 0em rgba(0,0,0,0.7);
		margin: 0 !important;
		padding: 0 !important;
	}

	/* Transparent header over hero */
	body.page-id-<?php echo $page_id; ?> .et_pb_section.et_pb_section_0_tb_header,
	body.page-id-<?php echo $page_id; ?>.et-db .et_pb_section.et_pb_section_0_tb_header {
		background-color: transparent !important;
	}
	body.page-id-<?php echo $page_id; ?> .et_pb_section_0_tb_header .et_pb_menu__menu a,
	body.page-id-<?php echo $page_id; ?> .et_pb_section_0_tb_header .et-menu-nav > ul > li > a {
		color: #FFFFFF !important;
	}
	body.page-id-<?php echo $page_id; ?> .et_pb_section_0_tb_header .et-menu-nav > ul > li > a:after,
	body.page-id-<?php echo $page_id; ?> .et_pb_section_0_tb_header .et_pb_menu__menu > nav > ul > li > a:after {
		border-top-color: #FFFFFF !important;
	}

	/* Hide the Divi template's Inner Page Hero (duplicate — replaced by custom hero).
	   Targets the fullwidth section from the body template regardless of section numbering. */
	body.page-id-<?php echo $page_id; ?> .et_pb_fullwidth_header.inner-hero,
	body.page-id-<?php echo $page_id; ?> .et_pb_fullwidth_section[class*="_tb_body"] {
		display: none !important;
	}

	/* Honorary card badge spacing — in wp_head to override Divi */
	body.page-id-<?php echo $page_id; ?> .esop-advisor-directory .advisor-badges {
		margin-top: auto !important;
	}
	body.page-id-<?php echo $page_id; ?> .esop-advisor-directory .advisor-honorary {
		padding-top: 20px !important;
		padding-bottom: 4px !important;
		margin: 0 !important;
	}
	body.page-id-<?php echo $page_id; ?> .esop-advisor-directory .advisor-badges .advisor-retired {
		margin: 0 !important;
		padding: 0 !important;
	}

	@media (max-width: 980px) {
		.esop-honorary-hero {
			height: 250px;
		}
		.esop-honorary-hero-title {
			font-size: 2em !important;
		}
		.esop-honorary-hero-inner {
			width: 90%;
		}
	}
	@media (max-width: 767px) {
		.esop-honorary-hero {
			height: 200px;
		}
		.esop-honorary-hero-title {
			font-size: 1.5em !important;
		}
		.esop-honorary-hero-inner {
			width: 96%;
			padding: 0 0 20px 0;
		}
	}
	</style>
	<?php
}

/**
 * Sticky header JS for Honorary Advisors page
 * Restores white header background on scroll (same behavior as specialty archives)
 */
add_action( 'wp_footer', 'esop_honorary_advisors_sticky_header_js', 100 );

function esop_honorary_advisors_sticky_header_js() {
	if ( ! is_page( 'honorary-advisors' ) ) {
		return;
	}
	?>
	<script>
	(function(){
		var hdr = document.querySelector('.et_pb_section_0_tb_header');
		if (!hdr) return;

		var scrollThreshold = 10;

		function handleScroll() {
			if (window.scrollY > scrollThreshold) {
				// Scrolled: white background, dark nav text
				hdr.style.setProperty('background-color', '#ffffff', 'important');
				hdr.querySelectorAll('.et_pb_menu__menu a, .et-menu-nav > ul > li > a').forEach(function(a){
					a.style.setProperty('color', '#2d3a4a', 'important');
				});
			} else {
				// At top: remove inline styles, let CSS handle it (transparent bg, white text)
				hdr.style.removeProperty('background-color');
				hdr.querySelectorAll('.et_pb_menu__menu a, .et-menu-nav > ul > li > a').forEach(function(a){
					a.style.removeProperty('color');
				});
			}
		}

		window.addEventListener('scroll', handleScroll, { passive: true });
		// Do NOT call handleScroll() here — initial state is handled by CSS
	})();
	</script>
	<?php
}

/**
 * Honorary Advisors Grid Shortcode
 *
 * Displays advisors marked as honorary in the same card-based grid layout
 * as the main advisor directory. Only shows advisors with _esop_advisor_honorary = '1'.
 *
 * Usage: [esop_honorary_advisors]
 *
 * Reuses .esop-advisor-directory CSS from the directory shortcode.
 */
add_shortcode( 'esop_honorary_advisors', 'esop_honorary_advisors_shortcode' );

function esop_honorary_advisors_shortcode( $atts ) {
	// Mark that directory CSS is needed (reuses same styles)
	global $esop_advisor_directory_used;
	$esop_advisor_directory_used = true;

	$atts = shortcode_atts( array(), $atts, 'esop_honorary_advisors' );

	// Query only honorary advisors (no thumbnail requirement — placeholder shown if missing)
	$advisors_query = new WP_Query( array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_esop_advisor_honorary',
				'value'   => '1',
				'compare' => '=',
			),
		),
	) );

	$placeholder_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>';

	// Build output via concatenation to avoid wpautop() inserting stray <p> tags
	$output = '<div class="esop-advisor-directory">';

	if ( $advisors_query->have_posts() ) {
		$output .= '<div class="advisor-grid">';

		while ( $advisors_query->have_posts() ) {
			$advisors_query->the_post();
			$advisor_id   = get_the_ID();
			$advisor_name = get_the_title();
			$permalink    = get_permalink();
			$has_image    = has_post_thumbnail( $advisor_id );
			$title        = get_post_meta( $advisor_id, '_esop_advisor_title', true );
			$company      = get_post_meta( $advisor_id, '_esop_advisor_company', true );
			$is_retired   = esop_advisor_is_retired( $advisor_id );
			$image_url    = $has_image ? get_the_post_thumbnail_url( $advisor_id, 'medium' ) : '';

			$image_class = $has_image ? 'advisor-image' : 'advisor-image advisor-image--placeholder';
			$image_style = $has_image ? ' style="background-image: url(' . esc_url( $image_url ) . ');"' : '';
			$image_inner = $has_image ? '' : $placeholder_svg;

			$output .= '<div class="advisor-card">';
			$output .= '<div class="' . $image_class . '"' . $image_style . '>';
			$output .= '<a href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( $advisor_name ) . '">' . $image_inner . '</a>';
			$output .= '</div>';
			$output .= '<div class="advisor-content">';
			$output .= '<h4 class="advisor-name"><a href="' . esc_url( $permalink ) . '">' . esc_html( $advisor_name ) . '</a></h4>';
			if ( ! empty( $title ) ) {
				$output .= '<div class="advisor-title">' . esc_html( $title ) . '</div>';
			}
			if ( ! empty( $company ) ) {
				$output .= '<p class="advisor-company">' . esc_html( $company ) . '</p>';
			}
			$output .= '<div class="advisor-badges" style="margin-top:auto !important">';
			$output .= '<div class="advisor-honorary" style="padding:20px 0 4px 0 !important">Honorary Advisor</div>';
			if ( $is_retired ) {
				$output .= '<div class="advisor-retired">Retired</div>';
			}
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class="advisor-view-wrap"><a href="' . esc_url( $permalink ) . '" class="advisor-view-btn">View Advisor</a></div>';
			$output .= '</div>';
		}

		wp_reset_postdata();
		$output .= '</div>';
	} else {
		$output .= '<div class="no-advisors"><p>' . esc_html__( 'No honorary advisors found.', 'esop-advisor' ) . '</p></div>';
	}

	$output .= '</div>';

	return $output;
}

// ============================================================================
// ESOP ADVISOR STAGES SHORTCODE (v1.31.0)
// ============================================================================
// Displays three stages of ESOP implementation with specialty category links.
// Used on the /esop-advisors/ page below the advisor directory grid.
// ============================================================================

add_shortcode( 'esop_advisor_stages', 'esop_advisor_stages_shortcode' );
add_shortcode( 'esop_advisor_picking', 'esop_advisor_picking_shortcode' );

/**
 * Shortcode: [esop_advisor_stages]
 *
 * Outputs the three stages of ESOP implementation with specialty category links.
 * The "Picking an ESOP Advisor" content block is now a separate shortcode:
 * [esop_advisor_picking]
 *
 * @since 1.31.0
 * @param array $atts Shortcode attributes (none currently)
 * @return string HTML output
 */
function esop_advisor_stages_shortcode( $atts ) {
	$atts = shortcode_atts( array(), $atts, 'esop_advisor_stages' );

	// Stage data: specialty slug => display name (mirrors /advisors/ page content)
	$stages = array(
		1 => array(
			'title'       => '1st Stage - Feasibility Study',
			'specialties' => array(
				'esop-accounting'                      => 'Accountants',
				'esop-lawyers'                         => 'ESOP Lawyers',
				'feasibility-study'                    => 'Feasibility Study',
				'esop-consultants'                     => 'ESOP Consultants',
				'investment-bankers'                   => 'Investment Banking',
				'succession-transition-exit-strategy'  => 'Succession / Transition / Exit Strategy Advisors',
				'esop-valuation'                       => 'ESOP Valuation',
			),
		),
		2 => array(
			'title'       => '2nd Stage - Due Diligence',
			'specialties' => array(
				'corporate-ma'                             => 'Mergers & Acquisitions',
				'repurchase-obligations'                   => 'Repurchase Obligation Study',
				'esop-tpa-third-party-administration'      => 'TPA (Third Party ESOP Administration)',
				'esop-trustees'                            => 'ESOP Trustees',
				'wealth-management'                        => 'Wealth Management & Advisors',
			),
		),
		3 => array(
			'title'       => '3rd Stage - After an ESOP Transaction',
			'specialties' => array(
				'esop-administrators'                          => 'ESOP Administrators',
				'esop-lawyers'                                 => 'Attorneys - Personal, Business, ESOP',
				'certification-training'                       => 'Certification / Training',
				'directors-governance'                         => 'Directors',
				'esop-communication'                           => 'ESOP Communication',
				'esop-committees'                              => 'ESOP Committees',
				'family-business'                              => 'Family Business Advisors',
				'directors-governance-2'                       => 'Governance Advisors',
				'leadership-development'                       => 'Leadership Development',
				'litigation-support'                           => 'Litigation Support',
				'strategic-planning-management-consulting'     => 'Management Consulting / Strategic Planning',
				'esop-ownership-culture'                       => 'ESOP Ownership Culture / Empowerment / Games',
				'esop-termination'                             => 'ESOP Termination Lawyers',
				'wealth-management-2'                          => 'Wealth Management Advisors',
			),
		),
	);

	// Build specialty links dynamically using get_term_link()
	// Some slugs appear in multiple stages (e.g., esop-lawyers, wealth-management, directors-governance)
	// so we use a key suffix (-2) to keep array keys unique while using the real slug for the link.
	$stage_links = array();
	foreach ( $stages as $num => $stage ) {
		$stage_links[ $num ] = array();
		foreach ( $stage['specialties'] as $key => $name ) {
			// Strip any "-2" suffix used for array key uniqueness
			$slug = preg_replace( '/-2$/', '', $key );
			$link = get_term_link( $slug, 'advisor_specialty' );
			if ( ! is_wp_error( $link ) ) {
				$stage_links[ $num ][] = array(
					'url'  => $link,
					'name' => $name,
				);
			} else {
				// Fallback: output as plain text if term not found
				$stage_links[ $num ][] = array(
					'url'  => '',
					'name' => $name,
				);
			}
		}
	}

	// Special entry: ESOP Jobs (external link in Stage 3)
	// Insert after "Governance Advisors" (index 7) — matches original page order
	array_splice( $stage_links[3], 8, 0, array( array(
		'url'  => 'https://esopb2b.com/esopjobs',
		'name' => 'ESOP Jobs',
	) ) );

	ob_start();

	// CSS — output once per page
	static $css_output = false;
	if ( ! $css_output ) {
		$css_output = true;
		?>
		<style type="text/css">
		.esop-advisor-stages-wrapper {
			padding-top: 45px;
			border-top: 1px solid #e2e8f0;
		}
		.esop-advisor-stages-wrapper .esop-stages-intro {
			font-size: 16px;
			line-height: 1.7;
			color: #4a5568;
			margin: 0 0 30px 0;
		}
		.esop-stages-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 30px;
			margin-bottom: 40px;
		}
		.esop-stages-grid .esop-stage-col--3 {
			grid-column: span 2;
		}
		.esop-stages-grid .esop-stage-col--3 .esop-stage-links {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 0 30px;
		}
		.esop-stages-grid h3 {
			font-size: 20px;
			font-weight: 700;
			color: #1a365d;
			margin: 0 0 15px 0;
			line-height: 1.3;
		}
		.esop-stage-links {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		.esop-stage-links li {
			margin: 0 0 10px 0;
			line-height: 1.4;
		}
		.esop-stage-links a {
			color: #0C71C3;
			text-decoration: none;
			font-size: 15px;
			transition: color 0.2s ease;
		}
		.esop-stage-links a:hover {
			color: #c75a23;
		}
		@media (max-width: 980px) {
			.esop-stages-grid {
				grid-template-columns: 1fr 1fr;
			}
			.esop-stages-grid .esop-stage-col--3 {
				grid-column: span 2;
			}
		}
		@media (max-width: 767px) {
			.esop-stages-grid {
				grid-template-columns: 1fr;
			}
			.esop-stages-grid .esop-stage-col--3 {
				grid-column: span 1;
			}
			.esop-stages-grid .esop-stage-col--3 .esop-stage-links {
				grid-template-columns: 1fr;
			}
		}
		</style>
		<?php
	}
	?>
	<div class="esop-advisor-stages-wrapper">

		<p class="esop-stages-intro">On ESOPMarketplace.com, you will find listings of the most competent and experienced ESOP Advisors to help your company with every aspect of implementing and running an ESOP.</p>

		<div class="esop-stages-grid">
			<?php foreach ( $stages as $num => $stage ) :
				$col_class = ( $num === 3 ) ? 'esop-stage-col esop-stage-col--3' : 'esop-stage-col';
			?>
				<div class="<?php echo esc_attr( $col_class ); ?>">
					<h3><?php echo esc_html( $stage['title'] ); ?></h3>
					<ul class="esop-stage-links">
						<?php foreach ( $stage_links[ $num ] as $item ) : ?>
							<li>
								<?php if ( ! empty( $item['url'] ) ) : ?>
									<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['name'] ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $item['name'] ); ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
	<?php

	return ob_get_clean();
}

/**
 * Shortcode: [esop_advisor_picking]
 *
 * Outputs the "Picking an ESOP Advisor Who's Right For Your Company" content block.
 * Intended for placement immediately after the Hero section on the /esop-advisors/ page,
 * before the advisor directory grid.
 *
 * @since 1.31.2
 * @return string HTML output
 */
function esop_advisor_picking_shortcode( $atts ) {
	$atts = shortcode_atts( array(), $atts, 'esop_advisor_picking' );

	ob_start();

	// CSS — output once per page
	static $picking_css_output = false;
	if ( ! $picking_css_output ) {
		$picking_css_output = true;
		?>
		<style type="text/css">
		.esop-picking-wrapper {
			margin-bottom: 30px;
		}
		.esop-picking-wrapper h2 {
			font-size: 26px;
			font-weight: 700;
			color: #1a365d;
			margin: 0 0 20px 0;
			line-height: 1.3;
		}
		.esop-picking-wrapper p {
			font-size: 16px;
			line-height: 1.7;
			color: #4a5568;
			margin: 0 0 16px 0;
		}
		</style>
		<?php
	}
	?>
	<div class="esop-picking-wrapper">
		<h2>Picking an ESOP Advisor Who&#8217;s Right For Your Company</h2>

		<p>Creating an ESOP includes 3 stages &#8211; first, a feasibility study to estimate the impact of an ESOP; second, due diligence during the transaction, to ensure fairness and regulatory compliance; and third, responsible management of the ESOP trust and good record-keeping.</p>

		<p>Moreover, communication with the workers is key. At each stage of ESOP implementation, it&#8217;s crucial to have a) open and sound communication between ESOP advisors, company owners and rank-and-file employees; b) a thorough effort to educate employees on what it means to be an ESOP participant; and c) an honest and fair accounting of the ESOP&#8217;s effects on the company and its workers. An ESOP advisor must not only be good at their job, but also at communicating and explaining their job to company employees.</p>

		<p>All of the advisors listed on our site have a proven record of professionalism and great communication skills. Pick any stage of an ESOP transaction on this page, and click on any category to view a listing of the best ESOP advisors near you!</p>
	</div>
	<?php

	return ob_get_clean();
}

// ============================================================================
// ESOP HISTORY TIMELINE COVERFLOW CAROUSEL (v1.31.2)
// ============================================================================
// Displays an interactive horizontal coverflow-style carousel of ESOP
// historical timeline events. 44 entries hardcoded in PHP array.
// Two display modes: cards (white bordered cards) and minimal (text only).
// ============================================================================

add_shortcode( 'esop_history_timeline', 'esop_history_timeline_shortcode' );

/**
 * Shortcode: [esop_history_timeline]
 *
 * Renders an interactive coverflow-style horizontal carousel of ESOP history events.
 *
 * @since 1.31.2
 * @param array $atts Shortcode attributes: style (cards|minimal), autoplay (true|false), speed (ms)
 * @return string HTML output
 */
function esop_history_timeline_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'style'    => 'cards',
		'autoplay' => 'false',
		'speed'    => '5000',
	), $atts, 'esop_history_timeline' );

	$style    = in_array( $atts['style'], array( 'cards', 'minimal' ), true ) ? $atts['style'] : 'cards';
	$autoplay = ( $atts['autoplay'] === 'true' || $atts['autoplay'] === '1' );
	$speed    = max( 1000, intval( $atts['speed'] ) );

	$timeline_data = array(
		array('year' => '1913', 'text' => 'Economist, lawyer and "father of the ESOP," <a href="http://en.wikipedia.org/wiki/Louis_O._Kelso" target="_blank" rel="noopener">Louis O. Kelso</a> is born in Denver, CO.'),
		array('year' => '1921', 'text' => 'Along with tax reductions, the Revenue Act of 1921 establishes a legal framework for Stock Bonus Plans.'),
		array('year' => '1956', 'text' => 'Louis Kelso develops a succession plan for Peninsula Newspapers, Inc., whose elderly co-owners want to transfer ownership to employees who lack the funds to buy in. Kelso\'s solution: borrow through the company\'s Stock Bonus Plan, repaying the loan with before-tax dollars. This "second income plan" is considered the first ESOP.'),
		array('year' => '1958', 'text' => 'To help a group of dairy farmers in the Central Valley of California, Kelso expands the ESOP principle to consumers by developing the Consumer Stock Ownership Plan (CSOP), which raises capital and promises consumers equity shares in the future. This becomes a popular solution for public utilities, and the groundwork for many cooperatively-funded enterprises, especially in agriculture.'),
		array('year' => '1958', 'text' => 'Kelso\'s and Mortimer Adler\'s book "<a href="http://www.amazon.com/Capitalist-Manifesto-Louis-Kelso/dp/1258153602/ref=sr_1_1?s=books&ie=UTF8&qid=1329683693&sr=1-1" target="_blank" rel="noopener">Capitalist Manifesto</a>" is published; the book argues that wealth concentration occurs due to the fact that capital is more productive than labor, and puts theoretical ground under the idea of compensating workers with capital instead of cash in hand.'),
		array('year' => '1959-60', 'text' => 'While an undergraduate at UC Berkeley, <a href="/esop-advisor-hof-roland-attenborough/">Roland Attenborough</a> begins a correspondence with Louis Kelso, later inviting him to present his ideas from the "Capitalist Manifesto" in front of various student organizations.'),
		array('year' => '1961', 'text' => 'Kelso and Adler publish their second book: "The New Capitalists." The book further develops their ideas for "capitalist democracy": it proposes to broaden the base of capital holders by making it easy for the average worker to take out non-recourse, insured bank loans as investment capital.'),
		array('year' => '1964', 'text' => 'The IRS sets rules for Plan Sponsors (designated responsible parties that are tasked with setting up health care or retirement accounts), allowing approval at the field, not national, IRS offices. This greatly eases the process of receiving approval for a Plan Sponsor.'),
		array('year' => '1967', 'text' => 'Kelso and Adler publish their third book: "Two-Factor Theory: The Economics of Reality." Subtitle: "How to Turn Eighty Million Workers Into Capitalists on Borrowed Money, and Other Proposals."'),
		array('year' => '1969', 'text' => 'Kelso hires Roland Attenborough as an associate, while in the process of structuring the first "classical" ESOP for Sullair Corporation. Attenborough does significant work in developing the plan document and trust agreement for the transaction.'),
		array('year' => '1971', 'text' => 'Kelso forms a merchant banking firm: Kelso Bangert &amp; Company. The firm specializes in leveraged buyouts, recapitalizations and growth capital transactions, frequently employing ESOPs.'),
		array('year' => '1971', 'text' => 'Harold Bangert, an investment banking client of Louis Kelso, coins the term "ESOP." The acronym eventually supplants Kelso\'s own preferred "second income plan" term.'),
		array('year' => '1971', 'text' => '<a href="/esop-advisor-hof-rob-edwards/">Rob Edwards</a> started practicing law.'),
		array('year' => '1973', 'text' => 'The Employee Retirement Income Security Act (ERISA), is slated for a vote in Congress. The law contains wide-ranging prohibitions on loans in qualified retirement plans, destroying ESOP tax advantages. Louis Kelso is introduced to Russell Long (D-LA), the chairman of the Senate Finance Committee. Soon, Long is persuaded to the merits of ESOPs and becomes an enthusiastic supporter, introducing language that defines and preserves ESOP tax advantages.'),
		array('year' => '1974', 'text' => 'ERISA passes in Congress. Along with strong regulation governing retirement benefits and other worker-protection measures, the law establishes a formal legal framework for ESOPs. The Act is the first piece of legislation to grant special status to the instruments, as well as the first to use the term ESOP.'),
		array('year' => '1974', 'text' => 'Menke &amp; Associates, Inc is formed by John Menke, one of the co-authors of ESOP legislation. The firm goes on to become America\'s largest ESOP administration firm.'),
		array('year' => '1975', 'text' => 'Rainer K. Schaaf founds R.K. SCHAAF ASSOCIATES, INC. From the beginning, R. K. Schaaf specializes in ESOP planning and administration, and is today one of the largest ESOP advisory firms in the nation.'),
		array('year' => '1975', 'text' => '<a href="/esop-advisor-hof-jared-kaplan/">Jared Kaplan</a> publishes the first article on ERISA ESOP rules. The article comes out in December 1975 in TAXES magazine, and is titled "ESOP\'s Fable."'),
		array('year' => '1976', 'text' => 'Private Capital Corporation is formed by <a href="/esop-advisor-hof-dickson-c-buxton/">Dick Buxton</a> and Joe Schuchert.'),
		array('year' => '1976', 'text' => 'ESOP advisor <a href="/esop-advisor-hof-greg-brown/">Greg Brown</a> begins work at law firm McDermott Will &amp; Emery.'),
		array('year' => '1976', 'text' => 'Rob Edwards begins working with ESOPs.'),
		array('year' => '1977', 'text' => 'ESOP Council of America is formed by Robert Smiley Jr. &amp; Richard Acheson.'),
		array('year' => '1977', 'text' => 'ESOPs come under attack from the Department of Labor, which proposes legislation to outlaw them. In response, Buxton and Harry Orchard found the National Association of ESOP Companies to protect the ESOP industry. The Association successfully lobbies to defeat the law.'),
		array('year' => '1978', 'text' => '<a href="/esop-advisor-hof-dennis-long/">Dennis Long</a> founds Benefit Consultants Inc. which will become BCI Group.'),
		array('year' => '1978', 'text' => 'The 1978 Revenue Act mandates 15% of Conrail – the primary Class I railroad in the U.S. Northeast – be owned by an ESOP.'),
		array('year' => '1978', 'text' => 'Buxton\'s Private Capital Corporation acquires Kelso &amp; Company.'),
		array('year' => '1979', 'text' => 'The ESOP Council of America and the National Association of ESOP Companies merge to form the ESOP Association with early trustee members like <a href="/esop-advisor-hof-merri-ash/">Merri Ash</a>.'),
		array('year' => 'Late 1979', 'text' => '<a href="/esop-advisor-hof-sue-ledingham/">Sue Ledingham</a> becomes an associate at R.K. Schaaf.'),
		array('year' => '1980', 'text' => 'The Chrysler Corporation Loan Guarantee Act requires that, as part of the conditions for federal emergency credit, 15% of company shares be held in an ESOP.'),
		array('year' => '1981', 'text' => 'The Economic Recovery Tax Act, also known as ERTA and the "Roth-Kemp Tax Cut," expands provisions for ESOPs.'),
		array('year' => '1981', 'text' => '<a href="/esop-advisor-hof-corey-rosen/">Corey Rosen</a> forms the National Center for Employee Ownership (NCEO). The Center\'s objective is to help individuals and businesses research employee ownership and to act as a clearinghouse for accurate and up-to-date information about ESOPs.'),
		array('year' => '1981', 'text' => 'After 3 years, Kelso &amp; Company separate from Private Capital Corporation. Frank Nickell, who joined the company in 1977, heads it to this day.'),
		array('year' => '1984', 'text' => 'Tax Reform Act of 1984 provides a big breakthrough for ESOPs by expanding the rules in the 1981 ERTA to cover additional types of stock transactions.'),
		array('year' => '1984', 'text' => 'The heyday of the corporate raider; Carl Icahn attempts to take over Virginia company Dan River Mills. The company fights off the attempt with an ESOP.'),
		array('year' => '1985', 'text' => 'As controller of a company undergoing a leveraged buyout, <a href="/esop-advisor-hof-scott-miller/">Scott Miller</a> oversees the installation of his first ESOP.'),
		array('year' => '1986', 'text' => 'Tax Reform Act of 1986 adds new incentives for Corporations to establish ESOPs.'),
		array('year' => '1991', 'text' => 'Louis Kelso passes away at the age of 77.'),
		array('year' => '1994', 'text' => 'With United Airlines on the brink of financial crisis, a large ESOP is founded as a bold new experiment in airline company governance. Over 50% of the company is distributed to employees.'),
		array('year' => '1997', 'text' => 'Taxpayer Relief Act of 1997 allows S Corporations to set up ESOPs.'),
		array('year' => '1998', 'text' => 'Employee owned S Corporations of America (ESCA) was formed to lobby on behalf of ESOPs to protect tax benefits from changes proposed by legislators.'),
		array('year' => '2003', 'text' => 'Benefit Consultants Inc (BCI) is sold to The Principal Financial Group.'),
		array('year' => '2003', 'text' => 'Rob Edwards joins Steiker, Fischer, Edwards &amp; Greenapple, P.C.'),
		array('year' => '2009', 'text' => 'Dennis Long (formerly of BCI) retires from the Principal Financial Group.'),
		array('year' => '2011', 'text' => 'Corey Rosen retires as Executive Director at the National Center for Employee Ownership, replaced by Loren Rodgers.'),
	);

	$total = count( $timeline_data );
	$instance_id = 'esop-timeline-' . wp_unique_id();

	ob_start();

	// CSS — output once per page (covers both cards and minimal modes)
	static $timeline_css_output = false;
	if ( ! $timeline_css_output ) {
		$timeline_css_output = true;
		?>
		<style type="text/css">
		/* === ESOP History Timeline Coverflow Carousel === */
		.esop-timeline-wrapper {
			position: relative;
			width: 100%;
			overflow: hidden;
			padding: 20px 0 10px;
		}
		.esop-timeline-carousel {
			position: relative;
			width: 100%;
			min-height: 150px;
		}
		.esop-timeline-item {
			position: absolute;
			top: 0;
			left: 50%;
			width: 420px;
			max-width: 85vw;
			transform: translateX(-50%) scale(0.8);
			opacity: 0;
			transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.45s ease;
			pointer-events: none;
			z-index: 1;
		}
		.esop-timeline-wrapper .esop-timeline-item.esop-timeline-active {
			transform: translateX(-50%) scale(1);
			opacity: 1;
			visibility: visible;
			pointer-events: auto;
			z-index: 3;
		}
		.esop-timeline-wrapper .esop-timeline-item.esop-timeline-prev {
			transform: translateX(calc(-50% - 460px)) scale(0.8);
			opacity: 0.55;
			visibility: visible;
			pointer-events: auto;
			z-index: 2;
		}
		.esop-timeline-wrapper .esop-timeline-item.esop-timeline-next {
			transform: translateX(calc(-50% + 460px)) scale(0.8);
			opacity: 0.55;
			visibility: visible;
			pointer-events: auto;
			z-index: 2;
		}

		/* Card mode */
		.esop-timeline-cards .esop-timeline-card {
			background: #fff;
			border: 1px solid #e2e8f0;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
		}
		.esop-timeline-cards .esop-timeline-active .esop-timeline-card {
			box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
			border-top: 3px solid #e35205;
		}

		/* Typography */
		.esop-timeline-year {
			font-size: 2rem;
			font-weight: 700;
			color: #1a365d;
			margin: 0 0 12px 0;
			line-height: 1.2;
		}
		.esop-timeline-text {
			color: #4a5568;
			font-size: 0.95rem;
			line-height: 1.6;
			margin: 0;
		}
		.esop-timeline-text a {
			color: #2b6cb0;
			text-decoration: none;
		}
		.esop-timeline-text a:hover {
			text-decoration: underline;
		}

		/* Navigation controls row */
		.esop-timeline-nav {
			display: flex !important;
			flex-direction: row !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 20px !important;
			padding: 40px 0 !important;
			width: 100% !important;
		}
		.esop-timeline-arrow {
			width: 44px;
			height: 44px;
			border-radius: 50%;
			background: #fff;
			border: 1px solid #e2e8f0;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: box-shadow 0.2s ease, border-color 0.2s ease;
			padding: 0;
			flex-shrink: 0;
		}
		.esop-timeline-arrow:hover {
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
			border-color: #cbd5e0;
		}
		.esop-timeline-arrow svg {
			width: 18px;
			height: 18px;
			stroke: #4a5568;
			fill: none;
			stroke-width: 2;
			stroke-linecap: round;
			stroke-linejoin: round;
			transition: stroke 0.2s ease;
		}
		.esop-timeline-arrow:hover svg {
			stroke: #1a365d;
		}

		/* Counter */
		.esop-timeline-counter {
			font-size: 0.8rem;
			color: #a0aec0;
			min-width: 60px;
			text-align: center;
		}

		/* Responsive: Tablet */
		@media (max-width: 1024px) {
			.esop-timeline-item {
				width: 340px;
			}
			.esop-timeline-wrapper .esop-timeline-item.esop-timeline-prev {
				transform: translateX(calc(-50% - 360px)) scale(0.8);
			}
			.esop-timeline-wrapper .esop-timeline-item.esop-timeline-next {
				transform: translateX(calc(-50% + 360px)) scale(0.8);
			}
		}

		/* Responsive: Mobile */
		@media (max-width: 767px) {
			.esop-timeline-carousel {
				min-height: 150px;
			}
			.esop-timeline-item {
				width: 280px;
				max-width: 70vw;
			}
			.esop-timeline-wrapper .esop-timeline-item.esop-timeline-prev {
				transform: translateX(calc(-50% - 220px)) scale(0.78);
				opacity: 0.3;
			}
			.esop-timeline-wrapper .esop-timeline-item.esop-timeline-next {
				transform: translateX(calc(-50% + 220px)) scale(0.78);
				opacity: 0.3;
			}
			.esop-timeline-arrow {
				width: 36px;
				height: 36px;
			}
			.esop-timeline-arrow svg {
				width: 14px;
				height: 14px;
			}
		}
		</style>
		<?php
	}

	$wrapper_class = 'esop-timeline-wrapper esop-timeline-' . esc_attr( $style );
	?>
	<div class="<?php echo $wrapper_class; ?>" id="<?php echo esc_attr( $instance_id ); ?>"
		role="region" aria-label="ESOP Historical Timeline"
		data-autoplay="<?php echo $autoplay ? 'true' : 'false'; ?>"
		data-speed="<?php echo esc_attr( $speed ); ?>"
		data-total="<?php echo esc_attr( $total ); ?>">

		<div class="esop-timeline-carousel">
			<?php
			$last_index = $total - 1;
			foreach ( $timeline_data as $i => $item ) :
				$item_class = 'esop-timeline-item';
				if ( $i === 0 ) {
					$item_class .= ' esop-timeline-active';
				} elseif ( $i === 1 ) {
					$item_class .= ' esop-timeline-next';
				} elseif ( $i === $last_index ) {
					$item_class .= ' esop-timeline-prev';
				}
			?>
				<div class="<?php echo esc_attr( $item_class ); ?>" data-index="<?php echo esc_attr( $i ); ?>">
					<div class="esop-timeline-card" style="padding:20px;">
						<h3 class="esop-timeline-year"><?php echo esc_html( $item['year'] ); ?></h3>
						<div class="esop-timeline-text"><?php echo $item['text']; ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="esop-timeline-nav" style="display:flex;flex-direction:row;align-items:center;justify-content:center;gap:20px;padding:40px 0;">
			<button class="esop-timeline-arrow esop-timeline-arrow-prev" aria-label="Previous timeline event" type="button">
				<svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"></polyline></svg>
			</button>
			<div class="esop-timeline-counter" aria-live="polite">
				<span class="esop-timeline-counter-current">1</span> of <span class="esop-timeline-counter-total"><?php echo esc_html( $total ); ?></span>
			</div>
			<button class="esop-timeline-arrow esop-timeline-arrow-next" aria-label="Next timeline event" type="button">
				<svg viewBox="0 0 24 24"><polyline points="9 6 15 12 9 18"></polyline></svg>
			</button>
		</div>

	</div>
	<?php

	// Schedule JS output via wp_footer (inline scripts in shortcode output are stripped by caching plugins)
	add_action( 'wp_footer', 'esop_history_timeline_output_scripts', 999 );

	return ob_get_clean();
}

/**
 * Output the timeline carousel JavaScript in the footer.
 * Hooked to wp_footer at priority 999 to ensure DOM is ready.
 *
 * @since 1.31.2
 */
function esop_history_timeline_output_scripts() {
	static $done = false;
	if ( $done ) return;
	$done = true;
	?>
	<script type="text/javascript">
	(function() {
		'use strict';

		function initTimeline(wrapper) {
			var items = wrapper.querySelectorAll('.esop-timeline-item');
			var total = items.length;
			if (total === 0) return;

			var current = 0;
			var isAnimating = false;
			var autoplay = wrapper.getAttribute('data-autoplay') === 'true';
			var speed = parseInt(wrapper.getAttribute('data-speed'), 10) || 5000;
			var autoplayTimer = null;

			var carousel = wrapper.querySelector('.esop-timeline-carousel');
			var prevBtn = wrapper.querySelector('.esop-timeline-arrow-prev');
			var nextBtn = wrapper.querySelector('.esop-timeline-arrow-next');
			var counterCurrent = wrapper.querySelector('.esop-timeline-counter-current');

			function mod(n, m) {
				return ((n % m) + m) % m;
			}

			function resizeCarousel() {
				var activeItem = items[current];
				if (activeItem && carousel) {
					carousel.style.height = activeItem.offsetHeight + 'px';
				}
			}

			function update() {
				var prevIdx = mod(current - 1, total);
				var nextIdx = mod(current + 1, total);

				for (var i = 0; i < total; i++) {
					items[i].classList.remove('esop-timeline-active', 'esop-timeline-prev', 'esop-timeline-next');
					if (i === current) {
						items[i].classList.add('esop-timeline-active');
					} else if (i === prevIdx) {
						items[i].classList.add('esop-timeline-prev');
					} else if (i === nextIdx) {
						items[i].classList.add('esop-timeline-next');
					}
				}

				resizeCarousel();

				if (counterCurrent) {
					counterCurrent.textContent = (current + 1);
				}
			}

			function goTo(index) {
				if (isAnimating) return;
				isAnimating = true;
				current = mod(index, total);
				update();
				setTimeout(function() { isAnimating = false; }, 460);
			}

			function next() { goTo(current + 1); }
			function prev() { goTo(current - 1); }

			// Arrow buttons
			if (prevBtn) prevBtn.addEventListener('click', function() { prev(); resetAutoplay(); });
			if (nextBtn) nextBtn.addEventListener('click', function() { next(); resetAutoplay(); });

			// Keyboard navigation
			wrapper.setAttribute('tabindex', '0');
			wrapper.addEventListener('keydown', function(e) {
				if (e.key === 'ArrowLeft') { e.preventDefault(); prev(); resetAutoplay(); }
				if (e.key === 'ArrowRight') { e.preventDefault(); next(); resetAutoplay(); }
			});

			// Touch/swipe support
			var touchStartX = 0;

			carousel.addEventListener('touchstart', function(e) {
				touchStartX = e.changedTouches[0].screenX;
			}, { passive: true });

			carousel.addEventListener('touchend', function(e) {
				var touchEndX = e.changedTouches[0].screenX;
				var diff = touchStartX - touchEndX;
				if (Math.abs(diff) > 50) {
					if (diff > 0) { next(); } else { prev(); }
					resetAutoplay();
				}
			}, { passive: true });

			// Click on flanking items to navigate
			for (var j = 0; j < total; j++) {
				(function(idx) {
					items[idx].addEventListener('click', function() {
						if (items[idx].classList.contains('esop-timeline-prev')) { prev(); resetAutoplay(); }
						else if (items[idx].classList.contains('esop-timeline-next')) { next(); resetAutoplay(); }
					});
				})(j);
			}

			// Autoplay
			function startAutoplay() {
				if (!autoplay) return;
				stopAutoplay();
				autoplayTimer = setInterval(next, speed);
			}
			function stopAutoplay() {
				if (autoplayTimer) { clearInterval(autoplayTimer); autoplayTimer = null; }
			}
			function resetAutoplay() {
				stopAutoplay();
				startAutoplay();
			}

			// Pause on hover/focus
			wrapper.addEventListener('mouseenter', stopAutoplay);
			wrapper.addEventListener('mouseleave', startAutoplay);
			wrapper.addEventListener('focusin', stopAutoplay);
			wrapper.addEventListener('focusout', startAutoplay);

			// Resize carousel on window resize
			window.addEventListener('resize', resizeCarousel);

			// Initialize
			update();
			startAutoplay();
		}

		// Init all instances
		var wrappers = document.querySelectorAll('.esop-timeline-wrapper');
		for (var i = 0; i < wrappers.length; i++) {
			if (!wrappers[i].dataset.initialized) {
				wrappers[i].dataset.initialized = '1';
				initTimeline(wrappers[i]);
			}
		}
	})();
	</script>
	<?php
}

// ============================================================================
// VISITOR TRACKING SYSTEM
// ============================================================================
// Version 1.0 - January 2026
// Tracks page views for advisor profiles and specialty archive pages
// with IP-based cooldown, bot exclusion, and automatic resets
// ============================================================================

// === VISITOR TRACKING: IP UTILITIES ===

/**
 * Get visitor IP address with proxy detection
 *
 * Checks multiple headers to get the real IP address even when behind
 * proxies, load balancers, or CDNs.
 *
 * @since 1.28.0
 * @return string|false Sanitized IP address or false if invalid
 */
function esop_advisor_get_visitor_ip() {
	$ip = '';

	// Check for forwarded IP (proxies/load balancers)
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		// May contain multiple IPs - get the first one (client IP)
		$ip_list = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
		$ip = trim( $ip_list[0] );
	} elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	}

	// Validate the IP address
	$validated_ip = filter_var( $ip, FILTER_VALIDATE_IP );

	if ( ! $validated_ip ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[ESOP Visitor Tracking] Invalid IP address detected: ' . $ip );
		}
		return false;
	}

	return $validated_ip;
}

/**
 * Check if current request is from a known bot/crawler
 *
 * @since 1.28.0
 * @return bool True if bot detected, false otherwise
 */
function esop_advisor_is_bot() {
	// Get user agent
	$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] )
		? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) )
		: '';

	// Skip if no user agent
	if ( empty( $user_agent ) ) {
		return true;
	}

	// Bot patterns to check (case-insensitive)
	$bot_patterns = array(
		'googlebot',
		'bingbot',
		'slurp',
		'crawler',
		'spider',
		'bot',
		'facebookexternalhit',
		'twitterbot',
		'linkedinbot',
		'pinterest',
		'semrush',
		'ahrefsbot',
		'mj12bot',
		'dotbot',
		'yandex',
		'baiduspider',
	);

	$user_agent_lower = strtolower( $user_agent );

	foreach ( $bot_patterns as $pattern ) {
		if ( strpos( $user_agent_lower, $pattern ) !== false ) {
			return true;
		}
	}

	return false;
}

// === VISITOR TRACKING: CORE TRACKING FUNCTIONS ===

/**
 * Track page view on template_redirect
 *
 * Hooks into template_redirect to track views for:
 * - Single advisor posts (esop_advisor)
 * - Specialty archive pages (advisor_specialty taxonomy)
 *
 * @since 1.28.0
 */
add_action( 'template_redirect', 'esop_advisor_track_page_view' );

function esop_advisor_track_page_view() {
	// Skip if admin or doing AJAX/cron
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}

	// Skip if bot
	if ( esop_advisor_is_bot() ) {
		return;
	}

	// Get visitor IP
	$ip = esop_advisor_get_visitor_ip();
	if ( ! $ip ) {
		return;
	}

	// Create IP hash for privacy
	$ip_hash = md5( $ip );

	// Check what page we're on and track accordingly
	if ( is_singular( 'esop_advisor' ) ) {
		esop_advisor_track_advisor_view( get_the_ID(), $ip_hash );
	} elseif ( is_tax( 'advisor_specialty' ) ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			esop_advisor_track_specialty_view( $term->term_id, $ip_hash );
		}
	}
}

/**
 * Track view for a single advisor
 *
 * @since 1.28.0
 * @param int    $post_id Post ID of the advisor
 * @param string $ip_hash MD5 hash of visitor IP
 * @return bool True if tracked, false if skipped (cooldown)
 */
function esop_advisor_track_advisor_view( $post_id, $ip_hash ) {
	$post_id = absint( $post_id );
	if ( ! $post_id ) {
		return false;
	}

	// Check cooldown transient
	$cooldown_key = 'esop_view_cooldown_' . $post_id . '_' . $ip_hash;

	if ( get_transient( $cooldown_key ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[ESOP Visitor Tracking] Cooldown active for advisor ' . $post_id );
		}
		return false;
	}

	// Increment view counts
	$total  = absint( get_post_meta( $post_id, '_esop_views_total', true ) );
	$yearly = absint( get_post_meta( $post_id, '_esop_views_yearly', true ) );
	$weekly = absint( get_post_meta( $post_id, '_esop_views_weekly', true ) );

	update_post_meta( $post_id, '_esop_views_total', $total + 1 );
	update_post_meta( $post_id, '_esop_views_yearly', $yearly + 1 );
	update_post_meta( $post_id, '_esop_views_weekly', $weekly + 1 );

	// Set cooldown transient (24 hours = 86400 seconds)
	set_transient( $cooldown_key, 1, 86400 );

	// Clear display cache
	delete_transient( 'esop_visitor_stats_' . $post_id );

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( '[ESOP Visitor Tracking] Tracked view for advisor ' . $post_id . ' - Total: ' . ( $total + 1 ) );
	}

	return true;
}

/**
 * Track view for a specialty archive page
 *
 * @since 1.28.0
 * @param int    $term_id Term ID of the specialty
 * @param string $ip_hash MD5 hash of visitor IP
 * @return bool True if tracked, false if skipped (cooldown)
 */
function esop_advisor_track_specialty_view( $term_id, $ip_hash ) {
	$term_id = absint( $term_id );
	if ( ! $term_id ) {
		return false;
	}

	// Check cooldown transient
	$cooldown_key = 'esop_view_cooldown_term_' . $term_id . '_' . $ip_hash;

	if ( get_transient( $cooldown_key ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[ESOP Visitor Tracking] Cooldown active for specialty ' . $term_id );
		}
		return false;
	}

	// Increment view counts
	$total  = absint( get_term_meta( $term_id, '_esop_specialty_views_total', true ) );
	$yearly = absint( get_term_meta( $term_id, '_esop_specialty_views_yearly', true ) );
	$weekly = absint( get_term_meta( $term_id, '_esop_specialty_views_weekly', true ) );

	update_term_meta( $term_id, '_esop_specialty_views_total', $total + 1 );
	update_term_meta( $term_id, '_esop_specialty_views_yearly', $yearly + 1 );
	update_term_meta( $term_id, '_esop_specialty_views_weekly', $weekly + 1 );

	// Set cooldown transient (24 hours = 86400 seconds)
	set_transient( $cooldown_key, 1, 86400 );

	// Clear display cache
	delete_transient( 'esop_visitor_stats_term_' . $term_id );

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( '[ESOP Visitor Tracking] Tracked view for specialty ' . $term_id . ' - Total: ' . ( $total + 1 ) );
	}

	return true;
}

// === VISITOR TRACKING: DISPLAY COMPONENT ===

/**
 * Display visitor statistics
 *
 * Shortcode: [esop_visitor_stats]
 * Auto-detects page type if not specified.
 *
 * @since 1.28.0
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
add_shortcode( 'esop_visitor_stats', 'esop_advisor_visitor_stats_shortcode' );

function esop_advisor_visitor_stats_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'type'    => '', // 'advisor' or 'specialty' - auto-detect if empty
		'post_id' => 0,
		'term_id' => 0,
	), $atts, 'esop_visitor_stats' );

	$output = '';
	$cache_key = '';

	// Auto-detect type if not specified
	if ( empty( $atts['type'] ) ) {
		if ( is_singular( 'esop_advisor' ) ) {
			$atts['type'] = 'advisor';
			$atts['post_id'] = get_the_ID();
		} elseif ( is_tax( 'advisor_specialty' ) ) {
			$atts['type'] = 'specialty';
			$term = get_queried_object();
			$atts['term_id'] = $term ? $term->term_id : 0;
		}
	}

	if ( $atts['type'] === 'advisor' && $atts['post_id'] ) {
		$post_id = absint( $atts['post_id'] );
		$cache_key = 'esop_visitor_stats_' . $post_id;

		// Check cache
		$cached = get_transient( $cache_key );
		if ( $cached !== false ) {
			return $cached;
		}

		// Get stats
		$total  = absint( get_post_meta( $post_id, '_esop_views_total', true ) );
		$yearly = absint( get_post_meta( $post_id, '_esop_views_yearly', true ) );
		$weekly = absint( get_post_meta( $post_id, '_esop_views_weekly', true ) );

		// Don't display if all zeros and no migration has occurred
		if ( $total === 0 && $yearly === 0 && $weekly === 0 ) {
			return '';
		}

		$output = esop_advisor_format_stats_output( $weekly, $yearly, $total );

	} elseif ( $atts['type'] === 'specialty' && $atts['term_id'] ) {
		$term_id = absint( $atts['term_id'] );
		$cache_key = 'esop_visitor_stats_term_' . $term_id;

		// Check cache
		$cached = get_transient( $cache_key );
		if ( $cached !== false ) {
			return $cached;
		}

		// Get stats
		$total  = absint( get_term_meta( $term_id, '_esop_specialty_views_total', true ) );
		$yearly = absint( get_term_meta( $term_id, '_esop_specialty_views_yearly', true ) );
		$weekly = absint( get_term_meta( $term_id, '_esop_specialty_views_weekly', true ) );

		// Don't display if all zeros
		if ( $total === 0 && $yearly === 0 && $weekly === 0 ) {
			return '';
		}

		$output = esop_advisor_format_stats_output( $weekly, $yearly, $total );
	}

	// Cache the output for 1 hour
	if ( ! empty( $output ) && ! empty( $cache_key ) ) {
		set_transient( $cache_key, $output, 3600 );
	}

	return $output;
}

/**
 * Format the stats output HTML
 *
 * @since 1.28.0
 * @param int $weekly Weekly views
 * @param int $yearly Yearly views
 * @param int $total  Total views
 * @return string HTML output
 */
function esop_advisor_format_stats_output( $weekly, $yearly, $total ) {
	$formatted_weekly = number_format( $weekly, 0, '.', ',' );
	$formatted_yearly = number_format( $yearly, 0, '.', ',' );
	$formatted_total  = number_format( $total, 0, '.', ',' );

	$output = '<div class="esop-visitor-stats">';
	$output .= '<span class="esop-stat esop-stat-weekly">Views this week: <strong>' . esc_html( $formatted_weekly ) . '</strong></span>';
	$output .= '<span class="esop-stat-separator"> | </span>';
	$output .= '<span class="esop-stat esop-stat-yearly">Views this year: <strong>' . esc_html( $formatted_yearly ) . '</strong></span>';
	$output .= '<span class="esop-stat-separator"> | </span>';
	$output .= '<span class="esop-stat esop-stat-total">Total views: <strong>' . esc_html( $formatted_total ) . '</strong></span>';
	$output .= '</div>';

	return $output;
}

/**
 * Auto-inject visitor stats into advisor single pages and specialty archives
 *
 * Adds stats display to the footer area of relevant pages.
 *
 * @since 1.28.0
 */
add_action( 'wp_footer', 'esop_advisor_auto_inject_stats' );

function esop_advisor_auto_inject_stats() {
	// Only on frontend, non-admin
	if ( is_admin() ) {
		return;
	}

	$stats_html = '';

	if ( is_singular( 'esop_advisor' ) ) {
		$post_id = get_the_ID();
		$total  = absint( get_post_meta( $post_id, '_esop_views_total', true ) );
		$yearly = absint( get_post_meta( $post_id, '_esop_views_yearly', true ) );
		$weekly = absint( get_post_meta( $post_id, '_esop_views_weekly', true ) );

		if ( $total > 0 || $yearly > 0 || $weekly > 0 ) {
			$stats_html = esop_advisor_format_stats_output( $weekly, $yearly, $total );
		}
	} elseif ( is_tax( 'advisor_specialty' ) ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$total  = absint( get_term_meta( $term->term_id, '_esop_specialty_views_total', true ) );
			$yearly = absint( get_term_meta( $term->term_id, '_esop_specialty_views_yearly', true ) );
			$weekly = absint( get_term_meta( $term->term_id, '_esop_specialty_views_weekly', true ) );

			if ( $total > 0 || $yearly > 0 || $weekly > 0 ) {
				$stats_html = esop_advisor_format_stats_output( $weekly, $yearly, $total );
			}
		}
	}

	if ( ! empty( $stats_html ) ) {
		echo '<div class="esop-visitor-stats-wrapper">' . $stats_html . '</div>';
	}
}

/**
 * Output CSS for visitor stats display
 *
 * @since 1.28.0
 */
add_action( 'wp_head', 'esop_advisor_visitor_stats_css' );

function esop_advisor_visitor_stats_css() {
	// Only output on relevant pages
	if ( ! is_singular( 'esop_advisor' ) && ! is_tax( 'advisor_specialty' ) ) {
		return;
	}
	?>
	<style type="text/css">
	/* ESOP Advisor Visitor Stats */
	.esop-visitor-stats-wrapper {
		position: fixed;
		bottom: 20px;
		right: 20px;
		z-index: 9999;
		pointer-events: none;
	}
	.esop-visitor-stats {
		background: rgba(255, 255, 255, 0.95);
		padding: 10px 15px;
		border-radius: 4px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		font-size: 11px;
		color: #666;
		line-height: 1.4;
		pointer-events: auto;
	}
	.esop-visitor-stats .esop-stat strong {
		color: #333;
		font-weight: 600;
	}
	.esop-visitor-stats .esop-stat-separator {
		color: #ccc;
		margin: 0 2px;
	}
	@media (max-width: 768px) {
		.esop-visitor-stats-wrapper {
			bottom: 10px;
			right: 10px;
			left: 10px;
		}
		.esop-visitor-stats {
			font-size: 10px;
			padding: 8px 12px;
			text-align: center;
		}
		.esop-visitor-stats .esop-stat-separator {
			display: block;
			height: 0;
			overflow: hidden;
		}
		.esop-visitor-stats .esop-stat {
			display: block;
		}
	}
	</style>
	<?php
}

// === VISITOR TRACKING: ADMIN META BOX ===

/**
 * Add visitor statistics meta box to advisor edit screen
 *
 * @since 1.28.0
 */
add_action( 'add_meta_boxes', 'esop_advisor_add_stats_meta_box' );

function esop_advisor_add_stats_meta_box() {
	add_meta_box(
		'esop_advisor_visitor_stats',
		__( 'Visitor Statistics', 'esop-advisor' ),
		'esop_advisor_stats_meta_box_render',
		'esop_advisor',
		'side',
		'default'
	);
}

/**
 * Render visitor statistics meta box
 *
 * @since 1.28.0
 * @param WP_Post $post Current post object
 */
function esop_advisor_stats_meta_box_render( $post ) {
	// Only show to admins
	if ( ! current_user_can( 'manage_options' ) ) {
		echo '<p>' . esc_html__( 'You do not have permission to view statistics.', 'esop-advisor' ) . '</p>';
		return;
	}

	$post_id = $post->ID;

	// Get stats
	$total  = absint( get_post_meta( $post_id, '_esop_views_total', true ) );
	$yearly = absint( get_post_meta( $post_id, '_esop_views_yearly', true ) );
	$weekly = absint( get_post_meta( $post_id, '_esop_views_weekly', true ) );
	?>
	<div class="esop-stats-meta-box">
		<table class="form-table" style="margin: 0;">
			<tr>
				<th style="padding: 8px 0; width: 50%;"><?php esc_html_e( 'Total Views:', 'esop-advisor' ); ?></th>
				<td style="padding: 8px 0;"><strong><?php echo esc_html( number_format( $total ) ); ?></strong></td>
			</tr>
			<tr>
				<th style="padding: 8px 0;"><?php esc_html_e( 'This Year:', 'esop-advisor' ); ?></th>
				<td style="padding: 8px 0;"><strong><?php echo esc_html( number_format( $yearly ) ); ?></strong></td>
			</tr>
			<tr>
				<th style="padding: 8px 0;"><?php esc_html_e( 'This Week:', 'esop-advisor' ); ?></th>
				<td style="padding: 8px 0;"><strong><?php echo esc_html( number_format( $weekly ) ); ?></strong></td>
			</tr>
		</table>
		<p style="font-size: 11px; color: #666; margin-top: 10px;">
			<?php esc_html_e( 'Statistics are tracked when visitors view this advisor profile.', 'esop-advisor' ); ?>
		</p>
	</div>
	<?php
}

// === VISITOR TRACKING: ADMIN MENU PAGE ===

/**
 * Add visitor statistics submenu page
 *
 * @since 1.28.0
 */
add_action( 'admin_menu', 'esop_advisor_add_stats_menu_page' );

function esop_advisor_add_stats_menu_page() {
	add_submenu_page(
		'edit.php?post_type=esop_advisor',
		__( 'Visitor Statistics', 'esop-advisor' ),
		__( 'View Statistics', 'esop-advisor' ),
		'manage_options',
		'esop-visitor-stats',
		'esop_advisor_stats_page_render'
	);
}

/**
 * Handle admin stats page actions (CSV export, manual resets)
 *
 * @since 1.28.0
 */
add_action( 'admin_init', 'esop_advisor_stats_page_actions' );

function esop_advisor_stats_page_actions() {
	// Check if we're on the stats page
	if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'esop-visitor-stats' ) {
		return;
	}

	// Handle CSV export
	if ( isset( $_GET['action'] ) && $_GET['action'] === 'export_csv' ) {
		if ( ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'esop_export_stats' ) ) {
			wp_die( 'Security check failed' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized' );
		}

		esop_advisor_export_stats_csv();
		exit;
	}
}

/**
 * Export visitor statistics to CSV
 *
 * @since 1.28.0
 */
function esop_advisor_export_stats_csv() {
	$filename = 'esop-visitor-stats-' . gmdate( 'Y-m-d' ) . '.csv';

	header( 'Content-Type: text/csv' );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'Pragma: no-cache' );
	header( 'Expires: 0' );

	$output = fopen( 'php://output', 'w' );

	// Header row
	fputcsv( $output, array(
		'Type',
		'ID',
		'Name',
		'Total Views',
		'Yearly Views',
		'Weekly Views',
	) );

	// Advisor stats
	$advisors = get_posts( array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	foreach ( $advisors as $advisor ) {
		fputcsv( $output, array(
			'Advisor',
			$advisor->ID,
			$advisor->post_title,
			get_post_meta( $advisor->ID, '_esop_views_total', true ) ?: 0,
			get_post_meta( $advisor->ID, '_esop_views_yearly', true ) ?: 0,
			get_post_meta( $advisor->ID, '_esop_views_weekly', true ) ?: 0,
		) );
	}

	// Specialty stats
	$specialties = get_terms( array(
		'taxonomy'   => 'advisor_specialty',
		'hide_empty' => false,
	) );

	if ( ! is_wp_error( $specialties ) ) {
		foreach ( $specialties as $specialty ) {
			fputcsv( $output, array(
				'Specialty',
				$specialty->term_id,
				$specialty->name,
				get_term_meta( $specialty->term_id, '_esop_specialty_views_total', true ) ?: 0,
				get_term_meta( $specialty->term_id, '_esop_specialty_views_yearly', true ) ?: 0,
				get_term_meta( $specialty->term_id, '_esop_specialty_views_weekly', true ) ?: 0,
			) );
		}
	}

	fclose( $output );
}

/**
 * Render the visitor statistics admin page
 *
 * @since 1.28.0
 */
function esop_advisor_stats_page_render() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to access this page.', 'esop-advisor' ) );
	}

	// Get sorting parameters
	$orderby = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'total';
	$order = isset( $_GET['order'] ) && strtoupper( $_GET['order'] ) === 'ASC' ? 'ASC' : 'DESC';
	$toggle_order = $order === 'ASC' ? 'desc' : 'asc';

	// Get all advisors
	$advisors = get_posts( array(
		'post_type'      => 'esop_advisor',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	// Build stats array for sorting
	$advisor_stats = array();
	foreach ( $advisors as $advisor ) {
		$advisor_stats[] = array(
			'id'     => $advisor->ID,
			'name'   => $advisor->post_title,
			'total'  => absint( get_post_meta( $advisor->ID, '_esop_views_total', true ) ),
			'yearly' => absint( get_post_meta( $advisor->ID, '_esop_views_yearly', true ) ),
			'weekly' => absint( get_post_meta( $advisor->ID, '_esop_views_weekly', true ) ),
		);
	}

	// Sort
	usort( $advisor_stats, function( $a, $b ) use ( $orderby, $order ) {
		$val_a = $orderby === 'name' ? $a['name'] : $a[ $orderby ];
		$val_b = $orderby === 'name' ? $b['name'] : $b[ $orderby ];

		if ( $orderby === 'name' ) {
			$result = strcasecmp( $val_a, $val_b );
		} else {
			$result = $val_a - $val_b;
		}

		return $order === 'ASC' ? $result : -$result;
	} );

	// Get specialties
	$specialties = get_terms( array(
		'taxonomy'   => 'advisor_specialty',
		'hide_empty' => false,
	) );

	$specialty_stats = array();
	if ( ! is_wp_error( $specialties ) ) {
		foreach ( $specialties as $specialty ) {
			$specialty_stats[] = array(
				'id'     => $specialty->term_id,
				'name'   => $specialty->name,
				'total'  => absint( get_term_meta( $specialty->term_id, '_esop_specialty_views_total', true ) ),
				'yearly' => absint( get_term_meta( $specialty->term_id, '_esop_specialty_views_yearly', true ) ),
				'weekly' => absint( get_term_meta( $specialty->term_id, '_esop_specialty_views_weekly', true ) ),
			);
		}
	}

	$base_url = admin_url( 'edit.php?post_type=esop_advisor&page=esop-visitor-stats' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Visitor Statistics', 'esop-advisor' ); ?></h1>

		<?php settings_errors( 'esop_stats' ); ?>

		<!-- Actions Row -->
		<div class="esop-stats-actions" style="margin: 20px 0; padding: 15px; background: #fff; border: 1px solid #ccd0d4;">
			<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'action', 'export_csv', $base_url ), 'esop_export_stats' ) ); ?>" class="button button-primary">
				<?php esc_html_e( 'Export to CSV', 'esop-advisor' ); ?>
			</a>
		</div>

		<!-- Advisor Statistics Table -->
		<h2><?php esc_html_e( 'Advisor Statistics', 'esop-advisor' ); ?></h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-name <?php echo $orderby === 'name' ? 'sorted ' . strtolower( $order ) : 'sortable desc'; ?>">
						<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $orderby === 'name' ? $toggle_order : 'asc' ), $base_url ) ); ?>">
							<span><?php esc_html_e( 'Advisor', 'esop-advisor' ); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-total <?php echo $orderby === 'total' ? 'sorted ' . strtolower( $order ) : 'sortable desc'; ?>">
						<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'total', 'order' => $orderby === 'total' ? $toggle_order : 'desc' ), $base_url ) ); ?>">
							<span><?php esc_html_e( 'Total Views', 'esop-advisor' ); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-yearly <?php echo $orderby === 'yearly' ? 'sorted ' . strtolower( $order ) : 'sortable desc'; ?>">
						<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'yearly', 'order' => $orderby === 'yearly' ? $toggle_order : 'desc' ), $base_url ) ); ?>">
							<span><?php esc_html_e( 'Yearly Views', 'esop-advisor' ); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-weekly <?php echo $orderby === 'weekly' ? 'sorted ' . strtolower( $order ) : 'sortable desc'; ?>">
						<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'weekly', 'order' => $orderby === 'weekly' ? $toggle_order : 'desc' ), $base_url ) ); ?>">
							<span><?php esc_html_e( 'Weekly Views', 'esop-advisor' ); ?></span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $advisor_stats ) ) : ?>
					<tr>
						<td colspan="4"><?php esc_html_e( 'No advisors found.', 'esop-advisor' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $advisor_stats as $stat ) : ?>
						<tr>
							<td>
								<a href="<?php echo esc_url( get_edit_post_link( $stat['id'] ) ); ?>">
									<strong><?php echo esc_html( $stat['name'] ); ?></strong>
								</a>
							</td>
							<td><?php echo esc_html( number_format( $stat['total'] ) ); ?></td>
							<td><?php echo esc_html( number_format( $stat['yearly'] ) ); ?></td>
							<td><?php echo esc_html( number_format( $stat['weekly'] ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<!-- Specialty Statistics Table -->
		<h2 style="margin-top: 30px;"><?php esc_html_e( 'Specialty Statistics', 'esop-advisor' ); ?></h2>
		<table class="wp-list-table widefat fixed striped">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Specialty', 'esop-advisor' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Total Views', 'esop-advisor' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Yearly Views', 'esop-advisor' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Weekly Views', 'esop-advisor' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( empty( $specialty_stats ) ) : ?>
					<tr>
						<td colspan="4"><?php esc_html_e( 'No specialties found.', 'esop-advisor' ); ?></td>
					</tr>
				<?php else : ?>
					<?php foreach ( $specialty_stats as $stat ) : ?>
						<tr>
							<td>
								<a href="<?php echo esc_url( get_edit_term_link( $stat['id'], 'advisor_specialty' ) ); ?>">
									<strong><?php echo esc_html( $stat['name'] ); ?></strong>
								</a>
							</td>
							<td><?php echo esc_html( number_format( $stat['total'] ) ); ?></td>
							<td><?php echo esc_html( number_format( $stat['yearly'] ) ); ?></td>
							<td><?php echo esc_html( number_format( $stat['weekly'] ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php
}

// === VISITOR TRACKING: SPECIALTY TERM META FIELDS ===

/**
 * Add visitor statistics fields to specialty edit screen
 *
 * @since 1.28.0
 * @param WP_Term $term Current term object
 */
add_action( 'advisor_specialty_edit_form_fields', 'esop_advisor_specialty_stats_fields', 20 );

function esop_advisor_specialty_stats_fields( $term ) {
	// Only show to admins
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$term_id = $term->term_id;

	$total  = absint( get_term_meta( $term_id, '_esop_specialty_views_total', true ) );
	$yearly = absint( get_term_meta( $term_id, '_esop_specialty_views_yearly', true ) );
	$weekly = absint( get_term_meta( $term_id, '_esop_specialty_views_weekly', true ) );
	?>
	<tr class="form-field">
		<th scope="row"><?php esc_html_e( 'Visitor Statistics', 'esop-advisor' ); ?></th>
		<td>
			<div style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd;">
				<p><strong><?php esc_html_e( 'Total Views:', 'esop-advisor' ); ?></strong> <?php echo esc_html( number_format( $total ) ); ?></p>
				<p><strong><?php esc_html_e( 'Yearly Views:', 'esop-advisor' ); ?></strong> <?php echo esc_html( number_format( $yearly ) ); ?></p>
				<p><strong><?php esc_html_e( 'Weekly Views:', 'esop-advisor' ); ?></strong> <?php echo esc_html( number_format( $weekly ) ); ?></p>
			</div>
			<p class="description">
				<?php esc_html_e( 'View counts are tracked when visitors view this specialty archive page.', 'esop-advisor' ); ?>
			</p>
		</td>
	</tr>
	<?php
}

// ============================================================================
// AUTHOR LIST SHORTCODE
// ============================================================================

/**
 * Display a list of WordPress post authors
 *
 * Shortcode: [esop_author_list]
 * Displays a vertical, left-aligned list of authors who have published posts.
 * Each author name links to their WordPress author archive page.
 *
 * Attributes:
 * - show_post_count: "yes" or "no" (default: "no") - Show post count after name
 * - orderby: "name" or "post_count" (default: "name") - Sort order
 * - order: "ASC" or "DESC" (default: "ASC") - Sort direction
 * - minimum_posts: integer (default: 1) - Minimum posts required to appear
 * - title: string (default: "") - Optional heading above the list
 * - exclude: comma-separated user_login values to exclude (default: "admin")
 *            Pass exclude="" to show all users including admin
 *
 * @since 1.29.0
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
add_shortcode( 'esop_author_list', 'esop_author_list_shortcode' );

function esop_author_list_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'show_post_count' => 'no',
		'orderby'         => 'name',
		'order'           => 'ASC',
		'minimum_posts'   => 1,
		'title'           => '',
		'exclude'         => 'admin',
	), $atts, 'esop_author_list' );

	// Sanitize attributes
	$show_post_count = strtolower( $atts['show_post_count'] ) === 'yes';
	$orderby         = in_array( $atts['orderby'], array( 'name', 'post_count' ), true ) ? $atts['orderby'] : 'name';
	$order           = strtoupper( $atts['order'] ) === 'DESC' ? 'DESC' : 'ASC';
	$minimum_posts   = absint( $atts['minimum_posts'] );
	$title           = sanitize_text_field( $atts['title'] );

	// Get users with published posts (any role)
	$user_args = array(
		'has_published_posts' => array( 'post' ),
	);

	// For name ordering, use display_name (not 'name' which sorts by user_login)
	if ( $orderby === 'name' ) {
		$user_args['orderby'] = 'display_name';
		$user_args['order']   = $order;
	}

	$users = get_users( $user_args );

	// Exclude users by login name (default: 'admin')
	$exclude_raw = trim( $atts['exclude'] );
	if ( ! empty( $exclude_raw ) ) {
		$exclude_logins = array_map( 'trim', explode( ',', $exclude_raw ) );
		$users = array_values( array_filter( $users, function( $user ) use ( $exclude_logins ) {
			return ! in_array( $user->user_login, $exclude_logins, true );
		} ) );
	}

	// Filter by minimum posts and build author data
	$authors = array();
	foreach ( $users as $user ) {
		$post_count = count_user_posts( $user->ID, 'post', true );
		if ( $post_count >= $minimum_posts ) {
			$authors[] = array(
				'id'           => $user->ID,
				'display_name' => $user->display_name,
				'post_count'   => $post_count,
				'url'          => get_author_posts_url( $user->ID ),
			);
		}
	}

	// Return empty string if no authors meet criteria
	if ( empty( $authors ) ) {
		return '';
	}

	// Sort by post_count if requested (get_users doesn't support this directly)
	if ( $orderby === 'post_count' ) {
		usort( $authors, function( $a, $b ) use ( $order ) {
			if ( $order === 'DESC' ) {
				return $b['post_count'] - $a['post_count'];
			}
			return $a['post_count'] - $b['post_count'];
		} );
	}

	// Build output with output buffering
	ob_start();

	// CSS output - use static flag to only output once per page
	static $css_output = false;
	if ( ! $css_output ) {
		$css_output = true;
		?>
		<style type="text/css">
		/* ESOP Author List Shortcode - Matches blog sidebar styling */
		.esop-author-list-wrapper {
			padding: 0;
			margin: 0;
		}
		.esop-author-list-title {
			font-family: 'Open Sans', Arial, sans-serif;
			font-size: 18px;
			font-weight: 600;
			color: #333333;
			margin: 0 0 15px 0;
			padding: 0;
			line-height: 1.4;
		}
		.esop-author-list {
			list-style: none;
			margin: 0;
			padding: 0;
		}
		.esop-author-list-item {
			margin: 0 0 0.5em 0;
			padding: 0;
			line-height: 1.5;
		}
		.esop-author-list-link {
			font-family: 'Open Sans', Arial, sans-serif;
			color: #666666;
			text-decoration: none;
			font-size: 14px;
			font-weight: 400;
			transition: color 0.2s ease;
		}
		.esop-author-list-link:hover {
			color: #82c0c7;
			text-decoration: none;
		}
		.esop-author-post-count {
			color: #999999;
			font-size: 13px;
			font-weight: 400;
		}
		</style>
		<?php
	}

	?>
	<div class="esop-author-list-wrapper">
		<?php if ( ! empty( $title ) ) : ?>
			<h4 class="esop-author-list-title"><?php echo esc_html( $title ); ?></h4>
		<?php endif; ?>

		<ul class="esop-author-list">
			<?php foreach ( $authors as $author ) : ?>
				<li class="esop-author-list-item">
					<a href="<?php echo esc_url( $author['url'] ); ?>" class="esop-author-list-link">
						<?php echo esc_html( $author['display_name'] ); ?>
						<?php if ( $show_post_count ) : ?>
							<span class="esop-author-post-count">(<?php echo esc_html( $author['post_count'] ); ?>)</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php

	return ob_get_clean();
}

// ============================================================================
// LEAD MANAGEMENT SYSTEM (v1.31.0)
// ============================================================================
//
// Components:
// 1. esop_lead CPT registration
// 2. Helper functions (activity log, pending count, email sending)
// 3. NinjaForms Form 5 submission hook
// 4. Admin menu & lead management page (WP_List_Table)
// 5. Admin action handlers (approve, reject, resend, trash, AJAX notes)
// 6. Dashboard widget & admin bar notification
// 7. Transient invalidation safety net
// 8. Frontend modal CSS & JS
// ============================================================================

// --- 1. Lead CPT Registration ---

add_action( 'init', 'esop_lead_register_post_type' );

function esop_lead_register_post_type() {
	$labels = array(
		'name'               => 'Advisor Leads',
		'singular_name'      => 'Advisor Lead',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Lead',
		'edit_item'          => 'Edit Lead',
		'view_item'          => 'View Lead',
		'all_items'          => 'All Leads',
		'search_items'       => 'Search Leads',
		'not_found'          => 'No leads found.',
		'not_found_in_trash' => 'No leads found in Trash.',
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'query_var'           => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'supports'            => array( 'title' ),
		'exclude_from_search' => true,
	);

	register_post_type( 'esop_lead', $args );
}

// --- 2. Helper Functions ---

/**
 * Add a timestamped entry to a lead's activity log
 *
 * @param int    $lead_id The lead post ID
 * @param string $action  Action identifier (submitted, approved, rejected, etc.)
 * @param string $detail  Human-readable detail string
 */
function esop_lead_add_log_entry( $lead_id, $action, $detail = '' ) {
	$log = get_post_meta( $lead_id, '_esop_lead_activity_log', true );
	if ( ! is_array( $log ) ) {
		$log = array();
	}

	$log[] = array(
		'timestamp' => current_time( 'mysql' ),
		'action'    => sanitize_text_field( $action ),
		'user_id'   => get_current_user_id(),
		'detail'    => sanitize_text_field( $detail ),
	);

	update_post_meta( $lead_id, '_esop_lead_activity_log', $log );
}

/**
 * Get the count of pending leads, with transient caching
 *
 * @return int
 */
function esop_lead_get_pending_count() {
	$count = get_transient( 'esop_lead_pending_count' );
	if ( false !== $count ) {
		return (int) $count;
	}

	$query = new WP_Query( array(
		'post_type'      => 'esop_lead',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'   => '_esop_lead_status',
				'value' => 'pending',
			),
		),
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	) );

	$count = $query->post_count;
	set_transient( 'esop_lead_pending_count', $count, HOUR_IN_SECONDS );
	return $count;
}

/**
 * Invalidate the pending leads count transient
 */
function esop_lead_invalidate_pending_count() {
	delete_transient( 'esop_lead_pending_count' );
}

/**
 * Send the advisor notification email for an approved lead
 *
 * @param int $lead_id The lead post ID
 * @return bool True on success, false on failure
 */
function esop_lead_send_advisor_email( $lead_id ) {
	$advisor_id    = get_post_meta( $lead_id, '_esop_lead_advisor_id', true );
	$visitor_name  = get_post_meta( $lead_id, '_esop_lead_visitor_name', true );
	$visitor_email = get_post_meta( $lead_id, '_esop_lead_visitor_email', true );
	$visitor_phone = get_post_meta( $lead_id, '_esop_lead_visitor_phone', true );
	$message       = get_post_meta( $lead_id, '_esop_lead_message', true );
	$source_url    = get_post_meta( $lead_id, '_esop_lead_source_url', true );

	// Use live advisor email if available, fall back to snapshot
	$advisor_email = '';
	if ( $advisor_id && get_post_type( $advisor_id ) === 'esop_advisor' ) {
		$advisor_email = get_post_meta( $advisor_id, '_esop_advisor_email', true );
	}
	if ( empty( $advisor_email ) ) {
		$advisor_email = get_post_meta( $lead_id, '_esop_lead_advisor_email', true );
	}

	if ( empty( $advisor_email ) || ! is_email( $advisor_email ) ) {
		esop_advisor_debug_log( 'Lead email failed: no valid advisor email', array( 'lead_id' => $lead_id ) );
		esop_lead_add_log_entry( $lead_id, 'email_failed', 'No valid advisor email address found.' );
		return false;
	}

	$phone_display  = ! empty( $visitor_phone ) ? esc_html( $visitor_phone ) : 'Not provided';
	$source_display = ! empty( $source_url ) ? esc_url( $source_url ) : home_url();

	$subject = sprintf( 'New Inquiry from %s — ESOP Marketplace', $visitor_name );

	$body = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">';
	$body .= '<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px 0;">';
	$body .= '<tr><td align="center">';
	$body .= '<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">';

	// Header
	$body .= '<tr><td style="background:#1a3a5c;padding:24px 30px;">';
	$body .= '<h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:600;">ESOP Marketplace</h1>';
	$body .= '</td></tr>';

	// Body
	$body .= '<tr><td style="padding:30px;">';
	$body .= '<p style="margin:0 0 20px;font-size:15px;line-height:1.6;color:#333;">A visitor has expressed interest in connecting with you through your ESOP Marketplace profile.</p>';

	// Visitor Details
	$body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">';
	$body .= '<tr><td colspan="2" style="padding:10px 0 6px;border-bottom:2px solid #1a3a5c;font-size:14px;font-weight:700;color:#1a3a5c;text-transform:uppercase;">Visitor Details</td></tr>';
	$body .= '<tr><td style="padding:8px 0;width:80px;font-size:14px;color:#666;vertical-align:top;">Name:</td><td style="padding:8px 0;font-size:14px;color:#333;">' . esc_html( $visitor_name ) . '</td></tr>';
	$body .= '<tr><td style="padding:8px 0;font-size:14px;color:#666;vertical-align:top;">Email:</td><td style="padding:8px 0;font-size:14px;color:#333;"><a href="mailto:' . esc_attr( sanitize_email( $visitor_email ) ) . '" style="color:#1a3a5c;">' . esc_html( $visitor_email ) . '</a></td></tr>';
	$body .= '<tr><td style="padding:8px 0;font-size:14px;color:#666;vertical-align:top;">Phone:</td><td style="padding:8px 0;font-size:14px;color:#333;">' . $phone_display . '</td></tr>';
	$body .= '</table>';

	// Message
	$body .= '<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">';
	$body .= '<tr><td style="padding:10px 0 6px;border-bottom:2px solid #1a3a5c;font-size:14px;font-weight:700;color:#1a3a5c;text-transform:uppercase;">Message</td></tr>';
	$body .= '<tr><td style="padding:12px 0;font-size:14px;line-height:1.6;color:#333;">' . nl2br( esc_html( $message ) ) . '</td></tr>';
	$body .= '</table>';

	// Footer
	$body .= '<hr style="border:none;border-top:1px solid #e0e0e0;margin:20px 0;">';
	$body .= '<p style="margin:0;font-size:12px;color:#999;line-height:1.5;">This inquiry was submitted through your profile at <a href="' . esc_url( $source_display ) . '" style="color:#1a3a5c;">' . esc_html( $source_display ) . '</a>.</p>';
	$body .= '<p style="margin:6px 0 0;font-size:12px;color:#999;">ESOP Marketplace &mdash; <a href="' . esc_url( home_url() ) . '" style="color:#1a3a5c;">' . esc_html( home_url() ) . '</a></p>';

	$body .= '</td></tr>';
	$body .= '</table>';
	$body .= '</td></tr></table>';
	$body .= '</body></html>';

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ESOP Marketplace <' . get_option( 'admin_email' ) . '>',
		'Reply-To: ' . sanitize_email( $visitor_email ),
	);

	$sent = wp_mail( $advisor_email, $subject, $body, $headers );

	if ( $sent ) {
		update_post_meta( $lead_id, '_esop_lead_email_sent', '1' );
		esop_lead_add_log_entry( $lead_id, 'email_sent', 'Email sent to ' . $advisor_email );
	} else {
		update_post_meta( $lead_id, '_esop_lead_email_sent', '' );
		esop_lead_add_log_entry( $lead_id, 'email_failed', 'Email delivery failed to ' . $advisor_email );
	}

	return $sent;
}

// --- 3. NinjaForms Submission Hook ---

add_action( 'ninja_forms_after_submission', 'esop_lead_capture_submission' );

/**
 * Capture NinjaForms Form 5 submissions and create esop_lead posts
 *
 * IMPORTANT: Field mapping uses field type and key/label patterns to identify values.
 * If NinjaForms Form 5 field structure changes, review the matching logic below.
 *
 * @param array $form_data NinjaForms submission data
 */
function esop_lead_capture_submission( $form_data ) {
	// Log entry for debugging (always log this one — helps diagnose hook issues)
	error_log( 'ESOP Lead capture: hook fired, form_id=' . ( isset( $form_data['form_id'] ) ? $form_data['form_id'] : 'MISSING' ) );

	// Only process Form 5
	$form_id = 0;
	if ( isset( $form_data['form_id'] ) ) {
		$form_id = intval( $form_data['form_id'] );
	} elseif ( isset( $form_data['id'] ) ) {
		$form_id = intval( $form_data['id'] );
	}
	if ( $form_id !== 5 ) {
		return;
	}

	$fields = isset( $form_data['fields'] ) ? $form_data['fields'] : array();

	// Log all field types/keys/labels for diagnostic purposes
	$field_summary = array();
	foreach ( $fields as $fid => $f ) {
		$field_summary[ $fid ] = array(
			'type'  => isset( $f['type'] ) ? $f['type'] : '?',
			'key'   => isset( $f['key'] ) ? $f['key'] : '?',
			'label' => isset( $f['label'] ) ? $f['label'] : '?',
			'value' => isset( $f['value'] ) ? mb_substr( (string) $f['value'], 0, 80 ) : '',
		);
	}
	error_log( 'ESOP Lead capture: fields=' . wp_json_encode( $field_summary ) );

	// Extract field values by matching type and key patterns
	$visitor_name  = '';
	$visitor_email = '';
	$visitor_phone = '';
	$message       = '';
	$advisor_id    = 0;
	$first_textbox = ''; // fallback for name

	foreach ( $fields as $field ) {
		$type  = isset( $field['type'] ) ? $field['type'] : '';
		$key   = isset( $field['key'] ) ? strtolower( $field['key'] ) : '';
		$label = isset( $field['label'] ) ? strtolower( $field['label'] ) : '';
		$value = isset( $field['value'] ) ? $field['value'] : '';

		// Skip empty values and submit buttons
		if ( '' === $value || 'submit' === $type ) {
			continue;
		}

		// Match advisor ID from hidden field
		if ( 'hidden' === $type && ( strpos( $key, 'advisor' ) !== false || strpos( $label, 'advisor' ) !== false ) ) {
			$advisor_id = intval( $value );
			continue;
		}

		// Match name field (firstname type, or textbox/text with "name" in key/label)
		if ( empty( $visitor_name ) && in_array( $type, array( 'firstname', 'textbox', 'text' ), true ) && ( strpos( $key, 'name' ) !== false || strpos( $label, 'name' ) !== false ) ) {
			// Skip "business name" fields — we want the person's name
			if ( strpos( $key, 'business' ) !== false || strpos( $label, 'business' ) !== false || strpos( $key, 'company' ) !== false || strpos( $label, 'company' ) !== false ) {
				continue;
			}
			$visitor_name = $value;
			continue;
		}

		// Track first textbox value as fallback name
		if ( empty( $first_textbox ) && in_array( $type, array( 'firstname', 'textbox', 'text' ), true ) ) {
			$first_textbox = $value;
		}

		// Match email field
		if ( empty( $visitor_email ) && 'email' === $type ) {
			$visitor_email = $value;
			continue;
		}

		// Match phone field
		if ( empty( $visitor_phone ) && ( 'phone' === $type || ( in_array( $type, array( 'textbox', 'text' ), true ) && ( strpos( $key, 'phone' ) !== false || strpos( $label, 'phone' ) !== false ) ) ) ) {
			$visitor_phone = $value;
			continue;
		}

		// Match message field (textarea)
		if ( empty( $message ) && 'textarea' === $type ) {
			$message = $value;
			continue;
		}
	}

	// Name fallback: use first textbox value if no explicit name field matched
	if ( empty( $visitor_name ) && ! empty( $first_textbox ) ) {
		$visitor_name = $first_textbox;
	}

	// Email fallback: search by key/label if no 'email' type field found
	if ( empty( $visitor_email ) ) {
		foreach ( $fields as $field ) {
			$key   = isset( $field['key'] ) ? strtolower( $field['key'] ) : '';
			$label = isset( $field['label'] ) ? strtolower( $field['label'] ) : '';
			$value = isset( $field['value'] ) ? $field['value'] : '';
			if ( ! empty( $value ) && ( strpos( $key, 'email' ) !== false || strpos( $label, 'email' ) !== false ) && is_email( $value ) ) {
				$visitor_email = $value;
				break;
			}
		}
	}

	// Advisor ID: check NF 'extra' data (set via nfRadio JS API)
	if ( empty( $advisor_id ) && ! empty( $form_data['extra']['esop_advisor_id'] ) ) {
		$advisor_id = intval( $form_data['extra']['esop_advisor_id'] );
	}

	// Sanitize all input
	$visitor_name  = sanitize_text_field( $visitor_name );
	$visitor_email = sanitize_email( $visitor_email );
	$visitor_phone = sanitize_text_field( $visitor_phone );
	$message       = sanitize_textarea_field( $message );

	error_log( 'ESOP Lead capture: extracted name=' . $visitor_name . ', email=' . $visitor_email . ', advisor_id=' . $advisor_id );

	// Require at minimum a name and email
	if ( empty( $visitor_name ) || empty( $visitor_email ) ) {
		error_log( 'ESOP Lead capture: FAILED — missing name or email. name="' . $visitor_name . '" email="' . $visitor_email . '"' );
		return;
	}

	// Validate advisor ID
	$advisor_valid   = false;
	$advisor_name    = '';
	$advisor_email   = '';
	$advisor_company = '';

	if ( $advisor_id && get_post_type( $advisor_id ) === 'esop_advisor' && get_post_status( $advisor_id ) === 'publish' ) {
		$advisor_valid   = true;
		$advisor_name    = get_the_title( $advisor_id );
		$advisor_email   = get_post_meta( $advisor_id, '_esop_advisor_email', true );
		$advisor_company = get_post_meta( $advisor_id, '_esop_advisor_company', true );
	} else {
		error_log( 'ESOP Lead capture: advisor ID invalid or not found — advisor_id=' . intval( $advisor_id ) );
	}

	// Source URL: check NF 'extra' data (set via nfRadio JS API)
	$source_url = '';
	if ( ! empty( $form_data['extra']['esop_source_url'] ) ) {
		$source_url = esc_url_raw( $form_data['extra']['esop_source_url'] );
	}

	// IP address
	$ip = '';
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) )[0];
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	}
	$ip = trim( $ip );

	// Create the lead post
	$title = sprintf(
		'Lead: %s → %s (%s)',
		$visitor_name,
		$advisor_valid ? $advisor_name : 'Unknown Advisor',
		current_time( 'Y-m-d' )
	);

	$lead_id = wp_insert_post( array(
		'post_type'   => 'esop_lead',
		'post_status' => 'publish',
		'post_title'  => $title,
	) );

	if ( is_wp_error( $lead_id ) || ! $lead_id ) {
		if ( defined( 'ESOP_ADVISOR_DEBUG' ) && ESOP_ADVISOR_DEBUG ) {
			$err = is_wp_error( $lead_id ) ? $lead_id->get_error_message() : 'empty ID';
			error_log( 'ESOP Lead capture: wp_insert_post failed - ' . $err );
		}
		return;
	}

	// Save all meta fields
	update_post_meta( $lead_id, '_esop_lead_visitor_name', $visitor_name );
	update_post_meta( $lead_id, '_esop_lead_visitor_email', $visitor_email );
	update_post_meta( $lead_id, '_esop_lead_visitor_phone', $visitor_phone );
	update_post_meta( $lead_id, '_esop_lead_message', $message );
	update_post_meta( $lead_id, '_esop_lead_advisor_id', $advisor_id );
	update_post_meta( $lead_id, '_esop_lead_advisor_name', $advisor_valid ? $advisor_name : 'Unknown' );
	update_post_meta( $lead_id, '_esop_lead_advisor_email', $advisor_email );
	update_post_meta( $lead_id, '_esop_lead_status', 'pending' );
	update_post_meta( $lead_id, '_esop_lead_submitted_at', current_time( 'mysql' ) );
	update_post_meta( $lead_id, '_esop_lead_source_url', $source_url );
	update_post_meta( $lead_id, '_esop_lead_ip_address', $ip );
	update_post_meta( $lead_id, '_esop_lead_email_sent', '' );
	update_post_meta( $lead_id, '_esop_lead_admin_notes', '' );

	// Initialize activity log
	$detail = 'Lead submitted';
	if ( ! empty( $source_url ) ) {
		$detail .= ' from ' . $source_url;
	}
	if ( ! $advisor_valid ) {
		$detail .= ' (Warning: invalid advisor ID ' . $advisor_id . ')';
	}
	esop_lead_add_log_entry( $lead_id, 'submitted', $detail );

	// Invalidate pending count transient
	esop_lead_invalidate_pending_count();

	error_log( 'ESOP Lead capture: SUCCESS — lead_id=' . $lead_id . ' advisor_id=' . $advisor_id . ' visitor=' . $visitor_name );
}

// --- 4. Admin Menu Registration ---

add_action( 'admin_menu', 'esop_lead_add_admin_menu' );

function esop_lead_add_admin_menu() {
	$pending = esop_lead_get_pending_count();
	$badge   = $pending > 0 ? ' <span class="awaiting-mod">' . intval( $pending ) . '</span>' : '';

	add_submenu_page(
		'edit.php?post_type=esop_advisor',
		'Advisor Leads',
		'Advisor Leads' . $badge,
		'manage_options',
		'esop-advisor-leads',
		'esop_lead_admin_page'
	);
}

/**
 * Main admin page router — list view or detail view
 */
function esop_lead_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized access.' );
	}

	// Display admin notices
	esop_lead_display_admin_notices();

	if ( isset( $_GET['view'] ) && intval( $_GET['view'] ) > 0 ) {
		esop_lead_detail_view( intval( $_GET['view'] ) );
	} else {
		esop_lead_list_view();
	}
}

/**
 * Display admin notices based on query parameters
 */
function esop_lead_display_admin_notices() {
	if ( ! isset( $_GET['notice'] ) ) {
		return;
	}

	$notice = sanitize_text_field( $_GET['notice'] );
	$count  = isset( $_GET['count'] ) ? intval( $_GET['count'] ) : 1;

	$messages = array(
		'approved'              => array( 'success', 'Lead approved and email sent to advisor.' ),
		'approved_no_email'     => array( 'warning', 'Lead approved. Email delivery may have failed — please verify the advisor received the notification.' ),
		'rejected'              => array( 'success', 'Lead rejected.' ),
		'trashed'               => array( 'success', 'Lead moved to trash.' ),
		'resent'                => array( 'success', 'Email resent to advisor.' ),
		'resend_failed'         => array( 'error', 'Email resend failed. Please try again or verify the advisor email address.' ),
		'already_approved'      => array( 'warning', 'This lead has already been approved.' ),
		'already_rejected'      => array( 'warning', 'This lead has already been rejected.' ),
		'bulk_approved'         => array( 'success', sprintf( '%d lead(s) approved.', $count ) ),
		'bulk_rejected'         => array( 'success', sprintf( '%d lead(s) rejected.', $count ) ),
		'bulk_trashed'          => array( 'success', sprintf( '%d lead(s) moved to trash.', $count ) ),
		'bulk_approved_partial' => array( 'warning', sprintf( '%d lead(s) approved. Some emails may have failed — check individual lead details.', $count ) ),
		'invalid_lead'          => array( 'error', 'Invalid lead.' ),
		'nonce_failed'          => array( 'error', 'Security check failed. Please try again.' ),
	);

	if ( isset( $messages[ $notice ] ) ) {
		$type = $messages[ $notice ][0];
		$msg  = $messages[ $notice ][1];
		echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
	}
}

// --- 5. WP_List_Table ---

/**
 * Lead list view with WP_List_Table
 */
function esop_lead_list_view() {
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	}

	$table = new ESOP_Lead_List_Table();
	$table->prepare_items();

	echo '<div class="wrap">';
	echo '<h1 class="wp-heading-inline">Advisor Leads</h1>';
	echo '<hr class="wp-header-end">';

	// Status filter tabs
	$table->views();

	echo '<form method="get">';
	echo '<input type="hidden" name="post_type" value="esop_advisor">';
	echo '<input type="hidden" name="page" value="esop-advisor-leads">';
	$table->display();
	echo '</form>';
	echo '</div>';
}

/**
 * WP_List_Table extension for advisor leads
 * Only define in admin context — WP_List_Table does not exist on the frontend.
 */
if ( is_admin() ) :

// WP_List_Table is not auto-loaded when MU-plugins parse; load it now.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class ESOP_Lead_List_Table extends WP_List_Table {

	public function __construct() {
		parent::__construct( array(
			'singular' => 'lead',
			'plural'   => 'leads',
			'ajax'     => false,
		) );
	}

	public function get_columns() {
		return array(
			'cb'      => '<input type="checkbox" />',
			'status'  => 'Status',
			'date'    => 'Date',
			'visitor' => 'Visitor',
			'advisor' => 'Advisor',
			'message' => 'Message',
			'actions' => 'Actions',
		);
	}

	public function get_sortable_columns() {
		return array(
			'date'    => array( 'date', true ),
			'visitor' => array( 'visitor', false ),
			'advisor' => array( 'advisor', false ),
			'status'  => array( 'status', false ),
		);
	}

	public function get_views() {
		$current  = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : 'all';
		$base_url = admin_url( 'edit.php?post_type=esop_advisor&page=esop-advisor-leads' );

		// Count leads by status
		$counts = array( 'all' => 0, 'pending' => 0, 'approved' => 0, 'rejected' => 0 );

		foreach ( array( 'pending', 'approved', 'rejected' ) as $s ) {
			$q = new WP_Query( array(
				'post_type'              => 'esop_lead',
				'post_status'            => 'publish',
				'fields'                 => 'ids',
				'meta_query'             => array( array( 'key' => '_esop_lead_status', 'value' => $s ) ),
				'posts_per_page'         => -1,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			) );
			$counts[ $s ] = $q->post_count;
		}
		$counts['all'] = $counts['pending'] + $counts['approved'] + $counts['rejected'];

		$views = array();
		foreach ( array( 'all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected' ) as $key => $label ) {
			$url   = 'all' === $key ? $base_url : add_query_arg( 'status', $key, $base_url );
			$class = $current === $key ? ' class="current"' : '';
			$views[ $key ] = sprintf( '<a href="%s"%s>%s <span class="count">(%d)</span></a>', esc_url( $url ), $class, esc_html( $label ), $counts[ $key ] );
		}
		return $views;
	}

	public function get_bulk_actions() {
		return array(
			'bulk_approve' => 'Approve Selected',
			'bulk_reject'  => 'Reject Selected',
			'bulk_trash'   => 'Move to Trash',
		);
	}

	public function extra_tablenav( $which ) {
		if ( 'top' !== $which ) {
			return;
		}

		// Advisor filter dropdown
		global $wpdb;
		$advisor_ids = $wpdb->get_col(
			"SELECT DISTINCT meta_value FROM {$wpdb->postmeta}
			 WHERE meta_key = '_esop_lead_advisor_id' AND meta_value != '' AND meta_value != '0'
			 ORDER BY meta_value ASC"
		);

		$selected = isset( $_GET['filter_advisor'] ) ? intval( $_GET['filter_advisor'] ) : 0;

		echo '<div class="alignleft actions">';
		echo '<select name="filter_advisor">';
		echo '<option value="">All Advisors</option>';
		foreach ( $advisor_ids as $aid ) {
			$aid = intval( $aid );
			if ( ! $aid ) continue;
			$name = get_the_title( $aid );
			if ( empty( $name ) ) continue;
			printf(
				'<option value="%d"%s>%s</option>',
				$aid,
				selected( $selected, $aid, false ),
				esc_html( $name )
			);
		}
		echo '</select>';
		submit_button( 'Filter', '', 'filter_action', false );
		echo '</div>';
	}

	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$per_page     = 20;
		$current_page = $this->get_pagenum();

		$args = array(
			'post_type'      => 'esop_lead',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $current_page,
			'meta_query'     => array(),
		);

		// Status filter
		$status_filter = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';
		if ( in_array( $status_filter, array( 'pending', 'approved', 'rejected' ), true ) ) {
			$args['meta_query'][] = array(
				'key'   => '_esop_lead_status',
				'value' => $status_filter,
			);
		}

		// Advisor filter
		if ( ! empty( $_GET['filter_advisor'] ) ) {
			$args['meta_query'][] = array(
				'key'   => '_esop_lead_advisor_id',
				'value' => intval( $_GET['filter_advisor'] ),
			);
		}

		// Sorting
		$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'date';
		$order   = isset( $_GET['order'] ) ? strtoupper( sanitize_text_field( $_GET['order'] ) ) : 'DESC';
		if ( ! in_array( $order, array( 'ASC', 'DESC' ), true ) ) {
			$order = 'DESC';
		}

		switch ( $orderby ) {
			case 'visitor':
				$args['meta_key'] = '_esop_lead_visitor_name';
				$args['orderby']  = 'meta_value';
				$args['order']    = $order;
				break;
			case 'advisor':
				$args['meta_key'] = '_esop_lead_advisor_name';
				$args['orderby']  = 'meta_value';
				$args['order']    = $order;
				break;
			case 'status':
				$args['meta_key'] = '_esop_lead_status';
				$args['orderby']  = 'meta_value';
				$args['order']    = $order;
				break;
			default:
				$args['orderby'] = 'date';
				$args['order']   = $order;
				break;
		}

		// Process bulk actions before query (may redirect)
		$this->process_bulk_action();

		$query = new WP_Query( $args );

		$this->items = $query->posts;

		$this->set_pagination_args( array(
			'total_items' => $query->found_posts,
			'per_page'    => $per_page,
			'total_pages' => ceil( $query->found_posts / $per_page ),
		) );
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="lead_ids[]" value="%d" />', $item->ID );
	}

	public function column_status( $item ) {
		$status = get_post_meta( $item->ID, '_esop_lead_status', true );
		$colors = array(
			'pending'  => '#dba617',
			'approved' => '#46b450',
			'rejected' => '#dc3232',
		);
		$color = isset( $colors[ $status ] ) ? $colors[ $status ] : '#999';
		return sprintf(
			'<span class="esop-lead-status-badge" style="display:inline-block;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;color:#fff;background:%s;">%s</span>',
			esc_attr( $color ),
			esc_html( ucfirst( $status ) )
		);
	}

	public function column_date( $item ) {
		$submitted = get_post_meta( $item->ID, '_esop_lead_submitted_at', true );
		if ( $submitted ) {
			return esc_html( date_i18n( 'M j, Y g:i a', strtotime( $submitted ) ) );
		}
		return esc_html( get_the_date( 'M j, Y g:i a', $item ) );
	}

	public function column_visitor( $item ) {
		$name  = get_post_meta( $item->ID, '_esop_lead_visitor_name', true );
		$email = get_post_meta( $item->ID, '_esop_lead_visitor_email', true );

		$output = '<strong>' . esc_html( $name ) . '</strong>';
		if ( $email ) {
			$output .= '<br><a href="mailto:' . esc_attr( sanitize_email( $email ) ) . '">' . esc_html( $email ) . '</a>';
		}

		// Row actions
		$actions  = array();
		$view_url = add_query_arg( array(
			'post_type' => 'esop_advisor',
			'page'      => 'esop-advisor-leads',
			'view'      => $item->ID,
		), admin_url( 'edit.php' ) );
		$actions['view'] = '<a href="' . esc_url( $view_url ) . '">View</a>';

		$status = get_post_meta( $item->ID, '_esop_lead_status', true );
		if ( 'pending' === $status ) {
			$approve_url = wp_nonce_url(
				admin_url( 'admin-post.php?action=esop_lead_approve&lead_id=' . $item->ID ),
				'esop_lead_action_' . $item->ID
			);
			$actions['approve'] = '<a href="' . esc_url( $approve_url ) . '" style="color:#46b450;">Approve</a>';

			$reject_url = wp_nonce_url(
				admin_url( 'admin-post.php?action=esop_lead_reject&lead_id=' . $item->ID ),
				'esop_lead_action_' . $item->ID
			);
			$actions['reject'] = '<a href="' . esc_url( $reject_url ) . '" style="color:#dc3232;">Reject</a>';
		}

		$trash_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=esop_lead_trash&lead_id=' . $item->ID ),
			'esop_lead_action_' . $item->ID
		);
		$actions['trash'] = '<a href="' . esc_url( $trash_url ) . '" class="submitdelete">Trash</a>';

		$output .= $this->row_actions( $actions );
		return $output;
	}

	public function column_advisor( $item ) {
		$advisor_id   = get_post_meta( $item->ID, '_esop_lead_advisor_id', true );
		$advisor_name = get_post_meta( $item->ID, '_esop_lead_advisor_name', true );

		if ( $advisor_id && get_post_type( $advisor_id ) === 'esop_advisor' ) {
			return '<a href="' . esc_url( get_edit_post_link( $advisor_id ) ) . '">' . esc_html( $advisor_name ) . '</a>';
		}
		return esc_html( $advisor_name ?: '—' );
	}

	public function column_message( $item ) {
		$msg = get_post_meta( $item->ID, '_esop_lead_message', true );
		if ( strlen( $msg ) > 80 ) {
			return esc_html( substr( $msg, 0, 80 ) ) . '&hellip;';
		}
		return esc_html( $msg );
	}

	public function column_actions( $item ) {
		$view_url = add_query_arg( array(
			'post_type' => 'esop_advisor',
			'page'      => 'esop-advisor-leads',
			'view'      => $item->ID,
		), admin_url( 'edit.php' ) );
		return '<a href="' . esc_url( $view_url ) . '" class="button button-small">View</a>';
	}

	public function column_default( $item, $column_name ) {
		return '—';
	}

	public function process_bulk_action() {
		$action = $this->current_action();
		if ( ! $action || ! in_array( $action, array( 'bulk_approve', 'bulk_reject', 'bulk_trash' ), true ) ) {
			return;
		}

		check_admin_referer( 'bulk-leads' );

		$lead_ids = isset( $_GET['lead_ids'] ) ? array_map( 'intval', (array) $_GET['lead_ids'] ) : array();
		if ( empty( $lead_ids ) ) {
			return;
		}

		$count        = 0;
		$email_failed = 0;

		foreach ( $lead_ids as $lead_id ) {
			if ( get_post_type( $lead_id ) !== 'esop_lead' ) {
				continue;
			}

			$current_status = get_post_meta( $lead_id, '_esop_lead_status', true );

			switch ( $action ) {
				case 'bulk_approve':
					if ( 'pending' !== $current_status ) break;
					update_post_meta( $lead_id, '_esop_lead_status', 'approved' );
					update_post_meta( $lead_id, '_esop_lead_reviewed_at', current_time( 'mysql' ) );
					update_post_meta( $lead_id, '_esop_lead_reviewed_by', get_current_user_id() );
					esop_lead_add_log_entry( $lead_id, 'approved', 'Approved via bulk action' );
					$sent = esop_lead_send_advisor_email( $lead_id );
					if ( ! $sent ) {
						$email_failed++;
					}
					$count++;
					break;

				case 'bulk_reject':
					if ( 'pending' !== $current_status ) break;
					update_post_meta( $lead_id, '_esop_lead_status', 'rejected' );
					update_post_meta( $lead_id, '_esop_lead_reviewed_at', current_time( 'mysql' ) );
					update_post_meta( $lead_id, '_esop_lead_reviewed_by', get_current_user_id() );
					esop_lead_add_log_entry( $lead_id, 'rejected', 'Rejected via bulk action' );
					$count++;
					break;

				case 'bulk_trash':
					esop_lead_add_log_entry( $lead_id, 'trashed', 'Trashed via bulk action' );
					wp_trash_post( $lead_id );
					$count++;
					break;
			}
		}

		esop_lead_invalidate_pending_count();

		$notice_map = array(
			'bulk_approve' => 'bulk_approved',
			'bulk_reject'  => 'bulk_rejected',
			'bulk_trash'   => 'bulk_trashed',
		);
		$notice = isset( $notice_map[ $action ] ) ? $notice_map[ $action ] : 'bulk_approved';
		if ( 'bulk_approve' === $action && $email_failed > 0 ) {
			$notice = 'bulk_approved_partial';
		}

		wp_redirect( add_query_arg( array(
			'post_type' => 'esop_advisor',
			'page'      => 'esop-advisor-leads',
			'notice'    => $notice,
			'count'     => $count,
		), admin_url( 'edit.php' ) ) );
		exit;
	}

	public function no_items() {
		echo 'No leads found.';
	}
}

endif; // is_admin() — end WP_List_Table class guard

// --- 6. Detail View ---

/**
 * Render the single lead detail view
 *
 * @param int $lead_id
 */
function esop_lead_detail_view( $lead_id ) {
	$lead = get_post( $lead_id );
	if ( ! $lead || 'esop_lead' !== $lead->post_type ) {
		echo '<div class="wrap"><div class="notice notice-error"><p>Lead not found.</p></div></div>';
		return;
	}

	// Meta values
	$visitor_name  = get_post_meta( $lead_id, '_esop_lead_visitor_name', true );
	$visitor_email = get_post_meta( $lead_id, '_esop_lead_visitor_email', true );
	$visitor_phone = get_post_meta( $lead_id, '_esop_lead_visitor_phone', true );
	$message       = get_post_meta( $lead_id, '_esop_lead_message', true );
	$advisor_id    = get_post_meta( $lead_id, '_esop_lead_advisor_id', true );
	$advisor_name  = get_post_meta( $lead_id, '_esop_lead_advisor_name', true );
	$advisor_email = get_post_meta( $lead_id, '_esop_lead_advisor_email', true );
	$status        = get_post_meta( $lead_id, '_esop_lead_status', true );
	$submitted_at  = get_post_meta( $lead_id, '_esop_lead_submitted_at', true );
	$reviewed_at   = get_post_meta( $lead_id, '_esop_lead_reviewed_at', true );
	$source_url    = get_post_meta( $lead_id, '_esop_lead_source_url', true );
	$ip_address    = get_post_meta( $lead_id, '_esop_lead_ip_address', true );
	$admin_notes   = get_post_meta( $lead_id, '_esop_lead_admin_notes', true );
	$email_sent    = get_post_meta( $lead_id, '_esop_lead_email_sent', true );
	$activity_log  = get_post_meta( $lead_id, '_esop_lead_activity_log', true );

	$advisor_company = '';
	if ( $advisor_id && get_post_type( $advisor_id ) === 'esop_advisor' ) {
		$advisor_company = get_post_meta( $advisor_id, '_esop_advisor_company', true );
	}

	// Status badge
	$status_colors = array( 'pending' => '#dba617', 'approved' => '#46b450', 'rejected' => '#dc3232' );
	$status_color  = isset( $status_colors[ $status ] ) ? $status_colors[ $status ] : '#999';

	$back_url = admin_url( 'edit.php?post_type=esop_advisor&page=esop-advisor-leads' );

	echo '<div class="wrap">';
	echo '<h1>';
	echo '<a href="' . esc_url( $back_url ) . '" style="text-decoration:none;">&larr;</a> ';
	echo esc_html( $lead->post_title );
	echo '</h1>';

	echo '<p>';
	echo '<span style="display:inline-block;padding:4px 14px;border-radius:12px;font-size:13px;font-weight:600;color:#fff;background:' . esc_attr( $status_color ) . ';">' . esc_html( ucfirst( $status ) ) . '</span>';
	echo ' &nbsp; Submitted: <strong>' . esc_html( $submitted_at ? date_i18n( 'F j, Y g:i a', strtotime( $submitted_at ) ) : '—' ) . '</strong>';
	if ( $reviewed_at ) {
		echo ' &nbsp; Reviewed: <strong>' . esc_html( date_i18n( 'F j, Y g:i a', strtotime( $reviewed_at ) ) ) . '</strong>';
	}
	echo '</p>';

	// Visitor Information
	echo '<div class="postbox" style="margin-top:20px;">';
	echo '<h2 class="hndle" style="padding:10px 15px;margin:0;cursor:default;">Visitor Information</h2>';
	echo '<div class="inside" style="padding:10px 15px;">';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>Name</th><td>' . esc_html( $visitor_name ) . '</td></tr>';
	echo '<tr><th>Email</th><td><a href="mailto:' . esc_attr( sanitize_email( $visitor_email ) ) . '">' . esc_html( $visitor_email ) . '</a></td></tr>';
	if ( $visitor_phone ) {
		echo '<tr><th>Phone</th><td><a href="tel:' . esc_attr( $visitor_phone ) . '">' . esc_html( $visitor_phone ) . '</a></td></tr>';
	}
	if ( $source_url ) {
		echo '<tr><th>Source Page</th><td><a href="' . esc_url( $source_url ) . '" target="_blank">' . esc_html( $source_url ) . '</a></td></tr>';
	}
	if ( $ip_address ) {
		echo '<tr><th>IP Address</th><td>' . esc_html( $ip_address ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '</div></div>';

	// Message
	echo '<div class="postbox">';
	echo '<h2 class="hndle" style="padding:10px 15px;margin:0;cursor:default;">Message</h2>';
	echo '<div class="inside" style="padding:10px 15px;">';
	echo '<div style="background:#f9f9f9;padding:15px;border-radius:4px;line-height:1.6;">' . nl2br( esc_html( $message ) ) . '</div>';
	echo '</div></div>';

	// Target Advisor
	echo '<div class="postbox">';
	echo '<h2 class="hndle" style="padding:10px 15px;margin:0;cursor:default;">Target Advisor</h2>';
	echo '<div class="inside" style="padding:10px 15px;">';
	echo '<table class="form-table"><tbody>';
	if ( $advisor_id && get_post_type( $advisor_id ) === 'esop_advisor' ) {
		echo '<tr><th>Name</th><td><a href="' . esc_url( get_edit_post_link( $advisor_id ) ) . '">' . esc_html( $advisor_name ) . '</a></td></tr>';
	} else {
		echo '<tr><th>Name</th><td>' . esc_html( $advisor_name ) . '</td></tr>';
	}
	echo '<tr><th>Email</th><td>' . esc_html( $advisor_email ) . '</td></tr>';
	if ( $advisor_company ) {
		echo '<tr><th>Company</th><td>' . esc_html( $advisor_company ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '</div></div>';

	// Internal Notes
	echo '<div class="postbox">';
	echo '<h2 class="hndle" style="padding:10px 15px;margin:0;cursor:default;">Internal Notes</h2>';
	echo '<div class="inside" style="padding:10px 15px;">';
	echo '<textarea id="esop-lead-notes" rows="5" style="width:100%;" placeholder="Add internal notes...">' . esc_textarea( $admin_notes ) . '</textarea>';
	echo '<p>';
	echo '<button type="button" id="esop-lead-save-notes" class="button button-secondary" data-lead-id="' . intval( $lead_id ) . '" data-nonce="' . esc_attr( wp_create_nonce( 'esop_lead_notes_nonce' ) ) . '">Save Notes</button>';
	echo '<span id="esop-lead-notes-status" style="margin-left:10px;color:#46b450;display:none;">Saved!</span>';
	echo '</p>';
	echo '</div></div>';

	// Action Buttons
	echo '<div class="postbox">';
	echo '<h2 class="hndle" style="padding:10px 15px;margin:0;cursor:default;">Actions</h2>';
	echo '<div class="inside" style="padding:10px 15px;">';

	if ( 'pending' === $status ) {
		$approve_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=esop_lead_approve&lead_id=' . $lead_id ),
			'esop_lead_action_' . $lead_id
		);
		$live_advisor_email = $advisor_id ? get_post_meta( $advisor_id, '_esop_advisor_email', true ) : $advisor_email;
		echo '<a href="' . esc_url( $approve_url ) . '" class="button button-primary" onclick="return confirm(\'Send this lead to ' . esc_js( $advisor_name ) . ' at ' . esc_js( $live_advisor_email ) . '?\');">Approve &amp; Send to Advisor</a> ';

		$reject_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=esop_lead_reject&lead_id=' . $lead_id ),
			'esop_lead_action_' . $lead_id
		);
		echo '<a href="' . esc_url( $reject_url ) . '" class="button" onclick="return confirm(\'Reject this lead?\');" style="color:#dc3232;border-color:#dc3232;">Reject</a> ';
	}

	if ( 'approved' === $status && '1' !== $email_sent ) {
		$resend_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=esop_lead_resend&lead_id=' . $lead_id ),
			'esop_lead_action_' . $lead_id
		);
		$live_advisor_email = $advisor_id ? get_post_meta( $advisor_id, '_esop_advisor_email', true ) : $advisor_email;
		echo '<a href="' . esc_url( $resend_url ) . '" class="button button-primary" onclick="return confirm(\'Resend this lead to ' . esc_js( $advisor_name ) . ' at ' . esc_js( $live_advisor_email ) . '?\');">Resend Email</a> ';
	}

	$trash_url = wp_nonce_url(
		admin_url( 'admin-post.php?action=esop_lead_trash&lead_id=' . $lead_id ),
		'esop_lead_action_' . $lead_id
	);
	echo '<a href="' . esc_url( $trash_url ) . '" class="button" onclick="return confirm(\'Move this lead to trash?\');" style="color:#dc3232;">Move to Trash</a> ';

	echo '&nbsp; <a href="' . esc_url( $back_url ) . '" class="button">&larr; Back to All Leads</a>';

	echo '</div></div>';

	// Activity Log
	echo '<div class="postbox">';
	echo '<h2 class="hndle" style="padding:10px 15px;margin:0;cursor:default;">Activity Log</h2>';
	echo '<div class="inside" style="padding:10px 15px;">';

	if ( is_array( $activity_log ) && ! empty( $activity_log ) ) {
		echo '<ul style="margin:0;list-style:none;padding:0;">';
		foreach ( array_reverse( $activity_log ) as $entry ) {
			$ts     = isset( $entry['timestamp'] ) ? date_i18n( 'M j, Y g:i a', strtotime( $entry['timestamp'] ) ) : '';
			$detail = isset( $entry['detail'] ) ? $entry['detail'] : $entry['action'];
			$user   = '';
			if ( ! empty( $entry['user_id'] ) ) {
				$u = get_userdata( $entry['user_id'] );
				if ( $u ) {
					$user = ' by ' . $u->display_name;
				}
			}
			echo '<li style="padding:6px 0;border-bottom:1px solid #f0f0f0;">';
			echo '<span style="color:#999;font-size:12px;">' . esc_html( $ts ) . '</span> &mdash; ';
			echo esc_html( ucfirst( str_replace( '_', ' ', $entry['action'] ) ) );
			if ( $user ) {
				echo esc_html( $user );
			}
			if ( $detail && $detail !== $entry['action'] ) {
				echo '<br><span style="color:#666;font-size:12px;margin-left:10px;">' . esc_html( $detail ) . '</span>';
			}
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<p style="color:#999;">No activity recorded.</p>';
	}

	echo '</div></div>';
	echo '</div>'; // .wrap

	// Admin JS for notes AJAX save
	?>
	<script type="text/javascript">
	(function(){
		var saveBtn = document.getElementById('esop-lead-save-notes');
		if (!saveBtn) return;
		saveBtn.addEventListener('click', function(){
			var notes = document.getElementById('esop-lead-notes').value;
			var leadId = saveBtn.getAttribute('data-lead-id');
			var nonce = saveBtn.getAttribute('data-nonce');
			var statusEl = document.getElementById('esop-lead-notes-status');
			saveBtn.disabled = true;
			saveBtn.textContent = 'Saving...';

			var xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxurl);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onload = function(){
				saveBtn.disabled = false;
				saveBtn.textContent = 'Save Notes';
				if (xhr.status === 200) {
					statusEl.style.display = 'inline';
					setTimeout(function(){ statusEl.style.display = 'none'; }, 2000);
				}
			};
			xhr.onerror = function(){
				saveBtn.disabled = false;
				saveBtn.textContent = 'Save Notes';
				alert('Save failed. Please try again.');
			};
			xhr.send('action=esop_lead_save_notes&lead_id=' + encodeURIComponent(leadId) + '&notes=' + encodeURIComponent(notes) + '&nonce=' + encodeURIComponent(nonce));
		});
	})();
	</script>
	<?php
}

// --- 7. Admin Action Handlers ---

add_action( 'admin_post_esop_lead_approve', 'esop_lead_handle_approve' );

function esop_lead_handle_approve() {
	$lead_id = isset( $_GET['lead_id'] ) ? intval( $_GET['lead_id'] ) : 0;

	if ( ! $lead_id || get_post_type( $lead_id ) !== 'esop_lead' ) {
		wp_redirect( add_query_arg( array( 'post_type' => 'esop_advisor', 'page' => 'esop-advisor-leads', 'notice' => 'invalid_lead' ), admin_url( 'edit.php' ) ) );
		exit;
	}

	check_admin_referer( 'esop_lead_action_' . $lead_id );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized.' );
	}

	$current_status = get_post_meta( $lead_id, '_esop_lead_status', true );
	if ( 'pending' !== $current_status ) {
		wp_redirect( add_query_arg( array(
			'post_type' => 'esop_advisor',
			'page'      => 'esop-advisor-leads',
			'view'      => $lead_id,
			'notice'    => 'already_approved',
		), admin_url( 'edit.php' ) ) );
		exit;
	}

	update_post_meta( $lead_id, '_esop_lead_status', 'approved' );
	update_post_meta( $lead_id, '_esop_lead_reviewed_at', current_time( 'mysql' ) );
	update_post_meta( $lead_id, '_esop_lead_reviewed_by', get_current_user_id() );
	esop_lead_add_log_entry( $lead_id, 'approved', 'Approved by admin' );

	$sent = esop_lead_send_advisor_email( $lead_id );

	esop_lead_invalidate_pending_count();

	$notice = $sent ? 'approved' : 'approved_no_email';

	wp_redirect( add_query_arg( array(
		'post_type' => 'esop_advisor',
		'page'      => 'esop-advisor-leads',
		'view'      => $lead_id,
		'notice'    => $notice,
	), admin_url( 'edit.php' ) ) );
	exit;
}

add_action( 'admin_post_esop_lead_reject', 'esop_lead_handle_reject' );

function esop_lead_handle_reject() {
	$lead_id = isset( $_GET['lead_id'] ) ? intval( $_GET['lead_id'] ) : 0;

	if ( ! $lead_id || get_post_type( $lead_id ) !== 'esop_lead' ) {
		wp_redirect( add_query_arg( array( 'post_type' => 'esop_advisor', 'page' => 'esop-advisor-leads', 'notice' => 'invalid_lead' ), admin_url( 'edit.php' ) ) );
		exit;
	}

	check_admin_referer( 'esop_lead_action_' . $lead_id );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized.' );
	}

	$current_status = get_post_meta( $lead_id, '_esop_lead_status', true );
	if ( 'pending' !== $current_status ) {
		wp_redirect( add_query_arg( array(
			'post_type' => 'esop_advisor',
			'page'      => 'esop-advisor-leads',
			'view'      => $lead_id,
			'notice'    => 'already_rejected',
		), admin_url( 'edit.php' ) ) );
		exit;
	}

	update_post_meta( $lead_id, '_esop_lead_status', 'rejected' );
	update_post_meta( $lead_id, '_esop_lead_reviewed_at', current_time( 'mysql' ) );
	update_post_meta( $lead_id, '_esop_lead_reviewed_by', get_current_user_id() );
	esop_lead_add_log_entry( $lead_id, 'rejected', 'Rejected by admin' );

	esop_lead_invalidate_pending_count();

	wp_redirect( add_query_arg( array(
		'post_type' => 'esop_advisor',
		'page'      => 'esop-advisor-leads',
		'view'      => $lead_id,
		'notice'    => 'rejected',
	), admin_url( 'edit.php' ) ) );
	exit;
}

add_action( 'admin_post_esop_lead_resend', 'esop_lead_handle_resend' );

function esop_lead_handle_resend() {
	$lead_id = isset( $_GET['lead_id'] ) ? intval( $_GET['lead_id'] ) : 0;

	if ( ! $lead_id || get_post_type( $lead_id ) !== 'esop_lead' ) {
		wp_redirect( add_query_arg( array( 'post_type' => 'esop_advisor', 'page' => 'esop-advisor-leads', 'notice' => 'invalid_lead' ), admin_url( 'edit.php' ) ) );
		exit;
	}

	check_admin_referer( 'esop_lead_action_' . $lead_id );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized.' );
	}

	$current_status = get_post_meta( $lead_id, '_esop_lead_status', true );
	$email_sent     = get_post_meta( $lead_id, '_esop_lead_email_sent', true );

	if ( 'approved' !== $current_status || '1' === $email_sent ) {
		wp_redirect( add_query_arg( array(
			'post_type' => 'esop_advisor',
			'page'      => 'esop-advisor-leads',
			'view'      => $lead_id,
			'notice'    => 'already_approved',
		), admin_url( 'edit.php' ) ) );
		exit;
	}

	$sent   = esop_lead_send_advisor_email( $lead_id );
	$notice = $sent ? 'resent' : 'resend_failed';

	wp_redirect( add_query_arg( array(
		'post_type' => 'esop_advisor',
		'page'      => 'esop-advisor-leads',
		'view'      => $lead_id,
		'notice'    => $notice,
	), admin_url( 'edit.php' ) ) );
	exit;
}

add_action( 'admin_post_esop_lead_trash', 'esop_lead_handle_trash' );

function esop_lead_handle_trash() {
	$lead_id = isset( $_GET['lead_id'] ) ? intval( $_GET['lead_id'] ) : 0;

	if ( ! $lead_id || get_post_type( $lead_id ) !== 'esop_lead' ) {
		wp_redirect( add_query_arg( array( 'post_type' => 'esop_advisor', 'page' => 'esop-advisor-leads', 'notice' => 'invalid_lead' ), admin_url( 'edit.php' ) ) );
		exit;
	}

	check_admin_referer( 'esop_lead_action_' . $lead_id );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized.' );
	}

	esop_lead_add_log_entry( $lead_id, 'trashed', 'Moved to trash by admin' );
	wp_trash_post( $lead_id );
	esop_lead_invalidate_pending_count();

	wp_redirect( add_query_arg( array(
		'post_type' => 'esop_advisor',
		'page'      => 'esop-advisor-leads',
		'notice'    => 'trashed',
	), admin_url( 'edit.php' ) ) );
	exit;
}

// AJAX handler for saving notes
add_action( 'wp_ajax_esop_lead_save_notes', 'esop_lead_ajax_save_notes' );

function esop_lead_ajax_save_notes() {
	check_ajax_referer( 'esop_lead_notes_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Unauthorized.' );
	}

	$lead_id = isset( $_POST['lead_id'] ) ? intval( $_POST['lead_id'] ) : 0;
	if ( ! $lead_id || get_post_type( $lead_id ) !== 'esop_lead' ) {
		wp_send_json_error( 'Invalid lead.' );
	}

	$notes = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';
	update_post_meta( $lead_id, '_esop_lead_admin_notes', $notes );
	esop_lead_add_log_entry( $lead_id, 'notes_updated', 'Internal notes updated' );

	wp_send_json_success();
}

// --- 8. Transient Invalidation Safety Net ---

add_action( 'wp_trash_post', 'esop_lead_invalidate_count_on_trash' );
add_action( 'before_delete_post', 'esop_lead_invalidate_count_on_trash' );

function esop_lead_invalidate_count_on_trash( $post_id ) {
	if ( get_post_type( $post_id ) === 'esop_lead' ) {
		esop_lead_invalidate_pending_count();
	}
}

// --- 9. Dashboard Widget ---

add_action( 'wp_dashboard_setup', 'esop_lead_register_dashboard_widget' );

function esop_lead_register_dashboard_widget() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	wp_add_dashboard_widget(
		'esop_lead_dashboard_widget',
		'Advisor Leads',
		'esop_lead_dashboard_widget_render'
	);
}

function esop_lead_dashboard_widget_render() {
	$pending_count = esop_lead_get_pending_count();
	$leads_url     = admin_url( 'edit.php?post_type=esop_advisor&page=esop-advisor-leads' );
	$pending_url   = add_query_arg( 'status', 'pending', $leads_url );

	echo '<div style="margin-bottom:12px;">';
	echo '<span style="display:inline-block;padding:6px 16px;border-radius:16px;font-size:14px;font-weight:700;';
	if ( $pending_count > 0 ) {
		echo 'background:#fff3cd;color:#856404;border:1px solid #ffc107;">';
		echo intval( $pending_count ) . ' pending lead' . ( $pending_count !== 1 ? 's' : '' );
	} else {
		echo 'background:#d4edda;color:#155724;border:1px solid #28a745;">';
		echo 'No pending leads';
	}
	echo '</span></div>';

	// Show 5 most recent pending leads
	$recent = new WP_Query( array(
		'post_type'      => 'esop_lead',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		'meta_query'     => array(
			array( 'key' => '_esop_lead_status', 'value' => 'pending' ),
		),
		'orderby' => 'date',
		'order'   => 'DESC',
	) );

	if ( $recent->have_posts() ) {
		echo '<ul style="margin:0;padding:0;list-style:none;">';
		while ( $recent->have_posts() ) {
			$recent->the_post();
			$lid = get_the_ID();
			$vn  = get_post_meta( $lid, '_esop_lead_visitor_name', true );
			$an  = get_post_meta( $lid, '_esop_lead_advisor_name', true );
			$dt  = get_post_meta( $lid, '_esop_lead_submitted_at', true );
			$view_url = add_query_arg( array(
				'post_type' => 'esop_advisor',
				'page'      => 'esop-advisor-leads',
				'view'      => $lid,
			), admin_url( 'edit.php' ) );

			echo '<li style="padding:6px 0;border-bottom:1px solid #f0f0f0;">';
			echo '<a href="' . esc_url( $view_url ) . '" style="text-decoration:none;">';
			echo '<strong>' . esc_html( $vn ) . '</strong> &rarr; ' . esc_html( $an );
			echo '</a>';
			if ( $dt ) {
				echo '<br><span style="color:#999;font-size:11px;">' . esc_html( date_i18n( 'M j, g:i a', strtotime( $dt ) ) ) . '</span>';
			}
			echo '</li>';
		}
		echo '</ul>';
		wp_reset_postdata();
	}

	echo '<p style="margin:12px 0 0;"><a href="' . esc_url( $leads_url ) . '">View All Leads &rarr;</a></p>';
}

// --- 10. Admin Bar Notification ---

add_action( 'admin_bar_menu', 'esop_lead_admin_bar_node', 999 );

function esop_lead_admin_bar_node( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$pending = esop_lead_get_pending_count();
	if ( $pending < 1 ) {
		return;
	}

	$wp_admin_bar->add_node( array(
		'id'    => 'esop-leads-pending',
		'title' => sprintf(
			'Leads <span class="esop-lead-admin-bar-count" style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;border-radius:9px;background:#d63638;color:#fff;font-size:11px;font-weight:600;padding:0 6px;margin-left:4px;vertical-align:middle;">%d</span>',
			$pending
		),
		'href'  => admin_url( 'edit.php?post_type=esop_advisor&page=esop-advisor-leads&status=pending' ),
	) );
}

// --- 11. Frontend Modal CSS ---

add_action( 'wp_head', 'esop_lead_modal_css', 30 );

function esop_lead_modal_css() {
	if ( ! is_singular( 'esop_advisor' ) ) {
		return;
	}
	?>
	<style id="esop-lead-modal-css">
	.esop-lead-modal{position:fixed;top:0;left:0;width:100%;height:100%;z-index:2147483647;display:none;align-items:center;justify-content:center;background:rgba(0,0,0,0.7)}
	.esop-lead-modal[style*="display: flex"],.esop-lead-modal[style*="display:flex"]{display:flex!important}
	.esop-lead-modal__container{position:relative;background:#fff;border-radius:12px;max-width:600px;width:calc(100% - 40px);max-height:85vh;overflow-y:auto;overscroll-behavior:contain;-webkit-overflow-scrolling:touch;padding:30px;box-shadow:0 20px 60px rgba(0,0,0,0.4)}
	.esop-lead-modal__close{position:sticky;top:0;float:right;background:#fff;border:none;font-size:28px;line-height:1;color:#666;cursor:pointer;padding:4px 8px;z-index:10;transition:color .2s;border-radius:4px}
	.esop-lead-modal__close:hover{color:#333;background:#f0f0f0}
	.esop-lead-modal__title{margin:0 0 20px;font-size:22px;font-weight:600;color:#1a3a5c;padding-right:30px;clear:right}
	.esop-lead-modal__form-wrap{margin:0}
	.esop-lead-modal__form-wrap .nf-form-cont{margin:0!important;padding:0!important}
	.esop-lead-modal__form-wrap .nf-form-title{display:none}
	.esop-lead-modal__form-wrap .nf-response-msg{padding:20px 0}
	@media(max-width:480px){
		.esop-lead-modal__container{padding:20px 16px;width:calc(100% - 20px);border-radius:8px}
		.esop-lead-modal__title{font-size:18px}
	}
	</style>
	<?php
}

// --- 12. Frontend Modal JS ---

add_action( 'wp_footer', 'esop_lead_modal_js', 100 );

function esop_lead_modal_js() {
	if ( ! is_singular( 'esop_advisor' ) ) {
		return;
	}
	?>
	<script type="text/javascript">
	(function(){
		'use strict';

		// Only init if contact button exists
		var buttons = document.querySelectorAll('.esop-contact-button[data-modal-target]');
		if (!buttons.length) return;

		var modal = null;
		var focusableEls = [];
		var lastFocused = null;

		function openModal(btn) {
			var modalId = btn.getAttribute('data-modal-target');
			modal = document.getElementById(modalId);
			if (!modal) return;

			lastFocused = document.activeElement;

			// Move modal to document.body to escape Divi stacking contexts
			if (modal.parentNode !== document.body) {
				document.body.appendChild(modal);
			}

			// Set advisor data for NF submission via nfRadio
			injectAdvisorData(btn);

			modal.style.display = 'flex';
			document.body.style.overflow = 'hidden';
			document.documentElement.style.overflow = 'hidden';

			// Update title with advisor name
			var title = modal.querySelector('.esop-lead-modal__title');
			var advisorName = btn.getAttribute('data-advisor-name');
			if (title && advisorName) {
				title.textContent = 'Contact ' + advisorName;
			}

			// Set up focus trap
			focusableEls = modal.querySelectorAll('a[href], button:not([disabled]), input:not([type="hidden"]):not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])');
			if (focusableEls.length) {
				focusableEls[0].focus();
			}
		}

		function closeModal() {
			if (!modal) return;
			modal.style.display = 'none';
			document.body.style.overflow = '';
			document.documentElement.style.overflow = '';
			if (lastFocused) lastFocused.focus();
			modal = null;
		}

		// Store the current advisor ID for NF submission injection
		var currentAdvisorId = null;

		function injectAdvisorData(btn) {
			currentAdvisorId = btn.getAttribute('data-advisor-id');

			// NF 3.x uses Backbone models — DOM injection does NOT work.
			// Use nfRadio 'before:submit' to inject advisor ID and source URL into form_data['extra'].
			if (typeof nfRadio !== 'undefined' && !window._esopNfHooked) {
				window._esopNfHooked = true;
				var channel = nfRadio.channel('forms');
				channel.on('before:submit', function(model) {
					var extra = model.get('extra') || {};
					extra.esop_advisor_id = currentAdvisorId || '';
					extra.esop_source_url = window.location.href;
					model.set('extra', extra);
				});
			}
		}

		// Button click handlers
		for (var i = 0; i < buttons.length; i++) {
			buttons[i].addEventListener('click', function(e) {
				e.preventDefault();
				openModal(this);
			});
		}

		// Close handlers — only when clicking the backdrop (modal div itself) or the X button
		document.addEventListener('click', function(e) {
			if (!modal) return;
			// Close on X button click
			if (e.target.classList.contains('esop-lead-modal__close')) {
				closeModal();
				return;
			}
			// Close on backdrop click (e.target === modal means the click was on the
			// dark background itself, NOT on any child like the form container)
			if (e.target === modal) {
				closeModal();
			}
		});

		// Escape key
		document.addEventListener('keydown', function(e) {
			if (modal && e.key === 'Escape') {
				closeModal();
			}
			// Focus trap
			if (modal && e.key === 'Tab' && focusableEls.length) {
				var first = focusableEls[0];
				var last = focusableEls[focusableEls.length - 1];
				if (e.shiftKey) {
					if (document.activeElement === first) {
						e.preventDefault();
						last.focus();
					}
				} else {
					if (document.activeElement === last) {
						e.preventDefault();
						first.focus();
					}
				}
			}
		});

		// NinjaForms success handling — auto-close after successful submission
		// Uses NF's nfRadio Backbone channel (NOT MutationObserver, which fires
		// on every DOM change and falsely detects the pre-existing .nf-response-msg container).
		if (typeof nfRadio !== 'undefined') {
			nfRadio.channel('forms').on('submit:response', function() {
				setTimeout(function() {
					if (modal) closeModal();
				}, 5000);
			});
		}
	})();
	</script>
	<?php
}
