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

		$id = empty( $item['variation_id'] ) ? $item['product_id'] : $item['variation_id'];

		$order_items[ $item_id ]['item_id'] = $id;

		foreach ( order_item_keys() as $key ) {
			$order_items[ $item_id ][ $key ] = get_post_meta( $id, "_$key", true );
		}

	}

	return $order_items;

}, 10, 2 );

/**
 * Order Item Keys
 *
 * @return array
 */

function order_item_keys() {

	return apply_filters( 'wp_data_sync_order_item_keys', [
		'upc',
		'mpn',
		'gtin8',
		'isbn',
		'price',
		'sale_price',
		'regular_price',
		'sku',
		'height',
		'length',
		'width',
		'weight',
		'shipping_class'
	] );

}