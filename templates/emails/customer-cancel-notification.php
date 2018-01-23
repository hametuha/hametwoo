<?php
/**
 * Cancelled mail if order is automatically cancelled.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-cancel-notification.php
 *
 * @var \Hametuha\HametWoo\Emails\CancelNotification $email
 * @var string $email_heading
 * @var WC_Order $order
 * @var bool     $sent_to_admin
 * @var bool     $plain_text
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

	<p>
		<?php
		// translators: %s Customer name.
		echo esc_html( sprintf( __( 'Dear %s,', 'hametwoo' ), $order->get_formatted_billing_full_name() ) );
		?>
	</p>

	<p>
		<?php
		// translators: %d order id.
		echo esc_html( sprintf( __( 'Your order #%d has been automatically cancelled.', 'hametwoo' ), $order->get_order_number() ) );
		?>
	</p>

	<?php if ( $reason = get_post_meta( $order->get_id(), '_hametwoo_cancel_reason', true ) ) : ?>
	<p>
		<strong><?php esc_html_e( 'Reason:', 'hametwoo' ) ?></strong><br />
		<?php echo esc_html( $reason ) ?>
	</p>
	<?php endif; ?>



<?php

/**
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Emails::order_schema_markup() Adds Schema.org markup.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
