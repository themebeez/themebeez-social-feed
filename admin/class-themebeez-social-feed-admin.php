<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themebeez.com
 * @since      1.0.0
 *
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Themebeez_Social_Feed
 * @subpackage Themebeez_Social_Feed/admin
 * @author     themebeez <themebeez@gmail.com>
 */
class Themebeez_Social_Feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;

		$this->version = $version;

		add_action( 'admin_menu', [ $this, 'plugin_page' ] );

		add_action( 'admin_init', [ $this, 'register_settings' ] );

		if( get_option( 'themebeez_social_feed_api_setting' ) ) {

			$this->options = get_option( 'themebeez_social_feed_api_setting' );
		}

		add_action( 'themebeez_social_feed_access_token_refresh', [ $this, 'refresh_access_token' ] );

		add_filter( 'cron_schedules', [ $this, 'access_token_refresh_schedule_time' ] );

		if( ! defined( 'DISABLE_WP_CRON' ) || ( defined( 'DISABLE_WP_CRON') && false === DISABLE_WP_CRON ) ) {

            if ( ! wp_next_scheduled( 'themebeez_social_feed_access_token_refresh' ) ) {

                wp_schedule_event( time(), 'fifty_days', 'themebeez_social_feed_access_token_refresh' );
            }
        }
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Themebeez_Social_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Themebeez_Social_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/themebeez-social-feed-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Themebeez_Social_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Themebeez_Social_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/themebeez-social-feed-admin.js', array( 'jquery' ), $this->version, false );
	}


	
	/**
	 * This function defines admin menu for plugin page.
	 *
	 * @since 1.0.0
	 */
	public function plugin_page() {

		add_menu_page( 
			'Themebeez Social Feed', 
			'TB Social Feed', 
			'manage_options', 
			'themebeez-social-feed', 
			[ $this, 'render_plugin_page' ], 
			'dashicons-instagram' 
		);
	}

	/**
	 * This function loads template necessary for rendering plugin page.
	 *
	 * @since 1.0.0
	 */
	public function render_plugin_page() {

		require_once plugin_dir_path( __FILE__ ) . 'partials/themebeez-social-feed-admin-display.php';
	}


	public function authorize_url() {

		$base_url 		= 'https://api.instagram.com/oauth/authorize';

		$client_id     	= '253380139186390';
    
	    $redirect_uri  	= 'https://themebeez.com/auth/';
	    
	    $response_type 	= 'code';
	    
	    $scope         	= 'user_profile,user_media';

	    $auth_url = add_query_arg( array(
            'client_id'     => $client_id,
            'response_type' => $response_type,
            'redirect_uri'  => $redirect_uri,
            'scope'         => $scope,
            'state'         => admin_url( 'admin.php?page=themebeez-social-feed' )
        ), $base_url );

        return $auth_url;
	}

	public function register_settings() {

		register_setting(
            'themebeez_social_feed_api_setting_group', // Option group
            'themebeez_social_feed_api_setting', // Option name
            [ $this, 'sanitize_setting_fields' ] // Sanitize
        );

        add_settings_section(
            'themebeez_social_feed_api_setting_section', // ID
            __( 'Instagram Account', 'themebeez-social-feed' ), // Title
            [ $this, 'section_template' ], // Callback
            'themebeez-social-feed' // Page
        );  

        add_settings_field(
            'instagram_username', // ID
            __( 'Username', 'themebeez-social-feed' ), // Title 
            [ $this, 'username_field_template' ], // Callback
            'themebeez-social-feed', // Page
            'themebeez_social_feed_api_setting_section' // Section           
        );   

        add_settings_field(
            'instagram_userid', // ID
            __( 'User ID', 'themebeez-social-feed' ), // Title 
            [ $this, 'userid_field_template' ], // Callback
            'themebeez-social-feed', // Page
            'themebeez_social_feed_api_setting_section' // Section           
        );     

        add_settings_field(
            'instagram_access_token', 
            __( 'Access Token', 'themebeez-social-feed' ), 
            array( $this, 'access_token_field_template' ), 
            'themebeez-social-feed', 
            'themebeez_social_feed_api_setting_section'
        );     
	}

	public function section_template() {}

	public function username_field_template() {

		printf(
            '<input type="text" id="instagram-username" name="themebeez_social_feed_api_setting[instagram_username]" value="%s" />',
            isset( $this->options['instagram_username'] ) ? esc_attr( $this->options['instagram_username']) : ''
        );
	}

	public function userid_field_template() {

		printf(
            '<input type="text" id="instagram-userid" name="themebeez_social_feed_api_setting[instagram_userid]" value="%s" />',
            isset( $this->options['instagram_userid'] ) ? esc_attr( $this->options['instagram_userid']) : ''
        );
	}

	public function access_token_field_template() {
		
		printf(
            '<input type="text" id="instagram-access-token" name="themebeez_social_feed_api_setting[instagram_access_token]" value="%s" />',
            isset( $this->options['instagram_access_token'] ) ? esc_attr( $this->options['instagram_access_token']) : ''
        );
	}

	public function sanitize_setting_fields( $fields ) {

		$new_input = array();

        if( isset( $fields['instagram_username'] ) ) {

            $new_input['instagram_username'] = sanitize_text_field( $fields['instagram_username'] );
        }

        if( isset( $fields['instagram_userid'] ) ) {

            $new_input['instagram_userid'] = absint( $fields['instagram_userid'] );
        }

        if( isset( $fields['instagram_access_token'] ) ) {

            $new_input['instagram_access_token'] = sanitize_text_field( $fields['instagram_access_token'] );
        }

        return $new_input;
	}

	public function update_api_data( $access_token ) {

		$inputs = array();

		// Verify the nonce before proceeding.
		$inputs['instagram_access_token'] = sanitize_text_field( $access_token );

		$url = 'https://graph.instagram.com/me';

		$url = add_query_arg(
			array(
				'fields'       => 'id,username',
				'access_token' => $inputs['instagram_access_token'],
			),
			$url
		);

		$remote_response = wp_remote_get( $url );

		$message = '';

		$type    = '';

		if ( is_array( $remote_response ) && ! is_wp_error( $remote_response ) ) {

			$response = wp_remote_retrieve_body( $remote_response );

			$json     = json_decode( $response, true );

			if ( isset( $json['username'] ) && isset( $json['id'] ) ) {

				$inputs['instagram_username'] = sanitize_text_field( $json['username'] );

				$inputs['instagram_userid']  = sanitize_text_field( $json['id'] );

				update_option( 'themebeez_social_feed_api_setting', $inputs );
			} 
		}
	}

	public function refresh_access_token() {

		$is_access_token_valid = themebeez_social_feed_is_access_token_valid();

		if( $is_access_token_valid ) {

	        $access_token     = $this->options['instagram_access_token'];

	        $refresh_token_url = add_query_arg( array(
	            'grant_type'   => 'ig_refresh_token',
	            'access_token' => $access_token,
	        ), 'https://graph.instagram.com/refresh_access_token' );
	        
	        $response = wp_remote_get( $refresh_token_url );

	        if( is_wp_error( $response ) ) { 

	        	$settings['instagram_access_token'] = '';

	        	update_option( 'themebeez_social_feed_api_setting', $settings );

	        	return;
	        }

	        if( 200 !== wp_remote_retrieve_response_code( $response ) ) { 

	        	$settings['instagram_access_token'] = '';

	        	update_option( 'themebeez_social_feed_api_setting', $settings );

	        	return;
	        }

	        $body = json_decode( wp_remote_retrieve_body($response) );

	        $settings['instagram_access_token'] = $body->access_token;

	        update_option( 'themebeez_social_feed_api_setting', $settings );
		}
	}

	public function access_token_refresh_schedule_time( $schedules ) {

		$schedules['fifty_days'] = array(
	        'interval' => 50 * DAY_IN_SECONDS,
	        'display' => esc_html__( 'Every Fifty Days', 'themebeez-social-feed' ),
        );

        return $schedules;
	}	
}
