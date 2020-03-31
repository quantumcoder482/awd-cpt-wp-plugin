<?php

// No direct access
defined( "ABSPATH" ) OR die();

/**
 * This is the header bar 
 */

$is_single = is_singular( 'resource' );

if( $is_single ) {
	$archive_url = get_post_type_archive_link( 'resource' );
	$archive_return_link = sprintf( '<a href="%s" class="back-link">%s</a>',
								$archive_url,
								__( 'BACK TO ARCHIVE', 'awd-resource-archive' )
							);
}

?>

<div class="header-bar">
    <div class="wrap">
        <?php echo ( $is_single ) ? $archive_return_link : ''; ?>
        <div class="awd-toggle"><?php echo do_shortcode( '[wpml_language_selector_widget]' ); ?></div>
        <a href="#mail_send_modal" data-toggle="modal" class="header-bar-icon" id="send_mail"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
    </div>
</div>