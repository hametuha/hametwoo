<?php

namespace Hametuha\HametWoo\Utility;


/**
 * Order handler
 *
 * @package hametwoo
 */
class OrderHandler {

	/**
	 * Restore stock.
	 *
	 * @param int|\WC_Order|null $order Order object.
	 *
	 * @return bool
	 */
	public static function restore_stock( $order ) {
		$order = wc_get_order( $order );
		if (
			! $order
			|| 'yes' !== get_option( 'woocommerce_manage_stock' )
			|| ! apply_filters( 'woocommerce_can_reduce_order_stock', true, $order )
			|| 1 > count( $order->get_items() )
		) {
			// No item to restore.
			return false;
		}
		$restored = false;
		foreach ( $order->get_items( 'line_item' ) as $item ) {
			/* @var \WC_Order_Item_Product $item Order item. */
			$qty = $item->get_quantity();
			$product = $item->get_product();
			if ( ! $qty || ! $product || ! $product->managing_stock() ) {
				continue;
			}
			$name = $product->get_formatted_name();
			$current_stock = $product->get_stock_quantity();
			$new_stock = wc_update_product_stock( $product, $qty, 'increase' );
			if ( ! $new_stock ) {
				// Oops, failed to update stock.
				continue;
			}
			// Add order note.
			// translators: %1$s item name, %2$d current stock quantity, %3$d new stock quantity.
			$order->add_order_note( sprintf( __( '%1$s stock increased from %2$d to %3$d.', 'hametwoo' ), $name, $current_stock, $new_stock ) );
			$restored = true;
		}
		return $restored;
	}
}
