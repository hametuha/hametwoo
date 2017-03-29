<?php

namespace Hametuha\HametWoo\Utility;

/**
 * Compatibility detector of WooCommerce
 *
 * @package hametwoo
 * @since 0.8.0
 */
class Compatibility {

	/**
	 * Kill constructor.
	 */
	private function __construct() {
		// Do not call from outside!
	}

	/**
	 * Get WooCommerce version
	 *
	 * @return string
	 */
	public static function woo_version() {
		return self::has_woo() ? WC()->version : '0.0.0';
	}

	/**
	 * Check if WooCommerce class exists.
	 *
	 * @return bool
	 */
	public static function has_woo() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Check if subscription plugin enabled.
	 *
	 * @return bool
	 */
	public static function subscription_available() {
		return class_exists( 'WC_Subscriptions' );
	}

	/**
	 * Test version compatibility
	 *
	 * @param string $required_version Required WooCommerce Version.
	 * @param string $operator         Operator. Default, '>='.
	 *
	 * @return bool
	 */
	public static function satisfies( $required_version, $operator = '>=' ) {
		$current_version = self::woo_version();
		return (bool) version_compare( $current_version, $required_version, $operator );
	}

	/**
	 * Check dependency for active plugins.
	 *
	 * @param array $plugin_file_array Plugin file name.
	 *
	 * @return bool
	 */
	public static function check_dependency( $plugin_file_array ) {
		$plugins = (array) get_option( 'active_plugins' );
		foreach ( (array) $plugin_file_array as $file ) {
			$found = false;
			foreach ( $plugins as $plugin ) {
				if ( false !== strpos( $plugin, $file ) ) {
					$found = true;
					break;
				}
			}
			// This plugin is not found.
			// So, check failed.
			if ( ! $found ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get current currency.
	 *
	 * @return string
	 */
	public static function get_currency() {
		return (string) get_option( 'woocommerce_currency', '' );
	}

	/**
	 * Check current currency.
	 *
	 * @param string $currency Current currency setting.
	 * @return bool
	 */
	public static function check_currency( $currency ) {
		return self::get_currency() === $currency;
	}

}
