<?php
namespace Hametuha\HametWoo\Custom;


use Hametuha\HametWoo\Emails\CancelNotification;
use Hametuha\HametWoo\Pattern\Singleton;

/**
 * Email class
 *
 * @package hametwoo
 */
class Email extends Singleton {

	/**
	 * Activate Custom emails.
	 */
	public static function activate() {
		add_filter( 'woocommerce_email_classes', function( $email_classes ) {
			$email_classes['Hametuha_Hametwoo_Emails_CancelNotification'] = new CancelNotification();
			return $email_classes;
		} );
	}
}
