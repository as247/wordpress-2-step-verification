<?php
/**
 * Created by PhpStorm.
 * User: as247
 * Date: 24-Oct-18
 * Time: 10:25 AM
 */

class Wp2sv_Upgrade
{
    protected $version_key='wp2sv_version';
    protected $versions=[];
    public function __construct()
    {
        $this->versions=[
            '2.0'=>'update20',
            '2.4'=>'update24',
			'2.5'=>'update25',
        ];
        add_action('wp2sv_upgrade',[$this,'run']);
        $this->runBackgroundUpgrade();

    }

	function update25(){
		$removeKeys=['wp2sv_email_sent_success','wp2sv_backup_failed','wp2sv_today'];
		foreach ($removeKeys as $key){
			delete_metadata( 'user', 0, $key, false, true );
		}
		delete_option('wp2sv_time_synced');
		if(!is_array(wp2sv_setting())){
			wp2sv_setting([
				'max_attempts'=>5,
				'attempts_lock'=>15,
				'max_emails'=>10,
				'emails_lock'=>30,
			]);
		}
	}
	function update24(){
		update_option( 'woocommerce_queue_flush_rewrite_rules', 'yes' );
	}
	function update20(){
		$users=get_users(['meta_key'=>'wp2sv_email','fields'=>'ids']);
		foreach ($users as $user){
			$email=get_user_meta($user,'wp2sv_email',true);
			Wp2sv_Model::forUser($user)->addEmail($email);
		}

		$removeKeys=["wp2sv_email","wp2sv_user_fav_trusted","last_selected_device","wp2sv_lastday"];

		foreach ($removeKeys as $key){
			delete_metadata( 'user', 0, $key, false, true );
		}
	}

    function runBackgroundUpgrade(){
    	if($this->needUpdate()) {
			if (!wp_next_scheduled('wp2sv_upgrade')) {
				wp_schedule_single_event(time(), 'wp2sv_upgrade');
			}
			add_action('admin_notices',[$this,'noticeUpgrade']);
		}
	}
	function noticeUpgrade(){
		$class = 'notice notice-info';
		$messages=[];
		$messages[]='<h3>';
		$messages[] = __('Wp2sv database update required','wordpress-2-step-verification');
		$messages[] ='</h3>';
		$messages[] ='<p>';
		$messages[] = __('Wp2sv has been updated! To keep things running smoothly, we have to update your database to the newest version.','wordpress-2-step-verification').' '.
			__('The database update process runs in the background and may take a little while, so please be patient.','wordpress-2-step-verification');
		$messages[]='</p>';


		$message=join("",$messages);
		printf( '<div class="%1$s">%2$s</div>', esc_attr( $class ), ( $message ) );
	}

    function run(){
        $currentVersion=$this->getVersion();
        foreach ($this->versions as $v=>$method){
            if(version_compare($currentVersion,$v,'<')){
                $this->$method();
                $this->updateVersion($v);
            }
        }
    }

    function needUpdate(){
    	$allVersions=array_keys($this->versions);
    	$latestVersion=end($allVersions);
		return version_compare($this->getVersion(),$latestVersion,'<');
	}
    function getVersion(){
        return get_site_option($this->version_key,'0.0');
    }
    function updateVersion($ver){
        update_site_option($this->version_key,$ver);
    }
}
