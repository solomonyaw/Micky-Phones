<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog"
	     aria-labelledby="edit-address-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit-address-modal-label"><?php _e( 'Edit address', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row" id='billing_email'>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-email"><?php _e( 'Billing Email', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-email" name="email">
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-firstname"><?php _e( 'First name', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-firstname" name="first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-lastname"><?php _e( 'Last name', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-lastname" name="last_name">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-company"><?php _e( 'Company', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-company" name="company">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-phone"><?php _e( 'Phone', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-address1"><?php _e( 'Address 1', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-address1"
                                        name="address_1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-address2"><?php _e( 'Address 2', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-address2"
                                        name="address_2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-city"><?php _e( 'City', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-city" name="city">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-postcode"><?php _e( 'Postcode', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-postcode"
                                        name="postcode">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-country"><?php _e( 'Country', 'phone-orders-for-woocommerce' ); ?></label>
                                <select class="form-control" id="input-edit-address-country" name="country">
                                    <?php foreach ( WC()->countries->get_countries() as $code => $name ): ?>
                                        <option value="<?php echo $code; ?>"><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-edit-address-state"><?php _e( 'State/County', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-edit-address-state" name="state">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php _e( 'Cancel', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-primary"
                        data-action="save-edit-address"><?php _e( 'Save address', 'phone-orders-for-woocommerce' ); ?></button>
            </div>
        </div>
    </div>
</div>