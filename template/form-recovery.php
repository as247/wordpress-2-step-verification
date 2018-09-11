<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<div class="form form-recovery">
    <form name="recoverForm" method="post" action="" id="recovery-form">
        <input type="hidden" name="wp2sv_nonce" value="<?php echo wp_create_nonce('wp2sv_nonce'); ?>"/>
        <input type="hidden" name="wp2sv_recovery_key" value="<?php $this->recovery->key() ?>"/>
        <input type="hidden" name="wp2sv_type" id="type" value="<?php echo $this->get_receive_method(); ?>"/>
        <div class="title"><?php _e('Upload an HTML file to your site','wordpress-2-step-verification');?></div>
        <div class="desc">
            <ol>
                <li><b><?php _e('Download','wordpress-2-step-verification');?></b><input class="link" type="submit" name="wp2sv_recovery_download" value="<?php _e('this HTML verification file','wordpress-2-step-verification');?>"> <span class="help">[<?php $this->recovery->file_name();?>]</span></li>
                <li><?php printf(__('<b>Upload</b> the file to Wordpress directory (same directory with %s)','wordpress-2-step-verification'),'<span>wp-config.php</span>');?></li>
                <li><?php _e('<b>Click</b> Verify below','wordpress-2-step-verification');?></li>
            </ol>
        </div>
        <?php if ($this->error_message): ?>
            <span class="error" id="error">
                <?php echo $this->error_message; ?>
            </span>
        <?php endif; ?>
        <input type="submit" name="wp2sv_recovery_verify" class="submit" value="<?php _e('Verify','wordpress-2-step-verification')?>">


    </form>
</div>
<div class="recovery">
    <?php include(dirname(__FILE__).'/others-link.php');?>
</div>
