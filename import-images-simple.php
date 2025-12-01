<?php
/**
 * Import CAKE Featured Images - Simple CSV Method
 * Upload a CSV with: id,image_value
 * 
 * RUN: https://new.esopmarketplace.com/import-images-simple.php
 */

require_once('wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

$csv_file = 'cake_images_only.csv';

// Possible image locations
$image_base_urls = array(
    'https://www.esopmarketplace.com/img/',
    'https://www.esopmarketplace.com/images/',
    'https://www.esopmarketplace.com/uploads/',
    'https://www.esopmarketplace.com/files/',
    'https://www.esopmarketplace.com/app/webroot/img/',
);

echo "<html><head><title>Import Images</title></head><body>";
echo "<h1>Import CAKE Featured Images</h1>";
echo "<pre>\n";

if (!file_exists($csv_file)) {
    die("Error: CSV file not found: " . $csv_file . "\n");
}

$handle = fopen($csv_file, 'r');
$headers = fgetcsv($handle);

echo "CSV loaded: " . $csv_file . "\n\n";
echo "=================================\n";
echo "PROCESSING...\n";
echo "=================================\n\n";

$stats = array('success' => 0, 'failed' => 0, 'skipped' => 0);

while (($data = fgetcsv($handle)) !== FALSE) {
    $row = array_combine($headers, $data);
    $cake_id = $row['id'];
    $image_file = $row['image_value'];
    
    // Find WordPress post
    $posts = get_posts(array(
        'post_type' => 'post',
        'meta_key' => '_cake_article_id',
        'meta_value' => $cake_id,
        'numberposts' => 1
    ));
    
    if (empty($posts)) {
        $stats['skipped']++;
        continue;
    }
    
    $post = $posts[0];
    
    if (has_post_thumbnail($post->ID)) {
        echo "⊘ " . $post->post_title . " (already has image)\n";
        $stats['skipped']++;
        continue;
    }
    
    echo "→ " . $post->post_title . "\n  Image: " . $image_file . "\n";
    
    // Find image URL
    $image_url = null;
    foreach ($image_base_urls as $base) {
        $test_url = $base . ltrim($image_file, '/');
        $headers_check = @get_headers($test_url, 1);
        if ($headers_check && strpos($headers_check[0], '200') !== false) {
            $image_url = $test_url;
            break;
        }
    }
    
    if (!$image_url) {
        echo "  ✗ Image not found on server\n\n";
        $stats['failed']++;
        continue;
    }
    
    // Download and attach
    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) {
        echo "  ✗ Download failed\n\n";
        $stats['failed']++;
        continue;
    }
    
    $file_array = array('name' => basename($image_file), 'tmp_name' => $tmp);
    $attachment_id = media_handle_sideload($file_array, $post->ID);
    
    if (is_wp_error($attachment_id)) {
        @unlink($file_array['tmp_name']);
        echo "  ✗ Upload failed\n\n";
        $stats['failed']++;
        continue;
    }
    
    set_post_thumbnail($post->ID, $attachment_id);
    echo "  ✓ Imported!\n\n";
    $stats['success']++;
    
    usleep(200000);
}

fclose($handle);

echo "=================================\n";
echo "SUMMARY\n";
echo "=================================\n";
echo "✓ Success: " . $stats['success'] . "\n";
echo "⊘ Skipped: " . $stats['skipped'] . "\n";
echo "✗ Failed: " . $stats['failed'] . "\n";
echo "=================================\n";
echo "</pre></body></html>";
?>