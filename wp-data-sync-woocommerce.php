<?php
/**
 * Plugin Name: WP Data Sync for WooCommerce
 * Plugin URI:  https://wpdatasync.com/products/
 * Description: Extend WP Data Sync to include additional WooCommecre functionality
 * Version:     2.1.0
 * Author:      WP Data Sync
 * Author URI:  https://wpdatasync.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-data-sync-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 3.5
 * WC tested up to: 5.8.0
 *
 * Package:     WP_DataSync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defines = [
	'WPDS_FOR_WOO_VERSION' => '2.1.0',
	'WPDS_FOR_WOO_CAP'     => 'manage_options'
];

foreach ( $defines as $define => $value ) {
	if ( ! defined( $define ) ) {
		define( $define, $value );
	}
}

foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/**/*.php' ) as $file ) {
	require_once $file;
}

// Start the engine.
add_action( 'plugins_loaded', function() {

	// Load text domain
	add_action( 'init', function() {
		load_plugin_textdomain( 'wp-data-sync-woocommerce', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	} );

} );

/**
 * Process WooCommerce.
 */

add_action( 'wp_data_sync_after_process', function ( $post_id, $data_sync ) {

	$post_type = $data_sync->get_post_type();

	if ( 'shop_order' === $post_type || 'shop_order_refund' === $post_type ) {
		return WC_Order_DataSync::instance()->wc_process( $data_sync );
	}


}, 10, 2 );

/**
 * WooCommerce ItemRequest
 */

add_filter( 'wp_data_sync_item_request', function( $item_data, $item_id, $item_request ) {

	$post_type = $item_request->get_post_type();

	if ( 'shop_order' === $post_type || 'shop_order_refund' === $post_type ) {
		return WC_Order_ItemRequest::instance()->wc_process( $item_data, $item_id, $item_request );
	}

	return $item_data;

}, 10, 3 );