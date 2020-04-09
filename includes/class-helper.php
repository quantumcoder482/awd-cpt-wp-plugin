<?php
/**
 * Helper class for repetitive tasks
 */

// No direct access
defined( "ABSPATH" ) OR die();

class AWDHelper {
	/**
	 * Set up section wrapper classes and ids.
	 * @param  string	$section_classes	any section-specific classes to add
	 * @return string 	classes and id to insert into section wrapper
	 */
	public function setup_wrapper_atts( $section_classes ) {
		// Set up wrapper classes and id
		$container_attributes = 'class="awd-sec ' . $section_classes;

		$parent_class = get_sub_field('parent_class');
		$container_attributes .= $parent_class ? ' ' . $parent_class . '"' : '"';

		$section_id = get_sub_field('section_id');
		$container_attributes .= $section_id ? ' id="' . $section_id . '"' : '';

		return $container_attributes;
	}

	public function phone_number_formatter( $number ) {
		$number_formats = array();

		$number_formats['raw'] = preg_replace( "/[^\d]/", "", $number );

		$length = strlen( $number_formats['raw'] );

		if( 10 <= $length ) {
			$chunks = array(
					'country'	=> substr( $number_formats['raw'], 0, ( $length - 10 ) ),
					'area'		=> substr( $number_formats['raw'], ( $length - 10 ), 3 ),
					'first'		=> substr( $number_formats['raw'], ( $length - 7 ), 3 ),
					'last'		=> substr( $number_formats['raw'], ( $length - 4 ), 4 ),
					);
			$number_formats['formatted'] = $chunks['country'] ? '+' . $chunks['country'] . ' ' : '';
			$number_formats['formatted'] .= '(' . $chunks['area'] . ') ' . $chunks['first'] . '-' . $chunks['last'];
		} else {
			$number_formats['formatted'] = $number;
		}

		return $number_formats;
	}

	/**
	 * Get primary category as set by Yoast
	 * @param  int		$post_id	Post ID of post to query
	 * @param  string	$taxonomy	Taxonomy to query
	 * @return object           	WP_Term object for primary category if set, otherwise first category
	 */
	public function get_primary_category( $post_id, $taxonomy ) {
		$category = get_the_terms( $post_id, $taxonomy );

		// Get primary (Yoast) term if it is set
		if ( class_exists('WPSEO_Primary_Term') ) {

		     // Show the post's 'Primary' category, if this Yoast feature is available, & one is set
			$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
			$wpseo_primary_term = $wpseo_primary_term->get_primary_term();
			$term = get_term( $wpseo_primary_term );

		     if ( !is_wp_error( $term ) ) {

		          // Return the primary category if set
		          return $term;

		     }

		}

		// If Yoast isn't available or if primary category isn't set, fall back to the first category in WP's list of assigned categories
		return $category[0];
	}
}