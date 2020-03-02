<?php
/**
* 3 column Boxes
*/

// No direct access
defined( "ABSPATH" ) OR die();

?>
<div class="awd-col3box awd-box awd-sec" <?php if( get_field('section_id') ): ?> id="<?php echo get_field('section_id'); endif; ?>" >
    <div class="wrap">
        <?php if( get_sub_field('heading') ): ?><h2><?php the_sub_field("heading"); ?></h2><?php endif; ?>
        <?php if( get_sub_field('subheading') ): ?><h5><?php the_sub_field("subheading"); ?></h5><?php endif; ?>
        <?php if( have_rows('items') ): $count = 1; while( have_rows('items')): the_row();?>            
        <?php
            $count_compare = $count % 3;
            $awd_box_itemicon = get_sub_field('icon');
            $awd_box_itemtitle = get_sub_field('title');
            $awd_box_itemdesc = get_sub_field('description');
            $awd_box_itemlink = get_sub_field('link');
        ?>
        <div class="one-third <?php if( $count_compare == "1" ): ?>first<?php endif; ?> box-item">
            <?php if( $awd_box_itemicon) : ?><img src="<?php echo $awd_box_itemicon['url']; ?>" alt="<?php echo $awd_box_itemicon['alt']; ?>" /><?php endif; ?><?php if( $awd_box_itemtitle) : ?><h4><?php echo $awd_box_itemtitle; ?></h4><?php endif; ?>
            <?php if( $awd_box_itemdesc) : ?><div><?php echo $awd_box_itemdesc; ?></div><?php endif; ?>
            <?php if( $awd_box_itemlink ): 
                $awd_box_itemlink_url = $awd_box_itemlink['url'];
                $awd_box_itemlink_title = $awd_box_itemlink['title'];
                $awd_box_itemlink_target = $awd_box_itemlink['target'] ? $awd_box_itemlink['target'] : '_self';
            ?>  
                <div class="box-link">
                    <a class="awd-bt" href="<?php echo esc_url($awd_box_itemlink_url); ?>" target="<?php echo esc_attr($awd_box_itemlink_target); ?>">
                       <span><?php echo $awd_box_itemlink_title ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php $count++; ?>
    <?php endwhile; endif; ?>
    <div class="clearfix"></div>  
    </div>
</div>