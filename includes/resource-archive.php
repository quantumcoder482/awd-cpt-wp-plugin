<?php
/**
* AWD Resource Archive
* 
* @link     https://austinwebanddesign.com
* @since    1.0.0
*
* @package  AWD Resource Archive
* @author   Austin Web & Design
*/

// No direct access
defined( "ABSPATH" ) OR die();

class AWDResourceArchive {
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The front-end layouts this plugin will output
     * @since    1.0.0
     * @access   protected
     * @var AWDLayouts $awd_layouts     Registers and constructs page layouts
     */
    protected $awd_layouts;

    /**
     * The shortcodes this plugin creates
     * @since 1.0.0
     * @access   protected
     * @var AWDShortcodes   $awd_shortcodes     Registers the shortcodes included in the plugin.
     */
    protected $awd_shortcodes;

    /**
     * The acf archive for resource archive
     * @since 1.0.0
     * @access   protected
     * @var ACF_Archive   $awd_acf_archive     Registers the acf archive
     */
    protected $awd_acf_archive;

    /**
     * The construct
     */
    public function __construct() {

        if ( defined( 'AWD_RESOURCE_ARCHIVE_VERSION' ) ) {
            $this->version = AWD_RESOURCE_ARCHIVE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'awd-resource-archive';

        $this->load_dependencies();
        $this->add_layouts();
        $this->add_shortcodes();
        $this->add_acf_archive();

        // add custom post type and taxonomies
        add_action( 'init', array( $this, 'awd_resource_archive' ), 0 );
        add_action( 'init', array( $this, 'awd_resource_category' ), 0 );
        add_action( 'init', array( $this, 'trimester_taxonomy' ), 0 );

        // Archive Ajax
        add_action( 'wp_ajax_nopriv_get_resource_posts', array( $this, 'get_posts_ajax' ) );
        add_action( 'wp_ajax_get_resource_posts', array( $this, 'get_posts_ajax' ) );

        // Email Ajax
        add_action( 'wp_ajax_nopriv_awd_send_email', array( $this, 'send_email' ) );
        add_action( 'wp_ajax_awd_send_email', array( $this, 'send_email' ) );

        // Get Custom Post URI
        add_action( 'wp_ajax_nopriv_get_custom_post_link', array( $this, 'get_custom_post_url' ) );
        add_action( 'wp_ajax_get_custom_post_link', array( $this, 'get_custom_post_url' ) );

        //* Add HR button to TinyMCE
        //* @link http://anythinggraphic.net/horizontal-rule-button-in-wordpress

        add_filter( 'mce_buttons', array( $this, 'ag_horizontal_rule_button') );
    
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - AWDLayouts. Registers and constructs page layouts.
     * - AWDShortcodes. Registers shortcodes to use on the site.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for assigning layouts to sections and pages
         */
        require_once( AWDRA_DIR . "includes/layout.php" );

        /**
         * The class responsible for preparing this plugin's shortcodes for use
         */
        require_once( AWDRA_DIR . "includes/shortcodes.php" );

        /**
         * The class responsible for acf archive for resource post type
         */

        require_once( AWDRA_DIR . "includes/acf-archive.php" );
    }

    private function add_layouts() {
        $this->awd_layouts = new AWDLayouts();
    }

    private function add_shortcodes() {
        $this->awd_shortcodes = new AWDShortcodes(); 
    }

    private function add_acf_archive() {
        $this->awd_acf_archive = new ACF_Archive();
    }

    /**
     * Post Type: Pregnancy Resources.
     */

    public function awd_resource_archive(){
        
        $labels = array(
            'name'                  => _x( 'Resources', 'Post Type General Name', 'awd-resource-archive' ),
            'singular_name'         => _x( 'Resource', 'Post Type Singular Name', 'awd-resource-archive' ),
            'menu_name'             => __( 'Resources', 'awd-resource-archive' ),
            'name_admin_bar'        => __( 'Resources', 'awd-resource-archive' ),
            'archives'              => __( 'Resource Archives', 'awd-resource-archive' ),
            'attributes'            => __( 'Resource Attributes', 'awd-resource-archive' ),
            'parent_item_colon'     => __( 'Parent Resource:', 'awd-resource-archive' ),
            'all_items'             => __( 'All Resources', 'awd-resource-archive' ),
            'add_new_item'          => __( 'Add New Resource', 'awd-resource-archive' ),
            'add_new'               => __( 'Add New', 'awd-resource-archive' ),
            'new_item'              => __( 'New Resource', 'awd-resource-archive' ),
            'edit_item'             => __( 'Edit Resource', 'awd-resource-archive' ),
            'update_item'           => __( 'Update Resource', 'awd-resource-archive' ),
            'view_item'             => __( 'View Resource', 'awd-resource-archive' ),
            'view_items'            => __( 'View Resources', 'awd-resource-archive' ),
            'search_items'          => __( 'Search Resource', 'awd-resource-archive' ),
            'not_found'             => __( 'Not found', 'awd-resource-archive' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'awd-resource-archive' ),
            'featured_image'        => __( 'Featured Image', 'awd-resource-archive' ),
            'set_featured_image'    => __( 'Set featured image', 'awd-resource-archive' ),
            'remove_featured_image' => __( 'Remove featured image', 'awd-resource-archive' ),
            'use_featured_image'    => __( 'Use as featured image', 'awd-resource-archive' ),
            'insert_into_item'      => __( 'Insert into resource', 'awd-resource-archive' ),
            'uploaded_to_this_item' => __( 'Uploaded to this resource', 'awd-resource-archive' ),
            'items_list'            => __( 'Resources list', 'awd-resource-archive' ),
            'items_list_navigation' => __( 'Resources list navigation', 'awd-resource-archive' ),
            'filter_items_list'     => __( 'Filter resources list', 'awd-resource-archive' ),
        );
        $args = array(
            'label'                 => __( 'Resource', 'awd-resource-archive' ),
            'description'           => __( 'Resource Posts', 'awd-resource-archive' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'post-formats', 'excerpt' ),
            'taxonomies'            => array( 'resource_category' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-businesswoman',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'rewrite'               => array('slug' => 'pregnancy-resources', 'with_front' => true),
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        register_post_type( 'resource', $args );
    }

    public function awd_resource_category(){

        $labels = array(
            'name' => _x('Resource Categories', 'Taxonomy General Name', 'resource-archive'),
            'singular_name' => _x('Resource Category', 'Taxonomy Singular Name', 'resource-archive'),
            'menu_name' => __('Categories', 'resource-archive'),
            'all_items' => __('All Categories', 'resource-archive'),
            'parent_item' => __('Parent Category', 'resource-archive'),
            'parent_item_colon' => __('Parent Category:', 'resource-archive'),
            'new_item_name' => __('New Category Name', 'resource-archive'),
            'add_new_item' => __('Add New Category', 'resource-archive'),
            'edit_item' => __('Edit Category', 'resource-archive'),
            'update_item' => __('Update Category', 'resource-archive'),
            'view_item' => __('View Category', 'resource-archive'),
            'separate_items_with_commas' => __('Separate categories with commas', 'resource-archive'),
            'add_or_remove_items' => __('Add or remove categories', 'resource-archive'),
            'choose_from_most_used' => __('Choose from the most used', 'resource-archive'),
            'popular_items' => __('Popular Categories', 'resource-archive'),
            'search_items' => __('Search Categories', 'resource-archive'),
            'not_found' => __('Not Found', 'resource-archive'),
            'no_terms' => __('No categories', 'resource-archive'),
            'items_list' => __('Categories list', 'resource-archive'),
            'items_list_navigation' => __('Categories list navigation', 'resource-archive'),
        );
        $rewrite = array(
            'slug' => 'resource-category',
            'with_front' => true,
            'hierarchical' => false,
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'rewrite' => $rewrite,
            'show_in_rest' => true,
        );
        register_taxonomy('resource_category', array('resource'), $args);
    }
    
    // Register Custom Taxonomy
    public function trimester_taxonomy(){
        
        $labels = array(
            'name'                       => _x( 'Trimesters', 'Taxonomy General Name', 'awd-resource-archive' ),
            'singular_name'              => _x( 'Trimester', 'Taxonomy Singular Name', 'awd-resource-archive' ),
            'menu_name'                  => __( 'Trimesters', 'awd-resource-archive' ),
            'all_items'                  => __( 'All Trimesters', 'awd-resource-archive' ),
            'parent_item'                => __( 'Parent Trimester', 'awd-resource-archive' ),
            'parent_item_colon'          => __( '', 'awd-resource-archive' ),
            'new_item_name'              => __( 'New Trimester Name', 'awd-resource-archive' ),
            'add_new_item'               => __( 'Add New Trimester', 'awd-resource-archive' ),
            'edit_item'                  => __( 'Edit Trimester', 'awd-resource-archive' ),
            'update_item'                => __( 'Update Trimester', 'awd-resource-archive' ),
            'view_item'                  => __( 'View Trimester', 'awd-resource-archive' ),
            'separate_items_with_commas' => __( 'Separate trimesters with commas', 'awd-resource-archive' ),
            'add_or_remove_items'        => __( 'Add or remove trimesters', 'awd-resource-archive' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'awd-resource-archive' ),
            'popular_items'              => __( 'Most-Used Trimesters', 'awd-resource-archive' ),
            'search_items'               => __( 'Search Trimesters', 'awd-resource-archive' ),
            'not_found'                  => __( 'Not Found', 'awd-resource-archive' ),
            'no_terms'                   => __( 'No Trimesters', 'awd-resource-archive' ),
            'items_list'                 => __( 'Trimesters list', 'awd-resource-archive' ),
            'items_list_navigation'      => __( 'Trimesters list navigation', 'awd-resource-archive' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
        );
        register_taxonomy( 'trimester', array( 'resource' ), $args );
    }


    /**
     *  Resource Posts Ajax Response
     */
    public function get_posts_ajax(){

        global $options;

        $args = array(
            'post_type'         => 'resource',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
        );

        parse_str( $_POST[ 'form_data' ], $post_data );

        if ( !$post_data['_wpnonce'] || ! wp_verify_nonce( $post_data['_wpnonce'], 'get_resource_posts' ) ){
            $response = array( "success" => false, "msg" => "Sorry, but nothing matched your search terms. Please try again with some different keywords." );
            echo json_encode( $response );
            wp_die();
        }

        if( isset( $post_data['search_string'] ) && $post_data['search_string'] !== '' ){
            $args['s'] = $post_data['search_string'];
        }

        $resource_category = "";
        if( isset( $post_data['resource_category'] ) && $post_data['resource_category'] !== '' ){
            $resource_category = $post_data['resource_category'];
        }
        $trimester = "";
        if( isset( $post_data['trimester'] ) && !empty( $post_data['trimester'] ) ){
            $trimester = $post_data['trimester'];
        }

        if( $resource_category != "" && $trimester != ""){
            $args['tax_query'] = array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'resource_category',
                        'field'    => 'name',
                        'terms'    => $resource_category,
                    ),
                    array(
                        'taxonomy' => 'trimester',
                        'field'    => 'name',
                        'terms'    => $trimester,
                    )
                );
        } elseif($resource_category != ""){
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'resource_category',
                    'field'    => 'name',
                    'terms'    => $resource_category,
                ),
            );
        } elseif($trimester != "") {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'trimester',
                    'field'    => 'name',
                    'terms'    => $trimester,
                ),
            );
        }

        $resources = new WP_Query( $args );

        $count = 0; 
        $response_string = "";
        
        while ( $resources->have_posts() ) {
            
            $resources->the_post(); 
            $count++; 
            $f = ($count % 3 == 1)? ' first' : '';


            $texonomy_icon_type = 'default-taxonomy-type';
            $post_terms = wp_get_post_terms(get_the_ID(), 'resource_category');
            if( $post_terms ){
                $texonomy_icon_type = $post_terms[0]->slug;
            }

            $response_string .= "
            <div class='one-third $f'>
                <a href='".$this->format_filtered_post_link( get_the_ID() )."'>
                    <div class='post-card' id='post-".get_the_ID()."' ".get_post_class()." >
                        <div class='card-left'>
                            <div class='".$texonomy_icon_type."'>&nbsp;</div>
                        </div>
                        <div class='card-right'>
                            <header class='entry-header'>".get_the_title()."</header>
                            <main class='entry-content'>".$this->excerpt(get_the_excerpt(), 125)."</main>
                            <footer class='entry-footer'>
                                <a href='".$this->format_filtered_post_link( get_the_ID() )."' class='read-more'>READ MORE</a>
                            </footer>
                        </div>
                    </div>
                </a>
            </div>";
        }

        echo json_encode( array("success"=>true,"count"=>$count, "result"=>$response_string ) );
        wp_die();

    }

    public function format_filtered_post_link( $id ) {
        $post_link = get_the_permalink( $id );

        if( 'link' === get_post_format( $id ) ) {
            $content = get_the_content( $id );

            if( esc_url_raw( $content ) === $content ) {
                $post_link = $content;
                $post_link .= "' target='_blank";
            }
        }

        return $post_link;
    }

    public function excerpt($string, $limit) {
        
        $excerpt = "";
        if( strlen($string) > $limit ){
            $excerpt = substr($string, 0, $limit);
            $excerpt .= ' ...';
        }else {
            $excerpt = $string;
        }
        
        return $excerpt;
    }

    /**
     *  Send Email Ajax Function
     */
    public function send_email() {

        global $options;

        parse_str( $_POST[ 'form_data' ], $post_data );

        if ( !$post_data['_wpnonce'] || ! wp_verify_nonce( $post_data['_wpnonce'], 'awd_send_email' ) ){
            $response = array( "success" => false, "msg" => "Sorry, but nothing matched token." );
            echo json_encode( $response );
            wp_die();
        }

        $to_email = $post_data['to_email'];
        $name = $post_data['name'];
        $url = $post_data['current_url'];

        $subject = "People's community clinic";
        $message = "PEOPLE'S COMMUNITY CLINIC \n".$url."\n From ".$name; 

        if( wp_mail($to_email, $subject, $message) ){
            $return_data = array(
                "success"   =>  true,
                "result"    =>  array(
                    "email" => $to_email,
                    "name"  => $name,
                    "url"   => $url
                )
            );
            echo json_encode($return_data);
        } else {
            $return_data = array(
                "success"  => false,
                "msg"      => "Sorry, but there are some issues on email sending"
            );
            echo json_encode($return_data);
        }
        
        wp_die();
    }

    /**
     *  Get Custom Post URI
     */

    public function get_custom_post_url(){

        global $options;
        $post_id = $_POST[ 'post_id' ];

        if( $post_id == '' ){
            $response = array( "success" => false, "url" => "" );
            echo json_encode( $response );
            wp_die();
        }

        $post_id = str_replace( 'post-', '', $post_id);
        $post_link = get_the_permalink( $post_id );
        $target = "_self";

        if( 'link' === get_post_format( $post_id ) ) {
            // $content = get_the_content( $post_id );
            $content =  get_post_field('post_content', $post_id);

            if( esc_url_raw( $content ) === $content ) {
                $post_link = $content;
                $target = "_blank";
            }
        }

        $response = array( "success" => true, "url" => $post_link, "target" => $target);
        echo json_encode($response);
        wp_die();

    } 

    /**
     * Add HR button on TinyMCE
     *
     * @return void
     */
    public function ag_horizontal_rule_button($buttons) {
        $buttons[] = 'hr';
        return $buttons;
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}