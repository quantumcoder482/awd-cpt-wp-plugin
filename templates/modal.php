<!--Email Send Modal Starts -->
<div class="modal fade" id="mail_send_modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Email this page</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="input-area">
                    <form id="awd_email_send_form" method="" action="" role="form" data-toggle="validator">
                        <?php wp_nonce_field('awd_send_email'); ?>
                        <input type="email" id="awd_modal_to_email" name="to_email" required="required" placeholder="Send to email address" value="" data-error="Please enter your email address correctly" />
                        <div class="help-block with-errors"></div>
                        <input type="text" id="awd_modal_name" name="name" required="required" placeholder="Your Name" value="" data-error="Please enter your name" />
                        <input type="hidden" id="awd_current_url" name="current_url" value="" />
                    </form>
                    <div class="frm_error"></div>
                </div>
                <button class="primary" id="mail_send_btn">Send</button>
                <div class="middle-bar">OR</div>
                <button class="secondary" id="copy_path_btn">Copy Link</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Link to this page sent to <span id="awd_email_sent">my@email.com</span>!</p>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Link copied!</p>
            </div>
        </div>
    </div>
</div>
<!-- Modal Confirmation 1 -->
