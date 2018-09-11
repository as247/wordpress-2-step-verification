<?php
class Wp2sv_AppPass{
    protected $user_id=0;
    function __construct($user_id=0)
    {
        if(!$user_id&&function_exists('get_current_user_id')&&function_exists('wp_get_current_user')){
            $user_id=get_current_user_id();
        }
        $this->user_id=$user_id;
    }
    function create($app_name){
        $pass=$this->random_password();
        $the_pass=array(
            'h'=>$this->hash_password($pass),//the hash
            'c'=>current_time('mysql'),//created time
            'u'=>false,//used
            'n'=>$app_name,
        );
        $passwords=$this->get_passwords();
        $passwords[]=$the_pass;
        end($passwords);
        $the_pass['i']=key($passwords);//index
        $the_pass['p']=$pass;//plaintext pass
        $this->update_passwords($passwords);
        return $the_pass;
    }
    function verify($password,&$index=false){
        $application_pass=$this->get_passwords();
        if(!is_array($application_pass)){
            return false;
        }
        foreach($application_pass as $index=> $app_pass){
            if($this->check_password($password, $app_pass['h'])){
                $this->password_used($index);
                return true;
            }
        }
        return false;
    }
    function password_used($index){
        $passwords=$this->get_passwords();
        if(isset($passwords[$index]['u'])){
            $passwords[$index]['u']=current_time('mysql');
            $this->update_passwords($passwords);
        }
    }
    function revoke($index){
        if(class_exists('WP_Session_Tokens')) {
            if ($password = $this->get_password($index)) {
                if (!empty($password['t'])) {
                    $manager=WP_Session_Tokens::get_instance(get_current_user_id());
                    foreach ($password['t'] as $token => $notuse) {
                        $manager->destroy($token);
                    }
                }
            }
        }
        $this->update_password($index,false);
    }
    function random_password($length=16){
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $password = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $password .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
        }
        return $password;
    }
    function hash_password($plain){
        return $this->get_hasher()->HashPassword($plain);
    }
    function check_password($pass,$hash){
        return $this->get_hasher()->CheckPassword($pass,$hash);
    }

    /**
     * @return PasswordHash
     */
    function get_hasher(){
        global $wp_hasher;
        // If the stored hash is longer than an MD5, presume the
        // new style phpass portable hash.
        if ( empty($wp_hasher) ) {
            require_once( ABSPATH . WPINC . '/class-phpass.php');
            // By default, use the portable hash from phpass
            $wp_hasher = new PasswordHash(8, true);
        }
        return $wp_hasher;
    }
    function get_passwords(){
        $pass=get_user_meta($this->user_id,'wp2sv_app_passwords',true);
        if(!is_array($pass)){
            $pass=array();
        }
        return $pass;
    }
    function get_password($index){
        $pass=$this->get_passwords();
        return isset($pass[$index])?$pass[$index]:false;
    }
    function update_passwords($passwords){
        if(!is_array($passwords)){
            $passwords=array();
        }
        update_user_meta($this->user_id,'wp2sv_app_passwords',$passwords);
    }
    function update_password($key,$pass){
        $passwords=$this->get_passwords();
        if($pass) {
            $passwords[$key] = $pass;
        }else{
            unset($passwords[$key]);
        }
        $this->update_passwords($passwords);
    }
    function clean_pass($password){
        $password=str_replace(' ','',$password);
        return $password;
    }
    function hash_key($token){
        // If ext/hash is not present, use sha1() instead.
        if ( function_exists( 'hash' ) ) {
            return hash( 'sha256', $token );
        } else {
            return sha1( $token );
        }
    }
    function collect_user_token($cookie,$expire, $expiration, $user_id){
        if(class_exists('WP_Session_Tokens')&&isset($GLOBALS['logged_in_with_wp2sv_app_password'])&&$user_id){
            $app_password_used=$GLOBALS['logged_in_with_wp2sv_app_password'];
            $this->user_id=$user_id;
            $cookie_elements = explode('|', $cookie);
            if ( count( $cookie_elements ) !== 4 ) {
                return ;
            }
            $token=$cookie_elements[2];
            if($pass=$this->get_password($app_password_used)){
                $pass['t'][$token]=true;
                $this->update_password($app_password_used,$pass);
            }
        }
    }

}