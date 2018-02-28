<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="coupon-modal" tabindex="-1" role="dialog" aria-labelledby="coupon-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="coupon-modal-label"><?php _e( 'Add coupon', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>


            <div class="modal-body">

                <form class="form-inline">
                    <select name="coupon-value" style="width: 190px;"></select>
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                            data-action="save-coupon"><?php _e( 'Apply', 'phone-orders-for-woocommerce' ); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>