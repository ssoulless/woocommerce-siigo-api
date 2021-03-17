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
				'option_name' => 'api_key',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )
			),
			/*array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'quote_selection_preference',
				'callback' => array( $this->callbacks, 'select_sanitize' )
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_name',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_first_name',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_last_name',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_email',
				'callback' => array( $this->callbacks, 'email_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_phone',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_street',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_crossstring',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			),
			array(
				'option_group' => 'woocommerce_siigo_api_plugin_settings',
				'option_name' => 'company_suburb',
				'callback' => array( $this->callbacks, 'text_field_sanitize' )	
			)*/
		);

		$this->settings->set_settings( $args );
	}

	public function set_sections()
	{
		$args = array(
			/*array(
				'id' => 'woocommerce_siigo_api_admin_company_info',
				'title' => __('Dirección de Origen', 'envioclick'),
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_company_info_section'),
				'page' => 'woocommerce_siigo_api_plugin'
			),*/
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
			/*array(
				'id' => 'shipping_quote_selection_preference',
				'title' => __('¿Cómo se van a cotizar sus órdenes?', 'envioclick'),
				'callback' => array( $this->callbacks, 'envioclick_quotation_preference_select'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'shipping_quote_selection_preference',
					'class' => 'quotation_preferences'
				)
			),*/
			array(
				'id' => 'siigo_api_key',
				'title' => 'API Key',
				'callback' => array( $this->callbacks, 'woocommerce_siigo_api_textfield'),
				'page' => 'woocommerce_siigo_api_authentication',
				'section' => 'woocommerce_siigo_api_authentication_index',
				'args' => array(
					'label_for' => 'siigo_api_key',
					'class' => 'siigo_api_key'
					'placeholder' => __('Pega la API key de Siigo aquí', 'woocommerce_siigo_api')
				)
			)
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
			),
			array(
				'id' => 'company_first_name',
				'title' => __('Nombre'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_first_name',
					'class' => 'company_first_name'
					'placeholder' => __('Nombre del representante', 'envioclick')
				) 
			),
			array(
				'id' => 'company_last_name',
				'title' => __('Apellido'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_last_name',
					'class' => 'company_last_name'
					'placeholder' => __('Apellido del representante', 'envioclick')
				) 
			),
			array(
				'id' => 'company_email',
				'title' => __('Correo electrónico para las notificaciones de envioclick', 'envioclick'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_email',
					'class' => 'company_email'
					'placeholder' => __('Correo electrónico', 'envioclick')
				) 
			),
			array(
				'id' => 'company_phone',
				'title' => __('Teléfono'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_phone',
					'class' => 'company_phone'
					'placeholder' => __('Teléfono', 'envioclick')
				) 
			),
			array(
				'id' => 'company_phone',
				'title' => __('Teléfono'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_phone',
					'class' => 'company_phone'
					'placeholder' => __('Teléfono', 'envioclick')
				) 
			),
			array(
				'id' => 'company_street',
				'title' => __('Dirección', 'envioclick'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_street',
					'class' => 'company_street'
					'placeholder' => __('Dirección', 'envioclick')
				)
			),
			array(
				'id' => 'company_crossstring',
				'title' => __('Bloque/Torre Oficina/apto', 'envioclick'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_crossstring',
					'class' => 'company_crossstring'
					'placeholder' => __('Bloque/Torre Oficina/apto', 'envioclick')
				)
			),
			array(
				'id' => 'company_suburb',
				'title' => __('Barrio/Localidad', 'envioclick'),
				'callback' => array( $this->callbacks, 'envioclick_textfield'),
				'page' => 'envioclick_plugin',
				'section' => 'envioclick_admin_index',
				'args' => array(
					'label_for' => 'company_suburb',
					'class' => 'company_suburb'
					'placeholder' => __('Barrio/Localidad', 'envioclick')
				)
			)*/
		);

		$this->settings->set_fields( $args );
	}
}