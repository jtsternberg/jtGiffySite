<?php
/*
Plugin Name: Giffy one-page app
Plugin URI: http://jtsternberg.com/?gifs
Description: Search for gifs in a collection
Author URI: http://jtsternberg.com
Author: Jtsternberg
Donate link: http://dsgnwrks.pro/give/
Version: 0.1.1
*/

class jtGiffySite {
	public static $this_plugin = null;
	public static $plugin_url  = null;
	public static $plugin_dir  = null;
	const PLUGIN  = 'jtGiffySite';
	const VERSION = '0.1.1';

	public function __construct() {
		$this->request = $_REQUEST;
		$this->is_json_url = 0 === strpos( $_SERVER['REQUEST_URI'], '/jtgiffy/json' );

		if ( isset( $this->request['gifs'], $this->request['json'] ) || isset( $_SERVER['REQUEST_URI'] ) && $this->is_json_url ) {
			$this->request['gifs'] = isset( $this->request['gifs'] ) ? $this->request['gifs'] : '';
			$this->request['json'] = 1;

			if ( ! empty( $this->request['text'] ) ) {
				$this->request['gifs'] = '';

			} elseif ( ! empty( $this->request['gifs'] ) ) {
				$this->request['text'] = $this->request['gifs'];
				$this->request['gifs'] = '';
			}
		}


		self::$this_plugin = plugin_basename( __FILE__ );
		self::$plugin_url  = trailingslashit( plugins_url( '' , __FILE__ )  );;
		self::$plugin_dir  = plugin_dir_path( __FILE__ );

		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		// is main plugin active? If not, throw a notice and deactivate
		if ( ! in_array( 'jtGiffy/jtGiffy.php', $plugins ) && ! in_array( 'jt-giffy/jtGiffy.php', $plugins ) ) {
			add_action( 'all_admin_notices', array( $this, 'jtGiffy_required' ) );
			return;
		}
	}

	public function hooks() {
		global $jtGiffy;

		if ( isset( $this->request['gifs'] ) ) {
			$_REQUEST = $this->request;
			remove_action( 'template_redirect', array( $jtGiffy, 'get_gifs' ), 9999 );
			add_action( 'template_redirect', array( $this, 'thegifs' ), 9999 );
		}
	}

	public function thegifs() {
		require_once( dirname( __FILE__ ) . '/template.php' );
	}

	public function jtGiffy_required() {
		echo '<div id="message" class="error"><p>'. sprintf( __( '%1$s requires the jtGiffy plugin to be installed/activated. %1$s has been deactivated.' ), self::PLUGIN ) .'</p></div>';
		deactivate_plugins( self::$this_plugin, true );
	}

}

$jtGiffySite = new jtGiffySite();
$jtGiffySite->hooks();
