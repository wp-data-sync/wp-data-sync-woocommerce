<?php
/**
 * Filter WooCommerce Price
 *
 * Apply WooCommerce price adjustment.
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Log;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Data Sync Regular Price Adjustment
 *
 * @param string                   $price
 * @param int                      $product_id
 * @param WP_DataSync\App\DataSync $data_sync
 *
 * @return mixed
 */

add_filter( 'wp_data_sync__regular_price_value', function( $price, $product_id, $data_sync ) {

	if ( empty( $price ) ) {
		return $price;
	}

	if ( $adjustment = get_option( 'wp_data_sync_regular_price_adjustment' ) ) {

		if ( empty ( $adjustment ) ) {
			return $price;
		}

		$price      = floatval( $price );
		$adjustment = floatval( $adjustment );

		Log::write( 'price-adjustment', $price, 'Regular Price - Before' );

		$price = $price * ( ( 100 + $adjustment ) / 100 );
		$price = round( $price, 2 );

		Log::write( 'price-adjustment', $price, 'Regular Price - After' );

	}

	return $price;

}, 10, 3 );

/**
 * WP Data Sync Sale Price Adjustment
 *
 * @param string                   $price
 * @param int                      $product_id
 * @param WP_DataSync\App\DataSync $data_sync
 *
 * @return mixed
 */

add_filter( 'wp_data_sync__sale_price_value', function( $price, $product_id, $data_sync ) {

	if ( empty( $price ) ) {
		return $price;
	}

	if ( $adjustment = get_option( 'wp_data_sync_sale_price_adjustment' ) ) {

		if ( empty ( $adjustment ) ) {
			return $price;
		}

		$price      = floatval( $price );
		$adjustment = floatval( $adjustment );

		Log::write( 'price-adjustment', $price, 'Sale Price - Before' );

		$price = $price * ( ( 100 + $adjustment ) / 100 );
		$price = round( $price, 2 );

		Log::write( 'price-adjustment', $price, 'Sale Price - After' );

	}

	return $price;

}, 10, 3 );