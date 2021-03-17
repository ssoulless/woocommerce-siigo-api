<?php
/**
 * @package EnvioclickPlugin
 */
namespace Inc\Api;

use Inc\Base\BaseController;
use Inc\Api\DaneCodes;

class RestClientApi extends BaseController
{
	public function register()
	{
		add_action('woocommerce_order_status_changed', array($this, 'build_and_send_request' ) );
	}

	public function build_and_send_request( $order_id, $old_status, $new_status )
	{

		if( $new_status == $this->status_trigger ) {

            $$origin_dane_code = DaneCodes::search_dane_codes($this->default_store_state, $this->default_store_city);
            $order = wc_get_order( $order_id );
            $order_data = $order->get_data();
            $order_shipping_state = strtoupper( $order_data['shipping']['state'] );
            $order_shipping_city = strtoupper( $order_data['shipping']['city'] );
            $order_shipping_address = $order_data['shipping']['address_1'];
            $destination_dane_code = DaneCodes::search_dane_codes( $order_shipping_state, $order_shipping_city );

            $origin = array(
                'company' => get_option( 'company_name' ),
                'firstName' => get_option( 'company_first_name' ),
                'lastName' => get_option( 'company_last_name' ),
                'email' => get_option( 'company_email' ),
                'phone' => get_option( 'company_phone' ),
                'street' => get_option( 'company_street' ),
                'number' => '--',
                'suburb' => get_option( 'company_suburb' ),
                'crossStreet' => get_option( 'company_crossstring' ),
                'reference' => 'NA',
                'originCode' => $origin_dane_code
            );

            $destination = array(
                'company' => 'NA',
                'firstName' => $order_data['shipping']['first_name'],
                'lastName' => ( ! empty( $order_data['shipping']['last_name'] ) ? $order_data['shipping']['last_name'] : '--'),
                'email' => $order_data['billing']['email'],
                'phone' => $order_data['billing']['phone'],
                'street' => $order_data['shipping']['address_1'],
                'number' => '--',
                'suburb' => 'NA',
                'crossStreet' => 'NA',
                'reference' => ( ! empty( $order_data['shipping']['address_2'] ) ? $order_data['shipping']['address_2'] : 'NA' ),
                'originCode' => $destination_dane_code
            );

        	foreach( $order->get_items() as $item_key => $item ) {

        		$product = $item->get_product();

        		if( empty( $product->get_length() ) || empty( $product->get_height() ) || empty( $product->get_width() ) || ! $product->has_weight() || empty( get_option( 'company_name' ) ) || empty( get_option( 'company_first_name' ) ) || empty( get_option( 'company_last_name' ) ) || empty( get_option( 'company_email' ) ) || empty( get_option( 'company_phone' ) ) || empty( get_option( 'company_street' ) ) || empty( get_option( 'company_suburb' ) ) || empty( get_option( 'company_crossstring' ) ) ) {
        			//TODO: Grab a user warning message.
        			continue;
        		}

                $shipment_reference = $order_id . '-' . $product->get_sku();

        		$package = array(
        			'description' => $item->get_name(),
        			'contentValue' => $item->get_total(),
					'weight': $product->get_weight(),
					'length': $product->get_length(),
					'height': $product->get_height(),
					'width': $product->get_width()
        		);

                $pickup_date = date('Y-m-d');

                $quotation_response = $this->send_quotation_request( $package, $origin_dane_code, $this->default_store_address, $destination_dane_code, $order_shipping_address);

                if ( ! is_array( $quotation_response ) || empty( $quotation_response ) ) {
                    //TODO: Throw exception, error to the user.
                    return;
                }

                $selected_rate = $this->select_quotation( $quotation_response['data']['rates'], get_option( 'quote_selection_preference' ) );

                $shipment_response = $this->send_shipment_request( $selected_rate['idRate'], $shipment_reference, $pickup_date, $package, $origin, $destination );

                if ($shipment_response['status'] != 'OK') {
                    //TODO: Error message
                }else{
                    //TODO: Print success message
                }

        	}
    	}
	}

    protected function select_quotation( $rates, $criteria )
    {
        $winner_rate = array();

        switch ($criteria) {
            case 'fastest':
                foreach ($rates as $rate) {
                    if ( empty( $winner_rate ) ){
                        $winner_rate = $rate;
                    }elseif ( $rate['deliveryDays'] < $winner_rate['deliveryDays'] ) {
                        $winner_rate = $rate;
                    }elseif ( $rate['deliveryDays'] == $winner_rate['deliveryDays'] ) {
                        if ( $rate['total'] < $winner_rate['total'] ) {
                            $winner_rate = $rate;
                        }
                    }
                }
                break;
            case 'cheapest':
                foreach ($rates as $rate) {
                    if ( empty( $winner_rate ) ){
                        $winner_rate = $rate;
                    }elseif ( $rate['total'] < $winner_rate['total'] ) {
                        $winner_rate = $rate;
                    }elseif ( $rate['total'] == $winner_rate['total'] ) {
                        if ( $rate['deliveryDays'] < $winner_rate['deliveryDays'] ) {
                            $winner_rate = $rate;
                        }
                    }
                }
                break;
            default:
                //TODO: Send an exception, error message to the user.
                break;
        }

        return $winner_rate;

    }

    protected function send_quotation_request( $package, $origin_code, $origin_address, $destination_code, $destination_address )
    {
        $body = array(
            'package' => $package,
            'originCode' => $origin_code,
            'originAddresse' => $origin_address,
            'destinationCode' => $destination_code,
            'destinationAddress' => $destination_address
        );
         
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'AuthorizationKey' => get_option( 'api_key' )
            ),
            'body' => $body,
            'timeout' => 60,
            'blocking' => false
        );
        $url = "$this->api_endpoint/quotation";

        $results = wp_remote_retrieve_body( wp_remote_post( $url, $args ) );

        return json_decode( $results );
    }

    protected function send_shipment_request( $id_rate, $shipment_reference, $pickup_date, array $package, array $origin, array $destination, array $args )
    {

        $defaults = array(
            'request_pickup' => true,
            'insurance' => true,
            'thermal_label' => false
        );

        $options = array_merge( $defaults, $args );

        $body = array(
            'idate' => $id_rate,
            'myShipmentReference' => $shipment_reference,
            'requestPickup' => $options['request_pickup'],
            'pickupDate' => $pickup_date,
            'insurance' => $options['insurance'],
            'thermalLabel' => $options['thermal_label'],
            'package' => $package,
            'origin' => $origin,
            'destination' => $destination
        );
         
        $request_args = array(
            'method' => 'POST',
            'headers' => array(
                'AuthorizationKey' => get_option( 'api_key' )
            ),
            'body' => $body,
            'timeout' => 60,
            'blocking' => false
        );

        $url = "$this->api_endpoint/shipment/request";

        $results = wp_remote_retrieve_body( wp_remote_post( $url, $request_args ) );

        return json_decode( $results );
    }
}