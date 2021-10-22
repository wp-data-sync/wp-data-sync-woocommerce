<?php
/**
 * WC_Order_ItemRequest
 *
 * Request WooCommerce product data
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\ItemRequest;
use WP_DataSync\App\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Order_ItemRequest {

	/**
	 * @var int
	 */

	private $order_id;

	/**
	 * @var WC_Order_ItemRequest
	 */

	public static $instance;

	/**
	 * WC_Order_ItemRequest constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * @return WC_Order_ItemRequest
	 */

	public static function instance() {

		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * WC Process.
	 *
	 * @param $item_data
	 * @param $order_id
	 * @param $item_request ItemRequest
	 *
	 * @return mixed
	 */

	public function wc_process( $item_data, $order_id, $item_request ) {

		$this->order_id = $order_id;

		if ( ! Settings::is_data_type_excluded( 'order_items' ) ) {

			if ( $order_items = $this->order_items() ) {

				if ( ! Settings::is_data_type_excluded( 'order_itemmeta' ) ) {
					$order_items = array_map( [ $this, 'order_itemmeta' ], $order_items );
				}

				$item_data['order_items'] = array_map( [ $this, 'unset_id' ], $order_items );

			}

		}

		return $item_data;

	}

	/**
	 * Fetch the order items from the database.
	 *
	 * @return array|bool|object
	 */

	public function order_items() {

		global $wpdb;

		$order_items = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT order_item_id, order_item_name, order_item_type
			FROM {$wpdb->prefix}woocommerce_order_items
			WHERE order_id = %d
			",
			intval( $this->order_id )
		), ARRAY_A );

		if ( empty( $order_items ) || is_wp_error( $order_items ) ) {
			return FALSE;
		}


		return $order_items;

	}

	/**
	 * Order itemmeta.
	 *
	 * @param $order_item
	 *
	 * @return mixed
	 */

	public function order_itemmeta( $order_item ) {

		global $wpdb;

		$order_itemmeta = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT meta_key, meta_value
			FROM {$wpdb->prefix}woocommerce_order_itemmeta
			WHERE order_item_id = %d
			",
			intval( $order_item['order_item_id'] )
		), ARRAY_A );

		if ( empty( $order_itemmeta ) || is_wp_error( $order_itemmeta ) ) {
			return $order_item;
		}

		$order_item['order_itemmeta'] = $order_itemmeta;

		return $order_item;

	}

	/**
	 * Unset ID.
	 *
	 * @param array $order_item
	 *
	 * @return mixed
	 */

	public function unset_id( $order_item ) {

		unset( $order_item['order_item_id'] );

		return $order_item;

	}


}