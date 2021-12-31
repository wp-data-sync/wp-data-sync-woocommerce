<?php
/**
 * Settings
 *
 * Plugin settings
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_data_sync_settings', function( $settings, $_settings ) {

	$settings['woocommerce'][] = [
		'key' 		=> 'wp_data_sync_manage_backorder_status',
		'label'		=> __( 'Manage Backorder Status', 'wp-data-sync-woocommerce' ),
		'callback'  => 'input',
		'args'      => [
			'sanitize_callback' => 'sanitize_text_field',
			'basename'          => 'checkbox',
			'type'		        => '',
			'class'		        => '',
			'placeholder'       => '',
			'info'              => __( 'Set backorder status based on stock quantity. NOTE: Allow backorders must be set for each product.', 'wp-data-sync-woocommerce' )
		]
	];

	$settings['woocommerce'][] = [
		'key' 		=> 'wp_data_sync_convert_product_weight',
		'label'		=> __( 'Convert Product Weight', 'wp-data-sync-woocommerce' ),
		'callback'  => 'input',
		'args'      => [
			'sanitize_callback' => 'sanitize_text_field',
			'basename'          => 'select',
			'selected'          => get_option( 'wp_data_sync_convert_product_weight' ),
			'name'              => 'wp_data_sync_convert_product_weight',
			'class'             => 'convert-product-weight widefat',
			'info'              => __( 'Convert the product weight. If you do not see the conversion you need. Please contact our support team to have it added.', 'wp-data-sync-woocommerce' ),
			'values'            => [
				'0'               => __( 'Do Not Convert Weight', 'wp-data-sync-woocommerce' ),
				'grams_kilograms' => __( 'Grams to Kilograms', 'wp-data-sync-woocommerce' ),
				'kilograms_grams' => __( 'Kilograms to Grams', 'wp-data-sync-woocommerce' ),
				'ounces_pounds'     => __( 'Pounds to Ounces', 'wp-data-sync-woocommerce' ),
				'pounds_ounces'     => __( 'Ounces to Pounds', 'wp-data-sync-woocommerce' ),
			]
		]
	];

	return $settings;

}, 99, 2 );
