<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// @todo - ver 1.3 - check all texts
/**
 * Settings for 1Pay Gateway.
 */
return array(
	'enabled' => array(
		'title'   => __( 'Enable/Disable', 'woo-viet' ),
		'type'    => 'checkbox',
		'label'   => __( '1Pay supported by Woo Viet', 'woo-viet' ),
		'default' => 'yes'
	),
	'title' => array(
		'title'       => __( 'Title', 'woo-viet' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woo-viet' ),
		'default'     => __( '1Pay supported by Woo Viet', 'woo-viet' ),
		'desc_tip'    => true,
	),
	'description' => array(
		'title'       => __( 'Description', 'woo-viet' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woo-viet' ),
		'default'     => __( 'Pay via 1Pay; you can have many options to choose from.', 'woo-viet' )
	),
	'email' => array(
		'title'       => __( 'PayPal Email', 'woo-viet' ),
		'type'        => 'email',
		'description' => __( 'Please enter your PayPal email address; this is needed in order to take payment.', 'woocommerce' ),
		'default'     => get_option( 'admin_email' ),
		'desc_tip'    => true,
		'placeholder' => 'you@youremail.com'
	),
);
