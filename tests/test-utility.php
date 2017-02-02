<?php
/**
 * Class SampleTest
 *
 * @package Hametwoo
 */

use Hametuha\HametWoo\Utility\Compatibility;

/**
 * Sample test case.
 */
class UtilityTest extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
		$_GET = [
			'foo' => 'var',
		];
		$_POST = [
			'foo' => 'var',
		];
		$_REQUEST = [
			'foo' => 'var',
			'_wpnonce' => wp_create_nonce( 'nonce' ),
		];
	}

	/**
	 * Test tool trait
	 */
	function test_tool() {
		$tool = new ConcreteTool();
		$this->assertInstanceOf( 'wpdb', $tool->db );
		$this->assertInstanceOf( '\\Hametuha\\HametWoo\\Utility\\Input', $tool->input );
		// Credit card expiry.
		$expiry_is = ' 07 / 18 ';
		$expiry_should = [ '07', '18' ];
		$this->assertEquals( $tool->convert_expiry( $expiry_is ), $expiry_should );
	}

	/**
	 * Check input utility.
	 */
	function test_input() {
		$input = \Hametuha\HametWoo\Utility\Input::get_instance();
		$this->assertEquals( 'var', $input->get( 'foo' ) );
		$this->assertNull( $input->get( 'no_var' ) );
		$this->assertEquals( 'var', $input->post( 'foo' ) );
		$this->assertNull( $input->post( 'no_var' ) );
		$this->assertEquals( 'var', $input->request( 'foo' ) );
		$this->assertNull( $input->request( 'no_var' ) );
		// Check JSON input with composer.json.
		$path = dirname( __DIR__ ) . '/composer.json';
		$input->stdin = $path;
		$composer = $input->input();
		$this->assertNotEmpty( $composer );
		$json = $input->json();
		$this->assertNotNull( $json );
		$this->assertEquals( 'hametuha/hametwoo', $json->name );
		$this->assertEquals( 1, $input->verify_nonce( 'nonce' ) );
		$this->assertFalse( $input->verify_nonce( 'no_nonce' ) );
	}

	/**
	 * Check compatibility
	 */
	function test_compatibility() {
		$this->assertEquals( '0.0.0', Compatibility::woo_version() );
		$this->assertFalse( Compatibility::has_woo() );
		$this->assertFalse( Compatibility::subscription_available() );
		$this->assertTrue( Compatibility::satisfies( '1.0.0' ) );
		$this->assertEmpty( Compatibility::get_currency() );
		$this->assertTrue( Compatibility::check_currency( '' ) );
	}
}
