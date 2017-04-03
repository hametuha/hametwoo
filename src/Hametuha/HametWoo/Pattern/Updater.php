<?php

namespace Hametuha\HametWoo\Pattern;

/**
 * Updater class
 *
 * @package hametwoo
 * @property-read string $cache_key
 */
abstract class Updater extends Singleton {

	/**
	 * Version number.
	 *
	 * @var string
	 */
	public $current_version = '';

	/**
	 * Cache life time in seconds.
	 *
	 * @var int
	 */
	protected $ttl = 30 * 60 * 60;

	/**
	 * Constructor
	 */
	protected function __construct() {
		// Register hooks.
		add_filter( 'plugins_api_args', [ $this, 'plugins_api_args' ], 10, 2 );
		add_filter( 'plugins_api', [ $this, 'plugins_api' ], 10, 3 );
		add_filter( 'plugins_api_result', [ $this, 'plugins_api_result' ], 10, 3 );
		add_filter( 'plugin_action_links', [ $this, 'plugin_action_links' ], 10, 4 );
		add_action( 'after_plugin_row', [ $this, 'after_plugin_row' ], 10, 3 );
		add_filter( 'wp_get_update_data', [ $this, 'wp_get_update_data' ], 10, 2 );
		add_action( 'core_upgrade_preamble', [ $this, 'core_upgrade_preamble' ] );
	}

	/**
	 * Initializer
	 *
	 * @param string $current_version Version of plugin.
	 */
	public static function init( $current_version ) {
		$instance = static::get_instance();
		$instance->current_version = $current_version;
	}

	/**
	 * Should return endpoint URL
	 *
	 * @return string URL to check version number.
	 */
	abstract protected function endpoint();

	/**
	 * Add timestamp for endpoint.
	 *
	 * @param string $url URL to get response.
	 * @return string
	 */
	protected function timestamped_endpoint( $url ) {
		// Make string for cache with GMT.
		// This is for caching thus you get
		// unique request only for 1 time in 10 minutes.
		$time = substr( date_i18n( 'YmdHi', false, true ), 0, -1 ) . '0';
		return add_query_arg( [
			'timestamp' => $time,
		], $url );
	}

	/**
	 * Parse result from remote server.
	 *
	 * @param string|\WP_Error $response Convert response.
	 * @return array|mixed|object
	 */
	protected function parse_result( $response ) {
		if ( is_wp_error( $response ) ) {
			return (object) [
				'success' => false,
				'version' => false,
				'error'   => $response->get_error_code(),
				'message' => $response->get_error_message(),
			];
		} else {
			$result = json_decode( $response );
			if ( is_null( $result ) ) {
				return (object) [
					'success' => false,
					'version' => false,
					'error'   => '500',
					'message' => 'Parser Error',
				];
			} else {
				return $result;
			}
		}
	}

	/**
	 * Get remote version file.
	 *
	 * @return object
	 */
	protected function remote_get_version() {
		$key = $this->cache_key;
		$version = get_site_transient( $key );
		if ( false !== $version ) {
			return $version;
		}
		$url = $this->timestamped_endpoint( $this->endpoint() );
		$response = wp_remote_get( $url );
		if ( is_wp_error( $response ) ) {
			$version = $this->parse_result( $response );
		} else {
			$version = $this->parse_result( $response['body'] );
		}
		set_site_transient( $key, $version, $this->ttl );
		return $version;
	}

	/**
	 * Detect if plugin has update.
	 *
	 * @return bool
	 */
	public function has_update() {
		$info = $this->remote_get_version();
		if ( ! isset( $info->version ) || ! $info->version ) {
			return false;
		} else {
			return version_compare( $this->version, $info->version, '<' );
		}
	}

	/**
	 * Filter for plugin api args.
	 *
	 * @param false|object|array $args   Plugins API arguments.
	 * @param string             $action The type of information.
	 * @return false|object|array
	 */
	public function plugins_api_args( $args, $action ) {
		return $args;
	}

	/**
	 * Plugin api response.
	 *
	 * @param false|object|array $response The result object or array. Default false.
	 * @param string             $action   The type of information being requested from the Plugin Install API.
	 * @param object             $args     Plugin API arguments.
	 * @return false|object|array
	 */
	public function plugins_api( $response, $action, $args ) {
		return $response;
	}

	/**
	 * Change Plugin Install API response
	 *
	 * @param object|\WP_Error $response Response object.
	 * @param string           $action   Information type.
	 * @param object           $args     Plugin API arguments.
	 * @return object|\WP_Error
	 */
	public function plugins_api_result( $response, $action, $args ) {
		return $response;
	}

	/**
	 * Update message.
	 *
	 * @param array $update_data Update result data.
	 * @param array $titles      Titles array.
	 * @return array
	 */
	public function wp_get_update_data( $update_data, $titles ) {
		if ( $this->has_update() ) {
			$update_data['counts']['plugins']++;
			$update_data['counts']['total']++;
			$titles['plugins'] = sprintf(
				/* translators: %d: Count of plugins to update */
				_n( '%d Plugin Update', '%d Plugin Updates', $update_data['counts']['plugins'], 'hametwoo' ),
				$update_data['counts']['plugins']
			);
			$update_data['title'] = esc_attr( implode( ', ', $titles ) );
		}
		return $update_data;
	}

	/**
	 * Action link for plugin table.
	 *
	 * @param array  $actions     Actions.
	 * @param string $plugin_file Main file name.
	 * @param array  $plugin_data File header array.
	 * @param string $context     'All', 'Active',
	 *                            'Inactive', 'Recently Activated', 'Upgrade',
	 *                            'Must-Use', 'Drop-ins', 'Search'.
	 * @return array
	 */
	public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
		return $actions;
	}

	/**
	 * Display row after plugin update.
	 *
	 * @param string $plugin_file Main plugin file name.
	 * @param array  $plugin_data File header.
	 * @param string $status      'All', 'Active',
	 *                            'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
	 *                            'Drop-ins', 'Search'.
	 */
	public function after_plugin_row( $plugin_file, $plugin_data, $status ) {
		// Do something to notify user.
		// <tr> tag with 3 columns.
	}

	/**
	 * Executed on 'Upgrade' screen.
	 */
	public function core_upgrade_preamble() {
		// Do something to dispaly data.
	}

	/**
	 * Getter
	 *
	 * @param string $name Variable name.
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'cache_key':
				return 'hametwoo_' . strtolower( str_replace( '\\', '_', get_called_class() ) );
				break;
			default:
				return parent::__get( $name );
				break;
		}
	}
}
