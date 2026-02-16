<?php
/**
 * Product Brands Custom Taxonomy
 *
 * @package ViroMarket
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Brand Taxonomy
 */
function viromarket_register_brand_taxonomy() {
	$labels = array(
		'name'                       => _x( 'Brands', 'taxonomy general name', 'viromarket' ),
		'singular_name'              => _x( 'Brand', 'taxonomy singular name', 'viromarket' ),
		'search_items'               => __( 'Search Brands', 'viromarket' ),
		'popular_items'              => __( 'Popular Brands', 'viromarket' ),
		'all_items'                  => __( 'All Brands', 'viromarket' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Brand', 'viromarket' ),
		'update_item'                => __( 'Update Brand', 'viromarket' ),
		'add_new_item'               => __( 'Add New Brand', 'viromarket' ),
		'new_item_name'              => __( 'New Brand Name', 'viromarket' ),
		'separate_items_with_commas' => __( 'Separate brands with commas', 'viromarket' ),
		'add_or_remove_items'        => __( 'Add or remove brands', 'viromarket' ),
		'choose_from_most_used'      => __( 'Choose from the most used brands', 'viromarket' ),
		'not_found'                  => __( 'No brands found.', 'viromarket' ),
		'menu_name'                  => __( 'Brands', 'viromarket' ),
	);

	$args = array(
		'hierarchical'          => true, // Set to true to allow parent/child brands
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'brand' ),
		'show_in_rest'          => true,
	);

	register_taxonomy( 'product_brand', array( 'product' ), $args );
}
add_action( 'init', 'viromarket_register_brand_taxonomy', 0 );
