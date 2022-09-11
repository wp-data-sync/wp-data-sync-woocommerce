<?php
/**
 * Allow Duplicate SKU
 *
 * @since   2.1.10
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Has Unique SKU
 *
 * @param bool $unique
 *
 * @return bool
 */

add_filter( 'wc_product_has_unique_sku', function( $unique ) {

	if ( Settings::is_checked( 'wp_data_sync_allow_duplicate_sku' ) ) {
		return false;
	}

	return $unique;

}, 99 );
