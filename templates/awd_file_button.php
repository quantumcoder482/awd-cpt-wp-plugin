<?php
/**
* File Button Section
*/

// No direct access
defined( "ABSPATH" ) OR die();

// Set up wrapper classes and id
$container_attributes = $this->helper->setup_wrapper_atts( 'awd-one-column' );

// Grab file
$file = get_sub_field('file');

// Check for file
if ( $file ) {
    $show_filesize = get_sub_field('show_filesize');
    $file_size = $show_filesize ? ' (' . size_format( $file['filesize'] ) . ')' : '';
    
    $file_url = $file['url'];
    $file_alt = $file['description'] ?? $file['filename'];

    $button_text = get_sub_field('button_text');
    $button_style = get_sub_field('button_style');
}

// Output HTML
?>
<div <?php echo $container_attributes; ?>>
    <div class="wrap">
        <?php if( $file ) { ?>
            <div class="section-button">
                <a class="<?php echo $button_style; ?>" href="<?php echo esc_url($file_url); ?>" target="_blank" alt="<?php printf( __( '%s', 'awd-resource-archive' ), $file_alt ); ?>">
                    <?php echo sprintf( __( '%s', 'awd-resource-archive' ), esc_html($button_text) ) . $file_size; ?>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<?php 