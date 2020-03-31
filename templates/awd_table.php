<?php
/**
* Table
*/

// No direct access
defined( "ABSPATH" ) OR die();

$table = get_sub_field( 'data' );

if ( ! empty ( $table ) ) { ?>
    <div class="awd-table awd-sec" <?php if( get_field('section_id') ): ?> id="<?php echo get_field('section_id'); endif; ?>" >
        <div class="wrap">
            <?php if( get_sub_field('heading') ): ?><h2><?php printf( __( '%s', 'awd-resource-archive' ), get_sub_field("heading") ); ?></h2><?php endif; ?>
            <?php if( get_sub_field('subheading') ): ?><h5><?php printf( __( '%s', 'awd-resource-archive' ), get_sub_field("subheading") ); ?></h5><?php endif; ?>
            <div class="table-inner">
                    <?php // begin table structure
                    echo '<table border="0">';

                        if ( ! empty( $table['caption'] ) ) {

                            echo '<caption>' . sprintf( __( '%s', 'awd-resource-archive' ), $table['caption'] ) . '</caption>';
                        }

                        if ( ! empty( $table['header'] ) ) {

                            echo '<thead>';

                                echo '<tr>';

                                    foreach ( $table['header'] as $th ) {

                                        echo '<th>';
                                            printf( __( '%s', 'awd-resource-archive' ), $th['c'] );
                                        echo '</th>';
                                    }

                                echo '</tr>';

                            echo '</thead>';
                        }

                        echo '<tbody>';

                            foreach ( $table['body'] as $tr ) {

                                echo '<tr>';

                                    foreach ( $tr as $td ) {

                                        echo '<td>';
                                            printf( __( '%s', 'awd-resource-archive' ), $td['c'] );
                                        echo '</td>';
                                    }

                                echo '</tr>';
                            }

                        echo '</tbody>';

                    echo '</table>'; ?>
                <div class="clearfix"></div>
            </div>
            
        </div>
    </div>
<?php }