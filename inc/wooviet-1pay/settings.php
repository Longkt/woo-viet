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
		'label'   => __( '1Pay Bank (by Woo Viet)', 'woo-viet' ),
		'default' => 'yes'
	),
	'title' => array(
		'title'       => __( 'Title', 'woo-viet' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woo-viet' ),
		'default'     => __( '1Pay Bank', 'woo-viet' ),
		'desc_tip'    => true,
	),
	'description' => array(
		'title'       => __( 'Description', 'woo-viet' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woo-viet' ),
		'default'     => __( 'With 1Pay Bank, you can make payment by using any local Vietnam ATM card.', 'woo-viet' )
	),
	'api_details' => array(
		'title'       => __( 'API Credentials', 'woo-viet' ),
		'type'        => 'title',
		'description' => sprintf( __( 'Enter your 1Pay API credentials. Contact 1Pay to have your credentials %shere%s.', 'woo-viet' ), '<a href="https://1pay.vn/home/contact.html">', '</a>' ),
	),
	'access_key' => array(
		'title'       => __( 'Access key', 'woo-viet' ),
		'type'        => 'text',
		'description' => __( 'Get your access key from 1Pay.', 'woo-viet' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Required', 'woocommerce' )
	),
	'secret' => array(
		'title'       => __( 'Secret', 'woocommerce' ),
		'type'        => 'password',
		'description' => __( 'Get your 1Pay Secret info.', 'woo-viet' ),
		'default'     => '',
		'desc_tip'    => true,
		'placeholder' => __( 'Required', 'woo-viet' )
	),

	/*
	// @todo: Add more help info later

	'help_title' => array(
		'title'       => __( 'More info about 1Pay', 'woo-viet' ),
		'type'        => 'title',
		'description' => __( 'You can see more info at this link.', 'woo-viet' )
	),
	*/

);
