<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$options = WC_Phone_Orders_Main::get_options(); 
?>

<div class="postbox closed">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php _e('Toggle panel: Settings', 'phone-orders-for-woocommerce') ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>

    <h2 class="hndle"><span><?php _e('Settings', 'phone-orders-for-woocommerce') ?></span></h2>

    <div class="inside">
        <div class="toolbar">
            <label>
                <input type="checkbox" <?php checked($options['auto_recalculate']) ?> id="wpo-settings-auto_recalculate">
                &nbsp;<?php _e('Automatically update Total and Taxes', 'phone-orders-for-woocommerce') ?>
            </label>
            <label>
				<?php _e('Set order status', 'phone-orders-for-woocommerce') ?>
                &nbsp;
					<select id="wpo-settings-order_status" style="width: 100%; max-width: 50%;">
						<?php foreach ( wc_get_order_statuses() as $i => $status ) { ?>
							<option value="<?php echo $i ?>" <?php if ( $i == $options['order_status'] ) echo 'selected'; ?>><?php echo $status ?></option>
						<?php } ?>
					</select>
                
            </label>
        </div>
    </div>
</div>