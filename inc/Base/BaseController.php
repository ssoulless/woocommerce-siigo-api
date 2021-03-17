<?php
/**
 * @package EnvioclickPlugin
 */
namespace Inc\Base;

class BaseController
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public $api_endpoint;

	public $status_trigger;

	public $default_store_state;

	public $default_store_city;

	public $default_store_address;

	public function __construct() 
	{
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2) );
		$this->plugin_url = plugin_dir_url( dirname(__FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname(__FILE__, 3 ) ) . '/woocommerce-envioclick.php';
		$this->api_endpoint = 'https://api.envioclickpro.com.co/api/v1';

		//This is the status that will trigger the API call to envioclick
		//TODO: Make this configurable from the admin settings
		$this->status_trigger = 'completed';


		//Default store origin
		//TODO: Make marketplace module so there's multiple origins according to product vendor
		$this->default_store_state = strtoupper( WC()->countries->get_base_state() );
		$this->default_store_city = strtoupper( WC()->countries->get_base_city() );
		$this->default_store_address = WC()->countries->get_base_address();

	}
}