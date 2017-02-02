<?php

namespace Hametuha\HametWoo\Utility;


use Hametuha\HametWoo\Pattern\Singleton;

/**
 * Input handler utility.
 *
 * @since 1.0.0
 * @package hametwoo
 */
class Input extends Singleton {

	/**
	 * Returns $_GET.
	 *
	 * @param string $key Key name of $_REQUEST.
	 * @param null   $default Returned value if key is not set.
	 *
	 * @return null
	 */
	public function get( $key, $default = null ) {
		return isset( $_GET[ $key ] ) ? $_GET[ $key ] : $default;
	}

	/**
	 * Returns $_POST.
	 *
	 * @param string $key Key name of $_REQUEST.
	 * @param null   $default Returned value if key is not set.
	 *
	 * @return null
	 */
	public function post( $key, $default = null ) {
		return isset( $_POST[ $key ] ) ? $_POST[ $key ] : $default;
	}

	/**
	 * Returns $_REQUEST.
	 *
	 * @param string $key Key name of $_REQUEST.
	 * @param null   $default Returned value if key is not set.
	 *
	 * @return mixed
	 */
	public function request( $key, $default = null ) {
		return isset( $_REQUEST[ $key ] ) ? $_REQUEST[ $key ] : $default;
	}

	/**
	 * Get input utility
	 *
	 * @return string
	 */
	public function input() {
		return file_get_contents( 'php://input' );
	}

	/**
	 * Get JSON body
	 *
	 * @return string
	 */
	public function json() {
		return json_decode( $this->input() );
	}

	/**
	 * Check nonce
	 *
	 * @param string $action Action to verify.
	 * @param string $key    Default '_wpnonce'.
	 * @return false|int
	 */
	public function verify_nonce( $action, $key = '_wpnonce' ) {
		return wp_verify_nonce( $this->request( $key ), $action );
	}
}
