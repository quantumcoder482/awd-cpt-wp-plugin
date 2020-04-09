<!--Email Send Modal Starts -->
<div class="modal fade" id="mail_send_modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><?php _e( 'Email this page', 'awd-resource-archive' ); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'awd-resource-archive' ); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="input-area">
                    <form id="awd_email_send_form" method="" action="" role="form" data-toggle="validator">
                        <?php wp_nonce_field('awd_send_email'); ?>
                        <input type="email" id="awd_modal_to_email" name="to_email" required="required" placeholder="<?php esc_attr_e( 'Send to email address', 'awd-resource-archive' ); ?>" value="" data-error="<?php esc_attr_e( 'Please enter your email address correctly', 'awd-resource-archive' ); ?>" />
                        <div class="help-block with-errors"></div>
                        <input type="text" id="awd_modal_name" name="name" required="required" placeholder="<?php esc_attr_e( 'Your Name', 'awd-resource-archive' ); ?>" value="" data-error="<?php esc_attr_e( 'Please enter your name', 'awd-resource-archive' ); ?>" />
                        <input type="hidden" id="awd_current_url" name="current_url" value="" />
                    </form>
                    <div class="frm_error"></div>
                </div>
                <button class="primary" id="mail_send_btn"><?php _e( 'Send', 'awd-resource-archive' ); ?></button>
                <div class="middle-bar"><?php _e( 'OR', 'awd-resource-archive' ); ?></div>
                <button class="secondary" id="copy_path_btn"><?php _e( 'Copy Link', 'awd-resource-archive' ); ?></button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ends -->

<!-- Confirmation Modal 1 -->
<div class="modal fade" id="email_sent_confirm" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'awd-resource-archive' ); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    <?php printf( '%s<span id="awd_email_sent">%s</span>!',
                        /* translators: modal email success notice */
                        __( 'Link to this page sent to', 'awd-resource-archive' ),
                        /* translators: default dummy email */
                        'my@email.com'
                    ); ?>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Modal Confirmation 1 -->

<!-- Confirmation Modal 2 -->
<div class="modal fade" id="page_link_copy" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'awd-resource-archive' ); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php _e( 'Link copied!', 'awd-resource-archive' ); ?></p>
            </div>
        </div>
    </div>
</div>
<!-- Modal Confirmation 1 -->
