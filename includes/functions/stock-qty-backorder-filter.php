<?php
/**
 * Filter WooCommerce Backorder Status Based on Stock Qty
 *
 * Set the backorder staus when the stock qty is less than 1.
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Data Sync Set Backorder Status by Stock Qty.
 *
 * @param string                   $price
 * @param int                      $product_id
 * @param WP_DataSync\App\DataSync $data_sync
 *
 * @return mixed
 */

add_filter( 'wp_data_sync__stock_value', function( $qty, $product_id, $data_sync ) {

	if ( empty( $qty ) ) {
		return $qty;
	}

	if ( ! Settings::is_checked( 'wp_data_sync_manage_backorder_status' ) ) {
		return $qty;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return $qty;
	}

	$product = wc_get_product( $product_id );

	// Default status
	$status = 'instock';

	if ( intval( $qty ) < 1 ) {
		$status = 'onbackorder';
	}

	$product->set_stock_status( $status );
	$product->save();

	return $qty;

}, 10, 3 );