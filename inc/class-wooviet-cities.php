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

		add_action( 'admin_enqueue_scripts', array( $this, 'loads_cities_scripts' ) );

		add_action( 'wp_ajax_add_select_cities', 'add_select_cities' );

	}

	/*public function localize_cities_scripts() {

		include( WOO_VIET_DIR . 'resource/vietnam_city_list.php' );

		$cities = json_encode( $cities['VN'] );

		add_action( 'admin_enqueue_scripts', array( $this, 'loads_cities_scripts' ) );

		wp_localize_script( 'woo-viet-cities-scripts', 'woo-viet-cities' , $cities['VN'] );
	}*/

	public function loads_cities_scripts() {

		wp_enqueue_script( 'woo-viet-cities-scripts', WOO_VIET_URL . 'assets/cities.js', array( 'jquery' ), '1.0', true );

	}

	public function add_select_cities() {

		if( isset($_POST['param']) ) {
			$state_selected = $_POST['param'];
		}
		echo "Success!";
		//add_filter( '$tag', $function_to_add, $priority = 10, $accepted_args = 1 )

		wp_die();
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
		include( WOO_VIET_DIR . 'resource/vietnam_city_list.php' );

		return $cities;

	}
}