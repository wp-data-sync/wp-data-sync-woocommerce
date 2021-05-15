<?php
/**
 * Add Custom Fields to Products
 *
 * Add custom fields to product data.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo\App;

/**
 * Add product data fields.
 */

add_action( 'woocommerce_product_options_general_product_data', function() {

	woocommerce_wp_text_input([
		'id'          => '_mpn',
		'label'       => __( 'MPN', 'wp-data-sync-woocommerce' ),
		'desc_tip'    => TRUE,
		'description' => __( 'Manufacturer Part Number', 'wp-data-sync-woocommerce' )
	]);

	woocommerce_wp_text_input([
		'id'          => '_gtin8',
		'label'       => __( 'GTIN', 'wp-data-sync-woocommerce' ),
		'desc_tip'    => TRUE,
		'description' => __( 'Global Trade Item Number', 'wp-data-sync-woocommerce' )
	]);

	woocommerce_wp_text_input([
		'id'          => '_isbn',
		'label'       => __( 'ISBN', 'wp-data-sync-woocommerce' ),
		'desc_tip'    => TRUE,
		'description' => __( 'International Standard Book Number', 'wp-data-sync-woocommerce' )
	]);

} );

/**
 * Save product data fields.
 */

add_action('woocommerce_process_product_meta', function( $product_id ) {

	$product = wc_get_product( $product_id );

	$_mpn = isset( $_POST['_mpn'] ) ? sanitize_text_field( $_POST['_mpn'] ) : '';
	$product->update_meta_data( '_mpn', $_mpn );

	$_gtin8 = isset( $_POST['_gtin8'] ) ? sanitize_text_field( $_POST['_gtin8'] ) : '';
	$product->update_meta_data( '_gtin8', $_gtin8 );

	$_isbn = isset( $_POST['_isbn'] ) ? sanitize_text_field( $_POST['_isbn'] ) : '';
	$product->update_meta_data( '_isbn', $_isbn );

	$product->save();

});