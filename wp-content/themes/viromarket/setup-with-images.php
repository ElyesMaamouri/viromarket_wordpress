<?php
/**
 * ViroMarket - Auto Setup with Images
 * 
 * Creates categories, products AND placeholder images!
 * 
 * @package ViroMarket
 */

// Load WordPress
$wp_load_paths = array(
    __DIR__ . '/../../../../wp-load.php',
    __DIR__ . '/../../../wp-load.php',
    dirname(dirname(dirname(dirname(__DIR__)))) . '/wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('Error: Could not find wp-load.php');
}

if (!current_user_can('manage_options')) {
    wp_die('Permission denied');
}

if (!class_exists('WooCommerce')) {
    wp_die('WooCommerce required');
}

// Include WordPress media functions
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

set_time_limit(300);

/**
 * Generate placeholder image and attach to product
 */
function viromarket_create_product_image($product_id, $product_name, $category) {
    // Use placeholder.com for product images
    $colors = array(
        'Electronics' => '4A90E2',
        'Fashion' => 'E91E63',
        'Home & Living' => '4CAF50',
        'Beauty & Health' => 'FF9800',
    );
    
    $color = isset($colors[$category]) ? $colors[$category] : '62D0B6';
    $text = urlencode(substr($product_name, 0, 20));
    
    // Generate image URL (800x800)
    $image_url = "https://via.placeholder.com/800x800/{$color}/FFFFFF?text={$text}";
    
    // Download image
    $tmp = download_url($image_url);
    
    if (is_wp_error($tmp)) {
        return false;
    }
    
    // Prepare file array
    $file_array = array(
        'name' => sanitize_file_name($product_name) . '.png',
        'tmp_name' => $tmp,
    );
    
    // Upload to media library
    $attachment_id = media_handle_sideload($file_array, $product_id);
    
    // Clean up temp file
    @unlink($tmp);
    
    if (is_wp_error($attachment_id)) {
        return false;
    }
    
    // Set as product featured image
    set_post_thumbnail($product_id, $attachment_id);
    
    return $attachment_id;
}

// Product data with images
$store_data = array(
    'Electronics' => array(
        'slug' => 'electronics',
        'subcategories' => array(
            'Smartphones' => array(
                array('name' => 'iPhone 15 Pro Max', 'price' => 1199, 'sale' => 1099),
                array('name' => 'Samsung Galaxy S24', 'price' => 1299, 'sale' => null),
                array('name' => 'Google Pixel 8 Pro', 'price' => 999, 'sale' => 899),
            ),
            'Laptops & Computers' => array(
                array('name' => 'MacBook Pro 16"', 'price' => 2499, 'sale' => null),
                array('name' => 'Dell XPS 15', 'price' => 1799, 'sale' => 1599),
            ),
        ),
    ),
    'Fashion' => array(
        'slug' => 'fashion',
        'subcategories' => array(
            'Men\'s Clothing' => array(
                array('name' => 'Denim Jacket', 'price' => 89, 'sale' => 69),
                array('name' => 'T-Shirt Pack', 'price' => 45, 'sale' => null),
            ),
            'Women\'s Clothing' => array(
                array('name' => 'Summer Dress', 'price' => 95, 'sale' => 75),
                array('name' => 'Leather Handbag', 'price' => 149, 'sale' => null),
            ),
        ),
    ),
    'Home & Living' => array(
        'slug' => 'home-living',
        'subcategories' => array(
            'Furniture' => array(
                array('name' => 'Modern Sofa', 'price' => 899, 'sale' => 799),
                array('name' => 'Dining Table', 'price' => 699, 'sale' => null),
            ),
        ),
    ),
    'Beauty & Health' => array(
        'slug' => 'beauty-health',
        'subcategories' => array(
            'Skincare' => array(
                array('name' => 'Anti-Aging Serum', 'price' => 79, 'sale' => 59),
                array('name' => 'Moisturizer SPF 30', 'price' => 45, 'sale' => null),
            ),
        ),
    ),
);

$stats = array('cats' => 0, 'products' => 0, 'images' => 0);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body{font-family:Arial;max-width:900px;margin:20px auto;padding:20px;background:#f5f5f5}
.header{background:linear-gradient(135deg,#62D0B6,#4CAF50);color:#fff;padding:30px;border-radius:12px;margin-bottom:30px}
.section{background:#fff;padding:20px;margin:15px 0;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1)}
.success{color:#27AE60;font-weight:bold}
.warning{color:#FF9800}
.summary{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:25px;border-radius:8px;margin-top:30px}
.btn{display:inline-block;background:#62D0B6;color:#fff;padding:12px 24px;text-decoration:none;border-radius:6px;margin:10px 5px}
.progress{width:100%;height:30px;background:#e0e0e0;border-radius:15px;overflow:hidden;margin:20px 0}
.progress-bar{height:100%;background:linear-gradient(90deg,#62D0B6,#4CAF50);width:0%;transition:width 0.3s}
</style>
</head>
<body>

<div class="header">
<h1>🚀 ViroMarket Setup with Images</h1>
<p>Creating categories, products, and placeholder images...</p>
</div>

<div class="progress"><div class="progress-bar" id="progress"></div></div>

<script>
let progress = 0;
function updateProgress(p) {
    progress = p;
    document.getElementById('progress').style.width = p + '%';
}
updateProgress(10);
</script>

<?php

foreach ($store_data as $parent_name => $parent_data) {
    echo '<div class="section">';
    
    // Create parent category
    $parent_term = term_exists($parent_name, 'product_cat');
    if (!$parent_term) {
        $parent_term = wp_insert_term($parent_name, 'product_cat', array('slug' => $parent_data['slug']));
        if (!is_wp_error($parent_term)) {
            $stats['cats']++;
            echo '<p class="success">✓ Category: ' . esc_html($parent_name) . '</p>';
        }
    }
    $parent_id = is_array($parent_term) ? $parent_term['term_id'] : $parent_term['term_id'];
    
    // Create subcategories and products
    foreach ($parent_data['subcategories'] as $sub_name => $products) {
        $sub_term = term_exists($sub_name, 'product_cat');
        if (!$sub_term) {
            $sub_term = wp_insert_term($sub_name, 'product_cat', array('parent' => $parent_id));
            if (!is_wp_error($sub_term)) {
                $stats['cats']++;
                echo '<p class="success" style="margin-left:20px">✓ Subcategory: ' . esc_html($sub_name) . '</p>';
            }
        }
        $sub_id = is_array($sub_term) ? $sub_term['term_id'] : $sub_term['term_id'];
        
        // Create products
        foreach ($products as $p) {
            $existing = get_page_by_title($p['name'], OBJECT, 'product');
            if (!$existing) {
                $product = new WC_Product_Simple();
                $product->set_name($p['name']);
                $product->set_status('publish');
                $product->set_regular_price($p['price']);
                if ($p['sale']) $product->set_sale_price($p['sale']);
                $product->set_manage_stock(true);
                $product->set_stock_quantity(rand(10, 100));
                $product->set_sku(strtoupper(substr(md5($p['name']), 0, 8)));
                $product->set_description('High-quality ' . $p['name'] . '. Perfect for everyday use.');
                
                $product_id = $product->save();
                
                if ($product_id) {
                    wp_set_object_terms($product_id, $sub_id, 'product_cat');
                    $stats['products']++;
                    
                    // Create image
                    $img_id = viromarket_create_product_image($product_id, $p['name'], $parent_name);
                    if ($img_id) {
                        $stats['images']++;
                        echo '<p class="success" style="margin-left:40px">✓ Product + Image: ' . esc_html($p['name']) . ' - $' . $p['price'] . '</p>';
                    } else {
                        echo '<p class="warning" style="margin-left:40px">✓ Product (no image): ' . esc_html($p['name']) . '</p>';
                    }
                    
                    flush();
                    ob_flush();
                }
            }
        }
    }
    
    echo '</div>';
}

echo '<script>updateProgress(100);</script>';

?>

<div class="summary">
<h2>✅ Setup Complete!</h2>
<p><strong>Categories:</strong> <?php echo $stats['cats']; ?></p>
<p><strong>Products:</strong> <?php echo $stats['products']; ?></p>
<p><strong>Images:</strong> <?php echo $stats['images']; ?></p>

<h3>Next Steps:</h3>
<ol>
<li>View your products and categories</li>
<li>Create navigation menus</li>
<li>Replace placeholder images with real photos</li>
<li>Customize product descriptions</li>
</ol>

<p style="margin-top:30px">
<a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="btn">View Products</a>
<a href="<?php echo admin_url('edit-tags.php?taxonomy=product_cat&post_type=product'); ?>" class="btn">View Categories</a>
<a href="<?php echo admin_url('nav-menus.php'); ?>" class="btn">Create Menus</a>
</p>

<p style="margin-top:20px;opacity:0.8;font-size:14px">
<strong>Note:</strong> Placeholder images created. Replace with real product photos for best results!
</p>
</div>

</body>
</html>
