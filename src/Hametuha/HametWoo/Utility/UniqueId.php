<?php

namespace Hametuha\HametWoo\Utility;

/**
 * Unique ID class
 *
 * @since 0.8.6
 * @package hametwoo
 */
class UniqueId {

	/**
	 * Avoid creating instance
	 *
	 * UniqueId constructor.
	 */
	private function __construct() {}

	/**
	 * Generate unique ID
	 *
	 * @param int $length Length of id. Default 16.
	 *
	 * @return string
	 */
	public static function generate( $length = 16 ) {
		if ( function_exists( 'random_bytes' ) ) {
			return bin2hex( random_bytes( $length ) );
		} elseif ( function_exists( 'openssl_random_pseudo_bytes' ) ) {
			return bin2hex( openssl_random_pseudo_bytes( $length ) );
		} else {
			$id      = uniqid();
			$hash    = '';
			$str_len = strlen( $id );
			$limit   = floor( $length * 2 / $str_len );
			for ( $i = 0; $i < $limit; $i++ ) {
				$hash .= $id;
			}
			$remainder = strlen( $id ) / ( $length * 2 );
			if ( $remainder ) {
				$hash .= substr( $id, 0, $remainder );
			}
			return $hash;
		}
	}
}
