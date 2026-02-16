<?php
/**
 * ViroMarket - Sample Categories Creator
 * 
 * Run this file ONCE to create sample product categories with subcategories
 * Access: yourdomain.com/wp-content/themes/viromarket/create-categories.php
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

/**
 * Categories structure (English by default)
 */
$categories = array(
    'Electronics' => array(
        'slug' => 'electronics',
        'description' => 'Electronic devices and gadgets',
        'subcategories' => array(
            'Smartphones',
            'Laptops & Computers',
            'Tablets',
            'Cameras & Photo',
            'Audio & Headphones',
            'Smart Home',
            'Wearable Technology',
        ),
    ),
    'Fashion' => array(
        'slug' => 'fashion',
        'description' => 'Clothing, shoes, and accessories',
        'subcategories' => array(
            'Men\'s Clothing',
            'Women\'s Clothing',
            'Kids & Baby',
            'Shoes & Footwear',
            'Bags & Accessories',
            'Jewelry & Watches',
            'Sunglasses',
        ),
    ),
    'Home & Living' => array(
        'slug' => 'home-living',
        'description' => 'Furniture and home decor',
        'subcategories' => array(
            'Furniture',
            'Kitchen & Dining',
            'Bedding & Bath',
            'Home Decor',
            'Garden & Outdoor',
            'Lighting',
            'Storage & Organization',
        ),
    ),
    'Sports & Entertainment' => array(
        'slug' => 'sports-entertainment',
        'description' => 'Sports equipment and entertainment',
        'subcategories' => array(
            'Gaming',
            'Sports Equipment',
            'Outdoor Recreation',
            'Books & Media',
            'Toys & Hobbies',
            'Musical Instruments',
            'Party Supplies',
        ),
    ),
    'Beauty & Health' => array(
        'slug' => 'beauty-health',
        'description' => 'Beauty products and health items',
        'subcategories' => array(
            'Skincare',
            'Makeup',
            'Hair Care',
            'Fragrances',
            'Health & Wellness',
            'Personal Care',
            'Vitamins & Supplements',
        ),
    ),
    'Food & Grocery' => array(
        'slug' => 'food-grocery',
        'description' => 'Fresh food and grocery items',
        'subcategories' => array(
            'Fresh Produce',
            'Pantry Staples',
            'Beverages',
            'Snacks & Sweets',
            'Organic & Natural',
            'Frozen Foods',
            'International Foods',
        ),
    ),
    'Automotive' => array(
        'slug' => 'automotive',
        'description' => 'Car parts and accessories',
        'subcategories' => array(
            'Car Parts',
            'Car Accessories',
            'Tools & Equipment',
            'Motorcycle',
            'Car Care',
            'Tires & Wheels',
            'GPS & Electronics',
        ),
    ),
    'Office & School' => array(
        'slug' => 'office-school',
        'description' => 'Office and school supplies',
        'subcategories' => array(
            'Office Supplies',
            'School Supplies',
            'Stationery',
            'Art & Craft',
            'Technology',
            'Desk Accessories',
            'Filing & Organization',
        ),
    ),
);

$created_categories = array();
$errors = array();

echo '<h1>ViroMarket - Creating Product Categories</h1>';
echo '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background: #f5f5f5; border-radius: 8px;">';

foreach ($categories as $parent_name => $parent_data) {
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
            $errors[] = 'Error creating ' . $parent_name . ': ' . $parent_term->get_error_message();
            continue;
        }
        
        $parent_id = $parent_term['term_id'];
        $created_categories[] = $parent_name;
        echo '<p style="color: green;">✓ Created parent category: <strong>' . esc_html($parent_name) . '</strong></p>';
    } else {
        $parent_id = $parent_term['term_id'];
        echo '<p style="color: orange;">⚠ Parent category already exists: <strong>' . esc_html($parent_name) . '</strong></p>';
    }
    
    // Create subcategories
    if (isset($parent_data['subcategories']) && is_array($parent_data['subcategories'])) {
        foreach ($parent_data['subcategories'] as $sub_name) {
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
                    $errors[] = 'Error creating ' . $sub_name . ': ' . $sub_term->get_error_message();
                    continue;
                }
                
                $created_categories[] = '  → ' . $sub_name;
                echo '<p style="color: green; margin-left: 20px;">✓ Created subcategory: ' . esc_html($sub_name) . '</p>';
            } else {
                echo '<p style="color: orange; margin-left: 20px;">⚠ Subcategory already exists: ' . esc_html($sub_name) . '</p>';
            }
        }
    }
    
    echo '<hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">';
}

echo '</div>';

// Summary
echo '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; border: 2px solid #62D0B6;">';
echo '<h2 style="color: #62D0B6;">Summary</h2>';
echo '<p><strong>Total categories created:</strong> ' . count($created_categories) . '</p>';

if (!empty($errors)) {
    echo '<h3 style="color: #F55157;">Errors:</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li style="color: #F55157;">' . esc_html($error) . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p style="color: green; font-size: 18px;">✓ All categories created successfully!</p>';
}

echo '<h3>Next Steps:</h3>';
echo '<ol>';
echo '<li>Go to <strong>Products > Categories</strong> to view your categories</li>';
echo '<li>Go to <strong>Appearance > Menus</strong> to create your navigation menu</li>';
echo '<li>Add categories to the "Categories" menu location</li>';
echo '<li>Start adding products to your categories</li>';
echo '</ol>';

echo '<p style="margin-top: 20px;"><a href="' . admin_url('edit-tags.php?taxonomy=product_cat&post_type=product') . '" style="background: #62D0B6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;">View Categories in Admin</a></p>';

echo '<p style="margin-top: 20px; color: #999; font-size: 12px;"><strong>Note:</strong> You can delete this file (create-categories.php) after running it once.</p>';

echo '</div>';
?>
