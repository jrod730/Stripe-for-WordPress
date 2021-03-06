<?php
/**
 * Plugin Name: Stripe for WordPress
 * Description: A way to manage your Stripe subscriptions, customers, and products
 * Author: Roy Sivan
 * Author URI: http://www.roysivan.com
 * Version: 1.2.0
 * Plugin URI: https://github.com/royboy789/Stripe-for-WordPress
 * License: GPL3+
 * Text Domain: wp-stripe
 */

define( 'WP_STRIPE_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_STRIPE_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_STRIPE_VERSION', '1.2.0' );
define( 'WP_STRIPE_FILE', __FILE__ );

require_once 'inc/wp-stripe-menu.php';
require_once 'inc/wp-stripe-scripts.php';
require_once 'inc/wp-stripe-routes.php';
require_once 'inc/wp-stripe-shortcode-inserter.php';
require_once 'inc/wp-stripe-shortcodes.php';

// EDD
include_once 'edd/licensing.php';

class wp_stripe {

	private $shortcodes;
	private $scripts;

	function __construct(){
		$this->shortcodes = new wp_stripe_shortcodes();
		$this->scripts = new wp_stripe_scripts();
	}
	function admin_page() {
		$wp_stripe_menu = new wp_stripe_menu();
		$wp_stripe_menu->register_menu();
	}
	function stripe_admin_scripts() {
		$this->scripts->admin_scripts();
		$this->scripts->admin_styles();
	}
	function stripe_fed_scripts() {
		$this->scripts->fed_scripts();
	}
	function stripe_routes() {
		$wp_stripe_routes = new wp_stripe_routes();
		$wp_stripe_routes->register_routes();
	}
	function stripe_shortcode_inserter() {
		$wp_stripe_shortcode_inerster = new wp_stripe_wp_shortcode_inserter();
		$wp_stripe_shortcode_inerster->media_button();
	}
	function stripe_new_customer_shortcode( $atts ) {
		return $this->shortcodes->stripe_wp_customer( $atts );
	}

}

$WP_STRIPE = new wp_stripe();

/*
 * Stripe for WP Admin Menu
 */
	add_action( 'admin_menu', array( $WP_STRIPE, 'admin_page' ) );

/*
 * Stripe for WP Scripts and Styles
 */
	add_action( 'admin_enqueue_scripts', array( $WP_STRIPE, 'stripe_admin_scripts' ) );
	add_action( 'wp_enqueue_scripts', array( $WP_STRIPE, 'stripe_fed_scripts' ) );

/*
 * Stripe for WP API Routes
 */
	add_action( 'rest_api_init', array( $WP_STRIPE, 'stripe_routes' ) );

/*
 * Stripe Shortcode Inserter
 */
	add_action( 'media_buttons', array( $WP_STRIPE, 'stripe_shortcode_inserter' ) );

/*
 * Register Shortcode
 */
	add_shortcode( 'stripe-wp-customer', array( $WP_STRIPE, 'stripe_new_customer_shortcode' ) );

?>