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

        $short_list = count( get_sub_field('contacts') ) ? 2 : NULL;
        if( have_rows('contacts') ): ?>
            <ul class="arrow-list <?php echo ( $short_list ? ' short' : '' ); ?>"><!-- begin contact info list -->
                <?php while( have_rows('contacts')): the_row();
                    $phone_entries = array();
                    $number_formats = array();

                    $business = get_sub_field('business') ? get_sub_field('business') : '';
                    $physical_address = get_sub_field('physical_address') ? get_sub_field('physical_address') : '';
                    $other_info = get_sub_field('other_info');

                    // loop through phones repeater and construct phone numbers list
                    if( have_rows('phones') ):
                        while( have_rows('phones') ): the_row();

                            $raw_number = get_sub_field('phone_number');
                            $number_formats[] = $this->helper->phone_number_formatter( $raw_number );

                            $phone_entries[] = get_sub_field('description') ? get_sub_field('description'): '';
                        endwhile;
                    endif; ?>

                    <li><!-- begin contact list item -->
                        <h3>
                            <?php printf(
                                /* translators: %s: contact title */
                                __( '%s', 'awd-resource-archive' ),
                                $business['name']
                            ); ?>
                        </h3>
                        <?php if( !empty( $business['hours'] ) || !empty( $physical_address ) || !empty( $phone_entries ) ) { ?>
                            <div class="details-box">
                                <?php // show hours if they exist
                                if( !empty( $business['hours'] ) ) { ?>
                                    <p>
                                        <?php printf(
                                            /* translators: %s: hours */
                                            __( '%s', 'awd-resource-archive' ),
                                            $business['hours']
                                        ); ?>
                                    </p>
                                <?php }
                                
                                // show address if it exists
                                if( !empty( $physical_address ) ) { ?>
                                    <p>
                                        <?php echo wp_kses(
                                            printf(
                                                /* translators: %s: physical address */
                                                __( '%s', 'awd-resource-archive' ),
                                                $physical_address
                                            ),
                                            array(
                                                'br'    => array(),
                                                'p'     => array(
                                                    'class' => array(),
                                                    'id'    => array()
                                                )
                                            )
                                        ); ?>
                                    </p>
                                <?php }

                                // show phone numbers if they exist
                                if( !empty( $phone_entries ) ) { ?>
                                    <ul><!-- begin phone numbers nested list -->
                                        <?php foreach( $phone_entries as $key => $phone_description ) {
                                            $tel_num = $number_formats[$key]['raw'];
                                            $maybe_dash = '';
                                            if( !empty( $phone_description ) ) {
                                                $maybe_dash = ' — ';
                                            } ?>
                                            <li>
                                                <?php printf(
                                                    /* translators: optional description of phone number, may be blank */
                                                    __( '%s', 'awd-resource-archive' ),
                                                    $phone_description
                                                );
                                                echo $maybe_dash; ?>
                                                <a href="tel:<?php echo $tel_num; ?>" class="no-break"><?php echo $number_formats[$key]['formatted']; ?></a>
                                            </li>
                                        <?php } ?>
                                    </ul><!-- end phone numbers nested list -->
                                <?php } ?>
                            </div>
                        <?php }

                        // show other info if it exists. sanitized, but allows html.
                        if( $other_info ) {
                            echo wp_kses_post( $other_info );
                        }

                        if( have_rows( 'websites' ) ): ?>
                            <ul class="awd-bare-list">
                                <?php while( have_rows( 'websites' ) ): the_row();
                                    $link = get_sub_field( 'link' );
                                    if( $link ) {
                                        $link_url = $link['url'];
                                        $link_title = $link['title'];
                                        $link_target = $link['target'] ? $link['target'] : '_self'; ?>
                                        
                                        <li>
                                            <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                                                <?php printf(
                                                    /* translators: %s: link title */
                                                    __( '%s', 'awd-resource-archive' ),
                                                    $link_title
                                                ); ?>
                                            </a>
                                        </li>
                                    <?php }
                                endwhile; ?>
                            </ul>
                        <?php endif; ?>
                    </li><!-- end contact list item -->
                <?php endwhile; ?>
            </ul><!-- end contact list -->
        <?php endif; ?>
    <div class="clearfix"></div>  
    </div>
</div>