<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<div class="wrap wp2sv-config">
    <div class="icon32" id="icon-options-general"><br/></div>
    <a href="<?php menu_page_url('wp2sv')?>">
        <h2><?php _e('Wordpress 2-step verification', 'wordpress-2-step-verification') ?></h2>
    </a>
    <?php if (!$this->is_configuring()): ?>
        <div id="message" class="updated">
            <p>
                <?php printf(__('2-step verification is <strong>%s</strong> for %s', 'wordpress-2-step-verification'), $this->get_status(), $this->user->display_name); ?>
                <span class="smaller" style="float:right;margin-left: 10px;">
                Your server time in UTC is: <span id="wp2sv-server-time"
                                                  data-timestamp="<?php echo $this->otp->time(); ?>"><?php echo date('Y-m-d H:i:s', $this->otp->time()); ?></span> <a
                        href="#" id="sync-clock" class="sync-link" title="Sync time">Sync time</a><br>
                    <?php if ($gmt_offset = get_option('gmt_offset')): ?>
                        Your local time in UTC<?php echo $gmt_offset > 0 ? '+' : '', $gmt_offset; ?> is:<span
                            id="wp2sv-local-time"
                            data-timestamp="<?php echo $this->otp->local_time(); ?>">><?php echo date('Y-m-d H:i:s', $this->otp->local_time()); ?></span>
                    <?php endif; ?>
            </span>
            </p>
            <p>
                <?php if ($this->is_enabled()): ?>
                    <a href="#" id="wp2sv-disable-link" class="modal-open"
                       data-modal="wp2sv-disable"><?php _e('Turn off 2-step verification', 'wordpress-2-step-verification'); ?>...</a>
                <?php else: ?>
                    <a href="#" id="wp2sv-enable-link"><?php _e('Turn on 2-step verification', 'wordpress-2-step-verification'); ?>...</a>
                <?php endif; ?>
                <?php //echo $this->otp->get_decoded_secret_key();
                //echo $this->get_url();
                ?>

            </p>

        </div>
    <?php endif; ?>
    <?php
        $contentClass=array();
        $contentClass[]='wp2sv-config';
        if($this->get_config_page_name()!='app'){
            $contentClass[]='default-content-area';
        }
    ?>
    <div class="<?php echo join(' ',$contentClass);?>" id="wp2sv-config-section">
        <form method="POST" action="" id="wp2sv-config-form">
            <input type="hidden" name="wp2sv_save" value="<?php $this->save_key(); ?>"/>
            <input type="hidden" name="wp2sv_action" value="" id="wp2sv_action"/>
            <input type="hidden" name="wp2sv_page_config" value="<?php echo $this->get_device() ?>" id="wp2sv_page_config"/>
            <input type="hidden" id="wp2sv_secret" value="<?php echo $this->secret_key(false);?>"/>
            <?php $this->get_current_page_config(); ?>

        </form>
    </div>
    <div class="modal-dialog-bg" style="opacity: 0.75; display: none;" aria-hidden="true"></div>
</div>