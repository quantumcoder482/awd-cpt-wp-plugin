<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package AWDRA
 * 
 */


/**
 *  Get custom fields for resource archive page
 * @ $post_id = resource  // current custom post type 
 */

$post_id = 'resource'; 
$title = get_field( 'title', $post_id);
$featured_image = get_field( 'featured_image', $post_id );
$sub_title = get_field( 'sub_title', $post_id );
$intro_text = get_field( 'intro_text', $post_id );

$page_header_classes = 'page-header';
$page_header_classes .= $featured_image ? ' awd-banner' : '';

/**
 *  Excert filter function 
 * @limit Integer 
 */

function excerpt($limit) {
    $result_string = get_the_excerpt();
    $excerpt = "";
    if( strlen($result_string) > $limit ){
        $excerpt = substr($result_string, 0, $limit);
        $excerpt .= ' ...';
    }else {
        $excerpt = $result_string;
    }
    
    return $excerpt;
}

/**
 * Check if post format is link and content only contains a valid URL
 * @param  int      $id Post ID
 * @return string       permalink for post or valid external URL with target blank
 */
function format_filtered_post_link( $id ) {
    $post_link = get_the_permalink( $id );

    if( 'link' === get_post_format( $id ) ) {
        $content = get_the_content( $id );

        if( esc_url_raw( $content ) === $content ) {
            $post_link = $content;
            $post_link .= '" target="_blank';
        }
    }

    return $post_link;
}

/**
 *  Custom query by filter options
 * @ search_string : any string filter
 * @ category :  after-baby, austin-resources, before-baby, breastfeeding-help, mothers-health
 * @ trimester:  1st trimester, 2nd trimester, 3rd trimester, after baby
 */

global $options;

$args = array(
    'post_type'         => 'resource',
    'post_status'       => 'publish',
    'posts_per_page'    => -1
);

$resources = new WP_Query( $args );

/**
 *  Get Categories for search
 */

$get_terms_args = array(
    'taxonomy' => 'resource_category', 
    'orderby' => 'name',
    'order' => 'ASC',
    'hide_empty' => false
);

$categories = get_terms( $get_terms_args );
get_header(); ?>

<div class="site-content" id="resource-archive">
    <div class="row">
    <?php if ( $resources->have_posts() ) : ?>
        
        <section class="<?php echo $page_header_classes; ?>"<?php echo $featured_image ? ' style="background-image: url(' . $featured_image . ')"' : ''; ?>>
            <div class="header-bar">
                <div class="wrap">
                    <a href="#mail_send_modal" data-toggle="modal" class="header-bar-icon" id="send_mail"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                </div>
            </div>
        </section>
        <div class="header-text">
                <?php 
                    if ( isset( $title ) ){
                        echo "<h1 class='page-title'>$title</h1>";
                    } else {
                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                    }
                ?>
                <?php 
                    if( isset($sub_title) ){
                        echo "<h2> $sub_title </h2>";
                    } 
                ?>
        </div>

        <?php if ( isset( $intro_text ) ) { ?>
            <section class="awd-intro">
                <div class="divider-line"></div>
                <div class='intro-text'>
                    <?php echo $intro_text; ?>
                </div>
            </section>
        <?php } ?>

        <section class="section-search">
            <div class="wrap">
                <form id="search_form" method="POST">
                     <?php wp_nonce_field( 'get_resource_posts' );?>
                    <input type="hidden" id="trimester" name="trimester" value="">
                    <div class="one-half first input-style">
                        <input type="text" id="search_string" name="search_string" placeholder="What are you searching for?" value="" >
                        <button class="search-button"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                    <div class="one-half select-style">
                        <select id="resource_category" name="resource_category">
                            <option value="">Choose a category</option>
                            <?php 
                                if( $categories ){
                                    foreach ($categories as $cat){
                                        echo "<option value='$cat->name'>$cat->name</option>";
                                    }
                                }
                            ?>
                        </select>
                        <button class="select-down"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
                    </div>
                    <div class="clearfix"></div>
                    <div style="text-align: left; margin-bottom: 0.8em;">Trimester</div>
                    <div class="tabs-container">
                        <div class="tabs-menuitem">
                            <div class="tab-menu active-tab" tab-attr="tab-0" style="display:none"></div>
                            <div class="tab-menu" tab-attr="tab-1">1st</div>
                            <div class="tab-menu" tab-attr="tab-2">2nd</div>
                            <div class="tab-menu" tab-attr="tab-3">3rd</div>
                            <div class="tab-menu" tab-attr="tab-4">After Baby</div>
                        </div>
                        <div class="tab-inner">
                            <?php if( have_rows( 'trimester_tab_intros', $post_id )): while( have_rows( 'trimester_tab_intros', $post_id ) ): the_row(); ?>
                                <div id="tab-0" class="tabs-content"><?php the_sub_field('description'); ?></div>
                                <div id="tab-1" class="tabs-content"><?php the_sub_field('1st_trimester'); ?></div>
                                <div id="tab-2" class="tabs-content"><?php the_sub_field('2nd_trimester'); ?></div>
                                <div id="tab-3" class="tabs-content"><?php the_sub_field('3rd_trimester'); ?></div>
                                <div id="tab-4" class="tabs-content"><?php the_sub_field('4th_trimester'); ?></div>
                            <?php endwhile; endif;?>
                        </div> 
                    </div>
                </form>
            </div>
        </section><!-- .page-header -->
        <?php /* Start the Loop */ ?>
        <section class="section-posts" id="section_posts">
        <?php $count = 0; while ( $resources->have_posts() ) : $resources->the_post(); $count++; ?>
            <div class="one-third <?php if($count % 3 == 1): echo 'first'; endif; ?>">
                <a href="<?php echo format_filtered_post_link( get_the_ID() ); ?>">
                    <div class="post-card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="card-left">
                            <?php
                                $texonomy_icon_type = 'default-taxonomy-type';
                                $post_terms = wp_get_post_terms(get_the_ID(), 'resource_category');
                                if( $post_terms ){
                                    $texonomy_icon_type = $post_terms[0]->slug;
                                }
                            ?>
                            <div class="<?php echo $texonomy_icon_type; ?>">&nbsp;</div>
                        </div>
                        <div class="card-right">
                            <header class="entry-header">
                                <?php the_title(); ?>
                            </header><!-- .entry-header -->
                            <main class="entry-content">
                                <?php if( has_excerpt() ): echo excerpt(125); else: echo 'Doesn\'t Have Excerpt'; endif;?>
                            </main><!-- .entry-content -->
                            <footer class="entry-footer">
                                <a href="<?php echo format_filtered_post_link( get_the_ID() ); ?>" class="read-more">READ MORE</a>
                            </footer><!-- .entry-footer -->
                        </div>                   
                    </div><!-- #post-## -->
                </a>
            </div>            
        <?php endwhile; ?>
        </section>
        <?php //the_posts_navigation(); ?>

    <?php else : ?>

        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'peoplescc' ); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

                    <p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'peoplescc' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

                <?php elseif ( is_search() ) : ?>

                    <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'peoplescc' ); ?></p>
                    <?php get_search_form(); ?>

                <?php else : ?>

                    <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'peoplescc' ); ?></p>
                    <?php get_search_form(); ?>

                <?php endif; ?>
            </div><!-- .page-content -->
        </section><!-- .no-results -->

    <?php endif; ?>

    </div><!-- #main -->
</div><!-- #primary -->
<div class="contact-info">
    <section class="map animated-box animated">
        <div class="map-holder" style="">
            <iframe src="https://mapsengine.google.com/map/u/0/embed?mid=zkL9fO5gPk5k.kiRH8hkYtknA&amp;output=classic&amp;z=12" width="600" height="600" frameborder="0" style="border: 0px; top: -50px; position: relative; height: 390px;" allowfullscreen="" class="same-height-left same-height-right"></iframe>
        </div>
    </section>
    <div class="container animated-box animated">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-contact same-height-left same-height-right" style="height: 309px;">
                    <div class="box">
                        <h3>PCC CENTER FOR WOMEN’S HEALTH</h3>
                        <address>
                            2909 North IH-35 <br> Austin, Texas 78722 <br> <a href="tel:1234567890" class="phone">512-478-4939</a>
                        </address>
                    </div>
                    <div class="box">
                        <h3>PCC NORTH</h3>
                        <address>
                            1101 Camino La Costa <br> Austin, TX 78752 <br> <a href="tel:1234567890" class="phone">512-478-4939</a>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
include( AWDRA_DIR . 'templates/modal.php'); 
get_sidebar();
get_footer();
