<?php

class PatternTest extends WP_UnitTestCase {


	/**
	 * Test singleton pattern.
	 */
	function test_singleton() {
		$first_instance  = ConcreteSingleton::get_instance();
		$second_instance = ConcreteSingleton::get_instance();
		$this->assertEquals( $first_instance, $second_instance );
		$this->assertInstanceOf( 'ConcreteSingleton', $first_instance );
	}

	/**
	 * Test validator.
	 */
	function test_validator() {
		// Validator itself does nothing.
		$this->assertTrue( \Hametuha\HametWoo\Pattern\Validator::validate() );
		// Concrete validator always failed.
		$this->assertWPError( ConcreteValidator::validate() );
	}
}
