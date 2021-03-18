<?php
/**
 * @package WoocommerceSiigoApi
 */
namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
	public function admin_general_settings()
	{
		return require_once( "$this->plugin_path/templates/admin.php" );
	}

	public function admin_authentication()
	{
		return require_once( "$this->plugin_path/templates/authentication.php" );	
	}

	public function select_sanitize( $input )
	{
		$errors = null;
		$value = sanitize_text_field( $input );

		$valid_values = array(
			'cheapest',
			'fastest',
		);

		if( ! in_array( $value, $valid_values ) ) {
			add_settings_error('quote_selection_preference', 'available_options_only', __('Only select options are available', 'woocommerce_siigo_api'), 'error');
			return get_option( 'quote_selection_preference' );
		}

		return $value;
	}

	public function text_field_sanitize( $input )
	{
		$value = sanitize_text_field($input);

		return $value;
	}

	public function email_field_sanitize( $input )
	{
		$value = sanitize_email( $input );

		return $value;
	}

	/*public function woocommerce_siigo_api_admin_section()
	{
		echo __('Selecciona cómo quieres que se seleccione la cotización para el envío de tus pedidos', 'woocommerce_siigo_api');
	}*/

	public function woocommerce_siigo_api_authentication_section()
	{
		echo __('Ingresa los datos de autenticación API de Siigo', 'woocommerce_siigo_api');
	}

	/*public function woocommerce_siigo_api_company_info_section()
	{
		echo __('Especifica la dirección de tu empresa, desde donde se van a recoger los envíos por parte de envioclick', 'envioclick');
	}*/

	public function woocommerce_siigo_api_textfield( array $args)
	{
		$field_name = $args['label_for'];
		$placeholder = $args['placeholder'];

		$value = esc_attr( get_option( 'api_key' ) );
		
		echo "<input type='text' class='regular-text' name='$field_name' value='$value' placeholder='$placeholder' >";		
	}
}