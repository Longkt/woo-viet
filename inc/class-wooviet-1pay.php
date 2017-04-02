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


class WooViet_1Pay extends WC_Payment_Gateway {
	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'wooviet_1pay';
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Proceed to 1Pay', 'woo-viet' );
		$this->method_title       = __( '1Pay supported by Woo Viet', 'woo-viet' );

		// @todo - check the method description
		$this->method_description =  __( '1Pay supported many ways for the payment in Vietnam - need to check!.', 'woo-viet' );
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
	function process_payment( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status('on-hold', __( 'Awaiting your payment', 'woo-viet' ));

		// Reduce stock levels
		$order->reduce_order_stock();

		// Remove cart
		$woocommerce->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}
}


