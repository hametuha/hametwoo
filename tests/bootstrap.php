<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Hametwoo
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	// Activate WooCommerce.
	$plugins_to_active = get_option( 'active_plugins', [] );
	$plugins_to_active[] = 'woocommerce/woocommerce.php';
	update_option( 'active_plugins', $plugins_to_active );
	// Auto Loader.
	$base = dirname( dirname( __FILE__ ) );
	require_once $base . '/vendor/autoload.php';
	// Test classes.
	$test = $base . '/tests/includes';
	foreach ( scandir( $test ) as $file ) {
		if ( preg_match( '#^[^.].*\.php$#', $file ) ) {
			require_once $test . '/' . $file;
		}
	}
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
