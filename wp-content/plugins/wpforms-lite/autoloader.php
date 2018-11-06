<?php
/**
 * Autoloader, inspired by PSR4.
 *
 * @package WPForms
 * @author     WPForms
 * @since      1.4.7
 * @license    GPL-2.0+
 * @copyright  Copyright (c) 2018, WPForms LLC
 */

/**
 * Register the autoloader.
 *
 * @since 1.4.7
 *
 * @param string $class
 */
spl_autoload_register( function ( $class ) {

	// Our base namespace for all plugin classes.
	$prefix = 'WPForms\\';

	// Does the class use the namespace prefix?
	$len = strlen( $prefix );
	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		// No, move to the next registered autoloader.
		return;
	}

	// Base directory for the namespace prefix.
	$base_dir = __DIR__ . '/src/';

	// Get the relative class name.
	$relative_class = substr( $class, $len );

	// Replace the namespace prefix with the base directory.
	// Replace namespace separators with directory separators in the relative
	// class name. Append with .php.
	$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	// Finally, require the file.
	if ( file_exists( $file ) ) {
		require_once $file;
	}
} );
