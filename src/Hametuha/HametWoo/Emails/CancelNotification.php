<?php

namespace Hametuha\HametWoo\Emails;
use Hametuha\HametWoo;

/**
 * Cancel notification mail
 *
 * @since 0.8.4
 * @package Hametuha\HametWoo\Emails
 */
class CancelNotification extends \WC_Email {

	/**
	 * Detect if this is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		// IF this is not explicitly disabled, always true.
		$disabled = 'no' === $this->enabled;
		return apply_filters( 'woocommerce_email_enabled_' . $this->id, ! $disabled, $this->object );
	}

	/**
	 * Mail Constructor
	 */
	public function __construct() {
		$this->id             = 'hametwoo_cancel_notification';
		$this->customer_email = true;
		$this->title          = __( 'Order Cancel Notification', 'hametwoo' );
		$this->description    = __( 'Send notification email to customer if the order is automatically cancelled.', 'hametwoo' );
		$this->heading        = __( 'Order Cancelled', 'hametwoo' );
		$this->subject        = __( 'Your order #{order_number} has been cancelled at {order_date}', 'hametwoo' );
		$this->template_html  = 'emails/customer-cancel-notification.php';
		$this->template_plain = 'emails/plain/customer-cancel-notification.php';
		$this->template_base  = HametWoo::root_dir() . '/templates/';
		add_action( 'woocommerce_order_status_pending_to_cancelled_notification', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_on-hold_to_cancelled_notification', array( $this, 'trigger' ) );
		add_action( 'woocommerce_order_status_processing_to_cancelled_notification', array( $this, 'trigger' ) );
		// Call constructor.
		parent::__construct();
		// Add resend action.
		add_filter( 'woocommerce_resend_order_emails_available', [ $this, 'add_to_action' ] );
	}

	/**
	 * Filter callback.
	 *
	 * @param array $mailer Allowed mailer for actions.
	 *
	 * @return array
	 */
	public function add_to_action( $mailer ) {
		$mailer[] = $this->id;
		return $mailer;
	}

	/**
	 * Get email
	 *
	 * @param int $order_id Order ID.
	 */
	public function trigger( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || ! $this->is_enabled() ) {
			return;
		}
		$reason = $order->get_meta( '_hametwoo_cancel_reason' );
		if ( ! $reason ) {
			// Don't send with no reason.
			return;
		}
		$this->object    = $order;
		$this->recipient = $this->object->billing_email;
		// Is html?
		foreach ( [
			'order_number' => $order->get_order_number(),
			'order_date'   => date_i18n( get_option( 'date_format' ) ),
		] as $find => $result ) {
			$this->find[ $find ]    = "{{$find}}";
			$this->replace[ $find ] = $result;
		}
		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * Get content html.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_html() {
		return wc_get_template_html( $this->template_html, [
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => false,
			'email'         => $this,
		] );
	}

	/**
	 * Get content plain.
	 *
	 * @access public
	 * @return string
	 */
	public function get_content_plain() {
		return wc_get_template_html( $this->template_plain, [
			'order'         => $this->object,
			'email_heading' => $this->get_heading(),
			'sent_to_admin' => false,
			'plain_text'    => true,
			'email'         => $this,
		] );
	}
}
