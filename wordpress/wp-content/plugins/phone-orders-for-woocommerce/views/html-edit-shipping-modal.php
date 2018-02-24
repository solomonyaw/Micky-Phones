<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="shipping-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="coupon-modal-label"><?php _e( 'Shipping method', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>


            <div class="modal-body">
                <form>
                    <ul id="shipping_method"><?php _e('No shipping methods available', 'phone-orders-for-woocommerce') ?></ul>
                    <input type="submit" class="hidden">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php _e( 'Cancel', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-danger"
                    data-action="remove-shipping">
                    <?php _e( 'Remove', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-primary"
                    data-action="save-shipping"><?php _e( 'Save', 'phone-orders-for-woocommerce' ); ?></button>
            </div>
        </div>
    </div>
</div>