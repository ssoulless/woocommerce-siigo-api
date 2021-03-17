<?php
/**
 * @package WoocommerceSiigoApi
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{

	public function register() {
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue') );
	}

	function enqueue(){
		// enqueue all the scripts
		wp_enqueue_style( 'adminWoocommerceSiigoApiStyles', $this->plugin_url . 'assets/admin.css' );
	}
}
