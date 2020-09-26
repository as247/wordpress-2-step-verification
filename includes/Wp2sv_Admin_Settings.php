<?php


class Wp2sv_Admin_Settings
{
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'page' ) );
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	/**
	 * Add options page
	 */
	public function page()
	{
		// This page will be under "Settings"
		add_options_page(
			__('Wordpress 2-step verification settings','wordpress-2-step-verification'),
			__('Wp2sv Settings','wordpress-2-step-verification'),
			'delete_users',
			'wp2sv-settings',
			array( $this, 'render' )
		);
	}

	/**
	 * Options page callback
	 */
	public function render()
	{
		// Set class property
		$this->options = wp2sv_setting();
		?>
		<div class="wrap">
			<h1><?php _e('Wp2sv Settings','wordpress-2-step-verification')?></h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'wp2sv_settings' );
				do_settings_sections( 'wp2sv_settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function init()
	{
		register_setting(
			'wp2sv_settings', // Option group
			'wp2sv_settings', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'wp2sv_attempts_limit', // ID
			__('Attempts Limit','wordpress-2-step-verification'), // Title
			function(){

            }, // Callback
			'wp2sv_settings' // Page
		);

		add_settings_field(
			'max_attempts', // ID
			__('Max Attempts','wordpress-2-step-verification'), // Title
			function (){
				$this->textField('max_attempts',__('Maximum failed attempts allowed before lockout','wordpress-2-step-verification'));
            }, // Callback
			'wp2sv_settings', // Page
			'wp2sv_attempts_limit' // Section
		);

		add_settings_field(
			'attempts_lock', // ID
			__('Lockout Time','wordpress-2-step-verification'), // Title
			function (){
				$this->textField('attempts_lock',__('minutes','wordpress-2-step-verification'));
			}, // Callback
			'wp2sv_settings', // Page
			'wp2sv_attempts_limit' // Section
		);

		add_settings_field(
			'max_emails', // ID
			__('Max Emails','wordpress-2-step-verification'), // Title
			function (){
				$this->textField('max_emails',__('Max number of emails user may request before lockout','wordpress-2-step-verification'));
			}, // Callback
			'wp2sv_settings', // Page
			'wp2sv_attempts_limit' // Section
		);
		add_settings_field(
			'emails_lock', // ID
			__('Email Lockout Time','wordpress-2-step-verification'), // Title
			function (){
				$this->textField('emails_lock',__('minutes','wordpress-2-step-verification'));
			}, // Callback
			'wp2sv_settings', // Page
			'wp2sv_attempts_limit' // Section
		);

		if(class_exists('\WooCommerce')){
			add_settings_section(
				'wp2sv_woo_integration', // ID
				__('WooCommerce Integration','wordpress-2-step-verification'), // Title
				function(){
					echo '';
				}, // Callback
				'wp2sv_settings' // Page
			);
			add_settings_field(
				'wp2sv_show_in_woo', // ID
				__('Show in WooCommerce','wordpress-2-step-verification'), // Title
				array( $this, 'showInMyAccount' ), // Callback
				'wp2sv_settings', // Page
				'wp2sv_woo_integration' // Section
			);
		}
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['show_in_woocommerce'] ) )
			$new_input['show_in_woocommerce'] = $input['show_in_woocommerce'] ? '1':'';
		else{
		    $new_input['show_in_woocommerce']='';
		}

		$numericFields=[
		    'max_attempts',
            'attempts_lock',
            'max_emails',
            'emails_lock',
        ];
		foreach ($numericFields as $field){
            $new_input[$field]=isset($input[$field])?absint($input[$field]):'';
		}

		return $new_input;
	}
    function textField($name,$desc=''){
        $value=isset($this->options[$name])?$this->options[$name]:'';
        printf('<input type="text" name="wp2sv_settings[%1$s]" value="%2$s"> %3$s',$name,$value,$desc);
	}
    function showInMyAccount(){
	    $checked=!empty($this->options['show_in_woocommerce']);
	    printf('<input type="hidden" name="wp2sv_settings[show_in_woocommerce]" value=""><label><input type="checkbox" name="wp2sv_settings[show_in_woocommerce]" value="1" %1$s>%2$s</label>',checked($checked,true,false),__('Show Wp2sv setup in WooCommerce My Account page','wordpress-2-step-verification'));
	}
}
