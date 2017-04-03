<?php
namespace Hametuha;

/**
 * Initializer
 *
 * @package hametwoo
 */
final class HametWoo {

	/**
	 * This instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * HametWoo constructor.
	 */
	private function __construct() {
		// Load translation.
		$language_dir = self::root_dir() . '/languages';
		load_plugin_textdomain( 'hametwoo', false, str_replace( ABSPATH . 'wp-content/plugins/', '', $language_dir ) );
		// Add template fallback.
		add_filter( 'woocommerce_locate_template', [ $this, 'template_fallback' ], 10, 3 );
	}

	/**
	 * Add hametwoo dir to template.
	 *
	 * @param string $template      File path.
	 * @param string $template_name Template name. e.g. email/cancel-order.php
	 * @param string $template_path Template base name. Default `woocommerce`.
	 *
	 * @return string
	 */
	public function template_fallback( $template, $template_name, $template_path ) {
		if ( ! file_exists( $template ) ) {
			// Woops, no template. Does hametwoo has it?
			$fallback = self::root_dir() . '/templates/' . $template_name;
			if ( file_exists( $fallback ) ) {
				$template = $fallback;
			}
		}
		return $template;
	}

	/**
	 * Get root directory
	 *
	 * @return string
	 */
	public static function root_dir() {
		return dirname( dirname( __DIR__ ) );
	}

	/**
	 * Send email short hand.
	 *
	 * @param string $mail_id   Mail ID.
	 * @param array  $arguments Argument passed to $mailer->trigger.
	 */
	public function send_mail( $mail_id, $arguments = [] ) {
		$mailers = WC()->mailer()->get_emails();
		foreach ( $mailers as $mailer ) {
			/* @var \WC_Email $mailer */
			if ( $mail_id == $mailer->id ) {
				if ( $arguments ) {
					call_user_func_array( [ $mailer, 'trigger' ], $arguments );
				} else {
					$mailer->trigger();
				}
			}
		}
	}


	/**
	 * Initializer
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
	}
}
