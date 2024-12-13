<?php
/**
Plugin Name: Hametwoo
Plugin URI: https://github.com/hametuha/hametwoo
Description: A WooCommerce extension for Hametuha.
Author: Hametuha INC.
Version: 1.0.0
Author URI: https://hametuha.co.jp/
License: GPL3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: hametwoo
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die();

/**
 * Activate plugins.
 */
add_action( 'plugins_loaded', function() {
	require_once __DIR__ . '/vendor/autoload.php';
	// Initialize.
	Hametuha\HametWoo::init();
	// Activate custom email.
	Hametuha\HametWoo\Custom\Email::get_instance()->activate();
} );
