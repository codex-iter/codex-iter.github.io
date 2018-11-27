<?php
/**
 * Plugin compatibility file.
 *
 * Jetpack
 *
 * @link  https://wordpress.org/plugins/jetpack/
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 *
 * Contents:
 *
 *  1) Requirements check
 * 10) Plugin integration
 */





/**
 * 1) Requirements check
 */

	if ( ! class_exists( 'Jetpack' ) ) {
		return;
	}





/**
 * 20) Plugin integration
 */

	define( 'MODERN_PATH_PLUGINS_JETPACK', MODERN_PATH_PLUGINS . 'jetpack/class-jetpack-' );

	require MODERN_PATH_PLUGINS_JETPACK . 'setup.php';
	require MODERN_PATH_PLUGINS_JETPACK . 'custom-post-types.php';
	require MODERN_PATH_PLUGINS_JETPACK . 'content-options.php';
	require MODERN_PATH_PLUGINS_JETPACK . 'infinite-scroll.php';
	require MODERN_PATH_PLUGINS_JETPACK . 'customize.php';
