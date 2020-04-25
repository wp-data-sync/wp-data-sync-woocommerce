<?php
/**
 * Plugin Action Links
 *
 * Add a settings link to plugin actions.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\App;

/**
 * Add settings link to plugin action links.
 */

add_filter( 'plugin_action_links', function( $links, $file ) {

	if ( ! is_plugin_active( 'wp-data-sync/wp-data-sync.php' ) ) {
		return $links;
	}

	if ( $file === WP_DATA_SYNC_WOO_PLUGIN ) {

		$links[] = '<a href="options-general.php?page=wp-data-sync&active_tab=woocommerce">' . __( 'Settings' ) . '</a>';

	}

	return $links;

}, 10, 2 );
