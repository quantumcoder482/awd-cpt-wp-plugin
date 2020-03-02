<?php

get_header();

// Check for featured image
$featured_image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$page_header_classes = 'page-header';
$page_header_classes .= $featured_image ? ' awd-banner' : '';

?>

<div id="main-content-resource" class="main-content">
    <div id="primary" class="content-area">
        <section class="<?php echo $page_header_classes; ?>"<?php echo $featured_image ? ' style="background-image: url(' . $featured_image . ')"' : ''; ?>>
            <div class="header-bar">
                <div class="wrap">
                    <a href="<?php echo get_post_type_archive_link( 'resource' ); ?>" class="back-link">BACK TO ARCHIVES</a>
                    <a href="#mail_send_modal" data-toggle="modal" class="header-bar-icon" id="send_mail"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                </div>
            </div>
        </section>
        <div class="header-text">
            <h1 class='page-title'><?php echo wp_kses_post( get_the_title() ); ?></h1>
        </div>
        <div id="content" class="site-content" role="main">
            <?php the_content(); ?>
        </div><!-- #content -->
    </div><!-- #primary -->
</div><!-- #main-content -->
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
