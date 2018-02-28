<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="tax-modal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="custom-item-modal-label"><?php _e( 'Tax', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>
            <div class="modal-body">
                    <form action="" method="post" class="tax-form">
                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th><?php _e( 'Rate name', 'phone-orders-for-woocommerce' ); ?></th>
                                    <th><?php _e( 'Tax class', 'phone-orders-for-woocommerce' ); ?></th>
                                    <th><?php _e( 'Rate code', 'phone-orders-for-woocommerce' ); ?></th>
                                    <th><?php _e( 'Rate %', 'phone-orders-for-woocommerce' ); ?></th>
                                </tr>
                            </thead>
                        <?php
                            global $wpdb;
                            $rates = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_tax_rates ORDER BY tax_rate_name LIMIT 100" );

                            foreach ( $rates as $rate ) {
                                echo '
                                    <tr>
                                        <td><input type="radio" id="add_order_tax_' . absint( $rate->tax_rate_id ) . '" name="add_order_tax" value="' . absint( $rate->tax_rate_id ) . '" /></td>
                                        <td><label for="add_order_tax_' . absint( $rate->tax_rate_id ) . '">' . WC_Tax::get_rate_label( $rate ) . '</label></td>
                                        <td>' . ( isset( $classes_options[ $rate->tax_rate_class ] ) ? $classes_options[ $rate->tax_rate_class ] : '-' ) . '</td>
                                        <td>' . WC_Tax::get_rate_code( $rate ) . '</td>
                                        <td class="order_tax_rate_' . absint( $rate->tax_rate_id ) . '">' . WC_Tax::get_rate_percent( $rate ) . '</td>
                                    </tr>
                                ';
                            }
                        ?>
                        </table>
                        <?php if ( absint( $wpdb->get_var( "SELECT COUNT(tax_rate_id) FROM {$wpdb->prefix}woocommerce_tax_rates;" ) ) > 100 ) : ?>
                            <p>
                                <label for="manual_tax_rate_id"><?php _e( 'Or, enter tax rate ID:', 'phone-orders-for-woocommerce' ); ?></label><br/>
                                <input type="number" name="manual_tax_rate_id" id="manual_tax_rate_id" step="1" placeholder="<?php esc_attr_e( 'Optional', 'phone-orders-for-woocommerce' ); ?>" />
                            </p>
                        <?php endif; ?>
                    </form>
                  </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php _e( 'Cancel', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-primary" data-action="add-tax"><?php _e( 'Add', 'phone-orders-for-woocommerce' ); ?></button>
            </div>
        </div>
    </div>
</div>