<?php
/**
 * Plugin Name: WP Data Sync for WooCommerce
 * Plugin URI:  https://wpdatasync.com/products/
 * Description: Extend WP Data Sync to include additional WooCommecre functionality
 * Version:     2.1.12
 * Author:      WP Data Sync
 * Author URI:  https://wpdatasync.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-data-sync-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 4.0
 * WC tested up to: 7.8.2
 *
 * Package:     WP_DataSync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defines = [
	'WPDS_FOR_WOO_VERSION' => '2.1.12',
	'WPDS_FOR_WOO_CAP'     => 'manage_options',
	'WPDS_FOR_WOO_ASSETS'  => plugins_url( 'assets/', __FILE__ ),
];

foreach ( $defines as $define => $value ) {
	if ( ! defined( $define ) ) {
		define( $define, $value );
	}
}

// Start the engine.
add_action( 'plugins_loaded', function() {

	foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/**/*.php' ) as $file ) {
		require_once $file;
	}

	// Load text domain
	add_action( 'init', function() {
		load_plugin_textdomain( 'wp-data-sync-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' );
	} );

} );
