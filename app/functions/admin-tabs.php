<?php
/**
 * Admin Tabs
 *
 * Add admin tabs to the WP Data Sync admin page.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

add_action( 'wp_data_sync_admin_tabs', function( $tabs ) {

	$tabs = array_merge( $tabs, [
		0 => [
			'label' => __( 'WooCommerce' ),
			'id'    => 'woocommerce'
		]
	] );

	return $tabs;

} );