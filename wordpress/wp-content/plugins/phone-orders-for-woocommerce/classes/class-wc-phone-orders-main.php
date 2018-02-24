<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

class WC_Phone_Orders_Main {
    public static $slug = 'phone-orders-for-woocommerce';
    private $path_views_default = '';

    /**
     * WC_Phone_Orders_Main constructor.
     */
    public function __construct() {
        $this->path_views_default = plugin_dir_path( plugin_dir_path( __FILE__ ) ) . "/views/";

        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

        add_action( 'wp_ajax_' . self::$slug, array( new WC_Phone_Orders_AJAX(), 'wp_ajax' ) );

        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        
        add_action( 'woocommerce_shipping_init', function ( $methods ) {
			include_once 'class-wc-phone-shipping-method.php';
		});
        add_filter( 'woocommerce_shipping_methods', function ( $methods ) {
			$methods['phone_orders'] = 'WC_Phone_Shipping_Method';
			return $methods;
		});
    }

    public function add_menu() {
	if( current_user_can( 'manage_woocommerce' ) ) {
        add_submenu_page(
            'woocommerce',
            __( 'Make New Order', 'phone-orders-for-woocommerce' ),
            __( 'Make New Order', 'phone-orders-for-woocommerce' ),
            'manage_woocommerce',
            self::$slug,
            array( $this, 'render_menu' )
        );
	}
    }

    public function render_menu() {
        $this->render( 'main-page' );
    }

    public function render( $view, $params = array(), $path_views = null ) {
        extract( $params );
        if ( ! $path_views ) {
            $path_views = $this->path_views_default;
        }
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'product',
            'post_status'      => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_manage_stock',
                    'value' => 'yes',
                ),
                array(
                    'key' => '_backorders',
                    'value' => 'no',
                ),
                 array(
                    'key' => '_stock',
                    'value' => '0',
                ),
            ),
            'fields'        => 'ids'
        );
        $exclude_products = json_encode(get_posts( $args ));

        include $path_views . "$view.php";
    }

    public function admin_enqueue_scripts( $hook ) {

        if ( 'woocommerce_page_phone-orders-for-woocommerce' === $hook) {

            define('WOOCOMMERCE_CART', 1);
            /* //don't load these scripts , we don't allow to pay yet
            $gateways = WC_Payment_Gateways::instance()->get_available_payment_gateways();
            foreach ($gateways as $key => $gateway) {
                if ($gateway instanceof WC_Payment_Gateway_CC) {
                    $gateway->payment_scripts();
                }
            }
            */

            wp_enqueue_style( 'select2', plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/css/select2.min.css' );
            wp_enqueue_style( 'bootstrap-css', plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/css/bootstrap.min.css' );
            wp_enqueue_style( self::$slug, plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/css/wc-phone-orders.css' );

            wp_enqueue_script( 'postbox' );
            wp_enqueue_script( 'select2', plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/js/select2.full.min.js', array( 'jquery' ) );
            wp_enqueue_script( 'jquery-blockui', plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/js/jquery.blockUI.min.js', array( 'jquery' ) );
            wp_enqueue_script( 'bootstrap-js', plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/js/bootstrap.min.js', array( 'jquery' ) );

            wp_enqueue_script( 'wc-phone-orders', plugin_dir_url( plugin_dir_path( __FILE__ ) ) . 'assets/js/wc-phone-orders.js', array( 'jquery' ) );
            wp_localize_script( 'wc-phone-orders', 'wc_phone_orders', array(
                'countries'              => WC()->countries->get_states(),
                'search_products_nonce'  => wp_create_nonce( 'search-products' ),
                'search_customers_nonce' => wp_create_nonce( 'search-customers' ),

                'Coupon' => __('Coupon ', 'phone-orders-for-woocommerce'),
                'Remove' => __('Remove ', 'phone-orders-for-woocommerce'),
                'Field_Name_Required' => __('Field "Name" is required', 'phone-orders-for-woocommerce'),
                'Discount' => __('Discount ', 'phone-orders-for-woocommerce'),
                'Add_Discount' => __('Add discount ', 'phone-orders-for-woocommerce'),
                'No_Shipping_Methods_Available' => __('No shipping methods available', 'phone-orders-for-woocommerce'),
                'Add_Shipping' => __('Add shipping ', 'phone-orders-for-woocommerce'),
                'Tax_Already_Added' => __('Tax already added ', 'phone-orders-for-woocommerce'),
            ) );

        }
    }

    public static function get_options() {
        $options = get_option(self::$slug);

        if (!is_array($options)) $options = array();

        $options = wp_parse_args( $options, array(
            'auto_recalculate' => false,
            'order_status' => 'wc-pending',
        ) );

        return $options;
    }

    public static function update_options($options) {
        update_option(self::$slug, $options);
    }

    public function load_textdomain() {
        load_plugin_textdomain( self::$slug, FALSE, basename( dirname( dirname( __FILE__ ) ) ) . '/languages/' );
    }
}