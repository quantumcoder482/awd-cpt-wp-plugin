<?php
/**
* Table
*/

// No direct access
defined( "ABSPATH" ) OR die();

?>
<div class="awd-table awd-sec" <?php if( get_field('section_id') ): ?> id="<?php echo get_field('section_id'); endif; ?>" >
    <div class="wrap">
        <?php if( get_sub_field('heading') ): ?><h2><?php the_sub_field("heading"); ?></h2><?php endif; ?>
        <?php if( get_sub_field('subheading') ): ?><h5><?php the_sub_field("subheading"); ?></h5><?php endif; ?>
        <div class="table-inner">
            <table>
                <?php if( have_rows('table_items') ): $count = 1; while( have_rows('table_items')): the_row();?>
                    <?php
                        $awd_tab_column1 = get_sub_field('awd_tab_column1');
                        $awd_tab_column2 = get_sub_field('awd_tab_column2');
                        $awd_tab_column3 = get_sub_field('awd_tab_column3');
                        $awd_tab_column4 = get_sub_field('awd_tab_column4');
                        $awd_tab_column5 = get_sub_field('awd_tab_column5');
                        $awd_tab_column6 = get_sub_field('awd_tab_column6');
                    ?>
                    <tr>
                        <?php if( $count == "1" ): ?><th><?php else: ?><td class="row_title"><?php endif; ?>
                            <?php if( $awd_tab_column1) : ?><?php echo $awd_tab_column1; ?><?php endif; ?>
                        <?php if( $count == "1" ): ?></th><?php else: ?></td><?php endif; ?>

                        <?php if( $count == "1" ): ?><th><?php else: ?><td><?php endif; ?>
                            <?php if( $awd_tab_column2) : ?><?php echo $awd_tab_column2; ?><?php endif; ?>
                        <?php if( $count == "1" ): ?></th><?php else: ?></td><?php endif; ?>

                        <?php if( $count == "1" ): ?><th><?php else: ?><td><?php endif; ?>
                            <?php if( $awd_tab_column3) : ?><?php echo $awd_tab_column3; ?><?php endif; ?>
                        <?php if( $count == "1" ): ?></th><?php else: ?></td><?php endif; ?>

                        <?php if( $count == "1" ): ?><th><?php else: ?><td><?php endif; ?>
                            <?php if( $awd_tab_column4) : ?><?php echo $awd_tab_column4; ?><?php endif; ?>
                        <?php if( $count == "1" ): ?></th><?php else: ?></td><?php endif; ?>

                        <?php if( $count == "1" ): ?><th><?php else: ?><td><?php endif; ?>
                            <?php if( $awd_tab_column5) : ?><?php echo $awd_tab_column5; ?><?php endif; ?>
                        <?php if( $count == "1" ): ?></th><?php else: ?></td><?php endif; ?>

                        <?php if( $count == "1" ): ?><th><?php else: ?><td><?php endif; ?>
                            <?php if( $awd_tab_column6) : ?><?php echo $awd_tab_column6; ?><?php endif; ?>
                        <?php if( $count == "1" ): ?></th><?php else: ?></td><?php endif; ?>

                    </tr>
                <?php $count ++; endwhile; endif; ?>
            </table>
            <div class="clearfix"></div>
        </div>
        
    </div>
</div>