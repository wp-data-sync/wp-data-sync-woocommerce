<?php
/**
 * WC_DataSync_Pos
 *
 * WP Data Sync for WooCommerce Pos methods
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\App;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;

if ( ! class_exists( 'Core' ) ) {
	return;
}

class WC_DataSync_Pos extends Core {

	/**
	 * @var WC_DataSync_Pos
	 */

	public static $instance;

	/**
	 * WC_DataSync_Pos constructor.
	 */

	public function __construct() {
		self::$instance  = $this;
	}

	/**
	 * Instance.
	 *
	 * @return WC_DataSync_Pos
	 */

	public static function instance() {

		if ( self::$instance === NULL ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register order route.
	 */

	public function register_order_route() {

		register_rest_route(
			'wp-data-sync/1.0/',
			'order/(?P<access_token>\S+)/(?P<order_id>\d+)',
			[
				'methods' => WP_REST_Server::READABLE,
				'args'    => [
					'access_token' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'access_key' ]
					],
					'order_id' => [
						'sanitize_callback' => 'absint',
						'validate_callback' => function( $param ) {
							return is_numeric( $param );
						}
					]
				],
				'permission_callback' => [ $this, 'access' ],
				'callback'            => [ $this, 'get_order' ],
			]
		);

	}

	/**
	 * Resister pos route.
	 */

	public function register_pos_route() {

		register_rest_route(
			'wp-data-sync/1.0/',
			'pos/(?P<access_token>\S+)',
			[
				'methods' => WP_REST_Server::EDITABLE,
				'args'    => [
					'access_token' => [
						'sanitize_callback' => 'sanitize_text_field',
						'validate_callback' => [ $this, 'access_key' ]
					]
				],
				'permission_callback' => [ $this, 'access' ],
				'callback'            => [ $this, 'update_pos' ],
			]
		);

	}

	/**
	 * Get order.
	 *
	 * @param $params
	 */

	public function get_order( $params ) {

		$order_id = $params['order_id'];

		$order = wc_get_order( $order_id );

		wp_send_json( $order, 200 );

	}

	/**
	 * Update pos data.
	 *
	 * @param WP_REST_Request $request
	 */

	public function update_pos( WP_REST_Request $request ) {

		$raw_data = $request->get_params();
		$data     = $this->sanitize_request( $raw_data );

		if ( $post_id = $this->post_id( $data['primary_id'] ) ) {

			if ( is_array( $data['pos_values'] ) ) {

				foreach ( $data['pos_values'] as $key => $value ) {
					update_post_meta( $post_id, $key, $value );
				}

			}

		}

	}

}

add_action( 'rest_api_init', function() {
	$pos = WC_DataSync_Pos::instance();
	$pos->register_order_route();
	$pos->register_pos_route();
} );