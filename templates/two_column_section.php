<?php
/**
* 2 Column Text
*/

// No direct access
defined( "ABSPATH" ) OR die();

// Set up wrapper classes and id
$container_attributes = $this->helper->setup_wrapper_atts( 'awd-two-columns' );

// Grab content
$left_content = get_sub_field('left_content');
$right_content = get_sub_field('right_content');

$button = get_sub_field('button');
// Check for button
if( $button ) {
    $button_url = $button['url'];
    $button_title = $button['title'];
    $button_target = $button['target'] ? $button['target'] : '_self';

    $button_style = get_sub_field('button_style');
    $button_position = get_sub_field('button_position');
}

// Output HTML
?>
<div <?php echo $container_attributes; ?>>
    <div class="wrap">
        <div class="one-half first">
            <?php _e( $left_content, 'awd-resource-archive' );

            if( $button && 'left' == $button_position ) { ?>
                <div class="section-button">
                    <a class="<?php echo $button_style; ?>" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"><?php _e( esc_html($button_title), 'awd-resource-archive' ); ?></a>
                </div>
            <?php } ?>
        </div>
        <div class="one-half">
            <?php _e( $right_content, 'awd-resource-archive' );

            if( $button && 'right' == $button_position ) { ?>
                <div class="section-button">
                    <a class="<?php echo $button_style; ?>" href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"><?php _e( esc_html($button_title), 'awd-resource-archive' ); ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>