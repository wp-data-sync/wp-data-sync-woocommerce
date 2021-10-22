<?php
/**
 * Filter WooCommerce Weight
 *
 * Convert WooCommerce weight based on conversion rules.
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Log;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Data Sync Weight Conversion
 *
 * @param string                   $weight
 * @param int                      $product_id
 * @param WP_DataSync\App\DataSync $data_sync
 *
 * @return mixed
 */

add_filter( 'wp_data_sync__weight_value', function( $weight, $product_id, $data_sync ) {

	if ( empty( $weight ) ) {
		return $weight;
	}

	if ( $conversion = get_option( 'wp_data_sync_convert_product_weight' ) ) {

		$weight = floatval( $weight );

		Log::write( 'weight-conversion', $weight, 'Weight Before Conversion' );

		switch ( $conversion ) {

			case 'grams_kilograms' :
				$weight = $weight / 1000;
				break;

			case 'kilograms_grams' :
				$weight = $weight * 1000;
				break;

			case 'ounces_pounds' :
				$weight = $weight / 16;
				break;

			case 'pounds_ounces' :
				$weight = $weight * 16;
				break;

		}

		Log::write( 'weight-conversion', $weight, 'Weight After Conversion' );

	}

	return $weight;

}, 10, 3 );