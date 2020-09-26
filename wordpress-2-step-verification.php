<?php
/**
 * Plugin Name: Wordpress 2-Step Verification
 * Plugin URI: http://as247.vui30.com/blog/wordpress-2-step-verification/
 * Description: Wordpress 2-Step Verification adds an extra layer of security to your Wordpress Account. In addition to your username and password, you'll enter a code that generated by Android/iPhone/Blackberry app or Plugin will send you via email upon signing in.
 * Author: as247
 * Version: 2.5.1
 * Author URI: http://as247.vui360.com/
 * Compatibility: WordPress 4.0
 * Text Domain: wordpress-2-step-verification
 * Domain Path: /languages
 * License: GPLv2 or later
 * Requires PHP: 5.6.0
 * Network: True
 */
if (!defined('PHP_INT_MAX')) {
    define('PHP_INT_MAX', 2147483647);
}
if (!defined('PHP_INT_MIN')) {
    define('PHP_INT_MIN', ~PHP_INT_MAX);
}
define('WP2SV_ABSPATH', dirname(__FILE__) . '/');
define('WP2SV_INC', WP2SV_ABSPATH . 'includes');
define('WP2SV_TEMPLATE', WP2SV_ABSPATH . 'template');
define('WP2SV_ASSETS_VERSION','2.4');
if(!defined('WP2SV_STRICT_MODE')){
    define('WP2SV_STRICT_MODE',true);
}
require_once(WP2SV_INC . '/helpers.php');

class Wordpress2StepVerification
{
    protected $setup = false;
    /**
     * @var WP_User
     */
    protected $user;
    protected static $instance;
    private $modules = array(
        'otp' => Wp2sv_OTP::class,
        'auth' => Wp2sv_Auth::class,
        'recovery' => Wp2sv_Recovery::class,
        'app_pass' => Wp2sv_AppPass::class,
        'handler' => Wp2sv_Handler::class,
        'admin' => Wp2sv_Admin::class,
        'setup' => Wp2sv_Setup::class,
        'backup_code' => Wp2sv_Backup_Code::class,
        'email' => Wp2sv_Email::class,
    );
    private $instances = [];
    protected $ready=false;

    function __construct(){
        try {
            spl_autoload_register([$this, 'autoload']);
        }catch (Exception $e){

        }
        $this->registerModules();
        load_plugin_textdomain('wordpress-2-step-verification', FALSE, basename(dirname(__FILE__)) . '/languages/');
        add_action('setup_theme', [$this, 'initialize'], PHP_INT_MAX);
        add_action('admin_enqueue_scripts', [$this, 'registerScripts'], 9);
		add_action('wp_enqueue_scripts', [$this, 'registerScripts'], 9);
        register_activation_hook(__FILE__,[$this,'activation']);
    }
    function activation(){
        update_option( 'woocommerce_queue_flush_rewrite_rules', 'yes' );
    }
    function registerModules(){
        $this->bind('app_pass', function () {
            return new Wp2sv_AppPass($this->user()->ID);
        });
    }
    function initialize(){
		new Wp2sv_Upgrade();
        add_action('wp2sv_handled',[$this,'verifiedUser'],1);
        add_action('after_setup_theme',[$this,'setup'],PHP_INT_MAX);
        add_action('wp2sv_setup',[$this,'createHandler']);
		do_action('wp2sv_init',$this);//Allow external hook
    }

    /**
     * Action after user verified (pass step 2)
     */
    function verifiedUser(){
        $this->make(Wp2sv_Woo::class);
		$this->make('setup');//Setup page
        if (is_admin()) {
            $this->getAdmin();
        }
    }
    function createHandler(){
        //Create handler so it can protect xml rpc
		$this->make('handler');

        if(!apply_filters('wp2sv_custom_handle',false)) {
            add_action('wp2sv_handle', function () {
                $this->getHandler()->run();
            });
        }
    }
    function setup(){
        do_action('wp2sv_setup',$this);
        if(wp2sv_is_strict_mode()){
            global $current_user;
            $current_user=null;//null current user then it will do set_current_user
            $this->runOnSetCurrentUser();
            $this->runOnInit();
        }else {
            $this->runOnInit();
        }
    }
    protected function runOnInit(){
        add_action('init', [$this, 'run'], PHP_INT_MIN);
        add_action('init', [$this, 'run'], 0);//in case negative priority isn't supported
        add_action('init', [$this, 'run'], 1);//in case 0 priority isn't supported too
    }
    protected function runOnSetCurrentUser(){
        add_action('set_current_user', [$this, 'run'], PHP_INT_MIN);
        add_action('set_current_user', [$this, 'run'], 0);
        add_action('set_current_user', [$this, 'run'], 1);
    }


    function run(){
        if(!did_action('wp2sv_handle')) {
            $this->handle();
        }
    }

    protected function handle(){
        $user = $this->get_current_user();
        if ($user instanceof WP_User) {
            if ($user->ID) {
                $this->user = $user;
                $this->instance('model', new Wp2sv_Model($this->user));
                do_action('wp2sv_handle',$this);
                do_action('wp2sv_handled',$this);

            }
        }
    }


    function registerScripts()
    {
        wp_register_script('vue', wp2sv_public('vendor/vue/vue.js'), array(), '2.5.1', true);
        wp_register_script('vue-resource', wp2sv_public('vendor/vue-resource/vue-resource.min.js'), ['vue'], '1.3.4', true);
        wp_register_script('wp2sv', wp2sv_assets('/js/wp2sv.js'), array('backbone', 'vue'), WP2SV_ASSETS_VERSION, true);
        wp_register_style('wp2sv-base', wp2sv_assets('/css/base.css'),array(),WP2SV_ASSETS_VERSION);
        wp_register_style('wp2sv-woo', wp2sv_assets('/css/woo.css'),array(),WP2SV_ASSETS_VERSION);
        wp_register_script( 'wp2sv-setup',wp2sv_assets('/js/setup.js'),array('wp2sv'),WP2SV_ASSETS_VERSION,true );
        wp_register_style( 'wp2sv-setup',wp2sv_assets('/css/setup.css'),['wp2sv-base','dashicons'],WP2SV_ASSETS_VERSION );
        if(function_exists('wp_set_script_translations')) {
			wp_set_script_translations('wp2sv-setup', 'wordpress-2-step-verification');
		}else{
        	new Wp2sv_Compatibility();
		}

        $wp2sv = [
        	'ajaxurl'=>admin_url( 'admin-ajax.php' ),
            'l10n' => [
                'ajax_fail' => __('Network error, please try again', 'wordpress-2-step-verification'),
            ],
            'url' => [
            	'site'=>site_url(),
                'root' => wp2sv_url(''),
                'public' => wp2sv_public(),
                'assets' => wp2sv_assets(''),
            ],
            '_nonce' => wp_create_nonce('wp2sv'),
        ];
        wp_localize_script('wp2sv', 'wp2sv', $wp2sv);

    }


    public static function getInstance(){
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Register an instance
     * @param $name
     * @param $value
     * @return $this
     */
    function instance($name, $value){
        $this->instances[$name] = $value;
        return $this;
    }

    protected function get_current_user(){
        global $current_user;
        return $current_user;
    }

    function user(){
        return $this->user;
    }

    /**
     * @return Wp2sv_Model
     */
    function model(){
        return $this->make('model');
    }

    /**
     * @return Wp2sv_Setup
     */
    function getSetup(){
        return $this->make('setup');
    }

    /**
     * @return Wp2sv_Admin
     */
    function getAdmin(){
        return $this->make('admin');
    }

    /**
     * @return Wp2sv_Handler
     */
    function getHandler(){
        return $this->make('handler');
    }

    /**
     * @return Wp2sv_OTP
     */
    function getOtp(){
        return $this->make('otp');
    }

    /**
     * @return Wp2sv_Auth
     */
    function getAuth(){
        return $this->make('auth');
    }

    /**
     * @return Wp2sv_Recovery
     */
    function getRecovery(){
        return $this->make('recovery');
    }

    /**
     * @return Wp2sv_AppPass
     */
    function getAppPassword(){
        return $this->make('app_pass');
    }

    function get($name){
        return $this->make($name);
    }

    function make($name){
        $class = $name;
        if (isset($this->modules[$name])) {
            $class = $this->modules[$name];
            if(is_string($class)){
            	$name=$class;//Use class for name
			}
        }
        if (!isset($this->instances[$name])) {
            if ($class instanceof Closure) {
                $this->instances[$name] = $class($this);
            } else {
                $this->instances[$name] = new $class($this);
            }
        }
        return $this->instances[$name];
    }

    function bind($name, $factory){
        $this->modules[$name] = $factory;
        return $this;
    }
    function bound($name){
    	return isset($this->modules[$name])||isset($this->instances[$name]);
	}

    function plugin_url($path = '', $echo = true){
        $url = plugins_url($path, __FILE__);
        if ($echo) {
            echo $url;
            return '';
        } else {
            return $url;
        }
    }

    function url($args = [],$backendUrl=false){
    	if(is_admin() || $backendUrl) {
			$url = menu_page_url('wp2sv', false);
		}else {
			$url = get_permalink(apply_filters('wp2sv_setup_page_id', get_option('wp2sv_setup_page_id')));
		}
		if ($args) {
			$url = add_query_arg($args, $url);
		}
		return $url;
    }

    /**
     * @param $class
     */
    public static function autoload($class){
        if (false === strpos($class, 'Wp2sv_')) {
            return;
        }
        $files = [
            WP2SV_INC . '/' . $class . '.php',
            WP2SV_INC . '/' . strtolower($class) . '.php'
        ];
        foreach ($files as $file) {
            if (file_exists($file)) {
                /** @noinspection PhpIncludeInspection */
                include_once $file;
                return;
            }
        }
    }
}

class Wp2sv extends Wordpress2StepVerification
{
}

Wp2sv::getInstance();
