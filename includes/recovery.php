<?php
class Wp2sv_Recovery{
    var $key;
    var $hash;
    var $id;
    /**
     * @var Wordpress2StepVerification
     */
    protected $wp2sv;
    function __construct($parent) {
        $this->wp2sv=$parent;
        if(isset($_REQUEST['wp2sv_recovery_key'])){
            $this->key=$_REQUEST['wp2sv_recovery_key'];
        }else {
            $this->key = wp_generate_password(12, false);
        }
        $token=wp_salt();
        if(function_exists('wp_get_session_token')){
            $token=wp_get_session_token();
        }
        $this->hash=wp_hash( $this->key . '|' . $token, 'nonce' );
        $this->id=substr($this->hash, -12, 10);
    }
    function handle(){
        if($this->wp2sv->post('wp2sv_recovery_key')){
            if($this->wp2sv->post('wp2sv_recovery_download')){
                $this->download();
                die;
            }
            if($this->wp2sv->post('wp2sv_recovery_verify')){
                if($this->verify()===1){
                    $this->wp2sv->code_check_ok();
                }else{
                    $this->wp2sv->error_message=sprintf(__('Could not verify the file, please make sure the file is uploaded to %s','wordpress-2-step-verification'),ABSPATH);
                }
            }
        }
    }
    function verify(){
        $file=ABSPATH.$this->get_file_name();
        if(file_exists($file)){
            if(@file_get_contents($file)===$this->hash){
                @unlink($file);
                return 1;
            }
            else{
                return 0;
            }
        }
        return false;
    }

    function get_file_name(){
        return 'wp2sv'.$this->id.'.html';
    }
    function file_name(){
        echo $this->get_file_name();
    }
    function key(){
        echo $this->key;
    }
    function download(){
        header('Content-type: application/octetstream');
        header('Content-Length: ' . strlen($this->hash));
        header('Content-Disposition: attachment; filename="' . $this->get_file_name() . '"');
        echo $this->hash;
        die;
    }

}