<?php
/**
 * @package WoocommerceSiigoApi
 */
/*
Plugin Name: Woocommerce Siigo API
Description: Integration of woocommerce with Siigo API.
Version: 0.1.0
Contributors: ssoulless
Author: Sebastian Velandia Giraldo
Author URI: https://github.com/ssoulless
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: woocommerce-siigo-api
Domain Path:  /languages
*/


// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Requiere once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_woocommerce_siigo_api_plugin() {
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_woocommerce_siigo_api_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_woocommerce_siigo_api_plugin() {
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_siigo_api_plugin' );


// Initializes all the Core classes of the plugin
if ( class_exists( 'Inc\\Init') ) {
	Inc\Init::register_services();
}