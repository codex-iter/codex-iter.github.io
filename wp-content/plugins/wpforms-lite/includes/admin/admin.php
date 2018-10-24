<?php
/**
 * Global admin related items and functionality.
 *
 * @package    WPForms
 * @author     WPForms
 * @since      1.3.9
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2017, WPForms LLC
 */

/**
 * Load styles for all WPForms-related admin screens.
 *
 * @since 1.3.9
 */
function wpforms_admin_styles() {

	if ( ! wpforms_is_admin_page() ) {
		return;
	}

	$min = wpforms_get_min_suffix();

	// jQuery confirm.
	wp_enqueue_style(
		'jquery-confirm',
		WPFORMS_PLUGIN_URL . 'assets/css/jquery-confirm.min.css',
		array(),
		'3.3.2'
	);

	// Minicolors (color picker).
	wp_enqueue_style(
		'minicolors',
		WPFORMS_PLUGIN_URL . 'assets/css/jquery.minicolors.css',
		array(),
		'2.2.6'
	);

	// FontAwesome.
	wp_enqueue_style(
		'wpforms-font-awesome',
		WPFORMS_PLUGIN_URL . 'assets/css/font-awesome.min.css',
		null,
		'4.7.0'
	);

	// Main admin styles.
	wp_enqueue_style(
		'wpforms-admin',
		WPFORMS_PLUGIN_URL . "assets/css/admin{$min}.css",
		array(),
		WPFORMS_VERSION
	);
}

add_action( 'admin_enqueue_scripts', 'wpforms_admin_styles' );

/**
 * Load scripts for all WPForms-related admin screens.
 *
 * @since 1.3.9
 */
function wpforms_admin_scripts() {

	if ( ! wpforms_is_admin_page() ) {
		return;
	}

	$min = wpforms_get_min_suffix();

	wp_enqueue_media();

	// jQuery confirm.
	wp_enqueue_script(
		'jquery-confirm',
		WPFORMS_PLUGIN_URL . 'assets/js/jquery.jquery-confirm.min.js',
		array( 'jquery' ),
		'3.3.2',
		false
	);

	// Minicolors (color picker).
	wp_enqueue_script(
		'minicolors',
		WPFORMS_PLUGIN_URL . 'assets/js/jquery.minicolors.min.js',
		array( 'jquery' ),
		'2.2.6',
		false
	);

	// Choices.js.
	wp_enqueue_script(
		'choicesjs',
		WPFORMS_PLUGIN_URL . 'assets/js/choices.min.js',
		array(),
		'2.8.10',
		false
	);

	// jQuery Conditionals.
	wp_enqueue_script(
		'jquery-conditionals',
		WPFORMS_PLUGIN_URL . "assets/js/jquery.conditionals.min.js",
		array( 'jquery' ),
		'1.0.1',
		false
	);

	// Main admin script.
	wp_enqueue_script(
		'wpforms-admin',
		WPFORMS_PLUGIN_URL . "assets/js/admin{$min}.js",
		array( 'jquery' ),
		WPFORMS_VERSION,
		false
	);

	$strings = array(
		'addon_activate'                  => esc_html__( 'Activate', 'wpforms' ),
		'addon_active'                    => esc_html__( 'Active', 'wpforms' ),
		'addon_deactivate'                => esc_html__( 'Deactivate', 'wpforms' ),
		'addon_inactive'                  => esc_html__( 'Inactive', 'wpforms' ),
		'addon_install'                   => esc_html__( 'Install Addon', 'wpforms' ),
		'addon_error'                     => esc_html__( 'Could not install addon. Please download from wpforms.com and install manually.', 'wpforms' ),
		'addon_search'                    => esc_html__( 'Searching Addons', 'wpforms' ),
		'ajax_url'                        => admin_url( 'admin-ajax.php' ),
		'cancel'                          => esc_html__( 'Cancel', 'wpforms' ),
		'close'                           => esc_html__( 'Close', 'wpforms' ),
		'entry_delete_confirm'            => esc_html__( 'Are you sure you want to delete this entry?', 'wpforms' ),
		'entry_delete_all_confirm'        => esc_html__( 'Are you sure you want to delete ALL entries?', 'wpforms' ),
		'entry_empty_fields_hide'         => esc_html__( 'Hide Empty Fields', 'wpforms' ),
		'entry_empty_fields_show'         => esc_html__( 'Show Empty Fields', 'wpforms' ),
		'entry_field_columns'             => esc_html__( 'Entries Field Columns', 'wpforms' ),
		'entry_note_delete_confirm'       => esc_html__( 'Are you sure you want to delete this note?', 'wpforms' ),
		'entry_unstar'                    => esc_html__( 'Unstar entry', 'wpforms' ),
		'entry_star'                      => esc_html__( 'Star entry', 'wpforms' ),
		'entry_read'                      => esc_html__( 'Mark entry read', 'wpforms' ),
		'entry_unread'                    => esc_html__( 'Mark entry unread', 'wpforms' ),
		'fields_select'                   => esc_html__( 'Select fields', 'wpforms' ),
		'form_delete_confirm'             => esc_html__( 'Are you sure you want to delete this form?', 'wpforms' ),
		'form_duplicate_confirm'          => esc_html__( 'Are you sure you want to duplicate this form?', 'wpforms' ),
		'heads_up'                        => esc_html__( 'Heads up!', 'wpforms' ),
		'importer_forms_required'         => esc_html__( 'Please select at least one form to import.', 'wpforms' ),
		'isPro'                           => wpforms()->pro,
		'nonce'                           => wp_create_nonce( 'wpforms-admin' ),
		'ok'                              => esc_html__( 'OK', 'wpforms' ),
		'plugin_install_activate_btn'     => esc_html__( 'Install and Activate', 'wpforms' ),
		'plugin_install_activate_confirm' => esc_html__( 'needs to be installed and activated to import its forms. Would you like us to install and activate it for you?', 'wpforms' ),
		'plugin_activate_btn'             => esc_html__( 'Activate', 'wpforms' ),
		'plugin_activate_confirm'         => esc_html__( 'needs to be activated to import its forms. Would you like us to activate it for you?', 'wpforms' ),
		'provider_delete_confirm'         => esc_html__( 'Are you sure you want to disconnect this account?', 'wpforms' ),
		'provider_auth_error'             => esc_html__( 'Could not authenticate with the provider.', 'wpforms' ),
		'save_refresh'                    => esc_html__( 'Save and Refresh', 'wpforms' ),
		'testing'                         => esc_html__( 'Testing', 'wpforms' ),
		'upgrade_completed'               => esc_html__( 'Upgrade was successfully completed!', 'wpforms' ),
		'upload_image_title'              => esc_html__( 'Upload or Choose Your Image', 'wpforms' ),
		'upload_image_button'             => esc_html__( 'Use Image', 'wpforms' ),
		'upgrade_modal'                   => wpforms_get_upgrade_modal_text(),
	);
	$strings = apply_filters( 'wpforms_admin_strings', $strings );

	wp_localize_script(
		'wpforms-admin',
		'wpforms_admin',
		$strings
	);
}

add_action( 'admin_enqueue_scripts', 'wpforms_admin_scripts' );

/**
 * Add body class to WPForms admin pages for easy reference.
 *
 * @since 1.3.9
 *
 * @param string $classes
 *
 * @return string
 */
function wpforms_admin_body_class( $classes ) {

	if ( ! wpforms_is_admin_page() ) {
		return $classes;
	}

	return "$classes wpforms-admin-page";
}

add_filter( 'admin_body_class', 'wpforms_admin_body_class', 10, 1 );

/**
 * Outputs the WPForms admin header.
 *
 * @since 1.3.9
 */
function wpforms_admin_header() {

	// Bail if we're not on a WPForms screen or page (also exclude form builder).
	if ( ! wpforms_is_admin_page() ) {
		return;
	}

	// Omit header from Welcome activation screen.
	if ( 'wpforms-getting-started' === $_REQUEST['page'] ) {
		return;
	}
	?>
	<div id="wpforms-header-temp"></div>
	<div id="wpforms-header" class="wpforms-header">
		<img class="wpforms-header-logo" src="<?php echo WPFORMS_PLUGIN_URL; ?>assets/images/logo.png" alt="WPForms Logo"/>
	</div>
	<?php
}

add_action( 'in_admin_header', 'wpforms_admin_header', 100 );

/**
 * Remove non-WPForms notices from WPForms pages.
 *
 * @since 1.3.9
 */
function wpforms_admin_hide_unrelated_notices() {

	// Bail if we're not on a WPForms screen or page.
	if ( empty( $_REQUEST['page'] ) || strpos( $_REQUEST['page'], 'wpforms' ) === false ) {
		return;
	}

	global $wp_filter;

	if ( ! empty( $wp_filter['user_admin_notices']->callbacks ) && is_array( $wp_filter['user_admin_notices']->callbacks ) ) {
		foreach ( $wp_filter['user_admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter['user_admin_notices']->callbacks[ $priority ][ $name ] );
					continue;
				}
				if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'wpforms' ) !== false ) {
					continue;
				}
				if ( ! empty( $name ) && strpos( $name, 'wpforms' ) === false ) {
					unset( $wp_filter['user_admin_notices']->callbacks[ $priority ][ $name ] );
				}
			}
		}
	}

	if ( ! empty( $wp_filter['admin_notices']->callbacks ) && is_array( $wp_filter['admin_notices']->callbacks ) ) {
		foreach ( $wp_filter['admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter['admin_notices']->callbacks[ $priority ][ $name ] );
					continue;
				}
				if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'wpforms' ) !== false ) {
					continue;
				}
				if ( ! empty( $name ) && strpos( $name, 'wpforms' ) === false ) {
					unset( $wp_filter['admin_notices']->callbacks[ $priority ][ $name ] );
				}
			}
		}
	}

	if ( ! empty( $wp_filter['all_admin_notices']->callbacks ) && is_array( $wp_filter['all_admin_notices']->callbacks ) ) {
		foreach ( $wp_filter['all_admin_notices']->callbacks as $priority => $hooks ) {
			foreach ( $hooks as $name => $arr ) {
				if ( is_object( $arr['function'] ) && $arr['function'] instanceof Closure ) {
					unset( $wp_filter['all_admin_notices']->callbacks[ $priority ][ $name ] );
					continue;
				}
				if ( ! empty( $arr['function'][0] ) && is_object( $arr['function'][0] ) && strpos( strtolower( get_class( $arr['function'][0] ) ), 'wpforms' ) !== false ) {
					continue;
				}
				if ( ! empty( $name ) && strpos( $name, 'wpforms' ) === false ) {
					unset( $wp_filter['all_admin_notices']->callbacks[ $priority ][ $name ] );
				}
			}
		}
	}
}

add_action( 'admin_print_scripts', 'wpforms_admin_hide_unrelated_notices' );

/**
 * Upgrade link used within the various admin pages.
 *
 * Previously was only included as a method in wpforms-lite.php, but made
 * available globally in 1.3.9.
 *
 * @since 1.3.9
 *
 * @param string $medium utm_medium URL parameter.
 *
 * @return string.
 */
function wpforms_admin_upgrade_link( $medium = 'link' ) {

	return apply_filters( 'wpforms_upgrade_link', 'https://wpforms.com/lite-upgrade/?discount=LITEUPGRADE&amp;utm_source=WordPress&amp;utm_medium=' . sanitize_key( apply_filters( 'wpforms_upgrade_link_medium', $medium ) ) . '&amp;utm_campaign=liteplugin' );
}

/**
 * Check the current PHP version and display a notice if on unsupported PHP.
 *
 * @since 1.4.0.1
 */
function wpforms_check_php_version() {

	// Display for PHP below 5.4.
	if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
		return;
	}

	// Display for admins only.
	if ( ! is_super_admin() ) {
		return;
	}

	// Display on Dashboard page only.
	if ( isset( $GLOBALS['pagenow'] ) && 'index.php' !== $GLOBALS['pagenow'] ) {
		return;
	}

	// Display the notice, finally.
	WPForms_Admin_Notice::error(
		'<p>' .
		sprintf(
			wp_kses(
				/* translators: %1$s - WPForms plugin name; %2$s - WPForms.com URL to a related doc. */
				__( 'Your site is running an outdated version of PHP that is no longer supported and may cause issues with %1$s. <a href="%2$s" target="_blank" rel="noopener noreferrer">Read more</a> for additional information.', 'wpforms' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			'<strong>WPForms</strong>',
			'https://wpforms.com/docs/supported-php-version/'
		) .
		'<br><br><em>' .
		wp_kses(
			__( '<strong>Please Note:</strong> After September 2018, if no further action is taken, WPForms functionality will be disabled.', 'wpforms' ),
			array(
				'strong' => array(),
				'em'     => array(),
			)
		) .
		'</em></p>'
	);
}
add_action( 'admin_init', 'wpforms_check_php_version' );

/**
 * Get an upgrade modal text.
 *
 * @since 1.4.4
 *
 * @return string
 */
function wpforms_get_upgrade_modal_text() {

	return
		'<p>' .
		esc_html__( 'Thanks for your interest in WPForms Pro!', 'wpforms' ) . '<br>' .
		sprintf(
			wp_kses(
				/* translators: %s - WPForms.com contact page URL. */
				__( 'If you have any questions or issues just <a href="%s" target="_blank" rel="noopener noreferrer">let us know</a>.', 'wpforms' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			'https://wpforms.com/contact/'
		) .
		'</p>' .
		'<p>' .
		wp_kses(
			__( 'After purchasing WPForms Pro, you\'ll need to <strong>download and install the Pro version of the plugin</strong>, and then <strong>remove the free plugin</strong>.', 'wpforms' ),
			array(
				'strong' => array(),
			)
		) . '<br>' .
		esc_html__( '(Don\'t worry, all your forms and settings will be preserved.)', 'wpforms' ) .
		'</p>' .
		'<p>' .
		sprintf(
			wp_kses(
				/* translators: %s - WPForms.com upgrade from Lite to paid docs page URL. */
				__( 'Check out <a href="%s" target="_blank" rel="noopener noreferrer">our documentation</a> for step-by-step instructions.', 'wpforms' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'rel'    => array(),
					),
				)
			),
			'https://wpforms.com/docs/upgrade-wpforms-lite-paid-license/?utm_source=WordPress&amp;utm_medium=link&amp;utm_campaign=liteplugin'
		) .
		'</p>';
}
