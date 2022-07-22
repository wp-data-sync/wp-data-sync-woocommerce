<?php
/**
 * Scripts and Styles
 *
 * Add custom scripts and styles.
 *
 * @since   2.1.8
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load admin styles.
 */

add_action( 'admin_enqueue_scripts', function() {
	wp_enqueue_style( 'wpds_for_woo_css', WPDS_FOR_WOO_ASSETS . 'css/admin.css', [], WPDS_FOR_WOO_VERSION );
} );
