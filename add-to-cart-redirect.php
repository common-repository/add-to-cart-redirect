<?php
/**
 * Plugin Name: WooCommerce Add to Cart Redirect - To Any Page
 * Plugin URI:  https://wpxon.com/add-to-cart-redirect
 * Description: After Add to Cart a Product it enables you to redirect to any specific page you want.
 * Author:      WPxon
 * Author URI:  https://wpxon.com 
 * Version:     1.0.2
 * Tags: add to cart redirect, redirect to checkout, redirect to page, redirect to shop, redirect to product
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: add-to-cart-redirect 
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Atcr {

	/**
	 * plugin version
	 * @var string
	 */
	const version = '1.0.2';
	
	/**
	 * class constructor
	 */
	function __construct(){
		$this->define_constants();		

		register_activation_hook( __FILE__ , [ $this, 'activate' ] ); 

		add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), [ $this, 'settings_action'] );
		add_action('admin_init', [ $this, 'plugin_redirect']);
	}

    /**
     * Initializes a singletone instance 
     * @return \Atcr
     */
	public static function init () {
		static $instance = false;

		if( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the required plugin constants
	 * @return void
	 */
	public function define_constants() {
		define( 'WCR_VERSION', self::version );    
		define( 'WCR_FILE', __FILE__ );    
		define( 'WCR_PATH', __DIR__ );    
		define( 'WCR_URL', plugins_url( '', WCR_FILE ) );    
		define( 'WCR_ASSETS', WCR_URL . '/assets' );    
	}


	public function init_plugin() {
  
		new Atcr\Redirect();
		 
		if( is_admin() ){
			new Atcr\Admin();	
		}
	}

	/**
	 * Do stuff uplon plugin activation
	 * @return void
	 */
	public function activate() {
		// save version
		$installed = get_option( 'wcr_installed' );
		if( !$installed ){
			update_option( 'wcr_installed', time() );
		}
		update_option( 'wcr_version', WCR_VERSION );
  		add_option('do_activation_redirect', true);
	}

	/**
	 * add settings page link to plugin action row
	 * @return void
	 */
	public function settings_action($links) { 
		$settings_link = '<a href="admin.php?page=wc-settings&tab=cart_redirect_to">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}

	/**
	 * redirect to settings page after activation
	 * @return void
	 */
	public function plugin_redirect() {
	  if (get_option('do_activation_redirect', false)) {
	    delete_option('do_activation_redirect');
	    if(!isset($_GET['activate-multi']))
	    {
	      wp_redirect( admin_url( '/admin.php?page=wc-settings&tab=cart_redirect_to' ) );
	     // exit;
	    }
	  }
	}
}

/**
 * Initializes the main plugin
 * @return \Atcr
 */
function woo_cart_redirect() {
	return Atcr::init();
}


// kick-off the plugin
woo_cart_redirect();
 