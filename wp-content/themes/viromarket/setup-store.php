<?php
/**
 * ViroMarket - Auto Setup: Categories + Sample Products
 * 
 * This script will:
 * 1. Create all product categories with subcategories
 * 2. Insert sample products in each category
 * 3. Add product images, prices, descriptions
 * 
 * Run ONCE: yourdomain.com/wp-content/themes/viromarket/setup-store.php
 * 
 * @package ViroMarket
 */

// Load WordPress - Auto-detect correct path
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
    die('Error: Could not find wp-load.php. Please make sure WordPress is installed.');
}

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this page.');
}

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    wp_die('WooCommerce must be installed and activated first.');
}

// Increase execution time
set_time_limit(300); // 5 minutes

/**
 * Categories structure with sample products
 */
$store_data = array(
    'Electronics' => array(
        'slug' => 'electronics',
        'description' => 'Electronic devices and gadgets',
        'subcategories' => array(
            'Smartphones' => array(
                'products' => array(
                    array('name' => 'iPhone 15 Pro Max', 'price' => 1199, 'sale_price' => 1099),
                    array('name' => 'Samsung Galaxy S24 Ultra', 'price' => 1299, 'sale_price' => null),
                    array('name' => 'Google Pixel 8 Pro', 'price' => 999, 'sale_price' => 899),
                ),
            ),
            'Laptops & Computers' => array(
                'products' => array(
                    array('name' => 'MacBook Pro 16"', 'price' => 2499, 'sale_price' => null),
                    array('name' => 'Dell XPS 15', 'price' => 1799, 'sale_price' => 1599),
                    array('name' => 'HP Spectre x360', 'price' => 1499, 'sale_price' => null),
                ),
            ),
            'Tablets' => array(
                'products' => array(
                    array('name' => 'iPad Pro 12.9"', 'price' => 1099, 'sale_price' => 999),
                    array('name' => 'Samsung Galaxy Tab S9', 'price' => 899, 'sale_price' => null),
                ),
            ),
        ),
    ),
    'Fashion' => array(
        'slug' => 'fashion',
        'description' => 'Clothing, shoes, and accessories',
        'subcategories' => array(
            'Men\'s Clothing' => array(
                'products' => array(
                    array('name' => 'Classic Denim Jacket', 'price' => 89, 'sale_price' => 69),
                    array('name' => 'Cotton T-Shirt Pack (3)', 'price' => 45, 'sale_price' => null),
                    array('name' => 'Slim Fit Chinos', 'price' => 79, 'sale_price' => 59),
                ),
            ),
            'Women\'s Clothing' => array(
                'products' => array(
                    array('name' => 'Floral Summer Dress', 'price' => 95, 'sale_price' => 75),
                    array('name' => 'Leather Handbag', 'price' => 149, 'sale_price' => null),
                    array('name' => 'Yoga Pants Set', 'price' => 65, 'sale_price' => 49),
                ),
            ),
            'Shoes & Footwear' => array(
                'products' => array(
                    array('name' => 'Running Sneakers', 'price' => 120, 'sale_price' => 99),
                    array('name' => 'Leather Boots', 'price' => 180, 'sale_price' => null),
                ),
            ),
        ),
    ),
    'Home & Living' => array(
        'slug' => 'home-living',
        'description' => 'Furniture and home decor',
        'subcategories' => array(
            'Furniture' => array(
                'products' => array(
                    array('name' => 'Modern Sofa 3-Seater', 'price' => 899, 'sale_price' => 799),
                    array('name' => 'Dining Table Set', 'price' => 699, 'sale_price' => null),
                    array('name' => 'Office Chair Ergonomic', 'price' => 299, 'sale_price' => 249),
                ),
            ),
            'Kitchen & Dining' => array(
                'products' => array(
                    array('name' => 'Cookware Set 12-Piece', 'price' => 199, 'sale_price' => 149),
                    array('name' => 'Coffee Maker Deluxe', 'price' => 129, 'sale_price' => null),
                ),
            ),
        ),
    ),
    'Beauty & Health' => array(
        'slug' => 'beauty-health',
        'description' => 'Beauty products and health items',
        'subcategories' => array(
            'Skincare' => array(
                'products' => array(
                    array('name' => 'Anti-Aging Serum', 'price' => 79, 'sale_price' => 59),
                    array('name' => 'Moisturizer SPF 30', 'price' => 45, 'sale_price' => null),
                    array('name' => 'Face Mask Set (5)', 'price' => 29, 'sale_price' => 19),
                ),
            ),
            'Makeup' => array(
                'products' => array(
                    array('name' => 'Lipstick Collection', 'price' => 39, 'sale_price' => null),
                    array('name' => 'Eyeshadow Palette', 'price' => 55, 'sale_price' => 45),
                ),
            ),
        ),
    ),
);

$stats = array(
    'categories_created' => 0,
    'products_created' => 0,
    'errors' => array(),
);

echo '<html><head><meta charset="UTF-8"><style>
body { font-family: Arial, sans-serif; max-width: 900px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
.header { background: linear-gradient(135deg, #62D0B6 0%, #4CAF50 100%); color: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; }
.header h1 { margin: 0; font-size: 32px; }
.header p { margin: 10px 0 0 0; opacity: 0.9; }
.section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.category { margin-bottom: 15px; padding: 15px; background: #f9f9f9; border-left: 4px solid #62D0B6; border-radius: 4px; }
.product { margin-left: 40px; padding: 8px; color: #666; font-size: 14px; }
.success { color: #27AE60; font-weight: bold; }
.error { color: #F55157; font-weight: bold; }
.warning { color: #FF9800; }
.summary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 8px; margin-top: 30px; }
.btn { display: inline-block; background: #62D0B6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 5px; font-weight: bold; }
.btn:hover { background: #4CAF50; }
.progress { width: 100%; height: 30px; background: #e0e0e0; border-radius: 15px; overflow: hidden; margin: 20px 0; }
.progress-bar { height: 100%; background: linear-gradient(90deg, #62D0B6 0%, #4CAF50 100%); transition: width 0.3s; }
</style></head><body>';

echo '<div class="header">';
echo '<h1>🚀 ViroMarket Store Setup</h1>';
echo '<p>Creating categories and inserting sample products...</p>';
echo '</div>';

echo '<div class="progress"><div class="progress-bar" style="width: 10%;"></div></div>';

// Process each category
foreach ($store_data as $parent_name => $parent_data) {
    echo '<div class="section">';
    echo '<div class="category">';
    
    // Create parent category
    $parent_term = term_exists($parent_name, 'product_cat');
    
    if (!$parent_term) {
        $parent_term = wp_insert_term(
            $parent_name,
            'product_cat',
            array(
                'slug' => $parent_data['slug'],
                'description' => $parent_data['description'],
            )
        );
        
        if (is_wp_error($parent_term)) {
            $stats['errors'][] = 'Error creating ' . $parent_name;
            echo '<p class="error">✗ Error creating: ' . esc_html($parent_name) . '</p>';
            continue;
        }
        
        $parent_id = $parent_term['term_id'];
        $stats['categories_created']++;
        echo '<p class="success">✓ Created category: <strong>' . esc_html($parent_name) . '</strong></p>';
    } else {
        $parent_id = $parent_term['term_id'];
        echo '<p class="warning">⚠ Category exists: <strong>' . esc_html($parent_name) . '</strong></p>';
    }
    
    // Process subcategories
    if (isset($parent_data['subcategories']) && is_array($parent_data['subcategories'])) {
        foreach ($parent_data['subcategories'] as $sub_name => $sub_data) {
            $sub_term = term_exists($sub_name, 'product_cat');
            
            if (!$sub_term) {
                $sub_slug = sanitize_title($sub_name);
                $sub_term = wp_insert_term(
                    $sub_name,
                    'product_cat',
                    array(
                        'slug' => $sub_slug,
                        'parent' => $parent_id,
                    )
                );
                
                if (is_wp_error($sub_term)) {
                    $stats['errors'][] = 'Error creating ' . $sub_name;
                    continue;
                }
                
                $sub_id = $sub_term['term_id'];
                $stats['categories_created']++;
                echo '<p class="success" style="margin-left: 20px;">✓ Created subcategory: ' . esc_html($sub_name) . '</p>';
            } else {
                $sub_id = $sub_term['term_id'];
                echo '<p class="warning" style="margin-left: 20px;">⚠ Subcategory exists: ' . esc_html($sub_name) . '</p>';
            }
            
            // Create products for this subcategory
            if (isset($sub_data['products']) && is_array($sub_data['products'])) {
                foreach ($sub_data['products'] as $product_data) {
                    // Check if product exists
                    $existing = get_page_by_title($product_data['name'], OBJECT, 'product');
                    
                    if (!$existing) {
                        // Create product
                        $product = new WC_Product_Simple();
                        $product->set_name($product_data['name']);
                        $product->set_status('publish');
                        $product->set_catalog_visibility('visible');
                        $product->set_description('High-quality ' . $product_data['name'] . '. Perfect for everyday use.');
                        $product->set_short_description('Premium quality product with excellent features.');
                        $product->set_sku(strtoupper(substr(md5($product_data['name']), 0, 8)));
                        $product->set_regular_price($product_data['price']);
                        
                        if ($product_data['sale_price']) {
                            $product->set_sale_price($product_data['sale_price']);
                        }
                        
                        $product->set_manage_stock(true);
                        $product->set_stock_quantity(rand(10, 100));
                        $product->set_stock_status('instock');
                        
                        // Save product
                        $product_id = $product->save();
                        
                        if ($product_id) {
                            // Assign to category
                            wp_set_object_terms($product_id, $sub_id, 'product_cat');
                            
                            $stats['products_created']++;
                            $price_display = $product_data['sale_price'] 
                                ? '<del>$' . $product_data['price'] . '</del> <strong>$' . $product_data['sale_price'] . '</strong>' 
                                : '$' . $product_data['price'];
                            echo '<p class="product success">✓ Product: ' . esc_html($product_data['name']) . ' - ' . $price_display . '</p>';
                        }
                    } else {
                        echo '<p class="product warning">⚠ Product exists: ' . esc_html($product_data['name']) . '</p>';
                    }
                }
            }
        }
    }
    
    echo '</div></div>';
}

echo '<div class="progress"><div class="progress-bar" style="width: 100%;"></div></div>';

// Summary
echo '<div class="summary">';
echo '<h2>✅ Setup Complete!</h2>';
echo '<p><strong>Categories created:</strong> ' . $stats['categories_created'] . '</p>';
echo '<p><strong>Products created:</strong> ' . $stats['products_created'] . '</p>';

if (!empty($stats['errors'])) {
    echo '<h3>Errors:</h3><ul>';
    foreach ($stats['errors'] as $error) {
        echo '<li>' . esc_html($error) . '</li>';
    }
    echo '</ul>';
}

echo '<h3>Next Steps:</h3>';
echo '<ol>';
echo '<li>Go to <strong>Products > Categories</strong> to view all categories</li>';
echo '<li>Go to <strong>Products > All Products</strong> to view sample products</li>';
echo '<li>Go to <strong>Appearance > Menus</strong> to create your navigation</li>';
echo '<li>Add product images via <strong>Products > Edit Product</strong></li>';
echo '<li>Customize product details and add more products</li>';
echo '</ol>';

echo '<p style="margin-top: 30px;">';
echo '<a href="' . admin_url('edit.php?post_type=product') . '" class="btn">View Products</a>';
echo '<a href="' . admin_url('edit-tags.php?taxonomy=product_cat&post_type=product') . '" class="btn">View Categories</a>';
echo '<a href="' . admin_url('nav-menus.php') . '" class="btn">Create Menus</a>';
echo '</p>';

echo '<p style="margin-top: 20px; opacity: 0.8; font-size: 14px;"><strong>Note:</strong> You can delete this file (setup-store.php) after running it.</p>';
echo '</div>';

echo '</body></html>';
?>
