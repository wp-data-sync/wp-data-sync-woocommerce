<?php
/**
 * Filter WooCommerce Order Items
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Data Sync Order Items
 *
 * @param array     $order_items
 * @param \WC_Order $order
 *
 * @return mixed
 */

add_filter( 'wp_data_sync_order_items', function( $order_items, $order ) {

	foreach ( $order_items as $item_id => $item ) {

		$product_id = $item['product_id'];

		$order_items[ $item_id ]['mpn']   = get_post_meta( $product_id, '_mpn', TRUE );
		$order_items[ $item_id ]['gtin8'] = get_post_meta( $product_id, '_gtin8', TRUE );
		$order_items[ $item_id ]['isbn']  = get_post_meta( $product_id, '_isbn', TRUE );

	}

	return $order_items;

}, 10, 2 );