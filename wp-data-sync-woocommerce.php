<?php
/**
 * Plugin Name: WP Data Sync - WooCommerce
 * Plugin URI:  https://wpdatasync.com/products/
 * Description: Sync raw product data into your WooCommerce Store.
 * Version:     1.0.6
 * Author:      WP Data Sync
 * Author URI:  https://wpdatasync.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-data-sync-woocommerce
 * Domain Path: /languages
 *
 * WC requires at least: 2.5
 * WC tested up to: 3.9.2
 *
 * Package:     WP_DataSync
 */

namespace WP_DataSync\App;

add_action( 'wp_data_sync_after_process', function( $post_id, $data ) {

	if ( class_exists( 'WooCommerce', FALSE ) ) {

		class WC_WP_DataSync extends DataSync {

			/**
			 * @var WC_WP_DataSync
			 */

			public static $instance;

			/**
			 * WC_WP_DataSync constructor.
			 */

			public function __construct() {
				parent::__construct();
				self::$instance = $this;
			}

			/**
			 * Instabce.
			 *
			 * @return DataSync|WC_WP_DataSync
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
			 * @param $post_id
			 * @param $data
			 */

			public function wc_process( $post_id, $data ) {

				/**
				 * Extract
				 *
				 * $attributes
				 * $attribute_taxonomies
				 * $product_gallery
				 */

				extract( $data );

				if ( isset( $attributes ) ) {
					$this->attributes( $post_id, $attributes, 0 );
				}

				if ( isset( $attribute_taxonomies ) ) {
					$this->attributes( $post_id, $attribute_taxonomies, 1 );
				}

				if ( isset( $product_gallery ) ) {
					$this->product_gallery( $post_id, $product_gallery );
				}

			}

			/**
			 * Product attributes.
			 *
			 * @param $post_id
			 * @param $attributes
			 * @param $is_taxonomy
			 */

			public function attributes( $post_id, $attributes, $is_taxonomy ) {

				if ( empty( $attributes ) ) {
					return;
				}

				$product_attributes = get_post_meta( $post_id, '_product_attributes', TRUE ) ?: [];

				foreach ( $attributes as $attribute ) {

					extract( $attribute );

					if ( $is_taxonomy ) {

						$taxonomy   = $this->attribute_taxonomy( $name );
						$term_names = wp_get_object_terms( $post_id, $taxonomy, [ 'fields' => 'names' ] );
						$term_ids   = [];

						foreach ( $values as $value ) {

							if ( ! in_array( $value, $term_names ) ) {
								$term_ids[] = $this->term_id( $value, $taxonomy, 0 );
							}

						}

						wp_set_object_terms( $post_id, $term_ids, $taxonomy );

					}

					$product_attributes[ $name ] = [
						'name'         => $is_taxonomy ? $taxonomy : $name,
						'value'        => $is_taxonomy ? 0 : join( ',', $values ),
						'position'     => 0,
						'is_visible'   => 1,
						'is_variation' => 0,
						'is_taxonomy'  => $is_taxonomy
					];

				}

				$product_attributes = apply_filters( 'wp_data_sync_product_attributes', $product_attributes );

				update_post_meta( $post_id, '_product_attributes', $product_attributes );

				do_action( 'wp_data_sync_attributes', $post_id, $attributes );
				do_action( 'wp_data_sync_attribute_taxonomiess', $post_id, $attributes );

			}

			/**
			 * Attribute taxonomy.
			 *
			 * @param $raw_name
			 *
			 * @return array
			 */

			public function attribute_taxonomy( $raw_name ) {

				// These are exported as labels, so convert the label to a name if possible first.
				$attribute_labels = wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name' );
				$attribute_name   = array_search( $raw_name, $attribute_labels, TRUE );

				if ( ! $attribute_name ) {
					$attribute_name = wc_sanitize_taxonomy_name( $raw_name );
				}

				$attribute_id  = wc_attribute_taxonomy_id_by_name( $attribute_name );
				$taxonomy_name = wc_attribute_taxonomy_name( $attribute_name );

				if ( $attribute_id ) {
					return $taxonomy_name;
				}

				// If the attribute does not exist, create it.
				$attribute_id = wc_create_attribute( [
					'name'         => $raw_name,
					'slug'         => $attribute_name,
					'type'         => 'select',
					'order_by'     => 'menu_order',
					'has_archives' => FALSE,
				] );

				// Register as taxonomy while importing.
				register_taxonomy(
					$taxonomy_name,
					apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_name, [ 'product' ] ),
					apply_filters( 'woocommerce_taxonomy_args_' . $taxonomy_name, [
						'labels'       => [
							'name' => $raw_name,
						],
						'hierarchical' => TRUE,
						'show_ui'      => FALSE,
						'query_var'    => TRUE,
						'rewrite'      => FALSE,
					] )
				);

				return $taxonomy_name;;

			}

			/**
			 * Create a WooCommerce image gallery.
			 *
			 * @param $post_id
			 * @param $product_gallery
			 */

			public function product_gallery( $post_id, $product_gallery ) {

				$attach_ids = [];

				foreach ( $product_gallery as $image_url ) {

					if ( $attach_id = $this->attachment( $post_id, $image_url ) ) {
						$attach_ids[] = $attach_id;
					}

				}

				$product_gallery_ids = apply_filters( 'wp_data_sync_product_gallery_ids', join( ',', $attach_ids ) );
				$product_gallery_key = apply_filters( 'wp_data_sync_product_gallery_meta_key', '_product_image_gallery' );

				update_post_meta( $post_id, $product_gallery_key, $product_gallery_ids );

				do_action( 'wp_data_sync_product_gallery', $post_id, $product_gallery );

			}

		}

		$wc_sync = WC_WP_DataSync::instance();
		$wc_sync->wc_process( $post_id, $data );

	}

}, 10, 2 );

/**
 * Show a notice if the WP Data Sync plugin is not activated.
 */

add_action( 'admin_notices', function() {

	if ( is_plugin_active( 'wp-data-sync/wp-data-sync.php' ) ) {
		return;
	}

	$class = 'notice notice-error';
	$message = __( 'NOTICE: The WP Data Sync plugin is required to use the WP Data Sync - WooCommerce extension.', 'wp-data-sync' );

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );

} );

/**
 * Load the plugin text domain.
 */

add_action( 'init', function() {
	load_plugin_textdomain( 'wp-data-sync-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

/**
 * Add settings link to plugin action links.
 */

add_filter( 'plugin_action_links', function( $links, $file ) {

	$this_plugin = plugin_basename( __FILE__ );

	if ( $file === $this_plugin ) {

		$links[] = '<a href="options-general.php?page=wp-data-sync">' . __( 'Settings' ) . '</a>';

	}

	return $links;

}, 10, 2 );
