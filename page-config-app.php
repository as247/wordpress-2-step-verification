<?php
/**
 * @var Wordpress2StepVerification $this
 */
?>
<div class="wp2sv-app-passwords">
    <h2><?php _e('App passwords','wordpress-2-step-verification'); ?></h2>
    <p class="notice"><?php _e('App passwords are used to access your account through apps such as Wordpress on an iPhone or Android or any apps use','wordpress-2-step-verification');?> <a target="_blank" href="https://codex.wordpress.org/XML-RPC_Support">XML-RPC</a>. <?php _e('We\'ll generate the app passwords for you, and you won\'t need to remember them.','wordpress-2-step-verification');?></p>
    <div class="app-passwords">
        <div class="pd">
            <div class="app-list-passwords">

                <table id="the-app-passwords" class="z-kh uo nea z-Di z-vk">
                    <thead>
                    <tr class="row">
                        <th class="col-name"><?php _e('Name','wordpress-2-step-verification');?></th>
                        <th class="col-created"><?php _e('Created','wordpress-2-step-verification');?></th>
                        <th class="col-last-used"><?php _e('Last used','wordpress-2-step-verification');?></th>
                        <th class="col-access"><?php _e('Access','wordpress-2-step-verification');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $all_pass=$this->app_password->get_passwords();
                        foreach ($all_pass as $i=>$thepass){
                            ?>
                            <tr class="app-password-item row">
                                <td class="col-name"><?php echo $thepass['n'];?></td>
                                <td class="col-created" data-c="<?php echo $thepass['c'];?>"><?php echo $thepass['c'];?></td>
                                <td class="col-last-used"><?php echo $thepass['u']?$thepass['u']:'&ndash;';?></td>
                                <td class="col-access">
                                    <div data-i="<?php echo $i?>" class="revoke-btn">
                                        <?php _e('Revoke','wordpress-2-step-verification');?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>

                    </tbody>
                </table>
                <div style="display: none;" class="no-app-pass"><?php _e('You have no app passwords.','wordpress-2-step-verification');?></div>
            </div>
            <div class="app-add-password">
                <span>
                    <input type="text" maxlength="100" class="app-name" placeholder="e.g. WP on my Android">
                </span>

                <div class="generate" style="-moz-user-select: none;"><?php _e('Generate','wordpress-2-step-verification');?></div>


            </div>
        </div>
    </div>


    <div id="app-password-created" class="modal-dialog" tabindex="0" style="opacity: 0;display: none;">
        <div class="modal-dialog-title">
            <p class="modal-dialog-title-text"><?php _e('Generated app password');?></p>
        </div>
        <div class="modal-dialog-content">
            <div class="content">
                <div class="apc-title"><?php _e('Your app password for your device','wordpress-2-step-verification');?></div>
                <div dir="ltr" class="apc-pass"><span class="iZ"></span></div>
                <div class="apc-direction">
                    <div class="apc-title"><?php _e('How to use it','wordpress-2-step-verification');?></div>
                    <?php _e('Go to the settings for your Wordpress Account in the application or device you are trying to set up. Replace your password with the 16-character password shown above.','wordpress-2-step-verification');?>
                    <p><?php _e('Just like your normal password, this app password grants complete access to your Wordpress Account. You won\'t need to remember it, so don\'t write it down or share it with anyone.','wordpress-2-step-verification');?></p></div>
            </div>
        </div>
        <div class="modal-dialog-buttons">
            <button class="wp2sv-buttonset-action" name="cancel"><?php _e('Done', 'wordpress-2-step-verification'); ?></button>
        </div>
    </div>

</div>

