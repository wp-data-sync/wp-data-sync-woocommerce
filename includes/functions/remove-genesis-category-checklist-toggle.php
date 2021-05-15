<?php
/**
 * Remove Genesis Category Checklist Toggle
 *
 * Remove Genesis category checklist toggle from brand taxonomy.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo\App;

add_action( 'admin_enqueue_scripts', function() {

	$theme = wp_get_theme();
	if ( 'genesis' !== $theme->get_template() ) {
		return;
	}

	$screen = get_current_screen();

	if ( 'product' === $screen->id ) {

		$taxonomy_key = brand_taxonomy_key();

		wp_add_inline_script( 'jquery',
			"
			setTimeout( function() {
				jQuery('#taxonomy-$taxonomy_key #genesis-category-checklist-toggle').remove();
			}, 1000 );
			"
		);

	}

}, 999 );