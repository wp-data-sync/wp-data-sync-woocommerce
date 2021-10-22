<?php
/**
 * WC_Order_DataSync
 *
 * WP Data Sync for WooCommerce Orders methods
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\App\DataSync;
use WP_DataSync\App\Log;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Order_DataSync {

	/**
	 * @var int
	 */

	private $order_id;

	/**
	 * @var array|bool
	 */

	private $order_items;

	/**
	 * @var WC_Order_DataSync
	 */

	private static $instance;

	/**
	 * WC_Order_DataSync constructor.
	 */

	public function __construct() {
		self::$instance = $this;
	}

	/**
	 * Instance.
	 *
	 * @return WC_Order_DataSync
	 */

	public static function instance() {

		if ( self::$instance === NULL ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * WooCommerce process data.
	 *
	 * @param DataSync $data_sync
	 */

	public function wc_process( $data_sync ) {

		$this->order_id = $data_sync->get_post_id();

		if ( $this->order_items = $data_sync->get_order_items() ) {
			$this->order_items();
		}

	}

	/**
	 * Add order items to the database.
	 *
	 * @throws \Exception
	 */

	public function order_items() {

		if ( is_array( $this->order_items ) ) {

			foreach ( $this->order_items as $order_item ) {

				if ( ! $item_id = $this->item_exists( $order_item ) ) {

					$item_id = wc_add_order_item( $this->order_id, [
						'order_item_name' => $order_item['order_item_name'],
						'order_item_type' => $order_item['order_item_type']
					] );

				}

				if ( $item_id && is_array( $order_item['order_itemmeta'] ) ) {
					$this->order_item_meta( $item_id, $order_item['order_itemmeta'] );
				}

			}

		}

	}

	/**
	 * Item exists.
	 *
	 * @param $order_item
	 *
	 * @return bool|int
	 */

	public function item_exists( $order_item ) {

		global $wpdb;

		$item_id = $wpdb->get_var( $wpdb->prepare(
			"
			SELECT order_item_id
			FROM {$wpdb->prefix}woocommerce_order_items
			WHERE order_id = %d
			AND order_item_name = %s  
			AND order_item_type = %s
			",
			intval( $this->order_id ),
			esc_sql( $order_item['order_item_name'] ),
			esc_sql( $order_item['order_item_type'] )
		) );

		if ( empty( $item_id ) || is_wp_error( $item_id ) ) {
			return FALSE;
		}

		return (int) $item_id;

	}

	/**
	 * Order item meta.
	 *
	 * @param int   $item_id
	 * @param array $order_itemmeta
	 *
	 * @throws \Exception
	 */

	public function order_item_meta( $item_id, $order_itemmeta ) {

		// TODO: what do we do if we have a _product_id, maybe nothing...
		foreach ( $order_itemmeta as $itemmeta ) {
			wc_update_order_item_meta( $item_id, $itemmeta[' meta_key'], $itemmeta[' meta_value'] );
		}

	}

}