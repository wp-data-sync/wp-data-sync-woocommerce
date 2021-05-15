<?php
/**
 * Register Brand Taxonomy
 *
 * Regoster brand taxonomy for WooCommerce products.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo\App;

add_action( 'init', function() {

	$args = apply_filters( 'wp_data_sync_brand_taxonomy_args', [
		'labels'            => brand_taxonomy_labels(),
		'hierarchical'      => TRUE,
		'show_in_nav_menus'	=> FALSE,
		'query_var'         => TRUE,
		'public' 			=> TRUE,
		'show_tagcloud'		=> FALSE
	] );

	register_taxonomy( brand_taxonomy_key(), 'product', $args );

}, 1 );

/**
 * Brand taxonomy key.
 * 
 * @return mixed|void
 */

function brand_taxonomy_key() {
	return apply_filters( 'wp_data_sync_brand_taxonomy_key', 'brand' );
}

/**
 * Taxonomy labels.
 *
 * @return array
 */

function brand_taxonomy_labels() {

	return apply_filters( 'wp_data_sync_brand_taxonomy_labels', [
		'name'              => _x( 'Brands', 'Brands', 'wp-data-sync-woocommerce' ),
		'singular_name'     => _x( 'Brand', 'Brand', 'wp-data-sync-woocommerce' ),
		'search_items'      => __( 'Search Brands', 'wp-data-sync-woocommerce' ),
		'all_items'         => __( 'All Brands', 'wp-data-sync-woocommerce' ),
		'parent_item'       => __( 'Parent Brand', 'wp-data-sync-woocommerce' ),
		'parent_item_colon' => __( 'Parent Brand:', 'wp-data-sync-woocommerce' ),
		'edit_item'         => __( 'View Brand', 'wp-data-sync-woocommerce' ),
		'update_item'       => __( 'Update Brand', 'wp-data-sync-woocommerce' ),
		'add_new_item'      => __( 'Add New Brand', 'wp-data-sync-woocommerce' ),
		'new_item_name'     => __( 'New Brand Name', 'wp-data-sync-woocommerce' ),
		'menu_name'         => __( 'Brand', 'wp-data-sync-woocommerce' ),
	] );

}
