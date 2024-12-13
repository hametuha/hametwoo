<?php

namespace Hametuha\HametWoo\Pattern;


use Hametuha\HametWoo\Utility\Tools;

/**
 * Singleton Class
 *
 * @package hametwoo
 */
abstract class Singleton {

	use Tools;

	/**
	 * Instance holder.
	 *
	 * @var array
	 */
	private static $instances = [];

	/**
	 * Singleton constructor.
	 */
	protected function __construct() {
		// Override if required.
	}

	/**
	 * Singleton accessor.
	 *
	 * @return static
	 */
	final public static function get_instance() {
		$class_name = get_called_class();
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}
		return self::$instances[ $class_name ];
	}
}
