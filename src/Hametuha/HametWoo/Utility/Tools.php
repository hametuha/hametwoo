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

	/**
	 * Version of HametWoo.
	 *
	 * @var string
	 */
	protected $version = '0.8.1';

	/**
	 * Convert posted string to array.
	 *
	 * @param string $string MM / YY string.
	 *
	 * @return array
	 */
	public function convert_expiry( $string ) {
		return array_map( function ( $var ) {
			return trim( $var );
		}, explode( '/', $string ) );
	}

	/**
	 * Get card icon URL
	 *
	 * @return array
	 */
	public function card_icons() {
		$icons = [];
		$base_url = WC()->plugin_url().'/assets/images/icons/credit-cards/';
		$base_dir = WC()->plugin_path().'/assets/images/icons/credit-cards/';
		if ( is_dir( $base_dir ) ) {
			foreach ( scandir( $base_dir ) as $icon ) {
				if ( preg_match( '#(.*)\.svg$#', $icon, $matches ) ) {
					$icons[$matches[1]] = $base_url . $icon;
				}
			}
		}
		return $icons;
	}

	/**
	 * Get style sheet for card input.
	 *
	 * @param string $selector
	 *
	 * @return string
	 */
	public function card_css( $selector = '.wc-credit-card-form-card-number' ) {
		$css = <<<CSS
{$selector}{
    background-repeat: no-repeat;
    background-position: right .6180469716em center;
    background-size: 31px 20px;
}
CSS;
		foreach ( $this->card_icons() as $icon => $url ) {
			$css .= <<<CSS

{$selector}.{$icon} {
	background-image: url("{$url}");
}
CSS;
		}
		return sprintf( '<style type="text/css">%s</style>', $css );

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
