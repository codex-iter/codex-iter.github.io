<?php
/**
 * Plugin Name: WPForms Lite
 * Plugin URI:  https://wpforms.com
 * Description: Beginner friendly WordPress contact form plugin. Use our Drag & Drop form builder to create your WordPress forms.
 * Author:      WPForms
 * Author URI:  https://wpforms.com
 * Version:     1.4.9
 * Text Domain: wpforms
 * Domain Path: languages
 *
 * WPForms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WPForms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WPForms. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.0.0
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2016, WPForms LLC
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't allow multiple versions to be active.
if ( class_exists( 'WPForms' ) ) {

	/**
	 * Deactivate if WPForms already activated.
	 *
	 * @since 1.0.0
	 */
	function wpforms_deactivate() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	add_action( 'admin_init', 'wpforms_deactivate' );

	/**
	 * Display notice after deactivation.
	 *
	 * @since 1.0.0
	 */
	function wpforms_lite_notice() {

		echo '<div class="notice notice-warning"><p>' . esc_html__( 'Please deactivate WPForms Lite before activating WPForms.', 'wpforms' ) . '</p></div>';

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
	add_action( 'admin_notices', 'wpforms_lite_notice' );

} else {

	/**
	 * Main WPForms class.
	 *
	 * @since 1.0.0
	 *
	 * @package WPForms
	 */
	final class WPForms {

		/**
		 * One is the loneliest number that you'll ever do.
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms
		 */
		private static $instance;

		/**
		 * Plugin version for enqueueing, etc.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		public $version = '1.4.9';

		/**
		 * The form data handler instance.
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_Form_Handler
		 */
		public $form;

		/**
		 * The entry data handler instance (Pro).
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_Entry_Handler
		 */
		public $entry;

		/**
		 * The entry fields data handler instance (Pro).
		 *
		 * @since 1.4.3
		 *
		 * @var WPForms_Entry_Fields_Handler
		 */
		public $entry_fields;

		/**
		 * The entry meta data handler instance (Pro).
		 *
		 * @since 1.1.6
		 *
		 * @var WPForms_Entry_Meta_Handler
		 */
		public $entry_meta;

		/**
		 * The front-end instance.
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_Frontend
		 */
		public $frontend;

		/**
		 * The process instance.
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_Process
		 */
		public $process;

		/**
		 * The smart tags instance.
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_Smart_Tags
		 */
		public $smart_tags;

		/**
		 * The Logging instance.
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_Logging
		 */
		public $logs;

		/**
		 * The Preview instance.
		 *
		 * @since 1.1.9
		 *
		 * @var WPForms_Preview
		 */
		public $preview;

		/**
		 * The License class instance (Pro).
		 *
		 * @since 1.0.0
		 *
		 * @var WPForms_License
		 */
		public $license;

		/**
		 * Paid returns true, free (Lite) returns false.
		 *
		 * @since 1.3.9
		 *
		 * @var boolean
		 */
		public $pro = false;

		/**
		 * Main WPForms Instance.
		 *
		 * Insures that only one instance of WPForms exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 *
		 * @return WPForms
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WPForms ) ) {

				self::$instance = new WPForms();
				self::$instance->constants();
				self::$instance->conditional_logic_addon_check();
				self::$instance->includes();

				// Load Pro or Lite specific files.
				if ( self::$instance->pro ) {
					require_once WPFORMS_PLUGIN_DIR . 'pro/wpforms-pro.php';
				} else {
					require_once WPFORMS_PLUGIN_DIR . 'lite/wpforms-lite.php';
				}

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ), 10 );
				add_action( 'plugins_loaded', array( self::$instance, 'objects' ), 10 );
			}

			return self::$instance;
		}

		/**
		 * Setup plugin constants.
		 *
		 * @since 1.0.0
		 */
		private function constants() {

			// Plugin version.
			if ( ! defined( 'WPFORMS_VERSION' ) ) {
				define( 'WPFORMS_VERSION', $this->version );
			}

			// Plugin Folder Path.
			if ( ! defined( 'WPFORMS_PLUGIN_DIR' ) ) {
				define( 'WPFORMS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'WPFORMS_PLUGIN_URL' ) ) {
				define( 'WPFORMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'WPFORMS_PLUGIN_FILE' ) ) {
				define( 'WPFORMS_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Slug - Determine plugin type and set slug accordingly.
			if ( apply_filters( 'wpforms_allow_pro_version', file_exists( WPFORMS_PLUGIN_DIR . 'pro/wpforms-pro.php' ) ) ) {
				$this->pro = true;
				define( 'WPFORMS_PLUGIN_SLUG', 'wpforms' );
			} else {
				define( 'WPFORMS_PLUGIN_SLUG', 'wpforms-lite' );
			}
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @since 1.0.0
		 */
		public function load_textdomain() {

			load_plugin_textdomain( 'wpforms', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Check to see if the conditional logic addon is running, if so then
		 * deactivate the plugin to prevent conflicts.
		 *
		 * @since 1.3.8
		 */
		private function conditional_logic_addon_check() {

			if ( function_exists( 'wpforms_conditional_logic' ) ) {

				// Load core files needed to activate deactivate_plugins().
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
				require_once ABSPATH . 'wp-includes/pluggable.php';

				// Deactivate Conditional Logic addon.
				deactivate_plugins( 'wpforms-conditional-logic/wpforms-conditional-logic.php' );

				// To avoid namespace collisions, reload current page.
				$url = esc_url_raw( remove_query_arg( 'wpforms-test' ) );
				wp_redirect( $url );
				exit;
			}
		}

		/**
		 * Include files.
		 *
		 * @since 1.0.0
		 */
		private function includes() {

			$this->includes_magic();

			// Global includes.
			require_once WPFORMS_PLUGIN_DIR . 'includes/functions.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-install.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-form.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-fields.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-frontend.php';
			// TODO: class-templates.php should be loaded in admin area only.
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-templates.php';
			// TODO: class-providers.php should be loaded in admin area only.
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-providers.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-process.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-smart-tags.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-logging.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-widget.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-preview.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/class-conditional-logic-core.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/emails/class-emails.php';
			require_once WPFORMS_PLUGIN_DIR . 'includes/integrations.php';

			// Admin/Dashboard only includes, also in ajax.
			if ( is_admin() ) {
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/admin.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-notices.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-menu.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/overview/class-overview.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/builder/class-builder.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/builder/functions.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-settings.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-welcome.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-tools.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-editor.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-review.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-importers.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/ajax-actions.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-am-notification.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-am-deactivation-survey.php';
				require_once WPFORMS_PLUGIN_DIR . 'includes/admin/class-am-dashboard-widget-extend-feed.php';
			}
		}

		/**
		 * Including the new files with PHP 5.3 style.
		 *
		 * @since 1.4.7
		 */
		private function includes_magic() {

			// We require PHP 5.3 for that to work.
			if ( version_compare( phpversion(), '5.3', '<' ) ) {
				return;
			}

			// Autoloader is put into its own file to save space here.
			require_once WPFORMS_PLUGIN_DIR . 'autoloader.php';

			/*
			 * Properly init the providers loader, that will handle all the related logic and further loading.
			 */
			add_action( 'wpforms_loaded', array( '\WPForms\Providers\Loader', 'get_instance' ) );

			/*
			 * Properly init the integrations loader, that will handle all the related logic and further loading.
			 */
			add_action( 'wpforms_loaded', array( '\WPForms\Integrations\Loader', 'get_instance' ) );
		}

		/**
		 * Setup objects.
		 *
		 * @since 1.0.0
		 */
		public function objects() {

			// Global objects.
			$this->form       = new WPForms_Form_Handler();
			$this->frontend   = new WPForms_Frontend();
			$this->process    = new WPForms_Process();
			$this->smart_tags = new WPForms_Smart_Tags();
			$this->logs       = new WPForms_Logging();
			$this->preview    = new WPForms_Preview();

			if ( is_admin() ) {
				if ( ! wpforms_setting( 'hide-announcements', false ) ) {
					new AM_Notification( WPFORMS_PLUGIN_SLUG, $this->version );
				}

				if ( $this->pro || ( ! $this->pro && ! file_exists( WP_PLUGIN_DIR . '/wpforms/wpforms.php' ) ) ) {
					new AM_Deactivation_Survey( 'WPForms', basename( dirname( __FILE__ ) ) );
				}
			}

			// Hook now that all of the WPForms stuff is loaded.
			do_action( 'wpforms_loaded' );
		}
	}

	/**
	 * The function which returns the one WPForms instance.
	 *
	 * @since 1.0.0
	 *
	 * @return WPForms
	 */
	function wpforms() {

		return WPForms::instance();
	}
	wpforms();
}
