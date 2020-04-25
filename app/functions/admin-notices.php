<?php
/**
 * Admin Notices
 *
 * Add admin notices
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\App;

/**
 * Show a notice if the WP Data Sync plugin is not activated.
 */

add_action( 'admin_notices', function() {

	if ( is_plugin_active( 'wp-data-sync/wp-data-sync.php' ) ) {
		return;
	}

	$class = 'notice notice-error';
	$message = __( 'NOTICE: The WP Data Sync plugin is required to use the WP Data Sync - WooCommerce extension.', 'wp-data-sync-woocommerce' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

} );