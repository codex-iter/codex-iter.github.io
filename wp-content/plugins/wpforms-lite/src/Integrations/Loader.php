<?php

namespace WPForms\Integrations;

/**
 * Class Loader gives ability to track/load all integrations.
 *
 * @package    WPForms\Integrations
 * @author     WPForms
 * @since      1.4.8
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, WPForms LLC
 */
class Loader {

	/**
	 * Get the instance of a class and store it in itself.
	 *
	 * @since 1.4.8
	 */
	public static function get_instance() {

		static $instance;

		if ( ! $instance ) {
			$instance = new Loader();
		}

		return $instance;
	}

	/**
	 * Loader constructor.
	 *
	 * @since 1.4.8
	 */
	public function __construct() {

		$core_integrations = array(
			new Gutenberg\FormSelector(),
			new WPMailSMTP\Notifications(),
		);

		$integrations = \apply_filters( 'wpforms_integrations_available', $core_integrations );

		foreach ( $integrations as $integration ) {
			$this->load_integration( $integration );
		}
	}

	/**
	 * Load an integration.
	 *
	 * @param IntegrationInterface $integration Instance of an integration class.
	 *
	 * @since 1.4.8
	 */
	protected function load_integration( IntegrationInterface $integration ) {
		if ( $integration->allow_load() ) {
			$integration->load();
		}
	}
}
