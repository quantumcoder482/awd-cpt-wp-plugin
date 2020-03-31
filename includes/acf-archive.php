<?php

defined( "ABSPATH" ) OR die(); // Exit if accessed directly

class ACF_Archive {
    /**
     * ACF_Archive constructor.
     */
    public function __construct() {
        add_action( 'after_setup_theme', [ $this, 'boot' ] );
    }

    /**
     * @return \ACF_Archive
     */
    public static function instance() {
        static $instance;

        if ( null !== $instance ) {
            return $instance;
        }

        return $instance = new static;
    }

    /**
     * Start the plugin
     * @return void
     */
    public function boot() {
        if ( ! class_exists( 'ACF' ) ) {
            add_action( 'admin_notices', [ $this, 'acf_installed_notify' ] );
            return;
        }
        
        add_action('acf/init', array( $this, 'my_acf_op_init') );
    }

    /**
     * @return void
     */
    public function acf_installed_notify() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
            	<?php /* translators: Plugin dependency notice */
            	_e( '<strong>ACF Archive is not working</strong>, Advanced Custom Fields is not installed.', 'awd-resource-archive' ); ?>
            </p>
        </div>
        <?php
    }

    /**
     * ACF Options Sub Page
     */
	public function my_acf_op_init() {

	    // Check function exists.
	    if( function_exists('acf_add_options_sub_page') ) {

	        $archive_option_page = acf_add_options_sub_page(array(
	        	'parent_slug' => 'edit.php?post_type=resource',
	        	/* translators: Backend archive screen title */
	            'page_title'  => __('Resource Archive'),
	            /* translators: Backend menu title */
	            'menu_title'  => __('Archive Settings'),
	            'capability'  => 'edit_posts'
	        ));
	    }
	}
}

