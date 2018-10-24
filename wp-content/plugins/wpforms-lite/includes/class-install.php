<?php
/**
 * Handles plugin installation upon activation.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
*/
class WPForms_Install {

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// When activated, trigger install method
		register_activation_hook( WPFORMS_PLUGIN_FILE, array( $this, 'install' ) );

		// Watch for new multisite blogs
		add_action( 'wpmu_new_blog', array( $this, 'new_multisite_blog' ), 10, 6 );
	}

	/**
	 * Let's get the party started.
	 *
	 * @since 1.0.0
	 * @param boolean $network_wide
	 */
	public function install( $network_wide = false ) {

		// Check if we are on multisite and network activating
		if ( is_multisite() && $network_wide ) {
			
			// Multisite - go through each subsite and run the installer
			if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
				
				// WP 4.6+
				$sites = get_sites();
				
				foreach ( $sites as $site ) {
					
					switch_to_blog( $site->blog_id );
					$this->run_install();
					restore_current_blog();
				}

			} else {

				$sites = wp_get_sites( array( 'limit' => 0 ) );
				
				foreach ( $sites as $site ) {
					
					switch_to_blog( $site['blog_id'] );
					$this->run_install();
					restore_current_blog();
				}
			}

		} else {

			// Normal single site
			$this->run_install();
		}


		// Abort so we only set the transient for single site installs
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Add transiet to trigger redirect to the Welcome screen
		set_transient( 'wpforms_activation_redirect', true, 30 );
	}

	/**
	 * Run the actual installer.
	 *
	 * @since 1.3.0
	 */
	function run_install() {

		$wpforms_install           = new stdClass();
		$wpforms_install->preview  = new WPForms_Preview;

		// Create form preview page
		$wpforms_install->preview->form_preview_check();

		// Hook for Pro users
		do_action( 'wpforms_install' );

		// Set current version, to be referenced in future updates
		update_option( 'wpforms_version', WPFORMS_VERSION );

		// Store the date when the initial activation was performed
		$type      = class_exists( 'WPForms_Lite' ) ? 'lite' : 'pro';
		$activated = get_option( 'wpforms_activated', array() );
		if ( empty( $activated[$type] ) ) {
			$activated[$type] = time();
			update_option( 'wpforms_activated', $activated );
		}
	}

	/**
	 * When a new site is created in multisite, see if we are network activated,
	 * and if so run the installer.
	 *
	 * @since 1.3.0
	 * @param int $blog_id
	 * @param int $user_id
	 * @param string $domain
	 * @param string $path
	 * @param int $site_id
	 * @param array $meta
	 */
	function new_multisite_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

		if ( is_plugin_active_for_network( plugin_basename( WPFORMS_PLUGIN_FILE ) ) ) {

			switch_to_blog( $blog_id );
			$this->run_install();
			restore_current_blog();

		}
	}
}
new WPForms_Install;