<?php
/**
* AWD Layouts
* 
* @link     https://austinwebanddesign.com
* @since    1.0.0
*
* @package  AWD Resource Archive
* @author   Austin Web & Design
*/

// No direct access
defined( "ABSPATH" ) OR die();

class AWDLayouts {
    /**
     * Helper class for repetitive tasks
     * @var obj
     */
    protected $helper;

    /**
    * The construct
    */
    public function __construct() {

        $this->load_dependencies();
        $this->group_actions();
        $this->assign_templates();
    }
    
    private function load_dependencies() {
        // Get helper class file
        require_once( AWDRA_DIR . "includes/class-helper.php" );
    }
    /**
    * Group Actions
    */
    private function group_actions() {
        add_filter( "the_content", array( $this, "get_content" ) );
        add_action( "wp_enqueue_scripts", array( $this, "add_sas" ) );
    }

    /**
     *  Assign template files for archive and single post type
     */
    public function assign_templates(){
        // add_filter( 'single_template', array( $this, 'myplugin_single_template' ) );
        // add_filter( 'archive_template', array( $this, 'myplugin_archive_template' ) );
        add_filter('template_include', array( $this, 'awd_set_template' ), 99 );
    }

    function awd_set_template( $template ) {
        $new_template = '';
        if( is_singular('resource') ) {
            $new_template = 'single-resource.php';
        }
        if( is_post_type_archive('resource') ) {
            $new_template = 'archive-resource.php';
        }
        $plugin_template = AWDRA_DIR . 'templates/' . $new_template;

        if( !empty( $new_template ) && file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
        return $template;
    }

    function myplugin_single_template( $template ) {

        global $post;

        if ($post->post_type === 'resource') {
            $single_template = AWDRA_DIR . 'templates/archive-resource.php';
            return $single_template;
        }
         return $template;

    }

    function myplugin_archive_template( $template ) {

        global $post;

        if ($post->post_type === 'resource') {
            $archive_template = AWDRA_DIR . 'templates/single-resource.php';
            return $archive_template;
        }

        return $template;

    }

    /**
    * Get Content
    * Gets ACF flexible field content
    */
    public function get_content( $content ) {
       if( ( 'resource' != get_post_type() ) AND !have_rows('flexible_sections') ) {
        //    echo "<script>console.log('ouch');</script>";
           return $content;
       }
       // Create helper object with this post's ID
       $this->helper = new AWDHelper();

       $new_content = '';

       while( have_rows('flexible_sections') ) {
           the_row();

           $section_type = get_row_layout();

           $section_template = AWDRA_DIR . "templates/$section_type.php";

           if( is_file( $section_template ) ) {
                ob_start();
                include $section_template;
                $new_content .= ob_get_clean();
           }
       }
       return $new_content ?? 'No content found.';
    }


    
    /**
    * Add SaS
    * Adds styles and scripts
    */
    public function add_sas() {
        
        // Style
        wp_enqueue_style( 'resource-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0' );
        wp_enqueue_style( "awdra-css", AWDRA_URL . "css/style.css" );
        
        // Script
        wp_enqueue_script( 'bootstrap-validator', '//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js', array(), '0.11.9', true);
        wp_enqueue_script( "awdra-js",  AWDRA_URL. "js/script.js", array('jquery', 'wp-i18n'), '1.0.0', true );
        
        wp_localize_script( 'awdra-js', 'settings', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'translations' => array(
                /* translators: "Share this page" error message */
                "loading_results" => __( "Loading results...", "awd-resource-archive" ),
                /* translators: "Share this page" error message */
                "cant_send_email" => __( "Sorry, there was an issue. Can't send email.", "awd-resource-archive" ),
                /* translators: "Share this page" error message */
                "please_input_name_email" => __( "Please enter email and name correctly!", "awd-resource-archive" ),
                /* translators: "Share this page" error message */
                "email_blank" => __( "Please enter email address", "awd-resource-archive" ),
                /* translators: "Share this page" error message */
                "name_blank" => __( "Please enter name", "awd-resource-archive" ),
                /* translators: "Share this page" error message */
                "email_invalid_format" => __( "Invalid Email Address Format", "awd-resource-archive" )
            )
        ) );
    }
}