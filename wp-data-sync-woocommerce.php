<?php
/**
 * Plugin Name: WP Data Sync - WooCommerce
 * Plugin URI:  https://wpdatasync.com/products/
 * Description: Sync raw product data into your WooCommerce Store.
 * Version:     1.0.7
 * Author:      WP Data Sync
 * Author URI:  https://wpdatasync.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-data-sync-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 2.5
 * WC tested up to: 4.0.1
 *
 * Package:     WP_DataSync
 */

namespace WP_DataSync\App;

$defines = [
	'WP_DATA_SYNC_WOO_VERSION' => '1.0.7',
	'WP_DATA_SYNC_WOO_PLUGIN'  => plugin_basename( __FILE__ )
];

foreach ( $defines as $define => $value ) {
	if ( ! defined( $define ) ) {
		define( $define, $value );
	}
}

foreach ( glob( plugin_dir_path( __FILE__ ) . 'app/**/*.php' ) as $file ) {
	require_once $file;
}

/**
 * After process hook.
 */

add_action( 'wp_data_sync_after_process', function( $post_id, $data, $data_sync ) {

	if ( class_exists( 'WooCommerce', FALSE ) ) {

		$wc_sync = WC_WP_DataSync::instance( $data_sync );
		$wc_sync->wc_process( $post_id, $data );

	}

}, 10, 3 );
