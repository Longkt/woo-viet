<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Vietnam Cities to WooCommerce
 *
 * @credit: https://github.com/8manos/wc-city-select
 * @author   htdat
 * @since    1.2
 *
 *
 */
class WooViet_Cities {

	public function __construct() {

		/**
		 * Load the 'WC City Select' class if this plugin is NOT active
		 */
		if ( ! class_exists( 'WC_City_Select' ) ) {
			include( WOO_VIET_DIR . 'lib/wc-city-select/wc-city-select.php' );
		}

		add_filter( 'wc_city_select_cities', array( $this, 'add_cities' ) );

	}

	/**
	 * Add Vietnam Cities
	 *
	 * @param $cities
	 *
	 * @return array
	 */
	public function add_cities( $cities ) {
		/**
		 * @source: https://github.com/htdat/woo-viet/issues/4#issuecomment-277449462
		 * @source: https://gist.github.com/10h30/7e9307d405ff9ef88cf7d226c90a5d13
		 */
		global $cities;

		include( WOO_VIET_DIR . 'resource/VN.php' );

		return $cities;

	}

}
