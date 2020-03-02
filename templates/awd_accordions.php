<?php
/**
* Accordions
*/

// No direct access
defined( "ABSPATH" ) OR die();

$accordions_count = count( get_sub_field('accordions') );
$split_point = intdiv( $accordions_count, 2 );
$split_point += $accordions_count & 1 ? 1 : 0;
?>
<div class="awd-accordion awd-sec" <?php if( get_sub_field('section_id') ): ?> id="<?php echo get_sub_field('section_id'); ?>" <?php endif; ?> >
    <div class="wrap">
        <?php if( get_sub_field('heading') ): ?><h2><?php echo get_sub_field('heading'); ?></h2><?php  endif; ?>
        <div class="accordion one-half first">
            <?php if( have_rows('accordions') ): $count = 0; while( have_rows('accordions')): the_row();?>
                <?php
                    $awd_ac_itemtitle = get_sub_field('title');
                    $awd_ac_itemdesc = get_sub_field('description');
                ?>
                <div class="accordion-title "><h5><?php echo $awd_ac_itemtitle; ?></h5></div>
                <div class="accordion-answer" ><?php echo $awd_ac_itemdesc; ?></div>

                <?php $count++;
                if( $count === $split_point ) {
                    echo '</div><div class="accordion one-half">';
                }

            endwhile; endif; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>