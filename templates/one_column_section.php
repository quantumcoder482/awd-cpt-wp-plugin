<?php
/**
* One Column Section
*/

// No direct access
defined( "ABSPATH" ) OR die();

// Set up wrapper classes and id
$container_attributes = $this->helper->setup_wrapper_atts( 'awd-one-column' );

// Grab content
$content = get_sub_field('content');
$button = get_sub_field('button');

// Check for button
if( $button ) {
    $button_url = $button['url'];
    $button_title = $button['title'];
    $button_target = $button['target'] ? $button['target'] : '_self';

    $button_style = get_sub_field('button_style');
}

// Output HTML
?>
<div <?php echo $container_attributes; ?>>
    <div class="wrap">
        <?php echo $content;

        if( $button ) { ?>
            <div class="section-button">
                <a class="<?php echo $button_style; ?>" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"><?php echo esc_html($button_title); ?></a>
            </div>
        <?php } ?>                   
    </div>
</div>

<?php 