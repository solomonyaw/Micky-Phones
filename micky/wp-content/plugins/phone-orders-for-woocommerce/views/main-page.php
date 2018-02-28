<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap" id="woo-phone-orders">

    <!--<h1 class="wp-heading-inline"><?php _e('Make new order', 'phone-orders-for-woocommerce') ?></h1>-->

    <h1 class="screen-reader-text"></h1>

    <div id="poststuff">

        <div id="post-body" class="metabox-holder columns-2">

            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox disable-on-order">

                        <span class="handlediv button-link">
                            <a href="" class="clear-customer" style="display: none;">&times;</a>
                        </span>

                        <h2><span><?php _e( 'Find or create a customer', 'phone-orders-for-woocommerce' ) ?></span></h2>

                        <div class="inside">
                            <div id="search-customer-box">
                                <a href="#" id="create-new-customer"><?php _e('New customer', 'phone-orders-for-woocommerce') ?></a>
                                <select id="select-customer"></select>
                            </div>

                            <div class="customer-box">
                                <?php
                                    $customer_data = array();
                                    include 'html-customer-details.php';
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php include 'html-settings-box.php' ?>
                </div>
            </div>

            <div id="postbox-container-2" class="postbox-container">
                <div class="postbox disable-on-order" id="woocommerce-order-items">

                    <span class="handlediv button-link">
                        <a href="" class="thickbox link-add-custom-item">
                            <?php _e( 'Add custom item', 'phone-orders-for-woocommerce' ) ?>
                        </a>
                    </span>

                    <h2><span><?php _e( 'Order details', 'phone-orders-for-woocommerce' ) ?></span></h2>

                    <div class="inside">

                        <div class="order-content">

                            <div id="search-items-box">
                                <select id="select-items" data-placeholder="<?php _e('Find products...', 'phone-orders-for-woocommerce') ?>"></select>
                            </div>

                            <div class="woocommerce_order_items_wrapper wc-order-items-editable">
                                <table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
                                    <thead>
                                        <tr>
                                            <th class="item sortable" colspan="2" data-sort="string-ins"><?php _e('Item', 'phone-orders-for-woocommerce') ?></th>
                                            <th class="item_cost sortable" data-sort="float"><?php _e('Cost', 'phone-orders-for-woocommerce') ?></th>
                                            <th class="quantity sortable" data-sort="int"><?php _e('Qty', 'phone-orders-for-woocommerce') ?></th>
                                            <th class="line_cost sortable" data-sort="float"><?php _e('Total', 'phone-orders-for-woocommerce') ?></th>
                                            <th class="wc-order-edit-line-item" width="1%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order_line_items"></tbody>
                                </table>
                            </div>

                        </div>

                        <?php include 'html-order-footer.php'; ?>

                    </div>
                </div>
            </div>

        </div>

    </div>

<?php
include 'html-modal-new-customer.php';
include 'html-modal-custom-item.php';
include 'html-edit-address.php';
include 'html-edit-discount-modal.php';
include 'html-edit-coupon-modal.php';
include 'html-edit-shipping-modal.php';
include 'html-edit-tax-modal.php';

wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
?>

</div>
<script>
    var exclude_products = <?php echo $exclude_products;?>;
</script>