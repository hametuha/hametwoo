<?php

namespace Hametuha\HametWoo\Pattern;

/**
 * Tab content for Account page.
 *
 * @package hametwoo
 */
abstract class Tab extends Singleton {

	/**
	 * Endpoint string.
	 *
	 * Should url ready string.
	 *
	 * @var string
	 */
	protected $endpoint = '';

	/**
	 * Constructor
	 */
	protected function __construct() {
		// Actions used to insert a new endpoint in the WordPress.
		add_action( 'init', [ $this, 'add_endpoints' ] );
		add_filter( 'query_vars', [ $this, 'add_query_vars' ], 0 );
		// Change title.
		add_filter( 'the_title', [ $this, 'endpoint_title' ] );
		// Inserting your new tab/page into the My Account page.
		add_filter( 'woocommerce_account_menu_items', [ $this, 'new_menu_items' ] );
		add_action( 'woocommerce_account_' . $this->endpoint . '_endpoint', [ $this, 'endpoint_content' ] );
		$this->init();
	}

	/**
	 * Executed on constructor
	 *
	 * @return void
	 */
	protected function init() {
		// Do nothing.
	}

	/**
	 * Change title for tab
	 *
	 * @since 0.8.0
	 * @package hametwoo
	 * @param string $title Title for this setting page.
	 *
	 * @return string
	 */
	public function endpoint_title( $title ) {
		global $wp_query;
		if ( isset( $wp_query->query_vars[ $this->endpoint ] ) && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
			/**
			 * hametwoo_tab_title
			 *
			 * Filter for Tab title.
			 *
			 * @package hametwoo
			 * @since   0.8.0
			 * @param string $title      Original Title
			 * @param string $endpoint   Endpoint name.
			 * @param string $class_name The instance which fires this filter.
			 *
			 * @return string
			 */
			$title = apply_filters( 'hametwoo_tab_title', $this->get_title(), $this->endpoint, get_called_class() );
		}

		return $title;
	}

	/**
	 * Return page title.
	 *
	 * @return string
	 */
	abstract protected function get_title();

	/**
	 * Register new endpoint to my account.
	 */
	public function add_endpoints() {
		add_rewrite_endpoint( $this->endpoint, EP_ROOT | EP_PAGES );
	}

	/**
	 * Add endpoint to menu
	 *
	 * @param array $items Menu to add.
	 *
	 * @return array
	 */
	abstract public function new_menu_items( array $items );

	/**
	 * Endpoint HTML content
	 */
	abstract public function endpoint_content();

	/**
	 * Add new query var
	 *
	 * @param array $vars Add query var.
	 *
	 * @return array
	 */
	public function add_query_vars( $vars ) {
		$vars[] = $this->endpoint;

		return $vars;
	}

}
