<?php
/**
* Contact List Section
*/

// No direct access
defined( "ABSPATH" ) OR die();

// Set up wrapper classes and id
$container_attributes = $this->helper->setup_wrapper_atts( 'awd-contacts' );

?>
<div <?php echo $container_attributes; ?>>
    <div class="wrap">
        <?php if( get_sub_field('heading') ) { ?>
            <h2><?php the_sub_field("heading"); ?></h2>
        <?php }

        if( have_rows('contacts') ): ?>

            <ul><!-- begin contact info list -->
                <?php while( have_rows('contacts')): the_row();
                    $phone_numbers = array();

                    $business = get_sub_field('business');
                    $physical_address = get_sub_field('physical_address');
                    $other_info = get_sub_field('other_info');

                    // loop through phones repeater and construct phone numbers list
                    if( have_rows('phones') ):
                        while( have_rows('phones') ): the_row();
                            $phone_description = get_sub_field('description') ? get_sub_field('description') . ' — ' : '';
                            $phone_numbers[] = $phone_description . get_sub_field('phone_number');
                        endwhile;
                    endif; ?>

                    <li><!-- begin contact list item -->
                        <h3><?php echo sanitize_text_field( $business['name'] ); ?></h3>
                        
                        <?php // show hours if they exist
                        if( !empty( $business['hours'] ) ) { ?>
                            <p><?php echo sanitize_text_field( $business['hours'] ); ?></p>
                        <?php }
                        
                        // show address if it exists
                        if( !empty( $physical_address ) ) { ?>
                            <p><?php echo sanitize_textarea_field( $physical_address ); ?></p>
                        <?php }

                        // show phone numbers if they exist
                        if( !empty( $phone_numbers ) ) { ?>
                            <ul><!-- begin phone numbers nested list -->
                                <?php foreach( $phone_numbers as $phone_number ) {
                                    echo '<li>' . $phone_number . '</li>';
                                } ?>
                            </ul><!-- end phone numbers nested list -->
                        <?php }

                        // show other info if it exists. sanitized, but allows html.
                        if( $other_info ) {
                            echo wp_kses_post( $other_info );
                        } ?>
                    </li><!-- end contact list item -->
                <?php endwhile; ?>
            </ul><!-- end contact list -->
        <?php endif; ?>
    <div class="clearfix"></div>  
    </div>
</div>