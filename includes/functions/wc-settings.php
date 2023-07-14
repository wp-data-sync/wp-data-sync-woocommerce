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
		'key' 		=> 'wp_data_sync_allow_duplicate_sku',
		'label'		=> __( 'Allow Duplicate SKU', 'wp-data-sync-woocommerce' ),
		'callback'  => 'input',
		'args'      => [
			'sanitize_callback' => 'sanitize_text_field',
			'basename'          => 'checkbox',
			'type'		        => '',
			'class'		        => 'allow-duplicate-sku',
			'placeholder'       => '',
			'info'              => __( 'Allow WooCommerce to use the same SKU for multiple products or variations. This can cause issues with some functionality. Proceed at your own risk.', 'wp-data-sync-woocommerce' )
		]
	];

	$settings['woocommerce'][] = [
		'key' 		=> 'wp_data_sync_update_duplicate_fields',
		'label'		=> __( 'Update Duplicate Fields', 'wp-data-sync' ),
		'callback'  => 'input',
		'args'      => [
			'sanitize_callback' => [ $_settings, 'sanitize_array' ],
			'basename'          => 'select-multiple',
			'name'              => 'wp_data_sync_update_duplicate_fields',
			'type'		        => '',
			'class'		        => 'update-duplicate-fields regular-text',
			'placeholder'       => '',
			'info'              => __( 'When multiple products use the same indetifier, update the selected fields for all products with a duplicate indetifier value.', 'wp-data-sync' ),
			'selected'          => get_option( 'wp_data_sync_update_duplicate_fields', [ 'none' ] ),
			'options'           => apply_filters( 'wp_data_sync_update_duplicate_field_options', [
				'none'              => __( 'None'),
				'_manage_stock'     => __( 'Manage Stock', 'woocommerce' ),
				'_stock'            => __( 'Stock Quantity', 'woocommerce' ),
				'_backorders'       => __( 'Allow Backorders', 'woocommerce' ),
				'_low_stock_amount' => __( 'Low Stock Threshold', 'woocommerce' ),
				'_regular_price'    => __( 'Regular Price', 'woocommerce' ),
				'_sale_price'       => __( 'Sale Price', 'woocommerce' ),
				'_weight'           => __( 'Weight', 'woocommerce' ),
				'_length'           => __( 'Length', 'woocommerce' ),
				'_width'            => __( 'Width', 'woocommerce' ),
				'_height'           => __( 'Height', 'woocommerce' )
			], $_settings )
		]
	];

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

	$settings['woocommerce'][] = [
		'key' 		=> 'wp_data_sync_regular_price_adjustment',
		'label'		=> __( 'Regular Price Adjustment (%)', 'wp-data-sync-woocommerce' ),
		'callback'  => 'input',
		'args'      => [
			'sanitize_callback' => 'floatval',
			'basename'          => 'text-input',
			'type'		        => 'number',
			'class'		        => 'regular-price-adjustment',
			'placeholder'       => '',
			'info'              => __( 'Multiply price to add/subtract price adjustment.', 'wp-data-sync-woocommerce' )
		]
	];

	$settings['woocommerce'][] = [
		'key' 		=> 'wp_data_sync_sale_price_adjustment',
		'label'		=> __( 'Sale Price Adjustment (%)', 'wp-data-sync-woocommerce' ),
		'callback'  => 'input',
		'args'      => [
			'sanitize_callback' => 'floatval',
			'basename'          => 'text-input',
			'type'		        => 'number',
			'class'		        => 'sale-price-adjustment',
			'placeholder'       => '',
			'info'              => __( 'Multiply price to add/subtract price adjustment.', 'wp-data-sync-woocommerce' )
		]
	];

	return $settings;

}, 99, 2 );
