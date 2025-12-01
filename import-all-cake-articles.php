<?php
/**
 * CAKE All Articles Importer
 * Imports Articles, Case Studies, and Ratings from one CSV file
 * 
 * INSTRUCTIONS:
 * 1. Run the SQL query to export all articles to CSV
 * 2. Upload CSV file as: cake_all_articles_export.csv
 * 3. Upload this file to WordPress root
 * 4. Run: https://new.esopmarketplace.com/import-all-cake-articles.php
 * 5. Delete this file when done
 */

// Load WordPress
require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');

// Configuration
$csv_file = 'cake_all_articles_export.csv';
$default_author_id = 1;
$post_status = 'draft'; // Change to 'publish' if you want posts live immediately

echo "<html><head><title>CAKE Import - All Articles</title></head><body>";
echo "<h1>CAKE Complete Migration</h1>";
echo "<pre>\n";

// Verify CSV exists
if (!file_exists($csv_file)) {
    die("Error: CSV file not found: " . $csv_file . "\n\nPlease upload the CSV file to the WordPress root directory.\n");
}

echo "CSV File: " . $csv_file . "\n";
echo "Post Status: " . $post_status . "\n\n";

// ============================================
// STEP 1: Create Categories
// ============================================
echo "=================================\n";
echo "Creating WordPress categories...\n";
echo "=================================\n";

$categories = array('Articles', 'Case Studies', 'Ratings');
$category_ids = array();

foreach ($categories as $cat_name) {
    $cat_id = get_cat_ID($cat_name);
    if ($cat_id == 0) {
        $cat_id = wp_insert_category(array('cat_name' => $cat_name));
        echo "✓ Created: " . $cat_name . " (ID: " . $cat_id . ")\n";
    } else {
        echo "✓ Exists: " . $cat_name . " (ID: " . $cat_id . ")\n";
    }
    $category_ids[$cat_name] = $cat_id;
}

echo "\n";

// ============================================
// STEP 2: Open and Parse CSV
// ============================================
$handle = fopen($csv_file, 'r');
if (!$handle) {
    die("Error: Could not open CSV file\n");
}

// Get headers
$headers = fgetcsv($handle);
echo "CSV Headers: " . implode(', ', $headers) . "\n\n";

echo "=================================\n";
echo "Starting import...\n";
echo "=================================\n\n";

$imported = 0;
$skipped = 0;
$errors = 0;
$stats_by_category = array(
    'Articles' => 0,
    'Case Studies' => 0,
    'Ratings' => 0
);

// ============================================
// STEP 3: Process Each Row
// ============================================
while (($data = fgetcsv($handle)) !== FALSE) {
    
    // Skip if not enough data
    if (count($data) < count($headers)) {
        continue;
    }
    
    // Map to associative array
    $article = array_combine($headers, $data);
    
    // Skip if no title
    if (empty($article['title'])) {
        $skipped++;
        continue;
    }
    
    // Check if already imported
    $existing = get_posts(array(
        'post_type' => 'post',
        'meta_key' => '_cake_article_id',
        'meta_value' => $article['id'],
        'numberposts' => 1
    ));
    
    if (!empty($existing)) {
        $cat_name = isset($article['category_name']) ? $article['category_name'] : 'Articles';
        echo "⊘ Skipping (already exists): " . $article['title'] . " [" . $cat_name . "]\n";
        $skipped++;
        continue;
    }
    
    // Determine category from category_name field
    $cat_name = isset($article['category_name']) ? $article['category_name'] : 'Articles';
    if (!isset($category_ids[$cat_name])) {
        $cat_name = 'Articles'; // Fallback
    }
    $cat_id = $category_ids[$cat_name];
    
    // Prepare content
    $post_content = isset($article['content']) ? $article['content'] : '';
    
    // Add external link if exists
    if (isset($article['link']) && !empty($article['link']) && $article['link'] != 'NULL') {
        $post_content .= "\n\n<p><a href=\"" . esc_url($article['link']) . "\" target=\"_blank\" rel=\"noopener\">Read more</a></p>";
    }
    
    // Prepare post data
    $post_data = array(
        'post_title'    => wp_strip_all_tags($article['title']),
        'post_content'  => $post_content,
        'post_status'   => $post_status,
        'post_author'   => $default_author_id,
        'post_type'     => 'post',
        'post_category' => array($cat_id),
    );
    
    // Handle dates
    if (isset($article['post_date']) && !empty($article['post_date']) && $article['post_date'] != 'NULL' && $article['post_date'] != '0000-00-00 00:00:00') {
        $post_data['post_date'] = $article['post_date'];
        $post_data['post_date_gmt'] = get_gmt_from_date($article['post_date']);
    }
    
    if (isset($article['post_modified']) && !empty($article['post_modified']) && $article['post_modified'] != 'NULL' && $article['post_modified'] != '0000-00-00 00:00:00') {
        $post_data['post_modified'] = $article['post_modified'];
        $post_data['post_modified_gmt'] = get_gmt_from_date($article['post_modified']);
    }
    
    // Handle slug
    if (isset($article['slug']) && !empty($article['slug']) && $article['slug'] != 'NULL') {
        $post_data['post_name'] = sanitize_title($article['slug']);
    }
    
    // Insert post
    $post_id = wp_insert_post($post_data, true);
    
    if (is_wp_error($post_id)) {
        echo "✗ Error: " . $article['title'] . " - " . $post_id->get_error_message() . "\n";
        $errors++;
    } else {
        echo "✓ Imported: " . $article['title'] . " [" . $cat_name . "] (WP ID: " . $post_id . ")\n";
        
        // Store metadata
        update_post_meta($post_id, '_cake_article_id', $article['id']);
        update_post_meta($post_id, '_cake_content_type', $cat_name);
        
        if (isset($article['slug'])) {
            update_post_meta($post_id, '_cake_hash', $article['slug']);
        }
        if (isset($article['type'])) {
            update_post_meta($post_id, '_cake_type', $article['type']);
        }
        if (isset($article['rating'])) {
            update_post_meta($post_id, '_cake_rating', $article['rating']);
        }
        if (isset($article['user_id'])) {
            update_post_meta($post_id, '_cake_user_id', $article['user_id']);
        }
        if (isset($article['member_id'])) {
            update_post_meta($post_id, '_cake_member_id', $article['member_id']);
        }
        
        // Handle featured image
        if (isset($article['image_value']) && !empty($article['image_value']) && $article['image_value'] != 'NULL') {
            $image_url = $article['image_value'];
            
            // Make relative URLs absolute
            if (strpos($image_url, 'http') !== 0) {
                $image_url = 'https://www.esopmarketplace.com/' . ltrim($image_url, '/');
            }
            
            // Download image
            $tmp = download_url($image_url);
            
            if (!is_wp_error($tmp)) {
                $file_array = array(
                    'name' => basename($image_url),
                    'tmp_name' => $tmp
                );
                
                $image_id = media_handle_sideload($file_array, $post_id);
                
                if (!is_wp_error($image_id)) {
                    set_post_thumbnail($post_id, $image_id);
                    echo "  ↳ Featured image set\n";
                } else {
                    @unlink($file_array['tmp_name']);
                }
            }
        }
        
        $imported++;
        $stats_by_category[$cat_name]++;
    }
    
    // Small delay
    usleep(100000);
}

fclose($handle);

// ============================================
// FINAL SUMMARY
// ============================================
echo "\n=================================\n";
echo "IMPORT COMPLETE!\n";
echo "=================================\n";
echo "✓ Successfully imported: " . $imported . "\n";
echo "⊘ Skipped (duplicates): " . $skipped . "\n";
echo "✗ Errors: " . $errors . "\n";
echo "\nBreakdown by category:\n";
foreach ($stats_by_category as $cat => $count) {
    echo "  - " . $cat . ": " . $count . " imported\n";
}
echo "=================================\n\n";

echo "Next Steps:\n";
echo "1. Go to Posts → All Posts in WordPress admin\n";
echo "2. Review imported posts (status: " . $post_status . ")\n";
echo "3. Filter by category to verify each type\n";
echo "4. Delete this script for security\n";
echo "=================================\n";

echo "</pre></body></html>";
?>