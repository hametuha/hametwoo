<?php

namespace Hametuha\HametWoo\Utility;


/**
 * Tool trait
 *
 * @package hametwoo
 * @property-read \wpdb  $db
 * @property-read array  $option
 * @property-read Input  $input
 */
trait Tools {

	protected $version = '0.8.0';

	/**
	 * Convert posted string to array.
	 *
	 * @param string $string MM / YY string.
	 *
	 * @return array
	 */
	protected function convert_expiry( $string ) {
		return array_map( function ( $var ) {
			return trim( $var );
		}, explode( '/', $string ) );
	}

	/**
	 * Getter
	 *
	 * @param string $name Key name.
	 *
	 * @return null
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'db':
				global $wpdb;
				return $wpdb;
				break;
			case 'input':
				return Input::get_instance();
				break;
			default:
				return null;
				break;
		}
	}

}
