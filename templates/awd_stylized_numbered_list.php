<?php
/**
* 3 column Boxes
*/

// No direct access
defined( "ABSPATH" ) OR die();

?>
<div class="awd-numbered-list awd-box awd-sec" <?php if( get_field('section_id') ): ?> id="<?php echo get_field('section_id'); endif; ?>" >
    <div class="wrap">
        <?php if( get_sub_field('heading') ): ?><h2><?php the_sub_field("heading"); ?></h2><?php endif; ?>
        <?php if( have_rows('list') ): $count = 1; ?>
        <ul>
            <?php while( have_rows('list')): the_row();?><li><?php echo get_sub_field('text'); ?></li><?php $count++; endwhile; ?>
        </ul>
        <?php endif; ?>
        
        <div class="clearfix"></div>
    </div>
</div>