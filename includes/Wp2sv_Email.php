<?php
/**
 * Created by PhpStorm.
 * User: as247
 * Date: 29-Oct-18
 * Time: 8:05 AM
 */

class Wp2sv_Email extends Wp2sv_Base
{
	protected function _construct()
	{
		add_filter('wp2sv_mail','Wp2sv_Mailer::send',100,4);
	}


	function getEmailSubject(){
        return __('Your verification code','wordpress-2-step-verification');
    }
    function getEmailContent(){
        $code=$this->otp->generate();
        $code=end($code);
        $code=str_pad($code,6,'0',STR_PAD_LEFT);
		/* translators: %s is replaced with verification code */
        return sprintf(__('Your verification code is %s','wordpress-2-step-verification'),$code);
    }

    /**
     * Sent code to registered email
     * @param $email
     * @return bool
     */
    function sendCodeToEmail($email){
        if($email) {
            return apply_filters('wp2sv_mail',null,$email, $this->getEmailSubject(), $this->getEmailContent());
        }
        return false;
    }
    function handle(){
        $action=$this->post('wp2sv_action');
        $email=$this->handler->getEmail();
        $code=$this->post('wp2sv_code');
        $scale=1;
        if($email) {
        	$limiter=Wp2sv_Limit::forUser($this->model);
            $sent = $this->model->email_sent;
            $sent = absint($sent);
            if ($action == 'send-email'
				||
				($this->handler->getPrimaryMethod() == 'email' && !$sent)) {
                if (!$limiter->isLockedEmail()) {
                    if ($this->sendCodeToEmail($email)) {
						$limiter->attemptEmail();
                    } else {
                        $this->handler->error(__('The e-mail could not be sent.','wordpress-2-step-verification').' '.__('Possible reason: your host may have disabled the mail() function...', 'wordpress-2-step-verification'));
                    }
                } else {
					/* translators: %s: Time for unlock */
					$this->handler->error(sprintf(__('You have exceeded maximum send email retries. Please try after %s.','wordpress-2-step-verification'),$limiter->sendMailWillBeUnlockIn()));
                }
            }else{
                $this->handler->error('');
            }
            if ($code) {
                $scale = $sent + 1;
            }
        }
        return $scale;
    }
}
