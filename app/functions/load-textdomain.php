<?php
/**
 * Load text domain
 *
 * Load the plugin text domain.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

/**
 * Load the plugin text domain.
 */

add_action( 'init', function() {

	load_plugin_textdomain( 'wp-data-sync-woocommerce', false, dirname( WP_DATA_SYNC_WOO_PLUGIN ) . '/languages' );

} );
