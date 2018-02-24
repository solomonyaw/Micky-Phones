<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="order_data_column">

    <h4>
        <?php _e( 'Billing Details', 'phone-orders-for-woocommerce' ); ?>
        <a href="#" class="edit_address" data-edit-address="billing">Edit</a>
    </h4>
    <p>
        <?php
            $address = array(
                'first_name' => isset( $customer_data['billing_first_name'] ) ? $customer_data['billing_first_name']: '',
                'last_name'  => isset( $customer_data['billing_last_name'] ) ? $customer_data['billing_last_name']:   '',
                'company'    => isset( $customer_data['billing_company'] ) ? $customer_data['billing_company']:       '',
                'address_1'  => isset( $customer_data['billing_address_1'] ) ? $customer_data['billing_address_1']:   '',
                'address_2'  => isset( $customer_data['billing_address_2'] ) ? $customer_data['billing_address_2']:   '',
                'city'       => isset( $customer_data['billing_city'] ) ? $customer_data['billing_city']:             '',
                'state'      => isset( $customer_data['billing_state'] ) ? $customer_data['billing_state']:           '',
                'postcode'   => isset( $customer_data['billing_postcode'] ) ? $customer_data['billing_postcode']:     '',
                'country'    => isset( $customer_data['billing_country'] ) ? $customer_data['billing_country']:       '',
            );
            $billing_address = WC()->countries->get_formatted_address( $address );

            if ( empty( $billing_address ) ) {
                echo empty( $shipping_address ) ? __('No billing address was provided.', 'phone-orders-for-woocommerce') : __('Same as shipping address.', 'phone-orders-for-woocommerce');
            } else {
                echo $billing_address;
            }
        ?>
    </p>

    <p>
        <label>
            <input type="checkbox" class="ship-different-address">
            <?php _e ('Ship to a different address?', 'phone-orders-for-woocommerce') ?>
        </label>
    </p>

    <div class="shipping-details" style="display: none">
        <h4>
            <?php _e( 'Shipping Details', 'phone-orders-for-woocommerce' ); ?>
            <a href="#" class="edit_address" data-edit-address="shipping">Edit</a>
        </h4>
        <p>
            <?php
                $address = array(
                    'first_name' => isset( $customer_data['shipping_first_name'] ) ? $customer_data['shipping_first_name']: '',
                    'last_name'  => isset( $customer_data['shipping_last_name'] ) ? $customer_data['shipping_last_name']:   '',
                    'company'    => isset( $customer_data['shipping_company'] ) ? $customer_data['shipping_company']:       '',
                    'address_1'  => isset( $customer_data['shipping_address_1'] ) ? $customer_data['shipping_address_1']:   '',
                    'address_2'  => isset( $customer_data['shipping_address_2'] ) ? $customer_data['shipping_address_2']:   '',
                    'city'       => isset( $customer_data['shipping_city'] ) ? $customer_data['shipping_city']:             '',
                    'state'      => isset( $customer_data['shipping_state'] ) ? $customer_data['shipping_state']:           '',
                    'postcode'   => isset( $customer_data['shipping_postcode'] ) ? $customer_data['shipping_postcode']:     '',
                    'country'    => isset( $customer_data['shipping_country'] ) ? $customer_data['shipping_country']:       '',
                );
                $shipping_address = WC()->countries->get_formatted_address( $address );

                if ( empty( $shipping_address ) ) {
                    _e('No shipping address was provided.', 'phone-orders-for-woocommerce');
                } else {
                    echo $shipping_address;
                }
            ?>
        </p>
    </div>

</div>
