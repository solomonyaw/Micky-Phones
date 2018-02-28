<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="modal fade" id="new-customer-modal" tabindex="-1" role="dialog"
	     aria-labelledby="new-customer-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="new-customer-modal-label"><?php _e( 'New customer', 'phone-orders-for-woocommerce' ) ?></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-firstname"><?php _e( 'First name', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-firstname"
                                        name="first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-lastname"><?php _e( 'Last name', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-lastname"
                                        name="last_name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="input-new-customer-email"><?php _e( 'Email', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-email" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h4><?php _e( 'Billing address', 'phone-orders-for-woocommerce' ); ?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-company"><?php _e( 'Company', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-company"
                                        name="company">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-phone"><?php _e( 'Phone', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-address1"><?php _e( 'Address 1', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-address1"
                                        name="address_1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-address2"><?php _e( 'Address 2', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-address2"
                                        name="address_2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-city"><?php _e( 'City', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-city" name="city">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-postcode"><?php _e( 'Postcode', 'phone-orders-for-woocommerce' ); ?></label>
                                <input type="text" class="form-control" id="input-new-customer-postcode"
                                        name="postcode">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-country"><?php _e( 'Country', 'phone-orders-for-woocommerce' ); ?></label>
                                <select class="form-control" id="input-new-customer-country" name="country">
                                    <?php foreach ( WC()->countries->get_countries() as $code => $name ): ?>
                                        <option value="<?php echo $code; ?>"><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="input-new-customer-state"><?php _e( 'State/County', 'phone-orders-for-woocommerce' ); ?></label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php _e( 'Cancel', 'phone-orders-for-woocommerce' ); ?></button>
                <button type="button" class="btn btn-primary"
                        data-action="save-new-customer"><?php _e( 'Save customer', 'phone-orders-for-woocommerce' ); ?></button>
            </div>
        </div>
    </div>
</div>