<?php
/**
 * Cancelled mail if order is automatically cancelled.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-cancel-notification.php
 *
 * @var \Hametuha\HametWoo\Emails\CancelNotification $email
 * @var string   $email_heading
 * @var WC_Order $order
 * @var bool     $sent_to_admin
 * @var bool     $plain_text
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '= ' . $email_heading . " =\n\n";

printf( __( 'Dear %s,', 'hametwoo' ), $order->get_formatted_billing_full_name() ) . "\n";
printf( __( 'Your order #%d has been automatically cancelled.', 'hametwoo' ), $order->get_order_number() ) . "\n\n";
$reason = $order->get_meta( '_hametwoo_cancel_reason' );
if ( $reason ) {
	esc_html_e( 'Reason:', 'hametwoo' );
	echo "\n" . esc_html( $reason ) . "\n\n";
}

echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Emails::order_schema_markup() Adds Schema.org markup.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

echo "\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );
