<?php
/**
 * Brand Taxonomy Radio Buttons
 *
 * Convert the brand taxonomy checkboxes to radio buttons.
 *
 * @since   1.0.0
 *
 * @package WP_DataSync
 */

namespace WP_DataSync\Woo\App;

use Walker_Category_Checklist;

/**
 * Brand taxonomy radio buttons.
 *
 * @param $args
 * @param $post_id
 *
 * @return mixed
 */

add_filter( 'wp_terms_checklist_args', function( $args, $post_id ) {

	if ( ! empty( $args['taxonomy'] ) &&  brand_taxonomy_key() === $args['taxonomy'] ) {

		// Don't override 3rd party walkers.
		if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) {

			class WPDS_Category_Radio extends Walker_Category_Checklist {

				public function start_el( &$output, $category, $depth = 0, $args = [], $id = 0 ) {

					$output .= sprintf(
						'<li id="%s-%s"><label class="selectit"><input value="%s" type="radio" name="tax_input[%s][]" id="in-%s-%s" %s %s/> %s</label></li>',
						esc_attr( $args['taxonomy'] ),
						esc_attr( $category->term_id ),
						esc_attr( $category->term_id ),
						esc_attr( $args['taxonomy'] ),
						esc_attr( $args['taxonomy'] ),
						esc_attr( $category->term_id ),
						esc_attr( checked( in_array( $category->term_id, $args['selected_cats'] ), true, false ) ),
						esc_attr( disabled( empty( $args['disabled'] ), false, false ) ),
						esc_html( apply_filters( 'the_category', $category->name ) )
					);

				}

			}

			$args['walker'] = new WPDS_Category_Radio;

		}

	}

	return $args;

}, 10, 2 );
