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
 * Package:     WP_DataSync
 */

add_action( 'admin_notices', function() {

	$class = 'notice notice-error';
	$p1    = __( 'NOTICE: The WP Data Sync for WooCommerce plugin has been combined with the WP Data Sync plugin.', 'wp-data-sync' );
	$p2    = __( 'To avoid conflicts, please deativate the WP Data Sync for WooCommerce plugin.', 'wp-data-sync' );
	$url   = 'https://wordpress.org/plugins/wp-data-sync/';

	printf(
		'<div class="%1$s"><p><b>%2$s</b></p><p>%3$s</p><p><a href="%4$s" target="_blank">%5$s</a></p></div>',
		esc_attr( $class ),
		esc_html( $p1 ),
		esc_html( $p2 ),
		esc_url( $url ),
		esc_html( 'WP Data Sync Plugin' )
	);

} );
