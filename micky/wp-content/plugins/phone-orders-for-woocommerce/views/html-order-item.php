<?php
/**
 * Shows an order item
 *
 * @var object $item The item being displayed
 * @var int $item_id The id of the item being displayed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$item_id       = $item['product_id'];
$product_link  = $_product ? admin_url( 'post.php?post=' . absint( $item_id ) . '&action=edit' ) : '';
$thumbnail     = $_product ? apply_filters( 'woocommerce_admin_order_item_thumbnail', $_product->get_image( 'thumbnail', array( 'title' => '' ), false ), $item_id, $item ) : '';
$tax_data      = empty( $legacy_order ) && wc_tax_enabled() ? maybe_unserialize( isset( $item['line_tax_data'] ) ? $item['line_tax_data'] : '' ) : false;
$item_total    = ( isset( $item['line_total'] ) ) ? esc_attr( wc_format_localized_price( $item['line_total'] ) ) : '';
$item_subtotal = ( isset( $item['line_subtotal'] ) ) ? esc_attr( wc_format_localized_price( $item['line_subtotal'] ) ) : '';
$class = '';

?>
<tr class="item <?php echo $class ?>"
	data-order_item_id="<?php echo $item_id; ?>"
	<?php if($item['in_stock'] !== null): ?> data-stockamount="<?php echo $item['in_stock'] ?>" <?php endif; ?>>
	<td class="thumb">
		<?php
			echo '<div class="wc-order-item-thumbnail">' . wp_kses_post( $thumbnail ) . '</div>';
		?>
	</td>
	<td class="name" data-sort-value="<?php echo esc_attr( $item['name'] ); ?>">
		<?php
			echo $product_link ? '<a target="_blank" href="' . esc_url( $product_link ) . '" class="wc-order-item-name">' .  esc_html( $item['name'] ) . '</a>' : '<div class="class="wc-order-item-name"">' . esc_html( $item['name'] ) . '</div>';

			if ( $_product && $_product->get_sku() ) {
				echo '<div class="wc-order-item-sku"><strong>' . __( 'SKU:', 'phone-orders-for-woocommerce' ) . '</strong> ' . esc_html( $_product->get_sku() ) . '</div>';
			}

			if ( ! empty( $item['variation_id'] ) ) {
				echo '<div class="wc-order-item-variation"><strong>' . __( 'Variation ID:', 'phone-orders-for-woocommerce' ) . '</strong> ';
				if ( ! empty( $item['variation_id'] ) && 'product_variation' === get_post_type( $item['variation_id'] ) ) {
					echo esc_html( $item['variation_id'] );
				} elseif ( ! empty( $item['variation_id'] ) ) {
					echo esc_html( $item['variation_id'] ) . ' (' . __( 'No longer exists', 'phone-orders-for-woocommerce' ) . ')';
				}
				echo '</div>';
			}
		?>

		<div class="item-msg"></div>
		<input type="hidden" class="order_item_id" name="order_item_id[]" value="<?php echo esc_attr( $item_id ); ?>" />
		<input type="hidden" name="order_item_tax_class[<?php echo absint( $item_id ); ?>]"
			value="<?php echo isset( $item['tax_class'] ) ? esc_attr( $item['tax_class'] ) : ''; ?>" />


	</td>

	<?php do_action( 'woocommerce_admin_order_item_values', $_product, $item, absint( $item_id ) ); ?>

	<td class="item_cost" width="1%">
		<div class="edit">
			<input type="text" autocomplete="off" name="order_item_cost[<?php echo absint( $item_id ); ?>]"
				placeholder="0" value="<?php echo $item['line_total']; ?>" data-cost="<?php echo $item['line_total']; ?>"
				size="4" class="cost" />
		</div>
	</td>
	<td class="quantity" width="1%">
		<div class="edit">
			<?php $item_qty = esc_attr( $item['qty'] ); ?>
			<input type="number" step="<?php echo apply_filters( 'woocommerce_quantity_input_step', '1', $_product ); ?>"
				min="0" autocomplete="off" name="order_item_qty[<?php echo absint( $item_id ); ?>]"
				placeholder="0" value="<?php echo $item_qty; ?>" data-qty="<?php echo $item_qty; ?>" size="4" class="qty" />
		</div>
	</td>
	<td class="line_total" width="1%" data-sort-value="<?php echo esc_attr( isset( $item['line_total'] ) ? $item['line_total'] : '' ); ?>">
		<div class="total">
			<?php
				if ( isset( $item['line_total'] ) ) {
					echo $item['line_total'];
				}
			?>
		</div>
	</td>

	<td class="wc-order-edit-line-item" width="1%">
		<div class="wc-order-edit-line-item-actions">
			<a class="delete-order-item tips" href="#" data-tip="<?php esc_attr_e( 'Delete item', 'phone-orders-for-woocommerce' ); ?>"></a>
		</div>
	</td>
</tr>
