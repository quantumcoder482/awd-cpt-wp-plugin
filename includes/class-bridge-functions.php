<?php
/**
* ACF Bridge Functions
* 
* @link     https://austinwebanddesign.com
* @since    1.0.0
*
* @package  AWD Resource Archive
* @author   Austin Web & Design
*/

// No direct access
defined( "ABSPATH" ) OR die();

class AWD_Bridge_Functions {
	public function _construct() {
		$this->group_actions();
	}

	public function group_actions() {
		add_action('acf/render_field_settings', array( $this, 'awd_external_link_field_settings') );
		//add_filter('acf/prepare_field/type=url', array( $this, 'awd_external_link_prepare_field') );
	}

	public function awd_external_link_field_settings( $field ) {
		acf_render_field_setting( $field, array(
		'label'			=> __('Show only on link posts?'),
		'instructions'	=> '',
		'name'			=> 'awd_external_link',
		'type'			=> 'true_false',
		'ui'			=> 1,
	), true);
	}

	public function awd_external_link_prepare_field( $field ) {
		
		// hide field if 'awd_external_link' isn't enabled
		if( empty($field['awd_external_link']) ) return false;		
		
		// return
		return $field;
	}
}