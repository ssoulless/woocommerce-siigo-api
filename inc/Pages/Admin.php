<?php
/**
 * @package WoocommerceSiigoAPI
 */
namespace Inc\Pages;

use \Inc\Base\BaseController;
use \Inc\Api\SettingsApi;
use \Inc\Api\Callbacks\AdminCallbacks;

class Admin extends BaseController
{
	public $settings;

	public $callbacks;

	public $pages = array();

	public $subpages = array();

	public function register() 
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->set_pages();

		$this->set_subpages();

		$this->set_settings();

		$this->set_sections();

		$this->set_fields();

		$this->settings->add_pages( $this->pages )->with_subpage( 'General Settings' )->add_subpages( $this->subpages )->register();
	}

	public function set_pages()
	{
		$this->pages = array(
			array(
				'page_title' => 'Siigo API', 
				'menu_title' => 'Siigo API', 
				'capability' => 'manage_options', 
				'menu_slug' => 'woocommerce_siigo_api_plugin', 
				'callback' => array( $this->callbacks, 'admin_general_settings' ), 
				'icon_url' => 'dashicons-admin-generic', 
				'position' => 9
			)
		);
	}

	public function set_subpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => 'woocommerce_siigo_api_plugin',
				'page_title' => 'Authentication', 
				'menu_title' => 'Siigo API Authentication', 
				'capability' => 'manage_options', 
				'menu_slug' => 'woocommerce_siigo_api_authentication', 
				'callback' => array( $this->callbacks, 'admin_authentication' )
			)
		);
	}

	public function set_settings()
	{
		$args = array(
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_authentication',
				'option_name' => 'nombre_clave_api',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_authentication',
				'option_name' => 'usuario_api',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_authentication',
				'option_name' => 'password_api',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_authentication',
				'option_name' => 'subscription-key_api',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )
			),
			/*array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'quote_selection_preference',
				'callback' => array( $this->callbacks, 'select_sanitize' )
			),*/
		);

		$this->settings->set_settings( $args );
	}

	public function set_sections()
	{
		$args = array(
			array(
				'id' => 'woocommerce_siigo_api_admin_index',
				'title' => __('Settings'),
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_admin_section'),
				'page' => 'woocommerce_siigo_api_plugin'
			),
			array(
				'id' => 'woocommerce_siigo_api_authentication_index',
				'title' => __('API Key Authentication'),
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_authentication_section'),
				'page' => 'woocommerce_siigo_api_authentication'
			),
			
		);

		$this->settings->set_sections( $args );
	}

	public function set_fields()
	{
		$args = array(
			array(
				'id' => 'siigo_nombre_clave_api',
				'title' => 'Nombre Clave',
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_textfield'),
				'page' => 'woocommerce_siigo_api_authentication',
				'section' => 'woocommerce_siigo_api_authentication_index',
				'args' => array(
					'label_for' => 'siigo_nombre_clave_api',
					'class' => 'siigo_nombre_clave_api'
					'placeholder' => __('Nombre clave de Siigo', 'woocommerce_siigo_api')
				)
			),
			array(
				'id' 	=> 'siigo_usuario_api',
				'title' => 'Usuario Api',
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_textfield' ),
				'page' 	=> 'woocommerce_siigo_api_authentication',
				'section'	=> 'woocommerce_siigo_api_authentication_index',
				'args'	=> array( 
					'label_for' => 'siigo_usuario_api',
					'class'		=> 'siigo_usuario_api',
					'placeholder' => __('Usuario API de Siigo', 'woocommerce_siigo_api')
				)

			),
			array(
				'id' 	=> 'siigo_password_api',
				'title' => 'Password',
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_textfield' ),
				'page' 	=> 'woocommerce_siigo_api_authentication',
				'section'	=> 'woocommerce_siigo_api_authentication_index',
				'args'	=> array( 
					'label_for' => 'siigo_password_api',
					'class'		=> 'siigo_password_api',
					'placeholder' => __('Password API de Siigo', 'woocommerce_siigo_api')
				)
			),
			array(
				'id' 	=> 'siigo_suscription_key_api',
				'title' => 'Clave de suscripción de Siigo',
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_textfield' ),
				'page' 	=> 'woocommerce_siigo_api_authentication',
				'section'	=> 'woocommerce_siigo_api_authentication_index',
				'args'	=> array( 
					'label_for' => 'siigo_suscription_key_api',
					'class'		=> 'siigo_suscription_key_api',
					'placeholder' => __('Clave de suscripción API de Siigo', 'woocommerce_siigo_api')
				)
			),
			/*array(
				'id' => 'company_name',
				'title' => __('Nombre de la empresa', 'envioclick'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_name',
					'class' => 'company_name'
					'placeholder' => __('Nombre de la empresa', 'envioclick')
				) 
			),*/
		);

		$this->settings->set_fields( $args );
	}
}