<?php
/**
 * Update Duplicate Fields
 *
 * @since   2.1.10
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\DataSync;
use WP_DataSync\App\Log;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function() {

	if ( ! $fields = get_option( 'wp_data_sync_update_duplicate_fields' ) ) {
		return;
	}

	if ( empty( $fields ) ) {
		return;
	}

	if ( ! is_array( $fields ) ) {
		return;
	}

	if ( 1 === count( $fields ) && 'none' === $fields[0] ) {
		return;
	}

	foreach ( $fields as $meta_key ) {
		add_action( "wp_data_sync_duplicate_post_meta_$meta_key", 'WP_DataSync\Woo\update_duplicate_meta_fields', 10, 3 );
	}

} );

/**
 * Update duplicate meta fields.
 *
 * @param string $meta_key
 * @param mixed $meta_value
 * @param DataSync $data_sync
 *
 * @return void
 */

function update_duplicate_meta_fields( $meta_key, $meta_value, $data_sync ) {

	if ( ! $product_ids = $data_sync->fetch_post_ids() ) {
		return;
	}

	// No need to continue if less than 2 ids
	if ( 2 > count( $product_ids ) ) {
		return;
	}

	foreach ( $product_ids as $product_id ) {
		$data_sync->save_post_meta( $product_id, $meta_key, $meta_value );
	}

	Log::write( 'update-duplicate-field', [
		'meta_key'    => $meta_key,
		'meta_value'  => $meta_value,
		'primary_id'  => $data_sync->get_primary_id(),
		'product_ids' => $product_ids
	] );

}
