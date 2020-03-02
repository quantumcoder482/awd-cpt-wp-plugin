<?php
/**
 * Helper class for repetitive tasks
 */

// No direct access
defined( "ABSPATH" ) OR die();

class AWDHelper {
	/**
	 * Post ID
	 * @var int
	 */
	protected $id;

	public function __construct( $id ) {
		$this->id = $id;
	}

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
}