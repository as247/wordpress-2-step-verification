<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<div class="form other-methods">
<ol id="possible-methods">
    <?php if($this->have_phone()){?>
    <li>
        <form method="POST">
            <input type="hidden" name="wp2sv_type" value="phone">
            <button type="submit"><img src="<?php $this->plugin_url('template/assets/images/authenticator.png'); ?>"><span class="btn-text">
                    <span class="text"><?php _e('Get a verification code from the <b>Google Authenticator</b> app','wordpress-2-step-verification');?></span>
                </span></button>
        </form>
    </li>
    <?php }else{?>

    <?php }?>
    <li>
        <form method="post">
            <input type="hidden" name="wp2sv_type" value="email">
            <input type="hidden" name="wp2sv_action" value="send-email">
            <?php wp_nonce_field('wp2sv_nonce','wp2sv_nonce');?>
            <button type="submit"><img src="<?php $this->plugin_url('template/assets/images/email.png'); ?>"><span class="btn-text">
                    <?php printf(__('Get an email with a verification code at %s','wordpress-2-step-verification'),$this->get_email_ending());?>
                </span></button>
        </form>
    </li>
    <?php if($this->have_backup_codes()){?>
    <li>
        <form method="post">
            <input type="hidden" name="wp2sv_type" value="backup-codes">
            <?php wp_nonce_field('wp2sv_nonce','wp2sv_nonce');?>
            <button type="submit"><img src="<?php $this->plugin_url('template/assets/images/backup.png'); ?>"><span class="btn-text">
                    <?php _e('Use backup code', 'wordpress-2-step-verification'); ?>
                </span></button>
        </form>
    </li>
    <?php }?>
    <?php if($this->can_recovery()){?>
        <li class="recovery">
            <form method="post">
                <input type="hidden" name="wp2sv_type" value="recovery">
                <?php wp_nonce_field('wp2sv_nonce','wp2sv_nonce');?>
                <button type="submit"><img src="<?php $this->plugin_url('template/assets/images/manualrecovery.png'); ?>"><span class="btn-text">
                    <?php _e('Manual recovery', 'wordpress-2-step-verification'); ?>
                        <em class="help-block"><?php _e('You will need ftp access to upload verification file', 'wordpress-2-step-verification'); ?></em>
                </span></button>
            </form>
        </li>
    <?php }?>
</ol>

</div>