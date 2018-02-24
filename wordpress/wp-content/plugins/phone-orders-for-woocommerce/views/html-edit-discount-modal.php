<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="discount-modal" tabindex="-1" role="dialog"
	     aria-labelledby="discount-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="discount-modal-label"><?php _e( 'Add discount', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>


            <div class="modal-body">

                <form class="form-inline" submit="return false;">

                    <div class="btn-group discount-type-toggler">
                        <label class="btn btn-default active">
                            <input type="radio" name="discount-type" autocomplete="off" value="fixed_cart" checked>$
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="discount-type" autocomplete="off" value="percent">%
                        </label>
                    </div>

                    <input type="text" class="form-control" placeholder="Amount" name="discount-amount">

                </form>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php _e( 'Cancel', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-danger"
                        data-action="remove-discount"><?php _e( 'Remove', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-primary"
                        data-action="save-discount"><?php _e( 'Apply', 'phone-orders-for-woocommerce' ); ?></button>
            </div>
        </div>
    </div>
</div>