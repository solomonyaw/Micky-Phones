<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="order-footer">

    <div class="order-footer__note">
        <p>
            <?php _e('Customer provided note', 'phone-orders-for-woocommerce') ?>
            <textarea placeholder="<?php _e('Add a note', 'phone-orders-for-woocommerce') ?>" id="customer-note"></textarea>
        </p>

        <p>
            <?php _e('Private note', 'phone-orders-for-woocommerce') ?>
            <textarea placeholder="<?php _e('Add a note', 'phone-orders-for-woocommerce') ?>" id="private-note"></textarea>
        </p>
    </div>

    <?php $currency_symbol = get_woocommerce_currency_symbol(); ?>
    <table class="wc-order-totals">
        <tbody>

            <tr>
                <td class="label-total"><?php _e('Subtotal', 'phone-orders-for-woocommerce') ?>:</td>
                <td width="1%"></td>
                <td class="subtotal">
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol ?></span><!--
                        --><span class="subtotal-value">0.00</span>
                    </span>
                </td>
            </tr>

            <tr class="coupons-list-add" >
                <td class="label-total"><a href="#" class="edit-coupon-modal"><?php _e('Add coupon', 'phone-orders-for-woocommerce') ?>:</a></td>
                <td width="1%"></td>
                <td><span class="woocommerce-Price-amount coupon-value"></td>
            </tr>


            <tr>
                <td class="label-total"><a href="#" class="edit-discount-modal"><?php _e('Add discount', 'phone-orders-for-woocommerce') ?>:</a></td>
                <td width="1%"></td>
                <td>
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol ?></span><!--
                        --><span class="discount-value">0.00</span>
                    </span>
                </td>
            </tr>

            <tr>
                <td class="label-total">
                    <a href="#" class="edit-shipping-modal"><?php _e('Add shipping', 'phone-orders-for-woocommerce') ?>:</a>
                    <span class="total-shipping-label"></span>
                </td>
                <td width="1%"></td>
                <td><span class="shipping-value amount"></td>
            </tr>

            <tr>
                <td colspan="3">
                    <button class="button button-primary" data-action="recalculate" style="display: none;"><?php _e('Recalculate', 'phone-orders-for-woocommerce') ?></button>
                </td>
            </tr>

            <tr class="order-taxes-line order-total-line--updated">
                <td class="label-total"><?php _e('Taxes', 'phone-orders-for-woocommerce') ?>:</td>
                <td width="1%"></td>
                <td class="total">
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol ?></span><!--
                        --><span class="order-total-value">0.00</span>
                    </span>
                </td>
            </tr>

            <tr class="order-total-line order-total-line--updated">
                <td class="label-total"><?php _e('Order Total', 'phone-orders-for-woocommerce') ?>:</td>
                <td width="1%"></td>
                <td class="total">
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol"><?php echo $currency_symbol ?></span><!--
                        --><span class="order-total-value">0.00</span>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <!--<button class="button" data-action="test"><?php _e('Test', 'phone-orders-for-woocommerce') ?></button>-->
    <div class="clear"></div>
</div>

<div class="order-actions" style="display: none;">
    <table class="wc-order__actions">
        <tr>
            <td>
                <button class="button" data-action="create-order"><?php _e('Create order', 'phone-orders-for-woocommerce') ?></button>

                <button class="button" data-action="view-order"><?php _e('View order', 'phone-orders-for-woocommerce') ?></button>
                <button class="button" data-action="send-order"><?php _e('Send invoice', 'phone-orders-for-woocommerce') ?></button>
                <a class="button" data-action="pay-order" target="_blank" href="#"><?php _e('Pay order', 'phone-orders-for-woocommerce') ?></a>
                <button class="button" data-action="new-order"><?php _e('Create new order', 'phone-orders-for-woocommerce') ?></button>

            </td>
            <td>
                <span class="description" style="display: none;">
                    <span class="woocommerce-help-tip" data-tip="To edit this order change the status back to &quot;Pending&quot;"></span>
                    <span class="description-content">This order is no longer editable.</span>
                </span>
            </td>
        </tr>
    </table>
</div>