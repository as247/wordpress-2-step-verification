<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<h2 class="section-heading">
    <?php _e('How to receive codes', 'wordpress-2-step-verification'); ?>
</h2>
<h3 class="section-label">
    <?php _e('Mobile application', 'wordpress-2-step-verification'); ?>
</h3>
<div class="section-data">
    <?php if ($this->wp2sv_mobile): ?>
        <div class="smallicon-content icon-checkmark">
            <?php echo $this->wp2sv_mobile; ?>
            <span class="edit-links-container">
        <a id="change-app-link" class="modal-open" data-modal="phone-change"
           href="#"><?php _e('Move to a different phone', 'wordpress-2-step-verification'); ?></a>

      </span>
        </div>
    <?php else: ?>

        <span class="edit-links-container">
      <a class="add-device" data-device="android" href="#">Android</a>
      -
      <a class="add-device" data-device="iphone" href="#">iPhone</a>
      -
      <a class="add-device" data-device="blackberry" href="#">BlackBerry</a>
      </span>
    <?php endif; ?>
</div>

<div class="section-spacer"></div>
<h3 class="section-label">
    <?php _e('Email'); ?>
</h3>
<div class="section-data">
    <?php if ($this->wp2sv_email): ?>
        <div class="smallicon-content icon-checkmark">
      <span class="email-display">
      <span dir="ltr">
        <?php echo $this->wp2sv_email; ?>
      </span>
      </span>
      <span class="edit-links-container">
      <a id="edit-email-link" href="#"><?php _e('Edit', 'wordpress-2-step-verification'); ?></a>
      -
      <a id="remove-email-link" href="#"><?php _e('Remove', 'wordpress-2-step-verification'); ?></a>
      </span>

        </div>
    <?php else: ?>
        <span class="edit-links-container">
            <a class="add-device" data-device="email" href="#">Add an email</a>
        </span>
    <?php endif; ?>
</div>

<div class="section-spacer"></div>
<div class="section-label">
    <h3>
        <?php _e('Printable backup codes','wordpress-2-step-verification');?>
    </h3>
    <div id="printable-warning">
        <span class="warning"><?php _e('Warning:', 'wordpress-2-step-verification'); ?></span>
        <?php _e('If your phone is unavailable, these codes will be the only way to sign in to your account.', 'wordpress-2-step-verification');?>
            <?php _e('Keep them someplace accessible, like your wallet.', 'wordpress-2-step-verification'); ?>
    </div>
</div>
<div class="section-data">
    <?php if ($this->get_backup_codes()) {
        ?>
        <p><?php printf(__('You have %s unused codes', 'wordpress-2-step-verification'), $this->get_backup_codes('unused')); ?></p>
        <table>
            <tr>
                <td width="50%"><?php _e('Generated on:', 'wordpress-2-step-verification'); ?></td>
                <td width="50%"><?php echo mysql2date(get_option('date_format'), $this->get_backup_codes('last')); ?></td>
            </tr>
        </table>

        <a id="show-codes-link" href="#"><?php _e('Show backup codes', 'wordpress-2-step-verification'); ?></a>

        <div id="printable-codes" style="display: none;">
            <div class="backup-codes">
                <?php $this->the_backup_codes(); ?>
            </div>
            <div class="fixed">
                <p><?php _e('Keep them someplace accessible, like your wallet.','wordpress-2-step-verification')?> <?php _e('Each code can be used only once.', 'wordpress-2-step-verification'); ?></p>
                <a id="print-backup-codes" class="wp2sv-buttons"><?php _e('Print', 'wordpress-2-step-verification'); ?></a>
                <a id="download-backup-codes" class="wp2sv-buttons"><?php _e('Save to text file', 'wordpress-2-step-verification'); ?></a>
            </div>
        </div>
        <div id="backup-codes-for-print" style="display: none">
            <h2><?php _e('BACKUP VERIFICATION CODES', 'wordpress-2-step-verification'); ?></h2>
            <span><?php echo $this->user->user_login; ?></span>
            <div class="backup-codes">
                <?php $this->the_backup_codes(); ?>
            </div>
            <div class="note fixed">
                <?php
                _e('Running out of backup codes? Generate new ones:');
                echo "<br/>";
                echo '<a href="' . admin_url('users.php?page=wp2sv') . '">' . admin_url('users.php?page=wp2sv') . '</a>';
                echo "<br/>";
                _e('Only the latest set of backup codes will work.', 'wordpress-2-step-verification');
                echo "<br/><br/>";
                _e('Keep them someplace accessible, like your wallet.', 'wordpress-2-step-verification');
                echo "<br/>";
                _e('Each code can be used only once.', 'wordpress-2-step-verification');
                ?>
            </div>
        </div>
    <?php } ?>
    <div class="actions fixed">
        <p style="float: none;clear: both">
            <?php _e('Running out of backup codes? Generate new ones at:', 'wordpress-2-step-verification'); ?>
            <br/>
            <?php echo admin_url('users.php?page=wp2sv'); ?>
            <br/>
            <?php _e('Only the latest set of backup codes will work.', 'wordpress-2-step-verification'); ?>

        </p>
        <a id="generate-codes-button" class="wp2sv-buttons"><?php _e('Generate new codes','wordpress-2-step-verification');?></a>
    </div>
</div>

<div class="section-spacer"></div>
<div class="section-label">
    <h3>
        <?php _e('Trust this computer', 'wordpress-2-step-verification'); ?>
    </h3>

    <div id="printable-warning">
        <span class="warning"><?php _e('Warning:', 'wordpress-2-step-verification'); ?></span>
        <?php _e('Trusted computers only ask for verification codes once every 30 days. If you lose your phone, you might be able to access your account from a trusted computer without needing a code. We recommend that you make this a trusted computer only if you trust the people who have access to it.', 'wordpress-2-step-verification'); ?>
    </div>
</div>
<div class="section-data">
    <input type="checkbox" name="trusted" id="trust_computer" value="1"<?php checked($this->wp2sv_user_fav_trusted()) ?>/>
    <label
        for="trust_computer"><?php echo $this->wp2sv_user_fav_trusted() ? __('Trusted', 'wordpress-2-step-verification') : __('Untrusted', 'wordpress-2-step-verification'); ?></label>
</div>

<div class="section-spacer"></div>
<div class="section-label">
    <h3>
        <?php _e('App passwords', 'wordpress-2-step-verification'); ?>
    </h3>

    <div id="printable-warning">
        <span class="warning"><?php _e('Warning:', 'wordpress-2-step-verification'); ?></span>
        <?php _e('App passwords are used in wordpress application.', 'wordpress-2-step-verification'); ?>
    </div>
</div>
<div class="section-data">
    <a id="manage-app-pass-link" href="<?php echo $this->get_page_url('app');?>"><?php
        $count=count($this->app_password->get_passwords());
        if(!$count) {
            _e('None', 'wordpress-2-step-verification');
        }else{
            printf(_n('%s password','%s passwords',$count),$count);
        }
        ?></a>
</div>

<div class="section-data modal-section">

    <div id="phone-change" class="modal-dialog" tabindex="0" style="opacity: 0;display: none;">
        <div class="modal-dialog-title">
            <span class="modal-dialog-title-text"></span><span
                class="modal-dialog-title-close" tabindex="0"></span></div>
        <div class="modal-dialog-content">
            <div class="chooseapptype-dialog" id="settings-choose-app-type-dialog">
                <h2 id="settings-choose-app-type-title">
                    <?php _e('Move Authenticator to a different phone', 'wordpress-2-step-verification'); ?>
                </h2>

                <p>
                    <?php _e('We only support a single Authenticator app configured for your account. Please select your new phone type:', 'wordpress-2-step-verification'); ?>
                </p>

                <p id="settings-no-choice-app-error" style="color:red;display: none">
                    <?php _e('Please select one phone type bellow', 'wordpress-2-step-verification'); ?>
                </p>

                <div class="settings-apptype-selector-box">
                    <div class="settings-apptype-radio">
                        <label>
                            <input type="radio" value="android" id="settings-choose-app-type-radio-android"
                                   class="apptype-android" name="settings-choose-app-type-radio">
                            Android
                        </label>
                    </div>
                    <div class="settings-apptype-radio">
                        <label>
                            <input type="radio" value="iphone" id="settings-choose-app-type-radio-iphone"
                                   class="apptype-iphone" name="settings-choose-app-type-radio">
                            iPhone
                        </label>
                    </div>
                    <div class="settings-apptype-radio">
                        <label>
                            <input type="radio" value="blackberry" id="settings-choose-app-type-radio-blackberry"
                                   class="apptype-blackberry" name="settings-choose-app-type-radio">
                            BlackBerry
                        </label>
                    </div>
                </div>
                <p id="settings-choose-app-type-old-authenticator-invalid">
                    <?php _e('Once you complete this setup,', 'wordpress-2-step-verification'); ?> <span
                        style="font-weight: bold;"><?php _e('the codes generated by your old Authenticator app will stop working', 'wordpress-2-step-verification'); ?></span>.
                </p>

            </div>
        </div>
        <div class="modal-dialog-buttons">

            <button name="action" class="wp2sv-buttonset-action"><?php _e('Continue', 'wordpress-2-step-verification'); ?></button>
            <button name="cancel"><?php _e('Cancel', 'wordpress-2-step-verification'); ?></button>
        </div>
    </div>
    <div id="wp2sv-disable" class="modal-dialog" tabindex="0" style="opacity: 0;display: none;" role="dialog"
         aria-labelledby=":9">
        <div class="modal-dialog-title modal-dialog-title-draggable"><span class="modal-dialog-title-text" id=":9"
                                                                           role="heading"></span><span
                class="modal-dialog-title-close" role="button" tabindex="0" aria-label="Close"></span></div>
        <div class="modal-dialog-content">
            <div class="disable-dialog" id="settings-disable-dialog">
                <h2>
                    <?php _e('Turn off 2-step verification', 'wordpress-2-step-verification'); ?>
                </h2>

                <p class="settings-textparagraph">
                    <?php _e('You will no longer be asked for verification codes when you sign in to your account', 'wordpress-2-step-verification'); ?>
                    .
                </p>


                <div class="settings-textparagraph">
                    <input type="checkbox" id="settings-disable-dialog-clearsettings" checked="checked"
                           class="settings-checkbox clearsettings" value="yes" name="wp2sv_clear_settings">
                    <label for="settings-disable-dialog-clearsettings">
                        <?php _e('Also clear my 2-step verification settings', 'wordpress-2-step-verification'); ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-dialog-buttons">
            <button name="action" class="wp2sv-buttonset-action"><?php _e('Turn off', 'wordpress-2-step-verification'); ?></button>
            <button name="cancel"><?php _e('Cancel', 'wordpress-2-step-verification'); ?></button>
        </div>
    </div>
</div>