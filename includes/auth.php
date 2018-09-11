<?php
!defined('MINUTE_IN_SECONDS')&&define( 'MINUTE_IN_SECONDS', 60 );
!defined('HOUR_IN_SECONDS')&&define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
!defined('DAY_IN_SECONDS')&&define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
class Wp2sv_Auth{
    var $cookie_name;
    var $cookie_name_secure;
    var $trusted_cookie_name;
    private $expiration_in_day=30;
    function __construct(){
        $this->cookie_name='wp2sv_'.COOKIEHASH;
        $this->cookie_name_secure='wp2sv_sec_'.COOKIEHASH;
        $this->trusted_cookie_name='wp2sv_'.md5(get_current_user_id());
        if ($cookie_elements = $this->parse_cookie()){
            foreach($cookie_elements as $key=>$val){
                    $this->$key=$val;
            }
        }
    }
    function is_trusted(){
        return @(bool)$_COOKIE[$this->trusted_cookie_name];
    }
    function validate_cookie(){
        if ( ! $cookie_elements = $this->parse_cookie() ) {
		  return false;
    	}
        $username = $cookie_elements['username'];
        $hmac = $cookie_elements['hmac'];
        $expired = $expiration = $cookie_elements['expiration'];
    
    	// Allow a grace period for POST and AJAX requests
    	if ( defined('DOING_AJAX') || 'POST' == $_SERVER['REQUEST_METHOD'] )
    		$expired += 3600;
    
    	// Quick check to see if an honest cookie has expired
    	if ( $expired < time() ) {
    		return false;
    	}
    
    	$user = get_user_by('login', $username);
    	if ( ! $user ) {
    		return false;
    	}

    	$pass_frag = substr($user->user_pass, 8, 4);
        $token=get_user_meta($user->ID,'wp2sv_secret_key',true);
        $key = wp_hash( $user->user_login . '|' . $pass_frag . '|' . $expiration . '|' . $token );
        // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
        $algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
        $hash = hash_hmac( $algo, $user->user_login . '|' . $expiration . '|' . $token, $key );
    	if ( $hmac != $hash ) {
    		return false;
    	}
    
    	if ( $expiration < time() ) // AJAX/POST grace period set above
    		$GLOBALS['login_grace_period'] = 1;
    
    	return $user->ID;
    }
    function generate_cookie($user_id, $expiration){
        $user = get_userdata($user_id);
    	$pass_frag = substr($user->user_pass, 8, 4);
        $token=get_user_meta($user_id,'wp2sv_secret_key',true);
        $key = wp_hash( $user->user_login . '|' . $pass_frag . '|' . $expiration . '|' . $token );
        // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
        $algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
        $hash = hash_hmac( $algo, $user->user_login . '|' . $expiration . '|' . $token, $key );
    	$cookie = $user->user_login .'|'. $expiration . '|' . $hash;
        return $cookie;
    }
    function parse_cookie($secure = ''){
        if ( '' === $secure ) {
            $secure = is_ssl();
        }
        if($secure) {
            $cookie_name = $this->cookie_name_secure;
        }else{
            $cookie_name = $this->cookie_name;
        }
        if ( empty($_COOKIE[$cookie_name]) )
			return false;
		$cookie = $_COOKIE[$cookie_name];
        $cookie_elements = explode('|', $cookie);
    	if ( count($cookie_elements) != 3 )
    		return false;
        
    	list($username ,$expiration, $hmac) = $cookie_elements;
    	return compact('username', 'expiration', 'hmac');
    }
    function set_cookie($user_id, $remember = false, $secure = ''){
        $remember=(bool)$remember;
        if ( $remember ) {
    		$expiration = time() + apply_filters('wp2sv_cookie_expiration', 30 * DAY_IN_SECONDS , $user_id, $remember);
            $expire = $expiration + ( 12 * HOUR_IN_SECONDS );
    	} else {
    		$expiration = time() + apply_filters('wp2sv_cookie_expiration', 2 * DAY_IN_SECONDS, $user_id, $remember);
    		$expire = 0;
    	}
        if ( '' === $secure ) {
            $secure = is_ssl();
        }
        $auth_cookie=$this->generate_cookie($user_id,$expiration);
        if($secure) {
            $cookie_name = $this->cookie_name_secure;
        }else{
            $cookie_name = $this->cookie_name;
        }
        setcookie($cookie_name, $auth_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure, true);
        setcookie($this->trusted_cookie_name, $remember, time()+365 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, false, true);
	    if ( COOKIEPATH != SITECOOKIEPATH ){
	       	setcookie($cookie_name, $auth_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure, true);
            setcookie($this->trusted_cookie_name, $remember, time()+365 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, false, true);
        }
    }
    function clear_cookie(){
        setcookie($this->cookie_name,' ',time() - 31536000,COOKIEPATH,COOKIE_DOMAIN);
        setcookie($this->cookie_name,' ',time() - 31536000,SITECOOKIEPATH,COOKIE_DOMAIN);
        setcookie($this->cookie_name_secure,' ',time() - 31536000,COOKIEPATH,COOKIE_DOMAIN);
        setcookie($this->cookie_name_secure,' ',time() - 31536000,SITECOOKIEPATH,COOKIE_DOMAIN);
    }
}