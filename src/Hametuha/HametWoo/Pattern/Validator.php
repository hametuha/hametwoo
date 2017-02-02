<?php

namespace Hametuha\HametWoo\Pattern;

/**
 * Validate set of test.
 *
 * To validate a set of conditions, extends this classs
 * and implement public methods which starts with 'test_' prefix.
 *
 * ```
 * public function test_woo_version() {
 *     if ( Compatibility::satisfies( '2.6' ) ) {
 *         return true
 *     } else {
 *         return new \WP_Error( 'version_too_small', 'This plugin requires version 2.6 and over' );
 *     }
 * }
 * ```
 *
 * If every test succeeded, `SomeValidator::validate()` returns true. If else, WP_Error.
 *
 * @since 0.8.0
 * @package hametwoo
 */
class Validator extends Singleton {

	/**
	 * Validate all method
	 *
	 * @return bool|\WP_Error
	 */
	public static function validate() {
		$instance = static::get_instance();
		$error = new \WP_Error();
		foreach ( get_class_methods( $instance ) as $method ) {
			$repl = new \ReflectionMethod( get_called_class(), $method );
			// Check if method is public.
			if ( ! $repl->isPublic() ) {
				continue;
			}
			// Check if method start with 'test_'.
			if ( 0 !== strpos( $method, 'test_' ) ) {
				continue;
			}
			$result = call_user_func( [ $instance, $method ] );
			if ( is_wp_error( $result ) ) {
				$error->add( $result->get_error_code(), $result->get_error_message() );
			}
		}
		return $error->get_error_messages()? $error : true;
	}
}
