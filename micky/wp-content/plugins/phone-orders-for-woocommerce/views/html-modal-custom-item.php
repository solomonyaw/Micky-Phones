<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="custom-item-modal" tabindex="-1" role="dialog"
	     aria-labelledby="custom-item-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="custom-item-modal-label"><?php _e( 'Add custom item', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-custom-item-name"><?php _e( 'Line item name', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-custom-item-name" name="name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="input-custom-item-price"><?php _e( 'Price per item', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-custom-item-price" name="price">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="input-custom-item-qty"><?php _e( 'Quantity', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" name="qty"
                                        id="input-custom-item-qty">
                            </div>
                        </div>
                    </div>

                    <!-- form do not submit by enter if input is absent -->
                    <input type="submit" class="hidden">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php _e( 'Cancel', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-primary"
                        data-action="save-custom-item"><?php _e( 'Save line item', 'phone-orders-for-woocommerce' ); ?></button>
            </div>
        </div>
    </div>
</div>