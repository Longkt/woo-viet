<?php 
if (! defined( 'ABSPATH' )) {
    exit;
}

class WooViet_Shipping_Method extends WC_Shipping_Method {
    public function __construct( $instance_id = 0 ) {
        $this->id                       = 'wooviet_shipping';
        $this->instance_id              = absint( $instance_id );
        $this->method_title             = __( 'WooViet Shipping City', 'woo-viet' );
        $this->method_description       = __( 'Allow to set shipping price to city (district) in Viet Nam', 'woo-viet' );
        $this->supports                 = array(
            'shipping-zones',
            'instance-settings',
        );

        $this->option_key               = $this->id . '_city_fields';
        $this->init();
    }
    
    public function init() {
        // Load the settings.
        $this->init_form_fields();
        $this->init_settings();

        // Define user set variables
        $this->title = $this->get_option( 'title' );
        $this->state_cost = $this->get_option( 'state_cost' );

        // Actions
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    }


    public function init_form_fields() {
        $this->instance_form_fields = array(
            'title' => array(
                'title'         => __( 'Title', 'woo-viet' ),
                'type'          => 'text',
                'description'   => __( 'This controls the title which the user sees during checkout.', 'woo-viet' ),
                'default'       => 'WooViet Shipping City',
                'desc_tip'      => true,
            ),
            'state_cost' => array(
                'title'         => __( 'State Cost', 'woo-viet' ),
                'type'          => 'price',
                'placeholder'   => '0',
                'description'   => __( 'Optional cost for shipping to state if customer choose any city.', 'woo-viet' ),
                'default'       => '',
                'desc_tip'      => true,
            ),
            'city_select'  => array(
                'type'          => 'city_select',
            ),
        );
                
    }

    /**
     * Get the instance ID from URL
     */
    public function get_current_instance_id() {
        $current_instance_id = '';
        if( isset( $_REQUEST['instance_id'] ) ) {
            $current_instance_id = $_REQUEST['instance_id'];
        }

        return $current_instance_id;
    }

    /**
     * Get list cities from the instance ID
     */
    public function get_list_cities() {
        global $wpdb;
        include( WOO_VIET_DIR . 'resource/VN.php' );
        $list_cities = array();

        // Query get location zone and method ID
        $location_zone = $wpdb->get_results( 
            "SELECT {$wpdb->prefix}woocommerce_shipping_zone_locations.location_code, {$wpdb->prefix}woocommerce_shipping_zone_methods.instance_id 
            FROM {$wpdb->prefix}woocommerce_shipping_zone_locations
            INNER JOIN {$wpdb->prefix}woocommerce_shipping_zone_methods
            ON {$wpdb->prefix}woocommerce_shipping_zone_locations.zone_id = {$wpdb->prefix}woocommerce_shipping_zone_methods.zone_id"
            , ARRAY_A );

        for ( $i = 0; $i < count( $location_zone ); $i++ ) {
            // Get location code
            $location_code[] = substr( $location_zone[$i]['location_code'], 3 );

            // Get method ID
            $instance_id[] = $location_zone[$i]['instance_id'];                   
        }

        // Get current method ID
        $current_instance_id = $this->get_current_instance_id();
        if( $current_instance_id ) {
            for ( $i = 0; $i < count( $instance_id ); $i++ ) {

                // Match current location ID
                if ( $current_instance_id == $instance_id[$i] ) {
                    foreach ( $cities['VN'] as $key => $value ) {
                        if( $location_code[$i] == $key ) {
                            $list_cities_temp[$i] = $value;
                        }                       
                    }

                    $list_cities = array_merge( $list_cities, $list_cities_temp[$i] );
                }
            }
        }

        return $list_cities;
    }

    /**
     * Calculate_shipping function
     */
    public function calculate_shipping( $package = array() ) {
        global $woocommerce;
        $package = $woocommerce->cart->get_shipping_packages();
        $options = $this->get_options();
        $label = $this->title;
        $cost = $this->state_cost;

        if( !empty( $options && $package[0]['destination']['city'] ) ) {
            foreach ($options as $key => $value) {
                if ( $key == $package[0]['destination']['city'] ) {
                    $label = $value['shipping-name'];
                    $cost = $value['city-cost'];
                }
            }
        }
        
        $this->add_rate( array(
          'id'   => $this->id,
          'label' => $label,
          'cost'   => $cost,
        ) );   
    }


    /**
     * Generate city select area
     */
    public function generate_city_select_html() {
        $list_cities = $this->get_list_city();
        ob_start();
        ?>
<script>
    jQuery(document).ready(function($) {
        var listCities = <?php echo json_encode($list_cities); ?>;
        var currentID   = 0;
        
        <?php 
        $options = $this->get_options();

        foreach ($options as $key => $value) {
            $value['key'] = $key;
            $row = json_encode($value);

            echo "$('#wooviet-shipping-city-settings table tbody tr:last').before(create_city_row({$row}));\n";
        }
        ?>

        $('#wooviet-shipping-city-settings').on('click', '.add-city-buttons .add', function(e) {
            var row = {};
            row['shipping-name'] = [];
            row['city-cost'] = [];
            row['city-name'] = [];
            $('#wooviet-shipping-city-settings table tbody tr:last').before(create_city_row(row));
            e.preventDefault();
        });

        $('#wooviet-shipping-city-settings').on('click', '.add-city-buttons .delete', function(e) {
            e.preventDefault();
            var citySelected = $(this).closest('table').find('.city-row .city-checkbox:checked');
            $.each(citySelected, function() {
                thisCityRow = $(this).closest('tr');
                thisCityRow.remove();
            })
        });

        function create_city_row(row){
            //lets get the ID of the last one
            
            currentID = $('#wooviet-shipping-city-settings .city-row').last().attr('id');

            if(typeof currentID == 'undefined' || currentID == ""){
                currentID = 1;
            } else {
                currentID = Number(currentID) + 1;
            }

            var html = '';

            html += '<tr id="' + currentID + '" class="city-row" >"';
                    
            html += '<input type="hidden" value="' + currentID + '" name="key[' + currentID + ']"></input>';
            html += '<td><input type="checkbox" class="city-checkbox"></input></span></td>';
            html += '<td><input type="text" size="30" value="' + row['shipping-name'] + '"  name="shipping-name[' + currentID + ']"/></td>';
            html += '<td><input type="text" size="30" value="' + row['city-cost'] + '"  name="city-cost[' + currentID + ']"/></td>';
            html += '<td>';
            html += '<select name="city-name[' + currentID + ']">';
            html += generate_city_html(row['city-name']);
            html += '</select>';
            html += '</td>';
            html += '</tr>';
            ;
            return html;
        }

        function generate_city_html(keys){
                                        
            html = "";
            
            for(var key in listCities){    
                if(keys.indexOf(key) != -1){
                    //we have a match
                    html += '<option value="' + key + '" selected="selected">' + listCities[key] + '</option>'; 
                } else {
                    html += '<option value="' + key + '">' + listCities[key] + '</option>'; 
                    
                }
            }
            
            return html;
        }
    });                    
</script>
        <tr>
            <th scope="row" class="titledesc">Table Rates</th>
            <td id="wooviet-shipping-city-settings">
                <table class="shippingrows widefat">
                    <thead>
                        <tr>
                            <th class="check-column"></th>
                            <th>Shipping Name</th>
                            <th>Cost</th>
                            <th>Cities</th>
                        </tr>
                    </thead>
                    <tbody style="border: 1px solid black;">
                       <tr style="border: 1px solid black;" class="alternate">
                            <td colspan="5" class="add-city-buttons">
                                <a href="#" class="add button">Add New Shipping City</a>
                                <a href="#" class="delete button">Delete Selected City</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <?php return ob_get_clean();
    }

    /**
     * Save all options
     */
    public function process_admin_options() {
        $options            = array();
        $keys               = array();
        $shipping_name      = array();
        $city_cost          = array();
        $city_name          = array();


        if( isset($_POST['key']) )              $keys                   = $_POST['key'];
        if( isset($_POST['shipping-name']) )    $shipping_name          = $_POST['shipping-name'];
        if( isset($_POST['city-cost']) )        $city_cost              = $_POST['city-cost'];
        if( isset($_POST['city-name']) )        $city_name              = $_POST['city-name'];

        foreach ($keys as $key => $value) {
            $name = $city_name[$key];

            $options[$name]                     = array();
            $options[$name]['shipping-name']    = $shipping_name[$key];
            $options[$name]['city-cost']        = $city_cost[$key];
            $options[$name]['city-name']        = $city_name[$key];    
        }

        update_option( $this->option_key, $options );
        parent::process_admin_options();
    }
    
    /**
     * Get custom options
     */
    public function get_options() {
        $options = array_filter( (array) get_option( $this->option_key ) );
        return $options;
    }
}