<?php

get_header();

// Check for featured image
$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
if(!$featured_image) {
    $featured_image = get_field( 'featured_image', 'resource' );
}

$page_header_classes = 'page-header';
$page_header_classes .= $featured_image ? ' awd-banner' : '';
    

?>
 
<div id="main-content-resource" class="main-content">
    <div id="primary" class="content-area">
        <section class="<?php echo $page_header_classes; ?>"<?php echo $featured_image ? ' style="background-image: url(' . $featured_image . ')"' : ''; ?>>
            <?php include_once( AWDRA_DIR . 'templates/awd_header_bar.php' ); ?>
        </section>
        <div class="header-text">
            <h1 class='page-title'><?php echo wp_kses_post( get_the_title() ); ?></h1>
        </div>
        <div id="content" class="site-content" role="main">
            <?php the_content(); ?>
        </div><!-- #content -->
    </div><!-- #primary -->
    <?php $footer_contact = get_post( 481 );

    if( $footer_contact instanceof WP_Post ) {
        echo do_shortcode( $footer_contact->post_content );
    } ?>
</div><!-- #main-content -->
<?php include( AWDRA_DIR . 'templates/modal.php');

get_sidebar();
get_footer();
