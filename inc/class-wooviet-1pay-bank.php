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
		$this->method_description =  __( '1Pay Bank supports all banks of Vietnam.', 'woo-viet' );
		$this->supports           = array(
			'products',
			'refunds'
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );

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
			'redirect' => $this->get_request_url( $order )
		);
	}

	/**
	 * Get the 1Pay request URL for an order.
	 * @param  WC_Order $order
	 * @return string
	 */
	public function get_request_url ( $order ) {

	}


}


