<?php
/**
 * WC_WP_DataSync_Pos
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

class WC_WP_DataSync_Pos extends Core {

	/**
	 * @var WC_WP_DataSync_Pos
	 */

	public static $instance;

	/**
	 * WC_WP_DataSync_Pos constructor.
	 */

	public function __construct() {
		self::$instance  = $this;
	}

	/**
	 * Instance.
	 *
	 * @return WC_WP_DataSync_Pos
	 */

	public static function instance() {

		if ( self::$instance === NULL ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Register the pos route.
	 */

	public function register_route() {

		register_rest_route(
			'wp-data-sync/1.0/',
			'pos/(?P<access_token>\S+)/(?P<order_id>\d+)',
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
	 * Get Order.
	 *
	 * @param WP_REST_Request $request
	 */

	public function get_order( WP_REST_Request $request ) {

		$order_id = $request['order_id'];

		$order = wc_get_order( $order_id );

		wp_send_json( $order, 200 );

	}

}

add_action( 'rest_api_init', function() {
	$pos = WC_WP_DataSync_Pos::instance();
	$pos->register_route();
} );