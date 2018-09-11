<?php
/**
 * @var Wordpress2StepVerification $this
 */
?><h1>
	<?php _e( 'Set up 2-step verification for', 'wordpress-2-step-verification'); ?> <span
		class="bolder"><?php echo $this->user->display_name; ?></span>
</h1>
<?php if(!$this->is_enabled()||!$this->is_changing_device()){?>
<ol class="process-map" id="process-map">
	<li class="pm-current" id="pm-generate"><?php
        $this->get_device()=='email'?
            _e( 'Set up your email', 'wordpress-2-step-verification'):
            _e( 'Set up your phone', 'wordpress-2-step-verification');
        ?></li>
	<li class="pm-incomplete" id="pm-remember"><?php _e( 'Verify computer', 'wordpress-2-step-verification'); ?></li>
	<li class="pm-incomplete" id="pm-confirm"><?php _e( 'Activate', 'wordpress-2-step-verification'); ?></li>
</ol>
<?php }?>
<div id="config-container">
    <?php if(!$this->get_device()){?>
	<div id="pm-subheader">
		<div class="pm-subheader" id="generate-header">
			<?php _e( 'Choose how you\'d like to get verification codes &mdash; in an email,or from an application on your smartphone.', 'wordpress-2-step-verification'); ?>
		</div>
	</div>
    <?php }?>
	<div class="config-section" id="device-type-selection">
		<div class="icon-content  icon-primaryphone" id="mobile-device-icon">
			<select id="device-type">
				<option value=""><?php _e('Choose one:','wordpress-2-step-verification');?></option>
				<optgroup label="<?php _e( 'Smartphone application', 'wordpress-2-step-verification'); ?>">
					<option value="android"<?php $this->last_page_selected('android');?>>Android</option>
					<option value="iphone"<?php $this->last_page_selected('iphone');?>>iPhone</option>
					<option value="blackberry"<?php $this->last_page_selected('blackberry');?>>BlackBerry</option>
				</optgroup>
				<optgroup label="Email">
					<option value="email"<?php $this->last_page_selected('email');?>>Email</option>
				</optgroup>
			</select>
		</div>
	</div>
</div>

<div id="button-container">

	<?php if($this->is_changing_device()&&$this->is_enabled()){?>
    <?php }else{?>
        <input type="button" id="back-button" value="« <?php _e('Back','wordpress-2-step-verification');?>" name="Back" disabled=""/>
        <span class="button-separator"></span>
	    <input type="button" id="next-button" value="<?php _e('Next','wordpress-2-step-verification')?> »" name="Next" disabled=""/>
    <?php }?>
    <span id="activate-button" class="g-button-activate" style="display: none">
        <a id="submit-button" style="text-decoration:none; color:white;"
           href="#"><?php _e( 'Turn on 2-step verification', 'wordpress-2-step-verification'); ?></a>
    </span>

	<a href="#" id="cancel-link"><?php _e('Cancel','wordpress-2-step-verification');?></a>
</div>
<div id="inactive-elements" class="inactive">
	<div class="config-section" id="email-address">
		<div class="heading"><?php _e('Add an email address where Wordpress 2-step verification can send codes.','wordpress-2-step-verification');?></div>
		<div class="phone-widget" id="primary-phone-widget">
			<table>
				<tbody>
				<tr>
					<td></td>
					<td></td>
					<td class="device-address" id="primary-email-address-location">
						<input type="text" value="<?php _e( $this->wp2sv_email ); ?>" id="primary-email"
						       name="emailAddress" dir="ltr"></td>
					<td class="primary-phone">&nbsp;<img src="<?php $this->plugin_url(); ?>/images/loading.gif"
					                                     class="smallicon" id="primary-phone-valid" alt=""
					                                     style="visibility: inherit;"></td>
					<td class="phone-usage-message primary-phone">
						<div><?php _e('Enter your email address','wordpress-2-step-verification');?>.</div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="primary-phone">
						<div class="example" id="primary-example-container"><?php _e('ex:','wordpress-2-step-verification');?> <span dir="ltr" id="primary-example-number"><?php _e('example@domain.com','wordpress-2-step-verification');?></span>
						</div>
					</td>

				</tr>
				</tbody>
			</table>
			<div class="inactive w2sverror" id="primary-error"></div>
		</div>
		<div id="primary-number-test">
			<div id="primary-test-heading" class="heading"><?php _e('Let\'s test the email.','wordpress-2-step-verification');?></div>
			<div id="primary-verify-inputs" class="border-box phone-test">
				<ol class="phone-test-steps">
					<li>
						<div class="ml-list-item"><?php _e('Click "Send code" and check your email for the verification code.','wordpress-2-step-verification');?>
							<div class="send-code-container">
								<input type="submit" name="SendCode" value="<?php _e('Send code','wordpress-2-step-verification');?>" id="primary-send-code"
								       disabled=""/>
                                <span class="box">
                                    <img style="visibility: hidden;" class="icon smallicon" alt=""
                                         src="<?php $this->plugin_url(); ?>/images/loading.gif">
                                    <div id="primary-code-sent" class="smallicon-content"></div>
</span>
							</div>
						</div>
					</li>
					<li class=" inactive-text" id="primary-test-input">
						<div class="ml-list-item">
							<?php _e('Enter the code you receive on your email','wordpress-2-step-verification');?>.
							<div>
								<div class="verify-code-widget">
									<label for="primary-verify-code">
										<?php _e('Code','wordpress-2-step-verification');?>:
									</label>
									<input type="text" disabled="" size="6" dir="ltr" id="primary-verify-code"
									       name="verifyPin" autocomplete="off">&nbsp;
                                    <?php if($this->is_enabled()&&$this->is_changing_device()){
                                    ?>
                                        <input type="submit" disabled value="<?php _e('Verify and save','wordpress-2-step-verification');?>" id="primary-verify-button" name="VerifyAndSaveApp">
                                        <?php
                                    }else{?>
									<input type="submit" disabled="" value="<?php _e('Verify','wordpress-2-step-verification');?>" id="primary-verify-button"
									       name="VerifyPhone">
                                    <?php }?>

								</div>
								<img style="visibility: hidden;" class="icon smallicon" alt=""
								     src="<?php $this->plugin_url(); ?>/images/loading.gif">

								<div id="primary-verify-container" class="smallicon-content"></div>
							</div>
						</div>
					</li>
				</ol>
			</div>
		</div>

	</div>

	<div id="remember-computer-state" class="config-section">
		<div class="remember-heading">
			<?php _e('Make this a <span class="trusted-computer-emphasis">trusted computer</span>','wordpress-2-step-verification');?>?
		</div>
		<div class="remember-box">
			<p class="remember-text">
				<?php
				_e('Trusted computers only ask for verification codes once every 30 days. If you lose your phone, you might be able to access your account from a trusted computer without needing a code. We recommend that you make this a trusted computer only if you trust the people who have access to it.','wordpress-2-step-verification');?>
			</p>
		</div>
		<label for="rememberComputerVerify">
			<input type="checkbox" id="rememberComputerVerify" name="trusted" checked=""/>
			<?php _e('Trust this computer','wordpress-2-step-verification');?>
			<br>
			<span class="smaller" style="margin-left:24px;"><?php _e('You can always change which computers you trust in your Account settings.','wordpress-2-step-verification');?></span>
		</label>
	</div>


	<div id="confirm-section" class="config-section">
		<div class="confirm-heading">
			<?php _e('Turn on 2-step verification','wordpress-2-step-verification');?>
		</div>
		<div id="confirm-action">
			<p>
				<?php _e('You will be asked for a code whenever you sign in from an unrecognized computer or device.','wordpress-2-step-verification');?>
			</p>
		</div>
	</div>

	<div id="configure-app-android">
		<div class="heading">
			<?php _e('Install the verification application for','wordpress-2-step-verification');?>
			<span id="app-download-type">Android</span>.
		</div>
		<ol class="app-instructions">
			<li><p class="ml-list-item">
					<?php _e('On your phone, go to the Google Play Store.','wordpress-2-step-verification');?>
				</p></li>
			<li><p class="ml-list-item">
					<?php _e('Search for <b>Google Authenticator</b>.','wordpress-2-step-verification');?>
					<span class="smaller secondary">(<a target="_blank"
					                                    href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"><?php
							_e('Download from the Google Play Store','wordpress-2-step-verification');?></a>)</span>
				</p></li>
			<li><p class="ml-list-item">
					<?php _e('Download and install the application.','wordpress-2-step-verification');?>
					<br>
				</p></li>
		</ol>
		<div class="heading">
			<?php _e('Now open and configure Google Authenticator.','wordpress-2-step-verification');?>
		</div>
		<ol class="app-instructions">
			<li>
				<?php _e('In Google Authenticator, touch Menu and select "Set up account." ','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('Select "Scan a barcode."','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('Use your phone\'s camera to scan this barcode.','wordpress-2-step-verification');?>
				<div class="qr-box">
					<img src="<?php $this->chart_url(); ?>"/>
				</div>
			</li>
		</ol>
		<div class="manual-zippy">
			<a href="#"><img src="<?php $this->plugin_url(); ?>/images/zippy_plus_sm.gif" class="icon"
			                 style="margin-top: 2px; visibility: inherit;"></a>

			<div class="smallicon-content"><a href="#"><p id="manual-label-android">
						<?php _e('Can\'t scan the barcode?','wordpress-2-step-verification');?>
					</p></a>
				<ol class="app-instructions inactive" id="manual-content-android">
					<li>
						<?php _e('In Google Authenticator, touch Menu and select "Set up account."','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('Select "Enter provided key"','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('In "Enter account name" type your wordpress username.','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('In "Enter your key" type your secret key:','wordpress-2-step-verification');?>
						<div class="secret-key-box">
							<div class="secret-key">
								<?php $this->secret_key(); ?>
							</div>
							<div class="smaller subtitle">
								<?php _e('Spaces don\'t matter.','wordpress-2-step-verification');?>
							</div>
						</div>
					</li>
					<li>
						<?php _e('Key type: make sure "Time-based" is selected.','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('Tap Add.','wordpress-2-step-verification');?>
					</li>
				</ol>
			</div>
			<div style="clear: both;"></div>

		</div>
	</div>

	<div id="configure-app-iphone">
		<div class="heading">
			<?php _e('Install the verification application for','wordpress-2-step-verification');?>
			<span id="app-download-type">iPhone</span>.
		</div>
		<ol class="app-instructions">
			<li><p class="ml-list-item">
					<?php _e('On your iPhone, tap the App Store icon.','wordpress-2-step-verification');?>
				</p></li>
			<li><p class="ml-list-item">
					<?php _e('Search for <b>Google Authenticator</b>.','wordpress-2-step-verification');?>
					<span class="smaller secondary">(<a
							href="http://itunes.apple.com/us/app/google-authenticator/id388497605?mt=8" target="_blank"><?php _e('Download from the App Store');?></a>)</span>
				</p></li>
			<li><p class="ml-list-item">
					<?php _e('Tap the app, and then tap Free to download and install it.','wordpress-2-step-verification');?>
				</p></li>
		</ol>
		<div class="heading">
			<?php _e('Now open and configure Google Authenticator.','wordpress-2-step-verification');?>
		</div>
		<ol class="app-instructions">
			<li>
				<?php _e('In Google Authenticator, tap "+", and then "Scan Barcode." ','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('Use your phone\'s camera to scan this barcode.','wordpress-2-step-verification');?>
				<div class="qr-box">
					<img src="<?php $this->chart_url(); ?>">
				</div>
			</li>
		</ol>
		<div class="manual-zippy">
			<a href="#"><img style="margin-top: 2px; visibility: inherit;" class="icon"
			                 src="<?php $this->plugin_url(); ?>/images/zippy_plus_sm.gif"></a>

			<div class="smallicon-content"><a href="#"><p id="manual-label-iphone">
						<?php _e('Can\'t scan the barcode?','wordpress-2-step-verification');?>
					</p></a>
				<ol id="manual-content-iphone" class="app-instructions inactive">
					<li>
						<?php _e('In Google Authenticator, tap +.','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('Key type: make sure "Time-based" is selected. ','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('In "Account" type your wordpress username.','wordpress-2-step-verification');?>
					</li>
					<li>
						<?php _e('In "Key" type your secret key:','wordpress-2-step-verification')?>
						<div class="secret-key-box">
							<div class="secret-key">
								<?php $this->secret_key(); ?>
							</div>
							<div class="smaller subtitle">
								<?php _e('Spaces don\'t matter.','wordpress-2-step-verification');?>
							</div>
						</div>
					</li>
					<li>
						<?php _e('Tap Done.','wordpress-2-step-verification');?>
					</li>
				</ol>
			</div>
			<div style="clear: both;"></div>

		</div>
	</div>
	<div id="configure-app-blackberry">
		<div class="heading">
			<?php _e('Install the verification application for','wordpress-2-step-verification');?>
			<span id="app-download-type">BlackBerry</span>.
		</div>
		<ol class="app-instructions">
			<li>
				<?php _e('On your phone, open a web browser.','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('Go to <strong>m.google.com/authenticator</strong>','wordpress-2-step-verification');?>.
			</li>
			<li>
				<?php _e('Download and install the Google Authenticator application.','wordpress-2-step-verification');?>
			</li>
		</ol>
		<div class="heading">
			<?php _e('Now open and configure Google Authenticator.','wordpress-2-step-verification');?>
		</div>
		<ol class="app-instructions">
			<li>
				<?php _e('In Google Authenticator, select Manual key entry.','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('In "Enter account name" type your wordpress username.','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('In "Enter key" type your secret key:','wordpress-2-step-verification');?>
				<div class="secret-key-box">
					<div class="secret-key">
						<?php $this->secret_key(); ?>
					</div>
					<div class="smaller subtitle">
						<?php _e('Spaces don\'t matter.','wordpress-2-step-verification');?>
					</div>
				</div>
			</li>
			<li>
				<?php _e('Choose Time-based type of key.','wordpress-2-step-verification');?>
			</li>
			<li>
				<?php _e('Tap Save.','wordpress-2-step-verification');?>
			</li>
		</ol>
	</div>
	<div id="app-verify-success" class="active-text">
		<p>
			<?php
			printf(__('Your %s device is configured','wordpress-2-step-verification'),$this->configuring_device());?>.
		</p>

		<p class="last verify-success-click-next-message">
			<?php _e('Click Next to continue.','wordpress-2-step-verification');?>
		</p>
	</div>
	<div id="app-verify-failures" class="verify-tip">
		<?php _e('Tip: Codes are time-dependent. Make sure your phone is set to the correct local time.','wordpress-2-step-verification');?>
	</div>
	<div id="email-verify-success" class="active-text">
		<p>
			<?php _e('Your email is configured.','wordpress-2-step-verification');?>
		</p>

		<p class="last verify-success-click-next-message">
			<?php _e('Click Next to continue.','wordpress-2-step-verification');?>
		</p>
	</div>
	<div id="configure-app" class="config-section">
		<div class="border-box mobile-app-step">
			<div id="configure-app-instructions"></div>
			<p class="last">
				<?php _e('Once you have scanned the barcode, enter the 6-digit verification code generated by the Authenticator app.','wordpress-2-step-verification');?>
			</p>

			<div class="verify-code-widget">
				<label for="app-verify-code">
					<?php _e('Code:','wordpress-2-step-verification');?>
				</label>
				<input type="text" size="6" dir="ltr" id="app-verify-code" name="verifyPinApp" autocomplete="off">&nbsp;
                <?php if($this->is_changing_device()&&$this->is_enabled()){?>
                    <input type="submit" value="<?php _e('Verify and save','wordpress-2-step-verification');?>" id="app-verify-button" name="VerifyAndSaveApp">
                <?php }else{?>
                <input type="submit" value="<?php _e('Verify','wordpress-2-step-verification');?>" id="app-verify-button" name="VerifyApp">
                <?php }?>
			</div>
			<img style="visibility: hidden;" class="icon smallicon" alt=""
			     src="<?php $this->plugin_url(); ?>/images/loading.gif">

			<div id="app-verify-container" class="smallicon-content"></div>
		</div>
	</div>
</div>
