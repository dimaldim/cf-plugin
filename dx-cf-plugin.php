<?php
/**
 * Plugin Name: DX Cloudflare Plugin
 * Description: DX Cloufdlare Plugin
 * Version: 1.0.0
 * Author: DevriX
 * Author URI: https://www.devrix.com/
 * Prefix: dxcf
 */

// If this file is called directly, abort.
use DXCF\DX_Cloudflare;

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DX_CF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DX_CF_ASSETS_URL', trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' ) );
define( 'DX_CF_INC_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'inc' ) );

require_once 'vendor/autoload.php';

class DX_CF_Plugin {
	/**
	 * @var string
	 */
	private $api_email;
	/**
	 * @var string
	 */
	private $api_key;

	public function __construct() {
		$this->api_email = '';
		$this->api_key   = '';

		$this->get_settings();

		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts( $hook ) {
		if ( 'toplevel_page_dx-cf-settings' === $hook ) {
			wp_enqueue_style( 'dx-cf-admin-styles', DX_CF_ASSETS_URL . 'styles/admin.css', '', '' );
		}
	}

	public function add_admin_menu_page() {
		add_menu_page(
			__( 'Cloudflare Settings', 'dxcf' ),
			__( 'Cloudflare Settings', 'dxcf' ),
			'manage_options',
			'dx-cf-settings',
			array( $this, 'admin_menu_page_content' )
		);
	}

	public function admin_menu_page_content() {
		if ( isset( $_POST['cf_email'] ) && isset( $_POST['cf_api_key'] ) && isset( $_POST['_wpnonce'] ) ) {
			check_admin_referer( 'dxcf' );
			$cf_email      = sanitize_text_field( $_POST['cf_email'] );
			$cf_api_key    = sanitize_text_field( $_POST['cf_api_key'] );
			$dx_cf_options = array(
				'cf_email'   => $cf_email,
				'cf_api_key' => $cf_api_key,
			);

			$this->api_email = $cf_email;
			$this->api_key   = $cf_api_key;
			update_option( 'dx_cf_settings', $dx_cf_options );
			$this->get_settings();

			echo 'Settings updated!';
		}

		$api_email        = $this->api_email;
		$api_key          = $this->api_key;
		$user_data        = $this->cloudflare( $api_email, $api_key )->get_user_id();
		$status           = array();
		$status['status'] = $user_data ? 'connected' : 'disconnected';
		$status['class']  = $user_data ? 'active' : 'inactive';
		include_once DX_CF_PLUGIN_PATH . 'template-parts/admin-page.php';
	}

	/**
	 * @param string $email - API Email.
	 * @param string $key - API Key.
	 *
	 * @return DX_Cloudflare
	 */
	private function cloudflare( $email, $key ) {
		return new DX_Cloudflare( $email, $key );
	}

	/**
	 * Update plugin settings
	 */
	private function get_settings() {
		$dx_cf_settings = get_option( 'dx_cf_settings' );

		$cf_email   = isset( $dx_cf_settings['cf_email'] ) ? esc_attr( $dx_cf_settings['cf_email'] ) : '';
		$cf_api_key = isset( $dx_cf_settings['cf_api_key'] ) ? esc_attr( $dx_cf_settings['cf_api_key'] ) : '';

		$this->api_email = $cf_email;
		$this->api_key   = $cf_api_key;
	}
}

new DX_CF_Plugin();
