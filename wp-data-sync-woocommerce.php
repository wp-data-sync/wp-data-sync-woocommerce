<?php
/**
 * Plugin Name: WP Data Sync - WooCommerce
 * Plugin URI:  https://wpdatasync.com/products/
 * Description: Add data fields to your WooCommecre products
 * Version:     2.0.0
 * Author:      WP Data Sync
 * Author URI:  https://wpdatasync.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-data-sync-woocommerce
 * Domain Path: /languages
 *
 * Package:     WP_DataSync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defines = [
	'WPDS_WOO_VERSION' => '2.0.0',
	'WPDS_WOO_CAP'     => 'manage_options'
];

foreach ( $defines as $define => $value ) {
	if ( ! defined( $define ) ) {
		define( $define, $value );
	}
}

foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/**/*.php' ) as $file ) {
	require_once $file;
}
