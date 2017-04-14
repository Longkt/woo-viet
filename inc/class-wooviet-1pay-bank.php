<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The class to handle the payment gateway 1Pay https://1pay.vn/home/
 *
 *
 * @author   htdat
 * @since    1.3
 *
 */


class WooViet_1Pay_Bank extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'wooviet_1pay_bank';
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Proceed to 1Pay', 'woo-viet' );
		$this->method_title       = __( '1Pay Bank (by Woo Viet)', 'woo-viet' );

		// @todo - check the method description
		$this->method_description =  __( '1Pay Bank supports all local banks in Vietnam.', 'woo-viet' );
		$this->supports           = array(
			'products',
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );
		$this->access_key = $this->get_option( 'access_key' );
		$this->secret = $this->get_option( 'secret' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

	}


	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include( 'wooviet-1pay/settings.php' );
	}

	/**
	 * Process the payment.
	 */
	public function process_payment( $order_id ) {

		$order          = wc_get_order( $order_id );

		return array(
			'result'   => 'success',
			'redirect' => $this->get_pay_url( $order )
		);
	}

	/**
	 * Get the 1Pay pay_url for an order.
	 * @param  WC_Order $order
	 * @return string
	 */
	public function get_pay_url ( $order ) {


		$access_key = $this->access_key;
		$secret = $this->secret;               // require your secret key from 1pay
		$return_url = $this->get_1pay_return_url();

		$command = 'request_transaction';
		$amount = $order->get_total();   // >10000
		$order_id = $order->id;
		$order_info = sprintf( 'The payment for the order number %1$s on the site: %2$s', $order->id, get_home_url());

		$data = "access_key=".$access_key."&amount=".$amount."&command=".$command."&order_id=".$order_id."&order_info=".$order_info."&return_url=".$return_url;
		$signature = hash_hmac("sha256", $data, $secret);
		$data.= "&signature=".$signature;

		// @todo: make the POST request to get pay_url
		/*
		$json_bankCharging = execPostRequest('http://api.1pay.vn/bank-charging/service', $data);
		//Ex: {"pay_url":"http://api.1pay.vn/bank-charging/sml/nd/order?token=LuNIFOeClp9d8SI7XWNG7O%2BvM8GsLAO%2BAHWJVsaF0%3D", "status":"init", "trans_ref":"16aa72d82f1940144b533e788a6bcb6"}
		$decode_bankCharging=json_decode($json_bankCharging,true);  // decode json
		$pay_url = $decode_bankCharging["pay_url"];
		header("Location: $pay_url");
		*/
	}

	public function get_1pay_return_url(){
		return WC()->api_request_url( __CLASS__ );
	}

}


