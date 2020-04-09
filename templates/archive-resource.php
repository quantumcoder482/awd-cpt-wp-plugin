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

$title = get_field( 'title', 'option');
$featured_image = get_field( 'featured_image', 'option' );
$sub_title = get_field( 'sub_title', 'option' );
$intro_text = get_field( 'intro_text', 'option' );

$page_header_classes = 'page-header';
$page_header_classes .= $featured_image ? ' awd-banner' : '';

include_once( AWDRA_DIR . 'includes/class-helper.php' );

$helper = new AWDHelper();

/**
 *  Excert filter function
 * @limit Integer
 */

function excerpt($limit) {
    $result_string = get_the_excerpt();
    $excerpt = "";
    if( strlen($result_string) > $limit ){

        $parts = preg_split( '/([\s\n\r]+)/', $result_string, null, PREG_SPLIT_DELIM_CAPTURE );
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $limit) { break; }
        }

        $excerpt = implode(array_slice($parts, 0, $last_part));
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
    'taxonomy'      => 'resource_category',
    'meta_key'      => 'display_order',
    'orderby'       => 'meta_value_num',
    'order'         => 'ASC',
    'hide_empty'    => false
);
$categories = get_terms( $get_terms_args );

$trimester_args = array(
    'taxonomy'      => 'trimester',
    'meta_key'      => 'display_order',
    'orderby'       => 'meta_value_num',
    'order'         => 'ASC',
    'hide_empty'    => false
);
$trimesters = get_terms( $trimester_args );

get_header(); ?>

<div class="site-content" id="resource-archive">
    <div class="row">
    <?php if ( $resources->have_posts() ) : ?>

        <section class="<?php echo $page_header_classes; ?>"<?php echo $featured_image ? ' style="background-image: url(' . $featured_image . ')"' : ''; ?>>
            <?php include_once( AWDRA_DIR . 'templates/awd_header_bar.php' ); ?>
        </section>
        <div class="header-text">
                <?php
                    if ( isset( $title ) ){ ?>
                        <h1 class='page-title'>
                            <?php printf(
                                /* translators: %s: Archive title */
                                __( '%s', 'awd-resource-archive' ),
                                $title
                            ); ?>
                        </h1>
                    <?php } else {
                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                    }
                ?>
                <?php
                    if( isset($sub_title) ){ ?>
                        <h2>
                            <?php printf(
                                /* translators: %s: Subtitle */
                                __( '%s', 'awd-resource-archive' ),
                                $sub_title
                            ); ?>
                        </h2>
                    <?php }
                ?>
        </div>

        <?php if ( isset( $intro_text ) ) { ?>
            <section class="awd-intro">
                <div class="divider-line"></div>
                <div class='intro-text'>
                    <?php printf(
                        /* translators: %s: Intro text */
                        __( '%s', 'awd-resource-archive' ),
                        $intro_text
                    ); ?>
                </div>
            </section>
        <?php } ?>

        <section class="section-search">
            <div class="wrap">
                <form id="search_form" method="POST">
                     <?php wp_nonce_field( 'get_resource_posts' );?>
                    <input type="hidden" id="trimester" name="trimester" value="">
                    <div class="one-half first input-style">
                        <input type="text" id="search_string" name="search_string" placeholder="<?php _e( 'What are you searching for?', 'awd-resource-archive' ); ?>" value="">
                        <button class="search-button"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                    <div class="one-half select-style">
                        <select id="resource_category" name="resource_category">
                            <option value=""><?php _e( 'Choose a category', 'awd-resource-archive' ); ?></option>
                            <?php
                                if( $categories ){
                                    foreach ($categories as $cat){
                                        $category_name = sprintf(
                                                /* translators: %s: Category name */
                                                __( '%s', 'awd-resource-archive' ),
                                                $cat->name
                                            );
                                        $category_slug = sprintf(
                                                /* translators: %s: Category slug */
                                                __( '%s', 'awd-resource-archive' ),
                                                $cat->slug
                                            ); ?>
                                        <option value='<?php echo $category_slug; ?>'><?php echo $category_name; ?></option>
                                    <?php }
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
                            <?php foreach( $trimesters as $tab_number => $trimester ) {
                                $short_name = get_field( 'awd_abbreviation', $trimester ) ?: $trimester->name; ?>

                                <div class="tab-menu" tab-attr="tab-<?php echo ++$tab_number; ?>" data-value="<?php echo $trimester->slug; ?>">
                                    <?php printf( __( '%s', 'awd-resource-archive' ),
                                        /* translators: %s: Trimester Abbreviation */
                                        $short_name
                                    ); ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="tab-inner">
                            <div id="tab-0" class="tabs-content">
                                <?php printf(
                                    /* translators: %s: Trimesters area description */
                                    __( '%s', 'awd-resource-archive' ),
                                    get_field( 'trimesters_default_description', 'option' )
                                ); ?>
                            </div>
                            <?php foreach( $trimesters as $tab_number => $trimester ) { ?>
                                <div id="tab-<?php echo ++$tab_number; ?>" class="tabs-content">
                                    <?php printf(
                                        /* translators: %s: Trimester description */
                                        __( '%s', 'awd-resource-archive' ),
                                        $trimester->description
                                    ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </section><!-- .page-header -->
        <?php /* Start the Loop */ ?>
        <section class="section-posts" id="section_posts">
        <?php $count = 0; while ( $resources->have_posts() ) : $resources->the_post(); $count++; ?>
            <div class="one-third <?php if($count % 3 == 1): echo 'first'; endif; ?>">
                <?php
                $custom_link = get_post_meta( get_the_id(), "awd_external_link", true );
                $target = ( "" == $custom_link )? "": "target=\"_blank\"";               
                $link = ( "" == $custom_link )? get_the_permalink(): $custom_link;
                ?>
                <a href="<?= $link; ?>" <?= $target; ?> style="display:block">                
                    <span style="display: block" class="post-card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <span style="display: block" class="card-left">
                            <?php
                                $taxonomy_icon = 'default-taxonomy-type';
                                $post_terms = wp_get_post_terms(get_the_ID(), 'resource_category');
                                if( $post_terms ){
                                    $primary_term = $helper->get_primary_category( get_the_ID(), 'resource_category' );
                                    $english_term_id = apply_filters( 'wpml_object_id', $primary_term->term_id, 'resource_category', true, 'en' );

                                    global $icl_adjust_id_url_filter_off;
                                    $orig_flag_value = $icl_adjust_id_url_filter_off;
                                    $icl_adjust_id_url_filter_off = true;

                                    $english_term = !empty( $english_term_id ) ? get_term_by( 'id', $english_term_id, 'resource_category' ) : '';

                                    $icl_adjust_id_url_filter_off = $orig_flag_value;

                                    $taxonomy_icon = !empty( $english_term ) ? $english_term->slug : 'default-taxonomy-type';
                                }
                            ?>
                            <span style="display: block" class="<?php echo $taxonomy_icon; ?>">&nbsp;</span>
                        </span>
                        <span style="display: block" class="card-right">
                            <span style="display: block" class="entry-header">
                                <?php the_title(); ?>
                            </span><!-- .entry-header -->
                            <span style="display: block" class="entry-content">
                                <?php if( has_excerpt() ): echo excerpt(80); else: _e( 'Visit this resource for more information', 'awd-resource-archive' ); endif;?>
                            </span><!-- .entry-content -->
                            <span class="entry-footer" style="display: block">
                                <span class="read-more"><?php _e( 'READ MORE', 'awd-resource-archive' ); ?></span>
                            </span><!-- .entry-footer -->
                        </span>
                    </span><!-- #post-## -->
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
    <?php $footer_contact = get_post( 481 );

    if( $footer_contact instanceof WP_Post ) {
        echo do_shortcode( $footer_contact->post_content );
    } ?>
</div><!-- #primary -->
<?php include( AWDRA_DIR . 'templates/modal.php');

get_sidebar();
get_footer();
