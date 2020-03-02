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

        add_action( 'admin_menu', [ $this, 'add_archive_option_page' ], 20 );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
        add_action( 'acf/input/admin_footer', [ $this, 'admin_footer' ], 10, 1 );
        add_filter( 'acf/location/rule_types', [ $this, 'location_rules_types' ] );
        add_filter( 'acf/location/rule_values/admin_page', [ $this, 'location_rules_values_archive' ] );
        add_filter( 'acf/location/rule_match/admin_page', [ $this, 'location_rules_match_archive' ], 10, 3);
    }

    /**
     * @return void
     */
    public function acf_installed_notify() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo __( '<strong>ACF Archive is not working</strong>, Advanced Custom Fields is not installed.', 'acf-archive' ); ?></p>
        </div>
        <?php
    }

    /**
     * Loop for each matching CPT to add menu
     * @return void
     */
    public function add_archive_option_page() {
        $post_types = $this->get_custom_post_types();

        foreach ( $post_types as $post_type => $post_type_object ) {

            if( $post_type === 'resource' ){

                $menu = 'edit.php?post_type=' . $post_type;

                if ( 'post' === $post_type ) {
                    $menu = 'edit.php';
                }

                $this->add_menu( $post_type_object->label, $menu );
            }

        }
    }

    /**
     * Load the ACF Assets only on archive options page
     * @param string $hook_suffix
     * @return void
     */
    public function admin_enqueue_scripts( $hook_suffix ) {
        $screen = get_current_screen();

        if ( $this->get_admin_page_slug( $screen->post_type ) === $hook_suffix ) {
            acf_enqueue_scripts();
        }
    }

    /**
     * Load ACF spinner on page submit
     * @return void
     */
    public function admin_footer() {
        $screen = get_current_screen();

        if ( $this->get_admin_page_slug( $screen->post_type ) === $screen->id ) {
            $this->print_spinner_script();
        }
    }

    /**
     * @param string $post_type
     * @return string
     */
    private function get_admin_page_slug( $post_type ) {
        return $post_type . '_page_archive-options';
    }

    /**
     * Print ACF spinner script
     * @return void
     */
    private function print_spinner_script() {
        ?>
        <script type="text/javascript">
            (function($) {
                var $spinner = $('p.submit .spinner');

                if ( ! $spinner.exists() ) {
                    $spinner = $('<span class="acf-spinner"></span>');
                    $('p.submit').append( $spinner );
                }
            })(jQuery);
        </script>
        <?php
    }

    /**
     * Add ACF menu page for each custom post type
     *
     * @param string $label
     * @param string $menu
     */
    private function add_menu( $label, $menu ) {
        $page_name = sprintf( __( '%s Archive', 'acf-archive' ), $label);

        $options = [
            'parent_slug' => $menu,
            'page_title'  => $page_name,
            'menu_title'  => $page_name,
            'capability'  => 'edit_posts',
            'menu_slug'   => 'archive-options',
        ];

        add_submenu_page(
            $options['parent_slug'],
            $options['page_title'],
            $options['menu_title'],
            $options['capability'],
            $options['menu_slug'],
            [ $this, 'render_menu' ]
        );
    }

    /**
     * Render the archive options page
     */
    public function render_menu() {
        $screen = get_current_screen();
        $post_type = $screen->post_type;

        if ( isset( $_POST[ $post_type ] ) && acf_verify_nonce( $post_type ) && acf_validate_save_post(true ) ) {
            acf_save_post( $post_type );
        }

        $field_groups = acf_get_field_groups( [ 'post_id' => $post_type ]);

        if ( empty( $field_groups ) ) {
            echo '<h2>' . __( 'No field groups are associated to this archive.', 'acf-archive' ) . '</h2>';
            return;
        }

        $post_type_object = get_post_type_object( $post_type );
        ?>

        <div class="wrap">
            <h1><?php printf( __( '%s Archive', 'acf-archive' ), $post_type_object->label ); ?></h1>

            <form action="" method="post">
                <?php
                acf_form_data( [
                    'post_id' => $post_type,
                    'screen'  => $post_type,
                    'nonce'   => $post_type,
                ] );

                foreach ( $field_groups as $field_group ) {
                    $fields = acf_get_fields($field_group);
                    acf_render_fields( $post_type, $fields );
                }
                ?>
                <p class="submit">
                    <button class="button button-primary" type="submit" name="<?php echo $post_type; ?>"><?php echo __( 'Submit', 'acf-archive' ); ?></button>
                </p>
            </form>
        </div>

        <?php
    }

    /**
     * Add the archive rule
     *
     * @param array $choices ACF location rules
     * @return array
     */
    public function location_rules_types( array $choices ) {
        $post = __( 'Page', 'acf' );

        $choices[ $post ]['admin_page'] = __( 'Archive Page', 'acf-archive' );

        return $choices;
    }

    /**
     * Add the archive rule values
     *
     * @param array $choices ACF location rules
     * @return array
     */
    public function location_rules_values_archive( $choices ) {
        foreach( $this->get_custom_post_types() as $post_type => $post_type_object ) {
            $choices[ $post_type ] = sprintf( __( '%s Archive', 'acf-archive' ), $post_type_object->label );
        }

        return $choices;
    }

    /**
     * Check if we are in the current post type for showing the fields.
     *
     * @param $match
     * @param $rule
     * @param $options
     * @return bool
     */
    public function location_rules_match_archive( $match, $rule, $options ) {
        if ( ! isset( $_GET['post_type'] ) || ! isset( $_GET['page'] ) ) {
            return $match;
        }

        return $_GET['post_type'] == $rule['value'] && $_GET['page'] == 'archive-options';
    }

    /**
     * Get all the custom post types with archive
     *
     * @return array
     */
    private function get_custom_post_types() {
        static $post_types;

        if ( null !== $post_types ) {
            return $post_types;
        }

        $args = [
            'public' => true,
            'has_archive' => true,
        ];

        $post_types = get_post_types( $args, 'objects' );

        return $post_types = apply_filters( 'acf_archive_post_types', $post_types );
    }
}

