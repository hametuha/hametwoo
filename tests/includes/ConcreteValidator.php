<?php


class ConcreteValidator extends \Hametuha\HametWoo\Pattern\Validator {

	/**
	 * Test
	 *
	 * @return WP_Error
	 */
	public function test_version() {
		return new WP_Error( '500', 'Always Failed.' );
	}

}
