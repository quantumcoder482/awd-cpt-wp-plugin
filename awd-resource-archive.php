<?php
/**
* Plugin Name: AWD Resource Archive
* Description: Custom post layouts and searchable/filterable archive
* Author: Austin Web & Design
* Author URI: https://www.austinwebanddesign.com/
* Text Domain: awd-resource-archive
*/

// No direct access
defined( "ABSPATH" ) OR die();

/**
 * Currently plugin version.
 */
define( 'AWD_RESOURCE_ARCHIVE_VERSION', '1.0.0' );

// Set plugin paths
define( "AWDRA_FILE", __FILE__ );
define( "AWDRA_DIR", plugin_dir_path( AWDRA_FILE ) );
define( "AWDRA_URL", plugin_dir_url( AWDRA_FILE ) );

// Get main plugin file
require_once( AWDRA_DIR . "includes/resource-archive.php" );

// Start the engine
new AWDResourceArchive();