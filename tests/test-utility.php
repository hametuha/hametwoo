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

	/**
	 * Setup global variables.
	 */
	public function setUp():void {
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
		// Check quantize.
		$cc_no = '0000 0000 0000 0000';
		$this->assertEquals( $tool->quantize( $cc_no ), '0000000000000000' );
		// Check product works.
		$this->assertEquals( $tool->get_all_products_in( 10 ), [] );
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
		// Check if version is bigger than 2.6.0.
		$this->assertNotEquals( '0.0.0', Compatibility::woo_version() );
		$this->assertTrue( Compatibility::has_woo() );
		$this->assertFalse( Compatibility::subscription_available() );
		$this->assertTrue( Compatibility::satisfies( '7.0.0' ) );
		$this->assertEquals( 'USD', Compatibility::get_currency() );
		$this->assertTrue( Compatibility::check_currency( 'USD' ) );
		$this->assertTrue( Compatibility::check_dependency( [ 'woocommerce.php' ] ) );
		$this->assertFalse( Compatibility::check_dependency( [ 'not-existing/plugin.php' ] ) );
	}

	/**
	 * Check if unique id is unique.
	 */
	function test_unique_id() {
		$id = \Hametuha\HametWoo\Utility\UniqueId::generate( 18 );
		$another_id = \Hametuha\HametWoo\Utility\UniqueId::generate( 18 );
		$this->assertNotEquals( $id, $another_id );
		$this->assertEquals( 36, strlen( $id ) );
	}
}
