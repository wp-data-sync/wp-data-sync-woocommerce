<?php
/**
 * Auto Update
 *
 * Auto update this plugin
 *
 * @since   2.1.3
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo\App;

use WP_DataSync\App\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Auto Update Plugin
 *
 * @param $update
 * @param $item
 *
 * @return bool
 */

add_filter( 'auto_update_plugin', function( $update, $item ) {

	if ( isset( $item->slug ) && 'wp-data-sync-woocommerce' === $item->slug ) {

		if ( Settings::is_checked( 'wp_data_sync_auto_update' ) ) {
			return TRUE;
		}

	}

	return $update;

}, 10, 2 );
