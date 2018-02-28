<?php
if (! defined( 'ABSPATH' )) {
    exit;
} // Exit if accessed directly

class WC_Phone_Orders_AJAX
{
	const manual_cart_discount_code = '_wpo_cart_discount';

    public function wp_ajax()
    {
        $request = $_REQUEST;

        $method = "ajax_{$request['method']}";
        if (method_exists( $this, $method )) {
            $this->$method( $request );
        }

        die;
    }

    private function ajax_load_item($request)
    {

        $item_to_add = sanitize_text_field( $_POST['id'] );

        // Find the item
        if (! is_numeric( $item_to_add )) {
            die();
        }

        $_product    = wc_get_product( $item_to_add );
        $class       = 'new_row';

        $price_excluding_tax = version_compare( WC_VERSION, '3.0.0', '<' ) ? $_product->get_price_excluding_tax() : wc_get_price_excluding_tax($_product);

        // Set values
        $item = array();
        $item['product_id']        = wc3($_product, 'id');
        $item['variation_id']      = isset( $_product->variation_id ) ? $_product->variation_id : '';
        $item['variation_data']    = $item['variation_id'] ? $_product->get_variation_attributes() : '';
        $item['name']              = $_product->get_title();
        $item['qty']               = 1;
        $item['line_subtotal']     = wc_format_decimal( $price_excluding_tax );
        $item['line_subtotal_tax'] = '';
        $item['line_total']        = wc_format_decimal( $price_excluding_tax );
        $item['line_tax']          = '';
        $item['type']              = 'line_item';
        $item['in_stock']          = $_product->get_stock_quantity();

        ob_start();
        include( plugin_dir_path( plugin_dir_path( __FILE__ ) ) . 'views/html-order-item.php' );
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json_success( array(
            'html' => $html,
            'item' => $item
        ) );
    }

    private function ajax_create_item($request)
    {

        parse_str( $request['data'], $data );

        $post_id = wp_insert_post( array(
            'post_title'   => $data['name'],
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'product',
        ) );

        wp_set_object_terms( $post_id, 'simple', 'product_type' );

        update_post_meta( $post_id, '_visibility', 'visible' );
        update_post_meta( $post_id, '_stock_status', 'instock' );
        update_post_meta( $post_id, 'total_sales', '0' );
        update_post_meta( $post_id, '_downloadable', 'no' );
        update_post_meta( $post_id, '_virtual', 'yes' );
        update_post_meta( $post_id, '_regular_price', '' );
        update_post_meta( $post_id, '_sale_price', '' );
        update_post_meta( $post_id, '_purchase_note', '' );
        update_post_meta( $post_id, '_featured', 'no' );
        update_post_meta( $post_id, '_weight', '' );
        update_post_meta( $post_id, '_length', '' );
        update_post_meta( $post_id, '_width', '' );
        update_post_meta( $post_id, '_height', '' );
        update_post_meta( $post_id, '_sku', '' );
        update_post_meta( $post_id, '_product_attributes', array() );
        update_post_meta( $post_id, '_sale_price_dates_from', '' );
        update_post_meta( $post_id, '_sale_price_dates_to', '' );
        update_post_meta( $post_id, '_price', $data['price'] );
        update_post_meta( $post_id, '_sold_individually', '' );
        update_post_meta( $post_id, '_manage_stock', 'no' );
        update_post_meta( $post_id, '_backorders', 'no' );
        update_post_meta( $post_id, '_stock', '' );

        wp_send_json_success( array(
            'id' => $post_id
        ) );
    }

    private function ajax_create_customer($request)
    {

        parse_str( $request['data'], $data );

        // $user_name = $data['first_name'] . ' ' . $data['last_name'] . rand(1, 1000);
        $user_id = wc_create_new_customer( $data['email'], '', wp_generate_password() );

        if (is_wp_error( $user_id )) {
            wp_send_json_error( $user_id->get_error_message() );
        }

        update_user_meta( $user_id, 'first_name', $data['first_name'] );
        update_user_meta( $user_id, 'last_name', $data['last_name'] );

        $billing_fields = array( 'email', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'postcode', 'country', 'state', 'phone' );
        foreach($billing_fields as $field) {
            update_user_meta( $user_id, 'billing_' . $field,  $data[$field] );
        }

        wp_send_json_success( array(
            'id' => $user_id
        ) );
    }

    private function ajax_update_customer($request)
    {

        parse_str( $request['data'], $data );

        $id = $data['id'];

        $prefix = 'billing_';
        $prefix_len = strlen('billing_');

        foreach ($data as $key => $value) {
            if (substr( $key, 0, $prefix_len ) == $prefix) {
                update_user_meta( $id, $key, $value );
            }
        }

        wp_send_json_success();
    }

    private function ajax_get_customer($request)
    {

        $user_id = $request['data'];

        $customer_data = array(
            'id' => $user_id,

            'billing_first_name' => get_user_meta( $user_id, 'billing_first_name', true ),
            'billing_last_name'  => get_user_meta( $user_id, 'billing_last_name', true ),
            'billing_company'    => get_user_meta( $user_id, 'billing_company', true ),
            'billing_address_1'  => get_user_meta( $user_id, 'billing_address_1', true ),
            'billing_address_2'  => get_user_meta( $user_id, 'billing_address_2', true ),
            'billing_city'       => get_user_meta( $user_id, 'billing_city', true ),
            'billing_postcode'   => get_user_meta( $user_id, 'billing_postcode', true ),
            'billing_country'    => get_user_meta( $user_id, 'billing_country', true ),
            'billing_state'      => get_user_meta( $user_id, 'billing_state', true ),
            'billing_email'      => get_user_meta( $user_id, 'billing_email', true ),
            'billing_phone'      => get_user_meta( $user_id, 'billing_phone', true ),

            'shipping_first_name' => get_user_meta( $user_id, 'shipping_first_name', true ),
            'shipping_last_name'  => get_user_meta( $user_id, 'shipping_last_name', true ),
            'shipping_company'    => get_user_meta( $user_id, 'shipping_company', true ),
            'shipping_address_1'  => get_user_meta( $user_id, 'shipping_address_1', true ),
            'shipping_address_2'  => get_user_meta( $user_id, 'shipping_address_2', true ),
            'shipping_city'       => get_user_meta( $user_id, 'shipping_city', true ),
            'shipping_postcode'   => get_user_meta( $user_id, 'shipping_postcode', true ),
            'shipping_country'    => get_user_meta( $user_id, 'shipping_country', true ),
            'shipping_state'      => get_user_meta( $user_id, 'shipping_state', true ),
            'shipping_email'      => get_user_meta( $user_id, 'shipping_email', true ),
            'shipping_phone'      => get_user_meta( $user_id, 'shipping_phone', true ),
        );

        if ( empty( $customer_data[ 'billing_email' ] ) ) {
            $customer_data[ 'billing_email' ] = get_userdata( $user_id )->user_email;
        }

        $customer_data = array_filter($customer_data);

        ob_start();
        include( plugin_dir_path( plugin_dir_path( __FILE__ ) ) . 'views/html-customer-details.php' );
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json_success( array(
            'html'   => $html,
            'fields' => (object) $customer_data
        ) );
    }

    private function ajax_get_formatted_address($request)
    {

        $customer_data = $request['data'];

        ob_start();
        include( plugin_dir_path( plugin_dir_path( __FILE__ ) ) . 'views/html-customer-details.php' );
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json_success( array(
            'html' => $html
        ) );
    }

    private function ajax_get_shipping_rates($data)
    {
        $result = $this->update_cart( $data['cart'] );
        wp_send_json_success( $result[ 'shipping' ] );
    }

    private function create_order( $data ) {
		$cart = $data['cart'];
        add_filter('woocommerce_checkout_customer_id', function($user_id) use ($cart) {
            return !empty($cart['customer']['id']) ? $cart['customer']['id'] : 0;
        });

        //refresh cart
        $this->update_cart( $cart );
        // checkout needs customer fields!
        $use_shipping_address = !empty( $cart['customer']['ship-different-address'] );
        $checkout_data = array();
		foreach($cart['customer'] as $key=>$value) {
			if( stripos( $key, 'billing_' ) !== false ) {
				$checkout_data[ $key ] = $value;
				if ( !$use_shipping_address ) // use billing details as delivery address
					$checkout_data[ str_replace('billing_', 'shipping_', $key) ] = $value;
			}
			elseif( $use_shipping_address AND stripos( $key, 'shipping_' ) !== false )
				$checkout_data[ $key ] = $value;
		}
        //remap incoming note
        $checkout_data['order_comments'] = $cart['customer_note'];
        //force "Cash On delivery"
        $checkout_data['payment_method'] = 'cod';
        $checkout = new WC_Checkout();
        $order_id = $checkout->create_order($checkout_data);

        if ( isset( $cart['private_note'] ) and ! empty( $cart['private_note'] ) ) {
            $order = wc_get_order( $order_id );
            $order->add_order_note( $cart['private_note'] );
        }

        WC()->cart->empty_cart();
        
        // set status ?
        $options = WC_Phone_Orders_Main::get_options();
        if( !empty($options['order_status']) ) {
			$order = wc_get_order( $order_id );
			$order->update_status( $options['order_status'] );
        }

        return $order_id ;
	}

    private function ajax_create_order($data)
    {
		$order_id = $this->create_order( $data );

        $order = wc_get_order($order_id);
        $payment_url = $order->get_checkout_payment_url();

        $result = array(
            'order_id' => $order_id,
            'message' => sprintf( __('Order #%s created', 'phone-orders-for-woocommerce'), $order_id),
            'payment_url' => $payment_url
        );
        return wp_send_json_success( $result );
    }
    private function ajax_create_order_email_invoice($data)
    {
        $order_id = $data['order_id'];

        $order = wc_get_order( $order_id );

        if (! $order) {
            wp_send_json_error(__( 'Order not found', 'phone-orders-for-woocommerce' ));
        }

        $email = $order->get_billing_email();

        if ( empty($email) ) {
            $user_info = get_userdata($data['cart']['customer']['id']);
            $email = $user_info->user_email;
        }

        if (!is_email( $email )) {
            return wp_send_json_error( __( 'A valid email address is required', 'phone-orders-for-woocommerce' ) );
        }

        try {
            WC()->mailer()->customer_invoice( $order );
        } catch (phpmailerException $e) {
            return wp_send_json_error( __( 'There was an error sending the email', 'phone-orders-for-woocommerce' ));
        }

        $result = array(
            'order_id' => $order_id,
            'email'    => $email,
            'message'  => sprintf( __('Invoice for order #%s has been sent to %s', 'phone-orders-for-woocommerce'), $order_id, $email)
        );
        return wp_send_json_success( $result );
    }

    // Sees if the customer has entered enough data to calc the shipping yet.
    private function should_recalculate ( $customer_data ) {
        if ( get_option( 'woocommerce_shipping_cost_requires_address' ) ) {
            return isset( $customer_data['shipping_state'], $customer_data['shipping_postcode'], $customer_data['shipping_country'] ) || isset( $customer_data['billing_state'], $customer_data['billing_postcode'], $customer_data['billing_country'] );
        }

        return true;
    }

    private function ajax_recalculate($data)
    {
        $result = null;

        if ( isset($data['cart']['customer']) AND $this->should_recalculate( $data['cart']['customer'] ) ) {
            $result = $this->update_cart( $data['cart'] );
        }

        wp_send_json_success( $result );
    }

    private function update_cart($data)
    {
        define( 'WOOCOMMERCE_CART', 1 );

        $cart_data = wp_parse_args( $data, array(
            'customer' => array(),
            'items'    => array(),
            'coupons'  => array(),
            // 'taxes'    => array(),
            'discount' => null,
            'shipping' => null
        ) );

        WC()->cart->empty_cart();
        wc_clear_notices(); // suppress front-end messages

        // items
        foreach ($cart_data['items'] as $item) {
			if( empty($item['variation_id']) ) // required  field for checkout!
				$item['variation_data'] = array();
            WC()->cart->add_to_cart($item['product_id'], $item['qty'], $item['variation_id'], $item['variation_data']);
        }

        // coupons
        WC()->cart->applied_coupons = array();
        foreach ($cart_data['coupons'] as $item) {
            WC()->cart->add_discount( $item );
        }

        // discount as another coupon
        if (! empty($cart_data['discount'])) {
            $discount = $cart_data['discount'];
            if( empty( $discount['type'] ) )
				$discount['type'] = 'fixed_cart';
            //create new coupon via action
            add_action( 'woocommerce_get_shop_coupon_data', function ($manual, $coupon) use ( $discount ) {
				if( $coupon != self::manual_cart_discount_code )
					return $manual;
				// fake coupon here
				return array( 'amount'=>$discount['amount'], 'discount_type'=>$discount['type'], 'id'=>-1 );
			}, 10, 2 );
            WC()->cart->add_discount( self::manual_cart_discount_code);
        }



        // customer
        if (! empty ($cart_data['customer'])) {
            $customer_data = $cart_data['customer'];

            $cart_customer = WC()->customer;

            $fields = array(
                'billing_first_name','billing_last_name','billing_company','billing_address_1','billing_address_2',
                'billing_city','billing_postcode','billing_country','billing_state','billing_email','billing_phone',
                'shipping_first_name','shipping_last_name','shipping_company','shipping_address_1','shipping_address_2',
                'shipping_city','shipping_postcode','shipping_country','shipping_state'
            );

            foreach ($fields as $field) {
                if ( isset($customer_data[$field]) ) {
                    $method = 'set_' . $field;
                    $cart_customer->$method( $customer_data[$field] );
                }
            }
        }

        // shipping
        if (isset( $cart_data['shipping'] ) && is_array( $cart_data['shipping'] )) {
            $chosen_shipping_methods = array( wc_clean( $cart_data['shipping']['id'] ) );
            WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );
        }

        WC()->cart->calculate_totals();

        $result = array(
            'subtotal' => WC()->cart->get_cart_subtotal(),
            'taxes' => wc_price( WC()->cart->get_taxes_total() ),
            'total' => $this->get_cart_total(),
            'shipping' => $this->get_shipping_rates()
        );

        return $result;
    }

    private function get_shipping_rates()
    {
        WC()->shipping->load_shipping_methods();
        $packages = WC()->shipping->get_packages();

        $shipping_rates_result = array();
        foreach ($packages as $package) {
            if (isset( $package['rates'] )) {
                $shipping_rates = array_map(function ($rate) {
                    return array(
                        'id' => $rate->id,
                        'label' => $rate->get_label(),
                        'cost' => $rate->cost
                    );
                }, $package['rates']);

                $shipping_rates_result = array_merge($shipping_rates_result, $shipping_rates);
            }
        }
        return array_values( $shipping_rates_result );
    }

    private function get_cart_total() {
        $value = '<strong>' . WC()->cart->get_total() . '</strong> ';

        // If prices are tax inclusive, show taxes here
        if ( wc_tax_enabled() && WC()->cart->tax_display_cart == 'incl' ) {
            $tax_string_array = array();

            if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
                foreach ( WC()->cart->get_tax_totals() as $code => $tax )
                    $tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
            } else {
                $tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
            }

            if ( ! empty( $tax_string_array ) ) {
                $taxable_address = WC()->customer->get_taxable_address();
                $estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
                    ? sprintf( ' ' . __( 'estimated for %s', 'woocommerce' ), WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
                    : '';
                $value .= '<small class="includes_tax">' . sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) . $estimated_text ) . '</small>';
            }
        }

        return apply_filters( 'woocommerce_cart_totals_order_total_html', $value );
    }

    public function ajax_save_settings($request)
    {
        $options = WC_Phone_Orders_Main::get_options();

        foreach ($request['data'] as $field => $value) {
            $options[$field] = $value;
        }

        WC_Phone_Orders_Main::update_options($options);
    }

    private function ajax_get_coupons_list($data)
    {
        $exclude = isset($_GET['exclude']) ? (array) $_GET['exclude'] : array();

        $term = $data['term'];

        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'asc',
            'post_type'        => 'shop_coupon',
            'post_status'      => 'publish',
        );
        $coupons = get_posts( $args );

        $result = array();
        foreach ($coupons as $coupon) {
            $coupon_name = $coupon->post_title;

            if ( in_array( $coupon_name, $exclude ) ) continue;
            if ( strpos( $coupon_name, $term ) === FALSE ) continue;

            $result[] = array(
                'id'   => $coupon_name,
                'text' => $coupon_name
            );
        }

        die(json_encode($result));
    }

}
