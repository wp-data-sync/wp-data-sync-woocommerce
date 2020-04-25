<?php
/**
 * WC_WP_DataSync
 *
 * WP Data Sync for WooCommerce methods
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\App;

class WC_WP_DataSync {

	/**
	 * @var WC_WP_DataSync
	 */

	public static $instance;

	/**
	 * @var DataSync
	 */

	private $data_sync;

	/**
	 * WC_WP_DataSync constructor.
	 *
	 * @param $data_sync
	 */

	public function __construct( $data_sync ) {

		$this->data_sync = $data_sync;

		self::$instance  = $this;

	}

	/**
	 * Instance.
	 *
	 * @param $data_sync
	 *
	 * @return WC_WP_DataSync
	 */

	public static function instance( $data_sync ) {

		if ( self::$instance === NULL ) {
			self::$instance = new self( $data_sync );
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
		 * $taxonomies
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

		$this->product_visibility( $post_id, $taxonomies );

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
						$term_ids[] = $this->data_sync->term_id( $value, $taxonomy, 0 );
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

			if ( $attach_id = $this->data_sync->attachment( $post_id, $image_url ) ) {
				$attach_ids[] = $attach_id;
			}

		}

		$product_gallery_ids = apply_filters( 'wp_data_sync_product_gallery_ids', join( ',', $attach_ids ) );
		$product_gallery_key = apply_filters( 'wp_data_sync_product_gallery_meta_key', '_product_image_gallery' );

		update_post_meta( $post_id, $product_gallery_key, $product_gallery_ids );

		do_action( 'wp_data_sync_product_gallery', $post_id, $product_gallery );

	}

	/**
	 * Product visibility.
	 *
	 * @param $post_id
	 * @param $taxonomies
	 */

	public function product_visibility( $post_id, $taxonomies ) {

		$taxonomy = 'product_visibility';

		if ( is_array( $taxonomies ) && array_key_exists( $taxonomy, $taxonomies ) ) {
			return;
		}

		if ( $terms = get_option( 'wp_data_sync_product_visibility' ) ) {

			$term_ids = [];

			if ( 'null' !== $terms ) {

				$terms = explode( ',', $terms );

				foreach ( $terms as $term ) {
					$term_ids[] = $this->data_sync->term_id( $term, $taxonomy, 0 );
				}

			}

			wp_set_object_terms( $post_id, $term_ids, $taxonomy );

		}

	}

}